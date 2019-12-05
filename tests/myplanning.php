<?php
/* Service required + RandomColor */
require_once dirname ( __FILE__ ) . '/../services/AppServiceImpl.class.php';
$appService = AppServiceImpl::getInstance();


//if(!isset($_SESSION)) session_start(); // Start session

// Afficher les erreurs à l'écran
//ini_set('display_errors', 1);
// Enregistrer les erreurs dans un fichier de log
//ini_set('log_errors', 1);
// Nom du fichier qui enregistre les logs (attention aux droits à l'écriture)
//ini_set('error_log', dirname(__file__) . '/errors/log_error_php.txt');

/* To prevent server misconfigured */
date_default_timezone_set('Europe/Paris');

/* French days names */
$arrayDays = array('DIMANCHE','LUNDI','MARDI','MERCREDI','JEUDI','VENDREDI','SAMEDI');

/* French months names */
$arrayMonths = array('JANVIER','FEVRIER','MARS','AVRIL','MAI','JUIN','JUILLET','AOUT','SEPTEMBRE','OCTOBRE','NOVEMBRE','DECEMBRE');

/* Get the PurchasingFair concerned */
$pfConcerned   = $appService->findOnePurchasingFair(2); // Change number w/ $_SESSION
/* PurchasingFair start/end datetimes + lunchBreak */
$startDatetime = DateTime::createFromFormat('Y-m-d H:i:s', $pfConcerned->getStartDatetime());
$endDatetime   = DateTime::createFromFormat('Y-m-d H:i:s', $pfConcerned->getEndDatetime());
$lunchBreak    = $pfConcerned->getLunchBreak();

$lunchBreakDec = ($lunchBreak == '12:00:00') ? 12 : ( ($lunchBreak == '12:30:00') ? 12.5 : 13 );
$arrayDatetimes = array();
        
// Since PHP 5.2.2 you can use <, >, == ; Here not <= because we consider time !
// http://php.net/manual/fr/function.date.php // Days of week start at 0, Months at 1
while($startDatetime < $endDatetime) { 
    
    $arrayDatetimes[] = $startDatetime;
    // http://php.net/manual/fr/datetime.add.php
    // http://php.net/manual/fr/dateinterval.construct.php
    // Clone because variables hold references to objects, not the objects themselves. 
    // So assignment just gets you more variables pointing to the same object, not multiple copies of the object.
    $startDatetime = clone $startDatetime; 
    $startDatetime->add( new DateInterval('P1D') ); // P = period, D = Day
}

$arrayEnterprisesAsProviders = $appService->findAllEnterprisesAsProvidersPf(2); // All the providers who participate in the Purchasing Fair

$arraySalespersons = array();
$counterSalespersons = 0;
foreach($arrayEnterprisesAsProviders as $key => $value) {
    $arraySalespersons[$value->getIdEnterprise()] = $appService->findAllParticipantsAsSalespersonsByProvider($value->getIdEnterprise());
    $counterSalespersons += count($arraySalespersons[$value->getIdEnterprise()]);
}

$arrayStores = $appService->findAllEnterprisesAsStoresPf(2);
// var_dump($arrayStores);
//$arrayParticipationsStores = array(); // MAGASINS
//foreach($arrayStores as $store) { // MAGASINS
//    $arrayParticipationsStores = array_merge($arrayParticipationsStores, $appService->findAllParticipationsByEnterpriseAndPurchasingFair($store, $pfConcerned));
//}

$arrayPfUnavailabilities = $appService->findPurchasingFairUnavailabilities($pfConcerned);
$arrayPfUnavailabilitiesSp = $appService->findPurchasingFairUnavailabilitiesSp($pfConcerned);

$colors = $appService->generateNColors(count($arraySalespersons));

// Step1
$arrayMultiDim = new SplFixedArray(21); // Plages horaires

// Step2
foreach($arrayMultiDim as $key => $value) {
    $arrayMultiDim[$key] = new SplFixedArray(5); // Commerciaux @TODO ne prendre que ceuxw qui participent au salon
}

foreach($arrayMultiDim as $key1 => $value1) {
    foreach($value1 as $key2 => $value2) {
        $arrayMultiDim[$key1][$key2] = "a";
    }
}

