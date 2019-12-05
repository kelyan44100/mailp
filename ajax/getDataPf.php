<?php
/* Errors & Service */
require_once dirname ( __FILE__ ) . '/../view/errors.inc.php';
require_once dirname ( __FILE__ ) . '/../services/AppServiceImpl.class.php';

$appService = AppServiceImpl::getInstance();

$pfConcerned = $appService->findOnePurchasingFair((int) trim(($_POST['idPurchasingFair'])));

// Response
echo json_encode($pfConcerned, JSON_PRETTY_PRINT);
?>