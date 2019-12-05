<?php
require_once dirname ( __FILE__ ) . '/view/check_pf_clotured.inc.php';
if( isset($isClotured) && $isClotured) { header('Location: ./purchasing_fair_list.php'); /*die();*/ }

require_once dirname ( __FILE__ ) . '/view/errors.inc.php';
require_once dirname ( __FILE__ ) . '/domain/PurchasingFair.class.php';
require_once dirname ( __FILE__ ) . '/services/AppServiceImpl.class.php';
require_once dirname ( __FILE__ ) . '/view/check_pf_clotured.inc.php';

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

//    var_dump($_POST); die();
    $arrayRequirements = array();
    foreach($_POST as $key => $value) {
        if( strpos($key, '_bis') === false ) {
            $hours = (int) ($_POST[$key]);
            $minutes = (int) ($_POST[$key.'_bis'] == '30' ? '50' : '00');
            if( $hours > 11 || ( $hours == 11 && $minutes > 0 ) ) { 
                ; // nothing 
            } 
            else {
                $numberOfHoursWanted = $hours.'.'.$minutes;
                $arrayRequirements[] = $appService->createRequirement($_SESSION['enterpriseConcerned'], $appService->findOneEnterprise(substr($key, 9)), $_SESSION['purchasingFairConcerned'], $numberOfHoursWanted);
            }
        }
    }
    
//    var_dump($arrayRequirements);
    
    foreach($arrayRequirements as $value) { $appService->saveRequirement($value); }
    
    header('Location: ./store_choice_providers.php'); // Home page redirection
}

$arrayRequirementsAlreadyRegistered = $appService->findRequirementFilteredDuoSorted($_SESSION['enterpriseConcerned'], $_SESSION['purchasingFairConcerned']);
?>
<!DOCTYPE html>
<html lang="fr">

