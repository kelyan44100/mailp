<?php
require_once dirname ( __FILE__ ) . '/view/errors.inc.php';
require_once dirname ( __FILE__ ) . '/services/AppServiceImpl.class.php';
require_once dirname ( __FILE__ ) . '/domain/PlanningDay.class.php';

if(!isset($_SESSION)) session_start(); // Start session

// Not connected as Admin ?
if(!isset($_SESSION['enterpriseConcerned']) && empty($_SESSION['enterpriseConcerned'])) {
    header('Location: ./disconnection.php'); // Redirection to Purchasing Fair list
}

header( 'content-type: text/html; charset=utf-8' ); // Specifies to the server to return UTF-8 - put in prod

$appService = AppServiceImpl::getInstance();

$arraySpecialGuest = $appService->findAllSpecialGuestForOneEnterpriseAndPf($_SESSION['enterpriseConcerned']->getIdEnterprise(), $_SESSION['purchasingFairConcerned']->getIdPurchasingFair());

/* French days names */
$arrayDays = array('DIMANCHE','LUNDI','MARDI','MERCREDI','JEUDI','VENDREDI','SAMEDI');

/* Get the PurchasingFair concerned */
//$pfConcerned   = $appService->findOnePurchasingFair(3); // @TODO Change number w/ $_SESSION
$pfConcerned = $_SESSION['purchasingFairConcerned'];

/* PurchasingFair start/end datetimes */
$startDatetime = DateTime::createFromFormat('Y-m-d H:i:s', $pfConcerned->getStartDatetime());
$endDatetime   = DateTime::createFromFormat('Y-m-d H:i:s', $pfConcerned->getEndDatetime());

$counterArrayPlanningDays = 0;

// Since PHP 5.2.2 you can use <, >, == ; Here not <= because we consider time !
// http://php.net/manual/fr/function.date.php // Days of week start at 0, Months at 1
while($startDatetime < $endDatetime) { 
    
    if($arrayDays[$startDatetime->format('w')] != 'SAMEDI' && $arrayDays[$startDatetime->format('w')] != 'DIMANCHE') {
        
        // Clone because variables hold references to objects, not the objects themselves. 
        // So assignment just gets you more variables pointing to the same object, not multiple copies of the object.
        $startAt = clone $startDatetime;
        $endAt   = clone $startDatetime;
        $endAt->setTime(19,0,0);
        $arrayPlanningDays[] = new PlanningDay(++$counterArrayPlanningDays, $startAt, $endAt, array(), array());
    }
    // http://php.net/manual/fr/datetime.add.php
    // http://php.net/manual/fr/dateinterval.construct.php
    $startDatetime->add( new DateInterval('P1D') ); // P = period, D = Day
}

?>

<!DOCTYPE html>
<html lang="fr">

