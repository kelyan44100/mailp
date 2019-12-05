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

if(isset($_POST) && !empty($_POST)) {
    
    if(isset($_POST['submitButtonInsert'])) {
        $enterpriseContactForm = $appService->createEnterpriseContact($_POST['civility'], $_POST['surname'], $_POST['name'], $_POST['email'], $_POST['oneEnterprise']);
        $validationOK = $appService->saveEnterpriseContact($enterpriseContactForm);
    }
    elseif(isset($_POST['submitButtonUpdate'])) {
        $enterpriseContactToUpdate = $appService->findOneEnterpriseContactByEnterprise($_SESSION['enterpriseConcerned']->getIdEnterprise());
        $enterpriseContactToUpdate->setCivility($_POST['civility']);
        $enterpriseContactToUpdate->setSurname($_POST['surname']);
        $enterpriseContactToUpdate->setName($_POST['name']);
        $enterpriseContactToUpdate->setEmail($_POST['email']);
        $validationOK = $appService->saveEnterpriseContact($enterpriseContactToUpdate);
    }
    $success = ($validationOK) ? true : false;
}

$providerContact = $appService->findOneEnterpriseContactByEnterprise($_SESSION['enterpriseConcerned']->getIdEnterprise());

?>

<!DOCTYPE html>
<html lang="fr">

