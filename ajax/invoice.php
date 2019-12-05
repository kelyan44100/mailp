<?php
require_once dirname ( __FILE__ ) . '/../view/errors.inc.php';
require_once dirname ( __FILE__ ) . '/../services/AppServiceImpl.class.php';
if(!isset($_SESSION)) session_start(); // Start session
        
$appService   = AppServiceImpl::getInstance();

/* French days names */
$arrayDays = array('DIMANCHE','LUNDI','MARDI','MERCREDI','JEUDI','VENDREDI','SAMEDI');

/* French months names */
$arrayMonths = array('JANVIER','FEVRIER','MARS','AVRIL','MAI','JUIN','JUILLET','AOUT','SEPTEMBRE','OCTOBRE','NOVEMBRE','DECEMBRE');

/* To prevent server misconfigured */
date_default_timezone_set('Europe/Paris');

$providerForInvoice       = $appService->findOneEnterprise($_GET['idEnterpriseInvoice']);
$purchasingFairForInvoice = $appService->findOnePurchasingFair($_GET['idPurchasingFairInvoice']);

$idProvider               = (int) $providerForInvoice->getIdEnterprise();
$idPurchasingFair         = (int) $purchasingFairForInvoice->getIdPurchasingFair();
$lunchProvider            = $appService->findLunchForOneEnterpriseAndPf($idProvider, $idPurchasingFair);
$totLunchesInvoice        = (int) $_GET['totLunches'];

//if(is_null($lunchProvider)) { header('Location: ./../404.php'); die(); }

// Agents - Added 21.06.2018
$arrayAgents = $appService->findAllAgent();
?>

<!-- Favicon -->
<?php require_once dirname ( __FILE__ ) . './../view/favicon.inc.php'; ?>

<!-- Bootstrap -->
<link href="./../css/bootstrap.min.css" rel="stylesheet">

<!-- Font Awesome -->
<link href="./../font-awesome/css/font-awesome.css" rel="stylesheet">

<!-- DataTables -->
<link href="./../css/plugins/dataTables/datatables.min.css" rel="stylesheet">

<!-- Hover -->
<link href="./../css/plugins/hover.css/hover-min.css" rel="stylesheet">

<!-- Toastr style -->
<link href="./../css/plugins/toastr/toastr.min.css" rel="stylesheet">

<!-- Animate -->
<link href="./../css/animate.css" rel="stylesheet">

<!-- Global -->
<link href="./../css/style.css" rel="stylesheet">

<!-- Custom style -->
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
/* Invoice style */
* { font-size:medium; }
@media print{ * { font-size:small; } .infoInvoice { font-size:x-small!important; } }
.ibox-content { border-style:none; }
</style>

<table id="loaderInvoice">
    <tr>
        <td><img src="./../img/loader/loader_icons_set1/128x/Preloader_2.gif" alt="Chargement en cours..." /></td>
    </tr>
</table>

