<?php 

function html_header ($path, $userLevel) {

echo '
<!DOCTYPE html>
<html lang="en">
	<head>
		<title>PLATO DataBase</title>
		
		<meta http-equiv="content-type" content="text/html; charset=utf-8" />
		<meta name="viewport" content="width=device-width, initial-scale=1.0" />

		<!-- ***** CSS ***** -->
		<!-- -->
		<!-- bootstrap 3.0.2 -->
		<!-- Datatable plugin bootstrap -->
			<link rel="stylesheet" type="text/css" href="'.$path.'libs/datatables/Bootstrap-3.3.5/css/bootstrap.min.css"/>
			<link rel="stylesheet" type="text/css" href="'.$path.'libs/datatables/DataTables-1.10.10/css/dataTables.bootstrap.min.css"/>
			<link rel="stylesheet" type="text/css" href="'.$path.'libs/datatables/Buttons-1.1.0/css/buttons.bootstrap.min.css"/>
			<link rel="stylesheet" type="text/css" href="'.$path.'libs/datatables/Responsive-2.0.0/css/responsive.bootstrap.min.css"/>
			<link rel="stylesheet" type="text/css" href="'.$path.'libs/datatables/Scroller-1.4.0/css/scroller.bootstrap.min.css"/>
			<link rel="stylesheet" type="text/css" href="'.$path.'libs/datatables/Select-1.1.0/css/select.bootstrap.min.css"/>

			<link rel="stylesheet/less" type="text/css" href="'.$path.'libs/themes/less/bootstrap.less"/>
			<link rel="stylesheet" type="text/css" href="'.$path.'libs/themes/style/delta.main.css" />
			<link rel="stylesheet" type="text/css" href="'.$path.'libs/themes/style/delta.grey.css"/>

			<script> less = {env: "development"};</script>
			<script type="text/javascript" src="'.$path.'libs/themes/js/less/less.js"></script>

			
			<script type="text/javascript" src="'.$path.'libs/datatables/jQuery-2.1.4/jquery-2.1.4.min.js"></script>
			<script type="text/javascript" src="'.$path.'libs/datatables/Bootstrap-3.3.5/js/bootstrap.min.js"></script>
			<script type="text/javascript" src="'.$path.'libs/datatables/pdfmake-0.1.18/build/pdfmake.min.js"></script>
			<script type="text/javascript" src="'.$path.'libs/datatables/pdfmake-0.1.18/build/vfs_fonts.js"></script>
			<script type="text/javascript" src="'.$path.'libs/datatables/DataTables-1.10.10/js/jquery.dataTables.min.js"></script>
			<script type="text/javascript" src="'.$path.'libs/datatables/DataTables-1.10.10/js/dataTables.bootstrap.min.js"></script>
			<script type="text/javascript" src="'.$path.'libs/datatables/Buttons-1.1.0/js/dataTables.buttons.min.js"></script>
			<script type="text/javascript" src="'.$path.'libs/datatables/Buttons-1.1.0/js/buttons.bootstrap.min.js"></script>
			<script type="text/javascript" src="'.$path.'libs/datatables/Buttons-1.1.0/js/buttons.colVis.min.js"></script>
			<script type="text/javascript" src="'.$path.'libs/datatables/Buttons-1.1.0/js/buttons.flash.min.js"></script>
			<script type="text/javascript" src="'.$path.'libs/datatables/Buttons-1.1.0/js/buttons.html5.min.js"></script>
			<script type="text/javascript" src="'.$path.'libs/datatables/Buttons-1.1.0/js/buttons.print.min.js"></script>
			<script type="text/javascript" src="'.$path.'libs/datatables/Responsive-2.0.0/js/dataTables.responsive.min.js"></script>
			<script type="text/javascript" src="'.$path.'libs/datatables/Responsive-2.0.0/js/responsive.bootstrap.min.js"></script>
			<script type="text/javascript" src="'.$path.'libs/datatables/Scroller-1.4.0/js/dataTables.scroller.min.js"></script>
			<script type="text/javascript" src="'.$path.'libs/datatables/Select-1.1.0/js/dataTables.select.min.js"></script>


			

	</head>
	<body>
		<br>
		<div id="sidebar"> 
			<h1 id="logo"></h1>  
			<a href="#" class="visible-phone"><i class="icon icon-home"></i> Dashboard</a>
			<ul>
				<li class="active"><a href="'.$path.'index.php"><i class="icon icon-home"></i> <span>Dashboard</span></a></li>
				<li class="submenu" id="userPanel">
					<a href="#"><i class="icon icon-th-list"></i> <span>Database</span> <span class="label">5</span></a>
					<ul>
						<li><a href="'.$path.'php/standard/standards.php">Standards</a></li>
						<li><a href="'.$path.'php/freshweight/freshweight.php">Freshweight</a></li>
						<li><a href="'.$path.'php/batches/batches.php">Batches</a></li>
						<li><a href="'.$path.'php/processing/processing.php">Raw Data Processing</a></li>
						<li><a href="'.$path.'php/export/export.php">Export</a></li>
					</ul>
				</li>';
				if ($userLevel == "admin"){
				echo'	<li class="submenu" id="adminPanel">
						<a href="#"><i class="icon icon-pencil"></i> <span>Admin Panel</span> <span class="label">5</span></a>
						<ul>
							<li><a href="'.$path.'php/admin/adminEnzyme/adminEz.php">Analytes Editor</a></li>
							<li><a href="'.$path.'php/admin/adminStandard/adminStd.php">Standards Editor</a></li>
							<li><a href="'.$path.'php/admin/adminExperiment/adminExp.php">Experiment Editor</a></li>
							<li><a href="'.$path.'php/admin/adminRawdata/adminRawdata.php">RawData Editor</a></li>
							<li><a href="'.$path.'php/admin/adminPwd/adminPwd.php">User Management</a></li>
						</ul>
					</li>';
				}
				echo'
				<li><a href="#"><i class="icon icon-th"></i> <span>Stocks</span></a></li>
				<li><a href="#"><i class="icon icon-th"></i> <span>Protocols</span></a></li>
				<li><a href="#"><i class="icon icon-th-list"></i> <span>Equations</span></a></li>
				<li class="submenu" id="chartsPanel">
					<a href="#"><i class="icon icon-signal"></i> <span>Charts &amp; graphs</span> <span class="label">1</span></a>
					<ul>
						<li><a href="'.$path.'php/charts/chart1.php">Charts</a></li>
					</ul>
				</li>
			</ul>
		</div>
';
}

