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
    $bytesWritten = file_put_contents(dirname ( __FILE__ ) . '/errors/log_emails.txt', ''); // Emptying the log file
    header('Location: ./admin_email_errors_check.php'); // Redirection to the same page to prevent browser warning (Do you want to reload this page...)
}
?>

<!DOCTYPE html>
<html lang="fr">

<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="theme-color" content="#0b70b5"><!-- Mobile browser Tab Color -->

    <title>PFManagement | Suivi des emails</title>
	
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

    <!-- Animate -->
    <link href="css/animate.css" rel="stylesheet">
	
    <!-- Global -->
    <link href="css/style.css" rel="stylesheet">
    
    <!-- Custom style -->
    <style>
    .ibox-title h5 { color:#0b70b5; }
    /* Replace the icon of the button */
    .hvr-icon-wobble-horizontal:before { content: "\f12d"; }
    #submitButton { background-color:#0b70b5;color:#ffffff;border:1px solid #000000; }
    #submitButton:hover { background-color:#ed8b18;color:#ffffff;border:1px solid #000000; }
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
                    <span class="m-r-sm text-muted welcome-message">Suivi des emails</span>
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
                    <h2><i class="fa fa-at" aria-hidden="true"></i> Suivi des emails envoyés</h2>
                    <ol class="breadcrumb">
                        <li class="active">
                            <strong>Administration / Suivi emails</strong>
                        </li>
                    </ol>
                </div>
            </div>

            <div class="wrapper wrapper-content">
                
                <div class="row" >
                    
                    <div class="col-lg-offset-1 col-lg-10">
                        <div class="ibox float-e-margins animated zoomIn">
                            <div class="ibox-title" style="border-top:1px solid #0b70b5">
                                <h5><i class="fa fa-file-text" aria-hidden="true"></i> Contenu du fichier journal</h5>
                            </div>
                            <div class="ibox-content">
                                <div class="row">
                                    <div class="col-lg-12">
                                        
                                        <div class="alert alert-info">
                                            <i class="fa fa-info-circle" aria-hidden="true"></i> <u>Liste des Codes d'erreurs</u><br/>
                                            #ERR-01 : Erreur lors de l'envoi (email incorrect ? Vérifier dans l'onglet Fiches de contact)<br/>
                                            #ERR-02 : Fiche de contact non-créée (En créer une dans l'onglet Fiches de contact)<br/>
                                            #ERR-03 : Erreur lors de l'envoi (email incorrect ? Vérifier dans l'onglet Liste des Commerciaux)<br/>
                                            #ERR-04 : L'email du Commercial n'est pas renseigné
                                        </div>
                                    <?php
                                    $counterErrors = 0;
                                    $logFile = fopen(dirname ( __FILE__ ) . '/errors/log_emails.txt', 'r');
                                    if ($logFile) {
                                        
                                        echo '<div class="alert alert-warning">';
                                        
                                        while (($buffer = fgets($logFile)) !== false) { 
                                            $counterErrors++;
                                            echo $buffer.'<br>'; 
                                        }
                                        
                                        if(!$counterErrors) { 
                                            echo '<i class="fa fa-info-circle" aria-hidden="true"></i> Fichier vide.'; 
                                        }
                                        
                                        echo '</div>';

                                        if (!feof($logFile)) { 
                                            echo "Erreur: fgets() a échoué\n"; 
                                        }
                                        
                                        fclose($logFile);
                                        
                                        $isDisabled = (!$counterErrors) ? 'disabled="disabled"' : '';
                                    }
                                    else { $isDisabled = 'disabled="disabled"'; }
                                    ?>
                                    </div>
                                    <form class="m-t col-lg-12" role="form" action="#" method="POST">
                                        <button type="submit" id="submitButton" name="submitButton" class="btn block full-width m-t m-b hvr-icon-wobble-horizontal" <?php echo $isDisabled; ?>>Effacer le contenu du fichier</button>
                                    </form>
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

    <!-- Custom and plugin javascript -->
    <script src="js/inspinia.js"></script>
    <script src="js/plugins/pace/pace.min.js"></script>
    
    <!-- Toastr script -->
    <script src="js/plugins/toastr/toastr.min.js"></script>
	
    <!-- Custom script -->
    <script>
    </script>

</body>

</html>
