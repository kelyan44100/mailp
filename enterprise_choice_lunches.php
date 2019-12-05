<?php
require_once dirname ( __FILE__ ) . '/view/errors.inc.php';
require_once dirname ( __FILE__ ) . '/services/AppServiceImpl.class.php';
require_once dirname ( __FILE__ ) . '/domain/PlanningDay.class.php';

if(!isset($_SESSION)) session_start(); // Start session

// Not connected as Admin ?
if(!isset($_SESSION['enterpriseConcerned']) && empty($_SESSION['enterpriseConcerned'])) {
    header('Location: ./disconnection.php'); // Redirection to Purchasing Fair list
    die(); // To prevent log error
}
ini_set('xdebug.var_display_max_depth', -1);
ini_set('xdebug.var_display_max_children', -1);
ini_set('xdebug.var_display_max_data', -1);

$appService = AppServiceImpl::getInstance();


if( isset($_POST) && !empty($_POST) ) {
        
    global $appService;
    
    // POST data
    $idEnterprise     = (int) $_SESSION['enterpriseConcerned']->getIdEnterprise();
    $idPurchasingFair = (int) $_SESSION['purchasingFairConcerned']->getIdPurchasingFair();
    $presentDetails   = (array) $_POST['days'];
    $participants     = (array) $_POST['selectParticipants'];

    //print_r($participants[0]);
    
    // For Lunches
    $lunchesDetailsDays = array();
    $lunchesDetailsDaysN = array();
    
    foreach($_POST as $key => $value) {
        
        // Check key
        if($key != 'selectParticipants' && $key != 'days') {
            
            // radioData[0] = 'radio' / radioData[1] = 'YYYY-mm-dd'
            $radioData = explode('_', $key);
            
            if($_POST[$key] == 'Y') { $lunchesDetailsDays[] = $radioData[1]; }
            if($_POST[$key] == 'N') { $lunchesDetailsDaysN[] = $radioData[1]; }
        }
    }

    //print_r($lunchesDetailsDays);

    foreach ($lunchesDetailsDays as $key => $value) {
        //print_r($value);
        $lunchParDay = $appService->findLunchForOneEnterpriseAndPfAndDayBis($idEnterprise, $idPurchasingFair, $value, $participants[0]);
        //print_r($lunchParDay);
        $repas = $appService->createLunch($_SESSION['enterpriseConcerned']->getIdEnterprise(), $_SESSION['purchasingFairConcerned']->getIdPurchasingFair(), count($participants), 0, $value, $participants[0]);
        if($lunchParDay != null){
            $appService->updateLunch($repas);
        }else{
            $appService->saveLunch($repas);
        }
    }

    foreach ($lunchesDetailsDaysN as $key => $value2) {

        $lunchParDay2 = $appService->findLunchForOneEnterpriseAndPfAndDayBis($idEnterprise, $idPurchasingFair, $value2, $participants[0]);

        if($lunchParDay2 != null){
            $appService->DeleteLunchByFour($idEnterprise, $idPurchasingFair, $value2, $participants[0]);
        }

    }

}

header( 'content-type: text/html; charset=utf-8' ); // Specifies to the server to return UTF-8 - put in prod

$lunchEnterprise = $appService->findLunchForOneEnterpriseAndPf($_SESSION['enterpriseConcerned']->getIdEnterprise(), $_SESSION['purchasingFairConcerned']->getIdPurchasingFair());

//print_r($lunchEnterprise);


/* French days names */
$arrayDays = array('DIMANCHE','LUNDI','MARDI','MERCREDI','JEUDI','VENDREDI','SAMEDI');

/* Get the PurchasingFair concerned */
//$pfConcerned   = $appService->findOnePurchasingFair(3); // @TODO Change number w/ $_SESSION
$pfConcerned = $_SESSION['purchasingFairConcerned'];

/* PurchasingFair start/end datetimes */
$startDatetime = DateTime::createFromFormat('Y-m-d H:i:s', $pfConcerned->getStartDatetime());
$endDatetime   = DateTime::createFromFormat('Y-m-d H:i:s', $pfConcerned->getEndDatetime());

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
$presents = $appService->findPresentByDuo($_SESSION['enterpriseConcerned']->getIdEnterprise(), $_SESSION['purchasingFairConcerned']->getIdPurchasingFair());

