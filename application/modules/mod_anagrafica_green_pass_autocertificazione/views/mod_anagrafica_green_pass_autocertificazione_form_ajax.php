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
   				<u> Autocertificazioni Green Pass</u>
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
   								<?php
									$fk_anagrafica_label = NULL;
									foreach ($fk_anagrafica_refval as $key => $value) {
										if ($value['id'] == $fk_anagrafica) {
											$fk_anagrafica_label = $value['nome'];
										}
									}
									?>

								<label for="fk_anagrafica"><b style="color:#990000">(*)</b>Anagrafica <?php echo form_error('fk_anagrafica') ?></label>
   								<!--<a style="cursor:pointer" onclick="winFormCombo('mod_anagrafica','frm_mod_anagrafica_','fk_anagrafica','id','nome','Nuovo Anagrafica')"><i class="fa fa-ellipsis-h" style="padding: 2px 4px;border:1px solid #ccc;"></i></a>-->
   								<a style="cursor:pointer" onclick="pulisciSelect2('fk_anagrafica')"><i class="fa fa-repeat" style="padding: 2px 4px;border:1px solid #ccc;"></i></a>

									<SELECT  
									id="fk_anagrafica" name="fk_anagrafica"
										CLASS='form-control select2-autocomplete' STYLE="width:100%">	
   									<OPTION VALUE></OPTION>
   									<?php
										foreach ($fk_anagrafica_refval as $key => $value) {
											if ($value['id'] == $fk_anagrafica) {
												echo "<option value='" . $value['id'] . "' SELECTED>" . $value['nome'] . "</option>";
											} else {
												echo "<option value='" . $value['id'] . "'>" . $value['nome'] . "</option>";
											}
										}
										?>
									</SELECT>
   							</div>
   						</div>

   						<div class="col-md-4">
   							<div class="form-group">
   								<label for="date"><b style="color:#990000">(*)</b>Data Fine Validita Autocertificazione <?php echo form_error('data_autocertificazione_fine_validita') ?></label>
   								<div class="input-group">
   									<div class="input-group-addon"><i class="fa fa-calendar"></i></div>
   									<input type="text" class="form-control datemask" name="data_autocertificazione_fine_validita" id="data_autocertificazione_fine_validita" placeholder="Data Autocertificazione Fine Validita" autocomplete="off" style="background-color:#FFFFFF" value="<?php echo $data_autocertificazione_fine_validita; ?>" />
   								</div>
   							</div>
   						</div>
 
   						<div class="col-md-4">
   							<div class="form-group">
   								<label for="documento_upload"><b style="color:#990000">(*)</b>Documento Upload <?php echo form_error('documento_upload') ?></label>
   								<div class="input-group">
   									<div class="input-group-addon"><i class="fa fa-text-height"></i></div>
   									<input type="hidden" name="documento_upload_hidden" id="documento_upload_hidden" value='<?php echo ($documento_upload != '') ? 'BLOB_SET' : '' ?>' />
   									<input type="file" class="form-control" name="documento_upload" id="documento_upload" />
   								</div>
   							</div>
   							<?php
								if ($documento_upload != '') {
									if ($id != '') {
										echo "<div id=\"dv_allegati_blob_documento_upload\">
									<div id=\"dv_allegati_blobErr_documento_upload\" style=\"display:none;font-size:18px\" class=\"alert alert-error alert-dismissable\" onclick=\"hideMsg('dv_allegati_blobErr_documento_upload')\">
									</div>										
									<a href='" . base_url() . "mod_anagrafica_green_pass_autocertificazione/scaricaAllegatoBlob/_mod_anagrafica_green_pass_autocertificazione/documento_upload/" . $id . "' target='_blank'>Scarica Allegato</a>";
										echo "<br><a style='cursor:pointer' onclick=\"rimuoviAllegatoBlob('mod_anagrafica_green_pass_autocertificazione', 'documento_upload','$id','FIELD_BLOB')\"><img src=\"".base_url()."/assets/images/delete2.png\" width='32'/> Elimina allegato</a></div>";
									} else {
										echo "<b style='color:#990000'>(Eventuali allegati caricati, sono disponibili in modifica)</b>";
									}
								}
								?>
   						</div>

   						<div class="col-md-12">
   							<div class="form-group">
   								<br><br><label for="note">Altri Allegati <?php echo form_error("allegati") ?>
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
												echo "<li id='allegato_" . $count . "'><a href='" . base_url() . "mod_anagrafica_green_pass_autocertificazione/scaricaAllegato/mod_anagrafica_green_pass_autocertificazione/" . $id . "/" . $nomeAllegato . "' target='_blank'>" . $allegato['allegato'] . "</a>";
												echo "<a style='cursor:pointer' onclick=\"rimuoviAllegato('mod_anagrafica_green_pass_autocertificazione', '$id','$nomeAllegato','allegato_$count')\"> | " . $icon_delete . "</a></li>";
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
			$ajaxAction = 'mod_anagrafica_green_pass_autocertificazione/create_action';
		} else {
			$ajaxAction = 'mod_anagrafica_green_pass_autocertificazione/update_action';
		}

		$data['ajaxAction'] = $ajaxAction;
		$data['frm_module_name'] = $frm_module_name;
		$data['id'] = $id;
		$data['id_main_content'] = $id_main_content;
		?>
   	<?php echo $this->load->view("jsconfig/mod_anagrafica_green_pass_autocertificazione_form_config.js.php", $data, true); ?>
   	<script src="<?php echo base_url(); ?>assets/js/form_submit_ajax.config.js"></script>
   </div>