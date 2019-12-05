<?php
require_once dirname ( __FILE__ ) . '/../services/AppServiceImpl.class.php';

if(!isset($_SESSION)) session_start(); // Start session

$appService = AppServiceImpl::getInstance();

if( isset($_POST['idUnavailability']) && !empty($_POST['idUnavailability']) ) {
    
    $idUnavailability = (int) $_POST['idUnavailability'];

    $count = $appService->deleteUnavailability($appService->findOneUnavailability($idUnavailability));

    echo ($count) ? 'Success' : 'Failed';
}
?>