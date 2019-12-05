<?php
require_once dirname ( __FILE__ ) . '/view/errors.inc.php';
require_once dirname ( __FILE__ ) . '/services/AppServiceImpl.class.php';

if(!isset($_SESSION)) session_start(); // Start session

/* To see all details when var_dump() function used */
ini_set('xdebug.var_display_max_depth', -1);
ini_set('xdebug.var_display_max_children', -1);
ini_set('xdebug.var_display_max_data', -1);

// Not connected as Admin ?
if(!isset($_SESSION['adminConnected']) && empty($_SESSION['adminConnected'])) {
    header('Location: ./disconnection.php'); // Redirection to disconnection
}

header( 'content-type: text/html; charset=utf-8' ); // Specifies to the server to return UTF-8 - put in prod

date_default_timezone_set('Europe/Paris');

$appService = AppServiceImpl::getInstance();

$file = './tmp/tmp_planning_pf'.$_SESSION['purchasingFairConcerned']->getIdPurchasingFair().'.html';


if( isset($_SESSION['purchasingFairConcerned']) && !empty($_SESSION['purchasingFairConcerned']) && file_exists($file) ) {
    $lunchesCalculated = $appService->lunchesCalculated($_SESSION['purchasingFairConcerned']->getIdPurchasingFair());
    $arrayStores    = $appService->findAllEnterprisesAsStores();
    $arrayProviders = $appService->findAllProviderPresentForOnePurchasingFair($_SESSION['purchasingFairConcerned']->getIdPurchasingFair());
}
elseif( isset($_SESSION['purchasingFairConcerned']) && !empty($_SESSION['purchasingFairConcerned']) && $_SESSION['purchasingFairConcerned']->getOneTypeOfPf()->getNameTypeOfPf() === 'Autre') {
    $lunchesCalculated = $appService->lunchesCalculated($_SESSION['purchasingFairConcerned']->getIdPurchasingFair());
    $arrayStores    = $appService->findAllEnterprisesAsStores();
    $arrayProviders = $appService->findAllProviderPresentForOnePurchasingFair($_SESSION['purchasingFairConcerned']->getIdPurchasingFair());
}
else { header('Location: ./purchasing_fair_list.php'); die(); } // Redirection to Purchasing Fair list

/* To prevent server misconfigured */
date_default_timezone_set('Europe/Paris');

/* French days names */
$arrayDays = array('DIMANCHE','LUNDI','MARDI','MERCREDI','JEUDI','VENDREDI','SAMEDI');

/* French months names */
$arrayMonths = array('JANVIER','FEVRIER','MARS','AVRIL','MAI','JUIN','JUILLET','AOUT','SEPTEMBRE','OCTOBRE','NOVEMBRE','DECEMBRE');

$pfConcerned = $_SESSION['purchasingFairConcerned'];
$startDatetime = DateTime::createFromFormat('Y-m-d H:i:s', $pfConcerned->getStartDatetime());
$endDatetime   = DateTime::createFromFormat('Y-m-d H:i:s', $pfConcerned->getEndDatetime());

$counterArrayPlanningDays= 0;

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

//var_dump($arrayPlanningDays);
// Added 27.08.2018 - Taking others Pf into account
$isOtherPf = ( $_SESSION['purchasingFairConcerned']->getOneTypeOfPf()->getNameTypeOfPf() === 'Autre' ) ? true : false;

$arrayFckingExportExcel = array();
?>

<!DOCTYPE html>
<html lang="fr">

