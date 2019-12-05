<?php
require_once dirname ( __FILE__ ) . '/../services/AppServiceImpl.class.php';
$appService = AppServiceImpl::getInstance();

// POST data
$idSpecialGuest = $_POST['idSpecialGuest'];

// Delete
$checkDelete = $appService->deleteSpecialGuest($idSpecialGuest);

// Print result
echo ($checkDelete) ? '1' : '-1';
?>