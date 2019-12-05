<style>
html, body, #loader {height:100%;width:100%;margin:0;padding:0;border:0;}
#loader td {vertical-align:middle;text-align:center;}
</style>

<table id="loader">
    <tr>
        <td><img src="./../img/loader/loader_icons_set1/128x/Preloader_4.gif" alt="Chargement en cours..." /></td>
    </tr>
</table>

<script src="./../js/jquery-3.1.1.min.js"></script>
<script>
    
$(document).ready(function(){
    
    $.post(
        './myplanningV3.php',
        {
//                action : 'load'
        },
        function(data) {
            $("#loader").hide();
            location.assign('./myplanningV3.php');
        },
        'text'
    );    
});
</script>