<?php

session_start();

require_once '../classe/Utilisateur.php';

function Connect() {
  if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Récupérer les données du formulaire
    $usernameOrEmail = $_POST['email'];
    $password = $_POST['password'];
    $connet = Utilisateur::login($usernameOrEmail,$password);
    
    if ($connet){
      if(isset($_SESSION['adm'])){
        if(!$_SESSION['adm']){
          echo "<script type='text/javascript'>
            window.location.href = '../pages/reservation.php'; 
          </script>";
          exit;
        }else{
          echo "<script type='text/javascript'>
            window.location.href = '../pages/Gest_reservation.php'; 
          </script>";
          exit;
        }
      }
    }else {
      echo "<script type='text/javascript'>
        alert('Identifiants incorrect. Veuillez réessayer !');
        window.location.href = '../pages/sign_in.php'; 
      </script>";
      exit;
    } 
  
  }
}
Connect();
?>

<?php
    $title = "Se connecter";
    include("../component/header.php");
?>

<!DOCTYPE html>
<html lang="en" class="h-full bg-gray-100">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <script src="https://unpkg.com/@tailwindcss/browser@4"></script>
</head>

<body class="h-full">
    <div class="flex min-h-full flex-col justify-center px-6 py-12 lg:px-8">
        <div class="sm:mx-auto sm:w-full sm:max-w-sm">
            <img class="mx-auto h-10 w-auto" src="https://tailwindui.com/plus-assets/img/logos/mark.svg?color=indigo&shade=600"
                alt="Your Company">
            <h2 class="mt-10 text-center text-2xl/9 font-bold tracking-tight text-gray-900">Accedez à son compte</h2>
        </div>

        <div class="mt-10 sm:mx-auto sm:w-full sm:max-w-sm">
            <form class="space-y-6" action="sign_in.php" method="POST">
                <div>
                    <label for="email" class="block text-sm/6 font-medium text-gray-900">Email ou nom
                        d'utilisateur</label>
                    <div class="mt-2">
                        <input name="email" id="email" autocomplete="email" required
                            class="block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm/6">
                    </div>
                </div>

                <div>
                    <div class="flex items-center justify-between">
                        <label for="password" class="block text-sm/6 font-medium text-gray-900">Mot de Passe</label>
                        <div class="text-sm">
                            <a href="#" class="font-semibold text-indigo-600 hover:text-indigo-500">Mot de passe
                                oublié?</a>
                        </div>
                    </div>
                    <div class="mt-2">
                        <input type="password" name="password" id="password" autocomplete="current-password" required
                            class="block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm/6">
                    </div>
                </div>

                <div>
                    <button type="submit"
                        class="flex w-full justify-center rounded-md bg-indigo-600 px-3 py-1.5 text-sm/6 font-semibold text-white shadow-xs hover:bg-indigo-500 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">Se
                        connecter</button>
                </div>
            </form>
        </div>
    </div>
</body>
</html>