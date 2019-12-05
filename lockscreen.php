<?php
require_once dirname ( __FILE__ ) . '/view/errors.inc.php';
require_once dirname ( __FILE__ ) . '/services/AppServiceImpl.class.php';

if(!isset($_SESSION)) session_start(); // Start session

header( 'content-type: text/html; charset=utf-8' ); // Specifies to the server to return UTF-8

$appService = AppServiceImpl::getInstance();
?>
<!DOCTYPE html>
<html lang="fr">

<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="theme-color" content="#0b70b5"><!-- Mobile browser Tab Color -->

    <title>PFManagement | Administration</title>

    <!-- Bootstrap -->
    <link href="css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link href="font-awesome/css/font-awesome.css" rel="stylesheet">
    
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
    /* https://www.alsacreations.com/astuce/lire/1216-arriere-plan-background-extensible.html | Free images HQ => https://unsplash.com/ | Pattern generator online : http://bgpatterns.com/
     * Src : https://unsplash.com/photos/npxXWgQ33ZQ
     * How to Optimize the background image (Custom Tuto)
     * Open the image with Photoshop
     * Resize the image with a width of 1920px (with constant L / H ratio)
     * 2017 CC Photoshop tab: File -> Export -> Save for Web with 80 quality
     */
    body { background:url('./img/lockscreen_background.jpg') no-repeat center fixed;-webkit-background-size: cover;/* Pour anciens Chrome et Safari */background-size:cover; }  
    /* Form in the center of the page */
    #centralForm { width:300px;background-color:#ffffff;padding:20px 20px 20px 20px;border-top:2px solid #ed8b18;box-shadow: 1px 1px 12px #555; }
    /* Replace the icon of the button */
    .hvr-icon-forward:before { content: "\f09c"; }
    /* Big icon */
    #lockIcon { color:#ed8b18; }
    /* Previous button */
    #leftButton { padding-right:1px; }
    #previousButtonAdmin { border:1px solid #ed8b18;background-color:#ffffff;color:#ed8b18; }
    /* Button for authentication */
    #rightButton { padding-left:1px; }
    #submitButtonAdmin { background-color:#ed8b18;color:#ffffff; }
    /* Input : text color and focus color */
    input:focus { border-color:#ed8b18!important; color:#ed8b18; }
    /* hack to center centralForm */
    #divForCenterXsSm { height:70px; }
    #divForCenterMdLg { height:180px; }
    /* Color orange E.Leclerc */
    .colorOrangeLeclerc { color:#ed8b18; }
    </style>

</head>

<body>
    
    <div class="row">
        <div id="divForCenterXsSm" class="col-xs-12 col-sm-12 hidden-md hidden-lg">&nbsp;</div> <!-- This div is only showed with xs & sm screens -->
        <div id="divForCenterMdLg" class="hidden-xs hidden-sm col-md-12 col-lg-12">&nbsp;</div> <!-- This div is only showed with md & lg screens -->
    </div>

    <div class="row">
        <div class="col-lg-12" text-center>

            <div id="centralForm" class="middle-box text-center lockscreen animated fadeInDown">
                    <div class="m-b-md">
                        <i id="lockIcon" class="fa fa-lock fa-5x" aria-hidden="true"></i>
                    </div>
                    <h3 class="colorOrangeLeclerc">Accès restreint</h3>
                    <p class="text-center">Vous tentez d'accéder aux fonctions d'administration. <br>Un mot de de passe est requis.</p>
                    <form id="adminForm" class="m-t" role="form" action="purchasing_fair_list.php" method="POST">
                        <div class="form-group">
                            <input id="passwordAdmin" type="password" class="form-control" placeholder="" required="" autofocus="">
                        </div>
                        <div class="row">
                            <div id="leftButton" class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
                                <button id="previousButtonAdmin" name="previousButtonAdmin" type="button" class="btn block full-width hvr-icon-back">Précédent</button>
                            </div>
                            <div id="rightButton" class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
                                <button id="submitButtonAdmin" name="submitButtonAdmin" type="submit" class="btn block full-width hvr-icon-forward">Débloquer</button>
                            </div>
                        </div>

                    </form>
            </div>

        </div>
    </div>

    <!-- Mainly scripts -->
    <script src="js/jquery-3.1.1.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
    
    <!-- Toastr script -->
    <script src="js/plugins/toastr/toastr.min.js"></script>
    
    <!-- Custom script -->
    <script>
    $("#adminForm").submit(function(e) { 
        e.preventDefault(); // To stop the form from submitting
        $.post(
            './ajax/authentication.php',
            {
                password : $("#passwordAdmin").val()
            },
            function(data) {
                if(data.trim() === 'Success') { 
                    // toastr.success('Authentification réussie..', 'Succès.');
                    window.location.href = './purchasing_fair_list.php'; // If all the validations OK
                }
                else {
                    toastr.error('Erreur d\'authentification.', 'Échec.');
                    $("#passwordAdmin").val("");
                }
            },
            'text'
        );
    });
    
    /* Click event (previousButtonAdmin) */
    $("#previousButtonAdmin").click(function(){ window.location.assign("./disconnection.php"); });
    </script>

</body>

</html>