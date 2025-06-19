<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TextAnalysis extends Model
{
    // لتفعيل mass assignment للنموذج
protected $fillable = [
    'input_text',
    'translated_to_en',
    'translated_to_ar',
 
];

}
