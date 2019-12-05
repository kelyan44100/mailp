<?php
require_once dirname ( __FILE__ ) . '/../services/AppServiceImpl.class.php';

if(!isset($_SESSION)) session_start(); // Start session

$appService = AppServiceImpl::getInstance();

	if( isset($_SESSION['enterpriseConcerned']) && !empty($_SESSION['enterpriseConcerned']) && isset($_SESSION['purchasingFairConcerned']) && !empty($_SESSION['purchasingFairConcerned'])) {
    
        $enterpriseConcerned = $_SESSION['enterpriseConcerned'];
        $purchasingFairConcerned = $_SESSION['purchasingFairConcerned'];

        //print_r($JourSelect);

        $findMySelect = $appService->findLogByIdEnterpriseByThreeBis($purchasingFairConcerned->getIdPurchasingFair(), $JourSelect, $enterpriseConcerned->getIdEnterprise());

        if($findMySelect != null){
            $appService->deleteLogPriseRdvDAOBis($enterpriseConcerned->getIdEnterprise(), $purchasingFairConcerned->getIdPurchasingFair()/*, $JourSelect*/);
        }
        
        //$appService->deleteLog($enterpriseConcerned->getIdEnterprise());

    }else{
        echo 'Error';
    }

?>