<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="theme-color" content="#0b70b5"><!-- Mobile browser Tab Color -->

    <title>PFManagement | Invités exceptionnels</title>
	
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
    /* Widget */
    .widget { color:#ffffff;border:1px solid #ffffff; }
    /* icon delete guest */
    #iconDeleteGuest {cursor:pointer;color:#ff0000!important;}
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
                <div class="col-lg-9">
                    <h2><i class="fa fa-user-plus" aria-hidden="true"></i> Saisie des invités exceptionnels pour le salon d'achats</h2>
                    <ol class="breadcrumb">
                        <li class="active">
                            <strong>Accès Fournisseur/Invités exceptionnels</strong>
                        </li>
                    </ol>
                </div>
                <div class="col-lg-3">
                    <?php require_once dirname ( __FILE__ ) . '/view/widget_pf_info.inc.php'; ?>
                </div>
            </div>

            <div class="wrapper wrapper-content">
                
                <div class="row" >
                    
                    <div class="col-lg-offset-3 col-lg-6">
                        <div class="ibox float-e-margins animated zoomIn">
                            <div class="ibox-title" style="border-top:1px solid #0b70b5">
                                <h5>Liste des invités exceptionnels</h5>
                                <div id="alertSpecialGuests" class="alert alert-<?php echo ( empty($arraySpecialGuest) ) ? 'danger' : 'success'; ?> m-t-md">
                                    <span>
                                        <span id="listSpecialGuestsRegistered">
                                        <?php if(empty($arraySpecialGuest)) { ?>
                                        <i class="fa fa-info-circle" aria-hidden="true"></i> Vous n'avez pas encore renseigné d'invités exceptionnels pour ce salon d'achats.
                                        <?php } else { ?>
                                        <i class="fa fa-check-circle" aria-hidden="true"></i> Vous avez renseigné les invités exceptionnels suivants pour ce salon d'achats :<br/>
                                        <?php                                         
                                        foreach($arraySpecialGuest as $specialGuest) { 
                                            
                                            $deleteAction = '&nbsp;<i id="iconDeleteGuest" class="fa fa-times-circle" aria-hidden="true" title="Supprimer"'
                                                    . ' onclick="deleteSpecialGuest('.$specialGuest->getIdSpecialGuest().');"></i>';
                                            
                                            echo $specialGuest.$deleteAction.'<br/>';
                                        }} 
                                        ?>
                                        </span>
                                    </span>
                                </div>
                            </div>
                            <div class="ibox-content">
                                <div class="row">
                                    <h2 class="text-center">Formulaire d'ajout</h2>
                                    <form id="formSpecialGuest" class="form-inline m-t" role="form" action="#" method="POST">   
                                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-10 col-lg-offset-1 form-group">
                                            <label for="civility">Civilité :</label>
                                            <select id="civility" class="form-control" name="civility" required="" style="width:100%">
                                                <option value="">Civilité</option>
                                                <option value="Madame">Madame</option>
                                                <option value="Monsieur">Monsieur</option>
                                            </select>
                                        </div>
                                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-1">&nbsp;</div><!-- Separation -->
                                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-10 col-lg-offset-1 m-t-md form-group">
                                            <label for="surname">Nom de famille :</label>
                                            <input class="form-control" type="text" id="surname" name="surname" placeholder="" maxlength="50" required="" size="20" style="width:100%">
                                        </div>
                                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-1">&nbsp;</div><!-- Separation -->
                                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-10 col-lg-offset-1 m-t-md form-group">
                                            <label for="name">Prénom :</label>
                                            <input  class="form-control" type="text" id="name" name="name" placeholder="" maxlength="50" required="" size="20" style="width:100%">
                                        </div>
                                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-1">&nbsp;</div><!-- Separation -->
                                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-10 col-lg-offset-1 m-t-md form-group">
                                            <label>Jours :</label><br/>
                                            <div id="idWarningCheckboxes" class="alert alert-warning m-t-md" style="display:none">
                                                <i class="fa fa-exclamation-triangle" aria-hidden="true"></i>
                                                &nbsp;Merci de choisir au moins un jour parmi ceux proposés.
                                            </div>
                                            <?php foreach($arrayPlanningDays as $value) { ?>
                                            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-8 col-lg-offset-4 checkbox">
                                                <label>
                                                    <input class="checkbox" name="days[]" type="checkbox" value="<?php echo $value->getStartDatetime()->format('Y-m-d'); ?>">
                                                    &nbsp;<?php echo $arrayDays[$value->getStartDatetime()->format('w')]. ' '.$value->getStartDatetime()->format('d/m/Y'); ?>
                                                </label>
                                            </div><br/>
                                            <?php } ?>
                                        </div>
                                        <div class="col-xs-12 col-sm-12 col-md-12 hidden-lg">&nbsp;</div><!-- Separation -->
                                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-10 col-lg-offset-1 m-t-md">
                                            <button type="submit" id="submitButtonInsert" name="submitButtonInsert" class="btn btn-success block full-width m-t m-b hvr-icon-wobble-horizontal">Ajouter</button>
                                        </div>
                                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-1">&nbsp;</div><!-- Separation -->
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
    function deleteSpecialGuest(idSpecialGuest) {
        
        if ( confirm('Confirmer votre choix ?') != false ) { // The confirm() method returns true if the user clicked "OK", and false otherwise.

            $.post(
                './ajax/deleteSpecialGuest.php',
                {
                    idSpecialGuest : idSpecialGuest
                },
                function(data) {
                    data = data.trim();
                    if(data !== '-1') { location.reload(); }
                    else { toastr.error('L\'invité exceptionnel n\'a pas été supprimé.', 'Échec.'); }
                },
                'text'
            );
        }
    }

    $(function(){
        // The submit event occurs when a form is submitted.
        // https://www.w3schools.com/jquery/event_submit.asp
        // https://www.w3schools.com/jquery/tryit.asp?filename=tryjquery_event_submit_prevent
        $('#formSpecialGuest').submit(function(event){
            event.preventDefault();
            if( $('.checkbox:checked').length === 0 ) { $('#idWarningCheckboxes').show(); }
            else {
                
                var civility = $('#civility option:selected').val();
                var surname  = $('#surname').val();
                var name     = $('#name').val();
                
                var days = '';
                var counterDays = 0;
                
                $(".checkbox:checked").each(function(){ 
                    days += ( (counterDays > 0) ? '|' : '') + $(this).val();
                    ++counterDays;
                });

                $.post(
                    './ajax/insertSpecialGuest.php',
                    {
                        enterprise : <?php echo $_SESSION['enterpriseConcerned']->getIdEnterprise(); ?>,
                        purchasingFair : <?php echo $_SESSION['purchasingFairConcerned']->getIdPurchasingFair(); ?>,
                        civility : civility,
                        surname : surname,
                        name : name,
                        days : days
                    },
                    function(data) {
                        
                        data = data.trim();
                        
                        if(data !== '-1') {
                            
                            // Form reset
                            // https://www.w3schools.com/jsref/met_form_reset.asp
                            $('#formSpecialGuest')[0].reset(); // JQUERY VERSION
                            // document.getElementById('formSpecialGuest').reset(); // JS VERSION
                            $('#idWarningCheckboxes').hide();
                            toastr.success('Ajout réussi.', 'Succès.');
                            
                            if( $('#alertSpecialGuests').hasClass('alert-danger') ) {
                                $('#alertSpecialGuests').removeClass('alert-danger');
                                $('#alertSpecialGuests').addClass('alert-success');
                                $('#listSpecialGuestsRegistered').html('<i class="fa fa-check-circle" aria-hidden="true"></i> Vous avez renseigné les invités exceptionnels suivants pour ce salon d\'achats :<br/>');
                            }
                            $('#listSpecialGuestsRegistered').html($('#listSpecialGuestsRegistered').html() + data + '<br/>');
                        }
                        else { toastr.error('L\'invité exceptionnel n\'a pas été ajouté.', 'Échec.'); }
                    },
                    'text'
                );
            }
        });
    });
    </script>

</body>

</html>
