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
				  <i class="fa fa-cubes"></i> Scadenze Notifiche		  
			<?php 
				if($perm_write == 'Y'){
				?>				  
			  <a class="btn btn-primary" onclick="createAjax('mod_scadenze_notifiche')">
				<i class="fa fa-plus"></i> Nuovo
			  </a>	  
			  <?php 
			  }
			  ?>	
					<?php 
					if(($perm_delete == 'Y') && ($isAdmin == TRUE) && ($mod_type == 'crud')){
					?>										
					<button class="btn btn-sm btn-danger deleteUser" disabled="disabled" id="btDeleteMass" name="btDeleteMass" 
						onclick='deleteMassiveEntry("entry_list", "check_id", "mod_scadenze_notifiche","deleteMassive")'>
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
				<?php 

					if (isset($comboGridFilter)) {
						foreach ($comboGridFilter as $filterName => $v) {
							echo "<b>" . $v['label'] . "</b> 
									<SELECT id='" . $filterName ."' name='" . $filterName . "'  ";
							if ($v['multiselect'] == TRUE) {
								echo " multiple ";
							}
					
							if ($v['bootstrapSelect'] == TRUE) {
								echo " class = 'mysearch_filter_combo_ajax' data-live-search='true' ";
							} else {
								echo " class = 'mysearch_filter' ";
							}
					
							if (isset($v['filterSlave'])) {
								echo ' onchange= "filter_slave_population_function('.$module.', '.$v['filterSlave']['filter_slave_population_function'].',
								this.value,'.$v['filterSlave']['filter_slave_id'].',true)" ';								
							}
					
							echo "	style='padding:6px 12px;font-size: 14px;'>";
							echo "<OPTION value=''>Seleziona...</OPTION>";
							foreach ($v['itemsList'] as $kOpt => $vOpt) {
								echo "<OPTION value='" . $vOpt['id'] . "'>" . $vOpt['nome'] . "</OPTION>";
							}
							echo "</SELECT> ";
						}
					}
										
			    ?>				
				<div class="tab-content">
					<div class="tab-pane tab-margin active" id="elenco">					
						<table class="table table-bordered table-striped" id="mytable" style='width: 100%;'>			
							<thead>
							<tr><th><input type="checkbox" id="check_master" name="check_master" onchange="selezionaDeselezionaTutti('check_master','check_id','btDeleteMass')" /></th>
		<th>Campo Data Scadenza</th>
		<th>Icona Notifica</th>
		<th>Mod Name</th>
		<th>Msg Notifica</th>
		<th>Nr Giorni Data Notifica</th>
		<th>Sql Command</th>
		<th>Table Name</th>
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
		<script>
		<?php
			echo "var request_js_id = '';";
			if(isset($_REQUEST['id'])){
				echo "request_js_id = '".$_REQUEST['id']."';";
			} 
		?>
		</script><?php echo $this->load->view("jsconfig/mod_scadenze_notifiche_datatable_config.js.php","",true);?>
<script src='<?php echo base_url(); ?>assets/js/datatable_ajax.config.js'></script>