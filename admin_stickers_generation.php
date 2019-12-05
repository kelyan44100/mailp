<?php
ob_start(); // Always before include, require etc.

require_once dirname ( __FILE__ ) . '/services/AppServiceImpl.class.php';

if(!isset($_SESSION)) session_start(); // Start session

$appService = AppServiceImpl::getInstance();

// Save PNG QR Codes to server
// SRC : http://phpqrcode.sourceforge.net/examples/index.php?example=005 & http://phpqrcode.sourceforge.net/examples/index.php?example=026
require_once dirname ( __FILE__ ) . '/phpqrcode/qrlib.php'; // Requirements

$tempDir = dirname ( __FILE__ ) . '/phpqrcode/temp/'; // Where the QR Code will be saved

// https://www.codexworld.com/delete-all-files-from-folder-using-php/
// http://php.net/manual/fr/function.glob.php
// https://stackoverflow.com/questions/4594180/deleting-all-files-from-a-folder-using-php
// http://php.net/manual/fr/function.array-map.php
//$filesToDelete = glob(dirname ( __FILE__ ) . '/phpqrcode/temp/*'); //Get all file names
//foreach($files as $file){ if(is_file($file)) { unlink($file); } } // Delete file - Do not work with subdirectories
array_map( 'unlink', glob(dirname ( __FILE__ ) . '/phpqrcode/temp/*') ); // Delete files - Do not work with subdirectories

