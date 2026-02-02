<?php
use Models\Tvoyage;
use Models\Tbillet;
session_start();

// Rediriger si pas connecté
if (!isset($_SESSION['user_email'])) {
    header('Location: index.php?controller=authentification');
    exit;
}

// Récupérer le code du voyage transmis
$voyage_code = null;
if (isset($_GET['code'])) {
    $voyage_code = $_GET['code'];
} elseif (isset($_POST['code'])) {
    $voyage_code = $_POST['code'];
}

if (!$voyage_code) {
    echo '<div class="bg-yellow-100 border border-yellow-400 text-yellow-700 px-4 py-3 rounded relative mb-4 text-center" role="alert">';
    echo 'Veuillez d’abord rechercher un trajet et cliquer sur "Réserver" pour accéder à cette page.';
    echo '</div>';
    echo '<div class="text-center mt-6"><a href="index.php?controller=rech_trajet" class="text-blue-600 hover:text-blue-500 font-medium">Rechercher un trajet</a></div>';
    exit;
}

// Vérifier que le code de voyage existe en base
$voyage = \Models\Tvoyage::getVoyageByCode($voyage_code);
if (!$voyage) {
    echo '<div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4 text-center" role="alert">';
    echo 'Le code de voyage fourni est invalide. Veuillez réessayer.';
    echo '</div>';
    echo '<div class="text-center mt-6"><a href="index.php?controller=rech_trajet" class="text-blue-600 hover:text-blue-500 font-medium">Rechercher un trajet</a></div>';
    exit;
}

if (isset($_POST['places'], $_POST['date'], $_POST['code'])) {
    $places = (int)$_POST['places'];
    $date = $_POST['date'];
    $email = $_SESSION['user_email'];
    // Vérifier si l'utilisateur a déjà réservé ce voyage à cette date
    if (\Models\Tbillet::hasReservation($voyage_code, $date, $email)) {
        echo '<div class="bg-yellow-100 border border-yellow-400 text-yellow-700 px-4 py-3 rounded relative mb-4 text-center" role="alert">';
        echo 'Vous avez déjà réservé ce voyage à cette date.';
        echo '</div>';
        echo '<div class="text-center mt-6"><a href="index.php?controller=rech_trajet" class="text-blue-600 hover:text-blue-500 font-medium">Rechercher un autre trajet</a></div>';
        exit;
    }
    // Enregistrement réel de la réservation : création des billets
    for ($i = 0; $i < $places; $i++) {
        \Models\Tbillet::savebillet($voyage_code, $date, $email);
    }
    // Redirection vers la page de paiement avec toutes les infos nécessaires
    header('Location: index.php?controller=paiement&code=' . urlencode($voyage_code) . '&date=' . urlencode($date) . '&places=' . urlencode($places) . '&email=' . urlencode($email));
    exit;
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Réservation - ONCF</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50">
    <!-- Navigation -->
    <nav class="bg-blue-900 text-white shadow-lg">
        <div class="container mx-auto px-4 py-4">
            <div class="flex items-center justify-between">
                <a href="index.php" class="flex items-center space-x-2">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"></path>
                    </svg>
                    <h1 class="text-2xl font-bold">ONCF</h1>
                </a>
                <div class="flex items-center space-x-4">
                    <?php if (isset($_SESSION['user_email'])) { ?>
                        <span class="text-green-200">Connecté : <?= htmlspecialchars($_SESSION['user_email']) ?></span>
                        <a href="index.php?controller=authentification&action=logout" class="hover:text-red-300 transition">Déconnexion</a>
                    <?php } else { ?>
                        <a href="index.php?controller=reservation" class="hover:text-blue-200 transition">Réserver</a>
                        <a href="index.php?controller=authentification" class="hover:text-blue-200 transition">Connexion</a>
                        <a href="index.php?controller=inscription" class="hover:text-blue-200 transition">Inscription</a>
                    <?php } ?>
                </div>
            </div>
        </div>
    </nav>
    <div class="min-h-screen flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
        <div class="max-w-md w-full space-y-8">
            <div>
                <h2 class="mt-6 text-center text-3xl font-extrabold text-gray-900">
                    Réserver un billet
                </h2>
                <p class="mt-2 text-center text-sm text-gray-600">
                    Remplissez le formulaire pour réserver votre trajet.
                </p>
            </div>
            <form class="mt-8 space-y-6 bg-white p-8 rounded-lg shadow-lg" action="index.php?controller=reservation&code=<?= htmlspecialchars($voyage_code) ?>" method="POST">
                <input type="hidden" name="code" value="<?= htmlspecialchars($voyage_code) ?>">
                <div class="rounded-md shadow-sm -space-y-px">
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Code du voyage</label>
                        <input type="text" value="<?= htmlspecialchars($voyage_code) ?>" disabled class="appearance-none rounded-md relative block w-full px-3 py-2 border border-gray-300 bg-gray-100 text-gray-900 sm:text-sm">
                    </div>
                    <!-- Ajoutez ici d'autres infos du voyage si besoin -->
                    <div class="mb-4">
                        <label for="date" class="block text-sm font-medium text-gray-700 mb-1">Date du voyage</label>
                        <input id="date" name="date" type="date" required class="appearance-none rounded-md relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 focus:outline-none focus:ring-blue-500 focus:border-blue-500 focus:z-10 sm:text-sm">
                    </div>
                    <div class="mb-4">
                        <label for="places" class="block text-sm font-medium text-gray-700 mb-1">Nombre de places</label>
                        <input id="places" name="places" type="number" min="1" max="10" required class="appearance-none rounded-md relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 focus:outline-none focus:ring-blue-500 focus:border-blue-500 focus:z-10 sm:text-sm" placeholder="1">
                    </div>
                </div>
                <div>
                    <button type="submit" class="group relative w-full flex justify-center py-2 px-4 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        Réserver
                    </button>
                    <button type="reset" class="mt-3 group relative w-full flex justify-center py-2 px-4 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        Annuler
                    </button>
                </div>
            </form>
            <div class="text-center mt-6">
                <a href="index.php?controller=authentification" class="text-blue-600 hover:text-blue-500 font-medium">Connexion</a>
                <span class="mx-2 text-gray-400">|</span>
                <a href="index.php?controller=inscription" class="text-green-600 hover:text-green-500 font-medium">Inscription</a>
            </div>
        </div>
    </div>
</body>
</html>
