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
						Welcome to the <strong>PLATO Database</strong>. Coming soon... all the pages!
						<a href="#" data-dismiss="alert" class="close">×</a>
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
										<li>
											<i class="icon-spinner icon-spin"></i><strong>0</strong> <small>Pending Orders</small></li>
										<li>
											<i class="icon-user"></i>
											<strong>
												<span id="user_compteur"></span>
											</strong> 
											<small>Total Users</small>
										</li>
										<li><i class="icon-arrow-right"></i> <strong><span id="batch_compteur"></span></strong> <small>Batch</small></li>
										<li class="divider"></li>
										<li><i class="icon-shopping-cart"></i> <strong>259</strong> <small>Total Menu Items</small></li>
										<li><i class="icon-tag"></i> <strong>4808</strong> <small>Total Orders</small></li>
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
						<div class="widget-title"><span class="icon"><i class="icon-file"></i></span><h5>Recent Posts</h5><span title="54 total posts" class="label label-info tip-left">54</span></div>
						<div class="widget-content nopadding">
							<ul class="recent-posts">
								<li>
									<div class="user-thumb">
										<img width="40" height="40" alt="User" src="libs/themes/images/avatar2.png">
									</div>
									<div class="article-post">
										<span class="user-info"> By: neytiri on 2 Aug 2012, 09:27 AM, IP: 186.56.45.7 </span>
										<p>
											<a href="#">Vivamus sed auctor nibh congue, ligula vitae tempus pharetra...</a>
										</p>
									<a class="btn btn-primary btn-mini" href="#" data-placement="right" data-original-title="Edit"><i class="icon-pencil"></i></a>
										<a class="btn btn-success btn-mini" href="#" data-placement="top" data-original-title="Approved"><i class="icon-ok"></i></a>
										<a class="btn btn-danger btn-mini" href="#" data-placement="left" data-original-title="Delete"><i class="icon-remove"></i></a>	
									</div>
								</li>
								<li>
									<div class="user-thumb">
										<img width="40" height="40" alt="User" src="libs/themes/images/avatar3.png">
									</div>
									<div class="article-post">
										<span class="user-info"> By: john on on 24 Jun 2012, 04:12 PM, IP: 192.168.24.3 </span>
										<p>
											<a href="#">Vivamus sed auctor nibh congue, ligula vitae tempus pharetra...</a>
										</p>
										<a class="btn btn-primary btn-mini" href="#" data-placement="right" data-original-title="Edit"><i class="icon-pencil"></i></a>
										<a class="btn btn-success btn-mini" href="#" data-placement="top" data-original-title="Approved"><i class="icon-ok"></i></a>
										<a class="btn btn-danger btn-mini" href="#" data-placement="left" data-original-title="Delete"><i class="icon-remove"></i></a>	
									</div>
								</li>
								<li>
									<div class="user-thumb">
										<img width="40" height="40" alt="User" src="libs/themes/images/avatar1.png">
									</div>
									<div class="article-post">
										<span class="user-info"> By: michelle on 22 Jun 2012, 02:44 PM, IP: 172.10.56.3 </span>
										<p>
											<a href="#">Vivamus sed auctor nibh congue, ligula vitae tempus pharetra...</a>
										</p>
										<a class="btn btn-primary btn-mini" href="#" data-placement="right" data-original-title="Edit"><i class="icon-pencil"></i></a>
										<a class="btn btn-success btn-mini" href="#" data-placement="top" data-original-title="Approved"><i class="icon-ok"></i></a>
										<a class="btn btn-danger btn-mini" href="#" data-placement="left" data-original-title="Delete"><i class="icon-remove"></i></a>
										</div>
								</li>
								<li class="viewall">
									<a title="View all posts" class="tip-top" href="#"> + View all + </a>
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
}

