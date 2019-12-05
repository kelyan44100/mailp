<?php
/* Errors & Service */
require_once dirname ( __FILE__ ) . '/../view/errors.inc.php';
require_once dirname ( __FILE__ ) . '/../services/AppServiceImpl.class.php';

$appService = AppServiceImpl::getInstance();

$newNameModified = trim(($_POST['newName']));
$idStore = (int) $_POST['idStore'];

$storeConcerned = $appService->findOneEnterprise($idStore);
$storeConcerned->setName($newNameModified);

// Response
echo $appService->saveEnterprise($storeConcerned);
?>