<?php
require('../../functions/check_login_admin.php');
include ('../../functions/html_functions.php');
// include ('../../functions/php_functions.php');

html_header("../../../", $_SESSION['login']);

editable_html_top_page("../../../","Biological Standards");

echo '<a href="#" title="Create a new Analyte" class="tip-bottom" id="createStandard"><i class="icon-plus"></i>Create a New Standard</a>
</div>';
echo'
<span id="statusSpan">
</span>';

echo'
<!-- Modal -->
<div class="modal fade" id="editRowModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
</div> 

<div class="modal fade" id="newStandardModal" tabindex="-1" role="dialog" aria-hidden="true">
</div> 

<div class="modal fade" id="addAnalyteModal" tabindex="-1" role="dialog" aria-hidden="true">
</div>
<!-- /.modal -->


<div class="row-fluid">
	<div class="span5" collapse-group>
		<div class="widget-box" style="background : transparent">
			<div class="widget-content nopadding">
				<table class="table display table-striped table-bordered table-hover" id="standard" width="100%">
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
							<th></th>
						</tr>
					</thead>
				</table>
			</div>
		</div>
	</div>';
echo'
	<div class="span7">
		<div id=analyte-wrapper>
			<div class="widget-box" style="background : transparent">
				<div class="widget-content nopadding">
					<button type="button" id ="addAnalyteButton" style="width:100%" class="btn btn-sm btn-info"><i class="icon-plus"></i> Add Analyte</button>
					<table id="ezTable" class="table table-striped table-bordered table-hover" style="width:100%">
						<thead>
							<tr>
								<th>Std_ez_ID</th>
								<th>EzID</th>
								<th>Analyte</th>
								<th>Code</th>
								<th>Slope</th>
								<th>Nature</th>
								<th>Amount</th>
								<th>Unit</th>
								<th>Dilution | Volume</th>
								<th></th>
							</tr>
						</thead>
						<tbody>
						</tbody>
					</table>
				</div>
			</div>
		</div>
	</div>';
echo'</div>';

html_footer("../../../");
?>
<script type="text/javascript" class="init">
	$('#analyte-wrapper').hide();
	var standardID ="";


$(document).ready(function() {

	$('#adminPanel').removeClass('submenu');
	$('#adminPanel').addClass('submenu open');
	setup_datatable();

	$('#standard').on('click', 'tr', function () {
		standardID = $('td', this).eq(0).text();
		if(standardID === "") {
		}
		else {
			refresh_enzyme(standardID);
			$('#analyte-wrapper').show();
		}
	});

	$('#standard tbody').on('click', 'button', function(event) {
		var table = $('#standard').DataTable();
		if(this.id == "editButton"){
			var data = table.row( $(this).parents('tr') ).data();
			construct_edit_modal_std(data, "edit");
		}
		else if (this.id == "deleteButton"){
			var data = table.row( $(this).parents('tr') ).data();
			construct_delete_modal_std(data, "delete");
		}
	});

	$('#createStandard').on('click', function() {
		var data="";
		construct_create_modal(data, "create");
	});

	$('#createStd').on('click', function() {
		construct_addStd_modal();
	});

	$('#addAnalyteButton').on('click', function() {
		construct_addAnalyte_modal(standardID);
	});

	$('#ezTable tbody').on('click', 'button', function(event) {
		var table = $('#ezTable').DataTable();
		if(this.id == "editButton"){
			var data = table.row( $(this).parents('tr') ).data();
			console.log(data);
			construct_edit_modal_analyte(data, "edit");
		}
		else if (this.id == "deleteButton"){
			var data = table.row( $(this).parents('tr') ).data();
			construct_delete_modal_analyte(data, "delete");
		}
	});

});

/**
 * Fonction d'ajout d'une enzyme a un standard
 * recupere depuis la fenetre modale :
 * une valeur(amount), une unité et et le nom de l'analyte a ajouter au standard
 */
