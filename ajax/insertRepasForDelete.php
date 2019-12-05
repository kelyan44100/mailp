<?php
require_once dirname ( __FILE__ ) . '/../services/AppServiceImpl.class.php';

if(!isset($_SESSION)) session_start(); // Start session

$appService = AppServiceImpl::getInstance();

	if( isset($_SESSION['enterpriseConcerned']) && !empty($_SESSION['enterpriseConcerned']) && isset($_SESSION['purchasingFairConcerned']) && !empty($_SESSION['purchasingFairConcerned']) && isset($_POST['JourSelect']) && !empty($_POST['JourSelect'])) {
    
        $enterpriseConcerned = $_SESSION['enterpriseConcerned'];
        $purchasingFairConcerned = $_SESSION['purchasingFairConcerned'];
        $JourSelect = $_POST['JourSelect'];

        $NbrRdvDay = $appService->findThreePriseRdvStoreBis($enterpriseConcerned->getIdEnterprise(), $purchasingFairConcerned->getIdPurchasingFair(), $JourSelect);

        $lunchEnterprise = $appService->findLunchForOneEnterpriseAndPfAndDay($enterpriseConcerned->getIdEnterprise(), $purchasingFairConcerned->getIdPurchasingFair(), $JourSelect);

        if($NbrRdvDay == null && $lunchEnterprise != null){

            echo 'Success';
        }

        die();

    }else{
        echo 'Error';
        die();
    }

?>