<?php
class StoreWorkforce {
    
    // Attributes
    private $oneEnterprise;
    private $outerClothing;
    private $underClothing;
    private $shoes;
    
    // Constructor
    public function __construct($oneEnterprise, $outerClothing, $underClothing, $shoes) {
        $this->oneEnterprise = $oneEnterprise;
        $this->outerClothing = $outerClothing;
        $this->underClothing = $underClothing;
        $this->shoes = $shoes;
    }

    // Getters & setters
    public function getOneEnterprise() { return $this->oneEnterprise; }

    public function getOuterClothing() { return $this->outerClothing; }

    public function getUnderClothing() { return $this->underClothing; }

    public function getShoes() { return $this->shoes; }

    public function setOneEnterprise($oneEnterprise) { $this->oneEnterprise = $oneEnterprise; }

    public function setOuterClothing($outerClothing) { $this->outerClothing = $outerClothing; }

    public function setUnderClothing($underClothing) { $this->underClothing = $underClothing; }

    public function setShoes($shoes) { $this->shoes = $shoes; }
}