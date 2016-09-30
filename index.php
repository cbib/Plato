<?php
session_save_path('/tmp');
session_start();
include 'php/functions/html_functions.php';
include 'php/functions/php_functions.php';
html_header("", $_SESSION['login']);
index_html_top_page("Dashboard");

echo'
		<div class="container-fluid">
			<div class="row-fluid">
				<div class="span12">
					<div class="alert alert-info">
						Welcome to <strong>PLATO Database</strong>.
						<a href="#" data-dismiss="alert" class="close">×</a>
					</div>
					<div class="alert alert-warning">
						To provide us some feedback or declare an issue, please follow this link :<strong> https://github.com/cbib/Plato/issues </strong>
						<a href="#" data-dismiss="alert" class="close">×</a>
					</div>
					<div id="ierror">
					</div>';


					if (!isset($_SESSION['login'])) {
						echo'<div class="alert alert-error">
							You are not connected, please connect.
							<a href="#" data-dismiss="alert" class="close">×</a>
						</div>';
					}
					else {
						echo'<div class="alert alert-success">
							You are connected as '.$_SESSION['login'].'.
							<a href="#" data-dismiss="alert" class="close">×</a>
						</div>';
						        
					}

					echo'
					<div class="widget-box">
						<div class="widget-title">
							<span class="icon">
								<i class="icon-signal"></i>
							</span>
							<h5>Site Statistics</h5>
							<div class="buttons">
								<button type="button" name="refresh" id="refresh" class="btn btn-mini">
									<i class="icon-refresh"></i> 
									Update stats
								</button>
							</div>
						</div>
						<div class="widget-content">
							<div class="row-fluid">
								<div class="span4">
									<ul class="site-stats">
										<li><i class="icon-user"></i><strong><span id="user_compteur"></span></strong><small>Total Users</small></li>
										<li><i class="icon-arrow-right"></i> <strong><span id="experiment_compteur"></span></strong><small>Experiments</small></li>
										<li><i class="icon-arrow-right"></i> <strong><span id="batch_compteur"></span></span></strong><small>Batch</small></li>
										<li><i class="icon-arrow-right"></i><strong><span id="sample_compteur"></span></strong><small>Samples</small></li>
										<li><i class="icon-arrow-right"></i> <strong><span id="data_compteur"></span></strong> <small>Raw Data entered</small></li>
									</ul>
								</div>
								<div class="span8">
									<div class="chart" id="batchEvolution">
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="row-fluid">
				<div class="span6">
					<div class="widget-box">
						<div class="widget-title"><span class="icon"><i class="icon-file"></i></span><h5>Informations</h5></div>
						<div class="widget-content nopadding">
							<ul class="recent-posts">
								<li>
									<div class="user-thumb">
										<img alt="User" src="libs/themes/images/avatar2.png">
									</div>
									<div class="article-post">
										<span class="user-info"> By : Labadmin</span>
										<p>
											<a href="#">Bienvenue sur Plato</a>
										</p>
										<a class="btn btn-primary btn-mini" href="#" data-placement="right" data-original-title="Edit"><i class="icon-pencil"></i></a>
										<a class="btn btn-success btn-mini" href="#" data-placement="top" data-original-title="Approved"><i class="icon-ok"></i></a>
										<a class="btn btn-danger btn-mini" href="#" data-placement="left" data-original-title="Delete"><i class="icon-remove"></i></a>
									</div>
								</li>
							</ul>
						</div>
					</div>
				</div>
				<div class="span6">
					<div class="widget-box widget-calendar">
						<div class="widget-title"><span class="icon"><i class="icon-calendar"></i></span><h5>Calendar</h5></div>
						<div class="widget-content nopadding">
							<div class="calendar"></div>
						</div>
					</div>
				</div>
			</div>
		</div>';

html_footer("");
?>

<script type="text/javascript" class="init">


$(document).ready(function() {
	var isIE = /*@cc_on!@*/false || !!document.documentMode;

	if (isIE) {
		$("#ierror").html('<div class="alert alert-error"> You are using Internet Explorer, some errors might occure. Please use Chrome or Firefox.<a href="#" data-dismiss="alert" class="close">×</a></div>');
	}

});

refresh_stat();

