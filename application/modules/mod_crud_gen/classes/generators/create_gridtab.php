<?php


class create_gridtab {
	
	public static function output($param_gen, $gridTitle, $table_arr){
		//print'<pre>';print_r( $table_arr);die();
		$helper = new Helper();
		foreach($param_gen as $key => $value){
			${$key} = $value;
		}		
		 
		$nrRow = 0;		
        $column_field_begin ="";
        $column_field_end ="";
        $string = "\n\t\t\t<?php";

		$string .= "\n\t\t\tif(\$winFormType == \"form\"){";
		$string .= "\n\t\t\t\techo'";   	
		$string .= "\n\t\t\t\t			<br><a class=\"btn btn-primary\" style=\"cursor:pointer\" onclick=\"winFormMasterDetails(\'$c_url\',\'winMasterDetail".$table_arr['TABLE_NAME']."\',\'insert\','.\$id.',\'NULL\',\'NUOVO ".$gridTitle."\', arrayValidationFields,\'winMasterDetail".$table_arr['TABLE_NAME']."\',\'form\',\'getMasterDetail".$table_arr['TABLE_NAME']."\')\">[ Aggiungi un elemento]</a><br>";
		$string .= "\n\t\t\t\t			<br><br>';";
		$string .= "\n\t\t	} else {";
		$string .= "\n\t\t\t	echo'";   	
		$string .= "\n\t\t\t			<br><a class=\"btn btn-primary\" style=\"cursor:pointer\" onclick=\"winFormMasterDetails(\'$c_url\',\'winMasterDetailMulti".$table_arr['TABLE_NAME']."\',\'insert\','.\$id.',\'NULL\',\'NUOVO ".$gridTitle."\', arrayValidationFields,\'winMasterDetailMulti".$table_arr['TABLE_NAME']."\',\'multi\',\'getMasterDetail".$table_arr['TABLE_NAME']."\')\">[ Aggiungi un elemento]</a><br>";
		$string .= "\n\t\t\t			<br><br>';";
		$string .= "\n\t\t	}";		
	 
		 //
		//RICERCA MASTER DETAILS
		//
		
		//COUNT NUMERO COLONNE UTILE PER SETTARE LA RICERCA
		$nrTd = 1;//PARTO DA UNO PERCHE ESCLUDO I CHECK IN TABELLA
		foreach ($column_name_ms_details[$table_arr['TABLE_NAME']] as $key => $row) {
			if((isset($row["COLUMN_KEY"])) && ($row["COLUMN_KEY"] != "PRI")){		
				$nrTd++;
			}

		}



		$string .= "\n\t\techo' <input  type=\"text\" class=\"form-control\" autocomplete=\"off\" 
		id=\"search".$table_arr['TABLE_NAME']."\" style=\"width:20%\" placeholder=\"Cerca...\"
		onkeypress=\"disableKeySubmit()\"
		onkeyup=\"searchInMasterDetailsTable(\'search".$table_arr['TABLE_NAME']."\', \'tbl".$table_arr['TABLE_NAME']."\',$nrTd)\"><br>';";
	

		$string .= "\n\t\techo\"<table class='TFtable' id='tbl".$table_arr['TABLE_NAME']."' style='font-size:12px'>
		<tr>
			<thead>
			<th class='sorting_disabled' rowspan='1' colspan='1' aria-label='' style='width:10%;'>
				<input type='checkbox' id='check_master".$table_arr['TABLE_NAME']."' name='check_master".$table_arr['TABLE_NAME']."' 
				onchange=\\\"selezionaDeselezionaTutti('check_master".$table_arr['TABLE_NAME']."','check_id".$table_arr['TABLE_NAME']."','btDeleteMass".$table_arr['TABLE_NAME']."')\\\">
			</th>\";";

		foreach ($column_name_ms_details[$table_arr['TABLE_NAME']] as $key => $row) {
		if((isset($row["COLUMN_KEY"])) && ($row["COLUMN_KEY"] != "PRI")){
		//SETTO LA LABEL
		if ($row["COLUMN_COMMENT"] != "") {
			$etichettaLabel = $row["COLUMN_COMMENT"];
		} else {
			$etichettaLabel = $row["COLUMN_NAME"];
		}
		$string .= "\n\t\techo'<th>" . $helper->label($etichettaLabel) . "</th>';";					
		}

		}	
		$string .= "\n\t\tif(\$winFormType == \"form\"){";
		$string .= "\n\t\t\techo'<th>Modifica</th>';";	
		$string .= "\n\t\t}";	

		$string .= "\n\t\techo'<th>Elimina</th>';";	
		$string .= "\n\t\techo'</tr>';";		

		$string .= "\n\t\techo'<tbody>';";

		$string .= "\n\t\tforeach(\$row as \$key => \$value){";
		$string .= "\n\t\t\techo\"<tr>\";";
		$string .= "\n\t\t\techo\"<td><input type='checkbox' id='check_id".$table_arr['TABLE_NAME']."' name='check_id".$table_arr['TABLE_NAME']."' value='\".\$value['id'].\"' onchange=\\\"verificaNrCheckBoxSelezionati('check_id".$table_arr['TABLE_NAME']."','btDeleteMass".$table_arr['TABLE_NAME']."')\\\"></td>\";";
		foreach ($column_name_ms_details[$table_arr['TABLE_NAME']] as $key => $row) {

		if((isset($row["COLUMN_KEY"])) && ($row["COLUMN_KEY"] != "PRI")){
		if(isset($column_name_ms_details[$table_arr['TABLE_NAME']]['COLUMNS_REFERENCED'])){
			$isFound = FALSE;
			foreach($column_name_ms_details[$table_arr['TABLE_NAME']]['COLUMNS_REFERENCED'] as $kk => $vv){
				if($row["COLUMN_NAME"] == $vv['COLUMN_NAME']){					
					$string .= "\n\t\t\techo\"<td><input type='hidden' id='id[]' name='id[]' value='\".\$value['id'].\"'>\".\$value['".$vv['REFERENCED_TABLE_NAME']."_".$vv['REFERENCED_COLUMN_NAME_TO_SHOW']."'].\"</td>\";";						
					$isFound = TRUE;
				}
			}
			if($isFound == FALSE){
				if(($row["DATA_TYPE"] == 'TIME') || ($row["DATA_TYPE"] == 'time')){
					$string .= "\n\t\t\techo\"<td><input type='hidden' id='id[]' name='id[]' value='\".\$value['id'].\"'>\".substr(\$value['".$row["COLUMN_NAME"]."'],0,-3).\"</td>\";";	
				} else {
					$string .= "\n\t\t\techo\"<td><input type='hidden' id='id[]' name='id[]' value='\".\$value['id'].\"'>\".\$value['".$row["COLUMN_NAME"]."'].\"</td>\";";	
				}										
			}
		} else {
			if(($row["DATA_TYPE"] == 'TIME') || ($row["DATA_TYPE"] == 'time')){
				$string .= "\n\t\t\techo\"<td><input type='hidden' id='id[]' name='id[]' value='\".\$value['id'].\"'>\".substr(\$value['".$row["COLUMN_NAME"]."'],0,-3).\"</td>\";";	
			} else {
				$string .= "\n\t\t\techo\"<td><input type='hidden' id='id[]' name='id[]' value='\".\$value['id'].\"'>\".\$value['".$row["COLUMN_NAME"]."'].\"</td>\";";	
			}
														
		}
		}

		}			


		$string .= "\n\t\t\tif(\$winFormType == \"form\"){";
		$string .= "\n\t\t\t\techo\"<td><a style='cursor:pointer' class='btn btn-sm btn-info' onclick ='winFormMasterDetails(\\\"$c_url\\\",\\\"winMasterDetail".$table_arr['TABLE_NAME']."\\\",\\\"edit\\\", \$id,\".\$value['id'].\",\\\"MODIFICA ".$gridTitle."\\\",arrayValidationFields,\\\"winMasterDetail".$table_arr['TABLE_NAME']."\\\",\\\"form\\\",\\\"getMasterDetail".$table_arr['TABLE_NAME']."\\\")' title='Modifica ".$gridTitle."'><i class='fa fa-edit'></a></td>\";";	
		$string .= "\n\t\t\t}";	
		$string .= "\n\t\t\techo\"<td><a style='cursor:pointer' class='btn btn-sm btn-danger deleteUser' onclick ='deleteMasterDetails(\".\$value['id'].\", \".\$id.\", \\\"$c_url\\\",\\\"".$table_arr['TABLE_NAME']."\\\",\\\"getMasterDetail".$table_arr['TABLE_NAME']."\\\")' title='Elimina'><i class='fa fa-trash'></a></td>\";";
		$string .= "\n\t\t\techo'</tr>';";
		//$string .= "\n\t\t\t}";	

		$string .= "\n\t\t}";
		$string .= "\n\t\techo'</tbody></table>';";


		$string .= "\n\t\techo'<br/><a class=\"btn btn-sm btn-danger deleteUser\" id=\"btDeleteMass".$table_arr['TABLE_NAME']."\" name=\"btDeleteMass".$table_arr['TABLE_NAME']."\"\""; 
		$string .= "\n\t\t			onclick=\"deleteMassiveMasterDetails('.\$id.',\'entry_list\',\'check_id".$table_arr['TABLE_NAME']."\',\'$c_url\',\'".$table_arr['TABLE_NAME']."\',\'getMasterDetail".$table_arr['TABLE_NAME']."\')\">";
		$string .= "\n\t\t			<i class=\"fa fa-trash\"></i> Cancellazione Massiva";
		$string .= "\n\t\t		</a>';";


 
		
		return $helper->createFile($string, APPPATH."modules/" . $param_gen['c_url'] . "/views/partials/gridtab/grd_" . $table_arr['TABLE_NAME'].".php");	
	}

}
