   
		<div id="main_content_ajax_form_read_$id">	
			<!-- Content Header (Page header) -->
				<div class="col-md-8">
				<h3>
					<i class="fa fa-cubes"></i> 
					<a>
					<u> Verbali riunioni</u>
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
							<label for="varchar"><b style="color:#990000">(*)</b>Oggetto <?php echo form_error('oggetto') ?></label>
											<div class="input-group">
												<div class="input-group-addon"><i class="fa fa-text-height"></i></div>			
							<input disabled='disabled' type="text" class="form-control" maxlength='50' name="oggetto" id="oggetto" placeholder="Oggetto" autocomplete="off" value="<?php echo $oggetto; ?>" />
						</div></div></div><div class="col-md-4">
	    <div class="form-group">
							<label for="date"><b style="color:#990000">(*)</b>Data Riunione <?php echo form_error('data_riunione_verbale') ?></label>
											<div class="input-group">
												<div class="input-group-addon"><i class="fa fa-calendar"></i></div>			
							<input  disabled='disabled' type="text" class="form-control datemask" name="data_riunione_verbale" id="data_riunione_verbale" placeholder="Data Riunione" 
								autocomplete="off" style="background-color:#FFFFFF" readonly="readonly" value="<?php echo $data_riunione_verbale; ?>" />
						</div></div></div><div class="col-md-4">
	    <div class="form-group">
							<label for="note">Note <?php echo form_error('note') ?></label>
							<textarea disabled='disabled' class="form-control" rows="3" name="note" id="note" placeholder="Note"><?php echo $note; ?></textarea>
						</div></div>
						</div>
							
					</div>
				</div>
			</div>
		</div>