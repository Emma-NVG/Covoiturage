<?php

class VilleManager {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    public function add($ville) {
        $requete = $this->db->prepare('INSERT INTO ville (vil_nom) VALUES (:vil_nom)');
        $requete->bindValue(':vil_nom', $ville->getVilNom());
        $retour = $requete->execute();
        return $retour;
    }

    public function getAllVilles() {
        $listeVilles = array();

        $requete = $this->db->prepare('SELECT vil_num, vil_nom FROM ville ORDER BY 2');
        $requete->execute();

        while ($ville = $requete->fetch(PDO::FETCH_OBJ))
            $listeVilles[] = new Ville($ville);

        $requete->closeCursor();
        return $listeVilles;
    }

    public function numberVilles() {
        $number_Villes = array();
        $requete = $this->db->prepare('SELECT COUNT(vil_num) FROM ville');
        $requete->execute();

        $number = $requete->fetch();
        $number_Villes = $number[0];
        $requete->closeCursor();
        return $number_Villes;
    }

}