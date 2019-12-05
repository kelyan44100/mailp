<?php
require_once dirname ( __FILE__ ) . '/../services/AppServiceImpl.class.php';
$appService = AppServiceImpl::getInstance();

if( isset($_POST['nbProviders']) && !empty($_POST['nbProviders']) ) {

    $nbProviders = json_decode($_POST['nbProviders']);    
    
    if(!empty($nbProviders) ) {
        $dataSuccess = '<hr style="border:0.5px solid #0b70b5" />';
        $dataSuccess  .= '<form class="form-inline col-lg-12 animated zoomIn" role="form" action="#" method="POST">';


        for($n = 0 ; $n < $nbProviders ; $n++) {
            $dataSuccess .= '<div class="form-group" style="width:50%">';
            $dataSuccess .= '<label>Nouveau Fournisseur '.($n+1).' :</label>';
            $dataSuccess .= '&nbsp;<input type="text" name="nameProvider'.($n+1).'"  maxlength="50" class="form-group form-control" required="">';
            $dataSuccess .= '</div>';
            $dataSuccess .= '<div class="form-group" style="width:50%">';
            $dataSuccess .= '<label>Type (T ou C) '.($n+1).' :</label>';
            $dataSuccess .= '&nbsp;<select name="typeOfProvider'.($n+1).'" class="form-group form-control" required="">';
            $arrayTypeOfProvider = $appService->findAllTypeOfProvider();
            foreach($arrayTypeOfProvider as $key => $top) {
                $dataSuccess .= '<option value="'.$top->getIdTypeOfProvider().'">'.$top->getNameTypeOfProvider().'</option>';
            }
            $dataSuccess .= '</select>';
            $dataSuccess .= '</div>';
        }
        
        $dataSuccess .= '<button type="submit" id="submitParticipants" class="btn btn-primary block full-width m-t m-b hvr-icon-bounce"> Valider les fournisseurs</button>';
        $dataSuccess .= '</form>';

        echo $dataSuccess;
    }
    else {
        $dataWarning  = '<div class="text-center text-danger">';
        $dataWarning .= '<span>';
        $dataWarning .= '<i class="fa fa-exclamation-triangle" aria-hidden="true"></i> Aucun Fournisseur à saisir !';
        $dataWarning .= '</span>';
        $dataWarning .= '</div>';
        
        echo $dataWarning;
    }
}
?>