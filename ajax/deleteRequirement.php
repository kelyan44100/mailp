<?php
require_once dirname ( __FILE__ ) . '/../services/AppServiceImpl.class.php';

if(!isset($_SESSION)) session_start(); // Start session

$appService = AppServiceImpl::getInstance();

if( isset($_POST['idRequirement']) && !empty($_POST['idRequirement']) ) {
    
    $idRequirement = (int) $_POST['idRequirement'];

    $count = $appService->deleteRequirement($appService->findOneRequirement($idRequirement));

    echo ($count) ? 'Success' : 'Failed';
}
?>