<?php
require('../functions/check_login.php');
include '../functions/html_functions.php';
include '../functions/php_functions.php';

html_header("../../",  $_SESSION['login']);

generic_html_top_page("../../","Export");
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
	<div id="button-wrapper">
		<div class="btn-group" data-toggle="buttons" role="group" id="buttonGroup">
			<button type="button" class="btn btn-lg btn-primary" id="normalTable">Normal</button>
			<button type="button" class="btn btn-lg btn-primary" id="aliquotMerge">Aliquot Merge</button>
			<button type="button" class="btn btn-lg btn-primary" id="batchCentering">Interbatch centering</button>
			<button type="button" class="btn btn-lg btn-primary" id="coeffVar">Std & CV</button>
		</div>
	</div>
</div>';

echo'
<div class="row-fluid">
	<div class="span5" collapse-group>
		<div class="widget-box" style="background : transparent">
			<div class="widget-content nopadding">
				<div id="expender-wrapper">
					<button type="button" class="btn btn-xs btn-info" id ="expender" style="width:100%"><i class="icon-list"></i> &nbsp; Expend experiment table </button>
				</div>
				<div id="experiment-wrapper">
					<table id="expTable" class="table display table-bordered table-hover " style="width:100%">
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
	</div>
</div>

<div class="row-fluid">
	<div id="rawtable-wrapper">
		<div id="reloadRawTable">
			<table id="RawTable" cellpadding="0" cellspacing="0" class="table table-bordered nowrap table-condensed table-body-center" style="width:100%">
			</table>
		</div>
	</div>
</div>

<div class="row-fluid">
	<div id="mergedTable-wrapper">
		<div id="reloadMergedTable">
			<table id="MergedTable" cellpadding="0" cellspacing="0" class="table table-bordered nowrap table-condensed table-body-center" style="width:100%">
			</table>
		</div>
	</div>
</div>

<!--<div class="row-fluid">
	<div id="interbatchTable-wrapper">
		<div id="reloadInterbatchTable">
			<table id="InterbatchTable" cellpadding="0" cellspacing="0" class="table table-bordered nowrap table-condensed table-body-center" style="width:100%">
			</table>
		</div>
	</div>
</div>-->

<div class="row-fluid">
	<div id="cvTable-wrapper">
		<div id="reloadCvTable">
			<table id="CvTable" cellpadding="0" cellspacing="0" class="table table-bordered nowrap table-condensed table-body-center" style="width:100%">
			</table>
		</div>
	</div>
</div>';

html_footer("../../");
?>
<script type="text/javascript" class="init">

	/* Global vars */
	var expID="";
	var expName="";
	var jsonData=[];
	var jsonColumns =[];
//	var sampleHash = new Array();
//	var aliquotHash = new Array();
//	var dataHash =  new Array();
	var batchIDs = {};
	var enzymeIDs = {};


