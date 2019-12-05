<?php
/* Service required + RandomColor BEFORE SESSION !! */
require_once dirname ( __FILE__ ) . '/../services/AppServiceImpl.class.php';
require_once dirname ( __FILE__ ) . '/../domain/PlanningDay.class.php';
require_once dirname ( __FILE__ ) . '/../domain/TimeSlot.class.php';

if(!isset($_SESSION)) session_start(); // Start session
header( 'content-type: text/html; charset=utf-8' ); // Specifies to the server to return UTF-8 - put in prod

/* To see all details when var_dump() function used */
ini_set('xdebug.var_display_max_depth', -1);
ini_set('xdebug.var_display_max_children', -1);
ini_set('xdebug.var_display_max_data', -1);

// http://php.net/manual/en/function.set-time-limit.php - Limits the maximum execution time
// The maximum execution time is in seconds. If set to zero, no time limit is imposed.
set_time_limit(0); // PROD SCA OUEST = 600 s / 60 = 10 min, uninteresting value configured

ob_start();

if( file_exists('./../tmp/tmp_planning_pf'.$_SESSION['purchasingFairConcerned']->getIdPurchasingFair().'.html') ) {
    require_once('./../tmp/tmp_planning_pf'.$_SESSION['purchasingFairConcerned']->getIdPurchasingFair().'.html');
}
else {
?>

<!DOCTYPE html>
<html>
<head>
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta charset="utf-8">


<?php
/* Object AppServiceImpl */
$appService = AppServiceImpl::getInstance();

/* To prevent server misconfigured */
date_default_timezone_set('Europe/Paris');

/* French days names */
$arrayDays = array('DIMANCHE','LUNDI','MARDI','MERCREDI','JEUDI','VENDREDI','SAMEDI');

/* French months names */
$arrayMonths = array('JANVIER','FEVRIER','MARS','AVRIL','MAI','JUIN','JUILLET','AOUT','SEPTEMBRE','OCTOBRE','NOVEMBRE','DECEMBRE');

/* Get the PurchasingFair concerned */
//$pfConcerned   = $appService->findOnePurchasingFair(3); // @TODO Change number w/ $_SESSION
$pfConcerned = $_SESSION['purchasingFairConcerned'];

/* PurchasingFair start/end datetimes + lunchBreak */
$startDatetime = DateTime::createFromFormat('Y-m-d H:i:s', $pfConcerned->getStartDatetime());
$endDatetime   = DateTime::createFromFormat('Y-m-d H:i:s', $pfConcerned->getEndDatetime());
$lunchBreak    = $pfConcerned->getLunchBreak();
$lunchBreakDec = ($lunchBreak == '12:00:00') ? 12 : ( ($lunchBreak == '12:30:00') ? 12.5 : 13 );

// 10 hours of appointments per day, which vary according to the lunch time
// Variables taken into account when placing Requirements
$maxRequirementAM = ($lunchBreakDec == 12) ? 4 : ( ($lunchBreakDec == 12.5) ? 4.5 : 5 );
$maxRequirementPM = ($lunchBreakDec == 12) ? 6 : ( ($lunchBreakDec == 12.5) ? 5.5 : 5 );

$counterArrayPlanningDays = 0;
        
// Since PHP 5.2.2 you can use <, >, == ; Here not <= because we consider time !
// http://php.net/manual/fr/function.date.php // Days of week start at 0, Months at 1
while($startDatetime < $endDatetime) { 
    
    if($arrayDays[$startDatetime->format('w')] != 'SAMEDI' && $arrayDays[$startDatetime->format('w')] != 'DIMANCHE') {
        
        // Clone because variables hold references to objects, not the objects themselves. 
        // So assignment just gets you more variables pointing to the same object, not multiple copies of the object.
        $startAt = clone $startDatetime;
        $endAt   = clone $startDatetime;
        $endAt->setTime(19,0,0);
        $arrayPlanningDays[] = new PlanningDay(++$counterArrayPlanningDays, $startAt, $endAt, array(), array());
    }
    // http://php.net/manual/fr/datetime.add.php
    // http://php.net/manual/fr/dateinterval.construct.php
    $startDatetime->add( new DateInterval('P1D') ); // P = period, D = Day
}

/* All Requirements for PurchasingFair */
$arrayAllRequirements = $appService->findAllRequirementsByPf($_SESSION['purchasingFairConcerned']->getIdPurchasingFair()); // TEST !

// var_dump(count($arrayAllRequirements)); // TEST !

/*  All the providers/stores who participate at Purchasing Fair */
// OLD VERSION Providers UPDATED ON 08.05.2018 WITH TEXTILE PRIORITY
$arrayEnterprisesAsProviders    = $appService->findAllEnterprisesAsProvidersPf($_SESSION['purchasingFairConcerned']->getIdPurchasingFair());
$arrayEnterprisesAsStores       = $appService->findAllEnterprisesAsStoresPf($_SESSION['purchasingFairConcerned']->getIdPurchasingFair());
$arrayEnterprisesAsStoresAbsent = $appService->findAllEnterprisesAsStoresPfAbsent($_SESSION['purchasingFairConcerned']->getIdPurchasingFair());
//die(var_dump($arrayEnterprisesAsStoresAbsent));
//$arrayEnterprisesAsStores[]    = $appService->findOneEnterprise(10); // ONLY FOR TEST W/ ONE STORE

// Added 03.08.2018
$arrayCheckIfAllStoresPositioned = array();

/* Hom many stores */
$counterStores = count($arrayEnterprisesAsStores);

$arraySalespersons = array();
$counterSalespersons = 0;
$positionSalespersonListSalesperson = 0;

foreach($arrayEnterprisesAsProviders as $key => $value) {
    $arraySalespersons[$value->getIdEnterprise()] = $appService->findAllParticipantsAsSalespersonsByProviderAndPf($value->getIdEnterprise(), $_SESSION['purchasingFairConcerned']->getIdPurchasingFair());
    $counterSalespersons += count($arraySalespersons[$value->getIdEnterprise()]);
}

foreach($arraySalespersons as $provider => $salespersons) {
    foreach($salespersons as $key => $salesperson) {
        $salesperson->setManyUnavailabilitiesSp($appService->findParticipantUnavailabilitiesSp($salesperson, $_SESSION['purchasingFairConcerned']));
        $salesperson->setPositionSalespersonListSalesperson($positionSalespersonListSalesperson);
        ++$positionSalespersonListSalesperson;
    }
}

foreach($arrayPlanningDays as $key => $planningDay) {
    $timeSlotStartDatetime = $planningDay->getStartDatetime();
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
    }
    $planningDay->setArrayTimeSlots($arrayTimeSlots);
    $planningDay->setArraySalespersons($arraySalespersons);
}

