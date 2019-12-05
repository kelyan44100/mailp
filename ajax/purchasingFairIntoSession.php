<?php
require_once dirname ( __FILE__ ) . '/../domain/PurchasingFair.class.php';
require_once dirname ( __FILE__ ) . '/../services/AppServiceImpl.class.php';

if(!isset($_SESSION)) session_start(); // Start session

$appService = AppServiceImpl::getInstance();

if( isset($_POST['idPurchasingFair']) && !empty($_POST['idPurchasingFair']) ) {
    
    $idPurchasingFairSent = (int) $_POST['idPurchasingFair'];
    
    $purchasingFairSelected = $appService->findOnePurchasingFair($idPurchasingFairSent);
    
    if( !empty( $idPurchasingFairSent ) ) { // Success
        $_SESSION['purchasingFairConcerned'] = $purchasingFairSelected;
       
        $now = new Datetime('now');
        $registrationClosingDate = new DateTime($_SESSION['purchasingFairConcerned']->getRegistrationClosingDateMagasin());

        $res = [$purchasingFairSelected->getOneTypeOfPf()->getNameTypeOfPf(), $now, $registrationClosingDate];
        $contenu = json_encode($res);
        print_r($contenu);
                  
        // echo 'Success';
        // Added 27.08.2018 - Taking others Pf into account
    } else { echo 'Failed'; } // Selection Failed
}
?>