<?php


class create_win_form {
	
	public static function output($arrayRefFields,$arrayField, $param_gen,$table){
 
		$helper = new Helper();
		foreach($param_gen as $key => $value){
			${$key} = $value;
		}		
		
		$arrayRefFields = $Mod_crud_gen_model->getFieldsRef($table['TABLE_NAME']);
		$nrRow = 0;		
        $column_field_begin ="";
        $column_field_end ="";
        $string = "\n\t\t\t<?php";
 
		//PRINT'<PRE>';PRINT_R($arrayRefFields);
		foreach ($arrayField as $k=> $row) {
			//PRINT'<PRE>';PRINT_R($row);
			//MOSTRO IL CAMPO SOLO SE SELEZIONATO

			if($row["COLUMN_KEY"] != "PRI"){
				//SETTO LA LABEL
				if($row["COLUMN_COMMENT"] != ""){
					$etichettaLabel = $row["COLUMN_COMMENT"];
				} else {
					$etichettaLabel = $row["COLUMN_NAME"];
				}	

				$string .= $column_field_begin;	
				$lblIsRequired = "";
				if($row['IS_NULLABLE'] == 'NO'){
					$lblIsRequired = "<b style=\"color:#990000\">(*)</b>";				
				}		

				
				switch($row["DATA_TYPE"]){
					case 'blob':
					case 'tinyblob':
					case 'mediumblob':
					case 'longblob':
					case 'binary':
					case 'varbinary':
						$string .= "\n\t\techo '<div class=\"form-group\">';
									\n\t\techo '<label for=\"".$row["DATA_TYPE"]."\">$lblIsRequired".$helper->label($etichettaLabel)." </label>';";
						$string .=" 
								if(\$rowWinForm['".$row["COLUMN_NAME"]."'] != ''){
									echo '<img src=\"data:image/jpeg;base64,'.\$rowWinForm['".$row["COLUMN_NAME"].".'] .'\" style=\"width:90px\"  />';	
								}
						";
						$string .= "\techo '				
												<div class=\"input-group\">
													<div class=\"input-group-addon\"><i class=\"fa fa-text-height\"></i></div>';";	
				
						$string .="\techo '<input type=\"file\" class=\"form-control\"  name=\"".$row["COLUMN_NAME"]."\" id=\"".$row["COLUMN_NAME"]."\"  />';";
							
						$string .= "\techo '</div></div>';";					
					break;
					
					
					case 'enum':
						$string .= "\n\t\techo '<div class=\"form-group\">';
									\n\t\techo '<label for=\"".$row["COLUMN_NAME"]."\">$lblIsRequired".$helper->label($etichettaLabel)." </label>';
									\n\t\techo '<SELECT name=\'".$row["COLUMN_NAME"]."\' id=\'".$row["COLUMN_NAME"]."\' 
										style=\"width:100%;padding: 6px 12px;font-size:14px;
										border-top-right-radius:0px;border-bottom-right-radius:0px;
										border-top-left-radius:0px;border-bottom-left-radius:0px;
										border:1px solid #ccc\"
									class=\"form-control\">';";
						$COLUMN_TYPE =  str_replace("enum(","",$row["COLUMN_TYPE"]);
						$COLUMN_TYPE = substr($COLUMN_TYPE, 0, -1);
						$arrayColumnType = explode(",",$COLUMN_TYPE);	
						$string .="\n\t\techo '<OPTION VALUE></OPTION>';";		
					
						foreach($arrayColumnType as $key => $value){
							$string .= "\n\t\tif(\$rowWinForm['".$row["COLUMN_NAME"]."'] == ".$value."){";
							$string .="\n\t\techo '<OPTION VALUE=\'".str_replace("'","",$value)."\'  SELECTED>".str_replace("'","",$value)."</OPTION>';";							
							$string .= "\n\t\t} else {";
							$string .="\n\t\techo '<OPTION VALUE=\'".str_replace("'","",$value)."\'  >".str_replace("'","",$value)."</OPTION>';";								
							$string .= "\n\t\t}";	
						}						
						$string .= "\n\t\techo '</SELECT>';
									\n\t\techo '</div>';";
						$string .= "\n\t\techo '';

						";					
					break;			
					
					case 'set':
						$string .= "\n\t\techo '<div class=\"form-group\">';
									\n\t\techo '<label for=\"".$row["COLUMN_NAME"]."\">$lblIsRequired".$helper->label($etichettaLabel)." </label>';";
						
						$COLUMN_TYPE =  str_replace("set(","",$row["COLUMN_TYPE"]);
						$COLUMN_TYPE = substr($COLUMN_TYPE, 0, -1);
						$arrayColumnType = explode(",",$COLUMN_TYPE);	

						foreach($arrayColumnType as $key => $value){
							$string .= "\techo '<b>".str_replace("'","",$value)."</b><input name=\'".$row["COLUMN_NAME"]."\' id=\'".$row["COLUMN_NAME"]."\' type=\"checkbox\" value=\'".str_replace("'","",$value)."\' >';";
						}						
	
						$string .= "\techo '</div>';";

					break;	

					case 'tinytext':
					case 'mediumtext':
					case 'longtext':
					case 'text':	
						$string .= "\n\t\techo '<div class=\"form-group\">';
									\n\t\techo '<label for=\"".$row["COLUMN_NAME"]."\">$lblIsRequired".$helper->label($etichettaLabel)."</label>';
									\n\t\techo '<textarea class=\"form-control\" rows=\"3\" name=\"".$row["COLUMN_NAME"]."\" id=\"".$row["COLUMN_NAME"]."\" placeholder=\"".$helper->label($etichettaLabel)."\">'.\$rowWinForm['".$row["COLUMN_NAME"]."'].'</textarea>';
									\n\t\techo '</div>';";		
						
					break;
					
					case 'char':
					case 'varchar':
						if(substr($row["COLUMN_NAME"],0,3) == "ora"){
							$string .= "\n\t\techo '<div class=\"form-group\">';
							\n\t\techo '<label for=\"".$row["DATA_TYPE"]."\">$lblIsRequired".$helper->label($etichettaLabel)."</label>';
							\n\t\techo '<div class=\"input-group\">';
							\n\t\techo '<div class=\"input-group-addon\"><i class=\"fa fa-text-calendar\"></i></div>';		
							\n\t\techo '<input type=\"text\" class=\"form-control timemask\" maxlength=\'".$row["CHARACTER_MAXIMUM_LENGTH"]."\' name=\"".$row["COLUMN_NAME"]."\" id=\"".$row["COLUMN_NAME"]."\" placeholder=\"".$helper->label($etichettaLabel)."\" autocomplete=\"off\" value=\"'.\$rowWinForm['".$row["COLUMN_NAME"]."'].'\" />';
							\n\t\techo '</div></div>';";													
						} else {
							if((substr($row["COLUMN_NAME"],0,5) == "email") || (substr($row["COLUMN_NAME"],0,4) == "mail")){
								$icon ="fa fa-at";
							} else {
								$icon ="fa fa-text-height";
							}							
							$string .= "\n\t\techo '<div class=\"form-group\">';
							\n\t\techo '<label for=\"".$row["DATA_TYPE"]."\">$lblIsRequired".$helper->label($etichettaLabel)."</label>';
							\n\t\techo '<div class=\"input-group\">';
							\n\t\techo '<div class=\"input-group-addon\"><i class=\"".$icon."\"></i></div>';		
							\n\t\techo '<input type=\"text\" class=\"form-control\" maxlength=\'".$row["CHARACTER_MAXIMUM_LENGTH"]."\' name=\"".$row["COLUMN_NAME"]."\" id=\"".$row["COLUMN_NAME"]."\" placeholder=\"".$helper->label($etichettaLabel)."\" autocomplete=\"off\" value=\"'.\$rowWinForm['".$row["COLUMN_NAME"]."'].'\" />';
							\n\t\techo '</div></div>';";		
						}
		
					break;
					
					
					case 'tinyint':
					case 'smallint':
					case 'mediumint':
					case 'bigint':
					case 'int':
					case 'serial':
						//print($row['COLUMN_NAME']);
						//print'<pre>';print_r($arrayRefFields);
						if(array_key_exists($row['COLUMN_NAME'],$arrayRefFields)){
							$string .= "\n\t\techo '<div class=\"form-group\">';
		 
							\$".$row["COLUMN_NAME"]."_label = NULL;
							foreach (\$".$row["COLUMN_NAME"]."_refval as \$key => \$value) {
								if (\$value['id'] == \$rowWinForm['".$row["COLUMN_NAME"]."']) {
									\$".$row["COLUMN_NAME"]."_label = \$value['nome'];
								}  
							}
				 						
							\n\t\techo '<label for=\"".$row["COLUMN_NAME"]."\">$lblIsRequired".$helper->label($etichettaLabel)." </label>';
							\n\t\techo \"							
							\n\t\t<input autofocus class='form-control' autocomplete='off' 
								oninput='onInput(\\\"".$row["COLUMN_NAME"]."_datalist_inp\\\",\\\"".$row["COLUMN_NAME"]."_datalist\\\",\\\"".$row["COLUMN_NAME"]."\\\")'
								style='width:100%;padding: 6px 12px;font-size:14px;
									border-top-right-radius:0px;border-bottom-right-radius:0px;
									border-top-left-radius:0px;border-bottom-left-radius:0px;
									border:1px solid #ccc'						
								list='".$row["COLUMN_NAME"]."_datalist'  
								name='".$row["COLUMN_NAME"]."_datalist_inp' 
								id='".$row["COLUMN_NAME"]."_datalist_inp' value='\$".$row["COLUMN_NAME"]."_label'>
							\n\t\t<input type='hidden' name='".$row["COLUMN_NAME"]."' id='".$row["COLUMN_NAME"]."' value='\".\$rowWinForm['".$row["COLUMN_NAME"]."'].\"' >
							\n\t\t<datalist name='".$row["COLUMN_NAME"]."_datalist' id='".$row["COLUMN_NAME"]."_datalist' >\";";
							$string .="\techo '<OPTION VALUE></OPTION>';";		
							$string .="\n";
							$string .="\n\t\tforeach (\$".$row["COLUMN_NAME"]."_refval as \$key => \$value) {";
							$string .="\n\t\t\tif (\$value['id'] == \$rowWinForm['".$row["COLUMN_NAME"]."']) {";
							$string .="\n\t\t\t\techo \"<option data-value='\" . \$value['id'] . \"' SELECTED>\" . \$value['nome'] . \"</option>\";";
							$string .="\n\t\t\t} else {";
							$string .="\n\t\t\t\techo \"<option data-value='\".\$value['id'] . \"'>\" . \$value['nome'] . \"</option>\";";
							$string .="\n\t\t\t}";
							$string .="\n\t\t}";						
							$string .= "\n\t\techo '</SELECT>';
										\n\t\techo '</div>';";


						} else {
							$string .= "\n\t\techo ' <div class=\"form-group\">';
										\n\t\techo '<label for=\"".$row["DATA_TYPE"]."\">$lblIsRequired".$helper->label($etichettaLabel)." </label>';
										\n\t\techo '<div class=\"input-group\">';
										\n\t\techo '<div class=\"input-group-addon\"><i class=\"fa fa-sort-numeric-asc\"></i></div>';			
										\n\t\techo '<input type=\"number\" class=\"form-control\" maxlength=\'".$row["NUMERIC_PRECISION"]."\' name=\"".$row["COLUMN_NAME"]."\" id=\"".$row["COLUMN_NAME"]."\" placeholder=\"".$helper->label($etichettaLabel)."\" autocomplete=\"off\" value=\"'.\$rowWinForm['".$row["COLUMN_NAME"]."'].'\" />';
										\n\t\techo '</div></div>';";			
						}	
						
					break;
					

					case 'decimal':
					case 'float':
					case 'double':
					case 'real':
						$string .= "\n\t\techo ' <div class=\"form-group\">';
						\n\t\techo '<label for=\"".$row["DATA_TYPE"]."\">$lblIsRequired".$helper->label($etichettaLabel)." </label>';
						\n\t\techo '<div class=\"input-group\">';
						\n\t\techo '<div class=\"input-group-addon\"><i class=\"fa fa-sort-numeric-asc\"></i></div>';			
						\n\t\techo '<input type=\"number\" class=\"form-control\" maxlength=\'".$row["NUMERIC_PRECISION"]."\' name=\"".$row["COLUMN_NAME"]."\" id=\"".$row["COLUMN_NAME"]."\" placeholder=\"".$helper->label($etichettaLabel)."\" autocomplete=\"off\" value=\"'.\$rowWinForm['".$row["COLUMN_NAME"]."'].'\" step=0.01 />';
						\n\t\techo '</div></div>';";				
		
					break;	

					
					//USATO PER IL FORMATO YYYY/MM/DD
					case 'date':
						$string .= "\n\t\techo '<div class=\"form-group\">';
									\n\t\techo '<label for=\"".$row["DATA_TYPE"]."\">$lblIsRequired".$helper->label($etichettaLabel)." </label>';
									\n\t\techo '<div class=\"input-group\">';
									\n\t\techo '<div class=\"input-group-addon\"><i class=\"fa fa-calendar\"></i></div>';			
									\n\t\techo '<input type=\"text\" class=\"form-control datemask\" name=\"".$row["COLUMN_NAME"]."\" id=\"".$row["COLUMN_NAME"]."\" placeholder=\"".$helper->label($etichettaLabel)."\"';
									\n\t\techo 'autocomplete=\"off\" style=\"background-color:#FFFFFF\" value=\"'.\$rowWinForm['".$row["COLUMN_NAME"]."'].'\" />';
									\n\t\techo '</div></div>';";	

					break;
					

					//USATO PER IL FORMATO YYYY/MM/DD HH:MM
					case 'datetime':
					case 'timestamp':
						$string .= "\n\t\techo '<div class=\"form-group\">';
									\n\t\techo '<label for=\"".$row["DATA_TYPE"]."\">$lblIsRequired".$helper->label($etichettaLabel)." </label>';
									\n\t\techo '			<div class=\"input-group\">';
									\n\t\techo '				<div class=\"input-group-addon\"><i class=\"fa fa-calendar\"></i></div>';	
									\n\t\techo '<input type=\"text\" class=\"form-control datetimemask\" name=\"".$row["COLUMN_NAME"]."\" id=\"".$row["COLUMN_NAME"]."\" placeholder=\"".$helper->label($etichettaLabel)."\" autocomplete=\"off\" value=\"'.\$rowWinForm['".$row["COLUMN_NAME"]."'].'\" />';
									\n\t\techo '</div></div>';";		

					break;
					
					
					//USATO PER IL FORMATO HH:MM
					case 'time':
						$string .= "\n\t\techo '<div class=\"form-group\">';
									\n\t\techo '<label for=\"".$row["DATA_TYPE"]."\">$lblIsRequired".$helper->label($etichettaLabel)." </label>';
									\n\t\techo '			<div class=\"input-group\">';
									\n\t\techo '				<div class=\"input-group-addon\"><i class=\"fa fa-calendar\"></i></div>';	
									\n\t\techo '<input type=\"text\" class=\"form-control timemask\" name=\"".$row["COLUMN_NAME"]."\" id=\"".$row["COLUMN_NAME"]."\" placeholder=\"".$helper->label($etichettaLabel)."\" autocomplete=\"off\" value=\"'.\$rowWinForm['".$row["COLUMN_NAME"]."'].'\" />';
									\n\t\techo '</div></div>';";		

					break;
					
					
					default:
						$string .= "\n\t\techo '<div class=\"form-group\">';
									\n\t\techo '<label for=\"".$row["DATA_TYPE"]."\">$lblIsRequired".$helper->label($etichettaLabel)." </label>';
									\n\t\techo '<div class=\"input-group\">';
									\n\t\techo '<div class=\"input-group-addon\"><i class=\"fa fa-cubes\"></i></div>';			
									\n\t\techo '<input type=\"text\" class=\"form-control\" maxlength=\'".$row["CHARACTER_MAXIMUM_LENGTH"]."\' name=\"".$row["COLUMN_NAME"]."\" id=\"".$row["COLUMN_NAME"]."\" placeholder=\"".$helper->label($etichettaLabel)."\" autocomplete=\"off\" value=\"'.\$rowWinForm['".$row["COLUMN_NAME"]."'].'\" />';
									\n\t\techo '</div></div>';";		

					break;
				}
			 
									
			}

					
			
			$string .= $column_field_end;	
		 
			$nrRow++;
			
		}

		return $helper->createFile($string, APPPATH."modules/" . $param_gen['c_url'] . "/views/partials/winform/win_" . $table['TABLE_NAME'].".php");		

	}

}	  


?>