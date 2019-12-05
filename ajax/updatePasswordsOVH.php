<?php
require_once dirname ( __FILE__ ) . '/../services/AppServiceImpl.class.php';
require_once dirname ( __FILE__ ) . '/../view/errors.inc.php';

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

$arrayPasswordsSCA = json_decode( $_POST['passwordsSCA'], true);

foreach($arrayPasswordsSCA['passwords'] as $key => $value) {
	$ent = $appServiceSCA->findOneEnterprise($value['idEnterprise']);
	$ent->setPassword($value['passwordEnterprise']);
	$appServiceSCA->saveEnterprisePasswordOVH($ent);
}

// Added 27.08.2018
// Update Providers present
// Delete All ProvidersPresent in the database
$allPP = $appServiceSCA->findAllProviderPresent();
foreach($allPP as $key => $pp) { $appServiceSCA->deleteProviderPresent($pp); }

// Insert all Providerspresent in JSON data
foreach($arrayPasswordsSCA['providersPresent'] as $key => $value) {
	$ent   = $appServiceSCA->findOneEnterprise($value['oneProvider']);
        $pf    = $appServiceSCA->findOnePurchasingFair($value['onePurchasingFair']);
        $newPP = $appServiceSCA->createProviderPresent($ent, $pf);
        $appServiceSCA->saveProviderPresent($newPP);
}
echo 'Success';
?>