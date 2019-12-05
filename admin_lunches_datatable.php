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

// Added 27.08.2018 - Taking others Pf into account
$isOtherPf = ( $_SESSION['purchasingFairConcerned']->getOneTypeOfPf()->getNameTypeOfPf() === 'Autre' ) ? true : false;
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
                    <h2><i class="fa fa-euro" aria-hidden="true"></i> Récapitulatif de TOUS les Repas connus aujourd'hui à <?php echo date('H:i:s'); ?> pour les Fournisseurs présents (avec prise en compte des invités except. dans le calcul)</h2>
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

                                <div id="divTableStores" class="table-responsive"><!--style="display:none"-->
                                    <table id="tableStores" class="table table-striped table-bordered table-hover">
                                        <thead>
                                            <tr>
                                                <th><i class="fa fa-text-width" aria-hidden="true"></i> | Nom Entreprise</th>
                                                <th><i class="fa fa-tag" aria-hidden="true"></i> | Profil</th>
                                                <th><i class="fa fa-calculator" aria-hidden="true"></i> | Nb prévus</th>
                                                <th><i class="fa fa-qrcode" aria-hidden="true"></i> | Nb annulés</th>
                                                <th><i class="fa fa-wrench" aria-hidden="true"></i> | Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        <?php
                                        $idPurchasingFair = $_SESSION['purchasingFairConcerned']->getIdPurchasingFair();
                                        $arrayEnterprisesAsProviders = $appService->findAllEnterprisesAsProviderPfBis($idPurchasingFair);

                                        //print_r($arrayEnterprisesAsProviders);
                                        
                                        // ONLY FOR PROVIDERS
                                        foreach($arrayEnterprisesAsProviders as $key => $pp) {
                                            
                                            $totLunchesPlannedProvider  = 0;
                                            $totLunchesCanceledProvider = 0;
                                            $totLunchesProvider = 0;
                                            $totSpecialGuestsLunches = 0;

                                            $idProvider    = $pp;
                                            $lunchProvider = $appService->findLunchForOneEnterpriseAndPf($idProvider, $idPurchasingFair);
                                            $specialGuests = $appService->findAllSpecialGuestForOneEnterpriseAndPf($idProvider, $idPurchasingFair);
                                            //print_r($lunchProvider);
                                            if(!empty($lunchProvider)) {
                                                                                        
                                                foreach($lunchProvider as $key => $value) {

                                                    $totLunchesProvider = $totLunchesProvider + $value->getLunchesPlanned();
                                                    if($key == 0){
                                                        $totLunchesCanceledProvider = $totLunchesCanceledProvider + $value->getLunchesCanceled();
                                                    }

                                                }
                                            }

                                            if(!empty($specialGuests)) {

                                                foreach($specialGuests as $specialGuest) {

                                                    ++$totSpecialGuestsLunches;
                                                    
                                                }

                                            }

                                            $totLunchesInvoice = $totLunchesProvider + $totSpecialGuestsLunches - $totLunchesCanceledProvider;
                                            //print_r($appService->findOneEnterprise($pp));
                                            echo '
                                            <tr>
                                            <td>'.$appService->findOneEnterprise($pp)->getName().'('.$appService->findOneEnterprise($pp)->getOneTypeOfProvider()->getNameTypeOfProvider()[0].')</td>
                                            <td>Fournisseur</td>
                                            <td id="tdLunchesPlanned_'.$pp.'">'.( $totLunchesProvider + $totSpecialGuestsLunches ).'</td>
                                            <td id="tdLunchesCanceled_'.$pp.'">'.$totLunchesCanceledProvider.'</td>
                                            <td>
                                                <i class="fa fa-plus-circle actionButton" aria-hidden="true" title="Ajouter un repas à annuler" onclick="plus('.$idProvider.');"></i>|
                                                <i class="fa fa-minus-circle actionButton" aria-hidden="true" title="Enlever un repas à annuler" onclick="minus('.$idProvider.');"></i>|
                                                <i class="fa fa-search-plus actionButton" aria-hidden="true" title="Facture" onclick="openInvoice('.$idProvider.', '.$idPurchasingFair.');"></i>
                                            </td>
                                            </tr>';                                
                                        }
                                        ?>
                                        </tbody>
                                        <tfoot>
                                            <tr>
                                                <th>Nom entreprise</th>
                                                <th>Profil</th>
                                                <th>Nb prévus</th>
                                                <th>Nb annulés</th>
                                                <th>Action</th>
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
                $('#divTableStores').show();
            }
        });
    });
    
    // +1 Lunch for Provider
    function plus(idEnterprise) {
        
        var oldValue = parseInt( $('#tdLunchesCanceled_' + idEnterprise).text(), 10);
        //console.log(oldValue);
        
        if(oldValue == 255) { toastr.error('Le nombre max de repas a été atteint', 'Échec'); }
        else {
            if( confirm('Confirmer l\'ajout d\'un repas à annuler ?') ) {
                $.post(
                    './ajax/updateLunch.php',
                    {
                        idEnterprise : idEnterprise,
                        plusOrMinus: 1
                    },
                    function(data) {
                        //console.log(data);
                        if(data.trim() == '1') {
                            $('#tdLunchesCanceled_' + idEnterprise).html('<span style="color:Green!important"><strong>' + (oldValue+1) + '</strong></span>');
                        }
                        else {
                            $('#tdLunchesCanceled_' + idEnterprise).html('<span style="color:Red!important"><strong>' + $('#tdLunchesCanceled_' + idEnterprise).text() + '</strong></span>');
                        }
                    },
                    'text'
                );
            }
            else { toastr.error('Vous avez annulé l\'ajout d\'un repas', 'Annulation'); }  
        }
    }
    
    // -1 Lunch for Provider
    function minus(idEnterprise) {
        
        var oldValue = parseInt( $('#tdLunchesCanceled_' + idEnterprise).text(), 10);
        
        if(oldValue <= 0) { toastr.error('Le nombre de repas annulés ne peut pas être négatif', 'Échec'); }
        else {
            if( confirm('Confirmer la suppression d\'un repas à annuler?') ) {
                $.post(
                    './ajax/updateLunch.php',
                    {
                        idEnterprise : idEnterprise,
                        plusOrMinus: -1
                    },
                    function(data) {
                        if(data.trim() == '1') {
                            $('#tdLunchesCanceled_' + idEnterprise).html('<span style="color:Green!important"><strong>' + (oldValue-1) + '</strong></span>');
                        }
                        else {
                            $('#tdLunchesCanceled_' + idEnterprise).html('<span style="color:Red!important"><strong>' + $('#tdLunchesCanceled_' + idEnterprise).text() + '</strong></span>');
                        }
                    },
                    'text'
                );
            }
            else { toastr.error('Vous avez annulé la suppression d\'un repas', 'Annulation'); }  
        }
    }
    
    // Open invoice in a new tab
    function openInvoice(idEnterprise, idPurchasingFair) {
    
        var totLunchesPlanned = parseInt($('#tdLunchesPlanned_' + idEnterprise).text().trim(), 10);
        var totLunchesCanceled = parseInt($('#tdLunchesCanceled_' + idEnterprise).text().trim(),10);
        var totlunches = totLunchesPlanned - totLunchesCanceled;

        var parameters = '?idEnterpriseInvoice=' + idEnterprise + '&idPurchasingFairInvoice=' + idPurchasingFair + '&totLunches=' + totlunches; 
        window.open('./ajax/invoice.php' + parameters, '_blank'); 
    }
    </script>

</body>

</html>
