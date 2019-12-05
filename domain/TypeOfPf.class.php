<?php

class TypeOfPf {
    
    // Attributes
    private $idTypeOfPf;
    private $nameTypeOfPf;
    
    // Constructor
    function __construct($nameTypeOfPf, $idTypeOfPf = -1) {
        $this->idTypeOfPf = $idTypeOfPf;
        $this->nameTypeOfPf = $nameTypeOfPf;
    }
    
    // Getters & setters
    function getIdTypeOfPf() { return $this->idTypeOfPf; }

    function getNameTypeOfPf() { return $this->nameTypeOfPf; }

    function setIdTypeOfPf($idTypeOfPf) { $this->idTypeOfPf = $idTypeOfPf; }

    function setNameTypeOfPf($nameTypeOfPf) { $this->nameTypeOfPf = $nameTypeOfPf; }    
}
?>

