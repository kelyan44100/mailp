<?php
$idPurchasingFair = $_POST['idPurchasingFair'];
$tmpPlanningContent = file_get_contents(dirname(__FILE__).'/tmp_planning_pf'.$idPurchasingFair.'.html');
echo $tmpPlanningContent;
?>