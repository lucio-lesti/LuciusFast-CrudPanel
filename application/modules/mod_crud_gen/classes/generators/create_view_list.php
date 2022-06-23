<?php  
 

class create_view_list extends AbstractGenerator{
	
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

		$general_settings = array('mod_title' => $mod_title,
							'mod_type'  => $mod_type,
							'form_columns_nr_layout' => $form_columns_nr_layout, 
							'parent_id' => $parent_id,
							'jenis_tabel' => $jenis_tabel,
							'export_excel' => $export_excel
							);
		$json_general_settings = json_encode($general_settings);
		if($mod_title != ''){
			$mod_title = $mod_title;
		} else {
			$mod_title = $c_url;
		}

		$grid_custom_settings_detail = array();
		$grid_custom_settings_detail = array();
		if(isset($enable_master_detail)){
			foreach($column_name_detail as $key => $value){
				$grid_custom_settings_detail[]['field_name'] = $value; 
				$grid_custom_settings_detail[]['label'] = $value;	
				$grid_custom_settings_detail[]['visible'] = 'TRUE';		
				$grid_custom_settings_detail[]['cell_type'] = 'cell';	
			}
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
					
				<div class=\"row\" style=\"margin-bottom: 10px\">
					<div class=\"col-md-3 text-right\">
						<form action=\"<?php echo site_url('$c_url/index'); ?>\" class=\"form-inline\" method=\"get\">
							<div class=\"input-group\">
								<input type=\"text\" class=\"form-control\" name=\"q\" value=\"<?php echo \$q; ?>\">
								<span class=\"input-group-btn\">
									<?php 
										if (\$q <> '')
										{
											?>
											<a href=\"<?php echo site_url('$c_url'); ?>\" class=\"btn btn-default\">Reset</a>
											<?php
										}
									?>
								  <button class=\"btn btn-primary\" type=\"submit\">Cerca</button>
								</span>
							</div>
						</form>
					</div>
				</div>
				<table width=\"100%\" class=\"table table-striped table-bordered table-hover\" id=\"dataTables-example\" style='width: 100%;'>
					<thead>
					<tr>
						<th><input type=\"checkbox\" id=\"check_master\" name=\"check_master\" onchange=\"selezionaDeselezionaTutti('check_master','check_id','btDeleteMass')\" /></th>";
				
		$nrRow = 0;	
		$grid_custom_settings[$nrRow]['field_name'] = $pk; 
		$grid_custom_settings[$nrRow]['label'] = "";	
		$grid_custom_settings[$nrRow]['visible'] = 'TRUE';		
		$grid_custom_settings[$nrRow]['cell_type'] = 'checkbox';		
				
		foreach ($non_pk as $row) {
			$nrRow++;		
			
			//MOSTRO LA COLONNA SOLO SE SELEZIONATA
			if(in_array($row['column_name'],$column_name)){
				//SETTO LA LABEL
				if($row["column_comment"] != ""){
					$etichettaLabel = $row["column_comment"];
				} else {
					$etichettaLabel = $row["column_name"];
				}		
				
				$grid_custom_settings[$nrRow]['column_name'] = $row["column_name"]; 
				$grid_custom_settings[$nrRow]['label'] = $etichettaLabel;	
				$grid_custom_settings[$nrRow]['visible'] = 'TRUE';		
				$grid_custom_settings[$nrRow]['cell_type'] = 'cell';		
				$string .= "\n\t\t<th>".$helper->label($etichettaLabel) . "</th>";		
			}	
		}
		$string .= "\n\t\t<th>Action</th>
					</tr></thead>";
		$string .= "<?php
					foreach ($" . $c_url . "_data as \$$c_url)
					{
						?>
						<tr>";



		$string .= "\n\t\t\t<td width=\"80px\"><input type=\"checkbox\" id=\"check_id\" name=\"check_id\" value ='<?php echo $" . $c_url ."->". $pk . " ?>' onchange='verificaNrCheckBoxSelezionati(\"check_id\",\"btDeleteMass\")'></td>";	

		$nrRow = 0;	
		$grid_custom_settings[$nrRow]['field_name'] = $pk; 
		$grid_custom_settings[$nrRow]['label'] = $etichettaLabel;	
		$grid_custom_settings[$nrRow]['visible'] = 'TRUE';		
		$grid_custom_settings[$nrRow]['cell_type'] = 'checkbox';	
			
		foreach ($non_pk as $row) {
			$nrRow++;
			
			//MOSTRO LA COLONNA SOLO SE SELEZIONATA
			if(in_array($row['column_name'],$column_name)){	
				//SETTO LA LABEL
				if($row["column_comment"] != ""){
					$etichettaLabel = $row["column_comment"];
				} else {
					$etichettaLabel = $row["column_name"];
				}		

				$grid_custom_settings[$nrRow]['field_name'] = $row["column_name"]; 
				$grid_custom_settings[$nrRow]['label'] = $etichettaLabel;	
				$grid_custom_settings[$nrRow]['visible'] = 'TRUE';		
				$grid_custom_settings[$nrRow]['cell_type'] = 'cell';
				$string .= "\n\t\t\t<td><?php echo $" . $c_url ."->". $row['column_name'] . " ?></td>";
			}	
			
		}

		$string .= "<td style=\"text-align:center\" width=\"200px\">
							<a class=\"btn btn-sm btn-info\" href='<?php echo base_url();?>$c_url/read/<?php echo $".$c_url."->".$pk."?>' title=\"Visualizza\">
							  <i class=\"fa fa-book\"></i>
							</a>				
							<a class=\"btn btn-sm btn-info\" href='<?php echo base_url();?>$c_url/update/<?php echo $".$c_url."->".$pk."?>'  title=\"Modifica\">
							  <i class=\"fa fa-pencil\"></i>
							</a>	
							<a class=\"btn btn-sm btn-danger deleteUser\" style='cursor:pointer' title=\"Cancella\" onclick=\"deleteEntry('<?php echo $".$c_url."->".$pk."?>' , '".$c_url."','delete')\" >
							  <i class=\"fa fa-trash\"></i>
							</a></td>";					

				

		$string .=  "\n\t\t</tr>
						<?php
					}
					?>
				</table>
				<div class=\"row\">
					<div class=\"col-md-6\">
						<span class=\"btn btn-default\">Numero Record : <?php echo \$total_rows ?></span><br/><br/>";
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
		$string .= "\n\t    <br><br></div>
					<div class=\"col-md-6 text-right\">
						<?php echo \$pagination ?>
					</div>
				</div>";

		$string .= "		
		</div>		
		<!--FINE-->
		</section>
		</div>
		<!--FINE-->";			


		$jsonGrid = json_encode($grid_custom_settings);				
				
		$sqlDelete = "DELETE FROM core_crud_settings WHERE mod_name= '".$table_name."'";
		//echo $sqlDelete;
		$hc->execSQL($sqlDelete);

		$grid_custom_settings_detail_json = json_encode($grid_custom_settings_detail);

		$sqlInsert = "INSERT INTO core_crud_settings(mod_name,
													mod_table_name,
													class_name,
													mod_type,
													mod_title,
													general_settings,
													grid_custom_settings,
													fields_custom_settings,
													grid_custom_settings_detail,
													fields_custom_settings_detail,
													is_generable) 
					VALUES('$table_name',
							'$table_name',
							'$table_name',
							'crud',
							'$mod_title',
							'$json_general_settings',
							'$jsonGrid',
							'',
							'$grid_custom_settings_detail_json',
							'',
							'S')";
		//echo "<br><br>".$sqlInsert;			
		$hc->execSQL($sqlInsert);				
		
	
		return $string;
	}

}	 
 
?>