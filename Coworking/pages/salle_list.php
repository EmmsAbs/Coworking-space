<?php
    require_once '../classe/Equipement.php';
    require_once '../classe/Salle.php';
    require_once '../classe/Type.php';

    // Suppression d'une salle si l'ID est présent dans l'URL
    if (isset($_GET['id']) && is_numeric($_GET['id'])) {
        Salle::delete($_GET['id']);
        header("Location: {$_SERVER['PHP_SELF']}"); // Redirection après suppression
        exit();
    }

    $salles = Salle::getAllSalles()

?>

<?php
    $title = "Salles List";
    include("../component/header_gest.php");
?>
    <div class="container mx-auto px-6 py-10">
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-3xl font-semibold text-gray-800">Liste des Salles</h2>
                <a href="../pages/salle_add.php" class="bg-blue-500 text-white px-4 py-2 rounded-lg hover:bg-blue-600">Ajouter</a>
            </div>
            <!-- Liste des salles -->
            <div class="overflow-x-auto bg-white rounded-lg shadow-md">
                <table class="min-w-full table-auto divide-y divide-gray-200">
                    <thead class="bg-gray-100">
                        <tr>
                            <th class="px-6 py-3 text-left text-sm font-semibold text-gray-600">Nom</th>
                            <th class="px-6 py-3 text-left text-sm font-semibold text-gray-600">Type</th>
                            <th class="px-6 py-3 text-left text-sm font-semibold text-gray-600">Capacité</th>
                            <th class="px-6 py-3 text-left text-sm font-semibold text-gray-600">Prix Horaire</th>
                            <th class="px-6 py-3 text-left text-sm font-semibold text-gray-600">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                    <?php foreach ($salles as $salle) : ?>
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4"><?php echo htmlspecialchars($salle['nom']); ?></td>
                            <td class="px-6 py-4"><?php echo htmlspecialchars($salle['type_nom']); ?></td>
                            <td class="px-6 py-4"><?php echo htmlspecialchars($salle['capacite']); ?></td>
                            <td class="px-6 py-4"><?php echo htmlspecialchars($salle['price']); ?></td>
                            <td class="px-6 py-4 space-x-3">
                                <a href="../pages/salle_view.php?idv=<?= $salle['id'] ?>">🔍</a>
                                <a href="../pages/salle_update.php?idm=<?= $salle['id'] ?>" class="text-blue-500 hover:underline">📝</a>
                                <a href="../pages/salle_list.php?id=<?= $salle['id'] ?>" class="text-red-500 hover:underline" onclick="return confirm('Confirmer la suppression ?')">🗑️</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</body>
</html>