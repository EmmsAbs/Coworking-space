<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($title) ? $title : 'Document'; ?></title>
    <script src="https://unpkg.com/@tailwindcss/browser@4"></script>
</head>
<?php
  // Get the current page URL (or a relevant identifier)
  $current_page = basename($_SERVER['PHP_SELF']); // e.g., equipement.php, salle_list.php

  // Function to check if a link should be active
  function is_active($page_name) {
    global $current_page;
    return ($current_page == $page_name) ? "bg-gray-700 text-white" : "text-gray-300 hover:bg-gray-700";
  }
?>
<body class="bg-gray-100 font-sans">
    <div class="min-h-screen flex">
        <!-- Sidebar -->
        <div class="hidden md:flex md:flex-shrink-0">
            <div class="flex flex-col w-64 bg-gray-800">
                <div class="flex items-center h-16 px-4 bg-gray-900">
                    <a href="../index.php" ><span class="text-xl font-bold text-white">CoworkingSpace</span> </a>
                </div>
                <div class="flex flex-col flex-1 overflow-y-auto">
                    <nav class="flex-1 px-2 py-4 space-y-2">
                        <a href="../pages/equipement.php" class="flex items-center px-4 py-2 text-gray-300 hover:bg-gray-700 rounded-lg <?php echo is_active("equipement.php"); ?>">
                            <i class="fas fa-calendar-plus mr-3"></i>
                            Équipements
                        </a>
                        <a href="../pages/salle_list.php" class="flex items-center px-4 py-2 text-gray-300 hover:bg-gray-700 rounded-lg <?php echo is_active("salle_list.php"); ?>">
                            <i class="fas fa-history mr-3"></i>
                            Salles
                        </a>
                        <a href="../pages/Gest_reservation.php" class="flex items-center px-4 py-2 text-gray-300 hover:bg-gray-700 rounded-lg <?php echo is_active("Gest_reservation.php"); ?>">
                            <i class="fas fa-user mr-3"></i>
                            Reservation
                        </a>
                        <a href="../pages/evenement_list.php" class="flex items-center px-4 py-2 text-gray-300 hover:bg-gray-700 rounded-lg <?php echo is_active("evenements.php"); ?>">
                            <i class="fas fa-calendar-alt mr-3"></i>
                            Évènements
                        </a>
                        <a href="../pages/Gest_utilisateurs.php" class="flex items-center px-4 py-2 text-gray-300 hover:bg-gray-700 rounded-lg <?php echo is_active("evenements.php"); ?>">
                            <i class="fas fa-calendar-alt mr-3"></i>
                            Clients
                        </a>
                    </nav>
                </div>
            </div>
        </div>
