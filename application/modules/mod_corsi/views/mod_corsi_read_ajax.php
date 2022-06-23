		<?php
			$icon = "fa fa-cubes";
			if($_SESSION['fast_ins_corsi'] == 'Y'){
				$icon = "fa fa-rocket";
			}			
		?>
		<div id="main_content_ajax_form_read_$id">	
			<!-- Content Header (Page header) -->
				<div class="col-md-8">
				<h3>
					<i class="<?php echo $icon ;?>"></i> 
					<a>
					<u> Corsi <?php if($_SESSION['fast_ins_corsi'] == 'Y'){ echo " - Inserimento Veloce";}?></u>
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
							<label for="varchar"><b style="color:#990000">(*)</b>Nome Corso <?php echo form_error('nome') ?></label>
											<div class="input-group">
												<div class="input-group-addon"><i class="fa fa-text-height"></i></div>			
							<input disabled='disabled' type="text" class="form-control" maxlength='50' name="nome" id="nome" placeholder="Nome Corso" autocomplete="off" value="<?php echo $nome; ?>" />
						</div></div></div><div class="col-md-4">
	    <div class="form-group">
						<label for="fk_affiliazione"><b style="color:#990000">(*)</b>Affiliazione <?php echo form_error('fk_affiliazione') ?></label>
						<SELECT disabled='disabled' name='fk_affiliazione' id='fk_affiliazione' class="form-control">
<OPTION VALUE></OPTION>
<?php
						foreach ($fk_affiliazione_refval as $key => $value) {
							if ($value['id'] == $fk_affiliazione) {
								echo "<option value='" . $value['id'] . "' SELECTED>" . $value['nome'] . "</option>";
							} else {
								echo "<option value='".$value['id'] . "'>" . $value['nome'] . "</option>";
							}
						}
						?></SELECT>
							</div></div><div class="col-md-4">
	    <div class="form-group">
						<label for="fk_disciplina"><b style="color:#990000">(*)</b>Disciplina <?php echo form_error('fk_disciplina') ?></label>
						<SELECT disabled='disabled' name='fk_disciplina' id='fk_disciplina' class="form-control">
<OPTION VALUE></OPTION>
<?php
						foreach ($fk_disciplina_refval as $key => $value) {
							if ($value['id'] == $fk_disciplina) {
								echo "<option value='" . $value['id'] . "' SELECTED>" . $value['nome'] . "</option>";
							} else {
								echo "<option value='".$value['id'] . "'>" . $value['nome'] . "</option>";
							}
						}
						?></SELECT>
							</div></div><div class="col-md-4">
	    <div class="form-group">
							<label for="date"><b style="color:#990000">(*)</b>Data Da <?php echo form_error('data_da') ?></label>
											<div class="input-group">
												<div class="input-group-addon"><i class="fa fa-calendar"></i></div>			
							<input  disabled='disabled' type="text" class="form-control" name="data_da" id="data_da" placeholder="Data Da" 
								autocomplete="off" readonly="readonly" value="<?php echo $data_da; ?>" />
						</div></div></div><div class="col-md-4">
	    <div class="form-group">
							<label for="date"><b style="color:#990000">(*)</b>Data A <?php echo form_error('data_a') ?></label>
											<div class="input-group">
												<div class="input-group-addon"><i class="fa fa-calendar"></i></div>			
							<input  disabled='disabled' type="text" class="form-control" name="data_a" id="data_a" placeholder="Data A" 
								autocomplete="off" readonly="readonly" value="<?php echo $data_a; ?>" />
						</div></div></div><div class="col-md-4">
	    <div class="form-group">
							<label for="tipologia_corso"><b style="color:#990000">(*)</b>Tipo Corso <?php echo form_error('tipologia_corso') ?></label>
							<SELECT disabled='disabled' name='tipologia_corso' id='tipologia_corso' class="form-control">
<OPTION VALUE></OPTION>
<OPTION VALUE='ABBONAMENTO'>ABBONAMENTO</OPTION>
<OPTION VALUE='MENSILE'>MENSILE</OPTION></SELECT>
						</div>
						<script>	
						$('[name=tipologia_corso] option').filter(function() { 
							return ($(this).text() == '<?php echo $tipologia_corso?>'); //To select Blue
						}).prop('selected', true);				
						</script>
					</div><div class="col-md-4">
	    <div class="form-group">
							<label for="float"><b style="color:#990000">(*)</b>Importo <?php echo form_error('importo_mensile') ?></label>
										<div class="input-group">
											<div class="input-group-addon"><i class="fa fa-sort-numeric-asc"></i></div>			
							<input disabled='disabled' type="number" class="form-control" maxlength='12' name="importo_mensile" id="importo_mensile" placeholder="Importo" autocomplete="off" value="<?php echo $importo_mensile; ?>" />
							</div></div></div>
						</div>
							
					</div>
				</div>
			</div>
		</div>