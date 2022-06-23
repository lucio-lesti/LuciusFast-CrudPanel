   <?php
	if ((isset($id)) && ($id != "")) {
		$id_main_content = $id;
	} else {
		$id_main_content = rand(0, 1000000);
	}
	$id_button = $id;
	?>
   <div <?php if ($winForm == 'TRUE') {
			echo " class='main_content_ajax_form' ";
		} ?> id="main_content_ajax_form_<?php echo $id_main_content; ?>">
   	<!-- Content Header (Page header) -->
   	<div class="col-md-8">
   		<h3>
   			<i class="fa fa-cubes"></i>
   			<a>
   				<u> Magazzino Tessere</u>
   			</a>
   			<?php
				if ((isset($id)) && ($id != "")) {
				?>
   				<b style='font-size:22px'> >> </b><b style='font-size:22px'>Modifica ID:<?= $id ?></b>
   			<?php } else { ?>
   				<b style='font-size:22px'> >> </b><b style='font-size:22px'>Nuovo</b>
   			<?php } ?>

   		</h3>
   		<?php
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

   		<div class="row">
   			<div class="col-md-12">
   				<?php echo validation_errors('<div class="alert alert-danger alert-dismissable">', ' <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button></div>'); ?>
   			</div>
   		</div>
   	</div>
   	<div class="row">
   		<!-- left column -->
   		<div class="col-md-12">
   			<!-- general form elements -->
   			<div class="box box-primary">
   				<!-- /.box-header -->
   				<!-- form start -->

   				<div class="box-body">
   					<?php $this->load->helper("form"); ?>
   					<?php
						//SE GIA SALVATO IMPEDISCO IL SUBMIT DEL FORM
						if ($afterSave == TRUE) {
							$preventDefault = 'onsubmit="event.preventDefault();return false"';
						}
						?>
   					<form action="<?php echo $action; ?>" method="post" name="<?php echo $frm_module_name . '_' . $id; ?>" id="<?php echo $frm_module_name . '_' . $id; ?>" <?php $preventDefault; ?>\>
   						<div class='col-md-12'>
   							<B STYLE='color:#990000'>(*)</B>Campi obbligatori
   							<br><br>
   						</div>
   						<div class="col-md-4">
   							<div class="form-group">
   								<label for="varchar"><b style="color:#990000">(*)</b>Nome Stock Tessere <?php echo form_error('nome') ?></label>
   								<div class="input-group">
   									<div class="input-group-addon"><i class="fa fa-text-height"></i></div>
   									<input type="text" class="form-control" maxlength='255' name="nome" id="nome" placeholder="Nome Stock Tessere" autocomplete="off" value="<?php echo $nome; ?>" />
   								</div>
   							</div>
   						</div>
   						<div class="col-md-4">
   							<div class="form-group">
   								<?php
									$fk_affiliazione_label = NULL;
									foreach ($fk_affiliazione_refval as $key => $value) {
										if ($value['id'] == $fk_affiliazione) {
											$fk_affiliazione_label = $value['nome'];
										}
									}
									?>
   								<label for="fk_affiliazione"><b style="color:#990000">(*)</b>Affiliazione <?php echo form_error('fk_affiliazione') ?></label>
   								<a style="cursor:pointer" onclick="winFormCombo('mod_affiliazioni','frm_mod_affiliazioni_','fk_affiliazione_datalist','id','nome','Nuovo Affiliazione')"><i class="fa fa-ellipsis-h" style="padding: 2px 4px;border:1px solid #ccc;"></i></a>
   								<a style="cursor:pointer" onclick="pulisciCampo('fk_affiliazione_datalist_inp')"><i class="fa fa-repeat" style="padding: 2px 4px;border:1px solid #ccc;"></i></a>


   								<span class="arrow_datalist">

   									<input autofocus class="form-control" autocomplete="off" list='fk_affiliazione_datalist' oninput='onInput("fk_affiliazione_datalist_inp","fk_affiliazione_datalist","fk_affiliazione")' style="width:100%;padding: 6px 12px;font-size:14px;
									border-top-right-radius:0px;border-bottom-right-radius:0px;
									border-top-left-radius:0px;border-bottom-left-radius:0px;
									border:1px solid #ccc" name='fk_affiliazione_datalist_inp' id='fk_affiliazione_datalist_inp' value='<?php echo $fk_affiliazione_label; ?>'></span>

   								<input type="hidden" name='fk_affiliazione' id='fk_affiliazione' value='<?php echo $fk_affiliazione; ?>'>

   								<datalist name='fk_affiliazione_datalist' id='fk_affiliazione_datalist' onselect="alert(this.text)">


   									<OPTION VALUE></OPTION>
   									<?php
										foreach ($fk_affiliazione_refval as $key => $value) {
											if ($value['id'] == $fk_affiliazione) {
												echo "<option data-value='" . $value['id'] . "' SELECTED>" . $value['nome'] . "</option>";
											} else {
												echo "<option data-value='" . $value['id'] . "'>" . $value['nome'] . "</option>";
											}
										}
										?>
   							</div>
   						</div>


   						<div class="col-md-4">
   							<div class="form-group">

   								<label for="fk_ente">Ente </label>


   								<SELECT disabled="disabled" name='fk_ente' id='fk_ente' class="form-control">
   									<OPTION VALUE='<?php echo $fk_ente; ?>'><?php echo $fk_ente_txt; ?></OPTION>
   								</SELECT>

   							</div>
   						</div>

						   
   						<div class="col-md-12">
   							<div class="form-group">
   								<label for="note">Allegati <?php echo form_error("allegati") ?>
   									<br><span style="font-size:12px"><b>File Ammessi:</b><b style="color:#990000"><?php echo implode(",", $extAdmitted); ?></b></span>
   								</label>
   								<input type="file" name="allegati[]" id="allegati[]" class="form-control" multiple />

   								<br>
   								<ul>
   									<?php
										$icon_delete = "<img src='" . base_url() . "assets/images/delete2.png' width='32'/>";

										$count = 0;
										foreach ($allegati as $key => $allegato) {
											$nomeAllegato =  $allegato['allegato'];

											if (($id != '')) {
												echo "<li id='allegato_" . $count . "'><a href='" . base_url() . "mod_magazzino_tessere/scaricaAllegato/mod_magazzino_tessere/" . $id . "/" . $nomeAllegato . "' target='_blank'>" . $allegato['allegato'] . "</a>";
												echo "<a style='cursor:pointer' onclick=\"rimuoviAllegato('mod_magazzino_tessere', '$id','$nomeAllegato','allegato_$count')\"> | " . $icon_delete . "</a></li>";
											} else {
												echo $allegato['allegato'] . "<BR>";
											}

											$count++;
										}
										if (($id == '')) {
											echo "<br><br><b> - Note:</b>Gli allegati possono essere cancellati solo in modifica";
										}
										?>
   								</ul>
   								<br />
   							</div>
   						</div>

   						<div class="col-md-12">
   							<div class="form-group" id="divAjaxMsg_container">
   								<div id="divAjaxMsg" style="display:none;font-size:18px" class="alert alert-success alert-dismissable" onclick="hideMsg('divAjaxMsg')">
   								</div>
   								<div id="divAjaxMsgErr" style="display:none;font-size:18px" class="alert alert-error alert-dismissable" onclick="hideMsg('divAjaxMsgErr')">
   								</div>
   								<div id="dv_upload_tessere"><input type="file" id="file_tessere" name="file_tessere"></div>
   								<p class="help-block">File Ammessi (*.xls,xlsx,csv)</p>
   								<a class="btn btn-primary" style="cursor:pointer" onclick="importExcelData()"><i class='fa fa-floppy-o'> <span style="font-family:Arial, Helvetica, sans-serif">Importa tessere da Excel</span></i></a>
   								<p><br><br></p>
   								<div id="master_details_list" name="master_details_list">
   									<ul class="nav nav-tabs" id="myTab" role="tablist">
   										<?php
											if (isset($master_details_list)) {
												$countTab = 0;
												foreach ($master_details_list as  $key => $master_details) {
													if ($countTab == 0) {
														echo '<li class="nav-item active">
									<a class="nav-link active" id="lnk-' . $master_details['id'] . '" data-toggle="tab" href="#' . $master_details['id'] . '" role="tab" aria-controls="' . $master_details['id'] . '" aria-selected="true" aria-expanded="true">' . $master_details['title'] . '</a>
									</li>';
													} else {
														echo '<li class="nav-item">
									<a class="nav-link" id="lnk-' . $master_details['id'] . '" data-toggle="tab" href="#' . $master_details['id'] . '" role="tab" aria-controls="' . $master_details['id'] . '" aria-selected="true" aria-expanded="true">' . $master_details['title'] . '</a>
									</li>';
													}
													$countTab++;
												}
											}
											?>
   									</ul>
   									<div class="tab-content">
   										<?php
											if (isset($master_details_list)) {
												$countTab = 0;
												foreach ($master_details_list as  $key => $master_details) {
													$active = "active";
													if ($countTab > 0) {
														$active = "";
													}
													echo '<div class="tab-pane ' . $active . '" id="' . $master_details['id'] . '" role="tabpanel" aria-labelledby="' . $master_details['id'] . '-tab">';
													echo  $master_details['function'];
													echo '</div>';
													$countTab++;
												}
											}
											?>

   									</div>
   								</div>
   								<p><br><br><br></p>
   							</div>
   						</div>
   						<input type="hidden" name="id" value="<?php echo $id; ?>" />
   						<div class="row">
   							<div class="col-md-12">
   								<?php
									if ($afterSave == NULL) {
									?>
   									<div class='row'>
   										<div class='col-md-6'>
   											<?php
												if ($winForm == "FALSE") {
												?>
   												<button id='<?php echo $button_id; ?>' type="submit" type="button" class="btn btn-success  button-submit" data-loading-text="Caricamento..."><span class="fa fa-save"></span> SALVA</button>
   											<?php } ?>
   										</div>

   										<div class="col-md-6" align="right">
   										</div>

   									</div>
   							</div>
   						</div>
   					<?php } ?>
   					</form>
   				</div>

   				<!-- form close -->
   			</div>
   		</div>
   	</div>


   	<?php
		if ($type_action == 'create') {
			$ajaxAction = 'mod_magazzino_tessere/create_action';
		} else {
			$ajaxAction = 'mod_magazzino_tessere/update_action';
		}
		?>
   	<script>
   		arrayValidationFields = [];
   		js_custom_operations_list = [];
   		objAjaxConfig.form.mod_name = "<?php echo $frm_module_name; ?>";
   		objAjaxConfig.form.recordID = "<?php echo $id; ?>";
   		objAjaxConfig.form.ajaxAction = "<?php echo $ajaxAction; ?>";
   		objAjaxConfig.form.id_main_content = "<?php echo $id_main_content; ?>";
   		$('.select2-autocomplete').select2();

   		arrayValidationFields['winMasterDetail_mod_magazzino_tessere_lista_tessere'] = [];
   		/*js_custom_operations_list['winMasterDetail_mod_magazzino_tessere_lista_tessere'] = [];
			js_custom_operations_list['winMasterDetail_mod_magazzino_tessere_lista_tessere'].check_date = function(){
				var ret = check_date_greater_then('data_validita_inizio','data_validita_fine');
				if(ret === false){
					document.getElementById('msg_err').style.display = "block";
            		document.getElementById('msg_err').innerHTML = "Date non valide";
            		ev.preventDefault();
				}
			};*/

   		arrayValidationFields['winMasterDetail_mod_magazzino_tessere_lista_tessere']['codice_tessera'] = [];
   		arrayValidationFields['winMasterDetail_mod_magazzino_tessere_lista_tessere']['codice_tessera']['label'] = "Codice Tessera"
   		arrayValidationFields['winMasterDetail_mod_magazzino_tessere_lista_tessere']['codice_tessera']['field_type'] = "varchar"

   		function importExcelData() {
   			showBiscuit();

   			var formData = new FormData($('#<?php echo $frm_module_name . '_' . $id; ?>')[0]);
   			formData.append('file_tessere', $('input[type=file]')[0].files[0]);

   			$.ajax({
   				url: baseURL + "/mod_magazzino_tessere/importExcelData",
   				type: 'post',
   				dataType: 'json',
   				data: formData,
   				contentType: false,
   				processData: false,
   				success: function(jsonData) {
   					console.log(jsonData);
   					if (jsonData.success == 'ko') {
   						document.getElementById('divAjaxMsgErr').style.display = "block";
   						document.getElementById('divAjaxMsgErr').innerHTML = jsonData.msg;
   					} else {
   						document.getElementById('divAjaxMsg').style.display = "block";
   						document.getElementById('divAjaxMsg').innerHTML = jsonData.msg;
   						document.getElementById("master_details_list").innerHTML = "";
   						document.getElementById("master_details_list").innerHTML = jsonData.master_details_list;
   					}

   					jQuery('#file_tessere').remove();
   					document.getElementById('dv_upload_tessere').innerHTML = "<input type=\"file\" id=\"file_tessere\" name=\"file_tessere\">";

   					hideBiscuit();
   				}
   			});

   		}
   	</script>
   	<script src="<?php echo base_url(); ?>assets/js/form_submit_ajax.config.js"></script>
   </div>