<?php
require_once dirname ( __FILE__ ) . '/../services/AppServiceImpl.class.php';

if(!isset($_SESSION)) session_start(); // Start session

$appService = AppServiceImpl::getInstance();

	$arrayEnterprises = $appService->findAllEnterprisesAsStores();

	if( isset($_SESSION['enterpriseConcerned']) && !empty($_SESSION['enterpriseConcerned'])&& isset($_SESSION['purchasingFairConcerned']) && !empty($_SESSION['purchasingFairConcerned'])) {
    
        $enterpriseConcerned = $_SESSION['enterpriseConcerned'];
        $purchasingFairConcerned = $_SESSION['purchasingFairConcerned'];
        
 
        $SpAssignedToStore = $appService->findByThreeIdsBisTwo($_SESSION['enterpriseConcerned']->getIdEnterprise(), $_SESSION['purchasingFairConcerned']->getIdPurchasingFair());

        if($SpAssignedToStore != null){

            $arrayNomEntrepriseCommerciaux = array();

            foreach ($SpAssignedToStore as $key => $value) {

                $arrayNomEntrepriseCommerciaux[] = [
                        "1" => $value->getOneProvider()->getName(),
                        "2" => $value->getOneParticipant()->getSurname(),
                        "3" => $value->getOneProvider()->getIdEnterprise(),
                        "4" => $value->getOneParticipant()->getIdParticipant(),
                    ];

            }
            $contenu_json = json_encode($arrayNomEntrepriseCommerciaux);
            
            print_r($contenu_json);
        }else{
            echo 'Error';
        }

        


    }else{
        echo 'Error';
    }

?>