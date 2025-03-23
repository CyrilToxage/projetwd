<template>

    <Head title="Gestion des questionnaires" />
    <AppLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Gestion des questionnaires
            </h2>
        </template>

        <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
            <div class="flex justify-between items-center mb-6">
                <h3 class="text-lg font-medium text-gray-900">Liste des questionnaires</h3>
                <Link :href="route('admin.formulaires.create')"
                    class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700">
                Nouveau questionnaire
                </Link>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Nom</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Date de création</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Statut
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Actions
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        <tr v-for="formulaire in props.formulaires" :key="formulaire.id">
                            <td class="px-6 py-4 whitespace-nowrap">{{ formulaire.nom }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">{{ formatDate(formulaire.created_at) }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span :class="[
                                    'px-2 inline-flex text-xs leading-5 font-semibold rounded-full',
                                    formulaire.actif ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800'
                                ]">
                                    {{ formulaire.actif ? 'Actif' : 'Inactif' }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium space-x-2">
                                <Link :href="route('admin.formulaires.edit', formulaire.id)"
                                    class="text-indigo-600 hover:text-indigo-900">
                                Modifier
                                </Link>
                                <button @click="handleGenerateLink(formulaire)"
                                    class="text-green-600 hover:text-green-900">
                                    Générer lien
                                </button>
                                <button @click="deleteFormulaire(formulaire)" class="text-red-600 hover:text-red-900">
                                    Supprimer
                                </button>
                                <a :href="route('admin.formulaires.export', formulaire.id)" target="_blank"
                                    class="text-blue-600 hover:text-blue-900">
                                    Exporter Excel
                                </a>
                            </td>
                        </tr>
                        <tr v-if="props.formulaires.length === 0">
                            <td colspan="4" class="px-6 py-4 text-center text-gray-500">
                                Aucun questionnaire trouvé
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </AppLayout>
</template>

<script setup>
import { ref } from 'vue'
import { Head, Link, router } from '@inertiajs/vue3'
import AppLayout from '@/Layouts/AppLayout.vue'

const props = defineProps({
    formulaires: {
        type: Array,
        default: () => []
    }
})

const formatDate = (date) => {
    if (!date) return ''
    try {
        const dateObj = new Date(date)
        if (isNaN(dateObj.getTime())) return ''

        return new Intl.DateTimeFormat('fr-FR', {
            day: '2-digit',
            month: '2-digit',
            year: 'numeric',
            hour: '2-digit',
            minute: '2-digit'
        }).format(dateObj)
    } catch (error) {
        return ''
    }
}

const handleGenerateLink = async (formulaire) => {
    router.post(
        route('admin.formulaires.generate-link', formulaire.id),
        {},
        {
            preserveScroll: true,
            onSuccess: (response) => {
                if (response?.url) {
                    navigator.clipboard.writeText(response.url)
                    alert('Lien copié dans le presse-papier !')
                }
            },
            onError: () => {
                alert('Erreur lors de la génération du lien')
            }
        }
    )
}

const deleteFormulaire = (formulaire) => {
    if (confirm('Êtes-vous sûr de vouloir supprimer ce questionnaire ? Cette action est irréversible.')) {
        router.delete(route('admin.formulaires.destroy', formulaire.id), {
            onSuccess: () => {
                // Le formulaire sera automatiquement retiré de la liste grâce à Inertia
            },
            onError: () => {
                alert('Une erreur est survenue lors de la suppression du questionnaire')
            }
        })
    }
}
</script>
