<?php

class create_install_file extends AbstractGenerator{
	
	public static function output($param_gen){
		//print'<pre>';print_r($param_gen);
		$helper = new Helper();
		foreach($param_gen as $key => $value){
			${$key} = $value;
		}
		  
		 
		$Mod_crud_gen_model = new Mod_crud_gen_model();
		$sqlModId = "SELECT MAX(mod_id)as mod_id FROM core_module_list";
		$rowModId = $Mod_crud_gen_model->execSQL($sqlModId);
		
		$mod_id = (int)$rowModId[0]['mod_id'] + 1;

		$sqlPosition = "SELECT MAX(position) AS position FROM core_module_list";
		$rowPosition = $Mod_crud_gen_model->execSQL($sqlPosition);
		$newPosition = (int)$rowPosition[0]['position'] + 1; 


		$fields = $all;

		$mod_icon = "fa fa-cubes";
		if(isset($mod_icon)){
			$mod_icon = $mod_icon;	
		}

		
		if(!isset($mod_title)){
			$mod_title = "Modulo ".$c_url;	
		}

		$mod_parentid = 0;
		if(isset($mod_gen_aggr)){
			$mod_parentid = $mod_gen_aggr;	
		}


		$sql = "DROP TABLE IF EXISTS $table_name;";
		$sql .= "\nCREATE TABLE IF NOT EXISTS $table_name (\n";

		$fieldsPk = array();
		$arrayPGFieldWithComment = array();
		foreach($fields as $key => $field){
			$lenght = "0";
			if($field['CHARACTER_MAXIMUM_LENGTH'] > 0 ){
				$lenght = $field['CHARACTER_MAXIMUM_LENGTH'];	
			} else {
				$lenght = $field['NUMERIC_PRECISION'];	
				if($field['NUMERIC_SCALE'] > 0){
					$lenght.= ",".$field['NUMERIC_SCALE'];	
				}
			}
			
			switch($dbdriver){
				case 'mysql':
				case 'mysqli':	
			
					if($lenght != ""){
						$sql .= " `".$field['COLUMN_NAME']."` ".$field['DATA_TYPE']."(".$lenght.")";	
					} else {
						$sql .= " `".$field['COLUMN_NAME']."` ".$field['DATA_TYPE'];
					}
				break;
				
				case 'postgre':	
					if($lenght != ""){
						$sql .= " ".$field['COLUMN_NAME']." ".$field['DATA_TYPE']."(".$lenght.")";	
					} else {
						$sql .= " ".$field['COLUMN_NAME']." ".$field['DATA_TYPE'];
					}
				break;		
				
				case 'mssql':
				
				break;				
			}

			
			if(trim(strtoupper($field['IS_NULLABLE'])) == 'NO'){
				$sql .= " NOT NULL";	
			} else {
				$sql .= " NULL";
			}
			
			switch($dbdriver){
				case 'mysql':
				case 'mysqli':		
					if(trim($field['COLUMN_COMMENT']) != ''){
						$sql .= " COMMENT '".$field['COLUMN_COMMENT']."'";	
					}		
					
					if(trim(strtoupper($field['EXTRA'])) == 'AUTO_INCREMENT'){
						$sql .= " ".$field['EXTRA'];	
					}			
				break;
				
				case 'postgre':
					if(trim($field['column_comment']) != ''){
						$arrayPGFieldWithComment[] = array('table_name' => $table_name, 
															'COLUMN_NAME' => $field['COLUMN_NAME'], 
															'column_comment' => $field['column_comment']);
					}			
				break;
				
				case 'mssql':
				
				break;			
			}			
			
			$sql .= ",\n";

			if(trim(strtoupper($field['COLUMN_KEY'])) == 'PRI'){
				$fieldsPk[] = $field['COLUMN_NAME'];
			}		
			
		}


		switch($dbdriver){
			case 'mysql':
			case 'mysqli':
				$sql .= "PRIMARY KEY (".implode(',', array_map('self::add_quotes', $fieldsPk)).")";
				$sql .= "\n) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;";
			break;	
			
			case 'postgre':
				$sql .= "CONSTRAINT ".$table_name."_pkey PRIMARY KEY (".implode(',', array_map('self::add_quotes_pg', $fieldsPk)).")";
				$sql .= "\n) WITH (
				  OIDS=FALSE
				);
				ALTER TABLE $table_name OWNER TO postgres;";
			break;
			
			case 'mssql':
			
			break;			
		}	


		$sql .= "	
			\nDELETE FROM core_module_list WHERE mod_name = '".$c_url."';
			\nDELETE FROM core_roles_permission WHERE mod_name = '".$c_url."';
			\nINSERT INTO core_module_list(mod_id,mod_name, class_name, mod_type, mod_title, mod_icon,mod_parentid, active, is_deletable,is_moveable,position,admin_perm_only)
				VALUES($mod_id,'".$c_url."','".$c_url."','mod_gen','$mod_title','fa $mod_icon',$mod_parentid,'Y','Y','Y',$newPosition,'N');";
		$sql .= "\nINSERT INTO core_roles_permission(role_id,mod_id,mod_name,perm_read,perm_write,perm_update,perm_delete)
					VALUES(1,$mod_id,'".$c_url."','Y','Y','Y','Y')";		
	
		$myfile = fopen($target ."/install.txt", "w") or die("Unable to open file!");
		fwrite($myfile, $sql);
		fclose($myfile);	

		//ESEGUO LE QUERY DI INSTALLAZIONE ANCHE SUL MIO AMBIENTE DI SVILUPPO
		$Mod_crud_gen_model->execSQL("DELETE FROM core_module_list WHERE mod_name = '".$c_url."'", TRUE);
		$Mod_crud_gen_model->execSQL("DELETE FROM core_roles_permission WHERE mod_name = '".$c_url."'", TRUE);
		$Mod_crud_gen_model->execSQL("INSERT INTO core_module_list(mod_id,mod_name, class_name, mod_type, mod_title, mod_icon,mod_parentid, active, is_deletable,is_moveable,position,admin_perm_only)
				VALUES($mod_id,'".$c_url."','".$c_url."','mod_gen','$mod_title','fa $mod_icon',$mod_parentid,'Y','Y','Y',$newPosition,'N')", TRUE);
		$Mod_crud_gen_model->execSQL("INSERT INTO core_roles_permission(role_id,mod_id,mod_name,perm_read,perm_write,perm_update,perm_delete)
					VALUES(1,$mod_id,'".$c_url."','Y','Y','Y','Y')", TRUE);			
	
		return $sql;
	}


	private static function add_quotes($str) {
		return sprintf("`%s`", $str);
	}
	
	private static function add_quotes_pg($str) {
		return sprintf("'%s'", $str);
	}	
	

}	



			
