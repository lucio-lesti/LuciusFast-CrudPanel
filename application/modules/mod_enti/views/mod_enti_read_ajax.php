   
		<div id="main_content_ajax_form_read_$id">	
			<!-- Content Header (Page header) -->
				<div class="col-md-8">
				<h3>
					<i class="fa fa-cubes"></i> 
					<a>
					<u> Enti</u>
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
							<label for="varchar"><b style="color:#990000">(*)</b>Nome Ente <?php echo form_error('nome') ?></label>
											<div class="input-group">
												<div class="input-group-addon"><i class="fa fa-text-height"></i></div>			
							<input disabled='disabled' type="text" class="form-control" maxlength='50' name="nome" id="nome" placeholder="Nome Ente" autocomplete="off" value="<?php echo $nome; ?>" />
						</div></div></div><div class="col-md-4">
	    <div class="form-group">
							<label for="varchar"><b style="color:#990000">(*)</b>Indirizzo <?php echo form_error('indirizzo') ?></label>
											<div class="input-group">
												<div class="input-group-addon"><i class="fa fa-text-height"></i></div>			
							<input disabled='disabled' type="text" class="form-control" maxlength='100' name="indirizzo" id="indirizzo" placeholder="Indirizzo" autocomplete="off" value="<?php echo $indirizzo; ?>" />
						</div></div></div><div class="col-md-4">
	    <div class="form-group">
						<label for="fk_comune"><b style="color:#990000">(*)</b>Citta <?php echo form_error('fk_comune') ?></label>
						<SELECT disabled='disabled' name='fk_comune' id='fk_comune' class="form-control">
<OPTION VALUE></OPTION>
<?php
						foreach ($fk_comune_refval as $key => $value) {
							if ($value['id'] == $fk_comune) {
								echo "<option value='" . $value['id'] . "' SELECTED>" . $value['nome'] . "</option>";
							} else {
								echo "<option value='".$value['id'] . "'>" . $value['nome'] . "</option>";
							}
						}
						?></SELECT>
							</div></div><div class="col-md-4">
	    <div class="form-group">
							<label for="varchar">Telefono <?php echo form_error('telefono') ?></label>
											<div class="input-group">
												<div class="input-group-addon"><i class="fa fa-text-height"></i></div>			
							<input disabled='disabled' type="text" class="form-control" maxlength='100' name="telefono" id="telefono" placeholder="Telefono" autocomplete="off" value="<?php echo $telefono; ?>" />
						</div></div></div><div class="col-md-4">
	    <div class="form-group">
							<label for="varchar">E-Mail <?php echo form_error('email') ?></label>
											<div class="input-group">
												<div class="input-group-addon"><i class="fa fa-text-height"></i></div>			
							<input disabled='disabled' type="text" class="form-control" maxlength='100' name="email" id="email" placeholder="E-Mail" autocomplete="off" value="<?php echo $email; ?>" />
						</div></div></div>
						</div>
							
					</div>
				</div>
			</div>
		</div>