$(document).ready(function() {
	/* Hide everything and dont allow user to click on buttons ! */
	$('#userPanel').removeClass('submenu');
	$('#userPanel').addClass('submenu open');
	$('#rawtable-wrapper').hide();
	$('#expender-wrapper').hide();
	$('#mergedTable-wrapper').hide();
	$('#cvTable-wrapper').hide();
	$( "button" ).filter( "#aliquotMerge" ).prop("disabled",true);
	$( "button" ).filter( "#batchCentering" ).prop("disabled",true);
	$( "button" ).filter( "#coeffVar" ).prop("disabled",true);
	$( "button" ).filter( "#normalTable" ).prop("disabled",true);


	setup_experiment_datatable();

	/* Listener for experiment table */
	$('#expTable').on('click', 'tr', function () {
		showWaitModal();
		clearTable('RawTable');
		$("#rawtable-wrapper").show();
		$('#expender-wrapper').show();
		$('#experiment-wrapper').hide();
		batchIDs = {};
		enzymeIDs = {};
		expID = $('td', this).eq(0).text();
		expName = $('td', this).eq(1).text();
		$('#experimentID').html(expName);
		generate_data(expID, expName);

		/**
		 * enable all buttons after an experiment has been selected
		 */
		$( "button" ).filter( "#aliquotMerge" ).prop("disabled",false);
		$( "button" ).filter( "#batchCentering" ).prop("disabled",false);
		$( "button" ).filter( "#coeffVar" ).prop("disabled",false);
		hideWaitModal();

	});

	$( "#expender" ).click(function() {
		setup_experiment_datatable();
		$('#rawtable-wrapper').hide();
		$('#expender-wrapper').hide();
		$('#mergedTable-wrapper').hide();
		$('#cvTable-wrapper').hide();
		$('#experiment-wrapper').show();
	});


	/* Listener on the button group */
	$('#buttonGroup button').click(function() {
		var selectedButton = $(this).attr('id')
		showWaitModal();
		document.getElementById('normalTable').classList.remove('active');
		document.getElementById('aliquotMerge').classList.remove('active');
		document.getElementById('batchCentering').classList.remove('active');
		document.getElementById('coeffVar').classList.remove('active');
		$( "button" ).filter( "#normalTable" ).prop("disabled",true);
		$( "button" ).filter( "#aliquotMerge" ).prop("disabled",true);
		$( "button" ).filter( "#batchCentering" ).prop("disabled",true);
		$( "button" ).filter( "#coeffVar" ).prop("disabled",true);
		switch(selectedButton) {
			case "normalTable":
				$( "button" ).filter( "#normalTable" ).prop("disabled",true);
				$( "button" ).filter( "#aliquotMerge" ).prop("disabled",false);
				$( "button" ).filter( "#batchCentering" ).prop("disabled",false);
				$( "button" ).filter( "#coeffVar" ).prop("disabled",false);
				document.getElementById('normalTable').classList.add('active');
		$('#experimentID').html(expName);
		generate_data(expID, expName);
				$('#rawtable-wrapper').show();
				$('#expender-wrapper').show();
				$('#mergedTable-wrapper').hide();
				$('#cvTable-wrapper').hide();
				break;

			case "aliquotMerge":
				$( "button" ).filter( "#normalTable" ).prop("disabled",false);
				$( "button" ).filter( "#aliquotMerge" ).prop("disabled",true);
				$( "button" ).filter( "#batchCentering" ).prop("disabled",true);
				$( "button" ).filter( "#coeffVar" ).prop("disabled",true);
				document.getElementById('aliquotMerge').classList.add('active');

				clearTable('MergedTable');

				$('#rawtable-wrapper').hide();
				$('#expender-wrapper').show();
				$('#mergedTable-wrapper').show();
				$('#cvTable-wrapper').hide();
				aliquotMerge(expName);
				break;

			case "batchCentering":
				$( "button" ).filter( "#normalTable" ).prop("disabled",false);
				$( "button" ).filter( "#aliquotMerge" ).prop("disabled",false);
				$( "button" ).filter( "#batchCentering" ).prop("disabled",true);
				$( "button" ).filter( "#coeffVar" ).prop("disabled",true);
				document.getElementById('batchCentering').classList.add('active');

				clearTable('InterbatchTable');

				$('#rawtable-wrapper').show();
				$('#expender-wrapper').show();
				$('#mergedTable-wrapper').hide();
				$('#cvTable-wrapper').hide();
				interBatchCentering("RawTable");
				break;

			case "coeffVar":
				$( "button" ).filter( "#normalTable" ).prop("disabled",false);
				$( "button" ).filter( "#aliquotMerge" ).prop("disabled",true);
				$( "button" ).filter( "#batchCentering" ).prop("disabled",true);
				$( "button" ).filter( "#coeffVar" ).prop("disabled",true);
				document.getElementById('coeffVar').classList.add('active');

				clearTable('CvTable');
				
				$('#rawtable-wrapper').hide();
				$('#expender-wrapper').show();
				$('#mergedTable-wrapper').hide();
				$('#cvTable-wrapper').show();
				standardsUsed("RawTable");
				break;

			default: 
				alert("This options doesn't exists");
		}
		hideWaitModal();
	});
}); 

// END OF DOCUMENT READY EVENTS


//##################################################
//##################################################
//################ Calls out ready #################
//##################################################
//##################################################


/**
 * get all experiment and make  datatable
 *
 * @method     setup_experiment_datatable
 */
