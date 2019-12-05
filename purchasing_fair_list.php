<?phprequire_once dirname ( __FILE__ ) . '/view/errors.inc.php';require_once dirname ( __FILE__ ) . '/services/AppServiceImpl.class.php';if(!isset($_SESSION)) session_start(); // Start session// Already connected as Enterprise & Admin (both)//if(isset($_SESSION['adminConnected']) && !empty($_SESSION['adminConnected']) && isset($_SESSION['enterpriseConcerned']) && !empty($_SESSION['enterpriseConcerned']) ) {//    header('Location: ./disconnection.php'); // Redirection to Purchasing Fair list//}header( 'content-type: text/html; charset=utf-8' ); // Specifies to the server to return UTF-8 - put in prod$appService = AppServiceImpl::getInstance();if( isset($_SESSION['enterpriseConcerned']) && !empty($_SESSION['enterpriseConcerned']) && isset($_SESSION['purchasingFairConcerned']) && !empty($_SESSION['purchasingFairConcerned']) &&         isset($_SESSION['isStoreOrProvider']) && !empty($_SESSION['isStoreOrProvider']) && $_SESSION['isStoreOrProvider'] == 'store' &&             !isset($_SESSION['adminConnected']) && empty($_SESSION['adminConnected'])  ) {    $now = new Datetime('now');    $registrationClosingDate = new DateTime($_SESSION['purchasingFairConcerned']->getRegistrationClosingDateMagasin());    if($now <= $registrationClosingDate) {        $whereIGoAfter = 'store_choice_participants';    }else{        //print_r("expression1");        $whereIGoAfter = 'purchasing_fair_list';    }}else if( isset($_SESSION['enterpriseConcerned']) && !empty($_SESSION['enterpriseConcerned']) &&        isset($_SESSION['isStoreOrProvider']) && !empty($_SESSION['isStoreOrProvider']) && $_SESSION['isStoreOrProvider'] == 'provider' &&             !isset($_SESSION['adminConnected']) && empty($_SESSION['adminConnected'])  ) { $whereIGoAfter = 'admin_salesperson_list'; }else {     $whereIGoAfter = 'admin_dashboard'; //purchasing_fair_list    //print_r("expression");}//var_dump($_SESSION['purchasingFairConcerned']);/* Unset the PurchasingFair object stored in the session (to hide elements in the menu) *///if(isset($_SESSION['purchasingFairConcerned'])) { unset($_SESSION['purchasingFairConcerned']); }// Not connected ? - Added 07.08.2018 to prevent going to homepage salespersonsif( !isset($_SESSION['adminConnected']) && !isset($_SESSION['enterpriseConcerned']) ) {    //header('Location: ./disconnection.php'); // User disconnected}// Added 27.08.2018 - Taking others Pf into account$isOtherPf = ( isset($_SESSION['purchasingFairConcerned']) && $_SESSION['purchasingFairConcerned']->getOneTypeOfPf()->getNameTypeOfPf() === 'Autre' ) ? true : false;?><!DOCTYPE html><html lang="fr"><head>    <meta charset="utf-8">    <meta name="viewport" content="width=device-width, initial-scale=1.0">    <meta name="theme-color" content="#0b70b5"><!-- Mobile browser Tab Color -->    <title>PFManagement | Liste des RDV GT</title>	    <!-- Favicon -->    <?php require_once dirname ( __FILE__ ) . '/view/favicon.inc.php'; ?>    <!-- Bootstrap -->    <link href="css/bootstrap.min.css" rel="stylesheet">        <!-- Font Awesome -->    <link href="font-awesome/css/font-awesome.css" rel="stylesheet">    <!-- Animate -->    <link href="css/animate.css" rel="stylesheet">        <!-- Global -->    <link href="css/style.css" rel="stylesheet">            <!-- Custom style -->    <style>    /* Widgets */    .widget { color:#ffffff;border:1px solid transparent; } /* To avoid the "jumpiness" bug when the hover border appears */    .widget:hover { background-color:#000000!important;color:#ffffff;border:1px solid #ffffff;box-shadow:1px 1px 12px #555;cursor:pointer; }    /* Isotope buttons */    .isotopeButton {        display: inline-block;        padding: 10px 18px;        background: #ffffff;        border: 1px solid #ed8b18;        color: #ed8b18;        font-size: 15px;        cursor: pointer;        font-weight: bold;    }    .isotopeButton:hover {        background-color: #ed8b18;        color: #ffffff;        border: 1px solid #ffffff;    }    .isotopeButton:active, .btn.is-checked {         background-color: #ed8b18;        outline:none; /* To remove blue border outline */    }    .isotopeButton.is-checked {        color: #ffffff;        border: 1px solid #ffffff;    }    /* Color orange E.Leclerc */    .colorOrangeLeclerc { color:#ed8b18; }    /* Animation delays */    #idButtonGroup { animation-delay: 0.0s; }    </style>  </head><body> <!-- class="mini-navbar" -->    <div id="wrapper">                <?php         if( isset($_SESSION['enterpriseConcerned']) && !empty($_SESSION['enterpriseConcerned'])                && isset($_SESSION['purchasingFairConcerned']) && !empty($_SESSION['purchasingFairConcerned'])                && $_SESSION['enterpriseConcerned']->getOneprofile()->getName() == "Magasin" ) {            require_once dirname ( __FILE__ ) . '/view/menu.store.inc.php';        }        elseif( isset( $_SESSION['enterpriseConcerned']) && !empty($_SESSION['enterpriseConcerned'])                 && isset($_SESSION['purchasingFairConcerned']) && !empty($_SESSION['purchasingFairConcerned'])                && $_SESSION['enterpriseConcerned']->getOneprofile()->getName() == "Fournisseur" ) {            require_once dirname ( __FILE__ ) . '/view/menu.provider.inc.php';        }        else { require_once dirname ( __FILE__ ) . '/view/menu.global.inc.php'; }        ?>        <div id="page-wrapper" class="gray-bg">        <div class="row border-bottom">        <nav class="navbar navbar-static-top  " role="navigation" style="margin-bottom: 0">        <div class="navbar-header">            <a class="navbar-minimalize minimalize-styl-2 btn btn-primary " href="#"><i class="fa fa-bars"></i> </a>        </div>            <ul class="nav navbar-top-links navbar-right">                <li>                    <span class="m-r-sm text-muted welcome-message">Vous êtes sur la page d'accueil.</span>                </li>                <li>                    <a href="./disconnection.php">                        <i class="fa fa-sign-out"></i> Quitter                    </a>                </li>            </ul>        </nav>        </div>            <div class="row wrapper border-bottom white-bg page-heading">                <div class="col-lg-9">                    <?php                     /* To prevent server misconfigured */                    date_default_timezone_set('Europe/Paris');                                        // http://php.net/manual/fr/class.datetime.php                    $todayDatetime = new DateTime();                                        // Server misconfigured SCA Ouest - Fixed 06/09/2018                    if($_SERVER['SERVER_NAME'] == '205.0.211.85') {                        // http://php.net/manual/fr/dateinterval.construct.php                        // There is a difference of 31 minutes and 25 seconds on the production server                        $todayDatetime->add( new DateInterval('PT31M25S') ); // P = period, T = Time, M = Minutes here, S = Seconds                    }                    $todayDate = $todayDatetime->format('d/m/Y');                    $todayTime = $todayDatetime->format('H:i:s');                    ?>                    <h2><i class="fa fa-th-large" aria-hidden="true"></i> Liste des RDV GTs d'achats - Nous sommes le                         <strong><?php echo $todayDate; ?></strong> et il est <strong><?php echo $todayTime; ?></h2>                    <ol class="breadcrumb">                        <li class="active">                            <strong>Liste des RDV GT</strong>                        </li>                    </ol>                </div>                <div class="col-lg-3">                    <?php if( !isset($_SESSION['purchasingFairConcerned']) && empty($_SESSION['purchasingFairConcerned']) && isset($_SESSION['enterpriseConcerned']) && !empty($_SESSION['enterpriseConcerned']) && !isset($_SESSION['adminConnected']) && empty($_SESSION['adminConnected']) ){ ?>                    <?php if( isset($_SESSION['enterpriseConcerned']) && !empty($_SESSION['enterpriseConcerned']) && !isset($_SESSION['adminConnected']) && empty($_SESSION['adminConnected']) ) { ?>                    <h2 class="text-center">Vous avez sélectionné le <?php                         //echo $_SESSION['enterpriseConcerned']->getOneProfile()->getName()                         echo "Fournisseur";                        ?></h2>                    <h2 class="colorOrangeLeclerc text-center"><em><?php echo $_SESSION['enterpriseConcerned']->getName().( ( is_null( $_SESSION['enterpriseConcerned']->getOneTypeOfProvider() ) ) ? '' : '('.$_SESSION['enterpriseConcerned']->getOneTypeOfProvider()->getNameTypeOfProvider()[0].')'); ?></em></h2>                    <?php } elseif( isset($_SESSION['adminConnected']) && !empty($_SESSION['adminConnected']) && isset($_SESSION['purchasingFairConcerned']) && !empty($_SESSION['purchasingFairConcerned'])) { ?>                    <?php require_once dirname ( __FILE__ ) . '/view/widget_pf_info.inc.php'; ?>                    <?php } elseif( isset($_SESSION['adminConnected']) && !empty($_SESSION['adminConnected']) && !isset($_SESSION['purchasingFairConcerned']) && empty($_SESSION['purchasingFairConcerned'])) { ?>                    <h2 class="text-center">Vous n'avez pas sélectionné de RDV GT</h2>                    <?php } ?>                    <?php } ?>                    <?php if( isset($_SESSION['purchasingFairConcerned']) && !empty($_SESSION['purchasingFairConcerned']) && ((isset($_SESSION['enterpriseConcerned']) && !empty($_SESSION['enterpriseConcerned'])) || (isset($_SESSION['adminConnected']) && !empty($_SESSION['adminConnected']))) ){ ?>                        <!-- WIDGETS GENERATION -->                        <?php $purchasingFairConcerned = $_SESSION['purchasingFairConcerned']; ?>                        <div id="pf_<?php echo $purchasingFairConcerned->getIdPurchasingFair(); ?>">                            <div class="widget style1" style="background-color:<?php echo $purchasingFairConcerned->getHexColor(); ?>" title="<?php echo $purchasingFairConcerned->getOneTypeOfPf()->getNameTypeOfPf(); ?>">                                <div class="row">                                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-4 text-center">                                        <i class="fa fa-handshake-o fa-4x" aria-hidden="true"></i>                                    </div>                                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-8 text-right" style="word-wrap: break-word;">                                        <span><?php echo $appService->myFrenchDate($purchasingFairConcerned->getStartDatetime()).' <i class="fa fa-long-arrow-right" aria-hidden="true"></i> '.$appService->myFrenchDate($purchasingFairConcerned->getEndDatetime()); ?></span>                                        <h3 class="font-bold"><?php echo $purchasingFairConcerned->getNamePurchasingFair(); ?></h3>                                    </div>                                </div>                            </div>                        </div>                        <!-- ./WIDGETS GENERATION -->                    <?php } ?>                </div>               </div>            <div class="wrapper wrapper-content">                                <div class="row">                                        <div class="col-lg-12 m-b-md">                        <div id="idButtonGroup" class="button-group filters-button-group">                            <div class="row">                                <div class="col-lg-12 text-center"><!--                                    <button class="btn isotopeButton" data-filter="*"><i class="fa fa-th-large" aria-hidden="true"></i> Tous</button>                                    <span class="colorOrangeLeclerc"> - </span>-->                                    <button class="btn isotopeButton is-checked" data-filter=".Textile"><i class="fa fa-filter" aria-hidden="true"></i> RDV GT</button><!--                                    <span class="colorOrangeLeclerc"> - </span>                                    <button class="btn isotopeButton" data-filter=".Autre"><i class="fa fa-filter" aria-hidden="true"></i> Autres</button>-->                                    <?php // if( isset($_SESSION['adminConnected']) && !empty($_SESSION['adminConnected']) ) { ?>                                    <span class="colorOrangeLeclerc"> - </span>                                    <button class="btn isotopeButton" data-filter=".Clotures"><i class="fa fa-filter" aria-hidden="true"></i> Clôturés</button>                                    <?php // } ?>                                </div>                                <div class="col-lg-12">&nbsp;</div>                                <div class="col-lg-12 text-center">                                    <p class="filter-count"></p>                                </div>                            </div>                        </div>                    </div>                  <div class="col-lg-12">                    <!-- add extra container element for Masonry -->                    <div class="grid">                                                <!-- add sizing element for columnWidth -->                        <div class="grid-sizer col-xs-12 col-sm-12 col-md-12 col-lg-3"></div>                                                <!-- WIDGETS GENERATION -->                        <?php                                                 // Determines the number of purchasing fairs to consider                        if( isset($_SESSION['adminConnected']) && !empty($_SESSION['adminConnected']) ) { $arrayPurchasingFairs = $appService->findAllPurchasingFairsAdmin(); }                        else { $arrayPurchasingFairs = $appService->findAllPurchasingFairsAdmin(); }                                                if (empty($arrayPurchasingFairs) ) {                             echo '<div class="col-lg-12 text-center text-danger"><h2><i class="fa fa-info-circle" aria-hidden="true"></i> Aucun RDV GT d\'achats n\'est actuellement ouvert aux inscriptions.</h2></div>';                        }                                                foreach($arrayPurchasingFairs as $key => $value) {                                                        // Providers only see the PurchasingFairs where they are/were present                            // Added 25.08.2018                            if( isset($_SESSION['enterpriseConcerned']) &&                                     !empty($_SESSION['enterpriseConcerned']) &&                                     $_SESSION['enterpriseConcerned']->getOneprofile()->getName() == 'Fournisseur') {                                 $pp = $appService->findOneProviderPresent($_SESSION['enterpriseConcerned']->getIdEnterprise(), $value->getIdPurchasingFair());                                // http://php.net/manual/en/control-structures.continue.php                                // https://stackoverflow.com/questions/4270102/php-foreach-continue                                if(empty($pp)) { continue 1; }                              }                                                        // We determine which isotope class to use                            //$now = new DateTime('now');                            $now = clone $todayDatetime;                            if(isset($_SESSION['enterpriseConcerned']) && !empty($_SESSION['enterpriseConcerned']) && $_SESSION['enterpriseConcerned']->getOneprofile()->getName() == 'Fournisseur'){                                $test = "1";                                $registrationClosingDate = new DateTime($value->getRegistrationClosingDateFournisseur());                            }else{                                $test = "2";                                $registrationClosingDate = new DateTime($value->getRegistrationClosingDateMagasin());                            }                            //$registrationClosingDate = new DateTime($value->getRegistrationClosingDateMagasin());                            $endDateTime = new DateTime($value->getEndDateTime());                            							                            // As of PHP 5.2.2, DateTime objects can be compared using comparison operators                            $isotopeClass = ( $now <= $endDateTime ) ? $value->getOneTypeOfPf()->getNameTypeOfPf() : 'Clotures';							                            if(  ( $now < $endDateTime ) or isset($_SESSION['adminConnected']) && !empty($_SESSION['adminConnected']) ) {                        ?>                            <!-- items use Bootstrap .col- classes -->                            <div class="grid-item col-xs-12 col-sm-12 col-md-12 col-lg-3 <?php echo $isotopeClass.' '.$test; ?>">                                <!-- wrap item content in its own element -->                                <div class="grid-item-content ">                                    <div id="pf_<?php echo $value->getIdPurchasingFair(); ?>" onclick="purchasingFairIntoSession(<?php echo $value->getIdPurchasingFair(); ?>);">                                        <div class="widget style1" style="background-color:<?php echo $value->getHexColor(); ?>" title="<?php echo $value->getOneTypeOfPf()->getNameTypeOfPf(); ?>">                                            <div class="row">                                                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-4 text-center">                                                    <i class="fa fa-handshake-o fa-4x" aria-hidden="true"></i>                                                </div>                                                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-8 text-right" style="word-wrap: break-word;">                                                    <span><?php echo $appService->myFrenchDate($value->getStartDatetime()).' <i class="fa fa-long-arrow-right" aria-hidden="true"></i> '.$appService->myFrenchDate($value->getEndDatetime()); ?></span>                                                    <h3 class="font-bold"><?php echo $value->getNamePurchasingFair(); ?></h3>                                                </div>                                            </div>                                        </div>                                    </div>                                </div>                            </div>                        <?php } } ?>                        <!-- ./WIDGETS GENERATION -->                                            </div><!-- ./grid -->                </div>                </div><!--./row -->                            </div><!-- ./ wrapper -->            <div class="footer">                <div class="pull-right">                    <strong><i class="fa fa-copyright" aria-hidden="true"></i></strong> E.Leclerc | SCA Ouest <?php echo date('Y'); ?>                </div>                <div>                    <strong>PFManagement</strong>                </div>            </div>        </div>        </div>    <!-- Mainly scripts -->    <script src="js/jquery-3.1.1.min.js"></script>    <script src="js/bootstrap.min.js"></script>    <script src="js/plugins/metisMenu/jquery.metisMenu.js"></script>    <script src="js/plugins/slimscroll/jquery.slimscroll.min.js"></script>     <!-- Custom and plugin javascript -->    <script src="js/inspinia.js"></script>    <script src="js/plugins/pace/pace.min.js"></script>        <!-- Isotope -->    <script src="js/plugins/isotope/isotope.pkgd.min.js"></script>        <script language="javascript" type='text/javascript'>        function session(){            window.location="disconnection.php"; //page de déconnexion        }        setTimeout("session()",600000); //ça fait bien 5min??? c'est pour le test    </script>    <script>    // Put the Purchasing Fair Object into Session on widget click    function purchasingFairIntoSession(idPurchasingFair) {        $.post(            './ajax/purchasingFairIntoSession.php',            {                idPurchasingFair : idPurchasingFair            },            function(data) {                                data = data.trim();                if(data !== 'Failed') { // If Pf putted into session                    $obj = JSON.parse(data);                    //console.log($obj[2]);                                        // If it is a classic Pf, check if the user is a Store or a Provider, and redirect with var $whereIGoAfter                    if( $obj[0] === 'Textile' && <?php echo ( !isset($_SESSION['adminConnected']) && empty($_SESSION['adminConnected']) ) ? 1 : 0; ?> ) {                        if(<?php echo (isset($_SESSION['enterpriseConcerned']) && !empty($_SESSION['enterpriseConcerned']) &&                         isset($_SESSION['isStoreOrProvider']) && !empty($_SESSION['isStoreOrProvider']) && $_SESSION['isStoreOrProvider'] == 'provider' && !isset($_SESSION['adminConnected']) && empty($_SESSION['adminConnected'])) ? 1 : 0; ?> ){                                                        $poursuite = 'admin_salesperson_list';                        }else{                            if(($obj[1]['date'].substr(0,4)+$obj[1]['date'].substr(5,2)+$obj[1]['date'].substr(8,2)+$obj[1]['date'].substr(11,2)+$obj[1]['date'].substr(14,2)+$obj[1]['date'].substr(17,2)) <= ($obj[2]['date'].substr(0,4)+$obj[2]['date'].substr(5,2)+$obj[2]['date'].substr(8,2)+$obj[2]['date'].substr(11,2)+$obj[2]['date'].substr(14,2)+$obj[2]['date'].substr(17,2))) {                                $poursuite = 'store_choice_participants';                            }else{                                //print_r("expression1");                                $poursuite = 'purchasing_fair_list';                            }                        }                        window.location.href = './'+$poursuite+'.php';                    }                    else if ( $obj[0] === 'Autre' && <?php echo ( isset($_SESSION['isStoreOrProvider']) && !empty($_SESSION['isStoreOrProvider']) && $_SESSION['isStoreOrProvider'] === 'store' ) ? 1 : 0; ?> ) {                        window.location.href = './<?php echo $whereIGoAfter; ?>.php';                    }                    else if ( $obj[0] === 'Autre' && <?php echo ( isset($_SESSION['isStoreOrProvider']) && !empty($_SESSION['isStoreOrProvider']) && $_SESSION['isStoreOrProvider'] === 'provider' ) ? 1 : 0; ?> ) {                        window.location.href = './admin_salesperson_list.php';                    }                    else {                        window.location.href = './<?php echo $whereIGoAfter; ?>.php';                    }                }                else {                    alert("Erreur fatale. Contactez l'administrateur");                }            },            'text'        );    }        $(function(){         /* Isotope layouts with Bootstrap grid system         * Src filtering layouts (without Bootstrap) : https://codepen.io/desandro/pen/Ehgij         * Info with Bootstrap : https://isotope.metafizzy.co/extras.html#bootstrap         * Update filtered item count : https://codepen.io/desandro/pen/pbkbBO         */        // init Isotope        var $grid = $('.grid').isotope({            itemSelector: '.grid-item', // use a separate class for itemSelector, other than .col-            percentPosition: true,            filter: '.Textile', // Default sorting            masonry: { columnWidth: '.grid-sizer' }        });                var iso          = $grid.data('isotope');        var $filterCount = $('.filter-count');        // Bind filter button click        $('.filters-button-group').on( 'click', 'button', function() {            var filterValue = $( this ).attr('data-filter');            //console.log(filterValue);            $grid.isotope({ filter: filterValue });            updateFilterCount();        });                function updateFilterCount() {            var nbPf   = iso.filteredItems.length;            var plural = (nbPf > 1) ? 's' : '';            $filterCount.html('<span class="label label-success"><i class="fa fa-caret-right" aria-hidden="true"></i> ' + nbPf + ' RDV GT' + plural + ' affiché' + plural + '</span>' );        }        updateFilterCount();        // Change is-checked class on buttons        $('.button-group').each( function( i, buttonGroup ) {            var $buttonGroup = $( buttonGroup );            $buttonGroup.on( 'click', 'button', function() {                $buttonGroup.find('.is-checked').removeClass('is-checked');                $( this ).addClass('is-checked');            });        });        });    </script></body></html>