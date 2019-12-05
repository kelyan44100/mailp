

            <div class="wrapper wrapper-content">
                
                <div class="row" >
                    
                    <div class="col-lg-offset-3 col-lg-6">
                        <div class="ibox float-e-margins animated fadeInDown">
                            <div class="ibox-title">
                                <h5>Formulaire</h5>
                                <div class="alert alert-warning m-t-md text-center">
                                    <span>
                                        Vous avez saisi <span id="infoNbParticipants" class="badge"></span> participant(s)
                                    </span><br><br>
                                    <span class="checkUncheckAll" onclick="checkAllCheckboxes();">
                                        <i class="fa fa-check-square-o" aria-hidden="true"></i> Tout cocher
                                    </span> | 
                                    <span class="checkUncheckAll" onclick="uncheckAllCheckboxes();">
                                        <i class="fa fa-square-o" aria-hidden="true"></i> Tout décocher
                                    </span>
                                </div>
                            </div>
                            <div class="ibox-content">
                                <div class="row">
                                    <form id="choiceSalespersonsForm" class="col-lg-12" role="form" action="./admin_list_of_stores_by_salesperson" method="POST">
                                    <?php
                                    $apeTest = $appService->findAllAssignmentsParticipantEnterpriseForOneEnterprise($_SESSION['enterpriseConcerned']->getIdEnterprise());

                                    // strcasecmp — Binary safe case-insensitive string comparison
                                    // Returns < 0 if str1 is less than str2; > 0 if str1 is greater than str2, and 0 if they are equal.
                                    function compareStrings($a, $b) { 
                                       $result = strcasecmp($a->getOneParticipant()->getSurname(), $b->getOneParticipant()->getSurname());
                                       // IF equal we compare the name
                                       return (!$result) ? strcasecmp($a->getOneParticipant()->getName(), $b->getOneParticipant()->getName()) : $result; 
                                    }

                                    usort($apeTest, 'compareStrings');

                                    foreach( $apeTest as $value ) {
                                        echo '<div class="i-checks"><label> <input type="checkbox" name="participants[]" value="idParticipant_'.$value->getOneParticipant()->getIdParticipant().'"> <i></i> '.$value->getOneParticipant().' </label></div>';
                                    }
                                    ?>
                                    </form>
                                    <div class="text-center">
                                        <button type="button" id="addNewParticipantButton" name="addNewParticipantButton" class="btn m-t-xs m-b hvr-icon-float-away" data-toggle="modal" data-target="#myModal">Pas dans la liste ?</button>
                                    </div>
                                    <div class="modal inmodal" id="myModal" tabindex="-1" role="dialog" aria-hidden="true">
                                        <div class="modal-dialog">
                                            <div class="modal-content animated fadeIn">
                                                <div class="modal-header">
                                                    <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                                                    <i class="fa fa-id-badge fa-2x"></i>
                                                    <h4 class="modal-title">Nouveau Commercial</h4>
                                                </div>
                                                <div class="modal-body">
                                                    <form id="saveNewParticipantForm" role="form" action="./admin_salesperson_list.php" method="POST">
                                                        <div class="form-group"><label>Civilité</label>
                                                            <select id="newParticipantCivility" name="newParticipantCivility" class="form-control" required="">
                                                                <option value="0">-- Choix civilité --</option>
                                                                <option value="Madame">Madame</option>
                                                                <option value="Monsieur">Monsieur</option>
                                                            </select>
                                                        </div>

                                                        <div class="form-group"><label>Nom</label>
                                                            <input type="text" id="newParticipantSurname" name="newParticipantSurname" maxlength="50" class="form-control" required="">
                                                        </div>

                                                        <div class="form-group"><label>Prénom</label>
                                                            <input type="text" id="newParticipantName" name="newParticipantName" maxlength="50" class="form-control" required="">
                                                        </div>

                                                        <div class="form-group"><label>E-mail</label>
                                                            <input type="email" id="newParticipantEMail" name="newParticipantEMail"  maxlength="100" class="form-control" required="">
                                                        </div>
                                                    </form>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-white" data-dismiss="modal" style="margin:0!important">Fermer</button>
                                                    <button id="saveNewParticipantButton" type="button" class="btn btn-primary">Enregistrer</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>                                    
                                </div>
                                <div class="col-lg-12">&nbsp;</div>
                            </div>
                            <div class="ibox-footer">
                                <button id="nextButton" name="nextButton" type="button" class="btn hvr-icon-forward pull-right">Suivant</button>
                                <button id="previousButton" name="previousButton" type="button" class="btn hvr-icon-back">Précédent</button>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-12 animated fadeInDown">
                    <?php
                    echo '<h5><i class="fa fa-info-circle" aria-hidden="true"></i> Participants déjà inscrits</h5>';
                    $counter = 0;
                    $limit = count($arrayParticipationsAlreadyRegistered);
                    if( $limit ) {
                        foreach($arrayParticipationsAlreadyRegistered as $value) {
                            $counter++;
                            echo $value->getOneParticipant()->getCivility(). ' '.$value->getOneParticipant()->getSurname().' '.$value->getOneParticipant()->getName().' '.$value->getOneParticipant()->getEmail();
                            echo ($counter < $limit) ? '<br>' : '';
                        }
                    }
                    else { echo 'Aucun Participant enregistré'; }
                    ?>
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

    <!-- Custom script -->
    <script>

    
    /* Click event (previousButton and nextButton) */
    $("#previousButton").click(function(){ window.location.assign("./store_unavailabilities_register.php"); });
    $("#nextButton").click(function(){ window.location.assign("./purchasing_fair_list.php"); });
    </script>

</body>

</html>
