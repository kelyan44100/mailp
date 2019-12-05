<?php
require_once dirname ( __FILE__ ) . '/../dao/AssignmentParticipantDepartmentDAOOVH.class.php';
require_once dirname ( __FILE__ ) . '/../dao/AssignmentParticipantEnterpriseDAOOVH.class.php';
require_once dirname ( __FILE__ ) . '/../dao/AssignmentSpStoreDAOOVH.class.php';
require_once dirname ( __FILE__ ) . '/../dao/DepartmentDAOOVH.class.php';
require_once dirname ( __FILE__ ) . '/../dao/EnterpriseDAOOVH.class.php';
require_once dirname ( __FILE__ ) . '/../dao/EnterpriseContactDAOOVH.class.php';
require_once dirname ( __FILE__ ) . '/../dao/LogDAOOVH.class.php';
require_once dirname ( __FILE__ ) . '/../dao/LunchDAOOVH.class.php';
require_once dirname ( __FILE__ ) . '/../dao/ParticipantDAOOVH.class.php';
require_once dirname ( __FILE__ ) . '/../dao/ParticipationDAOOVH.class.php';
require_once dirname ( __FILE__ ) . '/../dao/ProfileDAOOVH.class.php';
require_once dirname ( __FILE__ ) . '/../dao/ProviderPresentDAOOVH.class.php';
require_once dirname ( __FILE__ ) . '/../dao/PurchasingFairDAOOVH.class.php';
require_once dirname ( __FILE__ ) . '/../dao/QRCodeScanDAOOVH.class.php';
require_once dirname ( __FILE__ ) . '/../dao/RequirementDAOOVH.class.php';
require_once dirname ( __FILE__ ) . '/../dao/SalespersonDAOOVH.class.php';
require_once dirname ( __FILE__ ) . '/../dao/SpecialGuestDAOOVH.class.php';
require_once dirname ( __FILE__ ) . '/../dao/StatDAOOVH.class.php';
require_once dirname ( __FILE__ ) . '/../dao/TypeOfPfDAOOVH.class.php';
require_once dirname ( __FILE__ ) . '/../dao/TypeOfProviderDAOOVH.class.php';
require_once dirname ( __FILE__ ) . '/../dao/UnavailabilityDAOOVH.class.php';
require_once dirname ( __FILE__ ) . '/../dao/UnavailabilitySpDAOOVH.class.php';
require_once dirname ( __FILE__ ) . '/../domain/AssignmentParticipantDepartment.class.php';
require_once dirname ( __FILE__ ) . '/../domain/AssignmentParticipantEnterprise.class.php';
require_once dirname ( __FILE__ ) . '/../domain/AssignmentSpStore.class.php';
require_once dirname ( __FILE__ ) . '/../domain/Department.class.php';
require_once dirname ( __FILE__ ) . '/../domain/Enterprise.class.php';
require_once dirname ( __FILE__ ) . '/../domain/EnterpriseContact.class.php';
require_once dirname ( __FILE__ ) . '/../domain/Log.class.php';
require_once dirname ( __FILE__ ) . '/../domain/Lunch.class.php';
require_once dirname ( __FILE__ ) . '/../domain/MyEmail.class.php';
require_once dirname ( __FILE__ ) . '/../domain/Participant.class.php';
require_once dirname ( __FILE__ ) . '/../domain/Participation.class.php';
require_once dirname ( __FILE__ ) . '/../domain/Profile.class.php';
require_once dirname ( __FILE__ ) . '/../domain/ProviderPresent.class.php';
require_once dirname ( __FILE__ ) . '/../domain/PurchasingFair.class.php';
require_once dirname ( __FILE__ ) . '/../domain/QRCodeScan.class.php';
require_once dirname ( __FILE__ ) . '/../domain/RandomColor.class.php';
require_once dirname ( __FILE__ ) . '/../domain/Requirement.class.php';
require_once dirname ( __FILE__ ) . '/../domain/Salesperson.class.php';
require_once dirname ( __FILE__ ) . '/../domain/SpecialGuest.class.php';
require_once dirname ( __FILE__ ) . '/../domain/TypeOfPf.class.php';
require_once dirname ( __FILE__ ) . '/../domain/TypeOfProvider.class.php';
require_once dirname ( __FILE__ ) . '/../domain/Unavailability.class.php';
require_once dirname ( __FILE__ ) . '/../domain/UnavailabilitySp.class.php';
require_once dirname ( __FILE__ ) . '/../domain/Various.class.php';

