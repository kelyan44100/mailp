<?php
class Log {
		
    // Attributes
    private $idLog;
    private $oneEnterprise; // Class Enterprise
    private $ipAddress;
    private $actionDateTime;

    // Constructor
    public function __construct($oneEnterprise, $ipAddress, $actionDateTime= "", $idLog = -1) {
        $this->idLog = $idLog;
        $this->oneEnterprise = $oneEnterprise;
        $this->ipAddress = $ipAddress;
        $this->actionDateTime = $actionDateTime;
    }

    // Getters & setters
    public function getIdLog() { return $this->idLog; }

    public function getOneEnterprise() { return $this->oneEnterprise; }

    public function getIpAddress() { return $this->ipAddress; }

    public function getActionDateTime() { return $this->actionDateTime; }

    public function setIdLog($idLog) { $this->idLog = $idLog; }

    public function setOneEnterprise($oneEnterprise) { $this->oneEnterprise = $oneEnterprise; }

    public function setIpAddress($ipAddress) { $this->ipAddress = $ipAddress; }

    public function setActionDateTime($actionDateTime) { $this->actionDateTime = $actionDateTime; }
}
?>