<?php
require_once dirname ( __FILE__ ) . '/simpletest-1.1.7/autorun.php' ;
require_once dirname ( __FILE__ ) . '/../services/AppServiceImpl.class.php';

class DepartmentTest extends UnitTestCase {

/***************************************************** DEBUT TESTS DepartmentDAO ******************************************************************/
// echo '<h1>Récupération de tous les departements</h1>';
// $arrayDepartments = $appService->findAllDepartments();
// echo '<pre>';
// print_r($arrayDepartments);
// echo '</pre>';

// echo '<h1>Récupération d\'un seul departement</h1>';
// $departmentTest = $appService->findOneDepartment(975);
// echo '<pre>';
// if(is_null($departmentTest)) echo 'Departement non trouvé'; else print_r($departmentTest);
// echo '</pre>';

// echo '<h1>Insertion d\'un nouveau departement</h1>';
// $departmentInsert = new Department('NICOLAS', 1483);
// $count = $appService->saveDepartment($departmentInsert);
// echo '<pre>';
// echo 'Departement à insérer ?<br/>'; 
// print_r($departmentInsert);
// echo ($count) ? 'Insertion OK' : 'Insertion NOK';
// echo '</pre>';

// echo '<h1>Mise à jour d\'un profil</h1>';
// $profileUpdate = $arrayProfiles[4];
// $profileUpdate->setName("MINI_BOSS");
// $count = $appService->saveProfile($profileUpdate);
// echo '<pre>';
// echo 'Profil à màj ?<br/>';
// print_r($profileUpdate);
// echo ($count) ? 'Màj OK' : 'Màj NOK';
// echo '</pre>';

// echo '<h1>Suppression d\'un Departement</h1>';
// $departmentDelete = $arrayDepartments[4];
// $count = $appService->deleteDepartment($departmentDelete);
// echo '<pre>';
// echo 'Department à delete ?<br/>';
// print_r($departmentDelete);
// echo ($count) ? 'Suppression OK' : 'Suppression NOK';
// echo '</pre>';

// echo '<h1>Création d\'un department</h1>';
// $createdDepartment = $appService->createDepartment('NEW_DPT');
// echo '<pre>';
// print_r($createdDepartment);
// echo '</pre>';

// echo '<h1>Récupérer le departement d\'une entreprise</h1>';
// $enterpriseTest = $appService->findOneEnterprise(2);
// $enterpriseDepartment = $appService->findEnterpriseDepartment($enterpriseTest);
// echo '<pre>';
// print_r($enterpriseDepartment);
// echo '</pre>';
/****************************************************** FIN TESTS DepartmentDAO *******************************************************************/
}
