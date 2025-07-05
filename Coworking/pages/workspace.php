<?php
    require_once '../classe/Salle.php';

    $salles = Salle::getAllSalles();
?>



<?php
    $title = "Espace de travail";
    include("../component/header.php"); 
?>

<div class="bg-gray-100 max-w-7xl w-full mx-auto">
    <h1 class="text-4xl text-gray-800 text-center mb-10 drop-shadow-md">Les Offres du moment</h1>
        <!-- Contenu Principal -->
        <main class="container mx-auto p-6">
            <section class="grid grid-cols-3 gap-6">
                <?php foreach ($salles as $salle) : ?>
                    <div class="bg-white rounded-xl shadow-elegant overflow-hidden transform transition duration-300 hover:shadow-hover-lift hover:-translate-y-2 hover:perspective-hover">
                        <img src="<?php echo htmlspecialchars($salle['image']); ?>" alt="" class="w-full h-56 object-cover">
                        <div class="p-5">
                            <h3 class="text-xl font-bold mb-2"><?php echo htmlspecialchars($salle['nom']); ?></h3>
                            <div class="flex justify-between items-center">
                                <div class="text-yellow-500">★★★★☆</div>
                                <span class="text-green-600 font-semibold"><?php echo htmlspecialchars($salle['price']);?>DH/ heure</span>
                            </div>
                            <p class="mt-3 text-gray-600"><?php echo htmlspecialchars($salle['type_nom']);?></p>
                            <a href="../pages/salle_details.php?idd=<?= $salle['id']?>"><button class="mt-4 w-full bg-blue-600 text-white py-2 rounded-lg hover:bg-blue-700 transition">Détails</button></a>
                        </div>
                    </div>
                <?php endforeach; ?>
            </section>
        </main>
</div>

</body>
    <?php
        include("../component/footer.php"); 
    ?>
</html>