function setup_experiment_datatable(){
	$('#expTable').dataTable().fnDestroy();
	var table = $('#expTable').DataTable({
		scrollY:		500,
		scrollX:		true, 
		scroller:		true,
		destroy:		true,
		stateSave: true,
		select: "single",
		paging : true,
		dom: 'TB<"clear">frtip',
		ajax: 'get_all_experiment.php',
		order: [[1, "asc"]],
		createdRow: function( row, data, dataIndex ) {
			if ( data[2] == "" ) {
				$(row).css('background-color', '#FDFEFE');
			}
			else{
				$(row).css('background-color', '#D5F5E3');
			}
		},
		columnDefs: [ {
			"targets":0,
			"width": "20px"
		} ],
		buttons: [
			'copy', 'csv', 'excel', 'print'
		]
	});
}


/**
 * Get all data for an experiment and make arrays for datatables
 *
 * @method     generate_data
 * @param      {Int}  expID    { ID de l'experiment }
 * @param      {String}  expName  { Nom de l'experiment }
 */
function generate_data(expID, expName){
	$.ajax({
		url: "export_get_exp_data.php",
		type: "post",
		data: { expID : expID, expName : expName},
		beforeSend: function(){
			showWaitModal();
		},
		success: function(data) {
			if(data == ""){
				alert("ERROR experience vide");
			}
			else{
				console.log(data);
				var headSplit = data[0].split('>');
				var jsonArray =[];
				var colDef = [
					{title: "Batch"},
					{title: "Experiment"},
					{title: "Sample"},
					{title: "Aliquot"}
				];
				var columns = ["Batch", "Experiment", "Sample", "Aliquot"];
				/* Get the thead */
				for(var j=0; j<headSplit.length-1; j++){
					var objet = new Object();
					var ezSplit = headSplit[j].split("#");
					objet.title=ezSplit[0];
					colDef.push(objet);
					columns.push(ezSplit[0]);
					enzymeIDs[ezSplit[0]]=ezSplit[1];
				}
				/* Get other rows */
				for(var i=1;i<data.length; i++){
					var lineArray = data[i].split('#');
					batchIDs[lineArray[0]]=lineArray[2];
					for (var k = 2; k <lineArray.length; k++){
						var procValue =  lineArray[k] < 0 ? "NA" : lineArray[k];
						lineArray[k]=procValue;
					}
					jsonArray.push(lineArray.slice(2));
				}
				/* Those are global vars */
				jsonData = jsonArray;
				jsonColumns= colDef;
				/*  */
				setup_rawtable_datatable(colDef, jsonArray);
			}
		},
		complete: function(){
			hideWaitModal();
		}
	});
}

/**
 * Resete a datatable
 *
 * @method     clearTable
 * @param      {string}  tableID  { description }
 */
function clearTable(tableID){
	$(tableID).remove();
	$('#reload'+tableID).html('<table id="'+tableID+'" cellpadding="0" cellspacing="0" class="table table-bordered nowrap table-condensed table-body-center" style="width:100%"></table>');
}

/**
 * Make a datatables from previously prepared data (in generate_data)
 *
 * @method     setup_rawtable_datatable
 * @param      {<type>}  colDef   { description }
 * @param      {<type>}  dataset  { description }
 */
function setup_rawtable_datatable(colDef, dataset){
	$('#RawTable').DataTable({
		scrollX:true,
		scrollY: 600,
		paging: false,
		destroy: true,
		stateSave: true,
		dom: 'Brtip',
		columns: colDef,
		data: dataset,
		order: [[ 2, 'asc' ], [ 3, 'asc' ]],
		buttons: [
			'copy', 'csv', 'excel', 'print'
		]
	})
}


/**
 * Display the number of standard used / number of standards
 *
 * @method     standardsUsed
 * @param      {<type>}  tableID  { description }
 */
