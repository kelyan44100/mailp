<?php
require_once dirname ( __FILE__ ) . '/../services/AppServiceImpl.class.php';
require_once dirname ( __FILE__ ) . '/../services/AppServiceOVHImpl.class.php';
require_once dirname ( __FILE__ ) . '/../view/errors.inc.php';

header( 'content-type: text/html; charset=utf-8' ); // Specifies to the server to return UTF-8 - put in prod

/* To see all details when var_dump() function used */
ini_set('xdebug.var_display_max_depth', -1);
ini_set('xdebug.var_display_max_children', -1);
ini_set('xdebug.var_display_max_data', -1);

// http://php.net/manual/en/function.set-time-limit.php - Limits the maximum execution time
// The maximum execution time is in seconds. If set to zero, no time limit is imposed.
set_time_limit(0); // PROD SCA OUEST = 600 s / 60 = 10 min, uninteresting value configured

$appServiceSCA = AppServiceImpl::getInstance();

// Dump MySQL db - Added 08.09.2018
$appServiceSCA->dumpDatabase();

$jsonContent = file_get_contents(dirname(__FILE__).'/../tmp/tmp_json_ovh_bis.json');

$arrayJsonContent = json_decode($jsonContent, true); // true => convert object StdClass to array));

//die(var_dump($arrayJsonContent['participant'][0][1][0]['unavailability_sp']));

/* Update process pf_management_tmp ---> pf_management : 6 tables */

/* Table special_guest => DELETE (S) INSERT (P) */
/* The provider cannot change his ID so it is OK */
$appServiceSCA->deleteAllSpecialGuest();
$arraySpecialGuestsToInsert = array();
foreach($arrayJsonContent['special_guest'] as $key => $specialGuest) {
    
    $enterpriseSpecialGuest = $appServiceSCA->findOneEnterprise($specialGuest['oneEnterprise']);
    $purchasingFairSpecialGuest = $appServiceSCA->findOnePurchasingFair($specialGuest['onePurchasingFair']);

    $arraySpecialGuestsToInsert[] = $appServiceSCA->createSpecialGuest(
            $enterpriseSpecialGuest,
            $purchasingFairSpecialGuest,
            $specialGuest['civility'],
            $specialGuest['surname'],
            $specialGuest['name'],
            $specialGuest['days']
    );
}

foreach($arraySpecialGuestsToInsert as $key => $specialGuest) {
    $specialGuest->setIdSpecialGuest(-1); // -1 To insert , != -1 to update
    echo '- Insertion special_guest id '.$appServiceSCA->saveSpecialGuest($specialGuest).'<br/>';
}

/* End table special_guest */

/* Table lunch => DELETE (SCA) INSERT (OVH) */
/* The provider cannot change his ID so it is OK */
$appServiceSCA->deleteAllLunchesForProviders();
$arrayLunchesToInsert = array();
foreach($arrayJsonContent['lunches'] as $key => $lunch) {
    
    $enterpriseLunch = $appServiceSCA->findOneEnterprise($lunch['oneEnterprise']);
    $purchasingFairLunch = $appServiceSCA->findOnePurchasingFair($lunch['onePurchasingFair']);

    $arrayLunchesToInsert[] = $appServiceSCA->createLunch(
            $enterpriseLunch,
            $purchasingFairLunch,
            $lunch['lunchesPlanned'],
            $lunch['lunchesCanceled'],
            json_encode($lunch['lunchesDetails']),
            0
            );
}

foreach($arrayLunchesToInsert as $key => $lunch) {
    if($lunch->getOneEnterprise()->getOneProfile()->getIdProfile() == 1) {
        echo '- (F) Insertion lunch id '.$appServiceSCA->saveLunch($lunch).'<br/>';
    }
    else {
        echo '- (M) Non Insertion lunch<br/>';
    }
}
/* End table lunch */

/* Table present => DELETE (SCA) INSERT (OVH) */
/* The provider cannot change his ID so it is OK */
$appServiceSCA->deleteAllPresentsForProviders();
$arrayPresentsToInsert = array();
foreach($arrayJsonContent['presents'] as $key => $present) {
    
    $enterprisePresent = $appServiceSCA->findOneEnterprise($present['oneEnterprise']);
    $participantPresent = $appServiceSCA->findOneParticipant($present['oneParticipant']);
    $purchasingFairPresent = $appServiceSCA->findOnePurchasingFair($present['onePurchasingFair']);

    $arrayPresentsToInsert[] = $appServiceSCA->createPresent(
            $enterprisePresent,
            $participantPresent,
            $purchasingFairPresent,
            json_encode($present['presentDetails'])  
            );
}

foreach($arrayPresentsToInsert as $key => $present) {
    if($present->getOneEnterprise()->getOneProfile()->getIdProfile() == 1) {
        echo '- (F) Insertion present id '.$appServiceSCA->savePresent($present).'<br/>';
    }
    else {
        echo '- (M) Non Insertion present<br/>';
    }
}
/* End table present */

echo '1';
?>