   
	<!-- Content Header (Page header) -->
        <div class="col-md-4">
        <h3>
            <i class="fa fa-euro"></i> 
			<a>
			<u> Pagamenti/Ricevute</u>
			</a>
           <b style='font-size:22px'> >> </b><b style='font-size:22px'>Visualizza ID:<?=$id?></b>
        </h3>		 
        </div>		
        <div class="row">
            <!-- left column -->
            <div class="col-md-12">
                <!-- general form elements -->
                <div class="box box-primary">
                    <!-- /.box-header -->
                    <!-- form start -->
				
					 <div class="box-body">
						<?php $this->load->helper("form"); ?><div class="col-md-4">
	    <div class="form-group">
						<label for="int">Fk Anagrafica <?php echo form_error('fk_anagrafica') ?></label>
										<div class="input-group">
											<div class="input-group-addon"><i class="fa fa-sort-numeric-asc"></i></div>			
						<input disabled='disabled' type="number" class="form-control" maxlength='10' name="fk_anagrafica" id="fk_anagrafica" placeholder="Fk Anagrafica" autocomplete="off" value="<?php echo $fk_anagrafica; ?>" />
					</div></div></div><div class="col-md-4">
	    <div class="form-group">
						<label for="int">Fk Disciplina <?php echo form_error('fk_disciplina') ?></label>
										<div class="input-group">
											<div class="input-group-addon"><i class="fa fa-sort-numeric-asc"></i></div>			
						<input disabled='disabled' type="number" class="form-control" maxlength='10' name="fk_disciplina" id="fk_disciplina" placeholder="Fk Disciplina" autocomplete="off" value="<?php echo $fk_disciplina; ?>" />
					</div></div></div><div class="col-md-4">
	    <div class="form-group">
						<label for="varchar">Importo <?php echo form_error('importo') ?></label>
										<div class="input-group">
											<div class="input-group-addon"><i class="fa fa-text-height"></i></div>			
						<input disabled='disabled' type="text" class="form-control" maxlength='255' name="importo" id="importo" placeholder="Importo" autocomplete="off" value="<?php echo $importo; ?>" />
					</div></div></div><div class="col-md-4">
	    <div class="form-group">
						<label for="varchar">Data <?php echo form_error('data') ?></label>
										<div class="input-group">
											<div class="input-group-addon"><i class="fa fa-text-height"></i></div>			
						<input disabled='disabled' type="text" class="form-control" maxlength='255' name="data" id="data" placeholder="Data" autocomplete="off" value="<?php echo $data; ?>" />
					</div></div></div><div class="col-md-4">
	    <div class="form-group">
						<label for="mese">Mese <?php echo form_error('mese') ?></label>
						<SELECT disabled='disabled' name='mese' id='mese' class="form-control"> 
				<?php if($mese == 'SI'){
						echo '<OPTION VALUE="SI" SELECTED>SI</OPTION>
							<OPTION VALUE="NO">NO</OPTION>';				
					} else {
						echo '<OPTION VALUE="SI" >SI</OPTION>
							<OPTION VALUE="NO" SELECTED>NO</OPTION>';							
					}
				?>				
				</SELECT>
					</div></div><div class="col-md-4">
	    <div class="form-group">
						<label for="anno">Anno <?php echo form_error('anno') ?></label>
						<SELECT disabled='disabled' name='anno' id='anno' class="form-control"> 
				<?php if($anno == 'SI'){
						echo '<OPTION VALUE="SI" SELECTED>SI</OPTION>
							<OPTION VALUE="NO">NO</OPTION>';				
					} else {
						echo '<OPTION VALUE="SI" >SI</OPTION>
							<OPTION VALUE="NO" SELECTED>NO</OPTION>';							
					}
				?>				
				</SELECT>
					</div></div>
	    <input type="hidden" name="id" value="<?php echo $id; ?>" /> <div class="row">
				<div class="col-md-12">
						  <div class='row'>
							<div class='col-md-6'>

							</div>
							
								<div class="col-md-6" align="right">		
								</div>	
								
							</div>
				</div>
			</div>
				</div>
					
				<!-- form close -->
            </div>
        </div>
    </div>