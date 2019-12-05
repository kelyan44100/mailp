<?php
require_once dirname ( __FILE__ ) . '/../services/AppServiceImpl.class.php';
require_once dirname ( __FILE__ ) . '/../services/AppServiceOVHImpl.class.php';
require_once dirname ( __FILE__ ) . '/../view/errors.inc.php';

header( 'content-type: text/html; charset=utf-8' ); // Specifies to the server to return UTF-8 - put in prod

/* To see all details when var_dump() function used */
ini_set('xdebug.var_display_max_depth', -1);
ini_set('xdebug.var_display_max_children', -1);
ini_set('xdebug.var_display_max_data', -1);

// http://php.net/manual/en/function.set-time-limit.php - Limits the maximum execution time
// The maximum execution time is in seconds. If set to zero, no time limit is imposed.
set_time_limit(0); // PROD SCA OUEST = 600 s / 60 = 10 min, uninteresting value configured

$appServiceSCA = AppServiceImpl::getInstance();

// Dump MySQL db - Added 08.09.2018
$appServiceSCA->dumpDatabase();

$jsonContent = file_get_contents(dirname(__FILE__).'/../tmp/tmp_json_ovh.json');

$arrayJsonContent = json_decode($jsonContent, true); // true => convert object StdClass to array));

//die(var_dump($arrayJsonContent['participant'][0][1][0]['unavailability_sp']));

/* Update process pf_management_tmp ---> pf_management : 6 tables */

/* Table special_guest => DELETE (S) INSERT (P) */
/* The provider cannot change his ID so it is OK */
$appServiceSCA->deleteAllSpecialGuest();
$arraySpecialGuestsToInsert = array();
foreach($arrayJsonContent['special_guest'] as $key => $specialGuest) {
    
    $enterpriseSpecialGuest = $appServiceSCA->findOneEnterprise($specialGuest['oneEnterprise']);
    $purchasingFairSpecialGuest = $appServiceSCA->findOnePurchasingFair($specialGuest['onePurchasingFair']);

    $arraySpecialGuestsToInsert[] = $appServiceSCA->createSpecialGuest(
            $enterpriseSpecialGuest,
            $purchasingFairSpecialGuest,
            $specialGuest['civility'],
            $specialGuest['surname'],
            $specialGuest['name'],
            $specialGuest['days']
    );
}

foreach($arraySpecialGuestsToInsert as $key => $specialGuest) {
    $specialGuest->setIdSpecialGuest(-1); // -1 To insert , != -1 to update
    echo '- Insertion special_guest id '.$appServiceSCA->saveSpecialGuest($specialGuest).'<br/>';
}

/* End table special_guest */

/* Table participant */
/* Risque de conflits car participants Magasins et participants Fournisseur mélangés */
$arrayParticipantsSCA = $appServiceSCA->findAllParticipants();

$arrayParticipantsOVH = array();

/* $arrayJsonContent['participant'][0---n][idParticipant 0---m][0]['detailsParticipant'][0] */
foreach($arrayJsonContent['participant'] as $key => $value) {
    $participantToAdd = $appServiceSCA->createParticipant(
            $value[array_keys($value)[0]][0]['detailsParticipant'][0]['civility'],
            $value[array_keys($value)[0]][0]['detailsParticipant'][0]['surname'],
            $value[array_keys($value)[0]][0]['detailsParticipant'][0]['name'],
            $value[array_keys($value)[0]][0]['detailsParticipant'][0]['email']
            );
    
    $participantToAdd->setIdParticipant($value[array_keys($value)[0]][0]['detailsParticipant'][0]['idParticipant']);
    $arrayParticipantsOVH[] = $participantToAdd;    
}

$limitArrayParticipantsSCA = count($arrayParticipantsSCA);

