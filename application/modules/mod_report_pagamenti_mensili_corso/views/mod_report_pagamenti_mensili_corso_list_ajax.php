<!-- INIZIO-->
<div id="top_form"></div>
<div class="content-wrapper">
	<!-- Content Header (Page header) -->
	<section class="content-header">
		<?php
		$countNotify = 0;
		foreach ($notifyList as $k => $v) {
			if ($v['mod_name'] == $module) {
				$countNotify++;
			}
		}
		?>
		<?php
		if ($countNotify > 0) {
		?>
			<div id="divNotifyMsg" style="display: block; font-size: 18px;" class="alert alert-warning alert-dismissable" onclick="hideMsg('divNotifyMsg')">ATTENZIONE, CI SONO NOTIFICHE PER QUESTO MODULO
			</div>
		<?php } ?>

		<h1>
			<i class="fa fa-cubes"></i> Report Pagamenti Mensili
			<?php
			if (($perm_delete == 'Y') && ($isAdmin == TRUE) && ($mod_type == 'crud')) {
			?>
				<button class="btn btn-sm btn-danger deleteUser" disabled="disabled" id="btDeleteMass" name="btDeleteMass" onclick='deleteMassiveEntry("entry_list", "check_id", "mod_report_pagamenti_mensili_corso","deleteMassive")'>
					<i class="fa fa-trash"></i> Cancellazione Massiva
				</button>
			<?php
			}
			?>

		</h1>

	</section>
	<section class="content">
		<!-- INIZIO-->


		<!--DIV BOX-->
		<div class="box">
			<div class="box-header">
				<div class="box-tools">
				</div>
			</div>
			<!--DIV BOX-->
			<?php
			$this->load->helper('form');
			$error = $this->session->flashdata('error');
			if ($error) {
			?>
				<div class="alert alert-danger alert-dismissable">
					<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
					<?php echo $this->session->flashdata('error'); ?>
				</div>
			<?php } ?>
			<?php
			$success = $this->session->flashdata('success');
			if ($success) {
			?>
				<div class="alert alert-success alert-dismissable">
					<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
					<?php echo $this->session->flashdata('success'); ?>
				</div>
			<?php } ?>
			<div class="nav-tabs-custom">
				<ul class="nav nav-tabs" id="tab-menu">
					<li class="active"><a href='#elenco' id='href_elenco' data-toggle="tab" aria-expanded="true"></i><i class="fa fa-list-ol"></i> Elenco <i class='fa fa-cubes'></i></a></li>
				</ul>

				<div class="tab-content">
					<div class="tab-pane tab-margin active" id="elenco">

						<?php
						if (isset($comboGridFilter)) {
							foreach ($comboGridFilter as $filterName => $v) {
								echo "<b>" . $v['label'] . "</b> 
										<SELECT id='" . $filterName . "' name='" . $filterName . "'  ";
								if ($v['multiselect'] == TRUE) {
									echo " multiple ";
								}

								if ($v['bootstrapSelect'] == TRUE) {
									echo " class = 'mysearch_filter_combo_ajax' data-live-search=\"true\" data-actions-box=\"true\"  ";
									//echo " class = 'mysearch_filter_combo_ajax' data-live-search=\"true\"  ";
								} else {
									echo " class = 'mysearch_filter' ";
								}

								if (isset($v['filterSlave'])) {
									echo " onchange= 'filter_slave_population_function(\"" . $module . "\", \"" . $v['filterSlave']['filter_slave_population_function'] . "\", 
												this.value,\"" . $v['filterSlave']['filter_slave_id'] . "\", false);searchFilterGlobal.mod_esercizi_id = this.value' ";
								}

								echo "	style='padding:6px 12px;font-size: 14px;'>";
								echo "<OPTION value=''>Seleziona...</OPTION>";
								foreach ($v['itemsList'] as $kOpt => $vOpt) {
									echo "<OPTION value='" . $vOpt['id'] . "'>" . $vOpt['nome'] . "</OPTION>";
								}
								echo "</SELECT> ";
							}
						}
						?>
						&nbsp;&nbsp; <a class="btn btn-default" onclick="launchReport()" target="_blank">
							<i class="fa fa-print"></i> Stampa
						</a><br><br>
						<table class="table table-bordered table-striped" id="mytable" style='width: 100%;'>
							<thead>
								<tr>
									<th><input type="checkbox" id="check_master" name="check_master" onchange="selezionaDeselezionaTutti('check_master','check_id','btDeleteMass')" /></th>
									<th>Anagrafica</th>
									<th>Corso</th>
									<th>Tipo</th>
									<th>Data Iscrizione</th>
									<th>Set</th>
									<th>Ott</th>
									<th>Nov</th>
									<th>Dic</th>
									<th>Gen</th>
									<th>Feb</th>
									<th>Mar</th>
									<th>Apr</th>
									<th>Mag</th>
									<th>Giu</th>
									<th>Lug</th>
									<th>Ago</th>
									<th></th>
								</tr>
							</thead>

						</table>

						<div class="row">
							<div class="col-md-6">

							</div>

						</div>
					</div>
				</div>
			</div>
		</div>
		<!--FINE-->
	</section>