function standardsUsed(tableID){
//	var table=document.getElementById("RawTable");
	var hashStdUsed = {};
	console.log(batchIDs);
	$.each(batchIDs, function(batchID, batchName){
		hashStdUsed[batchName]={};
		$.each(enzymeIDs, function(ezName, ezID){
			hashStdUsed[batchName][ezName]={};
			$.ajax({
		        url: "get_exp_names_by_batch.php",
				type: "post",
				data: {batchID : batchID, ezID : ezID},
				async : false,
				success: function(data) {
					var obj = JSON.parse(data);
					hashStdUsed[batchName][ezName]["MaxStd"]= obj.MaxStd;
					hashStdUsed[batchName][ezName]["StdUsed"]= obj.StdUsed;
					$.ajax({
						url: "get_procvalues.php",
						type: "post",
						data: {batchID : batchID, ezID : ezID},
						async : false,
						success: function(data) {
							console.log(data);
							var obj2 = JSON.parse(data);
							var arrayOfNumbers = obj2.procValues.map(Number);
							hashStdUsed[batchName][ezName]["CV"] = getError(arrayOfNumbers,2);

						},
						error: function(xhr, status, error) {
							retour = false;
						}
					});
				},
				error: function(xhr, status, error) {
					retour = false;
				}
			});
		});
	});
	console.log(hashStdUsed);
	/* Create a 2D array
	 * First line is the thead
	 */
	var finalMap=[];
	var headerTab=["Batch","Experiment","Sample","Aliquot"];
	var table=document.getElementById("RawTable");
	var nbRows = table.rows.length ;
	var nbCols = table.rows[0].cells.length;

	for (var i=4; i<nbCols; i++){
		var code = table.rows[0].cells[i].firstChild.innerHTML.split('_')[0];
		headerTab.push(table.rows[0].cells[i].firstChild.innerHTML);
		headerTab.push(code+"_Std Used");
		headerTab.push(code+"_CV %");
	}
	finalMap.unshift(headerTab);

	for(var i=1; i<nbRows; i++){
		var tab = [];
		for (var j=0; j<4 ; j++){
			tab.push(table.rows[i].cells[j].innerText);
		}
		for (var j=4; j <= nbCols-1; j++){
			tab.push(table.rows[i].cells[j].innerText);
			if (hashStdUsed[table.rows[i].cells[0].innerText][table.rows[0].cells[j].firstChild.innerHTML]["MaxStd"] == null){
				tab.push("NA");
			}
			else{
				tab.push(hashStdUsed[table.rows[i].cells[0].innerText][table.rows[0].cells[j].firstChild.innerHTML]["StdUsed"]+"/"+hashStdUsed[table.rows[i].cells[0].innerText][table.rows[0].cells[j].firstChild.innerHTML]["MaxStd"]);
			}
			// tab.push((Math.round(hashStdUsed[table.rows[i].cells[0].innerText][table.rows[0].cells[j].firstChild.innerHTML]["CV"])*10)/10);
			if (isNaN(hashStdUsed[table.rows[i].cells[0].innerText][table.rows[0].cells[j].firstChild.innerHTML]["CV"])){
				tab.push("NA");
			}
			else{
				tab.push(hashStdUsed[table.rows[i].cells[0].innerText][table.rows[0].cells[j].firstChild.innerHTML]["CV"]);
			}
		}
		finalMap.push(tab);
	}
	console.log("finalMap");
	console.log(finalMap);
	printStdUsed(finalMap);
}


/**
 * Print the number of user standard and CV
 *
 * @method     printStdUsed
 * @param      {number}  map     { description }
 */
function printStdUsed(map){
	var colDef = [];
	var jsonDataMap=[];
	for(var j=0; j<map[0].length; j++){
		var objet = new Object();
		objet.title=map[0][j];
		colDef.push(objet);
	}
	for(var i=1;i<map.length-1; i++){
		if((typeof map[i] != 'undefined') || (map[i] != undefined) || (map[i] !="")) {
			jsonDataMap.push(map[i]);
		}
	}
	console.log("jsonDataMap");
	console.log(jsonDataMap);
	$('#CvTable').DataTable({
		scrollX:true,
		scrollY: 600,
		paging: false,
		destroy: true,
		stateSave: true,
		dom: 'Brtip',
		columns: colDef,
		"aaData": jsonDataMap,
		order: [[ 2, 'asc' ], [ 3, 'asc' ]],
		buttons: [
			'copy', 'csv', 'excel', 'print'
		]
	});
}

