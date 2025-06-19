<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class TextRequest extends Model
{
    protected $fillable = [
        'original_text', 
        'translated_text', 
        'rewritten_text', // تم تغييره من rephrased_text
        'verbs', 
        'nouns',
        'status'
    ];

    protected $casts = [
        'verbs' => 'array',
        'nouns' => 'array',
    ];
}
