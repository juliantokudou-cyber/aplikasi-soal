<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    use HasFactory;

    protected $fillable = [
        'question_text',
        'media_type',
        'media_file',
        'option_type', // Tambahkan ini
        'option_a',
        'option_b',
        'option_c',
        'correct_answer'
    ];

}
