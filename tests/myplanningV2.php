<?php

/* To see all details when var_dump() function used */
ini_set('xdebug.var_display_max_depth', -1);
ini_set('xdebug.var_display_max_children', -1);
ini_set('xdebug.var_display_max_data', -1);

/* Service required + RandomColor */
require_once dirname ( __FILE__ ) . '/../services/AppServiceImpl.class.php';
require_once dirname ( __FILE__ ) . '/../domain/PlanningDay.class.php';
require_once dirname ( __FILE__ ) . '/../domain/TimeSlot.class.php';
require_once dirname ( __FILE__ ) . '/../services/AppServiceImpl.class.php';

$appService = AppServiceImpl::getInstance();

/* To prevent server misconfigured */
date_default_timezone_set('Europe/Paris');

/* French days names */
$arrayDays = array('DIMANCHE','LUNDI','MARDI','MERCREDI','JEUDI','VENDREDI','SAMEDI');

/* French months names */
$arrayMonths = array('JANVIER','FEVRIER','MARS','AVRIL','MAI','JUIN','JUILLET','AOUT','SEPTEMBRE','OCTOBRE','NOVEMBRE','DECEMBRE');

/* Get the PurchasingFair concerned */
$pfConcerned   = $appService->findOnePurchasingFair(3); // Change number w/ $_SESSION
/* PurchasingFair start/end datetimes + lunchBreak */
$startDatetime = DateTime::createFromFormat('Y-m-d H:i:s', $pfConcerned->getStartDatetime());
$endDatetime   = DateTime::createFromFormat('Y-m-d H:i:s', $pfConcerned->getEndDatetime());
$lunchBreak    = $pfConcerned->getLunchBreak();

$lunchBreakDec = ($lunchBreak == '12:00:00') ? 12 : ( ($lunchBreak == '12:30:00') ? 12.5 : 13 );

$counterArrayPlanningDays = 0;
        
// Since PHP 5.2.2 you can use <, >, == ; Here not <= because we consider time !
// http://php.net/manual/fr/function.date.php // Days of week start at 0, Months at 1
while($startDatetime < $endDatetime) { 
    
    if($arrayDays[$startDatetime->format('w')] != 'SAMEDI' && $arrayDays[$startDatetime->format('w')] != 'DIMANCHE') {
        $startAt = clone $startDatetime;
        $endAt = clone $startDatetime;
        $endAt->setTime(19,0,0);
        
        $arrayPlanningDays[] = new PlanningDay(++$counterArrayPlanningDays, $startAt, $endAt, array(), array());
    }
    // http://php.net/manual/fr/datetime.add.php
    // http://php.net/manual/fr/dateinterval.construct.php
    // Clone because variables hold references to objects, not the objects themselves. 
    // So assignment just gets you more variables pointing to the same object, not multiple copies of the object.
//    $startDatetime = clone $startDatetime; 
    $startDatetime->add( new DateInterval('P1D') ); // P = period, D = Day
}


$arrayEnterprisesAsProviders = $appService->findAllEnterprisesAsProvidersPf(3); // All the providers who participate at Purchasing Fair
$arrayEnterprisesAsStores = $appService->findAllEnterprisesAsStoresPf(3); // All the stores who participate at Purchasing Fair

$counterStores = count($arrayEnterprisesAsStores);

//die(var_dump($nbStores));

$eTest = $appService->findOneEnterprise(1);
$aTest = $appService->findRequirementFilteredDuoWithTotNumberHoursAndUnavs($eTest, $appService->findOnePurchasingFair(3));

//die(var_dump($aTest));

$arraySalespersons = array();
$counterSalespersons = 0;
$positionSalespersonListSalesperson = 0;
foreach($arrayEnterprisesAsProviders as $key => $value) {
    $arraySalespersons[$value->getIdEnterprise()] = $appService->findAllParticipantsAsSalespersonsByProviderAndPf($value->getIdEnterprise(), 3);
    $counterSalespersons += count($arraySalespersons[$value->getIdEnterprise()]);
}

foreach($arraySalespersons as $provider => $salespersons) {
    foreach($salespersons as $key => $salesperson) {
        $salesperson->setManyUnavailabilitiesSp($appService->findParticipantUnavailabilitiesSp($salesperson, $appService->findOnePurchasingFair(3)));
        $salesperson->setPositionSalespersonListSalesperson($positionSalespersonListSalesperson);
        ++$positionSalespersonListSalesperson;
    }
}

