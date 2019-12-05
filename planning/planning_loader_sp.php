<?php
require_once dirname ( __FILE__ ) . '/../services/AppServiceImpl.class.php';

// Singleton AppServiceImpl
$appService = AppServiceImpl::getInstance();

// Check URL before continue
$checkParameters = isset($_GET['pf']) && !empty($_GET['pf'])
        && isset($_GET['sp']) && !empty($_GET['sp'])
        && isset($_GET['k']) && !empty($_GET['k']);

if($checkParameters) {
    
    // Get objects from ids
    $purchasingFairConcerned = $appService->findOnePurchasingFair( (int) $_GET['pf'] );
    $salespersonConcerned    = $appService->findOneParticipant( (int) $_GET['sp'] );
    // FAKE
    $randomKey               = (string) $_GET['k'];

    // Check if general planning exists
    $file = './../tmp/tmp_planning_pf'.$purchasingFairConcerned->getIdPurchasingFair().'.html';
    
    // if file exists and data OK
    if( file_exists($file) && !is_null($purchasingFairConcerned) && !is_null($salespersonConcerned) ) { 
    ?>

    <style>
    html, body, #loaderPlanningStore {height:100%;width:100%;margin:0;padding:0;border:0;}
    #loaderPlanningStore td {vertical-align:middle;text-align:center;}
    </style>

    <table id="loaderPlanningStore">
        <tr>
            <td><img src="./../img/loader/loader_icons_set2/128x/Preloader_8.gif" alt="Chargement en cours..." /></td>
        </tr>
    </table>

    <div id="divContentPlanningStore" style="display:none">
        <?php require_once dirname ( __FILE__ ) . $file; ?>
    </div>

    <script>
    // https://stackoverflow.com/questions/16335769/select-all-tables-having-a-class-jquery
    // https://stackoverflow.com/questions/7012469/get-all-rows-in-the-current-table-and-not-from-child-tables

    // Get all the lines for the concerned Salesperson and hide the others
    $('table.table2excel').each(function() { // Tables with table2excel class, all tables == $('table')
        $(this).find('> tbody > tr').each(function () {

            // return false; is equivalent of 'break' for jQuery loop 
            // return; is equivalent of 'continue' for jQuery loop
            // https://stackoverflow.com/questions/17162334/how-to-use-continue-in-jquery-each-loop
            // No Salesperon in the second <td> of each row
            if(typeof $(this).find('td:eq(1)').attr('class') === 'undefined') { 
                $(this).remove(); // Remove row
                return; // ===> continue iteration .each, change <tr>
            }

            else {

                // Get Salesperson id
                var idCurrentSalesperson = $(this).find('td:eq(1)').attr('class').substring(12);

                // If it is not the wanted Salesperson
                if(parseInt(idCurrentSalesperson, 10) !== <?php echo $salespersonConcerned->getIdParticipant(); ?>) {
                    $(this).remove(); // Remove row
                    return; // continue
                }

                else {

                    var rowChecker = 'nok';
                    // https://www.w3schools.com/jquery/traversing_not.asp
                    // The first two cells are not considered
                    $(this).find('td').not(':eq(0), :eq(1)').each(function() {
                        //console.log($('#'+ currentRow).attr('id'));
                        /* Returning 'false' from within the each function completely stops the 
                         * loop through all of the elements (this is like using a 'break' with a normal loop). 
                         * Returning 'true' from within the loop skips to the next iteration (this is like using a 'continue' with a normal loop).
                         */
                        if( $(this).children('div').length > 0 && $(this).children('div').text() !== 'IND' ) { //If I see my enterprise at least once
                            rowChecker = 'ok';
                            return false; // break
                        }
                    });
                    if( rowChecker === 'nok' ) { $(this).remove(); return; } // Clear row and continu
                }
            }
        });
    });
    $('#alertWarningDiv, #alertSuccessDiv').remove();
    $('#alertDangerDiv').removeClass('col-md-4').addClass('col-md-12');
    $('.event').on('dragstart', function (event) { return false; });
    $('.event').css('cursor', 'default');

    $(document).ready(function(){
        $('#loaderPlanningStore').hide();
        $('#divContentPlanningStore').show();
    });
    </script>
    <?php
    }
    else { require_once dirname ( __FILE__ ) . '/404.php'; }
}
else { require_once dirname ( __FILE__ ) . '/404.php'; }
?>