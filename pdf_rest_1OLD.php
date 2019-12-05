<?php
ob_start(); // Always before include, require etc.

require_once dirname ( __FILE__ ) . '/services/AppServiceImpl.class.php'; // Requirements
require_once dirname ( __FILE__ ) . '/html2pdf-4.4.0/html2pdf.class.php';
//
if(!isset($_SESSION)) session_start(); // Start session
//
$appService = AppServiceImpl::getInstance();
$arrayParticipations = $appService->findAllParticipationsByEnterpriseAndPurchasingFair($_SESSION['enterpriseConcerned'], $_SESSION['purchasingFairConcerned']);
?>
<page backtop="20mm" backbottom="20mm" backleft="10mm" backright="10mm"><page_header></page_header><page_footer></page_footer></page>

<table style="width:100%;"><tr><th style="text-align:center;width:100%;">Liste des Magasins / Représentant - Société <?php echo $_SESSION['enterpriseConcerned']->getName(); ?></th></tr></table>

<table class="table table-striped table-hover table-responsive table-bordered" style="width:100%;border: 1px solid #000000;border-collapse: collapse;">
                        <thead>
                            <tr>
                                <th  style="text-align:center;border: 1px solid #000000;border-collapse: collapse;">MAGASINS</th>
                                <?php foreach($arrayParticipations as $key => $value) { echo '<th style="text-align:center;border: 1px solid #000000;border-collapse: collapse;">REPR. '.($key+1).'<br>'.$value->getOneparticipant()->getSurname().'</th>'; } ?>
                            </tr>
                        </thead>
                        <tbody>
                    <?php 
                    $arrayEnterprises = $appService->findAllEnterprisesAsStores();
                    foreach($arrayEnterprises as $key => $value) {
                        $storeConcerned = $value;
                        echo '<tr>';
                        echo '<td style="text-align:center;border: 1px solid #000000;border-collapse: collapse;">'.$value->getName().'</td>';
                        
                        foreach($arrayParticipations as $key => $value) {
                        $checked = ( $appService->findOneAssignmentSpStore($value->getOneParticipant()->getIdParticipant(), $storeConcerned->getIdEnterprise(), $_SESSION['enterpriseConcerned']->getIdEnterprise(), $_SESSION['purchasingFairConcerned']->getIdPurchasingFair()) != null ) ?
                                'X' : '';
                        echo '<td style="text-align:center;border: 1px solid #000000;border-collapse: collapse;">';
                        echo $checked;
                        echo '</td>';
                        }

                        echo '</tr>';
                    }
                    ?>
                        </tbody>

                    </table>
<?php
$content = ob_get_clean();

try {
    $html2pdf = new HTML2PDF('L', 'A4', 'fr'); // Portrait / A4 / French
    $html2pdf -> pdf -> setTitle('recap_saisie_'.date('Y-m-d-H-i')); // Title in pdf viewer
    $html2pdf -> pdf -> setDisplayMode('fullpage'); // If output not D, display the pdf in the entire page
    $html2pdf -> writeHTML($content);
    $html2pdf-> Output('recap_saisie_'.date('Y-m-d-H-i').'.pdf', 'I'); // I = Show in browser, Force Download = D
} catch(HTML2PDF_exception $e) { die($e); }

?>