if($_SESSION['enterpriseConcerned']->getOneprofile()->getName() == "Magasin") {
    
    $arrayParticipations = $appService->findAllParticipationsByEnterpriseAndPurchasingFair($_SESSION['enterpriseConcerned'], $_SESSION['purchasingFairConcerned']);
    
    foreach($arrayParticipations as $value) {
        $codeContents  = $_SESSION['purchasingFairConcerned']->getIdPurchasingFair().'|'.$_SESSION['enterpriseConcerned']->getIdEnterprise().'|'.$value->getOneParticipant()->getIdParticipant(). '|'.$value->getLunch();

        // Generate filename (Purchasing Fair ID & Enterprise ID & and User ID)
        $fileName = 'PF_'.$_SESSION['purchasingFairConcerned']->getIdPurchasingFair().'_ENT_'.$_SESSION['enterpriseConcerned']->getIdEnterprise().'_PART_'.$value->getOneParticipant()->getIdParticipant().'.png'; 

        $pngAbsoluteFilePath = $tempDir.$fileName; // Absolute Path

        // echo 'Server PNG File: '.$pngAbsoluteFilePath; 

        QRcode::png($codeContents, $pngAbsoluteFilePath, QR_ECLEVEL_L, 3); // Generating
    }
    
    // PDF Generation
    require_once dirname ( __FILE__ ) . '/html2pdf-4.4.0/html2pdf.class.php'; // Requirements

    $tempDir = './phpqrcode/temp/'; // Where the QR Code has been saved

    $content = '<page backtop="20mm" backbottom="20mm" backleft="10mm" backright="10mm"><page_header></page_header><page_footer></page_footer></page>'; // For HTML2PDF

    $content .= '<table style="width:100%;"><tr><th style="text-align:center;width:100%"><img src="./img/logo_eleclerc_scaouest.png" style="width:378px;height:46px;"></th></tr></table>'; // LOGO E.LEclerc

    $content .= '<h5 style="text-align:center">'.$_SESSION['enterpriseConcerned']->getOneProfile()->getName().' : '.$_SESSION['enterpriseConcerned']->getName().'</h5>';

    $content .= '<h3 style="text-align:center">'.$_SESSION['purchasingFairConcerned']->getNamePurchasingFair().' - '.count($arrayParticipations).' étiquette(s)</h3>';

    foreach($arrayParticipations as $value) { // FOR EACH Participants

        $fileName = 'PF_'.$_SESSION['purchasingFairConcerned']->getIdPurchasingFair().'_ENT_'.$_SESSION['enterpriseConcerned']->getIdEnterprise().'_PART_'.$value->getOneParticipant()->getIdParticipant().'.png'; // Name of QR Code File

        // Insert into PDF File
        $content .= '
        <table style="border-collapse:collapse;border:1px solid #000000;width:100%">
            <thead>
                <tr>
                    <td style="border:1px solid black;text-align:center;width:50%">QR CODE</td>
                    <td style="border:1px solid black;text-align:center;width:50%">PARTICIPANT</td>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td rowspan="3" style="border:1px solid black;vertical-align:middle;text-align:center;width:50%"><img src="'.$tempDir.$fileName.'" height="100" width="100" ></td>
                    <td style="border:1px solid black;text-align:center;width:50%">'.$_SESSION['enterpriseConcerned']->getName().'</td>
                </tr>
                <tr>
                    <td style="border:1px solid black;text-align:center;width:50%">'.$value->getOneParticipant()->getSurname().' '.$value->getOneParticipant()->getName().'</td>
                </tr>
                <tr>
                    <td style="border:1px solid black;text-align:center;width:50%">Repas '.( ($value->getLunch()) ? 'OUI' : 'NON').'</td>
                </tr>

            </tbody>
        </table>';
    }
}
if($_SESSION['enterpriseConcerned']->getOneprofile()->getName() == "Fournisseur") {
    
    $arrayASS = $appService->findOneAssignmentSpStoreBis($_SESSION['enterpriseConcerned']->getIdEnterprise(), $_SESSION['purchasingFairConcerned']->getIdPurchasingFair()); // (Provider, Purchasing Fair)
    
    $counter = 0;
    $limit = count($arrayASS);
    // To prevent duplicates
    $arraySalespersonsIds = array();
    $arraySalespersonsPrinted = array();
    if( $limit ) {
        foreach($arrayASS as $ass) {
            if( !in_array($ass->getOneParticipant()->getIdParticipant(), $arraySalespersonsIds) ) {
                $counter++;
                $arraySalespersonsIds[] = $ass->getOneParticipant()->getIdParticipant();
                $arraySalespersonsPrinted[] = $ass->getOneParticipant();
            }
        }
    }
    
    // For Salespersons without special guest
    foreach($arraySalespersonsPrinted as $value) {
        $codeContents  = $_SESSION['purchasingFairConcerned']->getIdPurchasingFair().'|'.$_SESSION['enterpriseConcerned']->getIdEnterprise().'|'.$value->getIdParticipant(). '|'.(0);

        // Generate filename (Purchasing Fair ID & Enterprise ID & and User ID)
        $fileName = 'PF_'.$_SESSION['purchasingFairConcerned']->getIdPurchasingFair().'_ENT_'.$_SESSION['enterpriseConcerned']->getIdEnterprise().'_PART_'.$value->getIdParticipant().'.png'; 

        $pngAbsoluteFilePath = $tempDir.$fileName; // Absolute Path

        // echo 'Server PNG File: '.$pngAbsoluteFilePath; 

        QRcode::png($codeContents, $pngAbsoluteFilePath, QR_ECLEVEL_L, 3); // Generating
    }
    
    // For special guests - added 22.05.2018
    $arraySpecialGuests = $appService->findAllSpecialGuestForOneEnterpriseAndPf(
            $_SESSION['enterpriseConcerned']->getIdEnterprise(), 
            $_SESSION['purchasingFairConcerned']->getIdPurchasingFair());
    
    foreach($arraySpecialGuests as $specialGuest) {
        $codeContents  = $_SESSION['purchasingFairConcerned']->getIdPurchasingFair().'|'.$_SESSION['enterpriseConcerned']->getIdEnterprise().'|SG'.$specialGuest->getIdSpecialGuest(). '|'.(0);

        // Generate filename (Purchasing Fair ID & Enterprise ID & and User ID)
        $fileName = 'PF_'.$_SESSION['purchasingFairConcerned']->getIdPurchasingFair().'_ENT_'.$_SESSION['enterpriseConcerned']->getIdEnterprise().'_PART_SG_'.$specialGuest->getIdSpecialGuest().'.png'; 

        $pngAbsoluteFilePath = $tempDir.$fileName; // Absolute Path

        // echo 'Server PNG File: '.$pngAbsoluteFilePath; 

        QRcode::png($codeContents, $pngAbsoluteFilePath, QR_ECLEVEL_L, 3); // Generating
    }
    
    // PDF Generation
    require_once dirname ( __FILE__ ) . '/html2pdf-4.4.0/html2pdf.class.php'; // Requirements

    $tempDir = './phpqrcode/temp/'; // Where the QR Code has been saved

    $content = '<page backtop="20mm" backbottom="20mm" backleft="10mm" backright="10mm"><page_header></page_header><page_footer></page_footer></page>'; // For HTML2PDF

    $content .= '<table style="width:100%;"><tr><th style="text-align:center;width:100%"><img src="./img/logo_eleclerc_scaouest.png" style="width:378px;height:46px;"></th></tr></table>'; // LOGO E.LEclerc

    $content .= '<h5 style="text-align:center">'.$_SESSION['enterpriseConcerned']->getOneProfile()->getName().' : '.$_SESSION['enterpriseConcerned']->getName().'</h5>';

    $content .= '<h3 style="text-align:center">'.$_SESSION['purchasingFairConcerned']->getNamePurchasingFair().' - '.(count($arraySalespersonsPrinted) + count($arraySpecialGuests)).' étiquette(s)</h3>';

    // For each Participant without special guests
    foreach($arraySalespersonsPrinted as $value) {

        $fileName = 'PF_'.$_SESSION['purchasingFairConcerned']->getIdPurchasingFair().'_ENT_'.$_SESSION['enterpriseConcerned']->getIdEnterprise().'_PART_'.$value->getIdParticipant().'.png'; // Name of QR Code File

        // Insert into PDF File
        $content .= '
        <table style="border-collapse:collapse;border:1px solid #000000;width:100%">
            <thead>
                <tr>
                    <td style="border:1px solid black;text-align:center;width:50%">QR CODE</td>
                    <td style="border:1px solid black;text-align:center;width:50%">PARTICIPANT</td>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td rowspan="3" style="border:1px solid black;vertical-align:middle;text-align:center;width:50%"><img src="'.$tempDir.$fileName.'" height="100" width="100" ></td>
                    <td style="border:1px solid black;text-align:center;width:50%">'.$_SESSION['enterpriseConcerned']->getName().'</td>
                </tr>
                <tr>
                    <td style="border:1px solid black;text-align:center;width:50%">'.$value->getSurname().' '.$value->getName().'</td>
                </tr>
                <tr>
                    <td style="border:1px solid black;text-align:center;width:50%">Repas '.( ( 0 != 0) ? 'OUI' : 'NON').'</td>
                </tr>

            </tbody>
        </table>';
    }
    
    // For special guests - added 22.05.2018
    foreach($arraySpecialGuests as $specialGuest) {

        $fileName = 'PF_'.$_SESSION['purchasingFairConcerned']->getIdPurchasingFair().'_ENT_'.$_SESSION['enterpriseConcerned']->getIdEnterprise().'_PART_SG_'.$specialGuest->getIdSpecialGuest().'.png'; // Name of QR Code File

        // Insert into PDF File
        $content .= '
        <table style="border-collapse:collapse;border:1px solid #000000;width:100%">
            <thead>
                <tr>
                    <td style="border:1px solid black;text-align:center;width:50%">QR CODE</td>
                    <td style="border:1px solid black;text-align:center;width:50%;color:#ff0000;">PARTICIPANT - INVITÉ EXCEPT.</td>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td rowspan="3" style="border:1px solid black;vertical-align:middle;text-align:center;width:50%"><img src="'.$tempDir.$fileName.'" height="100" width="100" ></td>
                    <td style="border:1px solid black;text-align:center;width:50%">'.$_SESSION['enterpriseConcerned']->getName().'</td>
                </tr>
                <tr>
                    <td style="border:1px solid black;text-align:center;width:50%">'.$specialGuest->getSurname().' '.$specialGuest->getName().'</td>
                </tr>
                <tr>
                    <td style="border:1px solid black;text-align:center;width:50%">Repas '.( ( 0 != 0) ? 'OUI' : 'NON').'</td>
                </tr>

            </tbody>
        </table>';
    }
}

echo $content;

$content = ob_get_clean();

try {
    $html2pdf = new HTML2PDF('P', 'A4', 'fr'); // Portrait / A4 / French
    $html2pdf -> pdf -> setTitle('stickers_generation_'.date('YmdHis')); // Title in pdf viewer
    $html2pdf -> pdf -> setDisplayMode('fullpage'); // If output not D, display the pdf in the entire page
    $html2pdf -> writeHTML($content);
    $html2pdf-> Output('stickers_generation_'.date('YmdHis').'.pdf', 'I'); // I = Show in browser, Force Download = D
} catch(HTML2PDF_exception $e) { die($e); }

   
?>