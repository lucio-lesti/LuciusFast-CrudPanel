   
		<div id="main_content_ajax_form_read_$id">	
			<!-- Content Header (Page header) -->
				<div class="col-md-8">
				<h3>
					<i class="fa fa-cubes"></i> 
					<a>
					<u> Contratti Collaboratori</u>
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
							<label for="varchar"><b style="color:#990000">(*)</b>Nome Contratto <?php echo form_error('nome') ?></label>
											<div class="input-group">
												<div class="input-group-addon"><i class="fa fa-text-height"></i></div>			
							<input disabled='disabled' type="text" class="form-control" maxlength='50' name="nome" id="nome" placeholder="Nome Contratto" autocomplete="off" value="<?php echo $nome; ?>" />
						</div></div></div><div class="col-md-4">
	    <div class="form-group">
						<label for="fk_anagrafica"><b style="color:#990000">(*)</b>Collaboratore <?php echo form_error('fk_anagrafica') ?></label>
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
							<label for="varchar"><b style="color:#990000">(*)</b>Mansione <?php echo form_error('mansione') ?></label>
											<div class="input-group">
												<div class="input-group-addon"><i class="fa fa-text-height"></i></div>			
							<input disabled='disabled' type="text" class="form-control" maxlength='255' name="mansione" id="mansione" placeholder="Mansione" autocomplete="off" value="<?php echo $mansione; ?>" />
						</div></div></div><div class="col-md-4">
	    <div class="form-group">
							<label for="date"><b style="color:#990000">(*)</b>Data Da <?php echo form_error('data_da') ?></label>
											<div class="input-group">
												<div class="input-group-addon"><i class="fa fa-calendar"></i></div>			
							<input  disabled='disabled' type="text" class="form-control datemask" name="data_da" id="data_da" placeholder="Data Da" 
								autocomplete="off" style="background-color:#FFFFFF" readonly="readonly" value="<?php echo $data_da; ?>" />
						</div></div></div><div class="col-md-4">
	    <div class="form-group">
							<label for="date"><b style="color:#990000">(*)</b>Data A <?php echo form_error('data_a') ?></label>
											<div class="input-group">
												<div class="input-group-addon"><i class="fa fa-calendar"></i></div>			
							<input  disabled='disabled' type="text" class="form-control datemask" name="data_a" id="data_a" placeholder="Data A" 
								autocomplete="off" style="background-color:#FFFFFF" readonly="readonly" value="<?php echo $data_a; ?>" />
						</div></div></div><div class="col-md-4">
	    <div class="form-group">
							<label for="float"><b style="color:#990000">(*)</b>Importo Mensile <?php echo form_error('importo_mensile') ?></label>
										<div class="input-group">
											<div class="input-group-addon"><i class="fa fa-sort-numeric-asc"></i></div>			
							<input disabled='disabled' type="number" class="form-control" maxlength='9' name="importo_mensile" id="importo_mensile" placeholder="Importo Mensile" autocomplete="off" value="<?php echo $importo_mensile; ?>" />
							</div></div></div><div class="col-md-4">
	    <div class="form-group">
							<label for="tipo_pagamento"><b style="color:#990000">(*)</b>Tipo Pagamento <?php echo form_error('tipo_pagamento') ?></label>
							<SELECT disabled='disabled' name='tipo_pagamento' id='tipo_pagamento' class="form-control">
<OPTION VALUE></OPTION>
<OPTION VALUE='BONIFICO'>BONIFICO</OPTION>
<OPTION VALUE='CONTANTI'>CONTANTI</OPTION></SELECT>
						</div>
						<script>	
						$('[name=tipo_pagamento] option').filter(function() { 
							return ($(this).text() == '<?php echo $tipo_pagamento?>'); //To select Blue
						}).prop('selected', true);				
						</script>
					</div><div class="col-md-4">
	    <div class="form-group">
							<label for="varchar">Banca <?php echo form_error('banca') ?></label>
											<div class="input-group">
												<div class="input-group-addon"><i class="fa fa-text-height"></i></div>			
							<input disabled='disabled' type="text" class="form-control" maxlength='255' name="banca" id="banca" placeholder="Banca" autocomplete="off" value="<?php echo $banca; ?>" />
						</div></div></div><div class="col-md-4">
	    <div class="form-group">
							<label for="varchar">IBAN <?php echo form_error('iban') ?></label>
											<div class="input-group">
												<div class="input-group-addon"><i class="fa fa-text-height"></i></div>			
							<input disabled='disabled' type="text" class="form-control" maxlength='255' name="iban" id="iban" placeholder="IBAN" autocomplete="off" value="<?php echo $iban; ?>" />
						</div></div></div>
						</div>
							
					</div>
				</div>
			</div>
		</div>