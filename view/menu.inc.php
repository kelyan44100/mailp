<nav class="navbar-default navbar-static-side" role="navigation">
    <div class="sidebar-collapse">
        <ul class="nav metismenu" id="side-menu">
            <li class="nav-header">
                <div class="dropdown profile-element"> <span>
                    <!-- Tip with Adobe Photoshop CC 2017 :
                    1 - Take the maximum height of the image to edit
                    2 - Divide this value by 48 round down
                    3 - Create a square with the value obtained and place it on the area of ​​the image to keep
                    4- Crop the image many times
                    5 - Resize the image to 48x48 px -->
                    <?php if( isset($_SESSION['enterpriseConcerned']) && !empty($_SESSION['enterpriseConcerned']) && !isset($_SESSION['adminConnected']) ) { ?>
                    <img alt="profile_pic" class="img-circle" src="img/profiles_pics/store.jpg" style="height:48px;width:48px"/>
                    <?php } else { ?>
                    <img alt="profile_pic" class="img-circle" src="img/profiles_pics/admin.jpg" style="height:48px;width:48px"/>
                    <?php }  ?>

                     </span>
                    <a data-toggle="dropdown" class="dropdown-toggle" href="#">
                        <span class="clear">
                            <span class="block m-t-xs">
                                <strong class="font-bold">
                                <?php 
                                if( isset($_SESSION['enterpriseConcerned']) && !empty($_SESSION['enterpriseConcerned']) ) {
                                    echo $_SESSION['enterpriseConcerned']->getName();
                                }
                                else { echo '__ADMINISTRATEUR__'; }
                                ?>
                                </strong>
                            </span>
                            <span class="text-muted text-xs block">
                                <?php 
                                if( isset($_SESSION['enterpriseConcerned']) && !empty($_SESSION['enterpriseConcerned']) ) {
                                    echo $_SESSION['enterpriseConcerned']->getOneProfile()->getName();
                                }
                                else { echo 'Super Utilisateur'; }
                                ?>
                                <b class="caret"></b>
                            </span>
                        </span>
                    </a>
                    <ul class="dropdown-menu animated fadeInRight m-t-xs">
                        <li><a href="./disconnection.php"><i class="fa fa-sign-out" aria-hidden="true"></i> Quitter</a></li>
                    </ul>
                </div>
                <div class="logo-element">
                    <img id="miniLogo" class="img-responsive" src="./img/logo_leclerc_symbol_only_white.svg" alt="PFManagement" style="height:25px;width:25px;margin:0 auto">
                </div>
            </li>
            <?php if (isset($_SESSION['adminConnected']) && !empty($_SESSION['adminConnected']) ) { ?>
            <li>
                <a href="./index_provider.php"><i class="fa fa-id-card-o" aria-hidden="true"></i> <span class="nav-label">Liste Fournisseurs</span></a>
            </li>            
            <?php } ?>
            <li>
                <a href="./purchasing_fair_list.php"><i class="fa fa-th-large" aria-hidden="true"></i> <span class="nav-label">Liste salons</span></a>
            </li>
            <?php if ( !isset($_SESSION['adminConnected']) && empty($_SESSION['adminConnected']) && isset($_SESSION['purchasingFairConcerned']) ) { ?>
            <li>
                <a href="./store_choice_providers.php"><i class="fa fa-id-card-o" aria-hidden="true"></i> <i class="fa fa-clock-o" aria-hidden="true"></i> <span class="nav-label">Besoins en heures</span></a>
            </li>
            <li>
                <a href="./store_unavailabilities_register.php"><i class="fa fa-calendar-times-o" aria-hidden="true"></i> <span class="nav-label">Indisponibilités</span></a>
            </li>
            <li>
                <a href="./store_choice_participants.php"><i class="fa fa-users" aria-hidden="true"></i> <span class="nav-label">Participants</span></a>
            </li>
            <?php if ( isset($_SESSION['purchasingFairConcerned']) && !empty($_SESSION['purchasingFairConcerned']) && !isset($_SESSION['adminConnected']) ) { ?>
            <li>
                <a onclick="window.open('./pdf_participation_details.php', '_blank');"><i class="fa fa-file-pdf-o" aria-hidden="true"></i> <span class="nav-label">Récapitulatif</span></a>
            </li>
            <?php } ?>
            <?php } ?>
            <?php if (isset($_SESSION['adminConnected'])) { ?>
            <li>
                <a href="#"><i class="fa fa-wrench" aria-hidden="true"></i> <span class="nav-label">Administration</span> <span class="fa arrow"></span></a>
                <ul class="nav nav-second-level collapse">
                    <li><a href="./admin_assignment_store_department.php"><i class="fa fa-map-o" aria-hidden="true"></i> Regrouper Magasins</a></li>
                    <li><a href="./admin_purchasing_fair_register.php"><i class="fa fa-pencil" aria-hidden="true"></i> Saisie Salon</a></li>
                    <li><a href="./admin_providers_register.php"><i class="fa fa-id-card-o" aria-hidden="true"></i> Saisie Fournisseurs</a></li>
                    <li><a href="./admin_log_errors_check.php"><i class="fa fa-file-text" aria-hidden="true"></i> Visualiser Erreurs</a></li>
                </ul>
            </li>
            <?php } ?>
            <?php if (isset($_SESSION['purchasingFairConcerned']) && !empty($_SESSION['purchasingFairConcerned']) ) { ?>
            <li>
                <a href="#"><i class="fa fa-print" aria-hidden="true"></i> <span class="nav-label">Édition</span> <span class="fa arrow"></span></a>
                <ul class="nav nav-second-level collapse">
                    <li>
                        <a onclick="window.open('./admin_stickers_generation.php', '_blank');"><i class="fa fa-qrcode" aria-hidden="true"></i> <span class="nav-label">Étiquettes</span></a>
                    </li>
                </ul>
            </li>
            <?php } ?>
            <?php if (isset($_SESSION['purchasingFairConcerned']) && !empty($_SESSION['purchasingFairConcerned']) && isset($_SESSION['adminConnected']) && !empty($_SESSION['adminConnected'])) { ?>
            <li>
                <a href="#"><i class="fa fa-eye" aria-hidden="true"></i> <span class="nav-label">Synthèse</span> <span class="fa arrow"></span></a>
                <ul class="nav nav-second-level collapse">
                    <li>
                        <a onclick="window.open('./excel_summary_req.php', '_blank');"><i class="fa fa-file-excel-o" aria-hidden="true"></i> <span class="nav-label">Récap Mag-Besoins</span></a>
                    </li>
                    <li>
                        <a onclick="window.open('./excel_summary_sp_stores.php', '_blank');"><i class="fa fa-file-excel-o" aria-hidden="true"></i> <span class="nav-label">Récap Commerciaux/Mag</span></a>
                    </li>
                    <li>
                        <a href="./admin_summary_participants.php"><i class="fa fa-table" aria-hidden="true"></i> <span class="nav-label">Récap Participants</span></a>
                    </li>                    
                </ul>
            </li>
            <?php } ?>
        </ul>

    </div>
</nav>