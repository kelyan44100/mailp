<?php
class Unavailability {
    
    // Attributes
    private $idUnavailability;
    private $startDatetime;
    private $endDatetime;
    private $oneEnterprise; // Enterprise
    private $onePurchasingFair; // PurchasingFair
    private $dateDeletion;
    
    // Constructor
    public function __construct($startDatetime, $endDatetime, $oneEnterprise, $onePurchasingFair, $dateDeletion = "", $idUnavailability = -1) {
        $this->idUnavailability = $idUnavailability;
        $this->startDatetime = $startDatetime;
        $this->endDatetime = $endDatetime;
        $this->oneEnterprise = $oneEnterprise;
        $this->onePurchasingFair = $onePurchasingFair;
        $this->dateDeletion = $dateDeletion;
    }
    
    // Getters & setters
    public function getIdUnavailability() {  return $this->idUnavailability; }

    public function getStartDatetime() {  return $this->startDatetime; }

    public function getEndDatetime() { return $this->endDatetime; }

    public function getOneEnterprise() { return $this->oneEnterprise; }

    public function getOnePurchasingFair() { return $this->onePurchasingFair; }

    public function getDateDeletion() { return $this->dateDeletion; }

    public function setIdUnavailability($idUnavailability) { $this->idUnavailability = $idUnavailability; }

    public function setStartDatetime($startDatetime) { $this->startDatetime = $startDatetime; }

    public function setEndDatetime($endDatetime) {  $this->endDatetime = $endDatetime; }

    public function setOneEnterprise($oneEnterprise) { $this->oneEnterprise = $oneEnterprise; }

    public function setOnePurchasingFair($onePurchasingFair) { $this->onePurchasingFair = $onePurchasingFair; }

    public function setDateDeletion($dateDeletion) { $this->dateDeletion = $dateDeletion; }

    public function setTimeStartDateTime($timeToSet) { $this->setStartDatetime(substr($this->getStartDatetime(), 0, 10).' '.$timeToSet); } // Change time of $startDatetime
	
    public function setTimeEndDateTime($timeToSet) { $this->setEndDatetime(substr($this->getEndDatetime(), 0, 10).' '.$timeToSet); } // Change time of $endDatetime
	
    /*
     * Convert a daterange like that '01/08/2017 12:00:00 - 02/08/2017 12:00:00' to 'date_a' & 'date_b' (MySQL DATETIME() Format YYYY-MM-DD HH:MM:SS)
     */
    public static function convertDateRangeToMySqlFormat($dateRange) {
        $firstDate = substr($dateRange, 0, 19); // 1st part of daterange (before '-')
        $secondDate = substr($dateRange, 22); // 2nd part of daterange (after '-')
        $startDateTime = DateTime::createFromFormat('d/m/Y H:i:s', $firstDate)->format('Y-m-d H:i:s');
        $endDateTime = DateTime::createFromFormat('d/m/Y H:i:s', $secondDate)->format('Y-m-d H:i:s');
        return array('startDatetime' => $startDateTime, 'endDatetime' => $endDateTime);
    }

    /*
     * Convert two MySQL DATETIME() Format YYYY-MM-DD HH:MM:SS to daterange format like that '01/08/2017 12:00:00' and '02/08/2017 12:00:00'
     */
    public static function convertTwoMySqlDatetimeToDateRangeFormat($tartDatetime, $endDatetime) {
        $firstDate = DateTime::createFromFormat('Y-m-d H:i:s', $tartDatetime)->format('d/m/Y H:i:s');
        $secondDate = DateTime::createFromFormat('Y-m-d H:i:s', $endDatetime)->format('d/m/Y H:i:s');
        return array('firstDate' => $firstDate, 'secondDate' => $secondDate);
    }
}
?>