<div id="invoiceContent" class="white-bg" style="display:none">
    <div class="wrapper wrapper-content p-xs">
        <div class="ibox-content p-xs">
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
                    <h4>Facture FCT-<?php echo substr($providerForInvoice->getName(), 0,3).$providerForInvoice->getOneTypeOfprovider()->getNameTypeOfprovider()[0] ?>-PF<?php echo $purchasingFairForInvoice->getIdPurchasingFair(); ?></h4>
                    <?php
                    $agentForInvoice = null;
                    foreach($arrayAgents as $agent) {
                        $arrayExplodedProviders = explode( '|', $agent->getProviders() );
                        if( in_array( $providerForInvoice->getIdEnterprise(), $arrayExplodedProviders ) ) {
                            $agentForInvoice = $agent;
                            break 1; // exit foreach loop
                        }
                    }
                    if( !is_null( $agentForInvoice ) ) {
                    ?>
                    <address>
                        <?php echo $agentForInvoice. ' (Agent '.$providerForInvoice->getName().')'; ?><br/>
                        <span><?php echo $agentForInvoice->getAddressLine1(); ?></span><br/>
                        <span><?php echo $agentForInvoice->getAddressLine2(); ?></span>
                    </address>
                    <?php } else { ?>
                    <address>
                        <?php echo $providerForInvoice->getName().'('.$providerForInvoice->getOneTypeOfprovider()->getNameTypeOfprovider()[0].')'; ?><br/>
                        <span><?php echo $providerForInvoice->getPostalAddress(); ?></span><br/>
                        <span><?php echo $providerForInvoice->getPostalCode(). ' '.$providerForInvoice->getCity(); ?></span>
                    </address>
                    <?php } ?>
                    <p>
                        <span>À ST-ETIENNE-DE-MONTLUC, LE <strong><?php echo date('d/m/Y'); ?></strong></span><br/>
                    </p>
                </div>
                
                <div class="col-md-12">
                    <span><u>Votre n° de TVA intracommunautaire</u> : <?php echo $providerForInvoice->getVat(); ?></span><br/>
                    <span><u>Notre n° de TVA intracommunautaire</u> : FR 24 352 628 275</span>
                </div>
            </div>
            
            <div class="row">
                
                <div class="col-md-12 hidden-print text-center"><!-- Bootstrap 3 hidden-print class -->
                    <button id="exitButton" class="btn btn-danger text-right"><i class="fa fa-sign-out" aria-hidden="true"></i> Quitter</button>
                    <button id="btnPrintInvoice" class="btn btn-primary text-right"><i class="fa fa-print" aria-hidden="true"></i> Imprimer</button>
                </div>
                
                <div class="col-md-12 text-center m-t">
                    <span>
                    Frais de restauration - <strong><?php echo $purchasingFairForInvoice->getNamePurchasingFair(); ?></strong> 
                    </span>
                </div>
                                    
                <div class="col-md-12 text-left">
                    - REPAS
                </div>
                
                <?php 
                $specialGuests = $appService->findAllSpecialGuestForOneEnterpriseAndPf($idProvider, $idPurchasingFair);

                $totSpecialGuestsLunches = 0;

                foreach($specialGuests as $specialGuest) {
                    foreach( explode("|", $specialGuest->getDays()) as $day) {
                        ++$totSpecialGuestsLunches;
                    }
                }   
                $line0a = '-';
