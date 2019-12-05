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

if(isset($_POST) && !empty($_POST)) {
    
    $arrayNewEnterprises = array();
    $counterInsertsOK = 0;
    $arrayInsertsNOK = array();
    
    $arrayWithIndexes = array();
    $counterPost = count($_POST);
    
//    var_dump($_POST);
    
    foreach($_POST as $key => $value) { $arrayWithIndexes[] = $value; }
        
    for($n = 0 ; $n < $counterPost ; $n += 2 ) {
        if( $appService->saveEnterprise( 
                $appService->createEnterprise( trim(strtoupper($arrayWithIndexes[$n])), 
                        '12345678', 
                        '00.00',
                        'NC', 
                        'NC', 
                        'NC', 
                        'NC', 
                        $appService->findOneTypeOfProvider($arrayWithIndexes[$n+1]), 
                        $appService->findOneProfile(1), 
                        $appService->findOneDepartment(99) 
                        ) ) ) {
            $counterInsertsOK++;
        }
        else { 
            $arrayInsertsNOK[] = $arrayWithIndexes[$n]; 
        }
    }
    
    
//    foreach($_POST as $key => $value) {
//        

//    }
        
//    $nbParticipants = count($_POST) / 3; // Because the are 3 inputs for each Participant
//    $arrayParticipantsToRegister = array();
//
//    for( $w = 1 ; $w <= $nbParticipants ; $w++ ) {
//        $civility = (string) $_POST['civilityParticipant_'.$w];
//        $surname  = (string) $_POST['surnameParticipant_'.$w];
//        $name     = (string) $_POST['nameParticipant_'.$w];
//        $email    = (string) $_POST['emailParticipant_'.$w];
//        $arrayParticipantsToRegister[] = $appService->createParticipant($civility,$surname,$name,$email);
//    }
//    
//    foreach($arrayParticipantsToRegister as $value) {
//        
//        $idParticipantSaved = $appService->saveParticipant($value);
//        
//        // If the participant is coorectly saved
//        if( $idParticipantSaved ) {
//            $appService->saveAssignmentParticipantEnterprise($appService->createAssignmentParticipantEnterprise($appService->findOneParticipant($idParticipantSaved),$_SESSION['enterpriseConcerned']));
//            $appService->saveAssignmentParticipantDepartment($appService->createAssignmentParticipantDepartment($appService->findOneParticipant($idParticipantSaved),$_SESSION['enterpriseConcerned']->getOneDepartment()));
//            $appService->saveParticipation($appService->createParticipation($appService->findOneParticipant($idParticipantSaved),$_SESSION['purchasingFairConcerned'],'123456',0));
//        }
//    }
//    
//    header('Location: ./store_choice_participants.php'); // Home page redirection
}

//$arrayParticipationsAlreadyRegistered = $appService->findAllParticipationsByEnterpriseAndPurchasingFair($_SESSION['enterpriseConcerned'], $_SESSION['purchasingFairConcerned']);
?>

<!DOCTYPE html>
<html lang="fr">

