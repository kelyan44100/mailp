<?php
/* Errors & Service */
require_once dirname ( __FILE__ ) . '/../view/errors.inc.php';
require_once dirname ( __FILE__ ) . '/../services/AppServiceImpl.class.php';

$appService = AppServiceImpl::getInstance();

$idSalesperson = (int) $_POST['idSalesperson'];

$salespersonConcerned = $appService->findOneParticipant($idSalesperson);

// Response
echo $appService->deactivateParticipant($salespersonConcerned);
?>