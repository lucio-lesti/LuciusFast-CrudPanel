   <?php
	if ((isset($id)) && ($id != "")) {
		$id_main_content = $id;
	} else {
		$id_main_content = rand(0, 1000000);
	}
	$id_button = $id;

	$disabled = "disabled";
	$icon = "fa fa-rocket";
 
	?>
   <div <?php if ($winForm == 'TRUE') {
			echo " class='main_content_ajax_form' ";
		} ?> id="main_content_ajax_form_<?php echo $id_main_content; ?>">
   	<!-- Content Header (Page header) -->
   	<div class="col-md-8">
   		<h3>
   			<i class="<?php echo $icon;?>"></i>
   			<a>
   				<u> Corsi <?php if($_SESSION['fast_ins_corsi'] == 'Y'){ echo " - Inserimento Veloce";}?></u>
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
   								<label for="varchar"><b style="color:#990000">(*)</b>Nome Corso <?php echo form_error('nome') ?></label>
   								<div class="input-group">
   									<div class="input-group-addon"><i class="fa fa-text-height"></i></div>
   									<input type="text" <?php echo $disabled;?> class="form-control" maxlength='50' name="nome" id="nome" placeholder="Nome Corso" autocomplete="off" value="<?php echo $nome; ?>" />
   								</div>
   							</div>
   						</div>
						
						<?php
							if($_SESSION['fast_ins_corsi'] == 'N'){

						?>
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

   									<input <?php echo $disabled;?> autofocus class="form-control" autocomplete="off" list='fk_affiliazione_datalist' oninput='onInput("fk_affiliazione_datalist_inp","fk_affiliazione_datalist","fk_affiliazione");caricaDiscipline()' style="width:100%;padding: 6px 12px;font-size:14px;
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
   								<?php
									$fk_disciplina_label = NULL;
									foreach ($fk_disciplina_refval as $key => $value) {
										if ($value['id'] == $fk_disciplina) {
											$fk_disciplina_label = $value['nome'];
										}
									}
									?>
   								<label for="fk_disciplina"><b style="color:#990000">(*)</b>Disciplina <?php echo form_error('fk_disciplina') ?></label>
   								<a style="cursor:pointer" onclick="winFormCombo('mod_discipline','frm_mod_discipline_','fk_disciplina_datalist','id','nome','Nuovo Disciplina')"><i class="fa fa-ellipsis-h" style="padding: 2px 4px;border:1px solid #ccc;"></i></a>
   								<a style="cursor:pointer" onclick="pulisciCampo('fk_disciplina_datalist_inp')"><i class="fa fa-repeat" style="padding: 2px 4px;border:1px solid #ccc;"></i></a>


   								<span class="arrow_datalist">

   									<input <?php echo $disabled;?> autofocus class="form-control" autocomplete="off" list='fk_disciplina_datalist' oninput='onInput("fk_disciplina_datalist_inp","fk_disciplina_datalist","fk_disciplina")' style="width:100%;padding: 6px 12px;font-size:14px;
									border-top-right-radius:0px;border-bottom-right-radius:0px;
									border-top-left-radius:0px;border-bottom-left-radius:0px;
									border:1px solid #ccc" name='fk_disciplina_datalist_inp' id='fk_disciplina_datalist_inp' value='<?php echo $fk_disciplina_label; ?>'></span>

   								<input type="hidden" name='fk_disciplina' id='fk_disciplina' value='<?php echo $fk_disciplina; ?>'>

   								<datalist name='fk_disciplina_datalist' id='fk_disciplina_datalist' onselect="alert(this.text)">

								   
   							</div>
   						</div>


   						<div class="col-md-4">
   							<div class="form-group">
   								<label for="date"><b style="color:#990000">(*)</b>Data Da <?php echo form_error('data_da') ?></label>
   								<div class="input-group">
   									<div class="input-group-addon"><i class="fa fa-calendar"></i></div>
   									<input <?php echo $disabled;?> type="text" class="form-control datemask" name="data_da" id="data_da" placeholder="Data Da" autocomplete="off"  value="<?php echo $data_da; ?>" />
   								</div>
   							</div>
   						</div>
   						<div class="col-md-4">
   							<div class="form-group">
   								<label for="date"><b style="color:#990000">(*)</b>Data A <?php echo form_error('data_a') ?></label>
   								<div class="input-group">
   									<div class="input-group-addon"><i class="fa fa-calendar"></i></div>
   									<input <?php echo $disabled;?> type="text" class="form-control datemask" name="data_a" id="data_a" placeholder="Data A" autocomplete="off"  value="<?php echo $data_a; ?>" />
   								</div>
   							</div>
   						</div>


   						<div class="col-md-4">
   							<div class="form-group">
   								<label for="tipologia_corso"><b style="color:#990000">(*)</b>Tipo Corso <?php echo form_error('tipologia_corso') ?></label>
   								<SELECT  <?php echo $disabled;?> name='tipologia_corso' id='tipologia_corso' class="form-control" style="width:100%;padding: 6px 12px;font-size:14px;
									border-top-right-radius:0px;border-bottom-right-radius:0px;
									border-top-left-radius:0px;border-bottom-left-radius:0px;
									border:1px solid #ccc">
   									<OPTION <?php echo ($tipologia_corso == 'ABBONAMENTO' ? 'SELECTED' : ''); ?> VALUE='ABBONAMENTO'>ABBONAMENTO</OPTION>
   									<OPTION <?php echo ($tipologia_corso == 'MENSILE' ? 'SELECTED' : ''); ?> VALUE='MENSILE'>MENSILE</OPTION>
   								</SELECT>
   							</div>
   						</div>
   						<div class="col-md-4">
   							<div class="form-group">
   								<label for="float"><b style="color:#990000">(*)</b>Importo <?php echo form_error('importo_mensile') ?></label>
   								<div class="input-group">
   									<div class="input-group-addon"><i class="fa fa-sort-numeric-asc"></i></div>
   									<input <?php echo $disabled;?>  type="number" class="form-control" maxlength='12' name="importo_mensile" id="importo_mensile" placeholder="Importo" autocomplete="off" value="<?php echo $importo_mensile; ?>" step=0.01 />
   								</div>
   							</div>
   						</div>

   						<div class="col-md-12">
   							<div class="form-group">
   								<label for="note">Allegati <?php echo form_error("allegati") ?>
   									<br><span style="font-size:12px"><b>File Ammessi:</b><b style="color:#990000"><?php echo implode(",", $extAdmitted); ?></b></span>
   								</label>
   								<input <?php echo $disabled;?> type="file" name="allegati[]" id="allegati[]" class="form-control" multiple />

   								<br>
   								<ul>
   									<?php
										$icon_delete = "<img src='" . base_url() . "assets/images/delete2.png' width='32'/>";

										$count = 0;
										foreach ($allegati as $key => $allegato) {
											$nomeAllegato =  $allegato['allegato'];

											if (($id != '')) {
												echo "<li id='allegato_" . $count . "'><a href='" . base_url() . "mod_corsi/scaricaAllegato/mod_corsi/" . $id . "/" . $nomeAllegato . "' target='_blank'>" . $allegato['allegato'] . "</a>";
												echo "<a style='cursor:pointer' onclick=\"rimuoviAllegato('mod_corsi', '$id','$nomeAllegato','allegato_$count')\"> | " . $icon_delete . "</a></li>";
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
						<?php } ?>				
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
												if (($winForm == "FALSE") && ($_SESSION['fast_ins_corsi'] == 'N')){
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
			$ajaxAction = 'mod_corsi/create_action';
		} else {
			$ajaxAction = 'mod_corsi/update_action';
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


   		arrayValidationFields['winMasterDetail_mod_corsi_sale_calendario'] = [];
   		/*js_custom_operations_list['winMasterDetail_mod_corsi_sale_calendario'] = [];
			js_custom_operations_list['winMasterDetail_mod_corsi_sale_calendario'].check_date = function(){
				var ret = check_date_greater_then('data_validita_inizio','data_validita_fine');
				if(ret === false){
					document.getElementById('msg_err').style.display = "block";
            		document.getElementById('msg_err').innerHTML = "Date non valide";
            		ev.preventDefault();
				}
			};*/
   		arrayValidationFields['winMasterDetail_mod_corsi_sale_calendario']['data_corso_a'] = [];
   		arrayValidationFields['winMasterDetail_mod_corsi_sale_calendario']['data_corso_a']['label'] = "Data Corso a	"
   		arrayValidationFields['winMasterDetail_mod_corsi_sale_calendario']['data_corso_a']['field_type'] = "date"
   		arrayValidationFields['winMasterDetail_mod_corsi_sale_calendario']['data_corso_da'] = [];
   		arrayValidationFields['winMasterDetail_mod_corsi_sale_calendario']['data_corso_da']['label'] = "Data Corso Da"
   		arrayValidationFields['winMasterDetail_mod_corsi_sale_calendario']['data_corso_da']['field_type'] = "date"
   		arrayValidationFields['winMasterDetail_mod_corsi_sale_calendario']['fk_corso'] = [];
   		arrayValidationFields['winMasterDetail_mod_corsi_sale_calendario']['fk_corso']['label'] = "Corso"
   		arrayValidationFields['winMasterDetail_mod_corsi_sale_calendario']['fk_corso']['field_type'] = "int"
   		arrayValidationFields['winMasterDetail_mod_corsi_sale_calendario']['fk_sala'] = [];
   		arrayValidationFields['winMasterDetail_mod_corsi_sale_calendario']['fk_sala']['label'] = "Sala"
   		arrayValidationFields['winMasterDetail_mod_corsi_sale_calendario']['fk_sala']['field_type'] = "int"
   		arrayValidationFields['winMasterDetail_mod_corsi_sale_calendario']['ora_corso_a'] = [];
   		arrayValidationFields['winMasterDetail_mod_corsi_sale_calendario']['ora_corso_a']['label'] = "Ora a"
   		arrayValidationFields['winMasterDetail_mod_corsi_sale_calendario']['ora_corso_a']['field_type'] = "varchar"
   		arrayValidationFields['winMasterDetail_mod_corsi_sale_calendario']['ora_corso_da'] = [];
   		arrayValidationFields['winMasterDetail_mod_corsi_sale_calendario']['ora_corso_da']['label'] = "Ora da"
   		arrayValidationFields['winMasterDetail_mod_corsi_sale_calendario']['ora_corso_da']['field_type'] = "varchar"

   		arrayValidationFields['winMasterDetail_mod_corsi_iscrizioni'] = [];
   		/*js_custom_operations_list['winMasterDetail_mod_corsi_iscrizioni'] = [];
			js_custom_operations_list['winMasterDetail_mod_corsi_iscrizioni'].check_date = function(){
				var ret = check_date_greater_then('data_validita_inizio','data_validita_fine');
				if(ret === false){
					document.getElementById('msg_err').style.display = "block";
            		document.getElementById('msg_err').innerHTML = "Date non valide";
            		ev.preventDefault();
				}
			};*/
   		arrayValidationFields['winMasterDetail_mod_corsi_iscrizioni']['anno'] = [];
   		arrayValidationFields['winMasterDetail_mod_corsi_iscrizioni']['anno']['label'] = "Anno"
   		arrayValidationFields['winMasterDetail_mod_corsi_iscrizioni']['anno']['field_type'] = "enum"
   		arrayValidationFields['winMasterDetail_mod_corsi_iscrizioni']['fk_anagrafica'] = [];
   		arrayValidationFields['winMasterDetail_mod_corsi_iscrizioni']['fk_anagrafica']['label'] = "Alunno"
   		arrayValidationFields['winMasterDetail_mod_corsi_iscrizioni']['fk_anagrafica']['field_type'] = "int"
   		arrayValidationFields['winMasterDetail_mod_corsi_iscrizioni']['fk_corso'] = [];
   		arrayValidationFields['winMasterDetail_mod_corsi_iscrizioni']['fk_corso']['label'] = "Corso"
   		arrayValidationFields['winMasterDetail_mod_corsi_iscrizioni']['fk_corso']['field_type'] = "int"
   		arrayValidationFields['winMasterDetail_mod_corsi_iscrizioni']['is_frequentato'] = [];
   		arrayValidationFields['winMasterDetail_mod_corsi_iscrizioni']['is_frequentato']['label'] = "Frequentato"
   		arrayValidationFields['winMasterDetail_mod_corsi_iscrizioni']['is_frequentato']['field_type'] = "enum"
   		arrayValidationFields['winMasterDetail_mod_corsi_iscrizioni']['mese'] = [];
   		arrayValidationFields['winMasterDetail_mod_corsi_iscrizioni']['mese']['label'] = "Mese"
   		arrayValidationFields['winMasterDetail_mod_corsi_iscrizioni']['mese']['field_type'] = "enum"

   		arrayValidationFields['winMasterDetail_mod_corsi_insegnanti'] = [];
   		/*js_custom_operations_list['winMasterDetail_mod_corsi_insegnanti'] = [];
			js_custom_operations_list['winMasterDetail_mod_corsi_insegnanti'].check_date = function(){
				var ret = check_date_greater_then('data_validita_inizio','data_validita_fine');
				if(ret === false){
					document.getElementById('msg_err').style.display = "block";
            		document.getElementById('msg_err').innerHTML = "Date non valide";
            		ev.preventDefault();
				}
			};*/
   		arrayValidationFields['winMasterDetail_mod_corsi_insegnanti']['fk_anagrafica'] = [];
   		arrayValidationFields['winMasterDetail_mod_corsi_insegnanti']['fk_anagrafica']['label'] = "Insegnante"
   		arrayValidationFields['winMasterDetail_mod_corsi_insegnanti']['fk_anagrafica']['field_type'] = "int"
   		arrayValidationFields['winMasterDetail_mod_corsi_insegnanti']['fk_corso'] = [];
   		arrayValidationFields['winMasterDetail_mod_corsi_insegnanti']['fk_corso']['label'] = "Corso"
   		arrayValidationFields['winMasterDetail_mod_corsi_insegnanti']['fk_corso']['field_type'] = "int"

   		arrayValidationFields['winMasterDetail_mod_anagrafica_corsi'] = [];
   		/*js_custom_operations_list['winMasterDetail_mod_anagrafica_corsi'] = [];
			js_custom_operations_list['winMasterDetail_mod_anagrafica_corsi'].check_date = function(){
				var ret = check_date_greater_then('data_validita_inizio','data_validita_fine');
				if(ret === false){
					document.getElementById('msg_err').style.display = "block";
            		document.getElementById('msg_err').innerHTML = "Date non valide";
            		ev.preventDefault();
				}
			};*/
   		arrayValidationFields['winMasterDetail_mod_anagrafica_corsi']['data_iscrizione'] = [];
   		arrayValidationFields['winMasterDetail_mod_anagrafica_corsi']['data_iscrizione']['label'] = "Data Iscrizione"
   		arrayValidationFields['winMasterDetail_mod_anagrafica_corsi']['data_iscrizione']['field_type'] = "date"
   		arrayValidationFields['winMasterDetail_mod_anagrafica_corsi']['fk_anagrafica'] = [];
   		arrayValidationFields['winMasterDetail_mod_anagrafica_corsi']['fk_anagrafica']['label'] = "Alunno"
   		arrayValidationFields['winMasterDetail_mod_anagrafica_corsi']['fk_anagrafica']['field_type'] = "int"
   		arrayValidationFields['winMasterDetail_mod_anagrafica_corsi']['fk_corso'] = [];
   		arrayValidationFields['winMasterDetail_mod_anagrafica_corsi']['fk_corso']['label'] = "Corso"
   		arrayValidationFields['winMasterDetail_mod_anagrafica_corsi']['fk_corso']['field_type'] = "int"

   		arrayValidationFields['winMasterDetail_mod_corsi_giorni_orari'] = [];
   		/*js_custom_operations_list['winMasterDetail_mod_corsi_giorni_orari'] = [];
			js_custom_operations_list['winMasterDetail_mod_corsi_giorni_orari'].check_date = function(){
				var ret = check_date_greater_then('data_validita_inizio','data_validita_fine');
				if(ret === false){
					document.getElementById('msg_err').style.display = "block";
            		document.getElementById('msg_err').innerHTML = "Date non valide";
            		ev.preventDefault();
				}
			};*/
   		arrayValidationFields['winMasterDetail_mod_corsi_giorni_orari']['fk_corso'] = [];
   		arrayValidationFields['winMasterDetail_mod_corsi_giorni_orari']['fk_corso']['label'] = "Corso"
   		arrayValidationFields['winMasterDetail_mod_corsi_giorni_orari']['fk_corso']['field_type'] = "int"
   		arrayValidationFields['winMasterDetail_mod_corsi_giorni_orari']['giorno_settimana'] = [];
   		arrayValidationFields['winMasterDetail_mod_corsi_giorni_orari']['giorno_settimana']['label'] = "Giorno della Settimana"
   		arrayValidationFields['winMasterDetail_mod_corsi_giorni_orari']['giorno_settimana']['field_type'] = "enum"
   		arrayValidationFields['winMasterDetail_mod_corsi_giorni_orari']['ora_a'] = [];
   		arrayValidationFields['winMasterDetail_mod_corsi_giorni_orari']['ora_a']['label'] = "Ora a"
   		arrayValidationFields['winMasterDetail_mod_corsi_giorni_orari']['ora_a']['field_type'] = "time"
   		arrayValidationFields['winMasterDetail_mod_corsi_giorni_orari']['ora_da'] = [];
   		arrayValidationFields['winMasterDetail_mod_corsi_giorni_orari']['ora_da']['label'] = "Ora da"
   		arrayValidationFields['winMasterDetail_mod_corsi_giorni_orari']['ora_da']['field_type'] = "time"

   		arrayValidationFields['winMasterDetail_mod_pagamenti_ricevuti'] = [];
   		/*js_custom_operations_list['winMasterDetail_mod_pagamenti_ricevuti'] = [];
			js_custom_operations_list['winMasterDetail_mod_pagamenti_ricevuti'].check_date = function(){
				var ret = check_date_greater_then('data_validita_inizio','data_validita_fine');
				if(ret === false){
					document.getElementById('msg_err').style.display = "block";
            		document.getElementById('msg_err').innerHTML = "Date non valide";
            		ev.preventDefault();
				}
			};*/
   		arrayValidationFields['winMasterDetail_mod_pagamenti_ricevuti']['datapagamento'] = [];
   		arrayValidationFields['winMasterDetail_mod_pagamenti_ricevuti']['datapagamento']['label'] = "Data Pagamento"
   		arrayValidationFields['winMasterDetail_mod_pagamenti_ricevuti']['datapagamento']['field_type'] = "datetime"
   		arrayValidationFields['winMasterDetail_mod_pagamenti_ricevuti']['fk_anagrafica'] = [];
   		arrayValidationFields['winMasterDetail_mod_pagamenti_ricevuti']['fk_anagrafica']['label'] = "Alunno"
   		arrayValidationFields['winMasterDetail_mod_pagamenti_ricevuti']['fk_anagrafica']['field_type'] = "int"
   		arrayValidationFields['winMasterDetail_mod_pagamenti_ricevuti']['fk_corso'] = [];
   		arrayValidationFields['winMasterDetail_mod_pagamenti_ricevuti']['fk_corso']['label'] = "Corso"
   		arrayValidationFields['winMasterDetail_mod_pagamenti_ricevuti']['fk_corso']['field_type'] = "int"
   		arrayValidationFields['winMasterDetail_mod_pagamenti_ricevuti']['importo'] = [];
   		arrayValidationFields['winMasterDetail_mod_pagamenti_ricevuti']['importo']['label'] = "Pagato"
   		arrayValidationFields['winMasterDetail_mod_pagamenti_ricevuti']['importo']['field_type'] = "float"
   		arrayValidationFields['winMasterDetail_mod_pagamenti_ricevuti']['notepagamento'] = [];
   		arrayValidationFields['winMasterDetail_mod_pagamenti_ricevuti']['notepagamento']['label'] = "Note Pagamento"
   		arrayValidationFields['winMasterDetail_mod_pagamenti_ricevuti']['notepagamento']['field_type'] = "longtext"
   		arrayValidationFields['winMasterDetail_mod_pagamenti_ricevuti']['saldo'] = [];
   		arrayValidationFields['winMasterDetail_mod_pagamenti_ricevuti']['saldo']['label'] = "Saldo"
   		arrayValidationFields['winMasterDetail_mod_pagamenti_ricevuti']['saldo']['field_type'] = "enum"
   		arrayValidationFields['winMasterDetail_mod_pagamenti_ricevuti']['tipo_pagamento'] = [];
   		arrayValidationFields['winMasterDetail_mod_pagamenti_ricevuti']['tipo_pagamento']['label'] = "Tipo Pagamento"
   		arrayValidationFields['winMasterDetail_mod_pagamenti_ricevuti']['tipo_pagamento']['field_type'] = "set"

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

   		function getTesseramento() {
			debugger;
   			$.ajax({
   				url: baseURL + "/mod_corsi/getTesseramento/",
   				type: 'post',
   				dataType: 'json',
   				data: {
   					fk_anagrafica: document.getElementById('fk_anagrafica').value,
   					fk_corso: document.getElementById('fk_corso').value
   				},
   				success: function(jsonData) {
   					document.getElementById('fk_tesseramento').value = jsonData.fk_tesseramento;
   					hideBiscuit();
   				}
   			});
   		}


   		function caricaDiscipline() {
   			showBiscuit();
   			var where_condition_str = " WHERE id IN (";
   			where_condition_str += " SELECT fk_disciplina FROM _mod_enti_discipline";
   			where_condition_str += " INNER JOIN mod_affiliazioni ON _mod_enti_discipline.fk_ente = mod_affiliazioni.fk_ente";
   			where_condition_str += " 	AND mod_affiliazioni.id = " + document.getElementById('fk_affiliazione').value;
   			where_condition_str += ")";


   			$.ajax({
   				url: baseURL + "/mod_corsi/getKeyValuesFromTableViaPOST/",
   				type: 'post',
   				dataType: 'json',
   				data: {
   					table_referenced: 'mod_discipline',
   					id: 'id',
   					value: "nome",
   					where_condition: where_condition_str
   				},
   				success: function(jsonData) {
   					$('#fk_disciplina_datalist_inp').val('');
   					$('#fk_disciplina_datalist').empty();
   					for (var i = 0; i < jsonData.length; i++) {
   						$('#fk_disciplina_datalist').append('<option data-value="' + jsonData[i].id + '">' + jsonData[i].nome + '</option>');
   					}

   					$.ajax({
   						url: baseURL + "/mod_corsi/getDateEsercizio/" + document.getElementById('fk_affiliazione').value,
   						type: 'get',
   						dataType: 'json',
   						success: function(jsonData) {
   							console.log(jsonData[0].data_da);

							document.getElementById('data_da').value = jsonData[0].data_da;
							document.getElementById('data_a').value = jsonData[0].data_a;

   							hideBiscuit();
   						}
   					});

   				}
   			});


   		}
   	</script>
   	<script src="<?php echo base_url(); ?>assets/js/form_submit_ajax.config.js"></script>
   </div>