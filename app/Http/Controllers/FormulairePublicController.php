<?php

namespace App\Http\Controllers;

use App\Models\Formulaire;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Inertia\Inertia;

class FormulairePublicController extends Controller
{
    public function show($token)
    {
        $formulaire = Formulaire::with(['questions.options', 'questions.phrases'])
            ->where('token', $token)
            ->where('actif', true)
            ->firstOrFail();

        return Inertia::render('Formulaire/Show', [
            'formulaire' => $formulaire,
            'token' => $token
        ]);
    }

    public function submit(Request $request, $token)
    {
        $formulaire = Formulaire::with(['questions.options', 'questions.phrases'])
            ->where('token', $token)
            ->where('actif', true)
            ->firstOrFail();

        $validated = $request->validate([
            'reponses' => 'required|array',
            'reponses.*' => 'required'
        ]);

        $sessionToken = Str::random(32);

        foreach ($validated['reponses'] as $key => $reponse) {
            // Séparer l'ID de la question et l'ID de la phrase si présent
            $ids = explode('_', $key);
            $questionId = $ids[0];
            $phraseId = isset($ids[1]) ? $ids[1] : null;

            $question = $formulaire->questions->find($questionId);
            if ($question) {
                // Pour les questions à choix (radio, checkbox, select)
                if (in_array($question->type, ['radio', 'checkbox', 'select'])) {
                    // Si c'est un tableau de réponses (checkbox)
                    if (is_array($reponse)) {
                        foreach ($reponse as $optionTexte) {
                            $question->reponses()->create([
                                'contenu' => $optionTexte,
                                'session_token' => $sessionToken,
                                'phrase_id' => $phraseId
                            ]);
                        }
                    } else {
                        // Pour radio et select
                        $question->reponses()->create([
                            'contenu' => $reponse,
                            'session_token' => $sessionToken,
                            'phrase_id' => $phraseId
                        ]);
                    }
                } else {
                    // Pour les questions texte
                    $question->reponses()->create([
                        'contenu' => $reponse,
                        'session_token' => $sessionToken,
                        'phrase_id' => $phraseId
                    ]);
                }
            }
        }

        return redirect()->route('formulaire.merci');
    }

    public function merci()
    {
        return Inertia::render('Formulaire/Merci');
    }
}
