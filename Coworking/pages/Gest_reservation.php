<?php
    session_start();
    if(isset($_SESSION['adm']) && !$_SESSION['adm']){
        header("Location: ../index.php");
        exit();
    }

    require_once '../classe/Database.php';
    require_once '../classe/Salle.php';
    require_once '../classe/Type.php';
    require_once '../classe/Reservation.php';
    require_once '../classe/Client.php';
    $pdo = Database::connect();
?>

<?php

$salles = Salle::getAllSalles();

// Récupérer la semaine sélectionnée (par défaut, la semaine actuelle)
$selectedWeek = isset($_GET['week']) ? (int)$_GET['week'] : 0;
$startOfWeek = (new DateTime())->modify("+$selectedWeek weeks")->modify('today'); // Début à partir d'aujourd'hui
$endOfWeek = (clone $startOfWeek)->modify('+6 days'); // 7 jours à partir d'aujourd'hui

// Date et heure actuelles pour désactiver les heures passées
$now = new DateTime();

// Récupérer les filtres du formulaire
$filtre_annule = isset($_GET['annule']) ? $_GET['annule'] : null;
$filtre_salle_id = isset($_GET['salle_id']) ? (int)$_GET['salle_id'] : null;
$filtre_username = isset($_GET['username']) ? $_GET['username'] : null;

// Construire la requête SQL en fonction des filtres
$sql = "
    SELECT reservation.id, salle.nom AS salle_nom, reservation.dateDebut, reservation.dateFin, reservation.client_id, salle.price, reservation.annule, utilisateur.username
    FROM reservation
    JOIN salle ON reservation.salle_id = salle.id
    JOIN client ON reservation.client_id = client.id
    JOIN utilisateur ON client.id = utilisateur.id
    WHERE reservation.dateDebut >= :startOfWeek AND reservation.dateDebut <= :endOfWeek
";
// WHERE 1=1

$params = [];
$params['startOfWeek'] = $startOfWeek->format('Y-m-d 00:00:00');
$params['endOfWeek'] = $endOfWeek->format('Y-m-d 23:59:59');

if ($filtre_annule !== null) {
    $sql .= " AND reservation.annule = :annule";
    $params['annule'] = $filtre_annule;
}

if ($filtre_salle_id) {
    $sql .= " AND reservation.salle_id = :salle_id";
    $params['salle_id'] = $filtre_salle_id;
}

if ($filtre_username) {
    $sql .= " AND utilisateur.username LIKE :username";
    $params['username'] = "$filtre_username%";
}

$sql .= " ORDER BY reservation.dateDebut";

