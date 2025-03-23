<template>
    <div class="min-h-screen bg-gray-100">
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
                    <h1 class="text-2xl font-bold mb-6">{{ formulaire.nom }}</h1>

                    <form @submit.prevent="submit" class="space-y-6">
                        <div v-for="question in formulaire.questions" :key="question.id"
                            class="border rounded-lg p-4 bg-gray-50">
                            <div class="mb-4">
                                <label class="block text-lg font-medium text-gray-900 mb-2">
                                    {{ question.contenu }}
                                </label>

                                <!-- Question de type évaluation -->
                                <div v-if="question.type === 'evaluation'" class="space-y-6">
                                    <!-- En-tête avec les options -->
                                    <div class="grid grid-cols-5 gap-4 text-center border-b pb-2">
                                        <div v-for="option in question.options.slice(0, 5)" :key="option.id"
                                            class="text-sm font-medium text-gray-700">
                                            {{ option.texte }}
                                        </div>
                                    </div>

                                    <!-- Phrases à évaluer -->
                                    <div v-for="phrase in question.phrases" :key="phrase.id" class="space-y-2">
                                        <div class="flex">
                                            <p class="text-gray-900 flex-grow">{{ phrase.texte }}</p>
                                            <div class="grid grid-cols-5 gap-4 w-1/2">
                                                <div v-for="option in question.options.slice(0, 5)" :key="option.id"
                                                    class="flex justify-center">
                                                    <input type="radio"
                                                        :name="'question_' + question.id + '_phrase_' + phrase.id"
                                                        :value="option.texte"
                                                        v-model="form.reponses[question.id + '_' + phrase.id]"
                                                        class="h-4 w-4 border-gray-300 text-indigo-600 focus:ring-indigo-500" />
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Question de type texte -->
                                <div v-else-if="question.type === 'text'">
                                    <textarea v-model="form.reponses[question.id]" rows="3"
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"></textarea>
                                </div>

                                <!-- Questions à choix unique -->
                                <div v-else-if="question.type === 'radio'" class="space-y-2">
                                    <div v-for="option in question.options" :key="option.id" class="flex items-center">
                                        <input type="radio" :name="'question_' + question.id" :value="option.texte"
                                            v-model="form.reponses[question.id]"
                                            class="h-4 w-4 border-gray-300 text-indigo-600 focus:ring-indigo-500" />
                                        <label class="ml-3 block text-sm font-medium text-gray-700">
                                            {{ option.texte }}
                                        </label>
                                    </div>
                                </div>

                                <!-- Questions à choix multiples -->
                                <div v-else-if="question.type === 'checkbox'" class="space-y-2">
                                    <div v-for="option in question.options" :key="option.id" class="flex items-center">
                                        <input type="checkbox" :value="option.texte"
                                            v-model="form.reponses[question.id]"
                                            class="h-4 w-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500" />
                                        <label class="ml-3 block text-sm font-medium text-gray-700">
                                            {{ option.texte }}
                                        </label>
                                    </div>
                                </div>

                                <!-- Questions avec liste déroulante -->
                                <div v-else-if="question.type === 'select'">
                                    <select v-model="form.reponses[question.id]"
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                        <option value="">Sélectionnez une option</option>
                                        <option v-for="option in question.options" :key="option.id"
                                            :value="option.texte">
                                            {{ option.texte }}
                                        </option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="flex justify-end">
                            <button type="submit"
                                class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700"
                                :disabled="form.processing">
                                Soumettre
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { useForm } from '@inertiajs/vue3'
import { onMounted } from 'vue'

const props = defineProps({
    formulaire: {
        type: Object,
        required: true
    }
})

const form = useForm({
    reponses: {}
})

// Initialiser les réponses
onMounted(() => {
    props.formulaire.questions.forEach(question => {
        if (question.type === 'checkbox') {
            form.reponses[question.id] = []
        } else if (question.type === 'evaluation') {
            question.phrases?.forEach(phrase => {
                form.reponses[question.id + '_' + phrase.id] = ''
            })
        }
    })
})

const submit = () => {
    form.post(route('formulaire.submit', { token: props.formulaire.token }))
}
</script>
