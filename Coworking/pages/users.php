<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des Utilisateurs</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 font-sans">
    <div class="container mx-auto px-6 py-10">
        <!-- Header -->
        <h1 class="text-4xl font-extrabold text-center text-gray-800 mb-10">Gestion des Utilisateurs</h1>

        <!-- Liste des utilisateurs -->
        <h2 class="text-3xl font-semibold mb-6 text-gray-800">Liste des Utilisateurs</h2>
        <div class="overflow-x-auto bg-white rounded-lg shadow-md">
            <table class="min-w-full table-auto divide-y divide-gray-200">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="px-6 py-3 text-left text-sm font-semibold text-gray-600">Nom</th>
                        <th class="px-6 py-3 text-left text-sm font-semibold text-gray-600">Prénom</th>
                        <th class="px-6 py-3 text-left text-sm font-semibold text-gray-600">Nom d'utilisateur</th>
                        <th class="px-6 py-3 text-left text-sm font-semibold text-gray-600">CIN</th>
                        <th class="px-6 py-3 text-left text-sm font-semibold text-gray-600">Email</th>
                        <th class="px-6 py-3 text-left text-sm font-semibold text-gray-600">Adresse</th>
                        <th class="px-6 py-3 text-left text-sm font-semibold text-gray-600">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    <!-- Exemple dynamique des utilisateurs -->
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4">Jean</td>
                        <td class="px-6 py-4">Dupont</td>
                        <td class="px-6 py-4">jdupont</td>
                        <td class="px-6 py-4">123456789</td>
                        <td class="px-6 py-4">jean.dupont@example.com</td>
                        <td class="px-6 py-4">10 Rue de Paris</td>
                        <td class="px-6 py-4 space-x-2">
                            <a href="#" class="text-blue-500 hover:underline">Modifier</a>
                            <a href="#" class="text-red-500 hover:underline">Bloquer</a>
                        </td>
                    </tr>
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4">Marie</td>
                        <td class="px-6 py-4">Curie</td>
                        <td class="px-6 py-4">mcurie</td>
                        <td class="px-6 py-4">987654321</td>
                        <td class="px-6 py-4">marie.curie@example.com</td>
                        <td class="px-6 py-4">15 Avenue des Champs</td>
                        <td class="px-6 py-4 space-x-2">
                            <a href="#" class="text-blue-500 hover:underline">Modifier</a>
                            <a href="#" class="text-red-500 hover:underline">Bloquer</a>
                        </td>
                    </tr>
                    <!-- Fin Exemple dynamique -->
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>