function index_html_top_page($pagename) {
	echo '
		<div id="mainBody">
			<h1>'.$pagename.'
				<div class="pull-right">
					<a class="btn btn-large btn-info" data-toggle="modal" data-target="#login-modal"><i class="icon icon-user"></i> Login</a>
					<!--<a class="btn btn-large" title="" href="#"><i class="icon icon-cog"></i> Settings</a>-->
					<a class="btn btn-large btn-danger" title="" href="php/functions/logout.php"><i class="icon-off"></i></a>
				</div>
			</h1>
			<div id="breadcrumb">
				<a href="#" title="Go to Home" class="tip-bottom"><i class="icon-home"></i>'.$pagename.'</a>
			</div>';
			echo'
			<div class="modal fade" id="login-modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
				<div class="modal-dialog">
					<div class="loginmodal-container">
						<h1>Login to Your Account</h1><br>
				 		<form id="formLogin" method="post" action="php/functions/login.php">
							<input type="text" name="user" placeholder="Username">
							<input type="password" name="pass" placeholder="Password">
							<input type="submit" name="login" class="login loginmodal-submit" value="Login">
						</form>
					</div>
				</div>
			</div>
';

}

function editable_html_top_page($path, $pagename) {
	echo '<div id="mainBody">
			<h1>'.$pagename.'
				<div class="pull-right">
					<a class="btn btn-large btn-danger" title="" href="'.$path.'php/functions/logout.php"><i class="icon-off"></i></a>
				</div>
			</h1>
			<div id="breadcrumb">
				<a href="#" title="Go to Home" class="tip-bottom"><i class="icon-home"></i>'.$pagename.'</a>';
			}

function generic_html_top_page($path, $pagename) {
	echo '<div id="mainBody">
			<h1>'.$pagename.'
				<div class="pull-right">
					<a class="btn btn-large btn-danger" title="" href="'.$path.'php/functions/logout.php"><i class="icon-off"></i></a>
				</div>
			</h1>
			<div id="breadcrumb">
				<a href="#" title="Go to Home" class="tip-bottom"><i class="icon-home"></i>'.$pagename.'</a>
			</div>';
}

function html_footer($path) {
echo'			
				<div class="row-fluid">
					<div id="footer" class="span12">
						<h1 style="font-size:xx-small">Free Bootstrappage Admin. Brought to you by<a id="poweredBy" href="https://github.com/almasaeed2010/AdminLTE" target="_blank"> Almasaeed Studio</a></h1>
					</div>
				</div>
			</div>
		<script type="text/javascript" src="'.$path.'libs/themes/js/jquery.flot.min.js"></script>
		<script type="text/javascript" src="'.$path.'libs/themes/js/jquery.flot.resize.min.js"></script>
		<script type="text/javascript" src="'.$path.'libs/themes/js/jquery.peity.min.js"></script>
		<script type="text/javascript" src="'.$path.'libs/themes/js/fullcalendar.min.js"></script>
		<script type="text/javascript" src="'.$path.'libs/themes/js/delta.js"></script>
		<script type="text/javascript" src="'.$path.'libs/js/highcharts.js"></script>
		<script type="text/javascript" src="'.$path.'libs/js/highcharts-more.js"></script>
		<script type="text/javascript" src="'.$path.'libs/js//solid-gauge.js"></script>
		<script type="text/javascript" src="'.$path.'libs/js/exporting.js"></script>
	</body>
</html>';
}


?>