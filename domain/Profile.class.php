<?php
class Profile {

    // Attributes
    private $idProfile;
    private $name;
    private $dateDeletion;

    // Constructor
    public function __construct($name, $dateDeletion = "", $idProfile = -1) {
        $this->idProfile = $idProfile;
        $this->name = $name;
        $this->dateDeletion = $dateDeletion;
    }

    // Getters & Setters
    public function getIdProfile() { return $this->idProfile; }

    public function getName() { return $this->name; }

    public function getDateDeletion() { return $this->dateDeletion; }

    public function setIdProfile($idProfile) { $this->idProfile = $idProfile; }

    public function setName($name) { $this->name = $name; }

    public function setDateDeletion($dateDeletion) { $this->dateDeletion = $dateDeletion; }
}
?>