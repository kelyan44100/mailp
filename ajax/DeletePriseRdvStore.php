<?php
require_once dirname ( __FILE__ ) . '/../services/AppServiceImpl.class.php';

if(!isset($_SESSION)) session_start(); // Start session

$appService = AppServiceImpl::getInstance();

	$arrayEnterprises = $appService->findAllEnterprisesAsStores();

	if(isset($_SESSION['enterpriseConcerned']) && !empty($_SESSION['enterpriseConcerned']) && isset($_SESSION['purchasingFairConcerned']) && !empty($_SESSION['purchasingFairConcerned']) && isset($_POST['start_datetime']) && !empty($_POST['start_datetime']) && isset($_POST['idFournisseur']) && !empty($_POST['idFournisseur'])) {

        $idPurchasingFair = $_SESSION['purchasingFairConcerned']->getIdPurchasingFair();
        $idStore = $_SESSION['enterpriseConcerned']->getIdEnterprise();
        $idFournisseur = $_POST['idFournisseur'];
        $start_datetime = $_POST['start_datetime'];

        $del = $appService->deleteRDV($idPurchasingFair, $idStore, $idFournisseur, $start_datetime);
        //print_r($del);

        print_r("Success"); //Error1
        die();
            
    }else{
        print_r("Error1"); //Error1
        die();
    }


?>