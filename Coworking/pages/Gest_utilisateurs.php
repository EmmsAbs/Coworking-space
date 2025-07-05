<?php
session_start();
require_once '../classe/Client.php';


// Récupérer la liste des utilisateurs
$utilisateurs = Client::getAllClients();

// Traitement du blocage/déblocage
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['client_id'])) {
    $clientId = intval($_POST['client_id']);
    $action = $_POST['action']; // 'block' ou 'unblock'

    if ($action === 'block') {
        Client::blockClient($clientId);
        $_SESSION['success'] = "Utilisateur bloqué avec succès.";
    } elseif ($action === 'unblock') {
        Client::unblockClient($clientId);
        $_SESSION['success'] = "Utilisateur débloqué avec succès.";
    }

    header("Location: Gest_utilisateurs.php");
    exit();
}

$title = "Gestion des Utilisateurs";
include("../component/header_gest.php");
?>

<div class="bg-gradient-to-r mx-auto from-blue-50 to-gray-100 min-h-screen flex items-center justify-center p-6">
    <div class="bg-white rounded-2xl shadow-lg w-full max-w-4xl p-8">
        <h1 class="text-4xl font-extrabold text-gray-800 mb-6 text-center">Gestion des Utilisateurs</h1>

        <!-- Tableau des utilisateurs -->
        <div class="overflow-x-auto">
            <table class="min-w-full bg-white border border-gray-200">
                <thead>
                    <tr>
                        <th class="px-6 py-3 border-b-2 border-gray-300 text-left text-sm font-semibold text-gray-700">Nom</th>
                        <th class="px-6 py-3 border-b-2 border-gray-300 text-left text-sm font-semibold text-gray-700">Prénom</th>
                        <th class="px-6 py-3 border-b-2 border-gray-300 text-left text-sm font-semibold text-gray-700">Username</th>
                        <th class="px-6 py-3 border-b-2 border-gray-300 text-left text-sm font-semibold text-gray-700">Adresse</th>
                        <th class="px-6 py-3 border-b-2 border-gray-300 text-left text-sm font-semibold text-gray-700">CIN</th>
                        <th class="px-6 py-3 border-b-2 border-gray-300 text-left text-sm font-semibold text-gray-700">Email</th>
                        <th class="px-6 py-3 border-b-2 border-gray-300 text-left text-sm font-semibold text-gray-700">Statut</th>
                        <th class="px-6 py-3 border-b-2 border-gray-300 text-left text-sm font-semibold text-gray-700">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($utilisateurs as $utilisateur): ?>
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 border-b border-gray-200"><?= htmlspecialchars($utilisateur['nom']) ?></td>
                            <td class="px-6 py-4 border-b border-gray-200"><?= htmlspecialchars($utilisateur['prenom']) ?></td>
                            <td class="px-6 py-4 border-b border-gray-200"><?= htmlspecialchars($utilisateur['username']) ?></td>
                            <td class="px-6 py-4 border-b border-gray-200"><?= htmlspecialchars($utilisateur['adresse']) ?></td>
                            <td class="px-6 py-4 border-b border-gray-200"><?= htmlspecialchars($utilisateur['CIN']) ?></td>
                            <td class="px-6 py-4 border-b border-gray-200"><?= htmlspecialchars($utilisateur['email']) ?></td>
                            <td class="px-6 py-4 border-b border-gray-200">
                                <?= $utilisateur['blocked'] ? '<span class="text-red-600">Bloqué</span>' : '<span class="text-green-600">Actif</span>' ?>
                            </td>
                            <td class="px-6 py-4 border-b border-gray-200">
                                <form method="POST" class="inline">
                                    <input type="hidden" name="client_id" value="<?= $utilisateur['id'] ?>">
                                    <?php if ($utilisateur['blocked']): ?>
                                        <button type="submit" name="action" value="unblock" class="text-green-600 hover:text-green-900 font-semibold">
                                            Débloquer
                                        </button>
                                    <?php else: ?>
                                        <button type="submit" name="action" value="block" class="text-red-600 hover:text-red-900 font-semibold">
                                            Bloquer
                                        </button>
                                    <?php endif; ?>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>