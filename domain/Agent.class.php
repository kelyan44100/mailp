<?php
class Agent { //agents de sécurité
    
    // Attributes
    private $idAgent;
    private $civility;
    private $surname;
    private $name;
    private $addressLine1;
    private $addressLine2;
    private $providers;
    private $dateDeletion;
    
    // Constructor
    public function __construct($civility, $surname, $name, $addressLine1, $addressLine2, $providers, $dateDeletion = "", $idAgent = -1) {
        $this->idAgent = $idAgent;
        $this->civility = $civility;
        $this->surname = $surname;
        $this->name = $name;
        $this->addressLine1 = $addressLine1;
        $this->addressLine2 = $addressLine2;
        $this->providers = $providers;
        $this->dateDeletion = $dateDeletion;
    }
    
    // Getters & setters
    public function getIdAgent() { return $this->idAgent; }

    public function getCivility() { return $this->civility; }

    public function getSurname() { return $this->surname; }

    public function getName() { return $this->name; }

    public function getAddressLine1() { return $this->addressLine1; }

    public function getAddressLine2() { return $this->addressLine2; }    
    
    public function getProviders() { return $this->providers; }

    public function getDateDeletion() { return $this->dateDeletion; }

    public function setIdAgent($idAgent) { $this->idAgent = $idAgent; }

    public function setCivility($civility) { $this->civility = $civility; }

    public function setSurname($surname) { $this->surname = $surname; }

    public function setName($name) { $this->name = $name; }

    public function setAddressLine1($addressLine1) { $this->addressLine1 = $addressLine1; }

    public function setAddressLine2($addressLine2) { $this->addressLine2 = $addressLine2; }
     
    public function setProviders($providers) { $this->providers = $providers; }

    public function setDateDeletion($dateDeletion) { $this->dateDeletion = $dateDeletion; }
    
    // ToString
    public function __toString() { 
        return $this->getCivility().' '.$this->getSurname().' '.$this->getName();
    }
}