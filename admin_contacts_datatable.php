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

$arrayEnterprises = $appService->findAllEnterprises();

date_default_timezone_set('Europe/Paris');

function emailContactCompleted() {
    
    global $appService;
    global $arrayEnterprises;
    
    $totalEnterprises = count($arrayEnterprises);
    $counterEmails = 0;

    foreach($arrayEnterprises as $enterprise) {
        $enterpriseContact = $appService->findOneEnterpriseContactByEnterprise($enterprise->getIdEnterprise());
        if( !empty( $enterpriseContact->getEmail() ) ) { ++$counterEmails; }
    }
    return array($totalEnterprises, $counterEmails);
}

function validEmails() {
    
    global $appService;
    global $arrayEnterprises;
    
    $counterEmails = 0;
    $counterValidEmails = 0;

    foreach($arrayEnterprises as $enterprise) {
        $enterpriseContact = $appService->findOneEnterpriseContactByEnterprise($enterprise->getIdEnterprise());
        if( !empty( $enterpriseContact->getEmail() ) ) { ++$counterEmails; }
        if( filter_var( $enterpriseContact->getEmail(), FILTER_VALIDATE_EMAIL ) ) { ++$counterValidEmails; }
    }
    return array($counterEmails, $counterValidEmails);
}
?>

<!DOCTYPE html>
<html lang="fr">

