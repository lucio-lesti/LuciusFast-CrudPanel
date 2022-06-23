   
		<div id="main_content_ajax_form_read_$id">	
			<!-- Content Header (Page header) -->
				<div class="col-md-8">
				<h3>
					<i class="fa fa-external-link"></i> 
					<a>
					<u> Documenti Generici</u>
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
							<label for="varchar">Nome <?php echo form_error('nome') ?></label>
											<div class="input-group">
												<div class="input-group-addon"><i class="fa fa-text-height"></i></div>			
							<input disabled='disabled' type="text" class="form-control" maxlength='100' name="nome" id="nome" placeholder="Nome" autocomplete="off" value="<?php echo $nome; ?>" />
						</div></div></div><div class="col-md-4">
	    <div class="form-group">
							<label for="tipo_doc">Tipo Doc <?php echo form_error('tipo_doc') ?></label>
							<SELECT disabled='disabled' name='tipo_doc' id='tipo_doc' class="form-control">
<OPTION VALUE></OPTION>
<OPTION VALUE='FATTURA'>FATTURA</OPTION>
<OPTION VALUE='DDT'>DDT</OPTION>
<OPTION VALUE='RICEVUTA FISCALE'>RICEVUTA FISCALE</OPTION>
<OPTION VALUE='RICEVUTA NON FISCALE'>RICEVUTA NON FISCALE</OPTION>
<OPTION VALUE='ALTRO'>ALTRO</OPTION></SELECT>
						</div>
						<script>	
						$('[name=tipo_doc] option').filter(function() { 
							return ($(this).text() == '<?php echo $tipo_doc?>'); //To select Blue
						}).prop('selected', true);				
						</script>
					</div><div class="col-md-4">
	    <div class="form-group">
							<label for="date">Data <?php echo form_error('data') ?></label>
											<div class="input-group">
												<div class="input-group-addon"><i class="fa fa-calendar"></i></div>			
							<input  disabled='disabled' type="text" class="form-control datemask" name="data" id="data" placeholder="Data" 
								autocomplete="off" style="background-color:#FFFFFF" readonly="readonly" value="<?php echo $data; ?>" />
						</div></div></div><div class="col-md-4">
	    <div class="form-group">
							<label for="descrizione">Descrizione <?php echo form_error('descrizione') ?></label>
							<textarea disabled='disabled' class="form-control" rows="3" name="descrizione" id="descrizione" placeholder="Descrizione"><?php echo $descrizione; ?></textarea>
						</div></div>
						</div>
							
					</div>
				</div>
			</div>
		</div>