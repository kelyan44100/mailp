<?php
require_once dirname ( __FILE__ ) . '/../services/AppServiceImpl.class.php';

if(!isset($_SESSION)) session_start(); // Start session

$appService = AppServiceImpl::getInstance();

// POST data
$idEnterprise = (int) $_SESSION['enterpriseConcerned']->getIdEnterprise();
$idPurchasingFair = (int) $_SESSION['purchasingFairConcerned']->getIdPurchasingFair();
$dayLunch = (string) $_POST['dayLunch'];
$idParticipantLunch = (string) $_POST['idParticipant'];

$lunchEnterprise = $appService->findLunchForOneEnterpriseAndPf($idEnterprise, $idPurchasingFair);

$checkDelete = false;

$arrayLunchesDetails = $lunchEnterprise->getLunchesDetails();

foreach( $arrayLunchesDetails[$dayLunch]['participant'] as $key => $idParticipant ) {
    if(  $idParticipantLunch == $idParticipant ) {
        unset($arrayLunchesDetails[$dayLunch]['participant'][$key]);
        if(empty($arrayLunchesDetails[$dayLunch]['participant'])) {
            unset($arrayLunchesDetails[$dayLunch]);
        }
        $checkDelete = true;
        break 1;
    }
}

$lunchEnterprise->setLunchesDetails(json_encode($arrayLunchesDetails));

$checkInsert = $appService->updateLunch($lunchEnterprise);

// Print result
echo ($checkDelete) ? '1' : '-1';
?>