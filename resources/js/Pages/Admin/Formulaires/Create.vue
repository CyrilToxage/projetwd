<template>

    <Head title="Nouveau questionnaire" />
    <AppLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Nouveau questionnaire
            </h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
                    <form @submit.prevent="handleSubmit">
                        <div class="space-y-6">
                            <!-- Nom du formulaire -->
                            <div>
                                <label for="nom" class="block text-sm font-medium text-gray-700">Nom du
                                    questionnaire</label>
                                <input type="text" id="nom" v-model="form.nom"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                    required />
                            </div>

                            <!-- Questions -->
                            <div class="space-y-4">
                                <h3 class="text-lg font-medium text-gray-900">Questions</h3>

                                <div v-for="(question, index) in form.questions" :key="index"
                                    class="border rounded-md p-4 space-y-4">
                                    <div>
                                        <label :for="'question-' + index"
                                            class="block text-sm font-medium text-gray-700">Question</label>
                                        <textarea :id="'question-' + index" v-model="question.contenu" rows="2"
                                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                            required></textarea>
                                    </div>

                                    <div>
                                        <label :for="'type-' + index"
                                            class="block text-sm font-medium text-gray-700">Type de
                                            réponse</label>
                                        <select :id="'type-' + index" v-model="question.type"
                                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                            @change="handleTypeChange(question)" required>
                                            <option value="text">Texte</option>
                                            <option value="evaluation">Évaluation (5 niveaux)</option>
                                            <option value="radio">Choix unique</option>
                                            <option value="checkbox">Cases à cocher</option>
                                            <option value="select">Liste déroulante</option>
                                        </select>
                                    </div>

                                    <!-- Options de réponse pour radio, select et checkbox -->
                                    <div v-if="['radio', 'select', 'checkbox'].includes(question.type)"
                                        class="space-y-2">
                                        <label class="block text-sm font-medium text-gray-700">Options de
                                            réponse</label>
                                        <div v-for="(option, optionIndex) in question.options" :key="optionIndex"
                                            class="flex items-center space-x-2">
                                            <input type="text" v-model="option.texte"
                                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                                placeholder="Texte de l'option" required />
                                            <button type="button" @click="removeOption(question, optionIndex)"
                                                class="text-red-600 hover:text-red-900">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5"
                                                    viewBox="0 0 20 20" fill="currentColor">
                                                    <path fill-rule="evenodd"
                                                        d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                                                        clip-rule="evenodd" />
                                                </svg>
                                            </button>
                                        </div>
                                        <button type="button" @click="addOption(question)"
                                            class="inline-flex items-center px-3 py-1 border border-gray-300 rounded-md text-sm font-medium text-gray-700 hover:bg-gray-50">
                                            Ajouter une option
                                        </button>
                                    </div>

                                    <!-- Critères pour le type évaluation uniquement -->
                                    <div v-if="question.type === 'evaluation'" class="space-y-4">
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-2">Phrases à
                                                évaluer</label>
                                            <div v-for="(phrase, phraseIndex) in question.phrases" :key="phraseIndex"
                                                class="flex items-center space-x-2 mb-2">
                                                <input type="text" v-model="phrase.texte"
                                                    class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                                    placeholder="Ex: la qualité du support de cours" required />
                                                <button type="button" @click="removePhrase(question, phraseIndex)"
                                                    class="text-red-600 hover:text-red-900">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5"
                                                        viewBox="0 0 20 20" fill="currentColor">
                                                        <path fill-rule="evenodd"
                                                            d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                                                            clip-rule="evenodd" />
                                                    </svg>
                                                </button>
                                            </div>
                                            <button type="button" @click="addPhrase(question)"
                                                class="inline-flex items-center px-3 py-1 border border-gray-300 rounded-md text-sm font-medium text-gray-700 hover:bg-gray-50">
                                                Ajouter une phrase à évaluer
                                            </button>
                                        </div>

                                        <!-- Aperçu des options d'évaluation -->
                                        <div class="mt-2 bg-gray-50 p-4 rounded-lg">
                                            <p class="text-sm font-medium text-gray-700 mb-2">Options de réponse (non
                                                modifiables) :</p>
                                            <div class="grid grid-cols-5 gap-4 text-center">
                                                <div class="p-2 bg-white border rounded">Médiocre</div>
                                                <div class="p-2 bg-white border rounded">Insuffisant</div>
                                                <div class="p-2 bg-white border rounded">Sans avis</div>
                                                <div class="p-2 bg-white border rounded">Bon</div>
                                                <div class="p-2 bg-white border rounded">Excellent</div>
                                            </div>
                                        </div>
                                    </div>

                                    <button type="button" @click="removeQuestion(index)"
                                        class="inline-flex items-center px-3 py-1 border border-transparent text-sm leading-4 font-medium rounded-md text-red-600 hover:bg-red-100">
                                        Supprimer la question
                                    </button>
                                </div>

                                <button type="button" @click="addQuestion"
                                    class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:text-gray-500 focus:outline-none focus:border-blue-300 focus:ring focus:ring-blue-200 active:text-gray-800 active:bg-gray-50">
                                    Ajouter une question
                                </button>
                            </div>

                            <div class="flex justify-end space-x-3 mt-6">
                                <Link :href="route('admin.formulaires.index')"
                                    class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:text-gray-500 focus:outline-none focus:border-blue-300 focus:ring focus:ring-blue-200 active:text-gray-800 active:bg-gray-50">
                                Annuler
                                </Link>
                                <button type="submit"
                                    class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700"
                                    :disabled="form.processing">
                                    Créer le questionnaire
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </AppLayout>
</template>

