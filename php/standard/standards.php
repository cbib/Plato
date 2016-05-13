<?php
require('../functions/check_login.php');
include '../functions/html_functions.php';
include '../functions/php_functions.php';

html_header("../../",  $_SESSION['login']);

generic_html_top_page("../../","Biological Standard");


echo'
<span id="statusSpan">
</span>';

/**
 * Initialize standards table
 */
echo'
<div class="row-fluid">
	<div class="span5">
		<div class="widget-box" style="background : transparent">
			<div class="widget-content nopadding">
				<table id="stdTable" class="table display table-striped table-bordered table-hover" style="width:100%">
					<thead>
						<tr>
							<th>ID</th>
							<th>Name</th>
							<th>Genius</th>
							<th>Species</th>
							<th>Genotype</th>
							<th>Nature</th>
							<th>Owner</th>
							<th>Comment</th>
						</tr>
					</thead>
				</table>
			</div>
		</div>
	</div>';

/**
 * Initialize enzymes table
 */
echo'
	<div class="span7">
		<div id=analyte-wrapper>
			<div class="widget-box" style="background : transparent">
				<div class="widget-content nopadding">
					<div class="tab-content">
						<table id="ezTable" class="table table-striped table-bordered table-hover" style="width:100%">
							<thead>
								<tr>
									<th>Analyte</th>
									<th>Code</th>
									<th>Slope</th>
									<th>Nature</th>
									<th>Amount</th>
									<th>Unit</th>
									<th>Dilution | Volume</th>
								</tr>
							</thead>
							<tbody>
							</tbody>
						</table>
					</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>';

html_footer("../../");
?>

<script type="text/javascript" class="init">

	//Hide analyte table until a standard is selected
	$('#analyte-wrapper').hide();
	var standardID ="";


	$(document).ready(function() {
		// Keep the submenu open while navigating
		$('#userPanel').removeClass('submenu');
		$('#userPanel').addClass('submenu open');

		//Fill standard table
		setup_stdTable();

		// Listener on standard table
		$('#stdTable').on('click', 'tr', function () {
			standardID = $('td', this).eq(0).text();
			if(standardID === "") {
			}
			else {
				//Fill enzyme table
				setup_enzymeTable(standardID);
				$('#analyte-wrapper').show();
			}
		});
	});


/**
 * Initialize datatable, also used on refresh
 *
 * @method     setup_stdTable
 */
function setup_stdTable(){
	var table = $('#stdTable').DataTable({
        scrollY:        400,
        scroller: true, 
		dom: 'TB<"clear">frtip',
		ajax: 'standard_get_all_standard.php',
		select : "single",
		"columnDefs": [ 
			{
				"visible": false,
				"targets":3
			},
			{
				"visible": false,
				"targets":5
			},
			{
				"visible": false,
				"targets":6
			} 
		],
		buttons: [
			'copy', 'csv', 'excel', 'pdf', 'print', 'colvis'
		]
	});
}


/**
 * Reload enzyme datatable
 *
 * @method     refresh_enzyme
 * @param      {<type>}  standardID  { description }
 */
function setup_enzymeTable(standardID) {
	//Destroy enzyme table to rewritte each time a standard is selected
	$('#ezTable').dataTable().fnDestroy();
	var oTable = $("#ezTable").DataTable({ 
		paging: false,
		dom: 'TB<"clear">frtip',
		responsive:true,
		ajax:{
			url :"enzyme_from_standard.php", // json datasource
			type: "POST",  // by default get
			data: { standardID : standardID }
		},
		buttons: [
			'copy', 'csv', 'excel', 'pdf', 'print'
		]
	}); 
}

</script>