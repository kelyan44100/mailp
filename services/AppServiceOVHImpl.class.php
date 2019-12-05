<?php
require_once dirname ( __FILE__ ) . '/AppServiceOVH.class.php';

// Don't require these classes again, require only AppServiceOVH.class on each page

class AppServiceOVHImpl implements AppServiceOVH {

    // Attributes are xDAOOVH onjects
    private $assignmentParticipantDepartmentDAOOVH;
    private $assignmentParticipantEnterpriseDAOOVH;
    private $assignmentSpStoreDAOOVH;
    private $departmentDAOOVH;
    private $enterpriseDAOOVH;
    private $enterpriseContactDAOOVH;
    private $logDAOOVH;
    private $lunchDAOOVH;
    private $participantDAOOVH;
    private $participationDAOOVH;
    private $profileDAOOVH;
    private $providerPresentDAOOVH;
    private $purchasingFairDAOOVH;
    private $qrcodeScanDAOOVH;
    private $requirementDAOOVH;
    private $salespersonDAOOVH;
    private $specialGuestDAOOVH;
    private $statDAOOVH;
    private $typeofPfDAOOVH;
    private $typeOfProviderDAOOVH;
    private $unavailabilityDAOOVH;
    private $unavailabilitySpDAOOVH;
    
    private static $instance = null; // For Singleton use

    // Constructor
    function __construct() {
        $this->assignmentParticipantDepartmentDAOOVH = new AssignmentParticipantDepartmentDAOOVH();
        $this->assignmentParticipantEnterpriseDAOOVH = new AssignmentParticipantEnterpriseDAOOVH();
        $this->assignmentSpStoreDAOOVH = new AssignmentSpStoreDAOOVH();
        $this->departmentDAOOVH = new DepartmentDAOOVH();
        $this->enterpriseDAOOVH = new EnterpriseDAOOVH();
        $this->enterpriseContactDAOOVH = new EnterpriseContactDAOOVH();
        $this->logDAOOVH = new LogDAOOVH(); 
        $this->lunchDAOOVH = new LunchDAOOVH();
        $this->participantDAOOVH = new ParticipantDAOOVH();
        $this->participationDAOOVH = new ParticipationDAOOVH();
        $this->profileDAOOVH = new ProfileDAOOVH();
        $this->providerPresentDAOOVH = new ProviderPresentDAOOVH();
        $this->qrcodeScanDAOOVH = new QRCodeScanDAOOVH(); 
        $this->purchasingFairDAOOVH = new PurchasingFairDAOOVH(); 
        $this->requirementDAOOVH = new RequirementDAOOVH(); 
        $this->salespersonDAOOVH = new SalespersonDAOOVH();
        $this->specialGuestDAOOVH = new SpecialGuestDAOOVH(); 
        $this->statDAOOVH = new StatDAOOVH();
        $this->typeofPfDAOOVH = new TypeOfPfDAOOVH();
        $this->typeOfProviderDAOOVH = new TypeOfProviderDAOOVH();
        $this->unavailabilityDAOOVH = new UnavailabilityDAOOVH(); 
        $this->unavailabilitySpDAOOVH = new UnavailabilitySpDAOOVH(); 
    }
    
    // Singleton
    public static function getInstance() {

        if(is_null(self::$instance)) { self::$instance = new AppServiceOVHImpl(); }

        return self::$instance;
    }
   
    private function __clone() { } // Method of cloning also in private

    /* ------------------------------------------------ UnavailabilityDAOOVH methods ------------------------------------------------  */
    public function createUnavailability($startDatetime, $endDatetime, $idEnterprise, $idPurchasingFair) { return $createdUnavailability = new Unavailability($startDatetime, $endDatetime, $idEnterprise, $idPurchasingFair); }
    public function deactivateUnavailability(Unavailability $unavailability) { return $this->unavailabilityDAOOVH->deactivate($unavailability); }
    public function deleteUnavailability(Unavailability $unavailability) { return $this->unavailabilityDAOOVH->delete($unavailability); }
    public function findOneUnavailability($searchedIdUnavailability) { 
        $newUnavailability = $this->unavailabilityDAOOVH->findById($searchedIdUnavailability);
        if( $newUnavailability != null ) {  
            $newUnavailability->setOneEnterprise($this->findOneEnterprise($newUnavailability->getOneEnterprise())); // OneEnterprise = id
            $newUnavailability->setOnePurchasingFair($this->findOnePurchasingFair($newUnavailability->getOnePurchasingFair())); // OnePurchasingFair = id
            $newUnavailability->getOneEnterprise()->setOneProfile($this->findOneProfile($newUnavailability->getOneEnterprise()->getOneProfile()->getIdProfile()));
            $newUnavailability->getOneEnterprise()->setOneDepartment($this->findOneDepartment($newUnavailability->getOneEnterprise()->getOneDepartment()->getIdDepartment()));
        }
        return $newUnavailability;
    }
    public function findAllUnavailabilities() { 
        $arrayUnavailabilities = $this->unavailabilityDAOOVH->findAll();
        foreach($arrayUnavailabilities as $value) {
            $value->setOneEnterprise($this->findOneEnterprise($value->getOneEnterprise())); // OneEnterprise = id
            $value->setOnePurchasingFair($this->findOnePurchasingFair($value->getOnePurchasingFair())); // OnePurchasingFair = id
            $value->getOneEnterprise()->setOneProfile($this->findOneProfile($value->getOneEnterprise()->getOneProfile()->getIdProfile()));
            $value->getOneEnterprise()->setOneDepartment($this->findOneDepartment($value->getOneEnterprise()->getOneDepartment()->getIdDepartment()));
        }
        return $arrayUnavailabilities;
    }
    public function findEnterpriseUnavailabilities(Enterprise $enterprise, PurchasingFair $purchasingFair) {
        $arrayUnavailabilities = $this->unavailabilityDAOOVH->findEnterpriseUnavailabilities($enterprise, $purchasingFair); 
            foreach($arrayUnavailabilities as $value) {
            $value->setOneEnterprise($this->findOneEnterprise($value->getOneEnterprise())); // OneEnterprise = id
            $value->setOnePurchasingFair($this->findOnePurchasingFair($value->getOnePurchasingFair())); // OnePurchasingFair = id
            $value->getOneEnterprise()->setOneProfile($this->findOneProfile($value->getOneEnterprise()->getOneProfile()->getIdProfile()));
            $value->getOneEnterprise()->setOneDepartment($this->findOneDepartment($value->getOneEnterprise()->getOneDepartment()->getIdDepartment()));
        }
        return $arrayUnavailabilities;
    }
    public function saveUnavailability(Unavailability $unavailability) { return $this->unavailabilityDAOOVH->save($unavailability); }
    public function findPurchasingFairUnavailabilities(PurchasingFair $purchasingFair) { return $this->unavailabilityDAOOVH->findPurchasingFairUnavailabilities($purchasingFair); }
    /* ------------------------------------------------ ./UnavailabilityDAOOVH methods ------------------------------------------------  */    
    