function updateParticipantSCA(Participant $participantSCA, Participant $participantOVH) {
    
    /* Globals */
    global $appServiceSCA;
    global $arrayJsonContent;
    
    /* participant SCA & participant OVH ids */
    $idParticipantSCA = $participantSCA->getIdParticipant(); // ID to considere
    $idParticipantOVH = $participantOVH->getIdParticipant();
    
    /* Participant */
//    $appServiceSCA->saveParticipant($participantOVH);

    /* UnavailabilitySP */
    $arrayUnavailabilitySCA = $appServiceSCA->findAllUnavailabilitiesSpByParticipant($idParticipantSCA);
    $arrayUnavailabilityOVH = array();
    
    /* Get UnavailabilitySp */
    /* $arrayJsonContent['participant'][0---n][idParticipant 0---m][0]['unavailability_sp'][0] */
    foreach($arrayJsonContent['participant'] as $key => $value) {
        if(array_keys($value)[0] == $idParticipantOVH) {
            foreach($value[array_keys($value)[0]][0]['unavailability_sp'] as $key => $usp) {

                $uspToAdd = $appServiceSCA->createUnavailabilitySp(
                        $usp['startDatetime'], 
                        $usp['endDatetime'], 
                        $appServiceSCA->findOneParticipant($idParticipantSCA), 
                        $appServiceSCA->findOnePurchasingFair($usp['onePurchasingFair']));

                $uspToAdd->setIdUnavailability($usp['idUnavailability']);
                $arrayUnavailabilityOVH[] = $uspToAdd;    
            }
        }
    }
    
    // Check UnavailabilitySp
    foreach($arrayUnavailabilityOVH as $key => $uspOVH) {
        
        $isPresent = 0;
        
        foreach($arrayUnavailabilitySCA as $key => $uspSCA) {
            if($uspSCA->getIdUnavailability() == $uspOVH->getIdUnavailability()
                    && $uspSCA->getStartDatetime() == $uspOVH->getStartDatetime()
                    && $uspSCA->getEndDatetime() == $uspOVH->getEndDatetime()
                    && $uspSCA->getOnePurchasingFair()->getIdPurchasingFair() == $uspOVH->getOnePurchasingFair()->getIdPurchasingFair() ) {
            $isPresent = 1;
            break 1;
            }
        }
        if($isPresent) { // UPDATE
            $uspSCA->setStartDatetime($uspOVH->getStartDatetime());
            $uspSCA->setEndDatetime($uspOVH->getEndDatetime());
            $appServiceSCA->saveUnavailabilitySp($uspSCA);
        }
        else { // INSERT
			$uspOVH->setIdUnavailability(-1); // else there is nerver new insertion !!!
            $appServiceSCA->saveUnavailabilitySp($uspOVH);
        }
    } /* End UnavailabilitySp */
    
    /* AssignmentSpStore */
    $arrayAssignmentSpStoreSCA = $appServiceSCA->findAllAssignmentSpStoreByParticipant($idParticipantSCA);
    $arrayAssignmentSpStoreOVH = array();
    
    /* $arrayJsonContent['participant'][0---n][idParticipant 0---m][0]['assignment_sp_store'][0] */
    foreach($arrayJsonContent['participant'] as $key => $value) {
        if(array_keys($value)[0] == $idParticipantOVH) {
            foreach($value[array_keys($value)[0]][0]['assignment_sp_store'] as $key => $ass) {
                
                $assToAdd = $appServiceSCA->createAssignmentSpStore(
                        $appServiceSCA->findOneParticipant($idParticipantSCA), 
                        $appServiceSCA->findOneEnterprise($ass['oneStore']), 
                        $appServiceSCA->findOneEnterprise($ass['oneProvider']), 
                        $appServiceSCA->findOnePurchasingFair($ass['onePurchasingFair'])                       
                );

                $arrayAssignmentSpStoreOVH[] = $assToAdd;    
            }
        }
    }    
    
    /* Check AssignmentpStore */
    foreach($arrayAssignmentSpStoreSCA as $key => $assSCA) {
        $appServiceSCA->deleteAssignmentSpStoreBis($assSCA);
    }
    foreach($arrayAssignmentSpStoreOVH as $key => $assOVH) {
        $appServiceSCA->saveAssignmentSpStore($assOVH);
    } /* End AssignmentSpStore */
    
    /* AssignmentParticipantDepartment */
    $arrayAssignmentParticipantDepartmentSCA = $appServiceSCA->findAssignmentsParticipantDepartmentByParticipant($idParticipantSCA);
    
    $arrayAssignmentParticipantDepartmentOVH = array();
    
    /* $arrayJsonContent['participant'][0---n][idParticipant 0---m][0]['assignment_participant_department'][0] */
    foreach($arrayJsonContent['participant'] as $key => $value) {
        if(array_keys($value)[0] == $idParticipantOVH) {
            foreach($value[array_keys($value)[0]][0]['assignment_participant_department'] as $key => $apd) {
                
                $apdToAdd = $appServiceSCA->createAssignmentParticipantDepartment(
                        $appServiceSCA->findOneParticipant($idParticipantSCA), 
                        $appServiceSCA->findOneDepartment($apd['oneDepartment'])                       
                );

                $arrayAssignmentParticipantDepartmentOVH[] = $apdToAdd;    
            }
        }
    }    
    
    /* Check AssignmentParticipantDepartment */
    foreach($arrayAssignmentParticipantDepartmentSCA as $key => $apdSCA) {
        $appServiceSCA->deleteAssignmentParticipantDepartment($apdSCA);
    }
    foreach($arrayAssignmentParticipantDepartmentOVH as $key => $apdOVH) {
        $appServiceSCA->saveAssignmentParticipantDepartment($apdOVH);
    }
    
    /* AssignmentParticipantEnterprise */
    $arrayAssignmentParticipantEnterprisSCA = $appServiceSCA->findAllAssignmentsParticipantEnterpriseForOneParticipant($idParticipantSCA);    
    $arrayAssignmentParticipantEnterpriseOVH = array();

    /* $arrayJsonContent['participant'][0---n][idParticipant 0---m][0]['assignment_participant_enterprise'][0] */
    foreach($arrayJsonContent['participant'] as $key => $value) {
        if(array_keys($value)[0] == $idParticipantOVH) {
            foreach($value[array_keys($value)[0]][0]['assignment_participant_enterprise'] as $key => $ape) {
                
                $apeToAdd = $appServiceSCA->createAssignmentParticipantEnterprise(
                        $appServiceSCA->findOneParticipant($idParticipantSCA), 
                        $appServiceSCA->findOneEnterprise($ape['oneEnterprise'])                       
                );

                $arrayAssignmentParticipantEnterpriseOVH[] = $apeToAdd;    
            }
        }
    }
    
    /* Check AssignmentParticipantEnterprise */
    foreach($arrayAssignmentParticipantEnterprisSCA as $key => $apeSCA) {
        $appServiceSCA->deleteAssignmentParticipantEnterprise($apeSCA);
    }
    foreach($arrayAssignmentParticipantEnterpriseOVH as $key => $apeOVH) {
        $appServiceSCA->saveAssignmentParticipantEnterprise($apeOVH);
    } /* En AssignmentPArticipantEnterprise */
}

