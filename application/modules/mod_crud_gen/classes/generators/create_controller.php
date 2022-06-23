<?php
include 'create_win_form.php';
include 'create_win_form_multi.php';
include 'create_gridtab.php';
class create_controller extends AbstractGenerator{
		
	public static function output($param_gen){		
		$helper = new Helper();
		foreach($param_gen as $key => $value){
			${$key} = $value;
		}
 
		$string = "<?php";
		$string .= "\nif (!defined('BASEPATH')){";
		$string .= "\n\texit('No direct script access allowed');";
		$string .= "\n}";	
		$string .= "\nrequire APPPATH . '/libraries/BaseController.php';";
		$string .= "\nuse Dompdf\Dompdf;";
		$string .= "\n";
		$string .= "\nclass " . $c . " extends BaseController";
		$string .= "\n{";
		$string .= "\n";

		//METODO COSTRUTTORE
		$string .= "\n\tpublic function __construct()";
		$string .= "\n\t{";
		$string .= "\n\t\tparent::__construct();";
		$string .= "\n\t\t\$this->load->model('$m');";
		$string .= "\n\t\t\$this->mod_name = '$c_url';";
		$string .= "\n\t\t\$this->mod_type = '$mod_type';";	
		$string .= "\n\t\t\$this->mod_title = '$mod_title';";
		$string .= "\n\t\t\$this->modelClassModule =  \$this->$m;";
		$string .= "\n\t\t\$this->pkIdName = '$pk';";
		$string .= "\n\t\t\$this->viewName_ListAjax = '$v_list_ajax';";
		$string .= "\n\t\t\$this->viewName_FormROAjax = '$v_read_ajax';";
		$string .= "\n\t\t\$this->viewName_FormAjax = '$v_form_ajax';";
	    $string .= "\n";

		$string .= "\n\t\t\$this->MsgDBConverted['insert']['error']['1062'] = \"Esiste gia questo elemento per il modulo ".$mod_title."\";"; 
		$string .= "\n\t\t\$this->MsgDBConverted['insert']['error']['1452'] = \"Esiste gia questo elemento per il modulo ".$mod_title."\";";
		$string .= "\n\t\t\$this->MsgDBConverted['update']['error']['1062'] = \"Esiste gia questo elemento per il modulo ".$mod_title."\";"; 
		$string .= "\n\t\t\$this->MsgDBConverted['update']['error']['1452'] = \"Esiste gia questo elemento per il modulo ".$mod_title."\";"; 
		$string .= "\n\t\t\$this->MsgDBConverted['insert_massive']['error']['1062'] = \"Esiste gia questo elemento per il modulo ".$mod_title."\";"; 
		$string .= "\n\t\t\$this->MsgDBConverted['insert_massive']['error']['1452'] = \"Esiste gia questo elemento per il modulo ".$mod_title."\";"; 
		$string .= "\n\t\t\$this->MsgDBConverted['update_massive']['error']['1062'] = \"Esiste gia questo elemento per il modulo ".$mod_title."\";"; 
		$string .= "\n\t\t\$this->MsgDBConverted['update_massive']['error']['1452'] = \"Esiste gia questo elemento per il modulo ".$mod_title."\";"; 
		$string .= "\n\t\t\$this->MsgDBConverted['delete']['error']['1217'] = \"Impossibile eliminare questo elemento del modulo ".$mod_title.". E' usato nei seguenti moduli:\";"; 
		$string .= "\n\t\t\$this->MsgDBConverted['delete_massive']['error']['1217'] = \"Impossibile eliminare alcuni elementi del modulo ".$mod_title.". Sono usati nei seguenti moduli:\";"; 


		$string .= "\n\n\t\t//NOTE:NELLA FUNZIONE 'setFormFields' INDICARE NEL VETTORE CHE SI COLLEGA ALLA TABELLA REFERENZIATA";
		$string .= "\n\t\t//ALLA CHIAVE 'NOME', IL NOMINATIVO DEL CAMPO COLLEGATO";		

		foreach ($non_pk as $row) {
			if(array_key_exists($row['COLUMN_NAME'],$arrayRefFields)){
				$string .= "\n\t\t//PER L'ARRAY DI REFERENZIAMENTO, USARE IL CONCAT PER CONCATENARE PIU CAMPI NEL CAMPO 'NOME'";
				$string .= "\n\t\t//ES.CONCAT(cognome,\" \",nome)";
		 	  	$string .= "\n\t\t\$this->setFormFields('".$row['COLUMN_NAME']."'";
			  	$string .= ",'".$arrayRefFields[$row['COLUMN_NAME']]['REFERENCED_TABLE_NAME']."'";
			 	//$string .= ",array(\"id\" => '".$arrayRefFields[$row['COLUMN_NAME']]['REFERENCED_COLUMN_NAME']."', \"nome\" => '".$arrayRefFields[$row['COLUMN_NAME']]['REFERENCED_COLUMN_NAME']."'));";
				$string .= ",array(\"id\" => 'id', \"nome\" => 'nome'));";
			} else {
			 	$string .= "\n\t\t\$this->setFormFields('".$row['COLUMN_NAME']."');";	
			}
		}

		//SETTO LA CHIAVE PRIMARIA NEL FORM
		$string .= "\n\t\t\$this->setFormFields('".$pk."');";	


		$string .= "\n";
		foreach($arrayRefTables as $k => $table){
			$titleTable = self::getTableTitle($tablesTitle, $table['TABLE_NAME']); 
			$string .= "\n\t\t\$this->addMasterDetailsLoadFunc('getMasterDetail".$table['TABLE_NAME']."','".$titleTable."','getMasterDetail".$table['TABLE_NAME']."');";	
		}

		$string .= "\n\n\t\t//ABILITARE PER LE OPERAZIONI \"CUSTOM\"";
		$string .= "\n\n\t\t//LA CHIAVE DEL VETTORE \"custom_operations_list\" RAPPRESENTA IL NOME DELLA FUNZIONE";
		$string .= "\n\t\t//QUESTO PER AVERE UN CODICE ORDINATO E PER EVITARE CHE LE FUNZIONI CUSTOM NON VENGANO RICHIAMATE CORRETTAMENTE";
		$string .= "\n\t\t/*\$this->custom_operations_list['".$c_url."_check_date'] = function(\$request, \$id = NULL){
			\$ret = \$this->utilities->check_date_greater_then(\$request['data_da'], \$request['data_a']);
			if(\$ret === FALSE){
				\$this->session->set_flashdata('error',\"Data Da non puo essere maggiore di Data a\");
				return false;
			}				
		};*/";
		
		$string .= "\n";

		$string .= "\n\t}";
		$string .= "\n\n";
		//METODO COSTRUTTORE - fine



		//METODI MASTER DETAILS
		$countTab = 0;
		foreach($arrayRefTables as $k => $table){
			$string .= "\n\t/**";
			$string .= "\n\t* Funzione caricamento della master details, tabella ".$table['TABLE_NAME'];
			$string .= "\n\t* @param mixed \$id";
			$string .= "\n\t* @param string \$isAjax";
			$string .= "\n\t* @return string";
			$string .= "\n\t**/";			
			$string .= "\n\tpublic function getMasterDetail".$table['TABLE_NAME']."(\$id, \$isAjax = 'FALSE'){";	
			$string .= "\n\t\t\$html = '';";
			$string .= "\n\t\t\$winFormType =\"form\";//VALORI ACCETTATI: {'multi','form'}";
			$string .= "\n\t";
			$string .= "\n\t\t\$row =  \$this->modelClassModule->getMasterDetail".$table['TABLE_NAME']."(\$id, \$isAjax);";
			$string .= "\n\t\t\$data['id'] = \$id;";
			$string .= "\n\t\t\$data['isAjax'] = \$isAjax;";
			$string .= "\n\t\t\$data['row'] = \$row;";
			$string .= "\n\t\t\$data['winFormType'] = \$winFormType;";
			
			//print'<pre>';print_r($table);die();
			create_gridtab::output($param_gen, self::getTableTitle($tablesTitle, $table['TABLE_NAME']), $table);
			
			$string .= "\n\t\t\$html = \$this->load->view('partials/gridtab/grd_".$table['TABLE_NAME'].".php', \$data, TRUE);";
 	
			$string .= "\n\t\treturn \$html;";

			$string .= "\n\t}";	
			$string .= "\n\n";

			$countTab++;
		}
		
		//METODI MASTER DETAILS - FINE



		//METODI WINFORM MASTER DETAILS
		foreach($arrayRefTables as $k => $table){	
			$string .= "\n\t/**";
			$string .= "\n\t* Funzione caricamento della finestra per la master details, tabella ".$table['TABLE_NAME'];
			$string .= "\n\t* @param mixed \$action";
			$string .= "\n\t* @param string \$entryID";
			$string .= "\n\t* @param string \$entryIDMasterDetails";
			$string .= "\n\t* @return string";
			$string .= "\n\t**/";				
			$string .= "\n\tpublic function winMasterDetail".$table['TABLE_NAME']."(\$action, \$entryID, \$entryIDMasterDetails = NULL){";	
			$string .= "\n\t\tif(\$entryIDMasterDetails == 'NULL'){";
			$string .= "\n\t\t\t\$entryIDMasterDetails = '';";
			$string .= "\n\t\t}";			
			
			$string .= "\n\t\t\$data['action'] = \$action;";
			$string .= "\n\t\t\$data['rowWinForm'] =  \$this->modelClassModule->get_from_master_details_by_id(\$entryIDMasterDetails, '".$table['TABLE_NAME']."', 'id');";	
			$string .= "\n\t\t\$data['entryID'] = \$entryID;";
			$string .= "\n\t\t\$data['entryIDMasterDetails'] = \$entryIDMasterDetails;";	
			$arrayField = $Mod_crud_gen_model->getColumnsTable($table['TABLE_NAME']);	
			$arrayRefFields = $Mod_crud_gen_model->getFieldsRef($table['TABLE_NAME']);
			foreach ($arrayField as $k=> $row) {
				if($row["COLUMN_KEY"] != "PRI"){
					switch($row["DATA_TYPE"]){
							
						case 'tinyint':
						case 'smallint':
						case 'mediumint':
						case 'bigint':
						case 'int':
						case 'serial':
							if(array_key_exists($row['COLUMN_NAME'],$arrayRefFields)){
								$string .="\n\t\t\$data['".$row['COLUMN_NAME']."_refval'] = \$this->modelClassModule->getValuesByFk('".$arrayRefFields[$row['COLUMN_NAME']]['REFERENCED_TABLE_NAME']."',NULL, NULL);";
							}
						break;
					}
				}
			}     
	
			create_win_form::output($Mod_crud_gen_model->getFieldsRef($table['TABLE_NAME']), $arrayField , $param_gen, $table);

			$string .= "\n\t\t\$html = \$this->load->view('partials/winform/win_".$table['TABLE_NAME'].".php', \$data, TRUE);";
												
			$string .= "\n\t\treturn \$html;";										

			$string .= "\n\t}";	
			$string .= "\n\n";
		}
 
		//METODI WINFORM MASTER DETAILS - FINE



		//METODI WINFORM MASTER DETAILS - INSERIMENTO MULTIPLO
		foreach($arrayRefTables as $k => $table){	
			//print'<pre>';print_r($table);
			$string .= "\n\t/**";
			$string .= "\n\t* Funzione caricamento della finestra per la master details,in modalita di inserimento multiplo, tabella ".$table['TABLE_NAME'];
			$string .= "\n\t* @param mixed \$action";
			$string .= "\n\t* @param string \$entryID";
			$string .= "\n\t* @return string";
			$string .= "\n\t**/";				
			$string .= "\n\tpublic function winMasterDetailMulti".$table['TABLE_NAME']."(\$action, \$entryID){";	
			$string .="\n\t\t\$row = \$this->modelClassModule->get_all('".$table['TABLE_NAME']."', \$entryID);";	
			$string .= "\n\t\t\$data['action'] = \$action;";
			$string .= "\n\t\t\$data['entryID'] = \$entryID;";
			$string .= "\n\t\t\$data['row'] = \$row;";					
			$string .= "\n\t\t\$html = '';";
			$arrayRefFields = $Mod_crud_gen_model->getFieldsRef($table['TABLE_NAME']);
			$arrayField = $Mod_crud_gen_model->getColumnsTable($table['TABLE_NAME']);	
			
			//print'<pre>'.$table['TABLE_NAME'];print_r($arrayRefFields);
			//print'<pre>'.$table['TABLE_NAME'];print_r($arrayField);

			create_win_form_multi::output($arrayRefFields, $arrayField , $param_gen, $table);

			$string .= "\n\t\t\$html = \$this->load->view('partials/winform/winm_".$table['TABLE_NAME'].".php', \$data, TRUE);";

			$string .= "\n";										
			$string .= "\n\t\treturn \$html;";
			$string .= "\n\t}";	
			$string .= "\n\n";
		}
		

		//METODI WINFORM MASTER DETAILS - - INSERIMENTO MULTIPLO - FINE
		
		
		//print'<pre>';print_r($all);die();

		//METODO _rules		
		$string .= "\n\tpublic function _rules()";
		$string .= "\n\t{";
				foreach ($non_pk as $row) {
					$rule = "trim";
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
							$rule.="|numeric";
							$rule.="|max_length[".$row['NUMERIC_PRECISION']."]";
						break;
					
						case 'tinytext':
						case 'mediumtext':
						case 'longtext':
						case 'text':
						case 'char':
						case 'varchar':			
							if((substr($row["COLUMN_NAME"],0,5) == "email") || (substr($row["COLUMN_NAME"],0,4) == "mail")){
								$rule.="|valid_email";
							}
							
							$rule.="|max_length[".$row['CHARACTER_MAXIMUM_LENGTH']."]";
						break;						
					

 	
					}		
					
										
					if($row['IS_NULLABLE'] == 'NO'){
						$rule.= "|required";				
					}

					if(($row["DATA_TYPE"] == 'blob') || ($row["DATA_TYPE"] == 'tinyblob') || ($row["DATA_TYPE"] == 'mediumblob')
						|| ($row["DATA_TYPE"] == 'longblob') || ($row["DATA_TYPE"] == 'binary') || ($row["DATA_TYPE"] == 'varbinary')){
						
						if($row['IS_NULLABLE'] == 'NO'){
							$string .= "\n\t\tif (empty(\$_FILES['".$row['COLUMN_NAME']."']['name'])){";
							$string .= "\n\t\t\tif (empty(\$this->input->post('".$row['COLUMN_NAME']."_hidden'))){";
							$string .= "\n\t\t\t\t\$this->form_validation->set_rules('".$row['COLUMN_NAME']."', '".$row['COLUMN_COMMENT']."', 'required');";
							$string .= "\n\t\t\t}";
							$string .= "\n\t\t}";			
						}
					} else {
						if($row["DATA_TYPE"] == 'set'){
							$string .= "\n\t\t\$this->form_validation->set_rules('".$row['COLUMN_NAME']."[]', '".  strtolower($helper->label(addslashes($row['COLUMN_COMMENT'])))."', '$rule');";	
						} else {
							$string .= "\n\t\t\$this->form_validation->set_rules('".$row['COLUMN_NAME']."', '".  strtolower($helper->label(addslashes($row['COLUMN_COMMENT'])))."', '$rule');";	
						}
							
					}

							

				}    
				$string .= "\n\n\t\t\$this->form_validation->set_rules('$pk', '$pk', 'trim');";
				$string .= "\n\t\t\$this->form_validation->set_error_delimiters('<span class=\"text-danger\">', '</span>');";
		$string .= "\n\n\t}";
		//METODO _rules	 -FINE


		//CHIUDO LA GRAFFA DELLA CLASSE
		$string .= "\n\n}";
		
		return $string;
	}

	private static function getTableTitle($tablesTitle, $tableName){
		$title_of_table = "";

		if(isset( $tablesTitle[$tableName])){
			$tableDett = $tablesTitle[$tableName];
			$title_of_table = $tableDett['title_of_table'];
		} else {
			$title_of_table = $tableName;
		}
 

		return $title_of_table;
	}



}

?>