<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="theme-color" content="#0b70b5"><!-- Mobile browser Tab Color -->

    <title>PFManagement | Liste Repas</title>
	
    <!-- Favicon -->
    <?php require_once dirname ( __FILE__ ) . '/view/favicon.inc.php'; ?>

    <!-- Bootstrap -->
    <link href="css/bootstrap.min.css" rel="stylesheet">
	
    <!-- Font Awesome -->
    <link href="font-awesome/css/font-awesome.css" rel="stylesheet">
    
    <!-- DataTables -->
    <link href="css/plugins/dataTables/datatables.min.css" rel="stylesheet">
	
    <!-- Hover -->
    <link href="css/plugins/hover.css/hover-min.css" rel="stylesheet">
    
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
    /* ibox */
    .ibox-title { border-top:2px solid #0b70b5; }
    .ibox-title h5 { color:#0b70b5; }
    /* Replace the icon of the button */
    .hvr-icon-wobble-horizontal:before { content: "\f12d"; }
    /* Color orange E.Leclerc */
    .colorOrangeLeclerc { color:#ed8b18; }
    .colorBlueLeclerc { color:#0b70b5; }
    /* Spinner color */
    .sk-spinner-wave div { background-color:#0b70b5; }
    /* Update/Remove icons */
    .actionButton { cursor:pointer; }
    .fa-plus-circle { color:#0b70b5; }
    .fa-minus-circle { color:#ed8b18; }
    .fa-search-plus { color: green; }
    .iconExcel { color:#217346; }
    .iconExcel:hover { cursor:pointer; }
    </style>

</head>

<body>

    <div id="wrapper">

        <?php require_once dirname ( __FILE__ ) . '/view/menu.global.inc.php'; ?>

        <div id="page-wrapper" class="gray-bg">
        <div class="row border-bottom">
        <nav class="navbar navbar-static-top  " role="navigation" style="margin-bottom: 0">
        <div class="navbar-header">
            <a class="navbar-minimalize minimalize-styl-2 btn btn-primary " href="#"><i class="fa fa-bars"></i> </a>
        </div>
            <ul class="nav navbar-top-links navbar-right">
                <li>
                    <span class="m-r-sm text-muted welcome-message">Liste des Repas enregistrés</span>
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
                    <h2><i class="fa fa-table" aria-hidden="true"></i> Récapitulatif journalier de TOUS les Repas connus aujourd'hui à <?php echo date('H:i:s'); ?> pour le salon d'achats</h2>
                    <ol class="breadcrumb">
                        <li class="active">
                            <strong>Administration/Liste Repas</strong>
                        </li>
                    </ol>
                </div>
                <div class="col-lg-3">
                <?php if( isset($_SESSION['enterpriseConcerned']) && !empty($_SESSION['enterpriseConcerned']) && !isset($_SESSION['adminConnected']) && empty($_SESSION['adminConnected']) ) { ?>
                <h2 class="text-center">Vous avez sélectionné le <?php echo $_SESSION['enterpriseConcerned']->getOneProfile()->getName() ?></h2>
                <h2 class="colorOrangeLeclerc text-center"><em><?php echo $_SESSION['enterpriseConcerned']->getName(); ?></em></h2>
                <?php } elseif( isset($_SESSION['adminConnected']) && !empty($_SESSION['adminConnected']) && isset($_SESSION['purchasingFairConcerned']) && !empty($_SESSION['purchasingFairConcerned'])) { ?>
                <?php require_once dirname ( __FILE__ ) . '/view/widget_pf_info.inc.php'; ?>
                <?php } elseif( isset($_SESSION['adminConnected']) && !empty($_SESSION['adminConnected']) && !isset($_SESSION['purchasingFairConcerned']) && empty($_SESSION['purchasingFairConcerned'])) { ?>
                <h2 class="text-center">Vous n'avez pas sélectionné de salon</h2>
                <?php } ?>
                </div>
            </div>

            <div class="wrapper wrapper-content">
                
                <div class="row" >
                    
                    <div class="col-lg-12">
                        <div class="ibox float-e-margins animated zoomIn">
                            <div class="ibox-title" style="border-top:1px solid #0b70b5">
                                <h5><i class="fa fa-cutlery" aria-hidden="true"></i> Liste des Repas</h5>
                            </div>
                            <div class="ibox-content">

                                <div id="myspinner">
                                    <div class="sk-spinner sk-spinner-wave">
                                        <div class="sk-rect1"></div>
                                        <div class="sk-rect2"></div>
                                        <div class="sk-rect3"></div>
                                        <div class="sk-rect4"></div>
                                        <div class="sk-rect5"></div>
                                    </div>
                                    <div class="text-center colorBlueLeclerc">Chargement des données...</div>
                                </div>

                                <div id="divTableLunches" class="table-responsive" style="display:none"> <!--style="display:none"-->
                                    <table id="tableLunches" class="table table-striped table-bordered table-hover">
                                        <thead>
                                            <tr>
                                                <th><i class="fa fa-calendar" aria-hidden="true"></i> | Jour</th>
                                                <th><i class="fa fa-cutlery" aria-hidden="true"></i> | Repas Magasins</th>
                                                <th><i class="fa fa-cutlery" aria-hidden="true"></i> | Repas Fournisseurs</th>
                                                <th><i class="fa fa-user-plus" aria-hidden="true"></i> | Repas Invités except.</th>
                                                <th><i class="fa fa-calculator" aria-hidden="true"></i> | Total Jour</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        <?php
                                        
                                        // Get Id PurchasingFair
                                        $idPurchasingFair = $_SESSION['purchasingFairConcerned']->getIdPurchasingFair();

                                        
                                        // Stores
                                        $arrayLunchesStores = array();
										
                                        if(!$isOtherPf) {
                                            $arrayEnterprisesAsStores = $appService->findAllEnterprisesAsStoresPfBis($idPurchasingFair);
                                        }
                                        else {
                                            $arrayEnterprisesAsStores = $appService->findAllEnterprisesAsStoresPfBis();
                                        }
                                        $limitArrayStores = count($arrayEnterprisesAsStores);

                                        for($s = 0 ; $s < $limitArrayStores ; $s++) {
                                            $lunchStore = $appService->findLunchForOneEnterpriseAndPf($arrayEnterprisesAsStores[$s], $idPurchasingFair);

                                            //print_r($lunchStore);
                                            //print_r($appService->findOneEnterprise($arrayEnterprisesAsStores[$s])->getName());
                                            
                                            if(!empty($lunchStore)) {
                                               
                                                foreach($lunchStore as $key => $value) {
     
                                                    if(!isset($arrayLunchesStores[$value->getLunchesDetails()])) { // If 1st time
                                                        $arrayLunchesStores[$value->getLunchesDetails()] = $value->getLunchesPlanned();
                                                        //$arrayFckingExportExcel[$value->getLunchesDetails()]['stores'][$appService->findOneEnterprise($arrayEnterprisesAsStores[$s])->getName()][] = $appService->findOneParticipant($idParticipant)->getFourInformations();
                                                    }
                                                    else { 
                                                        $arrayLunchesStores[$value->getLunchesDetails()] = $arrayLunchesStores[$value->getLunchesDetails()] + $value->getLunchesPlanned();
                                                        //$arrayFckingExportExcel[$value->getLunchesDetails()]['stores'][$arrayEnterprisesAsStores[$s]->getName()][] = $appService->findOneParticipant($idParticipant)->getFourInformations();
                                                    }
                                                }
                                            }
                                            else { } // nothing
                                        }
                                        
                                        //print_r($arrayLunchesStores);
                                        // END Stores
                                        
                                        // Providers
                                        $arrayLunchesProviders = array();
										
                                        if(!$isOtherPf) {
                                            $arrayEnterprisesAsProviders = $appService->findAllEnterprisesAsProviderPfBis($idPurchasingFair);
                                        }
                                        else {
                                            $arrayPP = $appService->findAllProviderPresentForOnePurchasingFair($_SESSION['purchasingFairConcerned']->getIdPurchasingFair());
                                            $arrayEnterprisesAsProviders = array();
                                            foreach($arrayPP as $key => $pp) { $arrayEnterprisesAsProviders[] = $pp->getOneProvider(); }
                                        }
                                        $limitArrayProviders = count($arrayEnterprisesAsProviders);
                                        $arraySpecialGuestDay = array();

                                        //print_r($arrayEnterprisesAsProviders);
                                        
                                        for($p = 0 ; $p < $limitArrayProviders ; $p++) {
                                            
                                            // Get Lunch
                                            $lunchProvider = $appService->findLunchForOneEnterpriseAndPf($arrayEnterprisesAsProviders[$p], $idPurchasingFair);
                                            
                                            // Get SpecialGuests
                                            $specialGuests = $appService->findAllSpecialGuestForOneEnterpriseAndPf($arrayEnterprisesAsProviders[$p], $idPurchasingFair);

                                            if(!empty($lunchProvider)) {
                                                                                        
                                                foreach($lunchProvider as $key => $value) {

                                                    if(!isset($arrayLunchesProviders[$value->getLunchesDetails()])) { // If 1st time
                                                        $arrayLunchesProviders[$value->getLunchesDetails()] = $value->getLunchesPlanned();
                                                        //$arrayFckingExportExcel[$value->getLunchesDetails()]['providers'][$arrayEnterprisesAsProviders[$p]->getName()][] = $appService->findOneParticipant($idParticipant)->getFourInformations();
                                                    }
                                                    else { 
                                                        $arrayLunchesProviders[$value->getLunchesDetails()] = $arrayLunchesProviders[$value->getLunchesDetails()] + $value->getLunchesPlanned();
                                                        //$arrayFckingExportExcel[$value->getLunchesDetails()]['providers'][$arrayEnterprisesAsProviders[$p]->getName()][] = $appService->findOneParticipant($idParticipant)->getFourInformations();
                                                    }
                                                    
                                                }
                                            }
                                            
                                            // Lunches SpecialGuests calculation

                                            // Test if a special Guest is present this day
                                            // A Provider does not choose its SpecialGuests before having its planning, so he should enter valid dates.
                                            if(!empty($specialGuests)) {

                                                foreach($specialGuests as $specialGuest) {

                                                    //print_r($specialGuest);
                                                        
                                                    // Default 0 lunch for SpecialGuests this day
                                                    if(!isset($arraySpecialGuestDay[$specialGuest->getDays()])) {
                                                        $arraySpecialGuestDay[$specialGuest->getDays()] = 1;
                                                        //$arrayFckingExportExcel[$daySpecialGuest]['specialGuests'][] = $appService->findOneSpecialGuest($specialGuest->getIdSpecialGuest())->getFourInformations();
                                                    }
                                                    else { 
                                                        $arraySpecialGuestDay[$specialGuest->getDays()]++;
                                                        //$arrayFckingExportExcel[$daySpecialGuest]['specialGuests'][] = $appService->findOneSpecialGuest($specialGuest->getIdSpecialGuest())->getFourInformations();
                                                    }
                                                }
                                            }
                                        }

                                        //print_r($arraySpecialGuestDay);

                                        $counterPlanningDays = 0;
                                        foreach($arrayPlanningDays as $key => $planningDay) {

                                            $startDatetime = $arrayPlanningDays[$key]->getStartDatetime();
                                            $dayFormattedKey = $startDatetime->format('Y-m-d');
                                            $dayFormattedTd  = $startDatetime->format('d/m/Y');
                                                                                                                                    
                                            // Stores
                                            if( isset($arrayLunchesStores[$dayFormattedKey]) ) {
                                                $totLunchesDayStores = $arrayLunchesStores[$dayFormattedKey];
                                            }
                                            else { 
                                                $totLunchesDayStores = 0; 
                                            }
                                            
                                            // Providers
                                            if( isset($arrayLunchesProviders[$dayFormattedKey]) ) {
                                                $totLunchesDayProviders = $arrayLunchesProviders[$dayFormattedKey];
                                            }
                                            else { 
                                                $totLunchesDayProviders = 0; 
                                            }
                                            
                                            // SpecialGuests
                                            $totLunchesDaySpecialGuests = 0;
                                            if( isset($arraySpecialGuestDay[$dayFormattedKey]) ) {
                                                $totLunchesDaySpecialGuests += $arraySpecialGuestDay[$dayFormattedKey];
                                            }

                                            //print_r($counterPlanningDays);
                                            
                                            // Print data
                                            
                                            echo '
                                            <tr>
                                            <td>'.'(J'.str_pad(($counterPlanningDays+1), 2, '0', STR_PAD_LEFT).') '.$dayFormattedTd.'</td>
                                            <td>'.$totLunchesDayStores.'</td>
                                            <td>'.$totLunchesDayProviders.'</td>
                                            <td>'.$totLunchesDaySpecialGuests.'</td>
                                            <td>'.($totLunchesDayStores+$totLunchesDayProviders+$totLunchesDaySpecialGuests).'</td>
                                            </tr>';
                                            ++$counterPlanningDays;
                                        }
                                        ?>
                                        </tbody>
                                        <tfoot>
                                            <tr>
                                                <th>Jour</th>
                                                <th>Repas Magasins</th>
                                                <th>Repas Fournisseurs</th>
                                                <th>Repas Invités except.</th>
                                                <th>Total Jour</th>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>
                                
                            </div>
                        </div>
                    </div>
                    
                </div><!-- ./row -->
                
                <div class="row">
                    <div class="col-lg-12">

                        <?php                                   
                        // Check days before printing tables
                        foreach($arrayPlanningDays as $key => $planningDay) {
                            $startDatetime = $arrayPlanningDays[$key]->getStartDatetime();
                            $dayFormattedKey = $startDatetime->format('Y-m-d');

                            if(!isset($arrayFckingExportExcel[$dayFormattedKey])) {
                                $arrayFckingExportExcel[$dayFormattedKey]['stores'] = array();
                                $arrayFckingExportExcel[$dayFormattedKey]['providers'] = array();
                                $arrayFckingExportExcel[$dayFormattedKey]['specialGuests'] = array();
                            }
                            if(!isset($arrayFckingExportExcel[$dayFormattedKey]['stores'])) {
                                $arrayFckingExportExcel[$dayFormattedKey]['stores'] = array();
                            }
                            if(!isset($arrayFckingExportExcel[$dayFormattedKey]['providers'])) {
                                $arrayFckingExportExcel[$dayFormattedKey]['providers'] = array();
                            }
                            if(!isset($arrayFckingExportExcel[$dayFormattedKey]['specialGuests'])) {
                                $arrayFckingExportExcel[$dayFormattedKey]['specialGuests'] = array();
                            }                                        
                        }

                        // Now we can print all tables
                        ksort($arrayFckingExportExcel);
                        
                        // Excel Stores
                        foreach($arrayFckingExportExcel as $dayFckingExportExcel => $detailsDay) {

                            $strToEcho  = '<table class="table table-bordered" id="table_stores_'.$dayFckingExportExcel.'" style="display:none">';
                            $strToEcho .= '<tr><th>Nom Magasin</th><th>Repas</th></tr>';

                            if(isset($detailsDay['stores'])) {
                                foreach($detailsDay['stores'] as $storeName => $storeParticipants) {

                                    $strToEcho .= '<tr><td>'.$storeName.'</td><td>';

                                    foreach($storeParticipants as $key => $participantDetails) {
                                        $strToEcho .= $participantDetails.'<br/>';
                                    }

                                    $strToEcho .= '</td></tr>';
                                }
                                $strToEcho  .= '</table>';

                                echo $strToEcho;
                            }
                        }
                        
                        // Excel Providers
                        foreach($arrayFckingExportExcel as $dayFckingExportExcel => $detailsDay) {

                            $strToEcho  = '<table class="table table-bordered" id="table_providers_'.$dayFckingExportExcel.'" style="display:none">';
                            $strToEcho .= '<tr><th>Nom Fournisseur</th><th>Repas</th></tr>';

                            if(isset($detailsDay['providers'])) {
                                foreach($detailsDay['providers'] as $providerName => $providerParticipants) {

                                    $strToEcho .= '<tr><td>'.$providerName.'</td><td>';

                                    foreach($providerParticipants as $key => $participantDetails) {
                                        $strToEcho .= $participantDetails.'<br/>';
                                    }

                                    $strToEcho .= '</td></tr>';
                                }
                                $strToEcho  .= '</table>';

                                echo $strToEcho;
                            }
                        }
                        
                        // Excel SpecialGuests
                        foreach($arrayFckingExportExcel as $dayFckingExportExcel => $detailsDay) {

                            $strToEcho  = '<table class="table table-bordered" id="table_specialGuests_'.$dayFckingExportExcel.'" style="display:none">';
                            $strToEcho .= '<tr><th>Invité Exceptionnel</th></tr>';

                            if(isset($detailsDay['specialGuests'])) {
                                foreach($detailsDay['specialGuests'] as $key => $specialGuest) {

                                    $strToEcho .= '<tr><td>'.$specialGuest.'</td><td></tr>';
                                }
                                $strToEcho  .= '</table>';

                                echo $strToEcho;
                            }
                        }
                        ?>

                    </div>
                </div><!-- ./row -->
                
                <div class="row"><div class="col-lg-12">&nbsp;</div></div>

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

    <!-- DataTables -->
    <script src="js/plugins/dataTables/datatables.min.js"></script>

    <!-- Custom and plugin javascript -->
    <script src="js/inspinia.js"></script>
    <script src="js/plugins/pace/pace.min.js"></script>
    
    <!-- Toastr script -->
    <script src="js/plugins/toastr/toastr.min.js"></script>
    
    <!-- table2excel -->
    <script src="js/plugins/table2excel/jquery.table2excel.js"></script>
	
    <!-- Custom script -->
    <script>
    function exportToExcel(choice, dayFormattedKey) {
        $('#table_' + choice + '_'+ dayFormattedKey).table2excel({
            exclude: ".noExl",
            name: "Récap repas",
            filename: "recap-repas-" + choice + "-" + dayFormattedKey,
            fileext: ".xls",
            exclude_img: true,
            exclude_links: true,
            exclude_inputs: true
        });
    }

    $(function() {
        
        var table = $('#tableLunches').DataTable({
            language: { 
                url: './js/plugins/dataTables/localisation/french.json'
            },
            pageLength: 10,
            responsive: true,
            dom: '<"html5buttons"B>lTfgitp',
            buttons: [
                {extend: 'copy'},
                {extend: 'csv', title: 'recap_repas'},
                {extend: 'excel', title: 'recap_repas'},
                {extend: 'pdf', title: 'recap_repas'},
                {extend: 'print',
                    customize: function (win){
                        $(win.document.body).addClass('white-bg');
                        $(win.document.body).css('font-size', '10px');

                        $(win.document.body).find('table')
                            .addClass('compact')
                            .css('font-size', 'inherit');
                    }
                }
            ],
            initComplete: function( settings, json ) {
                $('#myspinner').hide();
                $('#divTableLunches').show();
            }
        });
    });
    </script>

</body>

</html>