$colors       = $appService->generateNColors($counterSalespersons);
$colorsStores = $appService->generateNColors($counterStores);

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

$arrayMultiDimPf = array();
for( $z = 0 ; $z < $counterArrayPlanningDays ; $z++ ) { // n-Pf x n-Salespersons x n-TimeSlots
    $arrayMultiDimPf[$z] = createArrayMultiDim($counterSalespersons, $sizeArrayTimeSlots);
}

$arrayMultiDimStores = array();
for( $z = 0 ; $z < $counterArrayPlanningDays ; $z++ ) { // n-Pf x n-Stores x n-TimeSlots
    $arrayMultiDimStores[$z] = createArrayMultiDim($counterSalespersons, $sizeArrayTimeSlots);
}

$offsetDuration = 0;

function checkBeforePlacement($arrayMultiDim, $planningNumber, $line, $duration, $requirement, $arrayMultiDimStores) {
    $posStart = -1;
    for( $z = 0 ; $z < 21 ; $z++) { // For one position in time slots
        $verifyWithDurationLine   = 0;
        $verifyWithDurationColumn = 0;
        for( $t = 0 ; $t < $duration ; $t++) { // check if duration OK for the next time slots
            if( ($z + $t) < 21 
                    && is_null($arrayMultiDim[$planningNumber][$line][$z + $t]) 
                    && ( is_null($arrayMultiDimStores[$planningNumber][$line][($z + $t)])
                    || !in_array($requirement->getOneStore()->getIdEnterprise(), $arrayMultiDimStores[$planningNumber][$line][($z + $t)]) )
               ) {
               $verifyWithDurationLine++;
               
               for($v = ($line-1) ; $v >= 0 ; $v--) { // Column check
                 if( !is_null($arrayMultiDim[$planningNumber][$v][$z + $t])
                         && $arrayMultiDim[$planningNumber][$v][$z + $t] instanceof Enterprise
                         && $arrayMultiDim[$planningNumber][$v][$z + $t] == $requirement->getOneStore()
                    ) { 
                     $verifyWithDurationColumn++;
                    }
               }
            }
        }
        if($verifyWithDurationLine == $duration && $verifyWithDurationColumn == 0) {  // OK for the line && NOK FOR the column
            return $z;
        }
    }
    return $posStart;
}
function makePlacement($arrayMultiDim, $planningNumber, $line, $duration, $store, $posStart) {
    for( $w = 0 ; $w < $duration ; $w++,$posStart++) {
        $arrayMultiDim[$planningNumber][$line][$posStart] = $store;
    }
}

/* Test if the Salesperson has several appointments at the same time - Added 05/06/2018 */
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

function storePlacement($arrayMultiDim, $counterTables, $salespersonNumberList, Requirement $requirement, $arrayMultiDimStores, $idSalesperson = -1) {
    $duration = floatval($requirement->getNumberOfHours()) * 1 / 0.5; // Cross product to get number of time slots required
    $posStart = checkBeforePlacement($arrayMultiDim, $counterTables, $salespersonNumberList, $duration, $requirement, $arrayMultiDimStores);
    
    global $arrayAllRequirements; // Global variable
    $requirementOK = false;

    if( $posStart != -1 && checkArraySpecificCase( $counterTables, $idSalesperson, $duration, $posStart ) ) { 
        makePlacement($arrayMultiDim, $counterTables, $salespersonNumberList, $duration, $requirement->getOneStore(), $posStart);
        unset($arrayAllRequirements[$requirement->getIdRequirement()]); // TEST !
        $requirementOK = true;
    }
    else {
        for( $tableNumber = ($counterTables+1) ; $tableNumber < 10 ; $tableNumber++ ) {
           $posStart = checkBeforePlacement($arrayMultiDim, $tableNumber, $salespersonNumberList, $duration, $requirement, $arrayMultiDimStores);
            if( $posStart != -1 && checkArraySpecificCase( $tableNumber, $idSalesperson, $duration, $posStart ) ) { // Not $counterTables !!!
                makePlacement($arrayMultiDim, $tableNumber, $salespersonNumberList, $duration, $requirement->getOneStore(), $posStart);
                unset($arrayAllRequirements[$requirement->getIdRequirement()]); // TEST !
                $requirementOK = true;
                break 1;
            }
        }
    }
    return $requirementOK;
}
?>
<link href="../css/bootstrap.min.css" rel="stylesheet">
<link href="../font-awesome/css/font-awesome.css" rel="stylesheet">

<!-- Toastr style -->
<link href="../css/plugins/toastr/toastr.min.css" rel="stylesheet">
<style>
.event { height:100%!important;width:100%!important;display:block; }
/* https://stackoverflow.com/questions/3632532/how-can-you-implement-a-grabbing-cursor-icon-in-chrome/25321042 */
.event:hover { cursor: -webkit-grab; cursor: grab; }

/* Loader gif src => http://smallenvelop.com/display-loading-icon-page-loads-completely/ */
/* Style used src => https://css-tricks.com/snippets/css/absolute-center-vertical-horizontal-an-image/ */
html, body, #loader {
   height:100%;
   width: 100%;
   margin: 0;
   padding: 0;
   border: 0;
}
#loader td {
   vertical-align: middle;
   text-align: center;
}

/* Scroll To Top Button */
#btnTop {
    display:none; /* Hidden by default */
    position:fixed; /* Fixed/sticky position */
    bottom:20px; /* Place the button at the bottom of the page */
    right:30px; /* Place the button 30px from the right */
    z-index:99; /* Make sure it does not overlap */
    border:none; /* Remove borders */
    outline:none; /* Remove outline */
    background-color:#0b70b5; /* Set a background color */
    color:#ffffff; /* Text color */
    cursor:pointer; /* Add a mouse pointer on hover */
    border-radius:50%; /* Rounded corners */
    font-size:23px; /* Increase font size */
}

