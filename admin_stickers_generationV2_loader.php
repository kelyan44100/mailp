<!-- Bootstrap -->
<link href="./css/bootstrap.min.css" rel="stylesheet">
<style>
html, body, #loaderStickers {
   height:100%;
   width: 100%;
   margin: 0;
   padding: 0;
   border: 0;
}
#loaderStickers td {
   vertical-align: middle;
   text-align: center;
}
@media print { 
    @page { margin: 1.3cm; }
    .pbreak { page-break-before:always; }
}
</style>

<table id="loaderStickers">
    <tr>
        <td><img src="./img/loader/loader_icons_set1/128x/Preloader_6.gif" alt="Chargement en cours..." /></td>
    </tr>
</table>

<div id="stickers" style="display:none">
    <div class="col-md-12 hidden-print text-center"><!-- Bootstrap 3 hidden-print class -->
        <button id="exitButton" class="btn btn-danger text-right"><i class="fa fa-sign-out" aria-hidden="true"></i> Quitter</button>
        <button id="btnPrintInvoice" class="btn btn-primary text-right"><i class="fa fa-print" aria-hidden="true"></i> Imprimer</button>
    </div>
    <div id="divStickersContent"></div>
</div>

<!-- Mainly scripts -->
<script src="./js/jquery-3.1.1.min.js"></script>
<script src="./js/bootstrap.min.js"></script>
<script src="./js/plugins/metisMenu/jquery.metisMenu.js"></script>
<script src="./js/plugins/slimscroll/jquery.slimscroll.min.js"></script>

<!-- Custom script -->
<script>
$(function(){
    $.get(
        './admin_stickers_generationV2.php',
        {
            typeEnterprise : '<?php echo $_GET['typeEnterprise']; ?>',
            day : '<?php echo $_GET['day']; ?>'
        },
        function(data) {
            $('#divStickersContent').html(data);
        },
        'html'
    );
});
</script>