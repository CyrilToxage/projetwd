<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Formulaire;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Inertia\Inertia;
use App\Exports\FormulairesExport;
use Maatwebsite\Excel\Facades\Excel;
use Carbon\Carbon;

class FormulaireController extends Controller
{
    public function index()
    {
        return Inertia::render('Admin/Formulaires/Index', [
            'formulaires' => Formulaire::with('questions')
                ->get()
                ->map(function ($formulaire) {
                    return [
                        'id' => $formulaire->id,
                        'nom' => $formulaire->nom,
                        'actif' => $formulaire->actif,
                        'token' => $formulaire->token,
                        'created_at' => $formulaire->created_at->format('Y-m-d H:i:s'),
                        'questions' => $formulaire->questions
                    ];
                })
        ]);
    }

    public function create()
    {
        return Inertia::render('Admin/Formulaires/Create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nom' => 'required|string|max:255',
            'questions' => 'required|array',
            'questions.*.contenu' => 'required|string',
            'questions.*.type' => 'required|string|in:text,radio,checkbox,evaluation,select',
            'questions.*.options' => 'array',
            'questions.*.options.*.texte' => 'required_if:questions.*.type,radio,checkbox,evaluation,select|string',
            'questions.*.phrases' => 'array',
            'questions.*.phrases.*.texte' => 'required_if:questions.*.type,evaluation,checkbox|string',
        ]);

        $formulaire = Formulaire::create([
            'nom' => $validated['nom']
        ]);

        foreach ($validated['questions'] as $questionData) {
            $question = $formulaire->questions()->create([
                'contenu' => $questionData['contenu'],
                'type' => $questionData['type'],
                'is_multiple_choice' => $questionData['type'] === 'checkbox'
            ]);

            if (isset($questionData['options'])) {
                foreach ($questionData['options'] as $optionData) {
                    $question->options()->create([
                        'texte' => $optionData['texte']
                    ]);
                }
            }

            if (isset($questionData['phrases']) && in_array($questionData['type'], ['evaluation', 'checkbox'])) {
                foreach ($questionData['phrases'] as $phraseData) {
                    $phrase = $question->phrases()->create([
                        'texte' => $phraseData['texte']
                    ]);
                }
            }
        }

        return redirect()->route('admin.formulaires.index');
    }

    public function edit(Formulaire $formulaire)
    {
        return Inertia::render('Admin/Formulaires/Edit', [
            'formulaire' => $formulaire->load(['questions.options', 'questions.phrases'])
        ]);
    }

    public function update(Request $request, Formulaire $formulaire)
    {
        $validated = $request->validate([
            'nom' => 'required|string|max:255',
            'questions' => 'required|array',
            'questions.*.contenu' => 'required|string',
            'questions.*.type' => 'required|string|in:text,radio,checkbox,evaluation,select',
            'questions.*.options' => 'array',
            'questions.*.options.*.texte' => 'required_if:questions.*.type,radio,checkbox,evaluation,select|string',
            'questions.*.phrases' => 'array',
            'questions.*.phrases.*.texte' => 'required_if:questions.*.type,evaluation,checkbox|string',
        ]);

        // Désactiver l'ancien formulaire
        $formulaire->update(['actif' => false]);

        // Créer une nouvelle version
        $newFormulaire = Formulaire::create([
            'nom' => $validated['nom']
        ]);

        foreach ($validated['questions'] as $questionData) {
            $question = $newFormulaire->questions()->create([
                'contenu' => $questionData['contenu'],
                'type' => $questionData['type'],
                'is_multiple_choice' => $questionData['type'] === 'checkbox'
            ]);

            if (isset($questionData['options'])) {
                foreach ($questionData['options'] as $optionData) {
                    $question->options()->create([
                        'texte' => $optionData['texte']
                    ]);
                }
            }

            if (isset($questionData['phrases']) && in_array($questionData['type'], ['evaluation', 'checkbox'])) {
                foreach ($questionData['phrases'] as $phraseData) {
                    $phrase = $question->phrases()->create([
                        'texte' => $phraseData['texte']
                    ]);
                }
            }
        }

        return redirect()->route('admin.formulaires.index');
    }

    public function generateLink(Formulaire $formulaire)
    {
        $url = route('formulaire.repondre', $formulaire->token);
        return response()->json(['url' => $url]);
    }

    public function destroy(Formulaire $formulaire)
    {
        // Supprimer d'abord les questions et leurs options
        foreach ($formulaire->questions as $question) {
            $question->options()->delete();
        }
        $formulaire->questions()->delete();

        // Supprimer le formulaire
        $formulaire->delete();

        return redirect()->route('admin.formulaires.index')
            ->with('message', 'Le questionnaire a été supprimé avec succès.');
    }

    public function export(Formulaire $formulaire)
    {
        $filename = Str::slug($formulaire->nom) . '_' . Carbon::now()->format('Y-m-d_His') . '.xlsx';
        return Excel::download(new FormulairesExport($formulaire), $filename);
    }
}
