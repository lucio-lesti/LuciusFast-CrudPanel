   
		<div id="main_content_ajax_form_read_$id">	
			<!-- Content Header (Page header) -->
				<div class="col-md-8">
				<h3>
					<i class="fa fa-cubes"></i> 
					<a>
					<u> Scadenze Notifiche</u>
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
							<label for="varchar"><b style="color:#990000">(*)</b>Campo Data Scadenza <?php echo form_error('campo_data_scadenza') ?></label>
											<div class="input-group">
												<div class="input-group-addon"><i class="fa fa-text-height"></i></div>			
							<input disabled='disabled' type="text" class="form-control" maxlength='50' name="campo_data_scadenza" id="campo_data_scadenza" placeholder="Campo Data Scadenza" autocomplete="off" value="<?php echo $campo_data_scadenza; ?>" />
						</div></div></div><div class="col-md-4">
	    <div class="form-group">
							<label for="varchar">Icona Notifica <?php echo form_error('icona_notifica') ?></label>
											<div class="input-group">
												<div class="input-group-addon"><i class="fa fa-text-height"></i></div>			
							<input disabled='disabled' type="text" class="form-control" maxlength='255' name="icona_notifica" id="icona_notifica" placeholder="Icona Notifica" autocomplete="off" value="<?php echo $icona_notifica; ?>" />
						</div></div></div><div class="col-md-4">
	    <div class="form-group">
							<label for="varchar"><b style="color:#990000">(*)</b>Mod Name <?php echo form_error('mod_name') ?></label>
											<div class="input-group">
												<div class="input-group-addon"><i class="fa fa-text-height"></i></div>			
							<input disabled='disabled' type="text" class="form-control" maxlength='50' name="mod_name" id="mod_name" placeholder="Mod Name" autocomplete="off" value="<?php echo $mod_name; ?>" />
						</div></div></div><div class="col-md-4">
	    <div class="form-group">
							<label for="varchar">Msg Notifica <?php echo form_error('msg_notifica') ?></label>
											<div class="input-group">
												<div class="input-group-addon"><i class="fa fa-text-height"></i></div>			
							<input disabled='disabled' type="text" class="form-control" maxlength='255' name="msg_notifica" id="msg_notifica" placeholder="Msg Notifica" autocomplete="off" value="<?php echo $msg_notifica; ?>" />
						</div></div></div><div class="col-md-4">
	    <div class="form-group">
							<label for="int">Nr Giorni Data Notifica <?php echo form_error('nr_giorni_data_notifica') ?></label>
										<div class="input-group">
											<div class="input-group-addon"><i class="fa fa-sort-numeric-asc"></i></div>			
							<input disabled='disabled' type="number" class="form-control" maxlength='10' name="nr_giorni_data_notifica" id="nr_giorni_data_notifica" placeholder="Nr Giorni Data Notifica" autocomplete="off" value="<?php echo $nr_giorni_data_notifica; ?>" />
							</div></div></div><div class="col-md-4">
	    <div class="form-group">
							<label for="sql_command">Sql Command <?php echo form_error('sql_command') ?></label>
							<textarea disabled='disabled' class="form-control" rows="3" name="sql_command" id="sql_command" placeholder="Sql Command"><?php echo $sql_command; ?></textarea>
						</div></div><div class="col-md-4">
	    <div class="form-group">
							<label for="varchar">Table Name <?php echo form_error('table_name') ?></label>
											<div class="input-group">
												<div class="input-group-addon"><i class="fa fa-text-height"></i></div>			
							<input disabled='disabled' type="text" class="form-control" maxlength='50' name="table_name" id="table_name" placeholder="Table Name" autocomplete="off" value="<?php echo $table_name; ?>" />
						</div></div></div>
						</div>
							
					</div>
				</div>
			</div>
		</div>