/**
 * Apply interbatch centering
 *
 * @method     interBatchCentering
 * @param      {<type>}  tableID  Id of the table to convert
 */
function interBatchCentering(tableID){
	var table=document.getElementById(tableID);
	var nbRows = table.rows.length ;
	var nbCols = table.rows[0].cells.length;
	var hashBatch = {};
	var MeanOfMeans={};


	/**
	 * Initialize Hash
	 */
	for(var i=1; i<nbRows; i++){
		hashBatch[table.rows[i].cells[0].innerText]={};
	}
//	var batchNumber = Object.keys(hashBatch).length;
	for(var i=1; i<nbRows; i++){
		for (var j=4; j <= nbCols-1; j++){
			hashBatch[table.rows[i].cells[0].innerText][table.rows[0].cells[j].firstChild.innerHTML]={};
			hashBatch[table.rows[i].cells[0].innerText][table.rows[0].cells[j].firstChild.innerHTML]["sum"]=0;
			hashBatch[table.rows[i].cells[0].innerText][table.rows[0].cells[j].firstChild.innerHTML]["nbRows"]=0;
			MeanOfMeans[table.rows[0].cells[j].firstChild.innerHTML]={};
		}
	}

	/**
	 * Fill Hash
	 */
	for(var i=1; i<nbRows; i++){
		for (var j=4; j <= nbCols-1; j++){
			var value = (isNaN(parseFloat(table.rows[i].cells[j].innerText))) ? 0 : parseFloat(table.rows[i].cells[j].innerText);
			hashBatch[table.rows[i].cells[0].innerText][table.rows[0].cells[j].firstChild.innerHTML]["sum"]+=value;
			if(!isNaN(parseFloat(table.rows[i].cells[j].innerText))) {
				hashBatch[table.rows[i].cells[0].innerText][table.rows[0].cells[j].firstChild.innerHTML]["nbRows"]++;
				MeanOfMeans[table.rows[0].cells[j].firstChild.innerHTML]["batchCounter"]=0;
				MeanOfMeans[table.rows[0].cells[j].firstChild.innerHTML]["value"]=0;
				MeanOfMeans[table.rows[0].cells[j].firstChild.innerHTML]["colIdx"]=j;
			}
		}
	}

	/**
	 * Means
	 */
	$.each(hashBatch, function(batchName, analytes){
		$.each(analytes, function(analyteName, values){

			var sum = hashBatch[batchName][analyteName]["sum"];
			var div = hashBatch[batchName][analyteName]["nbRows"];
			if(div==0){
				hashBatch[batchName][analyteName]["mean"]=0
			}
			else{
				hashBatch[batchName][analyteName]["mean"]= sum/div;
				MeanOfMeans[analyteName]["batchCounter"]++;
			}
		});
	});


	/**
	 * Mean of means
	 */
	$.each(hashBatch, function(batchName, analytes){
		$.each(analytes, function(analyteName, values){
			MeanOfMeans[analyteName]["value"]+=values.mean;
		});
	});
	$.each(MeanOfMeans, function(analytes, values){
		MeanOfMeans[analytes]["meanOfMeans"]=values.value/values.batchCounter;
	});

	var dtable = $('#RawTable').DataTable();
	$.each(MeanOfMeans, function(analytes, values){
		dtable
			.column( values.colIdx )
			.data()
			.each( function ( value, index ) {
				var newValue = Math.round((value/ hashBatch[table.rows[index+1].cells[0].innerText][analytes].mean*values.meanOfMeans)*1000)/1000;
				dtable.cell( index, values.colIdx ).data( newValue );
		})
		.draw();
	});
}



/**
 * Calculs pour le merge des aliquots
 *
 * @method     aliquotMerge
 * @param      {<type>}  expName  { description }
 */
