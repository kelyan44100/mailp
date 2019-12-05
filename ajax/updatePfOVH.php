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

$arrayPfSCA = json_decode( $_POST['pfSCA'], true);

foreach($arrayPfSCA['purchasingFairs'] as $key => $value) {
    $pf = $appServiceSCA->findOnePurchasingFair($value['idPf']);
    $pf->setNamePurchasingFair($value['namePf']);
    $pf->setHexColor($value['hexColorPf']);
    $pf->setStartDatetime( DateTime::createFromFormat('d/m/Y H:i:s', $value['startDatetimePf'])->format('Y-m-d H:i:s') );
    $pf->setEndDateTime( DateTime::createFromFormat('d/m/Y H:i:s', $value['endDatetimePf'])->format('Y-m-d H:i:s') );
    $pf->setLunchBreak($value['lunchBreakPf']);
    $pf->setOneTypeOfPf($appServiceSCA->findOneTypeOfPf($value['typePf']));
    $pf->setRegistrationClosingDate( DateTime::createFromFormat('d/m/Y H:i:s', $value['registrationClosingDatePf'])->format('Y-m-d H:i:s') );
    $pf->setDateDeletion($value['dateDeletionPf']);
    $appServiceSCA->savePurchasingFair($pf);
}
echo 'Success';
?>