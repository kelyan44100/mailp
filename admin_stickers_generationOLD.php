<?php
require_once dirname ( __FILE__ ) . '/services/AppServiceImpl.class.php';
if(!isset($_SESSION)) session_start(); // Start session
$appService = AppServiceImpl::getInstance();
$arrayParticipations = $appService->findAllParticipationsByEnterpriseAndPurchasingFair($_SESSION['enterpriseConcerned'], $_SESSION['purchasingFairConcerned']);
$arrayParticipants = array();
foreach($arrayParticipations as $value) {
    $arrayParticipants[] = $value->getOneParticipant();
}
$appService->generateStickers($_SESSION['purchasingFairConcerned'], $arrayParticipants);
?>