      /* ------------------------------------------------ UnavailabilitySpDAOOVH methods ------------------------------------------------  */
    public function createUnavailabilitySp($startDatetime, $endDatetime, $idParticipant, $idPurchasingFair) { return $createdUnavailabilitySp = new UnavailabilitySp($startDatetime, $endDatetime, $idParticipant, $idPurchasingFair); }
    public function deactivateUnavailabilitySp(UnavailabilitySp $unavailabilitySp) { return $this->unavailabilitySpDAOOVH->deactivate($unavailabilitySp); }
    public function deleteUnavailabilitySp(UnavailabilitySp $unavailabilitySp) { return $this->unavailabilitySpDAOOVH->delete($unavailabilitySp); }
    public function findOneUnavailabilitySp($searchedIdUnavailabilitySp) { 
        $newUnavailabilitySp = $this->unavailabilitySpDAOOVH->findById($searchedIdUnavailabilitySp);
        if( $newUnavailabilitySp != null ) {  
            $newUnavailabilitySp->setOneParticipant($this->findOneParticipant($newUnavailabilitySp->getOneParticipant())); // OneParticipant = id
            $newUnavailabilitySp->setOnePurchasingFair($this->findOnePurchasingFair($newUnavailabilitySp->getOnePurchasingFair())); // OnePurchasingFair = id
        }
        return $newUnavailabilitySp;
    }
    public function findAllUnavailabilitiesSp() { 
        $arrayUnavailabilitiesSp = $this->unavailabilitySpDAOOVH->findAll();
        foreach($arrayUnavailabilitiesSp as $value) {
            $value->setOneParticipant($this->findOneParticipant($value->getOneParticipant())); // OneParticipant = id
            $value->setOnePurchasingFair($this->findOnePurchasingFair($value->getOnePurchasingFair())); // OnePurchasingFair = id
        }
        return $arrayUnavailabilitiesSp;
    }
    public function findAllUnavailabilitiesSpByParticipant($searchedIdParticipant) { 
        $arrayUnavailabilitiesSp = $this->unavailabilitySpDAOOVH->findByIdParticipant($searchedIdParticipant);
        foreach($arrayUnavailabilitiesSp as $value) {
            $value->setOneParticipant($this->findOneParticipant($value->getOneParticipant())); // OneParticipant = id
            $value->setOnePurchasingFair($this->findOnePurchasingFair($value->getOnePurchasingFair())); // OnePurchasingFair = id
        }
        return $arrayUnavailabilitiesSp;
    }
    public function findParticipantUnavailabilitiesSp(Participant $participant, PurchasingFair $purchasingFair) {
        $arrayUnavailabilitiesSp = $this->unavailabilitySpDAOOVH->findParticipantUnavailabilitiesSp($participant, $purchasingFair); 
            foreach($arrayUnavailabilitiesSp as $value) {
            $value->setOneParticipant($this->findOneParticipant($value->getOneParticipant())); // OneEnterprise = id
            $value->setOnePurchasingFair($this->findOnePurchasingFair($value->getOnePurchasingFair())); // OnePurchasingFair = id
        }
        return $arrayUnavailabilitiesSp;
    }
    public function saveUnavailabilitySp(UnavailabilitySp $unavailabilitySp) { return $this->unavailabilitySpDAOOVH->save($unavailabilitySp); }
    public function findPurchasingFairUnavailabilitiesSp(PurchasingFair $purchasingFair) { return $this->unavailabilitySpDAOOVH->findPurchasingFairUnavailabilitiesSp($purchasingFair); }
    public function findSpWithUnavByEntAndPf($idEnterprise, $idPurchasingFair) { return $this->unavailabilitySpDAOOVH->findSpWithUnavByEntAndPf($idEnterprise, $idPurchasingFair); }
    /* ------------------------------------------------ ./UnavailabilitySpDAOOVH methods ------------------------------------------------  */
    
    /* ------------------------------------------------ PurchasingFairDAOOVH methods ------------------------------------------------ */
    public function createPurchasingFair($namePurchasingFair, $hexColor, $startDatetime, $endDateTime, $lunchBreak, $oneTypeOfPf, $registrationClosingDate) { return $createdPurchasingFair = new PurchasingFair($namePurchasingFair, $hexColor, $startDatetime, $endDateTime, $lunchBreak, $oneTypeOfPf, $registrationClosingDate); }
    public function deactivatePurchasingFair(PurchasingFair $purchasingFair) { return $this->purchasingFairDAOOVH->deactivate($purchasingFair); }
    public function deletePurchasingFair(PurchasingFair $purchasingFair) { return $this->purchasingFairDAOOVH->delete($purchasingFair); }
    public function findOnePurchasingFair($searchedIdPurchasingFair) { 
        $newPurchasingFair = $this->purchasingFairDAOOVH->findById($searchedIdPurchasingFair);
        if($newPurchasingFair != null) {
            $newPurchasingFair->setOneTypeOfPf($this->findOneTypeOfPf($newPurchasingFair->getOneTypeOfPf()));
        }
        return $newPurchasingFair;
    }
    public function findAllPurchasingFairs() {
        $arrayPurchasingFairs = $this->purchasingFairDAOOVH->findAll();
        foreach($arrayPurchasingFairs as $value) {
            $value->setOneTypeOfPf($this->findOneTypeOfPf($value->getOneTypeOfPf()));
        }
        return $arrayPurchasingFairs; 
    }
    public function findAllPurchasingFairsAdmin() {
        $arrayPurchasingFairs = $this->purchasingFairDAOOVH->findAllAdmin();
        foreach($arrayPurchasingFairs as $value) {
            $value->setOneTypeOfPf($this->findOneTypeOfPf($value->getOneTypeOfPf()));
        }
        return $arrayPurchasingFairs; 
    }
    public function findLastPurchasingFair() {
        $arrayPurchasingFairs = $this->findAllPurchasingFairs();
        return (count($arrayPurchasingFairs) > 0) ? $arrayPurchasingFairs[0] : null;
    }
    public function savePurchasingFair(PurchasingFair $purchasingFair) { return $this->purchasingFairDAOOVH->save($purchasingFair); }
    /* ------------------------------------------------ ./PurchasingFairDAOOVH methods ------------------------------------------------ */
    
