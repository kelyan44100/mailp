<?php
require_once dirname ( __FILE__ ) . '/../view/errors.inc.php';
require_once dirname ( __FILE__ ) . '/../services/AppServiceImpl.class.php';
if(!isset($_SESSION)) session_start(); // Start session
        
$appService   = AppServiceImpl::getInstance();

/* French days names */
$arrayDays = array('DIMANCHE','LUNDI','MARDI','MERCREDI','JEUDI','VENDREDI','SAMEDI');

/* French months names */
$arrayMonths = array('JANVIER','FEVRIER','MARS','AVRIL','MAI','JUIN','JUILLET','AOUT','SEPTEMBRE','OCTOBRE','NOVEMBRE','DECEMBRE');
?>

<link href="./../css/style.css" rel="stylesheet">
<style>
body {background-color:#ffffff!important;}
/* Loader gif src => http://smallenvelop.com/display-loading-icon-page-loads-completely/ */
/* Style used src => https://css-tricks.com/snippets/css/absolute-center-vertical-horizontal-an-image/ */
html, body, #loaderInvoice {
   height:100%;
   width: 100%;
   margin: 0;
   padding: 0;
   border: 0;
}
#loaderInvoice td {
   vertical-align: middle;
   text-align: center;
}
* { font-size:medium; }

@media print{ * { font-size:small; } .infoInvoice { font-size:x-small!important; } }

.ibox-content { border-style:none; }
</style>

<table id="loaderInvoice">
    <tr>
        <td><img src="../img/loader/loader_icons_set1/128x/Preloader_2.gif" alt="Chargement en cours..." /></td>
    </tr>
</table>

<div id="divHiddenLunchesCalculation" style="display:none">
<?php require_once dirname ( __FILE__ ) . '/planning_loader_provider.php'; ?>
</div>

