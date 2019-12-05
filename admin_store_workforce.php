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
    
//    $arrayStores        = $_POST; // Get all data send
    $arrayTotStores = (int) $_POST['totStores'];
    $arraySWUpdated = array(); // For a big update of StoreWorkforce    
    
    for( $s = 0 ; $s < $arrayTotStores; $s++ ) {
        $idStorePost = (int) $_POST['storeNum_'.$s];
        $outerSWPost = (int) $_POST['storeOuter_'.$idStorePost];
        $underSWPost = (int) $_POST['storeUnder_'.$idStorePost];
        $shoesSWPost = (int) $_POST['storeShoes_'.$idStorePost];
        
        $newSW = $appService->createStoreWorkforce(
                $appService->findOneEnterprise($idStorePost),
                $outerSWPost,
                $underSWPost,
                $shoesSWPost
                );
        
        $arraySWUpdated[] = $newSW;
    }
    
    // Big update
    foreach($arraySWUpdated as $sw) { $appService->updateStoreWorkforce($sw); }
    
    // Success
    $successInsert = 1;
}
?>

<!DOCTYPE html>
<html lang="fr">

<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="theme-color" content="#0b70b5"><!-- Mobile browser Tab Color -->

    <title>PFManagement | Effectifs Magasins</title>
	
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
                    <span class="m-r-sm text-muted welcome-message">Effectifs Magasins</span>
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
                    <h2><i class="fa fa-users" aria-hidden="true"></i> Effectifs des Magasins pour les salons régionaux</h2>
                    <ol class="breadcrumb">
                        <li class="active">
                            <strong>Effectifs Magasins</strong>
                        </li>
                    </ol>
                </div>
            </div>

            <div class="wrapper wrapper-content">
                
                <div class="row" >
                    
                    <div class="col-lg-offset-1 col-lg-10">
                        <div class="ibox float-e-margins animated slideInRight">
                            <div class="ibox-title" style="border-top:1px solid #0b70b5">
                                <h5>Formulaire</h5>
                                <div class="alert alert-danger m-t-md">
                                    <span>
                                        <i class="fa fa-bullhorn" aria-hidden="true"></i> Ce formulaire permet de vérifier les effectifs pour chaque Magasin.
                                    </span>
                                </div>
                            </div>
                            <div class="ibox-content">
                                <div class="row">
                                    <form class="m-t col-lg-12" role="form" action="#" method="POST">        
                                        <table class="table table-bordered table-hover">
                                            <thead>
                                                <tr>
                                                    <th class="text-center"><i class="fa fa-info-circle" aria-hidden="true"></i> Magasin</th>
                                                    <th class="text-center"><i class="fa fa-tag" aria-hidden="true"></i> Vêtement de dessus</th>
                                                    <th class="text-center"><i class="fa fa-tag" aria-hidden="true"></i> Vêtement de dessous</th>
                                                    <th class="text-center"><i class="fa fa-tag" aria-hidden="true"></i> Chaussure</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                            <?php 
                                            $arrayEnterprises = $appService->findAllEnterprisesAsStores();
                                            $counterStore = 0;
                                            
                                            foreach($arrayEnterprises as $key => $store)  {
                                                
                                                $rowContent  = '<input type="hidden" id="totStores'.$counterStore.'" name="storeNum_'.$counterStore.'" value="'.$store->getIdEnterprise().'">';
                                                
                                                $sw = $appService->findStoreWorkforceForOneEnterprise($store->getIdEnterprise());
                                                if(empty($sw)) {
                                                    $sw = $appService->createStoreWorkforce($store,0,0,0);
                                                    $appService->saveStoreWorkforce($sw);
                                                    $sw = $appService->findStoreWorkforceForOneEnterprise($store->getIdEnterprise());
                                                }
                                                
                                                $rowContent .= '<input type="hidden" id="totStores" name="totStores" value="'.count($arrayEnterprises).'">';
                                                $rowContent .= '<tr>';
                                                $rowContent .= '<td class="text-center" style="vertical-align:middle">'.$store->getName().'</td>';
                                                $rowContent .= '<td class="text-center" style="vertical-align:middle">';
                                                $rowContent .= '<div class="form-group" style="margin-bottom:0px!important">';
                                                $rowContent .= '<input type="number" class="form-control" id="storeOuter_'.$store->getIdEnterprise().'" name="storeOuter_'.$store->getIdEnterprise().'" value="'.$sw->getOuterClothing().'" min="0" max="255">';
                                                $rowContent .= '</div>';
                                                $rowContent .= '</td>';
                                                $rowContent .= '<td class="text-center" style="vertical-align:middle">';
                                                $rowContent .= '<div class="form-group" style="margin-bottom:0px!important">';
                                                $rowContent .= '<input type="number" class="form-control" id="storeUnder_'.$store->getIdEnterprise().'" name="storeUnder_'.$store->getIdEnterprise().'" value="'.$sw->getUnderClothing().'" min="0" max="255">';
                                                $rowContent .= '</div>';
                                                $rowContent .= '</td>';
                                                $rowContent .= '<td class="text-center" style="vertical-align:middle">';
                                                $rowContent .= '<div class="form-group" style="margin-bottom:0px!important">';
                                                $rowContent .= '<input type="number" class="form-control" id="storeShoes_'.$store->getIdEnterprise().'" name="storeShoes_'.$store->getIdEnterprise().'" value="'.$sw->getShoes().'" min="0" max="255">';
                                                $rowContent .= '</div>';
                                                $rowContent .= '</td>';
                                                $rowContent .= '</tr>';
                                                echo $rowContent;
                                                
                                                ++$counterStore;
                                            }
                                            ?>
                                            </tbody>
                                        </table>
                                        <button type="submit" id="submitButton" class="btn block full-width m-t m-b hvr-icon-wobble-horizontal">Valider les efectifs</button>

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
    // Success OR Error message
    <?php 
    if(isset($_POST) && !empty($_POST) && !empty($successInsert)) { ?>
        // Display a success toast, with a title
        toastr.success('Effectifs correctement enregistrés.', 'Succès.');
    <?php } elseif(isset($_POST) && !empty($_POST) && empty($successInsert)) { ?>
        toastr.error('Effectifs non enregistrés.', 'Échec.');
    <?php } ?>
    </script>

</body>

</html>
