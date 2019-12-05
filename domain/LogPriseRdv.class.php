<?php
class LogPriseRdv {
		
    // Attributes
    private $id;
    private $idEnterprise; // Class Enterprise
    private $actionDateTime;
    private $idPurchasingFair;
    private $jourSelect;

    // Constructor
    public function __construct($idEnterprise, $idPurchasingFair, $jourSelect, $actionDateTime = "", $id = -1) {
        $this->id = $id;
        $this->idEnterprise = $idEnterprise;
        $this->actionDateTime = $actionDateTime;
        $this->idPurchasingFair = $idPurchasingFair;
        $this->jourSelect = $jourSelect;
    }

    // Getters & setters
    public function getId() { return $this->id; }

    public function getIdEnterprise() { return $this->idEnterprise; }

    public function getActionDateTime() { return $this->actionDateTime; }

    public function getIdPurchasingFair() { return $this->idPurchasingFair; }

    public function getJourSelect() { return $this->jourSelect; }

    public function setId($id) { $this->id = $id; }

    public function setIdEnterprise($idEnterprise) { $this->idEnterprise = $idEnterprise; }

    public function setActionDateTime($actionDateTime) { $this->actionDateTime = $actionDateTime; }

    public function setIdPurchasingFair($idPurchasingFair) { $this->idPurchasingFair = $idPurchasingFair; }

    public function setJourSelect($jourSelect) { $this->jourSelect = $jourSelect; }
}
?>