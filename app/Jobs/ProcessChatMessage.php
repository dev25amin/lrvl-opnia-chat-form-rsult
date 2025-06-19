<?php
namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use App\Models\ChatMessage;

class ProcessChatMessage implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $chatMessage;

    public function __construct(ChatMessage $chatMessage)
    {
        $this->chatMessage = $chatMessage;
    }

    public function handle()
    {
        // تحديث الحالة إلى "معالجة"
        $this->chatMessage->update(['status' => 'processing']);

        $apiKey = env('OPENROUTER_API_KEY');
        if (!$apiKey) {
            Log::error('API Key for OpenRouter is missing.');
            $this->chatMessage->update(['status' => 'failed']);
            return;
        }

        try {
            // جلب سياق المحادثة السابقة
            $conversationHistory = $this->getConversationHistory();
            
            // إعداد الرسائل للـ API
            $messages = [
                [
                    'role' => 'system', 
                    'content' => 'أنت مساعد ذكي ومفيد. تجيب على الأسئلة باللغة العربية بطريقة واضحة ومفهومة. كن مهذباً ومساعداً دائماً.'
                ]
            ];

            // إضافة سياق المحادثة السابقة
            foreach ($conversationHistory as $msg) {
                $messages[] = ['role' => 'user', 'content' => $msg->user_message];
                if ($msg->ai_response) {
                    $messages[] = ['role' => 'assistant', 'content' => $msg->ai_response];
                }
            }

            // إضافة الرسالة الحالية
            $messages[] = ['role' => 'user', 'content' => $this->chatMessage->user_message];

            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $apiKey,
                'HTTP-Referer' => url('/'),
                'Content-Type' => 'application/json',
            ])->timeout(60)->post('https://openrouter.ai/api/v1/chat/completions', [
                'model' => 'openai/gpt-3.5-turbo',
                'messages' => $messages,
                'temperature' => 0.7,
                'max_tokens' => 1000,
            ]);

            if ($response->failed()) {
                Log::error('OpenRouter API request failed', [
                    'status' => $response->status(),
                    'response' => $response->body(),
                    'message_id' => $this->chatMessage->id
                ]);
                $this->chatMessage->update([
                    'ai_response' => 'عذراً، حدث خطأ في الاتصال بالخدمة. يرجى المحاولة مرة أخرى.',
                    'status' => 'failed'
                ]);
                return;
            }

            $result = $response->json();
            $aiResponse = $result['choices'][0]['message']['content'] ?? 'عذراً، لم أتمكن من الحصول على رد.';

            $this->chatMessage->update([
                'ai_response' => $aiResponse,
                'status' => 'completed'
            ]);

            Log::info('Chat message processed successfully', [
                'message_id' => $this->chatMessage->id,
                'session_id' => $this->chatMessage->session_id
            ]);

        } catch (\Exception $e) {
            Log::error('Error processing chat message', [
                'error' => $e->getMessage(),
                'message_id' => $this->chatMessage->id
            ]);

            $this->chatMessage->update([
                'ai_response' => 'عذراً، حدث خطأ تقني. يرجى المحاولة مرة أخرى.',
                'status' => 'failed'
            ]);
        }
    }

    private function getConversationHistory()
    {
        // جلب آخر 5 رسائل للحفاظ على السياق
        return ChatMessage::where('session_id', $this->chatMessage->session_id)
                         ->where('id', '<', $this->chatMessage->id)
                         ->where('status', 'completed')
                         ->orderBy('created_at', 'desc')
                         ->limit(5)
                         ->get()
                         ->reverse();
    }
}