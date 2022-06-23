<!-- INIZIO-->
	<div id="top_form"></div>
	<div class="content-wrapper">
	  <!-- Content Header (Page header) -->
	  <section class="content-header">
		<h1>
		  <i class="fa fa-bug"></i> Logs 
		  		
		  <?php 
                if($perm_delete == 'Y'){
            ?>         
            <button class="btn btn-sm btn-danger deleteUser" disabled="disabled" id="btDeleteMass" name="btDeleteMass"
                onclick='deleteMassiveEntry("entry_list", "check_id", "<?php echo $module;?>","deleteMassive")'>
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
				  <li class="active"><a href='#elenco' id='href_elenco' data-toggle="tab" aria-expanded="true"></i><i class="fa fa-list-ol"></i> Elenco  <i class='fa fa-bug'></i></a></li>
				</ul>  
				
				<div class="tab-content">
					<div class="tab-pane tab-margin active" id="elenco">					
						<table class="table table-bordered table-striped" id="mytable" style='width: 100%;'>			
							<thead>
							<tr>
								<th><input type="checkbox" id="check_master" name="check_master" onchange="selezionaDeselezionaTutti('check_master','check_id','btDeleteMass')" /></th>
		<th>Programma / Modulo</th>
		<th>Utente</th>
		<th>Azione</th>
		<th>Data</th>
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
objAjaxConfig.mod_name = "mod_logs";
objAjaxConfig.mod_title = "Logs";
objAjaxConfig.datatable.ordering = false;

var columnArray = [];
columnArray[1] = {type:"text", name:"programma"};	
columnArray[2] = {type:"text", name:"utente"};	
columnArray[3] = {type:"text", name:"azione"};	
columnArray[4] = {type:"datetime", name:"data"};

var columnGrid = [ 
    {"data": "ids","orderable": false}, 
    {"data": "programma"}, 
    {"data": "utente"}, 
	{"data": "azione"}, 
    {"data": "data"}, 
    {"data": "action","orderable": false,"className": "text-center"}
];
</script>
<script src='<?php echo base_url(); ?>assets/js/datatable_ajax.config.js'></script>