<?php
require('../functions/check_login.php');
include '../functions/html_functions.php';
include '../functions/php_functions.php';

html_header("../../",  $_SESSION['login']);

editable_html_top_page("../../","Batches");

echo '	<a href="#" title="Declare a new Batch" class="tip-bottom" id="createAddBatchButton"><i class="icon-plus"></i>Create Batch</a>
</div>

<div id="statusSpan">
</div>';

echo'
<!-- Modal -->
<div class="modal fade" id="addBatchModal" tabindex="-1" role="dialog" aria-hidden="true">
</div> 

<div class="modal fade" id="waitModal" tabindex="999" role="dialog" aria-hidden="true">
</div>
<!-- /.modal -->
';

echo'
<div class="row-fluid">
	<div class="span5">
		<div class="widget-box" style="background : transparent">
			<div class="widget-content nopadding">
				<div id="expender-wrapper">
					<button type="button" class="btn btn-xs btn-info" id ="expender" style="width:100%"><i class="icon-list"></i> &nbsp; Expend experiment table </button>
				</div>
				<div id="datatable-wrapper">
					<table id="expTable" class="table display table-bordered table-hover" style="width:100%">
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
		</div>
	</div>';

	echo'
	<div class="span5">
		<div id=select-wrapper>
			<h4> Select Batch </h4>
			<div class="form-group">
				<select id="selectBatch" class="form-control" name="selectBatch">';
					echo '<option value=""> ----- Select ----- </option>';
				echo' </select>
			</div>
		</div>
	</div>
</div>
	';

	echo'
	<div class="row-fluid">
		<div id="table-wrapper">
			<div class="span5">
				<h4>Batch layout </h4>
				<p></p> 
			</div>';
			echo '
			<div class="row-fluid">
				<div class="span12">
					<div class="widget-box" style="background : transparent">
						<div class="widget-content nopadding">
							<div class="container-fluid">
								<div class="responsive-table-line">
									<table id="batchTable" class="table dt-right table-bordered" style="width:100%">
										<thead>
											<tr>
												<th>#</th>
												<th>1</th>
												<th>2</th>
												<th>3</th>
												<th>4</th>
												<th>6</th>
												<th>5</th>
												<th>7</th>
												<th>8</th>
												<th>9</th>
												<th>10</th>
												<th>11</th>
												<th>12</th>
											</tr>
										</thead>
										<tbody>
											<tr>
												<td data-title="Droit">A</td>
												<td></td>
									            <td></td>
									            <td></td>
									            <td></td>
									            <td></td>
									            <td></td>
									            <td></td>
									            <td></td>
									            <td></td>
									            <td></td>
									            <td></td>
									            <td></td>
											</tr>
											<tr>
												<td data-title="Droit">B</td>
												<td></td>
									            <td></td>
									            <td></td>
									            <td></td>
									            <td></td>
									            <td></td>
									            <td></td>
									            <td></td>
									            <td></td>
									            <td></td>
									            <td></td>
									            <td></td>
											</tr>
											<tr>
												<td data-title="Droit">C</td>
												<td></td>
									            <td></td>
									            <td></td>
									            <td></td>
									            <td></td>
									            <td></td>
									            <td></td>
									            <td></td>
									            <td></td>
									            <td></td>
									            <td></td>
									            <td></td>
											</tr>
											<tr>
												<td data-title="Droit">D</td>
												<td></td>
									            <td></td>
									            <td></td>
									            <td></td>
									            <td></td>
									            <td></td>
									            <td></td>
									            <td></td>
									            <td></td>
									            <td></td>
									            <td></td>
									            <td></td>
											</tr>
											<tr>
												<td data-title="Droit">E</td>
												<td></td>
									            <td></td>
									            <td></td>
									            <td></td>
									            <td></td>
									            <td></td>
									            <td></td>
									            <td></td>
									            <td></td>
									            <td></td>
									            <td></td>
									            <td></td>
											</tr>
											<tr>
												<td data-title="Droit">F</td>
												<td></td>
									            <td></td>
									            <td></td>
									            <td></td>
									            <td></td>
									            <td></td>
									            <td></td>
									            <td></td>
									            <td></td>
									            <td></td>
									            <td></td>
									            <td></td>
											</tr>
											<tr>
												<td data-title="Droit">G</td>
												<td></td>
									            <td></td>
									            <td></td>
									            <td></td>
									            <td></td>
									            <td></td>
									            <td></td>
									            <td></td>
									            <td></td>
									            <td></td>
									            <td></td>
									            <td></td>
											</tr>
											<tr>
												<td data-title="Droit">H</td>
												<td></td>
												<td></td>
												<td></td>
												<td></td>
												<td></td>
												<td></td>
												<td></td>
												<td></td>
												<td></td>
												<td></td>
												<td></td>
												<td></td>
											</tr>
										</tbody>
									</table>
								</div>
							</div> <!-- container-fluid-->
						</div><!--widget-content-->
					</div>
				</div><!--span12-->
			</div><!--row-fluid-->
				<!--<div class="row-fluid">
					<div class="btn-toolbar" role="toolbar" aria-label="">
						<div class="btn-group" role="group" aria-label="">
							<button type="button" class="btn btn-info btn-lg" id ="editBatch"><i class="icon-pencil"></i> Edit batch </button>
						</div>
						<div class="btn-group" role="group" aria-label="">
							<button type="button" class="btn btn-danger btn-lg" id ="clearBatch"><i class="icon-remove"></i> Clear All </button>
						</div>
						<div class="btn-group" role="group" aria-label="">
							<button type="button" class="btn btn-success btn-lg" id ="saveBatch" disabled><i class="icon-ok"></i> Save </button>
						</div>
						<div class="btn-group" role="group" aria-label="">
							<button type="button" class="btn btn-default btn-lg" id ="cancelBatch" disabled><i class="icon-ban-circle"></i> Cancel </button>
						</div>
					</div>
				</div>-->
			</div>';
		echo' 		
		</div><!--table wrapper-->
	</div><!--row-fluid-->