//print_r($arrayPlanningDays);
?>

<!DOCTYPE html>
<html lang="fr">

<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="theme-color" content="#0b70b5"><!-- Mobile browser Tab Color -->

    <title>PFManagement | Présents + Repas</title>
    
    <!-- Favicon -->
    <?php require_once dirname ( __FILE__ ) . '/view/favicon.inc.php'; ?>

    <!-- Bootstrap -->
    <link href="css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link href="font-awesome/css/font-awesome.css" rel="stylesheet">
    
    <!-- Hover -->
    <link href="css/plugins/hover.css/hover-min.css" rel="stylesheet">
    
    <!-- Toastr style -->
    <link href="css/plugins/toastr/toastr.min.css" rel="stylesheet">

    <!-- Animate -->
    <link href="css/animate.css" rel="stylesheet">
    
    <!-- Select2 -->
    <link href="css/plugins/select2/select2.min.css" rel="stylesheet">
    
    <!-- Global -->
    <link href="css/style.css" rel="stylesheet">
    
    <!-- Custom style -->
    <style>
    /* Widget */
    .widget { color:#ffffff;border:1px solid #ffffff; }
    /* icon delete guest */
    .iconLunch {cursor:pointer;color:#ff0000!important;}
    .radioRed { color:#ff0000; }
    .radioGreen { color:#3cbc3c; }
    #submitButtonInsert { background-color:#0b70b5;border:1px solid #000000;color:#ffffff; }
    #submitButtonInsert:hover { background-color:#ed8b18; }
    </style>

</head>

<body>

    <div id="wrapper">

        <?php 
        if($_SESSION['enterpriseConcerned']->getOneProfile()->getName() == "Magasin") {
            require_once dirname ( __FILE__ ) . '/view/menu.store.inc.php';
        }
        else { require_once dirname ( __FILE__ ) . '/view/menu.provider.inc.php'; } 
        
        ?>

        <div id="page-wrapper" class="gray-bg">
        <div class="row border-bottom">
        <nav class="navbar navbar-static-top  " role="navigation" style="margin-bottom: 0">
        <div class="navbar-header">
            <a class="navbar-minimalize minimalize-styl-2 btn btn-primary " href="#"><i class="fa fa-bars"></i> </a>
        </div>
            <ul class="nav navbar-top-links navbar-right">
                <li>
                    <span class="m-r-sm text-muted welcome-message">Vous contacter</span>
                </li>
                <li>
                    <a href="./disconnection.php">
                        <i class="fa fa-sign-out"></i> Quitter
                    </a>
                </li>
            </ul>

        </nav>
        </div>
            <div class="row wrapper border-bottom white-bg page-heading">
                <div class="col-lg-9">
                    <h2><i class="fa fa-cutlery" aria-hidden="true"></i> Choix repas</h2>
                    <ol class="breadcrumb">
                        <li class="active">
                            <strong>Accès Magasin/Choix repas</strong>
                        </li>
                    </ol>
                </div>
                <div class="col-lg-3">
                    <?php require_once dirname ( __FILE__ ) . '/view/widget_pf_info.inc.php'; ?>
                </div>
            </div>

            <div class="wrapper wrapper-content">
                
                <div class="row" >
                    
                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-offset-2 col-lg-8">
                        <div class="ibox float-e-margins animated slideInDown">
                            <div class="ibox-title" style="border-top:1px solid #0b70b5">
                                <h5>Formulaire</h5>
                            </div>
                            <div class="ibox-content">
                                
                                <div class="row">
                                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                        
                                       <div id="alertLunches" class="alert alert-<?php echo ( empty($lunchEnterprise) ) ? 'danger' : 'success'; ?> m-t-md">
                                            <span>
                                                <span id="listLunchesRegistered">
                                                <?php if(empty($lunchEnterprise)) { ?>
                                                <i class="fa fa-info-circle" aria-hidden="true"></i> Aucun repas n'a encore été comptabilisé pour ce salon d'achats.
                                                <?php } else { ?>
                                                <i class="fa fa-info-circle" aria-hidden="true"></i> Les repas suivant ont été comptabilisé pour ce salon d'achats :<br/>
                                                <?php
                                                $arrayDayLunch = $appService->findArrayDayLunch($_SESSION['enterpriseConcerned']->getIdEnterprise(), $_SESSION['purchasingFairConcerned']->getIdPurchasingFair());

                                                foreach($arrayDayLunch as $day => $dl) {

                                                    $dayL = $dl[0];

                                                    $dayCreated =  DateTime::createFromFormat('Y-m-d', $dayL);
                                                    $dayName = $arrayDays[$dayCreated->format('w')];

                                                    echo '<strong>-- ';
                                                    echo $dayName. ' '.$dayCreated->format('d').'/';
                                                    echo $dayCreated->format('m').'/';
                                                    echo $dayCreated->format('Y');
                                                    echo '</strong>';

                                                    echo '<br/>';

                                                    foreach($lunchEnterprise as $day => $details) {

                                                        if($details->getLunchesDetails() == $dayL){

                                                            $participant = $appService->findOneParticipant($details->getIdParticipant());

                                                            echo $participant;

                                                            
                                                            echo '<br/>';
                                                        }

                                                        
                                                    }
                                                }}
                                                ?>
                                                </span>
                                            </span>
                                        </div>

                                    </div>
                                </div>
                                <div class="row">
                                    <h2 class="text-center">Formulaire d'ajout Repas</h2>
                                    <form id="formLunch" class="form-inline m-t" role="form" action="#" method="POST"> 
                                        
                                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-10 col-lg-offset-1 form-group">
                                            <select id="selectParticipants" name="selectParticipants[]" class="form-group form-control" required="">
                                                <option value="">--- Choix MAGASIN ---</option>
                                                <?php

                                                //$comm = $appService->findCommerciauxFournisseurByThree($_SESSION['enterpriseConcerned']->getIdEnterprise(), $_SESSION['purchasingFairConcerned']->getIdPurchasingFair());
                                                $comm = $appService->findDistinctSpStore($_SESSION['enterpriseConcerned']->getIdEnterprise(), $_SESSION['purchasingFairConcerned']->getIdPurchasingFair());

                                                //print_r($comm);


                                                // strcasecmp — Binary safe case-insensitive string comparison
                                                // Returns < 0 if str1 is less than str2; > 0 if str1 is greater than str2, and 0 if they are equal.
                                                function compareStrings($a, $b) { 
                                                   $result = strcasecmp($a->getOneParticipant()->getSurname(), $b->getOneParticipant()->getSurname());
                                                   // IF equal we compare the name
                                                   return (!$result) ? strcasecmp($a->getOneParticipant()->getName(), $b->getOneParticipant()->getName()) : $result; 
                                                }

                                                //usort($arrayAPE, 'compareStrings');                                                
                                                
                                                foreach($comm as $key => $ape) {
                                                    
                                                    $civility = $appService->findOneParticipant($ape)->getCivilitySmall();
                                                    $surname  = $appService->findOneParticipant($ape)->getSurname();
                                                    $name  = $appService->findOneParticipant($ape)->getName();

                                                    echo '<option value="'.$ape.'">'.$civility.' '.$surname.' '.$name.'</option>';
                                                }

                                                ?>
                                            </select>
                                        </div>
                                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-10 col-lg-offset-1 m-t-md form-group" id="main">


                                        </div>
                                        <div class="col-xs-12 col-sm-12 col-md-12 hidden-lg">&nbsp;</div><!-- Separation -->
                                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-10 col-lg-offset-1 m-t-md" style="display:none;" id="btsubmit">
                                            <button type="submit" id="submitButtonInsert" name="submitButtonInsert" class="btn block full-width m-t m-b hvr-icon-wobble-horizontal">Valider</button>
                                        </div>
                                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-1">&nbsp;</div><!-- Separation -->
                                    </form>
                                </div>
                                <div class="col-lg-12">&nbsp;</div>
                            </div>
                        </div>
                    </div>
                    
                </div><!-- ./row -->                

            </div>
            <div class="footer">
                <div class="pull-right">
                    <strong><i class="fa fa-copyright" aria-hidden="true"></i></strong> E.Leclerc | SCA Ouest <?php echo date('Y'); ?>
                </div>
                <div>
                    <strong>PFManagement</strong>
                </div>
            </div>

        </div>
        </div>

    <!-- Mainly scripts -->
    <script src="js/jquery-3.1.1.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script src="js/plugins/metisMenu/jquery.metisMenu.js"></script>
    <script src="js/plugins/slimscroll/jquery.slimscroll.min.js"></script>

    <!-- Custom and plugin javascript -->
    <script src="js/inspinia.js"></script>
    <script src="js/plugins/pace/pace.min.js"></script>
    
    <!-- Toastr script -->
    <script src="js/plugins/toastr/toastr.min.js"></script>
    
    <!-- Select2 -->
    <script src="js/plugins/select2/select2.full.min.js"></script>
    <script src="js/plugins/select2/i18n/fr.js"></script>
    
    <!-- Custom script -->
    <script>
    $("#selectParticipants").select2( { width:"100%", placeholder: "--- Cliquez ici pour selectionner un Participants ---", language: "fr" });

    $('#selectParticipants').change(function(){

        $('#main').empty();

        var $idCommercial = $('#selectParticipants option:selected').val();
        //console.log($idCommercial);
        var $NameCommercial = $('#selectParticipants option:selected').text();

        //console.log($idCommercial);

        $.post(
            './ajax/SelectRepasProvider.php',
            {
                idCommercial : $idCommercial,
            },
            function(data) {
                //console.log(data);

                $donnees = data.trim().substring(0,5);

                if($donnees != 'Error') {

                    if(data!=0){

                        $obj = JSON.parse(data);

                        console.log($obj);

                        $('#main').append($("<div class='col-xs-12 col-sm-12 col-md-12 col-lg-12'><div class='col-xs-5 col-sm-5 col-md-5 col-lg-5 col-xs-offset-1 col-sm-offset-1 col-md-offset-1 col-lg-offset-1 text-left' style='text-align: center;'> <label>Jours :</label> </div> <div class='col-xs-6 col-sm-6 col-md-6 col-lg-6 text-left' style='text-align: center;'> <label>Repas ? (Par défaut Non)</label> </div> </div>"));

                        $obj.forEach(function(date){
                            //console.log(date);

                            $('#main').append($(" <div class='col-xs-12 col-sm-12 col-md-12 col-lg-12'><div class='col-xs-5 col-sm-5 col-md-5 col-lg-5 col-xs-offset-1 col-sm-offset-1 col-md-offset-1 col-lg-offset-1 text-left checkbox'><label><span class='glyphicon glyphicon-hand-right'></span>&nbsp;"+date[1]+' '+date[2]+' '+date[3]+"</label></div><div class='col-xs-6 col-sm-6 col-md-6 col-lg-6 text-left radio' style='text-align: center;'><label class='radio-inline'><input type='radio' name="+'radio_'+date[0]+" value='N' checked><span class='radioRed' checked>&nbsp;Non</span></label><label class='radio-inline'><input type='radio' name="+'radio_'+date[0]+" value='Y' "+date[4]+"><span class='radioGreen'>&nbsp;Oui</span></label></div></div><br/> "));


                        });

                        $('#btsubmit').show();
                        $('#btsubmit').css("display","block");
                    }

                }else{
                    toastr.error('Erreur fatale. Contactez l\'administrateur.', 'Échec.');
                }
            },
            'text'
        );

    });
        
    <?php
    if(isset($_POST) && !empty($_POST)) { ?>
    toastr.success('Choix pris en compte.', 'Succès.');
    <?php } ?>

    function deleteLunch(dayLunch, idParticipant) {
        
        if ( confirm('Confirmer votre choix ?') != false ) { // The confirm() method returns true if the user clicked "OK", and false otherwise.

            $.post(
                './ajax/deleteLunch.php',
                {
                    dayLunch : dayLunch,
                    idParticipant : idParticipant
                },
                function(data) {
                    data = data.trim();
                    if(data !== '-1') { location.assign("./enterprise_choice_lunches.php"); }
                    else { toastr.error('Le repas n\'a pas été supprimé.', 'Échec.'); }
                },
                'text'
            );
        }
    }

    </script>

</body>

</html>
