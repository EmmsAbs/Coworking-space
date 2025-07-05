<?php
require_once '../classe/Evenement.php';
require_once '../classe/Salle.php';
require_once '../classe/Reservation.php';

// Récupérer tous les événements disponibles
$evenements = Evenement::getAllEvenements();

$title = "Liste des Événements";
include("../component/header.php");
?>

<div class="bg-gradient-to-r mx-auto from-blue-50 to-gray-100 min-h-screen p-6">
    <div class="container mx-auto">
        <h1 class="text-4xl font-extrabold text-gray-800 mb-6 text-center">Liste des Événements</h1>

        <!-- Liste des événements -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <?php foreach ($evenements as $evenement): ?>
                <?php
                // Vérifier si la salle est disponible pour cet événement
                $salleDisponible = Reservation::VerifierDisponibilite(
                    new DateTime($evenement['dateDebut']),
                    new DateTime ($evenement['dateFin']),
                    $evenement['salle_id']
                );
                ?>
                <div class="bg-white rounded-2xl shadow-lg p-6">
                    
                    <img src="<?= htmlspecialchars($evenement['image']) ?>" alt="<?= htmlspecialchars($evenement['nom']) ?>" class="rounded-xl w-full h-48 object-cover mb-4">
                    <h2 class="text-xl font-semibold text-gray-700 mb-4"><?= htmlspecialchars($evenement['nom']) ?></h2>
                    <p class="text-gray-600 text-md mt-2">Tarif : <strong><?= htmlspecialchars($evenement['tarif']) ?> DH</strong></p>
                    <p class="text-gray-600 text-md">Date de début : <strong><?= (new DateTime($evenement['dateDebut']))->format('Y-m-d H:i:s') ?></strong></p>
                    <p class="text-gray-600 text-md">Salle : <strong><?= htmlspecialchars(Salle::getSalleById( $evenement['salle_id'])-> getNom()) ?></strong></p>
                    <div class="text-center mt-4">
                        <a href="evenement_details.php?id=<?= $evenement['id'] ?>">
                            <button class="bg-blue-500 hover:bg-blue-600 text-white py-2 px-4 rounded-xl focus:outline-none focus:ring-4 focus:ring-blue-300 font-semibold">
                                Voir les détails
                            </button>
                        </a>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>