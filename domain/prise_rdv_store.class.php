<?php
class prise_rdv_store implements JsonSerializable {
    
    // Attributes
    private $idRDV;
    private $idStore;
    private $idFournisseur;
    private $idCommercial;
    private $idPurchasingFair;
    private $startDatetime;
    private $endDateTime;
    private $jourString;
    private $startString;
    private $endString;

    // Constructor
    public function __construct($idStore, $idFournisseur, $idCommercial, $idPurchasingFair, $startDatetime, $endDateTime, $jourString, $startString, $endString, $idRDV = -1) {
        $this->idRDV = $idRDV;
        $this->idStore = $idStore;
        $this->idFournisseur = $idFournisseur;
        $this->idCommercial = $idCommercial;
        $this->idPurchasingFair = $idPurchasingFair;
        $this->startDatetime = $startDatetime;
        $this->endDateTime = $endDateTime;
        $this->jourString = $jourString;
        $this->startString = $startString;
        $this->endString = $endString;
    }

    // Getters & setters
    public function getIdRDV() { return $this->idRDV; }

    public function getIdStore() { return $this->idStore; }
    
    public function getIdFournisseur() { return $this->idFournisseur; }

    public function getIdCommercial() { return $this->idCommercial; }

    public function getIdPurchasingFair() { return $this->idPurchasingFair; }

    public function getStartDatetime() { return $this->startDatetime; }
    
    public function getDayFromStartDatetime() { return DateTime::createFromFormat('Y-m-d H:i:s', $this->getStartDatetime())-> format('d'); }
    
    public function getMonthFromStartDatetime() { return DateTime::createFromFormat('Y-m-d H:i:s', $this->getStartDatetime())-> format('m'); }
    
    public function getYearFromStartDatetime() { return DateTime::createFromFormat('Y-m-d H:i:s', $this->getStartDatetime())-> format('Y'); }
    
    public function getHoursFromStartDatetime() { return DateTime::createFromFormat('Y-m-d H:i:s', $this->getStartDatetime())-> format('H'); }
    
    public function getMinutesFromStartDatetime() { return DateTime::createFromFormat('Y-m-d H:i:s', $this->getStartDatetime())-> format('i'); }

    public function getEndDateTime() { return $this->endDateTime; }

    public function getDayFromEndDatetime() { return DateTime::createFromFormat('Y-m-d H:i:s', $this->getEndDatetime())-> format('d'); }
    
    public function getMonthFromEndDatetime() { return DateTime::createFromFormat('Y-m-d H:i:s', $this->getEndDatetime())-> format('m'); }
    
    public function getYearFromEndDatetime() { return DateTime::createFromFormat('Y-m-d H:i:s', $this->getEndDatetime())-> format('Y'); }
    
    public function getHoursFromEndDatetime() { return DateTime::createFromFormat('Y-m-d H:i:s', $this->getEndDatetime())-> format('H'); }
    
    public function getMinutesFromEndDatetime() { return DateTime::createFromFormat('Y-m-d H:i:s', $this->getEndDatetime())-> format('i'); }

    public function getJourString() { return $this->jourString; }

    public function getStartString() { return $this->startString; }

    public function getEndString() { return $this->endString; }

    public function setIdRDV($idRDV) { $this->idRDV = $idRDV; }

    public function setIdStore($idStore) { $this->idStore = $idStore; }

    public function setIdFournisseur($idFournisseur) { $this->idFournisseur = $idFournisseur; }

    public function setIdCommercial($idCommercial) { $this->idCommercial = $idCommercial; }

    public function setIdPurchasingFair($idPurchasingFair) { $this->idPurchasingFair = $idPurchasingFair; }

    public function setStartDatetime($startDatetime) { $this->startDatetime = $startDatetime; }

    public function setEndDateTime($endDateTime) { $this->endDateTime = $endDateTime; }

    public function setJourString($jourString) { $this->jourString = $jourString; }

    public function setStartString($startString) { $this->startString = $startString; }

    public function setEndString($endString) { $this->endString = $endString; }
    
    // Specify data which should be serialized to JSON
    // http://php.net/manual/en/jsonserializable.jsonserialize.php
    public function jsonSerialize() {
        $array = [
            'idRDV' => $this->getIdRDV(),
            'idStore' => $this->getIdStore(),
            'idFournisseur' => $this->getIdFournisseur(),
            'idCommercial' => $this->getIdCommercial(),
            'idPurchasingFair' => $this->getIdPurchasingFair(), 
            'startDatetimePf' => DateTime::createFromFormat('Y-m-d H:i:s', $this->getStartDatetime())->format('d/m/Y H:i:s'), 
            'endDatetimePf' => DateTime::createFromFormat('Y-m-d H:i:s', $this->getEndDatetime())->format('d/m/Y H:i:s'), 
            ];
        return $array;
    }
}
?>