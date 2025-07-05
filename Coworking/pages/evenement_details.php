<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();

}
require_once '../classe/Evenement.php';
require_once '../classe/Salle.php';
require_once '../classe/Reservation.php';

// Vérifier si l'ID de l'événement est passé en paramètre
if (!isset($_GET['id'])) {
    header("Location: evenement_view.php");
    exit();
}

$evenementId = intval($_GET['id']);
$evenement = Evenement::getEvenementById($evenementId);

if (!$evenement) {
    echo "Événement non trouvé.";
    exit();
}

$title = "Détails de l'Événement";
include("../component/header.php");
?>

<div class="bg-gradient-to-r mx-auto from-blue-50 to-gray-100 min-h-screen flex items-center justify-center p-6">
<?php if (isset($_SESSION['error'])): ?>
    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4">
        <?php 
        echo $_SESSION['error'];
        unset($_SESSION['error']);
        ?>
    </div>
<?php endif; ?>

<?php if (isset($_SESSION['success'])): ?>
    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4">
        <?php 
        echo $_SESSION['success'];
        unset($_SESSION['success']);
        ?>
    </div>
<?php endif; ?>
    <div class="bg-white rounded-2xl shadow-lg w-full max-w-3xl p-8">
        <h1 class="text-4xl font-extrabold text-gray-800 mb-6 text-center">Détails de l'Événement</h1>
        <h2 class="text-2xl font-semibold text-gray-700 mb-6 text-center"><?= htmlspecialchars($evenement->getNom()) ?></h2>

        <!-- Image de l'événement -->
        <img src="<?= htmlspecialchars($evenement->getImage()) ?>" alt="Photo de l'événement" class="rounded-xl w-3/4 mx-auto object-cover mb-6">

        <!-- Informations sur l'événement -->
        <div class="space-y-4">
            <p class="text-gray-600 text-md">Description : <?= htmlspecialchars($evenement->getDescription()) ?></p>
            <p class="text-gray-600 text-md">Tarif : <strong><?= htmlspecialchars($evenement->getTarif()) ?> DH</strong></p>
            <p class="text-gray-600 text-md">Date de début : <strong><?= $evenement->getDateDebut()->format('Y-m-d H:i:s') ?></strong></p>
            <p class="text-gray-600 text-md">Date de fin : <strong><?= $evenement->getDateFin()->format('Y-m-d H:i:s') ?></strong></p>
            <p class="text-gray-600 text-md">Nombre de participants: <strong><?= $evenement->getParticipants() ?> personnes</strong></p>
            <p class="text-gray-600 text-md">Salle : <strong><?= htmlspecialchars($evenement->getSalle()->getNom()) ?></strong></p>
        </div>

        <!-- Boutons d'action -->
        <div class="flex justify-between mt-8">
    <?php if (isset($_SESSION['user_id']) && (!isset($_SESSION['adm']) || $_SESSION['adm'] == 0)): ?>
        <a href="participer.php?evenement_id=<?php echo $evenement->getId(); ?>" 
           class="inline-flex items-center justify-center px-6 py-3 bg-blue-600 text-white font-semibold rounded-lg shadow-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-all duration-200 transform hover:scale-105">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
            </svg>
            Participer
        </a>
    <?php endif; ?>
    <a href="evenement_view.php">
        <button class="bg-gray-500 hover:bg-gray-600 text-white py-3 px-6 rounded-xl focus:outline-none focus:ring-4 focus:ring-gray-300 font-semibold">
            Retour
        </button>
    </a>
</div>
    </div>
</div>