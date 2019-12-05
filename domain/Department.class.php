<?php
class Department {
    
    // Attributes
    private $idDepartment;
    private $name;
    
    // Constructor
    public function __construct($name, $idDepartment = -1) {
        $this->idDepartment = $idDepartment;
        $this->name = $name;
    }

    // Getters & setters
    public function getIdDepartment() { return $this->idDepartment; }

    public function getName() { return $this->name; }

    public function setIdDepartment($idDepartment) { $this->idDepartment = $idDepartment; }

    public function setName($name) { $this->name = $name; }
}
?>
