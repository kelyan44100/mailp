<?php
require_once dirname ( __FILE__ ) . '/view/errors.inc.php';
require_once dirname ( __FILE__ ) . '/services/AppServiceImpl.class.php';

if(!isset($_SESSION)) session_start(); // Start session

// Not connected as Admin ?
if(!isset($_SESSION['enterpriseConcerned']) && empty($_SESSION['enterpriseConcerned'])) {
    header('Location: ./disconnection.php'); // Redirection to Purchasing Fair list
}

header( 'content-type: text/html; charset=utf-8' ); // Specifies to the server to return UTF-8 - put in prod

$appService = AppServiceImpl::getInstance();

if( isset($_SESSION['salespersonsConcerned']) && !empty($_SESSION['salespersonsConcerned']) ) {
    
    $arrayParticipants = $_SESSION['salespersonsConcerned'];
    $nbParticipants = count($arrayParticipants);

}

if( isset($_SESSION['NotsalespersonsConcerned']) && !empty($_SESSION['NotsalespersonsConcerned']) ) {
    
    $NotS = array();

    foreach($_SESSION['NotsalespersonsConcerned'] as $value) {
        $NotS[] = $value->getIdParticipant();
    }

    $_SESSION['NotS'] = $NotS;

    //print_r($_SESSION['NotS']);
}

if( isset($_SESSION['AllCommerciauxPotentiels']) && !empty($_SESSION['AllCommerciauxPotentiels']) ) {
    
    $AllCommerciauxPotentielsDuFournisseurs = array();
    
    foreach($_SESSION['AllCommerciauxPotentiels'] as $value) {
        $AllCommerciauxPotentielsDuFournisseurs[] = $value->getIdParticipant();
    }

    $_SESSION['AllCommerciauxPotentielsDuFournisseurs'] = $AllCommerciauxPotentielsDuFournisseurs;

}


?>
<!DOCTYPE html>
<html lang="fr">

