   
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
					<u> Sale</u>
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
									<label for="varchar"><b style="color:#990000">(*)</b>Nome Sala <?php echo form_error('nome') ?></label>
													<div class="input-group">
														<div class="input-group-addon"><i class="fa fa-text-height"></i></div>			
									<input type="text" class="form-control" maxlength='100' name="nome" id="nome" placeholder="Nome Sala" autocomplete="off" value="<?php echo $nome; ?>" />
									</div></div></div><div class="col-md-4">
	    <div class="form-group">
						<?php
						$fk_sede_label = NULL;
						foreach ($fk_sede_refval as $key => $value) {
							if ($value['id'] == $fk_sede) {
								$fk_sede_label = $value['nome'];
							}  
						}
						?>						
						<label for="fk_sede"><b style="color:#990000">(*)</b>Sede <?php echo form_error('fk_sede') ?></label> 								
							
		<span class="arrow_datalist">
							
		<input autofocus class="form-control" autocomplete="off"
								list='fk_sede_datalist' 
								oninput='onInput("fk_sede_datalist_inp","fk_sede_datalist","fk_sede")'
								style="width:100%;padding: 6px 12px;font-size:14px;
									border-top-right-radius:0px;border-bottom-right-radius:0px;
									border-top-left-radius:0px;border-bottom-left-radius:0px;
									border:1px solid #ccc"
								name='fk_sede_datalist_inp' id='fk_sede_datalist_inp' 
								value='<?php echo $fk_sede_label;?>'></span>
							
		<input type="hidden" name='fk_sede' id='fk_sede' value='<?php echo $fk_sede;?>'>
							
		<datalist name='fk_sede_datalist' id='fk_sede_datalist' onselect="alert(this.text)">
							
		
<OPTION VALUE></OPTION>
<?php
						foreach ($fk_sede_refval as $key => $value) {
							if ($value['id'] == $fk_sede) {
								echo "<option data-value='" . $value['id'] . "' SELECTED>" . $value['nome'] . "</option>";
							} else {
								echo "<option data-value='".$value['id'] . "'>" . $value['nome'] . "</option>";
							}
						}
						?>
							</div></div><div class="col-md-4">
	    <div class="form-group">
							<label for="int"><b style="color:#990000">(*)</b>Capienza <?php echo form_error('capienza') ?></label>
										<div class="input-group">
											<div class="input-group-addon"><i class="fa fa-sort-numeric-asc"></i></div>			
							<input type="number" class="form-control" maxlength='10' name="capienza" id="capienza" placeholder="Capienza" autocomplete="off" value="<?php echo $capienza; ?>" />
							</div></div></div>
	
		<div class="col-md-12">
		<div class="form-group" id="divAjaxMsg_container">
			<div id="divAjaxMsg" style="display:none;font-size:18px" class="alert alert-success alert-dismissable" onclick="hideMsg('divAjaxMsg')">
			</div>
			<div id="divAjaxMsgErr" style="display:none;font-size:18px" class="alert alert-error alert-dismissable" onclick="hideMsg('divAjaxMsgErr')">
			</div>			
			<div id="master_details_list" name="master_details_list">
				<ul class="nav nav-tabs" id="myTab" role="tablist">
					<br>	
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
				$ajaxAction = 'mod_sale/create_action';
			} else {
				$ajaxAction = 'mod_sale/update_action';

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
			

			arrayValidationFields['winMasterDetail_mod_corsi_sale_calendario'] = [];
			/*js_custom_operations_list['winMasterDetail_mod_corsi_sale_calendario'] = [];
			js_custom_operations_list['winMasterDetail_mod_corsi_sale_calendario'].check_date = function(){
				var ret = check_date_greater_then('data_validita_inizio','data_validita_fine');
				if(ret === false){
					document.getElementById('msg_err').style.display = "block";
            		document.getElementById('msg_err').innerHTML = "Date non valide";
            		ev.preventDefault();
				}
			};*/
			arrayValidationFields['winMasterDetail_mod_corsi_sale_calendario']['data_corso_a'] = [];
			arrayValidationFields['winMasterDetail_mod_corsi_sale_calendario']['data_corso_a']['label'] = "Data Corso a	"
			arrayValidationFields['winMasterDetail_mod_corsi_sale_calendario']['data_corso_a']['field_type'] = "date"
			arrayValidationFields['winMasterDetail_mod_corsi_sale_calendario']['data_corso_da'] = [];
			arrayValidationFields['winMasterDetail_mod_corsi_sale_calendario']['data_corso_da']['label'] = "Data Corso Da"
			arrayValidationFields['winMasterDetail_mod_corsi_sale_calendario']['data_corso_da']['field_type'] = "date"
			arrayValidationFields['winMasterDetail_mod_corsi_sale_calendario']['fk_corso'] = [];
			arrayValidationFields['winMasterDetail_mod_corsi_sale_calendario']['fk_corso']['label'] = "Corso"
			arrayValidationFields['winMasterDetail_mod_corsi_sale_calendario']['fk_corso']['field_type'] = "int"
			arrayValidationFields['winMasterDetail_mod_corsi_sale_calendario']['fk_sala'] = [];
			arrayValidationFields['winMasterDetail_mod_corsi_sale_calendario']['fk_sala']['label'] = "Sala"
			arrayValidationFields['winMasterDetail_mod_corsi_sale_calendario']['fk_sala']['field_type'] = "int"
			arrayValidationFields['winMasterDetail_mod_corsi_sale_calendario']['ora_corso_a'] = [];
			arrayValidationFields['winMasterDetail_mod_corsi_sale_calendario']['ora_corso_a']['label'] = "Ora a"
			arrayValidationFields['winMasterDetail_mod_corsi_sale_calendario']['ora_corso_a']['field_type'] = "varchar"
			arrayValidationFields['winMasterDetail_mod_corsi_sale_calendario']['ora_corso_da'] = [];
			arrayValidationFields['winMasterDetail_mod_corsi_sale_calendario']['ora_corso_da']['label'] = "Ora da"
			arrayValidationFields['winMasterDetail_mod_corsi_sale_calendario']['ora_corso_da']['field_type'] = "varchar"
	
		</script>	
		<script src="<?php echo base_url(); ?>assets/js/form_submit_ajax.config.js"></script>
		</div>