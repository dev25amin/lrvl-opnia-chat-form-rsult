<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TextAnalysis;
use App\Jobs\ProcessTextAnalysis;

class TextAnalysisController extends Controller
{
    public function create()
    {
        return view('analyze');
    }

    public function store(Request $request)
    {
        $request->validate([
            'text' => 'required|string',
        ]);

        $analysis = TextAnalysis::create([
            'input_text' => $request->text,
        ]);

        dispatch(new ProcessTextAnalysis($analysis->id));

        return redirect()->route('analysis.show', $analysis->id);
    }

    public function show($id)
    {
        $analysis = TextAnalysis::findOrFail($id);
        return view('result', compact('analysis'));
    }
}
