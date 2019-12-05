<?php
require_once dirname ( __FILE__ ) . '/../services/AppServiceImpl.class.php';
if(!isset($_SESSION)) session_start(); // Start session

$arrayParticipationsAlreadyRegistered = AppServiceImpl::getInstance()->findAllParticipationsByEnterpriseAndPurchasingFair($_SESSION['enterpriseConcerned'],$_SESSION['purchasingFairConcerned']);
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
</style>

<table id="loaderInvoice">
    <tr>
        <td><img src="../img/loader/loader_icons_set1/128x/Preloader_2.gif" alt="Chargement en cours..." /></td>
    </tr>
</table>

<div id="divHiddenLunchesCalculation" style="display:none">
<?php require_once dirname ( __FILE__ ) . '/planning_loader_store.php'; ?>
</div>

<div id="invoiceContent" class="white-bg" style="display:none">
    <div class="wrapper wrapper-content p-xl">
        <div class="ibox-content p-xl">
            <div class="row">
                <div class="col-sm-6">
                    <h5>Émise par:</h5>
                    <address>
                        <strong>SCA OUEST</strong><br/>
                        1 ROUTE DE CORDEMAIS<br/>
                        44360 SAINT-ETIENNE-DE-MONTLUC<br/>
                        <i class="fa fa-phone" aria-hidden="true"></i> (+33) 02 40 85 10 10
                    </address>
                </div>

                <div class="col-sm-6 text-right">
                    <h4>Facture No.</h4>
                    <h4 class="text-navy">FCT-<?php echo substr($_SESSION['enterpriseConcerned']->getName(), 0,3); ?>-PF<?php echo $_SESSION['purchasingFairConcerned']->getIdPurchasingFair(); ?>
                    </h4>
                    <span>Destinataire:</span>
                    <address>
                        <strong><?php echo $_SESSION['enterpriseConcerned']->getOneProfile()->getName(); ?></strong><br/>
                        <?php echo $_SESSION['enterpriseConcerned']->getName(); ?><br/>
                        <i class="fa fa-at" aria-hidden="true"></i> <?php echo (!empty($storeContact)) ? ( (!empty($storeContact->getEmail()) ? $storeContact->getEmail() : 'Fiche de contact non renseignée' ) ) : 'Fiche de contact non renseignée' ?>
                    </address>
                    <p>
                        <span><strong>Date facturation:</strong> <span id="invoiceDate"></span></span><br/>
                        <span><strong>Paiement dû:</strong> <span id="dueDate"></span></span>
                    </p>
                </div>
            </div>

            <div class="table-responsive m-t">
                <table class="table invoice-table">
                    <thead>
                    <tr>
                        <th>Description</th>
                        <th>Quantité</th>
                        <th>Prix Unitaire (€)</th>
                        <th>TVA (20%)</th>
                        <th>Prix total (€)</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr>
                        <td>
                            <div><strong>Repas <?php echo $_SESSION['purchasingFairConcerned']->getNamePurchasingFair(); ?></strong></div>
                            <small>
                            <?php
                            $counterParticipation = 0;
                            $limit = count($arrayParticipationsAlreadyRegistered);
                            foreach($arrayParticipationsAlreadyRegistered as $participation) {
                                echo $participation->getOneParticipant().( ($counterParticipation < $limit) ? '<br/>' : '' );
                                ++$counterParticipation;
                            }
                            ?>
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
                    <td id="invoiceTotalTVA">$235.98</td>
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
    $('#exitButton').click(function(){ location.assign('./../store_choice_providers.php'); });
    
    $('table.table2excel').each(function() { // Tables with table2excel class, all tables == $('table')

        // console.log($(this).attr('id'));
        var currentTable = $(this).attr('id');
        // https://www.aspsnippets.com/Articles/Get-Count-Number-of-Rows-in-HTML-Table-using-JavaScript-and-jQuery.aspx
        var nbRows = $('#' + currentTable + ' td').closest('tr').length;

        arrayResponse.push({ 'day' : currentTable.substring(6), 'nbRows' : nbRows });
    });
    
    var invoiceDate  = new Date();
    var dueDate      = new Date();
    var unitPrice    = 0; // 0 => 4.75
    var tax          = Math.round10(unitPrice * 0.2, -2); // https://www.w3schools.com/js/js_numbers.asp
    var quantity     = 0;
    var totalPrice   = 0;
    var subTotal     = 0;
    var totalTVA     = 0;
    var totalInvoice = 0;
    
    for( var i = 0 ; i < arrayResponse.length ; i++) { if( arrayResponse[i].nbRows > 0 ) ++quantity; }
    
    quantity *= <?php echo $counterParticipation; ?>;
    
    totalPrice   = Math.round10(quantity * (0 * 1.2), -2); // 0 => 4.75
    subTotal     = Math.round10(quantity * 0, -2); // 0 => 4.75
    totalTVA     = Math.round10(quantity * (0 * 0.2), -2); // 0 => 4.75
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