<?php
session_start();

require_once '../classe/Evenement.php';

// Vérifier si l'ID de l'événement et du client sont passés en paramètre
if (!isset($_GET['evenement_id']) || !isset($_GET['client_id'])) {
    $_SESSION['error'] = "Paramètres manquants.";
    header("Location: participants_list.php");
    exit();
}

$evenementId = intval($_GET['evenement_id']);
$clientId = intval($_GET['client_id']);

// Supprimer la participation
if (Evenement::supprimerParticipation($evenementId, $clientId)) {
    $_SESSION['success'] = "La participation a été supprimée avec succès.";
} else {
    $_SESSION['error'] = "Une erreur s'est produite lors de la suppression de la participation.";
}

header("Location: participants_list.php?evenement_id=" . $evenementId);
exit();
?>