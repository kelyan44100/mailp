<?php
ob_start(); // Always before include, require etc.

require_once dirname ( __FILE__ ) . '/services/AppServiceImpl.class.php'; // Requirements
require_once dirname ( __FILE__ ) . '/html2pdf-4.4.0/html2pdf.class.php';

if(!isset($_SESSION)) session_start(); // Start session

$appService = AppServiceImpl::getInstance();

date_default_timezone_set('Europe/Paris');

$pfConcerned         = $_SESSION['purchasingFairConcerned'];
$enterpriseConcerned = $_SESSION['enterpriseConcerned'];

// Added 27.08.2018 - Taking others Pf into account
$isOtherPf = ( $_SESSION['purchasingFairConcerned']->getOneTypeOfPf()->getNameTypeOfPf() === 'Autre' ) ? true : false;

$arrayRequirementsAlreadyRegistered     = $appService->findRequirementFilteredDuo($enterpriseConcerned, $pfConcerned);
$arrayUnavailabilitiesAlreadyRegistered = $appService->findEnterpriseUnavailabilities($enterpriseConcerned, $pfConcerned);
$arrayParticipationsAlreadyRegistered   = $appService->findAllParticipationsByEnterpriseAndPurchasingFair($enterpriseConcerned, $pfConcerned);

$content = '<page backtop="20mm" backbottom="20mm" backleft="10mm" backright="10mm"><page_header></page_header><page_footer></page_footer></page>';
$content .= '<table style="width:100%;"><tr><th style="text-align:center;width:100%;"><img src="./img/logo_eleclerc_scaouest.png" style="width:340px;height:41px;"></th></tr></table>';
$content .= '<hr style="color:#ed8b18;">';
$content .= '<h3 style="color:#ed8b18;text-align:center;width:100%;margin:5px 0px 5px 0px;">'.strtoupper($enterpriseConcerned->getName()).'</h3>';
$content .= '<h5 style="color:#ed8b18;text-align:center;width:100%;margin:5px 0px 5px 0px;">RÉCAPITULATIF INSCRIPTION AU '.date('d').'/'.date('m').'/'.date('Y').' '.date('H').':'.date('i').'</h5>';
$content .= '<hr style="color:#ed8b18;">';
$content .= '<h2 style="color:#0b70b5;text-align:left;width:100%;margin-bottom:0;">> SALON D\'ACHATS</h2>';
$content .= '<hr style="color:#0b70b5;border-style:dashed;">';
$content .= '<span>- Numéro du salon (identifiant unique de référence) : '.$pfConcerned->getIdPurchasingFair().'</span><br>';
$content .= '<span>- Type de salon : '.$pfConcerned->getOneTypeOfPf()->getNameTypeOfPf().'</span><br>';
$content .= '<span>- Nom du salon : '.$pfConcerned->getNamePurchasingFair().'</span><br>';
$content .= '<span>- Dates de début et de fin : '.$appService->myFrenchDate($pfConcerned->getStartDatetime()).' -> '.$appService->myFrenchDate($pfConcerned->getEndDatetime()).'</span>';
if(!$isOtherPf) {
    $content .= '<h2 style="color:#0b70b5;text-align:left;width:100%;margin-bottom:0;">> FOURNISSEURS + BESOINS EN HEURES</h2>';
    $content .= '<hr style="color:#0b70b5;border-style:dashed;">';

    $counter1 = 0;
    $limit1 = count($arrayRequirementsAlreadyRegistered);
    if( $limit1 ) {
        foreach($arrayRequirementsAlreadyRegistered as $value) {
            $counter1++;
            $content .= $value->getOneProvider()->getName().'('.$value->getOneProvider()->getOneTypeOfProvider()->getNameTypeOfProvider()[0].') -> '.$value->getNumberOfHours(). " heure(s)";
            $content .= ($counter1 < $limit1) ? '<br>' : '';
        }
    }
    else { $content .= 'Aucun besoin en heures enregistré'; }

    $content .= '<h2 style="color:#0b70b5;text-align:left;width:100%;margin-bottom:0;">> INDISPONIBILITÉS</h2>';
    $content .= '<hr style="color:#0b70b5;border-style:dashed;">';

    $counter2 = 0;
    $limit2 = count($arrayUnavailabilitiesAlreadyRegistered);
    if( $limit2 ) {
        foreach($arrayUnavailabilitiesAlreadyRegistered as $value) {
            $counter2++;
            $content .= $appService->myFrenchDatetime($value->getStartDatetime()).' -> '.$appService->myFrenchDatetime($value->getEndDatetime());
            $content .= ($counter2 < $limit2) ? '<br>' : '';
        }
    }
    else { $content .= 'Aucune indisponibilité enregistrée'; }
}

$content .= '<h2 style="color:#0b70b5;text-align:left;width:100%;margin-bottom:0;">> PARTICIPANTS</h2>';
$content .= '<hr style="color:#0b70b5;border-style:dashed;">';

$counter3 = 0;
$limit3 = count($arrayParticipationsAlreadyRegistered);
if( $limit3 ) {
    foreach($arrayParticipationsAlreadyRegistered as $value) {
        $counter3++;
        $content .= $value->getOneParticipant()->getCivility(). ' '.$value->getOneParticipant()->getSurname().' '.$value->getOneParticipant()->getName().' / '.$value->getOneParticipant()->getEmail();
        $content .= ($counter3 < $limit3) ? '<br>' : '';
    }
}
else { $content .= 'Aucun participant enregistré'; }

echo $content;
$content = ob_get_clean();

try {
    $html2pdf = new HTML2PDF('P', 'A4', 'fr'); // Portrait / A4 / French
    $html2pdf -> pdf -> setTitle('recap_saisie_'.date('Y-m-d-H-i')); // Title in pdf viewer
    $html2pdf -> pdf -> setDisplayMode('fullpage'); // If output not D, display the pdf in the entire page
    $html2pdf -> writeHTML($content);
    $html2pdf-> Output('recap_saisie_'.date('Y-m-d-H-i').'.pdf', 'I'); // I = Show in browser, Force Download = D
} catch(HTML2PDF_exception $e) { die($e); }

ob_end_clean();
?>