//                $line0b = $lunchProvider->getLunchesPlanned() + $totSpecialGuestsLunches - $lunchProvider->getLunchesCanceled();
                $line0b = $totLunchesInvoice;
                $line1a = 12.57;
                $line1b = $line1a * $line0b;
                
                $line2a = 1.26;
                $line2b = $line2a * $line0b;
                
                $line3a = 2.81;
                $line3b = $line3a * $line0b;
                
                $line4a = 0.56;
                $line4b = $line4a * $line0b;
                
                $line5a = $line1a + $line3a;
                $line5b = $line5a * $line0b;
                
                $line6a = $line1a + $line2a + $line3a + $line4a;
                $line6b = $line6a * $line0b;
                ?>
                <div class="col-md-12">
                    <table class="table table-bordered table-hover table-responsive">
                        <tr>
                            <td style="width:50%">Nombre de déjeuners</td>
                            <td class="text-right" style="width:25%">-</td>
                            <td class="text-right" style="width:25%"><?php echo $line0b; ?></td>
                        </tr>
                        <tr>
                            <td style="width:50%">Montant HT Repas</td>
                            <td class="text-right" style="width:25%"><?php echo $appService->numberFormat($line1a, 'french'); ?> €</td>
                            <td class="text-right" style="width:25%"><?php echo $appService->numberFormat($line1b, 'french'); ?> €</td>
                        </tr>
                        <tr>
                            <td style="width:50%">TVA 10%</td>
                            <td class="text-right" style="width:25%"><?php echo $appService->numberFormat($line2a, 'french'); ?> €</td>
                            <td class="text-right" style="width:25%"><?php echo $appService->numberFormat($line2b, 'french'); ?> €</td>
                        </tr>                        
                        <tr>
                            <td style="width:50%">Montant HT Boissons</td>
                            <td class="text-right" style="width:25%"><?php echo $appService->numberFormat($line3a, 'french'); ?> €</td>
                            <td class="text-right" style="width:25%"><?php echo $appService->numberFormat($line3b, 'french'); ?> €</td>
                        </tr>                        
                        <tr>
                            <td style="width:50%">TVA 20%</td>
                            <td class="text-right" style="width:25%"><?php echo $appService->numberFormat($line4a, 'french'); ?> €</td>
                            <td class="text-right" style="width:25%"><?php echo $appService->numberFormat($line4b, 'french'); ?> €</td>
                        </tr>                        
                        <tr>
                            <td style="width:50%">Total HT</td>
                            <td class="text-right" style="width:25%"><?php echo $appService->numberFormat($line5a, 'french'); ?> €</td>
                            <td class="text-right" style="width:25%"><?php echo $appService->numberFormat($line5b, 'french'); ?> €</td>
                        </tr>                        
                        <tr>
                            <td style="width:50%">Total TTC</td>
                            <td class="text-right" style="width:25%"><?php echo $appService->numberFormat($line6a, 'french'); ?> €</td>
                            <td class="text-right" style="width:25%"><?php echo $appService->numberFormat($line6b, 'french'); ?> €</td>
                        </tr>                        
                    </table>
                </div>
                
                <div class="col-md-12">
                    <span class="infoInvoice">
                        Soit déjeuners <?php echo $line0b; ?> x <?php echo $appService->numberFormat($line6a, 'french'); ?> € TTC = 
                            <?php echo $appService->numberFormat($line6b, 'french'); ?> €</span>
                </div>
                
                                
                <div class="col-md-12 text-left m-t">
                    - CONSOMMATIONS
                </div>
                
                <div class="col-md-12">
                    <table class="table table-bordered table-hover table-responsive">
                        <tr>
                            <td style="width:50%">Nombre de consommations</td>
                            <td class="text-right" style="width:25%">-</td>
                            <td class="text-right" style="width:25%">-</td>
                        </tr>
                        <tr>
                            <td style="width:50%">Montant HT</td>
                            <td class="text-right" style="width:25%"><?php echo $appService->numberFormat(0.84, 'french'); ?> €</td>
                            <td class="text-right" style="width:25%">- €</td>
                        </tr>
                        <tr>
                            <td style="width:50%">TVA 20%</td>
                            <td class="text-right" style="width:25%"><?php echo $appService->numberFormat(0.17, 'french'); ?> €</td>
                            <td class="text-right" style="width:25%">- €</td>
                        </tr>                        
                        <tr>
                            <td style="width:50%">Total TTC</td>
                            <td class="text-right" style="width:25%"><?php echo $appService->numberFormat(1.01, 'french'); ?> €</td>
                            <td class="text-right" style="width:25%">- €</td>
                        </tr>                                             
                    </table>
                </div>
            
                <div class="col-md-12 m-t">
                    <span class="infoInvoice">Soit consommations - x <?php echo $appService->numberFormat(1.01, 'french'); ?> € TTC = - €</span>
                </div>
                
                <div class="col-md-12 m-t">
                    <table class="table table-bordered table-hover table-responsive">
                        <tr>
                            <td class="text-right" style="width:50%"><strong>MONTANT TTC</strong></td>
                            <td class="text-left" style="width:50%"><strong><?php echo $appService->numberFormat($line6b, 'french'); ?> €</strong></td>
                        </tr>                                         
                    </table>       
                </div>
                
                <div class="col-md-12 text-center"><em>
                    <span class="infoInvoice">
                        Aucun escompte ne sera effectué pour paiement anticipé. Toutes modifications du mode de paiement par le tiers pour lequel un retard de paiement sera constaté, 
                        entraînera une facturation d'intérêts de retard égale à trois fois le taux légal en vigueur ainsi qu'une indemnité forfaitaire minimum pour les frais de recouvrement 
                        de 40 €.
                    </span></em>
                </div>
                
            </div>
            
        </div>
    </div>
</div>

<!-- Mainly scripts -->
<script src="./../js/jquery-3.1.1.min.js"></script>
<script src="./../js/bootstrap.min.js"></script>
<script src="./../js/plugins/metisMenu/jquery.metisMenu.js"></script>
<script src="./../js/plugins/slimscroll/jquery.slimscroll.min.js"></script>

<!-- DataTables -->
<script src="./../js/plugins/dataTables/datatables.min.js"></script>

<!-- Custom and plugin javascript -->
<script src="./../js/inspinia.js"></script>
<script src="./../js/plugins/pace/pace.min.js"></script>

<!-- Toastr script -->
<script src="./../js/plugins/toastr/toastr.min.js"></script>

<!-- Custom script -->
<script>
$(function(){
    $('#loaderInvoice').hide();
    $('#invoiceContent').show();
    
    // Print invoice on user click
    $('#btnPrintInvoice').click(function(){ window.print(); });
    $('#exitButton').click(function(){ window.close(); });
});
</script>