$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$reservations = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Traitement de la modification ou de l'annulation d'une réservation
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['modifier'])) {
        $reservation_id = $_POST['reservation_id'];
        $date_debut = new DateTime($_POST['date_debut']);
        $date_fin = new DateTime($_POST['date_fin']);
        $now = new DateTime();

        // Vérifier si les heures sont passées
        if ($date_debut < $now) {
            echo "<div class='bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4'>Impossible de modifier une réservation passée.</div>";
        } elseif ($date_debut >= $date_fin) {
            echo "<div class='bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4'>La date de début ne peut pas être supérieure ou égale à la date de fin.</div>";
        } else {
            // Vérifier la disponibilité
            $stmt = $pdo->prepare("
                SELECT * FROM reservation
                WHERE salle_id = (SELECT salle_id FROM reservation WHERE id = :reservation_id)
                AND ((dateDebut < :date_fin) AND (dateFin > :date_debut))
                AND id != :reservation_id
            ");
            $stmt->execute([
                'reservation_id' => $reservation_id,
                'date_debut' => $date_debut->format('Y-m-d H:i:s'),
                'date_fin' => $date_fin->format('Y-m-d H:i:s')
            ]);
            $conflicts = $stmt->fetchAll();

            if (empty($conflicts)) {
                // Calculer la durée en heures
                $interval = $date_debut->diff($date_fin);
                $duree_totale = $interval->h + ($interval->i / 60);

                // Récupérer le prix horaire de la salle
                $stmt = $pdo->prepare("SELECT price FROM salle WHERE id = (SELECT salle_id FROM reservation WHERE id = :reservation_id)");
                $stmt->execute(['reservation_id' => $reservation_id]);
                $prix_heure = $stmt->fetchColumn();

                // Calculer le prix total
                $prix_total = $duree_totale * $prix_heure;

                // Mettre à jour la réservation
                $stmt = $pdo->prepare("UPDATE reservation SET dateDebut = :date_debut, dateFin = :date_fin WHERE id = :reservation_id");
                $stmt->execute([
                    'date_debut' => $date_debut->format('Y-m-d H:i:s'),
                    'date_fin' => $date_fin->format('Y-m-d H:i:s'),
                    'reservation_id' => $reservation_id
                ]);

                // Afficher la facture
                echo "<div class='bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4'>Réservation modifiée avec succès!</div>";
                echo "<div class='bg-white shadow-md rounded-lg p-6 mt-4'>
                        <h2 class='text-xl font-bold mb-4'>Facture</h2>
                        <p><strong>Durée totale :</strong> $duree_totale heures</p>
                        <p><strong>Prix horaire :</strong> $prix_heure €/h</p>
                        <p><strong>Prix total :</strong> $prix_total €</p>
                      </div>";
            } else {
                echo "<div class='bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4'>La salle n'est pas disponible à cette date et heure.</div>";
            }
        }
    } elseif (isset($_POST['annuler'])) {
        $reservation_id = $_POST['reservation_id'];
        $stmt = $pdo->prepare("UPDATE reservation SET annule = TRUE WHERE id = :reservation_id");
        $stmt->execute(['reservation_id' => $reservation_id]);
        echo "<div class='bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4'>Réservation annulée avec succès!</div>";
        header('Location: #');
        exit;
    }
}
?>

<!-- <!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Gestion des réservations</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
</head>
<body class="bg-gray-50 p-8"> -->
<?php
    $title = "Add Salle";
    include("../component/header_gest.php");
