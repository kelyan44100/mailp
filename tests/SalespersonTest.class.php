<?php
require_once dirname ( __FILE__ ) . '/simpletest-1.1.7/autorun.php' ;
require_once dirname ( __FILE__ ) . '/../services/AppServiceImpl.class.php';

class SalespersonTest extends UnitTestCase {

/******************************************************** DEBUT TESTS SalespersonDAO *********************************************************************/
// echo '<h1>Récupération de tous les commerciaux</h1>';
// $arraySalespersons = $appService->findAllSalespersons();
// echo '<pre>';
// print_r($arraySalespersons);
// echo '</pre>';

// echo '<h1>Récupération d\'un seul commercial</h1>';
// $salespersonTest = $appService->findOneSalesperson(1);
// echo '<pre>';
// if(is_null($salespersonTest)) echo 'Commercial non trouvé'; else print_r($salespersonTest);
// echo '</pre>';

// echo '<h1>Insertion d\'un nouveau commercial</h1>';
// $salespersonInsert = new Salesperson("Mr", "DOE", "John");
// $count = $appService->saveSalesperson($salespersonInsert);
// echo '<pre>';
// echo 'Commercial à insérer ?<br/>'; 
// print_r($salespersonInsert);
// echo ($count) ? 'Insertion OK' : 'Insertion NOK';
// echo '</pre>';

// echo '<h1>Mise à jour d\'un profil</h1>';
// $salespersonUpdate = $arraySalespersons[0];
// $salespersonUpdate->setName("BIGBOSS");
// $count = $appService->saveSalesperson($salespersonUpdate);
// echo '<pre>';
// echo 'Commercial à màj ?<br/>';
// print_r($salespersonUpdate);
// echo ($count) ? 'Màj OK' : 'Màj NOK';
// echo '</pre>';

// echo '<h1>Suppression d\'un Commercial</h1>';
// $salespersonDelete = $arraySalespersons[4];
// $count = $appService->deleteSalesperson($salespersonDelete);
// echo '<pre>';
// echo 'Commercial à delete ?<br/>';
// print_r($salespersonDelete);
// echo ($count) ? 'Suppression OK' : 'Suppression NOK';
// echo '</pre>';

// echo '<h1>Désactivation d\'un commercial</h1>';
// $salespersonDeactivate = $arraySalespersons[0];
// $count = $appService->deactivateSalesperson($salespersonDeactivate);
// echo '<pre>';
// echo 'Commercial à deactivate ?<br/>';
// print_r($salespersonDeactivate);
// echo ($count) ? 'Désactivation OK' : 'Désactivation NOK';
// echo '</pre>';

// echo '<h1>Création d\'un commercial</h1>';
// $createdSalesperson = $appService->createSalesperson("CIVILITY", "SURNAME", "NAME");
// echo '<pre>';
// print_r($createdSalesperson);
// echo '</pre>';
/******************************************************** FIN TESTS SalespersonDAO *********************************************************************/
}
