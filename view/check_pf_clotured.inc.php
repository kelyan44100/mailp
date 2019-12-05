<?php
require_once dirname ( __FILE__ ) . '/../domain/PurchasingFair.class.php';
require_once dirname ( __FILE__ ) . '/../services/AppServiceImpl.class.php';

if(!isset($_SESSION)) session_start(); // Start session

/* To prevent server misconfigured */
date_default_timezone_set('Europe/Paris');

/*if(!isset($_SESSION['enterpriseConcerned']) && empty($_SESSION['enterpriseConcerned'])) {
    header('Location: ./disconnection.php'); // Redirection to Purchasing Fair list
}else{*/

// Check if PurchasingFair is closed
$now = new DateTime('now');

if(isset($_SESSION['enterpriseConcerned']) && !empty($_SESSION['enterpriseConcerned']) && $_SESSION['enterpriseConcerned']->getOneprofile()->getName() == 'Fournisseur'){
    $registrationClosingDate = new DateTime($_SESSION['purchasingFairConcerned']->getRegistrationClosingDateFournisseur());
}else{
    $registrationClosingDate = new DateTime($_SESSION['purchasingFairConcerned']->getRegistrationClosingDateMagasin());
}

//}

// Check if PurchasingFair is closed
/*$now                     = new DateTime('now');
$registrationClosingDate = new DateTime($_SESSION['purchasingFairConcerned']->getRegistrationClosingDateMagasin());*/




// As of PHP 5.2.2, DateTime objects can be compared using comparison operators
$isClotured = ( $now > $registrationClosingDate ); // True = clotured ; false = Opened
?>