    /* ------------------------------------------------ EnterpriseDAOOVH methods ------------------------------------------------ */
    public function createEnterprise($name, $password, $panel, $postalAddress, $postalCode, $city, $vat, $oneTypeOfProvider, $oneProfile, $oneDepartment) { 
		return $createdEnterprise = new Enterprise($name, $password, $panel, $postalAddress, $postalCode, $city, $vat, $oneTypeOfProvider, $oneProfile, $oneDepartment); 
	}
    public function authentication($idEnterprise, $password, $profileEnterprise) { 
        $enterpriseConnected = $this->enterpriseDAOOVH->authentication($idEnterprise, $password, $profileEnterprise);
        if( $enterpriseConnected != null ) {
            $enterpriseConnected->setOneTypeOfProvider($this->findOneTypeOfProvider($enterpriseConnected->getOneTypeOfProvider()));
            $enterpriseConnected->setOneProfile($this->findOneProfile($enterpriseConnected->getOneProfile()));
            $enterpriseConnected->setOneDepartment($this->findOneDepartment($enterpriseConnected->getOneDepartment()));
        }
        return $enterpriseConnected;
    }
    public function deactivateEnterprise(Enterprise $enterprise) { return $this->enterpriseDAOOVH->deactivate($enterprise); }
    public function deleteEnterprise(Enterprise $enterprise) { return $this->enterpriseDAOOVH->delete($enterprise); }
    public function findOneEnterprise($searchedIdEnterprise) { 
        $newEnterprise = $this->enterpriseDAOOVH->findById($searchedIdEnterprise);
        $newEnterprise->setOneTypeOfProvider($this->findOneTypeOfProvider($newEnterprise->getOneTypeOfProvider()));
        $newEnterprise->setOneProfile($this->findOneProfile($newEnterprise->getOneProfile()));
        $newEnterprise->setOneDepartment($this->findOneDepartment($newEnterprise->getOneDepartment()));
        return $newEnterprise;
    }
    public function findAllEnterprises() { 
        $arrayEnterprises = $this->enterpriseDAOOVH->findAll();
        foreach($arrayEnterprises as $value) {
            $value->setOneTypeOfProvider($this->findOneTypeOfProvider($value->getOneTypeOfProvider()));
            $value->setOneProfile($this->findOneProfile($value->getOneProfile()));
            $value->setOneDepartment($this->findOneDepartment($value->getOneDepartment()));
        }
        return $arrayEnterprises;
    }
    public function findAllEnterprisesAsProviders() { 
        $arrayProviders = $this->enterpriseDAOOVH->findAllProviders();
        foreach($arrayProviders as $value) {
            $value->setOneTypeOfProvider($this->findOneTypeOfProvider($value->getOneTypeOfProvider()));
            $value->setOneDepartment($this->findOneDepartment($value->getOneDepartment()));
            $value->setOneProfile($this->findOneProfile($value->getOneProfile()));
        }
        return $arrayProviders;
    }
    public function findAllEnterprisesAsProvidersPf($searchedIdPurchasingFair) { 
        $arrayProvidersPf = $this->enterpriseDAOOVH->findByIdPf($searchedIdPurchasingFair);
        if(!empty($arrayProvidersPf)) {
            foreach($arrayProvidersPf as $value) {
                $value->setOneTypeOfProvider($this->findOneTypeOfProvider($value->getOneTypeOfProvider()));
                $value->setOneDepartment($this->findOneDepartment($value->getOneDepartment()));
                $value->setOneProfile($this->findOneProfile($value->getOneProfile()));
            }
        }
        return (!empty($arrayProvidersPf)) ? $arrayProvidersPf : array();
    }
    public function findAllProvidersWithTextilePriority($idPurchasingFair) { 
        $arrayProvidersPf = $this->enterpriseDAOOVH->findAllProvidersWithTextilePriority($idPurchasingFair);
        if(!empty($arrayProvidersPf)) {
            foreach($arrayProvidersPf as $value) {
                $value->setOneTypeOfProvider($this->findOneTypeOfProvider($value->getOneTypeOfProvider()));
                $value->setOneDepartment($this->findOneDepartment($value->getOneDepartment()));
                $value->setOneProfile($this->findOneProfile($value->getOneProfile()));
            }
        }
        return (!empty($arrayProvidersPf)) ? $arrayProvidersPf : array();
    }
    public function findAllEnterprisesAsStores() { 
        $arrayStores = $this->enterpriseDAOOVH->findAllStores();
        foreach($arrayStores as $value) {
            $value->setOneTypeOfProvider($this->findOneTypeOfProvider($value->getOneTypeOfProvider()));
            $value->setOneDepartment($this->findOneDepartment($value->getOneDepartment()));
            $value->setOneProfile($this->findOneProfile($value->getOneProfile()));
        }
        return $arrayStores;
    }
    public function findAllEnterprisesAsStoresPf($searchedIdPurchasingFair) { 
        $arrayStoresPf = $this->enterpriseDAOOVH->findByIdPf2($searchedIdPurchasingFair);
        foreach($arrayStoresPf as $value) {
            $value->setOneTypeOfProvider($this->findOneTypeOfProvider($value->getOneTypeOfProvider()));
            $value->setOneDepartment($this->findOneDepartment($value->getOneDepartment()));
            $value->setOneProfile($this->findOneProfile($value->getOneProfile()));
        }
        return $arrayStoresPf;
    }
    public function saveEnterprise(Enterprise $enterprise) { return $this->enterpriseDAOOVH->save($enterprise); }
    public function findAllStoresNotAvailableForTimeSlotAndPf($onePurchasingFair, $startTimeSlot, $endTimeSlot) {
        return $this->enterpriseDAOOVH->findAllStoresNotAvailableForTimeSlotAndPf($onePurchasingFair, $startTimeSlot, $endTimeSlot);
    }
    /* ------------------------------------------------ ./EnterpriseDAOOVH methods ------------------------------------------------ */
    
    /* ------------------------------------------------ ProfileDAOOVH methods ------------------------------------------------ */
    public function createProfile($name) { return $createdEnterprise = new Profile($name); }
    public function deactivateProfile(Profile $profile) { return $this->profileDAOOVH->deactivate($profile); }
    public function deleteProfile(Profile $profile) { return $this->profileDAOOVH->delete($profile); }
    public function findOneProfile($searchedIdProfile) { return $this->profileDAOOVH->findById($searchedIdProfile); }
    public function findAllProfiles() { return $this->profileDAOOVH->findAll(); }
    public function saveProfile(Profile $profile) { return $this->profileDAOOVH->save($profile); }
    /* ------------------------------------------------ ./ProfileDAOOVH methods ------------------------------------------------ */
     
    /* ------------------------------------------------ DepartmentDAOOVH methods ------------------------------------------------ */
    public function createDepartment($name) { return $createdDepartment = new Department($name); }
    public function deleteDepartment(Department $department) { return $this->departmentDAOOVH->delete($department); }
    public function findOneDepartment($searchedIdDepartment) { return $this->departmentDAOOVH->findById($searchedIdDepartment); }
    public function findAllDepartments() { return $this->departmentDAOOVH->findAll(); }
    /* ------------------------------------------------ ./DepartmentDAOOVH methods ------------------------------------------------ */

    /* ------------------------------------------------ ParticipantDAOOVH Methods ------------------------------------------------ */
    public function createparticipant($civility, $surname, $name, $email) { return $createdParticipant = new Participant($civility, $surname, $name, $email); }
    public function deactivateParticipant(Participant $participant) { return $this->participantDAOOVH->deactivate($participant); }
    public function deleteParticipant(participant $participant) { return $this->participantDAOOVH->delete($participant); }
    public function findAllparticipants() { return $this->participantDAOOVH->findAll(); }
    public function findOneParticipant($searchedIdparticipant) { return $this->participantDAOOVH->findById($searchedIdparticipant); }
    public function saveParticipant(participant $participant) { return $this->participantDAOOVH->save($participant); }
    public function findAllParticipantsAsSalespersons() { return $this->participantDAOOVH->findAllAsSalespersons(); }
    public function findAllParticipantsAsSalespersonsByProvider($idProvider) { return $this->participantDAOOVH->findAllParticipantsAsSalespersonsByProvider($idProvider); }
    public function findAllParticipantsAsSalespersonsByProviderAndPf($idProvider, $idPurchasingFair) { return $this->participantDAOOVH->findAllParticipantsAsSalespersonsByProviderAndPf($idProvider, $idPurchasingFair); }
    public function summaryParticipants($idPurchasingFair) { return $this->participantDAOOVH->summaryParticipants($idPurchasingFair); }
    /* ------------------------------------------------ ./ParticipantDAOOVH Methods ------------------------------------------------ */

