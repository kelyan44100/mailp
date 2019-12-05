<?php
require_once dirname ( __FILE__ ) . './../services/AppServiceImpl.class.php';
if(!isset($_SESSION)) session_start(); // Start session
$file = './../tmp/tmp_planning_pf'.$_SESSION['purchasingFairConcerned']->getIdPurchasingFair().'.html';
if( file_exists($file) ) { unlink($file); echo 'Success'; } else { echo 'Failed'; }
?>

