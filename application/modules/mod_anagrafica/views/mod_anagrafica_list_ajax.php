<!-- INIZIO-->
			<div id="top_form"></div>
			<div class="content-wrapper">
			  <!-- Content Header (Page header) -->
			  <section class="content-header">
				<?php 
					$countNotify = 0;
					foreach($notifyList as $k => $v){
						if($v['mod_name'] == $module){
							$countNotify++;
						}
					}
				?>
				<?php
					if($countNotify > 0){
				?>
				<div id="divNotifyMsg" style="display: block; font-size: 18px;" class="alert alert-warning alert-dismissable" 
					onclick="hideMsg('divNotifyMsg')">ATTENZIONE, CI SONO NOTIFICHE PER QUESTO MODULO
				</div>
				<?php } ?>

				<h1>
				  <i class="fa fa-cubes"></i> Anagrafica 
				  <?php 
				  	if($perm_write == 'Y'){
			  		?>				  
					<a class="btn btn-primary" onclick="createAjax('mod_anagrafica')">
					  <i class="fa fa-plus"></i> Nuovo
					</a>	  
					<?php 
                	}
            		?>	


					<?php 
					if($perm_delete == 'Y'){
					?>										
					<button class="btn btn-sm btn-danger deleteUser" disabled="disabled" id="btDeleteMass" name="btDeleteMass" 
						onclick='deleteMassiveEntry("entry_list", "check_id", "mod_anagrafica","deleteMassive")'>
					  <i class="fa fa-trash"></i> Cancellazione Massiva
					</button>	
					<?php 
                	}
            		?>						
					
				</h1>
				
			  </section>
			  <section class="content">
			<!-- INIZIO-->

			 
			<!--DIV BOX-->
			<div class="box">
					<div class="box-header">
					  <div class="box-tools">
					  </div>
					</div>
			<!--DIV BOX-->
					<?php
						$this->load->helper('form');
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
						if($success){
					?>
					<div class="alert alert-success alert-dismissable">
					  <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
					  <?php echo $this->session->flashdata('success'); ?>
					</div>
					<?php } ?>			
					<div class="nav-tabs-custom">
						<ul class="nav nav-tabs" id="tab-menu">
						  <li class="active"><a href='#elenco' id='href_elenco' data-toggle="tab" aria-expanded="true"></i><i class="fa fa-list-ol"></i> Elenco  <i class='fa fa-cubes'></i></a></li>
						</ul>  
						
						<div class="tab-content">
							<div class="tab-pane tab-margin active" id="elenco">					
								<table class="table table-bordered table-striped" id="mytable" style='width: 100%;'>			
									<thead>
									<tr>
										<th><input type="checkbox" id="check_master" name="check_master" onchange="selezionaDeselezionaTutti('check_master','check_id','btDeleteMass')" /></th>
		<th>Nome</th>
		<th>Cognome</th>
		<th>Data di Nascita</th>
		<th>Comune Nascita</th>
		<th>Indirizzo</th>
		<th>Cellulare</th>
		<th>Tutore</th>
		<th>Foto</th>
		<th></th>
					</tr></thead>
	    
				</table>
	    		
							   <div class="row">
									<div class="col-md-6">
	 							
									</div>
																
								</div>		
							</div>
						</div>	
					</div>		
		</div>		
		<!--FINE-->
		</section>
		</div>
		<!--FINE-->		
				<script type="text/javascript">
				
objAjaxConfig.mod_name = "mod_anagrafica";
				
objAjaxConfig.mod_title = "Anagrafica";				
				
var columnArray = [];
columnArray[1] = {type:"text", name:"mod_anagrafica_nome"};	
columnArray[2] = {type:"text", name:"mod_anagrafica_cognome"};
columnArray[3] = {type:"date", name:"mod_anagrafica_datanascita"};		
columnArray[4] = {type:"text", name:"mod_comuni_comune"};	
columnArray[5] = {type:"text", name:"mod_anagrafica_indirizzo"};	
columnArray[6] = {type:"text", name:"mod_anagrafica_cellulare"};	
columnArray[7] = {type:"text", name:"grd_tutore"};	
columnArray[8] = {type:"blob", name:"mod_anagrafica_img_foto"};	
var columnGrid = [
{"data": "ids","orderable": false},
{"data": "mod_anagrafica_nome"},
{"data": "mod_anagrafica_cognome"},
{"data": "mod_anagrafica_datanascita"},
{"data": "mod_comuni_comune"},
{"data": "mod_anagrafica_indirizzo"},
{"data": "mod_anagrafica_cellulare"},
{"data": "grd_tutore"},
{"data": "mod_anagrafica_img_foto"},
{"data": "action","orderable": false,"className": "text-center"}
];
<?php
if(isset($_REQUEST['id'])){
	echo "\neditAjax('mod_anagrafica',".$_REQUEST['id'].");";
}
if(isset($_REQUEST['add_new'])){
	echo "\ntabID = 1;";
	echo "\ncreateAjax('mod_anagrafica');";
}
?>
</script>
<script src='<?php echo base_url(); ?>assets/js/datatable_ajax.config.js'></script>