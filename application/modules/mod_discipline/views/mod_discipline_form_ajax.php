   
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
					<u> Discipline</u>
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
									<label for="varchar"><b style="color:#990000">(*)</b>Nome Disciplina <?php echo form_error('nome') ?></label>
													<div class="input-group">
														<div class="input-group-addon"><i class="fa fa-text-height"></i></div>			
									<input type="text" class="form-control" maxlength='255' name="nome" id="nome" placeholder="Nome Disciplina" autocomplete="off" value="<?php echo $nome; ?>" />
									</div></div></div><div class="col-md-4">
	    <div class="form-group">
									<label for="varchar"><b style="color:#990000">(*)</b>Codice Disciplina <?php echo form_error('codice_disciplina') ?></label>
													<div class="input-group">
														<div class="input-group-addon"><i class="fa fa-text-height"></i></div>			
									<input type="text" class="form-control" maxlength='30' name="codice_disciplina" id="codice_disciplina" placeholder="Codice Disciplina" autocomplete="off" value="<?php echo $codice_disciplina; ?>" />
									</div></div></div><div class="col-md-4">
	    <div class="form-group">
						<?php
						$fk_sport_label = NULL;
						foreach ($fk_sport_refval as $key => $value) {
							if ($value['id'] == $fk_sport) {
								$fk_sport_label = $value['nome'];
							}  
						}
						?>						
						<label for="fk_sport"><b style="color:#990000">(*)</b>Sport <?php echo form_error('fk_sport') ?></label> 								
						<a style="cursor:pointer" onclick="pulisciCampo('fk_sport_datalist_inp')"><i class="fa fa-repeat" style="padding: 2px 4px;border:1px solid #ccc;"></i></a>	

						
		<span class="arrow_datalist">
							
		<input autofocus class="form-control" autocomplete="off"
								list='fk_sport_datalist' 
								oninput='onInput("fk_sport_datalist_inp","fk_sport_datalist","fk_sport")'
								style="width:100%;padding: 6px 12px;font-size:14px;
									border-top-right-radius:0px;border-bottom-right-radius:0px;
									border-top-left-radius:0px;border-bottom-left-radius:0px;
									border:1px solid #ccc"
								name='fk_sport_datalist_inp' id='fk_sport_datalist_inp' 
								value='<?php echo $fk_sport_label;?>'></span>
							
		<input type="hidden" name='fk_sport' id='fk_sport' value='<?php echo $fk_sport;?>'>
							
		<datalist name='fk_sport_datalist' id='fk_sport_datalist' onselect="alert(this.text)">
							
		
<OPTION VALUE></OPTION>
<?php
						foreach ($fk_sport_refval as $key => $value) {
							if ($value['id'] == $fk_sport) {
								echo "<option data-value='" . $value['id'] . "' SELECTED>" . $value['nome'] . "</option>";
							} else {
								echo "<option data-value='".$value['id'] . "'>" . $value['nome'] . "</option>";
							}
						}
						?>
							</div></div>
	
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
									<a class="nav-link active" id="lnk-'.$master_details['id'].'" data-toggle="tab" href="#'.$master_details['id'].'" role="tab" aria-controls="'.$master_details['id'].'" aria-selected="true" aria-expanded="true">'.$master_details['title'].'</a>
									</li>';
								} else {
									echo'<li class="nav-item">
									<a class="nav-link" id="lnk-'.$master_details['id'].'" data-toggle="tab" href="#'.$master_details['id'].'" role="tab" aria-controls="'.$master_details['id'].'" aria-selected="true" aria-expanded="true">'.$master_details['title'].'</a>
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
								echo'<div class="tab-pane '.$active.'" id="'.$master_details['id'].'" role="tabpanel" aria-labelledby="'.$master_details['id'].'-tab">';
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
				$ajaxAction = 'mod_discipline/create_action';
			} else {
				$ajaxAction = 'mod_discipline/update_action';

			}	

			$data['ajaxAction'] = $ajaxAction;
			$data['frm_module_name'] = $frm_module_name;
			$data['id'] = $id;
			$data['id_main_content'] = $id_main_content;
		?>
<?php echo $this->load->view("jsconfig/mod_discipline_form_config.js.php", $data,true);?>
<script src="<?php echo base_url(); ?>assets/js/form_submit_ajax.config.js"></script>
		</div>