function insertParticipantOVH(Participant $participantOVH) {

    /* Globals */
    global $appServiceSCA;
    global $arrayJsonContent;
    
    /* Original ID */
    $idParticipantOVH = $participantOVH->getIdParticipant();
    
    /* Add Participant */
    $participantOVH->setIdParticipant(-1);
    $idNewParticipantSCA = $appServiceSCA->saveParticipant($participantOVH);
    echo '- Insertion participant id '.$idNewParticipantSCA.' '.$participantOVH->getCivility().' '.$participantOVH->getSurname().' '.$participantOVH->getName().' '.$participantOVH->getEmail().'<br/>';
    
//    if($idNewParticipantSCA == 0) {
//        file_put_contents('./../verif.txt', $participantOVH."\n", FILE_APPEND);
//        return -999999;
//    }
    
    /* UnavailabilitySp */
    $arrayUnavailabilityOVH = array();

    /* Get UnavailabilitySp */
    /* $arrayJsonContent['participant'][0---n][idParticipant 0---m][0]['unavailability_sp'][0] */
    foreach($arrayJsonContent['participant'] as $key => $value) {
        if(array_keys($value)[0] == $idParticipantOVH) {
            foreach($value[array_keys($value)[0]][0]['unavailability_sp'] as $key => $usp) {

                $uspToAdd = $appServiceSCA->createUnavailabilitySp(
                        $usp['startDatetime'], 
                        $usp['endDatetime'], 
                        $appServiceSCA->findOneParticipant($idNewParticipantSCA), 
                        $appServiceSCA->findOnePurchasingFair($usp['onePurchasingFair']));

                $arrayUnavailabilityOVH[] = $uspToAdd;    
            }
        }
    }
    
    foreach($arrayUnavailabilityOVH as $key => $usp) {
        $usp->setIdUnavailability(-1);
        echo '- Insertion unavailability_sp id '.$appServiceSCA->saveUnavailabilitySp($usp).'<br/>';
    }
        
    /* AssignmentSpStore */
    $arrayAssignmentSpStoreOVH = array();
    
    /* $arrayJsonContent['participant'][0---n][idParticipant 0---m][0]['assignment_sp_store'][0] */
    foreach($arrayJsonContent['participant'] as $key => $value) {
        if(array_keys($value)[0] == $idParticipantOVH) {
            foreach($value[array_keys($value)[0]][0]['assignment_sp_store'] as $key => $ass) {
                
                $assToAdd = $appServiceSCA->createAssignmentSpStore(
                        $appServiceSCA->findOneParticipant($idNewParticipantSCA), 
                        $appServiceSCA->findOneEnterprise($ass['oneStore']), 
                        $appServiceSCA->findOneEnterprise($ass['oneProvider']), 
                        $appServiceSCA->findOnePurchasingFair($ass['onePurchasingFair'])                       
                );

                $arrayAssignmentSpStoreOVH[] = $assToAdd;    
            }
        }
    }
    
    foreach($arrayAssignmentSpStoreOVH as $key => $ass) {
        echo '- Insertion assignment_sp_store '.$appServiceSCA->saveAssignmentSpStore($ass).'<br/>';
    }
        
    /* AssignmentParticipantDepartment */
    $arrayAssignmentParticipantDepartmentOVH = array();
    
    /* $arrayJsonContent['participant'][0---n][idParticipant 0---m][0]['assignment_participant_department'][0] */
    foreach($arrayJsonContent['participant'] as $key => $value) {
        if(array_keys($value)[0] == $idParticipantOVH) {
            foreach($value[array_keys($value)[0]][0]['assignment_participant_department'] as $key => $apd) {
                
                $apdToAdd = $appServiceSCA->createAssignmentParticipantDepartment(
                        $appServiceSCA->findOneParticipant($idNewParticipantSCA), 
                        $appServiceSCA->findOneDepartment($apd['oneDepartment'])                       
                );

                $arrayAssignmentParticipantDepartmentOVH[] = $apdToAdd;    
            }
        }
    }
    
    foreach($arrayAssignmentParticipantDepartmentOVH as $key => $apd) {
        $apd->getOneParticipant()->setIdParticipant($idNewParticipantSCA);
         echo '- Insertion assignment_participant_department '.$appServiceSCA->saveAssignmentParticipantDepartment($apd).'<br/>';
    }
        
    /* AssignmentParticipantEnterprise */
    $arrayAssignmentParticipantEnterpriseOVH = array();

    /* $arrayJsonContent['participant'][0---n][idParticipant 0---m][0]['assignment_participant_enterprise'][0] */
    foreach($arrayJsonContent['participant'] as $key => $value) {
        if(array_keys($value)[0] == $idParticipantOVH) {
            foreach($value[array_keys($value)[0]][0]['assignment_participant_enterprise'] as $key => $ape) {
                
                $apeToAdd = $appServiceSCA->createAssignmentParticipantEnterprise(
                        $appServiceSCA->findOneParticipant($idNewParticipantSCA), 
                        $appServiceSCA->findOneEnterprise($ape['oneEnterprise'])                       
                );

                $arrayAssignmentParticipantEnterpriseOVH[] = $apeToAdd;    
            }
        }
    }
    
    foreach($arrayAssignmentParticipantEnterpriseOVH as $key => $ape) {
        $ape->getOneParticipant()->setIdParticipant($idNewParticipantSCA);
         echo '- Insertion assignment_participant_enterprise '.$appServiceSCA->saveAssignmentParticipantEnterprise($ape).'<br/>';
    }
} // End function insertParticipant()

