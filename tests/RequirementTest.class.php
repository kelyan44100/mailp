<?php
require_once dirname ( __FILE__ ) . '/simpletest-1.1.7/autorun.php' ;
require_once dirname ( __FILE__ ) . '/../services/AppServiceImpl.class.php';

class RequirementTest extends UnitTestCase {
    
    // Methods
//    public function testFindByDuo() {
//        $appService = AppServiceImpl::getInstance();
//        $storeConcerned = $appService->findOneEnterprise(1);
//        $pfConcerned = $appService->findOnePurchasingFair(3);
//        $arrayTest = $appService->findRequirementFilteredDuo($storeConcerned, $pfConcerned);
//        $this->assertTrue(is_array($arrayTest));
//    }
//    
//    public function testFindByDuoWithTotalNumberHours() {
//        $appService = AppServiceImpl::getInstance();
//        $storeConcerned = $appService->findOneEnterprise(1);
//        $pfConcerned = $appService->findOnePurchasingFair(3);
//        $arrayTest = $appService->findRequirementFilteredDuoWithTotNumberHours($storeConcerned, $pfConcerned);
//        $this->assertTrue(is_array($arrayTest));
//    }
//    
//    public function testFindByDuoWithTotalNumberHoursAndUnavs() {
//        $appService = AppServiceImpl::getInstance();
//        $storeConcerned = $appService->findOneEnterprise(1);
//        $pfConcerned = $appService->findOnePurchasingFair(3);
//        $arrayTest = $appService->findRequirementFilteredDuoWithTotNumberHoursAndUnavs($storeConcerned, $pfConcerned);
//        var_dump($arrayTest);
//        $this->assertTrue(is_array($arrayTest));
//    }
    
    // public function testFindAllRequirementsByPf() {
        // $appService = AppServiceImpl::getInstance();
        // $arrayTest = $appService->findAllRequirementsByPf(4);
        // $this->assertTrue(is_array($arrayTest));
    // }
	
	public function testFindByThreeIdsBis() {
		$appService = AppServiceImpl::getInstance();
        $arrayTest = $appService->findOneAssignmentSpStoreQuatro(1,66,4);
		// var_dump($arrayTest);
		$this->assertIsA($arrayTest, 'AssignmentSpStore');
		// $this->assertTrue(is_array($arrayTest));
	}

/*************************************************** DEBUT TESTS RequirementDAO *************************************************************/
// echo '<h1>Création d\'un besoin en heures</h1>';
// $createdRequirement = $appService->createRequirement(51,2,1,6);
// echo '<pre>';
// print_r($createdRequirement);
// echo '</pre>';

// echo '<h1>Récupération de tous les besoins en heures</h1>';
// $arrayRequirements = $appService->findAllRequirements();
// echo '<pre>';
// print_r($arrayRequirements);
// echo '</pre>';

// echo '<h1>Récupération de tous les besoins en heures pour un salon et un magasin donnés</h1>';
// $arrayRequirements = $appService->findAllRequirementsForStoreAndPurchasingFair(1,3);
// echo '<pre>';
// print_r($arrayRequirements);
// echo '</pre>';
//
// echo '<h1>Récupération d\'un seul besoin en heures</h1>';
// $requirementTest = $appService->findOneRequirement(7);
// echo '<pre>';
// if(is_null($requirementTest)) echo 'Besoin en h non trouvé'; else print_r($requirementTest);
// echo '</pre>';

// echo '<h1>Récupération d\'un seul besoin en heures avec filtre TRIO</h1>';
// $requirementTest = $appService->findRequirementFilteredTrio($appService->findOneEnterprise(51),$appService->findOneEnterprise(2),$appService->findOnePurchasingFair(1));
// echo '<pre>';
// if(is_null($requirementTest)) echo 'Besoin en h non trouvé'; else print_r($requirementTest);
// echo '</pre>';

// echo '<h1>Récupération d\'un seul besoin en heures avec filtre DUO</h1>';
// $requirementTest = $appService->findRequirementFilteredDuo($appService->findOneEnterprise(51), $appService->findOnePurchasingFair(1));
// echo '<pre>';
// if(is_null($requirementTest)) echo 'Besoin en h non trouvé'; else print_r($requirementTest);
// echo '</pre>';

// echo '<h1>Insertion d\'un nouveau besoin</h1>';
// $requirementInsert = new Requirement($appService->findOneEnterprise(51),$appService->findOneEnterprise(2),$appService->findOnePurchasingFair(1),4);
// $count = $appService->saveRequirement($requirementInsert);
// echo '<pre>';
// echo 'Besoin en h à insérer ?<br/>'; 
// print_r($requirementInsert);
// echo ($count) ? 'Insertion OK' : 'Insertion NOK';
// echo '</pre>';

// echo '<h1>Mise à jour d\'un besoin</h1>';
// $requirementUpdate = $arrayRequirements[0];
// $requirementUpdate->setNumberOfHours(159);
// $count = $appService->saverequirement($requirementUpdate);
// echo '<pre>';
// echo 'Besoin à màj ?<br/>';
// print_r($requirementUpdate);
// echo ($count) ? 'Màj OK' : 'Màj NOK';
// echo '</pre>';

// echo '<h1>Suppression d\'un besoin en h</h1>';
// $requirementDelete = $arrayRequirements[0];
// $count = $appService->deleteRequirement($requirementDelete);
// echo '<pre>';
// echo 'Besoin à delete ?<br/>';
// print_r($requirementDelete);
// echo ($count) ? 'Suppression OK' : 'Suppression NOK';
// echo '</pre>';

// echo '<h1>Suppression de tous les besoinsen h pour un duo</h1>';
// $count = $appService->deleteRequirementFilteredDuo($appService->findOneEnterprise(51), $appService->findOnePurchasingFair(1));
// echo '<pre>';
// echo ($count) ? 'Suppression de '.$count.' rows OK' : 'Suppression NOK';
// echo '</pre>';
/***************************************************** FIN TESTS RequirementDAO *************************************************************/
}
