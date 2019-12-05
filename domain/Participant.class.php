<?php
//
class Participant implements JsonSerializable {

    // Attributes
    private $idParticipant;
    private $civility;
    private $surname;
    private $name;
    private $email;
    private $dateDeletion;
    private $manyUnavailabilitiesSp;
    private $positionSalespersonListSalesperson;

    // Constructor
    public function __construct($civility, $surname, $name, $email, $dateDeletion = "", $idParticipant = -1) {
        $this->idParticipant = $idParticipant;
        $this->civility = $civility;
        $this->surname = $surname;
        $this->name = $name;
        $this->email = $email;
        $this->dateDeletion = $dateDeletion;
        $this->manyUnavailabilitiesSp = array();
        $this->positionSalespersonListSalesperson = -1;
    }
    
    // Getters & setters
    public function getIdParticipant() { return $this->idParticipant; }

    public function getCivility() { return $this->civility; }
    
    public function getCivilitySmall() { return ( $this->civility == 'Monsieur' ? 'M.' : 'MME' ); }

    public function getSurname() { return $this->surname; }

    public function getName() { return $this->name; }

    public function getEmail() { return $this->email; }

    public function getDateDeletion() { return $this->dateDeletion; }
    
    public function getManyUnavailabilitiesSp() { return $this->manyUnavailabilitiesSp; }
    
    public function getPositionSalespersonListSalesperson() { return $this->positionSalespersonListSalesperson; }

    public function setIdParticipant($idParticipant) { $this->idParticipant = $idParticipant; }

    public function setCivility($civility) { $this->civility = $civility; }

    public function setSurname($surname) { $this->surname = $surname; }

    public function setName($name) { $this->name = $name; }

    public function setEmail($email) { $this->email = $email; }

    public function setDateDeletion($dateDeletion) { $this->dateDeletion = $dateDeletion; }
    
    public function setManyUnavailabilitiesSp($manyUnavailabilitiesSp) { $this->manyUnavailabilitiesSp = $manyUnavailabilitiesSp; }
    
    public function setPositionSalespersonListSalesperson($positionSalespersonListSalesperson) { $this->positionSalespersonListSalesperson = $positionSalespersonListSalesperson; }
     
    public function getIconMaleOrFemale() {
        return ($this->getCivility() == 'Madame') ? '<i class="fa fa-female" aria-hidden="true"></i>' : '<i class="fa fa-male" aria-hidden="true"></i>';
    }
    // toString
    public function __toString() { return $this->getIconMaleOrFemale().' '.$this->getCivility(). ' '.$this->getSurname().' '.$this->getName(). ' / '.$this->getEmail(); }
    
    public function getFourInformations() { return $this->getCivility(). ' '.$this->getSurname().' '.$this->getName(). ' / '.$this->getEmail(); }
    
    // Specify data which should be serialized to JSON
    // http://php.net/manual/en/jsonserializable.jsonserialize.php
    public function jsonSerialize() {
        $array = [
            'idParticipant' => $this->getIdParticipant(), 
            'civility' => $this->getCivility(), 
            'surname' => $this->getSurname(), 
            'name' => $this->getName(), 
            'email' => $this->getEmail(), 
            'dateDeletion' => $this->getDateDeletion()
            ];
        return $array;
    }
}
?>