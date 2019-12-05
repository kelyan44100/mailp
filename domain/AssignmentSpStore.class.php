<?php
class AssignmentSpStore implements JsonSerializable {
    
    // Attributes    
    private $oneParticipant;
    private $oneStore;
    private $oneProvider;
    private $onePurchasingFair;
    
    // Constructor
    function __construct($oneParticipant, $oneStore, $oneProvider, $onePurchasingFair) {
        $this->oneParticipant = $oneParticipant;
        $this->oneStore = $oneStore;
        $this->oneProvider = $oneProvider;
        $this->onePurchasingFair = $onePurchasingFair;
    }
    
    // Getters & setters
    function getOneParticipant() { return $this->oneParticipant; }

    function getOneStore() { return $this->oneStore; }

    function getOneProvider() { return $this->oneProvider; }

    function getOnePurchasingFair() { return $this->onePurchasingFair; }

    function setOneParticipant($oneParticipant) { $this->oneParticipant = $oneParticipant; }

    function setOneStore($oneStore) { $this->oneStore = $oneStore; }

    function setOneProvider($oneProvider) { $this->oneProvider = $oneProvider; }

    function setOnePurchasingFair($onePurchasingFair) { $this->onePurchasingFair = $onePurchasingFair; }
    
    // Specify data which should be serialized to JSON
    // http://php.net/manual/en/jsonserializable.jsonserialize.php
    public function jsonSerialize() {
        $array = [
            'oneParticipant' => $this->getOneParticipant()->getIdParticipant(), 
            'oneStore' => $this->getOneStore()->getIdEnterprise(),
            'oneProvider' => $this->getOneProvider()->getIdEnterprise(),
            'onePurchasingFair' => $this->getOnePurchasingFair()->getIdPurchasingFair()
            ];
        return $array;
    }
}
?>