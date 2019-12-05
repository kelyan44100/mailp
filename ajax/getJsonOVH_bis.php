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

/* Get 2 tables OVH to format JSON */

echo '{'; // start

/* Table special_guest */
echo '"special_guest": [';
$arraySpecialGuestsToInsert = $appServiceSCA->findAllSpecialGuest();
$limitArraySpecialGuestsToInsert = count($arraySpecialGuestsToInsert);
$counterArraySpecialGuestsToInsert = 0;
foreach($arraySpecialGuestsToInsert as $key => $specialGuest) {
    ++$counterArraySpecialGuestsToInsert;
    echo ''.$jsonEncode = json_encode($specialGuest, JSON_PRETTY_PRINT);
    echo ($counterArraySpecialGuestsToInsert < $limitArraySpecialGuestsToInsert) ? ',' : '';
}

/* Table lunch */
echo '],"lunches": [';
$arrayLunchesToInsert = $appServiceSCA->findAllLunchesForProviders(); // Only Providers lunches
$limitArrayLunchesToInsert = count($arrayLunchesToInsert);
$counterArrayLunchesToInsert = 0;
foreach($arrayLunchesToInsert as $key => $lunch) {
    ++$counterArrayLunchesToInsert;
    echo ''.$jsonEncode = json_encode($lunch, JSON_PRETTY_PRINT);
    echo ($counterArrayLunchesToInsert < $limitArrayLunchesToInsert) ? ',' : '';
}

/* Table present */
echo ' ],"presents": [';
$arrayPresentsToInsert = $appServiceSCA->findAllPresentsForProviders(); // Only Providers lunches
$limitArrayPresentsToInsert = count($arrayPresentsToInsert);
$counterArrayPresentsToInsert = 0;
foreach($arrayPresentsToInsert as $key => $present) {
    ++$counterArrayPresentsToInsert;
    echo ''.$jsonEncode = json_encode($present, JSON_PRETTY_PRINT);
    echo ($counterArrayPresentsToInsert < $limitArrayPresentsToInsert) ? ',' : '';
}

//echo ($limitArraySpecialGuestsToInsert == 0) ? '{ "empty": "empty" }' : '';
echo ']';
echo '}'; // END
?>