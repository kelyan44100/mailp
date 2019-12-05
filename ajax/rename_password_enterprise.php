<?php
/* Errors & Service */
require_once dirname ( __FILE__ ) . '/../view/errors.inc.php';
require_once dirname ( __FILE__ ) . '/../services/AppServiceImpl.class.php';

$appService = AppServiceImpl::getInstance();

$newPasswordModified = trim(($_POST['newPassword']));
$idEnterprise= (int) $_POST['$idEnterprise'];

$enterpriseConcerned = $appService->findOneEnterprise($idEnterprise);
$enterpriseConcerned->setPassword($newPasswordModified);

// Response
echo ($appService->saveEnterprise($enterpriseConcerned) == 1 ) ? $appService->findOneEnterprise($idEnterprise)->getPasswordHex() : -1;
?>