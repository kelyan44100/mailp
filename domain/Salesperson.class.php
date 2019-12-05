<?php
class Salesperson {
    
    // Attributes
    private $idSalesperson;
    private $civility;
    private $surname;
    private $name;
    private $dateDeletion;
    
    // Constructor
    public function __construct($civility, $surname, $name, $dateDeletion = "", $idSalesperson = -1) {
        $this->idSalesperson = $idSalesperson;
        $this->civility = $civility;
        $this->surname = $surname;
        $this->name = $name;
        $this->dateDeletion = $dateDeletion;
    }
    
    // Getters & setters
    public function getIdSalesperson() { return $this->idSalesperson; }

    public function getCivility() { return $this->civility; }

    public function getSurname() { return $this->surname; }

    public function getName() { return $this->name; }

    public function getDateDeletion() { return $this->dateDeletion; }

    public function setIdSalesperson($idSalesperson) { $this->idSalesperson = $idSalesperson; }

    public function setCivility($civility) { $this->civility = $civility; }

    public function setSurname($surname) { $this->surname = $surname; }

    public function setName($name) { $this->name = $name;  }

    public function setDateDeletion($dateDeletion) { $this->dateDeletion = $dateDeletion; }
}
?>