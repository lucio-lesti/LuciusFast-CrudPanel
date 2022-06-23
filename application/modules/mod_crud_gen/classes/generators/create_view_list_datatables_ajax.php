<?php 

class create_view_list_datatables_ajax extends AbstractGenerator{
	
	public static function output($param_gen){
		
		$helper = new Helper();
		foreach($param_gen as $key => $value){
			${$key} = $value;
		}			
		$form_columns_nr_layout = $form_columns_nr_layout;
		if($sel_mod_gerarchia == 'figlio'){
			$parent_id = $mod_gen_aggr ;	
		} else {
			$parent_id = 0;
		}

		$mod_icon = $mod_icon;
		$string = '<!-- INIZIO-->
			<div id="top_form"></div>
			<div class="content-wrapper">
			  <!-- Content Header (Page header) -->
			  <section class="content-header">
				<?php 
					$countNotify = 0;
					foreach($notifyList as $k => $v){
						if($v[\'mod_name\'] == $module){
							$countNotify++;
						}
					}
				?>
				<?php
					if($countNotify > 0){
				?>
				<div id="divNotifyMsg" style="display: block; font-size: 18px;" class="alert alert-warning alert-dismissable" 
					onclick="hideMsg(\'divNotifyMsg\')">ATTENZIONE, CI SONO NOTIFICHE PER QUESTO MODULO
				</div>
				<?php } ?>

				<h1>
				  <i class="fa '.$mod_icon .'"></i> '.$mod_title;
		if($mod_type == 'crud'){
			$string.='		  
			<?php 
				if($perm_write == \'Y\'){
				?>				  
			  <a class="btn btn-primary" onclick="createAjax(\''.$c_url.'\')">
				<i class="fa fa-plus"></i> Nuovo
			  </a>	  
			  <?php 
			  }
			  ?>';
		}
	

		$string.='	
					<?php 
					if(($perm_delete == \'Y\') && ($isAdmin == TRUE) && ($mod_type == \'crud\')){
					?>										
					<button class="btn btn-sm btn-danger deleteUser" disabled="disabled" id="btDeleteMass" name="btDeleteMass" 
						onclick=\'deleteMassiveEntry("entry_list", "check_id", "'.$c_url.'","deleteMassive")\'>
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
					<?php } ?>";
			$string .= "			
			<div class=\"nav-tabs-custom\">
				<ul class=\"nav nav-tabs\" id=\"tab-menu\">
				  <li class=\"active\"><a href='#elenco' id='href_elenco' data-toggle=\"tab\" aria-expanded=\"true\"></i><i class=\"fa fa-list-ol\"></i> Elenco  <i class='fa ".$mod_icon ."'></i></a></li>
				</ul>  
				<?php 

					if (isset(\$comboGridFilter)) {
						foreach (\$comboGridFilter as \$filterName => \$v) {
							echo \"<b>\" . \$v['label'] . \"</b> 
									<SELECT id='\" . \$filterName .\"' name='\" . \$filterName . \"'  \";
							if (\$v['multiselect'] == TRUE) {
								echo \" multiple \";
							}
					
							if (\$v['bootstrapSelect'] == TRUE) {
								echo \" class = 'mysearch_filter_combo_ajax' data-live-search='true' \";
							} else {
								echo \" class = 'mysearch_filter' \";
							}
					
							if (isset(\$v['filterSlave'])) {
								echo ' onchange= \"filter_slave_population_function('.\$module.', '.\$v['filterSlave']['filter_slave_population_function'].',
								this.value,'.\$v['filterSlave']['filter_slave_id'].',true)\" ';								
							}
					
							echo \"	style='padding:6px 12px;font-size: 14px;'>\";
							echo \"<OPTION value=''>Seleziona...</OPTION>\";
							foreach (\$v['itemsList'] as \$kOpt => \$vOpt) {
								echo \"<OPTION value='\" . \$vOpt['id'] . \"'>\" . \$vOpt['nome'] . \"</OPTION>\";
							}
							echo \"</SELECT> \";
						}
					}
										
			    ?>				
				<div class=\"tab-content\">
					<div class=\"tab-pane tab-margin active\" id=\"elenco\">					
						<table class=\"table table-bordered table-striped\" id=\"mytable\" style='width: 100%;'>			
							<thead>
							<tr>";
		//if($mod_type == "crud"){							
			$string .= "<th><input type=\"checkbox\" id=\"check_master\" name=\"check_master\" onchange=\"selezionaDeselezionaTutti('check_master','check_id','btDeleteMass')\" /></th>";
		//}

		$nrRow = 0;		
					
		$arrayOrd = array();
		foreach ($column_name as $keyReq =>$rowReq) {
			foreach ($non_pk as $row) {
				if($row['COLUMN_NAME'] == $rowReq){
					$arrayOrd[] = $row;
				}
			}
		}				
					
					
		//print'<pre>';print_r($arrayOrd);print'</pre>';	die();		
					
		foreach ($arrayOrd as $row) {

			$nrRow++;
				
			//SETTO LA LABEL
			if($row["COLUMN_COMMENT"] != ""){
				$etichettaLabel = $row["COLUMN_COMMENT"];
			} else {
				$etichettaLabel = $row["COLUMN_NAME"];
			}		
			
			$string .= "\n\t\t<th>".$helper->label($etichettaLabel) . "</th>";
			
		}
		$string .= "\n\t\t<th></th>
					</tr></thead>";

					
		$column_non_pk = array();
		foreach ($arrayOrd as $row) {
			//print'<pre>';print_r($row);
			switch($row['DATA_TYPE'] ){
				case 'decimal':
				case 'float':
				case 'double':
				case 'real':
				case 'serial':
					$column_non_pk[] .= "{\"data\": \"".$row['TABLE_NAME']."_".$row['COLUMN_NAME']."\", render: $.fn.dataTable.render.number( '.', ',', 2 , '€' )}";		
				break;

				default:
					$column_non_pk[] .= "{\"data\": \"".$row['TABLE_NAME']."_".$row['COLUMN_NAME']."\"}";		
				break;	
			} 
		}


		$col_non_pk = implode(',', $column_non_pk);

		$string .= "\n\t    
				</table>";

		$string .= "\n\t    		
							   <div class=\"row\">
									<div class=\"col-md-6\">";
		if ($export_excel == '1') {
			$string .= "\n\t\t<?php echo anchor(site_url('".$c_url."/excel/$model'), 'Esporta Tutto in  Excel', 'class=\"btn btn-primary\"'); ?>";
			$string .= "<br>(*)<b>Note di esportazione:</b> L'esportazione E' TOTALE e riguarda anche i records non selezionati</u>
			<br>(**)<b>Note di importazione:</b> Prima di re-importare il file Excel <u>ELIMINARE LA PRIMA COLONNA</u>";
		}
		if ($export_word == '1') {
			$string .= "\n\t\t<?php echo anchor(site_url('".$c_url."/word'), 'Word', 'class=\"btn btn-primary\"'); ?>";
		}
		if ($export_pdf == '1') {
			$string .= "\n\t\t<?php echo anchor(site_url('".$c_url."/pdf'), 'PDF', 'class=\"btn btn-primary\"'); ?>";
		}

		$string .= "\n\t 							
									</div>
																
								</div>		
							</div>
						</div>	
					</div>";	
				
		$string .= "		
		</div>		
		<!--FINE-->
		</section>
		</div>
		<!--FINE-->";		

		$string .= "
		<script>
		<?php
			echo \"var request_js_id = '';\";
			if(isset(\$_REQUEST['id'])){
				echo \"request_js_id = '\".\$_REQUEST['id'].\"';\";
			} 
		?>
		</script>";
 
		$string .= "<?php echo \$this->load->view(\"jsconfig/$c_url"."_datatable_config.js.php\",\"\",true);?>";

		$string .="\n<script src='<?php echo base_url(); ?>assets/js/datatable_ajax.config.js'></script>";
		return $string;
	}

}	


?>