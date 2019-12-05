<?php
require_once dirname ( __FILE__ ) . '/../services/AppServiceImpl.class.php';
require_once dirname ( __FILE__ ) . '/../view/errors.inc.php';

if(!isset($_SESSION)) session_start(); // Start session

$appService = AppServiceImpl::getInstance();

switch( (int) $_POST['profileEnterprise'] ) {
    
    // Provider profile
    case 1 : 
        
    // Decode data from POST
    $lunchesProvider = json_decode($_POST['arrayProviders'], true); // true => convert object stdClass to array
        
    // Get enterprise
    $provider =  $appService->findOneEnterprise($lunchesProvider['idProvider']);
        
    // Create Lunch
    $lunchCreatedProvider = $appService->createLunch(
        $provider, 
        $_SESSION['purchasingFairConcerned'],
        $lunchesProvider['totLunches'],
        0,
        json_encode($lunchesProvider['detailsLunches']),
        0
        );

    // Insert Lunch
    $appService->saveLunch($lunchCreatedProvider);

    break;
    
    // Store profile
    case 2 : 
    
    // Decode data from POST
    $lunchesStore = json_decode($_POST['arrayStores'], true); // true => convert object stdClass to array
    
    // Get enterprise
    $store =  $appService->findOneEnterprise($lunchesStore['idStore']);
    
    // Get participations
    $arrayParticipationsAlreadyRegistered = AppServiceImpl::getInstance()->findAllParticipationsByEnterpriseAndPurchasingFair(
            $store,
            $_SESSION['purchasingFairConcerned']);
    
    // Total Participations
    $totParticipations = count($arrayParticipationsAlreadyRegistered);
        
    // Update totDay value w/ Participations
    foreach($lunchesStore['detailsLunches'] as $key => $array) {
        if($array['totDay'] > 0) { 
            $lunchesStore['detailsLunches'][$key]['totDay'] *= $totParticipations; 
            // Not directly $array['totDay'] = .....
        }
    }

    // Create Lunch
    $lunchCreatedStore = $appService->createLunch(
            $store, 
            $_SESSION['purchasingFairConcerned'],
            $lunchesStore['totLunches'] * $totParticipations,
            0,
            json_encode($lunchesStore['detailsLunches'])
            );

    // Insert Lunch
    $appService->saveLunch($lunchCreatedStore);
    
    break;

    default : break;
}

echo 'Success';