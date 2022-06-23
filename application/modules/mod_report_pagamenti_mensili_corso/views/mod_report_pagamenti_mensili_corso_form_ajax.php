   
		<?php 
			if((isset($id)) && ($id != "")){
				$id_main_content = $id;
			} else {	
				$id_main_content = rand(0,1000000);		
			}	
			$id_button = $id;
		?>
		<div <?php if($winForm == 'TRUE'){ echo " class='main_content_ajax_form' "; } ?> id="main_content_ajax_form_<?php echo $id_main_content;?>">	
			<!-- Content Header (Page header) -->
				<div class="col-md-8">
				<h3>
					<i class="fa fa-cubes"></i> 
					<a>
					<u> Report Pagamenti Mensili</u>
					</a>
					<?php 
						if((isset($id)) && ($id != "")){
					?>
					<b style='font-size:22px'> >> </b><b style='font-size:22px'>Modifica ID:<?=$id?></b>
						<?php } else { ?>
					<b style='font-size:22px'> >> </b><b style='font-size:22px'>Nuovo</b>
					<?php }?>		   
				   
				</h3>		
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
				<div class="row">
					<!-- left column -->
					<div class="col-md-12">
						<!-- general form elements -->
						<div class="box box-primary">
							<!-- /.box-header -->
							<!-- form start -->
						
							 <div class="box-body">
								<?php $this->load->helper("form"); ?>
								<?php
									//SE GIA SALVATO IMPEDISCO IL SUBMIT DEL FORM
									if($afterSave == TRUE){
										$preventDefault = 'onsubmit="event.preventDefault();return false"';	
									}
								?>
										<div class="form-group">
											<?php 
												if((isset($id)) && ($id != '')){
											?>
											<a target='_blank' class='btn btn-default' href='<?php echo base_url()."mod_report_pagamenti_mensili_corso/stampa/$id";?>'><i class="fa fa-print"></i> STAMPA</a>
											<?php }?>
										</div>
				<form action="<?php echo $action; ?>" method="post" 
					name="<?php echo $frm_module_name.'_'.$id; ?>" 
					id="<?php echo $frm_module_name.'_'.$id; ?>" <?php $preventDefault;?>\>
					<div class='col-md-12'>
						<B STYLE='color:#990000'>(*)</B>Campi obbligatori
						<br><br>					
					</div><div class="col-md-4">
	    <div class="form-group">
									<label for="char"><b style="color:#990000">(*)</b>Ids <?php echo form_error('ids') ?></label>
													<div class="input-group">
														<div class="input-group-addon"><i class="fa fa-text-height"></i></div>			
									<input type="text" class="form-control" maxlength='0' name="ids" id="ids" placeholder="Ids" autocomplete="off" value="<?php echo $ids; ?>" />
									</div></div></div><div class="col-md-4">
	    <div class="form-group">
									<label for="char"><b style="color:#990000">(*)</b>Id <?php echo form_error('id') ?></label>
													<div class="input-group">
														<div class="input-group-addon"><i class="fa fa-text-height"></i></div>			
									<input type="text" class="form-control" maxlength='0' name="id" id="id" placeholder="Id" autocomplete="off" value="<?php echo $id; ?>" />
									</div></div></div><div class="col-md-4">
	    <div class="form-group">
									<label for="varchar"><b style="color:#990000">(*)</b>Anagrafica <?php echo form_error('Anagrafica') ?></label>
													<div class="input-group">
														<div class="input-group-addon"><i class="fa fa-text-height"></i></div>			
									<input type="text" class="form-control" maxlength='101' name="Anagrafica" id="Anagrafica" placeholder="Anagrafica" autocomplete="off" value="<?php echo $Anagrafica; ?>" />
									</div></div></div><div class="col-md-4">
	    <div class="form-group">
							<label for="date">Data Iscrizione <?php echo form_error('Data_Iscrizione') ?></label>
											<div class="input-group">
												<div class="input-group-addon"><i class="fa fa-calendar"></i></div>			
							<input type="text" class="form-control datemask" name="Data_Iscrizione" id="Data_Iscrizione" placeholder="Data Iscrizione" 
								autocomplete="off" style="background-color:#FFFFFF"  value="<?php echo $Data_Iscrizione; ?>" />
						</div></div></div><div class="col-md-4">
	    <div class="form-group">
									<label for="varchar">Set <?php echo form_error('Set') ?></label>
													<div class="input-group">
														<div class="input-group-addon"><i class="fa fa-text-height"></i></div>			
									<input type="text" class="form-control" maxlength='10' name="Set" id="Set" placeholder="Set" autocomplete="off" value="<?php echo $Set; ?>" />
									</div></div></div><div class="col-md-4">
	    <div class="form-group">
									<label for="varchar">Ott <?php echo form_error('Ott') ?></label>
													<div class="input-group">
														<div class="input-group-addon"><i class="fa fa-text-height"></i></div>			
									<input type="text" class="form-control" maxlength='10' name="Ott" id="Ott" placeholder="Ott" autocomplete="off" value="<?php echo $Ott; ?>" />
									</div></div></div><div class="col-md-4">
	    <div class="form-group">
									<label for="varchar">Nov <?php echo form_error('Nov') ?></label>
													<div class="input-group">
														<div class="input-group-addon"><i class="fa fa-text-height"></i></div>			
									<input type="text" class="form-control" maxlength='10' name="Nov" id="Nov" placeholder="Nov" autocomplete="off" value="<?php echo $Nov; ?>" />
									</div></div></div><div class="col-md-4">
	    <div class="form-group">
									<label for="varchar">Dic <?php echo form_error('Dic') ?></label>
													<div class="input-group">
														<div class="input-group-addon"><i class="fa fa-text-height"></i></div>			
									<input type="text" class="form-control" maxlength='10' name="Dic" id="Dic" placeholder="Dic" autocomplete="off" value="<?php echo $Dic; ?>" />
									</div></div></div><div class="col-md-4">
	    <div class="form-group">
									<label for="varchar">Gen <?php echo form_error('Gen') ?></label>
													<div class="input-group">
														<div class="input-group-addon"><i class="fa fa-text-height"></i></div>			
									<input type="text" class="form-control" maxlength='10' name="Gen" id="Gen" placeholder="Gen" autocomplete="off" value="<?php echo $Gen; ?>" />
									</div></div></div><div class="col-md-4">
	    <div class="form-group">
									<label for="varchar">Feb <?php echo form_error('Feb') ?></label>
													<div class="input-group">
														<div class="input-group-addon"><i class="fa fa-text-height"></i></div>			
									<input type="text" class="form-control" maxlength='10' name="Feb" id="Feb" placeholder="Feb" autocomplete="off" value="<?php echo $Feb; ?>" />
									</div></div></div><div class="col-md-4">
	    <div class="form-group">
									<label for="varchar">Mar <?php echo form_error('Mar') ?></label>
													<div class="input-group">
														<div class="input-group-addon"><i class="fa fa-text-height"></i></div>			
									<input type="text" class="form-control" maxlength='10' name="Mar" id="Mar" placeholder="Mar" autocomplete="off" value="<?php echo $Mar; ?>" />
									</div></div></div><div class="col-md-4">
	    <div class="form-group">
									<label for="varchar">Apr <?php echo form_error('Apr') ?></label>
													<div class="input-group">
														<div class="input-group-addon"><i class="fa fa-text-height"></i></div>			
									<input type="text" class="form-control" maxlength='10' name="Apr" id="Apr" placeholder="Apr" autocomplete="off" value="<?php echo $Apr; ?>" />
									</div></div></div><div class="col-md-4">
	    <div class="form-group">
									<label for="varchar">Mag <?php echo form_error('Mag') ?></label>
													<div class="input-group">
														<div class="input-group-addon"><i class="fa fa-text-height"></i></div>			
									<input type="text" class="form-control" maxlength='10' name="Mag" id="Mag" placeholder="Mag" autocomplete="off" value="<?php echo $Mag; ?>" />
									</div></div></div><div class="col-md-4">
	    <div class="form-group">
									<label for="varchar">Giu <?php echo form_error('Giu') ?></label>
													<div class="input-group">
														<div class="input-group-addon"><i class="fa fa-text-height"></i></div>			
									<input type="text" class="form-control" maxlength='10' name="Giu" id="Giu" placeholder="Giu" autocomplete="off" value="<?php echo $Giu; ?>" />
									</div></div></div><div class="col-md-4">
	    <div class="form-group">
									<label for="varchar">Lug <?php echo form_error('Lug') ?></label>
													<div class="input-group">
														<div class="input-group-addon"><i class="fa fa-text-height"></i></div>			
									<input type="text" class="form-control" maxlength='10' name="Lug" id="Lug" placeholder="Lug" autocomplete="off" value="<?php echo $Lug; ?>" />
									</div></div></div><div class="col-md-4">
	    <div class="form-group">
									<label for="varchar">Ago <?php echo form_error('Ago') ?></label>
													<div class="input-group">
														<div class="input-group-addon"><i class="fa fa-text-height"></i></div>			
									<input type="text" class="form-control" maxlength='10' name="Ago" id="Ago" placeholder="Ago" autocomplete="off" value="<?php echo $Ago; ?>" />
									</div></div></div>
	
							<div class="col-md-12">
								<div class="form-group">
										<label for="note">Allegati <?php echo form_error("allegati") ?>
										<br><span style="font-size:12px"><b>File Ammessi:</b><b style="color:#990000"><?php echo implode(",",$extAdmitted);?></b></span>
										</label>
										<input type="file" name="allegati[]" id="allegati[]" class="form-control" multiple />
										
										<br>
										<ul>
										<?php		
											$icon_delete = "<img src='".base_url()."assets/images/delete2.png' width='32'/>";
											
											$count = 0;
											foreach($allegati as $key => $allegato){
												$nomeAllegato =  $allegato['allegato'];

												if(($id != '')){
													echo "<li id='allegato_".$count."'><a href='".base_url()."mod_report_pagamenti_mensili_corso/scaricaAllegato/mod_report_pagamenti_mensili_corso/".$id."/".$nomeAllegato."' target='_blank'>".$allegato['allegato']."</a>"; 
													echo "<a style='cursor:pointer' onclick=\"rimuoviAllegato('mod_report_pagamenti_mensili_corso', '$id','$nomeAllegato','allegato_$count')\"> | ".$icon_delete."</a></li>";
													
												} else {
													echo $allegato['allegato']."<BR>"; 
												}
												
												$count++;
											}
											if(($id == '')){
												echo "<br><br><b> - Note:</b>Gli allegati possono essere cancellati solo in modifica";
											}
										?>
										</ul>
										<br/>
									</div>
							</div>
	
		<div class="col-md-12">
		<div class="form-group" id="divAjaxMsg_container">
			<div id="divAjaxMsg" style="display:none;font-size:18px" class="alert alert-success alert-dismissable" onclick="hideMsg('divAjaxMsg')">
			</div>
			<div id="divAjaxMsgErr" style="display:none;font-size:18px" class="alert alert-error alert-dismissable" onclick="hideMsg('divAjaxMsgErr')">
			</div>			
			<div id="master_details_list" name="master_details_list">
				<ul class="nav nav-tabs" id="myTab" role="tablist">	
				<?php
					if(isset($master_details_list)){
						$countTab = 0;
						foreach($master_details_list as  $key => $master_details){
							if($countTab == 0) {
								echo'<li class="nav-item active">
								<a class="nav-link active" id="lnk-'.$key.'" data-toggle="tab" href="#'.$key.'" role="tab" aria-controls="'.$key.'" aria-selected="true" aria-expanded="true">'.$master_details['title'].'</a>
								</li>';
							} else {
								echo'<li class="nav-item">
								<a class="nav-link" id="lnk-'.$key.'" data-toggle="tab" href="#'.$key.'" role="tab" aria-controls="'.$key.'" aria-selected="true" aria-expanded="true">'.$master_details['title'].'</a>
								</li>';
							}
							$countTab++;
						}	
					}
				?>
				</ul>
				<div class="tab-content">
				<?php
					if(isset($master_details_list)){
						$countTab = 0;
						foreach($master_details_list as  $key => $master_details){
							$active = "active";
							if($countTab > 0) {
								$active = "";
							} 
							echo'<div class="tab-pane '.$active.'" id="'.$key.'" role="tabpanel" aria-labelledby="'.$key.'-tab">';
							echo  $master_details['function'];
							echo'</div>';
							$countTab++;
						}	
					}
				?>

				</div>
			</div>					
			<p><br><br><br></p>
		</div>
	</div>

						<div class="col-md-12">
