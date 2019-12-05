<?php
require_once dirname ( __FILE__ ) . '/view/errors.inc.php';
require_once dirname ( __FILE__ ) . '/services/AppServiceImpl.class.php';

if(!isset($_SESSION)) session_start(); // Start session

// Already connected as Admin or Provider ?
if( ( isset($_SESSION['adminConnected']) && !empty($_SESSION['adminConnected']) ) || 
    ( isset($_SESSION['isStoreOrProvider']) && !empty($_SESSION['isStoreOrProvider']) && $_SESSION['isStoreOrProvider'] != 'store' )
  ) {
    header('Location: ./disconnection.php'); // User disconnected
}

header( 'content-type: text/html; charset=utf-8' ); // Specifies to the server to return UTF-8 - put in prod

$appService = AppServiceImpl::getInstance();

if( isset($_POST) && !empty($_POST) ) {

    // Recovery of data sent by the create form
    for($z = 1 ; $z <= 5 ; $z++) {

        $postDateTime   = $_POST['period_datetime_'.$z]; 
        $postDate       = $_POST['period_date_'.$z]; 
        $postSimpleDate = $_POST['simple_date_'.$z]; 

        if( isset($postDateTime) && !empty($postDateTime) ) { // Period Datetime values			
            $dataDatetime      = $appService->convertDateRangeToMySqlFormat($postDateTime);
            $idUnavailability  = $appService->saveUnavailability($appService->createUnavailability($dataDatetime['startDatetime'], $dataDatetime['endDatetime'], $_SESSION['enterpriseConcerned'], $_SESSION['purchasingFairConcerned']));
        }

        if( isset($postDate) && !empty($postDate) ) { // Periode Date values
            $dataDatetime      = $appService->convertDateRangeToMySqlFormat($postDate);
            $idUnavailability  = $appService->saveUnavailability($appService->createUnavailability($dataDatetime['startDatetime'], $dataDatetime['endDatetime'], $_SESSION['enterpriseConcerned'], $_SESSION['purchasingFairConcerned']));
        }

        if( isset($postSimpleDate) && !empty($postSimpleDate) ) { // Simple Date values		
            $dataDatetime      = $appService->convertDateRangeToMySqlFormat($postSimpleDate);
            $idUnavailability  = $appService->saveUnavailability($appService->createUnavailability($dataDatetime['startDatetime'], $dataDatetime['endDatetime'], $_SESSION['enterpriseConcerned'], $_SESSION['purchasingFairConcerned']));
        }
    }
    
    header('Location: ./store_unavailabilities_register.php'); // Home page redirection
}

$arrayUnavailabilitiesAlreadyRegistered = $appService->findEnterpriseUnavailabilities($_SESSION['enterpriseConcerned'],$_SESSION['purchasingFairConcerned']);
?>

<!DOCTYPE html>
<html lang="fr">

