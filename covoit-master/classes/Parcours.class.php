<?php

class Parcours {
    private $vil_num1;
    private $vil_num2;
    private $par_km;
    private $par_num;

    public function __construct($valeurs = array()) {
        if (!empty($valeurs)) $this->affecte($valeurs);
    }

    public function affecte($donnees) {
        foreach ($donnees as $attribut => $valeur) {
            switch ($attribut) {
                case 'vil_num1':
                    $this->setVilNum1($valeur);
                    break;
                case 'vil_num2':
                    $this->setVilNum2($valeur);
                    break;
                case 'par_km':
                    $this->setParKm($valeur);
                    break;
                case 'par_num':
                    $this->setParNum($valeur);
                    break;
            }
        }
    }

    public function getVilNum1() {
        return $this->vil_num1;
    }

    public function setVilNum1($vil_num1): void {
        $this->vil_num1 = $vil_num1;
    }

    public function getVilNum2() {
        return $this->vil_num2;
    }

    public function setVilNum2($vil_num2): void {
        $this->vil_num2 = $vil_num2;
    }

    public function getParKm() {
        return $this->par_km;
    }

    public function setParKm($par_km): void {
        $this->par_km = $par_km;
    }

    public function getParNum() {
        return $this->par_num;
    }

    public function setParNum($par_num): void {
        $this->par_num = $par_num;
    }
}