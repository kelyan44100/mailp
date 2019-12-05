<?php
/* Errors & Service */
require_once dirname ( __FILE__ ) . '/../view/errors.inc.php';
require_once dirname ( __FILE__ ) . '/../services/AppServiceImpl.class.php';

$appService = AppServiceImpl::getInstance();

// Reset passwords for all Providers
$arrayProviders         = $appService->findAllEnterprisesAsProviders();
$limitArrayProviders    = count($arrayProviders);
$counterCheckUpdate     = 0;
$arrayPasswordProviders = $appService->generateNPasswords($limitArrayProviders);
for( $n = 0 ; $n < $limitArrayProviders ; $n++) {
   $arrayProviders[$n]->setPassword($arrayPasswordProviders[$n]);
    $counterCheckUpdate += $appService->saveEnterprise($arrayProviders[$n]);
}

// Response
echo ( $counterCheckUpdate == $limitArrayProviders) ? $counterCheckUpdate : -1;
?>