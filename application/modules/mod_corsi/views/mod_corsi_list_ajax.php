<!-- INIZIO-->
<div id="top_form"></div>
<div class="content-wrapper">
	<!-- Content Header (Page header) -->
	<section class="content-header">
		<div id="warnCpCorsi" style="display: none; font-size: 18px;" class="alert alert-warning alert-dismissable" onclick="hideMsg('warnCpCorsi')">ATTENZIONE, NON SONO STATI COPIATI ALCUNI CORSI PERCHE GIA PRE-ESISTENTI.
		</div>
		<div id="okCpCorsi" style="display: none; font-size: 18px;" class="alert alert-success alert-dismissable" onclick="hideMsg('okCpCorsi')">CORSI COPIATI CORRETTAMENTE
		</div>
		<div id="koCpCorsi" style="display: none; font-size: 18px;" class="alert alert-error alert-dismissable" onclick="hideMsg('koCpCorsi')">ERRORE COPIA CORSI
		</div>
		<?php
		//print'<pre>';print_r($_REQUEST);
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

			<?php
			if ($_SESSION['fast_ins_corsi'] == 'Y') {
				echo "<i class=\"fa fa-rocket\"></i> Corsi - Inserimento Veloce";
			} else {
				echo "<i class=\"fa fa-cubes\"></i> Corsi";
			}
			?>
			<?php
			if (($perm_write == 'Y') && ($isAdmin == TRUE) && (($_SESSION['fast_ins_corsi'] == 'N'))) {
			?>
				<button disabled="disabled" class="btn btn-info" id="bt_copia_corsi" onclick="copiaCorsi()">
					<i class="fa fa-copy"></i> Copia Corsi
				</button>
				<a class="btn btn-primary" onclick="createAjax('mod_corsi')">
					<i class="fa fa-plus"></i> Nuovo
				</a>
			<?php
			}
			?>
			<?php
			if (($perm_delete == 'Y') && ($isAdmin == TRUE) && ($mod_type == 'crud')  && (($_SESSION['fast_ins_corsi'] == 'N'))) {
			?>
				<button class="btn btn-sm btn-danger deleteUser" disabled="disabled" id="btDeleteMass" name="btDeleteMass" onclick='deleteMassiveEntry("entry_list", "check_id", "mod_corsi","deleteMassive")'>
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
									echo " class = 'mysearch_filter_combo_ajax' data-live-search=\"true\" ";
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
									<th>Nome Corso</th>
									<th>Data Da</th>
									<th>Data A</th>
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
	<?php 
		if ($_SESSION['fast_ins_corsi'] == 'Y') {
			echo"\nvar fast_ins_corsi_js = 'Y';";
		} else {
			echo"\nvar fast_ins_corsi_js = 'N';";
		}	
	?>	
	objAjaxConfig.mod_name = "mod_corsi";

	objAjaxConfig.mod_title = "Corsi";

	var columnArray = [];
	columnArray[1] = {
		type: "text",
		name: "mod_corsi_nome"
	};
	columnArray[2] = {
		type: "date",
		name: "mod_corsi_data_da"
	};
	columnArray[3] = {
		type: "date",
		name: "mod_corsi_data_a"
	};
	var columnGrid = [{
			"data": "ids",
			"orderable": false
		},
		{
			"data": "mod_corsi_nome"
		},
		{
			"data": "mod_corsi_data_da"
		},
		{
			"data": "mod_corsi_data_a"
		},
		{
			"data": "action",
			"orderable": false,
			"className": "text-center"
		}
	];



	
	function editAjax(mod_name, recordID) {
		$('.btn.btn-sm.btn-info').addClass('disabled');
		$('.btn.btn-primary').addClass('disabled');
		$('.btn.btn-sm.btn-danger.deleteUser').addClass('disabled');
		var elementExists = document.getElementById('tab_li_ed' + recordID);
		if (!elementExists) {
			$('#tab-menu').append($("<li class='edit_tab'  id='tab_li_ed" + recordID + "'><a href='#tab" + recordID +
				"' role='tab' data-toggle='tab'> <i class='fa fa-edit'></i> <span id='tab_lbl_ed" + recordID +
				"'>Caricamento...</span> <i class='fa fa-cubes'></i> <button class='close' type='button' title='Remove this page'> × </button></a></li>"
			));
		}

		$.ajax({
			type: 'GET',
			url: mod_name + '/updateAjax/' + recordID,
			data: {
				recordID: recordID,
				fast_ins_corsi:fast_ins_corsi_js
			},
			success: function(response) {
				if (!elementExists) {
					$('.tab-content').append($("<div class='tab-pane fade' id='tab" + recordID + "'>" +
						response + "</div>"));
				}

				document.getElementById('tab_lbl_ed' + recordID).innerHTML = 'Modifica [#ID:' + recordID + '] ';
				document.getElementById('tab_li_ed' + recordID).style.cursor = 'pointer';
				document.getElementById('tab_li_ed' + recordID).style.pointerEvents = 'auto';
				document.getElementById('tab_li_ed' + recordID).classList.remove('disabled');

				//DISABILITO TEMPORANEAMENTE  LA POSSIBILITA DI PIU FORM APERTI
				document.getElementById('href_elenco').removeAttribute('data-toggle');
				/*
				$('.btn.btn-sm.btn-info').removeClass('disabled');
				$('.btn.btn-sm.btn-danger.deleteUser').removeClass('disabled');
				$('.btn.btn-primary').removeClass('disabled');
				*/

				$('.nav-tabs a[href="#tab' + recordID + '"]').tab('show');
				anchor = document.createElement('a');
				anchor.setAttribute('href', '#top_form');
				anchor.setAttribute('onclick', 'scrollaTop()');
				anchor.setAttribute('id', 'href_top_form');
				anchor.click();
				anchor.remove();
			}
		});

	}

	<?php
	if (isset($_REQUEST['id'])) {
		echo "editAjax('mod_corsi'," . $_REQUEST['id'] . ");";
	}
	?>
</script>


<script>
	var data_table = null;
	var firstInitDatatable = true;
	var tabID = 1;

	function preselezionaEsercizio() {
		showBiscuit();
		$("#mod_esercizi_id").val(<?php echo $mod_esercizio_id; ?>);
		$("#mod_esercizi_id").trigger("change");
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
						data.fast_ins_corsi = fast_ins_corsi_js;

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


	function copiaCorsi() {
		var frmString = "";
		var checkboxes = document.getElementsByName("check_id");
		var entry_list = "";

		var count = 0;
		for (var i = 0, n = checkboxes.length; i < n; i++) {
			if (checkboxes[i].checked == true) {
				if (count > 0) {
					entry_list += "," + checkboxes[i].value;
				} else {
					entry_list += checkboxes[i].value;
				}
				count++;
			}
		}


		frmString = "<form id='frm_copy_course' method='GET'>";
		frmString += "<div id=\"dlg_err\" style=\"display:none;font-size:18px\" class=\"alert alert-error alert-dismissable\" onclick=\"hideMsg('dlg_err')\"></div>";
		frmString += "<input type='hidden' id='entry_list_cp_course' name='entry_list_cp_course' />";
		frmString += "<b>Seleziona Esercizio</b><SELECT id='mod_esercizi_id_copy' name='mod_esercizi_id_copy' class='form-control'";
		frmString += " onchange='filter_slave_population_function(\"mod_corsi\", \"populateAffiliazioni\",";
		frmString += " this.value,\"fk_affiliazione_copy\")'>";
		frmString += " <OPTION VALUE=''>Seleziona...</OPTION>";

		<?php

		if (isset($comboGridFilter)) {
			foreach ($comboGridFilter as $filterName => $v) {
				if ($filterName == 'mod_esercizi_id') {
					foreach ($v['itemsList'] as $kOpt => $vOpt) {
						echo "\nfrmString +='<option value=" . $vOpt['id'] . ">" . $vOpt['nome'] . "</option>';";
					}
				}
			}
		}

		?>

		frmString += "</select>";

		frmString += "<br><b>Seleziona Affiliazione di Destinazione</b>";
		frmString += "<br><select id=\"fk_affiliazione_copy\" name=\"fk_affiliazione_copy\" ";
		frmString += " class=\"form-control\" style=\"padding:6px 12px;font-size: 14px;\"><option value=\"\">Seleziona...</option></select>";

		frmString += "</form>";


		document.getElementById('modal_body_corsi_form').innerHTML = frmString;


		document.getElementById('dlg_err').style.display = "none";

		$("#modal-corsi").modal();

		document.getElementById('entry_list_cp_course').value = entry_list;
		//$('#fk_affiliazione option').clone().appendTo('#mod_affiliaz_id_copy');

	}


	function validaFrmCpCorsi() {
		var form = $('#frm_copy_course')[0];
		var form_data = new FormData(form);
		if (document.getElementById('fk_affiliazione_copy').value == "") {
			document.getElementById('dlg_err').style.display = "block";
			document.getElementById('dlg_err').innerHTML = "SELEZIONARE AFFILIAZIONE";
			return false;
		} else {
			showBiscuit();
			document.getElementById('dlg_err').style.display = "none";
			$.ajax({
				url: baseURL + '/mod_corsi' + '/cp_corsi',
				type: 'POST',
				dataType: 'json',
				data: form_data,
				processData: false,
				contentType: false,
				success: function(data) {

					switch (data.success) {
						case 'ok':
							document.getElementById('warnCpCorsi').style.display = "none";
							document.getElementById('okCpCorsi').style.display = "block";
							document.getElementById('koCpCorsi').style.display = "none";
							break;

						case 'ko':
							document.getElementById('warnCpCorsi').style.display = "none";
							document.getElementById('okCpCorsi').style.display = "none";
							document.getElementById('koCpCorsi').style.display = "block";
							break;

						case 'warn':
							document.getElementById('warnCpCorsi').style.display = "block";
							document.getElementById('okCpCorsi').style.display = "none";
							document.getElementById('koCpCorsi').style.display = "none";
							break;
					}

					hideBiscuit();
					$("#modal-corsi").modal('hide');
				},
				error: function(request, error) {
					hideBiscuit();
					alert("Request: " + JSON.stringify(request));
					$("#modal-corsi").modal('hide');
				}
			});

		}

	}

	/*
	$.getScript('<?php echo base_url(); ?>assets/js/datatable_ajax.config.js')
	  .done(function( script, textStatus ) {
	    console.log( textStatus );
		preselezionaEsercizio();
	});
	*/
</script>



<div class="modal" tabindex="-1" role="dialog" id='modal-corsi'>
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id='title_info_corsi'><i class="fa fa-copy"></i> Copia Corsi</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body" id="modal_body_corsi_form">

			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-info" id="btok-corsi-confirm" onclick="validaFrmCpCorsi()">Ok</button>
				<button type="button" class="btn btn-secondary" id="btcancel-corsi-confirm" data-dismiss="modal">Chiudi </button>
			</div>
		</div>
	</div>
</div>