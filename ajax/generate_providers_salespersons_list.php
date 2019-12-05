<?php
require_once dirname ( __FILE__ ) . '/../services/AppServiceImpl.class.php';
$appService = AppServiceImpl::getInstance();

if( isset($_POST['provider']) && !empty($_POST['provider']) ) {

    // Value sent with Ajax
    $providerSelected = $_POST['provider'];
    
    // Returns all assignments Salesperson/Enterprise without distinction    
    $arrayASPE = $appService->findAllAssignmentsSalespersonEnterpriseForOneEnterprise($providerSelected);
    
    // IF at least one salesperson is found
    if( !empty($arrayASPE) ) {
        
        $dataSuccess  = '<form class="col-lg-4 col-lg-offset-4 animated fadeInDown" role="form" action="./store_unavailabilities_register.php" method="POST">';
        $dataSuccess .= '<table class="table table-hover">';
        $dataSuccess .= '<thead>';
        $dataSuccess .= '<tr>';
        $dataSuccess .= '<th class="text-center"><i class="fa fa-hashtag" aria-hidden="true"></i></th>';
        $dataSuccess .= '<th class="text-center"><i class="fa fa-user-circle-o" aria-hidden="true"></i> Civilité</th>';
        $dataSuccess .= '<th class="text-center"><i class="fa fa-user-circle-o" aria-hidden="true"></i> Nom</th>';
        $dataSuccess .= '<th class="text-center"><i class="fa fa-user-circle-o" aria-hidden="true"></i> Prénom</th>';
        $dataSuccess .= '</tr>';
        $dataSuccess .= '</thead>';
        $dataSuccess .= '<tbody>';

        foreach ($arrayASPE as $key => $value) {
            $dataSuccess .= '<tr>';
            $dataSuccess .= '<td class="text-center">'.($key+1).'</td>';
            $dataSuccess .= '<td class="text-center">'.$value->getOneSalesperson()->getCivility().'</td>';
            $dataSuccess .= '<td class="text-center">'.$value->getOneSalesperson()->getSurname().'</td>';
            $dataSuccess .= '<td class="text-center">'.$value->getOneSalesperson()->getName().'</td>';
            $dataSuccess .= '</tr>';
        }

        $dataSuccess .= '</tbody>';
        $dataSuccess .= '</table>';
        $dataSuccess .= '<button type="submit" id="submitButtonCreateAssignment" name="submitButtonCreateAssignment" class="btn btn-success m-t m-b"><i class="fa fa-plus" aria-hidden="true"></i> Créer</button>';
        $dataSuccess .= '<button type="submit" id="submitButtonUpdateAssignment" name="submitButtonUpdateAssignment" class="btn btn-warning m-t m-b"><i class="fa fa-pencil-square-o" aria-hidden="true"></i> Modifier</button>';
        $dataSuccess .= '<button type="submit" id="submitButtonDeleteAssignment" name="submitButtonDeleteAssignment" class="btn btn-danger m-t m-b"><i class="fa fa-times" aria-hidden="true"></i> Supprimer</button>';
        $dataSuccess .= '</form>';

        echo $dataSuccess;
    }
    else {
        
        $dataWarning  = '<div class="text-center text-danger">';
        $dataWarning .= '<span>';
        $dataWarning .= '<i class="fa fa-exclamation-triangle" aria-hidden="true"></i> Aucun commercial n\'est associé à ce Fournisseur';
        $dataWarning .= '</span>';
        $dataWarning .= '</div>';
        
        echo $dataWarning;
    }

}
?>