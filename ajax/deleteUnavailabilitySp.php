<?php
require_once dirname ( __FILE__ ) . '/../services/AppServiceImpl.class.php';

if(!isset($_SESSION)) session_start(); // Start session

$appService = AppServiceImpl::getInstance();

if( isset($_POST['idUnavailabilitySp']) && !empty($_POST['idUnavailabilitySp']) ) {
    
    $idUnavailabilitySp = (int) $_POST['idUnavailabilitySp'];

    $count = $appService->deleteUnavailabilitySp($appService->findOneUnavailabilitySp($idUnavailabilitySp));

    echo ($count) ? 'Success' : 'Failed';
}
?>