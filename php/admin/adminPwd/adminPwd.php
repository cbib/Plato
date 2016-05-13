<?php
require('../../functions/check_login_admin.php');
include ('../../functions/html_functions.php');
// include ('../../functions/php_functions.php');

html_header("../../../", $_SESSION['login']);

editable_html_top_page("../../../","User Management");

echo '<a href="#" title="Create a new Analyte" class="tip-bottom" id="createAnalyte"><i class="icon-plus"></i>Create a New user</a>
</div>';

echo'
<span id="statusSpan">
</span>';

echo'
<!-- Modal -->
<div class="modal fade" id="editUserModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
</div> <!-- /.modal -->


<div style="width: 80%; margin: 0px auto;">
<div class="row-fluid">
	<div class="span10">
		<div class="widget-box" style="background : transparent">
			<div class="widget-content nopadding">
			<table cellpadding="0" cellspacing="0" border="0" class="table table-striped table-bordered" id="users" width="100%">
				<thead>
					<tr>
						<th>ID</th>
						<th>Name</th>
						<th>Status</th>
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

	$('#users tbody').on('click', 'button', function(event) {
		var table = $('#users').DataTable();

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
		var userID = $("#id").val();
		var userName = $("#name").val();
		var userLevel = $("#status").val();
		var adminPassword = $("#adminPassword").val();
		var userPassword = $("#changePassword").val();
		var action = $("#action").val();
		var boolOK=true;

		if (action !="delete"){
			if((userName =="") || ( $.isNumeric(userName))) {
				$('#help-userName').html("Please add a correct Name");
				boolOK = false;
			}
			else{
				$('#help-userName').html("");
			}

			if((userLevel =="") || ( $.isNumeric(userLevel))) {
				$('#help-userLevel').html("Please add a correct user Level");
				boolOK = false;
			}
			else{
				$('#help-userLevel').html("");
			}

			if(adminPassword =="") {
				$('#help-adminPassword').html("Please enter the admin password");
				boolOK = false;
			}
			else{
				$('#help-adminPassword').html("");
			}

			if (undefined != userPassword){
				if((userPassword =="") || (userPassword.length >=8)) {
					$('#help-userPassword').html("");
					boolOK = true;
				}
				else{
					boolOK = false;
					$('#help-userPassword').html("Please enter a strong password (more than 8 characters)");
				}
			}
		}

		if (boolOK == true){
			$.ajax({
				url: "AdminUserUpdate.php",
				type: "post",
				data: { 
					userID : userID,
					userName : userName,
					userLevel : userLevel,
					adminPassword : adminPassword,
					userPassword : userPassword,
					action : action
				},
				success: function(data) {
					var obj = data;
					if(obj.status == 'success'){
						$("#editUserModal").modal('hide');
						$('#statusSpan').html('<div class="alert alert-success">'+obj.action+' Successful<a href="#" data-dismiss="alert" class="close">×</a></div>');
						setup_datatable();
					}
					else if(obj.status == 'error'){
						$("#editUserModal").modal('hide');
						$('#statusSpan').html('<div class="alert alert-error">'+obj.action+' Failure<a href="#" data-dismiss="alert" class="close">×</a></div>');
						setup_datatable();
					}
				},
				error: function(xhr, status, error) {
				}
			});
		}
	});





