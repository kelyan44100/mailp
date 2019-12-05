<?php
require_once dirname ( __FILE__ ) . '/../services/AppServiceImpl.class.php';

if(!isset($_SESSION)) session_start(); // Start session

$appService = AppServiceImpl::getInstance();

if( isset($_POST['salesperson']) && !empty($_POST['salesperson']) && isset($_POST['store']) && !empty($_POST['store']) 
        && isset($_POST['provider']) && !empty($_POST['provider']) && isset($_POST['purchasingFair']) && !empty($_POST['purchasingFair'])) {
    
    $idSalesperson    = (int) substr($_POST['salesperson'], 12);
    $idStore          = (int) substr($_POST['store'], 13);
    $idProvider       = (int) $_POST['provider'];
    $idPurchasingFair = (int) $_POST['purchasingFair'];
    
    $newASS = $appService->createAssignmentSpStore($appService->findOneParticipant($idSalesperson), $appService->findOneEnterprise($idStore), $appService->findOneEnterprise($idProvider), $appService->findOnePurchasingFair($idPurchasingFair));
    
    $count = $appService->deleteAssignmentSpStoreBis($newASS);
       
    echo ($count) ? 'Success' : 'Failed';
}
?>