<?php
require_once dirname ( __FILE__ ) . '/../services/AppServiceImpl.class.php';

if(!isset($_SESSION)) session_start(); // Start session

$appService = AppServiceImpl::getInstance();

	if( isset($_SESSION['enterpriseConcerned']) && !empty($_SESSION['enterpriseConcerned']) && isset($_SESSION['purchasingFairConcerned']) && !empty($_SESSION['purchasingFairConcerned']) && isset($_POST['check']) && !empty($_POST['check']) && isset($_POST['nbrepas']) && !empty($_POST['nbrepas']) && isset($_POST['jour_datetime']) && !empty($_POST['jour_datetime'])) {
    
        $enterpriseConcerned = $_SESSION['enterpriseConcerned'];
        $purchasingFairConcerned = $_SESSION['purchasingFairConcerned'];
        $check = $_POST['check'];
        $nbrepas = $_POST['nbrepas'];
        $jour_datetime = $_POST['jour_datetime'];
        
        $search = $appService->findLunchForOneEnterpriseAndPfAndDay($enterpriseConcerned->getIdEnterprise(), $purchasingFairConcerned->getIdPurchasingFair(), $jour_datetime);

        if($search != null){
            $lunchEnterprise1 = $appService->createLunch($enterpriseConcerned->getIdEnterprise(), $purchasingFairConcerned->getIdPurchasingFair(), $nbrepas, 0, $jour_datetime, 0);
            $appService->updateLunch($lunchEnterprise1);
        }else{
            $lunchEnterprise2 = $appService->createLunch($enterpriseConcerned->getIdEnterprise(), $purchasingFairConcerned->getIdPurchasingFair(), $nbrepas, 0, $jour_datetime, 0);
            $appService->saveLunch($lunchEnterprise2);
        }

        $lunchEnterprisebis = $appService->findLunchForOneEnterpriseAndPfAndDay($enterpriseConcerned->getIdEnterprise(), $purchasingFairConcerned->getIdPurchasingFair(), $jour_datetime);

        $contenu_json = json_encode($lunchEnterprisebis);

        print_r($contenu_json);


        die();

    }else{
        echo 'Error';
        die();
    }

?>