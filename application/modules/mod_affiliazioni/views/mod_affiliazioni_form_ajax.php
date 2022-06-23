   
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
					<i class="fa fa-copy"></i> 
					<a>
					<u> Affiliazioni</u>
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
									<label for="varchar"><b style="color:#990000">(*)</b>Nome Affiliazione <?php echo form_error('nome') ?></label>
													<div class="input-group">
														<div class="input-group-addon"><i class="fa fa-text-height"></i></div>			
									<input type="text" class="form-control" maxlength='50' name="nome" id="nome" placeholder="Nome Affiliazione" autocomplete="off" value="<?php echo $nome; ?>" />
									</div></div></div><div class="col-md-4">
	    <div class="form-group">
						<?php
						$fk_ente_label = NULL;
						foreach ($fk_ente_refval as $key => $value) {
							if ($value['id'] == $fk_ente) {
								$fk_ente_label = $value['nome'];
							}  
						}
						?>						
						<label for="fk_ente"><b style="color:#990000">(*)</b>Ente <?php echo form_error('fk_ente') ?></label> 								
						<!-- <a style="cursor:pointer" onclick="winFormCombo('mod_enti','frm_mod_enti_','fk_ente_datalist','id','nome','Nuovo Ente')"><i class="fa fa-ellipsis-h" style="padding: 2px 4px;border:1px solid #ccc;"></i></a>	-->
						<a style="cursor:pointer" onclick="pulisciCampo('fk_ente_datalist_inp')"><i class="fa fa-repeat" style="padding: 2px 4px;border:1px solid #ccc;"></i></a>	

						
		<span class="arrow_datalist">
							
		<input autofocus class="form-control" autocomplete="off"
								list='fk_ente_datalist' 
								oninput='onInput("fk_ente_datalist_inp","fk_ente_datalist","fk_ente")'
								style="width:100%;padding: 6px 12px;font-size:14px;
									border-top-right-radius:0px;border-bottom-right-radius:0px;
									border-top-left-radius:0px;border-bottom-left-radius:0px;
									border:1px solid #ccc"
								name='fk_ente_datalist_inp' id='fk_ente_datalist_inp' 
								value='<?php echo $fk_ente_label;?>'></span>
							
		<input type="hidden" name='fk_ente' id='fk_ente' value='<?php echo $fk_ente;?>'>
							
		<datalist name='fk_ente_datalist' id='fk_ente_datalist' onselect="alert(this.text)">
							
		
<OPTION VALUE></OPTION>
<?php
						foreach ($fk_ente_refval as $key => $value) {
							if ($value['id'] == $fk_ente) {
								echo "<option data-value='" . $value['id'] . "' SELECTED>" . $value['nome'] . "</option>";
							} else {
								echo "<option data-value='".$value['id'] . "'>" . $value['nome'] . "</option>";
							}
						}
						?>
							</div></div><div class="col-md-4">
	    <div class="form-group">
						<?php
						$fk_esercizio_label = NULL;
						foreach ($fk_esercizio_refval as $key => $value) {
							if ($value['id'] == $fk_esercizio) {
								$fk_esercizio_label = $value['nome'];
							}  
						}
						?>						
						<label for="fk_esercizio"><b style="color:#990000">(*)</b>Esercizio <?php echo form_error('fk_esercizio') ?></label> 								
						<!-- <a style="cursor:pointer" onclick="winFormCombo('mod_esercizi','frm_mod_esercizi_','fk_esercizio_datalist','id','nome','Nuovo Esercizio')"><i class="fa fa-ellipsis-h" style="padding: 2px 4px;border:1px solid #ccc;"></i></a>-->	
						<a style="cursor:pointer" onclick="pulisciCampo('fk_esercizio_datalist_inp')"><i class="fa fa-repeat" style="padding: 2px 4px;border:1px solid #ccc;"></i></a>	

						
		<span class="arrow_datalist">
							
		<input autofocus class="form-control" autocomplete="off"
								list='fk_esercizio_datalist' 
								oninput='onInput("fk_esercizio_datalist_inp","fk_esercizio_datalist","fk_esercizio")'
								style="width:100%;padding: 6px 12px;font-size:14px;
									border-top-right-radius:0px;border-bottom-right-radius:0px;
									border-top-left-radius:0px;border-bottom-left-radius:0px;
									border:1px solid #ccc"
								name='fk_esercizio_datalist_inp' id='fk_esercizio_datalist_inp' 
								value='<?php echo $fk_esercizio_label;?>'></span>
							
		<input type="hidden" name='fk_esercizio' id='fk_esercizio' value='<?php echo $fk_esercizio;?>'>
							
		<datalist name='fk_esercizio_datalist' id='fk_esercizio_datalist' onselect="alert(this.text)">
							
		
<OPTION VALUE></OPTION>
<?php
						foreach ($fk_esercizio_refval as $key => $value) {
							if ($value['id'] == $fk_esercizio) {
								echo "<option data-value='" . $value['id'] . "' SELECTED>" . $value['nome'] . "</option>";
							} else {
								echo "<option data-value='".$value['id'] . "'>" . $value['nome'] . "</option>";
							}
						}
						?>
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
													echo "<li id='allegato_".$count."'><a href='".base_url()."mod_affiliazioni/scaricaAllegato/mod_affiliazioni/".$id."/".$nomeAllegato."' target='_blank'>".$allegato['allegato']."</a>"; 
													echo "<a style='cursor:pointer' onclick=\"rimuoviAllegato('mod_affiliazioni', '$id','$nomeAllegato','allegato_$count')\"> | ".$icon_delete."</a></li>";
													
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
				$ajaxAction = 'mod_affiliazioni/create_action';
			} else {
				$ajaxAction = 'mod_affiliazioni/update_action';

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
			

			arrayValidationFields['winMasterDetail_mod_anagrafica_tessere_assoc'] = [];
			arrayValidationFields['winMasterDetail_mod_anagrafica_tessere_assoc']['fk_anagrafica'] = [];
			arrayValidationFields['winMasterDetail_mod_anagrafica_tessere_assoc']['fk_anagrafica']['label'] = "Alunno"
			arrayValidationFields['winMasterDetail_mod_anagrafica_tessere_assoc']['fk_anagrafica']['field_type'] = "int"
			arrayValidationFields['winMasterDetail_mod_anagrafica_tessere_assoc']['fk_affiliazione'] = [];
			arrayValidationFields['winMasterDetail_mod_anagrafica_tessere_assoc']['fk_affiliazione']['label'] = "Affiliazione"
			arrayValidationFields['winMasterDetail_mod_anagrafica_tessere_assoc']['fk_affiliazione']['field_type'] = "int"
			arrayValidationFields['winMasterDetail_mod_anagrafica_tessere_assoc']['tessera_associativa'] = [];
			arrayValidationFields['winMasterDetail_mod_anagrafica_tessere_assoc']['tessera_associativa']['label'] = "Tessera Associativa"
			arrayValidationFields['winMasterDetail_mod_anagrafica_tessere_assoc']['tessera_associativa']['field_type'] = "varchar"
	
		</script>	
		<script src="<?php echo base_url(); ?>assets/js/form_submit_ajax.config.js"></script>
		</div>