require_once dirname ( __FILE__ ) . '/../domain/Planning.class.php';
require_once dirname ( __FILE__ ) . '/../domain/PlanningDay.class.php';
require_once dirname ( __FILE__ ) . '/../domain/TimeSlot.class.php';


// Don't require these classes again, require only AppServiceOVH.class on each page

interface AppServiceOVH {

    /* ------------------------------------------------ UnavailabilityDAOOVH methods ------------------------------------------------  */
    public function createUnavailability($startDatetime, $endDatetime, $idEnterprise, $idPurchasingFair);
    public function deactivateUnavailability(Unavailability $unavailability);
    public function deleteUnavailability(Unavailability $unavailability);
    public function findOneUnavailability($searchedIdUnavailability);
    public function findAllUnavailabilities();
    public function findEnterpriseUnavailabilities(Enterprise $enterprise, PurchasingFair $purchasingFair);
    public function saveUnavailability(Unavailability $unavailability);
    public function findPurchasingFairUnavailabilities(PurchasingFair $purchasingFair);
    /* ------------------------------------------------ ./UnavailabilityDAOOVH methods ------------------------------------------------  */
    
    /* ------------------------------------------------ UnavailabilitySpDAOOVH methods ------------------------------------------------  */
    public function createUnavailabilitySp($startDatetime, $endDatetime, $idParticipant, $idPurchasingFair);
    public function deactivateUnavailabilitySp(UnavailabilitySp $unavailabilitySp);
    public function deleteUnavailabilitySp(UnavailabilitySp $unavailabilitySp);
    public function findOneUnavailabilitySp($searchedIdUnavailabilitySp);
    public function findAllUnavailabilitiesSp();
    public function findAllUnavailabilitiesSpByParticipant($searchedIdParticipant);
    public function saveUnavailabilitySp(UnavailabilitySp $unavailabilitySp);
    public function findParticipantUnavailabilitiesSp(Participant $participant, PurchasingFair $purchasingFair);
    public function findPurchasingFairUnavailabilitiesSp(PurchasingFair $purchasingFair);
    public function findSpWithUnavByEntAndPf($idEnterprise, $idPurchasingFair);
    /* ------------------------------------------------ ./UnavailabilitySpDAOOVH methods ------------------------------------------------  */
    
    /* ------------------------------------------------ PurchasingFairDAOOVH methods ------------------------------------------------ */
    public function createPurchasingFair($namePurchasingFair, $hexColor, $startDatetime, $endDateTime, $lunchBreak, $oneTypeOfPf, $registrationClosingDate);
    public function deactivatePurchasingFair(PurchasingFair $purchasingFair);
    public function deletePurchasingFair(PurchasingFair $purchasingFair);
    public function findOnePurchasingFair($searchedIdPurchasingFair);
    public function findAllPurchasingFairs();
    public function findAllPurchasingFairsAdmin();
    public function findLastPurchasingFair();
    public function savePurchasingFair(PurchasingFair $purchasingFair);
    /* ------------------------------------------------ ./PurchasingFairDAOOVH methods ------------------------------------------------ */
    
    /* ------------------------------------------------ EnterpriseDAOOVH methods ------------------------------------------------ */
    public function createEnterprise($name, $password, $panel, $postalAddress, $postalCode, $city, $vat, $oneTypeOfProvider, $oneProfile, $oneDepartment);
    public function authentication($idEnterprise, $password, $profileEnterprise);
    public function deactivateEnterprise(Enterprise $enterprise);
    public function deleteEnterprise(Enterprise $enterprise);
    public function findOneEnterprise($searchedIdEnterprise);
    public function findAllEnterprises();
    public function findAllEnterprisesAsProviders();
    public function findAllEnterprisesAsProvidersPf($searchedIdPurchasingFair);
    public function findAllProvidersWithTextilePriority($idPurchasingFair);
    public function findAllEnterprisesAsStores();
    public function findAllEnterprisesAsStoresPf($searchedIdPurchasingFair);
    public function saveEnterprise(Enterprise $enterprise);
    public function findAllStoresNotAvailableForTimeSlotAndPf($onePurchasingFair, $startTimeSlot, $endTimeSlot);
    /* ------------------------------------------------ ./EnterpriseDAOOVH methods ------------------------------------------------ */
    
    /* ------------------------------------------------ ProfileDAOOVH methods ------------------------------------------------ */
    public function createProfile($name);
    public function deactivateProfile(Profile $profile);
    public function deleteProfile(Profile $profile);
    public function findOneProfile($searchedIdProfile);
    public function findAllProfiles();
    public function saveProfile(Profile $profile);
    /* ------------------------------------------------ ./ProfileDAOOVH methods ------------------------------------------------ */
     