#btnTop:hover {background-color:#ed8b18;color:#000000;}
.dashedStyleMoved {border:3px dashed #ff0000!important;}
.dashedStyleNewPosition {border:3px dashed #00ff40!important;}
.dashedStyleChangeSaved {border:3px dashed #000000!important;}

/* Added 03.08.2018 */
.table-borderless td, .table-borderless th { border: 0!important; }
</style>

</head>
<body style="margin:0;">

<table id="loader">
    <tr>
        <td><img src="../img/loader/loader_icons_set1/128x/Preloader_3.gif" alt="Chargement en cours..." /></td>
    </tr>
</table>

<div style="display:none;" id="divContentPage">

    <!-- https://www.w3schools.com/howto/howto_js_scroll_to_top.asp -->
    <!-- https://www.w3schools.com/howto/howto_css_round_buttons.asp -->
    <button onclick="topFunction()" id="btnTop" title="Haut de page"><i class="fa fa-arrow-circle-up" aria-hidden="true"></i></button>

<?php
$counterTables = 0;

foreach($arrayPlanningDays as $key => $planningDay) { 
    $dayName   = $arrayDays[$planningDay->getStartDatetime()->format('w')];
    $dayNumber = $planningDay->getStartDatetime()->format('d');
    $monthName = $arrayMonths[$planningDay->getStartDatetime()->format('m') - 1];
    $year      = $planningDay->getStartDatetime()->format('Y');

    $spyLines = 0;

        foreach($planningDay->getArraySalespersons() as $provider => $salespersons) {

            $providerName = $appService->findOneEnterprise($provider)->getName();

            foreach($salespersons as $key => $salesperson) { // provider => array Salespersons

                foreach($planningDay->getArrayTimeSlots() as $key => $timeSlot) {

                    if($timeSlot->getIsLunchBreak()) {
                        $arrayMultiDimPf[$counterTables][$spyLines][$timeSlot->getIdSlot()] = '<i class="fa fa-cutlery" aria-hidden="true"></i>';
                    }
                    else {
                        foreach($salesperson->getManyUnavailabilitiesSp() as $key => $unavailabilitySp) {
                            if(DateTime::createFromFormat('Y-m-d H:i:s',$unavailabilitySp->getStartDatetime()) <= $timeSlot->getStartDatetime() && 
                                    DateTime::createFromFormat('Y-m-d H:i:s',$unavailabilitySp->getEndDatetime()) >= $timeSlot->getEndDatetime() ) {
                                $arrayMultiDimPf[$counterTables][$spyLines][$timeSlot->getIdSlot()] = 'IND';
                                break 1;
                            }
                        }
                    } // Updated 28.06.2018 - Previous version leave splfixedArray values as null value, it was not ok (placement errors)
                    $arrayMultiDimStores[$counterTables][$spyLines][$timeSlot->getIdSlot()] = $appService->findAllStoresNotAvailableForTimeSlotAndPf($_SESSION['purchasingFairConcerned'], $timeSlot->getStartDatetime(), $timeSlot->getEndDatetime());
                }
                $spyLines++;
            }
        }
            
    $counterTables++; 
} 
?>


<?php
// Expérimentation placement Magasins
//$arrayMultiDimPf[0][0][0] = '-';
$arrayExpTest = array();
//$arrayThree = array();
//for( $iterator = 0 ; $iterator < 10 ; $iterator++) {
//    $arrayThree[] = $arrayEnterprisesAsStores[$iterator];
//}
$positionStoreListStore = 0;
foreach($arrayEnterprisesAsStores as $key => $storeToIterate) {
    $storeToIterate->setPositionStoreListStore($positionStoreListStore);
    $arrayExpTest[] = $appService->findRequirementFilteredDuoWithTotNumberHoursAndUnavs($storeToIterate, $_SESSION['purchasingFairConcerned']);
    
    // Added 03.08.2018
    $arrayCheckIfAllStoresPositioned[$storeToIterate->getIdEnterprise()]['positionStoreListStore'] = $positionStoreListStore;
    $arrayCheckIfAllStoresPositioned[$storeToIterate->getIdEnterprise()]['atLeastOneAppointment'] = false;
    
    $positionStoreListStore++;
}

//var_dump($arrayExpTest);

// Log message for missing appointments
$missingRequirements = '';
$missingRequirements .= '<strong>Liste des besoins en heures nok :</strong>'."\n";

foreach($arrayExpTest as $key => $value) { // Each key of $arrayExpTest = content for one Store !
    $counterTables = 0;

    foreach($arrayPlanningDays as $key => $planningDay) { 

        foreach($planningDay->getArraySalespersons() as $provider => $salespersons) {

            $providerName = $appService->findOneEnterprise($provider)->getName();

            // Si fournisseur dans liste des besoins en heures magasin
//                if($requirement->getOneProvider()->getName() == $providerName) {
//                    echo 'Le Magasin '.$eTest->getName().' veut voir le Fournisseur '.$providerName.' pendant '.$requirement->getNumberOfHours().' heures<br/>';
            
            $arrayReqNOK = array();
            
            foreach($salespersons as $key => $salesperson) { // provider => array Salespersons
                foreach($value['requirements'] as $key => $requirement) {

                    // Can be null - empty
                    $assFetched = $appService->findOneAssignmentSpStoreQuatro($requirement->getOneStore()->getIdEnterprise(), $provider, $_SESSION['purchasingFairConcerned']->getIdPurchasingFair());
                    // Updated 12.07.2018 - It is possible to have multiple assignment for one store ! STAND BY
                    // We must have an array here, not a simple object
                    // $assFetchedIdsSalespersons = array();
                    // foreach($assFetched as $key => $ass) {
                            // $assFetchedIdsSalespersons[] = $ass->getOneParticipant()->getIdParticipant();
                    // }
					
                        if(!is_null($assFetched) && $requirement->getOneProvider()->getName() == $providerName && $assFetched->getOneParticipant()->getIdParticipant() == $salesperson->getIdParticipant()) {
                    // if(!empty($assFetched) && $requirement->getOneProvider()->getName() == $providerName && in_array($salesperson->getIdParticipant(), $assFetchedIdsSalespersons) ) {
                        $checkStorePlacement = storePlacement($arrayMultiDimPf, $counterTables, $salesperson->getPositionSalespersonListSalesperson(), $requirement, $arrayMultiDimStores, $salesperson->getIdParticipant());
                        
                        
                        // Added 02.08.2018 - Better store placement
                        if(!$checkStorePlacement) {
                            
//                            var_dump($requirement);
                                                        
                            // MySQL DECIMAL type is considered as (string) in php
                            $durationReqDecimal = floatval($requirement->getNumberOfHours()) * 1 / 0.5; // Cross product to get number of time slots required
                            
                            // Splitting requirements in 30 minutes time slots
                            $requirement->setNumberOfHours('00.50');
                            $counterReqExploded = 0;
                            
                            // The algorithm tries to place these splitted elements
                            for( $d = 0 ; $d < $durationReqDecimal ; $d++) {
                                $checkStorePlacement = storePlacement($arrayMultiDimPf, $counterTables, $salesperson->getPositionSalespersonListSalesperson(), $requirement, $arrayMultiDimStores, $salesperson->getIdParticipant());
                                if($checkStorePlacement) { ++$counterReqExploded; }
                            }
                            
                            // Test if all requirements are OK for the Store
                            // If it is not the case, a text is saved in the log file with the number of hours and minutes missing for the Store
                            if($counterReqExploded != $durationReqDecimal) {
                                
                                $missingReqStore      = $requirement->getOneStore()->getName();
                                $missingReqProvider   = $requirement->getOneProvider()->getName();
                                $missingRequirements .= '(#'.$requirement->getIdrequirement().') ';
                                $missingRequirements .= 'Il manque pour le Magasin '.$missingReqStore.' ';
                                
                                // Format time in log message
                                $h = 'h';
                                $m = 'm';
                                 // int * float = float, number_format returns a string, always shows 2 decimals
                                $valToConvert = number_format( ($durationReqDecimal - $counterReqExploded) * 0.5, 2);
                                
                                $missingFormatted = explode( '.', strval( $valToConvert ) );
                                $missingFormatted = $missingFormatted[0].$h.( $missingFormatted[1] == '5' ? '30' : '00').$m;
                                $missingRequirements .= $missingFormatted.' de rendez-vous avec ';
                                // https://stackoverflow.com/questions/3066421/writing-a-new-line-to-file-in-php
                                $missingRequirements .= ' le Fournisseur '.$missingReqProvider.".\n";
                            }
                            else { // If positionment succesful
                                if($arrayCheckIfAllStoresPositioned[$requirement->getOneStore()->getIdEnterprise()]['atLeastOneAppointment'] == false) {
                                    $arrayCheckIfAllStoresPositioned[$requirement->getOneStore()->getIdEnterprise()]['atLeastOneAppointment'] = true;
                                }
                            }
                        }
                        else { // If positionment succesful
                            if($arrayCheckIfAllStoresPositioned[$requirement->getOneStore()->getIdEnterprise()]['atLeastOneAppointment'] == false) {
                                $arrayCheckIfAllStoresPositioned[$requirement->getOneStore()->getIdEnterprise()]['atLeastOneAppointment'] = true;
                            }
                        }
                        unset($value['requirements'][$key]);
                        
                        break 1;
                    } // End if placement possible
                }
            }
        }
        $counterTables++; 
    }
}
//$arrayCheckIfAllStoresPositioned[2]['positionStoreListStore'] = 0;
//$arrayCheckIfAllStoresPositioned[2]['atLeastOneAppointment'] = false; // Create hierarchy in array !
$tableExtra = '<table class="table table-borderless table-responsive table-condensed">';
$counterTdExtra = 0;

$missingRequirements .= '<hr/>';
$missingRequirements .= '<strong>Liste des Magasins sans rendez-vous :</strong>'."\n";

foreach($arrayCheckIfAllStoresPositioned as $idStore => $arrayData) {
    $storeChecked = $appService->findOneEnterprise($idStore);
    if(!$arrayData['atLeastOneAppointment']) {
        $missingRequirements .= 'Le Magasin '.$storeChecked->getName(). ' n\'a aucun rendez-vous !'."\n";
        
        $tableExtra .= '<tr><td id="td_extra_'.$counterTdExtra.'" style="padding:0;';

        $before = '<div class="event storeClass_'.$idStore.'" style="background-color:'.$colorsStores[$arrayCheckIfAllStoresPositioned[$idStore]['positionStoreListStore']].';" id="div_tab_special_store_'.$idStore.'">';
        $after = '</div>';
        $backgroundColor = 'background-color:'.$colorsStores[$arrayCheckIfAllStoresPositioned[$idStore]['positionStoreListStore']].';';
        $divExtra = $before.$storeChecked->getName().$after;
        
        $tableExtra .= $backgroundColor;
        $tableExtra .= 'vertical-align:middle;text-align:center">';
        $tableExtra .= $divExtra;
        $tableExtra .= '</td></tr>';        
        
        $counterTdExtra++;
    }
}
$tableExtra .= '</tr></table>';
// Fin expérimentation

$missingRequirements .= '<hr/>';
// Stores absent
$missingRequirements .= '<strong>Liste des Magasins absents au salon d\'achats :</strong>'."\n";
foreach($arrayEnterprisesAsStoresAbsent as $storeAbsent) {
    $missingRequirements .= 'Le Magasin '.$storeAbsent->getName(). ' ne participe pas au salon d\'achats !'."\n";
}

// List of Requirements nok written in a file
// Having too many functions file_put_contents pointing to the same file generates errors
file_put_contents('./../tmp/req_nok_planning_pf'.$_SESSION['purchasingFairConcerned']->getIdPurchasingFair().'.txt', $missingRequirements);
?>

<div class="row">
    <div class="col-lg-12 text-center alert alert-info">
        <h4>
        <i class="fa fa-info-circle" aria-hidden="true"></i> Salon No.<?php echo $pfConcerned->getIdPurchasingFair(); ?> : <?php echo $pfConcerned->getNamePurchasingFair(); ?> 
        du <?php echo $appService->myFrenchDatetime($pfConcerned->getStartDatetime()); ?> au <?php echo $appService->myFrenchDatetime($pfConcerned->getEndDatetime()); ?>
        </h4>
    </div>
</div>

<div class="row">
    <div id="divExtraTable" class="col-lg-12">
    <?php echo $tableExtra; ?>
    </div>
</div>

<div class="row">
    <div id="alertDangerDiv" class="col-md-4 text-center alert alert-danger">
        <button id="previousButton" class="btn btn-danger"><i class="fa fa-sign-out" aria-hidden="true"></i> Quitter cette page</button>
        <br/>
        <span id="msgInfoEmpty" class="text-danger"></span>
    </div>
    <div id="alertWarningDiv"  class="col-md-4 text-center alert alert-warning">
        <button id="btnReloadPlanning" class="btn btn-warning" onclick="reloadPlanning();"><i class="fa fa-calendar-times-o" aria-hidden="true"></i> Regénérer le planning (recommence à zéro)</button>
        <br/>
        <span id="msgInfoEmpty2" class="text-danger"></span>
    </div>
    <div id="alertSuccessDiv" class="col-md-4 text-center alert alert-success">
        <button id="btnSaveChanges" class="btn btn-success" onclick="saveChangesFunction();"><i class="fa fa-floppy-o" aria-hidden="true"></i> Enregistrer les modifications</button>
        <br/>
        <span id="msgInfoChangesNotSaved" class="text-danger"></span>
    </div>
</div>

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
    

<h3 class="text-center">
    <i class="fa fa-calendar" aria-hidden="true"></i> <?php echo $dayName. ' ' .$dayNumber. ' ' .$monthName. ' ' .$year; ?>
    <i class="fa fa-file-excel-o" style="cursor:pointer;color:#217346" aria-hidden="true" onclick="exportToExcel('table_<?php echo $counterTables; ?>');"></i>
    <i id="eyeIcon_table_<?php echo $counterTables; ?>" class="fa fa-eye-slash eyeIcon" style="cursor:pointer;color:#0b70b5" aria-hidden="true"></i>
</h3>
<div class="table-responsive">
    <table class="table table-bordered table-condensed table2excel" id="table_<?php echo $counterTables; ?>" data-tableName="Test Table <?php echo $counterTables; ?>">
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
            $counterAddedLine = 0;

            foreach($planningDay->getArraySalespersons() as $provider => $salespersons) {
                
                $providerObj  = $appService->findOneEnterprise($provider);
                $providerName = $providerObj->getName();
                $providerId   = $providerObj->getIdEnterprise();

                foreach($salespersons as $key => $salesperson) { // provider => array Salespersons
                                        
                    echo '<tr id="tr_tab_'.$counterTables.'_line_'.$spyLines.'">';
                    echo '<td class="providerClass_'.$providerId.'" style="background-color:'.$colors[$counterColor].';vertical-align:middle;text-align:center;">'.$providerName.'<br/>('.$providerObj->getOneTypeOfProvider()->getNameTypeOfProvider()[0].')</td>';
                    echo '<td class="salesperson_'.$salesperson->getIdParticipant().'" style="background-color:'.$colors[$counterColor].';vertical-align:middle;text-align:center;">'.$salesperson->getCivilitySmall().' '.$salesperson->getSurname().'</td>';

                    foreach($planningDay->getArrayTimeSlots() as $key => $timeSlot) {
                        
                        $backgroundColor = 'background-color:#ffffff;';
                                     
                        $tdContent = $arrayMultiDimPf[$counterTables][$spyLines][$timeSlot->getIdSlot()];
                        
                        $td = '<td id="td_tab_'.$counterTables.'_line_'.$spyLines.'_timeslot_'.$timeSlot->getIdSlot().'" style="padding:0;';

                        if(!is_null($tdContent) && $tdContent == 'IND') {
                            $before = '<div class="event" style="background-color:#f1f3f6;" id="div_tab_'.$counterTables.'_line_'.$spyLines.'_timeslot_'.$timeSlot->getIdSlot().'" draggable="true">';
                            $after = '</div>';
                            $tdContent = $before.$tdContent.$after;
                            $backgroundColor = 'background-color:#f1f3f6;';
                        }
                        if(!is_null($tdContent) && $tdContent instanceof Enterprise) { // Possible to use is_a(object, className)
                            $before = '<div class="event storeClass_'.$tdContent->getIdEnterprise().'" style="background-color:'.$colorsStores[$tdContent->getPositionStoreListStore()].';" id="div_tab_'.$counterTables.'_line_'.$spyLines.'_timeslot_'.$timeSlot->getIdSlot().'" draggable="true">';
                            $after = '</div>';
                            $backgroundColor = 'background-color:'.$colorsStores[$tdContent->getPositionStoreListStore()].';';
                            $tdContent = $before.$tdContent->getName().$after;
                        }
                        
                        if($timeSlot->getIsLunchBreak()) {
                            $backgroundColor = 'background-color:#f1f3f6;';
                        }
                        
                        $td .= $backgroundColor;
                        
                        $td .= 'vertical-align:middle;text-align:center">';
                        
                        $td .= $tdContent;
                        
                        $td .= '</td>';
                        
                        echo $td;
                    }
                    echo '</tr>';
                    $spyLines++;
                }
                // Separation
                $counterColor++;
                echo '<tr id="tr_tab_'.$counterTables.'_addedLine_'.$counterAddedLine.'" class="addedTr">';
                for($j = 0 ; $j < $sizeArrayTimeSlots ; $j++) {
                    if($lunchBreakDec == 12 && $j == 10) { echo '<td id="td_tab_'.$counterTables.'_addedLine_'.$counterAddedLine.'_timeslot_'.$j.'" style="background-color:#f1f3f6;"></td>'; }
                    elseif($lunchBreakDec == 12.5 && $j == 11) { echo '<td id="td_tab_'.$counterTables.'_addedLine_'.$counterAddedLine.'_timeslot_'.$j.'" style="background-color:#f1f3f6;"></td>'; }
                    elseif($lunchBreakDec == 13 && $j == 12) { echo '<td id="td_tab_'.$counterTables.'_addedLine_'.$counterAddedLine.'_timeslot_'.$j.'" style="background-color:#f1f3f6;"></td>'; }
                    else { echo '<td id="td_tab_'.$counterTables.'_addedLine_'.$counterAddedLine.'_timeslot_'.$j.'"></td>'; }
                }
                echo '<td id="td_tab_'.$counterTables.'_addedLine_'.$counterAddedLine.'_timeslot_'.(++$j).'"></td><td id="td_tab_'.$counterTables.'_addedLine_'.$counterAddedLine.'_timeslot_'.(++$j).'"></td>';
                echo '</tr>';
                $counterAddedLine++;
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
</div>

</body>
</html>

<script src="../js/jquery-3.1.1.min.js"></script>
<script src="../js/plugins/table2excel/jquery.table2excel.js"></script>

<!-- Toastr script -->
<script src="../js/plugins/toastr/toastr.min.js"></script>

<!-- Mousetrap script -->
<script src="../js/plugins/mousetrap/mousetrap.min.js"></script>

<script>
var changesToSave = []; // Empty array, do not define it in document.ready function else the variable is not visible for the entire page
var eventTemp = '';

// Toastr options - Added 02.08.2018 and updated 12.09.2018 : Fix bug reported by Elodie
toastr.options = {
  "closeButton": true,
  "debug": false,
  "newestOnTop": false,
  "progressBar": true,
  "positionClass": "toast-top-left",
  "preventDuplicates": true,
  "onclick": null,
  "showDuration": "300",
  "hideDuration": "1000",
  "timeOut": "2000",
  "extendedTimeOut": "1000",
  "showEasing": "swing",
  "hideEasing": "linear",
  "showMethod": "fadeIn",
  "hideMethod": "fadeOut"
}

function exportToExcel(idTable) {
    $("#" + idTable).table2excel({
        exclude: ".noExl",
        name: "Planning",
        filename: "planning" + new Date().toISOString().replace(/[\-\:\.]/g, ""),
        fileext: ".xls",
        exclude_img: true,
        exclude_links: true,
        exclude_inputs: true
    });
}

//https://developer.mozilla.org/en-US/docs/Web/Guide/Parsing_and_serializing_XML
//https://www.w3schools.com/jquery/ajax_post.asp
//https://stackoverflow.com/questions/14621243/get-the-whole-documents-html-with-javascript-jquery
//https://stackoverflow.com/questions/817218/how-to-get-the-entire-document-html-as-a-string
// For updateing the planning you need a PHP script because JS cannot manipulate files
function saveChangesFunction() {
    
    if(changesToSave.length == 0) {
        $('#toast-container').remove(); // Fix bug 12.09.2018 reported by Elodie
        toastr.error('Veuillez effectuer au moins une modification.', 'Échec.');
    }
    else {
                
        var sendEmails = prompt('Envoyer les emails aux Magasins/Fournisseurs ? (O/N, défaut N)', 'N');
        if (sendEmails === null) { return; }
    
        if (sendEmails.toUpperCase().trim() === 'O') {
    
            // Mails sent correctly ?
            $.post(
                './../ajax/sendMail.php',
                {
                      enterprisesToContact : getAllEnterprisesToContact()
        //            planningContent : new XMLSerializer().serializeToString(document)
    //                        idPurchasingFair : <?php // echo $_SESSION['purchasingFairConcerned']->getIdPurchasingFair(); ?>,
    //                        planningContent : '<!DOCTYPE html>' + document.documentElement.outerHTML
                },
                function(data) {
                    if(data.trim() === 'OK') { 
                        toastr.success('Envoi email(s) réussi.', 'Succès.');
                        // https://stackoverflow.com/questions/1232040/how-do-i-empty-an-array-in-javascript
                        changesToSave = [];                         
                    }
                    else {
                        toastr.error('<a href="./../admin_email_errors_check.php">Cliquez ici pour + de détails</a>', 'Erreur envoi emails.');
                    }
                },
                'text'
            );
            // End Mail check
        
        }
        else {
            changesToSave = [];
            toastr.success('Choix de ne pas envoyer les emails pris en compte.', 'Succès.');
        }
        
        // Reset borders planning
        $('.dashedStyleMoved').removeClass('dashedStyleMoved');
        $('.dashedStyleNewPosition').addClass('dashedStyleChangeSaved');
        $('.dashedStyleNewPosition').removeClass('dashedStyleNewPosition');   
        
        $('#toast-container').remove(); // Fix bug 12.09.2018 reported by Elodie
        
        // Update (get all page content to file)
        $.post(
            './../ajax/updatePlanning.php',
            {
    //            planningContent : new XMLSerializer().serializeToString(document)
                idPurchasingFair : <?php echo $_SESSION['purchasingFairConcerned']->getIdPurchasingFair(); ?>,
                planningContent : '<!DOCTYPE html>' + document.documentElement.outerHTML
            },
            function(data) {
                if(data.trim() === 'Success') { 
                    toastr.success('Mise à jour réussie', 'Succès.');

                    // https://stackoverflow.com/questions/8562583/add-element-to-array-associative-arrays
                    $('#msgInfoChangesNotSaved').html('');
                    $('#msgInfoEmpty').html('');
                    $('#msgInfoEmpty2').html('');
                }
                else {
                    toastr.error('Mise à jour échouée', 'Échec.');
                }
            },
            'text'
        );
    }
}

function reloadPlanning() {
    $.post(
        './../ajax/reloadPlanning.php',
        {
//            planningContent : new XMLSerializer().serializeToString(document)
        },
        function(data) {
            if(data.trim() === 'Success') { 
                location.assign('./planning_loader_all.php');
            }
            else {
                toastr.error('Regénération échouée.', 'Échec.');
            }
        },
        'text'
    );
}

function scrollFunction() {
    if (document.body.scrollTop > 20 || document.documentElement.scrollTop > 20) {
        document.getElementById("btnTop").style.display = "block";
    } else {
        document.getElementById("btnTop").style.display = "none";
    }
}

// When the user clicks on the button, scroll to the top of the document
function topFunction() {
    document.body.scrollTop = 0; // For Safari
    document.documentElement.scrollTop = 0; // For Chrome, Firefox, IE and Opera
}

function getAllEnterprisesToContact() {
    var arrayMoved = [];
    var arrayNewPosition = [];

    // Retrieves the names of providers for whom the appointment has moved
    $('.dashedStyleMoved').each(function () {
        arrayMoved.push($(this).closest('tr').find('td:first').attr('class').substring(14)); // Add Ids Providers
    });

    // retrieves the name stores for which an appointment is moved and the name of the associated provider
    // The find() method returns descendant elements of the selected element.
    $('.dashedStyleNewPosition').each(function () {
        arrayNewPosition.push($(this).find('div').attr('class').split(' ')[1].substring(11)); // Add Id Store
        arrayMoved.push($(this).closest('tr').find('td:first').attr('class').substring(14)); // Add Ids New or Same Provider
    });

    return JSON.stringify(arrayMoved.concat(arrayNewPosition));
}

// https://www.html5rocks.com/en/tutorials/dnd/basics/
// https://stackoverflow.com/questions/23472505/drag-and-drop-table-cell-contents
// http://jsfiddle.net/videma/2RuBh/
var previousColor = '';
var previousId = '';

$(document).ready(function(){
    $("#loader").hide();
    $("#divContentPage").show();
        
    // When the user scrolls down 20px from the top of the document, show the button
    window.onscroll = function() {scrollFunction()};
    
    $(".eyeIcon").click(function(){
        
        var idEye = $(this).attr('id');
        var idTable = idEye.substring(8);
        
        // Show
        if( $(this).hasClass("fa-eye") ) {
            $(this).removeClass("fa-eye");
            $(this).addClass("fa-eye-slash");
            $('#' + idTable).toggle();
            // The toggle() method toggles between hide() and show() for the selected elements.
            //https://www.w3schools.com/jquery/eff_toggle.asp
        }
        // hide
        else {
            $(this).removeClass("fa-eye-slash");
            $(this).addClass("fa-eye");
            $('#' + idTable).toggle();
        }      
    });
    
    // Previous button
    $("#previousButton").click(function(){ window.location.assign('./../purchasing_fair_list.php'); });

    // https://www.html5rocks.com/en/tutorials/dnd/basics/
    // https://www.w3schools.com/jquery/traversing_parent.asp
    // https://www.w3schools.com/jquery/traversing_closest.asp
    // https://stackoverflow.com/questions/3203966/jquery-get-the-first-class-only-from-a-element
    $('.event').on("dragstart", function (event) {
        var dt = event.originalEvent.dataTransfer;
        dt.setData('Text', $(this).attr('id'));
        previousColor = $(this).css('background-color');
        previousId = $(this).parent().attr('id');
        // The closest() method returns the first ancestor of the selected element.
        // I don't use parent() beceause it returns <td> element
//        console.log($(this).closest('tr').find('td:first').attr('class')); // Provider
//        console.log($(this).attr('class').split(' ')[1]); // Store
        changesToSave.push({
                idOldProvider : $(this).closest('tr').find('td:first').attr('class').substring(14),
                idStore : $(this).attr('class').split(' ')[1].substring(11)
        });
//        console.log(previousColor);
//        console.log(previousId);
      });
    $('table td').on("dragenter dragover drop", function (event) {	
        event.preventDefault();
        if (event.type === 'drop') {
//               console.log($(this).text());
//               console.log($(this).children().length > 0);
            if($(this).text() != '' || $(this).children().length > 0 || $(this).closest('tr').hasClass('addedTr')) {
//              alert('Non autorisé');
                toastr.error('Déplacement non autorisé.', 'Échec.');
            }
            else {
               var data = event.originalEvent.dataTransfer.getData('Text',$(this).attr('id'));
               de=$('#'+data).detach();
               de.appendTo($(this));
//               console.log($(this).attr('id'));
//               console.log($('#'+$(this).attr('id')).parent());
                $(this).css('background-color', previousColor);
//                $(this).css('border', '3px dashed #ff0000');
                if( $(this).hasClass('dashedStyleMoved')) { $(this).removeClass('dashedStyleMoved'); }
                if( !$(this).hasClass('dashedStyleNewPosition')) { $(this).addClass('dashedStyleNewPosition'); }
//                console.log(!$(this).hasClass('dashedStyle'));
//                console.log($(this).closest('tr').find('td:first').attr('class'));
                changesToSave.push({ idNewProvider : $(this).closest('tr').find('td:first').attr('class').substring(14) }); // add a new object
                $('#' + previousId).css('background-color', '#ffffff');
                if( $('#' + previousId).hasClass('dashedStyleNewPosition')) { $('#' + previousId).removeClass('dashedStyleNewPosition'); }
                if( $('#' + previousId).hasClass('dashedStyleChangeSaved')) { $('#' + previousId).removeClass('dashedStyleChangeSaved'); }
                if( !$('#' + previousId).hasClass('dashedStyleMoved')) { $('#' + previousId).addClass('dashedStyleMoved'); }
                $('#' + previousId).css('border', '1px solid #ddd!important');
                $('#msgInfoChangesNotSaved').html('<i class="fa fa-exclamation-triangle" aria-hidden="true"></i> Pensez à sauvegarder votre travail');
                $('#msgInfoEmpty').html('<br/>');
                $('#msgInfoEmpty2').html('<br/>');
           }
        };
   });
   
    // Info on click class event
    // https://teamtreehouse.com/community/having-trouble-selecting-dynamically-created-elements-with-jquery
    $('td').on("click", ".event", function (event) { 
        toastr.warning('Vous avez sélectionné une case contenant [' + $(this).text() + '], vous pouvez utiliser un raccourci clavier.', 'Information.');
        eventTemp = $(this).clone();
        //console.log(eventTemp);
    });
   
    // Mousetrap
    
    // Copy appointment
    Mousetrap.bind('ctrl c', function() { 
        if(eventTemp.length) {
            toastr.success('Le rendez-vous a été copié. Cliquez sur une autre case pour effectuer la copie.','Information.');
            $('#msgInfoChangesNotSaved').html('<i class="fa fa-exclamation-triangle" aria-hidden="true"></i> Pensez à sauvegarder votre travail');
            $('#msgInfoEmpty').html('<br/>');
            $('#msgInfoEmpty2').html('<br/>');
        }
        else { 
            toastr.error('Veuillez d\'abord cliquer sur un rendez-vous.', 'Erreur.');
        }
    });
    
    // Remove appointment
    Mousetrap.bind('ctrl r', function() { 
        if(eventTemp.length) {
            $('#' + eventTemp.attr('id')).parent().css('background-color', '#ffffff');
            
            if( $('#' + eventTemp.attr('id')).parent().hasClass('dashedStyleNewPosition')) { $('#' + eventTemp.attr('id')).parent().removeClass('dashedStyleNewPosition'); }
            if( $('#' + eventTemp.attr('id')).parent().hasClass('dashedStyleChangeSaved')) { $('#' + eventTemp.attr('id')).parent().removeClass('dashedStyleChangeSaved'); }
            if( $('#' + eventTemp.attr('id')).parent().hasClass('dashedStyleMoved')) { $('#' + eventTemp.attr('id')).parent().removeClass('dashedStyleMoved'); }
            
            $('#' + eventTemp.attr('id')).parent().addClass('dashedStyleMoved');
            
            changesToSave.push({
                idOldProvider : $('#' + eventTemp.attr('id')).closest('tr').find('td:first').attr('class').substring(14),
                idStore : $('#' + eventTemp.attr('id')).attr('class').split(' ')[1].substring(11)
            });
            
            $('#' + eventTemp.attr('id')).remove();
            eventTemp = "";
            toastr.success('Le rendez-vous a bien été supprimé.','Information.');
            $('#msgInfoChangesNotSaved').html('<i class="fa fa-exclamation-triangle" aria-hidden="true"></i> Pensez à sauvegarder votre travail');
            $('#msgInfoEmpty').html('<br/>');
            $('#msgInfoEmpty2').html('<br/>');
        }
        else { 
            toastr.error('Veuillez d\'abord cliquer sur un rendez-vous !', 'Erreur.');
        }    
    });
    
    // Move appointment
    Mousetrap.bind('ctrl m', function() { 
        if(eventTemp.length) {
            $('#' + eventTemp.attr('id')).parent().css('background-color', '#ffffff');
            
            if( $('#' + eventTemp.attr('id')).parent().hasClass('dashedStyleNewPosition')) { $('#' + eventTemp.attr('id')).parent().removeClass('dashedStyleNewPosition'); }
            if( $('#' + eventTemp.attr('id')).parent().hasClass('dashedStyleChangeSaved')) { $('#' + eventTemp.attr('id')).parent().removeClass('dashedStyleChangeSaved'); }
            if( $('#' + eventTemp.attr('id')).parent().hasClass('dashedStyleMoved')) { $('#' + eventTemp.attr('id')).parent().removeClass('dashedStyleMoved'); }
            
            $('#' + eventTemp.attr('id')).parent().addClass('dashedStyleMoved');
            
            changesToSave.push({
                idOldProvider : $('#' + eventTemp.attr('id')).closest('tr').find('td:first').attr('class').substring(14),
                idStore : $('#' + eventTemp.attr('id')).attr('class').split(' ')[1].substring(11)
            });
            
            $('#' + eventTemp.attr('id')).remove();
            toastr.success('Le rendez-vous a été copié. Cliquez sur une autre case pour effectuer le déplacement.','Information.');
            $('#msgInfoChangesNotSaved').html('<i class="fa fa-exclamation-triangle" aria-hidden="true"></i> Pensez à sauvegarder votre travail');
            $('#msgInfoEmpty').html('<br/>');
            $('#msgInfoEmpty2').html('<br/>');
        }
        else { 
            toastr.error('Veuillez d\'abord cliquer sur un rendez-vous !', 'Erreur.');
        }
    });
    
    // Cancel action
//    Mousetrap.bind('ctrl q', function() { 
//        if(eventTemp.length && !$('#' + eventTemp.attr('id')).parent().children().length) {
//            $('#' + eventTemp.attr('id')).parent().append(eventTemp);
//            toastr.success('Action annulée.','Information.');
//            eventTemp = "";
//        }
//        else { 
//            toastr.error('Veuillez d\'abord cliquer sur un rendez-vous !', 'Erreur.');
//        }
//    });
//    
    $('td').on("click", function (event) {
        if(eventTemp.length && $(this).text() === '' && $(this).children().length === 0 && !$(this).closest('tr').hasClass('addedTr')) {
            $(this).append(eventTemp);
            
            // https://www.w3schools.com/jquery/jquery_traversing_siblings.asp
            // https://www.w3schools.com/jsref/jsref_split.asp
            // https://stackoverflow.com/questions/27509/detecting-an-undefined-object-property
            if (typeof $(this).prev().attr('id') === 'undefined') { // If I put an appoitnment in the first timeslot of a row
                var previousTdIdSplitted = $(this).next().attr('id').split('_'); // Get id of the next td
                var newTimeSlotNumberInId = parseInt(previousTdIdSplitted[6], 10) - 1; // Decrease timeslot value
            }
            else {
                var previousTdIdSplitted = $(this).prev().attr('id').split('_'); // Get Previous td id splitted
                var newTimeSlotNumberInId = parseInt(previousTdIdSplitted[6], 10) + 1; // Increase timeslot value
            }
            
            $(this).find('div').attr('id', 
                    'div_' +
                    previousTdIdSplitted[1] + '_' +
                    previousTdIdSplitted[2] + '_' +
                    previousTdIdSplitted[3] + '_' +
                    previousTdIdSplitted[4] + '_' +
                    previousTdIdSplitted[5] + '_' +
                    newTimeSlotNumberInId);
            $(this).css('background-color', eventTemp.css('background-color'));
            $(this).addClass('dashedStyleNewPosition');
            eventTemp = "";
            toastr.success('Le rendez-vous a bien été placé.','Information.');
            
            changesToSave.push({
                idNewProvider : $(this).closest('tr').find('td:first').attr('class').substring(14),
                idStore : $(this).children().attr('class').split(' ')[1].substring(11)
            });
        }
        else { 
            // nothing
        }
    });

});

</script>
<?php

// var_dump($arraySpecificCase);

}
$data = ob_get_contents();

ob_end_clean();

//echo '<!-- TMP PLANNING SUCCESSFULLY GENERATED -->';
echo $data;

file_put_contents('./../tmp/tmp_planning_pf'.$_SESSION['purchasingFairConcerned']->getIdPurchasingFair().'.html', $data);

// var_dump($arraySpecificCase);
SingletonConnectionMySQL::getInstance()->close();
?>