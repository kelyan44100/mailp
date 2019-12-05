<?php
/* Errors & Service */
require_once dirname ( __FILE__ ) . '/../view/errors.inc.php';
require_once dirname ( __FILE__ ) . '/../services/AppServiceImpl.class.php';

$appService = AppServiceImpl::getInstance();

$idProvider = (int) $_POST['idProvider'];

$providerConcerned = $appService->findOneEnterprise($idProvider);

// Response
echo $appService->deactivateEnterprise($providerConcerned);
?>