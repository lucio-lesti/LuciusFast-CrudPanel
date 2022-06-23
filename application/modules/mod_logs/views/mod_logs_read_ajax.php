   
	<!-- Content Header (Page header) -->
        <div class="col-md-4">
        <h3>
            <i class="fa fa-bug"></i> 
			<a>
			<u> Logs</u>
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
						<label for="varchar">Programma / Modulo <?php echo form_error('programma') ?></label>
										<div class="input-group">
											<div class="input-group-addon"><i class="fa fa-text-height"></i></div>			
						<input disabled='disabled' type="text" class="form-control" maxlength='100' name="programma" id="programma" placeholder="Programma / Modulo" autocomplete="off" value="<?php echo $programma; ?>" />
					</div></div></div><div class="col-md-4">
	    <div class="form-group">
						<label for="varchar">Utente <?php echo form_error('utente') ?></label>
										<div class="input-group">
											<div class="input-group-addon"><i class="fa fa-text-height"></i></div>			
						<input disabled='disabled' type="text" class="form-control" maxlength='100' name="utente" id="utente" placeholder="Utente" autocomplete="off" value="<?php echo $utente; ?>" />
					</div></div></div><div class="col-md-4">
	    <div class="form-group">
						<label for="varchar">Azione <?php echo form_error('azione') ?></label>
										<div class="input-group">
											<div class="input-group-addon"><i class="fa fa-text-height"></i></div>			
						<input disabled='disabled' type="text" class="form-control" maxlength='100' name="azione" id="azione" placeholder="Azione" autocomplete="off" value="<?php echo $azione; ?>" />
					</div></div></div><div class="col-md-4">
	    <div class="form-group">
						<label for="datetime">Data <?php echo form_error('data') ?></label>
										<div class="input-group">
											<div class="input-group-addon"><i class="fa fa-calendar"></i></div>			
						<input disabled='disabled' type="text" class="form-control datetimepicker" name="data" id="data" placeholder="Data" autocomplete="off" value="<?php echo $data; ?>" />
					</div></div></div>
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