<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="theme-color" content="#0b70b5"><!-- Mobile browser Tab Color -->

    <title>PFManagement | Saisie fournisseurs</title>
	
    <!-- Favicon -->
    <?php require_once dirname ( __FILE__ ) . '/view/favicon.inc.php'; ?>

    <!-- Bootstrap -->
    <link href="css/bootstrap.min.css" rel="stylesheet">
	
    <!-- Font Awesome -->
    <link href="font-awesome/css/font-awesome.css" rel="stylesheet">
	
    <!-- Hover -->
    <link href="css/plugins/hover.css/hover-min.css" rel="stylesheet">

    <!-- Animate -->
    <link href="css/animate.css" rel="stylesheet">
	
    <!-- Global -->
    <link href="css/style.css" rel="stylesheet">
    
    <!-- Custom style -->
    <style>
    .ibox-title { border-top:2px solid #0b70b5; }
    .ibox-title h5 { color:#0b70b5; }
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
                    <span class="m-r-sm text-muted welcome-message">Saisie Fournisseurs</span>
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
                <div class="col-lg-12">
                    <h2><i class="fa fa-id-card-o" aria-hidden="true"></i> Saisie des Fournisseurs</h2>
                    <ol class="breadcrumb">                      
                        <li class="active">
                            <strong>Saisie des Fournisseurs</strong>
                        </li>
                    </ol>
                </div>
            </div>

            <div class="wrapper wrapper-content">
                
                <div class="row" >
                    
                    <div class="col-lg-offset-3 col-lg-6">
                        <div class="ibox float-e-margins animated fadeInDown">
                            <div class="ibox-title">
                                <h5>Formulaire</h5>
                                <?php if( isset($_POST) && !empty($_POST) && empty($arrayInsertsNOK) ) { ?>
                                <div class="alert alert-success m-t-md">
                                    <span>
                                        <i class="fa fa-bullhorn" aria-hidden="true"></i> Les Fournisseurs ont bien été enregistrés.
                                    </span>
                                </div>
                                <?php } elseif (isset($_POST) && !empty($_POST) && !empty($arrayInsertsNOK)) { ?>
                                <div class="alert alert-danger m-t-md">
                                    <span>
                                        <i class="fa fa-bullhorn" aria-hidden="true"></i> Données enregistrées sauf pour le(s) fournisseur(s) suivant(s) :<br>
                                        <?php 
                                        $limit = count($arrayInsertsNOK) -1;
                                        $counter = 0;
                                        foreach($arrayInsertsNOK as $value) {
                                            echo $value;
                                            echo ( $counter < $limit ) ? ', ' : '.';
                                            
                                        }
                                        ?>
                                    </span>
                                </div>
                                <?php } ?>
                            </div>
                            <div class="ibox-content">
                                <div class="row">
                                    <form class="col-lg-12" role="form" action="#" method="POST">
                                        
                                        <div class="form-group">
                                                <label>Nombre de fournisseurs</label>
                                                <!-- New input types that are not supported by older web browsers, will behave as <input type="text"> -->
                                                <input type="number" class="form-control" id="nbProviders" name="nbProviders" value="" min="0" max="50" autofocus="">
                                        </div>

                                    </form>
                                    <div id="formProvidersGenerated">
                                        <div class="text-center text-danger"><span><i class="fa fa-info-circle" aria-hidden="true"></i> Merci de choisir un nombre de fournisseurs</span></div>
                                    </div>
                                </div>
                                <div class="col-lg-12">&nbsp;</div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-12 animated fadeInDown">
                    <?php
//                    echo '<h5><i class="fa fa-info-circle" aria-hidden="true"></i> Participants déjà inscrits</h5>';
//                    $counter = 0;
//                    $limit = count($arrayParticipationsAlreadyRegistered);
//                    if( $limit ) {
//                        foreach($arrayParticipationsAlreadyRegistered as $value) {
//                            $counter++;
//                            echo $value->getOneParticipant()->getCivility(). ' '.$value->getOneParticipant()->getSurname().' '.$value->getOneParticipant()->getName().' '.$value->getOneParticipant()->getEmail();
//                            echo ($counter < $limit) ? '<br>' : '';
//                        }
//                    }
//                    else { echo 'Aucun Participant enregistré'; }
                    ?>
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

    <!-- Custom script -->
    <script>
    $("#nbProviders").change(function() {
        
        $("#formProvidersGenerated").html(""); // Reset list providers
        
        var nbProviders = $("#nbProviders").val();
        
        console.log(JSON.stringify(nbProviders));

        $.post(
            './ajax/generate_providers_list.php',
            {
                nbProviders : JSON.stringify(nbProviders)
                
            },
            function(data){
                $("#formProvidersGenerated").html(data);
            },
            'text'
        );
//        console.log(selectedValues);
        return false;
    });

    </script>

</body>

</html>
