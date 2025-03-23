<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $formulaire->nom }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
    <div class="min-h-screen py-12 px-4 sm:px-6 lg:px-8">
        <div class="max-w-3xl mx-auto bg-white rounded-lg shadow-lg p-8">
            <h1 class="text-3xl font-bold text-gray-900 mb-8">{{ $formulaire->nom }}</h1>

            <form action="{{ route('formulaire.submit', $token) }}" method="POST" class="space-y-6">
                @csrf

                @foreach($formulaire->questions as $question)
                    <div class="bg-gray-50 p-6 rounded-lg">
                        <label class="block text-lg font-medium text-gray-900 mb-4">
                            {{ $question->contenu }}
                        </label>

                        @switch($question->type)
                            @case('text')
                                <textarea
                                    name="reponses[{{ $question->id }}]"
                                    rows="3"
                                    class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                    required
                                ></textarea>
                                @break

                            @case('radio')
                                <div class="space-y-2">
                                    @foreach(['1', '2', '3', '4', '5'] as $value)
                                        <div class="flex items-center">
                                            <input
                                                type="radio"
                                                name="reponses[{{ $question->id }}]"
                                                value="{{ $value }}"
                                                class="h-4 w-4 border-gray-300 text-indigo-600 focus:ring-indigo-500"
                                                required
                                            >
                                            <label class="ml-3 block text-sm font-medium text-gray-700">
                                                {{ $value }}
                                            </label>
                                        </div>
                                    @endforeach
                                </div>
                                @break

                            @default
                                <input
                                    type="text"
                                    name="reponses[{{ $question->id }}]"
                                    class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                    required
                                >
                        @endswitch
                    </div>
                @endforeach

                <div class="mt-8">
                    <button type="submit" class="w-full flex justify-center py-3 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        Soumettre mes réponses
                    </button>
                </div>
            </form>
        </div>
    </div>
</body>
</html>
