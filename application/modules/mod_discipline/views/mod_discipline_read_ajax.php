   
		<div id="main_content_ajax_form_read_$id">	
			<!-- Content Header (Page header) -->
				<div class="col-md-8">
				<h3>
					<i class="fa fa-cubes"></i> 
					<a>
					<u> Discipline</u>
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
							<label for="varchar"><b style="color:#990000">(*)</b>Nome Disciplina <?php echo form_error('nome') ?></label>
											<div class="input-group">
												<div class="input-group-addon"><i class="fa fa-text-height"></i></div>			
							<input disabled='disabled' type="text" class="form-control" maxlength='255' name="nome" id="nome" placeholder="Nome Disciplina" autocomplete="off" value="<?php echo $nome; ?>" />
						</div></div></div><div class="col-md-4">
	    <div class="form-group">
							<label for="varchar"><b style="color:#990000">(*)</b>Codice Disciplina <?php echo form_error('codice_disciplina') ?></label>
											<div class="input-group">
												<div class="input-group-addon"><i class="fa fa-text-height"></i></div>			
							<input disabled='disabled' type="text" class="form-control" maxlength='30' name="codice_disciplina" id="codice_disciplina" placeholder="Codice Disciplina" autocomplete="off" value="<?php echo $codice_disciplina; ?>" />
						</div></div></div><div class="col-md-4">
	    <div class="form-group">
						<label for="fk_sport"><b style="color:#990000">(*)</b>Sport <?php echo form_error('fk_sport') ?></label>
						<SELECT disabled='disabled' name='fk_sport' id='fk_sport' class="form-control">
<OPTION VALUE></OPTION>
<?php
						foreach ($fk_sport_refval as $key => $value) {
							if ($value['id'] == $fk_sport) {
								echo "<option value='" . $value['id'] . "' SELECTED>" . $value['nome'] . "</option>";
							} else {
								echo "<option value='".$value['id'] . "'>" . $value['nome'] . "</option>";
							}
						}
						?></SELECT>
							</div></div>
						</div>
							
					</div>
				</div>
			</div>
		</div>