<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="theme-color" content="#0b70b5"><!-- Mobile browser Tab Color -->

    <title>PFManagement | Liste Contacts</title>
	
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
    /* Spinenr color */
    .sk-spinner-wave div, .sk-spinner-three-bounce div{ background-color:#0b70b5; }
    /* Update/Remove icons */
    .actionEdit, .actionDelete { cursor:pointer }
    .actionEdit { color:#0b70b5; }
    .actionDelete { color:#ed8b18; }
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
                    <span class="m-r-sm text-muted welcome-message">Liste des Contacts enregistrés</span>
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
                    <h2><i class="fa fa-table" aria-hidden="true"></i> Récapitulatif de TOUS les Contacts connus aujourd'hui à <?php echo date('H:i:s'); ?></h2>
                    <ol class="breadcrumb">
                        <li class="active">
                            <strong>Administration/Liste Contacts</strong>
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
                    
                    <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6 animated zoomIn">
                        <div class="ibox" style="border-top:1px solid #0b70b5">
                            <div class="ibox-content">
                                <h5 class="text-center" style="color:#0b70b5">% fiches de contacts OK (email renseigné) <i class="fa fa-thumbs-o-up" aria-hidden="true"></i></h5>
                                <div class="text-center">
                                    <div id="sparklineA"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6 animated zoomIn">
                        <div class="ibox" style="border-top:1px solid #ed8b18">
                            <div class="ibox-content">
                                <h5 class="text-center" style="color:#ed8b18">% emails saisis VALIDES <i class="fa fa-smile-o" aria-hidden="true"></i></h5>
                                <div class="text-center">
                                    <div id="sparklineB"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-lg-12">
                        
                        <div class="ibox float-e-margins animated zoomIn">
                            <div class="ibox-title" style="border-top:1px solid #0b70b5">
                                <h5><i class="fa fa-users" aria-hidden="true"></i> Liste des Contacts</h5>
                            </div>
                            <div class="ibox-content">

                                <div id="myspinner">
                                <div class="sk-spinner sk-spinner-three-bounce">
                                    <div class="sk-bounce1"></div>
                                    <div class="sk-bounce2"></div>
                                    <div class="sk-bounce3"></div>
                                </div>
                                    <div class="text-center colorBlueLeclerc">Chargement des données...</div>
                                </div>
                                

                                <div id="divTableContacts" class="table-responsive" style="display:none">
                                    <div class="alert alert-info">
                                        <i class="fa fa-info-circle" aria-hidden="true"></i>&nbsp;
                                        L'accès à cette page permet de vérifier que chaque Fournisseur/Magasin possède une fiche de contact. Si ce n'est pas le cas, il en en sera créé une nouvelle avec les données à renseigner.
                                    </div>
                                    
                                    <table id="tableContacts" class="table table-striped table-bordered table-hover">
                                        <thead>
                                            <tr>
                                                <th><i class="fa fa-text-width" aria-hidden="true"></i> | Entreprise</th>
                                                <th><i class="fa fa-user-circle-o" aria-hidden="true"></i> | Profil</th>
                                                <th><i class="fa fa-venus-mars" aria-hidden="true"></i> | Civilité</th>
                                                <th><i class="fa fa-font" aria-hidden="true"></i> | Nom de famille</th>
                                                <th><i class="fa fa-bold" aria-hidden="true"></i> | Prénom</th>
                                                <th><i class="fa fa-at" aria-hidden="true"></i> | E-mail</th>
                                                <th><i class="fa fa-at" aria-hidden="true"></i> | Date enregistrement</th>
                                                <th><i class="fa fa-cogs" aria-hidden="true"></i> | Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            foreach($arrayEnterprises as $key => $enterprise) {
                                                
                                                $enterpriseContact = $appService->findOneEnterpriseContactByEnterprise($enterprise->getIdEnterprise());
                                                if( empty($enterpriseContact) ) { // If contact does not exist
                                                    $newEnterpriseContact = $appService->createEnterpriseContact('NC', 'NC', 'NC', '', $enterprise->getIdEnterprise());
                                                    $idNewEntepriseContact = $appService->saveEnterpriseContact($newEnterpriseContact);
                                                    $enterpriseContact = $appService->findOneEnterpriseContact($idNewEntepriseContact);
                                                }
                                                
                                                if( !is_null( $enterpriseContact->getOneEnterprise()->getOneTypeOfProvider() ) ) {
                                                    $typeEnterprise = '('.$enterpriseContact->getOneEnterprise()->getOneTypeOfProvider()->getNameTypeOfProvider()[0].')';
                                                }
                                                else { $typeEnterprise = ''; }
                                                                                                
                                                echo '
                                                <tr id="rowContacts_'.$enterpriseContact->getIdEnterpriseContact().'">
                                                <td id="tdEnterpriseContacts_'.$enterpriseContact->getIdEnterpriseContact().'">'.$enterpriseContact->getOneEnterprise()->getName().$typeEnterprise.'</td>
                                                <td id="tdProfileContacts_'.$enterpriseContact->getIdEnterpriseContact().'">'.$enterpriseContact->getOneEnterprise()->getOneProfile()->getName().'</td>
                                                <td id="tdCivilityContacts_'.$enterpriseContact->getIdEnterpriseContact().'">'.$enterpriseContact->getCivility().'</td>
                                                <td id="tdSurnameContacts_'.$enterpriseContact->getIdEnterpriseContact().'">'.$enterpriseContact->getSurname().'</td>
                                                <td id="tdNameContacts_'.$enterpriseContact->getIdEnterpriseContact().'">'.$enterpriseContact->getName().'</td>
                                                <td id="tdEmailContacts_'.$enterpriseContact->getIdEnterpriseContact().'">'.$enterpriseContact->getEmail().'</td>
                                                <td id="tdRegistrationDateContacts_'.$enterpriseContact->getIdEnterpriseContact().'">'.$enterpriseContact->getRegistrationDate().'</td>
                                                <td>
                                                <i class="fa fa-venus-mars actionEdit" aria-hidden="true" title="Modifier Civilité" onclick="contactUpdate('.$enterpriseContact->getIdEnterpriseContact().', \'civility\')"></i>
                                                &nbsp;&nbsp;
                                                <i class="fa fa-font actionEdit" aria-hidden="true" title="Modifier Nom de famille" onclick="contactUpdate('.$enterpriseContact->getIdEnterpriseContact().', \'surname\')"></i>
                                                &nbsp;&nbsp;
                                                <i class="fa fa-bold actionEdit" aria-hidden="true" title="Modifier Prénom" onclick="contactUpdate('.$enterpriseContact->getIdEnterpriseContact().', \'name\')"></i>
                                                &nbsp;&nbsp;
                                                <i class="fa fa-at actionEdit" aria-hidden="true" title="Modifier Email" onclick="contactUpdate('.$enterpriseContact->getIdEnterpriseContact().', \'email\')"></i>
                                                &nbsp;&nbsp;
                                                <i class="fa fa-times-circle actionDelete" aria-hidden="true" title="Supprimer" onclick="contactDelete('.$enterpriseContact->getIdEnterpriseContact().')"></i>
                                                </td>                                               
                                                </tr>';
                                            }
                                            ?>
                                        </tbody>
                                        <tfoot>
                                            <tr>
                                                <th>Entreprise</th>
                                                <th>Profil</th>
                                                <th>Civilité</th>
                                                <th>Nom de famille</th>
                                                <th>Prénom</th>
                                                <th>E-mail</th>
                                                <th>Date enregistrement</th>
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
    
    <!-- Sparkline -->
    <script src="js/plugins/sparkline/jquery.sparkline.min.js"></script>

    <!-- Custom and plugin javascript -->
    <script src="js/inspinia.js"></script>
    <script src="js/plugins/pace/pace.min.js"></script>
    
    <!-- Toastr script -->
    <script src="js/plugins/toastr/toastr.min.js"></script>
	
    <!-- Custom script -->
    <script>
     
    $(function() {
        
        <?php $arrayEmailContactCompleted = emailContactCompleted(); $arrayValidEmails = validEmails(); ?>
        $("#sparklineA").sparkline([<?php echo $arrayEmailContactCompleted[1]; ?>, <?php echo $arrayEmailContactCompleted[0] - $arrayEmailContactCompleted[1]; ?>], {
            type: 'pie',
            height: '40',
            sliceColors: ['#0b70b5', '#F5F5F5']
        });

        $("#sparklineB").sparkline([<?php echo $arrayValidEmails[1] ?>, <?php echo $arrayValidEmails[0] - $arrayValidEmails[1] ?>], {
            type: 'pie',
            height: '40',
            sliceColors: ['#ed8b18', '#F5F5F5']
        });
    
        var table = $('#tableContacts').DataTable({
            language: { 
                url: './js/plugins/dataTables/localisation/french.json'
            },
            pageLength: 10,
            responsive: true,
            dom: '<"html5buttons"B>lTfgitp',
            buttons: [
                {extend: 'copy'},
                {extend: 'csv', title: 'recap_contacts'},
                {extend: 'excel', title: 'recap_contacts'},
                {extend: 'pdf', title: 'recap_contacts'},
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
                $('#divTableContacts').show();
            }
        });
    });
    
    // Rename a Contacts LIVE
    function contactUpdate(idContact, whatIsChanged) {
        
        var tdId = '';
        var msgInfo = '';
        
        switch(whatIsChanged) {
            case 'civility': tdId = '#tdCivilityContacts_'; msgInfo = 'la nouvelle civilité :'; break;
            case 'surname' : tdId = '#tdSurnameContacts_'; msgInfo = 'le nouveau nom de famille :'; break;
            case 'name'    : tdId = '#tdNameContacts_'; msgInfo = 'le nouveau prénom :'; break;
            case 'email'   : tdId = '#tdEmailContacts_'; msgInfo = 'le nouvel email :'; break;
            default        : tdId = 'ERROR';
        }

        var oldValue = $(tdId + idContact).text();

        var newValue = prompt('Choisir ' + msgInfo, oldValue); // Get the previous value of file description

        if(newValue != null && newValue.length > 1 && newValue != oldValue) { // Check before update
            
            newValue = newValue.trim(); // The trim() method removes whitespace from both sides of a string

            $.post(
                './ajax/contacts_update.php',
                {
                    idContact    : idContact,
                    newValue      : newValue,
                    whatIsChanged : whatIsChanged
                },
                function(data) {
                    if(data.trim() === '1') {
                        $(tdId + idContact).html('<span style="color:Green!important"><strong>' + newValue + '</strong></span>');
                        toastr.success('Fiche de contact mise à jour.', 'Succès.');
                    }
                    else {
                        $(tdId + idContact).html('<span style="color:Red!important"><strong>' + $(tdId + idContact).text() + '</strong></span>');
                        toastr.error('Saisie incorrecte.', 'Échec.');
                    }
                },
                'text'
            );
        }
        else { toastr.error('Saisie annulée ou incorrecte.', 'Échec.'); }
    }
    
    // Delete a Contacts LIVE
    function contactDelete(idContact) {
        if ( confirm("Confirmer votre choix ?") != false ) { // The confirm() method returns true if the user clicked "OK", and false otherwise.

            $.post(
                './ajax/contacts_delete.php',
                {
                    idContact : idContact                    
                },
                function(data) {
                    if(data.trim() === '1') { location.reload(); }
                },
                'text'
            );        
        }
    }
    </script>

</body>

</html>