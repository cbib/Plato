<?php
require('../functions/check_login.php');
include '../functions/html_functions.php';
include '../functions/php_functions.php';


html_header("../../",  $_SESSION['login']);

/**
 * Editable top page allow to create button in the navbar
 * Dont forget to close the div when you use it
 */
editable_html_top_page("../../","Aliquots");
echo '<a href="#" id="createExpButton" title="Declare a new Experiment" class="tip-bottom"><i class="icon-plus"></i>Add Experiment</a>
</div>';

/**
 * Use to display informations to the user
 */
echo'
<span id="statusSpan">
</span>';

echo'
<!-- Modal -->
<div class="modal fade" id="addRowsModal" tabindex="-1" role="dialog" aria-hidden="true">
</div>
<div class="modal fade" id="createExpModal" tabindex="-1" role="dialog" aria-hidden="true">
</div> 
<div class="modal fade" id="waitModal" tabindex="1" role="dialog" aria-hidden="true">
</div>
<!-- /.modal -->';

echo'
<div class="row-fluid">
	<div class="span5">
		<div class="widget-box" style="background : transparent">
			<div class="widget-content nopadding">
				<table id="expTable" class="table display dt-right table-bordered" style="width:100%">
					<thead>
						<tr>
							<th>ID</th>
							<th>Name</th>
							<th>Associated Standard</th>
						</tr>
					</thead>
				</table>
			</div>
		</div>
	</div>';


echo'
	<div class="span7">
		<div id=sample-wrapper>
			<div class="widget-box" style="background : transparent">
				<div class="widget-content nopadding">
					<div class="btn-group btn-group-justified">
						<button type="button" class="btn btn-sm btn-info" id ="addRow" style="width:50%"><i class="icon-plus"></i> Add Single Row</button>
						<button type="button" class="btn btn-sm btn-info" id ="addRows" style="width:50%"><i class="icon-plus"></i> Add Multiple Rows</button>
					</div>
					<table id="spl_alq_table" class="table display table-striped dt-right table-bordered">
						<thead>
							<tr>
								<th>splID</th>
								<th class="sampleSearch">Sample</th>
								<th>aliquotID</th>
								<th class="aliquotSearch">Aliquot</th>
								<th>Value</th>
								<th>Location</th>
								<th>State</th>
								<th>FwID</th>
								<th>locID</th>
								<th></th>
							</tr>
						</thead>
					</table>
				</div>
			</div>
		</div>
	</div>
</div>';

html_footer("../../");
?>

<script type="text/javascript" class="init">

/* Global vars initialisation */
var expID=-1;
var oldValues;
var spl_alq_map = [];

/* Hide sample table while no experiment is selected */
$('#sample-wrapper').hide();

