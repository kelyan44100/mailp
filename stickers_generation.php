<?php 
require_once __DIR__.'/html2pdf-4.4.0/html2pdf.class.php';
ob_start();
?>
<page backtop="20mm" backbottom="20mm" backleft="10mm" backright="10mm"><page_header></page_header><page_footer></page_footer></page>
<!-- <table style="width: 100%;"><tr><th style="text-align: center; width: 100%"><img src="./images/applogo.png" style="width:100px;height:100px"></th></tr></table> -->
<table class="table table-bordered" style="border-collapse: collapse;border:1px solid black;width:50%">
    <thead>
        <tr>
            <td style="border:1px solid black;text-align:center">QR CODE</td>
            <td style="border:1px solid black;text-align:center">PARTICIPANT</td>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td rowspan="3" style="border:1px solid black;vertical-align:middle;text-align:center"><img src="./img/html_logo.png" height="100" width="100" ></td>
            <td style="border:1px solid black;text-align:center">${NOM_ENTREPRISE}</td>
        </tr>
        <tr>
            <td style="border:1px solid black;text-align:center">${NOM_PRENOM_PARTICIPANT}</td>
        </tr>
        <tr>
            <td style="border:1px solid black;text-align:center">${REPAS : O / N}</td>
        </tr>
        
    </tbody>
</table>
<?php
$content = ob_get_clean();
try {
	$html2pdf = new HTML2PDF('P', 'A4', 'fr'); // Portrait / A4 / French
	$html2pdf -> pdf -> setDisplayMode('fullpage'); // If output not D, display the pdf in the entire page
	$html2pdf -> writeHTML($content);
	$html2pdf-> Output('stickers_generation_'.date('YmdHis').'.pdf', 'I'); // I = Show in browser, Force Download = D
} catch(HTML2PDF_exception $e) { die($e); }
?>