    /* ------------------------------------------------ LogDAOOVH Methods ------------------------------------------------ */
    public function createLog($oneEnterprise, $ipAddress, $actionDescription) { return $createdLog = new Log($oneEnterprise, $ipAddress, $actionDescription); }
    public function findAllLogs() { 
        $arrayLogs = $this->logDAOOVH->findAll();
        foreach($arrayLogs as $value) { $value->setOneEnterprise($this->findOneEnterprise($value->getOneEnterprise())); }
        return $arrayLogs;
    }
    public function findOneLog($searchedIdLog) { 
        $searchedLog = $this->logDAOOVH->findById($searchedIdLog);
        $searchedLog->setOneEnterprise($this->findOneEnterprise($searchedLog->getOneEnterprise()));
        return $searchedLog;
    }
    public function saveLog(Log $log) { return $this->logDAOOVH->save($log); }
    public function findAllLogsForOneEnterprise(Enterprise $enterprise) { 
        $arrayLog = $this->logDAOOVH->findByEnterprise($enterprise);
        foreach($arrayLog as $value) { $value->setOneEnterprise($enterprise); }
        return $arrayLog;
    }
    /* ------------------------------------------------ ./LogDAOOVH Methods ------------------------------------------------ */
    
    /* ------------------------------------------------ SalespersonDAOOVH Methods ------------------------------------------------ */
    public function createSalesperson($civility, $surname, $name) { return $createdSalesperson = new Salesperson($civility, $surname, $name); }
    public function deactivateSalesperson(Salesperson $salesperson) { return $this->salespersonDAOOVH->deactivate($salesperson); }
    public function deleteSalesperson(Salesperson $salesperson) { return $this->salespersonDAOOVH->delete($salesperson); }
    public function findOneSalesperson($searchedIdSalesperson) { return $this->salespersonDAOOVH->findById($searchedIdSalesperson); }
    public function findAllSalespersons() { return $this->salespersonDAOOVH->findAll(); }
    public function saveSalesperson(Salesperson $salesperson) { return $this->salespersonDAOOVH->save($salesperson); }    
    /* ------------------------------------------------ ./SalespersonDAOOVH Methods ------------------------------------------------ */
    
    /* ------------------------------------------------ AssignmentParticipantEnterpriseDAOOVH Methods ------------------------------------------------ */
    public function createAssignmentParticipantEnterprise(Participant $oneParticipant, Enterprise $oneEnterprise) { return $createdAPE = new AssignmentParticipantEnterprise($oneParticipant, $oneEnterprise); }
    public function deleteAssignmentParticipantEnterprise(AssignmentParticipantEnterprise $assignmentParticipantEnterprise) { return $this->assignmentParticipantEnterpriseDAOOVH->delete($assignmentParticipantEnterprise); }
    public function findOneAssignmentParticipantEnterprise($searchIdParticipant, $searchedIdEnterprise) { 
        $oneAPE = $this->assignmentParticipantEnterpriseDAOOVH->findByTwoIds($searchIdParticipant, $searchedIdEnterprise);
        if($oneAPE != null) {
            $oneAPE->setOneParticipant($this->findOneParticipant($oneAPE->getOneParticipant()));
            $oneAPE->setOneEnterprise($this->findOneEnterprise($oneAPE->getOneEnterprise()));
        }
        return $oneAPE;
    }
    public function findAllAssignmentsParticipantEnterprise() { 
        $arrayAPE = $this->assignmentParticipantEnterpriseDAOOVH->findAll(); 
        foreach($arrayAPE as $value) {
            $value->setOneParticipant($this->findOneParticipant($value->getOneParticipant()));
            $value->setOneEnterprise($this->findOneEnterprise($value->getOneEnterprise()));
        }
        return $arrayAPE;
    }
    public function findAllAssignmentsParticipantEnterpriseForOneParticipant($searchedIdParticipant) {
        $arrayAPE = $this->assignmentParticipantEnterpriseDAOOVH->findByOneId($searchedIdParticipant); 
        foreach($arrayAPE as $value) {
            $value->setOneParticipant($this->findOneParticipant($value->getOneParticipant()));
            $value->setOneEnterprise($this->findOneEnterprise($value->getOneEnterprise()));
        }
        return $arrayAPE;    
    }

    public function findAllAssignmentsParticipantEnterpriseForOneEnterprise($searchedIdEnterprise) { 
        $arrayAPE = $this->assignmentParticipantEnterpriseDAOOVH->findAllAssignmentsparticipantEnterpriseForOneEnterprise($searchedIdEnterprise); 
        foreach($arrayAPE as $value) {
            $value->setOneParticipant($this->findOneParticipant($value->getOneParticipant()));
            $value->setOneEnterprise($this->findOneEnterprise($value->getOneEnterprise()));
        }
        return $arrayAPE;
    }
    public function saveAssignmentParticipantEnterprise(AssignmentParticipantEnterprise $assignmentParticipantEnterprise) { return $this->assignmentParticipantEnterpriseDAOOVH->insert($assignmentParticipantEnterprise); }    
    /* ------------------------------------------------ ./AssignmentParticipantEnterpriseDAOOVH Methods ------------------------------------------------ */
    
    /* ------------------------------------------------ AssignmentParticipantDepartmentDAOOVH Methods ------------------------------------------------ */
    public function createAssignmentParticipantDepartment(Participant $oneParticipant, Department $oneDepartment) { return $createdAPD = new AssignmentParticipantDepartment($oneParticipant, $oneDepartment); }
    public function deleteAssignmentParticipantDepartment(AssignmentParticipantDepartment $assignmentParticipantDepartment) { return $this->assignmentParticipantDepartmentDAOOVH->delete($assignmentParticipantDepartment); }
    public function findOneAssignmentParticipantDepartment($searchedIdParticipant, $searchedIdDepartment) { 
        $oneAPD = $this->assignmentParticipantDepartmentDAOOVH->findByTwoIds($searchedIdParticipant, $searchedIdDepartment);
        if($oneAPD != null) {
            $oneAPD->setOneParticipant($this->findOneParticipant($oneAPD->getOneParticipant()));
            $oneAPD->setOneDepartment($this->findOneDepartment($oneAPD->getOneDepartment()));
        }
        return $oneAPD;
    }
    public function findAllAssignmentsParticipantDepartment() { 
        $arrayAPD = $this->assignmentParticipantDepartmentDAOOVH->findAll(); 
        foreach($arrayAPD as $value) {
            $value->setOneParticipant($this->findOneparticipant($value->getOneparticipant()));
            $value->setOneDepartment($this->findOneDepartment($value->getOneDepartment()));
        }
        return $arrayAPD;
    }
    public function findAssignmentsParticipantDepartmentByParticipant($searchedIdParticipant) {
        $arrayAPD = $this->assignmentParticipantDepartmentDAOOVH->findByParticipant($searchedIdParticipant);
        foreach($arrayAPD as $value) {
            $value->setOneParticipant($this->findOneParticipant($value->getOneParticipant()));
            $value->setOneDepartment($this->findOneDepartment($value->getOneDepartment()));
        }
        return $arrayAPD;
    }
    public function saveAssignmentParticipantDepartment(AssignmentParticipantDepartment $assignmentParticipantDepartment) { return $this->assignmentParticipantDepartmentDAOOVH->insert($assignmentParticipantDepartment); }    
    /* ------------------------------------------------ ./AssignmentParticipantDepartmentDAOOVH Methods ------------------------------------------------ */
    
