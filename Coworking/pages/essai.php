// Vérification de la disponibilité de la salle
$salleDisponible = false; // Valeur par défaut
if ($evenement->getSalle() instanceof Salle && 
    $evenement->getDateDebut() instanceof DateTime && 
    $evenement->getDateFin() instanceof DateTime) {

    // Vérification de la disponibilité
    $salleDisponible = Reservation::VerifierDisponibilite(
        $evenement->getDateDebut(),
        $evenement->getDateFin(),
        $evenement->getSalle()->getId()
    );
}



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
        <?php if(isset($_SESSION['user_id']) && !isset($_SESSION['adm'])): ?>
             <a href="participer.php?evenement_id=<?php echo $evenement->getId(); ?>" class="btn btn-primary">
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

public static function login($usernameOrEmail, $password) {
        // Connexion à la base de données
        $pdo = Database::connect();

        // Vérification si l'email ou le username existe
        $stmt = $pdo->prepare("SELECT * FROM Utilisateur WHERE username = ? OR email = ?");
        $stmt->execute([$usernameOrEmail, $usernameOrEmail]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($user) {
            if (password_verify($password, $user['password'])) {
                $stmtClient = $pdo->prepare("SELECT * FROM Client WHERE id = ?");
                $stmtClient->execute([$user['id']]);
                $client = $stmtClient->fetch(PDO::FETCH_ASSOC);
                // Vérification si le compte client est actif      
                if ($client) {
                    if ($client['actif']==true) {                
                        // Créer une session pour le client
                        if (session_status() === PHP_SESSION_NONE) {
                            session_start();
                        }
                        $_SESSION['user_id'] = $client['id'];
                        $_SESSION['adm'] = false;
                        return true;
                    }
                } else {

                    $stmtGest = $pdo->prepare("SELECT * FROM Gestionnaire WHERE id = ?");
                    $stmtGest->execute([$user['id']]);
                    $Gest = $stmtGest->fetch(PDO::FETCH_ASSOC);
                    if ($Gest) {
                        session_start();
                        $_SESSION['user_id'] = $Gest['id'];
                        $_SESSION['adm'] = true;
            
                        return true; 
                    } 
                }
            } else {
                return false; // Mauvais mot de passe
            }
        } else {
            return false; // Utilisateur non trouvé
        }
    }