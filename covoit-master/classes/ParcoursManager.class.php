<?php

class ParcoursManager {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    public function add($parcours) {
        if ($this->notExisteAlready($parcours)) {
            $requete = $this->db->prepare('INSERT INTO parcours (vil_num1,vil_num2,par_km) VALUES (:vil_num1,:vil_num2,:par_km)');
            $requete->bindValue(':vil_num1', $parcours->getVilNum1());
            $requete->bindValue(':vil_num2', $parcours->getVilNum2());
            $requete->bindValue(':par_km', $parcours->getParKm());
            $retour = $requete->execute();
            return $retour;
        } else {
            return 0;
        }
    }

    public function notExisteAlready($parcours) {
        $requete = $this->db->prepare('SELECT COUNT(par_num) FROM parcours WHERE (vil_num1=(:vil_num1) AND vil_num2=(:vil_num2)) OR (vil_num1=(:vil_num2) AND vil_num2=(:vil_num1))');
        $requete->bindValue(':vil_num1', $parcours->getVilNum1());
        $requete->bindValue(':vil_num2', $parcours->getVilNum2());
        $requete->execute();

        $number = $requete->fetch();
        $numberParcours = $number[0];
        if ($numberParcours == 0) {
            return true;
        } else {
            return false;
        }
    }

    public function getAllParcours() {
        $listeParcours = array();

        $requete = $this->db->prepare('SELECT par_num, vil_num1,vil_num2,par_km FROM parcours ORDER BY 1');
        $requete->execute();

        while ($parcours = $requete->fetch(PDO::FETCH_OBJ))
            $listeParcours[] = new Parcours($parcours);

        $requete->closeCursor();
        return $listeParcours;
    }

    public function numberParcours() {
        $requete = $this->db->prepare('SELECT COUNT(par_num) FROM parcours');
        $requete->execute();

        $number = $requete->fetch();
        $numberParcours = $number[0];
        $requete->closeCursor();
        return $numberParcours;
    }

    public function getVilNomFromVilNum($vil_num) {
        $requete = $this->db->prepare('SELECT vil_nom FROM ville WHERE vil_num=(:vil_num)');
        $requete->bindValue(':vil_num', $vil_num);
        $requete->execute();

        $nom = $requete->fetch();
        $vil_nom = $nom[0];
        $requete->closeCursor();
        return $vil_nom;
    }

    public function getVillesDepart() {
        $requete = $this->db->prepare('SELECT DISTINCT vil_num1 FROM parcours UNION SELECT DISTINCT vil_num2 FROM parcours');
        $requete->execute();

        while ($villesDep = $requete->fetch()) {
            $array = array('vil_num' => $villesDep['vil_num1'], 'vil_nom' => $this->getVilNomFromVilNum($villesDep['vil_num1'],));
            $listeVillesDep[] = new Ville($array);
        }
        $requete->closeCursor();
        return $listeVillesDep;
    }

    public function getVillesArrivee($villeDepart) {
        $requete = $this->db->prepare('SELECT DISTINCT vil_num1 FROM parcours WHERE vil_num2=(:villeDepart) UNION SELECT DISTINCT vil_num2 FROM parcours WHERE vil_num1=(:villeDepart)');
        $requete->bindValue(':villeDepart', $villeDepart);
        $requete->execute();

        while ($villesAr = $requete->fetch()) {
            $array = array('vil_num' => $villesAr['vil_num1'], 'vil_nom' => $this->getVilNomFromVilNum($villesAr['vil_num1'],));
            $listeVillesAr[] = new Ville($array);
        }
        $requete->closeCursor();
        return $listeVillesAr;
    }

    public function getNumParcourAndSens($villeDepart, $villeArrivee) {
        $requete = $this->db->prepare('SELECT par_num FROM parcours WHERE vil_num1=(:villeDepart) AND vil_num2=(:villeArrivee)');
        $requete->bindValue(':villeDepart', $villeDepart);
        $requete->bindValue(':villeArrivee', $villeArrivee);
        $requete->execute();

        $requete2 = $this->db->prepare('SELECT par_num FROM parcours WHERE vil_num2=(:villeDepart) AND vil_num1=(:villeArrivee)');
        $requete2->bindValue(':villeDepart', $villeDepart);
        $requete2->bindValue(':villeArrivee', $villeArrivee);
        $requete2->execute();

        $resReq = $requete->fetch();
        $resReq2 = $requete2->fetch();

        if ($resReq != null) {
            $requete->closeCursor();
            $requete2->closeCursor();
            return array('par_num' => $resReq[0], 'sens' => 0);
        } else {
            $requete->closeCursor();
            $requete2->closeCursor();
            return array('par_num' => $resReq2[0], 'sens' => 1);
        }
    }
}