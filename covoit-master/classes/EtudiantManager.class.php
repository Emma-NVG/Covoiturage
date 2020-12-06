<?php

class EtudiantManager {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    public function add($etudiant) {
        $requete = $this->db->prepare('INSERT INTO etudiant (per_num,dep_num,div_num) VALUES (:per_num,:dep_num,:div_num)');
        $requete->bindValue(':per_num', $etudiant->getPerNum());
        $requete->bindValue(':dep_num', $etudiant->getDepNum());
        $requete->bindValue(':div_num', $etudiant->getDivNum());
        $retour = $requete->execute();
        return $retour;
    }

    public function modify($etudiant) {
        $requete = $this->db->prepare('UPDATE etudiant SET dep_num=:dep_num,div_num=:div_num WHERE per_num=:per_num');
        $requete->bindValue(':per_num', $etudiant->getPerNum());
        $requete->bindValue(':dep_num', $etudiant->getDepNum());
        $requete->bindValue(':div_num', $etudiant->getDivNum());
        $retour = $requete->execute();
        return $retour;
    }

    public function getAllEtudiants() {
        $listeEtudiants = array();

        $requete = $this->db->prepare('SELECT per_num,dep_num,div_num FROM etudiant ORDER BY 1');
        $requete->execute();

        while ($etudiants = $requete->fetch(PDO::FETCH_OBJ))
            $listeEtudiants[] = new Etudiant($etudiants);

        $requete->closeCursor();
        return $listeEtudiants;
    }

    public function getEtudiantFromPerNum($per_num) {
        $requete = $this->db->prepare('SELECT per_num,dep_num,div_num FROM etudiant WHERE per_num=(:per_num) ORDER BY 1');
        $requete->bindValue(':per_num', $per_num);
        $requete->execute();

        $etu = $requete->fetch(PDO::FETCH_OBJ);
        $etudiant = new Etudiant($etu);
        return $etudiant;
    }

    public function deleteEtudiantFromNum($per_num): void {
        $requete = $this->db->prepare('DELETE FROM etudiant WHERE per_num=(:per_num)');
        $requete->bindValue(':per_num', $per_num);
        $requete->execute();
        $requete->closeCursor();
    }
}