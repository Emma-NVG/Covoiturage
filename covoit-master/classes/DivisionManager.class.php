<?php

class DivisionManager {
    private $db;

    /**
     * DivisionManager constructor.
     * @param $db
     */
    public function __construct($db) {
        $this->db = $db;
    }

    /**
     * Fonction qui ajoute une division dans la table division de la base de donnée
     * @param $division
     * @return boolean
     */
    public function add($division) {
        $requete = $this->db->prepare('INSERT INTO division (div_num,div_nom) VALUES (:div_num,:div_nom)');
        $requete->bindValue(':div_num', $division->getDivNum());
        $requete->bindValue(':div_nom', $division->getDivNom());
        $retour = $requete->execute();
        return $retour;
    }

    /**
     * Fonction qui retourne un array contenant toutes les divisions de la table division stockée en base
     * @return array
     */
    public function getAllDivisions() {
        $listeDivisions = array();

        $requete = $this->db->prepare('SELECT div_num,div_nom FROM division ORDER BY 1');
        $requete->execute();

        while ($divisions = $requete->fetch(PDO::FETCH_OBJ))
            $listeDivisions[] = new Division($divisions);

        $requete->closeCursor();
        return $listeDivisions;
    }

    /**
     *Fonction qui retourne un objet division ayant les caractéristiques de la division stocké en base et ayant le numéro passé en paramètre
     * @param $div_num
     * @return Division
     */
    public function getDivFromDivNum($div_num) {
        $requete = $this->db->prepare('SELECT div_num,div_nom FROM division WHERE div_num=(:div_num)');
        $requete->bindValue(':div_num', $div_num);
        $requete->execute();

        $div = $requete->fetch(PDO::FETCH_OBJ);
        $division = new Division($div);
        return $division;
    }
}