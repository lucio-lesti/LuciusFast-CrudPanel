   
		<div id="main_content_ajax_form_read_$id">	
			<!-- Content Header (Page header) -->
				<div class="col-md-8">
				<h3>
					<i class="fa fa-copy"></i> 
					<a>
					<u> Affiliazioni</u>
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
							<label for="varchar"><b style="color:#990000">(*)</b>Nome Affiliazione <?php echo form_error('nome') ?></label>
											<div class="input-group">
												<div class="input-group-addon"><i class="fa fa-text-height"></i></div>			
							<input disabled='disabled' type="text" class="form-control" maxlength='50' name="nome" id="nome" placeholder="Nome Affiliazione" autocomplete="off" value="<?php echo $nome; ?>" />
						</div></div></div><div class="col-md-4">
	    <div class="form-group">
						<label for="fk_ente"><b style="color:#990000">(*)</b>Ente <?php echo form_error('fk_ente') ?></label>
						<SELECT disabled='disabled' name='fk_ente' id='fk_ente' class="form-control">
<OPTION VALUE></OPTION>
<?php
						foreach ($fk_ente_refval as $key => $value) {
							if ($value['id'] == $fk_ente) {
								echo "<option value='" . $value['id'] . "' SELECTED>" . $value['nome'] . "</option>";
							} else {
								echo "<option value='".$value['id'] . "'>" . $value['nome'] . "</option>";
							}
						}
						?></SELECT>
							</div></div><div class="col-md-4">
	    <div class="form-group">
						<label for="fk_esercizio"><b style="color:#990000">(*)</b>Esercizio <?php echo form_error('fk_esercizio') ?></label>
						<SELECT disabled='disabled' name='fk_esercizio' id='fk_esercizio' class="form-control">
<OPTION VALUE></OPTION>
<?php
						foreach ($fk_esercizio_refval as $key => $value) {
							if ($value['id'] == $fk_esercizio) {
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