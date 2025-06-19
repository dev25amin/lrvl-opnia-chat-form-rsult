<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class ChatMessage extends Model
{
    protected $fillable = [
        'session_id',
        'user_message',
        'ai_response',
        'status',
        'conversation_context'
    ];

    protected $casts = [
        'conversation_context' => 'array',
    ];

    // جلب آخر الرسائل في الجلسة
    public static function getRecentMessages($sessionId, $limit = 10)
    {
        return self::where('session_id', $sessionId)
                   ->where('status', 'completed')
                   ->orderBy('created_at', 'desc')
                   ->limit($limit)
                   ->get()
                   ->reverse()
                   ->values();
    }
}
