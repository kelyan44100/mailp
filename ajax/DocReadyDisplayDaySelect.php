<?php
require_once dirname ( __FILE__ ) . '/../services/AppServiceImpl.class.php';

if(!isset($_SESSION)) session_start(); // Start session

$appService = AppServiceImpl::getInstance();

    $arrayEnterprises = $appService->findAllEnterprisesAsStores();

    if( isset($_SESSION['enterpriseConcerned']) && !empty($_SESSION['enterpriseConcerned'])&& isset($_SESSION['purchasingFairConcerned']) && !empty($_SESSION['purchasingFairConcerned'])&& isset($_POST['jourSelect']) && !empty($_POST['jourSelect'])) {
    
        $jourSelect = $_POST['jourSelect'];

        $array = array();

        $array[] = [
                "1" => $appService->nom_jour($jourSelect),
                "2" => substr($jourSelect,8,2),
                "3" => $appService->nom_mois($jourSelect),
            ];

         
        $contenu_json = json_encode($array);
        
        print_r($contenu_json);


    }else{
        echo 'Error';
    }

?>
        