$(document).ready(function() {
	/* Let the navigation menu open */
	$('#userPanel').removeClass('submenu');
	$('#userPanel').addClass('submenu open');

	/* fw table need to be instantiate for further event (add rows) */
	setup_experiment_datatable();
	setup_freshweight_datatable(1);

	/* Listener on the experiment table */
	$('#expTable tbody').on('click', 'tr', function () {
		$('#sample-wrapper').show();
		expID = $('td', this).eq(0).text();
		if(expID == -1) {
			$('#statusSpan').html('<div class="alert alert-error"> Unselected Experience<a href="#" data-dismiss="alert" class="close">×</a></div>');
		}
		else {
			setup_freshweight_datatable(expID);
		}
	});

	/* Listener on single row add button */
	$('#addRow').click(function(e){
		var table = $('#spl_alq_table').DataTable();
		var rowNode = table
			.row.add( [ 
				'0',
				'<input class="form-control input-sm" type="text" id="sampleNB" value="1">',
				'0',
				'<input class="form-control input-sm" type="text" id="aliquotNB" value="1">',
				'<input class="form-control input-sm" type="text" id="value" value="0">',
				'<input class="form-control input-sm" type="text" id="location" value="">',
				'<input class="form-control input-sm" type="text" id="state" value="free">',
				'0',
				'0',
				"<button type= \"button\" id=\"createButton\" class=\"tabledit-edit-button btn btn-info btn-sm btn-default\" style=\"float: none;\"> <span class=\"glyphicon glyphicon-ok\"> </span> </button>"+
				"&nbsp <button type= \"button\" id=\"cancelRowButton\" class=\"tabledit-cancel-button btn btn-default btn-sm btn-default\" style=\"float: none;\"> <span class=\"glyphicon glyphicon-ban-circle\"> </span> </button>"])
			.draw()
			.node();
		$( rowNode )
			.css( 'color', 'blue' )
			.animate( { color: 'black' } );

		$( "button" ).filter( "#editButton" ).prop("disabled",true);
		$( "button" ).filter( "#deleteButton" ).prop("disabled",true);
		$( "button" ).filter( "#addRow" ).prop("disabled",true);
		$( "button" ).filter( "#addRows" ).prop("disabled",true);
	});

	/* Listener on add rows button */
	$('#addRows').click(function(e){
		create_addrows_modal();
	});

	/* Listener on add materials button */
	$('#createExpButton').click(function(e){
		create_addExp_modal();
	});

	/* Listener on possible events on sample aliquot table (delete, modify, save, cancel...) */
	$('#spl_alq_table tbody').on('click', 'button', function() {
		var table = $('#spl_alq_table').DataTable();
		var oTable = $('#spl_alq_table').dataTable();
		var rowID = table.row( $(this).parents('tr') ).index();
		var rowData = table.row( $(this).parents('tr') ).data();

		if(this.id == "editButton"){
			oldValues = save_row_state(table, rowID);
			make_row_editable(expID, table, rowID);
		}
		else if (this.id == "deleteButton"){
			delete_row(expID, table, oTable, rowID, "delete");
		}
		else if(this.id == "saveButton"){
			save_row(expID, table, oTable, rowID, "edit", oldValues);
		}
		else if(this.id == "cancelButton"){
			make_row_uneditable(table, rowID, oldValues);
		}
		else if(this.id == "createButton"){
			save_row(expID, table, oTable, rowID, "create", oldValues);
		}
		else if(this.id == "cancelRowButton"){
			table
				.row(rowID)
				.remove()
				.draw();
			$( "button" ).filter( "#editButton" ).prop("disabled",false);
			$( "button" ).filter( "#deleteButton" ).prop("disabled",false);
			$( "button" ).filter( "#addRows" ).prop("disabled",false);
			$( "button" ).filter( "#addRow" ).prop("disabled",false);
		}
		
	});
});

/* Listener for data paste in addrows modal */
$(document).bind("paste", "#sampleNbAddRows", function(e){
	var data = e.originalEvent.clipboardData.getData('Text');
	data=chomp(data);
	dispatchAddRowsDatas(data);
});

/* Save data pasted in addrows modal */
$(document).on("click", "#addRowsSubmit", function(e){
	save_addTable_content("addRowsTable");
});

/* Clear data in add multiple freshweight modal */
$(document).on("click", "#clearModal", function(e){
	resetModalTable();
});

/* Add new material */
$(document).on("click", "#materialSubmit", function(e){
	var expName = $('#expName').val();
	$.ajax({
		url: "insert_exp.php",
		type: "post",
		data: { expName : expName },
		beforeSend: function(){
				showWaitModal();
		},
		success: function(data) {
			var obj = data;
			if(obj.status == 'success'){
				$('#statusSpan').html('<div class="alert alert-success">'+obj.action+' Successful<a href="#" data-dismiss="alert" class="close">×</a></div>');
				$("#createExpModal").modal("hide");
			}
			else if(obj.status == 'error'){
				$('#statusSpan').html('<div class="alert alert-error">'+obj.action+' Failure<a href="#" data-dismiss="alert" class="close">×</a></div>');
				$("#createExpModal").modal("hide");
			}
		},
		error: function(xhr, status, error) {
			$('statusSpan').html('<div class="alert alert-error">Insertion Error : '+xhr.responseText+error+'<a href="#" data-dismiss="alert" class="close">×</a></div>');
		},
		complete: function(){
			hideWaitModal();
		}
	}); 
	setup_experiment_datatable();
});


/**
 * Display a modal with a progress bar when loading datas
 *
 * @method     showWaitModal
 */
function showWaitModal(){
    var modal = '<div class="modal-dialog modal-sm">'+
        '<div class="modal-content">'+
            '<div class="modal-header">'+
                '<h4 class="modal-title">'+
                    '<span class="glyphicon glyphicon-time">'+
                    '</span>Please Wait'+
                 '</h4>'+
            '</div>'+
            '<div class="modal-body">'+
                '<div id="insertProgressDiv" class="progress progress-striped active">'+
					'<div class="bar" style="width: 100%;"></div>'+
				'</div>'+
            '</div>'+
        '</div>'+
    '</div>';
    $('#waitModal').empty();
	$('#waitModal').append(modal);
	$("#waitModal").modal("show");
}

