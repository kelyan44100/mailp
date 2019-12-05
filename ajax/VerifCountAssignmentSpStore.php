<?php
require_once dirname ( __FILE__ ) . '/../services/AppServiceImpl.class.php';

if(!isset($_SESSION)) session_start(); // Start session

$appService = AppServiceImpl::getInstance();

	$arrayEnterprises = $appService->findAllEnterprisesAsStores();

	// $_SESSION['AllCommerciauxPotentielsDuFournisseurs'] est une array de tous les IDs des commerciaux potentiels
	if( isset($_SESSION['AllCommerciauxPotentielsDuFournisseurs']) && !empty($_SESSION['AllCommerciauxPotentielsDuFournisseurs']) ) {
    
	    $arrayAllCommerciaux = $_SESSION['AllCommerciauxPotentielsDuFournisseurs'];
	    $nbParticipants = count($arrayAllCommerciaux);

	}

    $test = "0";
    foreach($arrayEnterprises as $key => $value1) {
    	$storeConcerned = $value1;
    	$test = "0";
    	foreach($arrayAllCommerciaux as $key => $value) {
    		if($appService->findOneAssignmentSpStore($value, $storeConcerned->getIdEnterprise(), $_SESSION['enterpriseConcerned']->getIdEnterprise(), $_SESSION['purchasingFairConcerned']->getIdPurchasingFair()) != null ){
    			$test = "1";
    		}
		} 
		if($test == "0"){
        	echo 'Failed';
	    } /*else{
	    	echo 'Success';
	    }*/

    }
    if($test == "1"){
        echo 'Success';
    }


?>