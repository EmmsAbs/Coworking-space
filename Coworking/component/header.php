<?php
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($title) ? $title : 'Document'; ?></title>
    <script src="https://unpkg.com/@tailwindcss/browser@4"></script>
</head>
<body class="bg-gray-100">
    <div class="relative w-full h-full bg-cover bg-center"> 
        <!-- -[url('Img/Img13.jpg')] bg-cover bg-center"> -->
        <header class="">
            <nav class="flex items-center justify-between p-6 lg:px-8" aria-label="Global">
            <div class="flex lg:flex-1">
                <a href="../index.php" class="-m-1.5 p-1.5">
                <span class="sr-only">Your Company</span>
                <img class="h-8 w-auto" src="https://tailwindui.com/plus-assets/img/logos/mark.svg?color=indigo&shade=600" alt="">
                </a>
            </div>
            <div class="flex lg:hidden">
                <button type="button" class="-m-2.5 inline-flex items-center justify-center rounded-md p-2.5 text-gray-700">
                <span class="sr-only">Open main menu</span>
                <svg class="size-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true" data-slot="icon">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" />
                </svg>
                </button>
            </div>
            <div class="hidden lg:flex lg:gap-x-12">
                <a href="../pages/evenement_view.php" class="text-sm/6 font-semibold <?php echo ($title == 'Home') ? 'text-white' : 'text-gray-900'; ?>">Evènements</a>
                <a href="../pages/workspace.php" class="text-sm/6 font-semibold <?php echo ($title == 'Home') ? 'text-white' : 'text-gray-900'; ?>">Espaces de travail</a>
                <a href="#" class="text-sm/6 font-semibold <?php echo ($title == 'Home') ? 'text-white' : 'text-gray-900'; ?>">Contacts</a>
            </div>
            <div class="hidden lg:flex lg:flex-1 lg:justify-end">
            <?php if(isset($_SESSION['user_id'])): ?>
               <a href="../pages/mon_profil.php" class="text-sm/6 font-semibold mr-8 <?php echo ($title == 'Home') ? 'text-white' : 'text-gray-900'; ?>">
                  Mon compte
               </a>
            <?php endif; ?>
                <a href="<?php echo !isset($_SESSION['user_id']) ? htmlspecialchars("../pages/sign_in.php") : htmlspecialchars("../pages/logout.php");?>" class="text-sm/6 font-semibold <?php echo ($title == 'Home') ? 'text-white' : 'text-gray-900'; ?>"><?php echo !isset($_SESSION['user_id']) ? htmlspecialchars("Se connecter") : htmlspecialchars("Se deconnecter"); ?><span aria-hidden="true">&rarr;</span></a>
            </div>
            </nav>
            <!-- Mobile menu, show/hide based on menu open state. -->
            <div class="lg:hidden" role="dialog" aria-modal="true">
            <!-- Background backdrop, show/hide based on slide-over state. -->
            <div class="fixed inset-0 z-50"></div>
            <div class="fixed inset-y-0 right-0 z-50 w-full overflow-y-auto bg-white px-6 py-6 sm:max-w-sm sm:ring-1 sm:ring-gray-900/10">
                <div class="flex items-center justify-between">
                <a href="#" class="-m-1.5 p-1.5">
                    <span class="sr-only">Your Company</span>
                    <img class="h-8 w-auto" src="https://tailwindui.com/plus/img/logos/mark.svg?color=indigo&shade=600" alt="">
                </a>
                <button type="button" class="-m-2.5 rounded-md p-2.5 text-gray-700">
                    <span class="sr-only">Close menu</span>
                    <svg class="size-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true" data-slot="icon">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
                    </svg>
                </button>
                </div>
                <div class="mt-6 flow-root">
                <div class="-my-6 divide-y divide-gray-500/10">
                    <div class="space-y-2 py-6">
                    <a href="../pages/evenement_view.php" class="-mx-3 block rounded-lg px-3 py-2 text-base/7 font-semibold text-gray-900 hover:bg-gray-50">Evènements</a>
                    <a href="../pages/workspace.php" class="-mx-3 block rounded-lg px-3 py-2 text-base/7 font-semibold text-gray-900 hover:bg-gray-50">Espaces de travail</a>
                    <a href="#" class="-mx-3 block rounded-lg px-3 py-2 text-base/7 font-semibold text-gray-900 hover:bg-gray-50">Contacts</a>
                    </div>
                    <div class="py-6">
                    <a href="<?php echo !isset($_SESSION['user_id']) ? htmlspecialchars("../pages/sign_in.php") : htmlspecialchars("../pages/logout.php");?>" class="-mx-3 block rounded-lg px-3 py-2.5 text-base/7 font-semibold text-gray-900 hover:bg-gray-50"><?php echo !isset($_SESSION['user_id']) ? htmlspecialchars("Se connecter") : htmlspecialchars("Se deconnecter"); ?></a>
                    </div>
                </div>
                </div>
            </div>
            </div>
        </header>

