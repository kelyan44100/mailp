<?php
class SpecialGuest implements JsonSerializable {
    
    // Attributes
    private $idSpecialGuest;
    private $oneEnterprise;
    private $onePurchasingFair;
    private $civility;
    private $surname;
    private $name;
    private $days;
    
    // Constructor
    public function __construct($oneEnterprise, $onePurchasingFair, $civility, $surname, $name, $days, $idSpecialGuest= -1) {
        $this->idSpecialGuest = $idSpecialGuest;
        $this->oneEnterprise = $oneEnterprise;
        $this->onePurchasingFair = $onePurchasingFair;
        $this->civility = $civility;
        $this->surname = $surname;
        $this->name = $name;
        $this->days = $days;
    }

    // Getters & setters
    public function getIdSpecialGuest() { return $this->idSpecialGuest; }

    public function getOneEnterprise() { return $this->oneEnterprise; }

    public function getOnePurchasingFair() { return $this->onePurchasingFair; }

    public function getCivility() { return $this->civility; }

    public function getSurname() { return $this->surname; }

    public function getName() { return $this->name; }

    public function getDays() { return $this->days; }

    public function setIdSpecialGuest($idSpecialGuest) { $this->idSpecialGuest = $idSpecialGuest; }

    public function setOneEnterprise($oneEnterprise) { $this->oneEnterprise = $oneEnterprise; }

    public function setOnePurchasingFair($onePurchasingFair) { $this->onePurchasingFair = $onePurchasingFair; }

    public function setCivility($civility) { $this->civility = $civility; }

    public function setSurname($surname) { $this->surname = $surname; }

    public function setName($name) { $this->name = $name; }

    public function setDays($days) { $this->days = $days; }

    public function getIconMaleOrFemale() {
        return ($this->getCivility() == 'Madame') ? '<i class="fa fa-female" aria-hidden="true"></i>' : '<i class="fa fa-male" aria-hidden="true"></i>';
    }
    // toString
    public function __toString() { return $this->getIconMaleOrFemale().' '.$this->getCivility(). ' '.$this->getSurname().' '.$this->getName(). ' / Jours : '.$this->getDays(); }
    
    public function getFourInformations() { return $this->getCivility(). ' '.$this->getSurname().' '.$this->getName().' ('.$this->getOneEnterprise()->getName().')'; }
    
    // Specify data which should be serialized to JSON
    // http://php.net/manual/en/jsonserializable.jsonserialize.php
    public function jsonSerialize() {
        $array = [
            'idSpecialGuest' => $this->getIdSpecialGuest(), 
            'oneEnterprise' => $this->getOneEnterprise()->getIdEnterprise(), 
            'onePurchasingFair' => $this->getOnePurchasingFair()->getIdPurchasingFair(), 
            'civility' => $this->getCivility(), 
            'surname' => $this->getSurname(), 
            'name' => $this->getName(),
            'days' => $this->getDays()
            ];
        return $array;
    }
}
?>