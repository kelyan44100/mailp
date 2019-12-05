<?php
require_once dirname ( __FILE__ ) . '/../services/AppServiceImpl.class.php';

if(!isset($_SESSION)) session_start(); // Start session

$appService = AppServiceImpl::getInstance();

if( isset($_POST['idParticipant']) && !empty($_POST['idParticipant']) && isset($_POST['idPurchasingFair']) && !empty($_POST['idPurchasingFair']) ) {
    
    $idParticipant     = (int) $_POST['idParticipant'];
    $idPurchasingFair  = (int) $_POST['idPurchasingFair'];

    $count = $appService->deleteParticipation($appService->findOneParticipation($idParticipant, $idPurchasingFair));

    echo ($count) ? 'Success' : 'Failed';
}
?>