?>
    <div class="max-w-7xl mx-auto">
        <h1 class="text-3xl font-bold text-gray-800 mb-6">Gestion des réservations</h1>

        <!-- Navigation entre les semaines -->
        <div class="flex justify-between items-center mb-6">
            <!-- Bouton "Semaine précédente" désactivé si la semaine précédente commence avant la date actuelle -->
            <?php
            $previousWeekStart = (clone $startOfWeek)->modify('-7 days');
            #$isPreviousWeekAllowed = $previousWeekStart >= (new DateTime())->modify('today');
            ?>
            <a href="?week=<?= $selectedWeek - 1 ?>" class="bg-blue-500 text-white px-4 py-2 rounded-lg hover:bg-blue-600 transition duration-300">
                <i class="fas fa-chevron-left"></i> Semaine précédente
            </a>
            <span class="text-lg font-semibold text-gray-700">
                Semaine du <?= $startOfWeek->format('d/m/Y') ?> au <?= $endOfWeek->format('d/m/Y') ?>
            </span>
            <a href="?week=<?= $selectedWeek + 1 ?>" class="bg-blue-500 text-white px-4 py-2 rounded-lg hover:bg-blue-600 transition duration-300">
                Semaine suivante <i class="fas fa-chevron-right"></i>
            </a>
        </div>

        <!-- Formulaire de filtrage -->
        <form method="GET" class="mb-6 bg-white shadow-md rounded-lg p-6">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <!-- Filtre par statut d'annulation -->
                <div>
                    <label for="annule" class="block text-sm font-medium text-gray-700">Statut d'annulation :</label>
                    <select name="annule" id="annule" class="mt-1 block w-full p-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">Tous</option>
                        <option value="0" <?= $filtre_annule === '0' ? 'selected' : '' ?>>Non annulée</option>
                        <option value="1" <?= $filtre_annule === '1' ? 'selected' : '' ?>>Annulée</option>
                    </select>
                </div>

                <!-- Filtre par salle -->
                <div>
                    <label for="salle_id" class="block text-sm font-medium text-gray-700">Salle :</label>
                    <select name="salle_id" id="salle_id" class="mt-1 block w-full p-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">Toutes les salles</option>
                        <?php foreach ($salles as $salle): ?>
                            <option value="<?= $salle['id'] ?>" <?= $filtre_salle_id === $salle['id'] ? 'selected' : '' ?>><?= $salle['nom'] ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <!-- Filtre par utilisateur -->
                <div>
                    <label for="username" class="block text-sm font-medium text-gray-700">Utilisateur :</label>
                    <input type="text" name="username" id="username" value="<?= htmlspecialchars($filtre_username ?? '') ?>" class="mt-1 block w-full p-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Rechercher par nom d'utilisateur">
                </div>
            </div>
            <button type="submit" class="mt-4 bg-blue-500 text-white px-4 py-2 rounded-md hover:bg-blue-600 transition duration-300">
                <i class="fas fa-filter"></i> Filtrer
            </button>
        </form>

        <!-- Tableau des réservations -->
        <div class="bg-white shadow-md rounded-lg overflow-hidden">
            <table class="min-w-full">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Salle</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date et heure de début</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date et heure de fin</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Utilisateur</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Statut</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    <?php foreach ($reservations as $reservation): ?>
                        <?php
                        $date_debut = new DateTime($reservation['dateDebut']);
                        $date_fin = new DateTime($reservation['dateFin']);
                        $isPast = $date_debut < new DateTime();
                        ?>
                        <tr>
                            <td class="px-6 py-4 text-sm text-gray-800"><?= $reservation['salle_nom'] ?></td>
                            <td class="px-6 py-4 text-sm text-gray-800"><?= $date_debut->format('d/m/Y H:i') ?></td>
                            <td class="px-6 py-4 text-sm text-gray-800"><?= $date_fin->format('d/m/Y H:i') ?></td>
                            <td class="px-6 py-4 text-sm text-gray-800"><?= $reservation['username'] ?></td>
                            <td class="px-6 py-4 text-sm text-gray-800">
                            <?php
                                $now = new DateTime();
                                if ($reservation['annule']) {
                                    echo '<span class="text-red-600">Annulée</span>';
                                } elseif ($date_fin < $now) {
                                    echo '<span class="text-gray-600">Inactive</span>';
                                } else {
                                    echo '<span class="text-green-600">Active</span>';
                                }
                            ?>
                            </td>
                            <td class="px-6 py-4 text-sm">
                                <!-- Formulaire pour modifier une réservation -->
                                <form method="POST" class="inline-block">
                                    <input type="hidden" name="reservation_id" value="<?= $reservation['id'] ?>">
                                    <input type="datetime-local" name="date_debut" value="<?= $date_debut->format('Y-m-d\TH:i') ?>" class="p-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" <?= $isPast || $reservation['annule'] ? 'disabled' : '' ?>>
                                    <input type="datetime-local" name="date_fin" value="<?= $date_fin->format('Y-m-d\TH:i') ?>" class="p-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" <?= $isPast || $reservation['annule'] ? 'disabled' : '' ?>>
                                    <button type="submit" name="modifier" class="bg-blue-500 text-white px-4 py-2 rounded-md hover:bg-blue-600 transition duration-300" <?= $isPast || $reservation['annule'] ? 'disabled' : '' ?>>
                                        <i class="fas fa-edit"></i> Modifier
                                    </button>
                                </form>
                                <!-- Formulaire pour annuler une réservation -->
                                <form method="POST" class="inline-block">
                                    <input type="hidden" name="reservation_id" value="<?= $reservation['id'] ?>">
                                    <button type="submit" name="annuler" class="bg-red-500 text-white px-4 py-2 rounded-md hover:bg-red-600 transition duration-300" <?= $reservation['annule'] ? 'disabled' : '' ?>>
                                        <i class="fas fa-trash"></i> Annuler
                                    </button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>
