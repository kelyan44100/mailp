<?php
class TypeOfProvider {
    
    // Attributes
    private $idTypeOfProvider;
    private $nameTypeOfProvider;
    
    // Constructor
    function __construct($nameTypeOfProvider, $idTypeOfProvider = -1) {
        $this->idTypeOfProvider = $idTypeOfProvider;
        $this->nameTypeOfProvider = $nameTypeOfProvider;
    }
    
    // Getters & setters
    function getIdTypeOfProvider() { return $this->idTypeOfProvider; }

    function getNameTypeOfProvider() { return $this->nameTypeOfProvider; }

    function setIdTypeOfProvider($idTypeOfProvider) { $this->idTypeOfProvider = $idTypeOfProvider; }

    function setNameTypeOfProvider($nameTypeOfProvider) { $this->nameTypeOfProvider = $nameTypeOfProvider; }    
}
?>

