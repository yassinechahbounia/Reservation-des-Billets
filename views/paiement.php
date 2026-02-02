<?php
session_start();
use Models\Tbillet;
// Récupérer les infos transmises
$voyage_code = isset($_GET['code']) ? $_GET['code'] : '';
$date = isset($_GET['date']) ? $_GET['date'] : '';
$places = isset($_GET['places']) ? $_GET['places'] : '';
$email = '';
if (isset($_GET['email'])) {
    $email = $_GET['email'];
} elseif (isset($_SESSION['user_email'])) {
    $email = $_SESSION['user_email'];
}
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['nom'], $_POST['numero'], $_POST['mois'], $_POST['annee'], $_POST['crypto'])) {
    $nom = $_POST['nom'];
    $numero = $_POST['numero'];
    $mois = $_POST['mois'];
    $annee = $_POST['annee'];
    $crypto = $_POST['crypto'];
    $ok = Tbillet::checkcreditcard($nom, $numero, $annee, $mois, $crypto);
    if ($ok) {
        // Enregistrement du paiement : ici, on peut générer une facture ou marquer le billet comme payé
        // Redirection vers la facture ou PDF
        header("Location: index.php?controller=facture&code=$voyage_code&date=$date&places=$places&email=$email");
        exit;
    } else {
        echo '<div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4 text-center" role="alert">Paiement refusé. Vérifiez vos informations bancaires.</div>';
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Paiement - ONCF</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50">
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
                    <?php if (isset($_SESSION['user_email'])): ?>
                        <span class="text-green-200">Connecté : <?= htmlspecialchars($_SESSION['user_email']) ?></span>
                        <a href="index.php?controller=authentification&action=logout" class="hover:text-red-300 transition">Déconnexion</a>
                    <?php else: ?>
                        <a href="index.php?controller=reservation" class="hover:text-blue-200 transition">Réserver</a>
                        <a href="index.php?controller=authentification" class="hover:text-blue-200 transition">Connexion</a>
                        <a href="index.php?controller=inscription" class="hover:text-blue-200 transition">Inscription</a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </nav>
    <div class="min-h-screen flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
        <div class="max-w-md w-full space-y-8">
            <div>
                <h2 class="mt-6 text-center text-3xl font-extrabold text-gray-900">Paiement de la réservation</h2>
                <p class="mt-2 text-center text-sm text-gray-600">Vérifiez les informations et effectuez le paiement pour finaliser votre réservation.</p>
            </div>
            <div class="bg-white p-6 rounded-lg shadow-lg mb-6">
                <h3 class="text-lg font-semibold mb-2 text-blue-900">Récapitulatif</h3>
                <ul class="text-gray-700 text-sm space-y-1">
                    <li><b>Code du voyage :</b> <?= htmlspecialchars($voyage_code) ?></li>
                    <li><b>Date du voyage :</b> <?= htmlspecialchars($date) ?></li>
                    <li><b>Nombre de places :</b> <?= htmlspecialchars($places) ?></li>
                    <li><b>Email :</b> <?= htmlspecialchars($email) ?></li>
                </ul>
            </div>
            <form class="space-y-6" action="index.php?controller=paiement&code=<?= htmlspecialchars($voyage_code) ?>&date=<?= htmlspecialchars($date) ?>&places=<?= htmlspecialchars($places) ?>&email=<?= htmlspecialchars($email) ?>" method="POST">
                <div class="mb-4">
                    <label for="nom" class="block text-sm font-medium text-gray-700 mb-1">Nom sur la carte</label>
                    <input id="nom" name="nom" type="text" required class="appearance-none rounded-md relative block w-full px-3 py-2 border border-gray-300 text-gray-900 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm" placeholder="Nom complet">
                </div>
                <div class="mb-4">
                    <label for="numero" class="block text-sm font-medium text-gray-700 mb-1">Numéro de carte</label>
                    <input id="numero" name="numero" type="text" required maxlength="16" class="appearance-none rounded-md relative block w-full px-3 py-2 border border-gray-300 text-gray-900 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm" placeholder="1234 5678 9012 3456">
                </div>
                <div class="mb-4 grid grid-cols-2 gap-4">
                    <div>
                        <label for="mois" class="block text-sm font-medium text-gray-700 mb-1">Mois d'expiration</label>
                        <input id="mois" name="mois" type="text" required maxlength="2" class="appearance-none rounded-md relative block w-full px-3 py-2 border border-gray-300 text-gray-900 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm" placeholder="MM">
                    </div>
                    <div>
                        <label for="annee" class="block text-sm font-medium text-gray-700 mb-1">Année d'expiration</label>
                        <input id="annee" name="annee" type="text" required maxlength="4" class="appearance-none rounded-md relative block w-full px-3 py-2 border border-gray-300 text-gray-900 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm" placeholder="AAAA">
                    </div>
                </div>
                <div class="mb-4">
                    <label for="crypto" class="block text-sm font-medium text-gray-700 mb-1">Cryptogramme</label>
                    <input id="crypto" name="crypto" type="text" required maxlength="3" class="appearance-none rounded-md relative block w-full px-3 py-2 border border-gray-300 text-gray-900 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm" placeholder="123">
                </div>
                <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-3 px-6 rounded-md transition">Payer</button>
            </form>
        </div>
    </div>
</body>
</html>
