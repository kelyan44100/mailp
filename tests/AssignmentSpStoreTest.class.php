<?php
require_once dirname ( __FILE__ ) . '/simpletest-1.1.7/autorun.php' ;
require_once dirname ( __FILE__ ) . '/../services/AppServiceImpl.class.php';

class AssignmentSpStoreTest extends UnitTestCase {

/******************************************************** DEBUT TESTS AssignmentSpStoreDAO *********************************************************************/
// echo '<h1>Création d\'un ASS</h1>';
// $createdASS = $appService->createAssignmentSpStore($appService->findOneparticipant(1), $appService->findOneEnterprise(40), $appService->findOnePurchasingFair(1));
// echo '<pre>';
// print_r($createdASS);
// echo '</pre>';

// echo '<h1>Insertion d\'un nouveau ASS</h1>';
// $count = $appService->saveAssignmentSpStore($createdASS);
// echo '<pre>';
// echo 'APD à insérer ?<br/>'; 
// print_r($createdASS);
// echo ($count) ? 'Insertion OK' : 'Insertion NOK';
// echo '</pre>';
 
// echo '<h1>Récupération de tous les ASS</h1>';
// $arrayASS = $appService->findAllAssignmentsSpStore();
// echo '<pre>';
// print_r($arrayASS);
// echo '</pre>';

// echo '<h1>Récupération d\'un seul ASS</h1>';
//$assTest = $appService->findOneAssignmentSpStore(1,40,1);
// echo '<pre>';
// if(is_null($assTest)) echo 'ASS non trouvé'; else print_r($assTest);
// echo '</pre>';

// echo '<h1>Suppression d\'un ASS</h1>';
// $count = $appService->deleteAssignmentSpStore($assTest);
// echo '<pre>';
// echo 'ASS à delete ?<br/>';
// print_r($assTest);
// echo ($count) ? 'Suppression OK' : 'Suppression NOK';
// echo '</pre>';
/******************************************************** FIN TESTS AssignmentSpStoreDAO *********************************************************************/
    
    public function testSummaryOfAssignedStores() {
        
        $appService = AppServiceImpl::getInstance();
        
        $result = $appService->summaryOfAssignedStores(109, 3);
                
        $this->assertTrue(is_array($result));
    }
}
