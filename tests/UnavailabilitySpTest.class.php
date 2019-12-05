<?php
require_once dirname ( __FILE__ ) . '/simpletest-1.1.7/autorun.php' ;
require_once dirname ( __FILE__ ) . '/../services/AppServiceImpl.class.php';

class UnavailabilitySpTest extends UnitTestCase {

 /*************************************************** DEBUT TESTS UnavailabilitySpDAO *************************************************************/
// echo '<h1>Création d\'une UnavailabilitySpSp</h1>';
// $createdUnavailabilitySp = $appService->createUnavailabilitySp('DATETIME_1', 'DATETIME_2', 'ID_PARTICIPANT', 'ID_PURCHASING_FAIR');
// echo '<pre>';
// print_r($createdUnavailabilitySp);
// echo '</pre>';

// echo '<h1>Insertion d\'une nouvelle UnavailabilitySp</h1>';
// $unavailabilitySpInsert = new UnavailabilitySp('2017-01-01 01:01:01', '2017-01-02 02:02:03', $appService->findOneParticipant(1), $appService->findOnePurchasingFair(1));
// $count = $appService->saveUnavailabilitySp($unavailabilitySpInsert);
// echo '<pre>';
// echo 'Indispo à insérer ?<br/>'; 
// print_r($unavailabilitySpInsert);
// echo ($count) ? 'Insertion OK' : 'Insertion NOK';
// echo '</pre>';

// echo '<h1>Récupération de toutes les indispos</h1>';
// $arrayUnavailabilitiesSp = $appService->findAllUnavailabilitiesSp();
// echo '<pre>';
// print_r($arrayUnavailabilitiesSp);
// echo '</pre>';

// echo '<h1>Récupération d\'une seule indispo</h1>';
// $unavailabilitySpTest = $appService->findOneUnavailabilitySp(2);
// echo '<pre>';
// if(is_null($unavailabilitySpTest)) echo 'Indispo non trouvée'; else print_r($unavailabilitySpTest);
// echo '</pre>';

// echo '<h1>Mise à jour d\'une indispo</h1>';
// $unavailabilitySpUpdate = $unavailabilitySpTest;
// $unavailabilitySpUpdate->setEndDatetime("2093-01-01 01:01:01");
// $count = $appService->saveUnavailabilitySp($unavailabilitySpUpdate);
// echo '<pre>';
// echo 'Indispo à màj ?<br/>';
// print_r($unavailabilitySpUpdate);
// echo ($count) ? 'Màj OK' : 'Màj NOK';
// echo '</pre>';

// echo '<h1>Suppression d\'une indispo</h1>';
// $unavailabilitySpDelete = $unavailabilitySpTest;
// $count = $appService->deleteUnavailabilitySp($unavailabilitySpDelete);
// echo '<pre>';
// echo 'Indispo à delete ?<br/>';
// print_r($unavailabilitySpDelete);
// echo ($count) ? 'Suppression OK' : 'Suppression NOK';
// echo '</pre>';

// echo '<h1>Désactivation d\'une indispo</h1>';
// $unavailabilitySpDeactivate = $unavailabilitySpTest;
// $count = $appService->deactivateUnavailabilitySp($unavailabilitySpDeactivate);
// echo '<pre>';
// echo 'Indispo à deactivate ?<br/>';
// print_r($unavailabilitySpDeactivate);
// echo ($count) ? 'Désactivation OK' : 'Désactivation NOK';
// echo '</pre>';

// echo '<h1>Récupérer les indispos d\'un participant commercial</h1>';
// $participantUnavailabilities = $appService->findParticipantUnavailabilitiesSp($appService->findOneParticipant(1), $appService->findOnePurchasingFair(1));
// echo '<pre>';
// print_r($participantUnavailabilities);
// echo '</pre>';

/***************************************************** FIN TESTS UnavailabilitySpDAO *************************************************************/
}