<div id="invoiceContent" class="white-bg" style="display:none">
    <div class="wrapper wrapper-content p-sm">
        <div class="ibox-content p-sm">
            <div class="row">
                <div class="col-sm-6">
                    <address>
                        <img src="./../img/logo_eleclerc_invoice.png" alt="Logo E.Leclerc" height="25" width="109"><br/>
                        <strong>EDEL OUEST</strong><br/>
                        1 ROUTE DE CORDEMAIS<br/>
                        44360 ST-ETIENNE-DE-MONTLUC<br/>
                    </address>
                </div>

                <div class="col-sm-6 text-right">
                    <h4>Facture FCT-<?php echo substr($_SESSION['enterpriseConcerned']->getName(), 0,3).$_SESSION['enterpriseConcerned']->getOneTypeOfprovider()->getNameTypeOfprovider()[0] ?>-PF<?php echo $_SESSION['purchasingFairConcerned']->getIdPurchasingFair(); ?></h4>
                    <address>
                        <?php echo $_SESSION['enterpriseConcerned']->getName().'('.$_SESSION['enterpriseConcerned']->getOneTypeOfprovider()->getNameTypeOfprovider()[0].')'; ?><br/>
                        <span><?php echo $_SESSION['enterpriseConcerned']->getPostalAddress(); ?></span><br/>
                        <span><?php echo $_SESSION['enterpriseConcerned']->getPostalCode(). ' '.$_SESSION['enterpriseConcerned']->getCity(); ?></span>
                    </address>
                    <p>
                        <span>À ST-ETIENNE-DE-MONTLUC, LE <strong><?php echo date('d/m/Y'); ?></strong></span><br/>
                    </p>
                </div>
            </div>

            <div class="table-responsive m-t">
                <table class="table invoice-table">
                    <thead>
                    <tr>
                        <th>Description</th>
                        <th>Quantité</th>
                        <th>Prix Unitaire</th>
                        <th>TVA</th>
                        <th>Prix total</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr>
                        <td>
                            <div><strong>Repas <?php echo $_SESSION['purchasingFairConcerned']->getNamePurchasingFair(); ?></strong></div>
                            <small>
                                <span id="detailsSalespersons"></span><br/>
                                <span id="detailsSpecialGuests" class="text-danger">
                                    <?php
                                    $specialGuests = $appService->findAllSpecialGuestForOneEnterpriseAndPf(
                                            $_SESSION['enterpriseConcerned']->getIdEnterprise(),
                                            $_SESSION['purchasingFairConcerned']->getIdPurchasingFair()
                                            );
                                    
                                    $totSpecialGuestsLunches = 0;

                                    foreach($specialGuests as $specialGuest) {
                                        echo $specialGuest->getIconMaleOrFemale().' '.$specialGuest->getCivility(). ' '.$specialGuest->getSurname().' '.$specialGuest->getName().'<br/>';
                                        foreach( explode("|", $specialGuest->getDays()) as $day) {
                                            ++$totSpecialGuestsLunches;
                                            $datetime = DateTime::createFromFormat('Y-m-d h:i:s', $day.' 00:00:00');
                                            echo $arrayDays[$datetime->format('w')].' ';
                                            echo $datetime->format('d').' ';
                                            echo $arrayMonths[$datetime->format('m') - 1]. ' ';
                                            echo $datetime->format('Y');
                                            echo '<br/>';
                                        }
                                    }                                    
                                    ?>
                                </span>
                            </small>
                        </td>
                        <td id="invoicetTdQuantity"></td>
                        <td id="invoicetTdUnitPrice"></td>
                        <td id="invoicetTdTax"></td>
                        <td id="invoicetTdTotalPrice"></td>
                    </tr>

                    </tbody>
                </table>
            </div><!-- /table-responsive -->

            <table class="table invoice-total">
                <tbody>
                <tr>
                    <td><strong>Sous-total :</strong></td>
                    <td id="invoiceSubTotal"></td>
                </tr>
                <tr>
                    <td><strong>TVA :</strong></td>
                    <td id="invoiceTotalTVA"></td>
                </tr>
                <tr>
                    <td><strong>TOTAL :</strong></td>
                    <td id="invoiceTotalInvoice"></td>
                </tr>
                </tbody>
            </table>
            <div class="hidden-print text-right"><!-- Bootstrap 3 hidden-print class -->
                <button id="exitButton" class="btn btn-danger text-right"><i class="fa fa-sign-out" aria-hidden="true"></i> Quitter</button>
                <button id="btnPrintInvoice" class="btn btn-primary text-right"><i class="fa fa-print" aria-hidden="true"></i> Imprimer</button>
            </div>

            <div class="well m-t"><strong>Commentaires</strong>
                Facture générée en ligne via l'application PFManagement
            </div>
            
        </div>
    </div>
</div>

