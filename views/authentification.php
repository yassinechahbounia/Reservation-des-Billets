<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion - ONCF</title>
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
                    <a href="index.php?controller=authentification" class="hover:text-blue-200 transition">Connexion</a>
                    <a href="index.php?controller=inscription" class="hover:text-blue-200 transition">Inscription</a>
                    <a href="index.php?controller=reservation" class="hover:text-blue-200 transition">Réserver</a>
                </div>
            </div>
        </div>
    </nav>

    <div class="min-h-screen flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
        <div class="max-w-md w-full space-y-8">
            <div>
                <h2 class="mt-6 text-center text-3xl font-extrabold text-gray-900">
                    Connectez-vous à votre compte
                </h2>
                <p class="mt-2 text-center text-sm text-gray-600">
                    Ou
                    <a href="index.php?controller=inscription" class="font-medium text-blue-600 hover:text-blue-500">
                        créez un nouveau compte
                    </a>
                </p>
            </div>

            <?php
            session_start();
            use Models\Tvoyageur;

            if (isset($_SESSION['user_email'])) {
                echo '<div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4 text-center" role="alert">';
                echo 'Connecté en tant que <b>' . htmlspecialchars($_SESSION['user_email']) . '</b> | <a href="index.php?controller=authentification&action=logout" class="text-blue-600 hover:text-blue-500 font-medium">Déconnexion</a>';
                echo '</div>';
            }

            if(isset($_POST['actionauth'])){
                $email=$_POST['email'];
                $pass=$_POST['password'];
                
                $n=Tvoyageur::authentification($email, $pass);
                if($n!=0){
                    $_SESSION['user_email'] = $email;
                    echo '<div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">';
                    echo 'Connexion réussie !';
                    echo '</div>';
                }else{
                    echo '<div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">';
                    echo 'Erreur de connexion. Vérifiez vos identifiants.';
                    echo '</div>';
                }
            }

            if (isset($_GET['action']) && $_GET['action'] === 'logout') {
                session_destroy();
                header('Location: index.php?controller=authentification');
                exit;
            }
            ?>

            <form class="mt-8 space-y-6" action="index.php?controller=authentification" method="POST">
                <div class="rounded-md shadow-sm space-y-4">
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                        <input id="email" name="email" type="email" required class="appearance-none rounded-md relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 focus:outline-none focus:ring-blue-500 focus:border-blue-500 focus:z-10 sm:text-sm" placeholder="votre@email.com">
                    </div>
                    <div>
                        <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Mot de passe</label>
                        <input id="password" name="password" type="password" required class="appearance-none rounded-md relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 focus:outline-none focus:ring-blue-500 focus:border-blue-500 focus:z-10 sm:text-sm" placeholder="••••••••">
                    </div>
                </div>

                <div>
                    <button type="submit" name="actionauth" class="group relative w-full flex justify-center py-2 px-4 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        Se connecter
                    </button>
                    <button type="reset" class="mt-3 group relative w-full flex justify-center py-2 px-4 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        Annuler
                    </button>
                </div>
            </form>
        </div>
    </div>
</body>
</html>