    /* ------------------------------------------------ RequirementDAOOVH methods ------------------------------------------------ */
    public function createRequirement($oneStore, $oneProvider, $onePurchasingFair, $numberOfHours) { return $createdRequirement = new Requirement($oneStore, $oneProvider, $onePurchasingFair, $numberOfHours); }
    public function deleteRequirement(Requirement $requirement) { return $this->requirementDAOOVH->delete($requirement); }
    public function findOneRequirement($searchedIdRequirement) { 
        $searchedRequirement =  $this->requirementDAOOVH->findById($searchedIdRequirement);
        if($searchedRequirement != null) {
            $searchedRequirement->setOneStore($this->findOneEnterprise($searchedRequirement->getOneStore()));
            $searchedRequirement->setOneProvider($this->findOneEnterprise($searchedRequirement->getOneProvider()));
            $searchedRequirement->setOnePurchasingFair($this->findOnePurchasingFair($searchedRequirement->getOnePurchasingFair()));
        }
        return $searchedRequirement;
    }
    public function findRequirementFilteredDuo($oneStore, $onePurchasingFair) { 
        $arrayDuo = $this->requirementDAOOVH->findByDuo($oneStore, $onePurchasingFair);
        foreach($arrayDuo as $value) {
            $value->setOneStore($oneStore);
            $value->setOneProvider($this->findOneEnterprise($value->getOneProvider()));
            $value->setOnePurchasingFair($onePurchasingFair);
        }
        return $arrayDuo;
    }
    
    public function findRequirementFilteredDuoWithTotNumberHours($oneStore, $onePurchasingFair) { 
        $arrayDuo = array();
        $arrayDuo['requirements'] = $this->requirementDAOOVH->findByDuo($oneStore, $onePurchasingFair);
        foreach($arrayDuo['requirements'] as $value) {
            $value->setOneStore($oneStore);
            $value->setOneProvider($this->findOneEnterprise($value->getOneProvider()));
            $value->setOnePurchasingFair($onePurchasingFair);
        }
        $arrayDuo['totNumberOfHours'] = $this->requirementDAOOVH->findTotalNumberHours($oneStore, $onePurchasingFair);
        return $arrayDuo;
    }
    public function findRequirementFilteredDuoWithTotNumberHoursAndUnavs($oneStore, $onePurchasingFair) { 
        $arrayDuo = $this->findRequirementFilteredDuoWithTotNumberHours($oneStore, $onePurchasingFair);
        $arrayDuo['unavs'] = $this->unavailabilityDAOOVH->findEnterpriseUnavailabilities($oneStore, $onePurchasingFair);
        return $arrayDuo;
    }
    public function deleteRequirementFilteredDuo($oneStore, $onePurchasingFair) { return $this->requirementDAOOVH->deleteByDuo($oneStore, $onePurchasingFair); }
    public function findRequirementFilteredTrio($oneStore, $oneProvider, $onePurchasingFair) { 
        $oneRequirement = $this->requirementDAOOVH->findByTrio($oneStore, $oneProvider, $onePurchasingFair); 
        if($oneRequirement != NULL) {
            $oneRequirement->setOneStore($oneStore);
            $oneRequirement->setOneProvider($oneProvider);
            $oneRequirement->setOnePurchasingFair($onePurchasingFair);
        }
        return $oneRequirement;
    }
    public function findAllRequirements() { 
        $arrayRequirements = $this->requirementDAOOVH->findAll();
        foreach($arrayRequirements as $value) {
            $value->setOneStore($this->findOneEnterprise($value->getOneStore()));
            $value->setOneProvider($this->findOneEnterprise($value->getOneProvider()));
            $value->setOnePurchasingFair($this->findOnePurchasingFair($value->getOnePurchasingFair()));
        }
        return $arrayRequirements;
    }
    // public function findAllRequirementsForStoreAndPurchasingFair($idStore, $idPurchasingFair) { return $this->requirementDAOOVH->findAllFiltered($idStore, $idPurchasingFair); }
    public function saveRequirement(Requirement $requirement) { return $this->requirementDAOOVH->save($requirement); }
    /* ------------------------------------------------ ./RequirementDAOOVH methods ------------------------------------------------ */

    /* ------------------------------------------------ Various methods ------------------------------------------------ */
    public function getIpAddress() { return Various::getIP(); }
    public function myFrenchDate($datetimeMySql) { return Various::myFrenchDate($datetimeMySql); }
    public function myFrenchDatetime($datetimeMySql) { return Various::myFrenchDatetime($datetimeMySql); }
    public function myFrenchTime($datetimeMySql) { return Various::myFrenchTime($datetimeMySql); }
    public function deadline($datetimeMySql) { return Various::deadline($datetimeMySql); }
    public function bool2str($bool) { return Various::bool2str($bool); }
    public function compareObjects(&$o1, &$o2) { return Various::compareObjects($o1, $o2); }
    public function generateFakeUsers($howMany) { return Various::generateFakeUsers($howMany); }
    public function generateFakePF($howMany) { return Various::generateFakePF($howMany); }
    public function purchasingFairIsClosedForUser($datetimeMySql) { return Various::purchasingFairIsClosedForUser($datetimeMySql); }
    public function mychrono() { return Various::myChrono(); }
    public function sixDigitsGenerator($howManyCombinationsDoYouWant) { return Various::SixDigitsGenerator($howManyCombinationsDoYouWant); }
    public function generateQRCodes($idPurchasingFair, $arrayParticipants) { return Various::generateQRCodes($idPurchasingFair, $arrayParticipants); }
    public function generateStickers($idPurchasingFair, $arrayParticipants) { return Various::generateStickers($idPurchasingFair, $arrayParticipants); }
    public function convertDateRangeToMySqlFormat($dateRange) { return Unavailability::convertDateRangeToMySqlFormat($dateRange); }
    public function convertTwoMySqlDatetimeToDateRangeFormat($tartDatetime, $endDatetime) { return Unavailability::convertTwoMySqlDatetimeToDateRangeFormat($tartDatetime, $endDatetime); }
    public function generateParticipationDetailsPDF(Enterprise $oneEnterprise, PurchasingFair $onePurchasingFair) { ; } // DOES NOT WORK, ERROR DATA ALREADY SENT
    public function sortParticipantsBySurnameAndName($arrayOfObjectsThatContainParticipantsObjects) { return Various::sortParticipantsBySurnameAndName($arrayOfObjectsThatContainParticipantsObjects); }
    public function sortParticipantsBySurnameAndNameBis($arrayOfObjectsThatNotContainParticipantsObjects) { return Various::sortParticipantsBySurnameAndNameBis($arrayOfObjectsThatNotContainParticipantsObjects); }
    public function generateNColors($nColors) { return Various::generateNColors($nColors); }
    public function generateNPasswords($nbPasswords) { return Various::generateNPasswords($nbPasswords); }
    public function randomKey() { return Various::randomKey(); }
    public function generateSQLQueriesPanelCodes() { return Various::generateSQLQueriesPanelCodes(); }
    public function sendMail(MyEmail $myEmail) { return Various::sendMail($myEmail); }
    public function castelAccess() { return Various::castelAccess(); }
    public function numberFormat($numberToFormat, $numberFormat) { return Various::numberFormat($numberToFormat, $numberFormat); }
    /* ------------------------------------------------ ./Various methods ------------------------------------------------ */
    
