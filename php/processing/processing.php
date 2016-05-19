<?php
require('../functions/check_login.php');
include '../functions/html_functions.php';
include '../functions/php_functions.php';

html_header("../../", $_SESSION['login']);

generic_html_top_page("../../","Raw Data Processing");
echo'
<span id="statusSpan">
</span>';

echo'
<!-- Modal -->
<div class="modal fade" id="addRawDataModal" tabindex="-1" role="dialog" aria-hidden="true">
</div>

<div class="modal fade" id="waitModal" tabindex="1" role="dialog" aria-hidden="true">
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
					<table id="expTable" class="table display table-striped table-bordered table-hover" style="width:100%">
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
		<div id="head-wrapper">
			<h4>Analytes</h4>
			<div id="ezTable">
			</div>
		</div>
	</div>';

	echo '
	<div id="table-wrapper">
		<div class="row-fluid">
			<div class="span12">
				<div class="widget-box" style="background : transparent">
					<div class="widget-content nopadding">
						<div class="container-fluid">
							<div class="responsive-table-line">
								<h4>Raw Data </h4>
								<p></p>
									<table id="rawTable" class="table table-bordered table-condensed table-body-center table-striped" style="width:100%">
										<thead>
											<tr>
												<th>#</th>
												<th>1</th>
												<th>2</th>
												<th>3</th>
												<th>4</th>
												<th>5</th>
												<th>6</th>
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
												<td data-title="Droit">1</td>
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
												<td data-title="Droit">2</td>
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
												<td data-title="Droit">3</td>
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
												<td data-title="Droit">4</td>
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
												<td data-title="Droit">5</td>
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
												<td data-title="Droit">6</td>
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
												<td data-title="Droit">7</td>
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
												<td data-title="Droit">8</td>
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

			<div class="row-fluid">
				<div class="span12">
					<div class="widget-box" style="background : transparent">
						<div class="widget-content nopadding">
							<div class="container-fluid">
								<div class="responsive-table-line">
									<div class="row>
										<div class="col-lg-6"> <h4>Processed Data </h4></div> 
										<div class="col-lg-6" id="errorsInCtrl"></div>
									</div>
									<table id="processTable" class="table table-bordered table-condensed table-body-center table-striped" style="width:100%">
										<thead>
											<tr>
												<th>#</th>
												<th>1</th>
												<th>2</th>
												<th>3</th>
												<th>4</th>
												<th>5</th>
												<th>6</th>
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
												<td data-title="Droit">1</td>
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
												<td data-title="Droit">2</td>
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
												<td data-title="Droit">3</td>
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
												<td data-title="Droit">4</td>
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
												<td data-title="Droit">5</td>
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
												<td data-title="Droit">6</td>
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
												<td data-title="Droit">7</td>
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
												<td data-title="Droit">8</td>
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
			<div class="row-fluid">
				<div class="btn-toolbar" role="toolbar" aria-label="">
					<div class="btn-group" role="group" aria-label="">
						<button type="button" class="btn btn-success btn-lg" id ="saveProcessedData" disabled><i class="icon-ok"></i> Save </button>
					</div>
					<div id="insertProgressDiv" class="progress progress-striped active" style="display: none;">
						<div class="bar" style="width: 100%;"></div>
					</div>
				</div>
			</div>
		</div>
	</div>
';

html_footer("../../");
?>
<script type="text/javascript" class="init">

	/* Globals vars */
	var expID="";
	var expName="";
	var batchName="";
	var excludMap = new Array(12);
	var rawDataMap = new Array(12);
	var processDataMap = new Array(12);
	var dataTypeMap = new Array(12);
	var fwMap = new Array(12);
	var split="";
	var activity=-1;
	var ezMap = new Array();
	var rawIDMap =  new Array();
	var currentEzID=-1;

	/* Create 2D arrays */
	for (var i = 0; i < 10; i++) {
		excludMap[i] = new Array(8);
		rawDataMap[i] = new Array(8);
		dataTypeMap[i] = new Array(8);
		processDataMap[i] = new Array(8);
		fwMap[i]= new Array(8);
		rawIDMap[i] = new Array(8);
	}

