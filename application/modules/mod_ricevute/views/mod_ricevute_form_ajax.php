   
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
            <i class="fa fa-euro"></i> 
			<a>
			<u> Pagamenti/Ricevute</u>
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
			
<div class="col-md-12">
	    <div class="form-group">
				<?php 
				if((isset($id)) && ($id != "")){
				?>
				<a href="mod_stampe/stampaRic/NULL/NULL/<?php echo $id;?>" class="btn btn-default" target="_blank">
					<span class="fa fa-print"></span> Stampa</a>
					
				</div></div></div>
				<?php } ?>
			
			<div class="col-md-4">
	    <div class="form-group">
					<label for="int">Fk Anagrafica <?php echo form_error('fk_anagrafica') ?></label>
									<div class="input-group">
										<div class="input-group-addon"><i class="fa fa-sort-numeric-asc"></i></div>			
					<input type="number" class="form-control" maxlength='10' name="fk_anagrafica" id="fk_anagrafica" placeholder="Fk Anagrafica" autocomplete="off" value="<?php echo $fk_anagrafica; ?>" />
				</div></div></div>

			<div class="col-md-4">
	    <div class="form-group">
					<label for="int">Tessera Interna <?php echo form_error('fk_anagrafica') ?></label>
									<div class="input-group">
										<div class="input-group-addon"><i class="fa fa-text-height"></i></div>			
					<input type="text" class="form-control" maxlength='10' name="tessera" id="tessera" placeholder="Tessera" autocomplete="off" value="<?php echo $tessera; ?>" readonly="readonly" />
				</div></div></div>
				
			<div class="col-md-4">
	    <div class="form-group">
					<label for="int">Tessera Associativa<?php echo form_error('fk_anagrafica') ?></label>
									<div class="input-group">
										<div class="input-group-addon"><i class="fa fa-text-height"></i></div>			
					<input type="text" class="form-control" maxlength='10' name="tess_asc" id="tess_asc" placeholder="Tessera Associativa" autocomplete="off" value="<?php echo $tess_asc; ?>" readonly="readonly" />
				</div></div></div>				

			<div class="col-md-4">
	    <div class="form-group">
					<label for="int">Nome <?php echo form_error('fk_anagrafica') ?></label>
									<div class="input-group">
										<div class="input-group-addon"><i class="fa fa-text-height"></i></div>			
					<input type="text" class="form-control" maxlength='10' name="nome" id="nome" placeholder="Nome" autocomplete="off" value="<?php echo $nome; ?>" readonly="readonly" />
				</div></div></div>
				
			<div class="col-md-4">
	    <div class="form-group">
					<label for="int">Cognome <?php echo form_error('cognome') ?></label>
									<div class="input-group">
										<div class="input-group-addon"><i class="fa fa-text-height"></i></div>			
					<input type="text" class="form-control" maxlength='10' name="cognome" id="cognome" placeholder="Cognome" autocomplete="off" value="<?php echo $cognome; ?>" readonly="readonly"  />
				</div></div></div>

				
				<div class="col-md-4">
	    <div class="form-group">
					<label for="int">Fk Disciplina <?php echo form_error('fk_disciplina') ?></label>
									<div class="input-group">
										<div class="input-group-addon"><i class="fa fa-sort-numeric-asc"></i></div>			
					<input type="number" class="form-control" maxlength='10' name="fk_disciplina" id="fk_disciplina" placeholder="Fk Disciplina" autocomplete="off" value="<?php echo $fk_disciplina; ?>" />
				</div></div></div>
				
			<div class="col-md-4">
	    <div class="form-group">
					<label for="int">Disciplina <?php echo form_error('disciplina') ?></label>
									<div class="input-group">
										<div class="input-group-addon"><i class="fa fa-text-height"></i></div>			
					<input type="text" class="form-control" maxlength='10' name="disciplina" id="disciplina" placeholder="Disciplina" autocomplete="off" value="<?php echo $disciplina; ?>" readonly="readonly"  />
				</div></div></div>				
				
				
				<div class="col-md-4">
	    <div class="form-group">
					<label for="anno">Anno <?php echo form_error('anno') ?></label>
					<SELECT name='anno' id='anno' class="form-control">
