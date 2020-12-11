<?php

class ProposeManager {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    public function add($propose) {
        $requete = $this->db->prepare('INSERT INTO propose (par_num, per_num, pro_date, pro_time, pro_place, pro_sens) VALUES (:par_num, :per_num, :pro_date, :pro_time, :pro_place, :pro_sens)');
        $requete->bindValue(':par_num', $propose->getParNum());
        $requete->bindValue(':per_num', $propose->getPerNum());
        $requete->bindValue(':pro_date', $propose->getProDate());
        $requete->bindValue(':pro_time', $propose->getProTime());
        $requete->bindValue(':pro_place', $propose->getProPlace());
        $requete->bindValue(':pro_sens', $propose->getProSens());
        $retour = $requete->execute();
        return $retour;
    }

    public function getAllTrajetPropose() {
        $listePropose = array();

        $requete = $this->db->prepare('SELECT par_num, per_num, pro_date, pro_time, pro_place, pro_sens FROM propose ORDER BY 1');
        $requete->execute();

        while ($propose = $requete->fetch(PDO::FETCH_OBJ))
            $listePropose[] = new Propose($propose);

        $requete->closeCursor();
        return $listePropose;
    }

    public function getTrajetProposeWithCriteria($villeDepart, $villeArrivee, $date_dep, $heure_dep, $precision) {
        $listeTrajet = array();
        $requete = $this->db->prepare(' SELECT pa.par_num, per_num, DATE_FORMAT(pro_date,"%d/%m/%Y") as pro_date, pro_time, pro_place, pro_sens FROM propose pr
                                        JOIN parcours pa ON pa.par_num=pr.par_num
                                        WHERE vil_num1=(:vil_num1) AND vil_num2=(:vil_num2)
                                            AND pro_date BETWEEN (DATE_ADD((:prodate), INTERVAL -(:precision) DAY)) AND (DATE_ADD((:prodate), INTERVAL (:precision) DAY))
                                            AND pro_time >= (:pro_time) 
                                        ORDER BY 3
                                      ');
        $requete->bindValue(':prodate', $date_dep);
        $requete->bindValue(':vil_num1', $villeDepart);
        $requete->bindValue(':vil_num2', $villeArrivee);
        $requete->bindValue(':precision', $precision);
        $requete->bindValue(':pro_time', $heure_dep);
        $requete->execute();

        while ($trajetsPropose = $requete->fetch(PDO::FETCH_OBJ))
            $listeTrajet[] = new Propose($trajetsPropose);

        $requete->closeCursor();
        return $listeTrajet;
    }

    public function recupererAvis($per_num) {

        $requete = $this->db->prepare('SELECT ROUND(AVG(avi_note),1) as moyenne FROM avis WHERE per_num=(:per_num)');
        $requete->bindValue(':per_num', $per_num);
        $requete->execute();

        $requete2 = $this->db->prepare('SELECT avi_comm FROM avis WHERE per_num=(:per_num) ORDER BY avi_date DESC LIMIT 1 ');
        $requete2->bindValue(':per_num', $per_num);
        $requete2->execute();

        $avis = $requete->fetch();
        $avis2 = $requete2->fetch();

        $tab = array('moy_avis' => $avis, 'last_avis' => $avis2);
        $requete->closeCursor();
        return $tab;
    }

    public function deleteProposeFromPerNum($per_num): void {
        $requete = $this->db->prepare('DELETE FROM propose WHERE per_num=(:per_num)');
        $requete->bindValue(':per_num', $per_num);
        $requete->execute();
        $requete->closeCursor();
    }

    public function deleteAvisFromPerNum($per_num): void {
        $requete = $this->db->prepare('DELETE FROM avis WHERE per_num=(:per_num) OR per_per_num=(:per_num)');
        $requete->bindValue(':per_num', $per_num);
        $requete->execute();
        $requete->closeCursor();
    }


}