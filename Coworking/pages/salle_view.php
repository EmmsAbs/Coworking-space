<?php
    require_once '../classe/Equipement.php';
    require_once '../classe/Salle.php';
    require_once '../classe/Type.php';
  
    // Vérifier si on modifie un équipement
    $salle = null;
    if (isset($_GET['idv'])) {
          $salle = Salle::getSalleById(intval($_GET['idv']));
    }

?>


<?php
    $title = "Salle View";
    include("../component/header_gest.php");
?>
<div class="bg-gradient-to-r mx-auto from-blue-50 to-gray-100 min-h-screen flex items-center justify-center p-6">
    <div class="bg-white rounded-2xl shadow-lg w-full max-w-3xl p-8">
        <h1 class="text-4xl font-extrabold text-gray-800 mb-6 text-center">Présentation de la Salle</h1>
        <h2 class="text-2xl font-semibold text-gray-700 mb-6 text-center"><?= $salle ? htmlspecialchars($salle->getNom()):''?></h2>

        <!-- Image de la salle -->
        <img src="<?= $salle ? htmlspecialchars($salle->getImage()):''?>" alt="Photo de la salle" class="rounded-xl w-3/4 mx-auto object-cover mb-6">

        <!-- Informations sur la salle -->
        <div class="space-y-4">
            <h2 class="text-2xl font-semibold text-gray-700"><?= $salle ? htmlspecialchars($salle->getType()->getNom()):''?></h2>
            <p class="text-gray-600 text-md">Capacité : <strong><?= $salle ? htmlspecialchars($salle->getCapacite()):''?> personnes</strong></p>
            <p class="text-gray-600 text-md">Prix à l'heure : <strong><?= $salle ? htmlspecialchars($salle->getPrice()):''?> DH</strong></p>
            <p class="text-gray-600 text-md">Description : <?= $salle ? htmlspecialchars($salle->getDescription()):''?></p>
            <p class="text-gray-600 text-md">Équipements : 
            <?php if (empty($salle->getEquipements())): ?><p>Aucun équipement disponible.</p>
                <?php else: ?>
                    <?php foreach ($salle->getEquipements() as $equipement): ?>
                        <?php echo htmlspecialchars($equipement->getNom().', '); ?>
                     <?php endforeach; ?> 
                <?php endif; ?>
            </p>
        </div>

        <!-- Boutons d'action -->
        <div class="text-center mt-8">
            <a href="../pages/salle_list.php"><button class="bg-gray-500 hover:bg-gray-600 center text-white py-3 px-6 rounded-xl focus:outline-none focus:ring-4 focus:ring-gray-300 font-semibold" inline-block>
                Retour
            </button></a>
        </div>
    </div>
</body>
</html>