$(document).on("click", "#AnalyteSubmit", function(){

	var boolOK= true;
	var selectAnalyte = $('#selectAnalyte').val();
	var selectUnit = $('#selectUnit').val();


	var analyteValue = $('#analyteValue').val();
	var dilutionValue = $('#dilutionValue').val();
	/* coma are not accepted for the database */
	analyteValue = analyteValue.replace(/,/g, '.');
	if((analyteValue =="") || ( ! $.isNumeric(analyteValue))) {
		$('#help-inline-value').html("Please add a number");
		boolOK=false;
	}
	if(dilutionValue =="") {
		$('#help-inline-volume').html("Please add a value");
		boolOK=false;
	}
	else {
		if (boolOK==true) {
			$.ajax({
				url: "add_enzyme_to_standard.php", // json datasource
				type: "POST",  // method  , by default get
				data: {
					selectAnalyte: selectAnalyte,
					selectUnit: selectUnit,
					analyteValue: analyteValue,
					standardID: standardID,
					dilutionValue: dilutionValue
				},
				success: function (data) {
					var obj = JSON.parse(data);
					if (obj.status == 'success') {
						refresh_enzyme(standardID);
						$("#addAnalyteModal").modal('hide');
						$('#statusSpan').html('<div class="alert alert-success">Insertion Successful<a href="#" data-dismiss="alert" class="close">×</a></div>');
					}
					else if (obj.status == 'error') {
						$("#addAnalyteModal").modal('hide');
						$('#statusSpan').html('<div class="alert alert-error">Insertion Failure<a href="#" data-dismiss="alert" class="close">×</a></div>');
					}
				},
				error: function () {
					alert("failure");
				}
			});
		}
	}
});

/**
 * Listener for remove enzyme button
 */
$(document).on("click", "#deleteSubmit", function(){
	var stdEzIdToRemove = $("#stdEzIdToRemove").val();
	$.ajax({
		url: "remove_enzyme_in_standard.php",
		type: "post",
		data: { 
			stdEzIdToRemove : stdEzIdToRemove
		},
		success: function(data) {
			var obj = JSON.parse(data);
			if(obj.status == 'success'){
				refresh_enzyme(standardID)
				$("#editRowModal").modal('hide');
				$('#statusSpan').html('<div class="alert alert-success">Remove Successful<a href="#" data-dismiss="alert" class="close">×</a></div>');
			}
			else if(obj.status == 'error'){
				$("#editRowModal").modal('hide');
				$('#statusSpan').html('<div class="alert alert-error">Remove Failure<a href="#" data-dismiss="alert" class="close">×</a></div>');
			}
		},
		error: function(xhr, status, error) {
		}
	});
 });

/**
 * fonction d'edition d'une ligne d'analyte
 */
$(document).on("click", "#editAnalyteSubmit", function(){
	var stdezid = $("#stdezid").val();
	var slope = $("#slope").val();
	var nature = $("#nature").val();
	var amount = $("#amount2").val();
	amount = amount.replace(/,/g, '.');
	var newUnit = $("#selectUnitEdit").val();
	var dilutionValue = $("#dilutionValue").val();

	$.ajax({
		url: "edit_enzyme_in_standard.php",
		type: "post",
		data: { 
			stdezid : stdezid,
			amount : amount,
			unit : newUnit,
			dilutionValue : dilutionValue
		},
		success: function(data) {
			var obj = JSON.parse(data);
			if(obj.status == 'success'){
				$("#editRowModal").modal('hide');
				$('#statusSpan').html('<div class="alert alert-success">Edit Successful<a href="#" data-dismiss="alert" class="close">×</a></div>');
				refresh_enzyme(standardID);
			}
			else if(obj.status == 'error'){
				$("#editRowModal").modal('hide');
				$('#statusSpan').html('<div class="alert alert-error">Edit Failure<a href="#" data-dismiss="alert" class="close">×</a></div>');
			}
		},
		error: function(xhr, status, error) {
		}
	});
});

/**
 * fonction d'edition creation utilise pour les standard uniquement
 */
