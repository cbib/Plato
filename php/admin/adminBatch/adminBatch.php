<?php
require('../../functions/check_login_admin.php');
include ('../../functions/html_functions.php');
// include ('../../functions/php_functions.php');

html_header("../../../", $_SESSION['login']);

generic_html_top_page("../../../","Batches");

echo'
<span id="statusSpan">
</span>';

echo'
<!-- Modal -->
<div class="modal fade" id="editRowModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
</div> <!-- /.modal -->
<div class="row-fluid">
	<div class="span6" collapse-group>
		<div class="widget-box" style="background : transparent">
			<div class="widget-content nopadding">
				<div id="expender-wrapper">
					<button type="button" class="btn btn-xs btn-info" id ="expender" style="width:100%"><i class="icon-list"></i> &nbsp; Expend experiment table </button>
				</div>
				<div id="datatable-wrapper">
					<table id="expTable" class="table display table-striped table-bordered table-hover" style="width:100%">
						<thead>
							<tr>
								<th>ID</th>
								<th>Name</th>
							</tr>
						</thead>
					</table>
				</div>
			</div>
		</div>
	</div>';

	echo'
	<div class="span6">
		<div id="select-wrapper">
			<h4> Select Batch </h4>
			<div class="form-group">
				<select id="selectBatch" class="form-control">';
					echo '<option value=""> ----- Select ----- </option>';
				echo' </select>
			</div>
		</div>
		<div id="select-ez-wrapper">
			<h4> Select Data </h4>
			<div class="form-group">
				<select id="selectEz" class="form-control">';
					echo '<option value=""> ----- Select ----- </option>';
				echo' </select> 
			</div>
		</div>
	</div>
</div>
	';
html_footer("../../../");
?>



<script type="text/javascript" class="init">

$(document).ready(function() {
	$('#adminPanel').removeClass('submenu');
	$('#adminPanel').addClass('submenu open');
	$('#table-wrapper').hide();
	$('#select-wrapper').hide();
	$('#expender-wrapper').hide();
	$('#select-ez-wrapper').hide();
	setup_experiment_datatable();
	var expID =-1;
	var batchID=-1;
	var ezID= -1;
	var ezName = "";

	$('#expTable').on('click', 'tr', function () {
		expID = $('td', this).eq(0).text();
		$.ajax({
			url: "adminRawdata_get_batches.php",
			type: "post",
			data: { expID : expID },
			success: function(data) {
				var output = [];
				output.push('<option value=""> ----- Select ----- </option>');
				$.each(data, function(key, value)
				{
				  output.push('<option value="', key, '">', value, '</option>');
				});
				$('#selectBatch').html(output.join('')); 
				$("#select-wrapper").show();
				$("#select-ez-wrapper").hide();
			}
		});
	});

	$( "#selectBatch" ).click(function() {
		batchID = $( "#selectBatch" ).val();
		console.log("batch ID : "+batchID);
	});


	$('#selectBatch').change(function(){
		batchID = $( "#selectBatch" ).val();
		construct_delete_modal(batchID, "remove");
	});

});

$(document).on("click", "#editSubmit", function(e){
	var batchID = $("#batchID").val();
	console.log("click delete : => batch "+batchID );
		$.ajax({
		url: "adminBatchUpdateData.php",
		type: "post",
		data: { 
			batchID : batchID
		},
		success: function(data) {
			console.log("ok");
			$('#statusSpan').html('<div class="alert alert-success"> Deletion Successful<a href="#" data-dismiss="alert" class="close">×</a></div>');
		}
	});
	$("#editRowModal").modal("hide");
});

function setup_experiment_datatable(){
	$('#expTable').dataTable().fnDestroy();
	var table = $('#expTable').DataTable({
		scrollY:        400,
		scroller:       true,
		stateSave: true,
		//responsive: true,
		select: true,
		paging : true,
		dom: 'T<"clear">frtip',
		ajax: 'adminRawdata_database_functions.php',
		order: [[ 1, 'asc' ]],
		"columnDefs": [ {
			"targets":0,
			"width": "20px"
		} ]
	});
}

function construct_delete_modal(batchID, action){
	var modal ='<div class="modal-dialog">'+
	'<div class="loginmodal-container">'+
	    		'<h1>Confirm Deletion</h1><br>'+
	  			'<h6> Are you sure you want to remove the Batch : '+batchID+' ?</h6>'+
	  			'<form role="form" id="editRowForm" method="post">'+
  					'<input type="hidden" class="form-control required" id="action" name="action" value="'+action+'">'+
	               	'<input type="hidden" class="form-control required" id="batchID" name="batchID" value="'+batchID+'" disabled="disabled" /><br>'+
				'</form>'+
				'<div class="modal-footer">'+
					'<button type="button" data-dismiss="modal" class="btn btn-large">Cancel</button>'+
					'<button type="submit" id="editSubmit" class="btn btn-primary btn-large">Delete</button>'+
				'</div>'+
		'</div> <!-- /.modal-content -->'+
	'</div> <!-- /.modal-dialog -->';
	$('#editRowModal').empty();
	$('#editRowModal').append(modal);
	$("#editRowModal").modal("show");
}


</script>