    /* ------------------------------------------------ DepartmentDAOOVH methods ------------------------------------------------ */
    public function createDepartment($name);
    public function deleteDepartment(Department $department);
    public function findOneDepartment($searchedIdDepartment);
    public function findAllDepartments();
    /* ------------------------------------------------ ./DepartmentDAOOVH methods ------------------------------------------------ */

    /* ------------------------------------------------ ParticipantDAOOVH Methods ------------------------------------------------ */
    public function createParticipant($civility, $surname, $name, $email);
    public function deactivateParticipant(Participant $participant);
    public function deleteParticipant(Participant $participant);
    public function findAllParticipants();
    public function findOneParticipant($searchedIdParticipant);
    public function saveParticipant(Participant $participant);
    public function findAllParticipantsAsSalespersons();
    public function findAllParticipantsAsSalespersonsByProvider($idProvider);
    public function findAllParticipantsAsSalespersonsByProviderAndPf($idProvider, $idPurchasingFair);
    public function summaryParticipants($idPurchasingFair);
    /* ------------------------------------------------ ./ParticipantDAOOVH Methods ------------------------------------------------ */

    /* ------------------------------------------------ LogDAOOVH Methods ------------------------------------------------ */
    public function createLog($oneEnterprise, $ipAddress, $actionDescription);
    public function findAllLogs();
    public function findOneLog($searchedIdLog);
    public function saveLog(Log $log);
    public function findAllLogsForOneEnterprise(Enterprise $enterprise);
    /* ------------------------------------------------ ./LogDAOOVH Methods ------------------------------------------------ */
    
    /* ------------------------------------------------ SalespersonDAOOVH Methods ------------------------------------------------ */
    public function createSalesperson($civility, $surname, $name);
    public function deactivateSalesperson(Salesperson $salesperson);
    public function deleteSalesperson(Salesperson $salesperson);
    public function findOneSalesperson($searchedIdSalesperson);
    public function findAllSalespersons();
    public function saveSalesperson(Salesperson $salesperson);   
    /* ------------------------------------------------ ./SalespersonDAOOVH Methods ------------------------------------------------ */
    
    /* ------------------------------------------------ AssignmentParticipantEnterpriseDAOOVH Methods ------------------------------------------------ */
    public function createAssignmentParticipantEnterprise(Participant $participant, Enterprise $oneEnterprise);
    public function deleteAssignmentParticipantEnterprise(AssignmentPArticipantEnterprise $assignmentParticipantEnterprise);
    public function findOneAssignmentParticipantEnterprise($searchIdParticipant, $searchedIdEnterprise);
    public function findAllAssignmentsParticipantEnterprise();
    public function findAllAssignmentsParticipantEnterpriseForOneParticipant($searchedIdParticipant);
    public function findAllAssignmentsParticipantEnterpriseForOneEnterprise($searchedIdEnterprise);
    public function saveAssignmentParticipantEnterprise(AssignmentparticipantEnterprise $assignmentParticipantEnterprise);  
    /* ------------------------------------------------ ./AssignmentParticipantEnterpriseDAOOVH Methods ------------------------------------------------ */
    
    /* ------------------------------------------------ AssignmentParticipantDepartmentDAOOVH Methods ------------------------------------------------ */
    public function createAssignmentParticipantDepartment(Participant $oneParticipant, Department $oneDepartment);
    public function deleteAssignmentParticipantDepartment(AssignmentParticipantDepartment $assignmentParticipantDepartment);
    public function findOneAssignmentParticipantDepartment($searchedIdParticipant, $searchedIdDepartment);
    public function findAllAssignmentsParticipantDepartment();
    public function findAssignmentsParticipantDepartmentByParticipant($searchedIdParticipant);
    public function saveAssignmentParticipantDepartment(AssignmentParticipantDepartment $assignmentParticipantDepartment);    
    /* ------------------------------------------------ ./AssignmentParticipantDepartmentDAOOVH Methods ------------------------------------------------ */
    
    /* ------------------------------------------------ RequirementDAOOVH methods ------------------------------------------------ */
    public function createRequirement($oneStore, $oneProvider, $onePurchasingFair, $numberOfHours);
    public function deleteRequirement(Requirement $requirement);
    public function findOneRequirement($searchedIdRequirement);
    public function findRequirementFilteredDuo($oneStore, $onePurchasingFair);
    public function findRequirementFilteredDuoWithTotNumberHours($oneStore, $onePurchasingFair);
    public function findRequirementFilteredDuoWithTotNumberHoursAndUnavs($oneStore, $onePurchasingFair);
    public function deleteRequirementFilteredDuo($oneStore, $onePurchasingFair);
    public function findRequirementFilteredTrio($oneStore, $oneProvider, $onePurchasingFair);
    public function findAllRequirements();
    // public function findAllRequirementsForStoreAndPurchasingFair($idStore, $idPurchasingFair);
    public function saveRequirement(Requirement $requirement);
    /* ------------------------------------------------ ./RequirementDAOOVH methods ------------------------------------------------ */

