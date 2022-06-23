<style>
 #my_camera{
     width: 320px;
     height: 240px;
     border: 1px solid black;
}
</style>  
 
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
   	<div class="col-md-8" id="my_top">
   		<h3>
   			<i class="fa fa-cubes"></i>
   			<a>
   				<u> Anagrafica</u>
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
   								<label for="img_foto">Foto <?php echo form_error('img_foto') ?> (250px X 320px)</label>
								<a style='cursor:pointer' onclick="openCameraWinForm()"><i class="fa fa-video"></i></a> 
   								<div class="input-group">
   									<div class="input-group-addon"><i class="fa fa-camera"></i></div>
   									<input type="hidden" name="img_foto_hidden" id="img_foto_hidden" value='<?php echo $img_foto;?>' />
 
   									<input type="file" class="form-control" name="img_foto" id="img_foto"  />
   								</div>
   							</div>
   							<?php
								if ($img_foto != '') {
									echo '<div id="dv_allegati_blob_img_foto">
								<div id="dv_allegati_blobErr_img_foto" style="display:none;font-size:18px" class="alert alert-error alert-dismissable" onclick="hideMsg(\'dv_allegati_blobErr_img_foto\')">
								</div>								
								<div id="dv_foto_anagrafica"><img id="foto_anagrafica" src="data:image/jpeg;base64,' . $img_foto . '" style="width:250px;height:250px"  /></div> ';
									echo "<br/><a style='cursor:pointer' onclick=\"rimuoviFoto()\"><img src=\"" . base_url() . "assets/images/delete2.png\" width='32'/>Elimina immagine</a></div>";
								} else {
									echo '<div id="dv_allegati_blob_img_foto">
								<div id="dv_allegati_blobErr_img_foto" style="display:none;font-size:18px" class="alert alert-error alert-dismissable" onclick="hideMsg(\'dv_allegati_blobErr_img_foto\')">
								</div>								
								<div id="dv_foto_anagrafica"><img id="foto_anagrafica" src="' . base_url() . 'assets/images/edit_user_icon.png" style="width:250px;height:250px"  /></div>';
									echo "<br/></div>";
								}
								?>
   						</div>
   						<div class="col-md-4">
   							<div class="form-group">
   								<label for="varchar"><b style="color:#990000">(*)</b>Nome <?php echo form_error('nome') ?></label>
   								<div class="input-group">
   									<div class="input-group-addon"><i class="fa fa-text-height"></i></div>
   									<input type="text" class="form-control" maxlength='50' name="nome" id="nome" placeholder="Nome" autocomplete="off" value="<?php echo $nome; ?>" />
   								</div>
   							</div>
   						</div>
   						<div class="col-md-4">
   							<div class="form-group">
   								<label for="varchar"><b style="color:#990000">(*)</b>Cognome <?php echo form_error('cognome') ?></label>
   								<div class="input-group">
   									<div class="input-group-addon"><i class="fa fa-text-height"></i></div>
   									<input type="text" class="form-control" maxlength='50' name="cognome" id="cognome" placeholder="Cognome" autocomplete="off" value="<?php echo $cognome; ?>" />
   								</div>
   							</div>
   						</div>
   						<div class="col-md-4">
   							<div class="form-group">
   								<label for="date"><b style="color:#990000">(*)</b>Data Di Nascita <?php echo form_error('datanascita') ?></label>
   								<div class="input-group">
   									<div class="input-group-addon"><i class="fa fa-calendar"></i></div>
   									<input type="text" class="form-control datemask" name="datanascita" id="datanascita" placeholder="Data Di Nascita" autocomplete="off" style="background-color:#FFFFFF" value="<?php echo $datanascita; ?>" />
   								</div>
   							</div>
   						</div>
   						<div class="col-md-4">
   							<div class="form-group">
   								<label for="sesso"><b style="color:#990000">(*)</b>Sesso <?php echo form_error('sesso') ?></label>
   								<SELECT name='sesso' id='sesso' class="form-control" style="width:100%;padding: 6px 12px;font-size:14px;
									border-top-right-radius:0px;border-bottom-right-radius:0px;
									border-top-left-radius:0px;border-bottom-left-radius:0px;
									border:1px solid #ccc">
   									<OPTION <?php echo ($sesso == '' ? 'SELECTED' : ''); ?> VALUE=''></OPTION>
   									<OPTION <?php echo ($sesso == 'F' ? 'SELECTED' : ''); ?> VALUE='F'>F</OPTION>
   									<OPTION <?php echo ($sesso == 'M' ? 'SELECTED' : ''); ?> VALUE='M'>M</OPTION>
   								</SELECT>
   							</div>
   						</div>
   						<div class="col-md-4">
   							<?php
								$dv_fk_comune_nascita = "dv_fk_comune_nascita";
								if ($winForm == "TRUE") {
									$dv_fk_comune_nascita = "dv_fk_comune_nascita_winform";
								}
								?>
   							<div class="form-group" id="<?php echo $dv_fk_comune_nascita; ?>">
   								<?php
									$fk_comune_nascita_label = NULL;
									foreach ($fk_comune_nascita_refval as $key => $value) {
										if ($value['id'] == $fk_comune_nascita) {
											$fk_comune_nascita_label = $value['nome'];
										}
									}
									?>
   								<label for="fk_comune_nascita"><b style="color:#990000">(*)</b>Comune Di Nascita <?php echo form_error('fk_comune_nascita') ?></label>

   								<span class="arrow_datalist">
   									<a style="cursor:pointer" onclick="pulisciCampo('fk_comune_nascita_datalist_inp'<?php if ($winForm == 'TRUE') echo ',\'' . $dv_fk_comune_nascita . '\''; ?>)"><i class="fa fa-repeat" style="padding: 2px 4px;border:1px solid #ccc;"></i></a>
   									<input autofocus class="form-control" autocomplete="off" list='fk_comune_nascita_datalist' oninput='onInput("fk_comune_nascita_datalist_inp","fk_comune_nascita_datalist","fk_comune_nascita"<?php if ($winForm == "TRUE") echo ",\"winform\""; ?>)' style="width:100%;padding: 6px 12px;font-size:14px;
									border-top-right-radius:0px;border-bottom-right-radius:0px;
									border-top-left-radius:0px;border-bottom-left-radius:0px;
									border:1px solid #ccc" name='fk_comune_nascita_datalist_inp' id='fk_comune_nascita_datalist_inp' value='<?php echo $fk_comune_nascita_label; ?>'></span>

   								<input type="hidden" name='fk_comune_nascita' id='fk_comune_nascita' value='<?php echo $fk_comune_nascita; ?>'>

   								<datalist name='fk_comune_nascita_datalist' id='fk_comune_nascita_datalist' onselect="alert(this.text)">


   									<OPTION VALUE></OPTION>
   									<?php
										foreach ($fk_comune_nascita_refval as $key => $value) {
											if ($value['id'] == $fk_comune_nascita) {
												echo "<option data-value='" . $value['id'] . "' SELECTED>" . $value['nome'] . "</option>";
											} else {
												echo "<option data-value='" . $value['id'] . "'>" . $value['nome'] . "</option>";
											}
										}
										?>
   							</div>
   						</div>
   						<div class="col-md-4">
   							<?php
								$dv_fk_comune_residenza = "dv_fk_comune_residenza";
								if ($winForm == "TRUE") {
									$dv_fk_comune_residenza = "dv_fk_comune_residenza_winform";
								}
								?>
   							<div class="form-group" id="<?php echo $dv_fk_comune_residenza; ?>">
   								<?php
									$fk_comune_residenza_label = NULL;
									foreach ($fk_comune_residenza_refval as $key => $value) {
										if ($value['id'] == $fk_comune_residenza) {
											$fk_comune_residenza_label = $value['nome'];
										}
									}
									?>
   								<label for="fk_comune_residenza"><b style="color:#990000">(*)</b>Comune Residenza <?php echo form_error('fk_comune_residenza') ?></label>

   								<span class="arrow_datalist">
   									<a style="cursor:pointer" onclick="pulisciCampo('fk_comune_residenza_datalist_inp'<?php if ($winForm == 'TRUE') echo ',\'' . $dv_fk_comune_residenza . '\''; ?>)"><i class="fa fa-repeat" style="padding: 2px 4px;border:1px solid #ccc;"></i></a>
   									<input autofocus class="form-control" autocomplete="off" list='fk_comune_residenza_datalist' oninput='onInput("fk_comune_residenza_datalist_inp","fk_comune_residenza_datalist","fk_comune_residenza"<?php if ($winForm == "TRUE") echo ",\"winform\""; ?>)' style="width:100%;padding: 6px 12px;font-size:14px;
									border-top-right-radius:0px;border-bottom-right-radius:0px;
									border-top-left-radius:0px;border-bottom-left-radius:0px;
									border:1px solid #ccc" name='fk_comune_residenza_datalist_inp' id='fk_comune_residenza_datalist_inp' value='<?php echo $fk_comune_residenza_label; ?>'></span>

   								<input type="hidden" name='fk_comune_residenza' id='fk_comune_residenza' value='<?php echo $fk_comune_residenza; ?>'>

   								<datalist name='fk_comune_residenza_datalist' id='fk_comune_residenza_datalist'>


   									<OPTION VALUE></OPTION>
   									<?php
										foreach ($fk_comune_residenza_refval as $key => $value) {
											if ($value['id'] == $fk_comune_residenza) {
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
   								<label for="varchar"><b style="color:#990000">(*)</b>Indirizzo <?php echo form_error('indirizzo') ?></label>
   								<div class="input-group">
   									<div class="input-group-addon"><i class="fa fa-location-dot"></i></div>
   									<input type="text" class="form-control" maxlength='100' name="indirizzo" id="indirizzo" placeholder="Indirizzo" autocomplete="off" value="<?php echo $indirizzo; ?>" />
   								</div>
   							</div>
   						</div>
   						<div class="col-md-4">
   							<div class="form-group">
   								<label for="varchar"><b style="color:#990000">(*)</b>Codice Fiscale <?php echo form_error('codfiscale') ?></label>
   								<div class="input-group">
   									<div class="input-group-addon"><i class="fa fa-notes-medical"></i></div>
   									<input type="text" class="form-control" maxlength='16' name="codfiscale" id="codfiscale" placeholder="Codice Fiscale" autocomplete="off" value="<?php echo $codfiscale; ?>" />
   								</div>
   							</div>
   						</div>
   						<div class="col-md-4">
   							<div class="form-group">
   								<label for="documento">Documento Identita <?php echo form_error('documento') ?></label>
   								<div class="input-group">
   									<div class="input-group-addon"><i class="fa fa-passport"></i></div>
   									<input type="hidden" name="documento_hidden" id="documento_hidden" value='<?php echo ($documento != '') ? 'BLOB_SET' : '' ?>' />
   									<input type="hidden" name="nome_documento" id="nome_documento" value='<?php echo ($nome_documento)   ?>' />
   									<input type="file" class="form-control" name="documento" id="documento" />
   								</div>
   							</div>
   							<?php
								if ($documento != '') {
									if ($id != '') {
										echo "<div id=\"dv_allegati_blob_documento\">
									<div id=\"dv_allegati_blobErr_documento\" style=\"display:none;font-size:18px\" class=\"alert alert-error alert-dismissable\" onclick=\"hideMsg('dv_allegati_blobErr_documento')\">
									</div>										
									<a href='" . base_url() . "mod_anagrafica/scaricaAllegatoBlob/mod_anagrafica/documento/" . $id . "' target='_blank'>Scarica Allegato</a>";
										echo "<br><a style='cursor:pointer' onclick=\"rimuoviAllegatoBlob('mod_anagrafica', 'documento','$id','FIELD_BLOB')\"><img src=\"" . base_url() . "assets/images/delete2.png\" width='32'/> Elimina allegato</a></div>";
									} else {
										echo "<b style='color:#990000'>(Eventuali allegati caricati, sono disponibili in modifica)</b>";
									}
								}
								?>
   						</div>

   						<div class="col-md-4">
   							<div class="form-group">
   								<label for="varchar">Nr Documento <?php echo form_error('nr_documento') ?></label>
   								<div class="input-group">
   									<div class="input-group-addon"><i class="fa fa-id-card"></i></div>
   									<input type="text" class="form-control" maxlength='50' name="nr_documento" id="nr_documento" placeholder="Nr Documento" autocomplete="off" value="<?php echo $nr_documento; ?>" />
   								</div>
   							</div>
   						</div>
   						<div class="col-md-4">
   							<div class="form-group">
   								<label for="tipo_documento">Tipo Documento <?php echo form_error('tipo_documento') ?></label>
   								<SELECT name='tipo_documento' id='tipo_documento' class="form-control" style="width:100%;padding: 6px 12px;font-size:14px;
									border-top-right-radius:0px;border-bottom-right-radius:0px;
									border-top-left-radius:0px;border-bottom-left-radius:0px;
									border:1px solid #ccc">
   									<OPTION <?php echo (trim($tipo_documento) == '' ? 'SELECTED' : ''); ?> VALUE=''></OPTION>
   									<OPTION <?php echo ($tipo_documento == 'DOCUMENTO IDENTITA' ? 'SELECTED' : ''); ?> VALUE='DOCUMENTO IDENTITA'>DOCUMENTO IDENTITA</OPTION>
   									<OPTION <?php echo ($tipo_documento == 'PATENTE' ? 'SELECTED' : ''); ?> VALUE='PATENTE'>PATENTE</OPTION>
   									<OPTION <?php echo ($tipo_documento == 'CODICE FISCALE' ? 'SELECTED' : ''); ?> VALUE='CODICE FISCALE'>CODICE FISCALE</OPTION>
   								</SELECT>
   							</div>
   						</div>

   						<div class="col-md-4">
   							<div class="form-group">
   								<label for="varchar">Cellulare <?php echo form_error('cellulare') ?></label>
   								<div class="input-group">
   									<div class="input-group-addon"><i class="fa fa-mobile"></i></div>
   									<input type="text" class="form-control" maxlength='50' name="cellulare" id="cellulare" placeholder="Cellulare" autocomplete="off" value="<?php echo $cellulare; ?>" />
   								</div>
   							</div>
   						</div>
   						<div class="col-md-4">
   							<div class="form-group">
   								<label for="varchar">Telefono <?php echo form_error('telefono') ?></label>
   								<div class="input-group">
   									<div class="input-group-addon"><i class="fa fa-phone"></i></div>
   									<input type="text" class="form-control" maxlength='50' name="telefono" id="telefono" placeholder="Telefono" autocomplete="off" value="<?php echo $telefono; ?>" />
   								</div>
   							</div>
   						</div>
   						<div class="col-md-4">
   							<div class="form-group">
   								<label for="varchar">E-Mail <?php echo form_error('email') ?></label>
   								<div class="input-group">
   									<div class="input-group-addon"><i class="fa fa-at"></i></div>
   									<input type="text" class="form-control" maxlength='100' name="email" id="email" placeholder="E-Mail" autocomplete="off" value="<?php echo $email; ?>" />
   								</div>
   							</div>
   						</div>
   						<div class="col-md-4">
   							<div class="form-group">
   								<label for="doc_domanda_ammissione_socio">Domanda Ammissione Socio <?php echo form_error('doc_domanda_ammissione_socio') ?></label>
   								<div class="input-group">
   									<div class="input-group-addon"><i class="fa fa-file-contract"></i></div>
   									<input type="hidden" name="doc_domanda_ammissione_socio_hidden" id="doc_domanda_ammissione_socio_hidden" value='<?php echo ($doc_domanda_ammissione_socio != '') ? 'BLOB_SET' : '' ?>' />
   									<input type="file" class="form-control" name="doc_domanda_ammissione_socio" id="doc_domanda_ammissione_socio" />
   								</div>
   							</div>
   							<?php
								if ($doc_domanda_ammissione_socio != '') {
									if ($id != '') {
										echo "<div id=\"dv_allegati_blob_doc_domanda_ammissione_socio\">
									<div id=\"dv_allegati_blobErr_doc_domanda_ammissione_socio\" style=\"display:none;font-size:18px\" class=\"alert alert-error alert-dismissable\" onclick=\"hideMsg('dv_allegati_blobErr_doc_domanda_ammissione_socio')\">
									</div>										
									<a href='" . base_url() . "mod_anagrafica/scaricaAllegatoBlob/mod_anagrafica/doc_domanda_ammissione_socio/" . $id . "' target='_blank'>Scarica Allegato</a>";
										echo "<br><a style='cursor:pointer' onclick=\"rimuoviAllegatoBlob('mod_anagrafica', 'doc_domanda_ammissione_socio','$id','FIELD_BLOB')\"><img src=\"" . base_url() . "assets/images/delete2.png\" width='32'/> Elimina allegato</a></div>";
									} else {
										echo "<b style='color:#990000'>(Eventuali allegati caricati, sono disponibili in modifica)</b>";
									}
								}
								?>
   						</div>
   						<div class="col-md-4">
   							<?php
								$dv_fk_tutore = "dv_fk_tutore";
								if ($winForm == "TRUE") {
									$dv_fk_tutore = "dv_fk_tutore_winform";
								}
								?>
   							<div class="form-group" id="<?php echo $dv_fk_tutore; ?>">
   								<label for="int">Tutore <?php echo form_error('fk_tutore') ?></label>
   								<!-- <a style="cursor:pointer" onclick="pulisciCampo('fk_tutore_datalist_inp'<?php if ($winForm == 'TRUE') echo ',\'' . $dv_fk_tutore . '\''; ?>)"><i class="fa fa-repeat" style="padding: 2px 4px;border:1px solid #ccc;"></i></a> -->
								<a style="cursor:pointer" onclick="caricaTutori()"><i class="fa fa-repeat" style="padding: 2px 4px;border:1px solid #ccc;"></i></a>
								<a title="Nuovo tutore" style="cursor:pointer" href="<?php echo base_url()."mod_anagrafica/?add_new=Y"; ?>" target="_blank"><i class="fa fa-plus" style="padding: 2px 4px;border:1px solid #ccc;"></i></a>
								<?php 
									if(($fk_tutore != "") && ($fk_tutore != "0")){
								?>
								<a style="cursor:pointer" title="Vai all'anagrafica del tutore" href="<?php echo base_url()."mod_anagrafica/?id=".$fk_tutore; ?>" target="_blank"><i class="fa fa-edit" style="padding: 2px 4px;border:1px solid #ccc;"></i></a>
								<?php } ?>	  								
								<a title="Genera tutore fittizio" style="cursor:pointer" onclick="genTutore()"><i class="fa fa-flask" style="padding: 2px 4px;border:1px solid #ccc;"></i></a>
   								
								<div class="input-group">

   									<div class="input-group-addon"><i class="fa fa-user-group"></i></div>


   									<?php
										$fk_tutore_label = NULL;
										foreach ($fk_tutore_refval as $key => $value) {
											if ($value['id'] == $fk_tutore) {
												$fk_tutore_label = $value['nome'];
											}
										}
									?>

   									<span class="arrow_datalist">

   										<input autofocus class="form-control" autocomplete="off" list='fk_tutore_datalist' oninput='onInput("fk_tutore_datalist_inp","fk_tutore_datalist","fk_tutore")' style="width:100%;padding: 6px 12px;font-size:14px;
									border-top-right-radius:0px;border-bottom-right-radius:0px;
									border-top-left-radius:0px;border-bottom-left-radius:0px;
									border:1px solid #ccc" name='fk_tutore_datalist_inp' id='fk_tutore_datalist_inp' value='<?php echo $fk_tutore_label; ?>'></span>

   									<input type="hidden" name='fk_tutore' id='fk_tutore' value='<?php echo $fk_tutore; ?>'>

   									<datalist name='fk_tutore_datalist' id='fk_tutore_datalist'>
   										<?php
											foreach ($fk_tutore_refval as $key => $value) {
												if ($value['id'] == $fk_tutore) {
													echo "<option data-value='" . $value['id'] . "' SELECTED>" . $value['nome'] . "</option>";
												} else {
													echo "<option data-value='" . $value['id'] . "'>" . $value['nome'] . "</option>";
												}
											}
											?>

   								</div>
   							</div>
   						</div>
   						<div class="col-md-4">
   							<div class="form-group">
   								<label for="sottoposto_regime_green_pass"><b style="color:#990000">(*)</b>Sottoposto A Regime Di Green Pass <?php echo form_error('sottoposto_regime_green_pass') ?></label>
   								<SELECT name='sottoposto_regime_green_pass' id='sottoposto_regime_green_pass' class="form-control" style="width:100%;padding: 6px 12px;font-size:14px;
									border-top-right-radius:0px;border-bottom-right-radius:0px;
									border-top-left-radius:0px;border-bottom-left-radius:0px;
									border:1px solid #ccc">
   									<OPTION <?php echo ($sottoposto_regime_green_pass == 'SI' ? 'SELECTED' : ''); ?> VALUE='SI'>SI</OPTION>
   									<OPTION <?php echo ($sottoposto_regime_green_pass == 'NO' ? 'SELECTED' : ''); ?> VALUE='NO'>NO</OPTION>
   									<OPTION <?php echo ($sottoposto_regime_green_pass == 'ESENTATO' ? 'SELECTED' : ''); ?> VALUE='ESENTATO'>ESENTATO</OPTION>
   								</SELECT>
   							</div>
   						</div>
   						<div class="col-md-4">
   							<div class="form-group">
   								<label for="anagrafica_attributo"><u><b style="color:#990000">(*)</b>Attributo Anagrafica <?php echo form_error('anagrafica_attributo') ?></u></label><br>
   								<?php
									//print'<pre>ARRAY:';print_r($anagrafica_attributo);
									if (!is_array($anagrafica_attributo)) {
										$anagrafica_attributo_arr = explode(',', $anagrafica_attributo);
									} else {
										$anagrafica_attributo_arr = $anagrafica_attributo;
									}

									if (in_array('ALLIEVO', $anagrafica_attributo_arr)) {
										echo "<input name='anagrafica_attributo[]' id='anagrafica_attributo[]' type='checkbox' value='ALLIEVO' checked> <b>ALLIEVO </b><br>";
									} else {
										echo "<input name='anagrafica_attributo[]' id='anagrafica_attributo[]' type='checkbox' value='ALLIEVO'  ><b>ALLIEVO </b><br>";
									}
									if (in_array('INSEGNANTE', $anagrafica_attributo_arr)) {
										echo "<input name='anagrafica_attributo[]' id='anagrafica_attributo[]' type='checkbox' value='INSEGNANTE' checked> <b>INSEGNANTE </b><br>";
									} else {
										echo "<input name='anagrafica_attributo[]' id='anagrafica_attributo[]' type='checkbox' value='INSEGNANTE'  ><b>INSEGNANTE </b><br>";
									}
									if (in_array('DIRETTIVO', $anagrafica_attributo_arr)) {
										echo "<input name='anagrafica_attributo[]' id='anagrafica_attributo[]' type='checkbox' value='DIRETTIVO' checked> <b>DIRETTIVO </b><br>";
									} else {
										echo "<input name='anagrafica_attributo[]' id='anagrafica_attributo[]' type='checkbox' value='DIRETTIVO'  ><b>DIRETTIVO </b><br>";
									}
									if (in_array('COLLABORATORE', $anagrafica_attributo_arr)) {
										echo "<input name='anagrafica_attributo[]' id='anagrafica_attributo[]' type='checkbox' value='COLLABORATORE' checked> <b>COLLABORATORE </b><br>";
									} else {
										echo "<input name='anagrafica_attributo[]' id='anagrafica_attributo[]' type='checkbox' value='COLLABORATORE'  ><b>COLLABORATORE </b><br>";
									}
									?>
   							</div>
   						</div>


   						<div class="col-md-4">
   							<div class="form-group">
   								<label for="notetesto">Note <?php echo form_error('notetesto') ?></label>
   								<textarea class="form-control" rows="3" name="notetesto" id="notetesto" placeholder="Note"><?php echo $notetesto; ?></textarea>
   							</div>
   						</div>


   						<div class="col-md-12">
   							<div class="form-group">
   								<label for="firma">Firma <?php echo form_error('firma') ?> </label>
   								<div class="input-group">
   									<div class="input-group-addon"><i class="fa fa-pencil"></i></div>
   									<input type="hidden" name="firma_hidden" id="firma_hidden" value='<?php echo $firma;?>' />
									<input type="hidden" name="nome_img_firma" id="nome_img_firma" value='<?php echo $nome_img_firma;?>' />

   									<input type="file" class="form-control" name="firma" id="firma"  />
   								</div>
   							</div>
   							<?php
								if ($firma != '') {
									echo '<div id="dv_allegati_blob_firma">
								<div id="dv_allegati_blobErr_firma" style="display:none;font-size:18px" class="alert alert-error alert-dismissable" onclick="hideMsg(\'dv_allegati_blobErr_firma\')">
								</div>								
								<div id="dv_firma"><img name="img_firma" id="img_firma" src="data:image/jpeg;base64,' . $firma . '" style="width:450px;height:150px"  /></div> ';
									echo "<br/><a style='cursor:pointer' onclick=\"rimuoviFirma()\"><img src=\"" . base_url() . "assets/images/delete2.png\" width='32'/>Elimina Firma</a></div><br><br>";
								} else {
									echo '<div id="dv_allegati_blob_firma">
								<div id="dv_allegati_blobErr_firma" style="display:none;font-size:18px" class="alert alert-error alert-dismissable" onclick="hideMsg(\'dv_allegati_blobErr_firma\')">
								</div>								
								<div id="dv_firma"><img id="img_firma" src="' . base_url() . 'assets/images/firma_default.png" style="width:450px;height:150px"  /></div>';
									echo "<br/></div>";
								}   
								?>
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
												echo "<li id='allegato_" . $count . "'><a href='" . base_url() . "mod_anagrafica/scaricaAllegato/mod_anagrafica/" . $id . "/" . $nomeAllegato . "' target='_blank'>" . $allegato['allegato'] . "</a>";
												echo "<a style='cursor:pointer' onclick=\"rimuoviAllegato('mod_anagrafica', '$id','$nomeAllegato','allegato_$count')\"> | " . $icon_delete . "</a></li>";
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
											if ((isset($master_details_list)) && ($winForm == "FALSE")) {
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
											if ((isset($master_details_list)) && ($winForm == "FALSE")) {
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
			$ajaxAction = 'mod_anagrafica/create_action';
		} else {
			$ajaxAction = 'mod_anagrafica/update_action';
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


   		arrayValidationFields['winMasterDetail_mod_anagrafica_certificati_medici'] = [];
   		arrayValidationFields['winMasterDetail_mod_anagrafica_certificati_medici']['data_certificato'] = [];
   		arrayValidationFields['winMasterDetail_mod_anagrafica_certificati_medici']['data_certificato']['label'] = "Data Certificato"
   		arrayValidationFields['winMasterDetail_mod_anagrafica_certificati_medici']['data_certificato']['field_type'] = "date"
   		arrayValidationFields['winMasterDetail_mod_anagrafica_certificati_medici']['data_scadenza'] = [];
   		arrayValidationFields['winMasterDetail_mod_anagrafica_certificati_medici']['data_scadenza']['label'] = "Data Scadenza Certificato"
   		arrayValidationFields['winMasterDetail_mod_anagrafica_certificati_medici']['data_scadenza']['field_type'] = "date"
   		arrayValidationFields['winMasterDetail_mod_anagrafica_certificati_medici']['fk_anagrafica'] = [];
   		arrayValidationFields['winMasterDetail_mod_anagrafica_certificati_medici']['fk_anagrafica']['label'] = "fk_anagrafica"
   		arrayValidationFields['winMasterDetail_mod_anagrafica_certificati_medici']['fk_anagrafica']['field_type'] = "int"
   		arrayValidationFields['winMasterDetail_mod_anagrafica_certificati_medici']['tipologia'] = [];
   		arrayValidationFields['winMasterDetail_mod_anagrafica_certificati_medici']['tipologia']['label'] = "Tipologia Certificato"
   		arrayValidationFields['winMasterDetail_mod_anagrafica_certificati_medici']['tipologia']['field_type'] = "enum"
   		arrayValidationFields['winMasterDetail_mod_anagrafica_certificati_medici']['documento_upload'] = [];
   		arrayValidationFields['winMasterDetail_mod_anagrafica_certificati_medici']['documento_upload']['label'] = "Documento Upload"
   		arrayValidationFields['winMasterDetail_mod_anagrafica_certificati_medici']['documento_upload']['field_type'] = "longblob"
   		js_custom_operations_list['winMasterDetail_mod_anagrafica_certificati_medici'] = [];
   		js_custom_operations_list['winMasterDetail_mod_anagrafica_certificati_medici'].check_date = function() {
   			var ret = true;
			var data_certificato = new Date(document.getElementById('data_certificato').value);
			var data_scadenza = new Date(document.getElementById('data_scadenza').value);
   			if (data_certificato > data_scadenza) {
   				document.getElementById('msg_err').style.display = "block";
   				document.getElementById('msg_err').innerHTML = "Data Certificato non puo essere superiore a Data Scadenza Certificato";
   				ev.preventDefault();
   				ret = false;
   			}

   			return ret;
   		};


   		arrayValidationFields['winMasterDetail_mod_anagrafica_corsi'] = [];
   		arrayValidationFields['winMasterDetail_mod_anagrafica_corsi']['fk_anagrafica'] = [];
   		arrayValidationFields['winMasterDetail_mod_anagrafica_corsi']['fk_anagrafica']['label'] = "Allievo"
   		arrayValidationFields['winMasterDetail_mod_anagrafica_corsi']['fk_anagrafica']['field_type'] = "int"
   		arrayValidationFields['winMasterDetail_mod_anagrafica_corsi']['fk_corso'] = [];
   		arrayValidationFields['winMasterDetail_mod_anagrafica_corsi']['fk_corso']['label'] = "Corso"
   		arrayValidationFields['winMasterDetail_mod_anagrafica_corsi']['fk_corso']['field_type'] = "int"

   		arrayValidationFields['winMasterDetail_mod_anagrafica_green_pass'] = [];
   		arrayValidationFields['winMasterDetail_mod_anagrafica_green_pass']['data_validita_fine'] = [];
   		arrayValidationFields['winMasterDetail_mod_anagrafica_green_pass']['data_validita_fine']['label'] = "Data Validita Fine"
   		arrayValidationFields['winMasterDetail_mod_anagrafica_green_pass']['data_validita_fine']['field_type'] = "datetime"
   		arrayValidationFields['winMasterDetail_mod_anagrafica_green_pass']['data_validita_inizio'] = [];
   		arrayValidationFields['winMasterDetail_mod_anagrafica_green_pass']['data_validita_inizio']['label'] = "Data Validita Inizio"
   		arrayValidationFields['winMasterDetail_mod_anagrafica_green_pass']['data_validita_inizio']['field_type'] = "datetime"
   		arrayValidationFields['winMasterDetail_mod_anagrafica_green_pass']['documento_upload'] = [];
   		arrayValidationFields['winMasterDetail_mod_anagrafica_green_pass']['documento_upload']['label'] = "Documento Upload"
   		arrayValidationFields['winMasterDetail_mod_anagrafica_green_pass']['documento_upload']['field_type'] = "longblob"
   		arrayValidationFields['winMasterDetail_mod_anagrafica_green_pass']['fk_anagrafica'] = [];
   		arrayValidationFields['winMasterDetail_mod_anagrafica_green_pass']['fk_anagrafica']['label'] = "Allievo/Insegnante"
   		arrayValidationFields['winMasterDetail_mod_anagrafica_green_pass']['fk_anagrafica']['field_type'] = "int"
   		arrayValidationFields['winMasterDetail_mod_anagrafica_green_pass']['tipo_green_pass'] = [];
   		arrayValidationFields['winMasterDetail_mod_anagrafica_green_pass']['tipo_green_pass']['label'] = "Tipo Green Pass"
   		arrayValidationFields['winMasterDetail_mod_anagrafica_green_pass']['tipo_green_pass']['field_type'] = "enum"

   		arrayValidationFields['winMasterDetail_mod_anagrafica_green_pass_autocertificazione'] = [];
   		arrayValidationFields['winMasterDetail_mod_anagrafica_green_pass_autocertificazione']['data_autocertificazione_fine_validita'] = [];
   		arrayValidationFields['winMasterDetail_mod_anagrafica_green_pass_autocertificazione']['data_autocertificazione_fine_validita']['label'] = "Data Autocertificazione Fine Validita"
   		arrayValidationFields['winMasterDetail_mod_anagrafica_green_pass_autocertificazione']['data_autocertificazione_fine_validita']['field_type'] = "date"
   		arrayValidationFields['winMasterDetail_mod_anagrafica_green_pass_autocertificazione']['documento_upload'] = [];
   		arrayValidationFields['winMasterDetail_mod_anagrafica_green_pass_autocertificazione']['documento_upload']['label'] = "Documento Upload"
   		arrayValidationFields['winMasterDetail_mod_anagrafica_green_pass_autocertificazione']['documento_upload']['field_type'] = "longblob"

   		arrayValidationFields['winMasterDetail_mod_anagrafica_green_pass_autocertificazione']['fk_anagrafica'] = [];
   		arrayValidationFields['winMasterDetail_mod_anagrafica_green_pass_autocertificazione']['fk_anagrafica']['label'] = "Allievo/Insegnante"
   		arrayValidationFields['winMasterDetail_mod_anagrafica_green_pass_autocertificazione']['fk_anagrafica']['field_type'] = "int"

   		arrayValidationFields['winMasterDetail_mod_anagrafica_green_pass_esentati'] = [];
   		arrayValidationFields['winMasterDetail_mod_anagrafica_green_pass_esentati']['certificato_medico_esenzione'] = [];
   		arrayValidationFields['winMasterDetail_mod_anagrafica_green_pass_esentati']['certificato_medico_esenzione']['label'] = "Certificato Medico Esenzione"
   		arrayValidationFields['winMasterDetail_mod_anagrafica_green_pass_esentati']['certificato_medico_esenzione']['field_type'] = "longblob"
   		arrayValidationFields['winMasterDetail_mod_anagrafica_green_pass_esentati']['fk_anagrafica'] = [];
   		arrayValidationFields['winMasterDetail_mod_anagrafica_green_pass_esentati']['fk_anagrafica']['label'] = "Allievo/Insegnante"
   		arrayValidationFields['winMasterDetail_mod_anagrafica_green_pass_esentati']['fk_anagrafica']['field_type'] = "int"

   		arrayValidationFields['winMasterDetail_mod_anagrafica_tessere_assoc'] = [];
   		arrayValidationFields['winMasterDetail_mod_anagrafica_tessere_assoc']['fk_affiliazione'] = [];
   		arrayValidationFields['winMasterDetail_mod_anagrafica_tessere_assoc']['fk_affiliazione']['label'] = "Affiliazione"
   		arrayValidationFields['winMasterDetail_mod_anagrafica_tessere_assoc']['fk_affiliazione']['field_type'] = "int"
   		arrayValidationFields['winMasterDetail_mod_anagrafica_tessere_assoc']['fk_anagrafica'] = [];
   		arrayValidationFields['winMasterDetail_mod_anagrafica_tessere_assoc']['fk_anagrafica']['label'] = "Allievo"
   		arrayValidationFields['winMasterDetail_mod_anagrafica_tessere_assoc']['fk_anagrafica']['field_type'] = "int"
   		arrayValidationFields['winMasterDetail_mod_anagrafica_tessere_assoc']['tessera_associativa'] = [];
   		arrayValidationFields['winMasterDetail_mod_anagrafica_tessere_assoc']['tessera_associativa']['label'] = "Tessera Associativa"
   		arrayValidationFields['winMasterDetail_mod_anagrafica_tessere_assoc']['tessera_associativa']['field_type'] = "varchar"

   		arrayValidationFields['winMasterDetail_mod_anagrafica_tessere_interne'] = [];
   		arrayValidationFields['winMasterDetail_mod_anagrafica_tessere_interne']['fk_anagrafica'] = [];
   		arrayValidationFields['winMasterDetail_mod_anagrafica_tessere_interne']['fk_anagrafica']['label'] = "Allievo"
   		arrayValidationFields['winMasterDetail_mod_anagrafica_tessere_interne']['fk_anagrafica']['field_type'] = "int"
   		arrayValidationFields['winMasterDetail_mod_anagrafica_tessere_interne']['fk_esercizio'] = [];
   		arrayValidationFields['winMasterDetail_mod_anagrafica_tessere_interne']['fk_esercizio']['label'] = "Esercizio"
   		arrayValidationFields['winMasterDetail_mod_anagrafica_tessere_interne']['fk_esercizio']['field_type'] = "int"
   		arrayValidationFields['winMasterDetail_mod_anagrafica_tessere_interne']['tessera_interna'] = [];
   		arrayValidationFields['winMasterDetail_mod_anagrafica_tessere_interne']['tessera_interna']['label'] = "Tessera Interna"
   		arrayValidationFields['winMasterDetail_mod_anagrafica_tessere_interne']['tessera_interna']['field_type'] = "varchar"

   		arrayValidationFields['winMasterDetail_mod_corsi_insegnanti'] = [];
   		arrayValidationFields['winMasterDetail_mod_corsi_insegnanti']['fk_anagrafica'] = [];
   		arrayValidationFields['winMasterDetail_mod_corsi_insegnanti']['fk_anagrafica']['label'] = "Insegnante"
   		arrayValidationFields['winMasterDetail_mod_corsi_insegnanti']['fk_anagrafica']['field_type'] = "int"
   		arrayValidationFields['winMasterDetail_mod_corsi_insegnanti']['fk_corso'] = [];
   		arrayValidationFields['winMasterDetail_mod_corsi_insegnanti']['fk_corso']['label'] = "Corso"
   		arrayValidationFields['winMasterDetail_mod_corsi_insegnanti']['fk_corso']['field_type'] = "int"

   		arrayValidationFields['winMasterDetail_mod_corsi_iscrizioni'] = [];
   		arrayValidationFields['winMasterDetail_mod_corsi_iscrizioni']['anno'] = [];
   		arrayValidationFields['winMasterDetail_mod_corsi_iscrizioni']['anno']['label'] = "Anno"
   		arrayValidationFields['winMasterDetail_mod_corsi_iscrizioni']['anno']['field_type'] = "enum"
   		arrayValidationFields['winMasterDetail_mod_corsi_iscrizioni']['fk_anagrafica'] = [];
   		arrayValidationFields['winMasterDetail_mod_corsi_iscrizioni']['fk_anagrafica']['label'] = "Allievo"
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

   		arrayValidationFields['winMasterDetail_mod_insegnanti_discipline'] = [];
   		/*js_custom_operations_list['winMasterDetail_mod_insegnanti_discipline'] = [];
			js_custom_operations_list['winMasterDetail_mod_insegnanti_discipline'].check_date = function(){
				var ret = check_date_greater_then('data_validita_inizio','data_validita_fine');
				if(ret === false){
					document.getElementById('msg_err').style.display = "block";
            		document.getElementById('msg_err').innerHTML = "Date non valide";
            		ev.preventDefault();
				}
			};*/
   		arrayValidationFields['winMasterDetail_mod_insegnanti_discipline']['fk_anagrafica'] = [];
   		arrayValidationFields['winMasterDetail_mod_insegnanti_discipline']['fk_anagrafica']['label'] = "Insegnante"
   		arrayValidationFields['winMasterDetail_mod_insegnanti_discipline']['fk_anagrafica']['field_type'] = "int"
   		arrayValidationFields['winMasterDetail_mod_insegnanti_discipline']['fk_disciplina'] = [];
   		arrayValidationFields['winMasterDetail_mod_insegnanti_discipline']['fk_disciplina']['label'] = "Disciplina"
   		arrayValidationFields['winMasterDetail_mod_insegnanti_discipline']['fk_disciplina']['field_type'] = "int"


		function genTutore(){
			showBiscuit();
			$('#fk_tutore_datalist_inp').val('');
			$.ajax({
   				url: baseURL + "/mod_anagrafica/genTutore/",
   				type: 'post',
   				dataType: 'json',
				async: false,
   				success: function(jsonData) {
					$('#fk_tutore_datalist').empty();
					$('#fk_tutore_datalist').append('<option data-value="' + jsonData.id + '" SELECTED>' + jsonData.nome_tutore_gen + '</option>');
					$('#fk_tutore_datalist').val(jsonData.nome_tutore_gen);
					$('#fk_tutore_datalist').attr("data-value", jsonData.id);
					console.log(jsonData.id + " " + jsonData.nome_tutore_gen);

					hideBiscuit();
				}
   			});
			
			;		
		}

   		function caricaTutori() {
			$('#fk_tutore_datalist_inp').val('');
   			var where_condition_str = "";
   			<?php

				if ((isset($id)) && ($id != "")) {
					echo "\nwhere_condition_str = ' WHERE TIMESTAMPDIFF(YEAR, datanascita, CURDATE()) >= 18 AND id <> $id ';";
				} else {
					echo "\nwhere_condition_str = ' WHERE TIMESTAMPDIFF(YEAR, datanascita, CURDATE()) >= 18';";
				}

				?>

   			showBiscuit();

   			$.ajax({
   				url: baseURL + "/mod_anagrafica/getKeyValuesFromTableViaPOST/",
   				type: 'post',
   				dataType: 'json',
   				data: {
   					table_referenced: 'mod_anagrafica',
   					id: 'id',
   					value: "CONCAT(nome,' ', cognome, ' ', codfiscale)",
   					where_condition: where_condition_str
   				},
   				success: function(jsonData) {
   					$('#fk_tutore_datalist').empty();
   					for (var i = 0; i < jsonData.length; i++) {
   						$('#fk_tutore_datalist').append('<option data-value="' + jsonData[i].id + '">' + jsonData[i].nome + '</option>');
   					}
   					hideBiscuit();

   				}
   			});


   		}

   		//caricaTutori();
		 
		function initCameraJs(){
			Webcam.set({
     			width: 320,
     			height: 240,
     			image_format: 'jpeg',
     			jpeg_quality: 90
 			});
 			Webcam.attach( '#my_camera' );
		}   


		function take_snapshot() {
			// take snapshot and get image data
			Webcam.snap( function(data_uri) {
				// display results in page
				document.getElementById('results_hidden').value = data_uri;
				document.getElementById('results').innerHTML = 
				'<img src="'+data_uri+'"/>';

				document.getElementById('bt_set_foto').disabled = false;
			} );

			
		}


		function openCameraWinForm(){
			initCameraJs();
			$("#modal-camera").modal();
		}

		function setFoto(){
			$("#modal-camera").modal("hide");
			console.log(document.getElementById('results_hidden').value);
			document.getElementById("foto_anagrafica").src = document.getElementById('results_hidden').value;
			document.getElementById("img_foto_hidden").value = document.getElementById('results_hidden').value;
		}


		function rimuoviFoto(){
			if(document.getElementById('foto_anagrafica')){
				document.getElementById('foto_anagrafica');		
				var image_x = document.getElementById('foto_anagrafica');
				image_x.parentNode.removeChild(image_x);
				document.getElementById("img_foto_hidden").value = "";
	
				var img = document.createElement('img');
				img.width = '250';
				img.height = '250';
				img.id = 'foto_anagrafica';
				img.name = 'foto_anagrafica';
				document.getElementById('dv_foto_anagrafica').appendChild(img);				
			}


		}


		function rimuoviFirma(){
			if(document.getElementById('img_firma')){
				document.getElementById('img_firma');		
				var image_x = document.getElementById('img_firma');
				image_x.parentNode.removeChild(image_x);
				document.getElementById("firma_hidden").value = "";
	
				var img = document.createElement('img');
				img.width = '450';
				img.height = '150';
				img.id = 'img_firma';
				img.name = 'img_firma';
				document.getElementById('dv_firma').appendChild(img);				
			}


		}		
   	</script>
   	<script src="<?php echo base_url(); ?>assets/js/form_submit_ajax.config.js"></script>
   </div>



<div class="modal" tabindex="-1" role="dialog" id='modal-camera'  role="document">
  <div class="modal-dialog" role="document" style="width: 60%; max-width: 70%;">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id='dlg_title_camera'><i class="fa fa-camera"></i> <b>Scatta foto</b></h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
		<table style="width:100%">
			<tr>
				<td><div id="my_camera"></div></td>
				<td><div id="results" ></div></td>
			</tr>

		</table>
		<br><input type=button value="Scatta Foto" class="btn btn-info"  onClick="take_snapshot()">
		<input type ="hidden" name="results_hidden" id="results_hidden" />

 	  


      </div>
		<div class="modal-footer">
			<div id="dv_delete_allegato_blob" style="float: right;"><button onclick="setFoto()" type="button" class="btn btn-info" id="bt_set_foto" disabled>IMPOSTA FOTO</button></div>
			<div style="float: left;"><button type="button" class="btn btn-secondary" id="bt_cancel" data-dismiss="modal">ANNULLA	</button></div>
		</div>
    </div>
  </div>
</div>

