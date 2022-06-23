   <?php
    //print'<pre>';print_r(get_defined_vars());
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
   				<u> Tesseramenti</u>
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
   								<a style="cursor:pointer" onclick="winFormCombo('mod_anagrafica','frm_mod_anagrafica_','fk_anagrafica_datalist','id','nome','Nuovo Anagrafica')"><i class="fa fa-ellipsis-h" style="padding: 2px 4px;border:1px solid #ccc;"></i></a>
   								<a style="cursor:pointer" onclick="pulisciCampo('fk_anagrafica_datalist_inp')"><i class="fa fa-repeat" style="padding: 2px 4px;border:1px solid #ccc;"></i></a>


   								<span class="arrow_datalist">

   									<input autofocus class="form-control" autocomplete="off" list='fk_anagrafica_datalist' 
									   		oninput='onInput("fk_anagrafica_datalist_inp","fk_anagrafica_datalist","fk_anagrafica")';
											onchange ="getTessere()";
											style="width:100%;padding: 6px 12px;font-size:14px;
									border-top-right-radius:0px;border-bottom-right-radius:0px;
									border-top-left-radius:0px;border-bottom-left-radius:0px;
									border:1px solid #ccc" name='fk_anagrafica_datalist_inp' id='fk_anagrafica_datalist_inp' value='<?php echo  $fk_anagrafica_label; ?>'></span>

   								<input type="hidden" name='fk_anagrafica' id='fk_anagrafica' value='<?php echo $fk_anagrafica; ?>'>

   								<datalist name='fk_anagrafica_datalist' id='fk_anagrafica_datalist'>


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
   								<?php
									$fk_esercizio_label = NULL;
									foreach ($fk_esercizio_refval as $key => $value) {
										if ($value['id'] == $fk_esercizio) {
											$fk_esercizio_label = $value['nome'];
										}
									}
									?>
   								<label for="fk_esercizio"><b style="color:#990000">(*)</b>Esercizio <?php echo form_error('fk_esercizio') ?></label>

   								<SELECT name='fk_esercizio' id='fk_esercizio' class="form-control" onchange='filter_slave_population_function("mod_tesseramenti", "populateAffiliazioni", 
		this.value,"fk_affiliazione", true)'>
   									<OPTION VALUE></OPTION>
   									<?php
										foreach ($fk_esercizio_refval as $key => $value) {
											if ($value['id'] == $fk_esercizio) {
												echo "<option VALUE='" . $value['id'] . "' SELECTED>" . $value['nome'] . "</option>";
											} else {
												echo "<option VALUE='" . $value['id'] . "'>" . $value['nome'] . "</option>";
											}
										}
										?>
   								</SELECT>
   							</div>
   						</div>
   						<div class="col-md-4">
   							<div class="form-group">
   								<?php
									$fk_affiliazione_label = NULL;
									foreach ($fk_affiliazione_refval as $key => $value) {
										if ($value['id'] == $fk_affiliazione) {
											$fk_affiliazione_txt = $value['nome'];
										}
									}
									?>
   								<label for="fk_affiliazione"><b style="color:#990000">(*)</b>Affiliazione <?php echo form_error('fk_affiliazione') ?></label>


   								<SELECT onchange="getTessere()" name='fk_affiliazione' id='fk_affiliazione' class="form-control">
   									<OPTION VALUE='<?php echo $fk_affiliazione; ?>'><?php echo $fk_affiliazione_txt; ?></OPTION>
   								</SELECT>

   							</div>
   						</div>
   						<div class="col-md-4">
   							<div class="form-group">
   								<?php
								    if(!isset($tessera_interna)){
										$tessera_interna = "";
										foreach ($fk_tessera_interna_refval as $key => $value) {
											if ($value['id'] == $fk_tessera_interna) {
												$tessera_interna = $value['nome'];
											}
										}
								    }
									?>
   								<label for="fk_tessera_interna"><b style="color:#990000">(*)</b>Tessera Interna<?php echo form_error('fk_tessera_interna') ?></label>
   								<input type="hidden" name='fk_tessera_interna' id='fk_tessera_interna' value='<?php echo $fk_tessera_interna; ?>'>
   								<input type="hidden" name='tessera_interna' id='tessera_interna' class="form-control"  value='<?php echo $tessera_interna; ?>'>
								<input type="text" name='b_tessera_interna' id='b_tessera_interna' class="form-control" readonly="readonly" value='<?php echo $tessera_interna; ?>'>
   							</div>
   						</div>						   
   						<div class="col-md-4">
   							<div class="form-group">
   								<?php
									$codice_tessera = "";
									foreach ($fk_tessera_associativa_refval as $key => $value) {
										if ($value['id'] == $fk_tessera_associativa) {
											$codice_tessera = $value['nome'];
										}
									}
									?>
   								<label for="fk_tessera_associativa"><b style="color:#990000">(*)</b>Tessera Associativa<?php echo form_error('fk_tessera_associativa') ?></label>
								<a style="cursor:pointer" title="Lista/aggiorna tutte le tessere per questa affiliazione" onclick="getTessere()"><i class="fa fa-clone" style="padding: 2px 4px;border:1px solid #ccc;"></i></a>
   								<SELECT   name='fk_tessera_associativa' id='fk_tessera_associativa' class="form-control">
   									<OPTION VALUE='<?php echo $fk_tessera_associativa; ?>'><?php echo $codice_tessera; ?></OPTION>
   								</SELECT>							
 					
							</div>
   						</div>
   						<div class="col-md-4">
   							<div class="form-group">
   								<label for="date"><b style="color:#990000">(*)</b>Data Tesseramento <?php echo form_error('data_tesseramento') ?></label>
   								<div class="input-group">
   									<div class="input-group-addon"><i class="fa fa-calendar"></i></div>
   									<input type="text" class="form-control datemask" name="data_tesseramento" id="data_tesseramento" placeholder="Data Tesseramento" autocomplete="off" style="background-color:#FFFFFF" value="<?php echo $data_tesseramento; ?>" />
   								</div>
   							</div>
   						</div>
   						<div class="col-md-4">
   							<div class="form-group">
   								<label for="float"><b style="color:#990000">(*)</b>Importo <?php echo form_error('importo') ?></label>
   								<div class="input-group">
   									<div class="input-group-addon"><i class="fa fa-sort-numeric-asc"></i></div>
   									<input type="number" class="form-control" maxlength='9' name="importo" id="importo" placeholder="Importo" autocomplete="off" value="<?php echo $importo; ?>" step=0.01 />
   								</div>
   							</div>
   						</div>
   						<div class="col-md-4">
   							<div class="form-group">
   								<label for="modo_pagamento"><b style="color:#990000">(*)</b>Modalita Pagamento <?php echo form_error('modo_pagamento') ?></label>
   								<SELECT name='modo_pagamento' id='modo_pagamento' class="form-control" style="width:100%;padding: 6px 12px;font-size:14px;
									border-top-right-radius:0px;border-bottom-right-radius:0px;
									border-top-left-radius:0px;border-bottom-left-radius:0px;
									border:1px solid #ccc">
   									<OPTION <?php echo ($modo_pagamento == 'CONTANTI' ? 'SELECTED' : ''); ?> VALUE='CONTANTI'>CONTANTI</OPTION>
   									<OPTION <?php echo ($modo_pagamento == 'CARTA' ? 'SELECTED' : ''); ?> VALUE='CARTA'>CARTA</OPTION>
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
												echo "<li id='allegato_" . $count . "'><a href='" . base_url() . "mod_tesseramenti/scaricaAllegato/mod_tesseramenti/" . $id . "/" . $nomeAllegato . "' target='_blank'>" . $allegato['allegato'] . "</a>";
												echo "<a style='cursor:pointer' onclick=\"rimuoviAllegato('mod_tesseramenti', '$id','$nomeAllegato','allegato_$count')\"> | " . $icon_delete . "</a></li>";
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
								<?php 
									if(isset($id)){
								?>
								 <span style="background-color:#2cdd52;color:#2cdd52">[ &nbsp;&nbsp; ] ></span> <b>(*)</b><b>Tesseramento corrente</b>
   								<div id="master_details_list" name="master_details_list">
   									<ul class="nav nav-tabs" id="myTab" role="tablist">
									   <li class="nav-item active">
											<a class="nav-link active" id="lnk-0" data-toggle="tab" href="#0" role="tab" aria-controls="0" aria-selected="true" aria-expanded="true">Storico Tesseramenti</a>
										</li>
   									</ul>
   									<div class="tab-content">			   
									   <div class="tab-pane active" id="0" role="tabpanel" aria-labelledby="0-tab">
											<table class="TFtable" id="tbl_storico_tesseramenti" style="font-size:12px">
												<tbody>
													<tr>
													</tr>
												</tbody>
												<thead>
													<tr>
														<th>Tessera Interna</th>
														<th>Tessera Associativa</th>
														<th>Esercizio</th>
														<th>Affiliazione</th>
														<th>Data Tesseramento</th>
														<th>Modalita Pagamento</th>
														<th>Importo</th>
													</tr>
													<?php 
														//print'<pre>';print_r($tbl_storico_tesseramenti); 
														if((isset($tbl_storico_tesseramenti)) && (is_array($tbl_storico_tesseramenti))){
															foreach($tbl_storico_tesseramenti as $k => $v){
																if($v['id_tesseramento'] == $id){
																	echo "<tr style='background-color:#2cdd52;font-weight:bold;font-style: italic;'>";
																} else {
																	echo "<tr>";
																}	
																
																echo "<td>".$v['tessera_interna']."</td>";
																echo "<td>".$v['tessera_associativa']."</td>";
																echo "<td>".$v['esercizio']."</td>";
																echo "<td>".$v['affiliazione']."</td>";
																echo "<td>".$v['data_tesseramento']."</td>";
																echo "<td>".$v['modo_pagamento']."</td>";
																echo "<td>".$v['importo']."</td>";
																echo "</tr>";
															}
														}

													?>
												</thead>
												<tbody></tbody>
											</table>
 
										</div>

   									</div>
   								</div>
								<?php } ?>   
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
			$ajaxAction = 'mod_tesseramenti/create_action';
		} else {
			$ajaxAction = 'mod_tesseramenti/update_action';
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


   		function getTessere() {
			//debugger; 
			var fkAnagrafica = document.getElementById('fk_anagrafica').value;
			var fkEsercizio = document.getElementById('fk_esercizio').value;
			var fkAffiliazione = document.getElementById('fk_affiliazione').value;
			if(fkAnagrafica == ''){
				fkAnagrafica = 'NULL';
			}
			if(fkEsercizio == ''){
				fkEsercizio = 'NULL';
			}
			if(fkAffiliazione == ''){
				fkAffiliazione = 'NULL';
			}				
			
			if((fkEsercizio != 'NULL') && (fkAffiliazione != 'NULL') && (fkAnagrafica != 'NULL')){
				showBiscuit();
				$.ajax({
   				url: baseURL + "mod_tesseramenti/getTessera/" + fkAnagrafica + "/" + fkEsercizio + "/" + fkAffiliazione,
   				type: 'GET',
   				dataType: 'json',
   				success: function(jsonData) {
   					console.log(jsonData);
					$('#fk_tessera_associativa').empty();
					for (var i = 0; i < jsonData.lista_tessere_associative.length; i++) {
						$('#fk_tessera_associativa').append('<option value="' + jsonData.lista_tessere_associative[i].id + '">' + jsonData.lista_tessere_associative[i].codice_tessera + '</option>');
					}	
					<?php 
						if($id == ""){ 
					?>
					if(document.getElementById('fk_tessera_interna').value == ""){
						document.getElementById('fk_tessera_interna').value = jsonData.fk_tessera_interna
						document.getElementById('tessera_interna').value = jsonData.tessera_interna
						document.getElementById('b_tessera_interna').value = jsonData.tessera_interna
					}
					<?php }?>
   					hideBiscuit();
   				}
   			});
			}

   		}
   	</script>
   	<script src="<?php echo base_url(); ?>assets/js/form_submit_ajax.config.js"></script>
   </div>