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

/* To prevent server misconfigured */
date_default_timezone_set('Europe/Paris');

/* French days names */
$arrayDays = array('DIMANCHE','LUNDI','MARDI','MERCREDI','JEUDI','VENDREDI','SAMEDI');

/* French months names */
$arrayMonths = array('JANVIER','FEVRIER','MARS','AVRIL','MAI','JUIN','JUILLET','AOUT','SEPTEMBRE','OCTOBRE','NOVEMBRE','DECEMBRE');

$pfConcerned = $_SESSION['purchasingFairConcerned'];
$startDatetime = DateTime::createFromFormat('Y-m-d H:i:s', $pfConcerned->getStartDatetime());
$endDatetime   = DateTime::createFromFormat('Y-m-d H:i:s', $pfConcerned->getEndDatetime());

$counterArrayPlanningDays= 0;

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

// Added 27.08.2018 - Taking others Pf into account
$isOtherPf = ( $_SESSION['purchasingFairConcerned']->getOneTypeOfPf()->getNameTypeOfPf() === 'Autre' ) ? true : false;
?>

<!DOCTYPE html>
<html lang="fr">

<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="theme-color" content="#0b70b5"><!-- Mobile browser Tab Color -->

    <title>PFManagement | Récapitulatif Participants+Repas</title>
	
    <!-- Favicon -->
    <?php require_once dirname ( __FILE__ ) . '/view/favicon.inc.php'; ?>

    <!-- Bootstrap -->
    <link href="css/bootstrap.min.css" rel="stylesheet">
	
    <!-- Font Awesome -->
    <link href="font-awesome/css/font-awesome.css" rel="stylesheet">
    
    <!-- DataTables -->
    <link href="css/plugins/dataTables/datatables.min.css" rel="stylesheet">
	
    <!-- Hover -->
    <link href="css/plugins/hover.css/hover-min.css" rel="stylesheet">
    
    <!-- Toastr style -->
    <link href="css/plugins/toastr/toastr.min.css" rel="stylesheet">

    <!-- Animate -->
    <link href="css/animate.css" rel="stylesheet">
    
    <!-- jsTree -->
    <link href="css/plugins/jsTree/style.min.css" rel="stylesheet">
	
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
    .hvr-icon-wobble-horizontal:before { content: "\f12d"; }
    /* Spinner color */
    .sk-spinner-wave div { background-color:#0b70b5; }
    .text-leclerc-blue { color:#0b70b5; }
    .text-leclerc-orange { color:#ed8b18; }
    
    .jstree-open > .jstree-anchor > .fa-folder:before {
        content: "\f07c";
    }

    .jstree-default .jstree-icon.none {
        width: 0;
    }
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
                    <span class="m-r-sm text-muted welcome-message">Récapitulatif des Participants</span>
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
                    <h2><i class="fa fa-table" aria-hidden="true"></i> Récapitulatif des Participants+Repas connus aujourd'hui à <?php echo date('H:i:s'); ?></h2>
                    <ol class="breadcrumb">
                        <li class="active">
                            <strong>Administration/Récapitulatif participants</strong>
                        </li>
                    </ol>
                </div>
                <div class="col-lg-3">
                    <?php require_once dirname ( __FILE__ ) . '/view/widget_pf_info.inc.php'; ?>
                </div>
            </div>

            <div class="wrapper wrapper-content">
                
                <div class="row" >
                    
                    <div class="col-xs-12 col-md-12 col-md-12 col-lg-6 col-lg-offset-3">
                        <div class="ibox float-e-margins animated zoomIn">
                            <div class="ibox-title" style="border-top:1px solid #0b70b5">
                                <h5><i class="fa fa-users" aria-hidden="true"></i> Liste des Participants + <i class="fa fa-cutlery" aria-hidden="true"></i> Repas</h5>
                            </div>
                            <div class="ibox-content">
                                
                                <div id="myspinner">
                                    <div class="sk-spinner sk-spinner-wave">
                                        <div class="sk-rect1"></div>
                                        <div class="sk-rect2"></div>
                                        <div class="sk-rect3"></div>
                                        <div class="sk-rect4"></div>
                                        <div class="sk-rect5"></div>
                                    </div>
                                    <div class="text-center colorBlueLeclerc">Chargement des données...</div>
                                </div>
                                
                                <?php
                                 // Get Id PurchasingFair
                                $idPurchasingFair = $_SESSION['purchasingFairConcerned']->getIdPurchasingFair();
                                
                                $counterPlanningDays = 0;
                                foreach($arrayPlanningDays as $key => $planningDay) {
                                    $startDatetime = $arrayPlanningDays[$key]->getStartDatetime();
                                    $dayFormattedKey = $startDatetime->format('Y-m-d');
                                    $dayFormattedTd  = $startDatetime->format('d/m/Y');
									
                                    $arrayAllPresentsStores[$dayFormattedKey] = array();
                                    $arrayAllPresentsProviders[$dayFormattedKey] = array();
                                    $arrayAllPresentsSpecialGuests[$dayFormattedKey] = array();
                                    
                                    // Stores
                                    $arrayLunchesStores = array();

                                    if(!$isOtherPf) {
                                        $arrayEnterprisesAsStores = $appService->findAllEnterprisesAsStoresPf($idPurchasingFair);
                                    }
                                    else {
                                        $arrayEnterprisesAsStores = $appService->findAllEnterprisesAsStores();
                                    }
                                    $limitArrayStores = count($arrayEnterprisesAsStores);
//                                        
                                    for($s = 0 ; $s < $limitArrayStores ; $s++) {
										
                                        $arrayPresents = $appService->findPresentByDuo($arrayEnterprisesAsStores[$s]->getIdEnterprise(), $idPurchasingFair);

                                        foreach($arrayPresents as $key => $present) {
                                                $details = $present->getPresentDetails();

                                            foreach($details as $key => $day) {
                                                if($day == $dayFormattedKey) {
                                                    $arrayAllPresentsStores[$dayFormattedKey][$arrayEnterprisesAsStores[$s]->getName()][] = $present;
                                                    break 1;
                                                }
                                            }
                                        }
										
                                        $lunchStore = $appService->findLunchForOneEnterpriseAndPf($arrayEnterprisesAsStores[$s]->getIdEnterprise(), $idPurchasingFair);
										
                                        // $arrayLunchesStores[$dayFormattedKey][$arrayEnterprisesAsStores[$s]->getName()] = array();

                                        if(!empty($lunchStore)) {

                                            foreach($lunchStore->getLunchesDetails() as $day => $details) {

                                                foreach($details['participant'] as $key => $idParticipant) {

                                                    $arrayLunchesStores[$day][$arrayEnterprisesAsStores[$s]->getName()][] = $appService->findOneParticipant($idParticipant);
                                                }
                                            }
                                        }
                                        else { } // nothing
                                    }
                                    // END Stores
									
                                    // Providers
                                    $arrayLunchesProviders = array();

                                    if(!$isOtherPf) {
                                        $arrayEnterprisesAsProviders = $appService->findAllEnterprisesAsProvidersPf($idPurchasingFair);
                                    }
                                    else {
                                        $arrayPP = $appService->findAllProviderPresentForOnePurchasingFair($_SESSION['purchasingFairConcerned']->getIdPurchasingFair());
                                        $arrayEnterprisesAsProviders = array();
                                        foreach($arrayPP as $key => $pp) { $arrayEnterprisesAsProviders[] = $pp->getOneProvider(); }
                                    }
                                    $limitArrayProviders = count($arrayEnterprisesAsProviders);

                                    for($p = 0 ; $p < $limitArrayProviders ; $p++) {

                                        $arrayPresents = $appService->findPresentByDuo($arrayEnterprisesAsProviders[$p]->getIdEnterprise(), $idPurchasingFair);

                                        foreach($arrayPresents as $key => $present) {
                                            $details = $present->getPresentDetails();

                                            foreach($details as $key => $day) {
                                                if($day == $dayFormattedKey) {
                                                    $arrayAllPresentsProviders[$dayFormattedKey][$arrayEnterprisesAsProviders[$p]->getName()][] = $present;
                                                    break 1;
                                                }
                                            }
                                        }

                                        // Get Lunch
                                        $lunchProvider = $appService->findLunchForOneEnterpriseAndPf($arrayEnterprisesAsProviders[$p]->getIdEnterprise(), $idPurchasingFair);
                                        // $arrayLunchesProviders[$dayFormattedKey][$arrayEnterprisesAsProviders[$p]->getName()] = array();

                                        if(!empty($lunchProvider)) {

                                            foreach($lunchProvider->getLunchesDetails() as $day => $details) {

                                                foreach($details['participant'] as $key => $idParticipant) {

                                                    $arrayLunchesProviders[$day][$arrayEnterprisesAsProviders[$p]->getName()][] = $appService->findOneParticipant($idParticipant);
                                                }
                                            }
                                        }
                                    } // End Providers

                                    $counterTotalLunchesDay = 0;
                                ?>
                                
                                <div id="jstree<?php echo $counterPlanningDays; ?>" class="myJsTree" style="display:none;">
                                    <ul>
                                        <li class="jstree-open" data-jstree='{ "icon" : "fa fa-calendar" }'><span class="text-leclerc-blue"><?php echo $dayFormattedTd; ?></span>
                                        <ul>
                                            <li data-jstree='{ "icon" : "fa fa-users" }'>Participants présents
                                                <ul>
                                                    <li data-jstree='{ "icon" : "fa fa-shopping-cart" }'>Magasins
                                                        <ul>
                                                            <?php 
                                                            foreach($arrayAllPresentsStores[$dayFormattedKey] as $store => $presents) {
                                                            ?>
                                                            <li data-jstree='{ "icon" : "fa fa-shopping-basket" }'><?php echo $store; ?>
                                                                <ul>
                                                                    <?php foreach($presents as $key => $present) { ?>
                                                                    <li data-jstree='{ "icon" : "fa fa-id-badge" }'><?php echo $present->getOneParticipant(); ?></li>
                                                                    <?php } ?>
                                                                </ul>
                                                            </li>
                                                            <?php } ?>
                                                        </ul>
                                                    </li>
                                                    <li data-jstree='{ "icon" : "fa fa-truck" }'>Fournisseurs
                                                        <ul>
                                                            <?php 
                                                            foreach($arrayAllPresentsProviders[$dayFormattedKey] as $provider => $presents) {
                                                            ?>
                                                            <li data-jstree='{ "icon" : "fa fa-user-secret" }'><?php echo $provider; ?>
                                                                <ul>
                                                                    <?php foreach($presents as $key => $present) { ?>
                                                                    <li data-jstree='{ "icon" : "fa fa-id-badge" }'><?php echo $present->getOneParticipant(); ?></li>
                                                                    <?php } ?>
                                                                </ul>
                                                            </li>
                                                            <?php } ?>
                                                        </ul>
                                                    </li>
                                                </ul>
                                            </li>
                                            <li data-jstree='{ "icon" : "fa fa-cutlery" }'>Repas
                                                <ul>
                                                    <li data-jstree='{ "icon" : "fa fa-shopping-cart" }'>Magasins
                                                        <ul>
                                                            <?php
                                                            if(isset($arrayLunchesStores[$dayFormattedKey])) {
                                                            foreach($arrayLunchesStores[$dayFormattedKey] as $store => $participants) {
                                                            ?>
                                                            <li data-jstree='{ "icon" : "fa fa-shopping-basket" }'><?php echo $store; ?>
                                                                <ul>
                                                                    <?php foreach($participants as $key => $participant) { ?>
                                                                    <li data-jstree='{ "icon" : "fa fa-id-badge" }'><?php echo $participant; ++$counterTotalLunchesDay; ?></li>
                                                                    <?php } ?>
                                                                </ul>
                                                            </li>
                                                            <?php }} ?>
                                                        </ul>
                                                    </li>
                                                    <li data-jstree='{ "icon" : "fa fa-truck" }'>Fournisseurs
                                                        <ul>
                                                            <?php
                                                            if(isset($arrayLunchesProviders[$dayFormattedKey])) {
                                                            foreach($arrayLunchesProviders[$dayFormattedKey] as $provider => $participants) {
                                                            ?>
                                                            <li data-jstree='{ "icon" : "fa fa-user-secret" }'><?php echo $provider; ?>
                                                                <ul>
                                                                    <?php foreach($participants as $key => $participant) { ?>
                                                                    <li data-jstree='{ "icon" : "fa fa-id-badge" }'><?php echo $participant; ++$counterTotalLunchesDay; ?></li>
                                                                    <?php } ?>
                                                                </ul>
                                                            </li>
                                                            <?php }} ?>
                                                        </ul>
                                                    </li>
                                                    <li class="text-danger" data-jstree='{ "icon" : "fa fa-eur" }'>Total Général Repas
                                                        <ul>
                                                            <li data-jstree='{ "icon" : "fa fa-calculator" }'><?php echo $counterTotalLunchesDay; ?></li>
                                                        </ul>
                                                    </li>
                                                </ul>
                                            </li>
                                        </ul>
                                    </ul>
                                </div>
                                <?php ++$counterPlanningDays; } ?>
                                
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
        
    <!-- jsTree -->
    <script src="js/plugins/jsTree/jstree.min.js"></script>

    <!-- Custom script -->
    <script>
        
    $(function() { 
        $('#myspinner').hide();
        $('.myJsTree').show();
        // https://github.com/vakata/jstree/issues/953 - Open parent nodes on one click
        $('.myJsTree') .on('click', '.jstree-anchor', function (e) { $(this).jstree(true).toggle_node(e.target); }).jstree({ core : { dblclick_toggle : false } });
    });
    </script>

</body>

</html>