/**
 * Hide the progress bar when loading is over
 *
 * @method     hideWaitModal
 */
function hideWaitModal(){
	$("#waitModal").modal("hide");
}


/**
 * Control that data pasted in addrows are acceptables
 *
 * @method     controlAddData
 * @param      {<type>}  tableID  { description }
 */
function controlAddData(tableID){
	var table=document.getElementById(tableID);
//	console.log(table.rows.length);
//	console.log(table.rows[0]);
	var boolOK = true;
	var tabSplAlq = {};

	for(var i=1; i<table.rows.length;i++){
		tabSplAlq[table.rows[i].cells[0].firstChild.value]={};
	}

	for(var i=1; i<table.rows.length;i++){
		tabSplAlq[table.rows[i].cells[0].firstChild.value][table.rows[i].cells[1].firstChild.value]=0;
	}

	for(var i=1; i<table.rows.length;i++){
		tabSplAlq[table.rows[i].cells[0].firstChild.value][table.rows[i].cells[1].firstChild.value]+=1;
	}

//	console.log(tabSplAlq);



	for(var i=1; i<table.rows.length;i++){
		var sampleNB = table.rows[i].cells[0].firstChild.value;
		var aliquotNB = table.rows[i].cells[1].firstChild.value;
		var value = table.rows[i].cells[2].firstChild.value;
		/* coma are not accepted for the database */
		value = value.replace(/,/g, '.');
		var state = table.rows[i].cells[4].firstChild.value;
		var location = table.rows[i].cells[3].firstChild.value;
		var action="create";

		// console.log(sampleNB+". : ."+aliquotNB+"|");
		var boolExists = $('#spl_alq_table tr > td:contains( '+sampleNB+' ) + td:contains( '+aliquotNB+' )').length;
		//	console.log("BOOOOOOOOOOOOL 1 : "+boolExists);

		if(tabSplAlq[sampleNB][aliquotNB] > 1){
//			console.log("doublons");
			$('#addRowsTable tr').each(function(index){
				if( index == i)
					$(this).css("background-color", "#FF9494");
			});
			boolOK = false;
		}
		else if(boolExists > 0) {
//			console.log("deja present");
			$('#addRowsTable tr').each(function(index){
			    if( index == i)
			     $(this).css("background-color", "#FF9494");
			});
			boolOK = false;
		}
		else if((aliquotNB =="") || (! $.isNumeric(aliquotNB))) {
//			console.log("aliquot");
			$('#addRowsTable tr').each(function(index){
			    if( index == i) 
			     $(this).css("background-color", "#FF9494");
			}); 
			boolOK = false;
		}
		else if ((sampleNB =="") || (! $.isNumeric(sampleNB))) {
//			console.log("sample");
			$('#addRowsTable tr').each(function(index){
			    if( index == i) 
			     $(this).css("background-color", "#FF9494");
			});
			boolOK = false;
		}
		else if ((value =="") || (! $.isNumeric(value))) {
//			console.log("value");
			$('#addRowsTable tr').each(function(index){
			    if( index == i) 
			     $(this).css("background-color", "#FF9494");
			});
			boolOK = false;
		}
		else if ( $.isNumeric(state)) {
//			console.log("state : "+state);
			$('#addRowsTable tr').each(function(index){
			    if( index == i) 
			     $(this).css("background-color", "#FF9494");
			});
			boolOK = false;
		}
		else {
			$('#addRowsTable tr').each(function(index){
			    if( index == i) 
			     $(this).css("background-color", "#DFF2BF");
			});
		}
	}
	if(boolOK!=true){
		$('#addRowsTips').html('<div class="alert alert-error"> Please check for redundancy, wrong or empty values <a href="#" data-dismiss="alert" class="close">×</a></div>');
	}
	return boolOK;
}


/**
 * return an array with all table content
 *
 * @method     get_table_content
 * @param      {<type>}  tableID  { description }
 */
