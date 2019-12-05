<?php
require_once dirname ( __FILE__ ) . '/services/AppServiceImpl.class.php';

header( 'content-type: text/html; charset=utf-8' ); // Specifies to the server to return UTF-8

$appService = AppServiceImpl::getInstance();
?>
<!DOCTYPE html>
<html lang="fr">

<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="theme-color" content="#0b70b5"><!-- Mobile browser Tab Color -->

    <title>PFManagement | QRCode validator</title>
    
    <!-- Favicon -->
    <?php require_once dirname ( __FILE__ ) . '/view/favicon.inc.php'; ?>

    <!-- Bootstrap -->
    <link href="css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link href="font-awesome/css/font-awesome.css" rel="stylesheet">

    <!-- Hover -->
    <link href="css/plugins/hover.css/hover-min.css" rel="stylesheet">
    
    <!-- Animate -->
    <link href="css/animate.css" rel="stylesheet">
    
    <!-- Global -->
    <link href="css/style.css" rel="stylesheet">
    
    <!-- Custom style -->
    <style>
    body { background-color:#0b70b5!important; }
    /* Logo E.Leclerc w/ SCA Ouest ; Font Eurostile used info = http://logonews.fr/2012/10/02/les-nouveautes-de-leclerc/
     * Width : (756 / 2) = 378px and height : (92 / 2) = 46px /!\ Adapt values with browser inspector
     * Note that .img-responsive is not compatible with text-center, use margin:0 auto instead 
     */
    #applogo { width:340px;height:41px; }
    /* Replace the icon of the button */
    .hvr-icon-forward:before { content: "\f061"; }
    /* Form in the center of the page */
    #centralForm { width:400px;background-color:#ffffff;padding:20px 20px 20px 20px;border-top:2px solid #ed8b18;box-shadow: 1px 1px 12px #555; }
    /* previous icon on top-right div */
    #previousButton { color:#ed8b18;cursor:pointer; }
    /* Button to access the list of purchasing fairs */
    #submitButtonStore { background-color:#0b70b5;color:#ffffff; }
    #submitButtonStore:hover { border:1px solid #ed8b18; }
    /* Admin icon on top-right div */
    #adminButton { color:#ed8b18; }
    /* Hack to center centralForm */
    #divForCenterXsSm { height:100px; }
    #divForCenterMdLg { height:100px; }
    /* Devices icons in the bottom */
    #devicesIcons { cursor:help; }
    /* Color orange E.Leclerc */
    .colorOrangeLeclerc { color:#ed8b18; }
    .input-group-addon { color:#ed8b18;border:1px solid #ed8b18; }
    /* Change input border color simple & on focus */
    #myInput { border:1px solid #ed8b18; }
    #myInput:focus { color:#ed8b18;border:1px solid #ed8b18; }
    /* Change input placeholder color https://css-tricks.com/almanac/selectors/p/placeholder/ */
    #myInput::-webkit-input-placeholder { color: #ff0000; } /* Chrome/Opera/Safari */
    #myInput::-moz-placeholder { color: #ff0000; } /* Firefox 19+ */
    #myInput:-ms-input-placeholder { color: #ff0000; } /* IE 10+ */
    #myInput:-moz-placeholder { color: #ff0000; } /* Firefox 18- */
    </style>

</head>

<body>
    
    <div class="row">
        <div id="divForCenterXsSm" class="col-xs-12 col-sm-12 hidden-md hidden-lg">&nbsp;</div> <!-- This div is only showed with xs & sm screens -->
        <div id="divForCenterMdLg" class="hidden-xs hidden-sm col-md-12 col-lg-12">&nbsp;</div> <!-- This div is only showed with md & lg screens -->
    </div>
    
    <div class="row">
        <div class="col-xs-1 col-sm-1 col-md-1 col-lg-3">&nbsp;</div>
        <div class="col-xs-10 col-sm-10 col-md-10 col-lg-5 text-center">
            <div id="centralForm" class="text-center animated fadeInDown" style="width:100%!important">

                <!-- To center images which use the .img-responsive class, use .center-block instead of .text-center. (doc Bootstrap) -->
                <img id="applogo" class="img-responsive m-b-xl center-block" src="./img/logo_eleclerc_scaouest.png" alt="PFManagement">
                <h2 class="colorOrangeLeclerc m-t-xl">Vérification des QRCodes (-1 Repas)</h2>
                <div id="container" style="width:250px;height:250px;margin:0 auto"></div>
                <H2 class="text-center m-b-md">Scannez le QRCode d'un participant pour commencer.</h2>
                    <div class="input-group m-b">
                        <span class="input-group-addon"><i class="fa fa-qrcode fa-2x" aria-hidden="true"></i></span> <input id="myInput" name="myInput" type="text" placeholder="En attente scan..." class="form-control input-lg" autofocus="" autocomplete="off">
                    </div>

                    <h2 id="detailsParticipant"></h2>

                <h3 class="m-t-xl"> <i class="fa fa-copyright" aria-hidden="true"></i> E.Leclerc | SCA Ouest <?php echo date('Y'); ?></h3>
                <div id="devicesIcons" class="text-center m-t-md">
                    <i class="fa fa-tablet fa-3x" data-toggle="tooltip" data-placement="bottom" title="Optimisé pour les tablettes"></i>
                </div>
            </div>
            
        </div>
        <div class="col-xs-1 col-sm-1 col-md-1 col-lg-1 text-left animated fadeInDown" style="padding:0">
            <i id="previousButton" class="fa fa-2x fa-times-circle-o hvr-bounce-in" aria-hidden="true" data-toggle="tooltip" data-placement="top" title="Quitter"></i>
        </div>
        <div class="hidden-xs hidden-sm hidden-md col-lg-3">&nbsp;</div>
    </div>
    
    <div class="row">
        <div id="divForCenterXsSm" class="col-xs-12 col-sm-12 hidden-md hidden-lg">&nbsp;</div> <!-- This div is only showed with xs & sm screens -->
        <div id="divForCenterMdLg" class="hidden-xs hidden-sm col-md-12 col-lg-12">&nbsp;</div> <!-- This div is only showed with md & lg screens -->
    </div>

    <!-- Mainly scripts -->
    <script src="js/jquery-3.1.1.min.js"></script>
    <script src="js/bootstrap.min.js"></script>

    <!-- Highcharts -->
    <script src="js/plugins/highcharts/highcharts.js"></script>
    <script src="js/plugins/highcharts/highcharts-more.js"></script>

    <!-- Custom script -->
    <script>
    // Activate the display of information when hover the icons
    $(function () { $('[data-toggle="tooltip"]').tooltip() });
        
    // Previous buttons
    $("#previousButton").click(function(){ window.location.assign("./purchasing_fair_list.php"); });
    
    // Check if the QRCode scan is complete
        
    // Setup before functions
    var typingTimer;                //timer identifier
    var doneTypingInterval = 1000;  //time in ms, 1 second for example
    var $input = $('#myInput');

    // On keyup, start the countdown
    $input.on('keyup', function () {
      clearTimeout(typingTimer);
      typingTimer = setTimeout(doneTyping, doneTypingInterval);
    });

    // On keydown, clear the countdown 
    $input.on('keydown', function () { clearTimeout(typingTimer); });

    //user is "finished typing," do something
    function doneTyping () {
        $.post(
            './ajax/check_qrcode.php',
            {
                myInput : $("#myInput").val()
            },
            function(data) {
                $("#detailsParticipant").html(data);
                $("#myInput").val("");
            },
            'text'
        );
    }

    // Highcharts clock
    
    /**
     * Get the current time
     */
    function getNow() {
        var now = new Date();

        return {
            hours: now.getHours() + now.getMinutes() / 60,
            minutes: now.getMinutes() * 12 / 60 + now.getSeconds() * 12 / 3600,
            seconds: now.getSeconds() * 12 / 60
        };
    }

    /**
     * Pad numbers
     */
    function pad(number, length) {
        // Create an array of the remaining length + 1 and join it with 0's
        return new Array((length || 2) + 1 - String(number).length).join(0) + number;
    }

    var now = getNow();

    // Create the chart
    Highcharts.chart('container', {
        chart: {
            type: 'gauge',
            plotBackgroundColor: null,
            plotBackgroundImage: null,
            plotBorderWidth: 0,
            plotShadow: false,
            height: 250
        },
        plotOptions: {
            gauge: {
                dial: {
                    backgroundColor: '#0b70b5'
                },
                pivot: {
                    backgroundColor: '#0b70b5',
                }
            }
        },
        credits: {
            enabled: false
        },
        title: {
            text: ''
        },
        pane: {
            background: [{
                // default background
            }, {
                // reflex for supported browsers
                backgroundColor:  null
            }]
        },
        yAxis: {
            labels: {
                distance: -20
            },
            min: 0,
            max: 12,
            lineWidth: 0,
            showFirstLabel: false,

            minorTickInterval: 'auto',
            minorTickWidth: 1,
            minorTickLength: 5,
            minorTickPosition: 'inside',
            minorGridLineWidth: 0,
            minorTickColor: '#ed8b18',

            tickInterval: 1,
            tickWidth: 2,
            tickPosition: 'inside',
            tickLength: 10,
            tickColor: '#0b70b5',
            title: {
                text: '',
                style: {
                    color: '#BBB',
                    fontWeight: 'normal',
                    fontSize: '8px',
                    lineHeight: '10px'
                },
                y: 10
            }
        },
        tooltip: {
            formatter: function () {
                return this.series.chart.tooltipText;
            }
        },
        series: [{
            data: [{
                id: 'hour',
                y: now.hours,
                dial: {
                    radius: '60%',
                    baseWidth: 4,
                    baseLength: '95%',
                    rearLength: 0
                }
            }, {
                id: 'minute',
                y: now.minutes,
                dial: {
                    baseLength: '95%',
                    rearLength: 0
                }
            }, {
                id: 'second',
                y: now.seconds,
                dial: {
                    radius: '100%',
                    baseWidth: 1,
                    rearLength: '20%'
                }
            }],
            animation: false,
            dataLabels: {
                enabled: false
            }
        }]
    },

    // Move
    function (chart) {
        setInterval(function () {

            now = getNow();

            if (chart.axes) { // not destroyed
                var hour = chart.get('hour'),
                    minute = chart.get('minute'),
                    second = chart.get('second'),
                    // run animation unless we're wrapping around from 59 to 0
                    animation = now.seconds === 0 ?
                        false : {
                            easing: 'easeOutBounce'
                        };

                // Cache the tooltip text
                chart.tooltipText =
                    pad(Math.floor(now.hours), 2) + ':' +
                    pad(Math.floor(now.minutes * 5), 2) + ':' +
                    pad(now.seconds * 5, 2);

                hour.update(now.hours, true, animation);
                minute.update(now.minutes, true, animation);
                second.update(now.seconds, true, animation);
            }
        }, 1000);
    });

    /**
     * Easing function from https://github.com/danro/easing-js/blob/master/easing.js
     */
    Math.easeOutBounce = function (pos) {
        if ((pos) < (1 / 2.75)) { return (7.5625 * pos * pos); }
        if (pos < (2 / 2.75)) { return (7.5625 * (pos -= (1.5 / 2.75)) * pos + 0.75); }
        if (pos < (2.5 / 2.75)) { return (7.5625 * (pos -= (2.25 / 2.75)) * pos + 0.9375); }
        return (7.5625 * (pos -= (2.625 / 2.75)) * pos + 0.984375);
    };
    </script>

</body>

</html>