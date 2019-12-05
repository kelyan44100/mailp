<?php
require_once dirname ( __FILE__ ) . '/../services/AppServiceImpl.class.php';
if(!isset($_SESSION)) session_start(); // Start session

header( 'content-type: text/html; charset=utf-8' ); // Specifies to the server to return UTF-8 - put in prod

if( !isset( $_SESSION['purchasingFairConcerned'] ) && empty( $_SESSION['purchasingFairConcerned'] ) ) {
    header('Location: ./planning_404_error_2.php'); // Redirection to Purchasing Fair list
}
elseif( !file_exists('./../tmp/tmp_planning_pf'.$_SESSION['purchasingFairConcerned']->getIdPurchasingFair().'.html') ) {
    header('Location: ./planning_404_error.php'); // Redirection to Purchasing Fair list
}

$appService     = AppServiceImpl::getInstance();
$arrayStores    = $appService->findAllEnterprisesAsStores();
$arrayProviders = $appService->findAllEnterprisesAsProviders();

// Reset biling
$appService->deleteAllLunchesByPf($_SESSION['purchasingFairConcerned']->getIdPurchasingFair());
?>

<style>
html, body, #loaderLunches {height:100%;width:100%;margin:0;padding:0;border:0;}
#loaderLunches td {vertical-align:middle;text-align:center;}
</style>

<table id="loaderLunches">
    <tr>
        <td><img src="./../img/loader/loader_icons_set2/128x/Preloader_3.gif" alt="Facturation en cours..." /></td>
    </tr>
</table>

<div id="divHiddenLunchesCalculation" style="display:none">
<?php require_once dirname ( __FILE__ ) . '/../tmp/tmp_planning_pf'.$_SESSION['purchasingFairConcerned']->getIdPurchasingFair().'.html'; ?>
</div>

<!-- <div id="testContent"></div> -->

<!-- Mainly scripts -->
<script src="../js/jquery-3.1.1.min.js"></script>
<script src="../js/bootstrap.min.js"></script>
<script src="../js/plugins/metisMenu/jquery.metisMenu.js"></script>
<script src="../js/plugins/slimscroll/jquery.slimscroll.min.js"></script>

<!-- DataTables -->
<script src="../js/plugins/dataTables/datatables.min.js"></script>

<!-- Custom and plugin javascript -->
<script src="../js/inspinia.js"></script>
<script src="../js/plugins/pace/pace.min.js"></script>

<!-- Toastr script -->
<script src="../js/plugins/toastr/toastr.min.js"></script>

<!-- Custom script -->

<script>
var arrayStores    = [];
var arrayProviders = [];

$(function(){
    // Set array stores
    <?php foreach($arrayStores as $store) { ?>
    arrayStores.push({ 'idStore' : <?php echo $store->getIdEnterprise(); ?>, 'nameStore' : <?php echo "'".$store->getName()."'"; ?>, 'totLunches' : 0, 'detailsLunches' : [] });
    <?php } ?>

    // Set array providers
    <?php foreach($arrayProviders as $provider) { ?>
    arrayProviders.push({ 'idProvider' : <?php echo $provider->getIdEnterprise(); ?>, 'nameProvider' : <?php echo '"'.$provider->getName().'('.$provider->getOneTypeOfProvider()->getNameTypeOfProvider()[0].')"'; ?>, 'totLunches' : 0, 'detailsLunches' : [] });
    <?php } ?>

    // Calculate lunches - Stores
    for (var i = 0, len = arrayStores.length ; i < len ; i++) {

        $('table.table2excel').each(function() { // Tables with table2excel class, all tables == $('table')

            // Get day name
            var day = $(this).closest('table').parent().prev().text().trim();
                    
            // Create key in array JS
            // Push() method returns A number, representing the new length of the array
            // https://www.w3schools.com/jsref/jsref_push.asp
            var newLength = arrayStores[i].detailsLunches.push( { 'day' : day, 'totDay' : 0 });
                    
            var isFinded = 0;

            $(this).find('> tbody > tr').each(function () {

                $(this).find('td').not(':eq(0), :eq(1)').each(function() {
                    
                    if( $(this).text() === arrayStores[i].nameStore ) { //If I see my enterprise at least once
                        
                        ++arrayStores[i].totLunches;
                        ++arrayStores[i].detailsLunches[newLength - 1].totDay; 
                        isFinded = 1; 
                        return false;
                    }
                });
                if(isFinded === 1) { return false; }
            });
        });
        
        $.post(
            './../ajax/lunches_calculation.php',
            {
                arrayStores    : JSON.stringify(arrayStores[i]),
                profileEnterprise : 2
            },
            function(data) {

                //console.log(data);
                //window.location.assign("./../admin_lunches_datatable");
                // $('#testContent').html(data.trim());
            },
            'text'
        );
    }

    // Calculate lunches - Providers
    for (var i = 0, len = arrayProviders.length ; i < len ; i++) {

        $('table.table2excel').each(function() { // Tables with table2excel class, all tables == $('table')
            
            // Get day name
            var day = $(this).closest('table').parent().prev().text().trim();

            // Create key in array JS
            // Push() method returns A number, representing the new length of the array
            // https://www.w3schools.com/jsref/jsref_push.asp
            var newLength = arrayProviders[i].detailsLunches.push( { 'day' : day, 'totDay' : 0 });

            $(this).find('> tbody > tr').each(function () {

                if( $(this).find('td:first').text() === arrayProviders[i].nameProvider ) { 

                    $(this).find('td').not(':eq(0), :eq(1)').each(function() {

                        if( $(this).children('div').length > 0  && $(this).children('div').text() !== 'IND' ) { //If I see an appointment w/ Store at least once
                            ++arrayProviders[i].totLunches;
                            ++arrayProviders[i].detailsLunches[newLength - 1].totDay; 
                            return false;
                        }
                    });
                }
            });
        });
        
        $.post(
            './../ajax/lunches_calculation.php',
            {
                arrayProviders : JSON.stringify(arrayProviders[i]),
                profileEnterprise : 1
            },
            function(data) {

                //console.log(data);
                //window.location.assign("./../admin_lunches_datatable");
                // $('#testContent').html(data.trim());
            },
            'text'
        );
    }

    // Note that data size is too big if we send data in only one POST
    
    // $('#loaderLunches').hide();
    
    window.location.assign("./../admin_lunches_datatable");

});
</script>