<OPTION VALUE></OPTION>
<OPTION VALUE='2010'>2010</OPTION>
<OPTION VALUE='2011'>2011</OPTION>
<OPTION VALUE='2012'>2012</OPTION>
<OPTION VALUE='2013'>2013</OPTION>
<OPTION VALUE='2014'>2014</OPTION>
<OPTION VALUE='2015'>2015</OPTION>
<OPTION VALUE='2016'>2016</OPTION>
<OPTION VALUE='2017'>2017</OPTION>
<OPTION VALUE='2018'>2018</OPTION>
<OPTION VALUE='2019'>2019</OPTION>
<OPTION VALUE='2020'>2020</OPTION>
<OPTION VALUE='2021'>2021</OPTION>
<OPTION VALUE='2022'>2022</OPTION>
<OPTION VALUE='2023'>2023</OPTION>
<OPTION VALUE='2024'>2024</OPTION>
<OPTION VALUE='2025'>2025</OPTION>
<OPTION VALUE='2026'>2026</OPTION>
<OPTION VALUE='2027'>2027</OPTION>
<OPTION VALUE='2028'>2028</OPTION>
<OPTION VALUE='2029'>2029</OPTION>
<OPTION VALUE='2030'>2030</OPTION></SELECT>
				</div>
				<script>	
				$('[name=anno] option').filter(function() { 
					return ($(this).text() == '<?php echo $anno?>'); //To select Blue
				}).prop('selected', true);				
				</script>
			</div><div class="col-md-4">
	    <div class="form-group">
					<label for="mese">Mese <?php echo form_error('mese') ?></label>
					<SELECT name='mese' id='mese' class="form-control">
<OPTION VALUE></OPTION>
<OPTION VALUE='GENNAIO'>GENNAIO</OPTION>
<OPTION VALUE='FEBBRAIO'>FEBBRAIO</OPTION>
<OPTION VALUE='MARZO'>MARZO</OPTION>
<OPTION VALUE='APRILE'>APRILE</OPTION>
<OPTION VALUE='MAGGIO'>MAGGIO</OPTION>
<OPTION VALUE='GIUGNO'>GIUGNO</OPTION>
<OPTION VALUE='LUGLIO'>LUGLIO</OPTION>
<OPTION VALUE='AGOSTO'>AGOSTO</OPTION>
<OPTION VALUE='SETTEMBRE'>SETTEMBRE</OPTION>
<OPTION VALUE='OTTOBRE'>OTTOBRE</OPTION>
<OPTION VALUE='NOVEMBRE'>NOVEMBRE</OPTION>
<OPTION VALUE='DICEMBRE'>DICEMBRE</OPTION></SELECT>
				</div>
				<script>	
				$('[name=mese] option').filter(function() { 
					return ($(this).text() == '<?php echo $mese?>'); //To select Blue
				}).prop('selected', true);				
				</script>
			</div><div class="col-md-4">
	    <div class="form-group">
					<label for="varchar">Data <?php echo form_error('data') ?></label>
									<div class="input-group">
										<div class="input-group-addon"><i class="fa fa-calendar"></i></div>			
					<input type="text" class="form-control datepicker" maxlength='255' name="data" id="data" placeholder="Data" autocomplete="off" value="<?php echo $data; ?>" />
				</div></div></div><div class="col-md-4">
	    <div class="form-group">
					<label for="varchar">Importo <?php echo form_error('importo') ?></label>
									<div class="input-group">
										<div class="input-group-addon"><i class="fa fa-text-height"></i></div>			
					<input type="text" class="form-control" maxlength='255' name="importo" id="importo" placeholder="Importo" autocomplete="off" value="<?php echo $importo; ?>" />
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
		$ajaxAction = 'mod_ricevute/create_action';
	} else {
		$ajaxAction = 'mod_ricevute/update_action';

	}	
?>	
<script>
	$('.datetimepicker').datetimepicker({locale:'it', format: 'DD/MM/YYYY'}); 	
	$('.datepicker').datepicker({
		locale:'it',
		format: 'dd/mm/yyyy',
		changeMonth: true,
		changeYear: true,		
		numberOfMonths:2,
		todayHighlight: true,
		autoclose: true
	});


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
	