<?php
require_once dirname ( __FILE__ ) . '/services/AppServiceImpl.class.php';

if(!isset($_SESSION)) session_start(); // Session start()

$appService = AppServiceImpl::getInstance();

if( isset($_SESSION['enterpriseConcerned']) && !empty($_SESSION['enterpriseConcerned']) && isset($_SESSION['purchasingFairConcerned']) && !empty($_SESSION['purchasingFairConcerned'])) {
	
	$enterpriseConcerned = $_SESSION['enterpriseConcerned'];
    $purchasingFairConcerned = $_SESSION['purchasingFairConcerned'];

    $findMySelect = $appService->findLogByTwo($purchasingFairConcerned->getIdPurchasingFair(), $enterpriseConcerned->getIdEnterprise());

    //print_r($findMySelect);

    if($findMySelect != null){
        $appService->deleteLogPriseRdvDAOBis($enterpriseConcerned->getIdEnterprise(), $purchasingFairConcerned->getIdPurchasingFair());
    }
}

if(isset($_SESSION['enterpriseConcerned']) && !empty($_SESSION['enterpriseConcerned'])){
	//print_r($_SESSION['enterpriseConcerned']->getIdEnterprise());
	print_r($appService->deleteLog($_SESSION['enterpriseConcerned']->getIdEnterprise()));
}

// Destroys all session variables
$_SESSION = array();

// To completely destroy the session, also delete the session cookie. 
// Note: this will destroy the session and not only the session data!
if ( ini_get("session.use_cookies") ) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}

// Finally, the session is destroyed.
session_destroy();

// Home page redirection
 
header('Location: ./index_home.php');

?>