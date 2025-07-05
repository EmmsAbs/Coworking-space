<?php
require_once '../classe/Evenement.php';
require_once '../classe/Salle.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'] ?? null;
    if (!$id) {
        header("Location: evenement_list.php");
        exit();
    }

    // Récupérer l'événement existant
    $evenement = Evenement::getEvenementById($id);
    if (!$evenement) {
        header("Location: evenement_list.php");
        exit();
    }

    // Gestion de l'upload de l'image
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $uploadDir = '../Img'; // Dossier où stocker les images
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true); // Crée le dossier s'il n'existe pas
        }

        // Générer un nom de fichier unique pour éviter les conflits
        $fileName = uniqid() . '_' . basename($_FILES['image']['name']);
        $uploadFilePath = $uploadDir . $fileName;

        // Déplacer le fichier uploadé vers le dossier de destination
        if (move_uploaded_file($_FILES['image']['tmp_name'], $uploadFilePath)) {
            $evenement->setImage($uploadFilePath); // Mettre à jour le chemin de l'image
        } else {
            // Debug : Afficher l'erreur
            die("Erreur lors du déplacement du fichier uploadé.");
        }
    } else {
        // Debug : Afficher l'erreur d'upload
        die("Erreur lors de l'upload : " . $_FILES['image']['error']);
    }

    // Mettre à jour uniquement les champs fournis
    if (isset($_POST['nom']) && !empty($_POST['nom'])) {
        $evenement->setNom($_POST['nom']);
    }
    if (isset($_POST['description']) && !empty($_POST['description'])) {
        $evenement->setDescription($_POST['description']);
    }
    if (isset($_POST['tarif']) && !empty($_POST['tarif'])) {
        $evenement->setTarif(floatval($_POST['tarif']));
    }
    if (isset($_POST['participants']) && !empty($_POST['participants'])) {
        $evenement->setParticipants(intval($_POST['participants']));
    }
    if (isset($_POST['dateDebut']) && !empty($_POST['dateDebut'])) {
        $evenement->setDateDebut(new DateTime($_POST['dateDebut']));
    }
    if (isset($_POST['dateFin']) && !empty($_POST['dateFin'])) {
        $evenement->setDateFin(new DateTime($_POST['dateFin']));
    }
    if (isset($_POST['salle_id']) && !empty($_POST['salle_id'])) {
        $salle = Salle::getSalleById(intval($_POST['salle_id']));
        if ($salle) {
            $evenement->setSalle($salle);
        }
    }

    // Enregistrer les modifications
    if ($evenement->update()) {
        header("Location: evenement_list.php?success=1");
    } else {
        header("Location: evenement_list.php?error=1");
    }
    exit();
}