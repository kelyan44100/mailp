<?php
/* Errors & Service */
require_once dirname ( __FILE__ ) . '/../view/errors.inc.php';
require_once dirname ( __FILE__ ) . '/../services/AppServiceImpl.class.php';

$appService = AppServiceImpl::getInstance();

$newCivilityModified = trim(($_POST['newName']));
$idSalesperson = (int) $_POST['idSalesperson'];

$salespersonConcerned = $appService->findOneParticipant($idSalesperson);
$salespersonConcerned->setCivility($newCivilityModified);

// Response
if($newCivilityModified != 'Monsieur' && $newCivilityModified != 'Madame') 
    echo 0;
else
    echo $appService->saveparticipant($salespersonConcerned);
?>