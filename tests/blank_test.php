<?php
/* Service required + RandomColor */
require_once dirname ( __FILE__ ) . '/../services/AppServiceImpl.class.php';

/* To see all details when var_dump() function used */
ini_set('xdebug.var_display_max_depth', -1);
ini_set('xdebug.var_display_max_children', -1);
ini_set('xdebug.var_display_max_data', -1);

$appService = AppServiceImpl::getInstance();
// http://php.net/manual/fr/function.ob-start.php
ob_start();

?>

<?php 

?>
<script>
</script>

<?php

$data = ob_get_contents();

ob_end_clean();


echo $data;

$arrayA = array(1,2,3,4);
var_dump($arrayA);

$arrayB = array(1,2,6,7);
var_dump($arrayB);

$arrayC = array_merge($arrayA, $arrayB);
var_dump($arrayC);

$arrayD = array_unique($arrayC);
var_dump($arrayD);

echo 'Modulo =>';
echo 0 % 10;
?>

<script src="../js/jquery-3.1.1.min.js"></script>
<script src="../js/plugins/table2excel/jquery.table2excel.js"></script>

<?php //echo ( intval(3) != 0 && intval(1) != 0 && intval(189) != 0 && intval(0) != 0) ? 'true' : 'false'; 

//$arrayTest = array();
//$arrayTest[1] = array("toto");
//$arrayTest[2] = array("toto");
//$arrayTest[2][] = 'nicolas';
//$arrayTest[3] = array('molinaro');
//
//var_dump($arrayTest);

//if(array_key_exists("1", $arrayTest) && in_array('toto', $arrayTest[1])) echo 'OK'; else echo 'NOK';

/* Test if the Salesperson has several appointments at the same time - Added 04/06/2018 */
$arraySpecificCase = array();

//function checkArraySpecificCase($tableNumber, $idSalesperson, $lineAppearance) {
//    
//    // Access variable from the global scope
//    global $arraySpecificCase;
//    
//    // TESTS
//    
//    // Salesperson is already present today
//    if( array_key_exists( $tableNumber, $arraySpecificCase ) && array_key_exists( $idSalesperson, $arraySpecificCase[$tableNumber] ) ) {
//        
//        if( $arraySpecificCase[$tableNumber][$idSalesperson]['lineAppearance'] == $lineAppearance) {
//            return true;
//        }
//        else { return false; }
//    }
//     // Salesperson was not present today so he is added in array
//    elseif ( array_key_exists( $tableNumber, $arraySpecificCase ) && !array_key_exists( $idSalesperson, $arraySpecificCase[$tableNumber] ) ) {
//        $arraySpecificCase[$tableNumber][$idSalesperson] = array('lineAppearance' => $lineAppearance) ;
//        return true;
//    }
//    // Salesperson was not present today (or it his his first time for today) so he is added in array
//    elseif( !array_key_exists( $tableNumber, $arraySpecificCase ) ) {
//        $arraySpecificCase[$tableNumber][$idSalesperson] = array('lineAppearance' => $lineAppearance) ;
//        return true;
//    }
//}

//echo checkArraySpecificCase(0, 1, 50) ? 'true' : '- false -';
//echo checkArraySpecificCase(0, 1, 75) ? 'true' : '- false - ';
//echo checkArraySpecificCase(0, 2, 50) ? 'true' : '- false - ';
//echo checkArraySpecificCase(1, 4, 50) ? 'true' : '- false - ';
//checkArraySpecificCase(0, 2);
//checkArraySpecificCase(2, 2);

//var_dump($arraySpecificCase);