<script setup>
import { ref } from 'vue'
import { Head, Link, useForm } from '@inertiajs/vue3'
import AppLayout from '@/Layouts/AppLayout.vue'

const props = defineProps({})

const form = useForm({
    nom: '',
    questions: []
})

const addQuestion = () => {
    form.questions.push({
        contenu: '',
        type: 'text',
        options: [],
        phrases: []
    })
}

const addOption = (question) => {
    if (!question.options) {
        question.options = []
    }
    question.options.push({
        texte: '',
        valeur: question.options.length + 1
    })
}

const removeOption = (question, optionIndex) => {
    question.options.splice(optionIndex, 1)
    // Mettre à jour les valeurs des options restantes
    question.options.forEach((option, index) => {
        option.valeur = index + 1
    })
}

const removeQuestion = (index) => {
    form.questions.splice(index, 1)
}

const addPhrase = (question) => {
    if (!question.phrases) {
        question.phrases = []
    }
    question.phrases.push({
        texte: ''
    })
}

const removePhrase = (question, phraseIndex) => {
    question.phrases.splice(phraseIndex, 1)
}

const handleTypeChange = (question) => {
    if (question.type === 'evaluation') {
        // Options d'évaluation fixes uniquement pour le type evaluation
        question.options = [
            { texte: 'Médiocre', valeur: 1 },
            { texte: 'Insuffisant', valeur: 2 },
            { texte: 'Sans avis', valeur: 3 },
            { texte: 'Bon', valeur: 4 },
            { texte: 'Excellent', valeur: 5 }
        ]
        // Initialiser les phrases si pas déjà fait
        if (!question.phrases) {
            question.phrases = [{ texte: '' }]
        }
    } else if (question.type === 'checkbox') {
        // Pour les cases à cocher, on garde les options vides pour permettre la personnalisation
        question.options = []
        question.phrases = []
    } else if (!['radio', 'select'].includes(question.type)) {
        // Pour les autres types (texte), on vide les options et phrases
        question.options = []
        question.phrases = []
    }
}

const handleSubmit = () => {
    // Vérifier que les questions à choix ont au moins une option
    const hasInvalidQuestions = form.questions.some(question =>
        ['radio', 'select'].includes(question.type) &&
        (!question.options || question.options.length === 0)
    )

    if (hasInvalidQuestions) {
        alert('Certaines questions à choix n\'ont pas d\'options. Veuillez ajouter au moins une option pour chaque question à choix.')
        return
    }

    form.post(route('admin.formulaires.store'), {
        onSuccess: () => {
            form.reset()
        }
    })
}
</script>
