<?php

class SalarieManager {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    public function add($salarie) {
        $requete = $this->db->prepare('INSERT INTO salarie (per_num,sal_telprof,fon_num) VALUES (:per_num,:sal_telprof,:fon_num)');
        $requete->bindValue(':per_num', $salarie->getPerNum());
        $requete->bindValue(':sal_telprof', $salarie->getSalTelprof());
        $requete->bindValue(':fon_num', $salarie->getFonNum());
        $retour = $requete->execute();
        return $retour;
    }

    public function modify($salarie) {
        $requete = $this->db->prepare('UPDATE salarie SET sal_telprof=:sal_telprof,fon_num=:fon_num WHERE per_num=:per_num');
        $requete->bindValue(':per_num', $salarie->getPerNum());
        $requete->bindValue(':sal_telprof', $salarie->getSalTelprof());
        $requete->bindValue(':fon_num', $salarie->getFonNum());
        $retour = $requete->execute();
        return $retour;
    }

    public function getAllSalaries() {
        $listeSalaries = array();

        $requete = $this->db->prepare('SELECT per_num,sal_telprof,fon_num FROM salarie ORDER BY 1');
        $requete->execute();

        while ($salaries = $requete->fetch(PDO::FETCH_OBJ))
            $listeSalaries[] = new Salarie($salaries);

        $requete->closeCursor();
        return $listeSalaries;
    }

    public function getSalarieFromPerNum($per_num) {
        $requete = $this->db->prepare('SELECT per_num,sal_telprof,fon_num FROM salarie WHERE per_num=(:per_num) ORDER BY 1');
        $requete->bindValue(':per_num', $per_num);
        $requete->execute();

        $sal = $requete->fetch(PDO::FETCH_OBJ);
        $salarie = new Salarie($sal);
        return $salarie;
    }

    public function deleteSalarieFromNum($per_num): void {
        $requete = $this->db->prepare('DELETE FROM salarie WHERE per_num=(:per_num)');
        $requete->bindValue(':per_num', $per_num);
        $requete->execute();
        $requete->closeCursor();
    }
}