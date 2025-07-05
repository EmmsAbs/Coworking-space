<?php
    session_start();
    // Vérifie si l'utilisateur est connecté
    if (!isset($_SESSION['user_id'])) {
        // Redirige vers la page de connexion si non authentifié
        header("Location: ../pages/sign_in.php");
        exit();
    }

    if(isset($_SESSION['adm']) && $_SESSION['adm']){
        header("Location: ../index.php");
        exit();
    }

    $client_id = intval($_SESSION['user_id']);
?>

<?php
    require_once '../classe/Database.php';
    require_once '../classe/Salle.php';
    require_once '../classe/Type.php';
    require_once '../classe/Reservation.php';
    require_once '../classe/Client.php';
    $pdo = Database::connect();
?>

<?php
    $title = "Reservation";
    include("../component/header.php");
?>

<?php
// Récupérer les salles disponibles avec leur prix horaire
$salles = Salle::getAllSalles();

// Récupérer la semaine sélectionnée (par défaut, la semaine actuelle)
$selectedWeek = isset($_GET['week']) ? (int)$_GET['week'] : 0;
$startOfWeek = (new DateTime())->modify("+$selectedWeek weeks")->modify('today'); // Début à partir d'aujourd'hui
$endOfWeek = (clone $startOfWeek)->modify('+6 days'); // 7 jours à partir d'aujourd'hui

// Date et heure actuelles pour désactiver les heures passées
$now = new DateTime();

