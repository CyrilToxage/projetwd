<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Formulaire;
use App\Models\Question;
use App\Models\QuestionOption;
use App\Models\Phrase;

class FormulaireSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Création du formulaire
        $formulaire = Formulaire::create([
            'nom' => 'Évaluation des unités de formation',
            'actif' => true
        ]);

        // Question 1 : Section
        $question1 = Question::create([
            'formulaire_id' => $formulaire->id,
            'contenu' => 'Sélectionnez votre section',
            'type' => 'radio'
        ]);

        $sectionsOptions = [
            'Bachelier en informatique de gestion',
            'Bachelier en comptabilité',
            'Bachelier en marketing',
            'BES Designer',
            'BES Developer'
        ];

        foreach ($sectionsOptions as $option) {
            QuestionOption::create([
                'question_id' => $question1->id,
                'texte' => $option
            ]);
        }

        // Question 2 : Unité de formation
        $question2 = Question::create([
            'formulaire_id' => $formulaire->id,
            'contenu' => "Sélectionnez l'unité de formation à évaluer",
            'type' => 'radio'
        ]);

        $formationOptions = [
            'Anglais en situation appliqué à l\'enseignement supérieur UE2',
            'CMS niveau 1',
            'Veille technologique',
            'Environnement et technologies web',
            'SGBD',
            'Initiation à la programmation',
            'Scripts serveurs',
            'Création d\'applications web statiques',
            'Scripts clients',
            'Approche design (E-Learning)',
            'Projet web dynamique',
            'Framework et POO côté serveur',
            'Activités professionnelles de formation'
        ];

        foreach ($formationOptions as $option) {
            QuestionOption::create([
                'question_id' => $question2->id,
                'texte' => $option
            ]);
        }

        // Question 3 : Nom du professeur
        Question::create([
            'formulaire_id' => $formulaire->id,
            'contenu' => 'Quel est le nom du professeur qui a dispensé ce cours?',
            'type' => 'text'
        ]);

        // Question 4 : Fréquence de présence
        $question4 = Question::create([
            'formulaire_id' => $formulaire->id,
            'contenu' => 'A quelle fréquence avez-vous été présent(e) à ce cours?',
            'type' => 'radio'
        ]);

        $frequenceOptions = ['jamais', '20%', '40%', '60%', '80%', '100%'];
        foreach ($frequenceOptions as $option) {
            QuestionOption::create([
                'question_id' => $question4->id,
                'texte' => $option
            ]);
        }

        // Question 5 : Évaluation générale
        $question5 = Question::create([
            'formulaire_id' => $formulaire->id,
            'contenu' => 'De manière générale, comment évalueriez-vous... (Merci de cocher une case par question)',
            'type' => 'evaluation',
            'is_multiple_choice' => false
        ]);

        $evaluationPhrases = [
            'la qualité du support de cours?',
            'la qualité des exercices proposés?',
            'l\'intérêt que vous avez porté au cours?',
            'vos possibilités de participation au cours (interaction)?',
            'le mode d\'évaluation du cours?',
            'les liens avec les autres cours de la formation?',
            'l\'équipement mis à votre disposition (laboratoires, travaux pratiques,...)?',
            'la partie pratique du cours (laboratoires, exercices pratiques)?',
            'Les relations entre la classe et le professeur?'
        ];

        foreach ($evaluationPhrases as $phrase) {
            $phraseModel = Phrase::create([
                'question_id' => $question5->id,
                'texte' => $phrase
            ]);

            $evaluationOptions = [
                'Médiocre',
                'Insuffisant',
                'Sans avis',
                'Bon',
                'Excellent'
            ];

            foreach ($evaluationOptions as $option) {
                QuestionOption::create([
                    'question_id' => $question5->id,
                    'phrase_id' => $phraseModel->id,
                    'texte' => $option
                ]);
            }
        }

        // Question 6 : Commentaire grille
        Question::create([
            'formulaire_id' => $formulaire->id,
            'contenu' => 'Ajoutez, si vous le souhaitez, un commentaire par rapport à la grille ci-dessus.',
            'type' => 'text'
        ]);

        // Question 7 : Difficulté du cours
        $question7 = Question::create([
            'formulaire_id' => $formulaire->id,
            'contenu' => 'Le cours vous a-t-il paru difficile?',
            'type' => 'radio'
        ]);

        $difficulteOptions = [
            'Pas du tout',
            'Plutôt non',
            'Sans avis',
            'Plutôt oui',
            'Tout à fait'
        ];

        foreach ($difficulteOptions as $option) {
            QuestionOption::create([
                'question_id' => $question7->id,
                'texte' => $option
            ]);
        }

        // Question 8 : Apport du cours
        Question::create([
            'formulaire_id' => $formulaire->id,
            'contenu' => 'Que vous a apporté le cours? Qu\'avez-vous appris?',
            'type' => 'text'
        ]);

        // Question 9 : Format des notes
        $question9 = Question::create([
            'formulaire_id' => $formulaire->id,
            'contenu' => 'Sous quel format avez-vous reçu les notes de cours?',
            'type' => 'checkbox',
            'is_multiple_choice' => true
        ]);

        $formatOptions = [
            'au format papier',
            'au format PDF (téléchargeables)',
            'prise de notes pendant l\'exposé du prof',
            'Autres'
        ];

        foreach ($formatOptions as $option) {
            QuestionOption::create([
                'question_id' => $question9->id,
                'texte' => $option
            ]);
        }

        // Question 10 : Faculté d'adaptation
        $question10 = Question::create([
            'formulaire_id' => $formulaire->id,
            'contenu' => 'Selon vous, l\'enseignement proposé développe-t-il votre faculté d\'adaptation à de nouvelles matières?',
            'type' => 'radio'
        ]);

        $adaptationOptions = [
            'Pas du tout',
            'Plutôt non',
            'Sans avis',
            'Plutôt oui',
            'Tout à fait'
        ];

        foreach ($adaptationOptions as $option) {
            QuestionOption::create([
                'question_id' => $question10->id,
                'texte' => $option
            ]);
        }

        // Question 11 : Connaissance des objectifs
        $question11 = Question::create([
            'formulaire_id' => $formulaire->id,
            'contenu' => 'Avez-vous eu connaissance des objectifs du cours?',
            'type' => 'checkbox',
            'is_multiple_choice' => true
        ]);

        $objectifsOptions = [
            'OUI, via la brochure de l\'école',
            'OUI, via un document remis en classe',
            'OUI, cela a été évoqué oralement en classe',
            'OUI, via l\'Intranet et/ou le site du professeur',
            'NON, car j\'étais absent(e) quand ils ont été transmis',
            'NON, ils n\'ont pas été transmis',
            'ça ne m\'intéresse pas',
            'Autre'
        ];

        foreach ($objectifsOptions as $option) {
            QuestionOption::create([
                'question_id' => $question11->id,
                'texte' => $option
            ]);
        }

        // Question 12 : Objectifs atteints
        $question12 = Question::create([
            'formulaire_id' => $formulaire->id,
            'contenu' => 'Les objectifs, tels qu\'énoncés, ont-ils été atteints?',
            'type' => 'radio'
        ]);

        $atteintOptions = ['oui', 'Non', 'Sans avis'];
        foreach ($atteintOptions as $option) {
            QuestionOption::create([
                'question_id' => $question12->id,
                'texte' => $option
            ]);
        }

        // Question 13 : Commentaire objectifs
        Question::create([
            'formulaire_id' => $formulaire->id,
            'contenu' => 'Commentez votre choix à la question précédente',
            'type' => 'text'
        ]);

        // Question 14 : Quantité de matière
        $question14 = Question::create([
            'formulaire_id' => $formulaire->id,
            'contenu' => 'Que pensez-vous de la quantité de matière vue pour ce cours dans le cadre du temps attribué?',
            'type' => 'radio'
        ]);

        $quantiteOptions = ['1', '2', '3'];
        foreach ($quantiteOptions as $option) {
            QuestionOption::create([
                'question_id' => $question14->id,
                'texte' => $option
            ]);
        }

        // Question 15 : Évaluation détaillée
        $question15 = Question::create([
            'formulaire_id' => $formulaire->id,
            'contenu' => 'Comment trouvez-vous...',
            'type' => 'evaluation',
            'is_multiple_choice' => false
        ]);

        $evaluationPhrases15 = [
            'l\'information reçue AVANT les tests ou l\'examen',
            'les explications sur la cotation de vos réponses aux tests?',
            'les explications sur la cotation de vos réponses à l\'examen?'
        ];

        foreach ($evaluationPhrases15 as $phrase) {
            $phraseModel = Phrase::create([
                'question_id' => $question15->id,
                'texte' => $phrase
            ]);

            $evaluationOptions15 = [
                'Insuffisant',
                'Faible',
                'Satisfaisant',
                'Bon',
                'Très bon'
            ];

            foreach ($evaluationOptions15 as $option) {
                QuestionOption::create([
                    'question_id' => $question15->id,
                    'phrase_id' => $phraseModel->id,
                    'texte' => $option
                ]);
            }
        }

        // Question 16 : Pertinence évaluation
        $question16 = Question::create([
            'formulaire_id' => $formulaire->id,
            'contenu' => 'Le mode d\'évaluation pour ce cours est-il pertinent?',
            'type' => 'radio'
        ]);

        $pertinenceOptions = [
            'Pas du tout',
            'Plutôt non',
            'Sans avis',
            'Plutôt oui',
            'Tout à fait'
        ];

        foreach ($pertinenceOptions as $option) {
            QuestionOption::create([
                'question_id' => $question16->id,
                'texte' => $option
            ]);
        }

        // Question 17 : Commentaire évaluation
        Question::create([
            'formulaire_id' => $formulaire->id,
            'contenu' => 'Commentez votre réponse à la question précédente',
            'type' => 'text'
        ]);

        // Question 18 : Solutions faiblesses
        $question18 = Question::create([
            'formulaire_id' => $formulaire->id,
            'contenu' => 'Vous propose-t-on des solutions pour remédier à vos éventuelles faiblesses?',
            'type' => 'radio'
        ]);

        $solutionsOptions = ['oui', 'non'];
        foreach ($solutionsOptions as $option) {
            QuestionOption::create([
                'question_id' => $question18->id,
                'texte' => $option
            ]);
        }

        // Question 19 : Plus apprécié
        Question::create([
            'formulaire_id' => $formulaire->id,
            'contenu' => 'Qu\'avez-vous apprécié le plus durant la formation?',
            'type' => 'text'
        ]);

        // Question 20 : Moins apprécié
        Question::create([
            'formulaire_id' => $formulaire->id,
            'contenu' => 'Qu\'avez-vous apprécié le moins durant la formation?',
            'type' => 'text'
        ]);

        // Question 21 : Remarques
        Question::create([
            'formulaire_id' => $formulaire->id,
            'contenu' => 'Avez-vous des remarques à formuler concernant ce cours?',
            'type' => 'text'
        ]);
    }
}
