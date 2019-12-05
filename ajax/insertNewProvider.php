<?php
require_once dirname ( __FILE__ ) . '/../domain/Enterprise.class.php';
require_once dirname ( __FILE__ ) . '/../services/AppServiceImpl.class.php';

if(!isset($_SESSION)) session_start(); // Start session

$appService = AppServiceImpl::getInstance();

if( isset($_POST['nameNewProvider']) && !empty($_POST['nameNewProvider']) ) {
    
    $nameNewProviderSent = (string) $_POST['nameNewProvider'];
    
    $newProvider = $appService->createEnterprise( $nameNewProviderSent, '12345678', '00.00', $appService->findOneprofile(1), $appService->findOneDepartment(99) );
    
    $count = $appService->saveEnterprise($newProvider);
    
    echo $count;
}
?>