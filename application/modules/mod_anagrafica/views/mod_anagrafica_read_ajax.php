   <div id="main_content_ajax_form_read_$id">
   	<!-- Content Header (Page header) -->
   	<div class="col-md-8">
   		<h3>
   			<i class="fa fa-cubes"></i>
   			<a>
   				<u> Anagrafica</u>
   			</a>

   		</h3>

   	</div>
   	<div class="row">
   		<!-- left column -->
   		<div class="col-md-12">
   			<!-- general form elements -->
   			<div class="box box-primary">
   				<!-- /.box-header -->
   				<!-- form start -->

   				<div class="box-body">
 
   					<div class="col-md-4">
   						<div class="form-group">
   							<label for="varchar"><b style="color:#990000">(*)</b>Nome <?php echo form_error('nome') ?></label>
   							<div class="input-group">
   								<div class="input-group-addon"><i class="fa fa-text-height"></i></div>
   								<input disabled='disabled' type="text" class="form-control" maxlength='50' name="nome" id="nome" placeholder="Nome" autocomplete="off" value="<?php echo $nome; ?>" />
   							</div>
   						</div>
   					</div>
   					<div class="col-md-4">
   						<div class="form-group">
   							<label for="varchar"><b style="color:#990000">(*)</b>Cognome <?php echo form_error('cognome') ?></label>
   							<div class="input-group">
   								<div class="input-group-addon"><i class="fa fa-text-height"></i></div>
   								<input disabled='disabled' type="text" class="form-control" maxlength='50' name="cognome" id="cognome" placeholder="Cognome" autocomplete="off" value="<?php echo $cognome; ?>" />
   							</div>
   						</div>
   					</div>
   					<div class="col-md-4">
   						<div class="form-group">
   							<label for="date"><b style="color:#990000">(*)</b>Data Di Nascita <?php echo form_error('datanascita') ?></label>
   							<div class="input-group">
   								<div class="input-group-addon"><i class="fa fa-calendar"></i></div>
   								<input disabled='disabled' type="text" class="form-control datemask" name="datanascita" id="datanascita" placeholder="Data Di Nascita" autocomplete="off" style="background-color:#FFFFFF" readonly="readonly" value="<?php echo $datanascita; ?>" />
   							</div>
   						</div>
   					</div>
   					<div class="col-md-4">
   						<div class="form-group">
   							<label for="sesso"><b style="color:#990000">(*)</b>Sesso <?php echo form_error('sesso') ?></label>
   							<SELECT disabled='disabled' name='sesso' id='sesso' class="form-control">
   								<OPTION VALUE></OPTION>
   								<OPTION VALUE='F'>F</OPTION>
   								<OPTION VALUE='M'>M</OPTION>
   							</SELECT>
   						</div>
   						<script>
   							$('[name=sesso] option').filter(function() {
   								return ($(this).text() == '<?php echo $sesso ?>'); //To select Blue
   							}).prop('selected', true);
   						</script>
   					</div>
   					<div class="col-md-4">
   						<div class="form-group">
   							<label for="fk_comune_nascita"><b style="color:#990000">(*)</b>Comune Di Nascita <?php echo form_error('fk_comune_nascita') ?></label>
   							<SELECT disabled='disabled' name='fk_comune_nascita' id='fk_comune_nascita' class="form-control">
   								<OPTION VALUE></OPTION>
   								<?php
									foreach ($fk_comune_nascita_refval as $key => $value) {
										if ($value['id'] == $fk_comune_nascita) {
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
   							<label for="fk_comune_residenza"><b style="color:#990000">(*)</b>Comune Residenza <?php echo form_error('fk_comune_residenza') ?></label>
   							<SELECT disabled='disabled' name='fk_comune_residenza' id='fk_comune_residenza' class="form-control">
   								<OPTION VALUE></OPTION>
   								<?php
									foreach ($fk_comune_residenza_refval as $key => $value) {
										if ($value['id'] == $fk_comune_residenza) {
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
   							<label for="varchar"><b style="color:#990000">(*)</b>Indirizzo <?php echo form_error('indirizzo') ?></label>
   							<div class="input-group">
   								<div class="input-group-addon"><i class="fa fa-text-height"></i></div>
   								<input disabled='disabled' type="text" class="form-control" maxlength='100' name="indirizzo" id="indirizzo" placeholder="Indirizzo" autocomplete="off" value="<?php echo $indirizzo; ?>" />
   							</div>
   						</div>
   					</div>
   					<div class="col-md-4">
   						<div class="form-group">
   							<label for="varchar"><b style="color:#990000">(*)</b>Codice Fiscale <?php echo form_error('codfiscale') ?></label>
   							<div class="input-group">
   								<div class="input-group-addon"><i class="fa fa-text-height"></i></div>
   								<input disabled='disabled' type="text" class="form-control" maxlength='16' name="codfiscale" id="codfiscale" placeholder="Codice Fiscale" autocomplete="off" value="<?php echo $codfiscale; ?>" />
   							</div>
   						</div>
   					</div>
 
   					<div class="col-md-4">
   						<div class="form-group">
   							<label for="varchar">Cellulare <?php echo form_error('cellulare') ?></label>
   							<div class="input-group">
   								<div class="input-group-addon"><i class="fa fa-text-height"></i></div>
   								<input disabled='disabled' type="text" class="form-control" maxlength='50' name="cellulare" id="cellulare" placeholder="Cellulare" autocomplete="off" value="<?php echo $cellulare; ?>" />
   							</div>
   						</div>
   					</div>
   					<div class="col-md-4">
   						<div class="form-group">
   							<label for="varchar">Telefono <?php echo form_error('telefono') ?></label>
   							<div class="input-group">
   								<div class="input-group-addon"><i class="fa fa-text-height"></i></div>
   								<input disabled='disabled' type="text" class="form-control" maxlength='50' name="telefono" id="telefono" placeholder="Telefono" autocomplete="off" value="<?php echo $telefono; ?>" />
   							</div>
   						</div>
   					</div>
   					<div class="col-md-4">
   						<div class="form-group">
   							<label for="varchar">E-Mail <?php echo form_error('email') ?></label>
   							<div class="input-group">
   								<div class="input-group-addon"><i class="fa fa-text-height"></i></div>
   								<input disabled='disabled' type="text" class="form-control" maxlength='100' name="email" id="email" placeholder="E-Mail" autocomplete="off" value="<?php echo $email; ?>" />
   							</div>
   						</div>
   					</div>
   					<div class="col-md-4">
   						<div class="form-group">
   							<label for="longblob">Domanda Ammissione Socio <?php echo form_error('doc_domanda_ammissione_socio') ?></label>
   							<?php
								if ($doc_domanda_ammissione_socio != '') {
									echo '<img src="data:image/jpeg;base64,' . $doc_domanda_ammissione_socio . '" style="width:90px"  />';
								}
								?>

   							<div class="input-group">
   								<div class="input-group-addon"><i class="fa fa-text-height"></i></div> <input disabled='disabled' type="file" class="form-control" name="doc_domanda_ammissione_socio" id="doc_domanda_ammissione_socio" />
   							</div>
   						</div>
   					</div>
   					<div class="col-md-4">
   						<div class="form-group">
   							<label for="int">Tutore <?php echo form_error('fk_tutore') ?></label>
   							<div class="input-group">
   								<div class="input-group-addon"><i class="fa fa-sort-numeric-asc"></i></div>
   								<input disabled='disabled' type="number" class="form-control" maxlength='10' name="fk_tutore" id="fk_tutore" placeholder="Tutore" autocomplete="off" value="<?php echo $fk_tutore; ?>" />
   							</div>
   						</div>
   					</div>
   					<div class="col-md-4">
   						<div class="form-group">
   							<label for="sottoposto_regime_green_pass"><b style="color:#990000">(*)</b>Sottoposto A Regime Di Green Pass <?php echo form_error('sottoposto_regime_green_pass') ?></label>
   							<SELECT disabled='disabled' name='sottoposto_regime_green_pass' id='sottoposto_regime_green_pass' class="form-control">
   								<OPTION VALUE></OPTION>
   								<OPTION VALUE='SI'>SI</OPTION>
   								<OPTION VALUE='NO'>NO</OPTION>
   								<OPTION VALUE='ESENTATO'>ESENTATO</OPTION>
   							</SELECT>
   						</div>
   						<script>
   							$('[name=sottoposto_regime_green_pass] option').filter(function() {
   								return ($(this).text() == '<?php echo $sottoposto_regime_green_pass ?>'); //To select Blue
   							}).prop('selected', true);
   						</script>
   					</div>
   					<div class="col-md-4">
   						<div class="form-group">
   							<label for="anagrafica_attributo"><b style="color:#990000">(*)</b>Attributo Anagrafica <?php echo form_error('anagrafica_attributo') ?></label>
   							<b>ALUNNO</b><input disabled='disabled' name='anagrafica_attributo' id='anagrafica_attributo' type="checkbox" value='ALUNNO'>
   							<b>INSEGNANTE</b><input disabled='disabled' name='anagrafica_attributo' id='anagrafica_attributo' type="checkbox" value='INSEGNANTE'>
   							<b>DIRETTIVO</b><input disabled='disabled' name='anagrafica_attributo' id='anagrafica_attributo' type="checkbox" value='DIRETTIVO'>
   							<b>COLLABORATORE</b><input disabled='disabled' name='anagrafica_attributo' id='anagrafica_attributo' type="checkbox" value='COLLABORATORE'>
   						</div>
   					</div>
   					<div class="col-md-4">
   						<div class="form-group">
   							<label for="notetesto">Note <?php echo form_error('notetesto') ?></label>
   							<textarea disabled='disabled' class="form-control" rows="3" name="notetesto" id="notetesto" placeholder="Note"><?php echo $notetesto; ?></textarea>
   						</div>
   					</div>
   				</div>

   			</div>
   		</div>
   	</div>
   </div>