<?php
require_once dirname ( __FILE__ ) . '/../services/AppServiceImpl.class.php';
require_once dirname ( __FILE__ ) . '/../view/errors.inc.php';

$appServiceSCA = AppServiceImpl::getInstance();

// Set CORS
$appServiceSCA->cors();

// To prevent clear data
if(!isset($_POST['password'])) { die('Connexion refusée'); }
if( isset($_POST['password']) && !password_verify($_POST['password'], '$2y$10$aXrN7qSG5mYraviTya7NR.rKyEpNeI4hpDI6epQ3hgbP5O.lkQoKu')) {
    die('Connexion refusée');
}

$idPurchasingFair = $_POST['idPurchasingFair'];
$planningContent  = $_POST['planningContent'];
file_put_contents(dirname(__FILE__).'/tmp_planning_pf'.$idPurchasingFair.'.html', $planningContent);
echo 'Success';
?>