<script>
var arrayResponse = [];
var arraySalesperson = [];
$(function(){
    
    // https://developer.mozilla.org/en-US/docs/Web/JavaScript/Reference/Global_Objects/Math/round
    /**
    * Decimal adjustment of a number.
    *
    * @param {String}  type  The type of adjustment.
    * @param {Number}  value The number.
    * @param {Integer} exp   The exponent (the 10 logarithm of the adjustment base).
    * @returns {Number} The adjusted value.
    */
    function decimalAdjust(type, value, exp) {
        // If the exp is undefined or zero...
        if (typeof exp === 'undefined' || +exp === 0) {
            return Math[type](value);
        }
        value = +value;
        exp = +exp;
        // If the value is not a number or the exp is not an integer...
        if (value === null || isNaN(value) || !(typeof exp === 'number' && exp % 1 === 0)) {
            return NaN;
        }
        // Shift
        value = value.toString().split('e');
        value = Math[type](+(value[0] + 'e' + (value[1] ? (+value[1] - exp) : -exp)));
        // Shift back
        value = value.toString().split('e');
        return +(value[0] + 'e' + (value[1] ? (+value[1] + exp) : exp));
    }

    // Decimal round
    if (!Math.round10) {
        Math.round10 = function(value, exp) {
            return decimalAdjust('round', value, exp);
        };
    }
    // Decimal floor
    if (!Math.floor10) {
        Math.floor10 = function(value, exp) {
            return decimalAdjust('floor', value, exp);
        };
    }
    // Decimal ceil
    if (!Math.ceil10) {
        Math.ceil10 = function(value, exp) {
            return decimalAdjust('ceil', value, exp);
        };
    }
    
    // Print invoice on user click
    $('#btnPrintInvoice').click(function(){ window.print(); });
    $('#exitButton').click(function(){ location.assign('./../admin_salesperson_list.php'); });

    // Get all Salespersons in array and set lunches
    $('table.table2excel').each(function() { // Tables with table2excel class, all tables == $('table')
        $(this).find('> tbody > tr').each(function () {
            var idCurrentSalesperson = $(this).find('td:eq(1)').attr('class').substring(12);
//             https://stackoverflow.com/questions/586182/how-to-insert-an-item-into-an-array-at-a-specific-index
//             https://stackoverflow.com/questions/31065075/array-length-gives-incorrect-length
//             That's because length gives you the next index available in the array.
            if(typeof arraySalesperson[idCurrentSalesperson] === 'undefined') { 
                arraySalesperson[idCurrentSalesperson] = { 'totLunches' : 1, 'detailsDays' : $(this).closest('table').parent().prev().text().trim() }; 
            }
            else { 
                arraySalesperson[idCurrentSalesperson].totLunches += 1; 
                arraySalesperson[idCurrentSalesperson].detailsDays += ( '|' + $(this).closest('table').parent().prev().text().trim() );
            }
        });
    });

    // Other array to verify that all lunches are count

    $('table.table2excel').each(function() { // Tables with table2excel class, all tables == $('table')

        // console.log($(this).attr('id'));
        var currentTable = $(this).attr('id');
        // https://www.aspsnippets.com/Articles/Get-Count-Number-of-Rows-in-HTML-Table-using-JavaScript-and-jQuery.aspx
        var nbRows = $('#' + currentTable + ' td').closest('tr').length;

        arrayResponse.push({ 'day' : currentTable.substring(6), 'nbRows' : nbRows });
    });
    
    // Get the details for all salespersons
    $.post(
        './../ajax/get_details_salespersons.php',
        {
//            idSalespersons : Object.keys(arraySalesperson)
            idSalespersons : JSON.stringify(arraySalesperson)
        },
        function(data) {
            data = data.trim();
            $('#detailsSalespersons').html(data);
        },
        'text'
    );    
    
    var invoiceDate  = new Date();
    var dueDate      = new Date();
    var unitPrice    = 17.20;
    var tax          = 15.38;//Math.round10(unitPrice * 0.2, -2); // https://www.w3schools.com/js/js_numbers.asp
    var quantity     = 0;
    var totalPrice   = 0;
    var subTotal     = 0;
    var totalTVA     = 0;
    var totalInvoice = 0;
    
    for( var i = 0 ; i < arrayResponse.length ; i++) { if( arrayResponse[i].nbRows > 0 ) quantity += arrayResponse[i].nbRows; }
    
    quantity += <?php echo $totSpecialGuestsLunches; ?>
        
    totalPrice   = Math.round10(quantity * (4.75), -2);
    subTotal     = Math.round10(quantity * 4.75, -2);
    totalTVA     = Math.round10(quantity * (4.75), -2);
    totalInvoice = totalPrice;
    
    $('#invoiceDate').text(invoiceDate.getDate() + '/' + (invoiceDate.getMonth()+1) + '/' + invoiceDate.getFullYear());
    $('#dueDate').text(dueDate.getDate() + '/' + (dueDate.getMonth()+2) + '/' + dueDate.getFullYear());
    $('#invoicetTdQuantity').text(quantity);
    $('#invoicetTdUnitPrice').text(unitPrice);
    $('#invoicetTdTax').text(tax);
    $('#invoicetTdTotalPrice').text(totalPrice);
    $('#invoiceSubTotal').text(subTotal);
    $('#invoiceTotalTVA').text(totalTVA);
    $('#invoiceTotalInvoice').text(totalInvoice);
    
    $('#loaderInvoice').hide();
    $('#invoiceContent').show();
//    console.log(JSON.stringify(arrayResponse));
});


  

</script>