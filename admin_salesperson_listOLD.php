<?php
require_once dirname ( __FILE__ ) . '/domain/PurchasingFair.class.php';
require_once dirname ( __FILE__ ) . '/services/AppServiceImpl.class.php';

if(!isset($_SESSION)) session_start(); // Start session

//if(isset($_SESSION['user']) && !empty($_SESSION['user'])) // Already connected ?
//	header('Location: ./home.php'); // Home page redirection

header( 'content-type: text/html; charset=utf-8' ); // Specifies to the server to return UTF-8 - put in prod

$appService = AppServiceImpl::getInstance();
?>

<!DOCTYPE html>
<html>

<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>INSPINIA | Empty Page</title>

    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="font-awesome/css/font-awesome.css" rel="stylesheet">
    <link href="css/plugins/select2/select2.min.css" rel="stylesheet">
    <link href="css/plugins/hover.css/hover-min.css" rel="stylesheet">

    <link href="css/animate.css" rel="stylesheet">
    <link href="css/style.css" rel="stylesheet">
    <style>
    #submitButton { background-color:#0b70b5;color:#ffffff; }
    .actionIconUpdate { color:Orange;cursor:pointer; }
    .actionIconDelete { color:Red;cursor:pointer; }
    </style>
                    

</head>

<body class="">

    <div id="wrapper">

        <?php require_once dirname ( __FILE__ ) . '/view/menu.inc.php'; ?>

        <div id="page-wrapper" class="gray-bg">
        <div class="row border-bottom">
        <nav class="navbar navbar-static-top  " role="navigation" style="margin-bottom: 0">
        <div class="navbar-header">
            <a class="navbar-minimalize minimalize-styl-2 btn btn-primary " href="#"><i class="fa fa-bars"></i> </a>
            <form role="search" class="navbar-form-custom" action="search_results.html">
                <div class="form-group">
                    <input type="text" placeholder="Search for something..." class="form-control" name="top-search" id="top-search">
                </div>
            </form>
        </div>
            <ul class="nav navbar-top-links navbar-right">
                <li>
                    <span class="m-r-sm text-muted welcome-message">Welcome to INSPINIA+ Admin Theme.</span>
                </li>
                <li class="dropdown">
                    <a class="dropdown-toggle count-info" data-toggle="dropdown" href="#">
                        <i class="fa fa-envelope"></i>  <span class="label label-warning">16</span>
                    </a>
                    <ul class="dropdown-menu dropdown-messages">
                        <li>
                            <div class="dropdown-messages-box">
                                <a href="profile.html" class="pull-left">
                                    <img alt="image" class="img-circle" src="img/a7.jpg">
                                </a>
                                <div class="media-body">
                                    <small class="pull-right">46h ago</small>
                                    <strong>Mike Loreipsum</strong> started following <strong>Monica Smith</strong>. <br>
                                    <small class="text-muted">3 days ago at 7:58 pm - 10.06.2014</small>
                                </div>
                            </div>
                        </li>
                        <li class="divider"></li>
                        <li>
                            <div class="dropdown-messages-box">
                                <a href="profile.html" class="pull-left">
                                    <img alt="image" class="img-circle" src="img/a4.jpg">
                                </a>
                                <div class="media-body ">
                                    <small class="pull-right text-navy">5h ago</small>
                                    <strong>Chris Johnatan Overtunk</strong> started following <strong>Monica Smith</strong>. <br>
                                    <small class="text-muted">Yesterday 1:21 pm - 11.06.2014</small>
                                </div>
                            </div>
                        </li>
                        <li class="divider"></li>
                        <li>
                            <div class="dropdown-messages-box">
                                <a href="profile.html" class="pull-left">
                                    <img alt="image" class="img-circle" src="img/profile.jpg">
                                </a>
                                <div class="media-body ">
                                    <small class="pull-right">23h ago</small>
                                    <strong>Monica Smith</strong> love <strong>Kim Smith</strong>. <br>
                                    <small class="text-muted">2 days ago at 2:30 am - 11.06.2014</small>
                                </div>
                            </div>
                        </li>
                        <li class="divider"></li>
                        <li>
                            <div class="text-center link-block">
                                <a href="mailbox.html">
                                    <i class="fa fa-envelope"></i> <strong>Read All Messages</strong>
                                </a>
                            </div>
                        </li>
                    </ul>
                </li>
                <li class="dropdown">
                    <a class="dropdown-toggle count-info" data-toggle="dropdown" href="#">
                        <i class="fa fa-bell"></i>  <span class="label label-primary">8</span>
                    </a>
                    <ul class="dropdown-menu dropdown-alerts">
                        <li>
                            <a href="mailbox.html">
                                <div>
                                    <i class="fa fa-envelope fa-fw"></i> You have 16 messages
                                    <span class="pull-right text-muted small">4 minutes ago</span>
                                </div>
                            </a>
                        </li>
                        <li class="divider"></li>
                        <li>
                            <a href="profile.html">
                                <div>
                                    <i class="fa fa-twitter fa-fw"></i> 3 New Followers
                                    <span class="pull-right text-muted small">12 minutes ago</span>
                                </div>
                            </a>
                        </li>
                        <li class="divider"></li>
                        <li>
                            <a href="grid_options.html">
                                <div>
                                    <i class="fa fa-upload fa-fw"></i> Server Rebooted
                                    <span class="pull-right text-muted small">4 minutes ago</span>
                                </div>
                            </a>
                        </li>
                        <li class="divider"></li>
                        <li>
                            <div class="text-center link-block">
                                <a href="notifications.html">
                                    <strong>See All Alerts</strong>
                                    <i class="fa fa-angle-right"></i>
                                </a>
                            </div>
                        </li>
                    </ul>
                </li>


                <li>
                    <a href="login.html">
                        <i class="fa fa-sign-out"></i> Log out
                    </a>
                </li>
            </ul>

        </nav>
        </div>
            <div class="row wrapper border-bottom white-bg page-heading">
                <div class="col-sm-6">
                    <h2>Sélection des Fournisseurs et des commerciaux associés<br>
                        <strong>
                        Salon 
                        <?php 
                        echo $_SESSION['purchasingFairConcerned']->getNamePurchasingFair(). ' ';
                        echo $appService->myFrenchDateB($_SESSION['purchasingFairConcerned']->getStartDatetime()).' <i class="fa fa-long-arrow-right" aria-hidden="true"></i> '.$appService->myFrenchDateB($_SESSION['purchasingFairConcerned']->getEndDatetime());
                        ?>)
                        </strong>
                    </h2>
                    <ol class="breadcrumb">
                        <li>
                            <a href="index.html">This is</a>
                        </li>
                        <li class="active">
                            <strong>Breadcrumb</strong>
                        </li>
                    </ol>
                </div>
                <div class="col-sm-6">
                    <div class="title-action">
                        <a href="" class="btn btn-primary">This is action area</a>
                    </div>
                </div>
            </div>

            <div class="wrapper wrapper-content">
                
                
                <div class="row">
                    
                    <div class="col-lg-12">
                        <div class="ibox float-e-margins animated fadeInDown">
                            <div class="ibox-title" style="border-top:1px solid #0b70b5">
                                <h5>Basic IN+ Panel <small class="m-l-sm">This is custom panel</small></h5>
                                <div class="ibox-tools">
                                    <a class="collapse-link">
                                        <i class="fa fa-chevron-up"></i>
                                    </a>
                                    <a class="dropdown-toggle" data-toggle="dropdown" href="#">
                                        <i class="fa fa-wrench"></i>
                                    </a>
                                    <ul class="dropdown-menu dropdown-user">
                                        <li><a href="#">Config option 1</a>
                                        </li>
                                        <li><a href="#">Config option 2</a>
                                        </li>
                                    </ul>
                                    <a class="close-link">
                                        <i class="fa fa-times"></i>
                                    </a>
                                </div>
                            </div>
                            <div class="ibox-content">
                                <div class="row">
                                    <form class="m-t col-lg-4 col-lg-offset-4" role="form" action="./purchasing_fair_list.php" method="POST">        

                                        <select id="selectEnterprise" name="selectEnterprise" class="form-group form-control full-width" required="">
                                            <option value="">-- Choix Fournisseur --</option>
                                            <?php 
                                            $arrayEnterprises = $appService->findAllEnterprisesAsProviders();
                                            foreach($arrayEnterprises as $key => $value) 
                                                echo '<option value="'.$value->getIdEnterprise().'">'.$value->getName().' ('.$value->getOneDepartment()->getIdDepartment().')</option>';
                                            ?>
                                        </select>
                                        
                                    </form>
                                </div>
                                <div class="row m-t-md">
                                    <div id="formSalespersonsGenerated">
                                        <div class="text-center text-danger"><span><i class="fa fa-info-circle" aria-hidden="true"></i> Merci de choisir le Fournisseur pour laquelle vous souhaitez visualiser les Commerciaux</span></div>
                                    </div>
                                </div>
                                <div class="col-lg-12">&nbsp;</div>
                            </div>
                            <div class="ibox-footer">
                                <span class="pull-right">
                                  The righ side of the footer
                            </span>
                                This is simple footer example
                            </div>
                        </div>
                    </div>
                    
                    
                </div><!-- ./row -->
            </div>
            <div class="footer">
                <div class="pull-right">
                    10GB of <strong>250GB</strong> Free.
                </div>
                <div>
                    <strong>Copyright</strong> Example Company &copy; 2014-2017
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
    
    <!-- Select2 -->
    <script src="js/plugins/select2/select2.full.min.js"></script>

    <script>
    // select2 activation
    $("#selectEnterprise").select2( { placeholder: "--- Choix Fournisseur ---"});
    
    $("#selectEnterprise").change(function() {
        
        $("#formSalespersonsGenerated").html(""); // Reset list providers

        $.post(
            './ajax/generate_providers_salespersons_list.php',
            {
                provider :  $("#selectEnterprise").val()
                
            },
            function(data){
                $("#formSalespersonsGenerated").html(data);
            },
            'text'
        );
//        console.log(selectedValues);
        return false;
    });
    </script>

</body>

</html>
