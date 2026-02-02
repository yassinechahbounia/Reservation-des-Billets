<?php
namespace Models;

use Models\Dataaccess;

// Modèle pour la gestion des voyages
class Tvoyage {
    public static function chargervd(){
        $req="select distinct(villedepart) from voyage ";
        return Dataaccess::selection($req);
    }
    public static function chargerva(){
        $req="select distinct(villearrivee) from voyage ";
        return Dataaccess::selection($req);
    }
    public static function trajetParVille($vd,$va){
        $req="select * from voyage where villedepart ='$vd' and villearrivee='$va'";
        $cur=Dataaccess::selection($req);
       
        return $cur;
    }
    public static function getprix($cv){
        $req="select prixvoyage from voyage where codevoyage ='$cv'";
        $cur=Dataaccess::selection($req);
        $prix=0;
        while ($row = $cur->fetch()) {
            $prix=$row[0];
        }
        $cur->closeCursor();
        return $prix;
    }
    public static function getVoyageByCode($code) {
        // Sécurisation basique du code
        $code = addslashes($code);
        $req = "SELECT * FROM voyage WHERE codevoyage = '$code' LIMIT 1";
        $cur = Dataaccess::selection($req);
        $voyage = $cur ? $cur->fetch() : false;
        if ($cur) { $cur->closeCursor(); }
        return $voyage;
    }
}
