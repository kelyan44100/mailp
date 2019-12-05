<?php
require_once dirname ( __FILE__ ) . '/view/errors.inc.php';
require_once dirname ( __FILE__ ) . '/services/AppServiceImpl.class.php';

if(!isset($_SESSION)) session_start(); // Start session

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

if(isset($_SESSION['arrayAllPresentsStores'])) { unset($_SESSION['arrayAllPresentsStores']); }
if(isset($_SESSION['arrayAllPresentsProviders'])) { unset($_SESSION['arrayAllPresentsProviders']); }
if(isset($_SESSION['arrayAllPresentsSpecialGuests'])) { unset($_SESSION['arrayAllPresentsSpecialGuests']); }

// Added 27.08.2018 - Taking others Pf into account
$isOtherPf = ( $_SESSION['purchasingFairConcerned']->getOneTypeOfPf()->getNameTypeOfPf() === 'Autre' ) ? true : false;
?>

<!DOCTYPE html>
<html lang="fr">

<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="theme-color" content="#0b70b5"><!-- Mobile browser Tab Color -->

    <title>PFManagement | Contrôle</title>
	
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
    .fa-search-plus { color:green; }
    .iconPrint { color:#0b70b5; }
    .iconExcel { color:#217346; }
    .iconPrint:hover, .fa-envelope-open-o:hover, .fa-refresh:hover, .fa-print:hover, .fa-file-excel-o:hover { cursor:pointer; }
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
                    <span class="m-r-sm text-muted welcome-message">Contrôle</span>
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
                    <h2><i class="fa fa-qrcode" aria-hidden="true"></i> Génération des étiquettes + <i class="fa fa-braille" aria-hidden="true"></i> codes d'accès barrière</h2>
                    <ol class="breadcrumb">
                        <li class="active">
                            <strong>Administration/Contrôle</strong>
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
                                <h5><i class="fa fa-binoculars" aria-hidden="true"></i> Contrôle</h5>
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

                                <div id="divTableStores" class="table-responsive" style="display:none">
                                    <table id="tableStores" class="table table-striped table-bordered table-hover">
                                        <thead>
                                            <tr>
                                                <th><i class="fa fa-calendar" aria-hidden="true"></i> | Jour</th>
                                                <th><i class="fa fa-qrcode" aria-hidden="true"></i> | Étiquettes</th>
                                                <th><i class="fa fa-braille" aria-hidden="true"></i> | Codes d'accès barrière (Fournisseurs)</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        <?php
                                        if(!$isOtherPf) {
                                            $arrayEnterprisesAsProviders = $appService->findAllEnterprisesAsProvidersPf($_SESSION['purchasingFairConcerned']->getIdPurchasingFair());
                                            $arrayEnterprisesAsStores = $appService->findAllEnterprisesAsStoresPf($_SESSION['purchasingFairConcerned']->getIdPurchasingFair());
                                        }
                                        else {
                                            $arrayPP = $appService->findAllProviderPresentForOnePurchasingFair($_SESSION['purchasingFairConcerned']->getIdPurchasingFair());
                                            $arrayEnterprisesAsProviders = array();
                                            foreach($arrayPP as $key => $pp) { $arrayEnterprisesAsProviders[] = $pp->getOneProvider(); }
                                            $arrayEnterprisesAsStores = $appService->findAllEnterprisesAsStores();
                                        }
                                        
                                        // All Presents
                                        $arrayAllPresentsStores = array();
                                        $arrayAllPresentsProviders = array();
                                        $arrayAllPresentsSpecialGuests = array();
                                        
                                        $counterPlanningDays = 0;
                                        foreach($arrayPlanningDays as $key => $planningDay) {

                                            $startDatetime = $arrayPlanningDays[$key]->getStartDatetime();
                                            $dayFormattedKey = $startDatetime->format('Y-m-d');
                                            $dayFormattedTd  = $startDatetime->format('d/m/Y');
                                            
                                            $arrayAllPresentsStores[$dayFormattedKey] = array();
                                            $arrayAllPresentsProviders[$dayFormattedKey] = array();
                                            $arrayAllPresentsSpecialGuests[$dayFormattedKey] = array();
                                            
                                            // Stores
                                            foreach($arrayEnterprisesAsStores as $store) {
                                                $arrayPresents = $appService->findPresentByDuo($store->getIdEnterprise(), $pfConcerned->getIdPurchasingFair());

                                                foreach($arrayPresents as $key => $present) {
                                                    $details = $present->getPresentDetails();
                                                    
                                                    foreach($details as $key => $day) {
                                                        if($day == $dayFormattedKey) {
                                                            $arrayAllPresentsStores[$dayFormattedKey][] = $present;
                                                            break 1;
                                                        }
                                                    }
                                                }
                                            }
                                                                                        
                                            // Data For Javascript function
                                            //$dataStringyfiedStores = json_encode($arrayAllPresentsStores[$dayFormattedKey]);
                                            $_SESSION['arrayAllPresentsStores'] = $arrayAllPresentsStores;
                                            
                                            
                                            // Providers
                                            foreach($arrayEnterprisesAsProviders as $provider) {
                                                
                                                $specialGuests = $appService->findAllSpecialGuestForOneEnterpriseAndPf($provider->getIdEnterprise(), $pfConcerned->getIdPurchasingFair());
                                                
                                                $arrayPresents = $appService->findPresentByDuo($provider->getIdEnterprise(), $pfConcerned->getIdPurchasingFair());

                                                foreach($arrayPresents as $key => $present) {
                                                    $details = $present->getPresentDetails();
                                                    
                                                    foreach($details as $key => $day) {
                                                        if($day == $dayFormattedKey) {
                                                            $arrayAllPresentsProviders[$dayFormattedKey][] = $present;
                                                            break 1;
                                                        }
                                                    }
                                                }
                                                
                                                // SpecialGuests
                                                foreach($specialGuests as $specialGuest) {
                                                    foreach( explode('|', $specialGuest->getDays()) as $daySpecialGuest) {

                                                        if($daySpecialGuest == $dayFormattedKey) {
                                                            $arrayAllPresentsSpecialGuests[$dayFormattedKey][] = $specialGuest;
                                                        }
                                                    }
                                                }

                                            }
                                                                                        
                                            // Data For Javascript function
                                           // $dataStringyfiedProviders = json_encode($arrayAllPresentsProviders[$dayFormattedKey]);
                                            $_SESSION['arrayAllPresentsProviders'] = $arrayAllPresentsProviders;
                                            $_SESSION['arrayAllPresentsSpecialGuests'] = $arrayAllPresentsSpecialGuests;

                                                                                        
                                            // Print data
                                            if(!$isOtherPf) {
                                                echo '
                                                <tr>
                                                <td>'.'(J'.str_pad(($counterPlanningDays+1), 2, '0', STR_PAD_LEFT).') '.$dayFormattedTd.'</td>
                                                <td><i class="fa fa-shopping-cart iconPrint" aria-hidden="true" title="Magasins" onclick="printStickers(\''.$dayFormattedKey.'\', \'store\')"></i> - '
                                                        . '<i class="fa fa-truck iconPrint" aria-hidden="true" title="Fournisseurs" onclick="printStickers(\''.$dayFormattedKey.'\', \'provider\')"></i> -
                                                            <i class="fa fa-user-plus iconPrint" aria-hidden="true" title="Invités exceptionnels" onclick="printStickers(\''.$dayFormattedKey.'\', \'specialGuest\')"</td>
                                                ';
                                            }
                                            else {
                                                echo '
                                                <tr>
                                                <td>'.'(J'.str_pad(($counterPlanningDays+1), 2, '0', STR_PAD_LEFT).') '.$dayFormattedTd.'</td>
                                                <td><i class="fa fa-shopping-cart iconPrint" aria-hidden="true" title="Magasins" onclick="printStickers(\''.$dayFormattedKey.'\', \'store\')"></i> - '
                                                        . '<i class="fa fa-truck iconPrint" aria-hidden="true" title="Fournisseurs" onclick="printStickers(\''.$dayFormattedKey.'\', \'provider\')"></i></td>
                                                ';
                                            }
                                            
                                            if(file_exists('./castel/codes_pf_'.$_SESSION['purchasingFairConcerned']->getIdPurchasingFair().'_'.$dayFormattedKey.'.csv')) {
                                                echo '<td><a href="'.'./castel/codes_pf_'.$_SESSION['purchasingFairConcerned']->getIdPurchasingFair().'_'.$dayFormattedKey.'.csv'.'"><i class="fa fa-file-excel-o iconExcel" aria-hidden="true" title="Télécharger"></i></a>';
                                                echo ' - <i class="fa fa-refresh text-warning" aria-hidden="true" title="Regénérer" onclick="generateCodes(\''.$dayFormattedKey.'\')"></i>';
                                                echo ' - <i class="fa fa-envelope-open-o text-success" aria-hidden="true" title="Envoyer emails" onclick="sendEmails(\''.$dayFormattedKey.'\')"></i></td>';
                                            }
                                            else {
                                                echo '<td><i class="fa fa-file-excel-o text-danger" aria-hidden="true" title="Générer" onclick="generateCodes(\''.$dayFormattedKey.'\')"></i></td>';
                                            }
                                            
                                            echo '</tr>';
                                            ++$counterPlanningDays;
                                        }
                                        ?>
                                        </tbody>
                                        <tfoot>
                                            <tr>
                                                <th>Jour</th>
                                                <th>Étiquettes</th>
                                                <th>Codes d'accès barrière (Fournisseurs)</th>
                                            </tr>
                                        </tfoot>
                                    </table>
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

    <!-- DataTables -->
    <script src="js/plugins/dataTables/datatables.min.js"></script>

    <!-- Custom and plugin javascript -->
    <script src="js/inspinia.js"></script>
    <script src="js/plugins/pace/pace.min.js"></script>
    
    <!-- Toastr script -->
    <script src="js/plugins/toastr/toastr.min.js"></script>
	
    <!-- Custom script -->
    <script>
    $(function() {
        
        var table = $('#tableStores').DataTable({
            language: { 
                url: './js/plugins/dataTables/localisation/french.json'
            },
            pageLength: 10,
            responsive: true,
            dom: '<"html5buttons"B>lTfgitp',
            buttons: [
                {extend: 'copy'},
                {extend: 'csv', title: 'recap_controle'},
                {extend: 'excel', title: 'recap_controle'},
                {extend: 'pdf', title: 'recap_controle'},
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
                $('#divTableStores').show();
            }
        });
    });
    
    function printStickers(day, typeEnterprise) {
        window.open('./admin_stickers_generationV2_loader.php?day=' + day + '&typeEnterprise=' + typeEnterprise, '_blank'); 
    }
        
    function generateCodes(day) {
        
        $('.ibox-content').toggleClass('sk-loading');

        $.get(
            './ajax/codesCastelAccess.php',
            {
                day : day                    
            },
            function(data) {
                toastr.warning('Génération des codes en cours, la page va se recharger...', 'Patienter...');
                setTimeout(function () { location.reload(); }, 2000);
            },
            'text'
        );   
    }
    
    function sendEmails(day) {
        
        $('.ibox-content').toggleClass('sk-loading');
        
        toastr.warning('Envoi des emails en cours...', 'Patienter...');

        $.get(
            './admin_sending_codes_castel.php',
            {
                day : day                    
            },
            function(data) {
                
                $('.ibox-content').toggleClass('sk-loading');
                if( data.trim() === '1' ) {
                    toastr.success('Envoi des emails effectués.', 'Succès');
                }
                else {
                    toastr.error('Erreur lors de l\'envoi des emails, consultez le fichiers d\'erreurs pour + de détails.', 'Erreur');
                }
            },
            'text'
        );   
    }
    </script>

</body>

</html>
