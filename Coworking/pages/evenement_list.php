<?php
require_once '../classe/Evenement.php';
require_once '../classe/Salle.php';
require_once '../classe/Reservation.php';

// Récupérer tous les événements
$evenements = Evenement::getAllEvenements();

$title = "Liste des Événements";
include("../component/header_gest.php");
?>

<div class="bg-gradient-to-r mx-auto from-blue-50 to-gray-100 min-h-screen p-6">
    <div class="container mx-auto">
        <h1 class="text-4xl font-extrabold text-gray-800 mb-6 text-center">Liste des Événements</h1>

        <!-- Bouton Ajouter un Événement -->
        <div class="text-center mb-6">
            <a href="evenement_add.php">
                <button class="bg-green-600 hover:bg-green-700 text-white py-3 px-6 rounded-xl focus:outline-none focus:ring-4 focus:ring-green-300 font-semibold">
                    Ajouter un Événement
                </button>
            </a>
        </div>

        <!-- Liste des événements -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <?php foreach ($evenements as $evenement): ?>
                <div class="bg-white rounded-2xl shadow-lg p-6">
                    <h2 class="text-2xl font-semibold text-gray-700 mb-4"><?= htmlspecialchars($evenement['nom']) ?></h2>
                    <img src="<?= htmlspecialchars($evenement['image']) ?>" alt="<?= htmlspecialchars($evenement['nom']) ?>" class="rounded-xl w-full h-48 object-cover mb-4">
                    <p class="text-gray-600 text-md"><?= htmlspecialchars($evenement['description']) ?></p>
                    <p class="text-gray-600 text-md mt-2">Tarif : <strong><?= htmlspecialchars($evenement['tarif']) ?> DH</strong></p>
                    <p class="text-gray-600 text-md">Date de début : <strong><?= (new DateTime($evenement['dateDebut']))->format('Y-m-d H:i:s') ?></strong></p>
                    <p class="text-gray-600 text-md">Date de fin : <strong><?= (new DateTime($evenement['dateFin']))->format('Y-m-d H:i:s') ?></strong></p>
                    <p class="text-gray-600 text-md">Salle : <strong><?= htmlspecialchars(Salle::getSalleById( $evenement['salle_id'])-> getNom()) ?></strong></p>
                    <div class="text-center mt-4">
                        <a href="evenement_edit.php?id=<?= $evenement['id'] ?>">
                            <button class="bg-yellow-500 hover:bg-yellow-600 text-white py-2 px-4 rounded-xl focus:outline-none focus:ring-4 focus:ring-yellow-300 font-semibold">
                                Modifier
                            </button>
                        </a>
                        <a href="evenement_delete.php?id=<?= $evenement['id'] ?>">
                            <button class="bg-red-500 hover:bg-red-600 text-white py-2 px-4 rounded-xl focus:outline-none focus:ring-4 focus:ring-red-300 font-semibold ml-2">
                                Supprimer
                            </button>
                        </a>
                        <a href="participants_list.php?evenement_id=<?= $evenement['id']  ?>">
                            <button class="bg-gray-500 hover:bg-gray-600 text-white py-2 px-4 rounded-xl focus:outline-none focus:ring-4 focus:ring-gray-300 font-semibold ml-2">
                                Voir les participants
                            </button>
                        </a>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>