function aliquotMerge(expName){
	var table = document.getElementById("RawTable");
//	var prevSample = table.rows[1].cells[2].innerText;
//	var currentSample = table.rows[1].cells[2].innerText;
//	var values = [];
//	var diviseur = [];
//	var ezNumber = table.rows[0].cells.length - 3;
//	var count = {};
	var sampleLinkID ={};
	var valuesLinkSample ={};
	var divisorLinkSample ={};

	for(var i=0; i<jsonData.length; i++){
		sampleLinkID[jsonData[i][2]]=[];
		valuesLinkSample[jsonData[i][2]]=[jsonColumns.length-4];
		divisorLinkSample[jsonData[i][2]]=[jsonColumns.length-4];
	}

	for(var i=0; i<jsonData.length; i++){
		sampleLinkID[jsonData[i][2]].push(i);
		for(var j=0; j<jsonColumns.length-4; j++){
			valuesLinkSample[jsonData[i][2]][j]=0;
			divisorLinkSample[jsonData[i][2]][j]=0;
		}
	}

	for(var i=0; i<jsonData.length; i++){
		for(var j=0; j<jsonColumns.length-4; j++){
			if(jsonData[i][j+4] != "NA"){
				var value = parseFloat(jsonData[i][j+4]);
				valuesLinkSample[jsonData[i][2]][j]+=value;
				divisorLinkSample[jsonData[i][2]][j]+=1;
			}
		}
	}

//**********************************************************

	var finalMap=[];
	var tempTab=["Experiment","Sample"]

	for (var j=4 ; j<jsonColumns.length; j++){
		tempTab.push(jsonColumns[j].title);
	}
	finalMap.unshift(tempTab);
	for (var sample in valuesLinkSample ){
		var tab = [];
		tab.push(expName);
		tab.push(sample);
		for(var ez in valuesLinkSample[sample] ){
			tab.push(Math.round((valuesLinkSample[sample][ez]/divisorLinkSample[sample][ez])*100)/100);
		}
		finalMap.push(tab);
	}
	printAliquotMerge(finalMap, expName);
}



/**
 * Affichage du merge des aliquots
 *
 * @method     printAliquotMerge
 * @param      {number}  map      { description }
 * @param      {<type>}  expName  { description }
 */
function printAliquotMerge(map, expName){
	var colDef = [];
	var jsonDataMap=[];
	for(var j=0; j<map[0].length; j++){
		var objet = new Object();
		objet.title=map[0][j];
		colDef.push(objet);
	}
	for(var i=1;i<map.length; i++){
		if((typeof map[i] != 'undefined') || (map[i] != undefined) || (map[i] !="")) {
			jsonDataMap.push(map[i]);
		}
	}
	$('#MergedTable').DataTable({
		scrollX:true,
		scrollY: 600,
		paging: false,
		destroy: true,
		stateSave: true,
		dom: 'Brtip',
		columns: colDef,
		"aaData": jsonDataMap,
		buttons: [
			'copy', 'csv', 'excel', 'print'
		]
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
 * Show a modal with a striped and full progressbar
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
 * hide the progressbar modal
 *
 * @method     hideWaitModal
 */
function hideWaitModal(){
	$("#waitModal").modal("hide");
}


/******* FUNCTIONS FOR STD ERROR CALCULS (see processing)*/
function isArray (obj) {
	return Object.prototype.toString.call(obj) === "[object Array]";
}

function getNumWithSetDec ( num, numOfDec ){
	var pow10s = Math.pow( 10, numOfDec || 0 );
	return ( numOfDec ) ? Math.round( pow10s * num ) / pow10s : num;
}

function getAverageFromNumArr ( numArr, numOfDec ){
	if( !isArray( numArr ) ){ return false;	}
	var i = numArr.length, 
		sum = 0;
	while( i-- ){
		sum += numArr[ i ];
	}
	return getNumWithSetDec( (sum / numArr.length ), numOfDec );
}

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

function getStandardDeviation( numArr, numOfDec ){
	if( !isArray(numArr) ){ return false; }
	var stdDev = Math.sqrt( getVariance( numArr, numOfDec ) );
	return getNumWithSetDec( stdDev, numOfDec );
}

function getError(numArr, numOfDec){
	// console.log("numArr");
	// console.log(numArr);
	if( !isArray(numArr) ){ return false; }
	var ec = getStandardDeviation( numArr, numOfDec );
	var mean =  getAverageFromNumArr( numArr, numOfDec );
	var error = (ec/mean)*100;
	return getNumWithSetDec(error, numOfDec);
}

</script>
