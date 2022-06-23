<?php
 

class create_view_form extends AbstractGenerator{
	
	public static function output($param_gen){
		
		$helper = new Helper();
		foreach($param_gen as $key => $value){
			${$key} = $value;
		}		
		$form_columns_nr_layout = $form_columns_nr_layout;
		$fields_custom_settings = array();

		switch($form_columns_nr_layout){
			case 1:
			case '1':
				$column_field_begin ="";
				$column_field_end ="";
			break;
			
			case 2:
			case '2':
				$column_field_begin ="<div class=\"col-md-6\">";
				$column_field_end ="</div>";	
			break;

			case 3:
			case '3':
				$column_field_begin ="<div class=\"col-md-4\">";
				$column_field_end ="</div>";		
			break;	
			
			
			default:
				$column_field_begin ="";
				$column_field_end ="";	
			break;
			
		}


		$mod_icon = $mod_icon;
		$string ='
		<div class="content-wrapper">
			
			<!-- Content Header (Page header) -->
			<section class="content-header">
				<h1>
					<i class="fa '.$mod_icon .'"></i> 
					<a href="<?php echo site_url("'.$c_url.'") ?>">
					<u> '.$mod_title.'</u>
					</a>
				   <b style=\'font-size:20px\'> >> </b><b style=\'font-size:20px\'>Descrizione Azione</b>
				</h1>
					<div class="col-md-4">
						<?php
						$error = $this->session->flashdata(\'error\');
						if($error)
						{
					?>
							<div class="alert alert-danger alert-dismissable">
								<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
								<?php echo $this->session->flashdata(\'error\'); ?>
							</div>
							<?php } ?>
							<?php  
						$success = $this->session->flashdata(\'success\');
						if($success)
						{
					?>
							<div class="alert alert-success alert-dismissable">
								<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
								<?php echo $this->session->flashdata(\'success\'); ?>
							</div>
							<?php } ?>

							<div class="row">
								<div class="col-md-12">
									<?php echo validation_errors(\'<div class="alert alert-danger alert-dismissable">\', \' <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button></div>\'); ?>
								</div>
							</div>
					</div>		
			</section>
			<section class="content">
				
				<div class="row">
					<!-- left column -->
					<div class="col-md-12">
						<!-- general form elements -->
						<div class="box box-primary">
							<div class="box-header">
								<h3 class="box-title">Informazioni Modulo</h3>
							</div>
							<!-- /.box-header -->
							<!-- form start -->
						
							 <div class="box-body">
								<?php $this->load->helper("form"); ?>';
		 
								
		$string .= "
				<form action=\"<?php echo \$action; ?>\" method=\"post\" name=\"<?php echo \$frm_module_name; ?>\" id=\"<?php echo \$frm_module_name; ?>\">";

		$string .= "
					<div class='col-md-12'>
						<B STYLE='color:#990000'>(*)</B>Campi obbligatori
						<br><br>					
					</div>";

		$nrRow = 0;		
		foreach ($non_pk as $row) {
			//MOSTRO IL CAMPO SOLO SE SELEZIONATO
			if(in_array($row['COLUMN_NAME'],$field_name)){		
				$fields_custom_settings[$nrRow]['field_name'] = $row["COLUMN_NAME"]; 
				$fields_custom_settings[$nrRow]['visible'] = 'TRUE';	
				$fields_custom_settings[$nrRow]['position'] = array("row_nr" => $nrRow, "column_nr" => 1);
				if(isset($select_ext_mod[$row['COLUMN_NAME']])){
					$fields_custom_settings[$nrRow]['ext_mod'] = $select_ext_mod[$row['COLUMN_NAME']];			
				}

				//SETTO LA LABEL
				if($row["COLUMN_COMMENT"] != ""){
					$etichettaLabel = $row["COLUMN_COMMENT"];
				} else {
					$etichettaLabel = $row["COLUMN_NAME"];
				}	
					
				$fields_custom_settings[$nrRow]['label'] = $etichettaLabel;			
					
				$string .= $column_field_begin;	
				
				$lblIsRequired = "";
				if($row['IS_NULLABLE'] == 'NO'){
					$lblIsRequired = "<b style=\"color:#990000\">(*)</b>";				
				}		
				
				
				//PRINT'<PRE>';print_r($lblIsRequired);die();
				
				switch($row["DATA_TYPE"]){
					case 'blob':
					case 'tinyblob':
					case 'mediumblob':
					case 'longblob':
						$string .= "\n\t    <div class=\"form-group\">
								<label for=\"".$row["DATA_TYPE"]."\">$lblIsRequired".$helper->label($etichettaLabel)." <?php echo form_error('".$row["COLUMN_NAME"]."') ?></label>";
						$string .=" 
							<?php 
								if($".$row["COLUMN_NAME"]." != ''){
									echo '<img src=\"data:image/jpeg;base64,'.$".$row["COLUMN_NAME"].".'\" style=\"width:90px\"  />';	
								}
							?>				
						";
						$string .= "				
												<div class=\"input-group\">
													<div class=\"input-group-addon\"><i class=\"fa fa-text-height\"></i></div>";	
			
						$string .=" <input type=\"file\" class=\"form-control\"  name=\"".$row["COLUMN_NAME"]."\" id=\"".$row["COLUMN_NAME"]."\"  />";
							
						$string .= "	</div></div>";	
						$fields_custom_settings[$nrRow]['field_type'] = 'blob';						
					break;
					
					
					case 'enum':
					case 'set':
						$string .= "\n\t    <div class=\"form-group\">
								<label for=\"".$row["COLUMN_NAME"]."\">$lblIsRequired".$helper->label($etichettaLabel)." <?php echo form_error('".$row["COLUMN_NAME"]."') ?></label>
								<SELECT name='".$row["COLUMN_NAME"]."' id='".$row["COLUMN_NAME"]."' class=\"form-control\">";
						$string .=" 
						<?php if($".$row["COLUMN_NAME"]." == 'SI'){
								echo '<OPTION VALUE=\"SI\" SELECTED>SI</OPTION>
									<OPTION VALUE=\"NO\">NO</OPTION>';				
							} else {
								echo '<OPTION VALUE=\"SI\" >SI</OPTION>
									<OPTION VALUE=\"NO\" SELECTED>NO</OPTION>';							
							}
						?>				
						";						
						$string .= "</SELECT>
							</div>";	
						$fields_custom_settings[$nrRow]['field_type'] = 'select';					
					break;			
					
					case 'tinytext':
					case 'mediumtext':
					case 'longtext':
					case 'text':	
						$string .= "\n\t    <div class=\"form-group\">
								<label for=\"".$row["COLUMN_NAME"]."\">$lblIsRequired".$helper->label($etichettaLabel)." <?php echo form_error('".$row["COLUMN_NAME"]."') ?></label>
								<textarea class=\"form-control\" rows=\"3\" name=\"".$row["COLUMN_NAME"]."\" id=\"".$row["COLUMN_NAME"]."\" placeholder=\"".$helper->label($etichettaLabel)."\"><?php echo $".$row["COLUMN_NAME"]."; ?></textarea>
							</div>";	
						$fields_custom_settings[$nrRow]['field_type'] = 'textarea';			
						
					break;
					
					case 'char':
					case 'varchar':
						$string .= "\n\t    <div class=\"form-group\">
								<label for=\"".$row["DATA_TYPE"]."\">$lblIsRequired".$helper->label($etichettaLabel)." <?php echo form_error('".$row["COLUMN_NAME"]."') ?></label>
												<div class=\"input-group\">
													<div class=\"input-group-addon\"><i class=\"fa fa-text-height\"></i></div>			
								<input type=\"text\" class=\"form-control\" maxlength='".$row["CHARACTER_MAXIMUM_LENGTH"]."' name=\"".$row["COLUMN_NAME"]."\" id=\"".$row["COLUMN_NAME"]."\" placeholder=\"".$helper->label($etichettaLabel)."\" autocomplete=\"off\" value=\"<?php echo $".$row["COLUMN_NAME"]."; ?>\" />
							</div></div>";	
						$fields_custom_settings[$nrRow]['field_type'] = 'text';			
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
						$string .= "\n\t    <div class=\"form-group\">
								<label for=\"".$row["DATA_TYPE"]."\">$lblIsRequired".$helper->label($etichettaLabel)." <?php echo form_error('".$row["COLUMN_NAME"]."') ?></label>
												<div class=\"input-group\">
													<div class=\"input-group-addon\"><i class=\"fa fa-sort-numeric-asc\"></i></div>			
								<input type=\"number\" class=\"form-control\" maxlength='".$row["NUMERIC_PRECISION"]."' name=\"".$row["COLUMN_NAME"]."\" id=\"".$row["COLUMN_NAME"]."\" placeholder=\"".$helper->label($etichettaLabel)."\" autocomplete=\"off\" value=\"<?php echo $".$row["COLUMN_NAME"]."; ?>\" />
							</div></div>";			
						$fields_custom_settings[$nrRow]['field_type'] = 'numeric';			
					break;
					
					
					case 'date':
						$string .= "\n\t    <div class=\"form-group\">
								<label for=\"".$row["DATA_TYPE"]."\">$lblIsRequired".$helper->label($etichettaLabel)." <?php echo form_error('".$row["COLUMN_NAME"]."') ?></label>
												<div class=\"input-group\">
													<div class=\"input-group-addon\"><i class=\"fa fa-calendar\"></i></div>			
								<input type=\"text\" class=\"form-control datepicker\" name=\"".$row["COLUMN_NAME"]."\" id=\"".$row["COLUMN_NAME"]."\" placeholder=\"".$helper->label($etichettaLabel)."\" 
									autocomplete=\"off\" style=\"background-color:#FFFFFF\" readonly=\"readonly\" value=\"<?php echo $".$row["COLUMN_NAME"]."; ?>\" />
							</div></div>";	
						$fields_custom_settings[$nrRow]['field_type'] = 'date';		
					break;
					
					
					case 'datetime':
					case 'timestamp':
					case 'time':
						$string .= "\n\t    <div class=\"form-group\">
								<label for=\"".$row["DATA_TYPE"]."\">$lblIsRequired".$helper->label($etichettaLabel)." <?php echo form_error('".$row["COLUMN_NAME"]."') ?></label>
												<div class=\"input-group\">
													<div class=\"input-group-addon\"><i class=\"fa fa-calendar\"></i></div>			
								<input type=\"text\" class=\"form-control datetimepicker\" name=\"".$row["COLUMN_NAME"]."\" id=\"".$row["COLUMN_NAME"]."\" placeholder=\"".$helper->label($etichettaLabel)."\" autocomplete=\"off\" value=\"<?php echo $".$row["COLUMN_NAME"]."; ?>\" />
							</div></div>";		
						$fields_custom_settings[$nrRow]['field_type'] = 'datetime';	
					break;
					
					
					default:
						$string .= "\n\t    <div class=\"form-group\">
								<label for=\"".$row["DATA_TYPE"]."\">$lblIsRequired".$helper->label($etichettaLabel)." <?php echo form_error('".$row["COLUMN_NAME"]."') ?></label>
												<div class=\"input-group\">
													<div class=\"input-group-addon\"><i class=\"fa fa-cubes\"></i></div>			
								<input type=\"text\" class=\"form-control\" maxlength='".$row["CHARACTER_MAXIMUM_LENGTH"]."' name=\"".$row["COLUMN_NAME"]."\" id=\"".$row["COLUMN_NAME"]."\" placeholder=\"".$helper->label($etichettaLabel)."\" autocomplete=\"off\" value=\"<?php echo $".$row["COLUMN_NAME"]."; ?>\" />
							</div></div>";		
						$fields_custom_settings[$nrRow]['field_type'] = 'text';			
					break;
				}
				$string .= $column_field_end;	
			}
			$nrRow++;
			
		}
		 
		 
		if($form_allegati == 'Y'){		
			$string .= "\n\t
							<div class=\"col-md-12\">
								<div class=\"form-group\">
										<label for=\"note\">Allegati <?php echo form_error(\"allegati\") ?>
										<br><span style=\"font-size:12px\"><b>File Ammessi:</b><b style=\"color:#990000\"><?php echo implode(\",\",\$extAdmitted);?></b></span>
										</label>
										<input type=\"file\" name=\"allegati[]\" id=\"allegati[]\" class=\"form-control\" multiple />
										
										<br>
										<ul>
										<?php		
											\$icon_delete = \"<img src='\".base_url().\"assets/images/delete2.png' width='32'/>\";
											
											\$count = 0;
											foreach(\$allegati as \$key => \$allegato){
												\$nomeAllegato =  \$allegato['allegato'];

												if((\$id != '')){
													echo \"<li id='allegato_\".\$count.\"'><a href='\".base_url().\"$c/scaricaAllegato/$c/\".\$id.\"/\".\$nomeAllegato.\"' target='_blank'>\".\$allegato['allegato'].\"</a>\"; 
													echo \"<a style='cursor:pointer' onclick=\\\"rimuoviAllegato('$c', '\$id','\$nomeAllegato','allegato_\$count')\\\"> | \".\$icon_delete.\"</a></li>\";
													
												} else {
													echo \$allegato['allegato'].\"<BR>\"; 
												}
												
												\$count++;
											}
											if((\$id == '')){
												echo \"<br><br><b> - Note:</b>Gli allegati possono essere cancellati solo in modifica\";
											}
										?>
										</ul>
										<br/>
									</div>
							</div>";
		}					
							 

		$string .= "\n\t
		<div class=\"col-md-12\">
			<div class=\"form-group\">
				<div id=\"divAjaxMsg\" style=\"display:none;font-size:18px\" class=\"alert alert-success alert-dismissable\" onclick=\"hideMsg()\">
				</div>
				<?php 
					if(isset(\$master_details_list)){
						foreach(\$master_details_list as  \$master_details){
							echo \$master_details;
						}
					}
				?>
				<p><br><br><br></p>
			</div>
		</div>";		

		$string .= "\n\t    <input type=\"hidden\" name=\"".$pk."\" value=\"<?php echo $".$pk."; ?>\" /> ";

		$string .= "<div class=\"row\">
						<div class=\"col-md-12\">";	
						
		$string .= "		<div class=\"box-footer\">
								  <div class='row'>
									<div class='col-md-6'>

										<button id='<?php echo \$button_id; ?>' 
										onclick='submitFormModule(\"<?php echo \$frm_module_name; ?>\",\"<?php echo \$button_id; ?>\",\"reset_form\")'
										type=\"button\" class=\"btn btn-success  button-submit\"  data-loading-text=\"Caricamento...\"><span class=\"fa fa-save\"></span> SALVA</button>

										<button id='reset_form' name='reset_form' type=\"reset\" class=\"btn btn-default\">
										<span class=\"fa fa-eraser\"></span> RESET</button>		
									</div>
									
										<div class=\"col-md-6\" align=\"right\">
											<a href=\"<?php echo site_url('$c_url') ?>\" class=\"btn btn-default\"><i class=\"fa fa-arrow-circle-left\"></i> INDIETRO</a>			
										</div>	
										
									</div>
								</div>	
						</div>
					</div>	
			
		</div>		
		</div>";

		 
		$string .= "\n\t</form>";

		$string .= "
							</div>
							
							<!-- form close -->
							</div>
						</div>
					</div>
			</section>

			</div>";		
	
		return $string;
	}

}	
	
 

?>