<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des Événements</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 font-sans">
    <div class="container mx-auto px-6 py-10">
        <!-- Header -->
        <h1 class="text-4xl font-extrabold text-center text-gray-800 mb-10">Gestion des Événements</h1>

        <!-- Formulaire d'ajout d'événement -->
        <div class="bg-white p-8 rounded-lg shadow-md mb-10">
            <h2 class="text-2xl font-semibold mb-6 text-gray-700">Ajouter un Événement</h2>
            <form action="gestion_evenements.php" method="POST" class="space-y-6">
                <div>
                    <label for="nom" class="block text-sm font-medium text-gray-700">Nom de l'Événement</label>
                    <input type="text" id="nom" name="nom" required placeholder="Nom de l'événement" class="w-full mt-1 p-3 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500">
                </div>

                <div>
                    <label for="description" class="block text-sm font-medium text-gray-700">Description</label>
                    <textarea id="description" name="description" required placeholder="Description de l'événement" class="w-full mt-1 p-3 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500"></textarea>
                </div>

                <div>
                    <label for="date_debut" class="block text-sm font-medium text-gray-700">Date de Début</label>
                    <input type="date" id="date_debut" name="date_debut" required class="w-full mt-1 p-3 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500">
                </div>

                <div>
                    <label for="date_fin" class="block text-sm font-medium text-gray-700">Date de Fin</label>
                    <input type="date" id="date_fin" name="date_fin" required class="w-full mt-1 p-3 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500">
                </div>

                <div>
                    <label for="heure_debut" class="block text-sm font-medium text-gray-700">Heure de Début</label>
                    <input type="time" id="heure_debut" name="heure_debut" required class="w-full mt-1 p-3 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500">
                </div>

                <div>
                    <label for="heure_fin" class="block text-sm font-medium text-gray-700">Heure de Fin</label>
                    <input type="time" id="heure_fin" name="heure_fin" required class="w-full mt-1 p-3 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500">
                </div>

                <button type="submit" name="add_event" class="bg-blue-600 hover:bg-blue-700 text-white py-2 px-6 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-300">
                    Ajouter l'Événement
                </button>
            </form>
        </div>

        <!-- Liste des événements -->
        <h2 class="text-3xl font-semibold mb-6 text-gray-800">Liste des Événements</h2>
        <div class="overflow-x-auto bg-white rounded-lg shadow-md">
            <table class="min-w-full table-auto divide-y divide-gray-200">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="px-6 py-3 text-left text-sm font-semibold text-gray-600">Nom</th>
                        <th class="px-6 py-3 text-left text-sm font-semibold text-gray-600">Description</th>
                        <th class="px-6 py-3 text-left text-sm font-semibold text-gray-600">Date de Début</th>
                        <th class="px-6 py-3 text-left text-sm font-semibold text-gray-600">Date de Fin</th>
                        <th class="px-6 py-3 text-left text-sm font-semibold text-gray-600">Heure de Début</th>
                        <th class="px-6 py-3 text-left text-sm font-semibold text-gray-600">Heure de Fin</th>
                        <th class="px-6 py-3 text-left text-sm font-semibold text-gray-600">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    <!-- Exemple dynamique des événements -->
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4">Événement A</td>
                        <td class="px-6 py-4">Description de l'événement A</td>
                        <td class="px-6 py-4">2025-01-30</td>
                        <td class="px-6 py-4">2025-02-02</td>
                        <td class="px-6 py-4">14:00</td>
                        <td class="px-6 py-4">18:00</td>
                        <td class="px-6 py-4 space-x-2">
                            <a href="#" class="text-green-500 hover:underline">Modifier</a>
                            <a href="#" class="text-red-500 hover:underline">Supprimer</a>
                        </td>
                    </tr>
                    <!-- Fin Exemple dynamique -->
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>