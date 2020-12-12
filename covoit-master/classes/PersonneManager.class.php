<?php

class PersonneManager {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    public function add($personne) {
        $requete = $this->db->prepare('INSERT INTO personne (per_nom, per_prenom,per_tel,per_mail,per_login,per_pwd) VALUES (:per_nom, :per_prenom,:per_tel,:per_mail,:per_login,:per_pwd)');
        $requete->bindValue(':per_nom', $personne->getPerNom());
        $requete->bindValue(':per_prenom', $personne->getPerPrenom());
        $requete->bindValue(':per_tel', $personne->getPerTel());
        $requete->bindValue(':per_mail', $personne->getPerMail());
        $requete->bindValue(':per_login', $personne->getPerLogin());
        $requete->bindValue(':per_pwd', $personne->getPerPwd());
        $retour = $requete->execute();
        return $retour;
    }

    public function modify($personne, $pernum) {
        $requete = $this->db->prepare('UPDATE personne SET per_nom=:per_nom, per_prenom=:per_prenom,per_tel=:per_tel,per_mail=:per_mail,per_login=:per_login,per_pwd=:per_pwd WHERE per_num=:per_num');
        $requete->bindValue(':per_num', $pernum);
        $requete->bindValue(':per_nom', $personne->getPerNom());
        $requete->bindValue(':per_prenom', $personne->getPerPrenom());
        $requete->bindValue(':per_tel', $personne->getPerTel());
        $requete->bindValue(':per_mail', $personne->getPerMail());
        $requete->bindValue(':per_login', $personne->getPerLogin());
        $requete->bindValue(':per_pwd', $personne->getPerPwd());
        $retour = $requete->execute();
        return $retour;
    }

    public function getAllPersonnes() {
        $listePersonnes = array();

        $requete = $this->db->prepare('SELECT * FROM personne ORDER BY 1');
        $requete->execute();

        while ($personnes = $requete->fetch(PDO::FETCH_OBJ))
            $listePersonnes[] = new Personne($personnes);

        $requete->closeCursor();
        return $listePersonnes;
    }

    public function numberPersonne() {
        $requete = $this->db->prepare('SELECT COUNT(per_num) FROM personne');
        $requete->execute();

        $number = $requete->fetch();
        $numberPersonnes = $number[0];
        $requete->closeCursor();
        return $numberPersonnes;
    }

    public function isEtudiant($numPers) {
        $requete = $this->db->prepare('SELECT COUNT(per_num) FROM etudiant WHERE per_num=(:per_num) ');
        $requete->bindValue(':per_num', $numPers);
        $requete->execute();

        $id = $requete->fetch();
        $resultat = $id[0];
        $requete->closeCursor();
        if ($resultat == 0) {
            $categorie = "salarie";
        } else {
            $categorie = "etudiant";
        }
        return $categorie;
    }

    public function getPersonneFromPerNum($per_num) {
        $requete = $this->db->prepare('SELECT per_num, per_nom, per_prenom, per_tel, per_mail, per_login, per_pwd  FROM personne WHERE per_num=(:per_num) ORDER BY 1');
        $requete->bindValue(':per_num', $per_num);
        $requete->execute();

        $pers = $requete->fetch(PDO::FETCH_OBJ);
        return new Personne($pers);
    }

    public function getNumFromLogin($login) {
        $requete = $this->db->prepare('SELECT per_num FROM personne WHERE per_login=(:login) ORDER BY 1');
        $requete->bindValue(':login', $login);
        $requete->execute();

        $per_num = $requete->fetch();
        return $per_num[0];
    }

    public function loginAndPasswordValide($login, $password) {
        $requete = $this->db->prepare('SELECT per_pwd  FROM personne WHERE per_login=(:per_login)');
        $requete->bindValue(':per_login', $login);
        $requete->execute();

        $pwd = $requete->fetch();
        if ($pwd != null) {
            if ($password == $pwd['per_pwd']) {
                return true;
            } else {
                echo 'Votre pseudo ou mot de passe est incorrect';
                return false;
            }
        }
    }

    public function deletePersonneFromNum($per_num): void {
        $requete = $this->db->prepare('DELETE FROM personne WHERE per_num=(:per_num)');
        $requete->bindValue(':per_num', $per_num);
        $requete->execute();
        $requete->closeCursor();
    }
}
