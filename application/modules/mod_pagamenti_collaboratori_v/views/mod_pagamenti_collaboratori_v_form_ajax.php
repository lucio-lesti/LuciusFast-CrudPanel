   <?php
	if ((isset($id)) && ($id != "")) {
		$id_main_content = $id;
	} else {
		$id_main_content = rand(0, 1000000);
	}
	$id_button = $id;
	?>
   <div <?php if ($winForm == 'TRUE') {
			echo " class='main_content_ajax_form' ";
		} ?> id="main_content_ajax_form_<?php echo $id_main_content; ?>">
   	<!-- Content Header (Page header) -->
   	<div class="col-md-8">
   		<h3>
   			<i class="fa fa-cubes"></i>
   			<a>
   				<u> Pagamenti Collaboratori</u>
   			</a>
   			<?php
				if ((isset($id)) && ($id != "")) {
				?>
   				<b style='font-size:22px'> >> </b><b style='font-size:22px'>Modifica ID:<?= $id ?></b>
   			<?php } else { ?>
   				<b style='font-size:22px'> >> </b><b style='font-size:22px'>Nuovo</b>
   			<?php } ?>

   		</h3>
   		<?php
			$error = $this->session->flashdata('error');
			if ($error) {
			?>
   			<div class="alert alert-danger alert-dismissable">
   				<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
   				<?php echo $this->session->flashdata('error'); ?>
   			</div>
   		<?php } ?>
   		<?php
			$success = $this->session->flashdata('success');
			if ($success) {
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
						if ($afterSave == TRUE) {
							$preventDefault = 'onsubmit="event.preventDefault();return false"';
						}
						?>
   					<form action="<?php echo $action; ?>" method="post" name="<?php echo $frm_module_name . '_' . $id; ?>" id="<?php echo $frm_module_name . '_' . $id; ?>" <?php $preventDefault; ?>\>
						<input type ="hidden" name="fk_contratto" id="fk_contratto" value="<?php echo $contratto_id?>" />
						<input type ="hidden" name="fk_anagrafica" id="fk_anagrafica" value="<?php echo $fk_anagrafica?>" />
					   	<div class='col-md-12'>
   							<B STYLE='color:#990000'>(*)</B>Campi obbligatori
   							<br><br>
   						</div>
   						<div class="col-md-8">
   							<div class="form-group">
   								<label for="varchar">Collaboratore <?php echo form_error('collaboratore') ?></label>
	                            <a href="<?php echo base_url()."mod_anagrafica/?id=$fk_anagrafica"?>" target="_blank">
                                    <i class="fa fa-external-link" 
                                    style="border-radius: 0;border:solid 1px #999;background-color:#d2d6de;padding:5px"></i>
                                </a>
   								<div class="input-group">
   									<div class="input-group-addon"><i class="fa fa-text-height"></i></div>
   									<input type="text" style="width:450px" class="form-control" readonly="readonly" maxlength='120' name="collaboratore" id="collaboratore" placeholder="Collaboratore" autocomplete="off" value="<?php echo $collaboratore; ?>" />
   								</div>
   							</div>
   						</div>
   						<div class="col-md-8">
   							<div class="form-group">
   								<label for="varchar"> Nome Contratto <?php echo form_error('contratto_nome') ?></label>
								<a href="<?php echo base_url()."mod_contratti_collaboratori/?id=$contratto_id"?>" target="_blank">
                                    <i class="fa fa-external-link" 
                                    style="border-radius: 0;border:solid 1px #999;background-color:#d2d6de;padding:5px"></i>
                                </a>								   
   								<div class="input-group">
   									<div class="input-group-addon"><i class="fa fa-text-height"></i></div>
   									<input type="text" style="width:450px" readonly="readonly" class="form-control" maxlength='50' name="contratto_nome" id="contratto_nome" placeholder="Nome Contratto" autocomplete="off" value="<?php echo $contratto_nome; ?>" />
   								</div>
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
											if (isset($master_details_list)) {
												$countTab = 0;
												foreach ($master_details_list as  $key => $master_details) {
													if ($countTab == 0) {
														echo '<li class="nav-item active">
									<a class="nav-link active" id="lnk-' . $master_details['id'] . '" data-toggle="tab" href="#' . $master_details['id'] . '" role="tab" aria-controls="' . $master_details['id'] . '" aria-selected="true" aria-expanded="true">' . $master_details['title'] . '</a>
									</li>';
													} else {
														echo '<li class="nav-item">
									<a class="nav-link" id="lnk-' . $master_details['id'] . '" data-toggle="tab" href="#' . $master_details['id'] . '" role="tab" aria-controls="' . $master_details['id'] . '" aria-selected="true" aria-expanded="true">' . $master_details['title'] . '</a>
									</li>';
													}
													$countTab++;
												}
											}
											?>
   									</ul>
   									<div class="tab-content">
   										<?php
											if (isset($master_details_list)) {
												$countTab = 0;
												foreach ($master_details_list as  $key => $master_details) {
													$active = "active";
													if ($countTab > 0) {
														$active = "";
													}
													echo '<div class="tab-pane ' . $active . '" id="' . $master_details['id'] . '" role="tabpanel" aria-labelledby="' . $master_details['id'] . '-tab">';
													echo  $master_details['function'];
													echo '</div>';
													$countTab++;
												}
											}
											?>

   									</div>
   								</div>
   								<p><br><br><br></p>
   							</div>
   						</div>
   						<input type="hidden" name="" value="<?php echo $id; ?>" />
 
   					</form>
   				</div>

   				<!-- form close -->
   			</div>
   		</div>
   	</div>
   	<?php
		if ($type_action == 'create') {
			$ajaxAction = 'mod_pagamenti_collaboratori_v/create_action';
		} else {
			$ajaxAction = 'mod_pagamenti_collaboratori_v/update_action';
		}

		$data['ajaxAction'] = $ajaxAction;
		$data['frm_module_name'] = $frm_module_name;
		$data['id'] = $id;
		$data['id_main_content'] = $id_main_content;
		?>
   	<?php echo $this->load->view("jsconfig/mod_pagamenti_collaboratori_v_form_config.js.php", $data, true); ?>
   	<script src="<?php echo base_url(); ?>assets/js/form_submit_ajax.config.js"></script>
   </div>