<?php
require_once dirname ( __FILE__ ) . '/../services/AppServiceImpl.class.php';
if(!isset($_SESSION)) session_start(); // Start session
$appService = AppServiceImpl::getInstance();

$idEnterprise = (int) $_POST['idEnterprise'];
$plusOrMinus  = (int) $_POST['plusOrMinus'];

// Get Lunch concerned
$lunchToUpdate = $appService->findLunchForOneEnterpriseAndPf($idEnterprise,$_SESSION['purchasingFairConcerned']->getIdPurchasingFair());

// Update lunches canceled
if(!empty($lunchToUpdate)) {                                                        
    foreach($lunchToUpdate as $key => $value) {
    	//print_r($value);
        $value->setLunchesCanceled( $value->getLunchesCanceled() + $plusOrMinus ); // MAX 255 TINYINT
        $appService->updateLunch($value);

    }
}

echo "1";
