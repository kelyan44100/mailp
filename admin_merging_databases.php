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
?>

<!DOCTYPE html>
<html lang="fr">

<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="theme-color" content="#0b70b5"><!-- Mobile browser Tab Color -->

    <title>PFManagement | Bases de données</title>
	
    <!-- Favicon -->
    <?php require_once dirname ( __FILE__ ) . '/view/favicon.inc.php'; ?>

    <!-- Bootstrap -->
    <link href="css/bootstrap.min.css" rel="stylesheet">
	
    <!-- Font Awesome -->
    <link href="font-awesome/css/font-awesome.css" rel="stylesheet">
    
    <!-- Hover -->
    <link href="css/plugins/hover.css/hover-min.css" rel="stylesheet">
    
    <!-- Toastr style -->
    <link href="css/plugins/toastr/toastr.min.css" rel="stylesheet">
    
    <!-- Color picker -->
    <link href="css/plugins/colorpicker/bootstrap-colorpicker.min.css" rel="stylesheet">
	
    <!-- Daterange picker -->
    <link href="css/plugins/daterangepicker/daterangepicker.css" rel="stylesheet">
	
    <!-- Clockpicker -->
    <link href="css/plugins/clockpicker/clockpicker.css" rel="stylesheet">
	
    <!-- iCheck -->
    <link href="css/plugins/iCheck/custom.css" rel="stylesheet">

    <!-- Animate -->
    <link href="css/animate.css" rel="stylesheet">
	
    <!-- Global -->
    <link href="css/style.css" rel="stylesheet">
	
    <!-- Custom style -->
    <style>
    .mydaterangepicker { cursor: pointer; }
    .ibox-title { border-top:2px solid #0b70b5; }
    .ibox-title h5 { color:#0b70b5; }
    /* Replace the icon of the button */
    .hvr-icon-bounce:before { content: "\f135"; }
    #getJsonButton, #updateLunchAndPresentButton { color:#ed8b18;background-color:#ffffff; border-color:#ed8b18; }
    #doMergingButton, #doMergingLunchAndPresentsButton { color:#0b70b5;background-color:#ffffff; border-color:#0b70b5; }
    #updatePasswordsButton, #updatePurchasingFairButton { color:#ff0000;background-color:#ffffff; border-color:#ff0000; }
    #updatePlanningFileOVH { color:#00b050;background-color:#ffffff; border-color:#00b050; }
    #getJsonButton:hover, #updateLunchAndPresentButton:hover { color:#ffffff;background-color:#ed8b18;border:1px solid #000000; }
    #doMergingButton:hover, #doMergingLunchAndPresentsButton:hover { color:#ffffff;background-color:#0b70b5;border:1px solid #000000; }
    #updatePasswordsButton:hover, #updatePurchasingFairButton:hover { color:#ffffff;background-color:#ff0000;border:1px solid #000000; } 
    #updatePlanningFileOVH:hover { color:#ffffff;background-color:#00b050;border:1px solid #000000; }
    /* Spinner */
    .sk-spinner-wandering-cubes .sk-cube1, .sk-spinner-wandering-cubes .sk-cube2 { background-color:#0b70b5; }
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
                    <span class="m-r-sm text-muted welcome-message">Bases de données</span>
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
                <div class="col-sm-12">
                    <h2><i class="fa fa-database" aria-hidden="true"></i> Fusion des bases de données OVH <i class="fa fa-long-arrow-right" aria-hidden="true"></i> SCA OUEST</h2>
                    <ol class="breadcrumb">
                        <li>
                            <a href="./index_store.php">Accueil</a>
                        </li>
                        <li class="active">
                            <strong>Bases de données</strong>
                        </li>
                    </ol>
                </div>
            </div>

            <div class="wrapper wrapper-content">
                 <div class="row" >
                    
                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-offset-2 col-lg-8">
                        <div class="ibox float-e-margins animated fadeInDown">
                            <div class="ibox-title">
                                <h5>Formulaire - <span class="text-danger">Un mot de passe est nécessaire pour utiliser les fonctionnalités de cette page</span></h5>
                            </div>
                            <div class="ibox-content">
                                
                                <!-- Always place after ibox-content -->
                                <div class="sk-spinner sk-spinner-wandering-cubes">
                                    <div class="sk-cube1"></div>
                                    <div class="sk-cube2"></div>
                                </div>                         
                                
                                <div class="row p-sm">
                                                                        
                                    <div class="alert alert-info col-lg-12">
                                        <h4 class="text-left"><i class="fa fa-info-circle" aria-hidden="true"></i> 
                                        Cliquer sur les numéros pour + d'infos sur chaque action possible.
                                        </h4>
                                    </div>
                                    
                                    <!-- https://www.journaldunet.fr/web-tech/developpement/1202839-bootstrap-comment-aligner-une-image-responsive-au-centre/ -->
                                    <!-- https://www.w3schools.com/tags/tag_map.asp -->
                                    <!-- https://www.w3schools.com/tags/att_area_coords.asp -->
                                    <!-- https://stackoverflow.com/questions/29921696/can-i-have-an-onclick-event-on-a-imagemap-area-element -->
                                    <!-- https://github.com/davidjbradshaw/image-map-resizer -->
                                    <!--<img class="img-responsive center-block m-b-md" src="./doc/merging_info.png" alt="Info fusion" usemap="#mergingMap">-->
                                    
<!--                                    <map name="mergingMap">
                                        <area class="infoStep1" shape="circle" coords="412,284,12" alt="(1)" href="#" title="Cliquer pour + d'infos">
                                        <area class="infoStep2" shape="circle" coords="228,213,12" alt="(2)" href="#" title="Cliquer pour + d'infos">
                                        <area class="infoStep3" shape="circle" coords="300,114,12" alt="(3)" href="#" title="Cliquer pour + d'infos">
                                    </map>-->

                                    <!--<hr class="hr-line-solid"/>-->

                                    <img class="img-responsive center-block m-b-md" src="./doc/merging_info_V2.png" alt="Info fusion 2" usemap="#mergingMapBis">
                                    
                                    <map name="mergingMapBis">
                                        <area class="infoStep1Bis" shape="circle" coords="65,56,16" alt="(1)" href="#" title="Cliquer pour + d'infos">
                                        <area class="infoStep2Bis" shape="circle" coords="65,187,12" alt="(2)" href="#" title="Cliquer pour + d'infos">
                                        <area class="infoStep3Bis" shape="circle" coords="68,237,16" alt="(3+4)" href="#" title="Cliquer pour + d'infos">
                                        <area class="infoStep4Bis" shape="circle" coords="48,363,12" alt="(5)" href="#" title="Cliquer pour + d'infos">
                                    </map>     									
                                    
                                    <!--<div class="alert alert-info col-lg-12">
                                        <h4 class="text-left"><i class="fa fa-info-circle" aria-hidden="true"></i> Exporter la base de données mysql d'un serveur depuis un poste client en local :</h4>
                                        <span class="text-left">
                                        - Installer puTTY (installer le package complet grâce à l'installateur). 
                                        Lien <a href="https://www.chiark.greenend.org.uk/~sgtatham/putty/latest.html" target="_blank">ici</a><br/>
                                        - Ouvrir l'invité de commandes Windows (cmd.exe)<br/>
                                        > putty.exe -ssh [username]@[remotehost] (mot de passe requis)<br/>
                                        Dans la nouvelle interface, saisir les lignes de commandes suivantes :<br/>
                                        > mkdir mysql_backup<br/>
                                        > rm mysql_backup/*<br/>
                                        > mysqldump --opt -h [mysqlhost] -u [user] -p [database_name] > mysql_backup/$(date +"%Y%m%d").sql<br/>
                                        (mot de passe requis)<br/>
                                        > exit<br/>
                                        Dans l'invité de commandes windows, saisir la ligne de commande suivantes :<br/>
                                        > pscp username@[remotehost]:mysql_backup/* C:\wamp64\www\pf_management\scp (serveur WAMP)
                                        </span>
                                    </div>
                                    <button id="submitButton" class="btn btn-primary block full-width m-t m-b hvr-icon-bounce">
                                    Effectuer fusion
                                    </button>-->
                                    
                                    <button id="getJsonButton" class="btn btn-primary block full-width m-t m-b hvr-icon-bounce">
                                    1 - Récupérer données Saisie Fournisseurs OVH (V2)
                                    </button>
                                    
                                    <button id="doMergingButton" class="btn btn-primary block full-width m-t m-b hvr-icon-bounce">
                                    2 - Effectuer fusion SCA (V2)
                                    </button>
									
                                    <button id="updatePasswordsButton" class="btn btn-primary block full-width m-t m-b hvr-icon-bounce">
                                    3 - Màj mots de passe Fournisseurs OVH (V2) + Choix Présents (V1)
                                    </button>
                                    
                                    <button id="updatePurchasingFairButton" class="btn btn-primary block full-width m-t m-b hvr-icon-bounce">
                                    4 - Màj salons d'achats OVH (V2)
                                    </button>
                                        
                                    <?php if( isset( $_SESSION['purchasingFairConcerned'] ) ) { ?>
                                    <button id="updatePlanningFileOVH" class="btn btn-primary block full-width m-t m-b hvr-icon-bounce">
                                    5 - Export planning OVH (V2)
                                    </button>
                                    <?php } else { ?>
                                    <button class="btn btn-danger block full-width m-t m-b hvr-icon-bounce" disabled>
                                    5 - Export planning OVH (V2) - Veuillez choisir un salon d'achats
                                    </button>
                                    <?php } ?>
                                    
                                    <button id="updateLunchAndPresentButton" class="btn btn-primary block full-width m-t m-b hvr-icon-bounce">
                                    6 - Récupérer Présents + Repas OVH + Inv. Except. (V1)
                                    </button>
                                    
                                    <button id="doMergingLunchAndPresentsButton" class="btn btn-primary block full-width m-t m-b hvr-icon-bounce">
                                    7 - Effectuer fusion SCA Présents + Repas + Inv. Except. (V1)
                                    </button>
									
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
    
    
    <!-- Color picker -->
    <script src="js/plugins/colorpicker/bootstrap-colorpicker.min.js"></script>
    
    <!-- Daterange picker -->
    <script src="js/plugins/daterangepicker/moment.min.js"></script>
    <script src="js/plugins/daterangepicker/daterangepicker.js"></script>
       
    <!-- Clock picker -->
    <script src="js/plugins/clockpicker/clockpicker.js"></script>

    <!-- Custom and plugin javascript -->
    <script src="js/inspinia.js"></script>
    <script src="js/plugins/pace/pace.min.js"></script>
    
    <!-- Toastr script -->
    <script src="js/plugins/toastr/toastr.min.js"></script>
	
    <!-- iCheck -->
    <script src="js/plugins/iCheck/icheck.min.js"></script>
    
    <!-- Image map resizer -->
    <script src="js/plugins/image-map-resizer/imageMapResizer.min.js"></script>
    
    <script>
    $(function(){ 
                
        // For image map on screen resize
        $('map').imageMapResize();
		        
        // prevent default actions on area clicks
//        $('.infoStep1').on('click', function(e){
//            e.preventDefault();
//            alert('- Exporter la base de données "pf_management" OVH dans un fichier.sql.\n- Sur le serveur SCA Ouest, créer la base de données "pf_management_tmp" si elle n\'existe pas.\nSi elle existe déjà, supprimer toutes les tables qui s\'y trouvent.');
//        });
//        
//        $('.infoStep2').on('click', function(e){
//            e.preventDefault();
//            alert('- Importer les tables + les données du (1) dans la base de données "pf_management" SCA Ouest.\n- Cliquer sur le bouton situé sur cette page pour effectuer la fusion des données.');
//        });
//        
//        $('.infoStep3').on('click', function(e){
//            e.preventDefault();
//            alert('- Importer la base de données "pf_management" SCA Ouest vers OVH.');
//        });
		
        $('.infoStep1Bis').on('click', function(e){
            e.preventDefault();
            alert('- (1) Récupération des données OVH (6 tables) au format JSON et stockage dans un fichier SCA Ouest.\n- (6) Récupération des données OVH (2 tables) au format JSON et stockage dans un fichier SCA Ouest');
        });
        
        $('.infoStep2Bis').on('click', function(e){
            e.preventDefault();
            alert('- Mise à jour des données SCA Ouest à partir des données OVH stockées dans un fichier.');
        });
        
        $('.infoStep3Bis').on('click', function(e){
            e.preventDefault();
            alert('- Export des mots de passe SCA Ouest vers OVH + Fournisseurs Présents.\n- Mise à jour des salons OVH.');
        });
        
        $('.infoStep4Bis').on('click', function(e){
            e.preventDefault();
            alert('- Export du planning généré sur le serveur interne SCA Ouest vers le serveur externe OVH.');
        });
                    
//        $('#submitButton').on('click', function(){ 
//            
//            var password = prompt("Saisir le mot de passe :");
//
//            if(password === null) { toastr.error('Action annulée', 'Échec.'); }
//            else if(password !== null && password === "salon") { // Check before merging
//                
//                $('.ibox-content').toggleClass('sk-loading'); 
//
//                $.post(
//                    './ajax/merging_databases.php',
//                    {
//                    },
//                    function(data) {
//                        
//                        $('.ibox-content').toggleClass('sk-loading');
//                        
//                        if(data.trim() == '1')
//                            toastr.success('La fusion s\'est bien passée', 'Succès.');
//                        else
//                            toastr.error('La fusion ne s\'est pas bien passée', 'Échec.');
//                    },
//                    'text'
//                );
//            }
//            else { toastr.error('Vous n\'êtes pas autorisé à effectuer la fusion', 'Échec.'); }            
//        }); 
        
        // 1 - Get JSON
        $('#getJsonButton').on('click', function(){ 
            
            var password = prompt("Saisir le mot de passe :");

            if(password === null) { toastr.error('Action annulée', 'Échec.'); }
            else if(password !== null && password === "salon") { // Check before merging
                
                $('.ibox-content').toggleClass('sk-loading'); 

                $.get(
                    'http://www.scaouest.info/pf_management/ajax/getJsonOVH.php',
//                        './ajax/getJsonOVH.php', // TEST
                    {
                        password : '<?php echo 'eHgEjYPmxgUPBRFYLvFDfjaMpKjDzhBxyJYVtUNg'; ?>'
                    },
                    function(data) {
                        
                        console.log(JSON.stringify(data));
                        
                        $.post( // Response == 200 OK HTTP so we can use another $.post here
                            './ajax/updateJsonOVH.php',
                            {
                                jsonOVH : JSON.stringify(data)
                            },
                            function(data) {
                                
                                $('.ibox-content').toggleClass('sk-loading'); 

                                if(data.trim() === 'Success') {
                                    toastr.success('L\'import des données OVH s\'est bien déroulé', 'Succès.');
                                }
                                else {
                                    toastr.error('L\'import des données OVH ne s\'est pas bien déroulé', 'Erreur.');
                                }
                            },
                            'text'
                        );
                    },
                    'json'
                );               
            }
            else { toastr.error('Vous n\'êtes pas autorisé à effectuer la fusion', 'Échec.'); }            
        });
        
        // 2 - Merging
        $('#doMergingButton').on('click', function(){ 
            
            var password = prompt("Saisir le mot de passe :");

            if(password === null) { toastr.error('Action annulée', 'Échec.'); }
            else if(password !== null && password === "salon") { // Check before merging
                
                $('.ibox-content').toggleClass('sk-loading'); 

                $.post(
                    './ajax/merging_databases_V2.php',
                    {
                    },
                    function(data) {
                        $('.ibox-content').toggleClass('sk-loading'); 
                        console.log(data);
                        toastr.success('Intégration des données OVH vers SCA Ouest effectuée.', 'Succès.');
                    },
                    'text'
                );               
            }
            else { toastr.error('Vous n\'êtes pas autorisé à effectuer cette action.', 'Échec.'); }            
        });

        // 3 - Update passwords        
        $('#updatePasswordsButton').on('click', function(){ 

            var password = prompt("Saisir le mot de passe :");

            if(password === null) { toastr.error('Action annulée', 'Échec.'); }
            else if(password !== null && password === "salon") { // Check before merging

                $('.ibox-content').toggleClass('sk-loading'); 

                $.get(
                   './ajax/getJsonSCA.php', // TEST
                    {
                        password : '<?php echo 'eHgEjYPmxgUPBRFYLvFDfjaMpKjDzhBxyJYVtUNg'; ?>'
                    },
                    function(data) {

                        //console.log(data);						

                        $.get( // Response == 200 OK HTTP so we can use another $.post here
                            'http://www.scaouest.info/pf_management/ajax/updatePasswordsOVH.php',
                            {
                                password : '<?php echo 'eHgEjYPmxgUPBRFYLvFDfjaMpKjDzhBxyJYVtUNg'; ?>',
                                passwordsSCA : JSON.stringify(data)
                            },
                            function(data) {

                                $('.ibox-content').toggleClass('sk-loading'); 

                                if(data.trim() === 'Success') {
                                    toastr.success('L\'export des mots de passe s\'est bien déroulé', 'Succès.');
                                }
                                else {
                                    toastr.error('L\'export des mots de passe ne s\'est pas bien déroulé', 'Erreur.');
                                }
                            },
                            'text'
                        );
                    },
                    'json'
                );               
            }
            else { toastr.error('Vous n\'êtes pas autorisé à effectuer l\'export', 'Échec.'); }            
        });
        
        // 4 - Update purchasingFairs        
        $('#updatePurchasingFairButton').on('click', function(){ 

            var password = prompt("Saisir le mot de passe :");

            if(password === null) { toastr.error('Action annulée', 'Échec.'); }
            else if(password !== null && password === "salon") { // Check before merging

                $('.ibox-content').toggleClass('sk-loading'); 

                $.post(
                   './ajax/getJsonSCA_bis.php', // TEST
                    {
                        password : '<?php echo 'eHgEjYPmxgUPBRFYLvFDfjaMpKjDzhBxyJYVtUNg'; ?>'
                    },
                    function(data) {

                        console.log(data);						

                        $.get( // Response == 200 OK HTTP so we can use another $.post here
                            'http://www.scaouest.info/pf_management/ajax/updatePfOVH.php',
//                                './ajax/updatePfOVH.php', // TEST
                            {
                                password : '<?php echo 'eHgEjYPmxgUPBRFYLvFDfjaMpKjDzhBxyJYVtUNg'; ?>',
                                pfSCA : JSON.stringify(data)
                            },
                            function(data) {

                                $('.ibox-content').toggleClass('sk-loading'); 

                                if(data.trim() === 'Success') {
                                    toastr.success('L\'export des salons d\'achats s\'est bien déroulé', 'Succès.');
                                }
                                else {
                                    toastr.error('L\'export des salons d\'achats ne s\'est pas bien déroulé', 'Erreur.');
                                }
                            },
                            'text'
                        );
                    },
                    'json'
                );               
            }
            else { toastr.error('Vous n\'êtes pas autorisé à effectuer l\'export', 'Échec.'); }            
        });
        
        <?php if( isset( $_SESSION['purchasingFairConcerned'] ) ) { ?>
        // 5 - Update planning file OVH
        $('#updatePlanningFileOVH').on('click', function(){ 

            var password = prompt("Saisir le mot de passe :");

            if(password === null) { toastr.error('Action annulée', 'Échec.'); }
            else if(password !== null && password === "salon") { // Check before merging

                $('.ibox-content').toggleClass('sk-loading'); 

                $.post(
                   './tmp/upload_tmp_planning.php', // TEST
                    {
                        idPurchasingFair : <?php echo $_SESSION['purchasingFairConcerned']->getIdPurchasingFair(); ?>,
                        password : '<?php echo 'eHgEjYPmxgUPBRFYLvFDfjaMpKjDzhBxyJYVtUNg'; ?>'
                    },
                    function(data) {
						
                        $.get( // Response == 200 OK HTTP so we can use another $.post here
//                             './tmp/upload_tmp_planning_bis.php',
                               'http://www.scaouest.info/pf_management/tmp/upload_tmp_planning_bis.php',
                            {
                                password : '<?php echo 'eHgEjYPmxgUPBRFYLvFDfjaMpKjDzhBxyJYVtUNg'; ?>',
                                idPurchasingFair : <?php echo $_SESSION['purchasingFairConcerned']->getIdPurchasingFair(); ?>,
                                planningContent : data
                            },
                            function(data) {

                                $('.ibox-content').toggleClass('sk-loading'); 

                                if(data.trim() === 'Success') {
                                    toastr.success('L\'export du planning pour les Fournisseurs s\'est bien déroulé', 'Succès.');
                                }
                                else {
                                    toastr.error('L\'export du planning pour les Fournisseurs ne s\'est pas bien déroulé', 'Erreur.');
                                }
                            },
                            'text'
                        );
                    },
                    'html'
                );               
            }
            else { toastr.error('Vous n\'êtes pas autorisé à effectuer l\'export', 'Échec.'); }            
        });
        <?php } ?>

        // 6 - Get JSON
        $('#updateLunchAndPresentButton').on('click', function(){ 
            
            var password = prompt("Saisir le mot de passe :");

            if(password === null) { toastr.error('Action annulée', 'Échec.'); }
            else if(password !== null && password === "salon") { // Check before merging
                
                $('.ibox-content').toggleClass('sk-loading'); 

                $.get(
                    'http://www.scaouest.info/pf_management/ajax/getJsonOVH_bis.php',
//                        './ajax/getJsonOVH_bis.php', // TEST
                    {
                        password : '<?php echo 'eHgEjYPmxgUPBRFYLvFDfjaMpKjDzhBxyJYVtUNg'; ?>'
                    },
                    function(data) {
                        console.log(this.responseText);
                        console.log(JSON.stringify(data));
                        
                        $.post( // Response == 200 OK HTTP so we can use another $.post here
                            './ajax/updateJsonOVH_bis.php',
                            {
                                jsonOVH : JSON.stringify(data)
                            },
                            function(data) {
                                
                                $('.ibox-content').toggleClass('sk-loading'); 

                                if(data.trim() === 'Success') {
                                    toastr.success('L\'import des données OVH s\'est bien déroulé', 'Succès.');
                                }
                                else {
                                    toastr.error('L\'import des données OVH ne s\'est pas bien déroulé', 'Erreur.');
                                }
                            },
                            'text'
                        );
                    },
                    'json'
                );               
            }
            else { toastr.error('Vous n\'êtes pas autorisé à effectuer la fusion', 'Échec.'); }            
        });
        
        // 7 - Merging Lunches and Presents
        $('#doMergingLunchAndPresentsButton').on('click', function(){ 
            
            var password = prompt("Saisir le mot de passe :");

            if(password === null) { toastr.error('Action annulée', 'Échec.'); }
            else if(password !== null && password === "salon") { // Check before merging
                
                $('.ibox-content').toggleClass('sk-loading'); 

                $.post(
                    './ajax/merging_databases_V2_bis.php',
                    {
                    },
                    function(data) {
                        $('.ibox-content').toggleClass('sk-loading'); 
                        console.log(data);
                        toastr.success('Intégration des données OVH vers SCA Ouest effectuée.', 'Succès.');
                    },
                    'text'
                );               
            }
            else { toastr.error('Vous n\'êtes pas autorisé à effectuer cette action.', 'Échec.'); }            
        });

    });
    </script>

</body>

</html>