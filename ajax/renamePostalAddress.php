<?php
/* Errors & Service */
require_once dirname ( __FILE__ ) . '/../view/errors.inc.php';
require_once dirname ( __FILE__ ) . '/../services/AppServiceImpl.class.php';

$appService = AppServiceImpl::getInstance();

$newPostalAddressModified = trim(($_POST['newPostalAddress']));
$idProvider = (int) $_POST['idProvider'];

$providerConcerned = $appService->findOneEnterprise($idProvider);
$providerConcerned->setPostalAddress($newPostalAddressModified);

// Response
echo $appService->saveEnterprise($providerConcerned);
?>