function save_addTable_content(tableID){
	var table=document.getElementById(tableID);
	var tempBool=true;
	showWaitModal();
	for(var i=1; i<table.rows.length;i++){
		var sampleNB = table.rows[i].cells[0].firstChild.value;
		var aliquotNB = table.rows[i].cells[1].firstChild.value;
		var value = table.rows[i].cells[2].firstChild.value;
		value = value.replace(/,/g, '.');
		var state = table.rows[i].cells[4].firstChild.value;
		var location = table.rows[i].cells[3].firstChild.value;
		var action="create";

		$.ajax({
			url: "UpdateData.php",
			type: "post",
			data: { 
				sampleNB : sampleNB,
				aliquotNB : aliquotNB,
				value : value,
				state : state,
				location : location,
				action : action,
				expID : expID
			},
			success: function(data) {
				var obj = data;
				if(obj.status == 'success'){
					$('#addRowsTable tr').each(function(index){
						if( index == i) {
							$(this).css("background-color", "#DFF2BF");
						}
					}); 
				}
				else if(obj.status == 'error'){
					$('#addRowsTable tr').each(function(index){
						if( index == i) {
							$(this).css("background-color", "#FF9494");
						}
					});
					tempBool=false;
				}
				
			},
			error: function(xhr, status, error) {
			}
		});
	}
	if(tempBool ==true){
		$("#addRowsModal").modal("hide");
		$('#statusSpan').html('<div class="alert alert-success"> Insertion Successful<a href="#" data-dismiss="alert" class="close">×</a></div>');
		setup_freshweight_datatable(expID);
	}
	else {
		$('addRowsTips').html('<div class="alert alert-error">Insertion Error <a href="#" data-dismiss="alert" class="close">×</a></div>');
	}
	hideWaitModal();

}

/**
 * delete a row
 *
 * @method     delete_row
 * @param      {<type>}  expID   { description }
 * @param      {<type>}  table   { description }
 * @param      {<type>}  oTable  { description }
 * @param      {<type>}  rowID   { description }
 * @param      {string}  action  { description }
 */
function delete_row(expID, table, oTable, rowID, action){
	var sampleID = table.cell(rowID, 0).data();
	var aliquotID = table.cell(rowID, 2).data();
	var fwID = table.cell(rowID, 7).data();
	$.ajax({
        url: "UpdateData.php",
        type: "post",
        data: { 
        	sampleID : sampleID,
        	aliquotID : aliquotID,
        	action : action,
        	expID : expID,
        	fwID : fwID
        },
		success: function(data) {
			var obj = data;
			if(obj.status == 'success'){
				$('#statusSpan').html('<div class="alert alert-success">'+obj.action+' Successful<a href="#" data-dismiss="alert" class="close">×</a></div>');
				setup_freshweight_datatable(expID);
			}
			else if(obj.status == 'error'){
				$('#statusSpan').html('<div class="alert alert-error">'+obj.action+' Failure<a href="#" data-dismiss="alert" class="close">×</a></div>');
			}
			$( "button" ).filter( "#editButton" ).prop("disabled",false);
			$( "button" ).filter( "#deleteButton" ).prop("disabled",false);
			$( "button" ).filter( "#addRows" ).prop("disabled",false);
			$( "button" ).filter( "#addRow" ).prop("disabled",false);

		},
		error: function(xhr, status, error) {
		    $('statusSpan').html('<div class="alert alert-error">Insertion Error : '+xhr.responseText+error+'<a href="#" data-dismiss="alert" class="close">×</a></div>');
		}
	});
}



/**
 * save the value of a state in case of the edition fails
 *
 * @method     save_row_state
 * @param      {<type>}    expID   { description }
 * @param      {Function}  table   { description }
 * @param      {<type>}    rowID   { description }
 */
function save_row_state(table, rowID){
	var cell = table.cell(rowID, 1);
	var cellState = {
		sampleNB : table.cell(rowID, 1).data(),
		aliquotNB : table.cell(rowID, 3).data(),
		value : table.cell(rowID, 4).data(),
		state : table.cell(rowID, 6).data(),
		location : table.cell(rowID, 5).data()
	};
	return cellState;
}


/**
 * setup freshweight datatables for an experiment
 *
 * @method     setup_datatable
 * @param      {<type>}  expID   { description }
 */
