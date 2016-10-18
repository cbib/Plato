<?php
require('../functions/check_login.php');
include '../functions/html_functions.php';

html_header("../../", $_SESSION['login']);

generic_html_top_page("../../","Analyte Editor");

echo'
<div style="width: 80%; margin: 0px auto;">
<div class="row-fluid">
	<div class="span10">
		<div class="widget-box" style="background : transparent">
			<div class="widget-content nopadding">
				<table cellpadding="0" cellspacing="0" border="0" class="table table-striped table-bordered" id="enzyme" width="100%">
					<thead>
						<tr>
							<th>ID</th>
							<th>Analyte</th>
							<th>Code</th>
							<th>Slope</th>
							<th>Nature</th>
						</tr>
					</thead>
				</table>
			</div>
		</div>
	</div>
</div>
';
html_footer("../../");
?>



<script type="text/javascript" class="init">

$(document).ready(function() {
	$('#userPanel').removeClass('submenu');
	$('#userPanel').addClass('submenu open');
	setup_datatable();
});


function setup_datatable(){
	$('#enzyme').dataTable().fnDestroy();
	var table = $('#enzyme').DataTable({
		scrollY:        500,
        scroller:       true,
		dom: 'TB<"clear">frtip',
		ajax: 'analytes_database_functions.php',
		buttons: [
			'copy', 'csv', 'excel', 'print', 'colvis'
		],
		"columnDefs": [ {
            "targets":-1,
            "defaultContent": "<button type=\"button\" id=\"editButton\" class=\"tabledit-edit-button btn btn-info btn-sm btn-default\" style=\"float: none;\"> <span class=\"glyphicon glyphicon-pencil\"> </span> </button>"+
             "&nbsp <button type= \"submit\" id=\"deleteButton\" class=\"tabledit-edit-button btn btn-danger btn-sm btn-default\" style=\"float: none;\"> <span class=\"glyphicon glyphicon-trash\"> </span> </button>"
        } ]
	});
}

</script>