//$(function() {
        
        /*<?php $arrayEmailProviderCompleted = emailproviderCompleted(); $arrayValidEmails = validEmails(); ?>
        $("#sparklineA").sparkline([<?php echo $arrayEmailProviderCompleted[1]; ?>, <?php echo $arrayEmailProviderCompleted[0] - $arrayEmailProviderCompleted[1]; ?>], {
            type: 'pie',
            height: '40',
            sliceColors: ['#0b70b5', '#F5F5F5']
        });

        $("#sparklineB").sparkline([<?php echo $arrayValidEmails[1] ?>, <?php echo $arrayValidEmails[0] - $arrayValidEmails[1] ?>], {
            type: 'pie',
            height: '40',
            sliceColors: ['#ed8b18', '#F5F5F5']
        });
        
        <?php foreach($arrayValidEmails[2] as $emailError) { ?> 
            console.log("erreur email " + '<?php echo $emailError; ?>'); 
        <?php } ?>
        
        /*var table = $('#tableSalespersons').DataTable({
            language: { 
                url: './js/plugins/dataTables/localisation/french.json'
            },
            pageLength: 10,
            responsive: true,
            dom: '<"html5buttons"B>lTfgitp',
            buttons: [
                {extend: 'copy'},
                {extend: 'csv', title: 'recap_commerciaux'},
                {extend: 'excel', title: 'recap_commerciaux'},
                {extend: 'pdf', title: 'recap_commerciaux'},
                {extend: 'print',
                    customize: function (win){
                        $(win.document.body).addClass('white-bg');
                        $(win.document.body).css('font-size', '10px');

                        $(win.document.body).find('table')
                            .addClass('compact')
                            .css('font-size', 'inherit');
                    }
                }
            ],
            initComplete: function( settings, json ) {
                $('#myspinner').hide();
                $('#divTableSalespersons').show();
            }
        });*/
    //});