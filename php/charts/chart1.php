<?php
require('../functions/check_login.php');
include '../functions/html_functions.php';
include '../functions/php_functions.php';

html_header("../../",  $_SESSION['login']);

generic_html_top_page("../../","Charts");

echo' <div style="width: 100%; height: 600px; margin: 0 auto">
    <div id="container-speed" style="width: 40%; height: 500px; float: left"></div>
</div>
';

html_footer("../../");
?>

<script type="text/javascript" class="init">

$(document).ready(function() {
	$.ajax({
		url: 'charts_functions.php',
		type: 'GET',
		async: true,
		dataType: "json",
		data: {
			fonction:'sizeOfDb',
		},
		success: function (data) {
			printSize(parseInt(data["plato_export_02052016"]));
		}
	});
});



function printSize(dataSize) {

    var gaugeOptions = {
        chart: {
            type: 'solidgauge'
        },
        title : null,
        pane: {
            center: ['50%', '85%'],
            size: '140%',
            startAngle: -90,
            endAngle: 90,
            background: {
                backgroundColor: (Highcharts.theme && Highcharts.theme.background2) || '#EEE',
                innerRadius: '60%',
                outerRadius: '100%',
                shape: 'arc'
            }
        },

        tooltip: {
            enabled: false
        },
        // the value axis
        yAxis: {
            stops: [
                [0.1, '#55BF3B'], // green
                [0.5, '#DDDF0D'], // yellow
                [0.9, '#DF5353'] // red
            ],
            lineWidth: 0,
            minorTickInterval: null,
            tickPixelInterval: 400,
            tickWidth: 0,
            title: {
                y: -70
            },
            labels: {
                y: 16
            }
        },

        plotOptions: {
            solidgauge: {
                dataLabels: {
                    y: 5,
                    borderWidth: 0,
                    useHTML: true
                }
            }
        }
    };

    // The speed gauge
    $('#container-speed').highcharts(Highcharts.merge(gaugeOptions, {
        yAxis: {
            min: 0,
            max: 2000,
            title: {
                text: 'Used space'
            }
        },
        credits: {
            enabled: false
        },
        series: [{
            name: 'Space',
            data: [dataSize],
            dataLabels: {
                format: '<div style="text-align:center"><span style="font-size:25px;color:' +
                    ((Highcharts.theme && Highcharts.theme.contrastTextColor) || 'black') + '">{y}</span><br/>' +
                       '<span style="font-size:12px;color:silver">Mb</span></div>'
            },
            tooltip: {
                valueSuffix: ' Mb'
            }
        }]

    }));

}




</script>