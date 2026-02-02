<?php
namespace Models;

use Models\Dataaccess;

class Tbillet {
    public static function checkcreditcard($nom, $num, $anne, $mois, $crypto) {
        $cur = Dataaccess::selection("select * from Cartebancaire where numcarte='$num' and detenteur ='$nom' and anneeexp='$anne' and moisexp='$mois' and crypto ='$crypto'");
        return $cur->rowCount();
    }
    public static function savebillet($cv, $db, $email) {
        $req = "insert into billet(codevoyage,datebillet,email) values('$cv','$db','$email')";
        return Dataaccess::miseajour($req);
    }
    public static function hasReservation($codevoyage, $date, $email) {
        $codevoyage = addslashes($codevoyage);
        $date = addslashes($date);
        $email = addslashes($email);
        $req = "SELECT 1 FROM billet WHERE codevoyage = '$codevoyage' AND datebillet = '$date' AND email = '$email' LIMIT 1";
        $cur = Dataaccess::selection($req);
        $exists = $cur && $cur->fetch() ? true : false;
        if ($cur) { $cur->closeCursor(); }
        return $exists;
    }
}
