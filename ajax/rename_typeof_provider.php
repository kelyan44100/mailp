<?php
/* Errors & Service */
require_once dirname ( __FILE__ ) . '/../view/errors.inc.php';
require_once dirname ( __FILE__ ) . '/../services/AppServiceImpl.class.php';

$appService = AppServiceImpl::getInstance();

// Chaussure C = 1 & Textile T = 2
$newTypeModified = ( trim( ( $_POST['newType'] ) ) == 'C' ) ? 1 : 2;
$idEnterprise= (int) $_POST['$idEnterprise'];

$enterpriseConcerned = $appService->findOneEnterprise($idEnterprise);
$enterpriseConcerned->setOneTypeOfProvider($appService->findOneTypeOfProvider($newTypeModified));

// Response
echo ($appService->saveEnterprise($enterpriseConcerned) == 1 ) ? $appService->findOneEnterprise($idEnterprise)->getOneTypeOfProvider()->getNameTypeOfProvider() : -1;
?>