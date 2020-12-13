<?php

class SalarieManager {
    private $db;

    /**
     * SalarieManager constructor.
     * @param $db
     */
    public function __construct($db) {
        $this->db = $db;
    }

    /**
     * Fonction qui ajoute un salarie dans la table salarie de la base de donnée
     * @param $salarie
     * @return mixed
     */
    public function add($salarie) {
        $requete = $this->db->prepare('INSERT INTO salarie (per_num,sal_telprof,fon_num) VALUES (:per_num,:sal_telprof,:fon_num)');
        $requete->bindValue(':per_num', $salarie->getPerNum());
        $requete->bindValue(':sal_telprof', $salarie->getSalTelprof());
        $requete->bindValue(':fon_num', $salarie->getFonNum());
        $retour = $requete->execute();
        return $retour;
    }

    /**
     * Fonction qui permet de modifier les informations d'un salarie en les remplacant par celle en paramètre
     * @param $salarie
     * @return mixed
     */
    public function modify($salarie) {
        $requete = $this->db->prepare('UPDATE salarie SET sal_telprof=:sal_telprof,fon_num=:fon_num WHERE per_num=:per_num');
        $requete->bindValue(':per_num', $salarie->getPerNum());
        $requete->bindValue(':sal_telprof', $salarie->getSalTelprof());
        $requete->bindValue(':fon_num', $salarie->getFonNum());
        $retour = $requete->execute();
        return $retour;
    }

    /**
     * Fonction qui retourne tous les salariés de la table salarié
     * @return array
     */
    public function getAllSalaries() {
        $listeSalaries = array();

        $requete = $this->db->prepare('SELECT per_num,sal_telprof,fon_num FROM salarie ORDER BY 1');
        $requete->execute();

        while ($salaries = $requete->fetch(PDO::FETCH_OBJ))
            $listeSalaries[] = new Salarie($salaries);

        $requete->closeCursor();
        return $listeSalaries;
    }

    /**
     * Fonction qui retourne un objet salarié correspondant au salarié ayant le numéro en paramètre
     * @param $per_num
     * @return Salarie
     */
    public function getSalarieFromPerNum($per_num) {
        $requete = $this->db->prepare('SELECT per_num,sal_telprof,fon_num FROM salarie WHERE per_num=(:per_num) ORDER BY 1');
        $requete->bindValue(':per_num', $per_num);
        $requete->execute();

        $sal = $requete->fetch(PDO::FETCH_OBJ);
        $salarie = new Salarie($sal);
        return $salarie;
    }

    /**
     * Procédure qui permet de delete un salarie ayant pour numéro celui en paramètre de la base
     * @param $per_num
     */
    public function deleteSalarieFromNum($per_num) {
        $requete = $this->db->prepare('DELETE FROM salarie WHERE per_num=(:per_num)');
        $requete->bindValue(':per_num', $per_num);
        $requete->execute();
        $requete->closeCursor();
    }
}