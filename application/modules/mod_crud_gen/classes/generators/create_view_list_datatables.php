<?php 


class create_view_list_datatables extends AbstractGenerator{
	
	public static function output($param_gen){
		
		$helper = new Helper();
		foreach($param_gen as $key => $value){
			${$key} = $value;
		}			
		$form_columns_nr_layout = $form_columns_nr_layout;
		$grid_custom_settings = array();
		if($sel_mod_gerarchia == 'figlio'){
			$parent_id = $mod_gen_aggr ;	
		} else {
			$parent_id = 0;
		}


		$mod_icon = $mod_icon;
		$string = '<!-- INIZIO-->
			<div class="content-wrapper">
			  <!-- Content Header (Page header) -->
			  <section class="content-header">
				<h1>
				  <i class="fa '.$mod_icon .'"></i> '.$mod_title.' 
				  
					<a class="btn btn-primary" href="<?php echo base_url(); ?>'.$c_url.'/create">
					  <i class="fa fa-plus"></i> Nuovo
					</a>	  
					
					<button class="btn btn-sm btn-danger deleteUser" disabled="disabled" id="btDeleteMass" name="btDeleteMass" 
						onclick=\'deleteMassiveEntry("entry_list", "check_id", "'.$c_url.'","deleteMassive")\'>
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
			<!--DIV BOX-->';

		$string .= "
					<?php
						\$this->load->helper('form');
						\$error = \$this->session->flashdata('error');
						if(\$error)
						{
					?>
					<div class=\"alert alert-danger alert-dismissable\">
					  <button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-hidden=\"true\">×</button>
					  <?php echo \$this->session->flashdata('error'); ?>
					</div>
					<?php } ?>
					<?php  
						\$success = \$this->session->flashdata('success');
						if(\$success){
					?>
					<div class=\"alert alert-success alert-dismissable\">
					  <button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-hidden=\"true\">×</button>
					  <?php echo \$this->session->flashdata('success'); ?>
					</div>
					<?php } ?>
					
				<table class=\"table table-bordered table-striped\" id=\"mytable\" style='width: 100%;'>			
					<thead>
					<tr>
						<th><input type=\"checkbox\" id=\"check_master\" name=\"check_master\" onchange=\"selezionaDeselezionaTutti('check_master','check_id','btDeleteMass')\" /></th>";

		$nrRow = 0;	
		$grid_custom_settings[$nrRow]['field_name'] = $pk; 
		//$grid_custom_settings[$nrRow]['label'] = $etichettaLabel;	
		$grid_custom_settings[$nrRow]['visible'] = 'TRUE';		
		$grid_custom_settings[$nrRow]['cell_type'] = 'checkbox';	
					
		foreach ($non_pk as $row) {

			$nrRow++;

			//MOSTRO LA COLONNA SOLO SE SELEZIONATA
			if(in_array($row['COLUMN_NAME'],$COLUMN_NAME)){		
				//SETTO LA LABEL
				if($row["COLUMN_COMMENT"] != ""){
					$etichettaLabel = $row["COLUMN_COMMENT"];
				} else {
					$etichettaLabel = $row["COLUMN_NAME"];
				}	
				
				$grid_custom_settings[$nrRow]['COLUMN_NAME'] = $row["COLUMN_NAME"]; 
				$grid_custom_settings[$nrRow]['label'] = $etichettaLabel;	
				$grid_custom_settings[$nrRow]['visible'] = 'TRUE';		
				$grid_custom_settings[$nrRow]['cell_type'] = 'cell';	
				
				$string .= "\n\t\t<th>" . $helper->label($etichettaLabel) . "</th>";
			}
			
		}
		$string .= "\n\t\t<th>Action</th>
					</tr></thead>";

		$column_non_pk = array();
		foreach ($non_pk as $row) {
			//MOSTRO LA COLONNA SOLO SE SELEZIONATA
			if(in_array($row['COLUMN_NAME'],$COLUMN_NAME)){		
				$column_non_pk[] .= "{\"data\": \"".$row['COLUMN_NAME']."\"}";
			}
		}
		$col_non_pk = implode(',', $column_non_pk);

		$string .= "\n\t    
				</table>";
					


		$string .= "
		<div class=\"box-footer\">
		   <div class=\"row\">
				<div class=\"col-md-6\">";
		if ($export_excel == '1') {
			$string .= "\n\t\t<?php echo anchor(site_url('".$c_url."/excel'), 'Esporta Tutto in  Excel', 'class=\"btn btn-primary\"'); ?>";
			$string .= "<br>(*)<b>Note di esportazione:</b> L'esportazione E' TOTALE e riguarda anche i records non selezionati</u>
			<br>(**)<b>Note di importazione:</b> Prima di re-importare il file Excel <u>ELIMINARE LA PRIMA COLONNA</u>";
		}
		if ($export_word == '1') {
			$string .= "\n\t\t<?php echo anchor(site_url('".$c_url."/word'), 'Word', 'class=\"btn btn-primary\"'); ?>";
		}
		if ($export_pdf == '1') {
			$string .= "\n\t\t<?php echo anchor(site_url('".$c_url."/pdf'), 'PDF', 'class=\"btn btn-primary\"'); ?>";
		}
		$string .= "</div>
											
			</div>		
		</div>";	
				
		$string .= "		
		</div>		
		<!--FINE-->
		</section>
		</div>
		<!--FINE-->";			

		$string .= "		
				<script type=\"text/javascript\">
				\nvar columnArray = [];";
				
		/*
		print'<pre>';print_r($all);print'</pre>';
		print'<pre>';print_r($COLUMN_NAME);print'</pre>';
		die();		
		*/
				
		$colNrJsArray = 1;		
		foreach ($all as $row) {
		 
			//MOSTRO LA COLONNA SOLO SE SELEZIONATA
			if(in_array($row['COLUMN_NAME'],$COLUMN_NAME)){		
				switch($row["data_type"]){
					case 'blob':
					case 'tinyblob':
					case 'mediumblob':
					case 'longblob':
						$string .= "\ncolumnArray[".$colNrJsArray."] = {type:\"blob\", name:\"".$row['COLUMN_NAME']."\"};	";	
					break;
					
					case 'enum':
					case 'set':
						$string .= "\ncolumnArray[".$colNrJsArray."] = {type:\"select\", name:\"".$row['COLUMN_NAME']."\"};	";			
					break;
					
					case 'tinytext':
					case 'mediumtext':
					case 'longtext':
					case 'text':
					case 'char':
					case 'varchar':
						$string .= "\ncolumnArray[".$colNrJsArray."] = {type:\"text\", name:\"".$row['COLUMN_NAME']."\"};	";
					break;
					
					
					case 'tinyint':
					case 'smallint':
					case 'mediumint':
					case 'bigint':
					case 'int':
					case 'decimal':
					case 'float':
					case 'double':
					case 'real':
					case 'serial':
						$string .= "\ncolumnArray[".$colNrJsArray."] = {type:\"number\", name:\"".$row['COLUMN_NAME']."\"};	";
					break;
					
					
					case 'date':
						$string .= "\ncolumnArray[".$colNrJsArray."] = {type:\"date\", name:\"".$row['COLUMN_NAME']."\"};	";		
					break;
					
					case 'datetime':
					case 'timestamp':
					case 'time':
						$string .= "\ncolumnArray[".$colNrJsArray."] = {type:\"datetime\", name:\"".$row['COLUMN_NAME']."\"};	";
					break;
					
					
					default:
						$string .= "columnArray[".$colNrJsArray."] = {type:\"text\", name:\"".$row['COLUMN_NAME']."\"};	";
					break;
				}
				
				$colNrJsArray++;
			}	
			
		}		

		$string .="		
					\nvar filterArray = new Array(); 
					$(document).ready(function() {
						\nvar columnNrAction = 0;
						
						
						$.fn.dataTableExt.oApi.fnPagingInfo = function(oSettings)
						{
							return {
								\"iStart\": oSettings._iDisplayStart,
								\"iEnd\": oSettings.fnDisplayEnd(),
								\"iLength\": oSettings._iDisplayLength,
								\"iTotal\": oSettings.fnRecordsTotal(),
								\"iFilteredTotal\": oSettings.fnRecordsDisplay(),
								\"iPage\": Math.ceil(oSettings._iDisplayStart / oSettings._iDisplayLength),
								\"iTotalPages\": Math.ceil(oSettings.fnRecordsDisplay() / oSettings._iDisplayLength)
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
											strSelect = '<SELECT class=\'mysearch_filter\' id=\"' + columnArray[i].name + '\" name=\"' + columnArray[i].name + '\" type_field =\"' + columnArray[i].type + '\" >';
											strSelect += '<OPTION VALUE=\"\">Cerca...</OPTION>';
											strSelect += '<OPTION VALUE=\"SI\">SI</OPTION>';
											strSelect += '<OPTION VALUE=\"NO\">NO</OPTION>';
											strSelect += '</SELECT>';
											
											$(this).html( strSelect );										
										break;

										
										case 'text':
											$(this).html( '<input type=\"text\" placeholder=\"Cerca...\" class=\'mysearch_filter\' id=\"' + columnArray[i].name + '\" name=\"' + columnArray[i].name + '\" / style=\"width:80%\"  style=\"border-radius:3px\" autocomplete=\"off\" type_field =\"' + columnArray[i].type + '\" >' );										
										break;
		 
										case 'date':
											$(this).html( '<input type=\"text\" placeholder=\"Cerca...\" class=\'mysearch_filter datetimepicker\' id=\"' + columnArray[i].name + '\" name=\"' + columnArray[i].name + '\"  style=\"width:80%\" autocomplete=\"off\"  type_field =\"' + columnArray[i].type + '\" />' );										
										break;
		 
										case 'datetime':
											$(this).html( '<input type=\"text\" placeholder=\"Cerca...\" class=\'mysearch_filter datetimepicker\' id=\"' + columnArray[i].name + '\" name=\"' + columnArray[i].name + '\"style=\"width:80%\" autocomplete=\"off\"  type_field =\"' + columnArray[i].type + '\" />' );			
										break;	

										case 'number':
											$(this).html( '<input type=\"number\" placeholder=\"Cerca...\" class=\'mysearch_filter\' id=\"' + columnArray[i].name + '\" name=\"' + columnArray[i].name + '\" style=\"width:80%\" autocomplete=\"off\"  type_field =\"' + columnArray[i].type + '\" />' );		
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

						
						var t = $(\"#mytable\").dataTable({
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
										var searchFilter = document.getElementsByClassName(\"mysearch_filter\");
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
										var searchFilter = document.getElementsByClassName(\"mysearch_filter\");
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
								sSearch: \"Ricerca su tutte le colonne:\",	
								sProcessing: \"Caricamento... <IMG SRC='<?php echo base_url(); ?>assets/images/loading3.gif' />\"
							},
							processing: true,
							\"lengthChange\": false,
							serverSide: true,
							ajax: {\"url\": \"".$c_url."/json\", 
									\"type\": \"POST\",
								\"data\": function(data){	
									data.searchFilter = filterArray;
								}					
							},
							columns: [
								{
									\"data\": \"ids\",
									\"orderable\": false
								},".$col_non_pk.",
								{
									\"data\" : \"action\",
									\"orderable\": false,
									\"className\" : \"text-center\"
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
				</script>";		
	
		return $string;
	}

}	


		
?>