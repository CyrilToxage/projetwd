<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Question extends Model
{
    use HasFactory;

    protected $fillable = [
        'contenu',
        'type',
        'formulaire_id'
    ];

    public function formulaire(): BelongsTo
    {
        return $this->belongsTo(Formulaire::class);
    }

    public function reponses(): HasMany
    {
        return $this->hasMany(Reponse::class);
    }

    public function options()
    {
        return $this->hasMany(QuestionOption::class);
    }

    public function phrases()
    {
        return $this->hasMany(Phrase::class);
    }
}
