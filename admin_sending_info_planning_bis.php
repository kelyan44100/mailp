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

// Added 27.08.2018 - Taking others Pf into account
$isOtherPf = ( $_SESSION['purchasingFairConcerned']->getOneTypeOfPf()->getNameTypeOfPf() === 'Autre' ) ? true : false;

if(isset($_POST) && !empty($_POST) && isset( $_SESSION['purchasingFairConcerned'] ) && !empty( $_SESSION['purchasingFairConcerned'] )) {
    
    // Get content from templates files - Added 15.06.2018
    $contentEmailProviderFromFileBody = "";
    $contentEmailProviderFromFileAltBody = "";
    $emailTemplateProviderFile = fopen(dirname ( __FILE__ ) . '/templates_emails/template_email_planning_provider.txt', 'r');
    if ($emailTemplateProviderFile) {
        while (($buffer = fgets($emailTemplateProviderFile)) !== false) { 
            $contentEmailProviderFromFileBody .= nl2br($buffer);
            $contentEmailProviderFromFileAltBody .= $buffer;
        }
        fclose($emailTemplateProviderFile);
    }
    
    $contentEmailStoreFromFileBody = "";
    $contentEmailStoreFromFileAltBody = "";
    $emailTemplateStoreFile = fopen(dirname ( __FILE__ ) . '/templates_emails/template_email_planning_store.txt', 'r');
    if ($emailTemplateStoreFile) {
        while (($buffer = fgets($emailTemplateStoreFile)) !== false) { 
            $contentEmailStoreFromFileBody .= nl2br($buffer);
            $contentEmailStoreFromFileAltBody .= $buffer;
        }
        fclose($emailTemplateStoreFile);
    }  
    
    
    // Mails for Providers Present
    if(isset($_POST['msgProvider']) && !empty($_POST['msgProvider'])){

        // Providers Present
        $arrayPP      = $appService->findAllProviderPresentForOnePurchasingFair($_SESSION['purchasingFairConcerned']->getIdPurchasingFair());
        $totProviders = count($arrayPP);
        //print_r($totProviders);

        // Total mails to send
        $counterMailsToSend = $totProviders;

        // Check sending
        $counterSuccesfulSendingsProvider = 0;

        for( $i = 0 ; $i < $totProviders ; $i++) {
            //Ligne suivante à commenter peut etre pour ne pas réinitialiser les mots de passe à l'envoi du mail
			$arrayPP[$i]->getOneProvider()->setPassword($arrayPasswordsPP[$i]);
            $appService->saveEnterprise($arrayPP[$i]->getOneProvider());
                        

            $enterpriseContact = $appService->findOneEnterpriseContactByEnterprise($arrayPP[$i]->getOneProvider()->getIdEnterprise());

            if( !empty($enterpriseContact)) {

                $recipientAddress = $enterpriseContact->getEmail(); // 'test@scaouest.fr'
                $recipientName    = $enterpriseContact->getCivility().' '.$enterpriseContact->getSurname().' '.$enterpriseContact->getName();

                $recipient = array( 'recipientAddress' => $recipientAddress, 'recipientName' => $recipientName);

                $attachments = ($isOtherPf) ? array() : array('./doc/guides/provider_guide.pdf');

                $subject = 'Information importante - '.$_SESSION['purchasingFairConcerned']->getNamePurchasingFair();

                $altBody = $contentEmailProviderFromFileAltBody . "<br><br>" ." Cet email est destiné à ".$enterpriseContact->getCivility()." ".$enterpriseContact->getSurname()." ".$enterpriseContact->getName()." du ".$enterpriseContact->getOneEnterprise()->getOneProfile()->getName()." ".$enterpriseContact->getOneEnterprise()->getName().".";

                $body = $contentEmailProviderFromFileBody . "<br><br>" ." Cet email est destiné à ".$enterpriseContact->getCivility()." ".$enterpriseContact->getSurname()." ".$enterpriseContact->getName()." du ".$enterpriseContact->getOneEnterprise()->getOneProfile()->getName()." ".$enterpriseContact->getOneEnterprise()->getName().".";

                //print_r($body);

                $successfulSending = $appService->sendMail(new MyEmail($recipient, $attachments, $subject, $body, $altBody));

                if($successfulSending) {
                    $counterSuccesfulSendingsProvider++;
                    // http://php.net/manual/fr/function.file-put-contents.php
                    file_put_contents('./errors/log_emails.txt', '['.date('d-M-Y H:i:s e').'] EMAIL ENVOYÉ : '.$enterpriseContact->getOneEnterprise()->getOneProfile()->getName().' '.$enterpriseContact->getOneEnterprise()->getName()."\n", FILE_APPEND);

                }else {
                    file_put_contents('./errors/log_emails.txt', '['.date('d-M-Y H:i:s e').'] EMAIL NON ENVOYÉ #ERR-01 : '.$enterpriseContact->getOneEnterprise()->getOneProfile()->getName().' '.$enterpriseContact->getOneEnterprise()->getName()."\n", FILE_APPEND);
                }
            }else {
                file_put_contents('./errors/log_emails.txt', '['.date('d-M-Y H:i:s e').'] EMAIL NON ENVOYÉ #ERR-02 : '.$arrayPP[$i]->getOneProvider()->getOneProfile()->getName().' '.$arrayPP[$i]->getOneProvider()->getName()."\n", FILE_APPEND);
            }
        }

        $_SESSION['counterSuccesfulSendingsProvider'] = $counterSuccesfulSendingsProvider;
        //print_r($_SESSION['counterSuccesfulSendingsProvider']);

    }
    

    // Mails for Stores
    if(isset($_POST['msgStore']) && !empty($_POST['msgStore'])){

        // Stores
        $arrayStores = $appService->findAllEnterprisesAsStores();
        $totStores   = count($arrayStores);
            
        // Total mails to send
        $counterMailsToSend = $totStores;

        print_r($totStores);
        
        // Check sending
        $counterSuccesfulSendingsStore = 0;

        for( $j = 0 ; $j < $totStores ; $j++) {

            $enterpriseContact = $appService->findOneEnterpriseContactByEnterprise($arrayStores[$j]->getIdEnterprise());

            if( !empty($enterpriseContact)) {

                $recipientAddress = $enterpriseContact->getEmail(); // 'test@scaouest.fr'
                $recipientName    = $enterpriseContact->getCivility().' '.$enterpriseContact->getSurname().' '.$enterpriseContact->getName();

                $recipient = array( 'recipientAddress' => $recipientAddress, 'recipientName' => $recipientName);

                $attachments = ($isOtherPf) ? array() : array('./doc/guides/store_guide.pdf');

                $subject = 'Information importante - '.$_SESSION['purchasingFairConcerned']->getNamePurchasingFair();

                $altBody = $contentEmailProviderFromFileAltBody . "<br><br>" ." Cet email est destiné à ".$enterpriseContact->getCivility()." ".$enterpriseContact->getSurname()." ".$enterpriseContact->getName()." du ".$enterpriseContact->getOneEnterprise()->getOneProfile()->getName()." ".$enterpriseContact->getOneEnterprise()->getName().".";

                $body = $contentEmailProviderFromFileBody . "<br><br>" ." Cet email est destiné à ".$enterpriseContact->getCivility()." ".$enterpriseContact->getSurname()." ".$enterpriseContact->getName()." du ".$enterpriseContact->getOneEnterprise()->getOneProfile()->getName()." ".$enterpriseContact->getOneEnterprise()->getName().".";

                $successfulSending = $appService->sendMail(new MyEmail($recipient, $attachments, $subject, $body, $altBody));

                if($successfulSending) { 
                    $counterSuccesfulSendingsStore++;
                    // http://php.net/manual/fr/function.file-put-contents.php
                    file_put_contents('./errors/log_emails.txt', '['.date('d-M-Y H:i:s e').'] EMAIL ENVOYÉ : '.$enterpriseContact->getOneEnterprise()->getOneProfile()->getName().' '.$enterpriseContact->getOneEnterprise()->getName()."\n", FILE_APPEND);
                    
                    // Save data in CSV file - Added 30/05/2018
                    $csvData = $arrayStores[$j]->getIdEnterprise().';'.date('Y-m-d H:i:s');
                    file_put_contents('./tmp/tmp_check_emails_pf_store_info_planning'.$_SESSION['purchasingFairConcerned']->getIdPurchasingFair().'.csv', $csvData."\n", FILE_APPEND);                
                }
                else {
                    file_put_contents('./errors/log_emails.txt', '['.date('d-M-Y H:i:s e').'] EMAIL NON ENVOYÉ #ERR-01 : '.$enterpriseContact->getOneEnterprise()->getOneProfile()->getName().' '.$enterpriseContact->getOneEnterprise()->getName()."\n", FILE_APPEND);
                }
            }
            else {
                file_put_contents('./errors/log_emails.txt', '['.date('d-M-Y H:i:s e').'] EMAIL NON ENVOYÉ #ERR-02 : '.$arrayStores[$j]->getOneProfile()->getName().' '.$arrayStores[$j]->getName()."\n", FILE_APPEND);
            }
        }

        $_SESSION['counterSuccesfulSendingsStore'] = $counterSuccesfulSendingsStore;
        print_r($counterSuccesfulSendingsStore);

    }
    
}
?>

