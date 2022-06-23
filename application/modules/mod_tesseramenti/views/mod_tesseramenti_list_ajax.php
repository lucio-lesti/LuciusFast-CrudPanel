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
			<i class="fa fa-cubes"></i> Tesseramenti
			<?php
			if ($perm_write == 'Y') {
			?>
				<a class="btn btn-primary" onclick="createAjax('mod_tesseramenti')">
					<i class="fa fa-plus"></i> Nuovo
				</a>
			<?php
			}
			?>
			<?php
			if (($perm_delete == 'Y') && ($isAdmin == TRUE) && ($mod_type == 'crud')) {
			?>
				<button class="btn btn-sm btn-danger deleteUser" disabled="disabled" id="btDeleteMass" name="btDeleteMass" onclick='deleteMassiveEntry("entry_list", "check_id", "mod_tesseramenti","deleteMassive")'>
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
									echo " class = 'mysearch_filter_combo_ajax' data-live-search='true' ";
								} else {
									echo " class = 'mysearch_filter' ";
								}

								if (isset($v['filterSlave'])) {
									echo " onchange= 'filter_slave_population_function(\"" . $module . "\", \"" . $v['filterSlave']['filter_slave_population_function'] . "\", 
		this.value,\"" . $v['filterSlave']['filter_slave_id'] . "\", true)' ";
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

						<table class="table table-bordered table-striped" id="mytable" style='width: 100%;'>
							<thead>
								<tr>
									<th><input type="checkbox" id="check_master" name="check_master" onchange="selezionaDeselezionaTutti('check_master','check_id','btDeleteMass')" /></th>
									<th>Anagrafica</th>
									<th>Tessera Interna</th>
									<th>Tessera Associativa</th>
									<th>Data Tesseramento</th>
									<th></th>
								</tr>
							</thead>

						</table>

						<div class="row">
							<div class="col-md-6">
								<?php echo anchor(site_url('mod_tesseramenti/excel/mod_tesseramenti'), 'Esporta Tutto in  Excel', 'class="btn btn-primary"'); ?><br>(*)<b>Note di esportazione:</b> L'esportazione E' TOTALE e riguarda anche i records non selezionati</u>
								<br>(**)<b>Note di importazione:</b> Prima di re-importare il file Excel <u>ELIMINARE LA PRIMA COLONNA</u>

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
	var data_table = null;
	var firstInitDatatable = true;
	var tabID = 1;

	function preselezionaEsercizio() {
		showBiscuit();
		$("#mod_esercizi_nome").val(<?php echo $mod_esercizio_id; ?>);
		$("#mod_esercizi_nome").trigger("change");
		hideBiscuit();
	}

	function initDataTable() {
		var apiFilterDataTable = null
		var filterArray = new Array();

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
								strSelect = '<SELECT class=\'mysearch_filter_combo\' id="' + columnArray[i].name +
									'[]" name="' + columnArray[i].name + '[]" type_field ="' + columnArray[i].type +
									'"  multiple data-live-search="true"  data-actions-box="true" title="Seleziona..." >';
								//strSelect += '<OPTION VALUE="">Cerca...</OPTION>';
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


			var mydata_table = $("#mytable").dataTable({
				initComplete: function() {
					var api = this.api();
					apiFilterDataTable = this.api();;
					$('#mytable_filter input')
						.off('.DT')
						.on('keyup.DT', function(e) {
							if (e.keyCode == 13) {
								api.search(this.value).draw();
							}
							$('input[type=search]').val("");
						});

					/*$('select')*/
					$('.mysearch_filter')
						.off('.DT')
						.on('change', function(e) {
							var searchFilter = document.getElementsByClassName("mysearch_filter");
							for (var i = 0; i < searchFilter.length; i++) {
								var element = document.getElementById(searchFilter[i].id);
								var typeField = 'text';
								if (element.classList.contains('datepicker') == true) {
									typeField = 'date'
								}
								if (element.classList.contains('datetimepicker') == true) {
									typeField = 'datetime'
								}
								filterArray[i] = {
									'field': searchFilter[i].name,
									'value': searchFilter[i].value,
									'type_field': typeField
								};
							}
							//alert("2:" + this.value);;
							api.search(this.value).draw();
							$('input[type=search]').val("");
						});



					$('.mysearch_filter')
						.off('.DT')
						.on('keydown.DT', function(e) {
							var searchFilter = document.getElementsByClassName("mysearch_filter");
							for (var i = 0; i < searchFilter.length; i++) {
								var element = document.getElementById(searchFilter[i].id);
								var typeField = 'text';
								if (element.classList.contains('datepicker') == true) {
									typeField = 'date'
								}
								if (element.classList.contains('datetimepicker') == true) {
									typeField = 'datetime'
								}
								filterArray[i] = {
									'field': searchFilter[i].name,
									'value': searchFilter[i].value,
									'type_field': typeField
								};
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
				ordering: objAjaxConfig.datatable.ordering,
				ajax: {
					"url": objAjaxConfig.mod_name + "/json",
					"type": "POST",
					"data": function(data) {
						data.searchFilter = filterArray;

					},
					complete: function(data) {
						console.log(data['responseJSON']);
						if (firstInitDatatable == true) {
							preselezionaEsercizio();
							firstInitDatatable = false;
						}

						$('input[type=search]').val("");
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
				refresh: true
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


	objAjaxConfig.mod_name = "mod_tesseramenti";
	objAjaxConfig.mod_title = "Tesseramenti";

	var columnArray = [];
	columnArray[1] = {
		type: "text",
		name: "nome_anagrafica"
	};
	columnArray[2] = {
		type: "text",
		name: "mod_tesseramenti_tessera_interna"
	};
	columnArray[3] = {
		type: "text",
		name: "_mod_magazzino_tessere_lista_tessere_codice_tessera"
	};
	columnArray[4] = {
		type: "date",
		name: "mod_tesseramenti_data_tesseramento"
	};
	var columnGrid = [{
			"data": "ids",
			"orderable": false
		},
		{
			"data": "nome_anagrafica"
		},
		{
			"data": "mod_tesseramenti_tessera_interna"
		},		
		{
			"data": "_mod_magazzino_tessere_lista_tessere_codice_tessera"
		},
		{
			"data": "mod_tesseramenti_data_tesseramento"
		},
		{
			"data": "action",
			"orderable": false,
			"className": "text-center"
		}
	];
	<?php
	if (isset($_REQUEST['id'])) {
		echo "editAjax('mod_tesseramenti'," . $_REQUEST['id'] . ");";
	}
	?>
</script>