//die(var_dump($arrayEnterprisesAsStores));


foreach($arrayPlanningDays as $key => $planningDay) {
    $timeSlotStartDatetime = $planningDay->getStartDatetime();
//    $timeSlotEndDatetime->setTime(8,0,0);
    $sizeArrayTimeSlots = 21;
    $arrayTimeSlots = new SplFixedArray($sizeArrayTimeSlots);
    for( $i = 0 ; $i < $sizeArrayTimeSlots ; $i++) { // We start at 8:00am (default)
        $timeSlotEndDatetime   = clone $timeSlotStartDatetime;
        $timeSlotEndDatetime->add(new DateInterval('PT30M'));
        
        if( ($lunchBreakDec == 12 && $i == 8) || ($lunchBreakDec == 12.5 && $i == 9) || ($lunchBreakDec == 13 && $i == 10) ) {
            $timeSlotEndDatetime->add(new DateInterval('PT30M'));
            $isLunchBreak = true;
        }
        else { $isLunchBreak = false; }
       
        $arrayTimeSlots[$i] = new TimeSlot($i, $timeSlotStartDatetime, $timeSlotEndDatetime, $isLunchBreak);
        
        $timeSlotStartDatetimeTMP = clone $timeSlotEndDatetime;
        unset($timeSlotStartDatetime);
        $timeSlotStartDatetime = clone $timeSlotStartDatetimeTMP;

//        $timeSlotEndDatetime->add(new DateInterval('PT30M')); // P Period T Time
    }
    $planningDay->setArrayTimeSlots($arrayTimeSlots);
    $planningDay->setArraySalespersons($arraySalespersons);
}



$colors = $appService->generateNColors($counterSalespersons);

//var_dump($counterArrayPlanningDays);
//die();

function createArrayMultiDim($counterSalespersons, $sizeArrayTimeSlots) {
    
// Step1 - Creating array multi dim [n-lines (salespersons), n-columns (time slots) ]
    $arrayMultiDim = new SplFixedArray($counterSalespersons); // Plages horaires

    // Step2
    foreach($arrayMultiDim as $key => $value) {
        $arrayMultiDim[$key] = new SplFixedArray($sizeArrayTimeSlots); // Commerciaux @TODO ne prendre que ceuxw qui participent au salon
    }
    
    return $arrayMultiDim;
    
    // Default null
}



//foreach($arrayMultiDim as $key1 => $value1) {
//    foreach($value1 as $key2 => $value2) {
//        $arrayMultiDim[$key1][$key2] = "a";
//    }
//}

$arrayMultiDimPf = array();
for( $z = 0 ; $z < $counterArrayPlanningDays ; $z++ ) { // n-Pf x n-Salespersons x n-TimeSlots
    $arrayMultiDimPf[$z] = createArrayMultiDim($counterSalespersons, $sizeArrayTimeSlots);
}

$arrayMultiDimStores = array();
for( $z = 0 ; $z < $counterArrayPlanningDays ; $z++ ) { // n-Pf x n-Stores x n-TimeSlots
    $arrayMultiDimStores[$z] = createArrayMultiDim($counterSalespersons, $sizeArrayTimeSlots);
}

//var_dump($arrayTimeSlots); die();

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
$counterTables = 0;

