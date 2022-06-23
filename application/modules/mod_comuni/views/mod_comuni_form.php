
		<div class="content-wrapper">
			
			<!-- Content Header (Page header) -->
			<section class="content-header">
				<h1>
					<i class="fa fa-cubes"></i> 
					<a href="<?php echo site_url("mod_comuni") ?>">
					<u> Comuni</u>
					</a>
				   <b style='font-size:20px'> >> </b><b style='font-size:20px'>Descrizione Azione</b>
				</h1>
					<div class="col-md-4">
						<?php
						$error = $this->session->flashdata('error');
						if($error)
						{
					?>
							<div class="alert alert-danger alert-dismissable">
								<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
								<?php echo $this->session->flashdata('error'); ?>
							</div>
							<?php } ?>
							<?php  
						$success = $this->session->flashdata('success');
						if($success)
						{
					?>
							<div class="alert alert-success alert-dismissable">
								<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
								<?php echo $this->session->flashdata('success'); ?>
							</div>
							<?php } ?>

							<div class="row">
								<div class="col-md-12">
									<?php echo validation_errors('<div class="alert alert-danger alert-dismissable">', ' <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button></div>'); ?>
								</div>
							</div>
					</div>		
			</section>
			<section class="content">
				
				<div class="row">
					<!-- left column -->
					<div class="col-md-12">
						<!-- general form elements -->
						<div class="box box-primary">
							<div class="box-header">
								<h3 class="box-title">Informazioni Modulo</h3>
							</div>
							<!-- /.box-header -->
							<!-- form start -->
						
							 <div class="box-body">
								<?php $this->load->helper("form"); ?>
				<form action="<?php echo $action; ?>" method="post" name="<?php echo $frm_module_name; ?>" id="<?php echo $frm_module_name; ?>">
					<div class='col-md-12'>
						<B STYLE='color:#990000'>(*)</B>Campi obbligatori
						<br><br>					
					</div><div class="col-md-4">
	    <div class="form-group">
								<label for="int">Nr. Abitanti <?php echo form_error('abitanti') ?></label>
												<div class="input-group">
													<div class="input-group-addon"><i class="fa fa-sort-numeric-asc"></i></div>			
								<input type="number" class="form-control" maxlength='10' name="abitanti" id="abitanti" placeholder="Nr. Abitanti" autocomplete="off" value="<?php echo $abitanti; ?>" />
							</div></div></div><div class="col-md-4">
	    <div class="form-group">
								<label for="varchar"><b style="color:#990000">(*)</b>CAP <?php echo form_error('cap') ?></label>
												<div class="input-group">
													<div class="input-group-addon"><i class="fa fa-text-height"></i></div>			
								<input type="text" class="form-control" maxlength='10' name="cap" id="cap" placeholder="CAP" autocomplete="off" value="<?php echo $cap; ?>" />
							</div></div></div><div class="col-md-4">
	    <div class="form-group">
								<label for="varchar">CodFisc <?php echo form_error('codfisco') ?></label>
												<div class="input-group">
													<div class="input-group-addon"><i class="fa fa-text-height"></i></div>			
								<input type="text" class="form-control" maxlength='10' name="codfisco" id="codfisco" placeholder="CodFisc" autocomplete="off" value="<?php echo $codfisco; ?>" />
							</div></div></div><div class="col-md-4">
	    <div class="form-group">
								<label for="varchar"><b style="color:#990000">(*)</b>Prov. <?php echo form_error('codice_provincia') ?></label>
												<div class="input-group">
													<div class="input-group-addon"><i class="fa fa-text-height"></i></div>			
								<input type="text" class="form-control" maxlength='3' name="codice_provincia" id="codice_provincia" placeholder="Prov." autocomplete="off" value="<?php echo $codice_provincia; ?>" />
							</div></div></div><div class="col-md-4">
	    <div class="form-group">
								<label for="varchar"><b style="color:#990000">(*)</b>Regione <?php echo form_error('codice_regione') ?></label>
												<div class="input-group">
													<div class="input-group-addon"><i class="fa fa-text-height"></i></div>			
								<input type="text" class="form-control" maxlength='3' name="codice_regione" id="codice_regione" placeholder="Regione" autocomplete="off" value="<?php echo $codice_regione; ?>" />
							</div></div></div><div class="col-md-4">
	    <div class="form-group">
								<label for="varchar"><b style="color:#990000">(*)</b>Comune <?php echo form_error('comune') ?></label>
												<div class="input-group">
													<div class="input-group-addon"><i class="fa fa-text-height"></i></div>			
								<input type="text" class="form-control" maxlength='100' name="comune" id="comune" placeholder="Comune" autocomplete="off" value="<?php echo $comune; ?>" />
							</div></div></div><div class="col-md-4">
	    <div class="form-group">
								<label for="varchar"><b style="color:#990000">(*)</b>Prefisso <?php echo form_error('prefisso') ?></label>
												<div class="input-group">
													<div class="input-group-addon"><i class="fa fa-text-height"></i></div>			
								<input type="text" class="form-control" maxlength='10' name="prefisso" id="prefisso" placeholder="Prefisso" autocomplete="off" value="<?php echo $prefisso; ?>" />
							</div></div></div>
	
		<div class="col-md-12">
			<div class="form-group">
				<div id="divAjaxMsg" style="display:none;font-size:18px" class="alert alert-success alert-dismissable" onclick="hideMsg()">
				</div>
				<?php 
					if(isset($master_details_list)){
						foreach($master_details_list as  $master_details){
							echo $master_details;
						}
					}
				?>
				<p><br><br><br></p>
			</div>
		</div>
	    <input type="hidden" name="istat" value="<?php echo $istat; ?>" /> <div class="row">
						<div class="col-md-12">		<div class="box-footer">
								  <div class='row'>
									<div class='col-md-6'>

										<button id='<?php echo $button_id; ?>' 
										onclick='submitFormModule("<?php echo $frm_module_name; ?>","<?php echo $button_id; ?>","reset_form")'
										type="button" class="btn btn-success  button-submit"  data-loading-text="Caricamento..."><span class="fa fa-save"></span> SALVA</button>

										<button id='reset_form' name='reset_form' type="reset" class="btn btn-default">
										<span class="fa fa-eraser"></span> RESET</button>		
									</div>
									
										<div class="col-md-6" align="right">
											<a href="<?php echo site_url('mod_comuni') ?>" class="btn btn-default"><i class="fa fa-arrow-circle-left"></i> INDIETRO</a>			
										</div>	
										
									</div>
								</div>	
						</div>
					</div>	
			
		</div>		
		</div>
	</form>
							</div>
							
							<!-- form close -->
							</div>
						</div>
					</div>
			</section>

			</div>