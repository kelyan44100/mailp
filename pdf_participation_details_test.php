<?php
ob_start(); // Always before include, require etc.

require_once dirname ( __FILE__ ) . '/services/AppServiceImpl.class.php'; // Requirements
require_once dirname ( __FILE__ ) . '/html2pdf-4.4.0/html2pdf.class.php';

if(!isset($_SESSION)) session_start(); // Start session

$appService = AppServiceImpl::getInstance();

date_default_timezone_set('Europe/Paris');

$purchasingFairConcerned = $_SESSION['purchasingFairConcerned'];
$enterpriseConcerned = $_SESSION['enterpriseConcerned'];
$PauseMidi = $_SESSION['purchasingFairConcerned']->getLunchBreak();

$YearFromStartDatetime = $purchasingFairConcerned->getYearFromStartDatetime();
$MonthFromStartDatetime = $purchasingFairConcerned->getMonthFromStartDatetime();
$DayFromStartDatetime = $purchasingFairConcerned->getDayFromStartDatetime();

$YearFromEndDatetime = $purchasingFairConcerned->getYearFromEndDatetime();
$MonthFromEndDatetime = $purchasingFairConcerned->getMonthFromEndDatetime();
$DayFromEndDatetime = $purchasingFairConcerned->getDayFromEndDatetime();

$arrayDate = $appService->getDatesFromRange($YearFromStartDatetime.'-'.$MonthFromStartDatetime.'-'.$DayFromStartDatetime,$YearFromEndDatetime.'-'.$MonthFromEndDatetime.'-'.$DayFromEndDatetime);

$arrayDateBis = array();

foreach($arrayDate as $key => $value) {
    $dd = $appService->nom_jour($value);
    if($dd != "Samedi" && $dd != "Dimanche"){
        $arrayDateBis[] = $value;
    }
}

$HourStartDateTime = $purchasingFairConcerned->getStartDateTime(); //2019-05-11 08:00:00
$HourStartDateTimeBis = substr($HourStartDateTime, 11); //08:00:00
$HourStartDateTimeTer = substr($HourStartDateTimeBis,0,2).substr($HourStartDateTimeBis,3,2).substr($HourStartDateTimeBis,6,2);//080000

$HoursEndDateTime = $purchasingFairConcerned->getEndDateTime();
$HoursEndDateTimeBis = substr($HoursEndDateTime, 11);
$HoursEndDateTimeTer = substr($HoursEndDateTimeBis,0,2).substr($HoursEndDateTimeBis,3,2).substr($HoursEndDateTimeBis,6,2);

$arrayHour = array();
$arrayListDateTableau = array();


$content = '<page backtop="18mm" backbottom="18mm" backleft="10mm" backright="10mm"><page_header></page_header><page_footer></page_footer></page>';


// Added 27.08.2018 - Taking others Pf into account
$isOtherPf = ( $_SESSION['purchasingFairConcerned']->getOneTypeOfPf()->getNameTypeOfPf() === 'Autre' ) ? true : false;

$arrayRequirementsAlreadyRegistered     = $appService->findRequirementFilteredDuo($enterpriseConcerned, $purchasingFairConcerned);
$arrayUnavailabilitiesAlreadyRegistered = $appService->findEnterpriseUnavailabilities($enterpriseConcerned, $purchasingFairConcerned);
$arrayParticipationsAlreadyRegistered   = $appService->findAllParticipationsByEnterpriseAndPurchasingFair($enterpriseConcerned, $purchasingFairConcerned);


$content .= '<table style="width:100%;"><tr><th style="text-align:center;width:100%;margin: auto;"><img src="./img/logo_eleclerc_scaouest.png" style="width:340px;height:41px;"></th></tr></table>';
$content .= '<hr style="color:#ed8b18;">';
$content .= '<h3 style="color:#ed8b18;text-align:center;width:100%;margin:5px 0px 5px 0px;">'.strtoupper($enterpriseConcerned->getName()).'</h3>';
$content .= '<h5 style="color:#ed8b18;text-align:center;width:100%;margin:5px 0px 5px 0px;">RÉCAPITULATIF INSCRIPTION AU '.date('d').'/'.date('m').'/'.date('Y').' '.date('H').':'.date('i').'</h5>';
$content .= '<hr style="color:#ed8b18;">';



