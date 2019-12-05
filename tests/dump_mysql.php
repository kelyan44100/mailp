<?php
require_once dirname ( __FILE__ ) . '/../services/AppServiceImpl.class.php';
$appService = AppServiceImpl::getInstance();
$appService->dumpDatabase();

// echo $appService->getServerName();
?>