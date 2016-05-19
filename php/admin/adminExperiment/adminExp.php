<?php
require('../../functions/check_login_admin.php');
include ('../../functions/html_functions.php');
// include ('../../functions/php_functions.php');

html_header("../../../", $_SESSION['login']);

editable_html_top_page("../../../","Experiment Editor");

echo '<a href="#" title="Create a new Analyte" class="tip-bottom" id="createStandard"><i class="icon-plus"></i>Create a New Experiment</a>
</div>';
echo'
<span id="statusSpan">
</span>';

echo'
<!-- Modal -->
<div class="modal fade" id="editRowModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
</div> 
<!-- /.modal -->

<div style="width: 80%; margin: 0px auto;">
<div class="row-fluid">
	<div class="span10">
		<div class="widget-box" style="background : transparent">
			<div class="widget-content nopadding">
				<table class="table display table-bordered table-hover" id="expTable" width="100%">
					<thead>
						<tr>
							<th>ID</th>
							<th>Name</th>
							<th>Standards linked</th>
							<th></th>
						</tr>
					</thead>
				</table>
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
	setup_experiment_datatable();
	var select = getSelectStd();
	//console.log(select);


	$('#expTable tbody').on('click', 'button', function(event) {
		var table = $('#expTable').DataTable();
		if(this.id == "editButton"){
			var data = table.row( $(this).parents('tr') ).data();
			construct_edit_modal(data, "edit", select);
		}
		else if (this.id == "deleteButton"){
			var data = table.row( $(this).parents('tr') ).data();
			construct_delete_modal(data, "delete");
		}
    });

    $('#createStandard').on('click', function() {
    	var data="";
 		construct_create_modal(data, "create", select);
    });
});



	$(document).on("click", "#editSubmit", function(){
		var boolOK=true;
		var expID = $("#expID").val();
		var expName = $("#expName").val();
		var stdName = $("#stdName").val();
		var stdID = $("#selectStd").val();
		var action = $("#action").val();
		
		$.ajax({
			url: "AdminExpUpdateData.php",
			type: "post",
			data: { 
				expID : expID,
				expName : expName,
				stdID : stdID,
				action : action
			},
			success: function(data) {
				var obj = data;
				if(obj.status == 'success'){
					$("#editRowModal").modal('hide');
					$('#statusSpan').html('<div class="alert alert-success">'+obj.action+' Successful<a href="#" data-dismiss="alert" class="close">×</a></div>');
					setup_experiment_datatable();
				}
				else if(obj.status == 'error'){
					$("#editRowModal").modal('hide');
					$('#statusSpan').html('<div class="alert alert-error">'+obj.action+' Failure<a href="#" data-dismiss="alert" class="close">×</a></div>');
					setup_experiment_datatable();
				}
			},
			error: function(xhr, status, error) {
			}
		});
	 });


function getSelectStd(){

	var select="";
	$.ajax({
		url: "get_all_standard.php",
		type: "post",
		async: false,
		success: function(data) {
			$.each(data, function(key, value)
			{
				select += '<option value="'+value[0]+'">'+value[1]+'</option>';
			});
		}
	});
	return select;
}

function setup_experiment_datatable(){
	$('#expTable').dataTable().fnDestroy();
	var table = $('#expTable').DataTable({
		scrollY:        400,
		scroller:       true,
		select: "single",
		paging : true,
		dom: 'TB<"clear">frtip',
		ajax: 'adminExp_database_functions.php',
		buttons: [
			'copy', 'csv', 'excel', 'pdf', 'print'
		],
		order: [[ 1, 'asc' ]],
		columnDefs: [ 
			{
				"targets":0,
				"width": "6%"
			},
			{
				"targets":-1,
				"width": "12%",
				"defaultContent": "<button type=\"button\" id=\"editButton\" class=\"tabledit-edit-button btn btn-info btn-sm btn-default\" style=\"float: none;\"> <span class=\"glyphicon glyphicon-pencil\"> </span> </button>"+
				"&nbsp <button type= \"submit\" id=\"deleteButton\" class=\"tabledit-edit-button btn btn-danger btn-sm btn-default\" style=\"float: none;\"> <span class=\"glyphicon glyphicon-trash\"> </span> </button>"
	        }
        ]
	});
}