$content .= '<h2 style="color:#0b70b5;text-align:left;width:100%;margin-bottom:0;">> SALON D\'ACHATS</h2>';
$content .= '<hr style="color:#0b70b5;border-style:dashed;">';
$content .= '<span>- Numéro du salon (identifiant unique de référence) : '.$purchasingFairConcerned->getIdPurchasingFair().'</span><br>';
$content .= '<span>- Type de salon : '.$purchasingFairConcerned->getOneTypeOfPf()->getNameTypeOfPf().'</span><br>';
$content .= '<span>- Nom du salon : '.$purchasingFairConcerned->getNamePurchasingFair().'</span><br>';
$content .= '<span>- Dates de début et de fin : '.$appService->myFrenchDate($purchasingFairConcerned->getStartDatetime()).' -> '.$appService->myFrenchDate($purchasingFairConcerned->getEndDatetime()).'</span>';

$content .= '<h2 style="color:#0b70b5;text-align:left;width:100%;margin-bottom:0;">> PARTICIPANTS</h2>';
$content .= '<hr style="color:#0b70b5;border-style:dashed;">';

$counter3 = 0;
$limit3 = count($arrayParticipationsAlreadyRegistered);
if( $limit3 ) {
    foreach($arrayParticipationsAlreadyRegistered as $value) {
        $counter3++;
        $content .= $value->getOneParticipant()->getCivility(). ' '.$value->getOneParticipant()->getSurname().' '.$value->getOneParticipant()->getName().' / '.$value->getOneParticipant()->getEmail();
        $content .= ($counter3 < $limit3) ? '<br>' : '';
    }
}
else { $content .= 'Aucun participant enregistré'; }

$content .= '<div style="page-break-after: always;"> <span style="display: none;"> </span></div>';
//$content = explode('<br clear=all>', $rapport_html);
$content .= ' <page backtop="18mm" backbottom="18mm" backleft="10mm" backright="10mm"> </page>';

