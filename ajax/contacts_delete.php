<?php
require_once dirname ( __FILE__ ) . '/../services/AppServiceImpl.class.php';

$appService = AppServiceImpl::getInstance();

// Get data from POST
$idContact = (int) $_POST['idContact'];


// Remove contact & print result
echo $saving = $appService->deleteEnterpriseContact($appService->findOneEnterpriseContact($idContact));


