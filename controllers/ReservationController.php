<?php
namespace Controllers;

// Contrôleur pour la gestion des réservations
class ReservationController {
    public function index() {
        require_once __DIR__ . '/../views/reservation.php';
    }
}