function setup_freshweight_datatable(expID){
	showWaitModal();
	$('#spl_alq_table').dataTable().fnDestroy();
	$('#spl_alq_table').DataTable({
		scrollY:        600,
		scroller:       true,
		responsive: false,
		stateSave: true,
		select: "single",
		dom: 'TB<"clear">frtip',
		ajax: {
			url: "get_sample_aliquot_from_one_exp.php",
			type: "POST",
			data: { expID : expID }
		},
		"columnDefs": [
		{
			"targets": [ 0, 2, -2, -3],
			"visible": false,
			"searchable": false,
		},
		{
			"targets":-1,
			"defaultContent": "<button type=\"button\" id=\"editButton\" class=\"tabledit-edit-button btn btn-info btn-sm btn-default\" style=\"float: none;\"> <span class=\"glyphicon glyphicon-pencil\"> </span> </button>"+
			"&nbsp <button type= \"submit\" id=\"deleteButton\" class=\"tabledit-edit-button btn btn-danger btn-sm btn-default\" style=\"float: none;\"> <span class=\"glyphicon glyphicon-trash\"> </span> </button>"
		},
		{ "name": "sampleSearch",  "targets": 1 },
		{ "name": "aliquotSearch",  "targets": 3 }
		],
		buttons: [
			'copy', 'csv', 'excel', 'print'
		],
		order: [[ 1, 'asc' ], [ 3, 'asc' ]]
	});
	hideWaitModal();
}


/**
 * setup the table of experiments
 *
 * @method     setup_experiment_datatable
 */
function setup_experiment_datatable(){
	$('#expTable').dataTable().fnDestroy();
		var table = $('#expTable').DataTable({
			scrollY :	600,
			scroller :	true,
			stateSave: true,
			select :	"single",
			paging : 	true,
			dom : 		'TB<"clear">frtip',
			ajax : 		'get_all_experiment.php',
			buttons: [
				'copy', 'csv', 'excel', 'print'
			],
			order: [[ 1, 'asc' ]],
			columnDefs: [
				{
					"targets": -1,
					"visible": false,
					"searchable":false
				}
			],
		createdRow: function( row, data, dataIndex ) {
//			console.log(row);
//			console.log(dataIndex);
//			console.log(data);

			if ( data[2] == "") {
//				console.log("stdName : "+data[2]);
				$(row).css('background-color', '#FDFEFE');
			}
			else{
//				console.log("stdName : "+data[2]);
				$(row).css('background-color', '#D5F5E3');
			}
		}
	});
}





/**
 * Make a row editable in a datatable using table object and cell datatbles properties
 *
 * @method     make_row_editable
 * @param      {<type>}             expID   { description }
 * @param      {(Function|string)}  table   { description }
 * @param      {<type>}             rowID   { description }
 */
function make_row_editable(expID, table, rowID){
	var cell = table.cell(rowID, 1);
	table.cell(rowID, 1).data('<input class="form-control input-sm" type="text" id="sampleNB" value="'+table.cell(rowID, 1).data()+'">').draw();
	table.cell(rowID, 3).data('<input class="form-control input-sm" type="text" id="aliquotNB" value="'+table.cell(rowID, 3).data()+'">').draw();
	table.cell(rowID, 4).data('<input class="form-control input-sm" type="text" id="value" value="'+table.cell(rowID, 4).data()+'">').draw();
	table.cell(rowID, 6).data('<input class="form-control input-sm" type="text" id="state" value="'+table.cell(rowID, 6).data()+'">').draw();
	table.cell(rowID, 5).data('<input class="form-control input-sm" type="text" id="location" value="'+table.cell(rowID, 5).data()+'">').draw();
	table.cell(rowID, 9).data("<button type= \"button\" id=\"saveButton\" class=\"tabledit-edit-button btn btn-info btn-sm btn-default\" style=\"float: none;\"> <span class=\"glyphicon glyphicon-ok\"> </span> </button>"+
		"&nbsp <button type= \"button\" id=\"cancelButton\" class=\"tabledit-cancel-button btn btn-default btn-sm btn-default\" style=\"float: none;\"> <span class=\"glyphicon glyphicon-ban-circle\"> </span> </button>").draw();
	
	//disable all buttons while the current action is not termined
	$( "button" ).filter( "#editButton" ).prop("disabled",true);
	$( "button" ).filter( "#deleteButton" ).prop("disabled",true);
	$( "button" ).filter( "#addRows" ).prop("disabled",true);
	$( "button" ).filter( "#addRow" ).prop("disabled",true);
}

/**
 * make a row uneditable
 *
 * @method     make_row_uneditable
 * @param      {Function}  table      { description }
 * @param      {<type>}    rowID      { description }
 * @param      {<type>}    sampleNB   { description }
 * @param      {<type>}    aliquotNB  { description }
 * @param      {<type>}    value      { description }
 * @param      {<type>}    state      { description }
 */