$( "#refresh" ).click(function() {
	refresh_stat();
});

function refresh_stat(){
	$.ajax({
	   	url: 'php/index_functions/index_refresh.php',
        type: "GET",
	    data: {
	         fonction:'user_ctp',
	    },
	    success: function(data)
	    {
	    	// console.log(data);
			$('#user_compteur').text(data);
	    }
	});
	$.ajax({
	   	url: 'php/index_functions/index_refresh.php',
        type: "GET",
	    data: {
	         fonction:'batch_ctp',
	    },
	    success: function(data)
	    {
	    	// console.log(data);
			$('#batch_compteur').text(data);
	    }
	});
	$.ajax({
	   	url: 'php/index_functions/index_refresh.php',
        type: "GET",
	    data: {
	         fonction:'sample_cpt',
	    },
	    success: function(data)
	    {
	    	// console.log(data);
			$('#sample_compteur').text(data);
	    }
	});
	$.ajax({
	   	url: 'php/index_functions/index_refresh.php',
        type: "GET",
	    data: {
	         fonction:'experiment_cpt',
	    },
	    success: function(data)
	    {
	    	// console.log(data);
			$('#experiment_compteur').text(data);
	    }
	});
	$.ajax({
	   	url: 'php/index_functions/index_refresh.php',
        type: "GET",
	    data: {
	         fonction:'rawdata_cpt',
	    },
	    success: function(data)
	    {
	    	// console.log(data);
			$('#data_compteur').text(data);
	    }
	});
}


$(document).ready(function() {
	$.ajax({
		url: 'php/index_functions/index_refresh.php',
		type: 'GET',
		async: true,
		dataType: "json",
		data: {
			fonction:'batch_number_per_date',
		},
		success: function (data) {
			var serie1=[];
			$.each(data, function( index, datum ) {
				var arr = datum[0].split('-');
				Date.UTC(parseInt(arr[0],10),parseInt(arr[1],10),parseInt(arr[2],10))
				serie1[index]=[Date.UTC(parseInt(arr[0],10),parseInt(arr[1],10),parseInt(arr[2],10)),datum[1]*10];
			});
			$.ajax({
				url: 'php/index_functions/index_refresh.php',
				type: 'GET',
				async: true,
				dataType: "json",
				data: {
					fonction:'batch_cumul_per_date',
				},
				success: function (data2) {
					var serie2=[];
					$.each(data2, function( index, datum ) {
						var arr = datum[0].split('-');
						Date.UTC(parseInt(arr[0],10),parseInt(arr[1],10),parseInt(arr[2],10))
						serie2[index]=[Date.UTC(parseInt(arr[0],10),parseInt(arr[1],10),parseInt(arr[2],10)),datum[1]];
					});

					batchDataPrint(serie1, serie2);
				}
			});
		}
	});
});


function batchDataPrint(data, data2){
    $('#batchEvolution').highcharts({
        chart: {
            type: 'area',
            zoomType: 'x'
        },
        title: {
            text: 'Batch number evolution'
        },
        xAxis: {
                type: 'datetime'
        },
        yAxis: {
            title: {
                text: 'Batch number'
            }
        },
        tooltip: {
            formatter: function () {
                if (this.series.name == "Batch Number per day") {
                    return "<b>" + this.series.name + " : " + this.y/10;
                }
                else {
                	return "<b>" + this.series.name + " : " + this.y;
                }

            }
        },
        plotOptions: {
            area: {
                marker: {
                    enabled: false,
                    symbol: 'circle',
                    radius: 2,
                    states: {
                        hover: {
                            enabled: true
                        }
                    }
                },
				fillColor: {
                    linearGradient: {
                        x1: 0,
                        y1: 0,
                        x2: 0,
                        y2: 1
                    },
                    stops: [
                        [0, Highcharts.getOptions().colors[0]],
                        [1, Highcharts.Color(Highcharts.getOptions().colors[0]).setOpacity(0).get('rgba')]
                    ]
                },
                lineWidth: 1,
                states: {
                    hover: {
                        lineWidth: 1
                    }
                },
                
            }
        },
        series: [{
            name: 'Batch Number per day',
            data: data
        }, {
            name: 'Stacked Batch Number',
            data:  data2
        }]
    });
}


</script>