';

html_footer("../../");
?>

<script type="text/javascript" class="init">

	var expID="";
	var expName="";
	var currentBatchID ="";


$(document).ready(function() {
	$('#userPanel').removeClass('submenu');
	$('#userPanel').addClass('submenu open');

	$('#table-wrapper').hide();
	$('#select-wrapper').hide();
	$('#expender-wrapper').hide();

	/* Fill experiment table */
	setup_experiment_datatable();

	/* Listener on experiment table */
	$('#expTable').on('click', 'tr', function () {
		expID = $('td', this).eq(0).text();
		expName = $('td', this).eq(1).text();
		$.ajax({
			url: "batches_table_single.php",
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
			}
		});
	});

	/* Listener on the select batch input */
	$( "select" ).change( displayVals );

	$( "#expender" ).click(function() {
		$('#table-wrapper').hide();
		$('#expender-wrapper').hide();
		$('#datatable-wrapper').show();
	});

	/* Listener for batch creation */
	$( "#createAddBatchButton" ).click(function() {
		create_addBatch_modal();
	});

});

$(document).on("change", "#batchName" , function(e){
	$.ajax({
		url: "controlBatchNameAndNumber.php",
		type: "post",
		data: {batchName : $('#batchName').val()},
		dataType : 'text',
		success: function(data) {
			var obj = JSON.parse(data);
			if(obj.status == 'success'){
				$("#batchNumber").prop('disabled', false);
				if(obj.value !=null){
					$("#info-batchNumber").html(function(){
						return "The last batch with this name has the number : "+obj.value;
					});
				}
				else{
					$("#info-batchNumber").html(function(){
						return ""; 
					});
				}
			}
		}
	});
});

$(document).on("input", "#batchNumber" , function(e){
	console.log("input");
	if($('#batchNumber').val() == ""){
		$( "button" ).filter( "#addBatchSubmit" ).prop("disabled",true);
	}
	$.ajax({
		url: "controlBatchNameAndNumber2.php",
		type: "post",
		data: {batchName : $('#batchName').val(), batchNumber : $('#batchNumber').val()},
		dataType : 'text',
		success: function(data) {
			var obj = JSON.parse(data);
			if(obj.status == 'success'){
				var tipsStatus = document.getElementById("batchOk").className;

				if(obj.value !=false){
					$( "button" ).filter( "#addBatchSubmit" ).prop("disabled",true);
					$("#help-batchNumber").html(function(){
						return "<br> This batch alreay exists, please change the number"; 
					});
				}
				else{
					if(tipsStatus == "alert alert-error"){
						$("button").filter("#addBatchSubmit").prop("disabled", true);
					}
					else {
						$("button").filter("#addBatchSubmit").prop("disabled", false);
						$("#help-batchNumber").html(function () {
							return "";
						});
					}
				}
			}
		}
	});
});


/**
 * Get data paste on the first cell of the add data table
 */
