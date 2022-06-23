   
		<div id="main_content_ajax_form_read_$id">	
			<!-- Content Header (Page header) -->
				<div class="col-md-8">
				<h3>
					<i class="fa fa-cubes"></i> 
					<a>
					<u> Sale</u>
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
							<label for="varchar"><b style="color:#990000">(*)</b>Nome Sala <?php echo form_error('nome') ?></label>
											<div class="input-group">
												<div class="input-group-addon"><i class="fa fa-text-height"></i></div>			
							<input disabled='disabled' type="text" class="form-control" maxlength='100' name="nome" id="nome" placeholder="Nome Sala" autocomplete="off" value="<?php echo $nome; ?>" />
						</div></div></div><div class="col-md-4">
	    <div class="form-group">
						<label for="fk_sede"><b style="color:#990000">(*)</b>Sede <?php echo form_error('fk_sede') ?></label>
						<SELECT disabled='disabled' name='fk_sede' id='fk_sede' class="form-control">
<OPTION VALUE></OPTION>
<?php
						foreach ($fk_sede_refval as $key => $value) {
							if ($value['id'] == $fk_sede) {
								echo "<option value='" . $value['id'] . "' SELECTED>" . $value['nome'] . "</option>";
							} else {
								echo "<option value='".$value['id'] . "'>" . $value['nome'] . "</option>";
							}
						}
						?></SELECT>
							</div></div><div class="col-md-4">
	    <div class="form-group">
							<label for="int"><b style="color:#990000">(*)</b>Capienza <?php echo form_error('capienza') ?></label>
										<div class="input-group">
											<div class="input-group-addon"><i class="fa fa-sort-numeric-asc"></i></div>			
							<input disabled='disabled' type="number" class="form-control" maxlength='10' name="capienza" id="capienza" placeholder="Capienza" autocomplete="off" value="<?php echo $capienza; ?>" />
							</div></div></div>
						</div>
							
					</div>
				</div>
			</div>
		</div>