</div>
<!--FINE-->
<script type="text/javascript">
	objAjaxConfig.mod_name = "mod_report_pagamenti_mensili_corso";

	objAjaxConfig.mod_title = "Report Pagamenti Mensili";

	var columnArray = [];
	columnArray[1] = {
		type: "text",
		name: "Anagrafica"
	};
	columnArray[2] = {
		type: "text",
		name: "mod_corsi_nome"
	};
	/*
	columnArray[3] = {
		type: "text",
		name: "mod_corsi_tipo"
	};
	*/	
	columnArray[3] = {type:"select", name:"mod_corsi_tipo",items:['','MENSILE','ABBONAMENTO']};
	columnArray[4] = {
		type: "date",
		name: "Data_Iscrizione"
	};
	columnArray[5] = {
		type: "text",
		name: "Sett"
	};
	columnArray[6] = {
		type: "text",
		name: "Ott"
	};
	columnArray[7] = {
		type: "text",
		name: "Nov"
	};
	columnArray[8] = {
		type: "text",
		name: "Dic"
	};
	columnArray[9] = {
		type: "text",
		name: "Gen"
	};
	columnArray[10] = {
		type: "text",
		name: "Feb"
	};
	columnArray[11] = {
		type: "text",
		name: "Mar"
	};
	columnArray[12] = {
		type: "text",
		name: "Apr"
	};
	columnArray[13] = {
		type: "text",
		name: "Mag"
	};
	columnArray[14] = {
		type: "text",
		name: "Giu"
	};
	columnArray[15] = {
		type: "text",
		name: "Lug"
	};
	columnArray[16] = {
		type: "text",
		name: "Ago"
	};
	var columnGrid = [{
			"data": "id",
			"orderable": false
		},
		{
			"data": "Anagrafica"
		},
		{
			"data": "mod_corsi_nome"
		},
		{
			"data": "mod_corsi_tipo"
		},		
		{
			"data": "Data_Iscrizione"
		},
		{
			"data": "Sett"
		},
		{
			"data": "Ott"
		},
		{
			"data": "Nov"
		},
		{
			"data": "Dic"
		},
		{
			"data": "Gen"
		},
		{
			"data": "Feb"
		},
		{
			"data": "Mar"
		},
		{
			"data": "Apr"
		},
		{
			"data": "Mag"
		},
		{
			"data": "Giu"
		},
		{
			"data": "Lug"
		},
		{
			"data": "Ago"
		}
	];
	<?php
	if (isset($_REQUEST['id'])) {
		echo "editAjax('mod_report_pagamenti_mensili_corso'," . $_REQUEST['id'] . ");";
	}
	?>

	var data_table = null;
	var firstInitDatatable = true;
	var tabID = 1;
	var searchFilterGlobal = new Array();
	searchFilterGlobal.mod_corsi_tipo  = "";
	var filterArray = new Array();
	function preselezionaEsercizio() {
		showBiscuit();
		$("#mod_esercizi_id").val(<?php echo $mod_esercizio_id; ?>);
		$("#mod_esercizi_id").trigger("change");
		hideBiscuit();
	}


	$('select#mod_corsi_id').on('change', function(e){
		if(this.value != ""){
			searchFilterGlobal.mod_corsi_id = this.value;
		} else {
			delete searchFilterGlobal.mod_corsi_id;
		}
		
	});	


	function initDataTable() {
		var apiFilterDataTable = null
		//var filterArray = new Array();
		$(document).ready(function() {
			var columnNrAction = 0;
			$.fn.dataTableExt.oApi.fnPagingInfo = function(oSettings) {
				return {
					"iStart": oSettings._iDisplayStart,
					"iEnd": oSettings.fnDisplayEnd(),
					"iLength": oSettings._iDisplayLength,
					"iTotal": oSettings.fnRecordsTotal(),
					"iFilteredTotal": oSettings.fnRecordsDisplay(),
					"iPage": Math.ceil(oSettings._iDisplayStart / oSettings._iDisplayLength),
					"iTotalPages": Math.ceil(oSettings.fnRecordsDisplay() / oSettings._iDisplayLength)
				};
			};

			$('#mytable thead tr').clone(true).appendTo('#mytable thead');
			$('#mytable thead tr:eq(1) th').each(function(i) {
				columnNrAction++;
			});


			data_table = $('#mytable thead tr:eq(1) th').each(function(i) {
				if (i > 0) {
					if (i < (columnNrAction - 1)) {
						var title = $(this).text();
						switch (columnArray[i].type) {
							case 'select':
								var strSelect = '';
								strSelect = '<SELECT  style="background-color:#FFF;border:1px solid #999;padding:6px" class=\'mysearch_filter\'  id="' + columnArray[i].name +
									'" name="' + columnArray[i].name + '" type_field ="' + columnArray[i].type +
									'"  title="Seleziona..." >';
								for (var key in columnArray[i].items) {
									strSelect += '<OPTION VALUE="' + columnArray[i].items[key] + '">' +
										columnArray[i].items[key] + '</OPTION>';
								}
								strSelect += '</SELECT>';
								$(this).html(strSelect);
							break;

							case 'select_ajax':
								var strSelect = '';
								strSelect = '<SELECT class=\'mysearch_filter_combo_ajax\' id="' + columnArray[i].name +
									'" name="' + columnArray[i].name + '" type_field ="' + columnArray[i].type +
									'"  multiple data-live-search="true"  data-actions-box="true" title="Seleziona..." >';
								strSelect += '</SELECT>';
								$(this).html(strSelect);
								break;


							case 'datalist_ajax':
								var strSelect = '<input autofocus="" class="mysearch_filter form-control" list="' + columnArray[i].name + '_datalist" ' +
									' name="' + columnArray[i].name + '" id="' + columnArray[i].name + '" onchange="apiFilterDataTable.search(document.getElementById(\'' + columnArray[i].name + '\').value).draw();">';
								strSelect += '<DATALIST  id="' + columnArray[i].name +
									'_datalist" name="' + columnArray[i].name + '_datalist" + '
								' type_field ="' + columnArray[i].type +
									'" >';
								strSelect += '</DATALIST>';
								$(this).html(strSelect);
								break;

							case 'text':
								$(this).html(
									'<input type="text" placeholder="Cerca..." class=\'mysearch_filter form-control\' id="' +
									columnArray[i].name + '" name="' + columnArray[i].name +
									'" / style="width:100%"  style="border-radius:3px" autocomplete="off" type_field ="' +
									columnArray[i].type + '" >');
								break;


							case 'date':
								$(this).html(
									'<input type="text" placeholder="Cerca..." class=\'mysearch_filter datemask form-control\' id="' +
									columnArray[i].name + '" name="' + columnArray[i].name +
									'"style="width:100%" autocomplete="off"  type_field ="' + columnArray[i]
									.type + '" />');
								break;


							case 'datetime':
								$(this).html(
									'<input type="text" placeholder="Cerca..." class=\'mysearch_filter datetimemask form-control\' id="' +
									columnArray[i].name + '" name="' + columnArray[i].name +
									'"style="width:100%" autocomplete="off"  type_field ="' + columnArray[i]
									.type + '" />');
								break;


							case 'number':
								$(this).html(
									'<input type="number" placeholder="Cerca..." class=\'mysearch_filter form-control\' id="' +
									columnArray[i].name + '" name="' + columnArray[i].name +
									'" style="width:100%" autocomplete="off"  type_field ="' + columnArray[i]
									.type + '" />');
								break;

							case 'blob':
								$(this).html('');
								break;
						}

					} else {
						$(this).html('');
					}

				}

			});


			var mydata_table = $("#mytable").DataTable({
				initComplete: function() {
					var api = this.api();
					apiFilterDataTable = this.api();;
					$('#mytable_filter input')
						.off('.DT')
						.on('keyup.DT', function(e) {
							if (e.keyCode == 13) {
								api.search(this.value).draw();
							}

						});

					/*$('select')*/
					$('.mysearch_filter')
						.off('.DT')
						.on('change', function(e) {

							var searchFilter = document.getElementsByClassName("mysearch_filter");

							for (var i = 0; i < searchFilter.length; i++) {
								var valuePOST = searchFilter[i].value;
								var element = document.getElementById(searchFilter[i].id);
								var typeField = 'text';
								if (element.classList.contains('datepicker') == true) {
									typeField = 'date'
								}
								if (element.classList.contains('datetimepicker') == true) {
									typeField = 'datetime'
								}

								if ($('#' + searchFilter[i].name).is('select') == true) {
									valuePOST = $('select#' + searchFilter[i].name).val();
								}
								filterArray[i] = {
									'field': searchFilter[i].name,
									'value': valuePOST,
									'type_field': typeField
								};
							}
							//alert("2:" + this.value);;
							api.search(this.value).draw();
							$('input[type=search]').val("");
						});

					$('.mysearch_filter_combo_ajax')
						.off('.DT')
						.on('change', function(e) {

							var searchFilter = document.getElementsByClassName("mysearch_filter_combo_ajax");

							for (var i = 0; i < searchFilter.length; i++) {
								var valuePOST = searchFilter[i].value;
								var element = document.getElementById(searchFilter[i].id);
								var typeField = 'select';


								if ($('#' + searchFilter[i].name).is('select') == true) {
									valuePOST = $('select#' + searchFilter[i].name).val();
								}

								if (typeof searchFilter[i].name !== 'undefined') {
									filterArray[i] = {
										'field': searchFilter[i].name,
										'value': valuePOST,
										'type_field': typeField
									};
								}

							}
							//alert("2:" + this.value);;

							try {
								api.search(this.value).draw();
							} catch (err) {
								console.log("Exception Datatable:" + err);
							}

							$('input[type=search]').val("");

							//return false;
						});

					$('.mysearch_filter')
						.off('.DT')
						.on('keydown.DT', function(e) {
							var searchFilter = document.getElementsByClassName("mysearch_filter");
							for (var i = 0; i < searchFilter.length; i++) {
								var element = document.getElementById(searchFilter[i].id);
								var valuePOST = searchFilter[i].value;
								var typeField = 'text';
								if (element.classList.contains('datepicker') == true) {
									typeField = 'date'
								}
								if (element.classList.contains('datetimepicker') == true) {
									typeField = 'datetime'
								}
								if ($('#' + searchFilter[i].name).is('select') == true) {
									valuePOST = $('select#' + searchFilter[i].name).val();
								}
								filterArray[i] = {
									'field': searchFilter[i].name,
									'value': valuePOST,
									'type_field': typeField
								};
								if (e.keyCode == 13) {
									searchFilterGlobal[searchFilter[i].name] = {
										'field': searchFilter[i].name,
										'value': valuePOST,
										'type_field': typeField
									};	
								}							
							}
							if (e.keyCode == 13) {
								//alert("3:" + this.value);
								api.search(this.value).draw();
								$('input[type=search]').val("");
 
							}


						});
					$('.datepicker').datepicker({
						locale: 'it',
						format: 'DD/MM/YYYY'
					});
					$('.datetimepicker').datetimepicker({
						locale: 'it',
						format: 'DD/MM/YYYY'
					});
					$('.datetimepicker').on('dp.change', function() {
						e = $.Event('keydown');
						e.keyCode = 13;
						$('#' + this.id).trigger(e);
					});

					$("input[type=search]").prop("disabled", true);

					var cmb_corsi_tipo = document.getElementById("mod_corsi_tipo");
						cmb_corsi_tipo.addEventListener("change", function() {
						searchFilterGlobal.mod_corsi_tipo = this.value
					});							
				},
				oLanguage: {
					sSearch: "Ricerca su tutte le colonne:",
					emptyTable: "Nessun record",
					sProcessing: "Caricamento... <IMG SRC='" + baseURL + "assets/images/loading3.gif' />",
					paginate: {
						previous: " << ",
						next: " >> "
					},
				},
				"lengthChange": false,
				processing: true,
				serverSide: true,
				bPaginate:false,
				ordering: objAjaxConfig.datatable.ordering,
				ajax: {
					"url": objAjaxConfig.mod_name + "/json",
					"type": "POST",
					"data": function(data) {
						data.searchFilter = filterArray;
						console.log(filterArray);
					},
					complete: function(data) {
						console.log(data['responseJSON']);
						if (firstInitDatatable == true) {
							preselezionaEsercizio();
							firstInitDatatable = false;
						}
						return false;
					}
				},
				columns: columnGrid,
				order: [
					[1, 'desc']
				],
				rowCallback: function(row, data, iDisplayIndex) {
					var info = this.fnPagingInfo();
					var page = info.iPage;
					var length = info.iLength;
					var index = page * length + (iDisplayIndex + 1);
				},
				orderCellsTop: true,
				fixedHeader: false,
				autoWidth: false,
				deferRender: true,
			});

			//mydata_table.columns.adjust();

			$('.mysearch_filter_combo').selectpicker({
				includeSelectAllOption: true,
				deselectAllText: 'Deselez. tutti',
				selectAllText: 'Seleziona tutti',
				noneResultsText: 'Nessun risultato per {0}',
				dropupAuto: false,
				refresh: true
			});


			$('.mysearch_filter_combo_ajax').selectpicker({
				includeSelectAllOption: true,
				deselectAllText: 'Deselez. tutti',
				selectAllText: 'Seleziona tutti',
				noneResultsText: 'Nessun risultato per {0}',
				dropupAuto: false,
				refresh: true,
				noneSelectedText: 'Nessun elemento selezionato'
			});



		});
		var tabID = 1;
		var button = '<button class=\'close\' type=\'button\' title=\'Remove this page\'>×</button>';
		$('#tab-menu').on('click', '.close', function() {
			var tabID = $(this).parents('a').attr('href');
			showBiscuit();
			$(this).parents('li').remove();
			$(tabID).remove();

			//DISABILITO TEMPORANEAMENTE  LA POSSIBILITA DI PIU FORM APERTI
			$('#mytable').DataTable().ajax.reload(function(json) {
				hideBiscuit();
			});
			$('.btn.btn-sm.btn-info').removeClass('disabled');
			$('.btn.btn-sm.btn-danger.deleteUser').removeClass('disabled');
			$('.btn.btn-primary').removeClass('disabled');
			document.getElementById('href_elenco').setAttribute('data-toggle', 'tab');

			//display first tab
			var tabFirst = $('#tab-menu a:first');
			resetTab();
			tabFirst.tab('show');
		});


		var editHandler = function() {
			var t = $(this);
			t.css('visibility', 'hidden');
			$(this).prev().attr('contenteditable', 'true').focusout(function() {
				$(this).removeAttr('contenteditable').off('focusout');
				t.css('visibility', 'visible');
			});
		};
		$('.edit').click(editHandler);




	}
	initDataTable();
 

	function launchReport() {
		var url_print = "";
		var esercizio;
		var corso;
		var anagrafica;
		var mod_corsi_nome;
		var Data_Iscrizione;
		var Sett;
		var Ott;
		var Nov;
		var Dic;
		var Gen;
		var Feb;
		var Mar;
		var Apr;
		var Mag;
		var Giu;
		var Lug;
		var Ago;
		var listaCorsi;

		esercizio = "NULL";
		if((typeof searchFilterGlobal.mod_esercizi_id !== "undefined")){
			if(searchFilterGlobal.mod_esercizi_id.value != ""){
			//if(document.getElementById('mod_esercizi_id').value != ""){	
				esercizio = document.getElementById('mod_esercizi_id').value;
			}
		}

		corso = "NULL";
		if((typeof searchFilterGlobal.mod_corsi_id !== "undefined")){
			if(searchFilterGlobal.mod_corsi_id.value != ""){
			//if(document.getElementById('mod_esercizi_id').value != ""){	
				corso = document.getElementById('mod_corsi_id').value;
			}
		}


		anagrafica = "NULL";
		if((typeof searchFilterGlobal.Anagrafica !== "undefined")){
			if(searchFilterGlobal.Anagrafica.value != ""){
			//if(document.getElementById('Anagrafica').value != ""){
				anagrafica = document.getElementById('Anagrafica').value;
			}	
		}

		mod_corsi_nome = "NULL";
		if((typeof searchFilterGlobal.mod_corsi_nome !== "undefined")){
			if(searchFilterGlobal.mod_corsi_nome.value != ""){
			//if(document.getElementById('mod_corsi_nome').value != ""){
				mod_corsi_nome = document.getElementById('mod_corsi_nome').value;
			}
		}

		mod_corsi_tipo = "NULL";
		if((typeof searchFilterGlobal.mod_corsi_tipo !== "undefined")){
			if(searchFilterGlobal.mod_corsi_tipo.value != ""){
			//if(document.getElementById('mod_corsi_nome').value != ""){
				mod_corsi_tipo = document.getElementById('mod_corsi_tipo').value;
			}
		}
		
		
		Data_Iscrizione = "NULL";
		if((typeof searchFilterGlobal.Data_Iscrizione !== "undefined")){
			if(searchFilterGlobal.Data_Iscrizione.value != ""){
			//if(document.getElementById('Data_Iscrizione').value != ""){
				Data_Iscrizione  = document.getElementById('Data_Iscrizione').value;
			}
		}

		Sett = "NULL";
		if((typeof searchFilterGlobal.Set !== "undefined")){
			if(searchFilterGlobal.Set.value != ""){
			//if(document.getElementById('Set').value != ""){
				Sett  = document.getElementById('Set').value;
			}
		}

		Ott = "NULL";
		if((typeof searchFilterGlobal.Ott !== "undefined")){
			if(searchFilterGlobal.Ott.value != ""){
			//if(document.getElementById('Ott').value != ""){
				Ott  = document.getElementById('Ott').value;
			}
		}


		Nov = "NULL";
		if((typeof searchFilterGlobal.Nov !== "undefined")){
			if(searchFilterGlobal.Nov.value != ""){
			//if(document.getElementById('Nov').value != ""){		
				Nov  = document.getElementById('Nov').value;
			}
		}


		Dic = "NULL";
		if((typeof searchFilterGlobal.Dic !== "undefined")){
			if(searchFilterGlobal.Dic.value != ""){
			//if(document.getElementById('Dic').value != ""){
				Dic  = document.getElementById('Dic').value;
			}	
		}


		Gen = "NULL";
		if((typeof searchFilterGlobal.Gen !== "undefined")){
			if(searchFilterGlobal.Gen.value != ""){
			//if(document.getElementById('Gen').value != ""){
				Gen  = document.getElementById('Gen').value;
			}
		}


		Feb = "NULL";
		if((typeof searchFilterGlobal.Feb !== "undefined")){
			if(searchFilterGlobal.Feb.value != ""){
			//if(document.getElementById('Feb').value != ""){
				Feb  = document.getElementById('Feb').value;
			}
		}


		Mar = "NULL";
		if((typeof searchFilterGlobal.Mar !== "undefined")){
			if(searchFilterGlobal.Mar.value != ""){
			//if(document.getElementById('Mar').value != ""){
				Mar  = document.getElementById('Mar').value;
			}	
		}


		Apr = "NULL";
		if((typeof searchFilterGlobal.Apr !== "undefined")){
			if(searchFilterGlobal.Apr.value != ""){
			//if(document.getElementById('Apr').value != ""){
				Apr  = document.getElementById('Apr').value;
			}
		}


		Mag = "NULL";
		if((typeof searchFilterGlobal.Mag !== "undefined")){
			if(searchFilterGlobal.Mag.value != ""){
			//if(document.getElementById('Mag').value != ""){
				Mag  = document.getElementById('Mag').value;
			}	
		}


		Giu = "NULL";
		if((typeof searchFilterGlobal.Giu !== "undefined")){
			if(searchFilterGlobal.Giu.value != ""){
			//if(document.getElementById('Giu').value != ""){
				Giu  = document.getElementById('Giu').value;
			}
		}


		Lug = "NULL";		
		if((typeof searchFilterGlobal.Lug !== "undefined")){
			if(searchFilterGlobal.Lug.value != ""){
			//if(document.getElementById('Lug').value != ""){
				Lug  = document.getElementById('Lug').value;
			}
		}


		Ago = "NULL";	
		if((typeof searchFilterGlobal.Ago !== "undefined")){
			if(searchFilterGlobal.Ago.value != ""){
			//if(document.getElementById('Ago').value != ""){
				Ago  = document.getElementById('Ago').value;
			}							
		}

		listaCorsi = "NULL";
		if (corso != "NULL") {
			listaCorsi = $('select#mod_corsi_id').val().toString();
		}

	

		url_print = baseURL + "mod_report_pagamenti_mensili_corso/stampa_rep/" + esercizio + "/" + listaCorsi;
		url_print += "/" + anagrafica + "/" + mod_corsi_nome + "/" + Data_Iscrizione;
		url_print += "/" + Sett  + "/" + Ott + "/" + Nov + "/" + Dic + "/" + Gen + "/" + Feb;
		url_print += "/" + Mar  + "/"  + Apr + "/" + Mag + "/" + Giu + "/" + Lug + "/" + Ago + "/" + mod_corsi_tipo;

		window.open(url_print, '_blank');

	}
</script>