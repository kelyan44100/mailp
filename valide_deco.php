<?php
require_once dirname ( __FILE__ ) . '/services/AppServiceImpl.class.php';

if(!isset($_SESSION)) session_start(); // Session start()

$appService = AppServiceImpl::getInstance();

if( isset($_SESSION['enterpriseConcerned']) && !empty($_SESSION['enterpriseConcerned']) && isset($_SESSION['purchasingFairConcerned']) && !empty($_SESSION['purchasingFairConcerned'])) {
    
    $enterpriseConcerned = $_SESSION['enterpriseConcerned'];
    $purchasingFairConcerned = $_SESSION['purchasingFairConcerned'];

    $findMySelect = $appService->findLogByTwo($purchasingFairConcerned->getIdPurchasingFair(), $enterpriseConcerned->getIdEnterprise());

    //print_r($findMySelect);

    if($findMySelect != null){
        $appService->deleteLogPriseRdvDAOBis($enterpriseConcerned->getIdEnterprise(), $purchasingFairConcerned->getIdPurchasingFair());
    }
}

?>