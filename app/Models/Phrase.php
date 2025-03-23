<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Phrase extends Model
{
    use HasFactory;

    protected $fillable = [
        'texte',
        'question_id'
    ];

    public function question()
    {
        return $this->belongsTo(Question::class);
    }
}
