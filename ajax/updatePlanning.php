<?php
$idPurchasingFair = $_POST['idPurchasingFair'];
$planningContent  = $_POST['planningContent'];
file_put_contents('./../tmp/tmp_planning_pf'.$idPurchasingFair.'.html', $planningContent);
echo 'Success';
?>