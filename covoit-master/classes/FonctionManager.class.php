<?php

class FonctionManager {
    private $db;

    /**
     * FonctionManager constructor.
     * @param $db
     */
    public function __construct($db) {
        $this->db = $db;
    }

    /**
     * Fonction qui ajoute une fonction dans la table fonction de la base de donnée
     * @param $fonction
     * @return bool
     */
    public function add($fonction) {
        $requete = $this->db->prepare('INSERT INTO fonction (fon_num, fon_libelle) VALUES (:fon_num, :fon_libelle)');
        $requete->bindValue(':fon_num', $fonction->getFonNum());
        $requete->bindValue(':fon_libelle', $fonction->getFonLibelle());
        $retour = $requete->execute();
        return $retour;
    }

    /**
     * Fonction qui retourne un array contenant toutes les fonctions de la table fonction
     * @return array
     */
    public function getAllFonctions() {
        $listeFonctions = array();

        $requete = $this->db->prepare('SELECT fon_num, fon_libelle FROM fonction ORDER BY 2');
        $requete->execute();

        while ($fonctions = $requete->fetch(PDO::FETCH_OBJ))
            $listeFonctions[] = new Fonction($fonctions);

        $requete->closeCursor();
        return $listeFonctions;
    }

    /**
     * Fonction qui récupère la fonction correspondante au numéro en paramètre
     * @param $fon_num
     * @return Fonction
     */
    public function getFonFromFonNum($fon_num) {
        $requete = $this->db->prepare('SELECT fon_num, fon_libelle FROM fonction WHERE fon_num=(:fon_num)');
        $requete->bindValue(':fon_num', $fon_num);
        $requete->execute();

        $fon = $requete->fetch(PDO::FETCH_OBJ);
        $fonction = new Fonction($fon);
        return $fonction;
    }

}