var_dump($arrayMultiDim);
//echo '<pre>';
//echo $counterSalespersons;
//echo '</pre>';


?>
<link href="../css/bootstrap.min.css" rel="stylesheet">
<link href="../font-awesome/css/font-awesome.css" rel="stylesheet">

<h4 class="text-center alert alert-info">
    <i class="fa fa-info-circle" aria-hidden="true"></i> Salon N°<?php echo $pfConcerned->getIdPurchasingFair(); ?> : <?php echo $pfConcerned->getNamePurchasingFair(); ?> 
    du <?php echo $appService->myFrenchDatetime($pfConcerned->getStartDatetime()); ?> au <?php echo $appService->myFrenchDatetime($pfConcerned->getEndDatetime()); ?>

</h4>
<div class="row" style="padding: 5px">
    <div class="col-md-12">

<?php 
$counterTables = 1;

foreach($arrayDatetimes as $key => $datetime) { 
    $dayName   = $arrayDays[$datetime->format('w')];
    $dayNumber = $datetime->format('d');
    $monthName = $arrayMonths[$datetime->format('m') - 1];
    $year      = $datetime->format('Y');
    
    if($dayName != 'SAMEDI' && $dayName != 'DIMANCHE') {
?>
    

<h1 class="text-center">
    <i class="fa fa-calendar" aria-hidden="true"></i> <?php echo $dayName. ' ' .$dayNumber. ' ' .$monthName. ' ' .$year; ?>
    <i class="fa fa-file-excel-o" style="cursor:pointer;color:#217346" aria-hidden="true" onclick="exportToExcel('table_<?php echo $counterTables; ?>');"></i>
</h1>
<div class=" table-responsive">
    <table class="table table-hover table-bordered table-condensed table2excel" id="table_<?php echo $counterTables; ?>" data-tableName="Test Table <?php echo $counterTables; ?>">
        <thead>
            <tr>
                <th style="vertical-align:middle;text-align:center;">FOURNISSEUR</th>
                <th style="vertical-align:middle;text-align:center;">COMMERCIAL</th>
                <th>08H00 - 08H30</th>
                <th>08H30 - 09H00</th>
                <th>09H00 - 09h30</th>
                <th>09H30 - 10H00</th>
                <th>10H00 - 10H30</th>
                <th>10H30 - 11H00</th>
                <th>11H00 - 11H30</th>
                <th>11H30 - 12H00</th>
                
                <?php 
                if($lunchBreakDec == 12) {
                ?>
                <th style="background-color:#f1f3f6;">12H00 - 13H00</th>
                <th>13H00 - 13H30</th>
                <th>13H30 - 14H00</th>
                <?php
                }
                if($lunchBreakDec == 12.5) {
                ?>
                <th>12H00 - 12H30</th>
                <th style="background-color:#f1f3f6;">12H30 - 13H30</th>
                <th>13H30 - 14H00</th>                
                <?php
                }
                if($lunchBreakDec == 13) {
                ?>
                <th>12H00 - 12H30</th>
                <th>12H30 - 13H00</th>
                <th style="background-color:#f1f3f6;">13H00 - 14H00</th>
                <?php
                }
                ?>
                <th>14H00 - 14H30</th>
                <th>14H30 - 15H00</th>
                <th>15H00 - 15H30</th>
                <th>15H30 - 16H00</th>
                <th>16H00 - 16H30</th>
                <th>16H30 - 17H00</th>
                <th>17H00 - 17H30</th>
                <th>17H30 - 18H00</th>
                <th>18H00 - 18H30</th>
                <th>18H30 - 19H00</th>
            </tr>
        </thead>
        <tbdody>
            <?php
            $counterColor = 0;

            foreach($arraySalespersons as $provider => $salespersons) {
                
                $arraySpWithUnavByEntAndPf = $appService->findSpWithUnavByEntAndPf($provider, 2);
                $providerName = $appService->findOneEnterprise($provider)->getName();

                foreach($salespersons as $key => $salesperson) {
                                        
                    $caseStartDatetime = clone $datetime;
                    $caseStartDatetime->setTime(8,0,0);
                    $caseEndDatetime   = clone $datetime;
                    $caseEndDatetime->setTime(8,30,0);

                    echo '<tr>';
                    echo '<td style="background-color:'.$colors[$counterColor].';vertical-align:middle;text-align:center;">'.$providerName.'</td>';
                    echo '<td style="background-color:'.$colors[$counterColor].';vertical-align:middle;text-align:center;">'.$salesperson->getCivilitySmall().' '.$salesperson->getSurname().'</td>';

                    for($i = 0 ; $i < 21 ; $i++) {
                        
                        $td = '<td style="';
                        
                        $lunchColor = '';
                        $unavColor  = 'background-color:#f1f3f6;';
                        
                        if( $lunchBreakDec == 12 && $i == 8 ) { 
                            $lunchColor = 'background-color:#f1f3f6;';
                            $caseEndDatetime->add(new DateInterval('PT30M'));
                            
                        }
                        elseif( $lunchBreakDec == 12.5 && $i == 9 ) {
                            $lunchColor = 'background-color:#f1f3f6;';
                            $caseEndDatetime->add(new DateInterval('PT30M'));
                        }
                        elseif( $lunchBreakDec == 13 && $i == 10 ) {
                            $lunchColor = 'background-color:#f1f3f6;';
                            $caseEndDatetime->add(new DateInterval('PT30M'));
                        }
//                        else { $lunchColor = ''; }
                        
                        $tdContent = '';

                        if( isset($arraySpWithUnavByEntAndPf[$salesperson->getIdParticipant()] ) && !empty($arraySpWithUnavByEntAndPf[$salesperson->getIdParticipant()]) ) {
                            foreach($arraySpWithUnavByEntAndPf[$salesperson->getIdParticipant()] as $key => $unavs) {
                                if ( ( $unavs[0] <= $caseStartDatetime ) && ( $caseEndDatetime <= $unavs[1]) ) {
                                    $tdContent = 'IND'; break 1;
                                }
                            }
                        }
//                        else { $tdContent = $i; }
                        
                        $td .= ( $lunchColor != 'background-color:#f1f3f6;' && $tdContent == 'IND') ? $unavColor : $lunchColor;
                        
                        $td .= ';vertical-align:middle;text-align:center">';
                        
                        $td .= $tdContent;
                        
                        $td .= '</td>';
                        echo $td;
                        
                        $caseStartDatetimeTMP = clone $caseEndDatetime;
                        unset($caseStartDatetime);
                        $caseStartDatetime = clone $caseStartDatetimeTMP;
                        
                        $caseEndDatetime->add(new DateInterval('PT30M')); // P Period T Time
                    }
                    echo '</tr>';                
                }
                $counterColor++;
                echo '<tr>';
                for($j = 0 ; $j < 23 ; $j++) {
                    if($lunchBreakDec == 12 && $j == 10) { echo '<td style="background-color:#f1f3f6;"></td>'; }
                    elseif($lunchBreakDec == 12.5 && $j == 11) { echo '<td style="background-color:#f1f3f6;"></td>'; }
                    elseif($lunchBreakDec == 13 && $j == 12) { echo '<td style="background-color:#f1f3f6;"></td>'; }
                    else { echo '<td></td>'; }
                }
                echo '<tr>';
            }
            ?>
        </tbdody>

    </table>
</div>

<?php } $counterTables++; } ?>

    </div></div>

<script src="../js/jquery-3.1.1.min.js"></script>
<script src="../js/plugins/table2excel/jquery.table2excel.js"></script>

<script>
//			$(function() {
//                            
//                                var nbPlannings = <?php //echo $counterTables; ?>;
//                                console.log("Nb calendriers = " + nbPlannings);
//                                for( var i = 1 ; i <= nbPlannings ; i++) {
//                                    console.log(i);

function exportToExcel(idTable) {
    $("#" + idTable).table2excel({
        exclude: ".noExl",
        name: "Excel Document Name",
        filename: "monFichier" + new Date().toISOString().replace(/[\-\:\.]/g, ""),
        fileext: ".xls",
        exclude_img: true,
        exclude_links: true,
        exclude_inputs: true
    });
}
//                                }

//			});
</script>