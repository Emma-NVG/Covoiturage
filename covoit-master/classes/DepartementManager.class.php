<?php

class DepartementManager {
    private $db;

    /**
     * DepartementManager constructor.
     * @param $db
     */
    public function __construct($db) {
        $this->db = $db;
    }

    /**
     * Fonction qui ajoute un département dans la table département de la base de donnée
     * @param $departement
     * @return bool
     */
    public function add($departement) {
        $requete = $this->db->prepare('INSERT INTO departement (dep_num,dep_nom,vil_num) VALUES (:dep_num,:dep_nom,:vil_num)');
        $requete->bindValue(':dep_num', $departement->getDepNum());
        $requete->bindValue(':dep_nom', $departement->getDepNom());
        $requete->bindValue(':vil_nom', $departement->getVilNom());
        $retour = $requete->execute();
        return $retour;
    }

    /**
     * Fonction qui retourne un array contenant tout les départements de la table département stockée en base
     * @return array
     */
    public function getAllDepartements() {
        $listeDepartements = array();

        $requete = $this->db->prepare('SELECT dep_num,dep_nom,vil_num FROM departement ORDER BY 2');
        $requete->execute();

        while ($departements = $requete->fetch(PDO::FETCH_OBJ))
            $listeDepartements[] = new Departement($departements);

        $requete->closeCursor();
        return $listeDepartements;
    }

    /**
     *Fonction qui retourne un objet département ayant les caractéristiques du département stocké en base et ayant le numéro passé en paramètre
     * @param $dep_num
     * @return Departement
     */
    public function getDepFromDepNum($dep_num) {
        $requete = $this->db->prepare('SELECT dep_num,dep_nom,vil_num FROM departement WHERE dep_num=(:dep_num) ');
        $requete->bindValue(':dep_num', $dep_num);
        $requete->execute();

        $dep = $requete->fetch(PDO::FETCH_OBJ);
        $departement = new Departement($dep);
        return $departement;
    }
}