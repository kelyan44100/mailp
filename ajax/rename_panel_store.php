<?php
/* Errors & Service */
require_once dirname ( __FILE__ ) . '/../view/errors.inc.php';
require_once dirname ( __FILE__ ) . '/../services/AppServiceImpl.class.php';

$appService = AppServiceImpl::getInstance();

$newPanelModified = trim(($_POST['newPanel']));
$idStore = (int) $_POST['idStore'];

$storeConcerned = $appService->findOneEnterprise($idStore);
$storeConcerned->setPanel($newPanelModified);

// Response
echo $appService->saveEnterprise($storeConcerned);
?>