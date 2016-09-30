<?php
require('../../functions/check_login_admin.php');
include ('../../functions/html_functions.php');
// include ('../../functions/php_functions.php');

html_header("../../../", $_SESSION['login']);

editable_html_top_page("../../../","Analytes");

echo '<a href="#" title="Create a new Analyte" class="tip-bottom" id="createAnalyte"><i class="icon-plus"></i>Create a New Analyte</a>
</div>';
echo'
<span id="statusSpan">
</span>';

echo'
<!-- Modal -->
<div class="modal fade" id="editRowModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
</div> <!-- /.modal -->
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
						<th></th>
					</tr>
				</thead>
			</table>
		</div>
		</div>
	</div>	</div>
';
html_footer("../../../");
?>



<script type="text/javascript" class="init">

$(document).ready(function() {
	$('#adminPanel').removeClass('submenu');
	$('#adminPanel').addClass('submenu open');
	setup_datatable();

	$('#enzyme tbody').on('click', 'button', function(event) {
		var table = $('#enzyme').DataTable();
		if(this.id == "editButton"){
			var data = table.row( $(this).parents('tr') ).data();
			construct_edit_modal(data, "edit");
		}
		else if (this.id == "deleteButton"){
			var data = table.row( $(this).parents('tr') ).data();
			construct_delete_modal(data, "delete");
		}
    });

    $('#createAnalyte').on('click', function() {
    	var data="";
 		construct_create_modal(data, "create");
    });
});


	$(document).on("click", "#editSubmit", function(){
		var id = $("#id").val();
		var analyte = $("#analyte").val();
		var code = $("#code").val();
		console.log(code);
		var slope = $("#slope").val();
		var nature = $("#nature").val();
		var action = $("#action").val();
		$.ajax({
			url: "AdminEZUpdateData.php",
			type: "post",
			data: { 
				id : id,
				analyte : analyte,
				code : code,
				slope : slope,
				nature : nature,
				action : action
			},
			success: function(data) {
				var obj = data;
				if(obj.status == 'success'){
					$("#editRowModal").modal('hide');
					$('#statusSpan').html('<div class="alert alert-success">'+obj.action+' Successful<a href="#" data-dismiss="alert" class="close">×</a></div>');
					setup_datatable();
				}
				else if(obj.status == 'error'){
					$("#editRowModal").modal('hide');
					$('#statusSpan').html('<div class="alert alert-error">'+obj.action+' Failure<a href="#" data-dismiss="alert" class="close">×</a></div>');
					setup_datatable();
				}
			},
			error: function(xhr, status, error) {
			}
		});
	 });

// function setup_datatable(){
// 	$('#enzyme').dataTable().fnDestroy();
// 	var table = $('#enzyme').DataTable({
// 		"Paging": false,
// 		"dom": 'T<"clear">frtip',
// 		"sAjaxSource": 'adminEz_database_functions.php',
// 		"columnDefs": [ {
//             "targets":-1,
//             "defaultContent": "<button type=\"button\" id=\"editButton\" class=\"tabledit-edit-button btn btn-info btn-sm btn-default\" style=\"float: none;\"> <span class=\"glyphicon glyphicon-pencil\"> </span> </button>"+
//              "&nbsp <button type= \"submit\" id=\"deleteButton\" class=\"tabledit-edit-button btn btn-danger btn-sm btn-default\" style=\"float: none;\"> <span class=\"glyphicon glyphicon-trash\"> </span> </button>"
//         } ],
// 	});
// }