function construct_delete_modal(data, action){
	var modal ='<div class="modal-dialog">'+
	'<div class="loginmodal-container">'+
	    		'<h1>Confirm Deletion</h1><br>'+
	  			'<h6> Are you sure you want to remove '+data[1]+' experiment ?</h6>'+
	  			'<form role="form" id="editRowForm" method="post">'+
  					'<input type="hidden" class="form-control required" id="action" name="action" value="'+action+'">'+
	               	'<input type="hidden" class="form-control required" id="expID" name="expID" value="'+data[0]+'" disabled="disabled" /><br>'+
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

function construct_edit_modal(data, action, select){
	var modal ='<div class="modal-dialog">'+
		'<div class="modal-content">'+
			'<div class="modal-header">'+
	    		'<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>'+
	    		'<h4 class="modal-title">Editor</h4>'+
	  		'</div>'+
	  		'<div class="modal-body">'+
	  			'<form role="form" id="editRowForm" method="post">'+
		  			'<div class="modal-body">'+
						'<div class="form-group">'+
							'<input type="hidden" class="form-control required" id="action" name="action" value="'+action+'">'+

							'<label class="control-label" for="id">ID</label>'+
							'<input class="form-control name" type="text" id="expID" name="id" value="'+data[0]+'" required="required" /><br>'+

							'<label class="control-label" for="expName">Experiment Name</label>'+
							'<input class="form-control name" type="text" placeholder="Name" id="expName" name="name" value="'+data[1]+'" required="required" /><br>'+

							'<label class="control-label" for="stdName">Associated standard</label>'+
							'<input class="form-control genius" id="stdName" type="text" placeholder="Genius" value="'+data[2]+'" disabled ><br>'+

							'<label class="control-label" for="stdGenius">Change standard</label>'+
							'<select id="selectStd" class="form-control" name="selectStd">'+
							'<option value="-1">----- Select -----</option>';
						var modal2 = '</select></div>'+
					'</div> <!-- /.modal-body -->'+
				'</form>'+
				'<div class="modal-footer">'+
					'<button type="button" data-dismiss="modal" class="btn btn-large">Close</button>'+
					'<button type="submit" id="editSubmit" class="btn btn-primary btn-large">Submit</button>'+
				'</div>'+
		'</div> <!-- /.modal-content -->'+
	'</div> <!-- /.modal-dialog -->';

	var finalstring = modal+select+modal2;

	$('#editRowModal').empty();
	$('#editRowModal').append(finalstring);
	$("#editRowModal").modal("show");
}

function construct_create_modal(data, action, select){	
	var modal ='<div class="modal-dialog">'+
	'<div class="modal-content">'+
		'<div class="modal-header">'+
			'<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>'+
			'<h4 class="modal-title">New Experiment</h4>'+
		'</div>'+
		'<div class="modal-body">'+
			'<input type="hidden" class="form-control required" id="action" name="action" value="'+action+'">'+
			'<label class="control-label">Experiment name</label>'+
			'<input class="form-control name" id="expName"  type="text" placeholder="experiment name" required="required">'+
			'<span  style="color:red" id="help-expName"></span><br>'+

			'<label class="control-label">Select standard</label>'+
			'<select id="selectStd" class="form-control" name="selectStd">'+
				'<option value="-1"> ----- Select ----- </option>';

		var modal2 ='</select></div> <!-- /.modal-body -->'+
		'<div class="modal-footer">'+
			'<button type="button" data-dismiss="modal" class="btn btn-large">Close</button>'+
			'<button id="editSubmit" type="submit" class="btn btn-primary btn-large">Submit</button>'+
		'</div>'+
	'</div> <!-- /.modal-content -->'+
'</div> <!-- /.modal-dialog -->';

	$('#editRowModal').empty();
	$('#editRowModal').append(modal+select+modal2);
	$("#editRowModal").modal("show");
}

</script>