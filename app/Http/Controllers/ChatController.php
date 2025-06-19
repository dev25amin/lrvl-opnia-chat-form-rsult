<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ChatMessage;
use App\Jobs\ProcessChatMessage;
use Illuminate\Support\Str;

class ChatController extends Controller
{
    public function index(Request $request)
    {
        // إنشاء session_id جديد إذا لم يكن موجود
        $sessionId = $request->session()->get('chat_session_id');
        if (!$sessionId) {
            $sessionId = Str::uuid()->toString();
            $request->session()->put('chat_session_id', $sessionId);
        }

        // جلب الرسائل السابقة
        $messages = ChatMessage::getRecentMessages($sessionId, 50);

        return view('chat.index', compact('messages', 'sessionId'));
    }

    public function sendMessage(Request $request)
    {
        $request->validate([
            'message' => 'required|string|max:2000',
        ]);

        $sessionId = $request->session()->get('chat_session_id');
        if (!$sessionId) {
            $sessionId = Str::uuid()->toString();
            $request->session()->put('chat_session_id', $sessionId);
        }

        // حفظ رسالة المستخدم
        $chatMessage = ChatMessage::create([
            'session_id' => $sessionId,
            'user_message' => $request->message,
            'status' => 'pending'
        ]);

        // إرسال المهمة إلى الـ Queue
        ProcessChatMessage::dispatch($chatMessage);

        return response()->json([
            'success' => true,
            'message_id' => $chatMessage->id,
            'message' => 'تم إرسال الرسالة بنجاح'
        ]);
    }

    // API للتحقق من حالة الرسالة
    public function checkMessage($id)
    {
        $message = ChatMessage::findOrFail($id);
        return response()->json([
            'status' => $message->status,
            'data' => $message
        ]);
    }

    // API لجلب آخر الرسائل
    public function getMessages(Request $request)
    {
        $sessionId = $request->session()->get('chat_session_id');
        if (!$sessionId) {
            return response()->json(['messages' => []]);
        }

        $messages = ChatMessage::getRecentMessages($sessionId, 50);
        return response()->json(['messages' => $messages]);
    }

    // مسح المحادثة
    public function clearChat(Request $request)
    {
        $sessionId = $request->session()->get('chat_session_id');
        if ($sessionId) {
            ChatMessage::where('session_id', $sessionId)->delete();
        }
        
        // إنشاء جلسة جديدة
        $newSessionId = Str::uuid()->toString();
        $request->session()->put('chat_session_id', $newSessionId);

        return response()->json(['success' => true]);
    }
}