   
		<div id="main_content_ajax_form_read_$id">	
			<!-- Content Header (Page header) -->
				<div class="col-md-8">
				<h3>
					<i class="fa fa-cubes"></i> 
					<a>
					<u> Tipo Pagamento</u>
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
							<label for="varchar"><b style="color:#990000">(*)</b>Codice <?php echo form_error('codice') ?></label>
											<div class="input-group">
												<div class="input-group-addon"><i class="fa fa-text-height"></i></div>			
							<input disabled='disabled' type="text" class="form-control" maxlength='10' name="codice" id="codice" placeholder="Codice" autocomplete="off" value="<?php echo $codice; ?>" />
						</div></div></div><div class="col-md-4">
	    <div class="form-group">
							<label for="varchar"><b style="color:#990000">(*)</b>Descrizione <?php echo form_error('nome') ?></label>
											<div class="input-group">
												<div class="input-group-addon"><i class="fa fa-text-height"></i></div>			
							<input disabled='disabled' type="text" class="form-control" maxlength='255' name="nome" id="nome" placeholder="Descrizione" autocomplete="off" value="<?php echo $nome; ?>" />
						</div></div></div>
						</div>
							
					</div>
				</div>
			</div>
		</div>