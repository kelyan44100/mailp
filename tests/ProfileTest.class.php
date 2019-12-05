<?php
require_once dirname ( __FILE__ ) . '/simpletest-1.1.7/autorun.php' ;
require_once dirname ( __FILE__ ) . '/../services/AppServiceImpl.class.php';

class ProfileTest extends UnitTestCase {

/***************************************************** DEBUT TESTS ProfileDAO ******************************************************************/
// echo '<h1>Récupération de tous les profils</h1>';
// $arrayProfiles = $appService->findAllProfiles();
// echo '<pre>';
// print_r($arrayProfiles);
// echo '</pre>';

// echo '<h1>Récupération d\'un seul profil</h1>';
// $profileTest = $appService->findOneProfile(666);
// echo '<pre>';
// if(is_null($profileTest)) echo 'Profil non trouvé'; else print_r($profileTest);
// echo '</pre>';

// echo '<h1>Insertion d\'un nouveau profil</h1>';
// $profileInsert = new Profile('BIG_BOSS');
// $count = $appService->saveProfile($profileInsert);
// echo '<pre>';
// echo 'Profil à insérer ?<br/>'; 
// print_r($profileInsert);
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

// echo '<h1>Suppression d\'un Profil</h1>';
// $profileDelete = $arrayProfiles[4];
// $count = $appService->deleteProfile($profileDelete);
// echo '<pre>';
// echo 'Profile à delete ?<br/>';
// print_r($profileDelete);
// echo ($count) ? 'Suppression OK' : 'Suppression NOK';
// echo '</pre>';

// echo '<h1>Désactivation d\'un profil</h1>';
// $profileDeactivate = $arrayProfiles[4];
// $count = $appService->deactivateProfile($profileDeactivate);
// echo '<pre>';
// echo 'Profile à deactivate ?<br/>';
// print_r($profileDeactivate);
// echo ($count) ? 'Désactivation OK' : 'Désactivation NOK';
// echo '</pre>';

// echo '<h1>Création d\'un profil</h1>';
// $createdProfile = $appService->createProfile('TEST_1');
// echo '<pre>';
// print_r($createdProfile);
// echo '</pre>';

// echo '<h1>Récupérer le profil d\'une entreprise</h1>';
// $enterpriseTest = $appService->findOneEnterprise(51);
// $enterpriseProfile = $appService->findEnterpriseProfile($enterpriseTest);
// echo '<pre>';
// print_r($enterpriseProfile);
// echo '</pre>';
/****************************************************** FIN TESTS ProfileDAO *******************************************************************/
}