    /* ------------------------------------------------ StatDAOOVH methods ------------------------------------------------ */
    public function numberOfParticipationsInAPurchasingFair(User $user) { return $this->statDAOOVH->numberOfParticipationsInAPurchasingFair($user); }
    public function numberOfConnectionsByMonth(User $user) { return $this->statDAOOVH->numberOfConnectionsByMonth($user); }
    public function heatmapConnections(user $user) { return $this->statDAOOVH->heatmapConnections($user); }
    /* ------------------------------------------------ ./StatDAOOVH methods ------------------------------------------------ */
    
    /* ------------------------------------------------ ParticipationDAOOVH Methods ------------------------------------------------ */
    public function createParticipation($oneParticipant, $onePurchasingFair, $passwordParticipant, $lunch) { return $createdParticipation = new Participation($oneParticipant, $onePurchasingFair, $passwordParticipant, $lunch); }
    public function deleteParticipation(Participation $participation) { return $this->participationDAOOVH->delete($participation); }
    public function findOneParticipation($searchedIdParticipant, $searchedIdPurchasingFair) { 
        $oneParticipation =  $this->participationDAOOVH->findByTwoIds($searchedIdParticipant, $searchedIdPurchasingFair); 
        
        if( $oneParticipation != null ) {
            $oneParticipation->setOneParticipant($this->findOneParticipant($oneParticipation->getOneParticipant()));
            $oneParticipation->setOnePurchasingFair($this->findOnePurchasingFair($oneParticipation->getOnePurchasingFair()));
        }
        return $oneParticipation;
        
    }
    public function findAllParticipations() {
        $arrayParticipations = $this->participationDAOOVH->findAll();
        foreach($arrayParticipations as $value) {
            $value->setOneParticipant($this->findOneParticipant($value->getOneParticipant()));
            $value->setOnePurchasingFair($this->findOnePurchasingFair($value->getOnePurchasingFair()));
        }
        return $arrayParticipations;
    }
    public function saveParticipation(Participation $participation) { return $this->participationDAOOVH->insert($participation); }
    public function findAllParticipationsByEnterpriseAndPurchasingFair($oneEnterprise, $onePurchasingFair) { 
        $arrayParticipations = $this->participationDAOOVH->findAllByEnterpriseAndPurchasingFair($oneEnterprise, $onePurchasingFair);
        foreach($arrayParticipations as $value) {
            $value->setOneParticipant($this->findOneParticipant($value->getOneParticipant()));
            $value->setOnePurchasingFair($this->findOnePurchasingFair($value->getOnePurchasingFair()));
        }
        return $arrayParticipations;
    }
    /* ------------------------------------------------ ./ParticipationDAOOVH Methods ------------------------------------------------ */
    
    /* ------------------------------------------------ TypeOfPfDAOOVH Methods ------------------------------------------------ */
    public function createTypeOfPf($nameTypeOfPf) { return $newTypeOfPf = new TypeOfPf($nameTypeOfPf); }
    public function findOneTypeOfPf($searchedIdTypeOfPf) { return $this->typeofPfDAOOVH->findById($searchedIdTypeOfPf); }
    public function findAllTypeOfPf() { return $this->typeofPfDAOOVH->findAll(); }
    /* ------------------------------------------------ ./TypeOfPfDAOOVH Methods ------------------------------------------------ */
    
    /* ------------------------------------------------ AssignmentSpStoreDAOOVH Methods ------------------------------------------------ */
    public function createAssignmentSpStore(Participant $oneParticipant, Enterprise $oneStore, Enterprise $oneProvider, PurchasingFair $onePurchasingFair) { 
        return $createdASS = new AssignmentSpStore($oneParticipant, $oneStore, $oneProvider, $onePurchasingFair); 
    }
    public function deleteAssignmentSpStore(AssignmentSpStore $assignmentSpStore) { return $this->assignmentSpStoreDAOOVH->delete($assignmentSpStore); }
    public function deleteAssignmentSpStoreBis(AssignmentSpStore $assignmentSpStore) { return $this->assignmentSpStoreDAOOVH->deleteBis($assignmentSpStore); }
    public function findOneAssignmentSpStore($searchedIdParticipant, $searchedIdStore, $searchedIdProvider, $searchedIdPurchasingFair) { 
        $oneASS = $this->assignmentSpStoreDAOOVH->findByFourIds($searchedIdParticipant, $searchedIdStore, $searchedIdProvider, $searchedIdPurchasingFair);
        if($oneASS != null) {
            $oneASS->setOneParticipant($this->findOneParticipant($oneASS->getOneParticipant()));
            $oneASS->setOneStore($this->findOneEnterprise($oneASS->getOneStore()));
            $oneASS->setOneProvider($this->findOneEnterprise($oneASS->getOneProvider()));
            $oneASS->setOnePurchasingFair($this->findOnePurchasingFair($oneASS->getOnePurchasingFair()));
        }
        return $oneASS;
    }
    public function findOneAssignmentSpStoreBis($searchedIdProvider, $searchedIdPurchasingFair) {
        $arrayASS = $this->assignmentSpStoreDAOOVH->findByTwoIds($searchedIdProvider, $searchedIdPurchasingFair);
        foreach($arrayASS as $value) {
            $value->setOneParticipant($this->findOneParticipant($value->getOneParticipant()));
            $value->setOneStore($this->findOneEnterprise($value->getOneStore()));
            $value->setOneProvider($this->findOneEnterprise($value->getOneProvider()));
            $value->setOnePurchasingFair($this->findOnePurchasingFair($value->getOnePurchasingFair()));
        }
        return $arrayASS;    
    }
    public function findOneAssignmentSpStoreTer($searchedIdParticipant, $searchedIdProvider, $searchedIdPurchasingFair) {
        $arrayASS = $this->assignmentSpStoreDAOOVH->findByThreeIds($searchedIdParticipant, $searchedIdProvider, $searchedIdPurchasingFair);
        foreach($arrayASS as $value) {
            $value->setOneParticipant($this->findOneParticipant($value->getOneParticipant()));
            $value->setOneStore($this->findOneEnterprise($value->getOneStore()));
            $value->setOneProvider($this->findOneEnterprise($value->getOneProvider()));
            $value->setOnePurchasingFair($this->findOnePurchasingFair($value->getOnePurchasingFair()));
        }
        return $arrayASS;    
    }
    public function findOneAssignmentSpStoreQuatro($searchedIdStore, $searchedIdProvider, $searchedIdPurchasingFair) {
        $oneASS = $this->assignmentSpStoreDAOOVH->findByThreeIdsBis($searchedIdStore, $searchedIdProvider, $searchedIdPurchasingFair);
        if(!is_null($oneASS)) {
            $oneASS->setOneParticipant($this->findOneParticipant($oneASS->getOneParticipant()));
            $oneASS->setOneStore($this->findOneEnterprise($oneASS->getOneStore()));
            $oneASS->setOneProvider($this->findOneEnterprise($oneASS->getOneProvider()));
            $oneASS->setOnePurchasingFair($this->findOnePurchasingFair($oneASS->getOnePurchasingFair()));
        }
        return $oneASS;    
    }
    public function findAllAssignmentSpStoreByParticipant($searchedIdParticipant) {
        $arrayASS = $this->assignmentSpStoreDAOOVH->findAllByParticipant($searchedIdParticipant);
        foreach($arrayASS as $value) {
            $value->setOneParticipant($this->findOneParticipant($value->getOneParticipant()));
            $value->setOneStore($this->findOneEnterprise($value->getOneStore()));
            $value->setOneProvider($this->findOneEnterprise($value->getOneProvider()));
            $value->setOnePurchasingFair($this->findOnePurchasingFair($value->getOnePurchasingFair()));
        }
        return $arrayASS;     
    }
    public function findAllAssignmentsSpStore() { 
        $arrayASS = $this->assignmentSpStoreDAOOVH->findAll(); 
        foreach($arrayASS as $value) {
            $value->setOneParticipant($this->findOneparticipant($value->getOneparticipant()));
            $value->setOneEnterprise($this->findOneEnterprise($value->getOneEnterprise()));
            $value->setOnePurchasingFair($this->findOnePurchasingFair($value->getOnePurchasingFair()));
        }
        return $arrayASS;
    }
    public function saveAssignmentSpStore(AssignmentSpStore $assignmentSpStore) { return $this->assignmentSpStoreDAOOVH->insert($assignmentSpStore); }
    public function summaryOfAssignedStores($searchedIdProvider, $searchedIdPurchasingFair) {
        $arrayEnterprises = $this->assignmentSpStoreDAOOVH->summaryOfAssignedStores($searchedIdProvider, $searchedIdPurchasingFair);
        foreach($arrayEnterprises as $value) {
            $value->setOneTypeOfProvider($this->findOneTypeOfProvider($value->getOneTypeOfProvider()));
            $value->setOneDepartment($this->findOneDepartment($value->getOneDepartment()));
            $value->setOneProfile($this->findOneProfile($value->getOneProfile()));
        }
        return $arrayEnterprises;
    }
    /* ------------------------------------------------ ./AssignmentSpStoreDAOOVH Methods ------------------------------------------------ */
    
