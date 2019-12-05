<?php
require_once dirname ( __FILE__ ) . '/view/errors.inc.php';
require_once dirname ( __FILE__ ) . '/services/AppServiceImpl.class.php';

if(!isset($_SESSION)) session_start(); // Start session

// Not connected as Admin ?
if(!isset($_SESSION['adminConnected']) && empty($_SESSION['adminConnected'])) {
    header('Location: ./disconnection.php'); // Redirection to Purchasing Fair list
}

header( 'content-type: text/html; charset=utf-8' ); // Specifies to the server to return UTF-8 - put in prod

date_default_timezone_set('Europe/Paris');

$appService = AppServiceImpl::getInstance();

if( isset($_POST) && !empty($_POST) ) {
        
    $guideName = ( isset( $_POST['submitButtonProviderGuide']) ) ? 'provider_guide.pdf' : 'store_guide.pdf';

    $target_dir = 'doc/guides/';
    $target_file = $target_dir . $guideName;
    $uploadOk = 1;
    $uploadReallyOk = 1;
    $msgError = '';
    $msgSuccess = '';
    $fileType = strtolower(pathinfo($target_dir . basename($_FILES['fileToUpload']['name']),PATHINFO_EXTENSION));
    
    // Allow certain file formats
    if($fileType != 'pdf') {
        $uploadOk = 0;
        $uploadReallyOk = 0;
        $msgError = 'Fichier non uploadé (seuls les PDF sont acceptés)';
    }

    // Check if $uploadOk is set to 0 by an error
    // if everything is ok, try to upload file
    if ($uploadOk && $uploadReallyOk) {
        if (move_uploaded_file($_FILES['fileToUpload']['tmp_name'], $target_file)) {
            $uploadReallyOk = 1;
            $msgSuccess = 'Le fichier '. $guideName. ' a été uploadé !';
        } else {
            $uploadReallyOk = 0;
            $msgError = 'Fichier non uploadé (problème de droits ?)';
        }
    }
}
?>

<!DOCTYPE html>
<html lang="fr">