$(function () {
	$.ajax({
	   	url: 'php/index_functions/index_refresh.php',
        type: "GET",
	    data: {
	         fonction:'batch_number_per_date',
	    },
	    success: function(data)
	    {
	    	// console.log(data);
			$('#batchEvolution').highcharts({
            chart: {
                zoomType: 'x'
            },
            title: {
                text: 'Batch number evolution'
            },
            subtitle: {
                text: document.ontouchstart === undefined ?
                        'Click and drag in the plot area to zoom in' : 'Pinch the chart to zoom in'
            },
            xAxis: {
                type: 'datetime',
                dateTimeLabelFormats: {
					year: '%Y'
            	}
            },
            yAxis: {
                title: {
                    text: 'Batch Number'
                }
            },
            legend: {
                enabled: false
            },
            plotOptions: {
                area: {
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
                    marker: {
                        radius: 2
                    },
                    lineWidth: 1,
                    states: {
                        hover: {
                            lineWidth: 1
                        }
                    },
                    threshold: null
                }
            },

            series: [{
                type: 'area',
                name: 'Batch Number',
                data: [["2006-02-17",1],["2006-02-23",2],["2006-02-27",2],["2006-03-01",2],["2006-03-06",2],["2006-03-08",2],["2006-03-09",1],["2006-03-14",1],["2006-03-15",4],["2006-03-20",4],["2006-03-21",1],["2006-03-23",1],["2006-03-28",2],["2006-03-30",1],["2006-04-04",2],["2006-04-06",2],["2006-04-10",5],["2006-04-11",2],["2006-04-19",1],["2006-04-24",1],["2006-04-28",3],["2006-05-09",4],["2006-05-11",3],["2006-05-16",4],["2006-05-18",4],["2006-05-19",1],["2006-05-23",3],["2006-05-24",2],["2006-06-20",2],["2006-07-07",1],["2006-07-10",1],["2006-07-11",2],["2006-07-18",5],["2006-07-20",4],["2006-07-21",3],["2006-07-25",4],["2006-07-27",4],["2006-08-01",1],["2006-08-07",3],["2006-08-10",2],["2006-08-15",1],["2006-08-16",1],["2006-08-18",1],["2006-08-29",2],["2006-08-31",4],["2006-09-06",2],["2006-09-07",3],["2006-09-11",2],["2006-09-12",2],["2006-09-13",2],["2006-09-14",2],["2006-09-18",3],["2006-09-26",3],["2006-09-27",1],["2006-09-29",3],["2006-10-05",3],["2006-10-13",1],["2006-10-19",1],["2006-10-20",2],["2006-10-23",2],["2006-10-24",2],["2006-10-26",2],["2006-10-30",2],["2006-10-31",2],["2006-11-01",4],["2006-11-02",2],["2006-11-06",2],["2006-11-07",1],["2006-11-08",4],["2006-11-09",2],["2006-11-10",3],["2006-11-11",2],["2006-11-12",2],["2006-11-16",5],["2006-11-17",2],["2006-11-18",2],["2006-11-20",2],["2006-11-27",2],["2006-11-28",2],["2006-11-29",3],["2006-11-30",4],["2006-12-04",2],["2006-12-05",2],["2006-12-07",5],["2006-12-08",1],["2006-12-11",2],["2006-12-12",4],["2007-01-08",2],["2007-01-15",2],["2007-01-18",2],["2007-01-22",2],["2007-01-29",2],["2007-02-05",4],["2007-02-07",1],["2007-02-08",3],["2007-02-12",2],["2007-02-14",3],["2007-02-19",2],["2007-02-27",6],["2007-03-01",2],["2007-03-05",2],["2007-03-13",2],["2007-03-14",1],["2007-04-16",6],["2007-04-17",3],["2007-04-18",3],["2007-04-25",3],["2007-04-27",2],["2007-05-10",9],["2007-05-21",6],["2007-05-29",2],["2007-05-30",3],["2007-05-31",6],["2007-06-05",5],["2007-06-15",4],["2007-06-18",3],["2007-06-19",1],["2007-07-17",2],["2007-07-23",4],["2007-07-31",3],["2007-08-20",2],["2007-08-30",5],["2007-09-20",2],["2007-09-26",2],["2007-09-28",2],["2007-10-02",2],["2007-10-09",2],["2007-10-12",2],["2007-11-16",36],["2009-12-07",7],["2009-12-11",1],["2010-12-08",3],["2010-12-15",2],["2011-01-17",1],["2011-01-26",1],["2011-02-08",1],["2011-02-09",2],["2011-02-21",1],["2011-03-08",1],["2011-03-31",13],["2011-04-01",2],["2011-04-06",2],["2011-04-14",5],["2011-04-28",4],["2011-05-13",2],["2011-05-19",6],["2011-05-24",4],["2011-05-27",3],["2011-05-31",2],["2011-06-09",1],["2011-06-10",1],["2011-06-15",3],["2011-06-29",12],["2011-06-30",6],["2011-08-01",2],["2011-08-03",2],["2011-08-04",5],["2011-08-19",7],["2011-08-24",4],["2011-08-26",7],["2011-09-12",7],["2011-09-23",2],["2011-09-26",45],["2011-09-29",4],["2011-09-30",3],["2011-10-14",8],["2011-10-20",4],["2011-11-07",1],["2011-11-08",1],["2011-11-23",7],["2011-12-07",2],["2011-12-14",2],["2011-12-19",4],["2012-01-18",2],["2012-01-26",3],["2012-01-27",12],["2012-02-08",9],["2012-02-27",5],["2012-02-29",13],["2012-03-01",8],["2012-03-05",13],["2012-03-06",4],["2012-03-07",8],["2012-03-15",10],["2012-03-16",8],["2012-03-20",32],["2012-03-24",2],["2012-04-02",1],["2012-04-03",17],["2012-04-04",6],["2012-04-05",1],["2012-04-06",1],["2012-04-12",2],["2012-04-17",4],["2012-04-19",4],["2012-05-03",3],["2012-05-04",1],["2012-05-07",4],["2012-05-23",15],["2012-05-25",1],["2012-06-18",1],["2012-06-19",2],["2012-07-18",10],["2012-07-27",1],["2012-07-30",14],["2012-07-31",4],["2012-08-01",12],["2012-08-10",10],["2012-09-13",4],["2012-09-24",25],["2012-10-10",8],["2012-10-17",1],["2012-10-18",1],["2012-10-22",2],["2012-10-25",4],["2012-10-29",5],["2012-11-09",2],["2012-11-13",2],["2012-11-14",5],["2012-11-15",1],["2012-11-27",1],["2012-12-03",1],["2012-12-04",4],["2012-12-06",15],["2012-12-07",1],["2012-12-18",16],["2012-12-27",1],["2013-01-15",2],["2013-01-18",7],["2013-02-28",2],["2013-03-05",13],["2013-03-11",7],["2013-03-12",2],["2013-03-19",2],["2013-03-29",4],["2013-04-05",4],["2013-04-09",9],["2013-04-10",5],["2013-04-12",10],["2013-04-18",1],["2013-04-19",1],["2013-04-22",4],["2013-04-24",4],["2013-04-25",1],["2013-04-29",2],["2013-05-06",4],["2013-05-17",8],["2013-05-30",4],["2013-06-19",7],["2013-06-20",23],["2013-06-25",9],["2013-07-03",8],["2013-07-09",4],["2013-07-10",1],["2013-07-15",1],["2013-07-16",14],["2013-07-25",16],["2013-08-01",1],["2013-08-12",4],["2013-08-22",20],["2013-11-04",1],["2013-11-05",2],["2013-11-07",3],["2013-11-08",10],["2013-11-12",5],["2013-11-15",14],["2013-11-21",28],["2014-01-27",21],["2014-01-28",7],["2014-02-04",2],["2014-02-05",6],["2014-03-25",8],["2014-04-08",36],["2014-04-17",3],["2014-04-18",3],["2014-04-23",4],["2014-05-02",1],["2014-05-05",1],["2014-05-09",1],["2014-05-13",2],["2014-05-14",3],["2014-06-04",1],["2014-06-11",6],["2014-06-12",2],["2014-06-19",2],["2014-06-20",7],["2014-07-07",4],["2014-07-08",6],["2014-07-11",6],["2014-07-18",10],["2014-08-04",7],["2014-08-07",5],["2014-08-14",2],["2014-08-27",2],["2014-09-04",8],["2014-09-05",8],["2014-09-10",7],["2014-09-19",6],["2014-09-30",13],["2014-11-18",1],["2014-11-20",5],["2014-11-27",1],["2014-12-09",2],["2014-12-10",8],["2014-12-11",9],["2014-12-15",1],["2015-01-12",4],["2015-02-04",8],["2015-02-05",1],["2015-02-06",13],["2015-02-24",3],["2015-02-27",12],["2015-03-02",1],["2015-03-05",14],["2015-03-11",2],["2015-03-13",1],["2015-03-17",7],["2015-04-22",18],["2015-04-29",2],["2015-05-07",1],["2015-05-11",2],["2015-05-12",1],["2015-06-02",1],["2015-06-09",5],["2015-06-16",2],["2015-06-17",8],["2015-06-22",1],["2015-06-23",4],["2015-07-01",4],["2015-07-02",5],["2015-07-07",3],["2015-07-22",2],["2015-07-23",1],["2015-08-06",1],["2015-08-07",2],["2015-09-07",7],["2015-09-08",16],["2015-09-17",6],["2015-09-21",22],["2015-10-13",5],["2015-11-17",1],["2015-11-20",1]],
            	pointStart: Date.UTC(2006, 02, 17),
            	pointInterval: 24 * 3600 * 1000*10 // one day
            }]
        });
	    }
	});
});

</script>