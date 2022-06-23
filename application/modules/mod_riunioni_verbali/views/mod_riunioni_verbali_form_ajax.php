   
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
					<u> Verbali riunioni</u>
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
				<form action="<?php echo $action; ?>" method="post" 
					name="<?php echo $frm_module_name.'_'.$id; ?>" 
					id="<?php echo $frm_module_name.'_'.$id; ?>" <?php $preventDefault;?>\>
					<div class='col-md-12'>
						<B STYLE='color:#990000'>(*)</B>Campi obbligatori
						<br><br>					
					</div><div class="col-md-4">
	    <div class="form-group">
									<label for="varchar"><b style="color:#990000">(*)</b>Oggetto <?php echo form_error('oggetto') ?></label>
													<div class="input-group">
														<div class="input-group-addon"><i class="fa fa-text-height"></i></div>			
									<input type="text" class="form-control" maxlength='50' name="oggetto" id="oggetto" placeholder="Oggetto" autocomplete="off" value="<?php echo $oggetto; ?>" />
									</div></div></div><div class="col-md-4">
	    <div class="form-group">
							<label for="date"><b style="color:#990000">(*)</b>Data Riunione <?php echo form_error('data_riunione_verbale') ?></label>
											<div class="input-group">
												<div class="input-group-addon"><i class="fa fa-calendar"></i></div>			
							<input type="text" class="form-control datemask" name="data_riunione_verbale" id="data_riunione_verbale" placeholder="Data Riunione" 
								autocomplete="off" style="background-color:#FFFFFF"  value="<?php echo $data_riunione_verbale; ?>" />
						</div></div></div><div class="col-md-4">
	    <div class="form-group">
							<label for="note">Note <?php echo form_error('note') ?></label>
							<textarea class="form-control" rows="3" name="note" id="note" placeholder="Note"><?php echo $note; ?></textarea>
						</div></div>
	
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
													echo "<li id='allegato_".$count."'><a href='".base_url()."mod_riunioni_verbali/scaricaAllegato/mod_riunioni_verbali/".$id."/".$nomeAllegato."' target='_blank'>".$allegato['allegato']."</a>"; 
													echo "<a style='cursor:pointer' onclick=\"rimuoviAllegato('mod_riunioni_verbali', '$id','$nomeAllegato','allegato_$count')\"> | ".$icon_delete."</a></li>";
													
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
	    <input type="hidden" name="id" value="<?php echo $id; ?>" /> <div class="row">
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
				$ajaxAction = 'mod_riunioni_verbali/create_action';
			} else {
				$ajaxAction = 'mod_riunioni_verbali/update_action';

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