<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="theme-color" content="#0b70b5"><!-- Mobile browser Tab Color -->

    <title>PFManagement | Gestion docs</title>
	
    <!-- Favicon -->
    <?php require_once dirname ( __FILE__ ) . '/view/favicon.inc.php'; ?>

    <!-- Bootstrap -->
    <link href="css/bootstrap.min.css" rel="stylesheet">
	
    <!-- Font Awesome -->
    <link href="font-awesome/css/font-awesome.css" rel="stylesheet">
    
    <!-- Select2 -->
    <link href="css/plugins/select2/select2.min.css" rel="stylesheet">
    
    <!-- DataTables -->
    <link href="css/plugins/dataTables/datatables.min.css" rel="stylesheet">
	
    <!-- Hover -->
    <link href="css/plugins/hover.css/hover-min.css" rel="stylesheet">
    
    <!-- Toastr style -->
    <link href="css/plugins/toastr/toastr.min.css" rel="stylesheet">

    <!-- Animate -->
    <link href="css/animate.css" rel="stylesheet">
    
    <!-- Jasny -->
    <link href="css/plugins/jasny/jasny-bootstrap.min.css" rel="stylesheet">
	
    <!-- Global -->
    <link href="css/style.css" rel="stylesheet">
    
    <!-- Custom style -->
    <style>
    /* Widget */
    .widget { color:#ffffff;border:1px solid #ffffff; }
    /* ibox */
    .ibox-title { border-top:2px solid #0b70b5; }
    .ibox-title h5 { color:#0b70b5; }
    /* Color orange E.Leclerc */
    .colorOrangeLeclerc { color:#ed8b18; }
    .colorBlueLeclerc { color:#0b70b5; }
    #submitButtonProviderGuide { background-color:#0b70b5;color:#ffffff; }
    #submitButtonProviderGuide:hover { border:1px solid #ed8b18; }
    #submitButtonStoreGuide { background-color:#0b70b5;color:#ffffff; }
    #submitButtonStoreGuide:hover { border:1px solid #ed8b18; }
    .iconPDF { color:#ff0000; }
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
                    <span class="m-r-sm text-muted welcome-message">Gestion docs</span>
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
                    <h2><i class="fa fa-file-pdf-o" aria-hidden="true"></i> Choix pièces-jointes emails</h2>
                    <ol class="breadcrumb">
                        <li class="active">
                            <strong>Administration/Gestion docs</strong>
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
                    
                    <div class="col-lg-12">
                        <div class="ibox float-e-margins">
                            <div class="ibox-title" style="border-top:1px solid #0b70b5">
                                <h5><i class="fa fa-envelope-o" aria-hidden="true"></i> Formulaire Fournisseur</h5>
                            </div>
                            <div class="ibox-content">
                                
                                <div class="alert alert-warning">
                                    <i class="fa fa-exclamation-triangle" aria-hidden="true"></i>&nbsp;
                                    Ce fichier sera envoyé en pièce-jointe pour les Fournisseurs. Le fichier actuel est le suivant : 
                                    <a href="#" onclick="window.open('./doc/guides/provider_guide.pdf');"><i class="fa fa-file-pdf-o iconPDF" aria-hidden="true"></i></a>
                                </div>
                                
                                <form action="#" method="POST" enctype="multipart/form-data">
                                    <div class="fileinput fileinput-new input-group" data-provides="fileinput">
                                        <div class="form-control" data-trigger="fileinput">
                                            <i class="glyphicon glyphicon-file fileinput-exists"></i>
                                        <span class="fileinput-filename"></span>
                                        </div>
                                        <span class="input-group-addon btn btn-default btn-file">
                                            <span class="fileinput-new">Choix fichier</span>
                                            <span class="fileinput-exists">Changer</span>
                                            <input type="file" name="fileToUpload" required/>
                                        </span>
                                        <a href="#" class="input-group-addon btn btn-default fileinput-exists" data-dismiss="fileinput">Effacer</a>
                                    </div>
                                    <button type="submit" id="submitButtonProviderGuide" name="submitButtonProviderGuide" class="btn block full-width m-t-xl m-b-md hvr-icon-spin">Enregistrer le fichier Fournisseur</button>
                                </form>                                
                                
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-lg-12">
                        <div class="ibox float-e-margins">
                            <div class="ibox-title" style="border-top:1px solid #0b70b5">
                                <h5><i class="fa fa-envelope-o" aria-hidden="true"></i> Formulaire Magasins</h5>
                            </div>
                            <div class="ibox-content">
                                
                                <div class="alert alert-warning">
                                    <i class="fa fa-exclamation-triangle" aria-hidden="true"></i>&nbsp;
                                    Ce fichier sera envoyé en pièce-jointe pour les Magasins. Le fichier actuel est le suivant : 
                                    <a href="#" onclick="window.open('./doc/guides/store_guide.pdf');"><i class="fa fa-file-pdf-o iconPDF" aria-hidden="true"></i></a>
                                </div>
                                
                                <form action="#" method="POST" enctype="multipart/form-data">
                                    <div class="fileinput fileinput-new input-group" data-provides="fileinput">
                                        <div class="form-control" data-trigger="fileinput">
                                            <i class="glyphicon glyphicon-file fileinput-exists"></i>
                                        <span class="fileinput-filename"></span>
                                        </div>
                                        <span class="input-group-addon btn btn-default btn-file">
                                            <span class="fileinput-new">Choix fichier</span>
                                            <span class="fileinput-exists">Changer</span>
                                            <input type="file" name="fileToUpload" required/>
                                        </span>
                                        <a href="#" class="input-group-addon btn btn-default fileinput-exists" data-dismiss="fileinput">Effacer</a>
                                    </div>
                                    <button type="submit" id="submitButtonStoreGuide" name="submitButtonStoreGuide" class="btn block full-width m-t-xl m-b-md hvr-icon-spin">Enregistrer le fichier Magasin</button>
                                </form>                                                      
                                
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
    
    <!-- Jasny -->
    <script src="js/plugins/jasny/jasny-bootstrap.min.js"></script>
    
    <!-- Custom script -->
    <script>
    <?php if ( isset($_POST) && !empty($_POST) && isset($uploadReallyOk) && $uploadReallyOk ) { ?>
    toastr.success('<?php echo $msgSuccess; ?>', 'Succès.');
    <?php } elseif ( isset($_POST) && !empty($_POST) && isset($uploadReallyOk) && !$uploadReallyOk ) { ?>
    toastr.error('<?php echo $msgError; ?>', 'Échec.');
    <?php } ?>
    </script>

</body>

</html>