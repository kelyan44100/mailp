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

date_default_timezone_set('Europe/Paris');

// Get all Providers
$arrayProviders = $appService->findAllEnterprisesAsProviders();

if(isset($_POST) && !empty($_POST)) {
    
    $civilityAgentForm      = $_POST['civilityAgent'];
    $surnameAgentForm       = $_POST['surnameAgent'];
    $nameAgentForm          = $_POST['nameAgent'];
    $addresseLine1AgentForm = $_POST['addresseLine1Agent'];
    $addresseLine2AgentForm = $_POST['addresseLine2Agent'];
    $selectProvidersForm    = $_POST['selectProviders'];
    
    $providerFormFormatted = '';
    $counterIdsProviders = 0;
    
    foreach($selectProvidersForm as $idProvider) {
        $providerFormFormatted .= $idProvider;
        $counterIdsProviders++;
        $providerFormFormatted .= ($counterIdsProviders < count($selectProvidersForm) ) ? '|' : '';
    }
        
    $idAgentForm = -1;
    
    if(isset($_POST['submitButtonCase1'])) { $idAgentForm = 1; }
    if(isset($_POST['submitButtonCase2'])) { $idAgentForm = 2; }
    if(isset($_POST['submitButtonCase3'])) { $idAgentForm = 3; }
    if(isset($_POST['submitButtonCase4'])) { $idAgentForm = 4; }
    if(isset($_POST['submitButtonCase5'])) { $idAgentForm = 5; }

    $agentForm = $appService->findOneAgent($idAgentForm);
    $agentForm->setCivility($civilityAgentForm);
    $agentForm->setSurname($surnameAgentForm);
    $agentForm->setName($nameAgentForm);
    $agentForm->setAddressLine1($addresseLine1AgentForm);
    $agentForm->setAddressLine2($addresseLine2AgentForm);
    $agentForm->setProviders($providerFormFormatted);
    
    $appService->saveAgent($agentForm);
}

$arrayAgent = $appService->findAllAgent(); // 5 Agents - Special case. Updated 03.08.2018

?>

<!DOCTYPE html>
<html lang="fr">

