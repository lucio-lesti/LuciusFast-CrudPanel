   
		<div id="main_content_ajax_form_read_$id">	
			<!-- Content Header (Page header) -->
				<div class="col-md-8">
				<h3>
					<i class="fa fa-cubes"></i> 
					<a>
					<u> Pagamenti Ricevuti</u>
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
							<label for="int"><b style="color:#990000">(*)</b>Anagrafica Id <?php echo form_error('anagrafica_id') ?></label>
										<div class="input-group">
											<div class="input-group-addon"><i class="fa fa-sort-numeric-asc"></i></div>			
							<input disabled='disabled' type="number" class="form-control" maxlength='10' name="anagrafica_id" id="anagrafica_id" placeholder="Anagrafica Id" autocomplete="off" value="<?php echo $anagrafica_id; ?>" />
							</div></div></div><div class="col-md-4">
	    <div class="form-group">
							<label for="int"><b style="color:#990000">(*)</b>Esercizio Id <?php echo form_error('esercizio_id') ?></label>
										<div class="input-group">
											<div class="input-group-addon"><i class="fa fa-sort-numeric-asc"></i></div>			
							<input disabled='disabled' type="number" class="form-control" maxlength='10' name="esercizio_id" id="esercizio_id" placeholder="Esercizio Id" autocomplete="off" value="<?php echo $esercizio_id; ?>" />
							</div></div></div><div class="col-md-4">
	    <div class="form-group">
							<label for="varchar"><b style="color:#990000">(*)</b>Nome Affiliazione <?php echo form_error('affiliazione_id') ?></label>
											<div class="input-group">
												<div class="input-group-addon"><i class="fa fa-text-height"></i></div>			
							<input disabled='disabled' type="text" class="form-control" maxlength='50' name="affiliazione_id" id="affiliazione_id" placeholder="Nome Affiliazione" autocomplete="off" value="<?php echo $affiliazione_id; ?>" />
						</div></div></div>
						</div>
							
					</div>
				</div>
			</div>
		</div>