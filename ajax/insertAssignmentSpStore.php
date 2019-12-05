<?php
require_once dirname ( __FILE__ ) . '/../services/AppServiceImpl.class.php';

if(!isset($_SESSION)) session_start(); // Start session

$appService = AppServiceImpl::getInstance();

//var_dump($_POST['NotS']);

if(isset($_POST['salesperson']) && !empty($_POST['salesperson']) && isset($_POST['store']) && !empty($_POST['store']) 
        && isset($_POST['provider']) && !empty($_POST['provider'])  && isset($_POST['purchasingFair']) && !empty($_POST['purchasingFair'])) {

    $idSalesperson    = (int) substr($_POST['salesperson'], 12);
    $idStore          = (int) substr($_POST['store'], 13);
    $idProvider       = (int) $_POST['provider'];
    $idPurchasingFair = (int) $_POST['purchasingFair'];

    //print_r($_SESSION['NotS']);
	if(isset($_SESSION['NotS']) && !empty($_SESSION['NotS'])){
		foreach($_SESSION['NotS'] as $id) {
			/*if($appService->findOneAssignmentSpStore($appService->findOneParticipant($id), $appService->findOneEnterprise($idStore), $appService->findOneEnterprise($idProvider), $appService->findOnePurchasingFair($idPurchasingFair))!= null){*/
				$newASS = $appService->createAssignmentSpStore($appService->findOneParticipant($id), $appService->findOneEnterprise($idStore), $appService->findOneEnterprise($idProvider), $appService->findOnePurchasingFair($idPurchasingFair));
	    
		    	$count1 = $appService->deleteAssignmentSpStoreBis($newASS);
		    	//print_r($count1);
		    	//echo ($count1) ? 'Success' : 'Failed';
			//}
			
		}
	}
	
    
    $newASS = $appService->createAssignmentSpStore($appService->findOneParticipant($idSalesperson), $appService->findOneEnterprise($idStore), $appService->findOneEnterprise($idProvider), $appService->findOnePurchasingFair($idPurchasingFair));
    
    $count = $appService->saveAssignmentSpStore($newASS);
    
    echo ($count) ? 'Success' : 'Failed';

}
?>