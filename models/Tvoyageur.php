<?php
namespace Models;

use Models\Dataaccess;

class Tvoyageur {
    public static function inscription($email, $pass, $nom, $prenom, $dn, $telephone) {
        $req = "insert into voyageur values('$email','$pass','$nom','$prenom','$dn','$telephone')";
        return Dataaccess::miseajour($req);
    }
    public static function authentification($email, $pass) {
        $req = "select * from voyageur where email='$email' and password='$pass'";
        $cur = Dataaccess::selection($req);
        return $cur->rowCount();
    }
}