$(document).ready(function() {
	$('#userPanel').removeClass('submenu');
	$('#userPanel').addClass('submenu open');
	$('#table-wrapper').hide();
	$('#head-wrapper').hide();
	$('#select-wrapper').hide();
	$('#expender-wrapper').hide();

	setup_experiment_datatable();

	/* Click listener on experiment table */
	$('#expTable').on('click', 'tr', function () {
		expID = $('td', this).eq(0).text();
		expName = $('td', this).eq(1).text();
		$.ajax({
			url: "processing_get_single_batch.php",
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

	/* If the user dblclick on a raw value, the processed data are re-calculated  */
	$('#rawTable tbody').on( 'dblclick', 'td', function () {
		myRowIndex = $(this).parent().find('td').html().trim();
		myColIndex = $('#rawTable thead tr th').eq($(this).index()).html().trim();
		row=myRowIndex-1;
		col=myColIndex-1;
		//console.log("Row: " + (row) + "\nColumn: " + (col));
		var exclud = (excludMap[row][col]==0) ? 1 : 0;
		//console.log(exclud);
		excludMap[row][col] = exclud;
		//console.log(excludMap[row][col]);
		displayRawData(rawDataMap, dataTypeMap, excludMap, split);
		//console.log(split);
		if (split == "FULL"){
			processDataMap=processMetabolites(rawDataMap, dataTypeMap, excludMap, split, processDataMap, activity, fwMap);
		}
		else if (split == "SPLIT"){
			processDataMap= processEnzyme(rawDataMap, dataTypeMap, excludMap, split, processDataMap, activity, fwMap);
		}
		displayProcessData(processDataMap, dataTypeMap, excludMap, split);
		$( "button" ).filter( "#saveProcessedData" ).prop("disabled",false);
	});



	/* Listener on batch selection */
	$( "#selectBatch" ).click(function() {
		batchID = $( "#selectBatch" ).val();
		batchName = $("#selectBatch option:selected").text();
		makeMap(-1);
		if (split == "FULL"){
			processDataMap=processMetabolites(rawDataMap, dataTypeMap, excludMap, split, processDataMap, activity, fwMap);
		}
		else if (split == "SPLIT"){
			processDataMap= processEnzyme(rawDataMap, dataTypeMap, excludMap, split, processDataMap, activity, fwMap);
		}
		displayProcessData(processDataMap, dataTypeMap, excludMap, split);
		displayRawData(rawDataMap, dataTypeMap, excludMap, split);
	});

	/* Listener on the experiment table expander */
	$( "#expender" ).click(function() {
		$('#table-wrapper').hide();
		$('#head-wrapper').hide();
		$('#expender-wrapper').hide();
		$('#datatable-wrapper').show();
	});

	$( "#addBatch" ).click(function() {
		$('#addRawDataModal').text("for "+ expName);
		$('#batchName').attr("placeholder",expName);
	});

}); 
// END OF DOCUMENT READY EVENTS

//						//
//		Listeners		//
//						// 
$(document).on("click", "#addDataButton", function(e){
	batchID = $( "#selectBatch" ).val();
	create_addRawData_modal(batchID);
});


	/* Listener on enzyme table */
$(document).on( 'click', "#ezTable > tbody > tr >td ", function (e) {
	if ($(this).index() != Object.keys(ezMap).length) {
		myColIndex = $('#ezTable td').eq($(this).index()).html();
		currentEzID = ezMap[myColIndex];
		// makeMap(ezMap[myColIndex]);
		makeMap(currentEzID);
		//console.log(split);
		if (split == "FULL"){
			processDataMap=processMetabolites(rawDataMap, dataTypeMap, excludMap, split, processDataMap, activity, fwMap);
		}
		else if (split == "SPLIT"){
			processDataMap= processEnzyme(rawDataMap, dataTypeMap, excludMap, split, processDataMap, activity, fwMap);
		}
		displayProcessData(processDataMap, dataTypeMap, excludMap, split);
		displayRawData(rawDataMap, dataTypeMap, excludMap, split);
	}
});



/* When data are pasted in the first cell of the modal */
$(document).bind("paste", "#col1", function(e){
	var data = e.originalEvent.clipboardData.getData('Text');
	data=chomp(data);
	dispatchAddBatchDatas(data, dataTypeMap, excludMap);
});

$(document).on("click", "#rawDataSubmit", function(e){
	console.log("INSERT DATA");
	dataInsert("addrawDataValue", expID, batchID);
	console.log("Make Map");
	makeMap(currentEzID);
	console.log("displayRawData");
	displayRawData(rawDataMap, dataTypeMap, excludMap, split);
	console.log("displayProcessData");
	displayProcessData(processDataMap, dataTypeMap, excludMap, split);
	console.log("update");
	update(currentEzID, batchID, processDataMap, excludMap)
	$("#addBatchModal").modal("hide");
});

$(document).on("click", "#clearRawData", function(e){
	resetTable("rawTable");
});

$(document).on("click", "#clearProcessedData", function(e){
	resetTable("processTable");
});

$(document).on("click", "#saveProcessedData", function(e){
	update(currentEzID, batchID, processDataMap , excludMap);
	$( "button" ).filter( "#saveProcessedData" ).prop("disabled",true);

});

/**
 * Show a progress bar
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
 * Hide a progress bar
 *
 * @method     hideWaitModal
 */
function hideWaitModal(){
	$("#waitModal").modal("hide");
}

/**
 * Process data update (click on save under the process data table)
 *
 * @method     update
 * @param      {<type>}  ezID            { description }
 * @param      {<type>}  batchID         { description }
 * @param      {<type>}  processDataMap  { description }
 * @param      {<type>}  excludMap       { description }
 */
function update(ezID, batchID, processDataMap, excludMap){
	console.log("ezID Update : "+ezID);
	console.log("BatchID update : "+batchID);
	console.log("update processDataMap");
	console.log(processDataMap);
	console.log("update excludMap");
	console.log(excludMap);
	console.log("update rawIDMap");
	console.log(rawIDMap);
	showWaitModal();
	$.ajax({
		url: "save_edit.php",
		type: "post",
		data: { procdatas : processDataMap, excludMap : excludMap, batchID : batchID, ezID : ezID, rawIDMap : rawIDMap },
		dataType : 'text',
		success: function(data) {
			var obj = JSON.parse(data);
			if(obj.status == 'success'){
				$('#statusSpan').html('<div class="alert alert-success">'+obj.action+' Successful<a href="#" data-dismiss="alert" class="close">×</a></div>');
				hideWaitModal();
			}
			else if(obj.status == 'error'){
				$('#statusSpan').html('<div class="alert alert-error">'+obj.action+' Failure<a href="#" data-dismiss="alert" class="close">×</a></div>');
				hideWaitModal();
			}
		},
		error: function(xhr, status, error) {
			hideWaitModal();
			$('statusSpan').html('<div class="alert alert-error">Insertion Error : '+xhr.responseText+error+'<a href="#" data-dismiss="alert" class="close">×</a></div>');
			alert(data);
		}
	});
}

/**
 * Reset a table
 *
 * @method     resetTable
 * @param      {string}  tableID  { description }
 */
function resetTable(tableID){
	var table=document.getElementById(tableID);
	for(var i=0;i<8; i++){
		for(var j=0;j<12; j++){
			$('#'+tableID).find('tr').eq(i+1).find('td').eq(j+1).text("").css('background-color', '');
			$('#'+tableID).css("fontSize", 11);
		}
	}
}

/**
 * Insert new raw values
 *
 * @method     dataInsert
 * @param      {<type>}  tableID  { description }
 * @param      {<type>}  expID    { description }
 * @param      {<type>}  batchID  { description }
 */
function dataInsert(tableID, expID, batchID){
	//var rawdata = $('#addrawDataValue').val();
	//var rawdatas = rawdata.split(/(?:\t|\n)+/);
	var ezSelected = $('#selectAnalyte option:selected');
	var table=document.getElementById(tableID);
	var boolOK = true;
	var rawdatas = [];
	var ezID="";

	if(! ezSelected.val()) {
		$('#help-selectAnalyte').html("Please choose an analyte");
		boolOK = false;
    } else {
    	$('#help-selectAnalyte').html("");
    	ezID = ezSelected.val();
    	currentEzID=ezID
    }
	//prend les données de la table pour les mettre dans un tableau a deux dimensions
	showWaitModal();
	for(var i=1; i<table.rows.length;i++){
		for (var j=1; j <=12; j++){
			//console.log(table.rows[i].cells[j].firstChild.value);
			var value = table.rows[i].cells[j].firstChild.value.replace(/,/g, '.');
			rawdatas.push(value);
		}
	}
	//console.log(rawdatas);
	if(boolOK) {
		$.ajax({
			url: "insert_raw_data.php",
			type: "post",
			data: { rawdatas : rawdatas, expID : expID, batchID : batchID, ezID : ezID },
			dataType : 'text',
			async : true,
			success: function(data) {
				var obj = jQuery.parseJSON(data);
				if(obj.status == 'success'){
					$('#statusSpan').html('<div class="alert alert-success">Insertion Successful<a href="#" data-dismiss="alert" class="close">×</a></div>');
					hideWaitModal();
					$('#addRawDataModal').empty();
					$("#addRawDataModal").modal("hide");
				}
				else if( obj.status == 'error' ){
					hideWaitModal();
					$('#addDataTips').html('<div class="alert alert-error">'+obj.message+'<a href="#" data-dismiss="alert" class="close">×</a></div>');
				}
			},
			error: function(xhr, status, error) {
			}
		});
	}
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
		dom : 		'TB<"clear">frtip',
		ajax : 		'get_all_experiment.php',
		buttons: [
			'copy', 'csv', 'excel', 'pdf', 'print'
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
 * chomp a string
 *
 * @method     chomp
 * @param      {string}  raw_text  { description }
 * @return     {string}  { description_of_the_return_value }
 */
function chomp(raw_text){
	return raw_text.replace(/(\n|\r)+$/, '');
}

/**
 * display data on row tables when past.
 *
 * @method     dispatchAddRowsDatas
 * @param      {(number|string)}  data    { description }
 */
function dispatchAddBatchDatas(data, dataTypeMap, excludMap) {
	var rowsDatas = data.split(/(?:\n)+/);
	var dataLength = rowsDatas.length;
	var newRowContent="";
	var hashMap = new Object();
	if (dataLength == 8) {
		$('#addDataTips').html('<div class="alert alert-success"> Data Map processed <a href="#" data-dismiss="alert" class="close">×</a></div>');
	}
	else {
		$('#addDataTips').html('<div class="alert alert-error"> Size is not correct <a href="#" data-dismiss="alert" class="close">×</a></div>');
		return;
	}

	for (var i=0; i < dataLength; i++) {
		var rowDatas = rowsDatas[i].split(/(?:\t)+/);
		if (rowDatas.length < 12) {
			$('#addDataTips').html('<div class="alert alert-error"> Row '+i+' is not correct <a href="#" data-dismiss="alert" class="close">×</a></div>');
			 return;
		}
		newRowContent += ''+
		'<tr>'+
			'<td>'+ (i+1) +'</td>';
			for (var j=0; j < rowDatas.length; j++){
				var name=rowDatas[j];
				if(dataTypeMap[i][j] == 1){
					newRowContent += '<td><input style="background-color :#BDE5F8" class="form-control input-sm" type="text" id="1" value="'+name+'" ></td>';
				}
				else if(dataTypeMap[i][j] == 2){
					newRowContent += '<td><input style="background-color :#686868" class="form-control input-sm" type="text" id="1" value="'+name+'" ></td>';
				}
				else if(dataTypeMap[i][j] == 3){
					newRowContent += '<td><input style="background-color :#B7FFAF" class="form-control input-sm" type="text" id="1" value="'+name+'" ></td>';
				}
				else {
					newRowContent += '<td><input  class="form-control input-sm" type="text" id="1" value="'+name+'" ></td>';
				}
				if(excludMap[i][j] == 1){
					$('#rawTable').find('tr').eq(i+1).find('td').eq(j+1).css('text-decoration', 'line-through');
				}
			}
		newRowContent += '</tr>';
	} 
	$("#addrawDataValue tbody").html(newRowContent);
	$("#addrawDataValue").css("fontSize", 11);
}

function createSelectAnalyte(){
	var modal ="";
	$.ajax({
		async: false,
		url: "get_declared_enzymes_for_one_batch.php",
		type: "post",
		data: { 
			batchID : batchID
		},
		success: function(data) {
			console.log(data);
			for(var i = 0; i < data.length; i++) {
				var line = data[i];
					modal += '<option value="'+line.ez_id+'">'+line.ez_analyte+"&nbsp;&nbsp;"+line.ez_code+'</option>';
			}
		},
		error: function(xhr, status, error) {
		}
	});
	return modal;
}


/**
 * create modal for rawdata adding
 *
 * @method     create_addRawData_modal
 * @param      {<type>}  batchID  { description }
 */
function create_addRawData_modal(batchID){
	var modal =''+
	'<div class="modal-dialog-addBatch">'+
		'<div class="modal-content">'+
			'<div class="modal-header">'+
				'<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>'+
				'<h4 class="modal-title">Add Raw Data <span id=modalBatchName></span></h4>'+
			'</div>'+
			'<div class="modal-body">'+
				'<span id="addDataTips">'+
					'<div class="alert alert-success"> Please paste all your data in the first field of the table <a href="#" data-dismiss="alert" class="close">×</a></div>'+
				'</span>'+
				'<div class="row-fluid">'+
					'<div class="col-lg-3">'+
						'<label class="control-label" for="selectAnalyte">Select Analyte</label>'+
						'<select id="selectAnalyte" class="form-control" name="selectAnalyte">';
						modal += createSelectAnalyte();
						modal += '</select>'+
						'<span  style="color:red" id="help-selectAnalyte"></span>'+
					'</div><!-- /.col-lg-5 -->'+
				'</div> <!--/.row fluid-->'+
				'<div class="row-fluid">'+
					'<hr>'+
					'<table id="addrawDataValue" class="table dt-right table_bordered dt-right">'+
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
			'</div> <!-- /.modal-body -->'+
			'<div class="modal-footer">'+
				'<button type="button" data-dismiss="modal" class="btn btn-large">Close</button>'+
				'<button type="button" id="rawDataSubmit"  class="btn btn-primary btn-large">Submit</button>'+
			'</div>'+
		'</div> <!-- /.modal-content -->'+
	'</div> <!-- /.modal-dialog -->';

	$('#addRawDataModal').empty();
	$('#addRawDataModal').append(modal);
	$("#addRawDataModal").modal("show");
}

/**
 * make the map of the raw data
 *
 * @method     makeMap
 * @param      {(number|string)}  enzymeID  { description }
 */
function makeMap(enzymeID) {
	console.log("enzymeID : "+enzymeID);
	var ezID=0;
	var ezNameMap=[];
	$.ajax({
        url: "processing_get_enzymes.php",
        type: "post",
        data: { batchID : batchID},
		success: function(data) {
			console.log(data);
			if ((data.length == 0) || (typeof data == "undefined")){
				console.log("table vide");
				var content = '<table id="ezTable" class="table table-bordered table-condensed table-body-center table-striped" style="width:100%"><tr>';
				content += '<td style="background-color:transparent" align="center">';
				content += '<button type="button" id ="addDataButton" class="btn btn-sm btn-info"><i class="icon-plus"></i> Add Data</button>';
				content += '</td>';
				content += "</tr></table>";
				$('#ezTable').html(content);
				$("#ezTable").css("fontSize", 11);
				$('#head-wrapper').show();
				$('#expender-wrapper').show();
				$('#datatable-wrapper').hide();
			}
			else {
				var content = '<table id="ezTable" class="table table-bordered table-condensed table-body-center table-striped" style="width:100%"><tr>';
				for(i=0; i<data.length; i++){
					if(enzymeID != -1){
						if(data[i].split('|||')[1]==enzymeID){
							content += '<td style="background-color:green">' + data[i].split('|||')[3] + '</td>';
						}
						else {
							content += '<td>' + data[i].split('|||')[3] + '</td>';
						}
					}
					else{
						content += '<td>' + data[i].split('|||')[3] + '</td>';
					}
				    ezMap[data[i].split('|||')[3]]=data[i].split('|||')[1];
				    ezNameMap[data[i].split('|||')[1]]=data[i].split('|||')[2];
				}
				console.log(ezMap);

				content += '<td style="background-color:transparent" align="center">';
				content += '<button type="button" id ="addDataButton" class="btn btn-sm btn-info"><i class="icon-plus"></i> Add Data</button>';
				content += '</td>';

				content += "</tr></table>";
				$('#ezTable').html(content);
				$("#ezTable").css("fontSize", 11);

				if(enzymeID == -1){
					ezID = data[data.length-1].split('|||')[1];
					$("#ezTable td:contains('" + data[data.length-1].split('|||')[3] + "')").css('background-color', 'green');
				}
				else {
					ezID = currentEzID;
					enzymeID = ezID;
				}
				$( "p" ).html( "<b>Batch Infos</b> | Name : " + batchName + " | Enzyme : "+ezNameMap[ezID]);
				$.ajax({
			        url: "processing_get_rawData.php",
			        type: "post",
			        data: { batchID : batchID, ezID : ezID },
					success: function(data) {
						if(data=="error"){
							console.log("Get raw data Error");
						}
						else{
							var hashMap = new Object();
							for(var i=0;i<data.length; i++){
								hashMap[data[i].split('#')[1]]=0;
							}
							for(var i=0;i<data.length; i++){
								hashMap[data[i].split('#')[1]]++;
							}
							delete hashMap["EB"];
							delete hashMap["?"];
							var min=96;
							var minname="";
							for (var key in hashMap) {
								if (hashMap[key]<min){
									min = hashMap[key];
									minname=key;
									$.ajax({
								        url: "get_activity.php",
								        type: "post",
								        data: { minname : minname, ezID : ezID },
										success: function(data) {
											activity=data;
										}
									});
								}
							}

							for(var i=0;i<data.length; i++){
								var expName = data[i].split('#')[1];
								var row = parseInt(data[i].split('#')[2]);
								var col = parseInt(data[i].split('#')[3]);
								var rawvalue = parseFloat(data[i].split('#')[4]);
								var velocity = data[i].split('#')[5];
								var enzymeID = parseInt(data[i].split('#')[6]);
								var excluded = parseInt(data[i].split('#')[7]);
								var rawDataID =  parseInt(data[i].split('#')[8]);
								var layout =  data[i].split('#')[9];
								split=layout;
								var procValue =  data[i].split('#')[10] == "" ? "NA" : data[i].split('#')[10];
								var fw = parseFloat(data[i].split('#')[11]);

								if(velocity == "max"){
									rawDataMap[row+4][col]=rawvalue;
									excludMap[row+4][col]=excluded;
									fwMap[row+4][col] = fw;
									rawIDMap[row+4][col] = rawDataID;
									if(expName === "EB"){
										dataTypeMap[row+4][col] =  1;
									}
									else if(expName === "?"){
										dataTypeMap[row+4][col] =  2;
									}
									else if(expName === minname){
										dataTypeMap[row+4][col] =  3;
									}
									else {
										dataTypeMap[row+4][col] =  0;
									}
								}
								else {
									rawDataMap[row][col]=rawvalue;
									excludMap[row][col]=excluded;
									fwMap[row][col] = fw;
									rawIDMap[row][col] = rawDataID;
									if(expName === "EB"){
										dataTypeMap[row][col] =  1;
									}
									else if(expName === "?"){
										dataTypeMap[row][col] =  2;
									}
									else if(expName === minname){
										dataTypeMap[row][col] =  3;
									}
									else {
										dataTypeMap[row][col] =  0;
									}
									processDataMap[row][col]=procValue;
								}
								
							}
							displayRawData(rawDataMap, dataTypeMap, excludMap, layout);
							displayProcessData(processDataMap, dataTypeMap, excludMap, layout);
							$('#table-wrapper').show();
							$('#head-wrapper').show();
							$('#expender-wrapper').show();
							$('#datatable-wrapper').hide();
						}
					}
				});
			}
		}
	});
}

/**
 * Display raw values
 *
 * @method     displayRawData
 * @param      {<type>}  rawDataMap   { description }
 * @param      {<type>}  dataTypeMap  { description }
 * @param      {<type>}  excludMap    { description }
 * @param      {string}  layout       { description }
 */
function displayRawData(rawDataMap, dataTypeMap, excludMap, layout) {

	$("#rawTable tbody tr td").css('text-decoration', 'none');
	$("#rawTable tbody tr td").css('background-color', '');
	//EB:1, ?:2, control:3, data:0
	for(var i=0;i<8; i++){
		for(var j=0;j<12; j++){
			if(dataTypeMap[i][j] == 1){
				$('#rawTable').find('tr').eq(i+1).find('td').eq(j+1).text(rawDataMap[i][j]).css('background-color', '#BDE5F8');
			}
			else if(dataTypeMap[i][j] == 2){
				$('#rawTable').find('tr').eq(i+1).find('td').eq(j+1).text(rawDataMap[i][j]).css('background-color', '#686868');
			}
			else if(dataTypeMap[i][j] == 3){
				$('#rawTable').find('tr').eq(i+1).find('td').eq(j+1).text(rawDataMap[i][j]).css('background-color', '#B7FFAF');
			}
			else if(dataTypeMap[i][j] == 0){
				$('#rawTable').find('tr').eq(i+1).find('td').eq(j+1).text(rawDataMap[i][j]);
			}
			if(excludMap[i][j] == 1){
				$('#rawTable').find('tr').eq(i+1).find('td').eq(j+1).css('text-decoration', 'line-through');
			}
			$("#rawTable").css("fontSize", 11);
		}
	}
	if (layout==="SPLIT") {
		for(var i=4;i<8; i++){
			for(var j=0;j<12; j++){
				if(dataTypeMap[i][j] == 1){
					$('#rawTable').find('tr').eq(i+1).find('td').eq(j+1).css('background-color', '#A0CFDF');
				}
				else if(dataTypeMap[i][j] == 2){
					$('#rawTable').find('tr').eq(i+1).find('td').eq(j+1).css('background-color', '#686868');
				}
				else if(dataTypeMap[i][j] == 3){
					$('#rawTable').find('tr').eq(i+1).find('td').eq(j+1).css('background-color', '#B4D3B0');
				}
				else if(dataTypeMap[i][j] == 0){
					$('#rawTable').find('tr').eq(i+1).find('td').eq(j+1).css('background-color', '#D7D7D7');
				}
				if(excludMap[i][j] == 1){
					$('#rawTable').find('tr').eq(i+1).find('td').eq(j+1).css('text-decoration', 'line-through');
				}
				$("#rawTable").css("fontSize", 11);
			}
		}
	}
}

/**
 * Display processed values
 *
 * @method     displayProcessData
 * @param      {<type>}  processDataMap  { description }
 * @param      {<type>}  dataTypeMap     { description }
 * @param      {<type>}  excludMap       { description }
 * @param      {string}  layout          { description }
 */
function displayProcessData(processDataMap, dataTypeMap, excludMap, layout) {
	console.log("DISPLAY  : processdata");
	console.log(processDataMap);
	getStdDev(processDataMap, dataTypeMap, excludMap);
	$("#processTable tbody tr td").css('text-decoration', 'none');
	$("#processTable tbody tr td").css('background-color', '');

	//EB:1, ?:2, control:3 vert, data:0
	for(var i=0;i<8; i++){
		for(var j=0;j<12; j++){
				var processData = Math.round(processDataMap[i][j] * 100) / 100;
				if (isNaN(processData)){
					processData = "NA";
				}
			if(dataTypeMap[i][j] == 1){
				$('#processTable').find('tr').eq(i+1).find('td').eq(j+1).text("NA").css('background-color', '#BDE5F8');
			}
			else if(dataTypeMap[i][j] == 2){
				console.log("i"+i+" : j"+j);
				$('#processTable').find('tr').eq(i+1).find('td').eq(j+1).text("?").css('background-color', '#686868');
			}
			else if(dataTypeMap[i][j] == 3){
				$('#processTable').find('tr').eq(i+1).find('td').eq(j+1).text(processData).css('background-color', '#B7FFAF');
			}
			else if(dataTypeMap[i][j] == 0){
				$('#processTable').find('tr').eq(i+1).find('td').eq(j+1).text(processData);
			}
			if(excludMap[i][j] == 1){
				$('#processTable').find('tr').eq(i+1).find('td').eq(j+1).css('text-decoration', 'line-through');
			}
			$("#processTable").css("fontSize", 11);
		}
	}
	if (layout==="SPLIT") {
		for(var i=4;i<8; i++){
			for(var j=0;j<12; j++){
				$('#processTable').find('tr').eq(i+1).find('td').eq(j+1).text('').css('background-color', '#D7D7D7');
				if(excludMap[i][j] == 1){
					$('#processTable').find('tr').eq(i+1).find('td').eq(j+1).text('').css('text-decoration', 'line-through');
				}
				$("#processTable").css("fontSize", 11);
			}
		}
	}
}

/**
 * Calculs for processing values
 *
 * @method     processMetabolites
 * @param      {<type>}  rawDataMap      { description }
 * @param      {<type>}  dataTypeMap     { description }
 * @param      {<type>}  excludMap       { description }
 * @param      {<type>}  layout          { description }
 * @param      {<type>}  processDataMap  { description }
 * @param      {number}  activity        { description }
 * @param      {number}  fwMap           { description }
 * @return     {<type>}  { description_of_the_return_value }
 */
function processMetabolites(rawDataMap, dataTypeMap, excludMap, layout, processDataMap, activity, fwMap){
	/* Corrected_Val = (rawVal-AverageOf(buffer)) / frehsweight */
	var rawDataMap = rawDataMap.slice(0, 8);
	var averageOfBuffer = bufferAvg(rawDataMap, excludMap, dataTypeMap);
	/* Corrected_Control         = AverageOf((control - AverageOf(buffer))/freshweight) */
	var controlCorrected = correctedControlAvg(rawDataMap, excludMap, dataTypeMap, fwMap, averageOfBuffer);
	for(var i=0;i<8; i++){
		for(var j=0;j<12; j++){
			if((excludMap[i][j]==0) && (dataTypeMap[i][j]!=1)) {
				/* Final_Val   = ((Corrected_Val) / (Corrected_Control)) * Activation */
				var correctedRawData = (rawDataMap[i][j]-averageOfBuffer)/fwMap[i][j];
				correctedRawData = correctedRawData/controlCorrected;
				correctedRawData = correctedRawData*activity;
				processDataMap[i][j] = correctedRawData;
				/* processDataMap[i][j]= (rawDataMap[i][j] - averageOfBuffer)/fwMap[i][j]; */
			}
		}
	}
	return processDataMap;
}

/**
 * Send array of processed control data to get a standard error
 *
 * @method     getStdDev
 * @param      {<type>}  processDataMap  { description }
 * @param      {<type>}  dataTypeMap     { description }
 * @param      {<type>}  excludMap       { description }
 */
function getStdDev(processDataMap, dataTypeMap, excludMap){
	var stdArray=[];
	for(var i=0;i<8; i++){
		for(var j=0;j<12; j++){
			if(excludMap[i][j]==0){
				if(dataTypeMap[i][j]==3){
					if(!isNaN(processDataMap[i][j])){
						stdArray.push(parseFloat(processDataMap[i][j]));
					}
				}
			}
		}
	}
	var stdDev = getError(stdArray,2);
	$('#errorsInCtrl').html("Errors in control : "+stdDev); 
}

function processEnzyme(rawDataMap, dataTypeMap, excludMap, layout, processDataMap, activity, fwMap){
	/* Needed vars... */
	var slopeRatio = 1.0;
	var blankRawDataMap = rawDataMap.slice(0, 4);
	var blankDataTypeMap = dataTypeMap.slice(0, 4);
	var blankExcludMap = excludMap.slice(0, 4);
	var blankFwMap = fwMap.slice(0, 4);
	var maxRawDataMap = rawDataMap.slice(4, 8);
	var maxDataTypeMap = dataTypeMap.slice(4, 8);
	var maxExcludMap = excludMap.slice(4, 8);
	var maxFwMap = fwMap.slice(4, 8);
	var avgBlankBuffer = bufferAvg(blankRawDataMap, blankExcludMap, blankDataTypeMap);
	var avgMaxBuffer = bufferAvg(maxRawDataMap, maxExcludMap, maxDataTypeMap);

	/* Corrected_MAX_Control     = AverageOf((MAX_control - AverageOf(MAX_buffer))/freshweight/SloapRatio) */
	var corrMaxControl = corrControlMaxAverage (maxRawDataMap, maxExcludMap, maxDataTypeMap, maxFwMap, avgMaxBuffer, slopeRatio);
	/* Corrected_BLANK_Control   = AverageOf((BLANK_control - AverageOf(BLANK_buffer))/freshweight) */
	var corrBlankControl = corrControlBlankAverage (blankRawDataMap, blankExcludMap, blankDataTypeMap, blankFwMap, avgBlankBuffer);

	for(var i=0;i<4; i++){
		for(var j=0;j<12; j++){
			if((excludMap[i][j]==0) && (dataTypeMap[i][j]!=1)) {
		        /*Corrected_BLANK_Val = (rawBLANK-AverageOf(BLANK_buffer)) / frehsweight */
		        var corrBlankValue = blankRawDataMap[i][j]-avgBlankBuffer;
		        corrBlankValue = corrBlankValue/blankFwMap[i][j];
				/* Corrected_MAX_Val = ((rawMAX-AverageOf(BLANK_buffer) / SloapRatio) / frehsweight */
				var corrMaxValue = maxRawDataMap[i][j]-avgMaxBuffer;
				corrMaxValue = corrMaxValue/slopeRatio;
				corrMaxValue = corrMaxValue/maxFwMap[i][j];
				/* Final_Val  = ((Corrected_MAX_Val - Corrected_BLANK_Val) / (Corrected_MAX_Control - Corrected_BLANK_Control)) * Activation */
				var procValue = ((corrMaxValue-corrBlankValue)/(corrMaxControl-corrBlankControl))*activity;
				processDataMap[i][j]=procValue;
			}
		}
	}
	return processDataMap;
}

/**
 * Get corrected blank values 
 *
 * @method     correctBlankValues
 * @param      {number}    rawDataMap   { description }
 * @param      {<type>}    excludMap    { description }
 * @param      {<type>}    dataTypeMap  { description }
 * @param      {number}    fwMap        { description }
 * @param      {number}    bufferAvg    { description }
 * @param      {<type>}    valueType    { description }
 * @return     {Function}  { description_of_the_return_value }
 */
function correctBlankValues (rawDataMap, excludMap, dataTypeMap, fwMap, bufferAvg, valueType){
	var tempMap = Array();
	for(var i=0;i<rawDataMap.length; i++){
		tempMap[i]=Array();
		for(var j=0;j<12; j++){
			if((excludMap[i][j]==0) && (dataTypeMap[i][j]==valueType)){
				var x = rawDataMap[i][j];
				x = x - bufferAvg;
				x = x / fwMap[i][j];
				tempMap[i][j]=x;
			}
		}
	}
	return (tempMap);
}

/**
 * Get corrected Max values
 *
 * @method     correctMaxValues
 * @param      {number}    rawDataMap   { description }
 * @param      {<type>}    excludMap    { description }
 * @param      {<type>}    dataTypeMap  { description }
 * @param      {number}    fwMap        { description }
 * @param      {number}    bufferAvg    { description }
 * @param      {number}    slopeRatio   { description }
 * @param      {<type>}    valueType    { description }
 * @return     {Function}  { description_of_the_return_value }
 */
function correctMaxValues (rawDataMap, excludMap, dataTypeMap, fwMap, bufferAvg, slopeRatio, valueType){
	var tempMap = Array();
	for(var i=0;i<rawDataMap.length; i++){
		tempMap[i]=Array();
		for(var j=0;j<12; j++){
			if((excludMap[i][j]==0) && (dataTypeMap[i][j]==valueType)){
				var x = rawDataMap[i][j];
				x = x - bufferAvg;
				x = x / fwMap[i][j];
				x = x / slopeRatio;
				tempMap[i][j]=x;
			}
		}
	}
	return (tempMap);
}

/**
 * Not even sure of what it does
 *
 * @method     corrControlBlankAverage
 * @param      {number}  rawDataMap   { description }
 * @param      {<type>}  excludMap    { description }
 * @param      {<type>}  dataTypeMap  { description }
 * @param      {number}  fwMap        { description }
 * @param      {number}  buffAvg      { description }
 * @return     {number}  { description_of_the_return_value }
 */
function corrControlBlankAverage (rawDataMap, excludMap, dataTypeMap, fwMap, buffAvg){
	var somme=0;
	var it =0;
	var slopeRatio=1.0;
	for(var i=0;i<rawDataMap.length; i++){
		for(var j=0;j<12; j++){
			if((excludMap[i][j]==0) && (dataTypeMap[i][j]==3)){
				var x = rawDataMap[i][j];
				x = x - buffAvg;
				x = x / fwMap[i][j];

				somme+= x;//((rawDataMap[i][j] - buffAvg)/fwMap[i][j])/slope;
				it++;
			}
		}
	}
	return (somme/it);
}

function corrControlMaxAverage (rawDataMap, excludMap, dataTypeMap, fwMap, buffAvg, slopeRatio){
	var somme=0;
	var it =0;
	//var slopeRatio=1.0;
	for(var i=0;i<rawDataMap.length; i++){
		for(var j=0;j<12; j++){
			if((excludMap[i][j]==0) && (dataTypeMap[i][j]==3)){
				var x = rawDataMap[i][j];
				x = x - buffAvg;
				x = x / fwMap[i][j];
				x = x / slopeRatio;

				somme+= x;//((rawDataMap[i][j] - buffAvg)/fwMap[i][j])/slope;
				it++;
			}
		}
	}
	return (somme/it);
}

function correctedControlAvg (rawDataMap, excludMap, dataTypeMap, fwMap, buffAvg){
	var somme=0;
	var it =0;

	for(var i=0;i<8; i++){
		for(var j=0;j<12; j++){
			if((excludMap[i][j]==0) && (dataTypeMap[i][j]==3)){
				var x = rawDataMap[i][j];
				x = x - buffAvg;
				x = x / fwMap[i][j];

				somme+= x;//((rawDataMap[i][j] - buffAvg)/fwMap[i][j]);
				it++;
			}
		}
	}
	return (somme/it);
}

function bufferAvg(rawDataMap, excludMap, dataTypeMap){
	var somme=0;
	var it =0;
	for(var i=0;i<rawDataMap.length; i++){
		for(var j=0;j<12; j++){
			if(excludMap[i][j]==0){
				if(dataTypeMap[i][j]==1){
					somme+=rawDataMap[i][j];
					it++;
				}
			}
		}
	}
	return (somme/it);
}


/***********************************/
/* Functions for errors in control */
/***********************************/

/**
 * Because arrays are needed
 *
 * @method     isArray
 * @param      {<type>}  obj     { description }
 * @return     {<type>}  { description_of_the_return_value }
 */
function isArray (obj) {
	return Object.prototype.toString.call(obj) === "[object Array]";
}

/**
 * Trunk values
 *
 * @method     getNumWithSetDec
 * @param      {number}  num       { description }
 * @param      {<type>}  numOfDec  { description }
 * @return     {<type>}  { description_of_the_return_value }
 */
function getNumWithSetDec ( num, numOfDec ){
	var pow10s = Math.pow( 10, numOfDec || 0 );
	return ( numOfDec ) ? Math.round( pow10s * num ) / pow10s : num;
}

/**
 * Average calculation
 *
 * @method     getAverageFromNumArr
 * @param      {number}   numArr    { description }
 * @param      {<type>}   numOfDec  { description }
 * @return     {boolean}  { description_of_the_return_value }
 */
function getAverageFromNumArr ( numArr, numOfDec ){
	if( !isArray( numArr ) ){ return false;	}
	var i = numArr.length, 
		sum = 0;
	while( i-- ){
		sum += numArr[ i ];
	}
	return getNumWithSetDec( (sum / numArr.length ), numOfDec );
}

/**
 * Variance calculation
 *
 * @method     getVariance
 * @param      {number}            numArr    { description }
 * @param      {<type>}            numOfDec  { description }
 * @return     {(boolean|number)}  { description_of_the_return_value }
 */
function getVariance ( numArr, numOfDec ){
	if( !isArray(numArr) ){ return false; }
	var avg = getAverageFromNumArr( numArr, numOfDec ), 
		i = numArr.length,
		v = 0;
	while( i-- ){
		v += Math.pow( (numArr[ i ] - avg), 2 );
	}
	v /= numArr.length-1;
	return v;
}

/**
 * Standard deviation calculation
 *
 * @method     getStandardDeviation
 * @param      {<type>}   numArr    { description }
 * @param      {<type>}   numOfDec  { description }
 * @return     {boolean}  { description_of_the_return_value }
 */
function getStandardDeviation( numArr, numOfDec ){
	if( !isArray(numArr) ){ return false; }
	var stdDev = Math.sqrt( getVariance( numArr, numOfDec ) );
	return getNumWithSetDec( stdDev, numOfDec );
}

/**
 * Get errors in control, function "main" of those just above
 *
 * @method     getError
 * @param      {<type>}   numArr    { description }
 * @param      {<type>}   numOfDec  { description }
 * @return     {boolean}  { description_of_the_return_value }
 */
function getError(numArr, numOfDec){
	if( !isArray(numArr) ){ return false; }
	var ec = getStandardDeviation( numArr, numOfDec );
	var mean =  getAverageFromNumArr( numArr, numOfDec );
	var error = (ec/mean)*100;
	return getNumWithSetDec(error, numOfDec);

}



</script>