//$arrayEnterprisesAsProviders = $appService->findAllEnterprisesAsProvidersPf(3);
//
//$arraySalespersons = array();
//$counterSalespersons = 0;
//$positionSalespersonListSalesperson = 0;
//
//foreach($arrayEnterprisesAsProviders as $key => $value) {
//    $arraySalespersons[$value->getIdEnterprise()] = $appService->findAllParticipantsAsSalespersonsByProviderAndPf($value->getIdEnterprise(), $appService->findOnePurchasingFair(3)->getIdPurchasingFair());
//    $counterSalespersons += count($arraySalespersons[$value->getIdEnterprise()]);
//}
//
//foreach($arraySalespersons as $provider => $salespersons) {
//    foreach($salespersons as $key => $salesperson) {
//        $salesperson->setManyUnavailabilitiesSp($appService->findParticipantUnavailabilitiesSp($salesperson, $appService->findOnePurchasingFair(3)));
//        $salesperson->setPositionSalespersonListSalesperson($positionSalespersonListSalesperson);
//        ++$positionSalespersonListSalesperson;
//    }
//}
//var_dump($arraySalespersons);


/* Test if the Salesperson has several appointments at the same time - Added 04/06/2018 */
$arraySpecificCase = array();

function checkArraySpecificCase($tableNumber, $idSalesperson, $duration, $posStart) {
    
    // Access variable from the global scope
    global $arraySpecificCase;
    
    // TESTS
    
    if( array_key_exists( $tableNumber, $arraySpecificCase ) && array_key_exists( $idSalesperson, $arraySpecificCase[$tableNumber] ) ) {
                
        for($p = $posStart, $c = 0 ; $c < $duration ; $c++) {
            if($arraySpecificCase[$tableNumber][$idSalesperson][$p + $c] == 'X') {
                return false;
            }
        }
        for($p = $posStart, $c = 0 ; $c < $duration ; $c++) {
            $arraySpecificCase[$tableNumber][$idSalesperson][$p + $c] = 'X';
        }
        return true;
        
    }
    elseif ( array_key_exists( $tableNumber, $arraySpecificCase ) && !array_key_exists( $idSalesperson, $arraySpecificCase[$tableNumber] ) ) {
        $arraySpecificCase[$tableNumber][$idSalesperson] = new SplFixedArray(21);
        for($p = $posStart, $c = 0 ; $c < $duration ; $c++) {
            $arraySpecificCase[$tableNumber][$idSalesperson][$p + $c] = 'X';
        }
        return true;
    }
    elseif( !array_key_exists( $tableNumber, $arraySpecificCase ) ) {
        $arraySpecificCase[$tableNumber][$idSalesperson] = new SplFixedArray(21);
        for($p = $posStart, $c = 0 ; $c < $duration ; $c++) {
            $arraySpecificCase[$tableNumber][$idSalesperson][$p + $c] = 'X';
        }
        return true;
    }
}

echo (checkArraySpecificCase(0,60,3,10)) ? 'true' : 'false';
echo (checkArraySpecificCase(0,60,3,3)) ? 'true' : 'false';
echo (checkArraySpecificCase(0,70,3,0)) ? 'true' : 'false';
echo (checkArraySpecificCase(1,70,3,0)) ? 'true' : 'false';


var_dump($arraySpecificCase);

echo floatval('1.00') * 1 / 0.5;
echo '<hr/>';
echo strval( -1 * 0.5 );
echo '<hr/>';
echo strval( 1 * 0.5 );
echo '<hr/>';
echo strval( 2 * 0.5 );
echo '<hr/>';
echo strval( 2 * 0.5 );
echo '<hr/>';
echo number_format (1,2);

echo '<hr/>';
// Format time in log message
$h = 'h';
$m = 'm';
$valToConvert = number_format( 5 * 0.5, 2); // int * float = float, number_format returns a string

$missingFormatted = explode('.', strval( $valToConvert) );
$missingFormatted = $missingFormatted[0].$h.( $missingFormatted[1] == '50' ? '30' : '00').$m;

echo $missingFormatted;
echo '<hr/>';
echo floatval('2.30') * 1 / 0.5 // DEBUG TERRE DE MARINS ERROR PLANNING PF 4, IN TABLE THERE IS 2.3 INSTEAD OF 2.5 !!
?>

