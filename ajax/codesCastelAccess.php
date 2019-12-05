<?php
require_once dirname ( __FILE__ ) . '/../view/errors.inc.php';
require_once dirname ( __FILE__ ) . '/../services/AppServiceImpl.class.php';

if(!isset($_SESSION)) session_start(); // Start session

$appService = AppServiceImpl::getInstance();

$arrayAllPresentsProviders = $_SESSION['arrayAllPresentsProviders'][$_GET['day']];
//$arrayAllPresentsStores = $_SESSION['arrayAllPresentsStores'][$_GET['day']];
$arrayAllPresentsSpecialGuests = isset($_SESSION['arrayAllPresentsSpecialGuests'][$_GET['day']]) ? $_SESSION['arrayAllPresentsSpecialGuests'][$_GET['day']] : array();

// Total passwords
$arrayPasswordsSixdigits = $appService->sixDigitsGenerator(count($arrayAllPresentsProviders) + count($arrayAllPresentsSpecialGuests));

$dataCSV = '';

// 31 fields - See doc/pf_management_castel_csv.pdf for more information
echo '
<table id="tableCastelAccess" style="display:none">
    <tr>
        <th>Civilite</th>
        <th>Nom</th>
        <th>Prenom</th>
        <th>Societe</th>
        <th>Service</th>
        <th>Matricule</th>
        <th>Telephone</th>
        <th>Tel. mobile</th>
        <th>E-mail</th>
        <th>Nationalite</th>
        <th>Commentaire</th>
        <th>Code secret</th>
        <th>Code sous contrainte</th>
        <th>Photo</th>
        <th>Diffuser la photo</th>
        <th>Liste rouge</th>
        <th>Liste noire</th>
        <th>Champ 1</th>
        <th>Champ 2</th>
        <th>Champ 3</th>
        <th>Champ 4</th>
        <th>Champ 5</th>
        <th>Champ 6</th>
        <th>Visiteur</th>
        <th>Exempt</th>
        <th>Derniere visite</th>
        <th>LDAP</th>
        <th>Code securite Assoc 1</th>
        <th>Badge 0 Assoc 1</th>
        <th>Profil Badge Assoc 1 Crise 0</th>
        <th>Code securite Assoc 2</th>
    </tr>';

$counterSixDigits = 0;

// Stores ** STOP ! ** They do not need code to enter, they have a badge

// Providers
$counterArrayAllPresentsProviders = 0;
$limitArrayAllPresentsProviders = count($arrayAllPresentsProviders);
foreach($arrayAllPresentsProviders as $key => $present) {

    ++$counterArrayAllPresentsProviders;
    $dataCSVComma = ( $counterArrayAllPresentsProviders < $limitArrayAllPresentsProviders ) ? "\n" : '';
    
    echo '
    <tr>
    <td>'.$present->getOneParticipant()->getCivility().'</td>
    <td>'.$present->getOneParticipant()->getSurname().'</td>
    <td>'.$present->getOneParticipant()->getName().'</td>
    <td>'.$present->getOneEnterprise()->getName().'('.$present->getOneEnterprise()->getOneTypeOfProvider()->getNameTypeOfProvider().')</td>
    <td>NULL</td>
    <td>NULL</td>
    <td>NULL</td>
    <td>NULL</td>
    <td>'.$present->getOneParticipant()->getEmail().'</td>
    <td>NULL</td>
    <td>'.$present->getOneParticipant()->getIdParticipant().'</td>
    <td>'.$arrayPasswordsSixdigits[$counterSixDigits].'</td>
    <td>NULL</td>
    <td>NULL</td>
    <td>NULL</td>
    <td>NULL</td>
    <td>NULL</td>
    <td>NULL</td>
    <td>NULL</td>
    <td>NULL</td>
    <td>NULL</td>
    <td>NULL</td>
    <td>NULL</td>
    <td>NULL</td>
    <td>NULL</td>
    <td>NULL</td>
    <td>NULL</td>
    <td>NULL</td>
    <td>NULL</td>
    <td>NULL</td>
    <td>NULL</td>
    </tr>';
        
    $dataCSV .= 
    $present->getOneParticipant()->getCivility().';'.
    $present->getOneParticipant()->getSurname().';'.
    $present->getOneParticipant()->getName().';'.
    $present->getOneEnterprise()->getName().'('.$present->getOneEnterprise()->getOneTypeOfProvider()->getNameTypeOfProvider().')'.';'.
    'NULL'.';'.
    'NULL'.';'.
    'NULL'.';'.
    'NULL'.';'.
    $present->getOneParticipant()->getEmail().';'.
    'NULL'.';'.
    'PA_'.$present->getOneParticipant()->getIdParticipant().';'.
    $arrayPasswordsSixdigits[$counterSixDigits].';'.
    'NULL'.';'.
    'NULL'.';'.
    'NULL'.';'.
    'NULL'.';'.
    'NULL'.';'.
    'NULL'.';'.
    'NULL'.';'.
    'NULL'.';'.
    'NULL'.';'.
    'NULL'.';'.
    'NULL'.';'.
    'NULL'.';'.
    'NULL'.';'.
    'NULL'.';'.
    'NULL'.';'.
    'NULL'.';'.
    'NULL'.';'.
    'NULL'.';'.
    'NULL'.$dataCSVComma;
    
    $counterSixDigits++;
}

