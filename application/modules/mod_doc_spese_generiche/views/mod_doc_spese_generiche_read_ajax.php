   
		<div id="main_content_ajax_form_read_$id">	
			<!-- Content Header (Page header) -->
				<div class="col-md-8">
				<h3>
					<i class="fa fa-cubes"></i> 
					<a>
					<u> Documenti Spese Generiche</u>
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
							<label for="varchar">Nome <?php echo form_error('nome') ?></label>
											<div class="input-group">
												<div class="input-group-addon"><i class="fa fa-text-height"></i></div>			
							<input disabled='disabled' type="text" class="form-control" maxlength='100' name="nome" id="nome" placeholder="Nome" autocomplete="off" value="<?php echo $nome; ?>" />
						</div></div></div><div class="col-md-4">
	    <div class="form-group">
							<label for="date">Data Pagamento <?php echo form_error('data') ?></label>
											<div class="input-group">
												<div class="input-group-addon"><i class="fa fa-calendar"></i></div>			
							<input  disabled='disabled' type="text" class="form-control datemask" name="data" id="data" placeholder="Data Pagamento" 
								autocomplete="off" style="background-color:#FFFFFF" readonly="readonly" value="<?php echo $data; ?>" />
						</div></div></div><div class="col-md-4">
	    <div class="form-group">
							<label for="descrizione">Descrizione <?php echo form_error('descrizione') ?></label>
							<textarea disabled='disabled' class="form-control" rows="3" name="descrizione" id="descrizione" placeholder="Descrizione"><?php echo $descrizione; ?></textarea>
						</div></div><div class="col-md-4">
	    <div class="form-group">
						<label for="fk_tipo_spesa"><b style="color:#990000">(*)</b>Tipo Spesa <?php echo form_error('fk_tipo_spesa') ?></label>
						<SELECT disabled='disabled' name='fk_tipo_spesa' id='fk_tipo_spesa' class="form-control">
<OPTION VALUE></OPTION>
<?php
						foreach ($fk_tipo_spesa_refval as $key => $value) {
							if ($value['id'] == $fk_tipo_spesa) {
								echo "<option value='" . $value['id'] . "' SELECTED>" . $value['nome'] . "</option>";
							} else {
								echo "<option value='".$value['id'] . "'>" . $value['nome'] . "</option>";
							}
						}
						?></SELECT>
							</div></div><div class="col-md-4">
	    <div class="form-group">
						<label for="fk_tipopagamento"><b style="color:#990000">(*)</b>Tipo Pagamento <?php echo form_error('fk_tipopagamento') ?></label>
						<SELECT disabled='disabled' name='fk_tipopagamento' id='fk_tipopagamento' class="form-control">
<OPTION VALUE></OPTION>
<?php
						foreach ($fk_tipopagamento_refval as $key => $value) {
							if ($value['id'] == $fk_tipopagamento) {
								echo "<option value='" . $value['id'] . "' SELECTED>" . $value['nome'] . "</option>";
							} else {
								echo "<option value='".$value['id'] . "'>" . $value['nome'] . "</option>";
							}
						}
						?></SELECT>
							</div></div><div class="col-md-4">
	    <div class="form-group">
							<label for="float"><b style="color:#990000">(*)</b>Importo <?php echo form_error('importo') ?></label>
										<div class="input-group">
											<div class="input-group-addon"><i class="fa fa-sort-numeric-asc"></i></div>			
							<input disabled='disabled' type="number" class="form-control" maxlength='9' name="importo" id="importo" placeholder="Importo" autocomplete="off" value="<?php echo $importo; ?>" />
							</div></div></div>
						</div>
							
					</div>
				</div>
			</div>
		</div>