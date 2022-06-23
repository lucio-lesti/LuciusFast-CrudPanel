   
<?php 
	if((isset($id)) && ($id != "")){
		$id_main_content = $id;
	} else {	
		$id_main_content = rand(0,1000000);		
	}	
	$id_button = $id;
?>
<div id="main_content_ajax_form_<?php echo $id_main_content;?>">	
	<!-- Content Header (Page header) -->
        <div class="col-md-8">
        <h3>
            <i class="fa fa-bug"></i> 
			<a>
			<u> Logs</u>
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
			id="<?php echo $frm_module_name.'_'.$id; ?>" <?php $preventDefault;?>\><div class="col-md-4">
	    <div class="form-group">
					<label for="varchar">Programma / Modulo <?php echo form_error('programma') ?></label>
									<div class="input-group">
										<div class="input-group-addon"><i class="fa fa-text-height"></i></div>			
					<input type="text" class="form-control" maxlength='100' name="programma" id="programma" placeholder="Programma / Modulo" autocomplete="off" value="<?php echo $programma; ?>" />
				</div></div></div><div class="col-md-4">
	    <div class="form-group">
					<label for="varchar">Utente <?php echo form_error('utente') ?></label>
									<div class="input-group">
										<div class="input-group-addon"><i class="fa fa-text-height"></i></div>			
					<input type="text" class="form-control" maxlength='100' name="utente" id="utente" placeholder="Utente" autocomplete="off" value="<?php echo $utente; ?>" />
				</div></div></div><div class="col-md-4">
	    <div class="form-group">
					<label for="varchar">Azione <?php echo form_error('azione') ?></label>
									<div class="input-group">
										<div class="input-group-addon"><i class="fa fa-text-height"></i></div>			
					<input type="text" class="form-control" maxlength='100' name="azione" id="azione" placeholder="Azione" autocomplete="off" value="<?php echo $azione; ?>" />
				</div></div></div><div class="col-md-4">
	    <div class="form-group">
					<label for="datetime">Data <?php echo form_error('data') ?></label>
									<div class="input-group">
										<div class="input-group-addon"><i class="fa fa-calendar"></i></div>			
					<input type="text" class="form-control datetimepicker" name="data" id="data" placeholder="Data" autocomplete="off" value="<?php echo $data; ?>" />
				</div></div></div>
	    <input type="hidden" name="id" value="<?php echo $id; ?>" /> <div class="row">
				<div class="col-md-12">
						  <div class='row'>
							<div class='col-md-6'>

								<button id='<?php echo $button_id; ?>' type="submit" 
								type="button" class="btn btn-success  button-submit"  data-loading-text="Caricamento..."><span class="fa fa-save"></span> SALVA</button>

								<button id='reset_form' name='reset_form' type="reset" class="btn btn-default">
								<span class="fa fa-eraser"></span> RESET</button>		
							</div>
							
								<div class="col-md-6" align="right">		
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
<?php
	if($type_action == 'create'){
		$ajaxAction = 'mod_logs/create_action';
	} else {
		$ajaxAction = 'mod_logs/update_action';

	}	
?>	
<script>
	$('.datepicker').datepicker({locale:'it'});
	$('.datetimepicker').datetimepicker({locale:'it', format: 'DD/MM/YYYY'}); 	


	$(document).unbind('submit');
	$(document).on('submit','#<?php echo  $frm_module_name."_".$id;?>',function(e){

		var form = $('#<?php echo  $frm_module_name."_".$id;?>')[0];  
    	var form_data = new FormData(form);   

		e.preventDefault(); 
		showBiscuit();
		$.ajax({ 
			type: 'POST', 
			url: '<?php echo $ajaxAction;?>', 
			data: form_data,
			processData: false,
			contentType: false,
			success: function (response) {
				
				document.getElementById('main_content_ajax_form_<?php echo $id_main_content;?>').innerHTML = response;
				$('.datepicker').datepicker({locale:'it'});
				$('.datetimepicker').datetimepicker({locale:'it', format: 'DD/MM/YYYY'}); 
				
				hideBiscuit();					
							
			},
	        error: function (xhr, ajaxOptions, thrownError) {
	            alert('Errore Lancio Elaborazione.');
	            hideBiscuit();
	        } 				   
	   });
	   return false;
	});	
	

	function redirectSuccessfullAjaxCall(urlRedirect){
		$.ajax({
			type: 'GET',
			url: urlRedirect,
			success: function (response) {
				document.getElementById('main_content_ajax_form_<?php echo $id_main_content;?>').innerHTML = response;
				$('#mytable').DataTable().ajax.reload();
				$('.datepicker').datepicker({locale:'it'});
				$('.datetimepicker').datetimepicker({locale:'it', format: 'DD/MM/YYYY'}); 	
				
				hideBiscuit();

			}
		});
	}	
</script>	
</div>
	