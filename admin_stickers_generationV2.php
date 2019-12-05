<?php
ob_start();

require_once dirname ( __FILE__ ) . '/services/AppServiceImpl.class.php';

if(!isset($_SESSION)) session_start(); // Start session

$arrayAllPresentsProviders = $_SESSION['arrayAllPresentsProviders'][$_GET['day']];
$arrayAllPresentsStores = $_SESSION['arrayAllPresentsStores'][$_GET['day']];
$arrayAllPresentsSpecialGuests = isset($_SESSION['arrayAllPresentsSpecialGuests'][$_GET['day']]) ? $_SESSION['arrayAllPresentsSpecialGuests'][$_GET['day']] : array();
//var_dump($arrayAllPresentsStores);
$typeEnterprise = $_GET['typeEnterprise'];

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


if($typeEnterprise == 'store') {
    
    // We use $arrayAllPresentsStores
    
    if(empty($arrayAllPresentsStores)) { echo '<div class="text-center text-danger">Aucun Magasin présent.</div>'; }
    
    foreach($arrayAllPresentsStores as $present) {
        $codeContents  = $_SESSION['purchasingFairConcerned']->getIdPurchasingFair().'|'.$present->getOneEnterprise()->getIdEnterprise().'|'.$present->getOneParticipant()->getIdParticipant(). '|'.(1);

        // Generate filename (Purchasing Fair ID & Enterprise ID & and User ID)
        $fileName = 'PF_'.$_SESSION['purchasingFairConcerned']->getIdPurchasingFair().'_ENT_'.$present->getOneEnterprise()->getIdEnterprise().'_PART_'.$present->getOneParticipant()->getIdParticipant().'.png'; 

        $pngAbsoluteFilePath = $tempDir.$fileName; // Absolute Path

        // echo 'Server PNG File: '.$pngAbsoluteFilePath; 

        QRcode::png($codeContents, $pngAbsoluteFilePath, QR_ECLEVEL_L, 6); // Generating
    }
    
    // PDF Generation
    require_once dirname ( __FILE__ ) . '/html2pdf-4.4.0/html2pdf.class.php'; // Requirements

    $tempDir = './phpqrcode/temp/'; // Where the QR Code has been saved

    $content = '';
    
    $counterPresents = 0;
    
    foreach($arrayAllPresentsStores as $present) { // FOR EACH Participants

        $fileName = 'PF_'.$_SESSION['purchasingFairConcerned']->getIdPurchasingFair().'_ENT_'.$present->getOneEnterprise()->getIdEnterprise().'_PART_'.$present->getOneParticipant()->getIdParticipant().'.png'; // Name of QR Code File

        $pbreak = ($counterPresents > 0 && $counterPresents % 10 == 0) ? 'pbreak' : '';

        if($counterPresents % 2 == 0) { $content .= '<table class="'.$pbreak.'" style="border-collapse:collapse;width:180mm;height:54mm;table-layout:fixed;"><tr>'; }
        
        // Insert into PDF File
        $content .= '
        <td><table style="border:1px solid #000000;border-collapse:collapse;width:85mm;table-layout:fixed;height:54mm">
                <tr>
                    <td rowspan="2" style="vertical-align:middle;text-align:center;width:50%"><img src="'.$tempDir.$fileName.'" height="174" width="174" ></td>
                    <td style="text-align:center;width:50%">'.$present->getOneEnterprise()->getName().'</td>
                </tr>
                <tr>
                    <td style="text-align:center;width:50%">'.$present->getOneParticipant()->getSurname().' '.$present->getOneParticipant()->getName().'</td>
                </tr>

        </table></td>';
        
        
        ++$counterPresents;
        
        if($counterPresents % 2 != 0) { $content .= '<td style="width:10mm"></td>'; }

        
        if($counterPresents % 2 == 0) { $content .= '</tr></table>'; }
        

    }
    
    if($counterPresents == count($arrayAllPresentsStores) && $counterPresents % 2 != 0) { $content .= '</table>'; }
}

