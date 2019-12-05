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

$arrayParticipants = $appService->summaryParticipants($_SESSION['purchasingFairConcerned']->getIdPurchasingFair());

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

    <title>PFManagement | Récapitulatif Participants</title>
	
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
    /* Castel Access Logo */
    #castelAccessLogo { cursor:pointer; }
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
                    <h2><i class="fa fa-table" aria-hidden="true"></i> Récapitulatif des Participants connus aujourd'hui à <?php echo date('H:i:s'); ?></h2>
                    <ol class="breadcrumb">
                        <li class="active">
                            <strong>Administration/Récapitulatif participants</strong>
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
                                <h5><i class="fa fa-users" aria-hidden="true"></i> Liste des Participants</h5>
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
                                    
                                    <img id="castelAccessLogo" 
                                         alt="castel_access_logo" 
                                         title="Télecharger le fichier d'accès"
                                         class="img-responsive m-b-xl" 
                                         src="img/castel_access_logo.png" 
                                         onclick="exportCastelAccess();"
                                         style="height:45px;width:83px;margin: 0 auto;"/>
                                    <br/>
                                    
                                    <table id="tableParticipants" class="table table-striped table-bordered table-hover">
                                        <thead>
                                            <tr>
                                                <th>Civilité</th>
                                                <th>Nom</th>
                                                <th>Prénom</th>
                                                <th>E-mail</th>
                                                <th>Entreprise(s)</th>
                                                <th>Profil</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        <?php
                                        foreach($arrayParticipants as $key => $value) {
                                            echo '
                                            <tr>
                                            <td>'.$value['civility_participant'].'</td>
                                            <td>'.$value['surname_participant'].'</td>
                                            <td>'.$value['name_participant'].'</td>
                                            <td>'.$value['email_participant'].'</td>
                                            <td>'.$value['name_enterprise'].'</td>
                                            <td>'.$value['name_profile'].'</td>
                                            </tr>';
                                        }?>
                                        </tbody>
                                        <tfoot>
                                            <tr>
                                                <th>Civilité</th>
                                                <th>Nom</th>
                                                <th>Prénom</th>
                                                <th>E-mail</th>
                                                <th>Entreprise(s)</th>
                                                <th>Profil</th>
                                            </tr>
                                        </tfoot>
                                    </table>
                                    <?php
                                    // Total passwords
                                    $arrayPasswordsSixdigits = $appService->sixDigitsGenerator(count($arrayParticipants));
                                    
                                    // 31 fields - See doc/pf_management_castel_csv.pdf for more information
                                    echo '
                                    <table id="tableCastelAccess" style="display:none">
                                        <tr>
                                            <th>Civilite</th>
                                            <th>Nom</th>
                                            <th>Prenom</th>
                                            <th>Societe</th>
                                            <th>Service</th>
                                            <th>Matricule</th>
                                            <th>Telephone</th>
                                            <th>Tel. mobile</th>
                                            <th>E-mail</th>
                                            <th>Nationalite</th>
                                            <th>Commentaire</th>
                                            <th>Code secret</th>
                                            <th>Code sous contrainte</th>
                                            <th>Photo</th>
                                            <th>Diffuser la photo</th>
                                            <th>Liste rouge</th>
                                            <th>Liste noire</th>
                                            <th>Champ 1</th>
                                            <th>Champ 2</th>
                                            <th>Champ 3</th>
                                            <th>Champ 4</th>
                                            <th>Champ 5</th>
                                            <th>Champ 6</th>
                                            <th>Visiteur</th>
                                            <th>Exempt</th>
                                            <th>Derniere visite</th>
                                            <th>LDAP</th>
                                            <th>Code securite Assoc 1</th>
                                            <th>Badge 0 Assoc 1</th>
                                            <th>Profil Badge Assoc 1 Crise 0</th>
                                            <th>Code securite Assoc 2</th>
                                        </tr>';
                                    
                                    $counterSixDigits = 0;
                                    foreach($arrayParticipants as $key => $value) {
                                                                                
                                        echo '
                                        <tr>
                                        <td>'.$value['civility_participant'].'</td>
                                        <td>'.$value['surname_participant'].'</td>
                                        <td>'.$value['name_participant'].'</td>
                                        <td>'.$value['name_enterprise'].'</td>
                                        <td>NULL</td>
                                        <td>NULL</td>
                                        <td>NULL</td>
                                        <td>NULL</td>
                                        <td>'.$value['email_participant'].'</td>
                                        <td>NULL</td>
                                        <td>'.$value['name_profile'].'</td>
                                        <td>'.$arrayPasswordsSixdigits[$counterSixDigits++].'</td>
                                        <td>NULL</td>
                                        <td>NULL</td>
                                        <td>NULL</td>
                                        <td>NULL</td>
                                        <td>NULL</td>
                                        <td>NULL</td>
                                        <td>NULL</td>
                                        <td>NULL</td>
                                        <td>NULL</td>
                                        <td>NULL</td>
                                        <td>NULL</td>
                                        <td>NULL</td>
                                        <td>NULL</td>
                                        <td>NULL</td>
                                        <td>NULL</td>
                                        <td>NULL</td>
                                        <td>NULL</td>
                                        <td>NULL</td>
                                        <td>NULL</td>
                                        </tr>';
                                    }
                                    echo '</table>';
                                    ?>
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
    
    <!-- table2csv -->
    <script src="js/plugins/table2csv/table2csv.js"></script>
	
    <!-- Custom script -->
    <script>
    // CSV Export for CASTEL ACCESS
    function exportCastelAccess() {
        $("#tableCastelAccess").show();
        $("#tableCastelAccess").table2csv('output', {appendTo: '#out'});
        $('#tableCastelAccess').table2csv({
            filename: 'castel_access.csv',
            separator: ';', // default ','
            newline: '\n',
            quoteFields: false, // default true
            excludeColumns: '', // class Name
            excludeRows: '' // // class Name
        });
        $("#tableCastelAccess").hide()
    }
    
    $(function() {
        
        $('[data-toggle="tooltip"]').tooltip(); 
        
        var table = $('#tableParticipants').DataTable({
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
