<?php
require_once dirname ( __FILE__ ) . '/../services/AppServiceImpl.class.php';

if(!isset($_SESSION)) session_start(); // Start session

$appService = AppServiceImpl::getInstance();

if( isset($_POST['idRequirement']) && !empty($_POST['idRequirement']) && isset($_POST['numberOfHours']) && !empty($_POST['numberOfHours']) ) {
    
    // Cast
    $idRequirement = (int) $_POST['idRequirement'];
    
    $numberOfHoursWanted = explode(':', $_POST['numberOfHours']);
    $hours   = $numberOfHoursWanted[0];
    $minutes = (int) $numberOfHoursWanted[1];
    
    if($hours == 0 || $hours > 11 || ($hours == 11 && $minutes > 0 ) ) { 
        echo 'Failed'; 
    }
    else {
        $minutes = ( $minutes == 30 ? '50' : '00');

        $numberOfHours = $hours.'.'.$minutes;

        // Find the requirement concerned
        $requirementToUpdate = $appService->findOneRequirement($idRequirement);

        // Set the new number of hours
        $requirementToUpdate->setNumberOfHours($numberOfHours);

        $count = $appService->saveRequirement($requirementToUpdate);

        echo ($count) ? 'Success' : 'Failed';
    }
}
?>