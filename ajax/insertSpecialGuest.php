<?php
require_once dirname ( __FILE__ ) . '/../services/AppServiceImpl.class.php';
$appService = AppServiceImpl::getInstance();

// POST data
$idEnterprise      = $_POST['enterprise'];
$idPurchasingFair  = $_POST['purchasingFair'];
$civility          = $_POST['civility'];
$surname           = trim($_POST['surname']);
$name              = trim($_POST['name']);
$days              = $_POST['days'];

// Create
$oneEnterprise     = $appService->findOneEnterprise($idEnterprise);
$onePurchasingFair = $appService->findOnePurchasingFair($idPurchasingFair);
$newSpecialGuest   = $appService->createSpecialGuest($oneEnterprise, $onePurchasingFair, $civility, $surname, $name, $days);

// Insert
$checkInsert = $appService->saveSpecialGuest($newSpecialGuest);

$deleteAction = '&nbsp;<i id="iconDeleteGuest" class="fa fa-times-circle" aria-hidden="true" title="Supprimer" '
        . 'onclick="deleteSpecialGuest('.$checkInsert.');"></i>';

// Print result
echo ($checkInsert) ? $newSpecialGuest.$deleteAction : '-1';
?>