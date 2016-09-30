<?php
require('../functions/check_login.php');
include '../functions/html_functions.php';
include '../functions/php_functions.php';

html_header("../../",  $_SESSION['login']);

generic_html_top_page("../../","Charts");

echo' 
<div class="row">
  <div class="col-md-12 col-lg-12 col-xl-12" id="analytesUse"></div>
</div>
<hr/>
<div class="row">
  <div class="col-md-12 col-lg-12 col-xl-12" id="sizeOfDatabase"></div>
</div>
<hr/>
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
		success: function (dataSize) {
            console.log(dataSize);
			printSize(parseInt(dataSize["plato_export_02052016"]));
		}
	});

	$.ajax({
		url: 'charts_functions.php',
		type: 'GET',
		async: true,
		dataType: "json",
		data: {
			fonction:'analyte_distribution',
		},
		success: function (data) {
			// console.log(data);
			printAnalyteDistrib(data);
		}
	});
});


function printAnalyteDistrib(data){
	// console.log(data);
    $('#analytesUse').highcharts({
        chart: {
            type: 'column',
            zoomType: 'x'
        },
        title: {
            text: 'How often Analytes are used'
        },
        xAxis: {
            type: 'category',
            labels: {
                rotation: -60,
                style: {
                    fontSize: '11px',
                    fontFamily: 'Verdana, sans-serif'
                }
            }
        },
        yAxis: {
            min: 0,
            title: {
                text: 'Use of analytes'
            }
        },
        legend: {
            enabled: false
        },
        tooltip: {
            pointFormat: 'Used: <b>{point.y} times</b>'
        },
        series: [{
            name: 'Number',
            data: data,
            dataLabels: {
                enabled: true,
                rotation: -90,
                color: '#FFFFFF',
                align: 'right',
                format: '{point.y}', // one decimal
                y: 9, // 10 pixels down from the top
                style: {
                    fontSize: '10px',
                    fontFamily: 'Verdana, sans-serif'
                }
            }
        }]
    });
}



function printSize(dataSize) {
	console.log(dataSize);
    var gaugeOptions = {
        chart: {
            type: 'solidgauge'
        },
        title : 'Free space in database',
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
    $('#sizeOfDatabase').highcharts(Highcharts.merge(gaugeOptions, {
        yAxis: {
            min: 0,
            max: 20000,
            title: {
                text: 'Space used'
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
                       '<span style="font-size:12px;color:silver">Mb used over 20Gb</span></div>'
            },
            tooltip: {
                valueSuffix: ' Mb'
            }
        }]

    }));

}




</script>
