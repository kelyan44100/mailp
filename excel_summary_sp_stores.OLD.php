<?php
require_once dirname ( __FILE__ ) . '/view/errors.inc.php';
require_once dirname ( __FILE__ ) . '/services/AppServiceImpl.class.php';

if(!isset($_SESSION)) session_start(); // Start session

$appService = AppServiceImpl::getInstance();

// 1 - Search all existing stores and participations, storage in arrays
$arrayEnterprisesAsStores = $appService->findAllEnterprisesAsStores();
$arrayParticipations      = $appService->findAllParticipationsByEnterpriseAndPurchasingFair($_SESSION['enterpriseConcerned'], $_SESSION['purchasingFairConcerned']);

$arrayParticipations      = $appService->sortParticipantsBySurnameAndName($arrayParticipations);
                                
// 2 - We create the table
echo '<table class="table2excel" data-tableName="myAmazingTable">';

// 2a - Head
echo '<thead>';
echo '<tr>';
echo '<th>Magasin\Commerciaux</th>';
foreach($arrayParticipations as $key => $value) { echo '<th>REPR. '.($key+1).'<br>'.$value->getOneparticipant()->getSurname().'</th>'; }
echo '</tr>';
echo '</thead>';

// 2b - Body
echo '<tbody>';

// 2c - for each row of the table that concerns a store
foreach($arrayEnterprisesAsStores as $key => $value) {
    
    $storeConcerned = $value;
    
    echo '<tr>';
    
    // 2d - Show the store name
    echo '<td>'.$value->getName().'</td>';

    // 2e - Check if there is an assignment_sp_store
    foreach($arrayParticipations as $key => $value) {
        
    $checked = ( $appService->findOneAssignmentSpStore($value->getOneParticipant()->getIdParticipant(), $storeConcerned->getIdEnterprise(), $_SESSION['enterpriseConcerned']->getIdEnterprise(), $_SESSION['purchasingFairConcerned']->getIdPurchasingFair()) != null ) ?
            'X' : '';
    echo '<td>';
    echo $checked;
    echo '</td>';
    }

    echo '</tr>';
}

echo '</tbody>';
echo '</table>';
?>

<!-- JQuery -->
<script src="js/jquery-3.1.1.min.js"></script>

<!-- table2excel -->
<!-- src: https://github.com/rainabba/jquery-table2excel -->
<script src="js/plugins/table2excel/jquery.table2excel.js"></script>

<!-- Custom script -->
<script>
// Generate the Excel file when the document is ready
$(function() {
	$(".table2excel").table2excel({
		exclude: ".noExl",
		name: "recap_salon_<?php echo $_SESSION['purchasingFairConcerned']->getIdPurchasingFair(); ?>",
		filename: "Recap_salon_<?php echo $_SESSION['purchasingFairConcerned']->getIdPurchasingFair(); ?>_" + new Date().toISOString().replace(/[\-\:\.]/g, ""),
		fileext: ".xls",
		exclude_img: true,
		exclude_links: true,
		exclude_inputs: true
	});
	// Close the current page -- only possible if this page was generated with Javascript code (ok with onclick in view/menu.inc.php)
	window.close();
});
</script>