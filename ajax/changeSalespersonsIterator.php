<?php
require_once dirname ( __FILE__ ) . '/../services/AppServiceImpl.class.php';

if(!isset($_SESSION)) session_start(); // Start session

$appService = AppServiceImpl::getInstance();

if( isset($_POST['action']) && !empty($_POST['action']) ) {
    
    $action = (string) $_POST['action'];
    $iteratorValue = $_SESSION['salespersonsConcernedIterator'];
    $_SESSION['salespersonsConcernedIterator'] = ( $action == 'minus') ? ($iteratorValue-1): ($iteratorValue+1);
    
    echo $iteratorValue;
}
?>