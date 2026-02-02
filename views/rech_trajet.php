<?php
session_start();
use Models\Tvoyage;
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recherche de trajet - ONCF</title>
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

    <div class="container mx-auto px-4 py-8">
        <div class="max-w-4xl mx-auto">
            <h1 class="text-3xl font-bold text-gray-900 mb-8">Recherche de trajet</h1>
            
            <div class="bg-white rounded-lg shadow-md p-6 mb-8">
                <form action="index.php?controller=rech_trajet" method="POST" class="space-y-6">
                    <div class="grid md:grid-cols-2 gap-6">
                        <div>
                            <label for="vd" class="block text-sm font-medium text-gray-700 mb-2">Ville de départ</label>
                            <select name="vd" id="vd" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                                <?php
                                $cur1= Tvoyage::chargervd();
                                while ($row = $cur1->fetch()) {
                                    echo"<option value='$row[0]'>$row[0]</option>";
                                }
                                $cur1->closeCursor();
                                ?>
                            </select>
                        </div>
                        <div>
                            <label for="va" class="block text-sm font-medium text-gray-700 mb-2">Ville d'arrivée</label>
                            <select name="va" id="va" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                                <?php
                                $cur2= Tvoyage::chargerva();
                                while ($row = $cur2->fetch()) {
                                    echo"<option value='$row[0]'>$row[0]</option>";
                                }
                                $cur2->closeCursor();
                                ?>
                            </select>
                        </div>
                    </div>
                    <button type="submit" name="actionrechercher" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-3 px-6 rounded-md transition">
                        Rechercher
                    </button>
                </form>
            </div>

            <?php
            if(isset($_POST['actionrechercher'])){
                $vd=$_POST['vd'];
                $va=$_POST['va'];
                $cur3= Tvoyage::trajetParVille($vd, $va);
                $n=$cur3->rowCount();
                if($n!=0){
                    echo '<div class="bg-white rounded-lg shadow-md overflow-hidden">';
                    echo '<div class="overflow-x-auto">';
                    echo '<table class="min-w-full divide-y divide-gray-200">';
                    echo '<thead class="bg-gray-50">';
                    echo '<tr>';
                    echo '<th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Code</th>';
                    echo '<th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Heure départ</th>';
                    echo '<th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Ville départ</th>';
                    echo '<th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Heure arrivée</th>';
                    echo '<th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Ville arrivée</th>';
                    echo '<th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Prix</th>';
                    echo '<th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Action</th>';
                    echo '</tr>';
                    echo '</thead>';
                    echo '<tbody class="bg-white divide-y divide-gray-200">';
                    while ($row = $cur3->fetch()) {
                        echo '<tr class="hover:bg-gray-50">';
                        for ($i = 0; $i <6; $i++) {
                            $class = $i == 5 ? 'px-6 py-4 whitespace-nowrap text-sm font-semibold text-green-600' : 'px-6 py-4 whitespace-nowrap text-sm text-gray-900';
                            echo "<td class='$class'>$row[$i]</td>";
                        }
                        // Ajout du bouton Réserver
                        echo '<td class="px-6 py-4 whitespace-nowrap text-sm">';
                        echo '<a href="index.php?controller=reservation&code=' . urlencode($row[0]) . '" class="bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-4 rounded transition">Réserver</a>';
                        echo '</td>';
                        echo '</tr>';
                    }
                    $cur3->closeCursor();
                    echo '</tbody>';
                    echo '</table>';
                    echo '</div>';
                    echo '</div>';
                }else{
                    echo '<div class="bg-yellow-50 border border-yellow-200 text-yellow-800 px-4 py-3 rounded relative" role="alert">
                            <span class="block sm:inline">Aucun trajet disponible pour cette route.</span>
                          </div>';
                }
            }
            ?>
        </div>
    </div>
</body>
</html>
