<!-- INIZIO-->
			<div class="content-wrapper">
			  <!-- Content Header (Page header) -->
			  <section class="content-header">
				<h1>
				  <i class="fa fa-cubes"></i> Comuni 
				  
					<a class="btn btn-primary" href="<?php echo base_url(); ?>mod_comuni/create">
					  <i class="fa fa-plus"></i> Nuovo
					</a>	  
					
					<button class="btn btn-sm btn-danger deleteUser" disabled="disabled" id="btDeleteMass" name="btDeleteMass" 
						onclick='deleteMassiveEntry("entry_list", "check_id", "mod_comuni","deleteMassive")'>
					  <i class="fa fa-trash"></i> Cancellazione Massiva
					</button>			
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
					
				<table class="table table-bordered table-striped" id="mytable" style='width: 100%;'>			
					<thead>
					<tr>
						<th><input type="checkbox" id="check_master" name="check_master" onchange="selezionaDeselezionaTutti('check_master','check_id','btDeleteMass')" /></th>
		<th>Action</th>
					</tr></thead>
	    
				</table>
		<div class="box-footer">
		   <div class="row">
				<div class="col-md-6"></div>
											
			</div>		
		</div>		
		</div>		
		<!--FINE-->
		</section>
		</div>
		<!--FINE-->		
				<script type="text/javascript">
				
var columnArray = [];		
					
var filterArray = new Array(); 
					$(document).ready(function() {
						
var columnNrAction = 0;
						
						
						$.fn.dataTableExt.oApi.fnPagingInfo = function(oSettings)
						{
							return {
								"iStart": oSettings._iDisplayStart,
								"iEnd": oSettings.fnDisplayEnd(),
								"iLength": oSettings._iDisplayLength,
								"iTotal": oSettings.fnRecordsTotal(),
								"iFilteredTotal": oSettings.fnRecordsDisplay(),
								"iPage": Math.ceil(oSettings._iDisplayStart / oSettings._iDisplayLength),
								"iTotalPages": Math.ceil(oSettings.fnRecordsDisplay() / oSettings._iDisplayLength)
							};
						};

						$('#mytable thead tr').clone(true).appendTo( '#mytable thead' );
						$('#mytable thead tr:eq(1) th').each( function (i) {
							columnNrAction++;
						});					
						
						
						$('#mytable thead tr:eq(1) th').each( function (i) {
							if(i > 0){
								if(i < (columnNrAction - 1)){
									var title = $(this).text();
									switch(columnArray[i].type){
										case 'select':
											var strSelect = '';
											strSelect = '<SELECT class=\'mysearch_filter\' id="' + columnArray[i].name + '" name="' + columnArray[i].name + '" type_field ="' + columnArray[i].type + '" >';
											strSelect += '<OPTION VALUE="">Cerca...</OPTION>';
											strSelect += '<OPTION VALUE="SI">SI</OPTION>';
											strSelect += '<OPTION VALUE="NO">NO</OPTION>';
											strSelect += '</SELECT>';
											
											$(this).html( strSelect );										
										break;

										
										case 'text':
											$(this).html( '<input type="text" placeholder="Cerca..." class=\'mysearch_filter\' id="' + columnArray[i].name + '" name="' + columnArray[i].name + '" / style="width:80%"  style="border-radius:3px" autocomplete="off" type_field ="' + columnArray[i].type + '" >' );										
										break;
		 
										case 'date':
											$(this).html( '<input type="text" placeholder="Cerca..." class=\'mysearch_filter datetimepicker\' id="' + columnArray[i].name + '" name="' + columnArray[i].name + '"  style="width:80%" autocomplete="off"  type_field ="' + columnArray[i].type + '" />' );										
										break;
		 
										case 'datetime':
											$(this).html( '<input type="text" placeholder="Cerca..." class=\'mysearch_filter datetimepicker\' id="' + columnArray[i].name + '" name="' + columnArray[i].name + '"style="width:80%" autocomplete="off"  type_field ="' + columnArray[i].type + '" />' );			
										break;	

										case 'number':
											$(this).html( '<input type="number" placeholder="Cerca..." class=\'mysearch_filter\' id="' + columnArray[i].name + '" name="' + columnArray[i].name + '" style="width:80%" autocomplete="off"  type_field ="' + columnArray[i].type + '" />' );		
										break;		

										case 'blob':
											$(this).html( '' );		
										break;									
									}
							
								} else {
									$(this).html( '' );		
								}

							}

						});	

						
						var t = $("#mytable").dataTable({
							initComplete: function() {
								var api = this.api();
								$('#mytable_filter input')
									.off('.DT')
									.on('keyup.DT', function(e) {
										if (e.keyCode == 13) {
											api.search(this.value).draw();
									}
								});
								
								$('select')
									.off('.DT')
									.on('change', function(e) {
										var searchFilter = document.getElementsByClassName("mysearch_filter");
										for(var i=0; i< searchFilter.length; i++){
											var element = document.getElementById(searchFilter[i].id);
											var typeField = 'text';
											if(element.classList.contains('datepicker') == true) {
												typeField = 'date'	
											}
											if(element.classList.contains('datetimepicker') == true) {
												typeField = 'datetime'	
											}									
											filterArray[i] = {'field':searchFilter[i].name,'value':searchFilter[i].value,'type_field':typeField};
										}
										api.search(this.value).draw();
									});							
								
								$('.mysearch_filter')
									.off('.DT')
									.on('keydown.DT', function(e) {
										var searchFilter = document.getElementsByClassName("mysearch_filter");
										for(var i=0; i< searchFilter.length; i++){
											var element = document.getElementById(searchFilter[i].id);
											var typeField = 'text';
											if(element.classList.contains('datepicker') == true) {
												typeField = 'date'	
											}
											if(element.classList.contains('datetimepicker') == true) {
												typeField = 'datetime'	
											}									
											filterArray[i] = {'field':searchFilter[i].name,'value':searchFilter[i].value,'type_field':typeField};
										}
										if (e.keyCode == 13) {
											api.search(this.value).draw();
										}
									}
								);				

							},
							oLanguage: {
								sSearch: "Ricerca su tutte le colonne:",	
								sProcessing: "Caricamento... <IMG SRC='<?php echo base_url(); ?>assets/images/loading3.gif' />"
							},
							processing: true,
							"lengthChange": false,
							serverSide: true,
							ajax: {"url": "mod_comuni/json", 
									"type": "POST",
								"data": function(data){	
									data.searchFilter = filterArray;
								}					
							},
							columns: [
								{
									"data": "ids",
									"orderable": false
								},,
								{
									"data" : "action",
									"orderable": false,
									"className" : "text-center"
								}
							],
							order: [[1, 'desc']],
							rowCallback: function(row, data, iDisplayIndex) {
								var info = this.fnPagingInfo();
								var page = info.iPage;
								var length = info.iLength;
								var index = page * length + (iDisplayIndex + 1);
							   //$('td:eq(0)', row).html(index);
							},
							orderCellsTop: true,
							fixedHeader: false						
						});
					});
				</script>