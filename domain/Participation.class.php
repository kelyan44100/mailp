<?php

class Participation {
    
    // Attributes
    private $oneParticipant;
    private $onePurchasingFair;
    private $passwordParticipant;
    private $lunch;
    
    
    // Constructor
    function __construct($oneParticipant, $onePurchasingFair, $passwordParticipant, $lunch) {
        $this->oneParticipant = $oneParticipant;
        $this->onePurchasingFair = $onePurchasingFair;
        $this->passwordParticipant = $passwordParticipant;
        $this->lunch = $lunch;
    }

    // Getters & setters
    function getOneParticipant() { return $this->oneParticipant; }

    function getOnePurchasingFair() { return $this->onePurchasingFair; }

    function getPasswordParticipant() { return $this->passwordParticipant; }

    function getLunch() { return $this->lunch; }

    function setOneParticipant($oneParticipant) { $this->oneParticipant = $oneParticipant; }

    function setOnePurchasingFair($onePurchasingFair) { $this->onePurchasingFair = $onePurchasingFair; }

    function setPasswordParticipant($passwordParticipant) { $this->passwordParticipant = $passwordParticipant; }

    function setLunch($lunch) { $this->lunch = $lunch; }
}
?>

