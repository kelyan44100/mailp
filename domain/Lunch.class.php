<?php
class Lunch implements JsonSerializable {
    
    // Attributes
    private $idLunch;
    private $oneEnterprise;
    private $onePurchasingFair;
    private $lunchesPlanned;
    private $lunchesCanceled;
    private $lunchesDetails;
    private $idParticipant;
    
    // Constructor
    public function __construct($oneEnterprise, $onePurchasingFair, $lunchesPlanned, $lunchesCanceled, $lunchesDetails, $idParticipant, $idLunch = -1) {
        $this->idLunch = $idLunch;
        $this->oneEnterprise = $oneEnterprise;
        $this->onePurchasingFair = $onePurchasingFair;
        $this->lunchesPlanned = $lunchesPlanned;
        $this->lunchesCanceled = $lunchesCanceled;
        $this->lunchesDetails = $lunchesDetails;
        $this->idParticipant = $idParticipant;
    }

    // Getters & setters
    public function getIdLunch() { return $this->idLunch; }

    public function getOneEnterprise() { return $this->oneEnterprise; }

    public function getOnePurchasingFair() { return $this->onePurchasingFair; }

    public function getLunchesPlanned() { return $this->lunchesPlanned; }

    public function getLunchesCanceled() { return $this->lunchesCanceled; }
    
    public function getLunchesDetails() { return $this->lunchesDetails; }

    public function getIdParticipant() { return $this->idParticipant; }

    public function setIdLunch($idLunch) { $this->idLunch = $idLunch; }

    public function setOneEnterprise($oneEnterprise) { $this->oneEnterprise = $oneEnterprise; }

    public function setOnePurchasingFair($onePurchasingFair) { $this->onePurchasingFair = $onePurchasingFair; }

    public function setLunchesPlanned($lunchesPlanned) { $this->lunchesPlanned = $lunchesPlanned; }

    public function setLunchesCanceled($lunchesCanceled) { $this->lunchesCanceled = $lunchesCanceled; }

    public function setLunchesDetails($lunchesDetails) { $this->lunchesDetails = $lunchesDetails; }

    public function setIdParticipant($idParticipant) { $this->idParticipant = $idParticipant; }
	
	// Specify data which should be serialized to JSON
    // http://php.net/manual/en/jsonserializable.jsonserialize.php
    public function jsonSerialize() {
        $array = [
            'idLunch' => $this->getIdLunch(),
            'oneEnterprise' => $this->getOneEnterprise()->getIdEnterprise(), 
            'onePurchasingFair' => $this->getOnePurchasingFair()->getIdPurchasingFair(), 
            'lunchesPlanned' => $this->getLunchesPlanned(), 
            'lunchesCanceled' => $this->getLunchesCanceled(), 
            'lunchesDetails' => $this->getLunchesDetails(),
            'idParticipant' => $this->getIdParticipant()
            ];
        return $array;
    }
}