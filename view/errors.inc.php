<?php

date_default_timezone_set('Europe/Paris');

// Script a placer en haut de la page

// Show errors on the screen ?
ini_set('display_errors', 0);

// Save errors in a log file
ini_set('log_errors', 1);

// nom du fichier qui enregistre les logs (pay attention to writing rights)
ini_set('error_log', dirname(__FILE__) . '/../errors/log_errors_php.txt');

// View errors and warnings -- BUG
// error_reporting(e_all);
?>