<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="theme-color" content="#0b70b5"><!-- Mobile browser Tab Color -->

    <title>PFManagement | Agents</title>
	
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
    .hvr-icon-forward:before { content:'\f0c7'; }
    /* Color orange E.Leclerc */
    .colorOrangeLeclerc { color:#ed8b18; }
    .colorBlueLeclerc { color:#0b70b5; }
    /* Spinenr color */
    .sk-spinner-wave div, .sk-spinner-three-bounce div{ background-color:#0b70b5; }
    /* Update/Remove icons */
    .actionEdit, .actionDelete { cursor:pointer }
    .actionEdit { color:#0b70b5; }
    .actionDelete { color:#ed8b18; }
    /* Button to access the list of purchasing fairs */
    #submitButtonCase1, #submitButtonCase2, #submitButtonCase3, #submitButtonCase4, #submitButtonCase5 { background-color:#0b70b5;color:#ffffff; }
    #submitButtonCase1:hover, #submitButtonCase2:hover, #submitButtonCase3:hover, #submitButtonCase4:hover, #submitButtonCase5:hover { border:1px solid #ed8b18; }
    /* Select2 custom height input */
    .select2-selection.select2-selection--multiple {min-height:100px;}
    /* Download excel file*/
    #downloadExcel {cursor:pointer;}
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
                    <span class="m-r-sm text-muted welcome-message">Agents</span>
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
                    <h2><i class="fa fa-user-secret" aria-hidden="true"></i> Gestion des Agents qui représentent les Fournisseurs</h2>
                    <ol class="breadcrumb">
                        <li class="active">
                            <strong>Administration/GestionFournisseurs/Agents</strong>
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
                    <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
                        <div class="ibox float-e-margins">
                            <div class="ibox-title" style="border-top:1px solid #0b70b5">
                                <h5><i class="fa fa-user-secret" aria-hidden="true"></i> #1</h5>
                            </div>
                            <div class="ibox-content">
                                <form class="m-t" role="form" action="#" method="POST">
                                    <label>Civilité :</label>
                                    <select class="form-control" name="civilityAgent" required>
                                        <option value="">-- Choix civilité --</option>
                                        <option value="Madame" <?php echo ($arrayAgent[0]->getCivility() == 'Madame') ? 'selected' : ''; ?>>Madame</option>
                                        <option value="Monsieur" <?php echo ($arrayAgent[0]->getCivility() == 'Monsieur') ? 'selected' : ''; ?>>Monsieur</option>
                                    </select>
                                    <label>Nom de famille :</label>
                                    <input type="text" class="form-control" name="surnameAgent" maxlength="50" required value="<?php echo $arrayAgent[0]->getSurname(); ?>">
                                    <label>Prénom :</label>
                                    <input type="text" class="form-control" name="nameAgent" maxlength="50" required value="<?php echo $arrayAgent[0]->getName(); ?>">
                                    <label>Ligne Adresse 1 :</label>
                                    <input type="text" class="form-control" name="addresseLine1Agent" maxlength="100" required value="<?php echo $arrayAgent[0]->getAddressLine1(); ?>">
                                    <label>Ligne Adresse 2 :</label>
                                    <input type="text" class="form-control" name="addresseLine2Agent" maxlength="100" required value="<?php echo $arrayAgent[0]->getAddressLine2(); ?>">                                    <label>Fournisseurs associés :</label>
                                    <select id="selectProvidersCase1" name="selectProviders[]" class="form-group form-control full-width" multiple="multiple" required="" style="width:100%!important">
                                    <?php
                                    $arrayProvidersAgent = explode("|", $arrayAgent[0]->getProviders());
                                    foreach($arrayProviders as $key => $value) {
                                        $selected = ( in_array( $value->getIdEnterprise(), $arrayProvidersAgent ) ) ? 'selected' : ''; "";
                                        echo '<option value="'.$value->getIdEnterprise().'" '.$selected.'>'.$value->getName().'('.$value->getOneTypeOfProvider()->getNameTypeOfProvider()[0].')</option>';
                                    }
                                    ?>
                                    </select>
                                    <button type="submit" id="submitButtonCase1" name="submitButtonCase1" class="btn block full-width m-t-xl m-b-md hvr-icon-forward">Sauvegarder l'agent</button>
                                </form>
                            </div>
                        </div>
                    </div>
                    <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
                        <div class="ibox float-e-margins">
                            <div class="ibox-title" style="border-top:1px solid #0b70b5">
                                <h5><i class="fa fa-user-secret" aria-hidden="true"></i> #2</h5>
                            </div>
                            <div class="ibox-content">
                                <form class="m-t" role="form" action="#" method="POST">
                                    <label>Civilité :</label>
                                    <select class="form-control" name="civilityAgent" required>
                                        <option value="">-- Choix civilité --</option>
                                        <option value="Madame" <?php echo ($arrayAgent[1]->getCivility() == 'Madame') ? 'selected' : ''; ?>>Madame</option>
                                        <option value="Monsieur" <?php echo ($arrayAgent[1]->getCivility() == 'Monsieur') ? 'selected' : ''; ?>>Monsieur</option>
                                    </select>
                                    <label>Nom de famille :</label>
                                    <input type="text" class="form-control" name="surnameAgent" maxlength="50" required value="<?php echo $arrayAgent[1]->getSurname(); ?>">
                                    <label>Prénom :</label>
                                    <input type="text" class="form-control" name="nameAgent" maxlength="50" required value="<?php echo $arrayAgent[1]->getName(); ?>">
                                    <label>Ligne Adresse 1 :</label>
                                    <input type="text" class="form-control" name="addresseLine1Agent" maxlength="100" required value="<?php echo $arrayAgent[1]->getAddressLine1(); ?>">
                                    <label>Ligne Adresse 2 :</label>
                                    <input type="text" class="form-control" name="addresseLine2Agent" maxlength="100" required value="<?php echo $arrayAgent[1]->getAddressLine2(); ?>">
                                    <label>Fournisseurs associés :</label>
                                    <select id="selectProvidersCase2" name="selectProviders[]" class="form-group form-control full-width" multiple="multiple" required="" style="width:100%!important">
                                    <?php
                                   $arrayProvidersAgent = explode("|", $arrayAgent[1]->getProviders());
                                    foreach($arrayProviders as $key => $value) {
                                        $selected = ( in_array( $value->getIdEnterprise(), $arrayProvidersAgent ) ) ? 'selected' : ''; "";
                                        echo '<option value="'.$value->getIdEnterprise().'" '.$selected.'>'.$value->getName().'('.$value->getOneTypeOfProvider()->getNameTypeOfProvider()[0].')</option>';
                                    }
                                    ?>
                                    ?>
                                    </select>
                                    <button type="submit" id="submitButtonCase2" name="submitButtonCase2" class="btn block full-width m-t-xl m-b-md hvr-icon-forward">Sauvegarder l'agent</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div><!-- ./row -->  
                
                <div class="row" >
                    <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
                        <div class="ibox float-e-margins">
                            <div class="ibox-title" style="border-top:1px solid #0b70b5">
                                <h5><i class="fa fa-user-secret" aria-hidden="true"></i> #3</h5>
                            </div>
                            <div class="ibox-content">
                                <form class="m-t" role="form" action="#" method="POST">
                                    <label>Civilité :</label>
                                    <select class="form-control" name="civilityAgent" required>
                                        <option value="">-- Choix civilité --</option>
                                        <option value="Madame" <?php echo ($arrayAgent[2]->getCivility() == 'Madame') ? 'selected' : ''; ?>>Madame</option>
                                        <option value="Monsieur" <?php echo ($arrayAgent[2]->getCivility() == 'Monsieur') ? 'selected' : ''; ?>>Monsieur</option>
                                    </select>
                                    <label>Nom de famille :</label>
                                    <input type="text" class="form-control" name="surnameAgent" maxlength="50" required value="<?php echo $arrayAgent[2]->getSurname(); ?>">
                                    <label>Prénom :</label>
                                    <input type="text" class="form-control" name="nameAgent" maxlength="50" required value="<?php echo $arrayAgent[2]->getName(); ?>">
                                    <label>Ligne Adresse 1 :</label>
                                    <input type="text" class="form-control" name="addresseLine1Agent" maxlength="100" required value="<?php echo $arrayAgent[2]->getAddressLine1(); ?>">
                                    <label>Ligne Adresse 2 :</label>
                                    <input type="text" class="form-control" name="addresseLine2Agent" maxlength="100" required value="<?php echo $arrayAgent[2]->getAddressLine2(); ?>">
                                    <label>Fournisseurs associés :</label>
                                    <select id="selectProvidersCase3" name="selectProviders[]" class="form-group form-control full-width" multiple="multiple" required="" style="width:100%!important">
                                    <?php
                                   $arrayProvidersAgent = explode("|", $arrayAgent[2]->getProviders());
                                    foreach($arrayProviders as $key => $value) {
                                        $selected = ( in_array( $value->getIdEnterprise(), $arrayProvidersAgent ) ) ? 'selected' : ''; "";
                                        echo '<option value="'.$value->getIdEnterprise().'" '.$selected.'>'.$value->getName().'('.$value->getOneTypeOfProvider()->getNameTypeOfProvider()[0].')</option>';
                                    }
                                    ?>
                                    ?>
                                    </select>
                                    <button type="submit" id="submitButtonCase3" name="submitButtonCase3" class="btn block full-width m-t-xl m-b-md hvr-icon-forward">Sauvegarder l'agent</button>
                                </form>
                            </div>
                        </div>
                    </div>
                    <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
                        <div class="ibox float-e-margins">
                            <div class="ibox-title" style="border-top:1px solid #0b70b5">
                                <h5><i class="fa fa-user-secret" aria-hidden="true"></i> #4</h5>
                            </div>
                            <div class="ibox-content">
                                <form class="m-t" role="form" action="#" method="POST">
                                    <label>Civilité :</label>
                                    <select class="form-control" name="civilityAgent" required>
                                        <option value="">-- Choix civilité --</option>
                                        <option value="Madame" <?php echo ($arrayAgent[3]->getCivility() == 'Madame') ? 'selected' : ''; ?>>Madame</option>
                                        <option value="Monsieur" <?php echo ($arrayAgent[3]->getCivility() == 'Monsieur') ? 'selected' : ''; ?>>Monsieur</option>
                                    </select>
                                    <label>Nom de famille :</label>
                                    <input type="text" class="form-control" name="surnameAgent" maxlength="50" required value="<?php echo $arrayAgent[3]->getSurname(); ?>">
                                    <label>Prénom :</label>
                                    <input type="text" class="form-control" name="nameAgent" maxlength="50" required value="<?php echo $arrayAgent[3]->getName(); ?>">
                                    <label>Ligne Adresse 1 :</label>
                                    <input type="text" class="form-control" name="addresseLine1Agent" maxlength="100" required value="<?php echo $arrayAgent[3]->getAddressLine1(); ?>">
                                    <label>Ligne Adresse 2 :</label>
                                    <input type="text" class="form-control" name="addresseLine2Agent" maxlength="100" required value="<?php echo $arrayAgent[3]->getAddressLine2(); ?>">
                                    <label>Fournisseurs associés :</label>
                                    <select id="selectProvidersCase4" name="selectProviders[]" class="form-group form-control full-width" multiple="multiple" required="" style="width:100%!important">
                                    <?php
                                   $arrayProvidersAgent = explode("|", $arrayAgent[3]->getProviders());
                                    foreach($arrayProviders as $key => $value) {
                                        $selected = ( in_array( $value->getIdEnterprise(), $arrayProvidersAgent ) ) ? 'selected' : ''; "";
                                        echo '<option value="'.$value->getIdEnterprise().'" '.$selected.'>'.$value->getName().'('.$value->getOneTypeOfProvider()->getNameTypeOfProvider()[0].')</option>';
                                    }
                                    ?>
                                    ?>
                                    </select>
                                    <button type="submit" id="submitButtonCase4" name="submitButtonCase4" class="btn block full-width m-t-xl m-b-md hvr-icon-forward">Sauvegarder l'agent</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div><!-- ./row -->
                
                <div class="row" >
                    <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
                        <div class="ibox float-e-margins">
                            <div class="ibox-title" style="border-top:1px solid #0b70b5">
                                <h5><i class="fa fa-user-secret" aria-hidden="true"></i> #5</h5>
                            </div>
                            <div class="ibox-content">
                                <form class="m-t" role="form" action="#" method="POST">
                                    <label>Civilité :</label>
                                    <select class="form-control" name="civilityAgent" required>
                                        <option value="">-- Choix civilité --</option>
                                        <option value="Madame" <?php echo ($arrayAgent[4]->getCivility() == 'Madame') ? 'selected' : ''; ?>>Madame</option>
                                        <option value="Monsieur" <?php echo ($arrayAgent[4]->getCivility() == 'Monsieur') ? 'selected' : ''; ?>>Monsieur</option>
                                    </select>
                                    <label>Nom de famille :</label>
                                    <input type="text" class="form-control" name="surnameAgent" maxlength="50" required value="<?php echo $arrayAgent[4]->getSurname(); ?>">
                                    <label>Prénom :</label>
                                    <input type="text" class="form-control" name="nameAgent" maxlength="50" required value="<?php echo $arrayAgent[4]->getName(); ?>">
                                    <label>Ligne Adresse 1 :</label>
                                    <input type="text" class="form-control" name="addresseLine1Agent" maxlength="100" required value="<?php echo $arrayAgent[4]->getAddressLine1(); ?>">
                                    <label>Ligne Adresse 2 :</label>
                                    <input type="text" class="form-control" name="addresseLine2Agent" maxlength="100" required value="<?php echo $arrayAgent[4]->getAddressLine2(); ?>">
                                    <label>Fournisseurs associés :</label>
                                    <select id="selectProvidersCase5" name="selectProviders[]" class="form-group form-control full-width" multiple="multiple" required="" style="width:100%!important">
                                    <?php
                                   $arrayProvidersAgent = explode("|", $arrayAgent[4]->getProviders());
                                    foreach($arrayProviders as $key => $value) {
                                        $selected = ( in_array( $value->getIdEnterprise(), $arrayProvidersAgent ) ) ? 'selected' : ''; "";
                                        echo '<option value="'.$value->getIdEnterprise().'" '.$selected.'>'.$value->getName().'('.$value->getOneTypeOfProvider()->getNameTypeOfProvider()[0].')</option>';
                                    }
                                    ?>
                                    ?>
                                    </select>
                                    <button type="submit" id="submitButtonCase5" name="submitButtonCase5" class="btn block full-width m-t-xl m-b-md hvr-icon-forward">Sauvegarder l'agent</button>
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
    
    <!-- Select2 -->
    <script src="js/plugins/select2/select2.full.min.js"></script>
    <script src="js/plugins/select2/i18n/fr.js"></script>
    
    <!-- Toastr script -->
    <script src="js/plugins/toastr/toastr.min.js"></script>
    
    <!-- table2csv -->
    <script src="js/plugins/table2csv/table2csv.js"></script>
    
    <!-- tablz2excel -->
    <script src="js/plugins/table2excel/jquery.table2excel.js"></script>
	
    <!-- Custom script -->
    <script>
    $(document).ready(function(){
        // select2 activation
        $("#selectProvidersCase1").select2( { placeholder: "Cliquer ici pour choisir les Fournisseurs", language: "fr" });
        $("#selectProvidersCase2").select2( { placeholder: "Cliquer ici pour choisir les Fournisseurs", language: "fr" });
        $("#selectProvidersCase3").select2( { placeholder: "Cliquer ici pour choisir les Fournisseurs", language: "fr" });
        $("#selectProvidersCase4").select2( { placeholder: "Cliquer ici pour choisir les Fournisseurs", language: "fr" });
        $("#selectProvidersCase5").select2( { placeholder: "Cliquer ici pour choisir les Fournisseurs", language: "fr" });
    });
    <?php if(isset($_POST) && !empty($_POST)) { ?>
    toastr.success('L\'agent a été enregistré avec succès', 'Succès.');
    <?php } ?>
    </script>

</body>

</html>