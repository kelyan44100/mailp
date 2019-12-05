<?php
class EnterpriseContact {
    
    // Attributes
    private $idEnterpriseContact;
    private $civility;
    private $surname;
    private $name;
    private $email;
    private $oneEnterprise; // Class Enterprise
    private $registrationDate;

    // Constructor
    public function __construct($civility, $surname, $name, $email, $oneEnterprise, $registrationDate = "", $idEnterpriseContact = -1) {
        $this->idEnterpriseContact = $idEnterpriseContact;
        $this->civility = $civility;
        $this->surname = $surname;
        $this->name = $name;
        $this->email = $email;
        $this->oneEnterprise = $oneEnterprise;
        $this->registrationDate = $registrationDate;
    }
        
    // Getters & Setters
    public function getIdEnterpriseContact() { return $this->idEnterpriseContact; }

    public function getCivility() { return $this->civility; }

    public function getSurname() { return $this->surname; }

    public function getName() { return $this->name; }

    public function getEmail() { return $this->email; }
    
    public function getOneEnterprise() { return $this->oneEnterprise; }
        
    public function getRegistrationDate() { return $this->registrationDate; }

    public function setIdEnterpriseContact($idEnterpriseContact) { $this->idEnterpriseContact = $idEnterpriseContact; }

    public function setCivility($civility) { $this->civility = $civility; }

    public function setSurname($surname) { $this->surname = $surname; }

    public function setName($name) { $this->name = $name; }

    public function setEmail($email) { $this->email = $email; }

    public function setOneEnterprise($oneEnterprise) { $this->oneEnterprise = $oneEnterprise; }
    
    public function setRegistrationDate($registrationDate) { $this->registrationDate = $registrationDate; }
}
?>