if($typeEnterprise == 'provider') {
    
    // We use $arrayAllPresentsProviders
    
    if(empty($arrayAllPresentsProviders)) { echo '<div class="text-center text-danger">Aucun Fournisseur présent.</div>'; }
    
    foreach($arrayAllPresentsProviders as $present) {
        $codeContents  = $_SESSION['purchasingFairConcerned']->getIdPurchasingFair().'|'.$present->getOneEnterprise()->getIdEnterprise().'|'.$present->getOneParticipant()->getIdParticipant(). '|'.(1);

        // Generate filename (Purchasing Fair ID & Enterprise ID & and User ID)
        $fileName = 'PF_'.$_SESSION['purchasingFairConcerned']->getIdPurchasingFair().'_ENT_'.$present->getOneEnterprise()->getIdEnterprise().'_PART_'.$present->getOneParticipant()->getIdParticipant().'.png'; 

        $pngAbsoluteFilePath = $tempDir.$fileName; // Absolute Path

        // echo 'Server PNG File: '.$pngAbsoluteFilePath; 

        QRcode::png($codeContents, $pngAbsoluteFilePath, QR_ECLEVEL_L, 6); // Generating
    }
    
    // PDF Generation
    require_once dirname ( __FILE__ ) . '/html2pdf-4.4.0/html2pdf.class.php'; // Requirements

    $tempDir = './phpqrcode/temp/'; // Where the QR Code has been saved

    $content = '';
    
    $counterPresents = 0;
    
    foreach($arrayAllPresentsProviders as $present) { // FOR EACH Participants

        $fileName = 'PF_'.$_SESSION['purchasingFairConcerned']->getIdPurchasingFair().'_ENT_'.$present->getOneEnterprise()->getIdEnterprise().'_PART_'.$present->getOneParticipant()->getIdParticipant().'.png'; // Name of QR Code File

        $pbreak = ($counterPresents > 0 && $counterPresents % 10 == 0) ? 'pbreak' : '';

        if($counterPresents % 2 == 0) { $content .= '<table class="'.$pbreak.'" style="border-collapse:collapse;width:180mm;height:54mm;table-layout:fixed;"><tr>'; }
        
        // Insert into PDF File
        $content .= '
        <td><table style="border:1px solid #000000;border-collapse:collapse;width:85mm;table-layout:fixed;height:54mm">
                <tr>
                    <td rowspan="2" style="vertical-align:middle;text-align:center;width:50%"><img src="'.$tempDir.$fileName.'" height="174" width="174" ></td>
                    <td style="text-align:center;width:50%">'.$present->getOneEnterprise()->getName().'</td>
                </tr>
                <tr>
                    <td style="text-align:center;width:50%">'.$present->getOneParticipant()->getSurname().' '.$present->getOneParticipant()->getName().'</td>
                </tr>

        </table></td>';
        
        
        ++$counterPresents;
        
        if($counterPresents % 2 != 0) { $content .= '<td style="width:10mm"></td>'; }

        
        if($counterPresents % 2 == 0) { $content .= '</tr></table>'; }
        

    }
    
    if($counterPresents == count($arrayAllPresentsProviders) && $counterPresents % 2 != 0) { $content .= '</table>'; }
}

if($typeEnterprise == 'specialGuest') {
    
    // We use $arrayAllPresentsSpecialGuests
    
    if(empty($arrayAllPresentsSpecialGuests)) { echo '<div class="text-center text-danger">Aucun Invité exceptionnel présent.</div>'; }
    
    foreach($arrayAllPresentsSpecialGuests as $present) {
        $codeContents  = $_SESSION['purchasingFairConcerned']->getIdPurchasingFair().'|'.$present->getOneEnterprise()->getIdEnterprise().'|SG'.$present->getIdSpecialGuest(). '|'.(1);

        // Generate filename (Purchasing Fair ID & Enterprise ID & and User ID)
        $fileName = 'PF_'.$_SESSION['purchasingFairConcerned']->getIdPurchasingFair().'_ENT_'.$present->getOneEnterprise()->getIdEnterprise().'_PART_SG'.$present->getIdSpecialGuest().'.png'; 

        $pngAbsoluteFilePath = $tempDir.$fileName; // Absolute Path

        // echo 'Server PNG File: '.$pngAbsoluteFilePath; 

        QRcode::png($codeContents, $pngAbsoluteFilePath, QR_ECLEVEL_L, 6); // Generating
    }
    
    // PDF Generation
    require_once dirname ( __FILE__ ) . '/html2pdf-4.4.0/html2pdf.class.php'; // Requirements

    $tempDir = './phpqrcode/temp/'; // Where the QR Code has been saved

    $content = '';
    
    $counterPresents = 0;
    
    foreach($arrayAllPresentsSpecialGuests as $present) { // FOR EACH Participants

        $fileName = 'PF_'.$_SESSION['purchasingFairConcerned']->getIdPurchasingFair().'_ENT_'.$present->getOneEnterprise()->getIdEnterprise().'_PART_SG'.$present->getIdSpecialGuest().'.png'; // Name of QR Code File

        $pbreak = ($counterPresents > 0 && $counterPresents % 10 == 0) ? 'pbreak' : '';

        if($counterPresents % 2 == 0) { $content .= '<table class="'.$pbreak.'" style="border-collapse:collapse;width:180mm;height:54mm;table-layout:fixed;"><tr>'; }
        
        // Insert into PDF File
        $content .= '
        <td><table style="border:1px solid #000000;border-collapse:collapse;width:85mm;table-layout:fixed;height:54mm">
                <tr>
                    <td rowspan="2" style="vertical-align:middle;text-align:center;width:50%"><img src="'.$tempDir.$fileName.'" height="174" width="174" ></td>
                    <td style="text-align:center;width:50%">*'.$present->getOneEnterprise()->getName().'</td>
                </tr>
                <tr>
                    <td style="text-align:center;width:50%">'.$present->getSurname().' '.$present->getName().'</td>
                </tr>

        </table></td>';
        
        
        ++$counterPresents;
        
        if($counterPresents % 2 != 0) { $content .= '<td style="width:10mm"></td>'; }

        
        if($counterPresents % 2 == 0) { $content .= '</tr></table>'; }
        

    }
    
    if($counterPresents == count($arrayAllPresentsSpecialGuests) && $counterPresents % 2 != 0) { $content .= '</table>'; }
}

echo $content;

$data = ob_get_contents();

ob_end_clean();

echo $data;

?>
</div>

<!-- Mainly scripts -->
<script src="./js/jquery-3.1.1.min.js"></script>
<script src="./js/bootstrap.min.js"></script>
<script src="./js/plugins/metisMenu/jquery.metisMenu.js"></script>
<script src="./js/plugins/slimscroll/jquery.slimscroll.min.js"></script>

<!-- Custom script -->
<script>
$(function(){
    $('#loaderStickers').hide();
    $('#stickers').show();
    
    // Print invoice on user click
    $('#btnPrintInvoice').click(function(){ window.print(); });
    $('#exitButton').click(function(){ window.close(); });
});
</script>