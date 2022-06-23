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
   				<u> Contratti Collaboratori</u>
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
   					<div class="form-group">
   						<?php
							$mail_disabled = "";
							if($email == ""){
								$mail_disabled = "disabled";	
							}						   
							if ((isset($id)) && ($id != '')) {
							?>
   							<a target='_blank' class='btn btn-default' href='<?php echo base_url() . "mod_contratti_collaboratori/stampa/$id"; ?>'><i class="fa fa-print"></i> STAMPA</a>
							<a <?php echo $mail_disabled ?>  class='btn btn-default' onclick="sendMailWithAttach('mod_contratti_collaboratori','<?php echo $email;?>',<?php echo $id;?>,'Invio Contratto')" style="cursor:pointer"><i class="fa fa-at"></i> Invia per Email</a>
   						<?php } ?>
   					</div>
   					<form action="<?php echo $action; ?>" method="post" name="<?php echo $frm_module_name . '_' . $id; ?>" id="<?php echo $frm_module_name . '_' . $id; ?>" <?php $preventDefault; ?>\>
   						<div class='col-md-12'>
   							<B STYLE='color:#990000'>(*)</B>Campi obbligatori
   							<br><br>
   						</div>
   						<div class="col-md-4">
   							<div class="form-group">
   								<label for="varchar"><b style="color:#990000">(*)</b>Nome Contratto <?php echo form_error('nome') ?></label>
   								<div class="input-group">
   									<div class="input-group-addon"><i class="fa fa-text-height"></i></div>
   									<input type="text" class="form-control" maxlength='50' name="nome" id="nome" placeholder="Nome Contratto" autocomplete="off" value="<?php echo $nome; ?>" />
   								</div>
   							</div>
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
   								<label for="fk_anagrafica"><b style="color:#990000">(*)</b>Collaboratore <?php echo form_error('fk_anagrafica') ?></label>
   								<a style="cursor:pointer" onclick="winFormCombo('mod_anagrafica','frm_mod_anagrafica_','fk_anagrafica_datalist','id','nome','Nuovo Collaboratore')"><i class="fa fa-ellipsis-h" style="padding: 2px 4px;border:1px solid #ccc;"></i></a>
   								<a style="cursor:pointer" onclick="pulisciCampo('fk_anagrafica_datalist_inp')"><i class="fa fa-repeat" style="padding: 2px 4px;border:1px solid #ccc;"></i></a>


   								<span class="arrow_datalist">

   									<input autofocus class="form-control" autocomplete="off" list='fk_anagrafica_datalist' oninput='onInput("fk_anagrafica_datalist_inp","fk_anagrafica_datalist","fk_anagrafica")' style="width:100%;padding: 6px 12px;font-size:14px;
									border-top-right-radius:0px;border-bottom-right-radius:0px;
									border-top-left-radius:0px;border-bottom-left-radius:0px;
									border:1px solid #ccc" name='fk_anagrafica_datalist_inp' id='fk_anagrafica_datalist_inp' value='<?php echo $fk_anagrafica_label; ?>'></span>

   								<input type="hidden" name='fk_anagrafica' id='fk_anagrafica' value='<?php echo $fk_anagrafica; ?>'>

   								<datalist name='fk_anagrafica_datalist' id='fk_anagrafica_datalist' onselect="alert(this.text)">


   									<OPTION VALUE></OPTION>
   									<?php
										foreach ($fk_anagrafica_refval as $key => $value) {
											if ($value['id'] == $fk_anagrafica) {
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
   								<label for="varchar"><b style="color:#990000">(*)</b>Mansione <?php echo form_error('mansione') ?></label>
   								<div class="input-group">
   									<div class="input-group-addon"><i class="fa fa-text-height"></i></div>
   									<input type="text" class="form-control" maxlength='255' name="mansione" id="mansione" placeholder="Mansione" autocomplete="off" value="<?php echo $mansione; ?>" />
   								</div>
   							</div>
   						</div>
						
						   
   						<div class="col-md-4">
   							<div class="form-group">
   								<label for="date"><b style="color:#990000">(*)</b>Data Firma Contratto<?php echo form_error('data_firma_contratto') ?></label>
   								<div class="input-group">
   									<div class="input-group-addon"><i class="fa fa-calendar"></i></div>
   									<input type="text" class="form-control datemask" name="data_firma_contratto" id="data_firma_contratto" placeholder="Data Firma Contratto" autocomplete="off" style="background-color:#FFFFFF" value="<?php echo $data_firma_contratto; ?>" />
   								</div>
   							</div>
   						</div>


   						<div class="col-md-4">
   							<div class="form-group">
   								<label for="date"><b style="color:#990000">(*)</b>Data Da <?php echo form_error('data_da') ?></label>
   								<div class="input-group">
   									<div class="input-group-addon"><i class="fa fa-calendar"></i></div>
   									<input type="text" class="form-control datemask" name="data_da" id="data_da" placeholder="Data Da" autocomplete="off" style="background-color:#FFFFFF" value="<?php echo $data_da; ?>" />
   								</div>
   							</div>
   						</div>
   						<div class="col-md-4">
   							<div class="form-group">
   								<label for="date"><b style="color:#990000">(*)</b>Data A <?php echo form_error('data_a') ?></label>
   								<div class="input-group">
   									<div class="input-group-addon"><i class="fa fa-calendar"></i></div>
   									<input type="text" class="form-control datemask" name="data_a" id="data_a" placeholder="Data A" autocomplete="off" style="background-color:#FFFFFF" value="<?php echo $data_a; ?>" />
   								</div>
   							</div>
   						</div>
   						<div class="col-md-4">
   							<div class="form-group">
   								<label for="float"><b style="color:#990000">(*)</b>Importo Mensile <?php echo form_error('importo_mensile') ?></label>
   								<div class="input-group">
   									<div class="input-group-addon"><i class="fa fa-sort-numeric-asc"></i></div>
   									<input type="number" class="form-control" maxlength='9' name="importo_mensile" id="importo_mensile" placeholder="Importo Mensile" autocomplete="off" value="<?php echo $importo_mensile; ?>" step=0.01 />
   								</div>
   							</div>
   						</div>
   						<div class="col-md-4">
   							<div class="form-group">
   								<label for="fk_tipopagamento"><b style="color:#990000">(*)</b>Tipo Pagamento <?php echo form_error('tipo_pagamento') ?></label>
   								<SELECT name='fk_tipopagamento' id='fk_tipopagamento' class="form-control" style="width:100%;padding: 6px 12px;font-size:14px;
									border-top-right-radius:0px;border-bottom-right-radius:0px;
									border-top-left-radius:0px;border-bottom-left-radius:0px;
									border:1px solid #ccc">
   									<?php
										foreach ($fk_tipopagamento_refval as $key => $value) {
											$nome_predef_array = explode("_",$value['nome']);
											$nome = $nome_predef_array[0];
											$predefinito = $nome_predef_array[1];
											if ($value['id'] == $fk_tipopagamento) {
												echo "<option value='" . $value['id'] . "' SELECTED>" . $nome . "</option>";
											} else {
												if($fk_tipopagamento == ""){
													if($predefinito == 'SI'){
														echo "<option value='" . $value['id'] . "' SELECTED>" . $nome . "</option>";
													} else {
														echo "<option value='" . $value['id'] . "'>" . $nome . "</option>";
													}
												} else {
													echo "<option value='" . $value['id'] . "'>" . $nome . "</option>";
												}
											}
										}
									?>									

   								</SELECT>
   							</div>
   						</div>
   						<div class="col-md-4">
   							<div class="form-group">
   								<label for="varchar">Banca <?php echo form_error('banca') ?></label>
   								<div class="input-group">
   									<div class="input-group-addon"><i class="fa fa-text-height"></i></div>
   									<input type="text" class="form-control" maxlength='255' name="banca" id="banca" placeholder="Banca" autocomplete="off" value="<?php echo $banca; ?>" />
   								</div>
   							</div>
   						</div>
   						<div class="col-md-4">
   							<div class="form-group">
   								<label for="varchar">IBAN <?php echo form_error('iban') ?></label>
   								<div class="input-group">
   									<div class="input-group-addon"><i class="fa fa-text-height"></i></div>
   									<input type="text" class="form-control" maxlength='255' name="iban" id="iban" placeholder="IBAN" autocomplete="off" value="<?php echo $iban; ?>" />
   								</div>
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
												echo "<li id='allegato_" . $count . "'><a href='" . base_url() . "mod_contratti_collaboratori/scaricaAllegato/mod_contratti_collaboratori/" . $id . "/" . $nomeAllegato . "' target='_blank'>" . $allegato['allegato'] . "</a>";
												echo "<a style='cursor:pointer' onclick=\"rimuoviAllegato('mod_contratti_collaboratori', '$id','$nomeAllegato','allegato_$count')\"> | " . $icon_delete . "</a></li>";
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
								<a class="nav-link active" id="lnk-' . $key . '" data-toggle="tab" href="#' . $key . '" role="tab" aria-controls="' . $key . '" aria-selected="true" aria-expanded="true">' . $master_details['title'] . '</a>
								</li>';
													} else {
														echo '<li class="nav-item">
								<a class="nav-link" id="lnk-' . $key . '" data-toggle="tab" href="#' . $key . '" role="tab" aria-controls="' . $key . '" aria-selected="true" aria-expanded="true">' . $master_details['title'] . '</a>
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
													echo '<div class="tab-pane ' . $active . '" id="' . $key . '" role="tabpanel" aria-labelledby="' . $key . '-tab">';
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
			$ajaxAction = 'mod_contratti_collaboratori/create_action';
		} else {
			$ajaxAction = 'mod_contratti_collaboratori/update_action';
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


   		arrayValidationFields['winMasterDetail_mod_pagamenti_collaboratori'] = [];
   		/*js_custom_operations_list['winMasterDetail_mod_pagamenti_collaboratori'] = [];
			js_custom_operations_list['winMasterDetail_mod_pagamenti_collaboratori'].check_date = function(){
				var ret = check_date_greater_then('data_validita_inizio','data_validita_fine');
				if(ret === false){
					document.getElementById('msg_err').style.display = "block";
            		document.getElementById('msg_err').innerHTML = "Date non valide";
            		ev.preventDefault();
				}
			};*/
   		arrayValidationFields['winMasterDetail_mod_pagamenti_collaboratori']['fk_contratto'] = [];
   		arrayValidationFields['winMasterDetail_mod_pagamenti_collaboratori']['fk_contratto']['label'] = "Contratto"
   		arrayValidationFields['winMasterDetail_mod_pagamenti_collaboratori']['fk_contratto']['field_type'] = "int"
   		arrayValidationFields['winMasterDetail_mod_pagamenti_collaboratori']['datapagamento'] = [];
   		arrayValidationFields['winMasterDetail_mod_pagamenti_collaboratori']['datapagamento']['label'] = "Data Pagamento"
   		arrayValidationFields['winMasterDetail_mod_pagamenti_collaboratori']['datapagamento']['field_type'] = "date"
   		arrayValidationFields['winMasterDetail_mod_pagamenti_collaboratori']['ora_pagamento'] = [];
   		arrayValidationFields['winMasterDetail_mod_pagamenti_collaboratori']['ora_pagamento']['label'] = "Ora"
   		arrayValidationFields['winMasterDetail_mod_pagamenti_collaboratori']['ora_pagamento']['field_type'] = "time"
   		arrayValidationFields['winMasterDetail_mod_pagamenti_collaboratori']['importo'] = [];
   		arrayValidationFields['winMasterDetail_mod_pagamenti_collaboratori']['importo']['label'] = "Importo"
   		arrayValidationFields['winMasterDetail_mod_pagamenti_collaboratori']['importo']['field_type'] = "float"
   		arrayValidationFields['winMasterDetail_mod_pagamenti_collaboratori']['fk_tipopagamento'] = [];
   		arrayValidationFields['winMasterDetail_mod_pagamenti_collaboratori']['fk_tipopagamento']['label'] = "Tipo Pagamento"
   		arrayValidationFields['winMasterDetail_mod_pagamenti_collaboratori']['fk_tipopagamento']['field_type'] = "int"
   		arrayValidationFields['winMasterDetail_mod_pagamenti_collaboratori']['fk_causale_pagamento'] = [];
   		arrayValidationFields['winMasterDetail_mod_pagamenti_collaboratori']['fk_causale_pagamento']['label'] = "Causale Pagamento"
   		arrayValidationFields['winMasterDetail_mod_pagamenti_collaboratori']['fk_causale_pagamento']['field_type'] = "int"
   		//arrayValidationFields['winMasterDetail_mod_pagamenti_collaboratori']['notepagamento'] = [];
   		//arrayValidationFields['winMasterDetail_mod_pagamenti_collaboratori']['notepagamento']['label'] = "Note"
   		//arrayValidationFields['winMasterDetail_mod_pagamenti_collaboratori']['notepagamento']['field_type'] = "longtext"

   		arrayValidationFields['winMasterDetail_mod_contratti_collaboratori_corsi'] = [];
   		/*js_custom_operations_list['winMasterDetail_mod_contratti_collaboratori_corsi'] = [];
			js_custom_operations_list['winMasterDetail_mod_contratti_collaboratori_corsi'].check_date = function(){
				var ret = check_date_greater_then('data_validita_inizio','data_validita_fine');
				if(ret === false){
					document.getElementById('msg_err').style.display = "block";
            		document.getElementById('msg_err').innerHTML = "Date non valide";
            		ev.preventDefault();
				}
			};*/
   		arrayValidationFields['winMasterDetail_mod_contratti_collaboratori_corsi']['fk_contratto'] = [];
   		arrayValidationFields['winMasterDetail_mod_contratti_collaboratori_corsi']['fk_contratto']['label'] = "Contratto"
   		arrayValidationFields['winMasterDetail_mod_contratti_collaboratori_corsi']['fk_contratto']['field_type'] = "int"
   		arrayValidationFields['winMasterDetail_mod_contratti_collaboratori_corsi']['fk_corso'] = [];
   		arrayValidationFields['winMasterDetail_mod_contratti_collaboratori_corsi']['fk_corso']['label'] = "Corso"
   		arrayValidationFields['winMasterDetail_mod_contratti_collaboratori_corsi']['fk_corso']['field_type'] = "int"
   	</script>
   	<script src="<?php echo base_url(); ?>assets/js/form_submit_ajax.config.js"></script>
   </div>