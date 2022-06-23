   
		<div id="main_content_ajax_form_read_$id">	
			<!-- Content Header (Page header) -->
				<div class="col-md-8">
				<h3>
					<i class="fa fa-cubes"></i> 
					<a>
					<u> Report Pagamenti Mensili</u>
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
							<label for="char"><b style="color:#990000">(*)</b>Ids <?php echo form_error('ids') ?></label>
											<div class="input-group">
												<div class="input-group-addon"><i class="fa fa-text-height"></i></div>			
							<input disabled='disabled' type="text" class="form-control" maxlength='0' name="ids" id="ids" placeholder="Ids" autocomplete="off" value="<?php echo $ids; ?>" />
						</div></div></div><div class="col-md-4">
	    <div class="form-group">
							<label for="char"><b style="color:#990000">(*)</b>Id <?php echo form_error('id') ?></label>
											<div class="input-group">
												<div class="input-group-addon"><i class="fa fa-text-height"></i></div>			
							<input disabled='disabled' type="text" class="form-control" maxlength='0' name="id" id="id" placeholder="Id" autocomplete="off" value="<?php echo $id; ?>" />
						</div></div></div><div class="col-md-4">
	    <div class="form-group">
							<label for="varchar"><b style="color:#990000">(*)</b>Anagrafica <?php echo form_error('Anagrafica') ?></label>
											<div class="input-group">
												<div class="input-group-addon"><i class="fa fa-text-height"></i></div>			
							<input disabled='disabled' type="text" class="form-control" maxlength='101' name="Anagrafica" id="Anagrafica" placeholder="Anagrafica" autocomplete="off" value="<?php echo $Anagrafica; ?>" />
						</div></div></div><div class="col-md-4">
	    <div class="form-group">
							<label for="date">Data Iscrizione <?php echo form_error('Data_Iscrizione') ?></label>
											<div class="input-group">
												<div class="input-group-addon"><i class="fa fa-calendar"></i></div>			
							<input  disabled='disabled' type="text" class="form-control datemask" name="Data_Iscrizione" id="Data_Iscrizione" placeholder="Data Iscrizione" 
								autocomplete="off" style="background-color:#FFFFFF" readonly="readonly" value="<?php echo $Data_Iscrizione; ?>" />
						</div></div></div><div class="col-md-4">
	    <div class="form-group">
							<label for="varchar">Set <?php echo form_error('Set') ?></label>
											<div class="input-group">
												<div class="input-group-addon"><i class="fa fa-text-height"></i></div>			
							<input disabled='disabled' type="text" class="form-control" maxlength='10' name="Set" id="Set" placeholder="Set" autocomplete="off" value="<?php echo $Set; ?>" />
						</div></div></div><div class="col-md-4">
	    <div class="form-group">
							<label for="varchar">Ott <?php echo form_error('Ott') ?></label>
											<div class="input-group">
												<div class="input-group-addon"><i class="fa fa-text-height"></i></div>			
							<input disabled='disabled' type="text" class="form-control" maxlength='10' name="Ott" id="Ott" placeholder="Ott" autocomplete="off" value="<?php echo $Ott; ?>" />
						</div></div></div><div class="col-md-4">
	    <div class="form-group">
							<label for="varchar">Nov <?php echo form_error('Nov') ?></label>
											<div class="input-group">
												<div class="input-group-addon"><i class="fa fa-text-height"></i></div>			
							<input disabled='disabled' type="text" class="form-control" maxlength='10' name="Nov" id="Nov" placeholder="Nov" autocomplete="off" value="<?php echo $Nov; ?>" />
						</div></div></div><div class="col-md-4">
	    <div class="form-group">
							<label for="varchar">Dic <?php echo form_error('Dic') ?></label>
											<div class="input-group">
												<div class="input-group-addon"><i class="fa fa-text-height"></i></div>			
							<input disabled='disabled' type="text" class="form-control" maxlength='10' name="Dic" id="Dic" placeholder="Dic" autocomplete="off" value="<?php echo $Dic; ?>" />
						</div></div></div><div class="col-md-4">
	    <div class="form-group">
							<label for="varchar">Gen <?php echo form_error('Gen') ?></label>
											<div class="input-group">
												<div class="input-group-addon"><i class="fa fa-text-height"></i></div>			
							<input disabled='disabled' type="text" class="form-control" maxlength='10' name="Gen" id="Gen" placeholder="Gen" autocomplete="off" value="<?php echo $Gen; ?>" />
						</div></div></div><div class="col-md-4">
	    <div class="form-group">
							<label for="varchar">Feb <?php echo form_error('Feb') ?></label>
											<div class="input-group">
												<div class="input-group-addon"><i class="fa fa-text-height"></i></div>			
							<input disabled='disabled' type="text" class="form-control" maxlength='10' name="Feb" id="Feb" placeholder="Feb" autocomplete="off" value="<?php echo $Feb; ?>" />
						</div></div></div><div class="col-md-4">
	    <div class="form-group">
							<label for="varchar">Mar <?php echo form_error('Mar') ?></label>
											<div class="input-group">
												<div class="input-group-addon"><i class="fa fa-text-height"></i></div>			
							<input disabled='disabled' type="text" class="form-control" maxlength='10' name="Mar" id="Mar" placeholder="Mar" autocomplete="off" value="<?php echo $Mar; ?>" />
						</div></div></div><div class="col-md-4">
	    <div class="form-group">
							<label for="varchar">Apr <?php echo form_error('Apr') ?></label>
											<div class="input-group">
												<div class="input-group-addon"><i class="fa fa-text-height"></i></div>			
							<input disabled='disabled' type="text" class="form-control" maxlength='10' name="Apr" id="Apr" placeholder="Apr" autocomplete="off" value="<?php echo $Apr; ?>" />
						</div></div></div><div class="col-md-4">
	    <div class="form-group">
							<label for="varchar">Mag <?php echo form_error('Mag') ?></label>
											<div class="input-group">
												<div class="input-group-addon"><i class="fa fa-text-height"></i></div>			
							<input disabled='disabled' type="text" class="form-control" maxlength='10' name="Mag" id="Mag" placeholder="Mag" autocomplete="off" value="<?php echo $Mag; ?>" />
						</div></div></div><div class="col-md-4">
	    <div class="form-group">
							<label for="varchar">Giu <?php echo form_error('Giu') ?></label>
											<div class="input-group">
												<div class="input-group-addon"><i class="fa fa-text-height"></i></div>			
							<input disabled='disabled' type="text" class="form-control" maxlength='10' name="Giu" id="Giu" placeholder="Giu" autocomplete="off" value="<?php echo $Giu; ?>" />
						</div></div></div><div class="col-md-4">
	    <div class="form-group">
							<label for="varchar">Lug <?php echo form_error('Lug') ?></label>
											<div class="input-group">
												<div class="input-group-addon"><i class="fa fa-text-height"></i></div>			
							<input disabled='disabled' type="text" class="form-control" maxlength='10' name="Lug" id="Lug" placeholder="Lug" autocomplete="off" value="<?php echo $Lug; ?>" />
						</div></div></div><div class="col-md-4">
	    <div class="form-group">
							<label for="varchar">Ago <?php echo form_error('Ago') ?></label>
											<div class="input-group">
												<div class="input-group-addon"><i class="fa fa-text-height"></i></div>			
							<input disabled='disabled' type="text" class="form-control" maxlength='10' name="Ago" id="Ago" placeholder="Ago" autocomplete="off" value="<?php echo $Ago; ?>" />
						</div></div></div>
						</div>
							
					</div>
				</div>
			</div>
		</div>