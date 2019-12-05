<style>table, th, td { border:1px solid black;border-collapse:collapse; }</style>

<?php
require_once dirname ( __FILE__ ) . '/view/errors.inc.php';
require_once dirname ( __FILE__ ) . '/services/AppServiceImpl.class.php';

if(!isset($_SESSION)) session_start(); // Start session

// Export in browser - Added 26.06.2018
header("Content-Type:application/vnd.ms-excel;charset=utf-8");
header("Content-Disposition:attachment;filename=export_besoins_heures_".date('YmdHis').".xls");
header("Expires:0");
header("Cache-Control:must-revalidate,post-check=0,pre-check=0");
header("Cache-Control:private",false);

$appService = AppServiceImpl::getInstance();

// 1 - Search all existing stores and suppliers, storage in arrays
$arrayEnterprisesAsStores    = $appService->findAllEnterprisesAsStores();
// $arrayEnterprisesAsProviders = $appService->findAllEnterprisesAsProvidersPf($_SESSION['purchasingFairConcerned']->getIdPurchasingFair());
// Modified 27.06.2018
$arrayProviderPresent = $appService->findAllProviderPresentForOnePurchasingFair($_SESSION['purchasingFairConcerned']->getIdPurchasingFair());
$arrayEnterprisesAsProviders = array();
foreach($arrayProviderPresent as $key => $pp) { $arrayEnterprisesAsProviders[] = $pp->getOneProvider(); }

// 2 - We create the table
echo '<table class="table2excel" data-tableName="myAmazingTable">';

// 2a - Head
echo '<thead>';
echo '<tr>';
echo '<th>Magasin\Fournisseur</th>';
echo '<th>Indispos</th>';
foreach($arrayEnterprisesAsProviders as $provider) { echo '<th>'.$provider->getName().'('.$provider->getOneTypeOfProvider()->getNameTypeOfProvider()[0].')</th>'; }
echo '</tr>';
echo '</thead>';

// 2b - Body
echo '<tbody>';

// 2c - for each row of the table that concerns a store
foreach($arrayEnterprisesAsStores as $store) {
	
	echo '<tr>';
	
	// 2d - Show the store name
	echo '<td>'.$store->getName().'</td>';
	
	// 2e - Search for all the unavailabilities of the store
	$arrayEnterpriseUnavailabilities = $appService->findEnterpriseUnavailabilities($store, $_SESSION['purchasingFairConcerned']);
	
	echo '<td>';
	
	// 2f - Show the unavailability start & end datetimes
	foreach($arrayEnterpriseUnavailabilities as $unavailability) { echo $unavailability->getStartDatetime(). ' - '.$unavailability->getEndDatetime().'<br>'; }
	
	echo '</td>';
	
	// 2g - For each cell of the table, we search the requirements related to the store, provider and purchasing fair concerned
	foreach($arrayEnterprisesAsProviders as $provider) {
		
		$checkIfRequirementExist = $appService->findRequirementFilteredTrio($store,$provider,$_SESSION['purchasingFairConcerned']);
		
		echo '<td>'.( ( $checkIfRequirementExist != NULL ) ? $checkIfRequirementExist->getNumberOfHoursAlreadyRegistered() : '').'</td>';
	}
	echo '</tr>';
}

echo '</tbody>';
echo '</table>';
?>

<!-- JQuery -->
<!--<script src="js/jquery-3.1.1.min.js"></script>-->

<!-- table2excel -->
<!-- src: https://github.com/rainabba/jquery-table2excel -->
<!--<script src="js/plugins/table2excel/jquery.table2excel.js"></script>-->

<!-- Custom script -->
<!--<script>
 Generate the Excel file when the document is ready
$(function() {
	$(".table2excel").table2excel({
		exclude: ".noExl",
		name: "recap_besoins_salon_<?php //echo $_SESSION['purchasingFairConcerned']->getIdPurchasingFair(); ?>",
		filename: "recap_besoins_salon_<?php //echo $_SESSION['purchasingFairConcerned']->getIdPurchasingFair(); ?>_" + new Date().toISOString().replace(/[\-\:\.]/g, ""),
		fileext: ".xls",
		exclude_img: true,
		exclude_links: true,
		exclude_inputs: true
	});
	// Close the current page -- only possible if this page was generated with Javascript code (ok with onclick in view/menu.inc.php)
	window.close();
});
</script>-->