    /* ------------------------------------------------ Various methods ------------------------------------------------ */
    public function getIpAddress();
    public function myFrenchDate($datetimeMySql);
    public function myFrenchDatetime($datetimeMySql);
    public function myFrenchTime($datetimeMySql);
    public function deadline($datetimeMySql);
    public function bool2str($bool);
    public function compareObjects(&$o1, &$o2);
    public function generateFakeUsers($howMany);
    public function generateFakePF($howMany);
    public function purchasingFairIsClosedForUser($datetimeMySql);
    public function mychrono();
    public function sixDigitsGenerator($howManyCombinationsDoYouWant);
    public function generateQRCodes($idPurchasingFair, $arrayParticipants);
    public function generateStickers($idPurchasingFair, $arrayParticipants);
    public function convertDateRangeToMySqlFormat($dateRange);
    public function convertTwoMySqlDatetimeToDateRangeFormat($tartDatetime, $endDatetime);
    public function generateParticipationDetailsPDF(Enterprise $oneEnterprise, PurchasingFair $onePurchasingFair);
    public function sortParticipantsBySurnameAndName($arrayOfObjectsThatContainParticipantsObjects);
    public function sortParticipantsBySurnameAndNameBis($arrayOfObjectsThatNotContainParticipantsObjects);
    public function generateNColors($nColors);
    public function generateNPasswords($nbPasswords);
    public function randomKey();
    public function generateSQLQueriesPanelCodes();
    public function sendMail(MyEmail $myEmail);
    public function castelAccess();
    public function numberFormat($numberToFormat, $numberFormat);
    /* ------------------------------------------------ ./Various methods ------------------------------------------------ */
    
    /* ------------------------------------------------ StatDAOOVH methods ------------------------------------------------ */
    public function numberOfParticipationsInAPurchasingFair(User $user);
    public function numberOfConnectionsByMonth(User $user);
    public function heatmapConnections(user $user);
    /* ------------------------------------------------ ./StatDAOOVH methods ------------------------------------------------ */
    
    /* ------------------------------------------------ ParticipationDAOOVH Methods ------------------------------------------------ */
    public function createParticipation($oneParticipant, $onePurchasingFair, $passwordParticipant, $lunch);
    public function deleteParticipation(Participation $participation);
    public function findOneParticipation($searchedIdParticipant, $searchedIdPurchasingFair);
    public function findAllParticipations();
    public function saveParticipation(Participation $participation);    
    public function findAllParticipationsByEnterpriseAndPurchasingFair($oneEnterprise, $onePurchasingFair);
    /* ------------------------------------------------ ./ParticipationDAOOVH Methods ------------------------------------------------ */
    
    /* ------------------------------------------------ TypeOfPfDAOOVH Methods ------------------------------------------------ */
    public function createTypeOfPf($nameTypeOfPf);
    public function findOneTypeOfPf($searchedIdTypeOfPf);
    public function findAllTypeOfPf();
    /* ------------------------------------------------ ./TypeOfPfDAOOVH Methods ------------------------------------------------ */
    
    /* ------------------------------------------------ AssignmentSpStoreDAOOVH Methods ------------------------------------------------ */
    public function createAssignmentSpStore(Participant $oneParticipant, Enterprise $oneStore, Enterprise $oneProvider, PurchasingFair $onePurchasingFair);
    public function deleteAssignmentSpStore(AssignmentSpStore $assignmentSpStore);
    public function deleteAssignmentSpStoreBis(AssignmentSpStore $assignmentSpStore);
    public function findAllAssignmentsSpStore(); /* BUG */
    public function saveAssignmentSpStore(AssignmentSpStore $assignmentSpStore);
    public function findOneAssignmentSpStore($searchedIdParticipant, $searchedIdStore, $searchedIdProvider, $searchedIdPurchasingFair);
    public function findOneAssignmentSpStoreBis($searchedIdProvider, $searchedIdPurchasingFair);
    public function findOneAssignmentSpStoreTer($searchedIdParticipant, $searchedIdProvider, $searchedIdPurchasingFair);
    public function findOneAssignmentSpStoreQuatro($searchedIdStore, $searchedIdProvider, $searchedIdPurchasingFair);
    public function findAllAssignmentSpStoreByParticipant($searchedIdParticipant);
    public function summaryOfAssignedStores($searchedIdProvider, $searchedIdPurchasingFair);
    

