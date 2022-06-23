<?php


class create_js_datatable_config extends AbstractGenerator{
	
	public static function output($param_gen){
		
		$helper = new Helper();
		foreach($param_gen as $key => $value){
			${$key} = $value;
		}

		$string = "<?php /*FILE CONFIGURAZIONE DATATABLE*/ ?>";
		$string .= "\n\n<SCRIPT>";
		$string .= "\n\nobjAjaxConfig.mod_name = '$c_url';";
		$string .= "\nobjAjaxConfig.mod_title = '$mod_title';";				
		$string .= "\nvar columnArray = [];";
				
		$arrayOrdAll = array();
		foreach ($column_name as $keyReq =>$rowReq) {
			foreach ($all as $row) {
				if($row['COLUMN_NAME'] == $rowReq){
					$arrayOrdAll[] = $row;
				}
			}
		}			
		
		//$colNrJsArray = 0;	
		$colNrJsArray = 1;	
		if($mod_type == "crud"){
			$colNrJsArray = 1;	
		}	
			
		foreach ($arrayOrdAll as $row) {
			//print'<pre>';print_r($row);
			//MOSTRO LA COLONNA SOLO SE SELEZIONATA
			switch($row["DATA_TYPE"]){
				case 'blob':
				case 'tinyblob':
				case 'mediumblob':
				case 'longblob':
					$string .= "\ncolumnArray[".$colNrJsArray."] = {type:\"blob\", name:\"".$row['TABLE_NAME']."_".$row['COLUMN_NAME']."\"};	";	
				break;
				
				case 'enum':
					$string .= "\ncolumnArray[".$colNrJsArray."] = {type:\"select\", name:\"".$row['TABLE_NAME']."_".$row['COLUMN_NAME']."\",";			
						$COLUMN_TYPE =  str_replace("enum(","",$row["COLUMN_TYPE"]);
						$COLUMN_TYPE = substr($COLUMN_TYPE, 0, -1);
						$arrayColumnType = explode(",",$COLUMN_TYPE);
						$string .= "items:[";
						$countItems = 0;
						foreach($arrayColumnType as $key => $value){
							if($countItems > 0){
								$string .=","; 
							}
							$string .= $value;
							$countItems++;
						}
						$string .= "]";
						$string .= "};";
				break;	

				case 'set':
					$string .= "\ncolumnArray[".$colNrJsArray."] = {type:\"select\", name:\"".$row['TABLE_NAME']."_".$row['COLUMN_NAME']."\",";			
					$COLUMN_TYPE =  str_replace("set(","",$row["COLUMN_TYPE"]);
					$COLUMN_TYPE = substr($COLUMN_TYPE, 0, -1);
					$arrayColumnType = explode(",",$COLUMN_TYPE);
					$string .= "items:[";
					$countItems = 0;
					foreach($arrayColumnType as $key => $value){
						if($countItems > 0){
							$string .=","; 
						}
						$string .= $value;
						$countItems++;
					}
					$string .= "]";
					$string .= "};";
				break;
				
				case 'tinytext':
				case 'mediumtext':
				case 'longtext':
				case 'text':
				case 'char':
				case 'varchar':
					$string .= "\ncolumnArray[".$colNrJsArray."] = {type:\"text\", name:\"".$row['TABLE_NAME']."_".$row['COLUMN_NAME']."\"};	";
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
						//$string .= "\ncolumnArray[".$colNrJsArray."] = {type:\"text\", name:\"".$arrayRefFields[$row['COLUMN_NAME']]['REFERENCED_TABLE_NAME']."_".$arrayRefFields[$row['COLUMN_NAME']]['REFERENCED_COLUMN_NAME']."\"};	";
						$string .= "\ncolumnArray[".$colNrJsArray."] = {type:\"text\", name:\"".$arrayRefFields[$row['COLUMN_NAME']]['REFERENCED_TABLE_NAME']."_nome\"};	";
					} else {
						$string .= "\ncolumnArray[".$colNrJsArray."] = {type:\"number\", name:\"".$row['TABLE_NAME']."_".$row['COLUMN_NAME']."\"};	";
				 	}
					
				break;
				
				
				case 'date':
					$string .= "\ncolumnArray[".$colNrJsArray."] = {type:\"date\", name:\"".$row['TABLE_NAME']."_".$row['COLUMN_NAME']."\"};	";		
				break;
				
				case 'datetime':
				case 'timestamp':
				case 'time':
					$string .= "\ncolumnArray[".$colNrJsArray."] = {type:\"datetime\", name:\"".$row['TABLE_NAME']."_".$row['COLUMN_NAME']."\"};	";
				break;
				
				
				default:
					$string .= "columnArray[".$colNrJsArray."] = {type:\"text\", name:\"".$row['TABLE_NAME']."_".$row['COLUMN_NAME']."\"};	";
				break;
			}
			
			$colNrJsArray++;
			
		}		

					
		$string .="\nvar columnGrid = [";
		$string .="\n{\"data\": \"ids\",\"orderable\": false},";
		foreach ($arrayOrdAll as $row) {
			if(array_key_exists($row['COLUMN_NAME'],$arrayRefFields)){
				//$string .="\n{\"data\": \"".$arrayRefFields[$row['COLUMN_NAME']]['REFERENCED_TABLE_NAME']."_".$arrayRefFields[$row['COLUMN_NAME']]['REFERENCED_COLUMN_NAME']."\"},"; 
				$string .="\n{\"data\": \"".$arrayRefFields[$row['COLUMN_NAME']]['REFERENCED_TABLE_NAME']."_nome\"},"; 
			} else {
				$string .="\n{\"data\": \"".$row['TABLE_NAME']."_".$row['COLUMN_NAME']."\"},";
			 }
			
		}	
		$string .="\n{\"data\": \"action\",\"orderable\": false,\"className\": \"text-center\"}";
 
		$string .="\n];";
		$string .="\nif(request_js_id != ''){";
		$string .="\n\teditAjax('".$c_url."',request_js_id);";
		$string .="\n}";
		$string .= "\n\n</SCRIPT>";
		return $string;

    }
}