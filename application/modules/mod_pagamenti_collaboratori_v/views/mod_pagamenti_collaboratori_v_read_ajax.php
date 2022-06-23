   
		<div id="main_content_ajax_form_read_$id">	
			<!-- Content Header (Page header) -->
				<div class="col-md-8">
				<h3>
					<i class="fa fa-cubes"></i> 
					<a>
					<u> Pagamenti Collaboratori</u>
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
							<label for="varchar">Collaboratore <?php echo form_error('collaboratore') ?></label>
											<div class="input-group">
												<div class="input-group-addon"><i class="fa fa-text-height"></i></div>			
							<input disabled='disabled' type="text" class="form-control" maxlength='120' name="collaboratore" id="collaboratore" placeholder="Collaboratore" autocomplete="off" value="<?php echo $collaboratore; ?>" />
						</div></div></div><div class="col-md-4">
	    <div class="form-group">
							<label for="varchar"><b style="color:#990000">(*)</b>Nome Contratto <?php echo form_error('contratto_nome') ?></label>
											<div class="input-group">
												<div class="input-group-addon"><i class="fa fa-text-height"></i></div>			
							<input disabled='disabled' type="text" class="form-control" maxlength='50' name="contratto_nome" id="contratto_nome" placeholder="Nome Contratto" autocomplete="off" value="<?php echo $contratto_nome; ?>" />
						</div></div></div>
						</div>
							
					</div>
				</div>
			</div>
		</div>