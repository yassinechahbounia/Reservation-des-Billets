<?php
// Autoloader simple pour charger les classes du dossier models et controllers
spl_autoload_register(function ($class) {
    $prefixes = [
        'Models\\' => __DIR__ . '/../models/',
        'Controllers\\' => __DIR__ . '/../controllers/'
    ];
    foreach ($prefixes as $prefix => $base_dir) {
        $len = strlen($prefix);
        if (strncmp($prefix, $class, $len) === 0) {
            $relative_class = substr($class, $len);
            $file = $base_dir . $relative_class . '.php';
            if (file_exists($file)) {
                require_once $file;
                return;
            }
        }
    }
});

// Redirection automatique des anciennes routes directes vers le routage MVC
$scriptName = basename(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));
$legacyViews = [
    'reservation.php' => 'reservation',
    'authentification.php' => 'authentification',
    'paiement.php' => 'paiement',
    'inscription.php' => 'inscription',
    'facture.php' => 'facture',
    'pdf.php' => 'pdf',
    'rech_trajet.php' => 'rech_trajet',
];
if (isset($legacyViews[$scriptName])) {
    header('Location: /index.php?controller=' . $legacyViews[$scriptName]);
    exit;
}

// Routage MVC
$controller = isset($_GET['controller']) ? $_GET['controller'] : null;
$action = isset($_GET['action']) ? $_GET['action'] : 'index';
if ($controller) {
    $controllerClass = 'Controllers\\' . ucfirst($controller) . 'Controller';
    if (class_exists($controllerClass)) {
        $ctrl = new $controllerClass();
        if (method_exists($ctrl, $action)) {
            $ctrl->$action();
            exit;
        } else {
            echo "Action non trouvée.";
            exit;
        }
    } else {
        echo "Contrôleur non trouvé.";
        exit;
    }
}
// Si aucun contrôleur, afficher la page d'accueil
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ONCF - Réservation en ligne</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50">
    <!-- Navigation -->
    <nav class="bg-blue-900 text-white shadow-lg">
        <div class="container mx-auto px-4 py-4">
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-2">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"></path>
                    </svg>
                    <h1 class="text-2xl font-bold">ONCF</h1>
                </div>
                <div class="flex items-center space-x-4">
                    <a href="index.php?controller=rech_trajet" class="hover:text-blue-200 transition">Rechercher un trajet</a>
                    <a href="index.php?controller=authentification" class="bg-blue-700 hover:bg-blue-600 px-4 py-2 rounded transition">Connexion</a>
                    <a href="index.php?controller=inscription" class="bg-green-600 hover:bg-green-500 px-4 py-2 rounded transition">Inscription</a>
                </div>
            </div>
        </div>
    </nav>
    <!-- Hero Section -->
    <div class="bg-gradient-to-r from-blue-900 to-blue-700 text-white py-20">
        <div class="container mx-auto px-4 text-center">
            <h2 class="text-5xl font-bold mb-4">Réservez votre billet de train en ligne</h2>
            <p class="text-xl mb-8 text-blue-100">Voyagez en toute simplicité avec ONCF</p>
            <a href="index.php?controller=rech_trajet" class="bg-white text-blue-900 px-8 py-3 rounded-lg font-semibold hover:bg-blue-50 transition inline-block">
                Rechercher un trajet
            </a>
        </div>
    </div>
    <!-- Features Section -->
    <div class="container mx-auto px-4 py-16">
        <div class="grid md:grid-cols-3 gap-8">
            <div class="bg-white p-6 rounded-lg shadow-md text-center">
                <div class="bg-blue-100 w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <h3 class="text-xl font-semibold mb-2">Réservation rapide</h3>
                <p class="text-gray-600">Réservez votre billet en quelques clics</p>
            </div>
            <div class="bg-white p-6 rounded-lg shadow-md text-center">
                <div class="bg-green-100 w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                    </svg>
                </div>
                <h3 class="text-xl font-semibold mb-2">Paiement sécurisé</h3>
                <p class="text-gray-600">Transactions sécurisées et protégées</p>
            </div>
            <div class="bg-white p-6 rounded-lg shadow-md text-center">
                <div class="bg-purple-100 w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="w-8 h-8 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                </div>
                <h3 class="text-xl font-semibold mb-2">Billets électroniques</h3>
                <p class="text-gray-600">Recevez vos billets en format PDF</p>
            </div>
        </div>
    </div>
    <!-- Footer -->
    <footer class="bg-gray-800 text-white py-8 mt-16">
        <div class="container mx-auto px-4 text-center">
            <p>&copy; 2024 ONCF - Office National des Chemins de Fer. Tous droits réservés.</p>
        </div>
    </footer>
</body>
</html>
