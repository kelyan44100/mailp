<?php
/* Errors & Service */
require_once dirname ( __FILE__ ) . '/../view/errors.inc.php';
require_once dirname ( __FILE__ ) . '/../services/AppServiceImpl.class.php';

$appService = AppServiceImpl::getInstance();

$idStore = (int) $_POST['idStore'];

$storeConcerned = $appService->findOneEnterprise($idStore);

// Response
echo $appService->deactivateEnterprise($storeConcerned);
?>