$(document).bind("paste", '#col1', function(e){
	if (e.originalEvent.target.id=="col1"){
		var data = e.originalEvent.clipboardData.getData('Text');
		console.log("originalData");
		console.log(data);
		dispatchAddBatchDatas(data);
	}
	else {
		console.log(e.originalEvent.srcElement.id);
	}
});

/**
 * Functions of data insertion
 */
$(document).on("click", "#addBatchSubmit", function(e){
	batchInsert("addBatchTable");
});

$(document).on("click", "#clearModal", function(e){
	resetModalTable();
});

$(document).on("click", "#clearBatch", function(e){
	resetBatchTable();
});

$(document).on("click", "#editBatch", function(e){
	makeBatchEditable();
});

$(document).on("click", "#cancelBatch", function(e){
	makeBatchUnEditable();
});

$(document).on("click", "#saveBatch", function(e){
	batchEdition(currentBatchID, "batchTable");
	makeBatchUnEditable();
});


/**
 * Reset the table in the modal of batch creation
 *
 * @method     resetModalTable
 */
function resetModalTable(){
	var modal =''+
		'<thead>'+
			'<tr>'+
				'<th>#</th>'+
				'<th>1</th>'+
				'<th>2</th>'+
				'<th>3</th>'+
				'<th>4</th>'+
				'<th>5</th>'+
				'<th>6</th>'+
				'<th>7</th>'+
				'<th>8</th>'+
				'<th>9</th>'+
				'<th>10</th>'+
				'<th>11</th>'+
				'<th>12</th>'+
			'</tr>'+
		'</thead>'+
		'<tbody>'+
			'<tr>'+
			'<td>1</td>'+
				'<td><input class="form-control input-sm" type="text" id="col1" value="" placeholder="Paste all Data Here"></td>'+
				'<td><input class="form-control input-sm" type="text" id="2" value="" disabled></td>'+
				'<td><input class="form-control input-sm" type="text" id="3" value="" disabled></td>'+
				'<td><input class="form-control input-sm" type="text" id="4" value="" disabled></td>'+
				'<td><input class="form-control input-sm" type="text" id="5" value="" disabled></td>'+
				'<td><input class="form-control input-sm" type="text" id="6" value="" disabled></td>'+
				'<td><input class="form-control input-sm" type="text" id="7" value="" disabled></td>'+
				'<td><input class="form-control input-sm" type="text" id="8" value="" disabled></td>'+
				'<td><input class="form-control input-sm" type="text" id="9" value="" disabled></td>'+
				'<td><input class="form-control input-sm" type="text" id="10" value="" disabled></td>'+
				'<td><input class="form-control input-sm" type="text" id="11" value="" disabled></td>'+
				'<td><input class="form-control input-sm" type="text" id="12" value="" disabled></td>'+
			'</tr>'+
		'</tbody>';
	$('#addBatchTable').html(modal);
	$('#addBatchTips').html('<div id="batchOk" class="alert alert-success"> Please paste all your data in the first field of the table <a href="#" data-dismiss="alert" class="close">×</a></div>');
	$( "button" ).filter( "#addBatchSubmit" ).prop("disabled",true);

	$('#batchName').val("");
	$('#batchName').prop('disabled', true);
	$('#batchNumber').val("");
	$('#batchNumber').prop('disabled', true);
}

/**
 * Show a progress bar while loading
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
 * Hide loading progress bar
 *
 * @method     hideWaitModal
 */
function hideWaitModal(){
	$("#waitModal").modal("hide");
}


/**
 * create and show a modal window for batch creation
 *
 * @method     create_addBatch_modal
 */
    
