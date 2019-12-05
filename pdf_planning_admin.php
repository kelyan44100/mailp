<?phpob_start(); // Always before include, require etc.require_once dirname ( __FILE__ ) . '/services/AppServiceImpl.class.php'; // Requirementsrequire_once dirname ( __FILE__ ) . '/html2pdf-4.4.0/html2pdf.class.php';if(!isset($_SESSION)) session_start(); // Start session$appService = AppServiceImpl::getInstance();date_default_timezone_set('Europe/Paris');if(isset( $_SESSION['purchasingFairConcerned'] ) && !empty( $_SESSION['purchasingFairConcerned'] ) && isset( $_SESSION['adminConnected'] ) && !empty( $_SESSION['adminConnected'] )){    $purchasingFairConcerned = $_SESSION['purchasingFairConcerned'];    $enterpriseConcerned = $_SESSION['adminConnected'];    $PauseMidi = $_SESSION['purchasingFairConcerned']->getLunchBreak();    $YearFromStartDatetime = $purchasingFairConcerned->getYearFromStartDatetime();    $MonthFromStartDatetime = $purchasingFairConcerned->getMonthFromStartDatetime();    $DayFromStartDatetime = $purchasingFairConcerned->getDayFromStartDatetime();    $YearFromEndDatetime = $purchasingFairConcerned->getYearFromEndDatetime();    $MonthFromEndDatetime = $purchasingFairConcerned->getMonthFromEndDatetime();    $DayFromEndDatetime = $purchasingFairConcerned->getDayFromEndDatetime();    $arrayDate = $appService->getDatesFromRange($YearFromStartDatetime.'-'.$MonthFromStartDatetime.'-'.$DayFromStartDatetime,$YearFromEndDatetime.'-'.$MonthFromEndDatetime.'-'.$DayFromEndDatetime);    $arrayDateBis = array();    foreach($arrayDate as $key => $value) {        $dd = $appService->nom_jour($value);        if($dd != "Samedi" && $dd != "Dimanche"){            $arrayDateBis[] = $value;        }    }    $HourStartDateTime = $purchasingFairConcerned->getStartDateTime(); //2019-05-11 08:00:00    $HourStartDateTimeBis = substr($HourStartDateTime, 11); //08:00:00    $HourStartDateTimeTer = substr($HourStartDateTimeBis,0,2).substr($HourStartDateTimeBis,3,2).substr($HourStartDateTimeBis,6,2);//080000    $HoursEndDateTime = $purchasingFairConcerned->getEndDateTime();    $HoursEndDateTimeBis = substr($HoursEndDateTime, 11);    $HoursEndDateTimeTer = substr($HoursEndDateTimeBis,0,2).substr($HoursEndDateTimeBis,3,2).substr($HoursEndDateTimeBis,6,2);    $arrayHour = array();    $arrayListDateTableau = array();    function randomColor() {        $str = '#';        for($i = 0 ; $i < 6 ; $i++) {            $randNum = rand(0 , 15);            switch ($randNum) {                case 10: $randNum = 'A'; break;                case 11: $randNum = 'B'; break;                case 12: $randNum = 'C'; break;                case 13: $randNum = 'D'; break;                case 14: $randNum = 'E'; break;                case 15: $randNum = 'F'; break;            }            $str .= $randNum;        }        return $str;    }    $content = '<page backtop="18mm" backbottom="18mm" backleft="10mm" backright="10mm"><page_header></page_header><page_footer></page_footer></page>';    // Added 27.08.2018 - Taking others Pf into account    $isOtherPf = ( $_SESSION['purchasingFairConcerned']->getOneTypeOfPf()->getNameTypeOfPf() === 'Autre' ) ? true : false;    $content .= '<table style="width:100%;"><tr><th style="text-align:center;width:100%;margin: auto;"><img src="./img/logo_eleclerc_scaouest.png" style="width:340px;height:41px;"></th></tr></table>';    $content .= '<hr style="color:#ed8b18;">';    $content .= '<h3 style="color:#ed8b18;text-align:center;width:100%;margin:5px 0px 5px 0px;">'.strtoupper($enterpriseConcerned->getName()).'</h3>';    $content .= '<h5 style="color:#ed8b18;text-align:center;width:100%;margin:5px 0px 5px 0px;">RÉCAPITULATIF PLANNING AU '.date('d').'/'.date('m').'/'.date('Y').' '.date('H').':'.date('i').'</h5>';    $content .= '<hr style="color:#ed8b18;">';    $content .= '<h2 style="color:#0b70b5;text-align:left;width:100%;margin-bottom:0;">> SALON D\'ACHATS</h2>';    $content .= '<hr style="color:#0b70b5;border-style:dashed;">';    $content .= '<span>- Numéro du salon (identifiant unique de référence) : '.$purchasingFairConcerned->getIdPurchasingFair().'</span><br>';    $content .= '<span>- Type de salon : '.$purchasingFairConcerned->getOneTypeOfPf()->getNameTypeOfPf().'</span><br>';    $content .= '<span>- Nom du salon : '.$purchasingFairConcerned->getNamePurchasingFair().'</span><br>';    $content .= '<span>- Dates de début et de fin : '.$appService->myFrenchDate($purchasingFairConcerned->getStartDatetime()).' -> '.$appService->myFrenchDate($purchasingFairConcerned->getEndDatetime()).'</span>';    $content .= ' <page backtop="18mm" backbottom="18mm" backleft="10mm" backright="10mm"> </page>';    $content .= '<h2 style="color:#0b70b5;text-align:left;width:100%;margin-bottom:0;">> PRISE DE RENDEZ-VOUS</h2>';    $content .= '<hr style="color:#0b70b5;border-style:dashed;">';    /*------------------------------------------------------------PRISE DE RDV---------------------------------------------------------*/    // Providers Present    $arrayProviderPresent = $appService->findAllProviderPresentForOnePurchasingFair($_SESSION['purchasingFairConcerned']->getIdPurchasingFair());        $colors1 = $appService->generateNColors('100000');        foreach ($arrayDateBis as $key => $value) {            $datedate = $value;                $content .= '<h3 class="text-center" value="'.'date'.$value.'" class="selectJour" style="color:#ed8b18;text-align:center;width:100%;margin:5px 0px 5px 0px;">                '.$appService->nom_jour($value).' '.substr($value,8,2).' '.$appService->nom_mois($value).'</h3>                <div style="width:100%;text-align:center;">                    <table id="tableRDV'.$key.'" class="" style="border-collapse:collapse; width:100%;margin: auto;font-size:5px">';                $content .= '<tr>';                $content .= '                    <th style="vertical-align:middle;text-align:center;border:solid black 1px;font-size:5px;">FOURNISSEUR</th>                    <th style="vertical-align:middle;text-align:center;border:solid black 1px;font-size:5px">COMMERCIAL</th>';                $arrayHour = [];                for ($i=substr($HourStartDateTimeTer, 0, 2); $i < substr($HoursEndDateTimeTer, 0, 2); $i++) {                    if($i == substr($PauseMidi, 0, 2)){                        if(substr($PauseMidi, 3, 2) == "30"){                            $arrayHour[] = $value." ".$i.":00:00 - ".$i.":30:00";                            $arrayHour[] = $value." ".$i.":30:00 - ".($i+1).":30:00";                            $content .= '<th style="border:1px solid black;width:3%;font-size:5px;vertical-align:middle;text-align:center;" id="'.$i.':00:00 - '.$i.':30:00'.'" name="thLunch">'.$i.'H00 - '.$i.'H30'.'</th>';                            $content .= '<th style="border:1px solid black;width:3%;font-size:5px;vertical-align:middle;text-align:center;" id="'.$i.':30:00 - '.($i+1).':30:00'.'" name="thLunch">'.$i.'H30 - '.($i+1).'H30'.'</th>';                        }else{                            $arrayHour[] = $value." ".$i.":00:00 - ".($i+1).":00:00";                            $content .= '<th style="border:1px solid black;width:3%;font-size:5px;vertical-align:middle;text-align:center;" id="'.$i.':00:00 - '.($i+1).':00:00'.'" name="thLunch">'.$i.'H00 - '.($i+1).'H00'.'</th>';                        }                    }else{                        if($i == substr($HourStartDateTimeTer, 0, 2)){                            if(substr($HourStartDateTimeTer, 2, 2) == "30"){                                if($i+1<=9){                                    $arrayHour[] = $value." ".substr($HourStartDateTimeTer,0,2).":30:00 - 0".(substr($HourStartDateTimeTer,0,2)+1).":00:00";                                    $content .= '<th style="border:1px solid black;width:3%;font-size:5px;vertical-align:middle;text-align:center;" id="'.substr($HourStartDateTimeTer,0,2).':30:00 - 0'.(substr($HourStartDateTimeTer,0,2)+1).':00:00'.'" width="2px">'.substr($HourStartDateTimeTer,0,2).'H30 - 0'.(substr($HourStartDateTimeTer,0,2)+1).'H00'.'</th>';                                }else{                                    $arrayHour[] = $value." ".substr($HourStartDateTimeTer, 0, 2).":30:00 - ".(substr($HourStartDateTimeTer, 0, 2)+1).":00:00";                                    $content .= '<th style="border:1px solid black;width:3%;font-size:5px;vertical-align:middle;text-align:center;" id="'.substr($HourStartDateTimeTer,0,2).':30:00 - '.(substr($HourStartDateTimeTer,0,2)+1).':00:00'.'">'.substr($HourStartDateTimeTer,0,2).'H30 - '.(substr($HourStartDateTimeTer,0,2)+1).'H00'.'</th>';                                }                            }else{                                $arrayHour[] = $value." ".substr($HourStartDateTimeTer, 0, 2).":00:00 - ".substr($HourStartDateTimeTer, 0, 2).":30:00";                                $content .= '<th style="border:1px solid black;width:3%;font-size:5px;vertical-align:middle;text-align:center;" id="'.substr($HourStartDateTimeTer,0,2).':00:00 - '.substr($HourStartDateTimeTer,0,2).':30:00'.'">'.substr($HourStartDateTimeTer,0,2).'H00 - '.substr($HourStartDateTimeTer,0,2).'H30'.'</th>';                                if($i+1<=9){                                    $arrayHour[] = $value." ".substr($HourStartDateTimeTer,0,2).":30:00 - 0".(substr($HourStartDateTimeTer,0,2)+1).":00:00";                                    $content .= '<th style="border:1px solid black;width:3%;font-size:5px;vertical-align:middle;text-align:center;" id="'.substr($HourStartDateTimeTer,0,2).':30:00 - 0'.(substr($HourStartDateTimeTer,0,2)+1).':00:00'.'">'.substr($HourStartDateTimeTer,0,2).'H30 - 0'.(substr($HourStartDateTimeTer,0,2)+1).'H00'.'</th>';                                }else{                                    $arrayHour[] = $value." ".substr($HourStartDateTimeTer, 0, 2).":30:00 - ".(substr($HourStartDateTimeTer, 0, 2)+1).":00:00";                                    $content .= '<th style="border:1px solid black;width:3%;font-size:5px;vertical-align:middle;text-align:center;" id="'.substr($HourStartDateTimeTer,0,2).':30:00 - '.(substr($HourStartDateTimeTer,0,2)+1).':00:00'.'">'.substr($HourStartDateTimeTer,0,2).'H30 - '.(substr($HourStartDateTimeTer,0,2)+1).'H00'.'</th>';                                }                            }                        }else{                            if($i != substr($HoursEndDateTimeTer, 0, 2)){                                if(substr($PauseMidi, 3, 2) == "30" && $i == (substr($PauseMidi, 0, 2)+1)){                                    $arrayHour[] = $value." ".$i.":30:00 - ".($i+1).":00:00";                                    $content .= '<th style="border:1px solid black;width:3%;font-size:5px;vertical-align:middle;text-align:center;" id="'.$i.':30:00 - '.($i+1).':00:00'.'">'.$i.'H30 - '.($i+1).'H00'.'</th>';                                }else{                                    if($i<=9){                                        $arrayHour[] =  $value." "."0".$i.":00:00 - 0".$i.":30:00";                                        $content .= '<th style="border:1px solid black;width:3%;font-size:5px;vertical-align:middle;text-align:center;" id="0'.$i.':00:00 - 0'.$i.':30:00'.'">0'.$i.'H00 - 0'.$i.'H30'.'</th>';                                        if($i+1<=9){                                            $arrayHour[] =  $value." "."0".$i.":30:00 - 0".($i+1).":00:00";                                            $content .= '<th style="border:1px solid black;width:3%;font-size:5px;vertical-align:middle;text-align:center;" id="0'.$i.':30:00 - 0'.($i+1).':00:00'.'">0'.$i.'H30 - 0'.($i+1).'H00'.'</th>';                                        }else{                                            $arrayHour[] =  $value." "."0".$i.":30:00 - ".($i+1).":00:00";                                            $content .= '<th style="border:1px solid black;width:3%;font-size:5px;vertical-align:middle;text-align:center;" id="0'.$i.':30:00 - '.($i+1).':00:00'.'">0'.$i.'H30 - '.($i+1).'H00'.'</th>';                                        }                                    }else{                                        $arrayHour[] =  $value." ".$i.":00:00 - ".$i.":30:00";                                        $arrayHour[] =  $value." ".$i.":30:00 - ".($i+1).":00:00";                                        $content .= '<th style="border:1px solid black;width:3%;font-size:5px;vertical-align:middle;text-align:center;" id="'.$i.':00:00 - '.$i.':30:00'.'">'.$i.'H00 - '.$i.'H30'.'</th>';                                        $content .= '<th style="border:1px solid black;width:3%;font-size:5px;vertical-align:middle;text-align:center;" id="'.$i.':30:00 - '.($i+1).':00:00'.'">'.$i.'H30 - '.($i+1).'H00'.'</th>';                                    }                                }                            }else{                                if(substr($HoursEndDateTimeTer, 2, 2) == "30"){                                    $arrayHour[] =  $value." ".$i.":00:00 - ".$i.":30:00";                                    $content .= '<th style="border:1px solid black;width:3%;font-size:5px;vertical-align:middle;text-align:center;" id="'.$i.':00:00 - '.$i.':30:00'.'">'.$i.'H00 - '.$i.'H30'.'</th>';                                }                            }                        }                    }                }                $content .= '</tr>';                $content .=  '<tbody>';                /*------------------------------------------------------------------------------------------------------------*/                $thLunch = $purchasingFairConcerned->getLunchBreak();                // Salespersons concerned                foreach($arrayProviderPresent as $key1 => $provider) { //pour chaque fournisseur                    $arraySP = $appService->findAllParticipantsAsSalespersonsByProviderAndPf($provider->getOneProvider()->getIdEnterprise(), $_SESSION['purchasingFairConcerned']->getIdPurchasingFair());                    //print_r($provider->getOneProvider()->getName());                    foreach ($arraySP as $key2 => $commercial) { //pour chaque comercial                        //print_r($commercial->getName());                        //rdvs d'un jour avec un fournisseur, un commercial lors d'un PF                        $AllRdvStore = $appService->findRdvFournisseurByQuattroBis($provider->getOneProvider()->getIdEnterprise(), $purchasingFairConcerned->getIdPurchasingFair(), substr($arrayHour[0],0,10), $commercial->getIdParticipant());                        //print_r($AllRdvStore);                        if($AllRdvStore != null){                            $DifferentsMagasins = array(); //différents magasins                            foreach ($AllRdvStore as $key => $value1) {                                if($DifferentsMagasins == null){                                    $DifferentsMagasins[] = [                                        "0" => $value1->getIdStore(),                                        "1" => $appService->findOneEnterprise($value1->getIdStore())->getName(),                                    ];                                }else{                                    $temoin = "0";                                    foreach ($DifferentsMagasins as $key => $value2) {                                        if($value2["0"] == $value1->getIdStore()){                                            $temoin = "1";                                        }                                    }                                    if($temoin == "0"){                                        $DifferentsMagasins[] = [                                            "0" => $value1->getIdStore(),                                            "1" => $appService->findOneEnterprise($value1->getIdStore())->getName(),                                        ];                                    }                                }                            }                            //print_r($DifferentsMagasins);                            $content .= '<tr>';                            $content .= '<td style="background-color:'.$colors1[$provider->getOneProvider()->getIdEnterprise()*10].'; border:solid black 1px;">'.$provider->getOneProvider()->getName().'</td>';                            $content .= '<td style="background-color:'.$colors1[$commercial->getIdParticipant()*10].'; border:solid black 1px">'.$commercial->getSurname().' '.$commercial->getName().'</td>';                            //print_r("expression");                            $colors2 = $appService->generateNColors('35353');                            foreach ($arrayHour as $key3 => $CreneauHoraire) {                                //print_r(0);                                $ResultArray = [];                                foreach ($DifferentsMagasins as $key => $DF) {                                    //on récupère les prises de RDV d'un jour avec un fournisseur un commecial et un magasin                                    $CommercialRDV = $appService->findRdvFournisseurByCinq($provider->getOneProvider()->getIdEnterprise(), $purchasingFairConcerned->getIdPurchasingFair(), substr($arrayHour[0],0,10), $DF[0], $commercial->getIdParticipant());                                    //print_r($CommercialRDV);                                    $arrayAllPriseRDVForAllDays = [];                                    $JourSalon = substr($arrayHour[0],0,10);                                    /*------------------------------------------------------------------------------------------*/                                    $arrayAllPriseRDVForOneDay = array();                                    if($CommercialRDV != null){                                        foreach ($CommercialRDV as $key => $value) {                                            if($JourSalon == $value->getJourString()){                                                $arrayAllPriseRDVForOneDay[] = $appService->getHourFromRange(substr($value->getStartDatetime(),11,5), substr($value->getEndDatetime(),11,5));                                            }                                        }                                    }                                    $arrayAllPriseRDVForAllDays[] = $arrayAllPriseRDVForOneDay; //array des prises de rdv d'une journée pour un fournisseur                                    $ResultArray[] = [$DF[0], $DF[1], $arrayAllPriseRDVForAllDays];                                }                                //print_r($arrayAllPriseRDVForAllDays);                                if(count($ResultArray)<2){                                    foreach ($ResultArray as $key => $value) {                                        $obj0 = $value[0];//id Magasin                                        $obj1 = $value[1];//nom magasin                                        $obj2 = $value[2];//arrayAllPriseRDVForAllDays                                        $horaire = substr($CreneauHoraire, 11); //le créneau horaire                                        if(substr($horaire, 0, 8) != $thLunch){                                            $ref = "0";                                            $cpt = 0;                                            // affichage des RDV magasins - commerciaux                                            foreach ($obj2 as $key => $HeureIndispo2) {                                                //print_r($HeureIndispo2);                                                foreach ($HeureIndispo2 as $key => $HeureIndispo2bis) {                                                    //print_r($HeureIndispo2bis);                                                    foreach ($HeureIndispo2bis as $key => $HeureIndispo2bisbis) {                                                        if($horaire == $HeureIndispo2bisbis){                                                            //console.log(HeureIndispo2bis);                                                            //print_r($HeureIndispo2bisbis);                                                            $cpt = $cpt + 50;                                                            $ref = "1";                                                            $content .= '<td style="background-color:'.$colors2[$cpt*5].';border:1px solid black;width:3%;font-size:5px; font-weight:bold; vertical-align:middle;">'.$obj1.'</td>';                                                        }                                                    }                                                }                                            }                                            // affichage des disponibilitées pour les prise de RDV                                            if($ref == "0"){                                                $content .= '<td style="border:1px solid black;width:4%;font-size:5px;vertical-align:middle;text-align:center;width:3%"></td>';                                            }                                        }else{                                            $content .= '<td style="background-color:#f1f3f6;vertical-align:middle;text-align:center;border:solid black 1px;font-size:5px;width:3%"></td>';                                        }                                    }                                }else{                                    $reference = "0";                                    foreach ($ResultArray as $key => $value) {                                        $obj0 = $value[0];//id Magasin                                        $obj1 = $value[1];//nom magasin                                        $obj2 = $value[2];//arrayAllPriseRDVForAllDays                                        $horaire = substr($CreneauHoraire, 11); //le créneau horaire                                        $ref = "0";                                        if(substr($horaire, 0, 8) != $thLunch){                                            // affichage des RDV magasins - commerciaux                                            foreach ($obj2 as $key => $HeureIndispo2) {                                                //print_r($HeureIndispo2);                                                foreach ($HeureIndispo2 as $key => $HeureIndispo2bis) {                                                    //print_r($HeureIndispo2bis);                                                    foreach ($HeureIndispo2bis as $key => $HeureIndispo2bisbis) {                                                        if($horaire == $HeureIndispo2bisbis){                                                            $reference = "1";                                                            $ref = "1";                                                            $mag = $obj1;                                                        }                                                    }                                                }                                            }                                            // affichage des disponibilitées pour les prise de RDV                                            if($ref == "0" && $reference != "1"){                                                $reference = "0";                                            }                                        }else{                                            $reference = "2";                                        }                                    }                                    if($reference == "0"){                                        $content .= '<td style="border:1px solid black;width:4%;font-size:5px;vertical-align:middle;text-align:center;width:3%"></td>';                                    }else if($reference == "1"){                                        $content .= '<td style="background-color:'.$colors2[$obj0*10].';border:1px solid black;width:3%;font-size:7px; font-weight:bold; vertical-align:middle;">'.$mag.'</td>';                                    }else if($reference == "2"){                                        $content .= '<td style="background-color:#f1f3f6;vertical-align:middle;text-align:center;border:solid black 1px;font-size:5px;width:3%"></td>';                                    }                                }                            } //foreach hour                            $content .= '</tr>';                        } //end $AllRdvStore != null                    }            }                $content .=  '</tbody>                        </table>                    </div>';                    $RepasExceptionnels = $appService->findOneSpecialGuestByDay($purchasingFairConcerned->getIdPurchasingFair(), $datedate);                    //print_r($RepasExceptionnels);                    $nbRepas = $appService->findLunchByPfAndDay($purchasingFairConcerned->getIdPurchasingFair(), $datedate);                    $numberRepas = 0;                    if($nbRepas != null){                        foreach ($nbRepas as $key => $d) {                            $numberRepas = $numberRepas + $d->getLunchesPlanned();                        }                    }                    if($RepasExceptionnels != null){                        foreach ($RepasExceptionnels as $key => $re) {                            $numberRepas = $numberRepas + 1;                        }                    }                    //print_r($numberRepas);                    $content .='<br>';                $content .='<span>Nombre de repas du '.$datedate.' : '.$numberRepas.'</span>';            $content .= ' <page backtop="18mm" backbottom="18mm" backleft="10mm" backright="10mm"> </page>';        }        /*-----------------------------------------------------------------------------------------------------------------------------------*/    echo $content;    $content = ob_get_clean();    try {        $html2pdf = new HTML2PDF('L', 'A3', 'fr'); // Portrait / A4 / French        $html2pdf -> pdf -> setTitle('recap_saisie_'.date('Y-m-d-H-i')); // Title in pdf viewer        $html2pdf -> pdf -> setDisplayMode('fullpage'); // If output not D, display the pdf in the entire page        $html2pdf -> writeHTML($content);        ob_clean();        $html2pdf-> Output('recap_saisie_'.date('Y-m-d-H-i').'.pdf', 'I'); // I = Show in browser, Force Download = D    } catch(HTML2PDF_exception $e) { die($e); }}?>