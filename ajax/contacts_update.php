<?php
require_once dirname ( __FILE__ ) . '/../services/AppServiceImpl.class.php';

$appService = AppServiceImpl::getInstance();

// Get data from POST
$idContact     = (int) $_POST['idContact'];
$newValue      = (string) $_POST['newValue'];
$whatIsChanged = (string) $_POST['whatIsChanged'];

// Get EnterpriseContact object
$contactToUpdate = $appService->findOneEnterpriseContact($idContact);

// To know attribute to change
switch($whatIsChanged) {
    case 'civility': $contactToUpdate->setCivility($newValue); break;
    case 'surname' : $contactToUpdate->setSurname($newValue); break;
    case 'name'    : $contactToUpdate->setName($newValue); break;
    case 'email'   : 
        // http://php.net/manual/en/filter.examples.validation.php
        // http://php.net/manual/fr/function.filter-var.php
        // http://php.net/manual/fr/filter.filters.validate.php
        if( filter_var( $newValue, FILTER_VALIDATE_EMAIL ) ) {
            $contactToUpdate->setEmail($newValue); break;
        } else { die('0'); } 
    default : die('ERROR');
}

// Echo result
echo $saving = $appService->saveEnterpriseContact($contactToUpdate);