    /* ------------------------------------------------ QRCodeScanDAOOVH Methods ------------------------------------------------ */
    public function createQRCodeScan($onePurchasingFair, $oneEnterprise, $oneParticipant, $scanDatetime) { 
        return $createdQRCodeScan = new QRCodeScan($onePurchasingFair, $oneEnterprise, $oneParticipant, $scanDatetime); 
    }
    public function findAllQRCodeScan() { 
        $arrayQRCodeScan = $this->qrcodeScanDAOOVH->findAll();
        foreach($arrayQRCodeScan as $value) { 
            $value->setOnePurchasingFair($this->findOnePurchasingFair($value->getOnePurchasingFair()));
            $value->setOneEnterprise($this->findOneEnterprise($value->getOneEnterprise()));
            $value->setOneParticipant($this->findOneParticipant($value->getOneParticipant()));
        }
        return $arrayQRCodeScan;
    }
    public function findOneQRCodeScan($searchedIdQRCodeScan) { 
        $searchedQRCodeScan = $this->qrcodeScanDAOOVH->findById($searchedIdQRCodeScan);
        if(!empty($searchedQRCodeScan)) {
            $searchedQRCodeScan->setOneEnterprise($this->findOneEnterprise($searchedQRCodeScan->getOneEnterprise()));
            $searchedQRCodeScan->setOnePurchasingFair($this->findOneEnterprise($searchedQRCodeScan->getOnePurchasingFair()));
            $searchedQRCodeScan->setOneParticipant($this->findOneParticipant($searchedQRCodeScan->getOneParticipant()));
        }
        return $searchedQRCodeScan;
    }
    public function deleteQRCodeScan($idQRCodeScan) { return $this->qrcodeScanDAOOVH->delete($idQRCodeScan); }
    public function saveQRCodeScan(QRCodeScan $qrcodeScan) { return $this->qrcodeScanDAOOVH->insert($qrcodeScan); }
    public function findAllQRCodeScanByTrio($idPurchasingFair, $idEnterprise, $idParticipant) {
        $arrayQRCodeScan = $this->qrcodeScanDAOOVH->findAllByTrio($idPurchasingFair, $idEnterprise, $idParticipant);
        foreach($arrayQRCodeScan as $value) { 
            $value->setOnePurchasingFair($this->findOnePurchasingFair($value->getOnePurchasingFair()));
            $value->setOneEnterprise($this->findOneEnterprise($value->getOneEnterprise()));
            $value->setOneParticipant($this->findOneParticipant($value->getOneParticipant()));
        }
        return $arrayQRCodeScan;
    }
    /* ------------------------------------------------ ./QRCodeScanDAOOVH Methods ------------------------------------------------ */
    
    /* ------------------------------------------------ EnterpriseContactDAOOVH methods ------------------------------------------------ */
    public function createEnterpriseContact($civility, $surname, $name, $email, $oneEnterprise) {
        return new EnterpriseContact($civility, $surname, $name, $email, $this->findOneEnterprise($oneEnterprise));
    }
    public function deactivateEnterpriseContact(EnterpriseContact $enterpriseContact) { ; }
    public function deleteEnterpriseContact(EnterpriseContact $enterpriseContact) { return $this->enterpriseContactDAOOVH->delete($enterpriseContact); }
    public function findOneEnterpriseContact($searchedIdEnterpriseContact) {
        $enterpriseContactFinded = $this->enterpriseContactDAOOVH->findById($searchedIdEnterpriseContact);
        if($enterpriseContactFinded != null) {
            $enterpriseContactFinded->setOneEnterprise($this->findOneEnterprise($enterpriseContactFinded->getOneEnterprise()));
        }
        return $enterpriseContactFinded;
    }
    public function findOneEnterpriseContactByEnterprise($idEnterprise) {
        $enterpriseContactFinded = $this->enterpriseContactDAOOVH->findByEnterprise($idEnterprise);
        if($enterpriseContactFinded != null) {
            $enterpriseContactFinded->setOneEnterprise($this->findOneEnterprise($enterpriseContactFinded->getOneEnterprise()));
        }
        return $enterpriseContactFinded;
    }
    public function findAllEnterpriseContact() {
        $arrayentEnterpriseContactFinded = $this->enterpriseContactDAOOVH->findAll();
        foreach($arrayentEnterpriseContactFinded as $key => $value) {
            $value->setOneEnterprise($this->findOneEnterprise($value->getOneEnterprise()));
        }
        return $arrayentEnterpriseContactFinded;
    }
    public function saveEnterpriseContact(EnterpriseContact $enterpriseContact) { return $this->enterpriseContactDAOOVH->save($enterpriseContact); }
    /* ------------------------------------------------ ./EnterpriseContactDAOOVH methods ------------------------------------------------ */
    
    /* ------------------------------------------------ Planning generation ------------------------------------------------ */
    public function generatePlanning($idPurchasingFair) { }
    /* ------------------------------------------------ ./Planning generation ------------------------------------------------ */
    
