<?php 

require_once '../classe/Utilisateur.php';
require_once '../classe/Client.php';

function register() {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Récupérer les données du formulaire
        $nom = $_POST['nom'];
        $username = $_POST['username'];
        $prenom = $_POST['prenom'];
        $adresse = $_POST['adresse'];
        $CIN = $_POST['cin'];
        $email = $_POST['email'];
        $password = $_POST['password'];
        $actif = True;

           $client = new Client($nom, $username, $prenom, $adresse, $CIN, $email, $password, $actif);

        // Sauvegarder le client dans la base de données
        try {
            $client->save();
            echo "<script type='text/javascript'>
                alert('Client ajouté avec succès !');
                window.location.href = '../pages/sign_in.php';
            </script>";
            exit;
        }catch (Exception $e){
            echo "<script type='text/javascript'>
                alert('Erreur lors de l\'ajout du client.');
                window.location.href = '../pages/sign_up.php'; 
            </script>";
            exit;
        }
    }
}
register();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inscription</title>
    <script src="https://unpkg.com/@tailwindcss/browser@4"></script>
</head>
<body>
    <div class="flex justify-center items-center min-h-screen bg-gray-100">
        <!-- Afficher le message de validation ou d'erreur -->
        <form action="sign_up.php" method="POST" class="bg-white p-8 rounded-lg shadow-md w-96">
            <h2 class="text-2xl font-bold mb-6 text-center">Inscription</h2>

            <div class="mb-4">
                <label for="nom" class="block text-sm font-medium text-gray-700">Nom</label>
                <input type="text" name="nom" id="nom" required
                    class="mt-1 block w-full px-3 py-2 bg-white border border-gray-300 rounded-md text-gray-900 focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
            </div>

            <div class="mb-4">
                <label for="prenom" class="block text-sm font-medium text-gray-700">Prénom</label>
                <input type="text" name="prenom" id="prenom" required
                    class="mt-1 block w-full px-3 py-2 bg-white border border-gray-300 rounded-md text-gray-900 focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
            </div>

            <div class="mb-4">
                <label for="username" class="block text-sm font-medium text-gray-700">Nom d'utilisateur</label>
                <input type="text" name="username" id="username" required
                    class="mt-1 block w-full px-3 py-2 bg-white border border-gray-300 rounded-md text-gray-900 focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
            </div>

            <div class="mb-4">
                <label for="adresse" class="block text-sm font-medium text-gray-700">Adresse</label>
                <input type="text" name="adresse" id="adresse"
                    class="mt-1 block w-full px-3 py-2 bg-white border border-gray-300 rounded-md text-gray-900 focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
            </div>

            <div class="mb-4">
                <label for="cin" class="block text-sm font-medium text-gray-700">CIN</label>
                <input type="text" name="cin" id="cin"  maxlength="20" required
                    class="mt-1 block w-full px-3 py-2 bg-white border border-gray-300 rounded-md text-gray-900 focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
            </div>
            
            <div class="mb-4">
                <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                <input type="email" name="email" id="email" required pattern="[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$"
                    title="Veuillez entrer une adresse email valide avec '@' et '.'"
                    class="mt-1 block w-full px-3 py-2 bg-white border border-gray-300 rounded-md text-gray-900 focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
            </div>

            <div class="mb-4">
                <label for="password" class="block text-sm font-medium text-gray-700">Mot de passe</label>
                <input type="password" name="password" id="password" required
                    class="mt-1 block w-full px-3 py-2 bg-white border border-gray-300 rounded-md text-gray-900 focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
            </div>

            <button type="submit"
                class="w-full bg-blue-500 text-white py-2 px-4 rounded-md hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                S'inscrire
            </button>
        </form>
    </div>
</body>
</html>