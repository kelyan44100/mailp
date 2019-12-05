<?php
require_once dirname ( __FILE__ ) . '/../services/AppServiceImpl.class.php';

if(!isset($_SESSION)) session_start(); // Start session

$appService = AppServiceImpl::getInstance();

	if( isset($_SESSION['enterpriseConcerned']) && !empty($_SESSION['enterpriseConcerned']) && isset($_SESSION['purchasingFairConcerned']) && !empty($_SESSION['purchasingFairConcerned']) && isset($_POST['JourSelect']) && !empty($_POST['JourSelect'])) {
    
        $enterpriseConcerned = $_SESSION['enterpriseConcerned'];
        $purchasingFairConcerned = $_SESSION['purchasingFairConcerned'];
        $JourSelect = $_POST['JourSelect'];

        //print_r($JourSelect);

        $findMySelect = $appService->findLogByIdEnterpriseByThreeBis($purchasingFairConcerned->getIdPurchasingFair(), $JourSelect, $enterpriseConcerned->getIdEnterprise());

        if($findMySelect == null){
            $appService->deleteLogPriseRdvDAOBis($enterpriseConcerned->getIdEnterprise(), $purchasingFairConcerned->getIdPurchasingFair()/*, $JourSelect*/);

            $LogPriseRdvDAO = $appService->createLogPriseRdvDAO($enterpriseConcerned->getIdEnterprise(), $purchasingFairConcerned->getIdPurchasingFair(), $JourSelect);

            $appService->saveLogPriseRdvDAO($LogPriseRdvDAO);
        }
        

        $findLogRdv = $appService->findLogByIdEnterpriseByThree($purchasingFairConcerned->getIdPurchasingFair(), $JourSelect, $enterpriseConcerned->getIdEnterprise());

        //print_r($findLogRdv);

        if($findLogRdv != null){
            print_r("Log".$enterpriseConcerned->getIdEnterprise());
        }

        //print_r($LogPriseRdvDAO);



    }else{
        echo 'Error';
    }

?>