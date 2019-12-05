<?php
require_once dirname ( __FILE__ ) . '/../services/AppServiceImpl.class.php';
require_once dirname ( __FILE__ ) . '/../view/errors.inc.php';

if(!isset($_SESSION)) session_start(); // Start session

header( 'content-type: text/html; charset=utf-8' ); // Specifies to the server to return UTF-8 - put in prod

/* To see all details when var_dump() function used */
ini_set('xdebug.var_display_max_depth', -1);
ini_set('xdebug.var_display_max_children', -1);
ini_set('xdebug.var_display_max_data', -1);

$appServiceSCA = AppServiceImpl::getInstance();

// Set CORS
$appServiceSCA->cors();

// To prevent clear data
if(!isset($_POST['password'])) { die('Connexion refusée'); }
if( isset($_POST['password']) && !password_verify($_POST['password'], '$2y$10$aXrN7qSG5mYraviTya7NR.rKyEpNeI4hpDI6epQ3hgbP5O.lkQoKu')) {
    die('Connexion refusée');
}

/* Get hex passwords for all enterprises */
$arrayEnterprisesSCA = $appServiceSCA->findAllEnterprises();
$limitArrayEnterprisesSCA = count($arrayEnterprisesSCA);
$counterArrayEnterprisesSCA = 0;

// Added 27.08.2018 - Add Providers Present in JSON data
$arrayProvidersPresent = $appServiceSCA->findAllProviderPresent();
$limitArrayProvidersPresent = count($arrayProvidersPresent);
$counterArrayProvidersPresent = 0;

echo '{'; // start

echo '"passwords": [';

foreach($arrayEnterprisesSCA as $enterprise) {
    ++$counterArrayEnterprisesSCA;
    echo json_encode(array("idEnterprise" => $enterprise->getIdEnterprise(), "passwordEnterprise" => $enterprise->getPasswordHex() ),JSON_PRETTY_PRINT);
    echo ($counterArrayEnterprisesSCA < $limitArrayEnterprisesSCA) ? ',' : '';
}
echo ' ],';

echo '"providersPresent": [';

foreach($arrayProvidersPresent as $pp) {
    ++$counterArrayProvidersPresent;
    echo json_encode($pp, JSON_PRETTY_PRINT);
    echo ($counterArrayProvidersPresent < $limitArrayProvidersPresent) ? ',' : '';
}
echo ' ]';
echo '}'; // END
?>