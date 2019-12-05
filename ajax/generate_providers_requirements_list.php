<?php
require_once dirname ( __FILE__ ) . '/../services/AppServiceImpl.class.php';
$appService = AppServiceImpl::getInstance();

if( isset($_POST['providers']) && !empty($_POST['providers']) ) {

    $arrayProvidersSelected = json_decode($_POST['providers'], true); // When TRUE, returned objects will be converted into associative arrays.
    
    if(!empty($arrayProvidersSelected) ) {
        $dataSuccess  = '<form class="col-lg-12 form-inline" role="form" action="#" method="POST">';
        $dataSuccess .= '<table class="table table-hover">';
        $dataSuccess .= '<thead>';
        $dataSuccess .= '<tr>';
        $dataSuccess .= '<th class="text-center"><i class="fa fa-hashtag" aria-hidden="true"></i></th>';
        $dataSuccess .= '<th class="text-center"><i class="fa fa-id-card-o" aria-hidden="true"></i> Fournisseur (Type)</th>';
        $dataSuccess .= '<th class="text-center"><i class="fa fa-clock-o" aria-hidden="true"></i> Besoins en heures</th>';
        $dataSuccess .= '</tr>';
        $dataSuccess .= '</thead>';
        $dataSuccess .= '<tbody>';

        foreach ($arrayProvidersSelected as $key => $value) {
            $dataSuccess .= '<tr>';
            $dataSuccess .= '<td class="text-center">'.($key+1).'</td>';
            $dataSuccess .= '<td class="text-center">'.$value['1'].'</td>';
            $dataSuccess .= '<td class="text-center">';
            $dataSuccess .= '<label for="provider_'.$value['0'].'">Heure(s) : </label><input type="number" name="provider_'.$value['0'].'" value="1" min="1" max="11" class="form-group form-control" required="">';
            $dataSuccess .= '&nbsp;<label for="provider_'.$value['0'].'_bis">Minute(s) : </label><input type="number" name="provider_'.$value['0'].'_bis" value="00" min="0" max="30" step="30" class="form-group form-control" required="">';
            $dataSuccess .= '</td>';
            $dataSuccess .= '</tr>';
        }

        $dataSuccess .= '</tbody>';
        $dataSuccess .= '</table>';
        $dataSuccess .= '<button type="submit" id="submitRequirements" class="btn btn-primary block full-width m-t m-b hvr-icon-bounce"> Valider besoins en heures</button>';
        $dataSuccess .= '</form>';

        echo $dataSuccess;
    }
    else {
        $dataWarning  = '<div class="text-center text-danger">';
        $dataWarning .= '<span>';
        $dataWarning .= '<i class="fa fa-exclamation-triangle" aria-hidden="true"></i> Vous n\'avez choisi aucun Fournisseur dans la liste !';
        $dataWarning .= '</span>';
        $dataWarning .= '</div>';
        
        echo $dataWarning;
    }
}
?>