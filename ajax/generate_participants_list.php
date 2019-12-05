<?php
require_once dirname ( __FILE__ ) . '/../services/AppServiceImpl.class.php';
$appService = AppServiceImpl::getInstance();

if( isset($_POST['nbParticipants']) && !empty($_POST['nbParticipants']) ) {

    $nbParticipants = json_decode($_POST['nbParticipants']);    
    
    if(!empty($nbParticipants) ) {
        $dataSuccess  = '<form class="col-lg-12 animated fadeInDown" role="form" action="#" method="POST">';
        $dataSuccess .= '<table class="table table-hover">';
        $dataSuccess .= '<thead>';
        $dataSuccess .= '<tr>';
        $dataSuccess .= '<th class="text-center"><i class="fa fa-hashtag" aria-hidden="true"></i></th>';
        $dataSuccess .= '<th class="text-center"><i class="fa fa-venus-mars" aria-hidden="true"></i> Civilité</th>';
        $dataSuccess .= '<th class="text-center"><i class="fa fa-user" aria-hidden="true"></i> Nom</th>';
        $dataSuccess .= '<th class="text-center"><i class="fa fa-user-o" aria-hidden="true"></i> Prénom</th>';
        $dataSuccess .= '<th class="text-center"><i class="fa fa-at" aria-hidden="true"></i> E-mail</th>';
        $dataSuccess .= '</tr>';
        $dataSuccess .= '</thead>';
        $dataSuccess .= '<tbody>';

        for($n = 0 ; $n < $nbParticipants ; $n++) {
            $dataSuccess .= '<tr>';
            $dataSuccess .= '<td class="text-center">'.($n+1).'</td>';
            $dataSuccess .= '<td><select name="civilityParticipant_'.($n+1).'" class="form-group form-control" required="">';
            $dataSuccess .= '<option value="">-- Choix civilité --</option>';
            $dataSuccess .= '<option value="Madame">Madame</option>';
            $dataSuccess .= '<option value="Monsieur">Monsieur</option>';
            $dataSuccess .= '</select></td>';      
            $dataSuccess .= '<td class="text-center"><input type="text" name="surnameParticipant_'.($n+1).'" maxlength="50" class="form-group form-control" required=""></td>';
            $dataSuccess .= '<td class="text-center"><input type="text" name="nameParticipant_'.($n+1).'" maxlength="50" class="form-group form-control" required=""></td>';
            $dataSuccess .= '<td class="text-center"><input type="email" name="emailParticipant_'.($n+1).'"  maxlength="100" class="form-group form-control" required=""></td>';
            $dataSuccess .= '</tr>';
        }

        $dataSuccess .= '</tbody>';
        $dataSuccess .= '</table>';
        $dataSuccess .= '<button type="submit" id="submitParticipants" class="btn btn-primary block full-width m-t m-b hvr-icon-bounce"> Valider les participants</button>';
        $dataSuccess .= '</form>';

        echo $dataSuccess;
    }
    else {
        $dataWarning  = '<div class="text-center text-danger">';
        $dataWarning .= '<span>';
        $dataWarning .= '<i class="fa fa-exclamation-triangle" aria-hidden="true"></i> Vous n\'avez choisi aucun Participant pour ce salon !';
        $dataWarning .= '</span>';
        $dataWarning .= '</div>';
        
        echo $dataWarning;
    }
}
?>