<?php
session_start();

require_once '../classe/Client.php';

// Récupérer les informations du client connecté
$clientId = $_SESSION['user_id'];
$client = Client::getClientByID($clientId);

if (!$client) {
    $_SESSION['error'] = "Client non trouvé.";
    header("Location: index.php");
    exit();
}

// Traitement du formulaire de modification
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Récupérer les données du formulaire
    $nom = htmlspecialchars($_POST['nom']);
    $prenom = htmlspecialchars($_POST['prenom']);
    $adresse = htmlspecialchars($_POST['adresse']);
    $CIN = htmlspecialchars($_POST['CIN']);
    $email = htmlspecialchars($_POST['email']);
    $password = $_POST['password']; // Mot de passe non hashé

    // Préparer les données pour la mise à jour
    $data = [
        'nom' => $nom,
        'prenom' => $prenom,
        'adresse' => $adresse,
        'CIN' => $CIN,
        'email' => $email,
    ];

    // Si un nouveau mot de passe est fourni, l'ajouter aux données
    if (!empty($password)) {
        $data['password'] = password_hash($password, PASSWORD_BCRYPT);
    }

    // Mettre à jour les informations du client
    if (Client::updateClient($clientId, $data)) {
        $_SESSION['success'] = "Vos informations ont été mises à jour avec succès.";
        header("Location: mon_profil.php");
        exit();
    } else {
        $_SESSION['error'] = "Une erreur s'est produite lors de la mise à jour.";
    }
}

$title = "Mon Profil";
include("../component/header.php");
?>

<div class="bg-gradient-to-r mx-auto from-blue-50 to-gray-100 min-h-screen flex items-center justify-center p-6">
    <div class="bg-white rounded-2xl shadow-lg w-full max-w-3xl p-8">
        <h1 class="text-4xl font-extrabold text-gray-800 mb-6 text-center">Mon Profil</h1>

        <!-- Affichage des messages de succès ou d'erreur -->
        <?php if (isset($_SESSION['success'])): ?>
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                <?= $_SESSION['success'] ?>
            </div>
            <?php unset($_SESSION['success']); ?>
        <?php endif; ?>

        <?php if (isset($_SESSION['error'])): ?>
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                <?= $_SESSION['error'] ?>
            </div>
            <?php unset($_SESSION['error']); ?>
        <?php endif; ?>

        <!-- Formulaire de modification -->
        <form method="POST" class="space-y-6">
            <div>
                <label for="nom" class="block text-sm font-medium text-gray-700">Nom</label>
                <input type="text" name="nom" id="nom" value="<?= htmlspecialchars($client->getNom()) ?>" 
                       class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
            </div>
            <div>
                <label for="prenom" class="block text-sm font-medium text-gray-700">Prénom</label>
                <input type="text" name="prenom" id="prenom" value="<?= htmlspecialchars($client->getPrenom()) ?>" 
                       class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
            </div>
            <div>
                <label for="adresse" class="block text-sm font-medium text-gray-700">Adresse</label>
                <input type="text" name="adresse" id="adresse" value="<?= htmlspecialchars($client->getAdresse()) ?>" 
                       class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
            </div>
            <div>
                <label for="CIN" class="block text-sm font-medium text-gray-700">CIN</label>
                <input type="text" name="CIN" id="CIN" value="<?= htmlspecialchars($client->getCIN()) ?>" 
                       class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
            </div>
            <div>
                <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                <input type="email" name="email" id="email" value="<?= htmlspecialchars($client->getEmail()) ?>" 
                       class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
            </div>
            <div>
                <label for="password" class="block text-sm font-medium text-gray-700">Nouveau mot de passe (laisser vide pour ne pas changer)</label>
                <input type="password" name="password" id="password" 
                       class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
            </div>
            <div>
                <button type="submit" class="w-full bg-blue-600 text-white py-2 px-4 rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    Mettre à jour
                </button>
            </div>
        </form>
    </div>
</div>