<?php

class EtudiantManager {
    private $db;

    /**
     * EtudiantManager constructor.
     * @param $db
     */
    public function __construct($db) {
        $this->db = $db;
    }

    /**
     * Fonction qui ajoute un étudiant dans la table étudiant
     * @param $etudiant
     * @return bool
     */
    public function add($etudiant) {
        $requete = $this->db->prepare('INSERT INTO etudiant (per_num,dep_num,div_num) VALUES (:per_num,:dep_num,:div_num)');
        $requete->bindValue(':per_num', $etudiant->getPerNum());
        $requete->bindValue(':dep_num', $etudiant->getDepNum());
        $requete->bindValue(':div_num', $etudiant->getDivNum());
        $retour = $requete->execute();
        return $retour;
    }

    /**
     * Fonction qui modifie les informations d'un étudiant dans la table étudiant en remplacant les valeurs par celles en paramètre
     * @param $etudiant
     * @return bool
     */
    public function modify($etudiant) {
        $requete = $this->db->prepare('UPDATE etudiant SET dep_num=:dep_num,div_num=:div_num WHERE per_num=:per_num');
        $requete->bindValue(':per_num', $etudiant->getPerNum());
        $requete->bindValue(':dep_num', $etudiant->getDepNum());
        $requete->bindValue(':div_num', $etudiant->getDivNum());
        $retour = $requete->execute();
        return $retour;
    }

    /**
     * Fonction qui permet de récupérer tous les étudiants de la table étudiants
     * @return array
     */
    public function getAllEtudiants() {
        $listeEtudiants = array();

        $requete = $this->db->prepare('SELECT per_num,dep_num,div_num FROM etudiant ORDER BY 1');
        $requete->execute();

        while ($etudiants = $requete->fetch(PDO::FETCH_OBJ))
            $listeEtudiants[] = new Etudiant($etudiants);

        $requete->closeCursor();
        return $listeEtudiants;
    }

    /**
     * Fonction qui renvoie un objet étudiant ayant pour attribut les valeurs de l'étudiant correspondant au numéro passé en paramètre
     * @param $per_num
     * @return Etudiant
     */
    public function getEtudiantFromPerNum($per_num) {
        $requete = $this->db->prepare('SELECT per_num,dep_num,div_num FROM etudiant WHERE per_num=(:per_num) ORDER BY 1');
        $requete->bindValue(':per_num', $per_num);
        $requete->execute();

        $etu = $requete->fetch(PDO::FETCH_OBJ);
        $etudiant = new Etudiant($etu);
        return $etudiant;
    }

    /**
     * Fonction qui delete dans la base l'étudiant correspondant au numéro en paramètre
     * @param $per_num
     */
    public function deleteEtudiantFromNum($per_num){
        $requete = $this->db->prepare('DELETE FROM etudiant WHERE per_num=(:per_num)');
        $requete->bindValue(':per_num', $per_num);
        $requete->execute();
        $requete->closeCursor();
    }
}