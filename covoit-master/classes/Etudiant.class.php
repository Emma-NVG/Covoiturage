<?php

class Etudiant {
    private $per_num;
    private $dep_num;
    private $div_num;

    public function __construct($valeurs = array()) {
        if (!empty($valeurs)) $this->affecte($valeurs);
    }

    public function affecte($donnees) {
        foreach ($donnees as $attribut => $valeur) {
            switch ($attribut) {
                case 'per_num':
                    $this->setPerNum($valeur);
                    break;
                case 'dep_num':
                    $this->setDepNum($valeur);
                    break;
                case 'div_num':
                    $this->setDivNum($valeur);
                    break;
            }
        }
    }

    public function getPerNum() {
        return $this->per_num;
    }

    public function setPerNum($per_num): void {
        $this->per_num = $per_num;
    }

    public function getDepNum() {
        return $this->dep_num;
    }

    public function setDepNum($dep_num): void {
        $this->dep_num = $dep_num;
    }

    public function getDivNum() {
        return $this->div_num;
    }

    public function setDivNum($div_num): void {
        $this->div_num = $div_num;
    }


}