<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="theme-color" content="#0b70b5"><!-- Mobile browser Tab Color -->

    <title>PFManagement | Choix des Fournisseurs</title>
	
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
    /* ibox */
    .ibox-title { border-top:2px solid #0b70b5; }
    .ibox-title h5 { color:#0b70b5; }
    #submitRequirements { border:1px solid #000000; }
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
                    <span class="m-r-sm text-muted welcome-message">Choix des Fournisseurs + besoins en heures</span>
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
                    <h2><i class="fa fa-id-card-o" aria-hidden="true"></i> - <i class="fa fa-clock-o" aria-hidden="true"></i> Sélection des Fournisseurs et du nombre d'heures souhaités
                    </h2>
                    <ol class="breadcrumb">
                        <li>
                            <a href="./purchasing_fair_list.php">Liste des salons</a>
                        </li>
                        <li class="active">
                            <strong>Sélection Fournisseurs + besoins en heures</strong>
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
                    echo '<h5><i class="fa fa-info-circle" aria-hidden="true"></i> Besoins en heures déjà enregistrés</h5>';
                    $counter = 0;
                    $limit = count($arrayRequirementsAlreadyRegistered);
                    if( $limit ) {
                        foreach($arrayRequirementsAlreadyRegistered as $value) {
                            $counter++;
                            echo '<span id="rowRequirement_'.$value->getIdRequirement().'">';
                            echo '<i class="fa fa-id-card-o" aria-hidden="true"></i> '.$value->getOneProvider()->getName().'('.$value->getOneProvider()->getOneTypeOfProvider()->getNameTypeOfProvider()[0].') <i class="fa fa-long-arrow-right" aria-hidden="true"></i>&nbsp;';
                            echo '<i class="fa fa-clock-o" aria-hidden="true"></i>&nbsp;<span id="numberOfHoursRequirement_'.$value->getIdRequirement().'">'.$value->getNumberOfHoursAlreadyRegistered()."</span>";
                            echo '&nbsp;|&nbsp;';
                            echo '<i class="fa fa-pencil-square-o text-success" title="Modifier" aria-hidden="true" onclick="updateRequirement('.$value->getIdRequirement().');"></i>&nbsp;';
                            echo '<i class="fa fa-window-close text-danger" title="Supprimer" aria-hidden="true" onclick="deleteRequirement('.$value->getIdRequirement().');"></i>';
                            echo '</span>';
                            echo ($counter < $limit) ? '<br>' : '';
                        }
                    }
                    else { echo 'Aucun besoin en heures enregistré'; }
                    ?>
                    </div>
                    
                    <div class="col-lg-offset-3 col-lg-6">
                        <div class="ibox float-e-margins animated fadeInDown">
                            <div class="ibox-title">
                                <h5>Formulaire : Saisie de 1 à 11 heure(s) par tranches de 30 minutes.</h5>
                            </div>
                            <div class="ibox-content">
                                
                                <?php //var_dump($isClotured); ?>
                                <div class="row">
                                    <form class="m-t col-lg-12" role="form" action="#" method="POST">        
                                        
                                        <select id="selectEnterprise" name="selectEnterprise[]" class="form-group form-control full-width" multiple="multiple" required="">
                                            <?php 
											
                                            // By Fabien : Search all providers who have salespersons participating at the purchasing fair concerned. This does not show providers who do not participate !!
//                                            $arrayEnterprises = $appService->findAllEnterprisesAsProvidersPf($_SESSION['purchasingFairConcerned']->getIdPurchasingFair());
//                                            foreach($arrayEnterprises as $key => $value)  {
//                                                echo '<option value="'.$value->getIdEnterprise().'">'.$value->getName().'</option>';
//                                            }
                                            // V2 07/05/2018
                                            $arrayProviderPresent = $appService->findAllProviderPresentForOnePurchasingFair($_SESSION['purchasingFairConcerned']->getIdPurchasingFair());
                                            foreach($arrayProviderPresent as $key => $pp) {
                                                echo '<option value="'.$pp->getOneProvider()->getIdEnterprise().'">'.$pp->getOneProvider()->getName().'('.$pp->getOneProvider()->getOneTypeOfProvider()->getNameTypeOfProvider()[0].')</option>';
                                            }
                                            ?>
                                        </select>

                                    </form>
                                </div>
                                <div class="row m-t-md">
                                    <div id="formProvidersGenerated" class="col-lg-12">
                                        <div class="text-center text-danger"><span><i class="fa fa-info-circle" aria-hidden="true"></i> Merci de choisir les Fournisseurs pour lesquels vous avez des besoins en heures</span></div>
                                    </div>
                                    <div class="col-lg-12 m-b-md">&nbsp;</div>
                                </div>
                            </div>
                            <div class="ibox-footer">
                                <button id="nextButton" name="nextButton" type="button" class="btn hvr-icon-forward pull-right">Suivant</button>
                                <button id="previousButton" name="previousButton" type="button" class="btn hvr-icon-back">Précédent</button>
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
    
    <!-- Toastr script -->
    <script src="js/plugins/toastr/toastr.min.js"></script>

    <!-- Custom and plugin javascript -->
    <script src="js/inspinia.js"></script>
    <script src="js/plugins/pace/pace.min.js"></script>
    
    <!-- Select2 -->
    <script src="js/plugins/select2/select2.full.min.js"></script>
    <script src="js/plugins/select2/i18n/fr.js"></script>
    
    <!-- Bootbox -->
    <script src="js/plugins/bootbox.js/bootbox.min.js"></script>

    <script>
    // select2 activation
    $("#selectEnterprise").select2( { placeholder: "--- Choix Fournisseur(s) ---", language: "fr" });
    
    $("#selectEnterprise").change(function() {
        
        $("#formProvidersGenerated").html(""); // Reset list providers
        
        var selectedValues = [];   
        $("#selectEnterprise :selected").each(function(){
            selectedValues.push([$(this).val(), $(this).text()]); 
        });

        $.post(
            './ajax/generate_providers_requirements_list.php',
            {
                providers : JSON.stringify(selectedValues)
                
            },
            function(data){
                $("#formProvidersGenerated").html(data);
            },
            'text'
        );
//        console.log(selectedValues);
        return false;
    });
    
    /* Click event (previousButton and nextButton) */
    $("#previousButton").click(function(){ window.location.assign("./purchasing_fair_list.php"); });
    $("#nextButton").click(function(){ window.location.assign("./store_unavailabilities_register.php"); });
    
    
    bootbox.setDefaults({ locale: "fr" });
    
    /* Do an action when the user clicks the update or delete icons */
    function updateRequirement(idRequirement) {
        
        bootbox.prompt({
            title: 'Choisissez un nouveau besoin en heures :',
            inputType: 'time',
            value: $('#numberOfHoursRequirement_' + idRequirement).text().replace('h', ':').replace('m', ''),
            callback: function (result) {

                if(result !== null) {

                    // result = result.trim();

                    // if(result >= 1 && result <= 11) {

                        $.post(
                            './ajax/updateRequirement.php',
                            {
                                idRequirement : idRequirement,
                                numberOfHours : result
                            },
                            function(data) {
                                if(data.trim() === 'Success') {
                                    var hours = result.substring(0,2);
                                    var minutes = result.substring(3,5);
                                    if(parseInt(minutes, 10) != 30 && parseInt(minutes, 10) != 0) minutes = '00';
                                    $('#numberOfHoursRequirement_' + idRequirement).html('<span style="color:Green"><strong>' + hours + 'h' + minutes + 'm' + '</strong></span>'); // It overwrites the content
                                    toastr.success('Action réussie', 'Succès.');
                                }
                                else {
                                    toastr.error('Erreur. Saisie invalide', 'Échec.');
                                }
                            },
                            'text'
                        );
                    }
                    // else nothing
                    // else { toastr.error('Nombre d\'heures invalide', 'Échec.'); }
                // }
            }
        });
    }
    
    function deleteRequirement(idRequirement) {
        
        bootbox.confirm("Confirmer la suppression ?", function(result) { 
                        
            if(result === true) {
                
                $.post(
                    './ajax/deleteRequirement.php',
                    {
                        idRequirement : idRequirement
                    },
                    function(data) {
                        if(data.trim() === 'Success') {
                            $('#rowRequirement_' + idRequirement).html('<span class="text-danger"><i class="fa fa-exclamation-triangle" aria-hidden="true"></i>&nbsp;Besoin en heures supprimé.</span>');
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