function make_row_uneditable(table, rowID, values){
	var cell = table.cell(rowID, 1);
	table.cell(rowID, 1).data(values.sampleNB).draw();
	table.cell(rowID, 3).data(values.aliquotNB).draw();
	table.cell(rowID, 4).data(values.value).draw();
	table.cell(rowID, 6).data(values.state).draw();
	table.cell(rowID, 5).data(values.location).draw();
	table.cell(rowID, 9).data("<button type=\"button\" id=\"editButton\" class=\"tabledit-edit-button btn btn-info btn-sm btn-default\" style=\"float: none;\"> <span class=\"glyphicon glyphicon-pencil\"> </span> </button>"+
	"&nbsp <button type= \"submit\" id=\"deleteButton\" class=\"tabledit-edit-button btn btn-danger btn-sm btn-default\" style=\"float: none;\"> <span class=\"glyphicon glyphicon-trash\"> </span> </button>").draw();

	setup_freshweight_datatable(expID);
	//disable all buttons while the current action is not termined
	$( "button" ).filter( "#editButton" ).prop("disabled",false);
	$( "button" ).filter( "#deleteButton" ).prop("disabled",false);

	$( "button" ).filter( "#addRows" ).prop("disabled",false);
	$( "button" ).filter( "#addRow" ).prop("disabled",false);
}


/**
 * Save a row
 *
 * @method     save_row
 * @param      {Int}      expID   { ID of the selected experiment }
 * @param      {objet}    table   { Datatable object }
 * @param      {objet}    oTable  { Datatables object }
 * @param      {Int}      rowID   { ID de la ligne a modifier }
 * @param      {string}   action  { action a effectuer dans UpdatdeData.php }
 */
function save_row(expID, table, oTable, rowID, action, oldValues) {
	var sampleNB = $("#sampleNB").val().trim();
	var sampleID = table.cell(rowID, 0).data();
	var aliquotNB = $("#aliquotNB").val().trim();
	var aliquotID = table.cell(rowID, 2).data();
	var value = $("#value").val();
	var state = $("#state").val();
	var location = $('#location').val();
	var locID = table.cell(rowID, 8).data();

	// var boolExists = $('#spl_alq_table tr > td:contains( '+sampleNB+' ) + td:contains( '+aliquotNB+' )').length;
		var boolExists = $('#spl_alq_table tr > td:contains( '+sampleNB+' ) + td:contains( '+aliquotNB+' )').length;
		// var boolExists2 = $('#spl_alq_table tr > td:contains('+sampleNB+') + td:contains('+aliquotNB+')').length;
		// console.log("BOOOOOOOOOOOOL 1 : "+boolExists);
		// console.log("BOOOOOOOOOOOOL 2 : "+boolExists2);

	if(boolExists > 0) {
		$("#sampleNB").css('background-color', '#FF9494');
		$("#aliquotNB").css('background-color', '#FF9494');
		$('#statusSpan').html('<div class="alert alert-error">Freshweight already entered<a href="#" data-dismiss="alert" class="close">×</a></div>');
	}
	else if((aliquotNB =="") || (! $.isNumeric(aliquotNB))) {
		$("#aliquotNB").css('background-color', '#FF9494');
		$('#statusSpan').html('<div class="alert alert-error">Aliquot Empty or wrong<a href="#" data-dismiss="alert" class="close">×</a></div>');
	}
	else if ((sampleNB =="") || (! $.isNumeric(sampleNB))) {
		$("#sampleNB").css('background-color', '#FF9494');
		$('#statusSpan').html('<div class="alert alert-error">Sample Empty or wrong <a href="#" data-dismiss="alert" class="close">×</a></div>');
	}
	else if ((value =="") || (! $.isNumeric(value))) {
		$("#value").css('background-color', '#FF9494');
		$('#statusSpan').html('<div class="alert alert-error">Value Empty or wrong<a href="#" data-dismiss="alert" class="close">×</a></div>');
	}
	else{
		$.ajax({
	        url: "UpdateData.php",
	        type: "post",
	        data: { 
	        	sampleID : sampleID,
	        	sampleNB : sampleNB,
	        	aliquotID : aliquotID,
	        	aliquotNB : aliquotNB,
	        	value : value,
	        	state : state,
	        	location : location,
	        	action : action,
	        	expID : expID,
	        	locID : locID
	        },
			success: function(data) {
				var obj = data;
				if(obj.status == 'success'){
					var newValues = {
			        	sampleNB : ''+sampleNB+'',
			        	aliquotNB : ''+aliquotNB+'',
			        	value : value,
			        	state : state,
			        	location : location
			        };
					make_row_uneditable(table, rowID, newValues);
					$('#statusSpan').html('<div class="alert alert-success">'+obj.action+' Successful<a href="#" data-dismiss="alert" class="close">×</a></div>');
				}
				else if(obj.status == 'error'){
					make_row_uneditable(table, rowID, oldValues);
					$('#statusSpan').html('<div class="alert alert-error">'+obj.action+' Failure<a href="#" data-dismiss="alert" class="close">×</a></div>');
				}
			},
			error: function(xhr, status, error) {
			    $('statusSpan').html('<div class="alert alert-error">Insertion Error : '+xhr.responseText+error+'<a href="#" data-dismiss="alert" class="close">×</a></div>');
			}
		});
	}
}

