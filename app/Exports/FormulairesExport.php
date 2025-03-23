<?php

namespace App\Exports;

use App\Models\Formulaire;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithCustomStartCell;
use Maatwebsite\Excel\Concerns\WithMapping;
use Illuminate\Support\Facades\DB;

class FormulairesExport implements FromCollection, WithHeadings, WithStyles, WithColumnWidths, WithCustomStartCell, WithMapping
{
    protected $formulaire;
    protected $questions;
    protected $headings;

    public function __construct(Formulaire $formulaire)
    {
        $this->formulaire = $formulaire;
        // Charger uniquement les questions de ce formulaire
        $this->questions = $formulaire->questions()
            ->where('formulaire_id', $formulaire->id)
            ->with(['reponses.phrase', 'options', 'phrases'])
            ->get();
        $this->headings = $this->generateHeadings();
    }

    protected function generateHeadings()
    {
        $headings = [];
        foreach ($this->questions as $question) {
            if ($question->type === 'evaluation') {
                // Pour les questions d'évaluation, créer une colonne pour chaque phrase
                foreach ($question->phrases as $phrase) {
                    $headings[] = [
                        'question_id' => $question->id,
                        'phrase_id' => $phrase->id,
                        'heading' => $phrase->texte,
                        'type' => 'evaluation'
                    ];
                }
            } else if ($question->type === 'checkbox') {
                // Pour les questions à choix multiples, une seule colonne
                $headings[] = [
                    'question_id' => $question->id,
                    'phrase_id' => null,
                    'heading' => $question->contenu,
                    'type' => 'checkbox'
                ];
            } else {
                // Pour les autres types de questions, une seule colonne
                $headings[] = [
                    'question_id' => $question->id,
                    'phrase_id' => null,
                    'heading' => $question->contenu,
                    'type' => $question->type
                ];
            }
        }
        return $headings;
    }

    public function collection()
    {
        $reponses = collect();

        // Récupérer toutes les sessions qui ont répondu à ce formulaire spécifique
        $sessions = DB::table('reponses')
            ->join('questions', 'reponses.question_id', '=', 'questions.id')
            ->where('questions.formulaire_id', $this->formulaire->id)
            ->select('reponses.session_token')
            ->distinct()
            ->pluck('session_token');

        // Si aucune réponse n'existe encore, créer une ligne vide
        if ($sessions->isEmpty()) {
            // Créer une ligne vide avec le bon nombre de colonnes
            $ligne = array_fill(0, count($this->headings), '');
            $reponses->push($ligne);
            return $reponses;
        }

        // Pour chaque session
        foreach ($sessions as $sessionToken) {
            $ligne = [];

            // Pour chaque colonne d'en-tête
            foreach ($this->headings as $heading) {
                $question = $this->questions->find($heading['question_id']);

                if (!$question) {
                    $ligne[] = '';
                    continue;
                }

                if ($heading['type'] === 'evaluation') {
                    // Pour les questions d'évaluation
                    $reponse = DB::table('reponses')
                        ->where('question_id', $question->id)
                        ->where('session_token', $sessionToken)
                        ->where('phrase_id', $heading['phrase_id'])
                        ->value('contenu');

                    $ligne[] = $reponse ?: '';
                } else if ($heading['type'] === 'checkbox') {
                    // Pour les questions à choix multiples
                    $reponses_cochees = DB::table('reponses')
                        ->where('question_id', $question->id)
                        ->where('session_token', $sessionToken)
                        ->whereNull('phrase_id')
                        ->orderBy('created_at')
                        ->select('contenu')
                        ->get()
                        ->map(function ($item) {
                            return trim($item->contenu);
                        })
                        ->filter()
                        ->all();

                    $ligne[] = !empty($reponses_cochees) ? implode("\n", $reponses_cochees) : '';
                } else {
                    // Pour les autres types de questions
                    $reponse = DB::table('reponses')
                        ->where('question_id', $question->id)
                        ->where('session_token', $sessionToken)
                        ->whereNull('phrase_id')
                        ->value('contenu');

                    $ligne[] = $reponse ?: '';
                }
            }

            if (!empty(array_filter($ligne))) {
                $reponses->push($ligne);
            }
        }

        return $reponses;
    }

    public function headings(): array
    {
        return collect($this->headings)->pluck('heading')->toArray();
    }

    public function map($row): array
    {
        return $row;
    }

    public function styles(Worksheet $sheet)
    {
        $lastColumn = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex(count($this->headings));
        $lastRow = $sheet->getHighestRow();

        // Appliquer le style à l'en-tête
        $sheet->getStyle("A1:{$lastColumn}1")->applyFromArray([
            'font' => [
                'bold' => true,
                'color' => ['rgb' => 'FFFFFF'],
            ],
            'fill' => [
                'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                'startColor' => ['rgb' => '4472C4'],
            ],
        ]);

        // Style pour toutes les cellules
        $sheet->getStyle("A1:{$lastColumn}{$lastRow}")->applyFromArray([
            'alignment' => [
                'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_TOP,
                'wrapText' => true,
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                ],
            ],
        ]);

        // Ajuster la hauteur de la première ligne pour les longs textes
        $sheet->getRowDimension(1)->setRowHeight(40);

        // Ajuster la hauteur des autres lignes pour les réponses multiples
        for ($i = 2; $i <= $lastRow; $i++) {
            $sheet->getRowDimension($i)->setRowHeight(100);
        }
    }

    public function columnWidths(): array
    {
        $widths = [];
        $lastColumn = count($this->headings);

        // Définir une largeur de 50 pour chaque colonne, en commençant par A
        for ($i = 0; $i < $lastColumn; $i++) {
            $columnLetter = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($i + 1);
            $widths[$columnLetter] = 50;
        }

        return $widths;
    }

    public function startCell(): string
    {
        return 'A1';
    }
}
