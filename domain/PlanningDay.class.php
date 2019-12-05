<?php
class PlanningDay {
    
    // Attributes
    private $idPlanningDay;
    private $startDatetime;
    private $endDatetime;
    private $arrayTimeSlots;
    private $arraySalespersons;
    
    // Constructor
    public function __construct($idPlanningDay, $startDatetime, $endDatetime, $arrayTimeSlots, $arraySalespersons) {
        $this->idPlanningDay = $idPlanningDay;
        $this->startDatetime = $startDatetime;
        $this->endDatetime = $endDatetime;
        $this->arrayTimeSlots = $arrayTimeSlots;
        $this->arraySalespersons = $arraySalespersons;
    }

    // Getters & setters
    public function getIdPlanningDay() { return $this->idPlanningDay; }

    public function getStartDatetime() { return $this->startDatetime; }

    public function getEndDatetime() { return $this->endDatetime; }

    public function getArrayTimeSlots() { return $this->arrayTimeSlots; }
    
    public function getArraySalespersons() { return $this->arraySalespersons; }

    public function setIdPlanningDay($idPlanningDay) { $this->idPlanningDay = $idPlanningDay; }

    public function setStartDatetime($startDatetime) {  $this->startDatetime = $startDatetime; }

    public function setEndDatetime($endDatetime) { $this->endDatetime = $endDatetime; }

    public function setArrayTimeSlots($arrayTimeSlots) { $this->arrayTimeSlots = $arrayTimeSlots; }
    
    public function setArraySalespersons($arraySalespersons) { $this->arraySalespersons = $arraySalespersons; }
}
?>

