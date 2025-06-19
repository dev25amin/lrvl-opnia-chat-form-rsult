<?php
namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use App\Models\TextRequest;

class ProcessTextRequest implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $textRequest;

    public function __construct(TextRequest $textRequest)
    {
        $this->textRequest = $textRequest;
    }

    public function handle()
    {
        // تحديث الحالة إلى "معالجة"
        $this->textRequest->update(['status' => 'processing']);

        $apiKey = env('OPENROUTER_API_KEY');
        if (!$apiKey) {
            Log::error('API Key for OpenRouter is missing.');
            $this->textRequest->update(['status' => 'failed']);
            return;
        }

        $prompt = "Please analyze the following text and provide the response in this exact JSON format:
{
    \"arabic_translation\": \"الترجمة العربية هنا\",
    \"rewritten_text\": \"النص المعاد صياغته بنفس المعنى\",
    \"nouns\": [\"اسم1\", \"اسم2\", \"اسم3\"],
    \"verbs\": [\"فعل1\", \"فعل2\", \"فعل3\"]
}

Text to analyze: " . $this->textRequest->original_text;

        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $apiKey,
                'HTTP-Referer' => url('/'),
                'Content-Type' => 'application/json',
            ])->timeout(60)->post('https://openrouter.ai/api/v1/chat/completions', [
                'model' => 'openai/gpt-3.5-turbo',
                'messages' => [
                    ['role' => 'user', 'content' => $prompt],
                ],
                'temperature' => 0.7,
            ]);

            if ($response->failed()) {
                Log::error('OpenRouter API request failed', [
                    'status' => $response->status(),
                    'response' => $response->body()
                ]);
                $this->textRequest->update(['status' => 'failed']);
                return;
            }

            $result = $response->json();
            $content = $result['choices'][0]['message']['content'] ?? 'No content received.';

            // محاولة تحليل JSON
            $parsedContent = $this->parseApiResponse($content);

            $this->textRequest->update([
                'translated_text' => $parsedContent['arabic_translation'] ?? $content,
                'rewritten_text' => $parsedContent['rewritten_text'] ?? 'غير متوفر',
                'nouns' => $parsedContent['nouns'] ?? [],
                'verbs' => $parsedContent['verbs'] ?? [],
                'status' => 'completed'
            ]);

            Log::info('Text processing completed successfully', [
                'text_request_id' => $this->textRequest->id
            ]);

        } catch (\Exception $e) {
            Log::error('Error calling OpenRouter API', [
                'error' => $e->getMessage(),
                'text_request_id' => $this->textRequest->id
            ]);

            $this->textRequest->update(['status' => 'failed']);
        }
    }

    private function parseApiResponse($content)
    {
        // محاولة استخراج JSON من الرد
        if (preg_match('/\{.*\}/s', $content, $matches)) {
            $jsonString = $matches[0];
            $decoded = json_decode($jsonString, true);
            if (json_last_error() === JSON_ERROR_NONE) {
                return $decoded;
            }
        }
        
        // إذا فشل تحليل JSON، استخدم النص كما هو
        return [];
    }
}