<?php
    require_once '../classe/Equipement.php';

    // Vérifier si un formulaire a été soumis
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        try{
            if (isset($_POST['manage_equipement'])) {
                $nom = trim($_POST['nom_equipement']);
                $res = false;
                if (!empty($nom)) {
                    if (isset($_POST['equipement_id']) && !empty($_POST['equipement_id'])) {
                        // Modification
                        $equipement_to_edit = Equipement::getEquipementById(intval($_POST['equipement_id']));
                        $equipement_to_edit->setNom($nom);
                        $res=$equipement_to_edit->update();
                    } else {
                        //Ajout
                        $equipement_to_save = new Equipement(0,$nom);
                        $res=$equipement_to_save->save();
                       
                    }
                    if($res){ 
                        echo "<script type='text/javascript'>
                            alert('Operation effectuée avec succès!.');
                            window.location.href = '../pages/equipement.php'; 
                        </script>";
                    }else {
                        echo "<script type='text/javascript'>
                            alert('Erreur lors de l\'opération. Veuillez réessayer !');
                            window.location.href = '../pages/equipement.php'; 
                        </script>";
                      exit;
                    }
    
                } 
            }
        }catch (Exception $e){ 
            echo "<script type='text/javascript'>
                alert('Erreur lors de l\'opération. Veuillez réessayer !');
                window.location.href = '../pages/equipement.php'; 
            </script>";
            exit;
        }
    }

    // Vérifier si on modifie un équipement
    $equipement_to_edit = null;
    if (isset($_GET['idm'])) {
        $equipement_to_edit = Equipement::getEquipementById(intval($_GET['idm']));
    }

    if (isset($_GET['id']) && is_numeric($_GET['id'])) {
        $res = Equipement::delete(intval($_GET['id']));
        if($res){
            echo "<script type='text/javascript'>
                alert('Équipement supprimer avec succes !');
                window.location.href = '../pages/equipement.php'; 
            </script>";
            // header("Location: {$_SERVER['PHP_SELF']}"); // Redirection après suppression
            exit;
        }else {
            echo "<script type='text/javascript'>
                alert('Erreur lors de l\'opération. Impossible de supprimer l\'equipement car il est présent dans une salle!');
                window.location.href = '../pages/equipement.php'; 
            </script>";
          exit;
        }
    }

    $equipements = Equipement::getAllEquipements();

?>

<?php
    $titre_page = "Equipement List";
    include("../component/header_gest.php");
?>
    <div class="container mx-auto px-6 py-10">
        <!-- Header -->
        <h1 class="text-4xl font-extrabold text-center text-gray-800 mb-10">Gestion des Équipements</h1>

         <!-- Formulaire de gestion des équipements -->
        <div class="bg-white p-8 rounded-lg shadow-md mt-10">
            <h2 class="text-2xl font-semibold mb-6 text-gray-700">Ajouter ou Modifier un Équipement</h2>
            <form action="" method="POST" class="space-y-6">
                <input type="hidden" name="equipement_id" value="<?= $equipement_to_edit ? $equipement_to_edit->getId(): '' ?>">
                <div>
                    <label for="nom_equipement" class="block text-sm font-medium text-gray-700">Nom de l'Équipement</label>
                    <input  value="<?= $equipement_to_edit ? $equipement_to_edit->getNom(): ''?>" type="text" id="nom_equipement" name="nom_equipement" required placeholder="Nom de l'équipement" class="w-full mt-1 p-3 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500">
                </div>

                <button type="submit" name="manage_equipement" class="bg-blue-600 hover:bg-blue-700 text-white py-2 px-6 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-300">
                    <?= $equipement_to_edit ? "Modifier" : "Enregistrer" ?>
                </button>
            </form>
        </div>

        <!-- Liste des équipements -->
        <h2 class="text-3xl font-semibold mb-6 text-gray-800">Liste des Équipements</h2>
        <div class="overflow-x-auto bg-white rounded-lg shadow-md">
            <table class="min-w-full table-auto divide-y divide-gray-200">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="px-6 py-3 text-left text-sm font-semibold text-gray-600">Nom de l'Équipement</th>
                        <th class="px-6 py-3 text-left text-sm font-semibold text-gray-600">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    <!-- Exemple dynamique des équipements -->
                    <?php foreach ($equipements as $equipement) : ?>
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4"><?php echo htmlspecialchars($equipement['nom']); ?></td>
                            <td class="px-6 py-4 space-x-2">
                                <a href="../pages/equipement.php?idm=<?= $equipement['id'] ?>" class="text-blue-500 hover:underline">Modifier</a>
                                <a href="../pages/equipement.php?id=<?= $equipement['id'] ?>" class="text-red-500 hover:underline" onclick="return confirm('Confirmer la suppression ?')">Supprimer</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>