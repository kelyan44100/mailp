<?php
class TimeSlot {
    
    // Attributes
    private $idSlot;
    private $startDatetime;
    private $endDatetime;
    private $isLunchBreak;
    
    // Constructor
    public function __construct($idSlot, $startDatetime, $endDatetime, $isLunchBreak) {
        $this->idSlot = $idSlot;
        $this->startDatetime = $startDatetime;
        $this->endDatetime = $endDatetime;
        $this->isLunchBreak = $isLunchBreak;
    }

    // Getters & setters
    public function getIdSlot() { return $this->idSlot; }

    public function getStartDatetime() { return $this->startDatetime; }

    public function getEndDatetime() { return $this->endDatetime; }
    
    public function getIsLunchBreak() { return $this->isLunchBreak; }

    public function setIdSlot($idSlot) { $this->idSlot = $idSlot; }

    public function setStartDatetime($startDatetime) { $this->startDatetime = $startDatetime; }

    public function setEndDatetime($endDatetime) { $this->endDatetime = $endDatetime; }
    
    public function setIsLunchBreak($isLunchBreak) { $this->isLunchBreak = $isLunchBreak; }
}
?>
