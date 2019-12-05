<?php
require_once dirname ( __FILE__ ) . '/../services/AppServiceImpl.class.php';
require_once dirname ( __FILE__ ) . '/../services/AppServiceOVHImpl.class.php';
require_once dirname ( __FILE__ ) . '/../view/errors.inc.php';

header( 'content-type: text/html; charset=utf-8' ); // Specifies to the server to return UTF-8 - put in prod

/* To see all details when var_dump() function used */
ini_set('xdebug.var_display_max_depth', -1);
ini_set('xdebug.var_display_max_children', -1);
ini_set('xdebug.var_display_max_data', -1);

$appServiceSCA = AppServiceImpl::getInstance();
$appServiceOVH = AppServiceOVHImpl::getInstance();

/* Update process pf_management_tmp ---> pf_management : 6 tables */

/* Table special_guest => DELETE (S) INSERT (P) */
/* The provider cannot change his ID so it is OK */
$appServiceSCA->deleteAllSpecialGuest();
$arraySpecialGuestsToInsert = $appServiceOVH->findAllSpecialGuest();
foreach($arraySpecialGuestsToInsert as $key => $specialGuest) {
    $specialGuest->setIdSpecialGuest(-1); // -1 To insert , != -1 to update
    echo '- Insertion special_guest id '.$appServiceSCA->saveSpecialGuest($specialGuest).'<br/>';
}

/* Participants */
/* Risque de conflits car participants Magasins et participants Fournisseur mélangés */
$arrayParticipantsSCA = $appServiceSCA->findAllParticipants();
$arrayParticipantsOVH = $appServiceOVH->findAllParticipants();
$limitArrayParticipantsSCA = count($arrayParticipantsSCA);
$limitArrayParticipantsOVH = count($arrayParticipantsOVH);
$arrayIdsParticipantsOVH = array();
foreach($arrayParticipantsOVH as $participantOVH) {
    $arrayIdsParticipantsOVH[] = $participantOVH->getIdParticipant();
}

function updateParticipantSCA(Participant $participantSCA, Participant $participantOVH) {
    
    /* Globals */
    global $appServiceSCA;
    global $appServiceOVH;
    
    $idParticipantSCA = $participantSCA->getIdParticipant();
    $idParticipantOVH = $participantOVH->getIdParticipant();
    
    /* Participant */
    $appServiceSCA->saveParticipant($participantOVH);

    /* UnavailabilitySP */
    $arrayUnavailabilitySCA = $appServiceSCA->findAllUnavailabilitiesSpByParticipant($idParticipantSCA);
    $arrayUnavailabilityOVH = $appServiceOVH->findAllUnavailabilitiesSpByParticipant($idParticipantOVH);

    foreach($arrayUnavailabilityOVH as $key => $uspOVH) {
        
        $isPresent = 0;
        
        foreach($arrayUnavailabilitySCA as $key => $uspSCA) {
            if($uspOVH->getIdUnavailability() == $uspOVH->getIdUnavailability() ) {
            $isPresent = 1;
            break 1;
            }
        }
        if($isPresent) {
            $uspSCA->setStartDatetime($uspOVH->getStartDatetime());
            $uspSCA->setEndDatetime($uspOVH->getEndDatetime());
            $appServiceSCA->saveUnavailabilitySp($uspSCA);
        }
        else {
            $appServiceSCA->saveUnavailabilitySp($uspOVH);
        }
    }
    
    /* AssignmentSpStore */
    $arrayAssignmentSpStoreSCA = $appServiceSCA->findAllAssignmentSpStoreByParticipant($idParticipantSCA);
    $arrayAssignmentSpStoreOVH = $appServiceOVH->findAllAssignmentSpStoreByParticipant($idParticipantOVH);

    foreach($arrayAssignmentSpStoreSCA as $key => $assSCA) {
        $appServiceSCA->deleteAssignmentSpStoreBis($assSCA);
    }
    foreach($arrayAssignmentSpStoreOVH as $key => $assOVH) {
        $appServiceSCA->saveAssignmentSpStore($assOVH);
    }
    
    /* AssignmentParticipantDepartment */
    $arrayAssignmentParticipantDepartmentSCA = $appServiceSCA->findAssignmentsParticipantDepartmentByParticipant($idParticipantSCA);
    $arrayAssignmentParticipantDepartmentOVH = $appServiceOVH->findAssignmentsParticipantDepartmentByParticipant($idParticipantOVH);
    
    foreach($arrayAssignmentParticipantDepartmentSCA as $key => $apdSCA) {
        $appServiceSCA->deleteAssignmentParticipantDepartment($apdSCA);
    }
    foreach($arrayAssignmentParticipantDepartmentOVH as $key => $apdOVH) {
        $appServiceSCA->saveAssignmentParticipantDepartment($apdOVH);
    }
    
    /* AssignmentParticipantEnterprise */
    $arrayAssignmentParticipantEnterprisSCA = $appServiceSCA->findAllAssignmentsParticipantEnterpriseForOneParticipant($idParticipantSCA);
    $arrayAssignmentParticipantEnterprisOVH = $appServiceOVH->findAllAssignmentsParticipantEnterpriseForOneParticipant($idParticipantOVH);
    
    foreach($arrayAssignmentParticipantEnterprisSCA as $key => $apeSCA) {
        $appServiceSCA->deleteAssignmentParticipantEnterprise($apeSCA);
    }
    foreach($arrayAssignmentParticipantEnterprisOVH as $key => $apeOVH) {
        $appServiceSCA->saveAssignmentParticipantEnterprise($apeOVH);
    }
}

