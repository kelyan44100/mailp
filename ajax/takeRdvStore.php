<?php
require_once dirname ( __FILE__ ) . '/../services/AppServiceImpl.class.php';

if(!isset($_SESSION)) session_start(); // Start session

$appService = AppServiceImpl::getInstance();

	$arrayEnterprises = $appService->findAllEnterprisesAsStores();

	if( isset($_SESSION['enterpriseConcerned']) && !empty($_SESSION['enterpriseConcerned'])&& isset($_SESSION['purchasingFairConcerned']) && !empty($_SESSION['purchasingFairConcerned']) && isset($_POST['StartTime'])  && !empty($_POST['StartTime'])&& isset($_SESSION['arrayIndisposProviderForAllDaysBis'])  && !empty($_SESSION['arrayIndisposProviderForAllDaysBis'])&& isset($_SESSION['arrayGlobale'])  && !empty($_SESSION['arrayGlobale']) && isset($_POST['tableAllIdFournisseurs'])  && !empty($_POST['tableAllIdFournisseurs']) && isset($_POST['dureerdv'])  && !empty($_POST['dureerdv'])) {

        if(!(isset($_POST['JourSelect'])  && !empty($_POST['JourSelect']))){
             print_r("Error6");
             die();
        }
        if(!(isset($_POST['idFournisseur'])  && !empty($_POST['idFournisseur']))){
             print_r("Error7");
             die();
        }
    
        $arrayIndisposProviderForAllDaysBis = $_SESSION['arrayIndisposProviderForAllDaysBis'];
        $arrayGlobale = $_SESSION['arrayGlobale'];
        //print_r(substr(end($arrayGlobale["ArrayHoursDay"])[2], 8));
        $tableAllIdFournisseurs = $_POST['tableAllIdFournisseurs'];

        $NomCommercial = $_POST['NomCommercial'];
        $NomEntreprise = $_POST['NomFournisseur'];
        $arrayListDateTableau = $_SESSION['arrayListDateTableau'];

        $idFournisseur = $_POST['idFournisseur'];
        $enterpriseConcerned = $_SESSION['enterpriseConcerned'];
        $purchasingFairConcerned = $_SESSION['purchasingFairConcerned'];
        $JourSelect = $_POST['JourSelect']; // format 2019-04-20
        $StartTime = $_POST['StartTime']; 
		$convstarttime = DateTime::createFromFormat('H:i:s', $StartTime);
		$tstarttime=$convstarttime->format('U');
        $StartTime = substr($StartTime, 0,5);// format 09:00
        $StartTimeBis = substr($StartTime, 0,2).substr($StartTime, 3,2).'00'; // format 090000
		
		$dureerdv = $_POST['dureerdv'];
		//$convdureerdv = DateTime::createFromFormat('H:i', $dureerdv);
		//$tdureerdv=$convdureerdv->format('U');
		$dureerdv = substr($dureerdv, 0,5);// format 09:00
        $sdureerdv=(substr($dureerdv, 0,2)*3600)+(substr($dureerdv, 3,2)*60);
		
		
		$dureerdvbis = substr($dureerdv, 0,2).substr($dureerdv, 3,2).'00'; // format 090000
        
		//print_r($dureerdv."-");
		//$posdr=strpos($dureerdv,":");
		//$heuredr=substr($dureerdv,0,$posdr);
		//$minutes=substr($dureerdv,$posdr);
        //$EndTime = date("H:i", strtotime(substr($StartTime, 0, 5)) + strtotime($dureerdv)); // format 09:30
		//$EndTime = strtotime($StartTime) + strtotime($dureerdv); // format 09:30
		//$EndTime=date("H:i",$EndTime);
		//print_r($EndTime."-");
        //$EndTimeBis = substr($EndTime, 0,2).substr($EndTime, 3,2).'00'; // format 093000
		
		
		
		$tendtime=$tstarttime+$sdureerdv;
		$EndTimeBis = date("His",$tendtime);
		//print_r($EndTimeBis);
		if (strlen($EndTimeBis)==5)
		{
			$EndTimeBis="0".$EndTimeBis;
		}
		$EndTime=substr($EndTimeBis,0,2).":".substr($EndTimeBis,2,2);
		//print_r($EndTime);
        $startDatetime = $JourSelect.' '.$StartTime.':00'; // format 2019-04-29 09:00:00
        $endDatetime = $JourSelect.' '.$EndTime.':00'; // format 2019-04-29 09:30:00

        $thLunch = $_POST['thLunch']; //12:00:00 - 13:00:00
        $thLunchStart = substr($thLunch, 0,2).substr($thLunch, 3,2).'00'; //120000
        $thLunchEnd = substr($thLunch, 11,2).substr($thLunch, 14,2).'00'; //130000

        $arraySurbrillance = array();


        //print_r($thLunchEnd);

        //print_r($JourSelect);
        //print_r($arrayIndisposProviderForAllDays);

        //print_r($arrayIndisposProviderForAllDaysBis);
		/* print_r($StartTimeBis."-");
        print_r($EndTimeBis."-"); */
		/* if($StartTimeBis < $EndTimeBis){
			print_r("vrai");
		}
		else
		{
			print_r("false");
		} */
        //print_r(substr($arrayGlobale["end"], 8));

        if($StartTimeBis < $EndTimeBis){

            if($EndTimeBis <= substr(end($arrayGlobale["ArrayHoursDay"])[2], 8)){

                if(($EndTimeBis <= $thLunchStart && $StartTimeBis < $thLunchStart) || ($EndTimeBis > $thLunchEnd && $StartTimeBis >= $thLunchEnd)){

                    $SpAssignedToStore = $appService->findOneAssignmentSpStoreQuatro($enterpriseConcerned->getIdEnterprise(), $idFournisseur, $purchasingFairConcerned->getIdPurchasingFair());

                    //print_r($SpAssignedToStore);

                    $newPriseRdv = $appService->createPriseRdvStore($enterpriseConcerned->getIdEnterprise(), $idFournisseur, $SpAssignedToStore->getOneParticipant()->getIdParticipant(), $purchasingFairConcerned->getIdPurchasingFair(), $startDatetime, $endDatetime, $JourSelect, $StartTimeBis, $EndTimeBis);

                    //on regarde s'il y a des rdv qui existent avec tt identique sauf l'end datetime
                    $testFind = $appService->findByAllBis($newPriseRdv->getIdStore(), $newPriseRdv->getIdFournisseur(), $newPriseRdv->getIdCommercial(), $newPriseRdv->getIdPurchasingFair(), $newPriseRdv->getStartDatetime());
                    
                    //print_r($testFind);
                    $try = "0";
                    if($testFind != null){
                        $try = "1";
                    }

                    $signal = "0";

                    //if($testFind == null){

                        $ref4 = 0;
                        $cpt = 0;
                        $ref10 = 0;
                        foreach ($tableAllIdFournisseurs as $key => $value) {
                            if($ref10 != 1){
                                if($idFournisseur != $value[0]){
                                    $cpt ++;
                                }else{
                                    $ref10 = 1;
                                }
                            }
      
                        }
                        //print_r($cpt);

                        //print_r($jour);
                        // foreach sur toutes les indispos du fournisseur séléctionné (pour chaque JOUR)
                        //$cpt2 = -1;
                        foreach ($arrayIndisposProviderForAllDaysBis as $key => $jour) {
                            
                            if($jour != null){//si le jour contient des indispos (si l'array n'est pas vide)

                                if($key == $cpt){ //substr($jour[0], 0, 10) : "ex : 2019-04-23"
                                    $newArray = array();
                                    $ref4 = 1;
                                    foreach ($jour as $key => $heure) {
                                        $newArray[] = $heure;
                                    }
                                    //print_r($newArray); //array contenant toutes les indispos du jour ou on souhaite prendre le rendez-vous
                                }

                            }
                            
                        }

                       //print_r($newArray);

                        if($ref4 != 1){ //si le jour durant lesquel on souhaite prendre le RDV ne contient aucune indispo fournisseur
                            //print_r("aucune indispo fournisseur");
                            //print_r("test1");

                            $ListRdvJourWthOtherProvider = $appService->findRdvSameDayWithOtherProvider($enterpriseConcerned->getIdEnterprise(), $purchasingFairConcerned->getIdPurchasingFair(), $idFournisseur, $JourSelect, $StartTimeBis, $EndTimeBis);
                            //print_r($ListRdvJourWthOtherProvider);

                            /*le créneau de RDV n'est pas placé sur un RDV pris le même jour avec un autre fournisseur*/
                            if($ListRdvJourWthOtherProvider == null){
                                //print_r("ListRdvJourWthOtherProvider");
                                $ListRdvSameHourWithOtherStoreWithSp = $appService->findRdvSameHourWithOtherStoreWithSp($purchasingFairConcerned->getIdPurchasingFair(), $SpAssignedToStore->getOneParticipant()->getIdParticipant(), $JourSelect, $StartTimeBis, $EndTimeBis, $enterpriseConcerned->getIdEnterprise());

                                //print_r($ListRdvSameHourWithOtherStoreWithSp);

                                /*le créneau de RDV n'est pas placé sur un RDV pris durant le même horaire par un autre magasin avec le commerciale*/
                                if($ListRdvSameHourWithOtherStoreWithSp == null){
                                    $signal ="1";
                                }
                                
                            }

                        }else{
                            //print_r("présence d'indispos fournisseur");
                        
                            if($ref4 == 1){

                                // début d'indispo au format : "080000"
                                $start = substr($newArray[0], 11, 2).substr($newArray[0], 14, 2).substr($newArray[0], 17, 2);
                                // début d'indispo au format : "190000"
                                $end = substr(end($newArray), 22, 2).substr(end($newArray), 25, 2).substr(end($newArray), 28, 2);

                                //print_r($end);
                                /*le créneau de RDV n'est pas placé sur une indispo commerciale*/
                                if($EndTimeBis <= $start || $end <= $StartTimeBis){ 
                                    //print_r("probleme");
                                    
                                    $ListRdvJourWthOtherProvider = $appService->findRdvSameDayWithOtherProvider($enterpriseConcerned->getIdEnterprise(), $purchasingFairConcerned->getIdPurchasingFair(), $idFournisseur, $JourSelect, $StartTimeBis, $EndTimeBis);
                                    //print_r($ListRdvJourWthOtherProvider);
                                    /*le créneau de RDV n'est pas placé sur un RDV pris le même jour avec un autre fournisseur*/
                                    if($ListRdvJourWthOtherProvider == null){

                                        $ListRdvSameHourWithOtherStoreWithSp = $appService->findRdvSameHourWithOtherStoreWithSp($purchasingFairConcerned->getIdPurchasingFair(), $SpAssignedToStore->getOneParticipant()->getIdParticipant(), $JourSelect, $StartTimeBis, $EndTimeBis, $enterpriseConcerned->getIdEnterprise());

                                        //print_r($ListRdvSameHourWithOtherStoreWithSp);

                                        /*le créneau de RDV n'est pas placé sur un RDV pris durant le même horaire par un autre magasin avec le commerciale*/
                                        if($ListRdvSameHourWithOtherStoreWithSp == null){

                                            $signal ="1";

                                        }

                                    }

                                }else{
                                    print_r("Error5");//Error5
                                    die();
                                }

                            }
                        }
                        if($signal == "1"){

                            $IdParticipant = $appService->findOneAssignmentSpStoreQuatro($enterpriseConcerned->getIdEnterprise(), $idFournisseur, $_SESSION['purchasingFairConcerned']->getIdPurchasingFair());

                            $lunchParDay = $appService->findLunchForOneEnterpriseAndPfAndDayBis($idFournisseur, $_SESSION['purchasingFairConcerned']->getIdPurchasingFair(), $JourSelect, $IdParticipant->getOneParticipant()->getIdParticipant());

                            $repas = $appService->createLunch($idFournisseur, $_SESSION['purchasingFairConcerned']->getIdPurchasingFair(), 1, 0, $JourSelect, $IdParticipant->getOneParticipant()->getIdParticipant());


                            if($try == "0"){
                                //print_r("0");
                                $saveNewPriseRdv = $appService->savePriseRdvStore($newPriseRdv);
                                //print_r($lunchParDay);
                                if($lunchParDay != null){
                                    $appService->updateLunch($repas);
                                }else{
                                    $appService->saveLunch($repas);
                                }

                            }else{
                                //print_r("1");
                                //print_r($testFind);
                                $appService->deleteRDV($testFind[0]->getIdPurchasingFair(), $testFind[0]->getIdStore(), $testFind[0]->getIdFournisseur(), $testFind[0]->getStartDatetime());
                                $saveNewPriseRdv = $appService->savePriseRdvStore($newPriseRdv);
                            }

                            $signal = "0";
                            //print_r($newPriseRdv);

                            

                            //print_r("Success");

                            /*$test = [$arrayTpm, $arrayIndisposProviderForAllDays, $NomCommercial, $NomEntreprise];

                            $contenu_json = json_encode($test);

                            print_r($contenu_json);*/
                        }else{
                            print_r("Error4");//Error4
                            die();
                        }     

                    /*}else{
                        print_r("Error3");//Error3
                        die();
                    }*/

                }else{
                    print_r("Error8");//Error8
                    die();
                }

            }else{
                print_r("Error9");//Error2
                die();
            }

        }else{
            print_r("Error2");//Error2
            die();
        }

        /* ----------------------------------------------------------------------*/

        $arrayAllPriseRDVForAllDays = array();//array des prises de rdv d'une journée pour un fournisseur
        $arrayIndisposRelatedToRdvWithOtherProvider = array();
        $arrayIndisposRelatedToSameHourWithOtherStoreWithSp = array();

        //print_r($arrayGlobale);

        $ResultArray = array();
        
        //print_r($tableAllIdFournisseurs);

        foreach ($tableAllIdFournisseurs as $key => $idF) {

            
            $fr = $appService->findByQuatre($enterpriseConcerned->getIdEnterprise(), $purchasingFairConcerned->getIdPurchasingFair(), $idF[0], substr($arrayListDateTableau[0],0,10));
            
            $arraySurbrillance = [];

            if($fr != null){
                $arraySurbrillanceBis = [];
                foreach ($fr as $key => $value) {
                    $arraySurbrillanceBis[] = [
                        "jourRdv" => substr($value->getJourString(), 8, 2).' '.$appService->nom_mois($value->getJourString()),
                        "heureDebutRdv" => substr($value->getStartDatetime(), 11, 8),
                        "heureFinRdv" => substr($value->getEndDateTime(), 11, 8),
                    ];
                }
                $arraySurbrillance = [
                    "idFr" => $idF[0],
                    "RdvAutreJour" => $arraySurbrillanceBis,
                ];
            }

            $SpAssignedToStorebis = $appService->findOneAssignmentSpStoreQuatro($enterpriseConcerned->getIdEnterprise(), $idF[0], $purchasingFairConcerned->getIdPurchasingFair());

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

            $arrayIndisposProviderForOneDay = [];
            $arrayIndisposProviderForAllDays = [];

            //print_r($arrayGlobale);

            $JourSalon = $arrayGlobale;

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

                $ObjectIndisposOtherProvider = $appService->findIndisposOtherProvider($enterpriseConcerned->getIdEnterprise(), $purchasingFairConcerned->getIdPurchasingFair(), $idF[0], $JourSalon['jour']);

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
                //print_r("test");

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

                foreach($FourniseurIndispo as $value) {

                    $start = $value->getStartDatetime();
                    // début d'indispo au format : "20190423080000"
                    $startBis = substr($start, 0, 4).substr($start, 5, 2).substr($start, 8, 2).substr($start, 11, 2).substr($start, 14, 2).substr($start, 17, 2);

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
                    //$resultat[] = substr($value, 8, 2).':'.substr($value, 10, 2).':'.substr($value, 12, 2);
                }

                $arrayIndisposProviderForAllDays[] = $resultat;

            //} //end arrayGlobale

            //print_r($arrayAllPriseRDVForAllDays);
            //print_r($arrayIndisposRelatedToRdvWithOtherProvider);
            //print_r($arrayIndisposRelatedToSameHourWithOtherStoreWithSp);

            //print_r($arrayIndisposProviderForAllDays);

            $ResultArray[] = [$arrayIndisposProviderForAllDays, $idF[4], $idF[3], $arrayAllPriseRDVForAllDays, $arrayIndisposRelatedToRdvWithOtherProvider, $arrayIndisposRelatedToSameHourWithOtherStoreWithSp, $idF[0], $idF[1],$arraySurbrillance];

        }

        //print_r($ResultArray);

        /*$tabTest = array();

        $tabTest[] = [$arrayIndisposProviderForAllDays, $NomCommercial, $NomEntreprise, $arrayAllPriseRDVForAllDays, $arrayIndisposRelatedToRdvWithOtherProvider, $arrayIndisposRelatedToSameHourWithOtherStoreWithSp];*/

        $contenu_json = json_encode($ResultArray);

        print_r($contenu_json);

        die();
            
    }else{
        print_r("Error1"); //Error1
        die();
    }


?>