/**
 * Modal window for new experiment
 *
 * @method     create_addExp_modal
 */
function create_addExp_modal(){
	var modal =''+
	  '<div class="modal-dialog">'+
		'<div class="modal-content">'+
			'<div class="modal-header">'+
	    		'<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>'+
	    		'<h4 class="modal-title">New Experiment <span id=modalBatchName></span></h4>'+
	  		'</div>'+
	  			'<div class="modal-body">'+
		  			'<div class="form-group">'+
			            '<label class="control-label" for="number">Experiment Name</label>'+
			            '<div class="form-group has-error">'+
			            	'<input type="text" class="form-control required" id="expName" name="name" placeholder="Experiment name" required="required" /><br>'+
			        	'</div>'+
					'</div>'+
				'</div> <!-- /.modal-body -->'+
				'<div class="modal-footer">'+
					'<button type="button" data-dismiss="modal" class="btn btn-large">Close</button>'+
					'<button type="submit" id="materialSubmit"  class="btn btn-primary btn-large">Submit</button>'+
				'</div>'+
			'</div> <!-- /.modal-content -->'+
		'</div> <!-- /.modal-dialog -->'+

	$('#createExpModal').empty();
	$('#createExpModal').append(modal);
	$("#createExpModal").modal("show");
}

function resetModalTable (){
	var modal ='<thead>'+
					'<tr>'+
						'<th>Sample</th>'+
						'<th>Aliquot</th>'+
						'<th>Value</th>'+
						'<th>Location</th>'+
						'<th>State</th>'+
					'</tr>'+
				'</thead>'+
				'<tbody>'+
					'<tr>'+
					'<td><input class="form-control input-sm" type="text" id="sampleNbAddRows" value="" placeholder="Paste all Data Here"></td>'+
					'<td><input class="form-control input-sm" type="text" id="aliquotNbAddRows" value="" disabled></td>'+
					'<td><input class="form-control input-sm" type="text" id="valueAddRows" value="" disabled></td>'+
					'<td><input class="form-control input-sm" type="text" id="locationAddRows" value="UndefLocation" disabled></td>'+
					'<td><input class="form-control input-sm" type="text" id="stateAddRows" value="free" disabled></td>'+
					'</tr>'+
				'</tbody>';

	$('#addRowsTable').html(modal);
	$('#addRowsTips').html('<div class="alert alert-success"> Please paste all your data in the first field <a href="#" data-dismiss="alert" class="close">×</a></div>');
	$( "button" ).filter( "#addRowsSubmit" ).prop("disabled",true);
}

// .modal-body {
//     max-height: calc(100vh - 210px);
//     overflow-y: auto;
// }

/**
 * modal window for multiple row adding
 *
 * @method     create_addrows_modal
 */
