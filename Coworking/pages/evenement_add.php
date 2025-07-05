<?php
require_once '../classe/Evenement.php';
require_once '../classe/Salle.php';
require_once '../classe/Reservation.php';

$title = "Ajouter un Événement";
include("../component/header_gest.php");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nom = $_POST['nom'];
    $description = $_POST['description'];
    $participants = $_POST['participants'];
    $tarif = $_POST['tarif'];
    $dateDebut = new DateTime($_POST['dateDebut']);
    $dateFin = new DateTime($_POST['dateFin']);
    $salleId = $_POST['salle_id'];
    $salle = Salle::getSalleById($salleId);

    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        // Récupérer les informations du fichier téléchargé
        $imageTmpPath = $_FILES['image']['tmp_name'];
        $imageExtension = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);

        // Définir le nouveau nom de fichier (nom de la salle + extension)
        $newImageName = strtolower(str_replace(' ', '_', $nom)) . '.' . $imageExtension;

        // Spécifier le répertoire où les images seront stockées
        $uploadDir = '../Img/Events';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true); // Créer le dossier si nécessaire
        }

        // Définir le chemin complet du fichier
        $newImagePath = $uploadDir .'/' .$newImageName;
        if (move_uploaded_file($imageTmpPath, $newImagePath)) {
            // Vérifier si la salle est disponible
            if (!empty(Reservation::VerifierDisponibilite($dateDebut, $dateFin, $salleId))) {
                echo "La salle n'est pas disponible pour les dates sélectionnées.";
            } else {
                $evenement = new Evenement(
                    0,
                    $nom,
                    $description,
                    $newImagePath,
                    $tarif,
                    $participants,
                    $dateDebut,
                    $dateFin,
                    $salle
                );

                if ($evenement->save()) {
                    echo "<script type='text/javascript'>
                        alert('Événement ajouté avec succès!');
                        window.location.href = '../pages/evenement_list.php'; 
                    </script>";
                    exit;
                } else {
                    echo "<script type='text/javascript'>
                        alert('Erreur lors de l'ajout de l'événement. Veuillez réessayer!');
                        window.location.href = '../pages/evenement_add.php'; 
                    </script>";
                    exit;
                }
            }
        }
    }
}
?>

<div class="bg-gradient-to-r mx-auto from-blue-50 to-gray-100 min-h-screen flex items-center justify-center p-6">
    <div class="bg-white rounded-2xl shadow-lg w-full max-w-3xl p-8">
        <h1 class="text-4xl font-extrabold text-gray-800 mb-6 text-center">Ajouter un Événement</h1>

        <form method="POST" enctype="multipart/form-data">
            <div class="space-y-4">
                <label for="nom" class="block text-gray-700">Nom:</label>
                <input type="text" id="nom" name="nom" class="w-full px-4 py-2 border rounded-lg" required>

                <label for="description" class="block text-gray-700">Description:</label>
                <textarea id="description" name="description" class="w-full px-4 py-2 border rounded-lg" required></textarea>

                <label for="salle_id" class="block text-gray-700">Salle:</label>
                <select id="salle_id" name="salle_id" class="w-full px-4 py-2 border rounded-lg" required>
                    <?php
                    $salles = Salle::getAllSalles();
                    foreach ($salles as $salle) {
                        echo "<option value='" . $salle['id'] . "'>" . htmlspecialchars($salle['nom']) . "</option>";
                    }
                    ?>
                </select>

                <label for="tarif" class="block text-gray-700">Tarif:</label>
                <input type="number" id="tarif" name="tarif" step="0.01" class="w-full px-4 py-2 border rounded-lg" required>

                <label for="participants" class="block text-gray-700">Nombre de participants:</label>
                <input type="number" id="participants" name="participants" class="w-full px-4 py-2 border rounded-lg" required>

                <label for="dateDebut" class="block text-gray-700">Date de début:</label>
                <input type="datetime-local" id="dateDebut" name="dateDebut" class="w-full px-4 py-2 border rounded-lg" required>

                <label for="dateFin" class="block text-gray-700">Date de fin:</label>
                <input type="datetime-local" id="dateFin" name="dateFin" class="w-full px-4 py-2 border rounded-lg" required>

                
                <label for="image" class="block text-gray-700">Choisir un fichier:</label>
                <input type="file" id="image" name="image" accept="image/*" class="w-full px-4 py-2 border rounded-lg" required>
                
                
            </div>

            <div class="text-center mt-8">
                <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white py-3 px-6 rounded-xl focus:outline-none focus:ring-4 focus:ring-blue-300 font-semibold">
                    Ajouter
                </button>
            </div>
        </form>
    </div>
</div>