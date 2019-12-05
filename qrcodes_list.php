<?php
require_once dirname ( __FILE__ ) . '/view/errors.inc.php';
require_once dirname ( __FILE__ ) . '/services/AppServiceImpl.class.php';

if(!isset($_SESSION)) session_start(); // Start session

// Not connected as Admin ?
if(!isset($_SESSION['adminConnected']) && empty($_SESSION['adminConnected'])) {
    header('Location: ./disconnection.php'); // Redirection to Purchasing Fair list
}

header( 'content-type: text/html; charset=utf-8' ); // Specifies to the server to return UTF-8 - put in prod

$appService = AppServiceImpl::getInstance();

$arrayScans = $appService->findAllQRCodeScan();
//var_dump($arrayScans);

if(isset($_POST) && !empty($_POST)) { 
//    $bytesWritten = file_put_contents(dirname ( __FILE__ ) . '/errors/log_errors_php.txt', ''); // Emptying the log file
//    header('Location: ./admin_log_errors_check.php'); // Redirection to the same page to prevent browser warning (Do you want to reload this page...)
}

date_default_timezone_set('Europe/Paris');
?>

<!DOCTYPE html>
<html lang="fr">

<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="theme-color" content="#0b70b5"><!-- Mobile browser Tab Color -->

    <title>PFManagement | Récapitulatif scans</title>
	
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
    /* Spinner color */
    .sk-spinner-wave div { background-color:#0b70b5; }
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
                    <span class="m-r-sm text-muted welcome-message">Récapitulatif des Participants</span>
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
                    <h2><i class="fa fa-table" aria-hidden="true"></i> Récapitulatif des Scans connus aujourd'hui à <?php echo date('H:i:s'); ?></h2>
                    <ol class="breadcrumb">
                        <li class="active">
                            <strong>Administration/Repas/Récapitulatif scans</strong>
                        </li>
                    </ol>
                </div>
                <div class="col-lg-3">
                    <?php require_once dirname ( __FILE__ ) . '/view/widget_pf_info.inc.php'; ?>
                </div>
            </div>

            <div class="wrapper wrapper-content">
                
                <div class="row" >
                    
                    <div class="col-lg-12">
                        <div class="ibox float-e-margins animated zoomIn">
                            <div class="ibox-title" style="border-top:1px solid #0b70b5">
                                <h5><i class="fa fa-qrcode" aria-hidden="true"></i> Liste des scans du plus récent au plus ancien</h5>
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

                                <div id="divTableParticipants" class="table-responsive" style="display:none">
                                    <table id="tableParticipants" class="table table-striped table-bordered table-hover">
                                        <thead>
                                            <tr>
                                                <th><i class="fa fa-clock-o" aria-hidden="true"></i> | Date scan</th>
                                                <th><i class="fa fa-handshake-o" aria-hidden="true"></i> | Salon</th>
                                                <th><i class="fa fa-tag" aria-hidden="true"></i> | Profil</th>
                                                <th><i class="fa fa-id-card-o" aria-hidden="true"></i> | Entreprise</th>
                                                <th><i class="fa fa-venus-mars" aria-hidden="true"></i> | Civilité</th>
                                                <th><i class="fa fa-font" aria-hidden="true"></i> | Nom</th>
                                                <th><i class="fa fa-bold" aria-hidden="true"></i> | Prénom</th>
                                                <th><i class="fa fa-at" aria-hidden="true"></i>| E-mail</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            foreach($arrayScans as $key => $qrcodeScan) {
                                                
                                                // Add type of Provider to Enterprise name if Provider
                                                if($qrcodeScan->getOneEnterprise()->getOneProfile()->getIdProfile() == 1) {
                                                    $addType = '('.$qrcodeScan->getOneEnterprise()->getOneTypeOfProvider()->getNameTypeOfProvider()[0].')';
                                                }
                                                else { $addType = ''; }
                                                
                                                // $lunch = false;
                                                
                                                echo '
                                                <tr>
                                                <td>'.$qrcodeScan->getScanDatetime().'</td>
                                                <td>'.$qrcodeScan->getOnePurchasingFair()->getNamePurchasingFair().'</td>
                                                <td>'.$qrcodeScan->getOneEnterprise()->getOneProfile()->getName().'</td>
                                                <td>'.$qrcodeScan->getOneEnterprise()->getName().$addType.'</td>
                                                <td>'.$qrcodeScan->getOneParticipant()->getCivility().'</td>
                                                <td>'.$qrcodeScan->getOneParticipant()->getSurname().'</td>
                                                <td>'.$qrcodeScan->getOneParticipant()->getName().'</td>
                                                <td>'.$qrcodeScan->getOneParticipant()->getEmail().'</td>
                                                </tr>';
                                            }
                                            ?>
                                        </tbody>
                                        <tfoot>
                                            <tr>
                                                <th>Date scan</th>
                                                <th>Salon</th>
                                                <th>Profil</th>
                                                <th>Entreprise</th>
                                                <th>Civilité</th>
                                                <th>Nom</th>
                                                <th>Prénom</th>
                                                <th>E-mail</th>
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
        
        var table = $('#tableParticipants').DataTable({
            order: [[ 0, 'desc' ]],
            language: { 
                url: './js/plugins/dataTables/localisation/french.json'
            },
            pageLength: 10,
            responsive: true,
            dom: '<"html5buttons"B>lTfgitp',
            buttons: [
                {extend: 'copy'},
                {extend: 'csv', title: 'recap_participants'},
                {extend: 'excel', title: 'recap_participants'},
                {extend: 'pdf', title: 'recap_participants'},
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
                $('#divTableParticipants').show();
            }
        });
    });
    </script>

</body>

</html>
