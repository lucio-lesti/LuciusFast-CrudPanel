   
		<div id="main_content_ajax_form_read_$id">	
			<!-- Content Header (Page header) -->
				<div class="col-md-8">
				<h3>
					<i class="fa fa-cubes"></i> 
					<a>
					<u> Tesseramenti</u>
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
						
							 <div class="box-body"><div class="col-md-4">
	    <div class="form-group">
						<label for="fk_anagrafica"><b style="color:#990000">(*)</b>Anagrafica <?php echo form_error('fk_anagrafica') ?></label>
						<SELECT disabled='disabled' name='fk_anagrafica' id='fk_anagrafica' class="form-control">
<OPTION VALUE></OPTION>
<?php
						foreach ($fk_anagrafica_refval as $key => $value) {
							if ($value['id'] == $fk_anagrafica) {
								echo "<option value='" . $value['id'] . "' SELECTED>" . $value['nome'] . "</option>";
							} else {
								echo "<option value='".$value['id'] . "'>" . $value['nome'] . "</option>";
							}
						}
						?></SELECT>
							</div></div><div class="col-md-4">
	    <div class="form-group">
						<label for="fk_esercizio"><b style="color:#990000">(*)</b>Esercizio <?php echo form_error('fk_esercizio') ?></label>
						<SELECT disabled='disabled' name='fk_esercizio' id='fk_esercizio' class="form-control">
<OPTION VALUE></OPTION>
<?php
						foreach ($fk_esercizio_refval as $key => $value) {
							if ($value['id'] == $fk_esercizio) {
								echo "<option value='" . $value['id'] . "' SELECTED>" . $value['nome'] . "</option>";
							} else {
								echo "<option value='".$value['id'] . "'>" . $value['nome'] . "</option>";
							}
						}
						?></SELECT>
							</div></div><div class="col-md-4">
	    <div class="form-group">
						<label for="fk_affiliazione"><b style="color:#990000">(*)</b>Affiliazione <?php echo form_error('fk_affiliazione') ?></label>
						<SELECT disabled='disabled' name='fk_affiliazione' id='fk_affiliazione' class="form-control">
<OPTION VALUE></OPTION>
<?php
						foreach ($fk_affiliazione_refval as $key => $value) {
							if ($value['id'] == $fk_affiliazione) {
								echo "<option value='" . $value['id'] . "' SELECTED>" . $value['nome'] . "</option>";
							} else {
								echo "<option value='".$value['id'] . "'>" . $value['nome'] . "</option>";
							}
						}
						?></SELECT>
							</div></div><div class="col-md-4">
	    <div class="form-group">
						<label for="fk_tessera"><b style="color:#990000">(*)</b>Tessera <?php echo form_error('fk_tessera') ?></label>
						<SELECT disabled='disabled' name='fk_tessera' id='fk_tessera' class="form-control">
<OPTION VALUE></OPTION>
<?php
						foreach ($fk_tessera_refval as $key => $value) {
							if ($value['id'] == $fk_tessera) {
								echo "<option value='" . $value['id'] . "' SELECTED>" . $value['nome'] . "</option>";
							} else {
								echo "<option value='".$value['id'] . "'>" . $value['nome'] . "</option>";
							}
						}
						?></SELECT>
							</div></div><div class="col-md-4">
	    <div class="form-group">
							<label for="date"><b style="color:#990000">(*)</b>Data Tesseramento <?php echo form_error('data_tesseramento') ?></label>
											<div class="input-group">
												<div class="input-group-addon"><i class="fa fa-calendar"></i></div>			
							<input  disabled='disabled' type="text" class="form-control datemask" name="data_tesseramento" id="data_tesseramento" placeholder="Data Tesseramento" 
								autocomplete="off" style="background-color:#FFFFFF" readonly="readonly" value="<?php echo $data_tesseramento; ?>" />
						</div></div></div><div class="col-md-4">
	    <div class="form-group">
							<label for="float"><b style="color:#990000">(*)</b>Importo <?php echo form_error('importo') ?></label>
										<div class="input-group">
											<div class="input-group-addon"><i class="fa fa-sort-numeric-asc"></i></div>			
							<input disabled='disabled' type="number" class="form-control" maxlength='9' name="importo" id="importo" placeholder="Importo" autocomplete="off" value="<?php echo $importo; ?>" />
							</div></div></div><div class="col-md-4">
	    <div class="form-group">
							<label for="modo_pagamento"><b style="color:#990000">(*)</b>Modalita Pagamento <?php echo form_error('modo_pagamento') ?></label>
							<SELECT disabled='disabled' name='modo_pagamento' id='modo_pagamento' class="form-control">
<OPTION VALUE></OPTION>
<OPTION VALUE='CONTANTI'>CONTANTI</OPTION>
<OPTION VALUE='CARTA'>CARTA</OPTION></SELECT>
						</div>
						<script>	
						$('[name=modo_pagamento] option').filter(function() { 
							return ($(this).text() == '<?php echo $modo_pagamento?>'); //To select Blue
						}).prop('selected', true);				
						</script>
					</div>
						</div>
							
					</div>
				</div>
			</div>
		</div>