$(document).on("click", "#createStdSubmit", function(){
	var id = $("#id").val();
	var stdName = $('#stdName').val();
	stdName = stdName.trim();
	stdName = stdName.replace(/\s+/g, '');
	var stdGenius = $('#stdGenius').val();
	var stdSpecies = $('#stdSpecies').val();
	var stdGenotype = $('#stdGenotype').val();
	var stdNature = $('#stdNature').val();
	var stdOwner = $('#stdOwner').val();
	var stdComment = $('#stdComment').val();
	var boolOK = true;
	var action = $("#action").val();
	var table = $('#standard').DataTable();
	table.search("").draw();

	var colArray = $('#standard td:nth-child(2)').map(function(){
		return $(this).text();
	}).get();

	if((stdName =="") || ( $.isNumeric(stdName)) || (stdName.indexOf("-") != -1) || (colArray.indexOf(stdName)!=-1) ) {
		$('#help-stdName').html("Please add a correct Name");
		boolOK = false;
	}
	else{
		$('#help-stdName').html("");
	}

	if((stdGenius =="") || ( $.isNumeric(stdGenius))) {
		$('#help-stdGenius').html("Please add a correct Genius");
		boolOK = false;
	}
	else{
		$('#help-stdGenius').html("");
	}

	if((stdSpecies =="") || ( $.isNumeric(stdSpecies))) {
		$('#help-stdSpecies').html("Please add a correct Species");
		boolOK = false;
	}
	else {
		$('#help-stdSpecies').html("");
	}

	if((stdGenotype =="") || ( $.isNumeric(stdGenotype))) {
		$('#help-stdGenotype').html("Please add a correct Genotype");
		boolOK = false;
	}
	else {
		$('#help-stdGenotype').html("");
	}

	if((stdNature =="") || ( $.isNumeric(stdNature))) {
		$('#help-stdNature').html("Please add a correct Nature");
		boolOK = false;
	}
	else {
		$('#help-stdNature').html("");
	}

	if((stdOwner =="") || ( $.isNumeric(stdOwner))) {
		$('#help-stdOwner').html("Please add a correct Owner");
		boolOK = false;
	}
	else {
		$('#help-stdOwner').html("");
	}

	if (boolOK == true){
		$.ajax({
			url: "AdminSTDUpdateData.php",
			type: "post",
			data: { 
				id : id,
				stdName : stdName,
				stdGenius : stdGenius,
				stdSpecies : stdSpecies,
				stdGenotype : stdGenotype,
				stdNature : stdNature,
				stdOwner : stdOwner,
				stdComment : stdComment,
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
	}
 });

	/**
	 * fonction d'edition creation utilise pour les standard uniquement
	 */
	$(document).on("click", "#editSubmit", function(){
		var id = $("#id").val();
		var stdName = $('#stdName').val();
		stdName = stdName.trim();
		stdName = stdName.replace(/ +/g, '');
		var stdGenius = $('#stdGenius').val();
		var stdSpecies = $('#stdSpecies').val();
		var stdGenotype = $('#stdGenotype').val();
		var stdNature = $('#stdNature').val();
		var stdOwner = $('#stdOwner').val();
		var stdComment = $('#stdComment').val();
		var boolOK = true;
		var action = $("#action").val();
		var table = $('#standard').DataTable();
		table.search("").draw();

		var colArray = $('#standard td:nth-child(2)').map(function(){
			return $(this).text();
		}).get();

		if((stdName =="") || ( $.isNumeric(stdName)) ) {
			$('#help-stdName').html("Please add a correct Name");
			boolOK = false;
		}
		else{
			$('#help-stdName').html("");
		}

		if((stdGenius =="") || ( $.isNumeric(stdGenius))) {
			$('#help-stdGenius').html("Please add a correct Genius");
			boolOK = false;
		}
		else{
			$('#help-stdGenius').html("");
		}

		if((stdSpecies =="") || ( $.isNumeric(stdSpecies))) {
			$('#help-stdSpecies').html("Please add a correct Species");
			boolOK = false;
		}
		else {
			$('#help-stdSpecies').html("");
		}

		if((stdGenotype =="") || ( $.isNumeric(stdGenotype))) {
			$('#help-stdGenotype').html("Please add a correct Genotype");
			boolOK = false;
		}
		else {
			$('#help-stdGenotype').html("");
		}

		if((stdNature =="") || ( $.isNumeric(stdNature))) {
			$('#help-stdNature').html("Please add a correct Nature");
			boolOK = false;
		}
		else {
			$('#help-stdNature').html("");
		}

		if((stdOwner =="") || ( $.isNumeric(stdOwner))) {
			$('#help-stdOwner').html("Please add a correct Owner");
			boolOK = false;
		}
		else {
			$('#help-stdOwner').html("");
		}

		if (boolOK == true){
			$.ajax({
				url: "AdminSTDUpdateData.php",
				type: "post",
				data: {
					id : id,
					stdName : stdName,
					stdGenius : stdGenius,
					stdSpecies : stdSpecies,
					stdGenotype : stdGenotype,
					stdNature : stdNature,
					stdOwner : stdOwner,
					stdComment : stdComment,
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
		}
	});


	/**
 * Print standard datatble
 *
 * @method     setup_datatable
 */
function setup_datatable(){
	$('#standard').dataTable().fnDestroy();
	var table = $('#standard').DataTable({
		scrollY:		500,
		scrollX:		true, 
		scroller:		true,
		destroy:		true,
		stateSave: true,
		select: "single",
		paging : true,
		dom: 'TB<"clear">frtip',
		ajax: 'adminStd_database_functions.php',
		buttons: [
			'copy', 'csv', 'excel', 'print', 'colvis'
		],
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
			},
			{
				"targets":0,
				"visible":true,
				"width": "1px"
			},
			{
				"targets":-1,
				"width": "60px",
				"defaultContent": "<button type=\"button\" id=\"editButton\" class=\"tabledit-edit-button btn btn-info btn-sm btn-default\" style=\"float: none;\"> <span class=\"glyphicon glyphicon-pencil\"> </span> </button>"+
				"&nbsp <button type= \"submit\" id=\"deleteButton\" class=\"tabledit-edit-button btn btn-danger btn-sm btn-default\" style=\"float: none;\"> <span class=\"glyphicon glyphicon-trash\"> </span> </button>"
	        }
        ]
	});
}

/**
 * Reload enzyme datatable
 *
 * @method     refresh_enzyme
 * @param      {<type>}  standardID  { description }
 */
function refresh_enzyme(standardID) {
	$('#ezTable').dataTable().fnDestroy();
	var oTable = $("#ezTable").DataTable({ 
		"paging": false,
		"sDom": 'TB<"clear">lfrtip',
		stateSave: true,
		"ajax":{
			url :"enzyme_from_standard.php", // json datasource
			type: "POST",  // method  , by default get
			data: { standardID : standardID }
		},
		buttons: [
			'copy', 'csv', 'excel', 'print'
		],
		"columnDefs": 
		[
			{
				"targets":0,
				"visible":false,
				"searchable":false,
				"width": "0%"
			},
			{
				"targets":1,
				"visible":false,
				"searchable":false,
				"width": "0%"
			},
			{
				"targets":-1,
				"width": "65px",
				"defaultContent": "<button type=\"button\" id=\"editButton\" class=\"tabledit-edit-button btn btn-info btn-sm btn-default\" style=\"float: none;\"> <span class=\"glyphicon glyphicon-pencil\"> </span> </button>"+
				" <button type= \"submit\" id=\"deleteButton\" class=\"tabledit-edit-button btn btn-danger btn-sm btn-default\" style=\"float: none;\"> <span class=\"glyphicon glyphicon-trash\"> </span> </button>"
			}
		]
	});
	$("#ezTable").css("fontSize", 11);
}

/**
 * Modal for analyte adding
 *
 * @method     construct_addAnalyte_modal
 * @param      {<type>}  standardID  { description }
 */
function construct_addAnalyte_modal(standardID){
	var modal = ''+
	'<div class="modal-dialog">'+
		'<div class="modal-content">'+
			'<div class="modal-header">'+
				'<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>'+
				'<h4 class="modal-title">Add Analyte</h4>'+
			'</div>'+
			'<div class="modal-body">'+
				'<form id="addAnalyte" method="post" class="addStd">'+
					'<div class="form-group">'+
						'<div class="control-group">'+
							'<div class="controls">'+
								'<label class="control-label" for="selectAnalyte">Select Analytes</label>'+
								'<select class="form-control" id="selectAnalyte">';
								$.ajax({
									async: false,
									url: "enzyme_not_from_standard.php",
									type: "post",
									data: { 
										standardID : standardID
									},
									success: function(data) {
										for(var i = 0; i < data.length; i++) {
											var line = data[i];
											if(line.ez_is_enzyme == "Metabolite"){
												modal += '<option  style="background: #039099; color:#FFF" value = "'+line.ez_id+'">'+line.ez_analyte+' - '+line.ez_code+'</option>';
											}
											else if(line.ez_is_enzyme == "Enzyme"){
												modal += '<option  style="background: #ccffe6;" value = "'+line.ez_id+'">'+line.ez_analyte+' - '+line.ez_code+'</option>';
											}
											else {
												modal += '<option  style="background: #e6ffcc;" value = "'+line.ez_id+'">'+line.ez_analyte+' - '+line.ez_code+'</option>';
											}
										}
									},
									error: function(xhr, status, error) {
									}
								});
								modal += '</select>'+
								'<br/>'+
								'<label class="control-label" for="selectUnit">Select Unit</label>'+
								'<select class="form-control" id="selectUnit">';
								$.ajax({
									async: false,
									url: "standard_get_all_unit.php",
									type: "post",
									data: { 
										standardID : standardID
									},
									success: function(data) {
										//console.log(data);
										for(var i = 0; i < data.length; i++) {
											var line = data[i];

											if ((line.unit_name == "mg/gFW") || (line.unit_name == "µmol/gFW") || (line.unit_name == "nmol/gFW/min")){
												console.log("."+line.unit_name+".");
												modal += '<option style="background: #D8D8D8;"  value = "'+line.unit_id+'"><strong>'+line.unit_name+'</strong></option>';
											}
											else {
												modal += '<option value = "' + line.unit_id + '">' + line.unit_name + '</option>';
											}
										}
									},
									error: function(xhr, status, error) {
									}
								});
								modal += '</select>'+
								'<br/>'+
								'<label class="control-label" for="analyteValue">Insert Value</label>'+
								'<input class="form-control" value"" id="analyteValue" type="text" placeholder="Value" required="required">'+
								'<span  style="color:red" id="help-inline-value"></span>'+
								'<br/>'+
								'<label class="control-label" for="dilutionValue">Dilution or Volume value</label>'+
								'<input class="form-control" value"" id="dilutionValue" type="text" placeholder="Value" required="required">'+
								'<span  style="color:red" id="help-inline-volume"></span>'+
								'<br/>'+
				            '</div>'+
				        '</div>'+
					'</div>'+
				'</form>'+
			'</div> <!-- /.modal-body -->'+
			'<div class="modal-footer">'+
				'<button type="button" data-dismiss="modal" class="btn btn-large">Close</button>'+
				'<button id="AnalyteSubmit" type="submit" class="btn btn-primary btn-large">Submit</button>'+
			'</div>'+
		'</div> <!-- /.modal-content -->'+
	'</div> <!-- /.modal-dialog -->';

	$('#addAnalyteModal').empty();
	$('#addAnalyteModal').append(modal);
	$("#addAnalyteModal").modal("show");
}

/**
 * Modal for standard deleting
 *
 * @method     construct_delete_modal_std
 * @param      {string}  data    { description }
 * @param      {string}  action  { description }
 */
function construct_delete_modal_std(data, action){
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

/**
 * Create modal for analyte deletion, on the fly
 *
 * @method     construct_delete_modal
 * @param      {string}  data    { description }
 * @param      {<type>}  action  { description }
 */
function construct_delete_modal_analyte(data, action){
	var modal =
	'<div class="modal-dialog">'+
		'<div class="loginmodal-container">'+
			'<h1>Confirm Deletion</h1><br>'+
			'<h6> Are you sure you want to remove '+data[2]+' ?</h6>'+
			'<form role="form" id="editRowForm" method="post">'+
				'<input type="hidden" class="form-control required" id="stdEzIdToRemove" name="stdEzIdToRemove" value="'+data[0]+'" disabled="disabled" /><br>'+
			'</form>'+
			'<div class="modal-footer">'+
				'<button type="button" data-dismiss="modal" class="btn btn-large">Cancel</button>'+
				'<button type="submit" id="deleteSubmit" class="btn btn-primary btn-large">Delete</button>'+
			'</div>'+
		'</div> <!-- /.modal-content -->'+
	'</div> <!-- /.modal-dialog -->';
	$('#editRowModal').empty();
	$('#editRowModal').append(modal);
	$("#editRowModal").modal("show");
}

/**
 * Modal for analyte edition
 *
 * @method     construct_edit_modal_analyte
 * @param      {string}  data    { description }
 * @param      {<type>}  action  { description }
 */
function construct_edit_modal_analyte(data, action){
	var modal ='<div class="modal-dialog">'+
		'<div class="modal-content">'+
			'<div class="modal-header">'+
	    		'<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>'+
	    		'<h4 class="modal-title">Editor</h4>'+
	  		'</div>'+
	  		'<div class="modal-body">'+
	  			'<form id="editRowForm" method="post">'+
		  			'<div class="modal-body">'+
			  			'<div class="form-group">'+
							'<input type="hidden" class="form-control required" id="stdezid" name="stdezid" value="'+data[0]+'">'+
							'<label class="control-label" for="analyte">Analyte</label>'+
								'<input type="text" class="form-control required" id="analyte" name="analyte" value="'+data[2]+'" disabled="disabled"/>'+
							'<label class="control-label" for="code">Code</label>'+
								'<input type="text" class="form-control required" id="code" name="code" value="'+data[3]+'" disabled="disabled"/>'+
							'<label class="control-label" for="slope">Slope</label>'+
								'<input type="text" class="form-control required" id="slope" name="slope" value="'+data[4]+'" disabled="disabled"/>'+
							'<label class="control-label" for="nature">Nature</label>'+
							'<select id="nature" class="form-control" disabled="disabled">';
								if(data[5] == "Metabolite"){
									modal+='<option value="Metabolite" selected>Metabolite</option>';
								}
								else {
									modal+='<option value="Metabolite">Metabolite</option>';
								}
								if(data[5] == "Enzyme"){
									modal+='<option value="Enzyme" selected>Enzyme</option>';
								}
								else {
									modal+='<option value="Enzyme">Enzyme</option>';
								}
								if(data[5] == "Other"){
									modal+='<option value="Other" selected>Other</option>';
								}
								else {
									modal+='<option value="Other">Other</option>';
								}
							modal+='</select>'+
							'<label class="control-label" for="amount2">Amount</label>'+
								'<input type="text" class="form-control required" id="amount2" name="amount2" value="'+data[6]+'" required="required" />'+

							'<label class="control-label" for="selectUnit">Select Unit</label>'+
								'<select class="form-control" id="selectUnitEdit">';
								$.ajax({
									async: false,
									url: "standard_get_all_unit.php",
									type: "post",
									data: { 
										standardID : standardID
									},
									success: function(data2) {
										//console.log(data);
										for(var i = 0; i < data2.length; i++) {
											var line = data2[i];
											modal += '<option value = "'+line.unit_id+'"';
											if(line.unit_name == data[7]){
												modal += 'selected="selected"';
											}
											modal += '>'+line.unit_name+'</option>';
										}
									},
									error: function(xhr, status, error) {
									}
								});
								modal += '</select>'+
							'<label class="control-label" for="dilutionValue">Dilution | Volume</label>'+
								'<input type="text" class="form-control required" id="dilutionValue" name="dilutionValue" value="'+data[8]+'" required="required" />'+
							'<br/>'+
						'</div>'+
					'</div> <!-- /.modal-body -->'+
				'</form>'+
				'<div class="modal-footer">'+
						'<button type="button" data-dismiss="modal" class="btn btn-large">Close</button>'+
						'<button type="submit" id="editAnalyteSubmit" class="btn btn-primary btn-large">Submit</button>'+
				'</div>'+
		'</div> <!-- /.modal-content -->'+
	'</div> <!-- /.modal-dialog -->';

	$('#editRowModal').empty();
	$('#editRowModal').append(modal);
	$("#editRowModal").modal("show");
}


/**
 * Modal for standard edition
 *
 * @method     construct_edit_modal_std
 * @param      {string}  data    { description }
 * @param      {string}  action  { description }
 */
function construct_edit_modal_std(data, action){
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
							'<input class="form-control name" type="text" id="id" name="id" value="'+data[0]+'" required="required" disabled /><br>'+


							'<input class="form-control name" type="text" id="stdName" value="'+data[1]+'" placeholder="Name" name="name" required="required" />'+
							'<span  style="color:red" id="help-stdName"></span>'+
							'<br>'+

							'<input class="form-control genius" id="stdGenius" type="text" value="'+data[2]+'" placeholder="Genius" required="required">'+
							'<span  style="color:red" id="help-stdGenius"></span>'+
							'<br>'+

							'<input class="form-control species" id="stdSpecies" type="text" value="'+data[3]+'" placeholder="Species" required="required">'+
							'<span  style="color:red" id="help-stdSpecies"></span>'+
							'<br>'+

							'<input class="form-control nature" id="stdGenotype" type="text" value="'+data[4]+'" placeholder="Genotype" required="required">'+
							'<span  style="color:red" id="help-stdGenotype"></span>'+
							'<br>'+

							'<input class="form-control nature" id="stdNature" type="text" value="'+data[5]+'" placeholder="Nature" required="required">'+
							'<span  style="color:red" id="help-stdNature"></span>'+
							'<br>'+

							'<input class="form-control uri" id="stdOwner" type="text" value="'+data[6]+'" placeholder="Owner" required="required">'+
							'<span  style="color:red" id="help-stdOwner"></span>'+
							'<br>'+

							'<input class="form-control comment" id="stdComment" type="text" value="'+data[7]+'" placeholder="Comment">'+
							'<span  style="color:red" id="help-stdComment"></span>'+
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

	$('#editRowModal').empty();
	$('#editRowModal').append(modal);
	$("#editRowModal").modal("show");
}

/**
 * Modal for standard creation
 *
 * @method     construct_create_modal
 * @param      {number}  data    { description }
 * @param      {string}  action  { description }
 */
function construct_create_modal(data, action){	
	var modal =''+
	'<div class="modal-dialog">'+
	'<div class="modal-content">'+
		'<div class="modal-header">'+
			'<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>'+
			'<h4 class="modal-title">New Standard</h4>'+
		'</div>'+
		'<div class="modal-body">'+
			'<form id="newStdForm" method="post">'+
	  			'<div class="form-group">'+
					'<div class="control-group">'+

						'<input type="hidden" class="form-control required" id="action" name="action" value="'+action+'">'+

						'<input class="form-control name" type="text" placeholder="Name" id="stdName" name="name" required="required" />'+
						'<span  style="color:red" id="help-stdName"></span>'+
						'<br>'+

						'<input class="form-control genius" id="stdGenius" type="text" placeholder="Genius" required="required">'+
						'<span  style="color:red" id="help-stdGenius"></span>'+
						'<br>'+

						'<input class="form-control species" id="stdSpecies" type="text" placeholder="Species" required="required">'+
						'<span  style="color:red" id="help-stdSpecies"></span>'+
						'<br>'+

						'<input class="form-control nature" id="stdGenotype" type="text" placeholder="Genotype" required="required">'+
						'<span  style="color:red" id="help-stdGenotype"></span>'+
						'<br>'+

						'<input class="form-control nature" id="stdNature" type="text" placeholder="Nature" required="required">'+
						'<span  style="color:red" id="help-stdNature"></span>'+
						'<br>'+

						'<input class="form-control uri" id="stdOwner"  type="text" placeholder="Owner" required="required">'+
						'<span  style="color:red" id="help-stdOwner"></span>'+
						'<br>'+

						'<input class="form-control comment" id="stdComment" type="text" placeholder="Comment">'+
						'<span  style="color:red" id="help-stdComment"></span>'+
						'<br>'+

					'</div>'+
				'</div>'+
			'</form>'+
		'</div> <!-- /.modal-body -->'+
		'<div class="modal-footer">'+
			'<button type="button" data-dismiss="modal" class="btn btn-large">Close</button>'+
			'<button id="createStdSubmit" type="submit" class="btn btn-primary btn-large">Submit</button>'+
		'</div>'+
	'</div> <!-- /.modal-content -->'+
	'</div> <!-- /.modal-dialog -->';


	$('#editRowModal').empty();
	$('#editRowModal').append(modal);
	$("#editRowModal").modal("show");
}

</script>