<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="theme-color" content="#0b70b5"><!-- Mobile browser Tab Color -->

    <title>PFManagement | Choix des commerciaux</title>

    <!-- Bootstrap -->
    <link href="css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link href="font-awesome/css/font-awesome.css" rel="stylesheet">
    
    <!-- Toastr style -->
    <link href="css/plugins/toastr/toastr.min.css" rel="stylesheet">
    
    <!-- iCheck -->
    <link href="css/plugins/iCheck/custom.css" rel="stylesheet">

    <!-- Animate -->
    <link href="css/animate.css" rel="stylesheet">
    
    <!-- Global -->
    <link href="css/style.css" rel="stylesheet">
    
    <!-- Custom style -->
    <style>
    /* Widget */
    .widget { color:#ffffff;border:1px solid #ffffff; }
    .ibox-title { border-top:2px solid #0b70b5; }
    .ibox-title h5 { color:#0b70b5; }
    .checkUncheckAll {cursor:pointer }
    #previousButton { background-color:#ffffff;color:#0b70b5;border:1px solid #0b70b5; }
    #nextButton { background-color:#0b70b5;color:#ffffff;border:1px solid #000000; }
    /* Scroll Back To previous Button*/
    #scrollBackToPreviousButton {
        display: none; /* Hidden by default */
        position: fixed; /* Fixed/sticky position */
        bottom: 20px; /* Place the button at the bottom of the page */
        right: 30px; /* Place the button 30px from the right */
        z-index: 99; /* Make sure it does not overlap */
        border: 1px solid #ffffff; /* borders */
        outline: none; /* Remove outline */
        background-color: #0b70b5; /* Set a background color */
        color: white; /* Text color */
        cursor: pointer; /* Add a mouse pointer on hover */
        padding: 15px; /* Some padding */
        border-radius: 10px; /* Rounded corners */
    }

    #scrollBackToPreviousButton:hover {
        background-color: #ed8b18; /* Add a background on hover */
    }
    </style>

</head>

<body>

    <div id="wrapper">

        <?php require_once dirname ( __FILE__ ) . '/view/menu.provider.inc.php'; ?>

        <div id="page-wrapper" class="gray-bg">
        <div class="row border-bottom">
        <nav class="navbar navbar-static-top  " role="navigation" style="margin-bottom: 0">
        <div class="navbar-header">
            <a class="navbar-minimalize minimalize-styl-2 btn btn-primary " href="#"><i class="fa fa-bars"></i> </a>
        </div>
            <ul class="nav navbar-top-links navbar-right">
                <li>
                    <span class="m-r-sm text-muted welcome-message">Choix des commerciaux pour le salon.</span>
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
                    <h2><i class="fa fa-id-badge" aria-hidden="true"></i> Choix des commerciaux</h2>
                    <ol class="breadcrumb">
                        <li>
                            <a href="./purchasing_fair_list.php">Liste des salons</a>
                        </li>
                        <li class="active">
                            <strong>Choix des commerciaux</strong>
                        </li>
                    </ol>
                </div>
                <div class="col-lg-3">
                    <?php require_once dirname ( __FILE__ ) . '/view/widget_pf_info.inc.php'; ?>
                </div>
            </div>
            
            <div class="row">
                
                <div class="col-lg-offset-1 col-lg-10 m-t-md">
                    <div id="myIbox" class="ibox float-e-margins">
                        <div class="ibox-title">
                            <h5>Formulaire</h5>
                            <div class="alert alert-danger m-t-md">
                                <span>
                                    <i class="fa fa-bullhorn" aria-hidden="true"></i> Ces informations sont d'une grande importance. Vous devez attribuer un commercial pour chaque Magasin !<!-- il est de votre ressort de saisir des données valides pour recevoir les invitations.-->
                                </span>
                            </div>
                        </div>
                        <div class="ibox-content">
                            <div class="row">
                                <div class="col-lg-12" style="overflow-x:scroll;">
                                <table id="myTable" class="table table-striped table-hover table-responsive table-bordered"  style="display:none">
                        <thead>
                            <tr> <!-- utiliser des Boutons RADIO à la place des CHECKBOX-->
                                <td class="text-center"><b>MAGASINS</b></td>
                                <?php foreach($arrayParticipants as $key => $value) { echo '<th class="text-center">REPRÉSENTANT '.($key+1).' :<br>Nom : '.$value->getSurname().'</th>'; } ?>
                            </tr>
                        </thead>
                        <tbody>
                    <?php 
                    $arrayEnterprises = $appService->findAllEnterprisesAsStores();
                    $_SESSION['arrayEnterprises'] = $arrayEnterprises;
                    foreach($arrayEnterprises as $key => $value) {
                        $storeConcerned = $value; //on met dans collone de gauche tous les entreprises qui sont des magasins
                        echo '<tr>';
                        echo '<td class="text-center">'.$value->getName().'</td>';
                        
                        foreach($arrayParticipants as $key => $value) {
                        $checked = ( $appService->findOneAssignmentSpStore($value->getIdParticipant(), $storeConcerned->getIdEnterprise(), $_SESSION['enterpriseConcerned']->getIdEnterprise(), $_SESSION['purchasingFairConcerned']->getIdPurchasingFair()) != null ) ?
                                'checked' : '';
                        echo '<td class="text-center">';
                        echo '<div class="i-checks"><label> <input type="radio" value="participant_'.$value->getIdParticipant().'" name="idEnterprise_'.$storeConcerned->getIdEnterprise().'" '.$checked.'> <i></i></label></div>';
                        echo '</td>';
                        }

                        echo '</tr>';
                    }
                    ?>
                        </tbody>

                    </table>
                                </div>
                            </div>
                            <div class="col-lg-12">&nbsp;</div>
                        </div>
                        <div class="ibox-footer">
                            <button id="nextButton" name="nextButton" type="button" class="btn hvr-icon-forward pull-right">Suivant</button>
                            <button id="previousButton" name="previousButton" type="button" class="btn hvr-icon-back">Précédent</button>
                        </div>
                    </div>
                </div>
                
                
                <div class="col-lg-offset-3 col-lg-6 m-t-md">
                &nbsp;
                </div>
                
                <!-- Scroll Back To Previous Button-->
                <button onclick="scrollBackToPrevious()" id="scrollBackToPreviousButton" title=""><i class="fa fa-arrow-circle-left" aria-hidden="true"></i> Précédent</button>
                
            </div><!-- ./row -->

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
    
    <!-- iCheck -->
    <script src="js/plugins/iCheck/icheck.min.js"></script>
    
    <!-- Toastr script -->
    <script src="js/plugins/toastr/toastr.min.js"></script>

    <!-- Custom and plugin javascript -->
    <script src="js/inspinia.js"></script>
    <script src="js/plugins/pace/pace.min.js"></script>

    <!-- Custom script -->
    <script>
    // icheck class
    $(document).ready(function () {
        $('.i-checks').iCheck({
            checkboxClass: 'icheckbox_square-green',
            radioClass: 'iradio_square-green'
        });
    });
    
    // obligatoire sinon confilt avec jquery et les listerner
    $('input').on('ifChecked', function(event){

        /*console.log($(this).attr('name'));
        console.log($(this).val());*/

        $.post(
            './ajax/insertAssignmentSpStore.php',
            {
                store          : $(this).attr('name'),
                salesperson    : $(this).val(),
                provider       : <?php echo $_SESSION['enterpriseConcerned']->getIdEnterprise(); ?>,
                purchasingFair : <?php echo $_SESSION['purchasingFairConcerned']->getIdPurchasingFair(); ?>
            },
            function(data) {
                if(data.trim() === 'Success') { 
                     //toastr.success('Affectation réussie', 'Succès.');
                }
                else {
                    //console.log(data);
                    toastr.error('Erreur fatale. Contactez l\'administrateur.', 'Échec.');
                }
            },
            'text'
        );
    
    });
    
    $('input').on('ifUnchecked', function(event){ 
    console.log("inchecked");
        $.post(
            './ajax/deleteAssignmentSpStore.php',
            {
                store          : $(this).attr('name'),
                salesperson    : $(this).val(),
                provider       : <?php echo $_SESSION['enterpriseConcerned']->getIdEnterprise(); ?>,
                purchasingFair : <?php echo $_SESSION['purchasingFairConcerned']->getIdPurchasingFair(); ?>
            },
            function(data) {
                if(data.trim() === 'Success') { 
                     //toastr.success('Action réussie', 'Succès.');
                }
                else {
                    //toastr.error('Erreur fatale. Contactez l\'administrateur.', 'Échec.');
                }
            },
            'text'
        );


    });
    
    function checkAllCheckboxes() { $('input').iCheck('check'); }
    
    function uncheckAllCheckboxes() { $('input').iCheck('uncheck'); }
    
    $("#previousButton").click(function(){ window.location.assign("./admin_salesperson_list.php"); });
    $("#nextButton").click(function(){
        $nb = 0;
        $('tbody tr').each(function(){
            $nb++;
        });
        $.post(
            './ajax/VerifCountAssignmentSpStore.php',
            {
                numberOfLigne : $nb,

            },
            function(data) {
                if(data.trim() === 'Success') { 
                    toastr.success('Action réussie', 'Succès.');
                    window.location.assign("./admin_sp_unav_register.php"); 
                }
                else {
                    console.log(data);
                    alert("Erreur. Certains Magasins n'ont pas de commerciaux attribué.");
                }
            },
            'text'
        );
        
    });


    $(function() { $('#myTable').show(); }); // Because the i-check plugin takes a long time to modify the checkbox classes 

    // https://www.w3schools.com/howto/howto_js_scroll_to_top.asp
    // When the user scrolls down 20px from the top of the document, show the button
    window.onscroll = function() {scrollFunction()};

    function scrollFunction() {
        if (document.body.scrollTop > 20 || document.documentElement.scrollTop > 20) {
            document.getElementById('scrollBackToPreviousButton').style.display = 'block';
        } else {
            document.getElementById('scrollBackToPreviousButton').style.display = 'none';
        }
    }
    // When the user clicks on the button, scroll to the top of the document
    function scrollBackToPrevious() {
        // document.body.scrollTop = 0; // For Safari
        // document.documentElement.scrollTop = 0; // For Chrome, Firefox, IE and Opera
        window.location.assign("./admin_salesperson_list.php");
    }
    </script>

</body>

</html>
