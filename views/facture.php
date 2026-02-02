<?php
// Récupérer les infos depuis GET
$voyage_code = $_GET['code'] ?? '';
$date = $_GET['date'] ?? '';
$places = $_GET['places'] ?? '';
$email = $_GET['email'] ?? '';
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Votre billet - ONCF</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50">
    <div class="min-h-screen flex items-center justify-center px-4">
        <div class="max-w-md w-full bg-white rounded-lg shadow-lg p-8 text-center">
            <div class="mb-4">
                <svg class="mx-auto h-16 w-16 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                </svg>
            </div>
            <h2 class="text-2xl font-bold text-gray-900 mb-4">Votre billet est confirmé !</h2>
            <div class="text-left mb-6">
                <p><b>Code du voyage :</b> <?= htmlspecialchars($voyage_code) ?></p>
                <p><b>Date du voyage :</b> <?= htmlspecialchars($date) ?></p>
                <p><b>Nombre de places :</b> <?= htmlspecialchars($places) ?></p>
                <p><b>Email :</b> <?= htmlspecialchars($email) ?></p>
            </div>
            <!-- Bouton pour télécharger le PDF -->
            <a href="pdf.php?cv=<?= urlencode($voyage_code) ?>&db=<?= urlencode($date) ?>&np=<?= urlencode($places) ?>&email=<?= urlencode($email) ?>" class="inline-block bg-green-600 hover:bg-green-700 text-white font-semibold py-2 px-6 rounded-md transition mt-4">
                Télécharger le billet PDF
            </a>
            <a href="index.php" class="inline-block bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-6 rounded-md transition mt-2">
                Retour à l'accueil
            </a>
        </div>
    </div>
</body>
</html>