    /* ------------------------------------------------ ProviderPresentDAOOVH Methods ------------------------------------------------ */
    public function createProviderPresent(Enterprise $oneProvider, PurchasingFair $onePurchasingFair) { return $createdPP = new ProviderPresent($oneProvider, $onePurchasingFair); }
    public function deleteProviderPresent(ProviderPresent $providerPresent) { return $this->providerPresentDAOOVH->delete($providerPresent); }
    public function deletePPForOnePurchasingFair($idPurchasingFair){ return $this->providerPresentDAOOVH->deleteForOnePurchasingFair($idPurchasingFair); }
    public function findOneProviderPresent($searchIdProvider, $searchedIdPurchasingFair) { 
        $onePP = $this->providerPresentDAOOVH->findByTwoIds($searchIdProvider, $searchedIdPurchasingFair);
        if($onePP != null) {
            $onePP->setOneProvider($this->findOneEnterprise($onePP->getOneProvider()));
            $onePP->setOnePurchasingFair($this->findOnePurchasingFair($onePP->getOnePurchasingFair()));
        }
        return $onePP;
    }
    public function findAllProviderPresent() { 
        $arrayPP = $this->providerPresentDAOOVH->findAll(); 
        foreach($arrayPP as $value) {
            $value->setOneProvider($this->findOneEnterprise($value->getOneProvider()));
            $value->setOnePurchasingFair($this->findOnePurchasingFair($value->getOnePurchasingFair()));
        }
        return $arrayPP;
    }
    public function findAllProviderPresentForOneProvider($searchedIdProvider) {
        $arrayPP = $this->providerPresentDAOOVH->findByOneId($searchedIdProvider); 
        foreach($arrayPP as $value) {
            $value->setOneProvider($this->findOneEnterprise($value->getOneProvider()));
            $value->setOnePurchasingFair($this->findOnePurchasingFair($value->getOnePurchasingFair()));
        }
        return $arrayPP;    
    }
    public function findAllProviderPresentForOnePurchasingFair($searchedIdPurchasingFair) { 
        $arrayPP = $this->providerPresentDAOOVH->findAllProviderPresentForOnePurchasingFair($searchedIdPurchasingFair); 
        foreach($arrayPP as $value) {
            $value->setOneProvider($this->findOneEnterprise($value->getOneProvider()));
            $value->setOnePurchasingFair($this->findOnePurchasingFair($value->getOnePurchasingFair()));
        }
        return $arrayPP;
    }
    public function saveProviderPresent(ProviderPresent $providerPresent) { return $this->providerPresentDAOOVH->insert($providerPresent); }    
    /* ------------------------------------------------ ./ProviderPresentDAOOVH Methods ------------------------------------------------ */
    
    /* ------------------------------------------------ TypeOfProviderDAOOVH Methods ------------------------------------------------ */
    public function createTypeOfProvider($nameTypeOfProvider) { return $newTypeOfProvider = new TypeOfProvider($nameTypeOfProvider); }
    public function findOneTypeOfProvider($searchedIdTypeOfProvider) { return $this->typeOfProviderDAOOVH->findById($searchedIdTypeOfProvider); }
    public function findAllTypeOfProvider() { return $this->typeOfProviderDAOOVH->findAll(); }
    /* ------------------------------------------------ ./TypeOfProviderDAOOVH Methods ------------------------------------------------ */
    
    /* ------------------------------------------------ SpecialGuestDAOOVH Methods ------------------------------------------------ */
    public function createSpecialGuest($oneEnterprise, $onePurchasingFair, $civility, $surname, $name, $days) { 
        return $createdSpecialGuest = new SpecialGuest($oneEnterprise, $onePurchasingFair, $civility, $surname, $name, $days); 
    }
    public function findAllSpecialGuest() { 
        $arraySpecialGuest = $this->specialGuestDAOOVH->findAll();
        foreach($arraySpecialGuest as $value) { 
            $value->setOneEnterprise($this->findOneEnterprise($value->getOneEnterprise()));
            $value->setOnePurchasingFair($this->findOnePurchasingFair($value->getOnePurchasingFair())); 
        }
        return $arraySpecialGuest;
    }
    public function findOneSpecialGuest($searchedIdSpecialGuest) { 
        $searchedSpecialGuest = $this->specialGuestDAOOVH->findById($searchedIdSpecialGuest);
        if(!empty($searchedSpecialGuest)) {
            $searchedSpecialGuest->setOneEnterprise($this->findOneEnterprise($searchedSpecialGuest->getOneEnterprise()));
            $searchedSpecialGuest->setOnePurchasingFair($this->findOneEnterprise($searchedSpecialGuest->getOnePurchasingFair()));
        }
        return $searchedSpecialGuest;
    }
    public function deleteSpecialGuest($idSpecialGuest) { return $this->specialGuestDAOOVH->delete($idSpecialGuest); }
    public function deleteAllSpecialGuest() { return $this->specialGuestDAOOVH->deleteAll(); }
    public function saveSpecialGuest(SpecialGuest $specialGuest) { return $this->specialGuestDAOOVH->save($specialGuest); }
    public function findAllSpecialGuestForOneEnterpriseAndPf($idEnterprise, $idPurchasingFair) { 
        $arraySpecialGuest = $this->specialGuestDAOOVH->findByEnterpriseAndPf($idEnterprise, $idPurchasingFair);
        foreach($arraySpecialGuest as $value) { 
            $value->setOneEnterprise($this->findOneEnterprise($idEnterprise));
            $value->setOnePurchasingFair($this->findOnePurchasingFair($idPurchasingFair)); 
        }
        return $arraySpecialGuest;
    }
    /* ------------------------------------------------ ./SpecialGuestDAOOVH Methods ------------------------------------------------ */
    
    /* ------------------------------------------------ LunchDAOOVH Methods ------------------------------------------------ */
    public function createLunch($oneEnterprise, $onePurchasingFair, $lunchesPlanned, $lunchesCanceled) { 
        return $createdLunch = new Lunch($oneEnterprise, $onePurchasingFair, $lunchesPlanned, $lunchesCanceled); 
    }
    public function lunchesCalculated($idPurchasingFair) {
        return $this->lunchDAOOVH->lunchesCalculated($idPurchasingFair);
    }
    public function saveLunch(Lunch $lunch) { return $this->lunchDAOOVH->insert($lunch); }
    public function updateLunch(Lunch $lunch) { return $this->lunchDAOOVH->update($lunch) ; }
    public function deleteAllLunchesByPf($idPurchasingFair) { return $this->lunchDAOOVH->deleteAllByPf($idPurchasingFair); }
    public function findLunchForOneEnterpriseAndPf($idEnterprise, $idPurchasingFair) { 
        $lunch = $this->lunchDAOOVH->findByEnterpriseAndPF($idEnterprise, $idPurchasingFair);
        if( !empty($lunch) ) { 
            $lunch->setOneEnterprise($this->findOneEnterprise($idEnterprise));
            $lunch->setOnePurchasingFair($this->findOnePurchasingFair($idPurchasingFair)); 
        }
        return $lunch;
    }
    /* ------------------------------------------------ ./LunchDAOOVH Methods ------------------------------------------------ */
}
?>