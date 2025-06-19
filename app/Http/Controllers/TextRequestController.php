<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TextRequest;
use App\Jobs\ProcessTextRequest;

class TextRequestController extends Controller
{
    public function create()
    {
        return view('form');
    }

    public function store(Request $request)
    {
        $request->validate([
            'original_text' => 'required|string|max:5000',
        ]);

        $text = TextRequest::create([
            'original_text' => $request->original_text,
            'status' => 'pending'
        ]);

        // دفع المهمة إلى الـ Queue
        ProcessTextRequest::dispatch($text);

        return redirect()->route('text.show', $text->id)
                        ->with('message', 'تم إرسال النص للمعالجة، يرجى الانتظار...');
    }

    public function show($id)
    {
        $text = TextRequest::findOrFail($id);
        return view('result', compact('text'));
    }

    // API endpoint للتحقق من حالة المعالجة
    public function checkStatus($id)
    {
        $text = TextRequest::findOrFail($id);
        return response()->json([
            'status' => $text->status,
            'data' => $text
        ]);
    }
}