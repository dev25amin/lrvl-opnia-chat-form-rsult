<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

use App\Models\TextAnalysis;
use Stichoza\GoogleTranslate\GoogleTranslate;

class ProcessTextAnalysis implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $textAnalysisId;

    public function __construct($textAnalysisId)
    {
        $this->textAnalysisId = $textAnalysisId;
    }

    public function handle(): void
    {
        $analysis = TextAnalysis::find($this->textAnalysisId);
        if (!$analysis) return;

        $text = $analysis->input_text;

        $tr = new GoogleTranslate();
        
        // ترجمات
        $translatedToEn = $tr->setTarget('en')->translate($text);
        $translatedToAr = $tr->setTarget('ar')->translate($text);

        $analysis->update([
            'translated_to_en' => $translatedToEn,
            'translated_to_ar' => $translatedToAr,
        ]);
    }
}
