<?php
// Home page redirection
/*if($_SERVER['SERVER_NAME'] == '205.0.211.85' || $_SERVER['SERVER_NAME'] == 'localhost' || $_SERVER['SERVER_NAME'] == '::1') {  */ 
    //header('Location: ./index_store.php');
	//require_once('./index_store.php');
	require_once('./index_home.php');
	die(); // stop le script
//}
/*else {
    //header('Location: ./index_provider.php');
	require_once('./index_provider.php');
	die();
}*/
?>