<?php
require_once dirname ( __FILE__ ) . '/../services/AppServiceImpl.class.php';

if(!isset($_SESSION)) session_start(); // Start session

$appService = AppServiceImpl::getInstance();

    if( isset($_SESSION['enterpriseConcerned']) && !empty($_SESSION['enterpriseConcerned']) && isset($_SESSION['purchasingFairConcerned']) && !empty($_SESSION['purchasingFairConcerned']) && isset($_POST['idCommercial']) && !empty($_POST['idCommercial'])) {

        $enterpriseConcerned = $_SESSION['enterpriseConcerned'];
        $purchasingFairConcerned = $_SESSION['purchasingFairConcerned'];
        $idCommercial = $_POST['idCommercial'];

        $arrayDays = array('DIMANCHE','LUNDI','MARDI','MERCREDI','JEUDI','VENDREDI','SAMEDI');

        $startDatetime = DateTime::createFromFormat('Y-m-d H:i:s', $purchasingFairConcerned->getStartDatetime());
        $endDatetime   = DateTime::createFromFormat('Y-m-d H:i:s', $purchasingFairConcerned->getEndDatetime());

        $counterArrayPlanningDays= 0;

        while($startDatetime < $endDatetime) { 
            
            if($arrayDays[$startDatetime->format('w')] != 'SAMEDI' && $arrayDays[$startDatetime->format('w')] != 'DIMANCHE') {
                $startAt = clone $startDatetime;
                $endAt   = clone $startDatetime;
                $endAt->setTime(19,0,0);
                $arrayPlanningDays[] = new PlanningDay(++$counterArrayPlanningDays, $startAt, $endAt, array(), array());
            }
            $startDatetime->add( new DateInterval('P1D') );
        }

        $jourRepas = $appService->findCommerciauxFournisseurByThreeBis($enterpriseConcerned->getIdEnterprise(), $purchasingFairConcerned->getIdPurchasingFair(), $idCommercial);
        $arrayresult = array();

        foreach ($arrayPlanningDays as $key => $value) {

            $VerifCheck = $appService->findLunchForOneEnterpriseAndPfAndDayBis($enterpriseConcerned->getIdEnterprise(), $purchasingFairConcerned->getIdPurchasingFair(), $value->getStartDatetime()->format('Y-m-d'), $idCommercial);
            //print_r($VerifCheck);
            $tmp = "";
            if($VerifCheck != null){
                $tmp = "checked";
            }
            $arrayresult[] = [$value->getStartDatetime()->format('Y-m-d'), $appService->nom_jour($value->getStartDatetime()->format('Y-m-d')), substr($value->getStartDatetime()->format('Y-m-d'),8,2), $appService->nom_mois($value->getStartDatetime()->format('Y-m-d')), $tmp];
           
        }

        $contenu = json_encode($arrayresult);

        print_r($contenu);

        die();

    }else{
        echo 'Error';
        die();
    }

?>