function setup_datatable(){
	$('#enzyme').dataTable().fnDestroy();
	var table = $('#enzyme').DataTable({
		scrollY:        500,
        scroller:       true,
		dom: 'TB<"clear">frtip',
		ajax: 'adminEz_database_functions.php',
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

function construct_delete_modal(data, action){
	var modal ='<div class="modal-dialog">'+
	'<div class="loginmodal-container">'+
	    		'<h1>Confirm Deletion</h1><br>'+
	  			'<h6> Are you sure you want to remove '+data[1]+' ?</h6>'+
	  			'<form role="form" id="editRowForm" method="post">'+
  					'<input type="hidden" class="form-control required" id="action" name="action" value="'+action+'">'+
	               	'<input type="hidden" class="form-control required" id="id" name="id" value="'+data[0]+'" disabled="disabled" /><br>'+
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

function construct_edit_modal(data, action){
	console.log(data);
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
							'<label class="control-label" for="number">ID</label>'+
								'<input type="text" class="form-control required" id="id" name="id" value="'+data[0]+'" disabled="disabled" /><br>'+
							'<label class="control-label" for="number">Analyte Name</label>'+
								'<input type="text" class="form-control required" id="analyte" name="analyte" value="'+data[1]+'" required="required" /><br>'+
							'<label class="control-label" for="number">Code</label>'+
								'<input type="text" class="form-control required" id="code" name="code" value="'+data[2]+'" required="required" /><br>'+
							'<label class="control-label" for="number">Slope</label>'+
								'<input type="text" class="form-control required" id="slope" name="slope" value="'+data[3]+'" required="required" /><br>'+
							'<label class="control-label" for="number">Nature</label>'+
							'<select id="nature" class="form-control">'+
								  '<option value="'+data[4]+'">'+data[4]+'</option>'+
								  '<option value="Metabolite">Metabolite</option>'+
								  '<option value="Enzyme">Enzyme</option>'+
								  '<option value="Other">Other</option>'+
							'</select>'+
						'</div>'+
					'</div> <!-- /.modal-body -->'+
				'</form>'+
				'<div class="modal-footer">'+
						'<button type="button" data-dismiss="modal" class="btn btn-large">Close</button>'+
						'<button type="submit" id="editSubmit" class="btn btn-primary btn-large">Submit</button>'+
				'</div>'+
		'</div> <!-- /.modal-content -->'+
	'</div> <!-- /.modal-dialog -->';

	$('#editRowModal').empty();
	$('#editRowModal').append(modal);
	$("#editRowModal").modal("show");
}

function construct_create_modal(data, action){	
	var modal ='<div class="modal-dialog">'+
		'<div class="modal-content">'+
			'<div class="modal-header">'+
	    		'<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>'+
	    		'<h4 class="modal-title">Creator</h4>'+
	  		'</div>'+
	  		'<div class="modal-body">'+

	  			'<form role="form" id="editRowForm" method="post">'+
		  			'<div class="modal-body">'+
			  			'<div class="form-group">'+
							'<input type="hidden" class="form-control required" id="action" name="action" value="'+action+'">'+
							'<label class="control-label" for="number">ID</label>'+
								'<input type="text" class="form-control required" id="id" name="id" placeholder="Automatically fill" disabled="disabled" /><br>'+
							'<label class="control-label" for="number">Analyte Name</label>'+
								'<input type="text" class="form-control required" id="analyte" name="analyte" placeholder="ex: Lactate" required="required" /><br>'+
							'<label class="control-label" for="number">Code</label>'+
								'<input type="text" class="form-control required" id="code" name="code" placeholder="ex: A08" required="required" /><br>'+
							'<label class="control-label" for="number">Slope</label>'+
								'<input type="text" class="form-control required" id="slope" name="slope" placeholder="ex : 1" required="required" /><br>'+
							'<label class="control-label" for="number">Nature</label>'+
							'<select id="nature" class="form-control">'+
								'<option value="Metabolite">Metabolite</option>'+
								'<option value="Enzyme">Enzyme</option>'+
								'<option value="Other">Other</option>'+
							'</select>'+
						'</div>'+
					'</div> <!-- /.modal-body -->'+
				'</form>'+
				'<div class="modal-footer">'+
						'<button type="button" data-dismiss="modal" class="btn btn-large">Close</button>'+
						'<button type="submit" id="editSubmit" class="btn btn-primary btn-large">Submit</button>'+
				'</div>'+
		'</div> <!-- /.modal-content -->'+
	'</div> <!-- /.modal-dialog -->';
	$('#editRowModal').empty();
	$('#editRowModal').append(modal);
	$("#editRowModal").modal("show");
}

</script>