function create_addBatch_modal() {
	var modal ='<div class="modal-dialog-addBatch">'+
		'<div class="modal-content">'+
			'<div class="modal-header">'+
	    		'<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>'+
	    		'<h4 class="modal-title">Create Batch</h4>'+
	  		'</div>'+
	  		'<div class="modal-body" style="overflow-y:auto;">'+
	  			'<span id="addBatchTips">'+
	    			'<div class="alert alert-success"> Please paste all your data in the first field of the table <a href="#" data-dismiss="alert" class="close">×</a></div>'+
	    		'</span>'+
				'<div class="row-fluid">'+
					'<table id="addBatchTable" class="table dt-right table_bordered dt-right">'+
						'<thead>'+
							'<tr>'+
								'<th>#</th>'+
								'<th>1</th>'+
								'<th>2</th>'+
								'<th>3</th>'+
								'<th>4</th>'+
								'<th>5</th>'+
								'<th>6</th>'+
								'<th>7</th>'+
								'<th>8</th>'+
								'<th>9</th>'+
								'<th>10</th>'+
								'<th>11</th>'+
								'<th>12</th>'+
							'</tr>'+
						'</thead>'+
						'<tbody>'+
							'<tr>'+
							'<td>1</td>'+
								'<td><input class="form-control input-sm" type="text" id="col1" value="" placeholder="Paste all Data Here"></td>'+
								'<td><input class="form-control input-sm" type="text" id="2" value="" disabled></td>'+
								'<td><input class="form-control input-sm" type="text" id="3" value="" disabled></td>'+
								'<td><input class="form-control input-sm" type="text" id="4" value="" disabled></td>'+
								'<td><input class="form-control input-sm" type="text" id="5" value="" disabled></td>'+
								'<td><input class="form-control input-sm" type="text" id="6" value="" disabled></td>'+
								'<td><input class="form-control input-sm" type="text" id="7" value="" disabled></td>'+
								'<td><input class="form-control input-sm" type="text" id="8" value="" disabled></td>'+
								'<td><input class="form-control input-sm" type="text" id="9" value="" disabled></td>'+
								'<td><input class="form-control input-sm" type="text" id="10" value="" disabled></td>'+
								'<td><input class="form-control input-sm" type="text" id="11" value="" disabled></td>'+
								'<td><input class="form-control input-sm" type="text" id="12" value="" disabled></td>'+
							'</tr>'+
						'</tbody>'+
					'</table>'+
				'</div>'+
				'<hr>'+
				'<div class="row-fluid">'+
					'<div class="col-lg-2">'+
						'<div class="input-group">'+
							'<input class="form-control name" type="text" placeholder="Batch Name" id="batchName" name="name" required="required" disabled/>'+
							'<span  style="color:red" id="help-batchName"></span>'+
						'</div><!-- /input-group -->'+
					'</div><!-- /.col-lg-2 -->'+
					'<div class="col-lg-2">'+
						'<div class="input-group">'+
							'<input class="form-control name" type="text" placeholder="Batch Number" id="batchNumber" name="batchNumber" required="required" disabled/>'+
							'<span  style="color:red" id="help-batchNumber"></span>'+
							'<span  style="color:green" id="info-batchNumber"></span>'+
						'</div><!-- /input-group -->'+
					'</div><!-- /.col-lg-2 -->'+
					'<div class="col-lg-1">'+
						'<div class="radio">'+
							'<label>'+
								'<input type="radio" name="layout" id="full" value="FULL" checked>'+
								'Full'+
							'</label>'+
						'</div>'+
					'</div><!-- /.col-lg-1 -->'+
					'<div class="col-lg-1">'+
						'<div class="radio">'+
							'<label>'+
								'<input type="radio" name="layout" id="split" value="SPLIT">'+
								'Split'+
							'</label>'+
							'<span  style="color:red" id="help-layout"></span>'+
							'<span  style="color:green" id="info-layout"></span>'+
						'</div>'+

					'</div><!-- /.col-lg-1 -->'+
						'<span  style="color:red" id="help-layout"></span>'+
					'</div><!-- /.col-lg-2 -->'+
				'</div><!-- /.row -->'+
				'<div class="modal-footer">'+
						'<button type="button" id="clearModal" class="btn btn-large">Clear</button>'+
						'<button type="button" data-dismiss="modal" class="btn btn-large">Close</button>'+
						'<button type="submit" id="addBatchSubmit" class="btn btn-primary btn-large" disabled>Submit</button>'+
				'</div>'+
			'</div> <!-- /.modal-content -->'+
		'</div> <!-- /.modal-dialog -->';

	$('#addBatchModal').empty();
	$('#addBatchModal').append(modal);
	$("#addBatchModal").modal("show");

}

/**
 * Insertion of a new batch in the database
 *
 * @method     batchInsert
 * @param      {<type>}  tableID  { description }
 */
