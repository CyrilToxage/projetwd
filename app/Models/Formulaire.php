<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Formulaire extends Model
{
    protected $fillable = [
        'nom',
        'token',
        'actif'
    ];

    protected $casts = [
        'actif' => 'boolean'
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($formulaire) {
            $formulaire->token = Str::random(10);
            $formulaire->actif = true;
        });
    }

    public function questions(): HasMany
    {
        return $this->hasMany(Question::class);
    }

    public function clone(): self
    {
        $clone = $this->replicate();
        $clone->token = Str::random(10);
        $clone->save();

        foreach ($this->questions as $question) {
            $questionClone = $question->replicate();
            $questionClone->formulaire_id = $clone->id;
            $questionClone->save();
        }

        return $clone;
    }
}
