   
		<div id="main_content_ajax_form_read_$id">	
			<!-- Content Header (Page header) -->
				<div class="col-md-8">
				<h3>
					<i class="fa fa-cubes"></i> 
					<a>
					<u> Autocertificazioni Green Pass</u>
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
							<label for="date"><b style="color:#990000">(*)</b>Data Autocertificazione Fine Validita <?php echo form_error('data_autocertificazione_fine_validita') ?></label>
											<div class="input-group">
												<div class="input-group-addon"><i class="fa fa-calendar"></i></div>			
							<input  disabled='disabled' type="text" class="form-control datemask" name="data_autocertificazione_fine_validita" id="data_autocertificazione_fine_validita" placeholder="Data Autocertificazione Fine Validita" 
								autocomplete="off" style="background-color:#FFFFFF" readonly="readonly" value="<?php echo $data_autocertificazione_fine_validita; ?>" />
						</div></div></div><div class="col-md-4">
	    <div class="form-group">
							<label for="varchar"><b style="color:#990000">(*)</b>Nome Documento <?php echo form_error('nome_documento') ?></label>
											<div class="input-group">
												<div class="input-group-addon"><i class="fa fa-text-height"></i></div>			
							<input disabled='disabled' type="text" class="form-control" maxlength='255' name="nome_documento" id="nome_documento" placeholder="Nome Documento" autocomplete="off" value="<?php echo $nome_documento; ?>" />
						</div></div></div><div class="col-md-4">
	    <div class="form-group">
							<label for="longblob"><b style="color:#990000">(*)</b>Documento Upload <?php echo form_error('documento_upload') ?></label> 
						<?php 
							if($documento_upload != ''){
								echo '<img src="data:image/jpeg;base64,'.$documento_upload.'" style="width:90px"  />';	
							}
						?>				
									
											<div class="input-group">
												<div class="input-group-addon"><i class="fa fa-text-height"></i></div> <input disabled='disabled' type="file" class="form-control"  name="documento_upload" id="documento_upload"  />	</div></div></div>
						</div>
							
					</div>
				</div>
			</div>
		</div>