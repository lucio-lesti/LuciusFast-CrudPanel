<?php 

class create_model extends AbstractGenerator{
	
	public static function output($param_gen){
		
		$helper = new Helper();
		foreach($param_gen as $key => $value){
			${$key} = $value;
		}		

		$string = "<?php";
		$string .= "\nif (!defined('BASEPATH')){";
		$string .= "\n\texit('No direct script access allowed');";
		$string .= "\n}";
		$string .= "\n";
		$string .= "\nrequire APPPATH . '/libraries/BaseModel.php';";
		$string .= "\n";
		$string .= "\nclass " . $m . " extends BaseModel";
		$string .= "\n{";
		$string .= "\n";	
		$string .= "\n\tpublic function __construct(){";
		$string .= "\n\t\tparent::__construct();";
		$string .= "\n\t\t\$this->table = '$table_name';";
		$string .= "\n\t\t\$this->id = '$pk';";
		$string .= "\n\t\t\$this->mod_name = '$c_url';";	
		$string .= "\n\t\t\$this->mod_type = '$mod_type';";	
		$string .= "\n";		
		
		/*
		//DISATTIVO LA SCRITTURA DEL METODO "setFieldArraySearch"
		foreach ($non_pk as $row) {
			switch($row["DATA_TYPE"]){
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
						$string .= "\n\t\t\$this->setFieldArraySearch('".$row['COLUMN_NAME']."',FIELD_NUMERIC,";
						$string .= "'".$arrayRefFields[$row['COLUMN_NAME']]['REFERENCED_TABLE_NAME']."',";
						//$string .= "array(\"id\" => '".$arrayRefFields[$row['COLUMN_NAME']]['REFERENCED_COLUMN_NAME']."', \"nome\" => '".$arrayRefFields[$row['COLUMN_NAME']]['REFERENCED_COLUMN_NAME']."'));";
						$string .= "array(\"id\" => 'id', \"nome\" => 'nome'));";
				 	} else {
					  $string .= "\n\t\t\$this->setFieldArraySearch('".$row['COLUMN_NAME']."', FIELD_NUMERIC);";
				 	}
				break;
			
				case 'tinytext':
				case 'mediumtext':
				case 'longtext':
				case 'text':
				case 'char':
				case 'varchar':	
				case 'enum':	
				case 'set':	
					if(array_key_exists($row['COLUMN_NAME'],$arrayRefFields)){
						$string .= "\n\t\t\$this->setFieldArraySearch('".$row['COLUMN_NAME']."',FIELD_STRING,";
						$string .= "'".$arrayRefFields[$row['COLUMN_NAME']]['REFERENCED_TABLE_NAME']."',";
						//$string .= "array(\"id\" => '".$arrayRefFields[$row['COLUMN_NAME']]['REFERENCED_COLUMN_NAME']."', \"nome\" => '".$arrayRefFields[$row['COLUMN_NAME']]['REFERENCED_COLUMN_NAME']."'));";
						$string .= "array(\"id\" => 'id', \"nome\" => 'nome'));";
				 	} else {
						$string .= "\n\t\t\$this->setFieldArraySearch('".$row['COLUMN_NAME']."', FIELD_STRING);";
				 	}								
					
				break;
				
				case 'blob':
				case 'tinyblob':
				case 'mediumblob':			
				case 'longblob':	
				case 'binary':
				case 'varbinary':		
					$string .= "\n\t\t\$this->setFieldArraySearch('".$row['COLUMN_NAME']."', FIELD_BLOB);";
				break;					
			
			}				
		}				
		$string .= "\n\t\t\$this->setFieldArraySearch('".$pk."', FIELD_NUMERIC);";
		*/

		$string .= "\n\n\t\t//NOTE:NELLA FUNZIONE 'setFieldArrayGrid' INDICARE NEL VETTORE CHE SI COLLEGA ALLA TABELLA REFERENZIATA";
		$string .= "\n\t\t//ALLA CHIAVE 'NOME', IL NOMINATIVO DEL CAMPO COLLEGATO";
		$string .= "\n\n\t\t//NOTE 2: NELLA FUNZIONE 'setFieldArrayGrid' se nella chiave \"nome\" si usa una array, la classe \"BaseModel\" lo interpreta come un concat";	
		$string .= "\n";
		
		foreach ($non_pk as $row) {
			switch($row["DATA_TYPE"]){
				case 'tinyint':
				case 'smallint':
				case 'mediumint':
				case 'bigint':
				case 'int':
				case 'serial':
					if(array_key_exists($row['COLUMN_NAME'],$arrayRefFields)){
						$string .= "\n\t\t\$this->setFieldArrayGrid('".$row['COLUMN_NAME']."',FIELD_NUMERIC,";
						$string .= "'".$arrayRefFields[$row['COLUMN_NAME']]['REFERENCED_TABLE_NAME']."',";
						//$string .= "array(\"id\" => '".$arrayRefFields[$row['COLUMN_NAME']]['REFERENCED_COLUMN_NAME']."', \"nome\" => '".$arrayRefFields[$row['COLUMN_NAME']]['REFERENCED_COLUMN_NAME']."'));";
						$string .= "array(\"id\" => 'id', \"nome\" => 'nome'));";
				 	} else {
					  $string .= "\n\t\t\$this->setFieldArrayGrid('".$row['COLUMN_NAME']."', FIELD_NUMERIC);";
				 	}					
				break;
			

				case 'decimal':
				case 'float':
				case 'double':
				case 'real':
					$string .= "\n\t\t\$this->setFieldArrayGrid('".$row['COLUMN_NAME']."', FIELD_FLOAT);";
				break;		
				
				
				case 'date':
					$string .= "\n\t\t\$this->setFieldArrayGrid('".$row['COLUMN_NAME']."', FIELD_DATE);";
				break;	

				case 'time':
					$string .= "\n\t\t\$this->setFieldArrayGrid('".$row['COLUMN_NAME']."', FIELD_STRING);";
				break;

				case 'datetime':
				case 'timestamp':
				//case 'time':
					$string .= "\n\t\t\$this->setFieldArrayGrid('".$row['COLUMN_NAME']."', FIELD_DATETIME);";
				break;

				case 'tinytext':
				case 'mediumtext':
				case 'longtext':
				case 'text':
				case 'char':
				case 'varchar':			
				case 'enum':	
				case 'set':	
					if(array_key_exists($row['COLUMN_NAME'],$arrayRefFields)){
						$string .= "\n\t\t\$this->setFieldArrayGrid('".$row['COLUMN_NAME']."',FIELD_STRING,";
						$string .= "'".$arrayRefFields[$row['COLUMN_NAME']]['REFERENCED_TABLE_NAME']."',";
						$string .= "array(\"id\" => 'id', \"nome\" => 'nome'));";
				 	} else {
					  $string .= "\n\t\t\$this->setFieldArrayGrid('".$row['COLUMN_NAME']."', FIELD_STRING);";
				 	}	
				break;
				
				case 'blob':
				case 'tinyblob':
				case 'mediumblob':			
				case 'longblob':	
				case 'binary':
				case 'varbinary':	
					$firstChars = substr($row['COLUMN_NAME'],0,3);
					if($firstChars == 'img'){
						$string .= "\n\t\t\$this->setFieldArrayGrid('".$row['COLUMN_NAME']."', FIELD_BLOB_IMG);";
					} else {
						$string .= "\n\t\t\$this->setFieldArrayGrid('".$row['COLUMN_NAME']."', FIELD_BLOB);";
					}
					
				break;	
			
			}				
		}			
		$string .= "\n\t\t\$this->setFieldArrayGrid('".$pk."', FIELD_NUMERIC);";
		$string .= "\n\n\t\t//ESEMPIO DI TABELLA REFERENZIATA CHE NON HA IL CAMPO 'NOME'. QUI INDICHIAMO AL PROGRAMMA QUALE E' IL CAMPO DA USARE COME CAMPO 'NOME'";
		$string .= "\n\t\t//P.S.QUESTA OPERAZIONE E' POSSIBILE FARLA ANCHE NEL METODO 'setFieldArrayGrid'";
		$string .= "\n\t\t//\$this->arrayColumnsReferenced['mod_sport']['nome'] = \"sport\"; ";

		//CHIUDO LA GRAFFA DEL METODO COSTRUTTORE
		$string .= "\n\n\t}";

		$string .= "\n\n";
		//METODI QUERY MASTER DETAILS
		foreach($arrayRefTables as $k => $table){	
			//print'<pre>';print_r($table);
			$string .= "\n\t/**";
			$string .= "\n\t* Funzione caricamento della master details, tabella ".$table['TABLE_NAME'];
			$string .= "\n\t* @param mixed \$id";
			$string .= "\n\t* @param string \$isAjax";
			$string .= "\n\t* @return string";
			$string .= "\n\t**/";					
			$string .= "\n\tpublic function getMasterDetail".$table['TABLE_NAME']."(\$id, \$isAjax = 'FALSE'){";	

			//$string .= "\n\t\t\$sql =\"SELECT ".$table['TABLE_NAME'].".id";
			$string .= "\n\t\t\$sql =\"SELECT ".$table['TABLE_NAME'].".* ";
			foreach($table['COLUMNS_REFERENCED'] as $k => $v){
				$string .= "\n\t\t\t\t,".$v['REFERENCED_TABLE_NAME'].".".$v['REFERENCED_COLUMN_NAME_TO_SHOW'] ." AS ".$v['REFERENCED_TABLE_NAME']."_".$v['REFERENCED_COLUMN_NAME_TO_SHOW'];	
			}
			$string .= "\n\t\t	FROM ".$table['TABLE_NAME'];
			foreach($table['COLUMNS_REFERENCED'] as $k => $v){
				$string .= "\n\t\t\t INNER JOIN ".$v['REFERENCED_TABLE_NAME']."
							ON ".$v['TABLE_NAME'].".".$v['COLUMN_NAME'] ." = ".$v['REFERENCED_TABLE_NAME'].".".$v['REFERENCED_COLUMN_NAME'];	
			}				
			//$string .= "\n\t\t			WHERE ".$table['COLUMN_NAME']." = \".\$id;";				
			$string .= "\n\t\t	WHERE ".$table['TABLE_NAME'].".".$table['COLUMN_NAME']." = \".\$id;";
			
			$string .= "\n\t\t\$row =  \$this->db->query(\$sql)->result_array();	";
			$string .= "\n\t\treturn \$row;";			
			
			$string .= "\n\t}";	
			$string .= "\n\n";
		}
		//METODI QUERY MASTER DETAILS - FINE


		//METODI QUERY MASTER DETAILS - LISTE PER INSERIMENTO MULTIPLO
		foreach($arrayRefTables as $k => $table){		
			//print'<pre>';print_r($table);	
			$string .= "\n\t/**";
			$string .= "\n\t* Funzione caricamento delle liste per inserimento multiplo master details, tabella ".$table['TABLE_NAME'];
			$string .= "\n\t* @param mixed \$id";
			$string .= "\n\t* @param string \$isAjax";
			$string .= "\n\t* @return string";
			$string .= "\n\t**/";				
			$string .= "\n\tpublic function getList".$table['TABLE_NAME']."(\$id, \$isAjax = 'FALSE'){";	
			
			//$string .= "\n\t\t\$sql =\"SELECT ".$table['TABLE_NAME'].".id";
			$string .= "\n\t\t\$sql =\"SELECT ".$table['TABLE_NAME'].".* ";
			foreach($table['COLUMNS_REFERENCED'] as $k => $v){
				$string .= "\n\t\t\t,".$v['REFERENCED_TABLE_NAME'].".".$v['REFERENCED_COLUMN_NAME_TO_SHOW'] ." AS ".$v['REFERENCED_TABLE_NAME']."_".$v['REFERENCED_COLUMN_NAME_TO_SHOW'];	
			}			
			$string .= "\n\t\t			FROM ".$table['TABLE_NAME'];
			foreach($table['COLUMNS_REFERENCED'] as $k => $v){
				$string .= "\n\t\t\t\t INNER JOIN ".$v['REFERENCED_TABLE_NAME']."
							ON ".$v['TABLE_NAME'].".".$v['COLUMN_NAME'] ." = ".$v['REFERENCED_TABLE_NAME'].".".$v['REFERENCED_COLUMN_NAME'];	
			}					
			//$string .= "\n\t\t			WHERE ".$table['COLUMN_NAME']." = \".\$id;";				
			$string .= "\n\t\t	WHERE ".$table['TABLE_NAME'].".".$table['COLUMN_NAME']." = \".\$id;";

			$string .= "\n\t\t\$row =  \$this->db->query(\$sql)->result_array();	";
			$string .= "\n\t\treturn \$row;";			
			$string .= "\n\t}";	
			$string .= "\n\n";
		}
		//METODI QUERY MASTER DETAILS - LISTE PER INSERIMENTO MULTIPLO - FINE
		
		
		//CHIUDO LA GRAFFA DELLA CLASSE
		$string .= "\n\n}";

		return $string;
	}

}	


?>