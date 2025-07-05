<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once '../classe/Evenement.php';
require_once '../classe/Client.php';

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['user_id'])) {
    $_SESSION['error'] = "Vous devez être connecté pour participer à un événement.";
    header('Location: ../index.php');
    exit();
}

// Vérifier si l'ID de l'événement est fourni
if (!isset($_GET['evenement_id'])) {
    $_SESSION['error'] = "ID de l'événement manquant.";
    header('Location: evenement_view.php');
    exit();
}

$evenementId = intval($_GET['evenement_id']);
$clientId = $_SESSION['user_id'];

try {
    // Récupérer l'événement
    $evenement = Evenement::getEvenementById($evenementId);
    
    if (!$evenement) {
        throw new Exception("Événement non trouvé.");
    }
    
    // Vérifier si l'événement n'est pas déjà complet
    $clients = Evenement::getEventClients($evenementId);
    if (count($clients) >= $evenement->getParticipants()) {
        throw new Exception("Désolé, l'événement est complet.");
    }
    
    // Ajouter la participation
    $success = Evenement::ajouterParticipation($evenementId, $clientId);
    
    if ($success) {
        $_SESSION['success'] = "Votre participation a été enregistrée avec succès !";
    } else {
        throw new Exception("Vous participez déjà à cet événement ou une erreur est survenue.");
    }
    
} catch (Exception $e) {
    $_SESSION['error'] = $e->getMessage();
}

// Redirection vers la page de détails de l'événement
header("Location: evenement_details.php?id=" . $evenementId);
exit();