<?php


class create_js_form_config extends AbstractGenerator{
	
	public static function output($param_gen){
		
		$helper = new Helper();
		foreach($param_gen as $key => $value){
			${$key} = $value;
		}
		$string = "";
		$string = "<?php /* FILE CONFIGURAZIONE FORM */?>";
		$string .= "\n\n<SCRIPT>";
		$string .= "\n\narrayValidationFields = [];";
		$string .= "\njs_custom_operations_list = [];";
		$string .= "\nobjAjaxConfig.form.mod_name ='<?php echo \$frm_module_name;?>';";
		$string .= "\nobjAjaxConfig.form.recordID ='<?php echo \$id;?>';";
		$string .= "\nobjAjaxConfig.form.ajaxAction = '<?php echo \$ajaxAction;?>';";
		$string .= "\nobjAjaxConfig.form.id_main_content = '<?php echo \$id_main_content;?>';";
		$string .= "\n$('.select2-autocomplete').select2();";
		foreach($arrayRefTables as $k => $table){	
			$string .= "\narrayValidationFields['winMasterDetail".$table['TABLE_NAME']."'] = [];";
			$string .= "\n\n/*js_custom_operations_list['winMasterDetail".$table['TABLE_NAME']."'] = [];";
			$string .= "\njs_custom_operations_list['winMasterDetail".$table['TABLE_NAME']."'].check_date = function(){";
			$string .= "\nvar ret = check_date_greater_then('data_validita_inizio','data_validita_fine');";
			$string .= "\nif(ret === false){";
			$string .= "\n\tdocument.getElementById('msg_err').style.display =  'block';";
			$string .= "\n\tdocument.getElementById('msg_err').innerHTML = 'Date non valide';";
			$string .= "\n\tev.preventDefault();";
			$string .= "\n}";
			$string .= "};*/\n";		
			$columns = $Mod_crud_gen_model->getColumnsTable($table['TABLE_NAME']);
			foreach($columns as $k => $v){
				if($v['COLUMN_KEY'] <> "PRI"){
					$string .= "\narrayValidationFields['winMasterDetail".$table['TABLE_NAME']."']['".$v['COLUMN_NAME']."'] = [];";
					if($v['COLUMN_COMMENT'] != ""){
						$string .= "\narrayValidationFields['winMasterDetail".$table['TABLE_NAME']."']['".$v['COLUMN_NAME']."']['label'] = \"".$v['COLUMN_COMMENT']."\"";
					} else {
						$string .= "\narrayValidationFields['winMasterDetail".$table['TABLE_NAME']."']['".$v['COLUMN_NAME']."']['label'] = \"".$v['COLUMN_NAME']."\"";
					}
					//echo substr($v["COLUMN_NAME"],0,5) ." -".substr($v["COLUMN_NAME"],0,4)."<br>";
					if((substr($v["COLUMN_NAME"],0,5) == "email") || (substr($v["COLUMN_NAME"],0,4) == "mail")){
						$string .= "\narrayValidationFields['winMasterDetail".$table['TABLE_NAME']."']['".$v['COLUMN_NAME']."']['field_type'] = \"email\"";
					} else {
						$string .= "\narrayValidationFields['winMasterDetail".$table['TABLE_NAME']."']['".$v['COLUMN_NAME']."']['field_type'] = \"".$v['DATA_TYPE']."\"";
					}	
					
				}

			}
			$string .= "\n";
		}		

		$string .= "\n</SCRIPT>";
		return $string;

    }
}