function create_addrows_modal() {
	var modal ='<div class="modal-dialog">'+
		'<div class="modal-content">'+
			'<div class="modal-header">'+
				'<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>'+
				'<h4 class="modal-title">Freshweight Insertion</h4>'+
			'</div>'+
			'<div class="modal-body" style="max-height:600px;overflow-y:auto;">'+
				'<span id="addRowsTips">'+
					'<div class="alert alert-success"> Please paste all your data in the first field <a href="#" data-dismiss="alert" class="close">×</a></div>'+
				'</span>'+
				'<table id="addRowsTable" class="table dt-right table_bordered dt-right">'+
					'<thead>'+
						'<tr>'+
							'<th>Sample</th>'+
							'<th>Aliquot</th>'+
							'<th>Value</th>'+
							'<th>Location</th>'+
							'<th>State</th>'+
						'</tr>'+
					'</thead>'+
					'<tbody>'+
						'<tr>'+
						'<td><input class="form-control input-sm" type="text" id="sampleNbAddRows" value="" placeholder="Paste all Data Here"></td>'+
						'<td><input class="form-control input-sm" type="text" id="aliquotNbAddRows" value="" disabled></td>'+
						'<td><input class="form-control input-sm" type="text" id="valueAddRows" value="" disabled></td>'+
						'<td><input class="form-control input-sm" type="text" id="locationAddRows" value="UndefLocation" disabled></td>'+
						'<td><input class="form-control input-sm" type="text" id="stateAddRows" value="free" disabled></td>'+
						'</tr>'+
					'</tbody>'+
				'</table>'+
			'</div>'+
			'<div class="modal-footer">'+
				'<button type="button" id="clearModal" class="btn btn-large">Clear</button>'+
				'<button type="button" data-dismiss="modal" class="btn btn-large">Close</button>'+
				'<button type="submit" id="addRowsSubmit" class="btn btn-primary btn-large" disabled>Submit</button>'+
			'</div>'+
	'</div> <!-- /.modal-content -->'+
'</div> <!-- /.modal-dialog -->';

	$('#addRowsModal').empty();
	$('#addRowsModal').append(modal);
	$("#addRowsModal").modal("show");
}


/**
 * remove useless strings (retour chariot a la fin de ligne, equivalent dechomp en perl)
 *
 * @method     chomp
 * @param      {string}  raw_text  { description }
 * @return     {string}  { description_of_the_return_value }
 */
function chomp(raw_text){
  return raw_text.replace(/(\n|\r)+$/, '');
}


function chomp_array(array){
	var newArray = [];
	for (var i=0; i< array.length; i++){
		if(array[i]!=""){
			newArray.push(array[i].replace(/(\n|\r)+$/, ''));
		}
	}
	return newArray;
}

/**
 * display data on row tables when past.
 *
 * @method     dispatchAddRowsDatas
 * @param      {(number|string)}  data    { description }
 */
function dispatchAddRowsDatas(data) {
	//data = data.trim();
	var rowsdatas = data.split(/(?:\n)+/);
	var dataLength = rowsdatas.length;
	var newRowContent="";
	for (var i=0; i < dataLength; i++) {
		var rowDatas = rowsdatas[i].split(/(?:\t)+/);
//		console.log(rowDatas);
//		console.log(rowDatas.length);
		rowDatas = chomp_array(rowDatas);
		if (rowDatas.length ==3){
			newRowContent += ''+
				'<tr>'+
					'<td><input class="form-control input-sm" type="text" id="sampleNbAddRows" value="'+rowDatas[0]+'" ></td>'+
					'<td><input class="form-control input-sm" type="text" id="aliquotNbAddRows" value="'+rowDatas[1]+'"></td>'+
					'<td><input class="form-control input-sm" type="text" id="valueAddRows" value="'+rowDatas[2]+'"></td>'+
					'<td><input class="form-control input-sm" type="text" id="locationAddRows" value="UndefLocation"></td>'+
					'<td><input class="form-control input-sm" type="text" id="stateAddRows" value="free"></td>'+
				'</tr>';
		}
		if (rowDatas.length ==4){
			newRowContent += ''+
				'<tr>'+
					'<td><input class="form-control input-sm" type="text" id="sampleNbAddRows" value="'+rowDatas[0]+'" ></td>'+
					'<td><input class="form-control input-sm" type="text" id="aliquotNbAddRows" value="'+rowDatas[1]+'"></td>'+
					'<td><input class="form-control input-sm" type="text" id="valueAddRows" value="'+rowDatas[2]+'"></td>'+
					'<td><input class="form-control input-sm" type="text" id="locationAddRows" value="'+rowDatas[3]+'"></td>'+
					'<td><input class="form-control input-sm" type="text" id="stateAddRows" value="free"></td>'+
				'</tr>';
		}
	} 
	$("#addRowsTable tbody").html(newRowContent);

	var boolOK = controlAddData("addRowsTable");
	if(boolOK==true){
		$( "button" ).filter( "#addRowsSubmit" ).prop("disabled",false);
	}
}








</script>