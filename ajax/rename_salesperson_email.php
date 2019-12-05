<?php
/* Errors & Service */
require_once dirname ( __FILE__ ) . '/../view/errors.inc.php';
require_once dirname ( __FILE__ ) . '/../services/AppServiceImpl.class.php';

$appService = AppServiceImpl::getInstance();

$newEmailModified = trim(($_POST['newName']));
$idSalesperson = (int) $_POST['idSalesperson'];

$salespersonConcerned = $appService->findOneParticipant($idSalesperson);

// http://php.net/manual/en/filter.examples.validation.php
// http://php.net/manual/fr/function.filter-var.php
// http://php.net/manual/fr/filter.filters.validate.php
if( filter_var( $newEmailModified, FILTER_VALIDATE_EMAIL ) ) {
    $salespersonConcerned->setEmail($newEmailModified);
}
else { die('0'); } 

// Response
echo $appService->saveparticipant($salespersonConcerned);
?>