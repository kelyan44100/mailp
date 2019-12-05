<?php
require_once dirname ( __FILE__ ) . '/../services/AppServiceOVHImpl.class.php';
require_once dirname ( __FILE__ ) . '/../services/AppServiceImpl.class.php';

/* To see all details when var_dump() function used */
ini_set('xdebug.var_display_max_depth', -1);
ini_set('xdebug.var_display_max_children', -1);
ini_set('xdebug.var_display_max_data', -1);

//$appServiceOVH = AppServiceOVHImpl::getInstance();
$appServiceSCA = AppServiceImpl::getInstance();
//var_dump($appServiceOVH->findOnePurchasingFair(3));
//var_dump($appService->findOnePurchasingFair(3));

//$storeToIterate = $appServiceSCA->findOneEnterprise(23);
$pf = $appServiceSCA->findOnePurchasingFair(4);
//
//    $arrayExpTest[] = $appServiceSCA->findRequirementFilteredDuoWithTotNumberHoursAndUnavs($storeToIterate, $pf);
//var_dump($arrayExpTest);

// $a = $appServiceSCA->findAllStoresNotAvailableForTimeSlotAndPf($pf, Datetime::createFromFormat('Y-m-d H:i:s', '2019-09-03 07:30:00'),  Datetime::createFromFormat('Y-m-d H:i:s', '2020-09-03 08:00:00'));

// echo ((empty($a) && in_array(23, $a))) ? 'true' : 'false';
var_dump(SingletonConnectionMySQL::getInstance()->getDbh());
SingletonConnectionMySQL::getInstance()->close();
var_dump(SingletonConnectionMySQL::getInstance()->getDbh());

$arrayMonths = array('JANVIER','FEVRIER','MARS','AVRIL','MAI','JUIN','JUILLET','AOUT','SEPTEMBRE','OCTOBRE','NOVEMBRE','DECEMBRE');

echo 'Le mois de septembre est à la position '.array_search('SEPTEMBRE', $arrayMonths). 'du tableau';

$storeTest = $appServiceSCA->findOneEnterprise(1);
$pfTest = $appServiceSCA->findOnePurchasingFair(4);

$arrayRequirements = $appServiceSCA->findRequirementFilteredDuoWithTotNumberHoursAndUnavs($storeTest, $pfTest);

//var_dump($arrayRequirements['requirements']);

echo '<hr/>';
foreach($arrayRequirements['requirements'] as $key => $req) {
    $myKey = $key;
    if($req->getOneProvider()->getName() == 'ABACA') {
        echo 'Fournisseur ABACA trouvé, clé élément => '.$myKey;
        break 1;
    }
}

$copyReq = clone $arrayRequirements['requirements'][$myKey];

// http://php.net/manual/fr/function.array-splice.php
// See example #1 last instruction

array_splice($arrayRequirements['requirements'], ($myKey+1), 0, array($copyReq)); // array here !!! see doc

var_dump($arrayRequirements['requirements']);

echo '<hr/>';

$arrayStr = array('nicolas', 'alexandre', 'molinaro');

var_dump($arrayStr);

array_splice($arrayStr, 1, 0, 'jérôme');

var_dump($arrayStr);

unset($arrayStr[0]);

var_dump($arrayStr);

unset($arrayStr[666]);

var_dump($arrayStr);

echo '<hr/>';
?>

<link href="../css/bootstrap.min.css" rel="stylesheet">

<div class="col-sm-3" style="background-color:#ff0000">aaa</div>

<div id="testJson"></div>

<!-- Mainly scripts -->
<script src="../js/jquery-3.1.1.min.js"></script>
<script src="../js/bootstrap.min.js"></script>
<script src="../js/plugins/metisMenu/jquery.metisMenu.js"></script>
<script src="../js/plugins/slimscroll/jquery.slimscroll.min.js"></script>


<!-- Color picker -->
<script src="../js/plugins/colorpicker/bootstrap-colorpicker.min.js"></script>

<!-- Daterange picker -->
<script src="../js/plugins/daterangepicker/moment.min.js"></script>
<script src="../js/plugins/daterangepicker/daterangepicker.js"></script>

<!-- Clock picker -->
<script src="../js/plugins/clockpicker/clockpicker.js"></script>

<!-- Custom and plugin javascript -->
<script src="../js/inspinia.js"></script>
<script src="../js/plugins/pace/pace.min.js"></script>

<!-- Toastr script -->
<script src="../js/plugins/toastr/toastr.min.js"></script>

<!-- iCheck -->
<script src="../js/plugins/iCheck/icheck.min.js"></script>

<!-- Custom script -->
<script>
//$.post(
//'http://www.scaouest.info/pf_management/ajax/getJsonOVH.php',
//{
//},
//function(data) {
//    data = data.trim();
//    $('#testJson').text(data);
//},
//'text'
//);
</script>