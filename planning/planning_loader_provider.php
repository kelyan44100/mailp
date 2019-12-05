<?php
require_once dirname ( __FILE__ ) . '/../services/AppServiceImpl.class.php';
if(!isset($_SESSION)) session_start(); // Start session
$file = './../tmp/tmp_planning_pf'.$_SESSION['purchasingFairConcerned']->getIdPurchasingFair().'.html';
if( file_exists($file) ) { 
?>

<style>
html, body, #loaderPlanningProvider {height:100%;width:100%;margin:0;padding:0;border:0;}
#loaderPlanningProvider td {vertical-align:middle;text-align:center;}
</style>

<table id="loaderPlanningProvider">
    <tr>
        <td><img src="./../img/loader/loader_icons_set1/128x/Preloader_7.gif" alt="Chargement en cours..." /></td>
    </tr>
</table>

<div id="divContentPlanningProvider" style="display:none">
    <?php require_once dirname ( __FILE__ ) . $file; ?>
</div>

<script>
// https://stackoverflow.com/questions/16335769/select-all-tables-having-a-class-jquery
// https://stackoverflow.com/questions/7012469/get-all-rows-in-the-current-table-and-not-from-child-tables

// CHECK THE FIRST TD OF EACH TR
$('table.table2excel').each(function() { // Tables with table2excel class, all tables == $('table')

    // console.log($(this).attr('id'));
    var currentTable = $(this).attr('id');
	
    $(this).find('> tbody > tr').each(function () {
		
        // console.log($(this).text());
        var currentRow = $(this).attr('id');

        // https://www.w3schools.com/jquery/sel_first.asp
        // https://stackoverflow.com/questions/13330778/find-first-td-with-text-in-a-tr-with-jquery
        // https://stackoverflow.com/questions/4996521/jquery-selecting-each-td-in-a-tr
        // https://api.jquery.com/each/
        // http://api.jquery.com/jquery.each/
        // https://api.jquery.com/remove/
        // If I see my enterprise ine the first row td
        if( $('#' + currentRow).find('td:first').text() !== '<?php echo $_SESSION['enterpriseConcerned']->getName().'('. $_SESSION['enterpriseConcerned']->getOneTypeOfProvider()->getNameTypeOfProvider()[0].')'; ?>' ) { 
            //console.log($('#'+ currentRow).attr('id'));
            $('#'+ currentRow).remove();
        }
    });
});

// CHECK OTHERS TD - START AT 3RD
$('table.table2excel').each(function() { // Tables with table2excel class, all tables == $('table')

    // console.log($(this).attr('id'));
    var currentTable = $(this).attr('id');
	
    $(this).find('> tbody > tr').each(function () {
		
        // console.log($(this).text());
        var currentRow = $(this).attr('id');

        // https://www.w3schools.com/jquery/sel_first.asp
        // https://stackoverflow.com/questions/13330778/find-first-td-with-text-in-a-tr-with-jquery
        // https://stackoverflow.com/questions/4996521/jquery-selecting-each-td-in-a-tr
        // https://api.jquery.com/each/
        // http://api.jquery.com/jquery.each/
        // https://api.jquery.com/remove/
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
                return false;
            }
        });
        
        if( rowChecker === 'nok' ) { $('#'+ currentRow).remove(); } 
    });
});
$('#alertWarningDiv, #alertSuccessDiv').remove();
$('#alertDangerDiv').removeClass('col-md-4').addClass('col-md-12');
$('.event').on('dragstart', function (event) { return false; });
$('.event').css('cursor', 'default');

$(document).ready(function(){
    $('#loaderPlanningProvider').hide();
    $('#divContentPlanningProvider').show();
    
    // The off() method is most often used to remove event handlers attached with the on() method.
    // https://www.w3schools.com/jquery/event_off.asp
    $('td').off('click');
    
    // Unbind Mousetrap keyboard events
    Mousetrap.unbind('ctrl c');
    Mousetrap.unbind('ctrl r');
    Mousetrap.unbind('ctrl m');
});
</script>
<?php
}
else { require_once dirname ( __FILE__ ) . '/planning_404_error.php'; }
?>