function setup_datatable(){
	$('#users').dataTable().fnDestroy();
	var table = $('#users').DataTable({
		scrollY:        500,
        scroller:       true,
		dom: 'TB<"clear">frtip',
		ajax: 'get_all_users.php',
		buttons: [
			'copy', 'csv'
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

					'<label class="control-label" for="number">Admin Password</label>'+
						'<input type="password" placeholder="Mandatory" class="form-control required" id="adminPassword" name="password" value="" required="required" />'+
						'<span  style="color:red" id="help-adminPassword"></span>'+
						'<br>'+
				'</form>'+
				'<div class="modal-footer">'+
					'<button type="button" data-dismiss="modal" class="btn btn-large">Cancel</button>'+
					'<button type="submit" id="editSubmit" class="btn btn-primary btn-large">Delete</button>'+
				'</div>'+
		'</div> <!-- /.modal-content -->'+
	'</div> <!-- /.modal-dialog -->';

	$('#editUserModal').empty();
	$('#editUserModal').append(modal);
	$("#editUserModal").modal("show");
}

function construct_edit_modal(data, action){
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
								'<input type="text" class="form-control required" id="id" name="id" value="'+data[0]+'" disabled="disabled"/><br>'+

							'<label class="control-label" for="number">Name </label>'+
								'<input type="text" class="form-control required" id="name" name="name" value="'+data[1]+'" required="required" /><br>'+
								'<span  style="color:red" id="help-userName"></span>'+
								'<br>'+

							'<label class="control-label" for="number">Status</label>'+
							'<select id="status" class="form-control">'+
								'<option value="'+data[2]+'">'+data[2]+'</option>"'+
								'<option value="admin">admin</option>'+
								'<option value="user">user</option>'+
							'</select>'+
							'<span  style="color:red" id="help-userLevel"></span>'+
							'<br>'+

							'<label class="control-label" for="number">Admin Password</label>'+
								'<input type="password" placeholder="Mandatory" class="form-control required" id="adminPassword" name="password" value="" required="required" />'+
								'<span  style="color:red" id="help-adminPassword"></span>'+
								'<br>'+

							'<label class="control-label" for="number">Change password</label>'+
								'<input type="password" placeholder="keep free if you don\'t want to change" class="form-control required" id="changePassword" name="changePassword" value=""/><br>'+

						'</div>'+
					'</div> <!-- /.modal-body -->'+
				'</form>'+
				'<div class="modal-footer">'+
						'<button type="button" data-dismiss="modal" class="btn btn-large">Close</button>'+
						'<button type="submit" id="editSubmit" class="btn btn-primary btn-large">Submit</button>'+
				'</div>'+
		'</div> <!-- /.modal-content -->'+
	'</div> <!-- /.modal-dialog -->';

	$('#editUserModal').empty();
	$('#editUserModal').append(modal);
	$("#editUserModal").modal("show");
}

function construct_create_modal(data, action){	
	var modal ='<div class="modal-dialog">'+
		'<div class="modal-content">'+
			'<div class="modal-header">'+
	    		'<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>'+
	    		'<h4 class="modal-title">User Creator</h4>'+
	  		'</div>'+
	  		'<div class="modal-body">'+

	  			'<form role="form" id="editRowForm" method="post">'+
		  			'<div class="modal-body">'+
			  			'<div class="form-group">'+
							'<input type="hidden" class="form-control required" id="action" name="action" value="'+action+'">'+

							'<label class="control-label" for="number">Name </label>'+
								'<input type="text" class="form-control required" id="name" name="name" required="required" /><br>'+
								'<span  style="color:red" id="help-userName"></span>'+

							'<label class="control-label" for="number">Status</label>'+
							'<select id="status" class="form-control">'+
								'<option value="admin">admin</option>'+
								'<option value="user">user</option>'+
							'</select>'+
							'<span  style="color:red" id="help-userLevel"></span>'+
							'<br>'+

							'<label class="control-label" for="number">Admin Password</label>'+
								'<input type="password" placeholder="Mandatory" class="form-control required" id="adminPassword" name="password" required="required" /><br>'+
								'<span  style="color:red" id="help-adminPassword"></span>'+

							'<label class="control-label" for="number">User Password</label>'+
								'<input type="password" placeholder="Please choose a strong password" class="form-control required" id="changePassword" name="changePassword" value=""/><br>'+
								'<span  style="color:red" id="help-userPassword"></span>'+
								'<br>'+

						'</div>'+
					'</div> <!-- /.modal-body -->'+
				'</form>'+
				'<div class="modal-footer">'+
						'<button type="button" data-dismiss="modal" class="btn btn-large">Close</button>'+
						'<button type="submit" id="editSubmit" class="btn btn-primary btn-large">Submit</button>'+
				'</div>'+
		'</div> <!-- /.modal-content -->'+
	'</div> <!-- /.modal-dialog -->';
	$('#editUserModal').empty();
	$('#editUserModal').append(modal);
	$("#editUserModal").modal("show");
}

</script>