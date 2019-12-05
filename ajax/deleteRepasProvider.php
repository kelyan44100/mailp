<?php
require_once dirname ( __FILE__ ) . '/../services/AppServiceImpl.class.php';

if(!isset($_SESSION)) session_start(); // Start session

$appService = AppServiceImpl::getInstance();

    if( isset($_SESSION['enterpriseConcerned']) && !empty($_SESSION['enterpriseConcerned']) && isset($_SESSION['purchasingFairConcerned']) && !empty($_SESSION['purchasingFairConcerned']) && isset($_POST['JourSelect']) && !empty($_POST['JourSelect']) && isset($_POST['idFournisseur']) && !empty($_POST['idFournisseur'])) {
    
        $enterpriseConcerned = $_SESSION['enterpriseConcerned'];
        $purchasingFairConcerned = $_SESSION['purchasingFairConcerned'];
        $JourSelect = $_POST['JourSelect'];
        $idFournisseur = $_POST['idFournisseur'];

        $IdParticipant = $appService->findOneAssignmentSpStoreQuatro($enterpriseConcerned->getIdEnterprise(), $idFournisseur, $_SESSION['purchasingFairConcerned']->getIdPurchasingFair());

        $lunchParDay2 = $appService->findLunchForOneEnterpriseAndPfAndDayBis($idFournisseur, $purchasingFairConcerned->getIdPurchasingFair(), $JourSelect, $IdParticipant->getOneParticipant()->getIdParticipant());

        if($lunchParDay2 != null){
            $appService->DeleteLunchByFour($idFournisseur, $purchasingFairConcerned->getIdPurchasingFair(), $JourSelect, $IdParticipant->getOneParticipant()->getIdParticipant());
        }

        die();

    }else{
        echo 'Error';
        die();
    }

?>