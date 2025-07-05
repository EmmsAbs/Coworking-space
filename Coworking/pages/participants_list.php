<?php
session_start();

require_once '../classe/Evenement.php';
require_once '../classe/Client.php';

// Vérifier si l'ID de l'événement est passé en paramètre
if (!isset($_GET['evenement_id'])) {
    $_SESSION['error'] = "ID de l'événement manquant.";
    header("Location: evenement_view.php");
    exit();
}

$evenementId = intval($_GET['evenement_id']);

// Récupérer l'événement et ses participants
$evenement = Evenement::getEvenementById($evenementId);
if (!$evenement) {
    $_SESSION['error'] = "Événement non trouvé.";
    header("Location: evenement_view.php");
    exit();
}

$participants = Evenement::getListeParticipants($evenementId);

$title = "Liste des Participants";
include("../component/header_gest.php");
?>

<div class="bg-gradient-to-r mx-auto from-blue-50 to-gray-100 min-h-screen flex items-center justify-center p-6">
    <div class="bg-white rounded-2xl shadow-lg w-full max-w-4xl p-8">
        <h1 class="text-4xl font-extrabold text-gray-800 mb-6 text-center">Liste des Participants</h1>
        <h2 class="text-2xl font-semibold text-gray-700 mb-6 text-center"><?= htmlspecialchars($evenement->getNom()) ?></h2>

        <!-- Tableau des participants -->
        <div class="overflow-x-auto">
            <table class="min-w-full bg-white border border-gray-200">
                <thead>
                    <tr>
                        <th class="px-6 py-3 border-b-2 border-gray-300 text-left text-sm font-semibold text-gray-700">Nom</th>
                        <th class="px-6 py-3 border-b-2 border-gray-300 text-left text-sm font-semibold text-gray-700">Email</th>
                        <th class="px-6 py-3 border-b-2 border-gray-300 text-left text-sm font-semibold text-gray-700">Date d'inscription</th>
                        <th class="px-6 py-3 border-b-2 border-gray-300 text-left text-sm font-semibold text-gray-700">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($participants as $participant): ?>
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 border-b border-gray-200"><?= htmlspecialchars($participant['nom']) ?></td>
                            <td class="px-6 py-4 border-b border-gray-200"><?= htmlspecialchars($participant['email']) ?></td>
                            <td class="px-6 py-4 border-b border-gray-200"><?= htmlspecialchars($participant['date_inscription']) ?></td>
                            <td class="px-6 py-4 border-b border-gray-200">
                                <a href="supp_participant.php?evenement_id=<?= $evenementId ?>&client_id=<?= $participant['id'] ?>" 
                                   class="text-red-600 hover:text-red-900 font-semibold">
                                    Supprimer
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>