if(!$isOtherPf) {
    $content .= '<h2 style="color:#0b70b5;text-align:left;width:100%;margin-bottom:0;">> PRISE DE RENDEZ-VOUS</h2>';
    $content .= '<hr style="color:#0b70b5;border-style:dashed;">';

/*------------------------------------------------------------PRISE DE RDV---------------------------------------------------------*/

    foreach ($arrayDateBis as $key => $value) {

        $testRdv = $appService->findThreePriseRdvStoreBis($enterpriseConcerned->getIdEnterprise(), $purchasingFairConcerned->getIdPurchasingFair(), $value);

        if($testRdv != null){

            $content .= '<h3 class="text-center" value="'.'date'.$value.'" class="selectJour" style="color:#ed8b18;text-align:center;width:100%;margin:5px 0px 5px 0px;">
            '.$appService->nom_jour($value).' '.substr($value,8,2).' '.$appService->nom_mois($value).'</h3>
            <div style="width:100%;text-align:center;">
                <table id="tableRDV'.$key.'" class="" style="border-collapse:collapse; width:100%;">';
                    
            $content .= '<tr>';


            $content .= '
                <th style="vertical-align:middle;text-align:center;border:solid black 1px;font-size:6px; width:9%">FOURNISSEUR</th>
                <th style="vertical-align:middle;text-align:center;border:solid black 1px;font-size:6px">COMMERCIAL</th>';

            $arrayHour = [];

            for ($i=substr($HourStartDateTimeTer, 0, 2); $i < substr($HoursEndDateTimeTer, 0, 2); $i++) {
                if($i == substr($PauseMidi, 0, 2)){
                    if(substr($PauseMidi, 3, 2) == "30"){
                        $arrayHour[] = $value." ".$i.":00:00 - ".$i.":30:00";
                        $arrayHour[] = $value." ".$i.":30:00 - ".($i+1).":30:00";
                        $content .= '<th style="border:1px solid black;width:3.4%;font-size:6px;vertical-align:middle;text-align:center;" id="'.$i.':00:00 - '.$i.':30:00'.'" name="thLunch">'.$i.'H00 - '.$i.'H30'.'</th>';
                        $content .= '<th style="border:1px solid black;width:3.4%;font-size:6px;vertical-align:middle;text-align:center;" id="'.$i.':30:00 - '.($i+1).':30:00'.'" name="thLunch">'.$i.'H30 - '.($i+1).'H30'.'</th>';
                    }else{
                        $arrayHour[] = $value." ".$i.":00:00 - ".($i+1).":00:00";
                        $content .= '<th style="border:1px solid black;width:3.4%;font-size:6px;vertical-align:middle;text-align:center;" id="'.$i.':00:00 - '.($i+1).':00:00'.'" name="thLunch">'.$i.'H00 - '.($i+1).'H00'.'</th>';
                    }
                }else{
                    if($i == substr($HourStartDateTimeTer, 0, 2)){
                        if(substr($HourStartDateTimeTer, 2, 2) == "30"){
                            if($i+1<=9){
                                $arrayHour[] = $value." ".substr($HourStartDateTimeTer,0,2).":30:00 - 0".(substr($HourStartDateTimeTer,0,2)+1).":00:00";
                                $content .= '<th style="border:1px solid black;width:3.4%;font-size:6px;vertical-align:middle;text-align:center;" id="'.substr($HourStartDateTimeTer,0,2).':30:00 - 0'.(substr($HourStartDateTimeTer,0,2)+1).':00:00'.'" width="2px">'.substr($HourStartDateTimeTer,0,2).'H30 - 0'.(substr($HourStartDateTimeTer,0,2)+1).'H00'.'</th>';
                            }else{
                                $arrayHour[] = $value." ".substr($HourStartDateTimeTer, 0, 2).":30:00 - ".(substr($HourStartDateTimeTer, 0, 2)+1).":00:00";
                                $content .= '<th style="border:1px solid black;width:3.4%;font-size:6px;vertical-align:middle;text-align:center;" id="'.substr($HourStartDateTimeTer,0,2).':30:00 - '.(substr($HourStartDateTimeTer,0,2)+1).':00:00'.'">'.substr($HourStartDateTimeTer,0,2).'H30 - '.(substr($HourStartDateTimeTer,0,2)+1).'H00'.'</th>';
                            }
                        }else{
                            $arrayHour[] = $value." ".substr($HourStartDateTimeTer, 0, 2).":00:00 - ".substr($HourStartDateTimeTer, 0, 2).":30:00";
                            $content .= '<th style="border:1px solid black;width:3.4%;font-size:6px;vertical-align:middle;text-align:center;" id="'.substr($HourStartDateTimeTer,0,2).':00:00 - '.substr($HourStartDateTimeTer,0,2).':30:00'.'">'.substr($HourStartDateTimeTer,0,2).'H00 - '.substr($HourStartDateTimeTer,0,2).'H30'.'</th>';
                            if($i+1<=9){
                                $arrayHour[] = $value." ".substr($HourStartDateTimeTer,0,2).":30:00 - 0".(substr($HourStartDateTimeTer,0,2)+1).":00:00";
                                $content .= '<th style="border:1px solid black;width:3.4%;font-size:6px;vertical-align:middle;text-align:center;" id="'.substr($HourStartDateTimeTer,0,2).':30:00 - 0'.(substr($HourStartDateTimeTer,0,2)+1).':00:00'.'">'.substr($HourStartDateTimeTer,0,2).'H30 - 0'.(substr($HourStartDateTimeTer,0,2)+1).'H00'.'</th>';
                            }else{
                                $arrayHour[] = $value." ".substr($HourStartDateTimeTer, 0, 2).":30:00 - ".(substr($HourStartDateTimeTer, 0, 2)+1).":00:00";
                                $content .= '<th style="border:1px solid black;width:3.4%;font-size:6px;vertical-align:middle;text-align:center;" id="'.substr($HourStartDateTimeTer,0,2).':30:00 - '.(substr($HourStartDateTimeTer,0,2)+1).':00:00'.'">'.substr($HourStartDateTimeTer,0,2).'H30 - '.(substr($HourStartDateTimeTer,0,2)+1).'H00'.'</th>';
                            }
                        }
                    }else{
                        if($i != substr($HoursEndDateTimeTer, 0, 2)){
                            if(substr($PauseMidi, 3, 2) == "30" && $i == (substr($PauseMidi, 0, 2)+1)){
                                $arrayHour[] = $value." ".$i.":30:00 - ".($i+1).":00:00";
                                $content .= '<th style="border:1px solid black;width:3.4%;font-size:6px;vertical-align:middle;text-align:center;" id="'.$i.':30:00 - '.($i+1).':00:00'.'">'.$i.'H30 - '.($i+1).'H00'.'</th>';
                            }else{
                                if($i<=9){
                                    $arrayHour[] =  $value." "."0".$i.":00:00 - 0".$i.":30:00";
                                    $content .= '<th style="border:1px solid black;width:3.4%;font-size:6px;vertical-align:middle;text-align:center;" id="0'.$i.':00:00 - 0'.$i.':30:00'.'">0'.$i.'H00 - 0'.$i.'H30'.'</th>';
                                    if($i+1<=9){
                                        $arrayHour[] =  $value." "."0".$i.":30:00 - 0".($i+1).":00:00";
                                        $content .= '<th style="border:1px solid black;width:3.4%;font-size:6px;vertical-align:middle;text-align:center;" id="0'.$i.':30:00 - 0'.($i+1).':00:00'.'">0'.$i.'H30 - 0'.($i+1).'H00'.'</th>';
                                    }else{
                                        $arrayHour[] =  $value." "."0".$i.":30:00 - ".($i+1).":00:00";
                                        $content .= '<th style="border:1px solid black;width:3.4%;font-size:6px;vertical-align:middle;text-align:center;" id="0'.$i.':30:00 - '.($i+1).':00:00'.'">0'.$i.'H30 - '.($i+1).'H00'.'</th>';
                                    }
                                }else{
                                    $arrayHour[] =  $value." ".$i.":00:00 - ".$i.":30:00";
                                    $arrayHour[] =  $value." ".$i.":30:00 - ".($i+1).":00:00";
                                    $content .= '<th style="border:1px solid black;width:3.4%;font-size:6px;vertical-align:middle;text-align:center;" id="'.$i.':00:00 - '.$i.':30:00'.'">'.$i.'H00 - '.$i.'H30'.'</th>';
                                    $content .= '<th style="border:1px solid black;width:3.4%;font-size:6px;vertical-align:middle;text-align:center;" id="'.$i.':30:00 - '.($i+1).':00:00'.'">'.$i.'H30 - '.($i+1).'H00'.'</th>';
                                }
                            }
                            
                        }else{
                            if(substr($HoursEndDateTimeTer, 2, 2) == "30"){
                                $arrayHour[] =  $value." ".$i.":00:00 - ".$i.":30:00";
                                $content .= '<th style="border:1px solid black;width:3.4%;font-size:6px;vertical-align:middle;text-align:center;" id="'.$i.':00:00 - '.$i.':30:00'.'">'.$i.'H00 - '.$i.'H30'.'</th>';
                            }
                        }
                    }
                }
            }
            $content .= '</tr>';

            $arrayListDateTableau[] = $arrayHour;

            $content .=  '<tbody>';

            /*------------------------------------------------------------------------------------------------------------*/

            $thLunch = $purchasingFairConcerned->getLunchBreak();

            $DaySelect=0;
            $arrayGlobale = array(); //array comportant : "jour, start, end, ArrayHoursDay" pour chaque jours du salon
            $ArrayDayHourBis = array(); // liste des id des th au format : [0] => {[1] => "20190423080000"; [2] =>"20190423083000"}, [1] => ...
            $essai = "";
            $value = $arrayHour;
            $ArrayDayHourBis = [];
            $size = sizeof($value)-1;
            //print_r($arrayHour);

            foreach($value as $key2 => $value2) {
                $ArrayDayHourBis[] = [
                    /*début du créneau de demi-heure*/
                    "1" => substr($value2, 0, 4).substr($value2, 5, 2).substr($value2, 8, 2).substr($value2, 11, 2).substr($value2, 14, 2).substr($value2, 17, 2),
                    /*fin du créneau de demi-heure*/
                    "2" => substr($value2, 0, 4).substr($value2, 5, 2).substr($value2, 8, 2).substr($value2, 22, 2).substr($value2, 25, 2).substr($value2, 28, 2)
                ];
                $essai = substr($value[$size], 0, 4).substr($value[$size], 5, 2).substr($value[$size], 8, 2).substr($value[$size], 11, 2).substr($value[$size], 14, 2).substr($value[$size], 17, 2);
            }

            $arrayGlobale[] = [
                "jour" => substr($value[0],0,10),
                "start" => substr($value[0], 0, 4).substr($value[0], 5, 2).substr($value[0], 8, 2).substr($value[0], 11, 2).substr($value[0], 14, 2).substr($value[0], 17, 2),
                "end" => $essai,
                "ArrayHoursDay" => $ArrayDayHourBis
            ];


            //$asignSPstore = $appService->findByThreeIdsBisTwo($enterpriseConcerned->getIdEnterprise(), $purchasingFairConcerned->getIdPurchasingFair());

            $AllRdvStore = $appService->findThreePriseRdvStoreBis($enterpriseConcerned->getIdEnterprise(), $purchasingFairConcerned->getIdPurchasingFair(), $arrayGlobale[0]["jour"]); //rdv du jour selectionné

            if($AllRdvStore != null){

                $DifferentsFournisseurs = array();

                foreach ($AllRdvStore as $key => $value1) {
                    if($DifferentsFournisseurs == null){
                        $DifferentsFournisseurs[] = [
                            "0" => $value1->getIdFournisseur(),
                            "1" => $appService->findOneEnterprise($value1->getIdFournisseur())->getName(),
                            "2" => $value1->getIdCommercial(),
                            "3" => $appService->findOneParticipant($value1->getIdCommercial())->getSurname(),
                        ];
                    }else{
                        $temoin = "0";
                        foreach ($DifferentsFournisseurs as $key => $value2) {
                            if($value2["0"] == $value1->getIdFournisseur()){
                                $temoin = "1";
                            }
                        }
                        if($temoin == "0"){
                            $DifferentsFournisseurs[] = [
                                "0" => $value1->getIdFournisseur(),
                                "1" => $appService->findOneEnterprise($value1->getIdFournisseur())->getName(),
                                "2" => $value1->getIdCommercial(),
                                "3" => $appService->findOneParticipant($value1->getIdCommercial())->getSurname(),
                            ];
                        }
                    }
                }

                /*$arrayFrAssigned = array();
                foreach ($asignSPstore as $key => $value) {
                    $arrayFrAssigned[] = [
                        "0" => $value->getOneProvider()->getIdEnterprise(), //id fourniseur
                        "1" => $value->getOneProvider()->getName(), //nom fournisseur
                        "2" => $value->getOneParticipant()->getIdParticipant(), //id commercial
                        "3" => $value->getOneParticipant()->getSurname(), //nom commercial
                    ];
                }*/


                $ResultArray = [];

                foreach ($DifferentsFournisseurs as $key => $DF) {

                    $SpAssignedToStorebis = $appService->findOneAssignmentSpStoreQuatro($enterpriseConcerned->getIdEnterprise(), $DF[0], $purchasingFairConcerned->getIdPurchasingFair());

                    //on récupère les prises de RDV du store avec son commercial
                    $CommercialRDV = $appService->findOnePriseRdvByIdCommercialAndPF($SpAssignedToStorebis->getOneParticipant()->getIdParticipant(), $enterpriseConcerned->getIdEnterprise(), $purchasingFairConcerned->getIdPurchasingFair());

                    /*on récupère les indispos du commercial qui est attribué au magasin*/
                    $FourniseurIndispo = $appService->findParticipantUnavailabilitiesSp($SpAssignedToStorebis->getOneParticipant(), $purchasingFairConcerned);

                    //print_r($CommercialRDV);

                    $arrayAllPriseRDVForAllDays = [];
                    $arrayIndisposRelatedToRdvWithOtherProvider = [];
                    $arrayIndisposRelatedToSameHourWithOtherStoreWithSp = [];

                    $arrayIndisposProviderForOneDay = array();
                    $arrayIndisposProviderForAllDays = array();

                    $JourSalon = $arrayGlobale[0];

                    //foreach ($arrayGlobale as $key => $JourSalon) {

                    /*------------------------------------------------------------------------------------------*/

                    $arrayAllPriseRDVForOneDay = array();
                    if($CommercialRDV != null){
                        foreach ($CommercialRDV as $key => $value) {
                            if($JourSalon['jour'] == $value->getJourString()){
                                $arrayAllPriseRDVForOneDay[] = $appService->getHourFromRange(substr($value->getStartDatetime(),11,5), substr($value->getEndDatetime(),11,5));
                            }
                        }
                    }
                    
                    $arrayAllPriseRDVForAllDays[] = $arrayAllPriseRDVForOneDay; //array des prises de rdv d'une journée pour un fournisseur

                    //print_r($arrayAllPriseRDVForAllDays);

                    /*------------------------------------------------------------------------------------------*/
                    //print_r($DF["idF"]);
                    $ObjectIndisposOtherProvider = $appService->findIndisposOtherProvider($enterpriseConcerned->getIdEnterprise(), $purchasingFairConcerned->getIdPurchasingFair(), $DF[0], $JourSalon['jour']);

                    //print_r($ObjectIndisposOtherProvider->getIdFournisseur());

                    $arraybis = array();

                    if($ObjectIndisposOtherProvider != null){
                        foreach ($ObjectIndisposOtherProvider as $key => $value) {
                            //print_r($value);
                            $arraybis[] = [
                                "1" => $appService->getHourFromRange(substr($value->getStartDatetime(),11,5), substr($value->getEndDatetime(),11,5)),
                                "2" => $appService->findOneEnterprise($value->getIdFournisseur())->getName()
                            ];
                        }
                    }
                    $arrayIndisposRelatedToRdvWithOtherProvider[] = $arraybis; //array des indispos liées à rdv pris le même jour avec un autre fournisseur

                    /*------------------------------------------------------------------------------------------*/

                    $ObjectIndispoSameHourWithOtherStoreWithSp = $appService->findIndispoSameHourWithOtherStoreWithSp($purchasingFairConcerned->getIdPurchasingFair(), $SpAssignedToStorebis->getOneParticipant()->getIdParticipant(), $JourSalon['jour'], $enterpriseConcerned->getIdEnterprise());

                    //print_r($ObjectIndispoSameHourWithOtherStoreWithSp);

                    $arrayTer = array();

                    if($ObjectIndispoSameHourWithOtherStoreWithSp != null){
                        foreach ($ObjectIndispoSameHourWithOtherStoreWithSp as $key => $value) {
                            //print_r($value);
                            $arrayTer[] = [
                                "1" => $appService->getHourFromRange(substr($value->getStartDatetime(),11,5), substr($value->getEndDatetime(),11,5)),
                                "2" => $appService->findOneEnterprise($value->getIdStore())->getName()
                            ];
                        }
                    }
                    $arrayIndisposRelatedToSameHourWithOtherStoreWithSp[] = $arrayTer; //array des indispos liées à rdv pris durant le même horaire par un autre magasin avec le commercial

                    /*------------------------------------------------------------------------------------------*/

                    $arrayIndisposProviderForOneDay = [];

                    //print_r($FourniseurIndispo);

                    foreach($FourniseurIndispo as $value) {
                        //print_r(json_encode($value));

                        $start = $value->getStartDatetime();
                        // début d'indispo au format : "20190423080000"
                        $startBis = substr($start, 0, 4).substr($start, 5, 2).substr($start, 8, 2).substr($start, 11, 2).substr($start, 14, 2).substr($start, 17, 2);
                        //print_r($start);

                        $end = $value->getEndDatetime();
                        // début d'indispo au format : "20190423190000"
                        $endBis = substr($end, 0, 4).substr($end, 5, 2).substr($end, 8, 2).substr($end, 11, 2).substr($end, 14, 2).substr($end, 17, 2);


                        if($endBis > $JourSalon['start'] && $endBis <= $JourSalon['end']){
             
                            if($startBis < $JourSalon['start']){
                                foreach ($JourSalon['ArrayHoursDay'] as $key => $value) {
                                    if($value[1] < $endBis){
                                        $ref = '0';
                                        foreach ($arrayIndisposProviderForOneDay as $key => $value1) {
                                            if($value[1] == $value1){
                                                $ref = '1';
                                            }
                                        }
                                        if($ref != '1'){
                                            $arrayIndisposProviderForOneDay[] = $value[1];
                                        }
                                        
                                    }
                                }

                            }
                            if($startBis >= $JourSalon['start']){
                                foreach ($JourSalon['ArrayHoursDay'] as $key => $value) {
                                    if($startBis < $value[2] && $endBis > $value[1]){
                                        $ref = '0';
                                        foreach ($arrayIndisposProviderForOneDay as $key => $value1) {
                                            if($value[1] == $value1){
                                                $ref = '1';
                                            }
                                        }
                                        if($ref != '1'){
                                            $arrayIndisposProviderForOneDay[] = $value[1];
                                        }
                                    }
                                }
                            }

                        }

                        if($endBis > $JourSalon['end']){

                            if($startBis < $JourSalon['start']){

                                foreach ($JourSalon['ArrayHoursDay'] as $key => $value) {
                                    $ref = '0';
                                    foreach ($arrayIndisposProviderForOneDay as $key => $value1) {
                                        if($value[1] == $value1){
                                            $ref = '1';
                                        }
                                    }
                                    if($ref != '1'){
                                        $arrayIndisposProviderForOneDay[] = $value[1];
                                    }
                                }

                            }
                            if($startBis >= $JourSalon['start']){

                                foreach ($JourSalon['ArrayHoursDay'] as $key => $value) {
                                    if($startBis < $value[2]){
                                        $ref = '0';
                                        foreach ($arrayIndisposProviderForOneDay as $key => $value1) {
                                            if($value[1] == $value1){
                                                $ref = '1';
                                            }
                                        }
                                        if($ref != '1'){
                                            $arrayIndisposProviderForOneDay[] = $value[1];
                                        }
                                    }
                                }
                                
                            }
                        }


                    }

                    //print_r($arrayIndispos);
                    //print_r($arrayIndisposProviderForOneDay);
                    $resultat =array();

                    foreach ($arrayIndisposProviderForOneDay as $key => $value) {
                        $Y_M_D = substr($value, 0, 4).'-'.substr($value, 4, 2).'-'.substr($value, 6, 2).' ';
                        //print_r($value.' ');
                        $H = substr($value, 8, 2);
                        $M = substr($value, 10, 2);

                        $HBis = $H+1;
                        $MBis = '00';

                        if($H == substr($thLunch, 0, 2) && $M == substr($thLunch, 3, 2)){
                            if($M == "30"){
                                $resultat[] = substr($value, 8, 2).':'.substr($value, 10, 2).':'.substr($value, 12, 2).' - '.(substr($value, 8, 2)+1).':30:00';
                            }else{
                                $resultat[] = substr($value, 8, 2).':'.substr($value, 10, 2).':'.substr($value, 12, 2).' - '.(substr($value, 8, 2)+1).':00:00';
                            }
                        }
                        elseif($M == '30'){
                            if($H < 9){
                                $resultat[] = substr($value, 8, 2).':'.substr($value, 10, 2).':'.substr($value, 12, 2).' - 0'.$HBis.':'.$MBis.':00';
                            }else{
                                $resultat[] = substr($value, 8, 2).':'.substr($value, 10, 2).':'.substr($value, 12, 2).' - '.$HBis.':'.$MBis.':00';
                            }
                            
                        }
                        elseif($M == '00'){
                            $resultat[] = substr($value, 8, 2).':'.substr($value, 10, 2).':'.substr($value, 12, 2).' - '.$H.':30:00';
                        }
                    }

                    $arrayIndisposProviderForAllDays[] = $resultat;



                    $ResultArray[] = [$arrayIndisposProviderForAllDays, $DF[3], $DF[1], $arrayAllPriseRDVForAllDays, $arrayIndisposRelatedToRdvWithOtherProvider, $arrayIndisposRelatedToSameHourWithOtherStoreWithSp, $DF[0]];

                }

                //print_r(json_encode($ResultArray));
                //print_r($arrayHour);


                //print_r(json_encode($ResultArray));
                foreach ($ResultArray as $key => $value) {
                    $obj0 = $value[0];//array des indispos du commerciale selectionné
                    $obj1 = $value[1];//nom du commerciale
                    $obj2 = $value[2];//nom Entreprise
                    $obj3 = $value[3];//arrayAllPriseRDVForAllDays
                    $obj4 = $value[4];//arrayIndisposRelatedToRdvWithOtherProvider
                    $obj5 = $value[5];//arrayIndisposRelatedToSameHourWithOtherStoreWithSp
                    $obj6 = $value[6];//id Fournisseur

                    //print_r(json_encode($obj2));

                    $content .= '<tr>';

                    $content .= '<td style="border:solid black 1px; width:9%; font-size:10px; vertical-align:middle">'.$obj2.'</td>';
                    $content .= '<td style="border:solid black 1px; width:9%; font-size:10px; vertical-align:middle">'.$obj1.'</td>';

                    //print_r($thLunch);

                    foreach ($arrayHour as $key2 => $value2) {
                        //print_r(substr($value2, 11));
                        $indispo = substr($value2, 11);
                        $idthBis = substr($value2, 0, 10).substr($value2, 11,8).substr($value2, 22,8);
                        //print_r($indispo);

                        if(substr($indispo, 0, 8) != $thLunch){

                            $ref = "0";

                            // affichage des indispos des fournisseurs renseignées initialement
                            $refter = '0';
                            //print_r($indispo.' ');
                            foreach ($obj0 as $key => $HeureIndispo1) {
                                foreach ($HeureIndispo1 as $key => $HeureIndispo1bis) {
                                    if($indispo === $HeureIndispo1bis){
                                        //print_r("HeureIndispo1");
                                        $ref = "1";
                                        $refter = "1";
                                        //$content .= '<td style="background-color:#8C9899;border:1px solid black;width:3.4%"></td>';
                                        $content .= '<td style="border:1px solid black;width:3.4%"></td>';
                                    }
                                }
                            }

                            // affichage des RDV magasins - commerciaux
                            foreach ($obj3 as $key => $HeureIndispo2) {
                                //print_r($HeureIndispo2);
                                foreach ($HeureIndispo2 as $key => $HeureIndispo2bis) {
                                    //print_r($HeureIndispo2bis);
                                    foreach ($HeureIndispo2bis as $key => $HeureIndispo2bisbis) {
                                        if($indispo == $HeureIndispo2bisbis){
                                            //console.log(HeureIndispo2bis);
                                            $ref = "1";
                                            $content .= '<td style="background-color:#2CC36B;border:1px solid black;width:3.4%"></td>';
                                        }
                                    }
                                }
                            }

                            // affichage des indispos des commerciaux lié aux RDV qui ont été pris avec eux par un autre magasin
                            $refbis = "0";
                            foreach ($obj5 as $key => $HeureIndispo4) {
                                //print_r($HeureIndispo4);
                                foreach ($HeureIndispo4 as $key => $HeureIndispo4bis) {
                                    //print_r($HeureIndispo4bis);
                                    if($indispo == $HeureIndispo4bis[1][0] && $refter == "0"){
                                        $ref = "1";
                                        $refbis = "1";
                                        //$content .= '<td style="background-color:#EA6153;border:1px solid black;width:3.4%"></td>';
                                        $content .= '<td style="border:1px solid black;width:3.4%"></td>';

                                    }
                                }
                            }

                            // affichage des indispos de prise de RDV car un rdv a déja été pris avec un autre fournisseur le même durant le même créneau horaire
                            foreach ($obj4 as $key => $HeureIndispo3) {
                                foreach ($HeureIndispo3 as $key => $HeureIndispo3bis) {
                                    //print_r($HeureIndispo3bis);
                                    if($indispo == $HeureIndispo3bis[1][0] && $refbis == "0" && $refter == "0"){
                                        $ref = "1";
                                        //$content .= '<td style="background-color:#EC5E00;border:1px solid black;width:3.4%"></td>';
                                        $content .= '<td style="border:1px solid black;width:3.4%"></td>';

                                    }
                                }
                            }

                            // affichage des disponibilitées pour les prise de RDV
                            if($ref == "0"){
                                $content .= '<td style="border:1px solid black;width:4%;font-size:6px;vertical-align:middle;text-align:center;width:3.4%"></td>';
                            }

                        }else{
                            $content .= '<td style="background-color:#f1f3f6;vertical-align:middle;text-align:center;border:solid black 1px;font-size:6px;width:3.4%"></td>';
                        }

                    }

                    $content .= '</tr>';

                }

            } //end $AllRdvStore != null

            $content .=  '</tbody>
                    </table>
                </div>';
            

        }

    }

    /*-----------------------------------------------------------------------------------------------------------------------------------*/

}

echo $content;
$content = ob_get_clean();

try {
    $html2pdf = new HTML2PDF('L', 'A3', 'fr'); // Portrait / A4 / French
    $html2pdf -> pdf -> setTitle('recap_saisie_'.date('Y-m-d-H-i')); // Title in pdf viewer
    $html2pdf -> pdf -> setDisplayMode('fullpage'); // If output not D, display the pdf in the entire page
    $html2pdf -> writeHTML($content);
    ob_clean();
    $html2pdf-> Output('recap_saisie_'.date('Y-m-d-H-i').'.pdf', 'I'); // I = Show in browser, Force Download = D
} catch(HTML2PDF_exception $e) { die($e); }
?>