function batchInsert(tableID){

	var table=document.getElementById(tableID);
	var boolOK = true;
	var rawdatas = [];
	var batchName = $('#batchName');
	var batchNumber = $('#batchNumber');
	var batchLayout = $("input[name='layout']:checked").val();
	//console.log("batchLayout : "+batchLayout);
	var tablelength = 0;

	if (batchLayout=="SPLIT"){
		tablelength=4;
	}
	else if (batchLayout=="FULL"){
		tablelength = 8;
	}

	/* Take data to make a 2D array */
	//console.log("tablelength = "+tablelength);
	for(var i=1; i<=tablelength; i++){
		for (var j=1; j <=12; j++){
			rawdatas.push(table.rows[i].cells[j].firstChild.value);
		}
	}
	//console.log(rawdatas);
	/* Controls on insert data */
    if((!batchName.val()) || ($.isNumeric(batchName.val()))) {
		$('#help-batchName').html("Please add a correct Name");
		boolOK = false;
    } else {
    	$('#help-batchName').html("");
    }

    if((!batchNumber.val()) || (! $.isNumeric(batchNumber.val()))) {
		$('#help-batchNumber').html("Please add a correct Number");
		boolOK = false;
    } else {
    	$('#help-batchNumber').html("");
    }


	/* Data insertion */
	if(boolOK) {
		$( "button" ).filter( "#addBatchSubmit" ).prop("disabled",true);
		$.ajax({
			url: "insert_batch.php",
			type: "post",
			data: { rawdatas : rawdatas, batchName : batchName.val(), batchLayout : batchLayout, expID : expID, batchNumber : batchNumber.val() },
			dataType : 'text',
			beforeSend: function(){
				showWaitModal();
			},
			success: function(data) {
				var obj = JSON.parse(data);
				if(obj.status == 'success'){
					$("#addBatchModal").modal("hide");
					$('#statusSpan').html('<div id="batchOk" class="alert alert-success">'+obj.action+' Successful<a href="#" data-dismiss="alert" class="close">×</a></div>');
				}
				else if(obj.status == 'error'){
					$('#addBatchTips').html('<div id="batchOk" class="alert alert-error">'+obj.action+' Failure<a href="#" data-dismiss="alert" class="close">×</a></div>');
				}
			},
			error: function(xhr, status, error) {
				$('addBatchTips').html('<div id="batchOk" class="alert alert-error">Insertion Error : '+xhr.responseText+error+'<a href="#" data-dismiss="alert" class="close">×</a></div>');
			},
			complete: function(){
				hideWaitModal();
			}
		});
	}

}

/**
 * send batch data from batchtable to insert_batch.php
 *
 * @method     batchEdition
 * @param      {<type>}  batchID  { description }
 * @param      {<type>}  tableID  { description }
 */
function batchEdition(batchID, tableID){
	var table=document.getElementById(tableID);
	var rawdatas = [];
	/* Take data to make a 2D array */
	for(var i=1; i<table.rows.length;i++){
		for (var j=1; j <=12; j++){
			rawdatas.push(table.rows[i].cells[j].firstChild.value);
		}
	}
	/* Insert batch data */
	$.ajax({
		url: "insert_batch.php",
		type: "post",
		data: { rawdatas : rawdatas, batchID : batchID },
		dataType : 'text',
		beforeSend: function(){
			showWaitModal();
		},
		success: function(data) {
			var obj = JSON.parse(data);
			if(obj.status == 'success'){
				$('#statusSpan').html('<div class="alert alert-success">'+obj.action+' Successful<a href="#" data-dismiss="alert" class="close">×</a></div>');
			}
			else if(obj.status == 'error'){
				$('#statusSpan').html('<div class="alert alert-error">'+obj.action+' Failure<a href="#" data-dismiss="alert" class="close">×</a></div>');
			}
		},
		error: function(xhr, status, error) {
			$('statusSpan').html('<div class="alert alert-error">Insertion Error : '+xhr.responseText+error+'<a href="#" data-dismiss="alert" class="close">×</a></div>');
			alert(data);
		},
		complete: function(){
			hideWaitModal();
		}
	});
}

/**
 * reset datatable 12*8 when click on CLEAR all
 *
 * @method     resetBatchTable
 */
function resetBatchTable(){
	for(var i=0;i<8; i++){
		for(var j=0;j<12; j++){
			$('#batchTable').find('tr').eq(i+1).find('td').eq(j+1).text("").css('background-color', '');
			$("#batchTable").css("fontSize", 11);
		}
	}

}

/**
 * Allow to edit a batch
 *
 * @method     makeBatchEditable
 */
function makeBatchEditable(){
	var table = $('#batchTable').DataTable();
	for(var i=0;i<8; i++){
		for(var j=1;j<=12; j++){
			table.cell(i, j).data('<input class="form-control input-sm" type="text" id="cell'+i+':'+j+'" value="'+table.cell(i, j).data()+'">').draw();
		}
	}

	$( "button" ).filter( "#clearBatch" ).prop("disabled",true);
	$( "button" ).filter( "#editBatch" ).prop("disabled",true);
	$( "button" ).filter( "#cancelBatch" ).prop("disabled",false);
	$( "button" ).filter( "#saveBatch" ).prop("disabled",false);
	$( "button" ).filter( "#expender" ).prop("disabled",true);
}