foreach($arrayParticipantsOVH as $key => $participantOVH) {
    $isPresent = 0;
	
    for( $i = 0 ; $i < $limitArrayParticipantsSCA; $i++ ) { // plutot arrayidparticipants ovh in_array
	
		$index = $i;

        if( 
            mb_strtolower( $participantOVH->getSurname(), 'UTF-8' ) == mb_strtolower( $arrayParticipantsSCA[$i]->getSurname(), 'UTF-8' )
            && mb_strtolower( $participantOVH->getName(), 'UTF-8' ) == mb_strtolower( $arrayParticipantsSCA[$i]->getName(), 'UTF-8' )
            && mb_strtolower( $participantOVH->getEmail(), 'UTF-8' ) == mb_strtolower( $arrayParticipantsSCA[$i]->getEmail(), 'UTF-8' )
        ) {
            updateParticipantSCA($arrayParticipantsSCA[$i], $participantOVH); 
            $isPresent = 1; 
             echo 'update';
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
        
		// $index => else overflow array
//        echo '- Comparaison NOK : ';
//        echo $participantOVH->getIdParticipant(). ' != '.$arrayParticipantsSCA[$index]->getIdParticipant().', ';
//        echo mb_strtolower( $participantOVH->getSurname(), 'UTF-8' ). ' != '.mb_strtolower( $arrayParticipantsSCA[$index]->getSurname(), 'UTF-8' ).', ';
//        echo mb_strtolower( $participantOVH->getName(), 'UTF-8' ). ' != '.mb_strtolower( $arrayParticipantsSCA[$index]->getName(), 'UTF-8' ).', ';
//        echo mb_strtolower( $participantOVH->getEmail(), 'UTF-8' ). ' != '.mb_strtolower( $arrayParticipantsSCA[$index]->getEmail(), 'UTF-8' ).'.<br/>';
    }
}

echo '1';
?>