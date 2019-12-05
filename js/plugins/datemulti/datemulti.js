//Permet d'afficher au click un calendrier avec les jours antérieur et les week-end désactiver pour selectionner plusieurs jours
$(document).ready(function () {
    $('#datepicker').datepicker({
        startDate: new Date(),
        multidate: true,
        format: "dd/mm/yyyy",
        daysOfWeekHighlighted: "1,2,3,4,5",
        daysOfWeekDisabled: [0,6],
        datesDisabled: ['11/12/2018'],
        language: 'en'
    }).on('changeDate', function (e) {
        // `e` here contains the extra attributes
        $(this).find('.input-group-addon .count').text(' ' + e.dates.length);
    });
});