foreach($arrayPlanningDays as $key => $planningDay) { 
    $dayName   = $arrayDays[$planningDay->getStartDatetime()->format('w')];
    $dayNumber = $planningDay->getStartDatetime()->format('d');
    $monthName = $arrayMonths[$planningDay->getStartDatetime()->format('m') - 1];
    $year      = $planningDay->getStartDatetime()->format('Y');
    
    
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
            $spyLines = 0;

            foreach($planningDay->getArraySalespersons() as $provider => $salespersons) {
                
                $providerName = $appService->findOneEnterprise($provider)->getName();
                
                foreach($salespersons as $key => $salesperson) { // provider => array Salespersons
                                        
                    echo '<tr>';
                    echo '<td style="background-color:'.$colors[$counterColor].';vertical-align:middle;text-align:center;">'.$providerName.'</td>';
                    echo '<td style="background-color:'.$colors[$counterColor].';vertical-align:middle;text-align:center;">'.$salesperson->getCivilitySmall().' '.$salesperson->getSurname().'</td>';

                    foreach($planningDay->getArrayTimeSlots() as $key => $timeSlot) {

                        $td = '<td style="';
                        
                        $lunchColor = '';
                        $unavColor  = 'background-color:#f1f3f6;';
                        
                        if($timeSlot->getIsLunchBreak()) { $lunchColor = 'background-color:#f1f3f6;'; }
             
                        $tdContent = '';
                        
                        foreach($salesperson->getManyUnavailabilitiesSp() as $key => $unavailabilitySp) {
                            if(DateTime::createFromFormat('Y-m-d H:i:s',$unavailabilitySp->getStartDatetime()) <= $timeSlot->getStartDatetime() && 
                                    DateTime::createFromFormat('Y-m-d H:i:s',$unavailabilitySp->getEndDatetime()) >= $timeSlot->getEndDatetime() ) {
                                $tdContent = 'IND'; 
                                $arrayMultiDimPf[$counterTables][$spyLines][$timeSlot->getIdSlot()] = 'IND';
                                $arrayMultiDimStores[$counterTables][$spyLines][$timeSlot->getIdSlot()] = $appService->findAllStoresNotAvailableForTimeSlotAndPf($appService->findOnePurchasingFair(3), $timeSlot->getStartDatetime(), $timeSlot->getEndDatetime());
                                break 1;
                            }
                        }
                        $td .= ( $lunchColor != 'background-color:#f1f3f6;' && $tdContent == 'IND') ? $unavColor : $lunchColor;
                        
                        $td .= ';vertical-align:middle;text-align:center">';
                        
                        $td .= $tdContent;
                        
                        $td .= '</td>';
                        
                        echo $td;
                    }
                    echo '</tr>';
                    $spyLines++;
                }
                // Separation
                $counterColor++;
                echo '<tr>';
                for($j = 0 ; $j < $sizeArrayTimeSlots ; $j++) {
                    if($lunchBreakDec == 12 && $j == 10) { echo '<td style="background-color:#f1f3f6;"></td>'; }
                    elseif($lunchBreakDec == 12.5 && $j == 11) { echo '<td style="background-color:#f1f3f6;"></td>'; }
                    elseif($lunchBreakDec == 13 && $j == 12) { echo '<td style="background-color:#f1f3f6;"></td>'; }
                    else { echo '<td></td>'; }
                }
                echo '<td></td><td></td>';
                echo '<tr>';
            }
            ?>
        </tbdody>

    </table><?php 
//    echo 'Planning Number.'.$counterTables. '<br/>';
//    foreach($arrayMultiDimPf[$counterTables] as $key => $data) {
//        echo 'Line #'.$key.' : ';
//        foreach($data as $column => $value) {
//            echo '['.$key.']['.$column.'] => '.$value. ' ; ';
//        }
//        echo '<br/>';
//    }
//    var_dump($arrayMultiDimPf[$counterTables]); 
    
//    echo 'Magasins - Planning Number.'.$counterTables. '<br/>';
//    foreach($arrayMultiDimStores[$counterTables] as $key => $data) {
//        echo 'Line #'.$key.' : ';
//        foreach($data as $column => $arrayIds) {
//            
//            $sizeArrayIds = count($arrayIds);
//            $counterArrayIds = 0;
//            $strContent = '';
//            if(!is_null($arrayIds)) {
//                foreach($arrayIds as $idStore) {
//                    $counterArrayIds++;
//                    $comma = ($counterArrayIds < $sizeArrayIds) ? ',' : '';
//                    $strContent .= $idStore.$comma;
//                }
//            }
//            echo '['.$key.']['.$column.'] => '.$strContent. ' ; ';
//        }
//        echo '<br/>';
//    }
    ?>
</div>

<?php  $counterTables++; } ?>

    </div></div>

<script src="../js/jquery-3.1.1.min.js"></script>
<script src="../js/plugins/table2excel/jquery.table2excel.js"></script>

<script>
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
</script>

<?php die(var_dump($arrayPlanningDays)); ?>