<?php
namespace Controllers;

// Contrôleur pour la gestion des paiements
class PaiementController {
    public function index() {
        require_once __DIR__ . '/../views/paiement.php';
    }
}