<!DOCTYPE html>
<html lang="fr">

<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="theme-color" content="#0b70b5"><!-- Mobile browser Tab Color -->

    <title>PFManagement | Invitations</title>
	
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
    .hvr-icon-forward:before { content:'\f1d8'; }
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
    #submitButtonProviders { background-color:#0b70b5;color:#ffffff; }
    #submitButtonProviders:hover { border:1px solid #ed8b18; }

    #submitButtonStore { background-color:#0b70b5;color:#ffffff; }
    #submitButtonStore:hover { border:1px solid #ed8b18; }
    /* Select2 custom height input */
    .select2-selection.select2-selection--multiple {min-height:100px;}
    /* Spinner */
    .ibox-content.sk-loading > .sk-spinner { top: 35%!important; }
    .sk-spinner-circle.sk-spinner { width:40px!important;height:40px!important; }
    .sk-spinner-circle .sk-circle:before { background-color:#0b70b5; }
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
                    <span class="m-r-sm text-muted welcome-message">Info Planning 1</span>
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
                    <h2><i class="fa fa-flag-o" aria-hidden="true"></i> Informer les Fournisseurs présents + Magasins</h2>
                    <ol class="breadcrumb">
                        <li class="active">
                            <strong>Administration/Info Planning 1</strong>
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

                    <!--Mails for Providers Present-->
                    <div class="col-lg-12">
                        <div class="ibox float-e-margins">
                            <div class="ibox-title" style="border-top:1px solid #0b70b5">
                                <h5><i class="fa fa-envelope-o" aria-hidden="true"></i> Formulaire d'envoi Fournisseurs</h5>
                                <div class="ibox-tools">
                                    <a>
                                        <i class="fa fa-chevron-up collapse-link"></i>
                                    </a>
                                </div>
                            </div>
                            <div class="ibox-content">

                                <!-- Always place after ibox-content -->
                                <div class="sk-spinner sk-spinner-circle">
                                    <div class="sk-circle1 sk-circle"></div>
                                    <div class="sk-circle2 sk-circle"></div>
                                    <div class="sk-circle3 sk-circle"></div>
                                    <div class="sk-circle4 sk-circle"></div>
                                    <div class="sk-circle5 sk-circle"></div>
                                    <div class="sk-circle6 sk-circle"></div>
                                    <div class="sk-circle7 sk-circle"></div>
                                    <div class="sk-circle8 sk-circle"></div>
                                    <div class="sk-circle9 sk-circle"></div>
                                    <div class="sk-circle10 sk-circle"></div>
                                    <div class="sk-circle11 sk-circle"></div>
                                    <div class="sk-circle12 sk-circle"></div>
                                </div>

                                <div class="alert alert-danger">
                                    <i class="fa fa-exclamation-triangle" aria-hidden="true"></i>&nbsp;
                                    Cet email a pour but d'informer les Fournisseurs présents au salon d'achats qu'ils peuvent dès à présent consulter leur planning respectif.
                                </div>

                                <div class="form-group">

                                    <form role="form" action="#" method="POST"> 

                                        <label for="comment">Comment:</label>
                                        <textarea name="msgProvider" class="form-control" rows="20" id="textareaEmailProvider" style="resize:none"><?php
                                        $counterErrors = 0;
                                        $result = "";
                                        $logFile = fopen(dirname ( __FILE__ ) . '/templates_emails/template_email_planning_provider.txt', 'r');
                                        if ($logFile) {

                                            while (($buffer = fgets($logFile)) !== false) { 
                                                $counterErrors++;
                                                $result .= $buffer; 
                                            }

                                            if(!$counterErrors) { 
                                                $result = 'Fichier vide.'; 
                                            }

                                            if (!feof($logFile)) { 
                                                $result = "Erreur: fgets() a échoué\n"; 
                                            }

                                            fclose($logFile);

                                            echo $result;
                                        }
                                        ?>
                                        </textarea>
                                        <button type="button" id="updateButtonEmailProviders" name="updateButtonEmailProviders" class="btn btn-success block full-width m-t-xl m-b-md hvr-icon-spin" onclick="updateEmailEnterprise(1);">Mettre à jour email</button>

                                        <!--------------------------------------------------------------------------------->
                                        
                                        <?php if( isset($_SESSION['purchasingFairConcerned']) 
                                                && !empty($_SESSION['purchasingFairConcerned']) 
                                                && !file_exists('./tmp/tmp_check_emails_pf_provider_info_planning'.$_SESSION['purchasingFairConcerned']->getIdPurchasingFair().'.csv') ) { ?>
                                        <button type="submit" id="submitButtonProviders" name="submitButtonProviders" class="btn block full-width m-t-xl m-b-md hvr-icon-forward">Informer les Fournisseurs</button>
                                        <?php } ?>
                                        <!--<?php if( isset($_SESSION['purchasingFairConcerned']) 
                                                && !empty($_SESSION['purchasingFairConcerned']) 
                                                && file_exists('./tmp/tmp_check_emails_pf_provider_info_planning'.$_SESSION['purchasingFairConcerned']->getIdPurchasingFair().'.csv') ) { ?>
                                        <button type="button" class="btn block full-width m-t-xl m-b-md" disabled><i class="fa fa-exclamation-circle" aria-hidden="true"></i> Emails d'informations déjà envoyés</button>
                                        <?php } ?>-->
                                        <?php if( !isset($_SESSION['purchasingFairConcerned']) 
                                                && empty($_SESSION['purchasingFairConcerned']) ) { ?>
                                        <button type="button" class="btn block full-width m-t-xl m-b-md" disabled><i class="fa fa-exclamation-circle" aria-hidden="true"></i> Veuillez choisir un salon d'achats</button>
                                        <?php } ?>
                                        
                                    </form>

                                </div>
                            </div>

                        </div>
                    </div>

                    <!--Mails for Store-->
                    <div class="col-lg-12">
                        <div class="ibox float-e-fournisseur">
                            <div class="ibox-title" style="border-top:1px solid #0b70b5">
                                <h5><i class="fa fa-envelope-o" aria-hidden="true"></i> Formulaire d'envoi Magasin</h5>
                                <div class="ibox-tools">
                                    <a>
                                        <i class="fa fa-chevron-up collapse-link"></i>
                                    </a>
                                </div>
                            </div>
                            <div class="ibox-content">

                                <!-- Always place after ibox-content -->
                                <div class="sk-spinner sk-spinner-circle">
                                    <div class="sk-circle1 sk-circle"></div>
                                    <div class="sk-circle2 sk-circle"></div>
                                    <div class="sk-circle3 sk-circle"></div>
                                    <div class="sk-circle4 sk-circle"></div>
                                    <div class="sk-circle5 sk-circle"></div>
                                    <div class="sk-circle6 sk-circle"></div>
                                    <div class="sk-circle7 sk-circle"></div>
                                    <div class="sk-circle8 sk-circle"></div>
                                    <div class="sk-circle9 sk-circle"></div>
                                    <div class="sk-circle10 sk-circle"></div>
                                    <div class="sk-circle11 sk-circle"></div>
                                    <div class="sk-circle12 sk-circle"></div>
                                </div>

                                <div class="alert alert-danger">
                                    <i class="fa fa-exclamation-triangle" aria-hidden="true"></i>&nbsp;
                                    Cet email a pour but d'informer les Magasins présents au salon d'achats qu'ils peuvent dès à présent consulter leur planning respectif.
                                </div>

                                <div class="form-group">

                                    <form role="form" action="#" method="POST" id="formStore"> 

                                        <label for="comment">Comment:</label>
                                        <textarea name="msgStore" class="form-control" rows="20" id="textareaEmailStore" style="resize:none"><?php
                                        $counterErrors = 0;
                                        $result = "";
                                        $logFile = fopen(dirname ( __FILE__ ) . '/templates_emails/template_email_planning_store.txt', 'r');
                                        if ($logFile) {

                                            while (($buffer = fgets($logFile)) !== false) { 
                                                $counterErrors++;
                                                $result .= $buffer; 
                                            }

                                            if(!$counterErrors) { 
                                                $result = 'Fichier vide.'; 
                                            }

                                            if (!feof($logFile)) { 
                                                $result = "Erreur: fgets() a échoué\n"; 
                                            }

                                            fclose($logFile);

                                            echo $result;
                                        }
                                        ?>
                                        </textarea>
                                        <button type="button" id="updateButtonEmailStores" name="updateButtonEmailStores" class="btn btn-success block full-width m-t-xl m-b-md hvr-icon-spin" onclick="updateEmailEnterprise(2);">Mettre à jour email</button>

                                        <!--------------------------------------------------------------------------------->
                                        
                                        <?php if( isset($_SESSION['purchasingFairConcerned']) 
                                                && !empty($_SESSION['purchasingFairConcerned']) 
                                                && !file_exists('./tmp/tmp_check_emails_pf_store_info_planning'.$_SESSION['purchasingFairConcerned']->getIdPurchasingFair().'.csv') ) { ?>
                                        <button type="submit" id="submitButtonStore" name="submitButtonStore" class="btn block full-width m-t-xl m-b-md hvr-icon-forward">Informer les Magasins</button>
                                        <?php } ?>
                                        <!--<?php if( isset($_SESSION['purchasingFairConcerned']) 
                                                && !empty($_SESSION['purchasingFairConcerned']) 
                                                && file_exists('./tmp/tmp_check_emails_pf_store_info_planning'.$_SESSION['purchasingFairConcerned']->getIdPurchasingFair().'.csv') ) { ?>
                                        <button type="button" class="btn block full-width m-t-xl m-b-md" disabled><i class="fa fa-exclamation-circle" aria-hidden="true"></i> Emails d'informations déjà envoyés</button>
                                        <?php } ?>-->
                                        <?php if( !isset($_SESSION['purchasingFairConcerned']) 
                                                && empty($_SESSION['purchasingFairConcerned']) ) { ?>
                                        <button type="button" class="btn block full-width m-t-xl m-b-md" disabled><i class="fa fa-exclamation-circle" aria-hidden="true"></i> Veuillez choisir un salon d'achats</button>
                                        <?php } ?>
                                        
                                    </form>

                                </div>
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
    
    <!-- table2csv -->
    <script src="js/plugins/table2csv/table2csv.js"></script>
    
    <!-- table2excel -->
    <script src="js/plugins/table2excel/jquery.table2excel.js"></script>
    
    <!-- Custom script -->
    <script>
    function updateEmailEnterprise(profileEnterprise) {
         
        var enterprise = '';
        var emailContent = '';
         
        // Provider
        if(profileEnterprise === 1) { enterprise = 'provider'; emailContent = $('#textareaEmailProvider').val(); }

        // Store
        if(profileEnterprise === 2) { enterprise = 'store'; emailContent = $('#textareaEmailStore').val(); }

        
        $.post(
            './ajax/updateEmailTemplatePlanning.php',
            {
                profileEnterprise : enterprise,
                emailContent : emailContent
            },
            function(data) {
                if(data.trim() === 'Success') { 
                    toastr.success('L\'email a été mis à jour.', 'Succès.');
                }
            },
            'text'
        );    
    }

    <?php if(isset($_POST['msgProvider']) && !empty($_POST['msgProvider']) &&  $_SESSION['counterSuccesfulSendingsProvider'] == $counterMailsToSend) { ?>
        console.log(<?php $counterMailsToSend ?>);
        toastr.success('Tous les emails ont été envoyés.', 'Succès.');
    <?php } elseif(isset($_POST['msgProvider']) && !empty($_POST['msgProvider']) &&  $_SESSION['counterSuccesfulSendingsProvider'] != $counterMailsToSend) { ?>
    toastr.error('Tous les emails n\'ont pas été envoyés.', 'Erreur.');
    <?php } ?>

    <?php if(isset($_POST['msgStore']) && !empty($_POST['msgStore']) &&  $_SESSION['counterSuccesfulSendingsStore'] == $counterMailsToSend) { ?>
    toastr.success('Tous les emails ont été envoyés.', 'Succès.');
    <?php } elseif(isset($_POST['msgStore']) && !empty($_POST['msgStore']) &&  $_SESSION['counterSuccesfulSendingsStore'] != $counterMailsToSend) { ?>
    toastr.error('Tous les emails n\'ont pas été envoyés.', 'Erreur.');
    <?php } ?>

    /* Spinner */
    $(function(){ $('#submitButtonProviders').on('click', function(){ $('.ibox-content').toggleClass('sk-loading'); }); });
    $(function(){ $('#submitButtonStore').on('click', function(){ $('.ibox-content').toggleClass('sk-loading'); }); });

    </script>

</body>

</html>