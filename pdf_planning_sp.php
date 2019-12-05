<?php
ob_start(); // Always before include, require etc.

require_once dirname ( __FILE__ ) . '/services/AppServiceImpl.class.php'; // Requirements
require_once dirname ( __FILE__ ) . '/html2pdf-4.4.0/html2pdf.class.php';

if(!isset($_SESSION)) session_start(); // Start session

$appService = AppServiceImpl::getInstance();

// Check URL before continue
$checkParameters = isset($_GET['pf']) && !empty($_GET['pf'])
        && isset($_GET['sp']) && !empty($_GET['sp'])
        && isset($_GET['k']) && !empty($_GET['k']);

if($checkParameters) {
    
    // Get objects from ids
    $purchasingFairConcerned = $appService->findOnePurchasingFair( (int) $_GET['pf'] );
    $salespersonConcerned    = $appService->findOneParticipant( (int) $_GET['sp'] );

    date_default_timezone_set('Europe/Paris');

    $purchasingFairConcerned = $_SESSION['purchasingFairConcerned'];
    //$enterpriseConcerned = $_SESSION['enterpriseConcerned'];
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

    $content .= '<table style="width:100%;"><tr><th style="text-align:center;width:100%;"><img src="./img/logo_eleclerc_scaouest.png" style="width:340px;height:41px;"></th></tr></table>';
    $content .= '<hr style="color:#ed8b18;">';
    $content .= '<h3 style="color:#ed8b18;text-align:center;width:100%;margin:5px 0px 5px 0px;">'.strtoupper($salespersonConcerned->getName().' '.$salespersonConcerned->getSurname()).'</h3>';
    $content .= '<h5 style="color:#ed8b18;text-align:center;width:100%;margin:5px 0px 5px 0px;">MON PLANNING AU '.date('d').'/'.date('m').'/'.date('Y').' '.date('H').':'.date('i').'</h5>';
    $content .= '<hr style="color:#ed8b18;">';



    $content .= '<h2 style="color:#0b70b5;text-align:left;width:100%;margin-bottom:0;">> SALON D\'ACHATS</h2>';
    $content .= '<hr style="color:#0b70b5;border-style:dashed;">';
    $content .= '<span>- Numéro du salon (identifiant unique de référence) : '.$purchasingFairConcerned->getIdPurchasingFair().'</span><br>';
    $content .= '<span>- Type de salon : '.$purchasingFairConcerned->getOneTypeOfPf()->getNameTypeOfPf().'</span><br>';
    $content .= '<span>- Nom du salon : '.$purchasingFairConcerned->getNamePurchasingFair().'</span><br>';
    $content .= '<span>- Dates de début et de fin : '.$appService->myFrenchDate($purchasingFairConcerned->getStartDatetime()).' -> '.$appService->myFrenchDate($purchasingFairConcerned->getEndDatetime()).'</span>';

    $content .= '<h2 style="color:#0b70b5;text-align:left;width:100%;margin-bottom:0;">> PLANNING</h2>';
    $content .= '<hr style="color:#0b70b5;border-style:dashed;">';

    //$content .= ' <page backtop="18mm" backbottom="18mm" backleft="10mm" backright="10mm"> </page>';

    if(!$isOtherPf) {

    /*------------------------------------------------------------PRISE DE RDV---------------------------------------------------------*/

        foreach ($arrayDateBis as $key => $value) {

            $testRdv = $appService->findRdvCommercialByThree($salespersonConcerned->getIdParticipant(), $purchasingFairConcerned->getIdPurchasingFair(), $value);

            if($testRdv != null){

                $content .= '<h3 class="text-center" value="'.'date'.$value.'" class="selectJour" style="color:#ed8b18;text-align:center;width:100%;margin:5px 0px 5px 0px;">
                '.$appService->nom_jour($value).' '.substr($value,8,2).' '.$appService->nom_mois($value).'</h3>
                <div style="width:100%;text-align:center;">
                    <table id="tableRDV'.$key.'" class="" style="border-collapse:collapse; width:100%;margin: auto;">';
                        
                $content .= '<tr>';


                $content .= '
                    <th style="vertical-align:middle;text-align:center;border:solid black 1px;font-size:12px; width:9%">MAGASIN</th>';

                $arrayHour = [];

                for ($i=substr($HourStartDateTimeTer, 0, 2); $i < substr($HoursEndDateTimeTer, 0, 2); $i++) {
                    if($i == substr($PauseMidi, 0, 2)){
                        if(substr($PauseMidi, 3, 2) == "30"){
                            $arrayHour[] = $value." ".$i.":00:00 - ".$i.":30:00";
                            $arrayHour[] = $value." ".$i.":30:00 - ".($i+1).":30:00";
                            $content .= '<th style="border:1px solid black;width:3.9%;font-size:12px;vertical-align:middle;text-align:center;" id="'.$i.':00:00 - '.$i.':30:00'.'" name="thLunch">'.$i.'H00 - '.$i.'H30'.'</th>';
                            $content .= '<th style="border:1px solid black;width:3.9%;font-size:12px;vertical-align:middle;text-align:center;" id="'.$i.':30:00 - '.($i+1).':30:00'.'" name="thLunch">'.$i.'H30 - '.($i+1).'H30'.'</th>';
                        }else{
                            $arrayHour[] = $value." ".$i.":00:00 - ".($i+1).":00:00";
                            $content .= '<th style="border:1px solid black;width:3.9%;font-size:12px;vertical-align:middle;text-align:center;" id="'.$i.':00:00 - '.($i+1).':00:00'.'" name="thLunch">'.$i.'H00 - '.($i+1).'H00'.'</th>';
                        }
                    }else{
                        if($i == substr($HourStartDateTimeTer, 0, 2)){
                            if(substr($HourStartDateTimeTer, 2, 2) == "30"){
                                if($i+1<=9){
                                    $arrayHour[] = $value." ".substr($HourStartDateTimeTer,0,2).":30:00 - 0".(substr($HourStartDateTimeTer,0,2)+1).":00:00";
                                    $content .= '<th style="border:1px solid black;width:3.9%;font-size:12px;vertical-align:middle;text-align:center;" id="'.substr($HourStartDateTimeTer,0,2).':30:00 - 0'.(substr($HourStartDateTimeTer,0,2)+1).':00:00'.'" width="2px">'.substr($HourStartDateTimeTer,0,2).'H30 - 0'.(substr($HourStartDateTimeTer,0,2)+1).'H00'.'</th>';
                                }else{
                                    $arrayHour[] = $value." ".substr($HourStartDateTimeTer, 0, 2).":30:00 - ".(substr($HourStartDateTimeTer, 0, 2)+1).":00:00";
                                    $content .= '<th style="border:1px solid black;width:3.9%;font-size:12px;vertical-align:middle;text-align:center;" id="'.substr($HourStartDateTimeTer,0,2).':30:00 - '.(substr($HourStartDateTimeTer,0,2)+1).':00:00'.'">'.substr($HourStartDateTimeTer,0,2).'H30 - '.(substr($HourStartDateTimeTer,0,2)+1).'H00'.'</th>';
                                }
                            }else{
                                $arrayHour[] = $value." ".substr($HourStartDateTimeTer, 0, 2).":00:00 - ".substr($HourStartDateTimeTer, 0, 2).":30:00";
                                $content .= '<th style="border:1px solid black;width:3.9%;font-size:12px;vertical-align:middle;text-align:center;" id="'.substr($HourStartDateTimeTer,0,2).':00:00 - '.substr($HourStartDateTimeTer,0,2).':30:00'.'">'.substr($HourStartDateTimeTer,0,2).'H00 - '.substr($HourStartDateTimeTer,0,2).'H30'.'</th>';
                                if($i+1<=9){
                                    $arrayHour[] = $value." ".substr($HourStartDateTimeTer,0,2).":30:00 - 0".(substr($HourStartDateTimeTer,0,2)+1).":00:00";
                                    $content .= '<th style="border:1px solid black;width:3.9%;font-size:12px;vertical-align:middle;text-align:center;" id="'.substr($HourStartDateTimeTer,0,2).':30:00 - 0'.(substr($HourStartDateTimeTer,0,2)+1).':00:00'.'">'.substr($HourStartDateTimeTer,0,2).'H30 - 0'.(substr($HourStartDateTimeTer,0,2)+1).'H00'.'</th>';
                                }else{
                                    $arrayHour[] = $value." ".substr($HourStartDateTimeTer, 0, 2).":30:00 - ".(substr($HourStartDateTimeTer, 0, 2)+1).":00:00";
                                    $content .= '<th style="border:1px solid black;width:3.9%;font-size:12px;vertical-align:middle;text-align:center;" id="'.substr($HourStartDateTimeTer,0,2).':30:00 - '.(substr($HourStartDateTimeTer,0,2)+1).':00:00'.'">'.substr($HourStartDateTimeTer,0,2).'H30 - '.(substr($HourStartDateTimeTer,0,2)+1).'H00'.'</th>';
                                }
                            }
                        }else{
                            if($i != substr($HoursEndDateTimeTer, 0, 2)){
                                if(substr($PauseMidi, 3, 2) == "30" && $i == (substr($PauseMidi, 0, 2)+1)){
                                    $arrayHour[] = $value." ".$i.":30:00 - ".($i+1).":00:00";
                                    $content .= '<th style="border:1px solid black;width:3.9%;font-size:12px;vertical-align:middle;text-align:center;" id="'.$i.':30:00 - '.($i+1).':00:00'.'">'.$i.'H30 - '.($i+1).'H00'.'</th>';
                                }else{
                                    if($i<=9){
                                        $arrayHour[] =  $value." "."0".$i.":00:00 - 0".$i.":30:00";
                                        $content .= '<th style="border:1px solid black;width:3.9%;font-size:12px;vertical-align:middle;text-align:center;" id="0'.$i.':00:00 - 0'.$i.':30:00'.'">0'.$i.'H00 - 0'.$i.'H30'.'</th>';
                                        if($i+1<=9){
                                            $arrayHour[] =  $value." "."0".$i.":30:00 - 0".($i+1).":00:00";
                                            $content .= '<th style="border:1px solid black;width:3.9%;font-size:12px;vertical-align:middle;text-align:center;" id="0'.$i.':30:00 - 0'.($i+1).':00:00'.'">0'.$i.'H30 - 0'.($i+1).'H00'.'</th>';
                                        }else{
                                            $arrayHour[] =  $value." "."0".$i.":30:00 - ".($i+1).":00:00";
                                            $content .= '<th style="border:1px solid black;width:3.9%;font-size:12px;vertical-align:middle;text-align:center;" id="0'.$i.':30:00 - '.($i+1).':00:00'.'">0'.$i.'H30 - '.($i+1).'H00'.'</th>';
                                        }
                                    }else{
                                        $arrayHour[] =  $value." ".$i.":00:00 - ".$i.":30:00";
                                        $arrayHour[] =  $value." ".$i.":30:00 - ".($i+1).":00:00";
                                        $content .= '<th style="border:1px solid black;width:3.9%;font-size:12px;vertical-align:middle;text-align:center;" id="'.$i.':00:00 - '.$i.':30:00'.'">'.$i.'H00 - '.$i.'H30'.'</th>';
                                        $content .= '<th style="border:1px solid black;width:3.9%;font-size:12px;vertical-align:middle;text-align:center;" id="'.$i.':30:00 - '.($i+1).':00:00'.'">'.$i.'H30 - '.($i+1).'H00'.'</th>';
                                    }
                                }
                                
                            }else{
                                if(substr($HoursEndDateTimeTer, 2, 2) == "30"){
                                    $arrayHour[] =  $value." ".$i.":00:00 - ".$i.":30:00";
                                    $content .= '<th style="border:1px solid black;width:3.9%;font-size:12px;vertical-align:middle;text-align:center;" id="'.$i.':00:00 - '.$i.':30:00'.'">'.$i.'H00 - '.$i.'H30'.'</th>';
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


                $AllRdvStore = $appService->findRdvCommercialByThree($salespersonConcerned->getIdParticipant(), $purchasingFairConcerned->getIdPurchasingFair(), $arrayGlobale[0]["jour"]); //rdv fournisseur du jour selectionné

                //print_r($AllRdvStore);

                if($AllRdvStore != null){

                    $DifferentsFournisseurs = array(); //différents magasins

                    foreach ($AllRdvStore as $key => $value1) {
                        if($DifferentsFournisseurs == null){
                            $DifferentsFournisseurs[] = [
                                "0" => $value1->getIdStore(),
                                "1" => $appService->findOneEnterprise($value1->getIdStore())->getName(),
                            ];
                        }else{
                            $temoin = "0";
                            foreach ($DifferentsFournisseurs as $key => $value2) {
                                if($value2["0"] == $value1->getIdStore()){
                                    $temoin = "1";
                                }
                            }
                            if($temoin == "0"){
                                $DifferentsFournisseurs[] = [
                                    "0" => $value1->getIdStore(),
                                    "1" => $appService->findOneEnterprise($value1->getIdStore())->getName(),
                                ];
                            }
                        }
                    }

                   // print_r($DifferentsFournisseurs);

                    $ResultArray = [];

                    foreach ($DifferentsFournisseurs as $key => $DF) {

                        $SpAssignedToStorebis = "magasin";

                        //on récupère les prises de RDV du fournisseur avec un magasin
                        $CommercialRDV = $appService->findRdvCommercialByQuattro($salespersonConcerned->getIdParticipant(), $purchasingFairConcerned->getIdPurchasingFair(), $arrayGlobale[0]["jour"], $DF[0]);

                        //print_r($CommercialRDV);

                        $arrayAllPriseRDVForAllDays = [];

                        $commercial = "";

                        $JourSalon = $arrayGlobale[0];

                        /*------------------------------------------------------------------------------------------*/

                        $arrayAllPriseRDVForOneDay = array();
                        if($CommercialRDV != null){
                            foreach ($CommercialRDV as $key => $value) {
                                if($JourSalon['jour'] == $value->getJourString()){
                                    $arrayAllPriseRDVForOneDay[] = $appService->getHourFromRange(substr($value->getStartDatetime(),11,5), substr($value->getEndDatetime(),11,5));
                                    $commercial = $appService->findOneParticipant($value->getIdCommercial())->getSurname();
                                }
                            }
                        }
                        //print_r("expression");
                        //print_r($commercial);
                        
                        $arrayAllPriseRDVForAllDays[] = $arrayAllPriseRDVForOneDay; //array des prises de rdv d'une journée avec un magasin

                        //print_r($arrayAllPriseRDVForAllDays);


                        $ResultArray[] = [$DF[0], $DF[1], $arrayAllPriseRDVForAllDays, $commercial];

                    }

                    //print_r(json_encode($ResultArray));
                    //print_r($arrayHour);


                    //print_r(json_encode($ResultArray));
                    foreach ($ResultArray as $key => $value) {
                        $obj0 = $value[0];//id Magasin
                        $obj1 = $value[1];//nom magasin
                        $obj2 = $value[2];//arrayAllPriseRDVForAllDays
                        $obj3 = $value[3];//commecial

                        //print_r(json_encode($obj2));

                        $content .= '<tr>';

                        $content .= '<td style="border:solid black 1px; width:9%">'.$obj1.'</td>';

                        //print_r($thLunch);

                        foreach ($arrayHour as $key2 => $value2) {
                            //print_r(substr($value2, 11));
                            $indispo = substr($value2, 11);
                            $idthBis = substr($value2, 0, 10).substr($value2, 11,8).substr($value2, 22,8);
                            //print_r($indispo);

                            if(substr($indispo, 0, 8) != $thLunch){

                                $ref = "0";

                                // affichage des RDV magasins - commerciaux
                                foreach ($obj2 as $key => $HeureIndispo2) {
                                    //print_r($HeureIndispo2);
                                    foreach ($HeureIndispo2 as $key => $HeureIndispo2bis) {
                                        //print_r($HeureIndispo2bis);
                                        foreach ($HeureIndispo2bis as $key => $HeureIndispo2bisbis) {
                                            if($indispo == $HeureIndispo2bisbis){
                                                //console.log(HeureIndispo2bis);
                                                $ref = "1";
                                                $content .= '<td style="background-color:#2CC36B;border:1px solid black;width:3.9%;font-size:7px; font-weight:bold; vertical-align:middle;"></td>';
                                            }
                                        }
                                    }
                                }

                                // affichage des disponibilitées pour les prise de RDV
                                if($ref == "0"){
                                    $content .= '<td style="border:1px solid black;width:4%;font-size:12px;vertical-align:middle;text-align:center;width:3.9%"></td>';
                                }

                            }else{
                                $content .= '<td style="background-color:#f1f3f6;vertical-align:middle;text-align:center;border:solid black 1px;font-size:12px;width:3.9%"></td>';
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
        $html2pdf = new HTML2PDF('L', 'A4', 'fr'); // Portrait / A4 / French
        $html2pdf -> pdf -> setTitle('recap_saisie_'.date('Y-m-d-H-i')); // Title in pdf viewer
        $html2pdf -> pdf -> setDisplayMode('fullpage'); // If output not D, display the pdf in the entire page
        $html2pdf -> writeHTML($content);
        ob_clean();
        $html2pdf-> Output('recap_saisie_'.date('Y-m-d-H-i').'.pdf', 'I'); // I = Show in browser, Force Download = D
    } catch(HTML2PDF_exception $e) { die($e); }
}
?>