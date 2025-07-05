<?php
    require_once '../classe/Equipement.php';
    require_once '../classe/Salle.php';
    require_once '../classe/Type.php';

     // Vérifier si on modifie un équipement
    $salle_to_edit = null;
    if (isset($_GET['idm'])) {
        $salle_to_edit = Salle::getSalleById(intval($_GET['idm']));
    }

    if(!$salle_to_edit && $_SERVER['REQUEST_METHOD'] !== 'POST'){
       header('Location: ../pages/salle_list.php');
       exit;
    }

    $equipements = Equipement::getAllEquipements();
    //$selectedEquipementIds = array_map(function($eq) { return $eq->getId(); }, $salle_to_edit->getEquipements());
    $selectedEquipementIds = $salle_to_edit ? array_map(function($eq) { return $eq->getId(); }, $salle_to_edit->getEquipements()) : [];
    $types = Type::getAllTypes();

    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['salle_id'])) {
        $salle_to_edit = Salle::getSalleById(intval($_POST['salle_id']));
        $nom = $_POST['nom'];
        $description = $_POST['description'];
        $capacite = $_POST['capacite'];
        $price = $_POST['price'];
        $typeId = $_POST['type'];
        $equipementIds = isset($_POST['equipements']) ? array_filter(explode(',', $_POST['equipements']), 'is_numeric') : [];
      
        try {
            // Récupérer le type de salle
            $type = Type::getTypeById($typeId);
            if($type){
                if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
                    unlink($salle_to_edit->getImage());
                    // Récupérer les informations du fichier téléchargé
                    $imageTmpPath = $_FILES['image']['tmp_name'];
                    $imageExtension = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
                    $newImageName = strtolower(str_replace(' ', '_', $nom)) . '.' . $imageExtension;
                    // Spécifier le répertoire où les images seront stockées
                    $uploadDir = '../Img';
                    if (!is_dir($uploadDir)) {
                        mkdir($uploadDir, 0777, true); // Créer le dossier si nécessaire
                    }
                    // Définir le chemin complet du fichier
                    $newImagePath = $uploadDir .'/' .$newImageName;
                    if (move_uploaded_file($imageTmpPath, $newImagePath)) {
                        #modifier image
                        $salle_to_edit -> setImage($newImagePath);
                   
                    }  
                }
                $salle_to_edit -> setNom($nom);
                $salle_to_edit -> setDescription($description);
                $salle_to_edit -> setCapacite($capacite);
                $salle_to_edit -> setPrice($price);
                $salle_to_edit -> setType($type);
                $salle_to_edit -> clearEquipements();
                
                // Associer les équipements à la salle
                foreach ($equipementIds as $equipementId) {
                    $equipement  = Equipement::getEquipementById(intval($equipementId));
                    if ($equipement) {
                        $salle_to_edit->ajouterEquipement($equipement);
                    }
                }
                $res = $salle_to_edit->update();
                if($res){
                    echo "<script type='text/javascript'>
                        alert('Salle modifiée avec succès!');
                        window.location.href = '../pages/salle_list.php'; 
                    </script>";
                    exit;
                }else{
                    echo "<script type='text/javascript'>
                    alert('Erreur lors de l'ajout de la salle. Veuillez réessayer !');
                    window.location.href = '../pages/salle_update.php'; 
                    </script>";
                    exit;
                }
            }
        }catch (Exception $e) {
          echo "<script type='text/javascript'>
            alert('Erreur lors de l'ajout de la salle. Veuillez réessayer !');
            window.location.href = '../pages/salle_update.php'; 
          </script>";
          exit;
        }
    }
?>

<script>
    function moveSelected(sourceId, targetId) {
        let source = document.getElementById(sourceId);
        let target = document.getElementById(targetId);
        let selectedOptions = Array.from(source.selectedOptions);
        
        selectedOptions.forEach(option => {
            target.appendChild(option);
        });
        updateHiddenInput();
    }

    function updateHiddenInput() {
        let hiddenInput = document.getElementById("equipements-hidden");
        let selectedItems = document.querySelectorAll("#selected-equipements option");
        let values = Array.from(selectedItems).map(item => item.value);
        hiddenInput.value = values.join(",");
    }
</script>

<?php
    $titre_page = "update Salle";
    include("../component/header_gest.php");
?>
    <div class="container mx-auto px-6 py-10">
        <!-- Header -->
        <h1 class="text-4xl font-extrabold text-center text-gray-800 mb-10">Modifier une salle</h1>

        <!-- Formulaire d'ajout de salle -->
        <div class="bg-white p-8 rounded-lg shadow-md mb-10">
            <h2 class="text-2xl font-semibold mb-6 text-gray-700">Salle</h2>
            <form action="salle_update.php" method="POST" class="space-y-6" enctype="multipart/form-data">
                <input type="hidden" name="salle_id" value="<?= $salle_to_edit ? $salle_to_edit->getId() : '' ?>">

                <div>
                    <label for="nom" class="block text-sm font-medium text-gray-700">Nom de la Salle</label>
                    <input value="<?= $salle_to_edit ? $salle_to_edit->getNom():''?>" type="text" id="nom" name="nom" required placeholder="Salle A" class="w-full mt-1 p-3 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500">
                </div>

                <div>
                    <label for="type" class="block text-sm font-medium text-gray-700">Type de salle</label>
                    <select name="type" id="type" required class="w-full mt-1 p-3 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500">
                        <?php foreach ($types as $type): ?>
                            <option value="<?php echo $type['id']; ?>"<?php echo ($type['id'] == $salle_to_edit->getType()->getId()) ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($type['nom']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div>
                    <label for="description" class="block text-sm font-medium text-gray-700">Description</label>
                    <textarea id="description" name="description" class="w-full mt-1 p-3 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500" placeholder="Décrivez la salle"><?= $salle_to_edit ? $salle_to_edit->getDescription():''?></textarea>
                </div>

                <div>
                    <label for="capacite" class="block text-sm font-medium text-gray-700">Capacité</label>
                    <input value="<?= $salle_to_edit ? $salle_to_edit->getCapacite():''?>" type="number" id="capacite" name="capacite" min ="0" step="10" required placeholder="50" class="w-full mt-1 p-3 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500">
                </div>

                <div>
                    <label for="price" class="block text-sm font-medium text-gray-700">Prix horaire (DH)</label>
                    <input value="<?= $salle_to_edit ? $salle_to_edit->getPrice():''?>" type="number" id="price" name="price" step="25" min ="0.0" required placeholder="Entrez le prix" class="w-full mt-1 p-3 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500">
                </div>

                <div class="flex space-x-4 items-center">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Équipements Disponibles</label>
                        <select id="available-equipements" multiple size="6" class="w-full p-3 border border-gray-300 rounded-md">
                            <?php foreach ($equipements as $equipement): ?>
                                <?php if (!in_array($equipement['id'], $selectedEquipementIds)): ?>
                                    <option value="<?= htmlspecialchars($equipement['id']) ?>">
                                        <?= htmlspecialchars($equipement['nom']) ?>
                                    </option>
                                <?php endif; ?>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    
                    <div class="flex flex-col space-y-2">
                        <button type="button" onclick="moveSelected('available-equipements', 'selected-equipements')" class="bg-blue-500 hover:bg-blue-700 text-white px-4 py-2 rounded">→</button>
                        <button type="button" onclick="moveSelected('selected-equipements', 'available-equipements')" class="bg-red-500 hover:bg-red-700 text-white px-4 py-2 rounded">←</button>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Équipements Sélectionnés</label>
                        <select id="selected-equipements" multiple size="6" class="w-full p-3 border border-gray-300 rounded-md">
                            <?php foreach ($salle_to_edit->getEquipements() as $equipement): ?>
                                <option value="<?= $equipement->getId() ?>" selected>
                                    <?= htmlspecialchars($equipement->getNom()) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                <input type="hidden" id="equipements-hidden" name="equipements">

                <div>
                    <label for="image" class="block text-sm font-medium text-gray-700">Image de la Salle</label>
                    <input type="file" id="image" name="image" accept="image/*" class="w-full mt-1 p-3 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500">
                    <img src="<?= $salle_to_edit ? $salle_to_edit->getImage():''?>" width="200" height="200"> 
                </div>
                


                <button type="submit" name="update_salle" class="bg-blue-600 hover:bg-blue-700 text-white py-2 px-6 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-300">
                    Modifier la Salle
                </button>
            </form>
        </div>
    </div>
</body>
</html>