function insertParticipantOVH(Participant $participantOVH) {

    /* Globals */
    global $appServiceSCA;
    global $appServiceOVH;
    
    $idParticipantOVH = $participantOVH->getIdParticipant();
    
    /* Add Participant */
    $participantOVH->setIdParticipant(-1);
    $idNewParticipantSCA = $appServiceSCA->saveParticipant($participantOVH);
    
    echo '- Insertion participant id '.$idNewParticipantSCA.' '.$participantOVH->getCivility().' '.$participantOVH->getSurname().' '.$participantOVH->getName().' '.$participantOVH->getEmail().'<br/>';
    
    
    /* UnavailabilitySp */
    $arrayUnavailabilityOVH = $appServiceOVH->findAllUnavailabilitiesSpByParticipant($idParticipantOVH);
    foreach($arrayUnavailabilityOVH as $key => $usp) {
        $usp->setIdUnavailability(-1);
        $usp->getOneParticipant()->setIdParticipant($idNewParticipantSCA);
         echo '- Insertion unavailability_sp id '.$appServiceSCA->saveUnavailabilitySp($usp).'<br/>';
    }
    
    /* AssignmentSpStore */
    $arrayAssignmentSpStoreOVH = $appServiceOVH->findAllAssignmentSpStoreByParticipant($idParticipantOVH);
    foreach($arrayAssignmentSpStoreOVH as $key => $ass) {
        $ass->getOneParticipant()->setIdParticipant($idNewParticipantSCA);
        echo '- Insertion assignment_sp_store '.$appServiceSCA->saveAssignmentSpStore($ass).'<br/>';
    }
    
    /* AssignmentParticipantDepartment */
    $arrayAssignmentParticipantDepartmentOVH = $appServiceOVH->findAssignmentsParticipantDepartmentByParticipant($idParticipantOVH);
    //var_dump($arrayAssignmentParticipantDepartmentOVH);
    foreach($arrayAssignmentParticipantDepartmentOVH as $key => $apd) {
        $apd->getOneParticipant()->setIdParticipant($idNewParticipantSCA);
         echo '- Insertion assignment_participant_department '.$appServiceSCA->saveAssignmentParticipantDepartment($apd).'<br/>';
    }
    
    /* AssignmentParticipantEnterprise */
    $arrayAssignmentParticipantEnterprise = $appServiceOVH->findAllAssignmentsParticipantEnterpriseForOneParticipant($idParticipantOVH);
    foreach($arrayAssignmentParticipantEnterprise as $key => $ape) {
        $ape->getOneParticipant()->setIdParticipant($idNewParticipantSCA);
         echo '- Insertion assignment_participant_enterprise '.$appServiceSCA->saveAssignmentParticipantEnterprise($ape).'<br/>';
    }
    
}

foreach($arrayParticipantsOVH as $key => $participantOVH) {
    $isPresent = 0;
    for( $i = 0 ; $i < $limitArrayParticipantsSCA; $i++ ) { // plutot arrayidparticipants ovh in_array
        if( 
            $participantOVH->getIdParticipant() == $arrayParticipantsSCA[$i]->getIdParticipant() 
            && mb_strtolower( $participantOVH->getSurname(), 'UTF-8' ) == mb_strtolower( $arrayParticipantsSCA[$i]->getSurname(), 'UTF-8' )
            && mb_strtolower( $participantOVH->getName(), 'UTF-8' ) == mb_strtolower( $arrayParticipantsSCA[$i]->getName(), 'UTF-8' )
            && mb_strtolower( $participantOVH->getEmail(), 'UTF-8' ) == mb_strtolower( $arrayParticipantsSCA[$i]->getEmail(), 'UTF-8' )
        ) {
            updateParticipantSCA($arrayParticipantsSCA[$i], $participantOVH); 
            $isPresent = 1; 
//             echo 'update';
//            echo '- Comparaison OK : ';
//            echo $participantOVH->getIdParticipant(). ' = '.$arrayParticipantsSCA[$i]->getIdParticipant().', ';
//            echo mb_strtolower( $participantOVH->getSurname(), 'UTF-8' ). ' = '.mb_strtolower( $arrayParticipantsSCA[$i]->getSurname(), 'UTF-8' ).', ';
//            echo mb_strtolower( $participantOVH->getName(), 'UTF-8' ). ' = '.mb_strtolower( $arrayParticipantsSCA[$i]->getName(), 'UTF-8' ).', ';
//            echo mb_strtolower( $participantOVH->getEmail(), 'UTF-8' ). ' = '.mb_strtolower( $arrayParticipantsSCA[$i]->getEmail(), 'UTF-8' ).'.<br/>';
            
            break 1;
        }
    }
    if(!$isPresent) {  
        
        insertParticipantOVH($participantOVH); 
        
//        echo '- Comparaison NOK : ';
//        echo $participantOVH->getIdParticipant(). ' != '.$arrayParticipantsSCA[$i]->getIdParticipant().', ';
//        echo mb_strtolower( $participantOVH->getSurname(), 'UTF-8' ). ' != '.mb_strtolower( $arrayParticipantsSCA[$i]->getSurname(), 'UTF-8' ).', ';
//        echo mb_strtolower( $participantOVH->getName(), 'UTF-8' ). ' != '.mb_strtolower( $arrayParticipantsSCA[$i]->getName(), 'UTF-8' ).', ';
//        echo mb_strtolower( $participantOVH->getEmail(), 'UTF-8' ). ' != '.mb_strtolower( $arrayParticipantsSCA[$i]->getEmail(), 'UTF-8' ).'.<br/>';
    }
}

echo '1';
?>