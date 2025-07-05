<?php
require_once '../classe/Evenement.php';
require_once '../classe/Salle.php';

$id = $_GET['id'] ?? null;
if (!$id) {
    header("Location: evenement_list.php");
    exit();
}

$evenement = Evenement::getEvenementById($id);
if (!$evenement) {
    header("Location: evenement_list.php");
    exit();
}

$salles = Salle::getAllSalles();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modifier un Événement</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100">
    <div class="container mx-auto px-6 py-10">
        <!-- Header -->
        <h1 class="text-4xl font-extrabold text-center text-gray-800 mb-10">Modifier un Événement</h1>

        <!-- Formulaire de modification -->
        <div class="bg-white p-8 rounded-lg shadow-md mb-10">
            <h2 class="text-2xl font-semibold mb-6 text-gray-700">Événement</h2>
            
            <form action="evenement_update.php" method="POST" enctype="multipart/form-data" class="space-y-6">
                <input type="hidden" name="id" value="<?= $evenement->getId() ?>">

                <!-- Nom de l'événement -->
                <div>
                    <label for="nom" class="block text-sm font-medium text-gray-700">Nom de l'événement</label>
                    <input type="text" id="nom" name="nom" 
                        value="<?= htmlspecialchars($evenement->getNom()) ?>" 
                        placeholder="Conférence Tech" 
                        class="w-full mt-1 p-3 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-200">
                </div>

                <!-- Description -->
                <div>
                    <label for="description" class="block text-sm font-medium text-gray-700">Description</label>
                    <textarea id="description" name="description" 
                        rows="4" 
                        placeholder="Décrivez l'événement" 
                        class="w-full mt-1 p-3 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-200"
                    ><?= htmlspecialchars($evenement->getDescription()) ?></textarea>
                </div>

                <!-- Afficher l'image actuelle -->
                <div class="mb-4">
                     <label class="block text-sm font-medium text-gray-700">Image actuelle</label>
                     <?php if ($evenement->getImage()): ?>
                        <img src="<?= htmlspecialchars($evenement->getImage()) ?>" alt="Image de l'événement" class="mt-2 w-48 h-48 object-cover rounded-lg">
                    <?php else: ?>
                        <p class="text-gray-500">Aucune image disponible.</p>
                    <?php endif; ?>
                </div>

                 <!-- Champ pour uploader une nouvelle image -->
                 <div>
                     <label for="image" class="block text-sm font-medium text-gray-700">Nouvelle image</label>
                     <input type="file" id="image" name="image" 
                         accept="image/*" 
                         class="w-full mt-1 p-3 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-200">
                </div>

                <!-- Tarif et Participants sur la même ligne -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="tarif" class="block text-sm font-medium text-gray-700">Tarif (DH)</label>
                        <input type="number" id="tarif" name="tarif" 
                            value="<?= htmlspecialchars($evenement->getTarif()) ?>" 
                            min="0" 
                            step="0.01" 
                            placeholder="0.00" 
                            class="w-full mt-1 p-3 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-200">
                    </div>

                    <div>
                        <label for="participants" class="block text-sm font-medium text-gray-700">Nombre de participants</label>
                        <input type="number" id="participants" name="participants" 
                            value="<?= htmlspecialchars($evenement->getParticipants()) ?>" 
                            min="1" 
                            placeholder="50" 
                            class="w-full mt-1 p-3 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-200">
                    </div>
                </div>

                <!-- Dates sur la même ligne -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="dateDebut" class="block text-sm font-medium text-gray-700">Date de début</label>
                        <input type="datetime-local" id="dateDebut" name="dateDebut" 
                            value="<?= $evenement->getDateDebut()->format('Y-m-d\TH:i') ?>" 
                            class="w-full mt-1 p-3 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-200">
                    </div>

                    <div>
                        <label for="dateFin" class="block text-sm font-medium text-gray-700">Date de fin</label>
                        <input type="datetime-local" id="dateFin" name="dateFin" 
                            value="<?= $evenement->getDateFin()->format('Y-m-d\TH:i') ?>" 
                            class="w-full mt-1 p-3 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-200">
                    </div>
                </div>

                <!-- Salle -->
                <div>
                    <label for="salle_id" class="block text-sm font-medium text-gray-700">Salle</label>
                    <select id="salle_id" name="salle_id" 
                        class="w-full mt-1 p-3 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-200">
                        <?php foreach ($salles as $salle): ?>
                            <option value="<?= $salle['id'] ?>" 
                                <?= $salle['id'] == $evenement->getSalle()->getId() ? 'selected' : '' ?>>
                                <?= htmlspecialchars($salle['nom']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <!-- Boutons d'action -->
                <div class="flex space-x-4 justify-end pt-6">
                    <a href="evenement_list.php" 
                       class="bg-gray-500 hover:bg-gray-600 text-white py-2 px-6 rounded-md focus:outline-none focus:ring-2 focus:ring-gray-300 transition duration-200">
                        Annuler
                    </a>
                    <button type="submit" 
                            class="bg-blue-600 hover:bg-blue-700 text-white py-2 px-6 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-300 transition duration-200">
                        Modifier l'événement
                    </button>
                </div>
            </form>
        </div>