<?php
require_once dirname ( __FILE__ ) . '/Participant.class.php' ;
require_once dirname ( __FILE__ ) . '/Department.class.php' ;
//affectation des participants à un departement
class AssignmentParticipantDepartment implements JsonSerializable { 
    
    // Attributes
    private $oneParticipant;
    private $oneDepartment;
     
    // Constructor
    public function __construct($oneParticipant, $oneDepartment) {
        $this->oneParticipant = $oneParticipant;
        $this->oneDepartment = $oneDepartment;
    }
    
    // Getters & setters
    public function getOneParticipant() { return $this->oneParticipant; }

    public function getOneDepartment() { return $this->oneDepartment; }

    public function setOneParticipant($oneParticipant) { $this->oneParticipant = $oneParticipant; }

    public function setOneDepartment($oneDepartment) { $this->oneDepartment = $oneDepartment; }
    
    // Specify data which should be serialized to JSON
    // http://php.net/manual/en/jsonserializable.jsonserialize.php
    public function jsonSerialize() {
        $array = [
            'oneParticipant' => $this->getOneParticipant()->getIdParticipant(), 
            'oneDepartment' => $this->getOneDepartment()->getIdDepartment()
            ];
        return $array;
    }
}