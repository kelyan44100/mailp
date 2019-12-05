<?php
header( 'content-type: text/html; charset=utf-8' ); // Specifies to the server to return UTF-8 - put in prod
?>
<!DOCTYPE html>
<html>

<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>PFManagement | Page d'erreur</title>

    <link href="./../css/bootstrap.min.css" rel="stylesheet">
    <link href="./../font-awesome/css/font-awesome.css" rel="stylesheet">

    <link href="./../css/animate.css" rel="stylesheet">
    <link href="./../css/style.css" rel="stylesheet">

    <style>
    /* Custom colors for elements */
    body {background-color:#0b70b5!important;color:#ffffff;}
    #homeButton {background-color:#0b70b5!important;border:1px solid #ffffff!important;}
    #homeButton:hover {background-color:#ed8b18!important;border:1px solid #ffffff!important;}
    /* Hack to center content */
    #divForCenterXsSm {height:70px;}
    #divForCenterMdLg {height:100px;}
    </style>
</head>

<body>

    <div class="row">
        <div id="divForCenterXsSm" class="col-xs-12 col-sm-12 hidden-md hidden-lg">&nbsp;</div> <!-- This div is only showed with xs & sm screens -->
        <div id="divForCenterMdLg" class="hidden-xs hidden-sm col-md-12 col-lg-12">&nbsp;</div> <!-- This div is only showed with md & lg screens -->
    </div>

    <div class="middle-box text-center animated fadeInDown">
        <h1><i class="fa fa-calendar-times-o" aria-hidden="true"></i></h1>
        <h3 class="font-bold">Erreur.</h3>
        <div class="error-desc">
            <!-- Apple example : https://www.apple.com/fr/windows -->
            Le planning général n'a pas encore été généré.
        </div>
        <button id="homeButton" class="btn btn-primary m-t-md">
            <i class="fa fa-home" aria-hidden="true"></i> Accueil
        </button>
    </div>

    <!-- Mainly scripts -->
    <script src="./../js/jquery-3.1.1.min.js"></script>
    <script src="./../js/bootstrap.min.js"></script>
    <!-- Custom script -->
    <script>
    $("#homeButton").click(function(){ window.location.assign('./../purchasing_fair_list.php'); });
    </script>

</body>

</html>