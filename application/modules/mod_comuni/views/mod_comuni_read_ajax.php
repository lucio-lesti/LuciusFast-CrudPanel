   
		<div id="main_content_ajax_form_read_$id">	
			<!-- Content Header (Page header) -->
				<div class="col-md-8">
				<h3>
					<i class="fa fa-cubes"></i> 
					<a>
					<u> Comuni</u>
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
							<label for="varchar"><b style="color:#990000">(*)</b>Comune <?php echo form_error('comune') ?></label>
											<div class="input-group">
												<div class="input-group-addon"><i class="fa fa-text-height"></i></div>			
							<input disabled='disabled' type="text" class="form-control" maxlength='100' name="comune" id="comune" placeholder="Comune" autocomplete="off" value="<?php echo $comune; ?>" />
						</div></div></div><div class="col-md-4">
	    <div class="form-group">
							<label for="varchar"><b style="color:#990000">(*)</b>CAP <?php echo form_error('cap') ?></label>
											<div class="input-group">
												<div class="input-group-addon"><i class="fa fa-text-height"></i></div>			
							<input disabled='disabled' type="text" class="form-control" maxlength='10' name="cap" id="cap" placeholder="CAP" autocomplete="off" value="<?php echo $cap; ?>" />
						</div></div></div><div class="col-md-4">
	    <div class="form-group">
							<label for="varchar"><b style="color:#990000">(*)</b>Prefisso <?php echo form_error('prefisso') ?></label>
											<div class="input-group">
												<div class="input-group-addon"><i class="fa fa-text-height"></i></div>			
							<input disabled='disabled' type="text" class="form-control" maxlength='10' name="prefisso" id="prefisso" placeholder="Prefisso" autocomplete="off" value="<?php echo $prefisso; ?>" />
						</div></div></div><div class="col-md-4">
	    <div class="form-group">
							<label for="varchar"><b style="color:#990000">(*)</b>Prov. <?php echo form_error('codice_provincia') ?></label>
											<div class="input-group">
												<div class="input-group-addon"><i class="fa fa-text-height"></i></div>			
							<input disabled='disabled' type="text" class="form-control" maxlength='3' name="codice_provincia" id="codice_provincia" placeholder="Prov." autocomplete="off" value="<?php echo $codice_provincia; ?>" />
						</div></div></div><div class="col-md-4">
	    <div class="form-group">
							<label for="varchar"><b style="color:#990000">(*)</b>Regione <?php echo form_error('codice_regione') ?></label>
											<div class="input-group">
												<div class="input-group-addon"><i class="fa fa-text-height"></i></div>			
							<input disabled='disabled' type="text" class="form-control" maxlength='3' name="codice_regione" id="codice_regione" placeholder="Regione" autocomplete="off" value="<?php echo $codice_regione; ?>" />
						</div></div></div>
						</div>
							
					</div>
				</div>
			</div>
		</div>