<?php
require_once dirname ( __FILE__ ) . '/../services/AppServiceImpl.class.php';

if(!isset($_SESSION)) session_start(); // Start session

$appService = AppServiceImpl::getInstance();

// POST data
$idEnterprise = (int) $_SESSION['enterpriseConcerned']->getIdEnterprise();
$idPurchasingFair = (int) $_SESSION['purchasingFairConcerned']->getIdPurchasingFair();
$dayPresentPost = (string) $_POST['dayPresent'];
$idParticipantPresent = (string) $_POST['idParticipant'];

$presentFinded = $appService->findPresentByTrio($idEnterprise, $idParticipantPresent, $idPurchasingFair);

$checkDelete = false;

$arrayPresentDetails = $presentFinded->getPresentDetails();

foreach( $arrayPresentDetails as $key => $dayPresentArray ) {
    
    if( $dayPresentPost == $dayPresentArray ) {
        unset($arrayPresentDetails[$key]);
        $checkDelete = true;
        break 1;
    }
}

$presentFinded->setPresentDetails(json_encode($arrayPresentDetails));

$checkInsert = $appService->updatePresent($presentFinded);

// Print result
echo ($checkDelete) ? '1' : '-1';
?>