<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="theme-color" content="#0b70b5"><!-- Mobile browser Tab Color -->

    <title>PFManagement | Fiche contact</title>
	
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
    select, input[type="text"], input[type="email"] { border: none }
    /* https://www.w3schools.com/howto/howto_css_placeholder.asp */
    /* Chrome, Firefox, Opera, Safari 10.1+ */
    ::placeholder { 
        text-align:center;
        color:#23a689; 
        opacity:1; /* Firefox */ 
    }
    /* Internet Explorer 10-11 */
    :-ms-input-placeholder { text-align:center;color:#23a689; }
    /* Microsoft Edge */
    ::-ms-input-placeholder { text-align:center;color:#23a689; }
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
                    <span class="m-r-sm text-muted welcome-message">Vous contacter</span>
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
                    <h2><i class="fa fa-map-o" aria-hidden="true"></i> Fiche de contact dématérialisée</h2>
                    <ol class="breadcrumb">
                        <li class="active">
                            <strong>Accès Fournisseur</strong>
                        </li>
                    </ol>
                </div>
            </div>

            <div class="wrapper wrapper-content">
                
                <div class="row" >
                    
                    <div class="col-lg-offset-3 col-lg-6">
                        <div class="ibox float-e-margins animated zoomIn">
                            <div class="ibox-title" style="border-top:1px solid #0b70b5">
                                <h5>Votre fiche de contact</h5>
                                <?php if(is_null($providerContact)) { ?>
                                <div class="alert alert-danger m-t-md">
                                    <span>
                                        <i class="fa fa-bullhorn" aria-hidden="true"></i> Vous n'avez pas encore renseigné votre fiche de contact.
                                    </span>
                                </div>
                                <?php } else { ?>
                                <div class="alert alert-success m-t-md">
                                    <span>
                                        <i class="fa fa-bullhorn" aria-hidden="true"></i> Vous avez déjà renseigné votre fiche de contact, il est possible la modifier.
                                    </span>
                                </div>
                                <?php } ?>
                            </div>
                            <div class="ibox-content">
                                <?php if(is_null($providerContact)) { ?>
                                <div class="row">
                                    <form class="form-inline m-t col-lg-12" role="form" action="#" method="POST">        
                                        <div class="text-justify">
                                            Je suis
                                            <select name="civility" required="" style="color:#23a689;">
                                                <option value="">Civilité</option>
                                                <option value="Madame">Madame</option>
                                                <option value="Monsieur">Monsieur</option>
                                            </select>
                                            <input type="text" name="surname" placeholder="Nom" maxlength="50" required="" size="20" style="color:#23a689;">
                                            <input type="text"  name="name" placeholder="Prénom" maxlength="50" required="" size="20" style="color:#23a689;">,
                                            contact du <strong><?php echo $_SESSION['enterpriseConcerned']->getOneProfile()->getName().' '.$_SESSION['enterpriseConcerned']->getName(); ?></strong>.
                                            Il est possible de m'envoyer des emails à l'adresse suivante :
                                            <input type="email" name="email" placeholder="E-mail" maxlength="100" required="" size="20" style="color:#23a689;">.
                                            <br/>
                                            <div class="text-right m-t-xl m-b-xl">
                                                <em>Fait à Saint-Étienne-de-Montluc, le <?php echo date('d'); ?>/<?php echo date('m'); ?>/<?php echo date('Y'); ?></em>
                                            </div>
                                            <input type="hidden" name="oneEnterprise" value="<?php echo $_SESSION['enterpriseConcerned']->getIdEnterprise(); ?>">
                                        </div>
                                        <button type="submit" id="submitButtonInsert" name="submitButtonInsert" class="btn btn-success block full-width m-t m-b hvr-icon-wobble-horizontal">Valider la fiche de contact</button>
                                    </form>
                                </div>
                                <?php } else { 
                                $selectMadame   = ($providerContact->getCivility() == 'Madame') ? 'selected=""' : '';
                                $selectMonsieur = ($providerContact->getCivility() == 'Monsieur') ? 'selected=""' : '';
                                ?>
                                <div class="row">
                                    <form class="form-inline m-t col-lg-12" role="form" action="#" method="POST">        
                                        <div class="text-justify">
                                            Je suis
                                            <select name="civility" required="" style="color:#23a689;">
                                                <option value="">Civilité</option>
                                                <option value="Madame" <?php echo $selectMadame; ?>>Madame</option>
                                                <option value="Monsieur"<?php echo $selectMonsieur; ?>>Monsieur</option>
                                            </select>
                                            <input type="text" value="<?php echo $providerContact->getSurname(); ?>" name="surname" placeholder="Nom" maxlength="50" required="" size="20" style="color:#23a689;">
                                            <input type="text" value="<?php echo $providerContact->getName(); ?>"name="name" placeholder="Prénom" maxlength="50" required="" size="20" style="color:#23a689;">,
                                            contact du <strong><?php echo $_SESSION['enterpriseConcerned']->getOneProfile()->getName().' '.$_SESSION['enterpriseConcerned']->getName(); ?></strong>.
                                            Il est possible de m'envoyer des emails à l'adresse suivante :
                                            <input type="email" value="<?php echo $providerContact->getEmail(); ?>" name="email" placeholder="E-mail" maxlength="100" required="" size="25" style="color:#23a689;">.
                                            <br/>
                                            <div class="text-right m-t-xl m-b-xl">
                                                <em>Dernier enregistrement le <?php echo DateTime::createFromFormat('Y-m-d H:i:s', $providerContact->getRegistrationDate())->format('d/m/Y à H:i:s') ?></em>
                                            </div>
                                            <input type="hidden" name="oneEnterprise" value="<?php echo $_SESSION['enterpriseConcerned']->getIdEnterprise(); ?>">
                                        </div>
                                        <button type="submit" id="submitButtonUpdate" name="submitButtonUpdate" class="btn btn-success block full-width m-t m-b hvr-icon-wobble-horizontal">Sauvegarder les modifications</button>
                                    </form>
                                </div>
                                <?php } ?>
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
    if(isset($_POST) && !empty($_POST) && !empty($success)) { ?>
        // Display a success toast, with a title
        toastr.success('Formulaire traité.', 'Succès.');
    <?php } elseif(isset($_POST) && !empty($_POST) && empty($success)) { ?>
        toastr.error('Formulaire non traité.', 'Échec.');
    <?php } ?>
    </script>

</body>

</html>
