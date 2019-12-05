<?php
class QRCodeScan {
    
    // Attributes
    private $idQRCodeScan;
    private $onePurchasingFair;
    private $oneEnterprise;
    private $oneParticipant;
    private $scanDatetime;
    
    // Constructor
    public function __construct($onePurchasingFair, $oneEnterprise, $oneParticipant, $scanDatetime, $idQRCodeScan = -1) {
        $this->idQRCodeScan = $idQRCodeScan;
        $this->onePurchasingFair = $onePurchasingFair;
        $this->oneEnterprise = $oneEnterprise;
        $this->oneParticipant = $oneParticipant;
        $this->scanDatetime = $scanDatetime;
    }
    
    // Getters & setters
    public function getIdQRCodeScan() { return $this->idQRCodeScan; }

    public function getOnePurchasingFair() { return $this->onePurchasingFair; }

    public function getOneEnterprise() { return $this->oneEnterprise; }

    public function getOneParticipant() { return $this->oneParticipant; }

    public function getScanDatetime() { return $this->scanDatetime; }

    public function setIdQRCodeScan($idQRCodeScan) { $this->idQRCodeScan = $idQRCodeScan; }

    public function setOnePurchasingFair($onePurchasingFair) { $this->onePurchasingFair = $onePurchasingFair; }

    public function setOneEnterprise($oneEnterprise) { $this->oneEnterprise = $oneEnterprise; }

    public function setOneParticipant($oneParticipant) { $this->oneParticipant = $oneParticipant; }

    public function setScanDatetime($scanDatetime) { $this->scanDatetime = $scanDatetime; }
    
    // toString
    public function __toString() {
        $toString  = $this->getOnePurchasingFair()->getIdPurchasingFair().'|';
        $toString .= $this->getOneEnterprise()->getIdEnterprise().'|';
        $toString .= $this->getOneParticipant()->getIdParticipant().'|';
        return $toString();
    }
}
?>
