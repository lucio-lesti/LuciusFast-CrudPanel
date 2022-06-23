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
				  <i class="fa fa-cubes"></i> Comuni		  
			<?php 
				if($perm_write == 'Y'){
				?>				  
			  <a class="btn btn-primary" onclick="createAjax('mod_comuni')">
				<i class="fa fa-plus"></i> Nuovo
			  </a>	  
			  <?php 
			  }
			  ?>	
					<?php 
					if(($perm_delete == 'Y') && ($isAdmin == TRUE) && ($mod_type == 'crud')){
					?>										
					<button class="btn btn-sm btn-danger deleteUser" disabled="disabled" id="btDeleteMass" name="btDeleteMass" 
						onclick='deleteMassiveEntry("entry_list", "check_id", "mod_comuni","deleteMassive")'>
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
							<tr><th><input type="checkbox" id="check_master" name="check_master" onchange="selezionaDeselezionaTutti('check_master','check_id','btDeleteMass')" /></th>
		<th>Comune</th>
		<th>CAP</th>
		<th>Prefisso</th>
		<th>ID Comune</th>
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
				
objAjaxConfig.mod_name = "mod_comuni";
				
objAjaxConfig.mod_title = "Comuni";				
				
var columnArray = [];
columnArray[1] = {type:"text", name:"mod_comuni_comune"};	
columnArray[2] = {type:"text", name:"mod_comuni_cap"};	
columnArray[3] = {type:"text", name:"mod_comuni_prefisso"};	
columnArray[4] = {type:"text", name:"id"};	
var columnGrid = [
{"data": "ids","orderable": false},
{"data": "mod_comuni_comune"},
{"data": "mod_comuni_cap"},
{"data": "mod_comuni_prefisso"},
{"data": "id"},
{"data": "action","orderable": false,"className": "text-center"}
];
<?php
if(isset($_REQUEST['id'])){
	echo "editAjax('mod_comuni',".$_REQUEST['id'].");";
}
?>
</script>
<script src='<?php echo base_url(); ?>assets/js/datatable_ajax.config.js'></script>