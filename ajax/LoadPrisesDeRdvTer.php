<?php

    $DaySelect=0;
    $arrayGlobale = array(); //array comportant : "jour, start, end, ArrayHoursDay" pour chaque jours du salon
    $ArrayDayHourBis = array(); // liste des id des th au format : [0] => {[1] => "20190423080000"; [2] =>"20190423083000"}, [1] => ...
    $essai = "";

        $value = $arrayHour;

        $ArrayDayHourBis = [];

        $size = sizeof($value)-1;
        //print_r($size);
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

    $AllRdvStore = $appService->findThreePriseRdvStoreBis($enterpriseConcerned->getIdEnterprise(), $purchasingFairConcerned->getIdPurchasingFair(), substr($arrayHour[0],0,10));

    
   

    if($AllRdvStore != null){



        $DifferentsFournisseurs = array();

        foreach ($AllRdvStore as $key => $value1) {
            if($DifferentsFournisseurs == null){
                $DifferentsFournisseurs[] = [
                    "idF" => $value1->getIdFournisseur(),
                    "nomF" => $appService->findOneEnterprise($value1->getIdFournisseur())->getName(),
                    "idC" => $value1->getIdCommercial(),
                    "nomC" => $appService->findOneParticipant($value1->getIdCommercial())->getSurname(),
                ];
            }else{
                $temoin = "0";
                foreach ($DifferentsFournisseurs as $key => $value2) {
                    if($value2["idF"] == $value1->getIdFournisseur()){
                        $temoin = "1";
                    }
                }
                if($temoin == "0"){
                    $DifferentsFournisseurs[] = [
                        "idF" => $value1->getIdFournisseur(),
                        "nomF" => $appService->findOneEnterprise($value1->getIdFournisseur())->getName(),
                        "idC" => $value1->getIdCommercial(),
                        "nomC" => $appService->findOneParticipant($value1->getIdCommercial())->getSurname(),
                    ];
                }
            }
        }
        //print_r($DifferentsFournisseurs);

        foreach ($DifferentsFournisseurs as $key => $DF) {

            $SpAssignedToStorebis = $appService->findOneAssignmentSpStoreQuatro($enterpriseConcerned->getIdEnterprise(), $DF["idF"], $purchasingFairConcerned->getIdPurchasingFair());

            //on récupère les prises de RDV du store avec son commercial
            $CommercialRDV = $appService->findOnePriseRdvByIdCommercialAndPF($SpAssignedToStorebis->getOneParticipant()->getIdParticipant(), $enterpriseConcerned->getIdEnterprise(), $purchasingFairConcerned->getIdPurchasingFair());

            /*on récupère les indispos du commercial qui est attribué au magasin*/
            //$FourniseurIndispo = $appService->findAllUnavailabilitiesSpByParticipant($SpAssignedToStorebis->getOneParticipant()->getIdParticipant());
            $FourniseurIndispo = $appService->findParticipantUnavailabilitiesSp($SpAssignedToStorebis->getOneParticipant(), $purchasingFairConcerned);

            //print_r($CommercialRDV);

            $arrayAllPriseRDVForAllDays = [];
            $arrayIndisposRelatedToRdvWithOtherProvider = [];
            $arrayIndisposRelatedToSameHourWithOtherStoreWithSp = [];

            $arrayIndisposProviderForOneDay = array();
            $arrayIndisposProviderForAllDays = array();

            foreach ($arrayGlobale as $key => $JourSalon) {
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
                $ObjectIndisposOtherProvider = $appService->findIndisposOtherProvider($enterpriseConcerned->getIdEnterprise(), $purchasingFairConcerned->getIdPurchasingFair(), $DF["idF"], $JourSalon['jour']);

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
                }

                $arrayIndisposProviderForAllDays[] = $resultat;

            } //end arrayGlobale

            //print_r($arrayAllPriseRDVForAllDays);
            //print_r($arrayIndisposRelatedToRdvWithOtherProvider);
            //print_r($arrayIndisposRelatedToSameHourWithOtherStoreWithSp);

            //print_r($arrayIndisposProviderForAllDays);

            $ResultArray[] = [$arrayIndisposProviderForAllDays, $DF["nomC"], $DF["nomF"], $arrayAllPriseRDVForAllDays, $arrayIndisposRelatedToRdvWithOtherProvider, $arrayIndisposRelatedToSameHourWithOtherStoreWithSp, $DF["idF"]];

        }

        /*$contenu_json = json_encode($ResultArray);

        print_r($contenu_json);*/

    }else{
        
    }

?>