<?php
	if ($afterSave == NULL) {
?>
								  <div class='row'>
									<div class='col-md-6'>
										<?php 
										if($winForm == "FALSE"){
										?>
										<button id='<?php echo $button_id; ?>' type="submit" 
										type="button" class="btn btn-success  button-submit"  data-loading-text="Caricamento..."><span class="fa fa-save"></span> SALVA</button>
 										<?php } ?>
									</div>
									
										<div class="col-md-6" align="right">		
										</div>	
										
									</div>
						</div>
					</div>
<?php } ?>
	</form>
						</div>
							
						<!-- form close -->
					</div>
				</div>
			</div>
		<?php
			if($type_action == 'create'){
				$ajaxAction = 'mod_report_pagamenti_mensili_corso/create_action';
			} else {
				$ajaxAction = 'mod_report_pagamenti_mensili_corso/update_action';

			}	
		?>
		<script>
			arrayValidationFields = [];
			js_custom_operations_list = [];
			objAjaxConfig.form.mod_name ="<?php echo $frm_module_name;?>";
			objAjaxConfig.form.recordID ="<?php echo $id;?>";
			objAjaxConfig.form.ajaxAction = "<?php echo $ajaxAction;?>";
			objAjaxConfig.form.id_main_content = "<?php echo $id_main_content;?>";
			$('.select2-autocomplete').select2();	
			
	
		</script>	
		<script src="<?php echo base_url(); ?>assets/js/form_submit_ajax.config.js"></script>
		</div>