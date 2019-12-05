<?php
/* Errors & Service */
require_once dirname ( __FILE__ ) . '/../view/errors.inc.php';
require_once dirname ( __FILE__ ) . '/../services/AppServiceImpl.class.php';

$appService = AppServiceImpl::getInstance();

$newNameModified = trim(($_POST['newName']));
$idSalesperson = (int) $_POST['idSalesperson'];

$salespersonConcerned = $appService->findOneParticipant($idSalesperson);
$salespersonConcerned->setName($newNameModified);

// Response
echo $appService->saveparticipant($salespersonConcerned);
?>