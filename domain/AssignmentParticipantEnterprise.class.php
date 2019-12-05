<?php
require_once dirname ( __FILE__ ) . '/Participant.class.php' ;
require_once dirname ( __FILE__ ) . '/Enterprise.class.php' ;

class AssignmentParticipantEnterprise implements JsonSerializable {
    
    // Attributes
    private $oneParticipant;
    private $oneEnterprise;

    // Constructor
    public function __construct($oneParticipant, $oneEnterprise) {
        $this->oneParticipant = $oneParticipant;
        $this->oneEnterprise = $oneEnterprise;
    }
    
    // Getters & setters
    public function getOneParticipant() { return $this->oneParticipant; }
   
    public function getOneEnterprise() { return $this->oneEnterprise; }

    public function setOneParticipant($oneParticipant) { $this->oneParticipant = $oneParticipant; }
    
    public function setOneEnterprise($oneEnterprise) { $this->oneEnterprise = $oneEnterprise; }
    
    // Specify data which should be serialized to JSON
    // http://php.net/manual/en/jsonserializable.jsonserialize.php
    public function jsonSerialize() {
        $array = [
            'oneParticipant' => $this->getOneParticipant()->getIdParticipant(), 
            'oneEnterprise' => $this->getOneEnterprise()->getIdEnterprise()
            ];
        return $array;
    }
}