<?php
class Planning {
    
    // Attributes
    private $idPlanning;
    private $onePurchasingFair;
    private $arrayPlanningDays;
    
    // Constructor
    public function __construct($onePurchasingFair, $arrayPlanningDays, $idPlanning = -1) {
        $this->idPlanning = $idPlanning;
        $this->onePurchasingFair = $onePurchasingFair;
        $this->arrayPlanningDays = $arrayPlanningDays;
    }

        // Getters & setters
    public function getIdPlanning() { return $this->idPlanning; }

    public function getOnePurchasingFair() { return $this->onePurchasingFair; }

    public function getArrayPlanningDays() { return $this->arrayPlanningDays; }

    public function setIdPlanning($idPlanning) { $this->idPlanning = $idPlanning; }

    public function setOnePurchasingFair($onePurchasingFair) { $this->onePurchasingFair = $onePurchasingFair; }

    public function setArrayPlanningDays($arrayPlanningDays) { $this->arrayPlanningDays = $arrayPlanningDays; }
}
?>