// Traitement du formulaire de réservation
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $salle_id = $_POST['salle_id'];
    $selected_slots = json_decode($_POST['selected_slots'], true);

    // Calculer la durée totale et le prix total
    $duree_totale = 0; // en heures
    $prix_total = 0;

    foreach ($selected_slots as $slot) {
        $date_debut = new DateTime($slot['date'] . ' ' . $slot['heure_debut']);
        $date_fin = new DateTime($slot['date'] . ' ' . $slot['heure_fin']);

        $conflicts = Reservation::VerifierDisponibilite($date_debut, $date_fin, intval($salle_id));

        if (empty($conflicts)) {
            // Calculer la durée en heures
            $interval = $date_debut->diff($date_fin);
            $duree_totale += $interval->h + ($interval->i / 60);

            // Insérer la réservation
            $reservation = new Reservation(0, Client::getClientByID($client_id), Salle::getSalleById($salle_id), $date_debut, $date_fin);
            $reservation->save();
        } else {
            echo "<div class='bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4'>La salle n'est pas disponible pour le créneau " . $date_debut->format('H:i') . " - " . $date_fin->format('H:i') . ".</div>";
        }
    }

    // Calculer le prix total
    $prix_heure = $salles[array_search($salle_id, array_column($salles, 'id'))]['price'];
    $prix_total = $duree_totale * $prix_heure;

    // Afficher la facture
    echo "<div class='bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4'>Réservation effectuée avec succès!</div>";
    echo "<div class='bg-white shadow-md rounded-lg p-6 mt-4'>
            <h2 class='text-xl font-bold mb-4'>Facture</h2>
            <p><strong>Durée totale :</strong> $duree_totale heures</p>
            <p><strong>Prix horaire :</strong> $prix_heure Dh/h</p>
            <p><strong>Prix total :</strong> $prix_total Dh</p>
          </div>";
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Réservation de salles</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
</head>
<body class="bg-gray-50 p-8">
    <div class="max-w-7xl mx-auto">
        <h1 class="text-3xl font-bold text-gray-800 mb-6">Réservation de salles</h1>

        <!-- Navigation entre les semaines -->
        <div class="flex justify-between items-center mb-6">
            <!-- Bouton "Semaine précédente" désactivé si la semaine précédente commence avant la date actuelle -->
            <?php
            $previousWeekStart = (clone $startOfWeek)->modify('-7 days');
            $isPreviousWeekAllowed = $previousWeekStart >= (new DateTime())->modify('today');
            ?>
            <a href="?week=<?= $selectedWeek - 1 ?>" class="bg-blue-500 text-white px-4 py-2 rounded-lg hover:bg-blue-600 transition duration-300 <?= !$isPreviousWeekAllowed ? 'pointer-events-none bg-gray-300' : '' ?>">
                <i class="fas fa-chevron-left"></i> Semaine précédente
            </a>
            <span class="text-lg font-semibold text-gray-700">
                Semaine du <?= $startOfWeek->format('d/m/Y') ?> au <?= $endOfWeek->format('d/m/Y') ?>
            </span>
            <a href="?week=<?= $selectedWeek + 1 ?>" class="bg-blue-500 text-white px-4 py-2 rounded-lg hover:bg-blue-600 transition duration-300">
                Semaine suivante <i class="fas fa-chevron-right"></i>
            </a>
        </div>

        <!-- Formulaire de réservation -->
        <form method="POST" class="mb-8">
            <label for="salle_id" class="block text-sm font-medium text-gray-700">Salle:</label>
            <select name="salle_id" id="salle_id" class="mt-1 block w-full p-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                <?php foreach ($salles as $salle): ?>
                    <option value="<?= $salle['id'] ?>"><?= $salle['nom'] ?> - <?= Type::getTypeById($salle['type_id'])->getNom()?> (<?= $salle['price'] ?> Dh/h)</option>
                <?php endforeach; ?>
            </select>

            <!-- Tableau des disponibilités -->
            <div class="mt-4 bg-white shadow-md rounded-lg overflow-hidden">
                <table class="min-w-full">
                    <thead class="bg-gray-100">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Heure</th>
                            <?php
                            for ($i = 0; $i < 7; $i++) {
                                $date = (clone $startOfWeek)->modify("+$i days");
                                echo "<th class='px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider'>" . $date->format('d/m/Y') . "</th>";
                            }
                            ?>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        <?php
                        for ($heure = 8; $heure <= 22; $heure++) {
                            echo "<tr>";
                            echo "<td class='px-6 py-4 text-sm text-gray-800'>$heure:00</td>";
                            for ($i = 0; $i < 7; $i++) {
                                $date = (clone $startOfWeek)->modify("+$i days")->format('Y-m-d');
                                $heure_debut = sprintf('%02d:00:00', $heure);
                                $heure_fin = sprintf('%02d:00:00', $heure + 1);

                                // Vérifier si le créneau est déjà réservé ou passé
                                $isPast = (new DateTime("$date $heure_debut")) < $now;
                                $stmt = $pdo->prepare("SELECT * FROM reservation WHERE salle_id = :salle_id AND dateDebut = :heure_debut AND dateFin = :heure_fin");
                                $stmt->execute([
                                    'salle_id' => $salles[0]['id'], // Par défaut, on vérifie pour la première salle
                                    'heure_debut' => "$date $heure_debut",
                                    'heure_fin' => "$date $heure_fin"
                                ]);
                                $reserved = $stmt->fetch();

                                $disabled = $reserved || $isPast ? 'disabled' : '';
                                echo "<td class='px-6 py-4 text-sm'>
                                        <input type='checkbox' name='slots[]' value='{\"date\":\"$date\", \"heure_debut\":\"$heure_debut\", \"heure_fin\":\"$heure_fin\"}' $disabled class='slot-checkbox p-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500'>
                                      </td>";
                            }
                            echo "</tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>

            <!-- Champ caché pour stocker les créneaux sélectionnés -->
            <input type="hidden" name="selected_slots" id="selected_slots">

            <button type="submit" class="mt-4 bg-blue-500 text-white px-4 py-2 rounded-md hover:bg-blue-600 transition duration-300">
                <i class="fas fa-calendar-check"></i> Réserver
            </button>
        </form>
    </div>

    <script>
        // Gestion des créneaux sélectionnés
        const checkboxes = document.querySelectorAll('.slot-checkbox');
        const selectedSlotsInput = document.getElementById('selected_slots');
        const selectedSlots = [];

        checkboxes.forEach(checkbox => {
            checkbox.addEventListener('change', () => {
                if (checkbox.checked) {
                    selectedSlots.push(JSON.parse(checkbox.value));
                } else {
                    const index = selectedSlots.findIndex(slot => slot.date === JSON.parse(checkbox.value).date && slot.heure_debut === JSON.parse(checkbox.value).heure_debut);
                    if (index !== -1) {
                        selectedSlots.splice(index, 1);
                    }
                }
                selectedSlotsInput.value = JSON.stringify(selectedSlots);
            });
        });
    </script>
</body>
</html>