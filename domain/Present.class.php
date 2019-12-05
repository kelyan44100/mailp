<?php
class Present implements JsonSerializable {

    // Attributes
    private $oneEnterprise;
    private $oneParticipant;
    private $onePurchasingFair;
    private $presentDetails;

    // Constructor
    public function __construct($oneEnterprise, $oneParticipant, $onePurchasingFair, $presentDetails) {
        $this->oneEnterprise = $oneEnterprise;
        $this->oneParticipant = $oneParticipant;
        $this->onePurchasingFair = $onePurchasingFair;
        $this->presentDetails = $presentDetails;
    }

        // Getters & Setters
    public function getOneEnterprise() { return $this->oneEnterprise; }

    public function getOneParticipant() { return $this->oneParticipant; }

    public function getOnePurchasingFair() { return $this->onePurchasingFair; }
    
    public function getPresentDetails() { return $this->presentDetails; }
 
    public function setOneEnterprise($oneEnterprise) { $this->oneEnterprise = $oneEnterprise; }

    public function setOneParticipant($oneParticipant) { $this->oneParticipant = $oneParticipant; }

    public function setOnePurchasingFair($onePurchasingFair) { $this->onePurchasingFair = $onePurchasingFair; }
    
    public function setPresentDetails($presentDetails) { $this->presentDetails = $presentDetails; }
	
	// Specify data which should be serialized to JSON
    // http://php.net/manual/en/jsonserializable.jsonserialize.php
    public function jsonSerialize() {
        $array = [
            'oneEnterprise' => $this->getOneEnterprise()->getIdEnterprise(), 
            'oneParticipant' => $this->getOneParticipant()->getIdParticipant(), 
            'onePurchasingFair' => $this->getOnePurchasingFair()->getIdPurchasingFair(), 
            'presentDetails' => $this->getPresentDetails() 
            ];
        return $array;
    }
}
?>