    /* ------------------------------------------------ ./AssignmentSpStoreDAOOVH Methods ------------------------------------------------ */
    
    /* ------------------------------------------------ QRCodeScanDAOOVH Methods ------------------------------------------------ */
    public function createQRCodeScan($onePurchasingFair, $oneEnterprise, $oneParticipant, $scanDatetime);
    public function findAllQRCodeScan();
    public function findOneQRCodeScan($searchedIdQRCodeScan);
    public function deleteQRCodeScan($idQRCodeScan);
    public function saveQRCodeScan(QRCodeScan $qrcodeScan);
    public function findAllQRCodeScanByTrio($idPurchasingFair, $idEnterprise, $idParticipant);
    /* ------------------------------------------------ ./QRCodeScanDAOOVH Methods ------------------------------------------------ */
    
    /* ------------------------------------------------ EnterpriseContactDAOOVH methods ------------------------------------------------ */
    public function createEnterpriseContact($civility, $surname, $name, $email, $oneEnterprise);
    public function deactivateEnterpriseContact(EnterpriseContact $enterpriseContact);
    public function deleteEnterpriseContact(EnterpriseContact $enterpriseContact);
    public function findOneEnterpriseContact($searchedIdEnterpriseContact);
    public function findOneEnterpriseContactByEnterprise($idEnterprise);
    public function findAllEnterpriseContact();
    public function saveEnterpriseContact(EnterpriseContact $enterpriseContact);
    /* ------------------------------------------------ ./EnterpriseContactDAOOVH methods ------------------------------------------------ */

    /* ------------------------------------------------ Planning generation ------------------------------------------------ */
    /* ------------------------------------------------ ./Planning generation ------------------------------------------------ */
    
    /* ------------------------------------------------ ProviderPresentDAOOVH Methods ------------------------------------------------ */
    public function createProviderPresent(Enterprise $oneProvider, PurchasingFair $idPurchasingFair);
    public function deleteProviderPresent(ProviderPresent $providerPresent);
    public function deletePPForOnePurchasingFair($idPurchasingFair);
    public function findOneProviderPresent($searchIdProvider, $searchedIdPurchasingFair);
    public function findAllProviderPresent();
    public function findAllProviderPresentForOneProvider($searchedIdProvider);
    public function findAllProviderPresentForOnePurchasingFair($searchedIdPurchasingFair);
    public function saveProviderPresent(ProviderPresent $providerPresent);
    /* ------------------------------------------------ ./ProviderPresentDAOOVH Methods ------------------------------------------------ */
    
    /* ------------------------------------------------ TypeOfPfDAOOVH Methods ------------------------------------------------ */
    public function createTypeOfProvider($nameTypeOfProvider);
    public function findOneTypeOfProvider($searchedIdTypeOfProvider);
    public function findAllTypeOfProvider();
    /* ------------------------------------------------ ./TypeOfPfDAOOVH Methods ------------------------------------------------ */
    
    /* ------------------------------------------------ SpecialGuestDAOOVH Methods ------------------------------------------------ */
    public function createSpecialGuest($oneEnterprise, $onePurchasingFair, $civility, $surname, $name, $days);
    public function findAllSpecialGuest();
    public function findOneSpecialGuest($idSpecialGuest);
    public function saveSpecialGuest(SpecialGuest $specialGuest);
    public function deleteSpecialGuest($idSpecialGuest);
    public function deleteAllSpecialGuest();
    public function findAllSpecialGuestForOneEnterpriseAndPf($idEnterprise, $idPurchasingFair);
    /* ------------------------------------------------ ./SpecialGuestDAOOVH Methods ------------------------------------------------ */
    
    /* ------------------------------------------------ LunchDAOOVH Methods ------------------------------------------------ */
    public function createLunch($oneEnterprise, $onePurchasingFair, $lunchesPlanned, $lunchesCanceled);
    public function lunchesCalculated($idPurchasingFair);
    public function saveLunch(Lunch $lunch);
    public function updateLunch(Lunch $lunch);
    public function deleteAllLunchesByPf($idPurchasingFair);
    public function findLunchForOneEnterpriseAndPf($idEnterprise, $idPurchasingFair);
    /* ------------------------------------------------ ./LunchDAOOVH Methods ------------------------------------------------ */
}
?>