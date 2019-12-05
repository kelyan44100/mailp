<?php
require_once dirname ( __FILE__ ) . '/simpletest-1.1.7/autorun.php' ;
require_once dirname ( __FILE__ ) . '/../services/AppServiceImpl.class.php';

class UnavailabilityTest extends UnitTestCase {

/*************************************************** DEBUT TESTS UnavailabilityDAO *************************************************************/
// echo '<h1>Création d\'une indisponibilité</h1>';
// $createdUnavailability = $appService->createUnavailability('DATETIME_1', 'DATETIME_2', 'ID_ENTERPRISE', 'ID_PURCHASING_FAIR');
// echo '<pre>';
// print_r($createdUnavailability);
// echo '</pre>';

// echo '<h1>Récupération de toutes les indispos</h1>';
// $arrayUnavailabilities = $appService->findAllUnavailabilities();
// echo '<pre>';
// print_r($arrayUnavailabilities);
// echo '</pre>';

// echo '<h1>Récupération d\'une seule indispo</h1>';
// $unavailabilityTest = $appService->findOneUnavailability(2);
// echo '<pre>';
// if(is_null($unavailabilityTest)) echo 'Indispo non trouvée'; else print_r($unavailabilityTest);
// echo '</pre>';

// echo '<h1>Insertion d\'une nouvelle indispo</h1>';
// $unavailabilityInsert = new Unavailability('2017-01-01 01:01:01', '2017-01-02 02:02:03', 51, 1);
// $count = $appService->saveUnavailability($unavailabilityInsert);
// echo '<pre>';
// echo 'Indispo à insérer ?<br/>'; 
// print_r($unavailabilityInsert);
// echo ($count) ? 'Insertion OK' : 'Insertion NOK';
// echo '</pre>';

// echo '<h1>Mise à jour d\'une indispo</h1>';
// $unavailabilityUpdate = $arrayUnavailabilities[0];
// $unavailabilityUpdate->setEndDatetime("2000-01-01 01:01:01");
// $count = $appService->saveUnavailability($unavailabilityUpdate);
// echo '<pre>';
// echo 'Indispo à màj ?<br/>';
// print_r($unavailabilityUpdate);
// echo ($count) ? 'Màj OK' : 'Màj NOK';
// echo '</pre>';

// echo '<h1>Suppression d\'une indispo</h1>';
// $unavailabilityDelete = $arrayUnavailabilities[0];
// $count = $appService->deleteUnavailability($unavailabilityDelete);
// echo '<pre>';
// echo 'Indispo à delete ?<br/>';
// print_r($unavailabilityDelete);
// echo ($count) ? 'Suppression OK' : 'Suppression NOK';
// echo '</pre>';

// echo '<h1>Désactivation d\'une indispo</h1>';
// $unavailabilityDeactivate = $arrayUnavailabilities[0];
// $count = $appService->deactivateUnavailability($unavailabilityDeactivate);
// echo '<pre>';
// echo 'Indispo à deactivate ?<br/>';
// print_r($unavailabilityDeactivate);
// echo ($count) ? 'Désactivation OK' : 'Désactivation NOK';
// echo '</pre>';

// echo '<h1>Récupérer les indispos d\'une entreprise</h1>';
// $unavailabilitiesTest = $appService->findOneEnterprise(51);
// $enterpriseUnavailabilities = $appService->findEnterpriseUnavailabilities($unavailabilitiesTest, $purchasingFairTest);
// echo '<pre>';
// print_r($enterpriseUnavailabilities);
// echo '</pre>';

// echo '<h1>Récupérer les indispos d\'un salon d\'achat</h1>';
// $unavailabilitiesTest = $appService->findOnePurchasingFair(3);
// $purchasingFairUnavailabilities = (!is_null($unavailabilitiesTest)) ? $appService->findPurchasingFairUnavailabilities($unavailabilitiesTest) : 'Tableau vide';
// echo '<pre>';
// print_r($purchasingFairUnavailabilities);
// echo '</pre>';

// echo '<h1>Enregistrement d\'un indispo avec entreprise</h1>';
// $enterprise = $appService->findOneEnterprise(52);
// $unavailability = $appService->findOneUnavailability(9);
// $count = $appService->saveUnavailabilityRegister($enterprise, $unavailability);
// echo '<pre>';
// echo ($count) ? 'Indispo_register OK' : 'Indispo_register NOK';
// echo '</pre>';

// $newDateTime = $appService->createUnavailability('2017-01-01 01:01:01', '2017-02-02 02:02:02', 20);
// var_dump($newDateTime);

// $newDateTime->setTimeEndDateTime('23:59:59');
// var_dump($newDateTime);

// $appService->saveUnavailability($newDateTime);
/***************************************************** FIN TESTS UnavailabilityDAO *************************************************************/
}
