<?php
require_once dirname ( __FILE__ ) . '/../services/AppServiceImpl.class.php';

if(!isset($_SESSION)) session_start(); // Start session

$appService = AppServiceImpl::getInstance();

if( isset($_POST['salespersons']) && !empty($_POST['salespersons']) ) {
   
    $arraySalespersons = json_decode($_POST['salespersons']);

    $arrayAllSalespersons = json_decode($_POST['AllSalespersons']);
    
    $salespersonsFormOtherProviders = json_decode($_POST['salespersonsFormOtherProviders']);
    
    $salespersonsIntoSession = array();

    $salespersonsIntoSessionBis = array();

    $AllCommerciauxPotentiels = array();

    foreach($arraySalespersons as $value) { //on ajoute dans les array les commerciaux selectionné dans les checkbox
        $idSalesperson = (int) substr($value, 14);
        $salespersonsIntoSession[] = $appService->findOneParticipant($idSalesperson);
        $AllCommerciauxPotentiels[] = $appService->findOneParticipant($idSalesperson);
    }

    // on ajoute dans les array des commerciaux qui n'ont pas été coché dans les checkbox.
    $test = FALSE;
    foreach($arrayAllSalespersons as $value) {
        $test = FALSE;
        $idOneOfAllSalespersons = (int) substr($value, 14);
        foreach($arraySalespersons as $value) {
            $idSalesperson = (int) substr($value, 14);
            if($idOneOfAllSalespersons == $idSalesperson){
                $test = TRUE;
            }
        }
        if($test == FALSE){
            $salespersonsIntoSessionBis[] = $appService->findOneParticipant($idOneOfAllSalespersons);
            $AllCommerciauxPotentiels[] = $appService->findOneParticipant($idOneOfAllSalespersons);
        }
    }

    //print_r($salespersonsIntoSessionBis) ;

    // on ajoute dans les array les commerciaux existants cher d'autres fournisseurs
    foreach($salespersonsFormOtherProviders as $value) { 
        $salespersonFromOtherProviders = $appService->findOneParticipant($value);
        $newAPE = $appService->createAssignmentParticipantEnterprise($salespersonFromOtherProviders, $_SESSION['enterpriseConcerned']);
        $appService->saveAssignmentParticipantEnterprise($newAPE);
        $salespersonsIntoSession[] = $salespersonFromOtherProviders;
        $AllCommerciauxPotentiels[] = $salespersonFromOtherProviders;
    }

    //print_r($AllCommerciauxPotentiels); //ca affiche bien tous les commerciaux potentiels.

    if( !(empty($salespersonsIntoSession)) ) { // Success
        $_SESSION['salespersonsConcerned'] = $salespersonsIntoSession;
        $_SESSION['NotsalespersonsConcerned'] = $salespersonsIntoSessionBis;
        $_SESSION['salespersonsConcernedIterator'] = 0;
        $_SESSION['AllCommerciauxPotentiels'] = $AllCommerciauxPotentiels;
        echo 'Success';
    } else { echo 'Failed'; } // Failed
}
?>