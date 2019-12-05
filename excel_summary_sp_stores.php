<style>table, th, td { border:1px solid black;border-collapse:collapse; }</style>

<?php
require_once dirname ( __FILE__ ) . '/view/errors.inc.php';
require_once dirname ( __FILE__ ) . '/services/AppServiceImpl.class.php';

if(!isset($_SESSION)) session_start(); // Start session

// Export in browser
header("Content-Type:application/vnd.ms-excel;charset=utf-8");
header("Content-Disposition:attachment;filename=export_commerciaux_magasins_".date('YmdHis').".xls");
header("Expires:0");
header("Cache-Control:must-revalidate,post-check=0,pre-check=0");
header("Cache-Control:private",false);

$appService = AppServiceImpl::getInstance();

// 1 - Search all existing stores/providers, storage in arrays
$arrayEnterprisesAsStores    = $appService->findAllEnterprisesAsStores();
$arrayEnterprisesAsProviders = $appService->findAllEnterprisesAsProvidersPf($_SESSION['purchasingFairConcerned']->getIdPurchasingFair());
                                
// 2 - We create the table
echo '<table>';

// 2a - Head
echo '<thead>';
echo '<tr>';
echo '<th>Magasin\Fournisseur</th>';

// 2b - Providers
foreach($arrayEnterprisesAsProviders as $key => $provider) { echo '<th>'.$provider->getName().'('.$provider->getOneTypeOfProvider()->getNameTypeOfProvider()[0].')</th>'; }
echo '</tr>';

// 2c - Salespersons
echo '<tr>';
echo '<td>Magasin\Commerciaux</td>';

foreach($arrayEnterprisesAsProviders as $key => $provider) {
    
    $arrayAPE = $appService->findAllAssignmentsParticipantEnterpriseForOneEnterprise($provider->getIdEnterprise());
    
    echo '<td><table>';
    
    foreach($arrayAPE as $key2 => $oneAPE) { echo '<th>REPR. '.($key2).'<br>'.$oneAPE->getOneparticipant()->getSurname().'</th>'; }
    
    echo '</table></td>';
}

echo '</tr>';
echo '</thead>';

// 3 - Body
echo '<tbody>';

foreach($arrayEnterprisesAsStores as $key => $store) {
    
    echo '<tr>';
    
    // 3b - Show the store name
    echo '<td>'.$store->getName().'</td>';

    // 3c - Check if there is an assignment_sp_store for each salesperson for each provider
    foreach($arrayEnterprisesAsProviders as $key => $provider) { 
        
        $arrayAPE = $appService->findAllAssignmentsParticipantEnterpriseForOneEnterprise($provider->getIdEnterprise());
        
        echo '<td><table>';
        
        foreach($arrayAPE as $key2 => $oneAPE) {
            
            $checked = ( $appService->findOneAssignmentSpStore($oneAPE->getOneParticipant()->getIdParticipant(), $store->getIdEnterprise(), $provider->getIdEnterprise(), $_SESSION['purchasingFairConcerned']->getIdPurchasingFair()) != null ) ?
                'X' : '';    
            
            echo '<th>'.$checked.'</th>';
        }
        echo '</table></td>';
    }
    echo '</tr>';
}

echo '</tbody>';
echo '</table>';
?>