<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta name="theme-color" content="#0b70b5"><!-- Mobile browser Tab Color -->

    <title>PFManagement | Saisie indisponibilités</title>
	
    <!-- Favicon -->
    <?php require_once dirname ( __FILE__ ) . '/view/favicon.inc.php'; ?>

    <!-- Bootstrap -->
    <link href="css/bootstrap.min.css" rel="stylesheet">
	
    <!-- Font Awesome -->
    <link href="font-awesome/css/font-awesome.css" rel="stylesheet">
	
    <!-- Select2 -->
    <link href="css/plugins/select2/select2.min.css" rel="stylesheet">
	
    <!-- Hover -->
    <link href="css/plugins/hover.css/hover-min.css" rel="stylesheet">
	
    <!-- Daterangepicker -->
    <link href="css/plugins/daterangepicker/daterangepicker.css" rel="stylesheet">
    
    <!-- Toastr style -->
    <link href="css/plugins/toastr/toastr.min.css" rel="stylesheet">

    <!-- Animate -->
    <link href="css/animate.css" rel="stylesheet">
	
    <!-- Global -->
    <link href="css/style.css" rel="stylesheet">
	
    <!-- Custom style -->
    <style>
    /* Widget */
    .widget { color:#ffffff;border:1px solid #ffffff; }
    /* previous and next buttons */
    #previousButton { background-color:#ffffff;color:#0b70b5;border:1px solid #0b70b5; }
    #nextButton { background-color:#0b70b5;color:#ffffff;border:1px solid #000000; }
    #submitButton { background-color:#0b70b5;color:#ffffff; }
    .mydaterangepicker { cursor: pointer; }
    #submitUnavailabilities { border:1px solid #000000; }
    /* Update - Delete icons */
    .fa { cursor:pointer; }
    </style>

</head>

<body>

    <div id="wrapper">

        <?php require_once dirname ( __FILE__ ) . '/view/menu.store.inc.php'; ?>

        <div id="page-wrapper" class="gray-bg">
        <div class="row border-bottom">
        <nav class="navbar navbar-static-top  " role="navigation" style="margin-bottom: 0">
        <div class="navbar-header">
            <a class="navbar-minimalize minimalize-styl-2 btn btn-primary " href="#"><i class="fa fa-bars"></i> </a>
        </div>
            <ul class="nav navbar-top-links navbar-right">
                <li>
                    <span class="m-r-sm text-muted welcome-message">Saisie indisponibilités.</span>
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
                    <h2><i class="fa fa-calendar-times-o" aria-hidden="true"></i> Saisie des indisponibilités</h2>
                    <ol class="breadcrumb">
                        <li>
                            <a href="./purchasing_fair_list.php">Liste des salons</a>
                        </li>
                        <li class="active">
                            <a href="./store_choice_providers.php">Sélection Fournisseurs + besoins en heures</a>
                        </li>                        
                        <li class="active">
                            <strong>Saisie indisponibilités</strong>
                        </li>
                    </ol>
                </div>
                <div class="col-lg-3">
                    <?php require_once dirname ( __FILE__ ) . '/view/widget_pf_info.inc.php'; ?>
                </div>                
            </div>

            <div class="wrapper wrapper-content">
                
                
                <div class="row" >
                    
                    <div class="col-lg-12 animated fadeInDown alert alert-success">
                    <?php
                    echo '<h5><i class="fa fa-info-circle" aria-hidden="true"></i> Indisponibilités déjà enregistrées</h5>';
                    $counter = 0;
                    $limit = count($arrayUnavailabilitiesAlreadyRegistered);
                    if( $limit ) {
                        foreach($arrayUnavailabilitiesAlreadyRegistered as $value) {
                            $counter++;
                            echo '<span id="rowUnavailability_'.$value->getIdUnavailability().'">';
                            echo '<i class="fa fa-hourglass-start" aria-hidden="true"></i> '.$appService->myFrenchDatetime($value->getStartDatetime()).' <i class="fa fa-long-arrow-right" aria-hidden="true"></i> <i class="fa fa-hourglass-end" aria-hidden="true"></i> '.$appService->myFrenchDatetime($value->getEndDatetime());
                            echo '&nbsp;|&nbsp;';
                            echo '<i class="fa fa-window-close text-danger" title="Supprimer" aria-hidden="true" onclick="deleteUnavailability('.$value->getIdUnavailability().');"></i>';
                            echo '</span>';
                            echo ($counter < $limit) ? '<br>' : '';
                        }
                    }
                    else { echo 'Aucune indisponibilité enregistrée'; }
                    ?>
                    </div>                    
                    
                    <form id="registerUnavailabilitiesForm" action="#" method="POST">
                        <div class="col-lg-offset-3 col-lg-7 animated fadeInDown m-b">
                            <div class="tabs-container">
                                <ul class="nav nav-tabs">
                                    <li class="active"><a data-toggle="tab" href="#tab-3"><i class="fa fa-calendar" aria-hidden="true"></i>+ <i class="fa fa-clock-o" aria-hidden="true"></i> PÉRIODES AVEC H:M:S</a></li>
                                    <li class=""><a data-toggle="tab" href="#tab-4"><i class="fa fa-calendar" aria-hidden="true"></i> PÉRIODES SANS H:M:S (JOURNÉES ENTIÈRES)</a></li>
                                    <li class=""><a data-toggle="tab" href="#tab-5"><i class="fa fa-calendar" aria-hidden="true"></i> DATES SIMPLES (JOURNÉES ENTIÈRES)</a></li>
                                </ul>
                                <div class="tab-content">
                                    <div id="tab-3" class="tab-pane active">
                                        <div class="panel-body">
                                        <?php 
                                        for($a = 1 ; $a <= 5 ; $a++) {
                                        echo '
                                        <div class="row rowDaterange">
                                            <div class="col-sm-12">
                                                <div class="input-group">
                                                    <span class="input-group-addon"><i class="fa fa-calendar-plus-o" aria-hidden="true"></i></span>
                                                    <div class="form-group" id="form_group_period_datetime_'.$a.'">
                                                        <input id="period_datetime_'.$a.'" name="period_datetime_'.$a.'" type="text" class="form-control mydaterangepicker" placeholder="Cliquer ici pour ajouter une période avec H:M:S">
                                                        <i class="form-group__bar"></i>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <br/>
                                        '; } ?>
                                        </div>
                                    </div>
                                    <div id="tab-4" class="tab-pane">
                                        <div class="panel-body">
                                        <?php 
                                        for($b = 1 ; $b <= 5 ; $b++) {
                                        echo '
                                        <div class="row rowDaterange">
                                            <div class="col-sm-12">
                                                <div class="input-group">
                                                    <span class="input-group-addon"><i class="fa fa-calendar-plus-o" aria-hidden="true"></i></span>
                                                    <div class="form-group" id="form_group_period_date_'.$b.'">
                                                        <input id="period_date_'.$b.'" name="period_date_'.$b.'" type="text" class="form-control mydaterangepicker" placeholder="Cliquer ici pour ajouter une période sans H:M:S">
                                                        <i class="form-group__bar"></i>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <br/>
                                        '; } ?>
                                        </div>
                                    </div>
                                    <div id="tab-5" class="tab-pane">
                                        <div class="panel-body">
                                        <?php 
                                        for($c = 1 ; $c <= 5 ; $c++) {
                                        echo '
                                        <div class="row rowDaterange">
                                            <div class="col-sm-12">
                                                <div class="input-group">
                                                    <span class="input-group-addon"><i class="fa fa-calendar-plus-o" aria-hidden="true"></i></span>
                                                    <div class="form-group">
                                                        <input id="simple_date_'.$c.'" name="simple_date_'.$c.'" type="text" class="form-control mydaterangepicker" placeholder="Cliquer ici pour ajouter une date simple">
                                                        <i class="form-group__bar"></i>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <br/>
                                        '; } ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-12 text-center animated fadeInDown">
                            <button id="previousButton" name="previousButton" type="button" class="btn hvr-icon-back">Précédent</button>
                            <button id="submitUnavailabilities" type="submit" class="btn btn-primary hvr-icon-bounce"> Valider les indispos</button>
                            <button id="nextButton" name="nextButton" type="button" class="btn hvr-icon-forward">Suivant</button>
                        </div>
                    </form>
                    
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
    
    <!-- Toastr script -->
    <script src="js/plugins/toastr/toastr.min.js"></script>
    
    <!-- Bootbox -->
    <script src="js/plugins/bootbox.js/bootbox.min.js"></script>

    <!-- Custom and plugin javascript -->
    <script src="js/inspinia.js"></script>
    <script src="js/plugins/pace/pace.min.js"></script>
    
    <!-- Select2 -->
    <script src="js/plugins/select2/select2.full.min.js"></script>
    
    <!-- Daterange picker -->
    <script src="js/plugins/daterangepicker/moment.min.js"></script>
    <script src="js/plugins/daterangepicker/daterangepicker.js"></script>

    <script>
    <?php 
    /* Generate JS Daterangepickers x15. Daterange plugin adaptations had to be made to be able to insert valid datetime in mySQL Database.
     * Explanations: 
     * [CREATION] 
     * For the periods entered with time, the seconds :00 are added to the start and end inputs.
     * For the periods entered without the time AND for the simple dates, the time 00:00:00 is added at start input and 23:59:59 is added at end input (full day)
    */
    $startDay     = $_SESSION['purchasingFairConcerned']->getDayFromStartDatetime();
    $startMonth   = $_SESSION['purchasingFairConcerned']->getMonthFromStartDatetime();
    $startYear    = $_SESSION['purchasingFairConcerned']->getYearFromStartDatetime();
    $startHours   = $_SESSION['purchasingFairConcerned']->getHoursFromStartDatetime();
    $startMinutes = $_SESSION['purchasingFairConcerned']->getMinutesFromStartDatetime();
    
    $endDay     = $_SESSION['purchasingFairConcerned']->getDayFromEndDatetime();
    $endMonth   = $_SESSION['purchasingFairConcerned']->getMonthFromEndDatetime();
    $endYear    = $_SESSION['purchasingFairConcerned']->getYearFromEndDatetime();
    $endHours   = $_SESSION['purchasingFairConcerned']->getHoursFromEndDatetime();
    $endMinutes = $_SESSION['purchasingFairConcerned']->getMinutesFromEndDatetime();
    
    for($i = 1 ; $i <= 5 ; $i++) {
        echo  '
        $("#period_datetime_" + '.$i.').daterangepicker({
            "showWeekNumbers": true,
            "timePicker": true,
            "timePicker24Hour": true,
            "locale": {
                "format": "DD/MM/YYYY HH:mm",
                "separator": " - ",
                "applyLabel": "<i class=\"fa fa-check\" aria-hidden=\"true\"></i>",
                "cancelLabel": "<i class=\"fa fa-times\" aria-hidden=\"true\"></i>",
                "fromLabel": "From",
                "toLabel": "To",
                "customRangeLabel": "Custom",
                "weekLabel": "S",
                "daysOfWeek": [
                    "Di",
                    "Lu",
                    "Ma",
                    "Me",
                    "Je",
                    "Ve",
                    "Sa"
                ],
                "monthNames": [
                    "Janvier",
                    "Février",
                    "Mars",
                    "Avril",
                    "Mai",
                    "Juin",
                    "Juillet",
                    "Août",
                    "Septembre",
                    "Octobre",
                    "Novembre",
                    "Décembre"
                ],
                "firstDay": 1
            },
            "showCustomRangeLabel": false,
            "autoUpdateInput": false,
            "startDate": "'.$startDay.'/'.$startMonth.'/'.$startYear.' '.$startHours.':'.$startMinutes.'",
            "endDate": "'. $endDay .'/'.$endMonth.'/'.$endYear.' '.$endHours.':'.$endMinutes.'",
            "applyClass": "btn-info",
            "cancelClass": "btn-danger"
        });

        $("#period_datetime_" + '.$i.').on(\'apply.daterangepicker\', function(ev, picker) {
            $("#period_datetime_" + '.$i.').val(picker.startDate.format("DD/MM/YYYY HH:mm:00") + " - " + picker.endDate.format("DD/MM/YYYY HH:mm:00"));
        });

        $("#period_datetime_" + '.$i.').on(\'cancel.daterangepicker\', function(ev, picker) {
        $("#period_datetime_" + '.$i.').val("");
        });

        $("#period_date_" + '.$i.').daterangepicker({
            "showWeekNumbers": true,
            "timePicker": false,
            "timePicker24Hour": false,
            "locale": {
                "format": "DD/MM/YYYY HH:mm",
                "separator": " - ",
                "applyLabel": "<i class=\"fa fa-check\" aria-hidden=\"true\"></i>",
                "cancelLabel": "<i class=\"fa fa-times\" aria-hidden=\"true\"></i>",
                "fromLabel": "From",
                "toLabel": "To",
                "customRangeLabel": "Custom",
                "weekLabel": "S",
                "daysOfWeek": [
                    "Di",
                    "Lu",
                    "Ma",
                    "Me",
                    "Je",
                    "Ve",
                    "Sa"
                ],
                "monthNames": [
                    "Janvier",
                    "Février",
                    "Mars",
                    "Avril",
                    "Mai",
                    "Juin",
                    "Juillet",
                    "Août",
                    "Septembre",
                    "Octobre",
                    "Novembre",
                    "Décembre"
                ],
                "firstDay": 1
            },
            "showCustomRangeLabel": false,
            "autoUpdateInput": false,
            "startDate": "'.$startDay.'/'.$startMonth.'/'.$startYear.' 00:00",
            "endDate": "'. $endDay .'/'.$endMonth.'/'.$endYear.' 23:59",
            "applyClass": "btn-info",
            "cancelClass": "btn-danger"
        });

        $("#period_date_" + '.$i.').on(\'apply.daterangepicker\', function(ev, picker) {
            $("#period_date_" + '.$i.').val(picker.startDate.format("DD/MM/YYYY 00:00:00") + " - " + picker.endDate.format("DD/MM/YYYY 23:59:59"));
        });

        $("#period_date_" + '.$i.').on(\'cancel.daterangepicker\', function(ev, picker) {
            $("#period_date_" + '.$i.').val("");
        });

        $("#simple_date_" + '.$i.').daterangepicker({
            "showWeekNumbers": true,
            "timePicker": false,
            "timePicker24Hour": false,
            "locale": {
                "format": "DD/MM/YYYY HH:mm",
                "separator": " - ",
                "applyLabel": "<i class=\"fa fa-check\" aria-hidden=\"true\"></i>",
                "cancelLabel": "<i class=\"fa fa-times\" aria-hidden=\"true\"></i>",
                "fromLabel": "From",
                "toLabel": "To",
                "customRangeLabel": "Custom",
                "weekLabel": "S",
                "daysOfWeek": [
                    "Di",
                    "Lu",
                    "Ma",
                    "Me",
                    "Je",
                    "Ve",
                    "Sa"
                ],
                "monthNames": [
                    "Janvier",
                    "Février",
                    "Mars",
                    "Avril",
                    "Mai",
                    "Juin",
                    "Juillet",
                    "Août",
                    "Septembre",
                    "Octobre",
                    "Novembre",
                    "Décembre"
                ],
                "firstDay": 1
            },
            "showCustomRangeLabel": false,
            "autoUpdateInput": false,
            "singleDatePicker": true,
            "startDate": "'.$startDay.'/'.$startMonth.'/'.$startYear.' 00:00",
            "endDate": "'.$startDay.'/'.$startMonth.'/'.$startYear.' 23:59",
            "applyClass": "btn-info",
            "cancelClass": "btn-danger"
        });

        $("#simple_date_" + '.$i.').on(\'apply.daterangepicker\', function(ev, picker) {
            $("#simple_date_" + '.$i.').val(picker.startDate.format("DD/MM/YYYY 00:00:00") + " - " + picker.endDate.format("DD/MM/YYYY 23:59:59"));
        });

        $("#simple_date_" + '.$i.').on(\'cancel.daterangepicker\', function(ev, picker) {
            $("#simple_date_" + '.$i.').val("");
        });
        ';
    }
    ?>
    /* Click event (previousButton and nextButton) */
    $("#previousButton").click(function(){ window.location.assign("./store_choice_providers.php"); });
    $("#nextButton").click(function(){ window.location.assign("./store_choice_participants.php"); });
    
    bootbox.setDefaults({ locale: "fr" });

    function deleteUnavailability(idUnavailability) {
        
        bootbox.confirm("Confirmer la suppression ?", function(result) { 
                        
            if(result === true) {
                
                $.post(
                    './ajax/deleteUnavailability.php',
                    {
                        idUnavailability : idUnavailability
                    },
                    function(data) {
                        if(data.trim() === 'Success') {
                            $('#rowUnavailability_' + idUnavailability).html('<span class="text-danger"><i class="fa fa-exclamation-triangle" aria-hidden="true"></i>&nbsp;Indisponibilité supprimée.</span>');
                            toastr.success('Action réussie', 'Succès.');
                        }
                        else {
                            toastr.error('Erreur fatale. Contactez l\'administrateur.', 'Échec.');
                        }
                    },
                    'text'
                );                
            }
        });
    }
    </script>

</body>

</html>
