<?php


class create_view_read_ajax extends AbstractGenerator{
	
	
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
		<div id="main_content_ajax_form_read_$id">	
			<!-- Content Header (Page header) -->
				<div class="col-md-8">
				<h3>
					<i class="fa '.$mod_icon .'"></i> 
					<a>
					<u> '.$mod_title.'</u>
					</a>	   
				   
				</h3>		

				</div>		
				<div class="row">
					<!-- left column -->
					<div class="col-md-12">
						<!-- general form elements -->
						<div class="box box-primary">
							<!-- /.box-header -->
							<!-- form start -->
						
							 <div class="box-body">';
								
		$column_non_pk = "";
		$arrayOrd = array();
		foreach ($field_name as $keyReq =>$rowReq) {
			foreach ($non_pk as $row) {
				if($row['COLUMN_NAME'] == $rowReq){
					$arrayOrd[] = $row;
				}
			}
		}			
				
				
		$nrRow = 0;		
		foreach ($arrayOrd as $row) {
			//MOSTRO IL CAMPO SOLO SE SELEZIONATO
		 
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
				
				
			//PRINT'<PRE>';print_r($row);die();	
			
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
			
					$string .=" <input disabled='disabled' type=\"file\" class=\"form-control\"  name=\"".$row["COLUMN_NAME"]."\" id=\"".$row["COLUMN_NAME"]."\"  />";
						
					$string .= "	</div></div>";	
					$fields_custom_settings[$nrRow]['field_type'] = 'blob';						
				break;
				
				
				case 'enum':
					$string .= "\n\t    <div class=\"form-group\">
							<label for=\"".$row["COLUMN_NAME"]."\">$lblIsRequired".$helper->label($etichettaLabel)." <?php echo form_error('".$row["COLUMN_NAME"]."') ?></label>
							<SELECT disabled='disabled' name='".$row["COLUMN_NAME"]."' id='".$row["COLUMN_NAME"]."' class=\"form-control\">";
					$COLUMN_TYPE =  str_replace("enum(","",$row["COLUMN_TYPE"]);
					$COLUMN_TYPE = substr($COLUMN_TYPE, 0, -1);
					$arrayColumnType = explode(",",$COLUMN_TYPE);	
					$string .="\n<OPTION VALUE></OPTION>";		
					foreach($arrayColumnType as $key => $value){
						$string .="\n<OPTION VALUE='".str_replace("'","",$value)."'>".str_replace("'","",$value)."</OPTION>";	
					}						
					$string .= "</SELECT>
						</div>";
					$string .= "
						<script>	
						$('[name=".$row["COLUMN_NAME"]."] option').filter(function() { 
							return ($(this).text() == '<?php echo $".$row["COLUMN_NAME"]."?>'); //To select Blue
						}).prop('selected', true);				
						</script>
					";	
					$fields_custom_settings[$nrRow]['field_type'] = 'select';					
				break;			
				
				case 'set':
					$string .= "\n\t    <div class=\"form-group\">
							<label for=\"".$row["COLUMN_NAME"]."\">$lblIsRequired".$helper->label($etichettaLabel)." <?php echo form_error('".$row["COLUMN_NAME"]."') ?></label>";
					
					$COLUMN_TYPE =  str_replace("set(","",$row["COLUMN_TYPE"]);
					$COLUMN_TYPE = substr($COLUMN_TYPE, 0, -1);
					$arrayColumnType = explode(",",$COLUMN_TYPE);	

					foreach($arrayColumnType as $key => $value){
						$string .= "\n\t <b>".str_replace("'","",$value)."</b><input disabled='disabled' name='".$row["COLUMN_NAME"]."' id='".$row["COLUMN_NAME"]."' type=\"checkbox\" value='".str_replace("'","",$value)."' >";
					}						
 
					$string .= "\n\t</div>";

					$fields_custom_settings[$nrRow]['field_type'] = 'checkbox';	
				break;	

				case 'tinytext':
				case 'mediumtext':
				case 'longtext':
				case 'text':	
					$string .= "\n\t    <div class=\"form-group\">
							<label for=\"".$row["COLUMN_NAME"]."\">$lblIsRequired".$helper->label($etichettaLabel)." <?php echo form_error('".$row["COLUMN_NAME"]."') ?></label>
							<textarea disabled='disabled' class=\"form-control\" rows=\"3\" name=\"".$row["COLUMN_NAME"]."\" id=\"".$row["COLUMN_NAME"]."\" placeholder=\"".$helper->label($etichettaLabel)."\"><?php echo $".$row["COLUMN_NAME"]."; ?></textarea>
						</div>";	
					$fields_custom_settings[$nrRow]['field_type'] = 'textarea';			
					
				break;
				
				case 'char':
				case 'varchar':
					$string .= "\n\t    <div class=\"form-group\">
							<label for=\"".$row["DATA_TYPE"]."\">$lblIsRequired".$helper->label($etichettaLabel)." <?php echo form_error('".$row["COLUMN_NAME"]."') ?></label>
											<div class=\"input-group\">
												<div class=\"input-group-addon\"><i class=\"fa fa-text-height\"></i></div>			
							<input disabled='disabled' type=\"text\" class=\"form-control\" maxlength='".$row["CHARACTER_MAXIMUM_LENGTH"]."' name=\"".$row["COLUMN_NAME"]."\" id=\"".$row["COLUMN_NAME"]."\" placeholder=\"".$helper->label($etichettaLabel)."\" autocomplete=\"off\" value=\"<?php echo $".$row["COLUMN_NAME"]."; ?>\" />
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
					if(array_key_exists($row['COLUMN_NAME'],$arrayRefFields)){
						$string .= "\n\t    <div class=\"form-group\">
						<label for=\"".$row["COLUMN_NAME"]."\">$lblIsRequired".$helper->label($etichettaLabel)." <?php echo form_error('".$row["COLUMN_NAME"]."') ?></label>
						<SELECT disabled='disabled' name='".$row["COLUMN_NAME"]."' id='".$row["COLUMN_NAME"]."' class=\"form-control\">";
						$string .="\n<OPTION VALUE></OPTION>";		
						$string .="\n<?php
						foreach (\$".$row["COLUMN_NAME"]."_refval as \$key => \$value) {
							if (\$value['id'] == \$".$row["COLUMN_NAME"].") {
								echo \"<option value='\" . \$value['id'] . \"' SELECTED>\" . \$value['nome'] . \"</option>\";
							} else {
								echo \"<option value='\".\$value['id'] . \"'>\" . \$value['nome'] . \"</option>\";
							}
						}
						?>";						
						$string .= "</SELECT>
							</div>";

						$fields_custom_settings[$nrRow]['field_type'] = 'select';	

					} else {
						$string .= "\n\t    <div class=\"form-group\">
							<label for=\"".$row["DATA_TYPE"]."\">$lblIsRequired".$helper->label($etichettaLabel)." <?php echo form_error('".$row["COLUMN_NAME"]."') ?></label>
										<div class=\"input-group\">
											<div class=\"input-group-addon\"><i class=\"fa fa-sort-numeric-asc\"></i></div>			
							<input disabled='disabled' type=\"number\" class=\"form-control\" maxlength='".$row["NUMERIC_PRECISION"]."' name=\"".$row["COLUMN_NAME"]."\" id=\"".$row["COLUMN_NAME"]."\" placeholder=\"".$helper->label($etichettaLabel)."\" autocomplete=\"off\" value=\"<?php echo $".$row["COLUMN_NAME"]."; ?>\" />
							</div></div>";			
						$fields_custom_settings[$nrRow]['field_type'] = 'numeric';			

					}	
					
				break;
				
				
				case 'date':
					$string .= "\n\t    <div class=\"form-group\">
							<label for=\"".$row["DATA_TYPE"]."\">$lblIsRequired".$helper->label($etichettaLabel)." <?php echo form_error('".$row["COLUMN_NAME"]."') ?></label>
											<div class=\"input-group\">
												<div class=\"input-group-addon\"><i class=\"fa fa-calendar\"></i></div>			
							<input  disabled='disabled' type=\"text\" class=\"form-control datemask\" name=\"".$row["COLUMN_NAME"]."\" id=\"".$row["COLUMN_NAME"]."\" placeholder=\"".$helper->label($etichettaLabel)."\" 
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
							<input disabled='disabled' type=\"text\" class=\"form-control datetimemask\" name=\"".$row["COLUMN_NAME"]."\" id=\"".$row["COLUMN_NAME"]."\" placeholder=\"".$helper->label($etichettaLabel)."\" autocomplete=\"off\" value=\"<?php echo $".$row["COLUMN_NAME"]."; ?>\" />
						</div></div>";		
					$fields_custom_settings[$nrRow]['field_type'] = 'datetime';	
				break;
				
				
				default:
					$string .= "\n\t    <div class=\"form-group\">
							<label for=\"".$row["DATA_TYPE"]."\">$lblIsRequired".$helper->label($etichettaLabel)." <?php echo form_error('".$row["COLUMN_NAME"]."') ?></label>
											<div class=\"input-group\">
												<div class=\"input-group-addon\"><i class=\"fa fa-cubes\"></i></div>			
							<input disabled='disabled' type=\"text\" class=\"form-control\" maxlength='".$row["CHARACTER_MAXIMUM_LENGTH"]."' name=\"".$row["COLUMN_NAME"]."\" id=\"".$row["COLUMN_NAME"]."\" placeholder=\"".$helper->label($etichettaLabel)."\" autocomplete=\"off\" value=\"<?php echo $".$row["COLUMN_NAME"]."; ?>\" />
						</div></div>";		
					$fields_custom_settings[$nrRow]['field_type'] = 'text';			
				break;
			}
			$string .= $column_field_end;	
		 
			$nrRow++;
			
		}
				

		$string .= "
						</div>
							
					</div>
				</div>
			</div>";	
		$string .= "
		</div>";			
	
		return $string;
	}

}	  


?>