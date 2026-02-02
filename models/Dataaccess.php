<?php
namespace Models;

// Ensure Composer autoload is loaded before any Dotenv usage
if (file_exists(__DIR__ . '/../vendor/autoload.php')) {
    require_once __DIR__ . '/../vendor/autoload.php';
}

use PDO;
use Exception;
use Dotenv\Dotenv;

class Dataaccess {
    const ERROR_PREFIX = 'Erreur : ';
    public static function connextion() {
        // Charger les variables d'environnement depuis .env
        $dotenv = Dotenv::createImmutable(dirname(__DIR__));
        $dotenv->load();
        try {
            $bdd = new PDO(
                'mysql:host=' . $_ENV['DB_HOST'] . ';dbname=' . $_ENV['DB_NAME'],
                $_ENV['DB_USER'],
                $_ENV['DB_PASS']
            );
            $bdd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            return $bdd;
        } catch (Exception $e) {
            echo self::ERROR_PREFIX . $e->getMessage();
            return null;
        }
    }

    public static function miseajour($req) {
        try {
            $bdd = Dataaccess::connextion();
            if ($bdd === null) {
                throw new DatabaseConnectionException('Connexion à la base de données échouée.');
            }
            return $bdd->exec($req);
        } catch (DatabaseConnectionException $e) {
            echo self::ERROR_PREFIX . $e->getMessage();
        } catch (Exception $e) {
            echo self::ERROR_PREFIX . $e->getMessage();
        }
        return null;
    }

    public static function selection($req) {
        try {
            $bdd = self::connextion();
            if ($bdd === null) {
                throw new DatabaseConnectionException('Connexion à la base de données échouée.');
            }
            return $bdd->query($req);
        } catch (DatabaseConnectionException $e) {
            echo self::ERROR_PREFIX . $e->getMessage();
        } catch (Exception $e) {
            echo self::ERROR_PREFIX . $e->getMessage();
        }
        return null;
    }
}

class DatabaseConnectionException extends \Exception {}
