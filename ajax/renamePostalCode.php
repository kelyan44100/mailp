<?php
/* Errors & Service */
require_once dirname ( __FILE__ ) . '/../view/errors.inc.php';
require_once dirname ( __FILE__ ) . '/../services/AppServiceImpl.class.php';

$appService = AppServiceImpl::getInstance();

$newPostalCodeModified = trim(($_POST['newPostalCode']));
$idProvider = (int) $_POST['idProvider'];

$providerConcerned = $appService->findOneEnterprise($idProvider);
$providerConcerned->setPostalCode($newPostalCodeModified);

// Response
echo $appService->saveEnterprise($providerConcerned);
?>