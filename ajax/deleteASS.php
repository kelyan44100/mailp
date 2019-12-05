<?php
require_once dirname ( __FILE__ ) . '/../services/AppServiceImpl.class.php';

if(!isset($_SESSION)) session_start(); // Start session

$appService = AppServiceImpl::getInstance();

if( isset($_POST['idParticipant']) && !empty($_POST['idParticipant']) && isset($_POST['idProvider']) && !empty($_POST['idProvider']) 
        && isset($_POST['idPurchasingFair']) && !empty($_POST['idPurchasingFair']) ) {
    
    $idParticipant    = (int) $_POST['idParticipant'];
    $idProvider       = (int) $_POST['idProvider'];
    $idPurchasingFair = (int) $_POST['idPurchasingFair'];

    $enterpriseFake = $appService->createEnterprise(
            'fakeName', 
            '12345678', 
            '00.00',
			'NC', 
			'NC', 
			'NC', 
			'NC', 
			999,
            $appService->findOneProfile(1), 
            $appService->findOneDepartment(1)
            );
    
    $count = $appService->deleteAssignmentSpStore(
            $appService->createAssignmentSpStore(
                    $appService->findOneParticipant($idParticipant), 
                    $enterpriseFake, 
                    $appService->findOneEnterprise($idProvider), 
                    $appService->findOnePurchasingFair($idPurchasingFair)
                    ));

    echo ($count) ? 'Success' : 'Failed';
}
?>