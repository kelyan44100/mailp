<?php
/* Errors & Service */
require_once dirname ( __FILE__ ) . '/../view/errors.inc.php';
require_once dirname ( __FILE__ ) . '/../services/AppServiceImpl.class.php';

$appService = AppServiceImpl::getInstance();

$newNameModified = trim(($_POST['newName']));
$idProvider = (int) $_POST['idProvider'];

$providerConcerned = $appService->findOneEnterprise($idProvider);
$providerConcerned->setName($newNameModified);

// Response
echo $appService->saveEnterprise($providerConcerned);
?>