<?php
require_once dirname ( __FILE__ ) . '/../services/AppServiceImpl.class.php';

if(!isset($_SESSION)) session_start(); // Start session

$appService = AppServiceImpl::getInstance();

	if( isset($_SESSION['enterpriseConcerned']) && !empty($_SESSION['enterpriseConcerned']) && isset($_SESSION['purchasingFairConcerned']) && !empty($_SESSION['purchasingFairConcerned']) && isset($_POST['JourSelect']) && !empty($_POST['JourSelect']) && isset($_POST['nbRepas']) && !empty($_POST['nbRepas'])) {
    
        $enterpriseConcerned = $_SESSION['enterpriseConcerned'];
        $purchasingFairConcerned = $_SESSION['purchasingFairConcerned'];
        $JourSelect = $_POST['JourSelect'];
        $nbRepas = $_POST['nbRepas'];
        
        $lunchEnterprise = $appService->createLunch($enterpriseConcerned->getIdEnterprise(), $purchasingFairConcerned->getIdPurchasingFair(), $nbRepas, 0, $JourSelect, 0);
        $res = $appService->updateLunch($lunchEnterprise);

        print_r($res);

        die();

    }else{
        echo 'Error';
        die();
    }

?>