<?php
class ProviderPresent implements JsonSerializable {
    
    // Attributes
    private $oneProvider; // Class Enterprise
    private $onePurchasingFair; // Class PurchasingFair
    
    // Constructor
    public function __construct($oneProvider, $onePurchasingFair) {
        $this->oneProvider = $oneProvider;
        $this->onePurchasingFair = $onePurchasingFair;
    }

    // Getters & setters
    public function getOneProvider() { return $this->oneProvider; }

    public function getOnePurchasingFair() { return $this->onePurchasingFair; }

    public function setOneProvider($oneProvider) { $this->oneProvider = $oneProvider; }

    public function setOnePurchasingFair($onePurchasingFair) { $this->onePurchasingFair = $onePurchasingFair; }
    
    // Specify data which should be serialized to JSON
    // http://php.net/manual/en/jsonserializable.jsonserialize.php
    public function jsonSerialize() {
        $array = [
            'oneProvider' => $this->getOneProvider()->getIdEnterprise(),
            'onePurchasingFair' => $this->getOnePurchasingFair()->getIdPurchasingFair()
            ];
        return $array;
    }
}
