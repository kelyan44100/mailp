<?php
class Requirement {
		
    // Attributes
    private $idRequirement;
    private $oneStore; // Class Enterprise - -Added
    private $oneProvider; // Class Enterprise -- Added
    private $onePurchasingFair; // Class PurchasingFair -- Added
    private $numberOfHours;

    // Constructor
    public function __construct($oneStore, $oneProvider, $onePurchasingFair, $numberOfHours, $idRequirement = -1) {
        $this->idRequirement = $idRequirement;
        $this->oneStore = $oneStore;
        $this->oneProvider = $oneProvider;
        $this->onePurchasingFair = $onePurchasingFair;
        $this->numberOfHours = $numberOfHours;
    }

    // Getters & setters
    public function getIdRequirement() { return $this->idRequirement; }

    public function getOneStore() { return $this->oneStore; }

    public function getOneProvider() { return $this->oneProvider; }

    public function getOnePurchasingFair() { return $this->onePurchasingFair; }

    public function getNumberOfHours() { return $this->numberOfHours; }
    
    public function getNumberOfHoursAlreadyRegistered() {
        
        $h = 'h';
        $m = 'm';
        $numberOfHoursClient = explode('.', $this->getNumberOfHours());
        
        return $numberOfHoursClient[0].$h.( $numberOfHoursClient[1] == '50' ? '30' : '00').$m;
    }

    public function setIdRequirement($idRequirement) { $this->idRequirement = $idRequirement; }

    public function setOneStore($oneStore) { $this->oneStore = $oneStore; }

    public function setOneProvider($oneProvider) { $this->oneProvider = $oneProvider; }

    public function setOnePurchasingFair($onePurchasingFair) { $this->onePurchasingFair = $onePurchasingFair; }

    public function setNumberOfHours($numberOfHours) { $this->numberOfHours = $numberOfHours; }
}
?>