<?php
require_once dirname ( __FILE__ ) . '/simpletest-1.1.7/autorun.php' ;
require_once dirname ( __FILE__ ) . '/../services/AppServiceImpl.class.php';

class AssignmentParticipantEnterpriseTest extends UnitTestCase {
    
    
    public function testGetAllEnterprisesForParticipant() {
        $appService = AppServiceImpl::getInstance();
        $this->assertTrue(is_array($appService->findAllAssignmentsParticipantEnterpriseForOneParticipant(60))); // DEQUATRE Sandrine
        var_dump($appService->findAllAssignmentsParticipantEnterpriseForOneParticipant(60));
    }

/******************************************************** DEBUT TESTS AssignmentParticipantEnterpriseDAO *********************************************************************/
// echo '<h1>Insertion d\'un nouveau APE</h1>';
// $createdAPE = $appService->createAssignmentParticipantEnterprise($appService->findOneParticipant(2), $appService->findOneEnterprise(4));
// $count = $appService->saveAssignmentParticipantEnterprise($createdAPE);
// echo '<pre>';
// echo 'APE à insérer ?<br/>'; 
// print_r($createdAPE);
// echo ($count) ? 'Insertion OK' : 'Insertion NOK';
// echo '</pre>';

// echo '<h1>Récupération de tous les APE</h1>';
// $arrayAPE = $appService->findAllAssignmentsParticipantEnterprise();
// echo '<pre>';
// print_r($arrayAPE);
// echo '</pre>';

// echo '<h1>Récupération d\'un seul APE</h1>';
// $apeTest = $appService->findOneAssignmentParticipantEnterprise(3,3);
// echo '<pre>';
// if(is_null($apeTest)) echo 'APE non trouvé'; else print_r($apeTest);
// echo '</pre>';

// echo '<h1>Récupération des APE pour une Enterprise</h1>';
// $apeTest = $appService->findAllAssignmentsParticipantEnterpriseForOneEnterprise(3);
// echo '<pre>';
// if(is_null($apeTest)) echo 'APE non trouvé'; else print_r($apeTest);
// echo '</pre>';

// echo '<h1>Suppression d\'un APE</h1>';
// $apeDelete = $appService->findOneAssignmentParticipantEnterprise(3, 3);
// $count = $appService->deleteAssignmentParticipantEnterprise($apeDelete);
// echo '<pre>';
// echo 'APE à delete ?<br/>';
// print_r($apeDelete);
// echo ($count) ? 'Suppression OK' : 'Suppression NOK';
// echo '</pre>';

// echo '<h1>Création d\'un APE</h1>';
// $createdAPE = $appService->createAssignmentParticipantEnterprise($appService->findOneparticipant(3), $appService->findOneEnterprise(3));
// echo '<pre>';
// print_r($createdAPE);
// echo '</pre>';
/******************************************************** FIN TESTS AssignmentParticipantEnterpriseDAO *********************************************************************/
}