/**
 * Remove inputs added for batch edition
 *
 * @method     makeBatchUnEditable
 */
function makeBatchUnEditable(){
	var table = $('#batchTable').DataTable();
	for(var i=0;i<8; i++){
		for(var j=1;j<=12; j++){
			var valij = $("input[id='cell"+i+":"+j+"']").val();
			table.cell(i, j).data(valij);
		}
	}

	$( "button" ).filter( "#clearBatch" ).prop("disabled",false);
	$( "button" ).filter( "#editBatch" ).prop("disabled",false);
	$( "button" ).filter( "#cancelBatch" ).prop("disabled",true);
	$( "button" ).filter( "#saveBatch" ).prop("disabled",true);
	$( "button" ).filter( "#expender" ).prop("disabled",false);
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
		select :	"single",
		paging : 	true,
		stateSave: true,
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
			if ( data[2] == "" ) {
				$(row).css('background-color', '#FDFEFE');
			}
			else{
				$(row).css('background-color', '#D5F5E3');
			}
		}
	});
}


/**
 * display data on row tables when past.
 *
 * @method     dispatchAddRowsDatas
 * @param      {(number|string)}  data    { description }
 */
function dispatchAddBatchDatas(data) {
	data = data.replace(/ +/g, '');
	data = chomp(data);
	data = data.replace(/(\r\n|\n|\r)/gm,"\n");
	var rowsDatas = data.split(/(?:\n)+/);

	var dataLength = rowsDatas.length;
	var newRowContent = "";
	var hashMap = new Object();
	var boolOK = true;
	var hashSeekDoublons = {};

	if (dataLength == 4) {
		$('#addBatchTips').html('<div id="batchOk" class="alert alert-success"> Batch Map process <a href="#" data-dismiss="alert" class="close">×</a></div>');
	}
	else if (dataLength == 8) {
		$('#addBatchTips').html('<div id="batchOk" class="alert alert-success"> Batch Map process <a href="#" data-dismiss="alert" class="close">×</a></div>');
	}
	else {
		$('#addBatchTips').html('<div id="batchOk" class="alert alert-error"> Batch Size is not correct <a href="#" data-dismiss="alert" class="close">×</a></div>');
		boolOK = false;
	}

	for (var i = 0; i < dataLength; i++) {
		var rowDatas = rowsDatas[i].split(/(?:\t)+/);
		for (var j = 0; j < rowDatas.length; j++) {
			var name = rowDatas[j];
			hashMap[name.split('-')[0]] = 0;
			hashSeekDoublons[name] = 0;

		}
	}
	for (var i = 0; i < dataLength; i++) {
		var rowDatas = rowsDatas[i].split(/(?:\t)+/);
		for (var j = 0; j < rowDatas.length; j++) {
			var name = rowDatas[j];
			hashMap[name.split('-')[0]]++;
			hashSeekDoublons[name] +=1;
		}
	}

	delete hashMap["EB"];
	delete hashMap["?"];
	delete hashMap[""];
	delete hashSeekDoublons["EB"];
	delete hashSeekDoublons["?"];
	delete hashSeekDoublons[""];


	var min = 96;
	var minname = "";
	/* Find the standard name (minname), it's based on the fact that standards are less present than data of interest*/
	// console.log("hashmap length : "+Object.keys(hashMap).length);
	if (Object.keys(hashMap).length > 2) {
		$('#addBatchTips').html('<div id="batchOk" class="alert alert-error"> Only one control is allowed <a href="#" data-dismiss="alert" class="close">×</a></div>');
		boolOK = false;
	} else {
		for (var key in hashMap) {
			if (hashMap[key] < min) {
				min = hashMap[key];
				minname = key;
			}
		}
	}

	for (var key in hashSeekDoublons) {
		if (hashSeekDoublons[key] > 1) {
			boolOK = false;
			$('#addBatchTips').html('<div id="batchOk" class="alert alert-error"> You have entered identicals freshweights '+key+' <a href="#" data-dismiss="alert" class="close">×</a></div>');
		}
	}

	checkUse(rowsDatas).then(function (checkUseResponse) {
		console.log(checkUseResponse);
		for (var i = 0; i < dataLength; i++) {
			var rowDatas = rowsDatas[i].split(/(?:\t)+/);
			$('input:radio[name=layout]:nth(0)').attr('checked', true);
			$('input:radio[name=layout]').attr('disabled', true);
			if (rowDatas.length < 12) {
				$('#addBatchTips').html('<div id="batchOk" class="alert alert-error"> Row ' + (i + 1) + ' is not correct <a href="#" data-dismiss="alert" class="close">×</a></div>');
				boolOK = false;
			}
			newRowContent += '' +
				'<tr>' +
				'<td>' + (i + 1) + '</td>';
			for (var j = 0; j < rowDatas.length; j++) {
				var name = chomp(rowDatas[j]);
				console.log(name + " : " + checkUseResponse[i][j]);
				console.log(name + " : " + hashSeekDoublons[name]);
				if ((checkUseResponse[i][j] != "free") || (hashSeekDoublons[name] >1)) {
					newRowContent += '<td><input style="background-color :#EB9018" class="form-control input-sm" type="text" id="1" value="' + name + '" ></td>';
					boolOK = false;
					$('#addBatchTips').html('<div id="batchOk" class="alert alert-error"> Some freshweights are already used or not created <a href="#" data-dismiss="alert" class="close">×</a></div>');
				}
				else {
					if (name === "EB") {
//							console.log("blanc");
						newRowContent += '<td><input style="background-color :#BDE5F8" class="form-control input-sm" type="text" id="1" value="' + name + '" ></td>';
					}
					else if (name === "?") {
//							console.log("?");
						newRowContent += '<td><input style="background-color :#686868" class="form-control input-sm" type="text" id="1" value="' + name + '" ></td>';
					}
					else if (name.split('-')[0] === minname) {
//							console.log("minname => standard");
						newRowContent += '<td><input style="background-color :#B7FFAF" class="form-control input-sm" type="text" id="1" value="' + name + '" ></td>';
					}
					else if (name == "") {
//							console.log("void");
						newRowContent += '<td><input style="background-color :#686868" class="form-control input-sm" type="text" id="1" value="?" ></td>';
					}
					else {
						newRowContent += '<td><input  class="form-control input-sm" type="text" id="1" value="' + name + '" ></td>';
					}
				}
			}
			newRowContent += '</tr>';
		}
		if (dataLength == 4) {
			for (var i = 4; i < dataLength + 4; i++) {
				var rowDatas = rowsDatas[i - 4].split(/(?:\t)+/);
				$('input:radio[name=layout]:nth(1)').attr('checked', true);
				$('input:radio[name=layout]').attr('disabled', true);
				if (rowDatas.length < 12) {
					$('#addBatchTips').html('<div id="batchOk" class="alert alert-error"> Row ' + i + ' is not correct <a href="#" data-dismiss="alert" class="close">×</a></div>');
					//return;
					boolOK = false;
				}
				newRowContent += '' +
					'<tr>' +
					'<td>' + (i + 1) + '</td>';
				for (var j = 0; j < rowDatas.length; j++) {
					var name = chomp(rowDatas[j]);
					if (name === "EB") {
						newRowContent += '<td bgcolor="#A4A4A4"><input style="background-color :#BDE5F8" class="form-control input-sm" type="text" id="1" value="' + name + '" ></td>';
					}
					else if (name === "?") {
						newRowContent += '<td bgcolor="#A4A4A4"><input style="background-color :#686868" class="form-control input-sm" type="text" id="1" value="' + name + '" ></td>';
					}
					else if (name.split('-')[0] === minname) {
						newRowContent += '<td bgcolor="#A4A4A4"><input style="background-color :#B7FFAF" class="form-control input-sm" type="text" id="1" value="' + name + '" ></td>';
					}
					else if (name == "") {
						// console.log("void");
						newRowContent += '<td><input style="background-color :#686868" class="form-control input-sm" type="text" id="1" value="?" ></td>';
					}
					else {
						newRowContent += '<td bgcolor="#A4A4A4"><input  class="form-control input-sm" type="text" id="1" value="' + name + '" ></td>';
					}
				}
				newRowContent += '</tr>';
			}
		}
		$("#addBatchTable tbody").html(newRowContent);
		$("#addBatchTable").css("fontSize", 11);
		$("#batchName").prop('disabled', false);

	});
}