// SpecialGuests
$counterArrayAllPresentsSpecialGuests = 0;
$limitArrayAllPresentsSpecialGuests = count($arrayAllPresentsSpecialGuests);
foreach($arrayAllPresentsSpecialGuests as $key => $present) {
    
    ++$counterArrayAllPresentsSpecialGuests;
    $dataCSVCommaBefore = ( $counterArrayAllPresentsProviders > 0 && $counterArrayAllPresentsSpecialGuests == 1) ? "\n" : '';
    $dataCSVComma = ( $counterArrayAllPresentsSpecialGuests < $limitArrayAllPresentsSpecialGuests ) ? "\n" : '';

    echo '
    <tr>
    <td>'.$present->getCivility().'</td>
    <td>'.$present->getSurname().'</td>
    <td>'.$present->getName().'</td>
    <td>'.$present->getOneEnterprise()->getName().'('.$present->getOneEnterprise()->getOneTypeOfProvider()->getNameTypeOfProvider().')</td>
    <td>NULL</td>
    <td>NULL</td>
    <td>NULL</td>
    <td>NULL</td>
    <td>'.'NC'.'</td>
    <td>NULL</td>
    <td>'.$present->getIdSpecialGuest().'</td>
    <td>'.$arrayPasswordsSixdigits[$counterSixDigits].'</td>
    <td>NULL</td>
    <td>NULL</td>
    <td>NULL</td>
    <td>NULL</td>
    <td>NULL</td>
    <td>NULL</td>
    <td>NULL</td>
    <td>NULL</td>
    <td>NULL</td>
    <td>NULL</td>
    <td>NULL</td>
    <td>NULL</td>
    <td>NULL</td>
    <td>NULL</td>
    <td>NULL</td>
    <td>NULL</td>
    <td>NULL</td>
    <td>NULL</td>
    <td>NULL</td>
    </tr>';
    
    $dataCSV .= $dataCSVCommaBefore;
    
    $dataCSV .= 
    $present->getCivility().';'.
    $present->getSurname().';'.
    $present->getName().';'.
    $present->getOneEnterprise()->getName().'('.$present->getOneEnterprise()->getOneTypeOfProvider()->getNameTypeOfProvider().')'.';'.
    'NULL'.';'.
    'NULL'.';'.
    'NULL'.';'.
    'NULL'.';'.
    'NC'.';'.
    'NULL'.';'.
    'SG_'.$present->getIdSpecialGuest().';'.
    $arrayPasswordsSixdigits[$counterSixDigits].';'.
    'NULL'.';'.
    'NULL'.';'.
    'NULL'.';'.
    'NULL'.';'.
    'NULL'.';'.
    'NULL'.';'.
    'NULL'.';'.
    'NULL'.';'.
    'NULL'.';'.
    'NULL'.';'.
    'NULL'.';'.
    'NULL'.';'.
    'NULL'.';'.
    'NULL'.';'.
    'NULL'.';'.
    'NULL'.';'.
    'NULL'.';'.
    'NULL'.';'.
    'NULL'.$dataCSVComma;
    
    $counterSixDigits++;
}

// SpecialGuests
echo '</table>';

$bytesWritten = file_put_contents(dirname ( __FILE__ ) . '/../castel/codes_pf_'.$_SESSION['purchasingFairConcerned']->getIdPurchasingFair().'_'.$_GET['day'].'.csv', $dataCSV); // Emptying the log file

?>

<!-- Mainly scripts -->
<script src="./../js/jquery-3.1.1.min.js"></script>
<script src="./../js/bootstrap.min.js"></script>
<script src="./../js/plugins/metisMenu/jquery.metisMenu.js"></script>
<script src="./../js/plugins/slimscroll/jquery.slimscroll.min.js"></script>

<!-- table2csv -->
<script src="./../js/plugins/table2csv/table2csv.js"></script>

<!-- Custom script -->
<script>
// CSV Export for CASTEL ACCESS
//function exportCastelAccess() {
//    $("#tableCastelAccess").show();
//    $("#tableCastelAccess").table2csv('output', {appendTo: '#out'});
//    $('#tableCastelAccess').table2csv({
//        filename: 'castel_access_<?php //echo $_GET['day']; ?>.csv',
//        separator: ';', // default ','
//        newline: '\n',
//        quoteFields: false, // default true
//        excludeColumns: '', // class Name
//        excludeRows: '' // // class Name
//    });
//    $("#tableCastelAccess").hide();
//}
//
//exportCastelAccess();
//window.close();
</script>