/**
 * Check if a freshweight is already used
 *
 * @method     checkUse
 * @param      {<type>}   line    { description }
 * @return     {boolean}  { description_of_the_return_value }
 */
function checkUse(data){
	console.log("data");
	console.log(data);
	return new Promise(function (resolve, reject) {
		$.ajax({
			url: "batch_is_used.php",
			type: "post",
			data: {data: data},
			beforeSend: function(){
				showWaitModal();
			},
			success: function (data) {
				var obj = JSON.parse(data);
				console.log("RESULT OF IS USED 2");
				console.log(data);
				console.log(obj);
				resolve(obj.result);
			},
			error: function (xhr, status, error) {
				reject(Error("error in checkUse"));
			},
			complete: function(){
				hideWaitModal();
			}
		});
	});
}


/**
 * Display batches values, add colors
 *
 * @method     displayVals
 */
function displayVals() {
	var batchID = $( "#selectBatch" ).val();
	currentBatchID = batchID;
	$.ajax({
        url: "batches_get_rawData.php",
        type: "post",
        data: { batchID : batchID },
		success: function(data) {
			for(var i=0;i<8; i++){
				for(var j=0;j<12; j++){
					$('#batchTable').find('tr').eq(i+1).find('td').eq(j+1).text("").css('background-color', '');
				}
			}
			var hashMap = new Object();
			for(var i=0;i<data.length; i++){
				hashMap[data[i].split('-')[1]]=0;
			}
			for(var i=0;i<data.length; i++){
				hashMap[data[i].split('-')[1]]++;
			}
			delete hashMap["EB"];
			delete hashMap["?"];
			var min=96;
			var minname="";
			for (var key in hashMap) {
				if (hashMap[key]<min){
					min = hashMap[key];
					minname=key;
				}
			}
			$.ajax({
		        url: "batches_get_batch_layout.php",
		        type: "post",
		        data: { batchID : batchID },
				success: function(layout) {
					//console.log(layout);
					for(var i=0;i<data.length; i++){
						var name = data[i].split('-')[1];
						var sample = data[i].split('-')[2];
						var aliquot = data[i].split('-')[3];
						var row = parseInt(data[i].split('-')[4]);
						var col = parseInt(data[i].split('-')[5]);
						$('#table-wrapper').show();
						$('#expender-wrapper').show();
						$('#datatable-wrapper').hide();
						if(name === "EB"){
					    	$('#batchTable').find('tr').eq(row+1).find('td').eq(col+1).text(name).css('background-color', '#BDE5F8');
						}
						else if(name === "?"){
					    	$('#batchTable').find('tr').eq(row+1).find('td').eq(col+1).text(name).css('background-color', '#686868');
						}
						else if(name === minname){
							$('#batchTable').find('tr').eq(row+1).find('td').eq(col+1).text(name+"-"+sample+"-"+aliquot).css('background-color', '#B7FFAF');
						}
						else {
							$('#batchTable').find('tr').eq(row+1).find('td').eq(col+1).text(name+"-"+sample+"-"+aliquot);
						}
						$("#batchTable").css("fontSize", 11);
					}
					if(layout=="SPLIT"){
						for(var i=0;i<data.length; i++){
							var name = data[i].split('-')[1];
							var sample = data[i].split('-')[2];
							var aliquot = data[i].split('-')[3];
							var row = parseInt(data[i].split('-')[4]);
							var col = parseInt(data[i].split('-')[5]);
							if(name === "EB"){
								$('#batchTable').find('tr').eq(row+5).find('td').eq(col+1).text(name).css('background-color', '#A0CFDF');
							}
							else if(name === "?"){
								$('#batchTable').find('tr').eq(row+5).find('td').eq(col+1).text(name).css('background-color', '#686868');
							}
							else if(name === minname){
								$('#batchTable').find('tr').eq(row+5).find('td').eq(col+1).text(name+"-"+sample+"-"+aliquot).css('background-color', '#B4D3B0');
							}
							else {
								$('#batchTable').find('tr').eq(row+5).find('td').eq(col+1).text(name+"-"+sample+"-"+aliquot).css('background-color', '#D7D7D7');
							}
						}
					}
					$('#batchTable').dataTable().fnDestroy();
					$('#batchTable').DataTable({
						responsive:true,
						stateSave: true,
						dom: 'TB<"clear">',
						buttons: [
							'copy', 'csv', 'excel', 'print'
						]
					});
				}
			});
		}
	});
}


/**
 * Remove useless strings (retour chariot a la fin de ligne, equivalent de chomp en perl)
 *
 * @method     chomp
 * @param      {string}  raw_text  { description }
 * @return     {string}  { description_of_the_return_value }
 */
function chomp(raw_text){
	return raw_text.replace(/(\n|\r)+$/, '').trim();

}

</script>
