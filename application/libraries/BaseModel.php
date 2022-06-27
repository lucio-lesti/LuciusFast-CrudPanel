<?php defined ( 'BASEPATH' ) or exit ( 'No direct script access allowed' ); 


/**
 * Class : BaseModel (BaseModel)
 * @author : Lucio Lesti
 * @version : 1.0
 * @since : 27.02.2021
 */
 

class BaseModel extends CI_Model
{
    
    public $table = NULL;
    public $id = NULL;
    public $order = 'DESC';
    protected $utilities = NULL;

    public $arrayFieldSearch = array();
    public $arrayFieldGrid = array();


    public $arrayColumnsReferenced = array();
    public $mod_name = NULL;
    public $miodb = NULL;
    protected $enableCustomQueryMethod = FALSE;
    protected $customQueryMethod = array("json" => array(
                                                "fields" => array(),
                                                "table_from" => NULL,
                                                "tables_join" => array())
                                    );

    protected $mod_type = "crud";           


    /**
     * 
     * Costruttore della classe 
     */
    public function __construct(){
        parent::__construct();
        $this->utilities = new Utilities();
    }    


    /**
     * 
     * Ritorna il json usato dalla griglia datatable
     * @param array $searchFilter
     * @return JSON
     */
    public function json(Array $searchFilter)
    {
        $fields = array();
        $fieldsInfoDetails = array();
        $tableReferenced = array();

        //SE "enableCustomQueryMethod" FALSE, 
        //COSTRUISCO LA QUERY IN BASE AI VALORI SETTATI DAL METODO "setFieldArrayGrid"
        if($this->enableCustomQueryMethod  == FALSE){
            foreach($this->arrayFieldGrid as $key => $field){
                
                if(isset($field['columns_referenced'])){
                    if(isset($this->arrayColumnsReferenced[$field['tablename_referenced']]['nome'])){
                        $field_columns_referenced_nome = $this->arrayColumnsReferenced[$field['tablename_referenced']]['nome'];
                    } else {
                        $field_columns_referenced_nome = $field['columns_referenced']['nome'];
                    }
                    
                    $isConcat = 'FALSE';
                    //VERIFICO SE UN ARRAY, SE SI È UN CONCAT MYSQL/POSTGRESQL
                    if(is_array($field_columns_referenced_nome)){
                        $isConcat = 'TRUE';
                        $alias = "nome_alias";
                        if(isset($field['alias_name'])){
                            $alias = $field['alias_name'];
                        }
                        $myField = " CONCAT(";

                        $concat_count = 0;
                        $field_blank = FALSE;
                        foreach($field_columns_referenced_nome as $k => $v){
                            if(($v == "") || ($v == " ")){
                                $v = "' '";
                                $field_blank = TRUE;
                            } 
                            if($concat_count > 0){
                                if( $field_blank == FALSE){
                                    $myField .= ",".$field['tablename_referenced'].".".$v; 
                                } else {
                                    $myField .= ",".$v; 
                                }
                            } else {
                                if( $field_blank == FALSE){
                                    $myField .= $field['tablename_referenced'].".".$v; 
                                } else {
                                    $myField .= $v; 
                                }                                
                            } 
                            $concat_count++;
                            $field['columns_referenced']['nome'] = $alias;
                            $field_columns_referenced_nome = $alias;
                        }
                        $myField .= ") AS ".$alias;
                    } else {
                        //CAMPO DI TIPO DATE/DATETIME/TIMESTAMP
                        if($field['type'] == 'FIELD_DATE'){
                            $myField = " DATE_FORMAT(".$field['tablename_referenced'].".".$field_columns_referenced_nome.",'%d/%m/%Y') AS ".$field['tablename_referenced']."_".$field_columns_referenced_nome;
                        } elseif($field['type'] == 'FIELD_DATETIME'){
                            $myField = " DATE_FORMAT(".$field['tablename_referenced'].".".$field_columns_referenced_nome .",'%d/%m/%Y %H:%i') AS ".$field['tablename_referenced']."_".$field_columns_referenced_nome;
                        } elseif($field['type'] == 'FIELD_BLOB'){
                            $myField = ' CONCAT(\'| ALLEGATO\',\' |\') AS '.$field['tablename_referenced']."_".$field_columns_referenced_nome;
                        } elseif($field['type'] == 'FIELD_BLOB_IMG'){
                            $myField = ' CONCAT(\'<img src="data:image/jpeg;base64,\',TO_BASE64('.$field['tablename_referenced'].".".$field_columns_referenced_nome.'),\'" style="width:90px" />\') AS '.$field['tablename_referenced']."_".$field_columns_referenced_nome;
                        } elseif($field['type'] == 'FIELD_FLOAT'){                      
                             $myField = " FORMAT(".$field['tablename_referenced'].".".$field_columns_referenced_nome .",2,'it_IT') AS ".$field['tablename_referenced']."_".$field_columns_referenced_nome;                                    
                        } else {
                            $myField = $field['tablename_referenced'].".".$field_columns_referenced_nome." AS ".$field['tablename_referenced']."_".$field_columns_referenced_nome;
                        }    
                            
                    }


                    $fields[] = $myField;
 
                    //TABELLE REFERENZIATE
                    $tableReferenced[$field['tablename_referenced']] = array("column"=> $field['field'], 
                                                                            'tablename_referenced'=> $field['tablename_referenced'],
                                                                            'tablename_referenced_pk' => $field['columns_referenced']['id'],
                                                                            "join"=> $field['joinType'],
                                                                            "join_condition"=> $field['join_condition'] );

                    if($isConcat == 'TRUE'){
                        $fieldsInfoDetails[$field['columns_referenced']['nome']]['table'] = $field['tablename_referenced'];
                        $fieldsInfoDetails[$field['columns_referenced']['nome']]['ref_by_field'] = $field['field'];
                        $fieldsInfoDetails[$field['columns_referenced']['nome']]['field_name'] = $field_columns_referenced_nome;                        
                        $fieldsInfoDetails[$field['columns_referenced']['nome']]['field_alias_name'] = $field_columns_referenced_nome;
                    } else {
                        $fieldsInfoDetails[$field['tablename_referenced']."_".$field['columns_referenced']['nome']]['table'] = $field['tablename_referenced'];
                        $fieldsInfoDetails[$field['tablename_referenced']."_".$field['columns_referenced']['nome']]['ref_by_field'] = $field['field'];
                        $fieldsInfoDetails[$field['tablename_referenced']."_".$field['columns_referenced']['nome']]['field_name'] = $field_columns_referenced_nome;
                        $fieldsInfoDetails[$field['tablename_referenced']."_".$field['columns_referenced']['nome']]['field_alias_name'] = $field['tablename_referenced']."_".$field_columns_referenced_nome;
                    }    
                                  
                } else {
                    if($field['field'] != "id"){
                        if($field['field'] == $this->id){                       
                            $fields[] = $this->table.".".$field['field'] ." AS id";
    
                            $fieldsInfoDetails[$this->table."_".$field['field']]['table'] = $this->table;
                            $fieldsInfoDetails[$this->table."_".$field['field']]['ref_by_field'] = NULL;
                            $fieldsInfoDetails[$this->table."_".$field['field']]['field_name'] = $field['field'];
                            $fieldsInfoDetails[$this->table."_".$field['field']]['field_alias_name'] = $this->table.".".$field['field']." AS id";                          
    
                        } else {

                            //CAMPO DI TIPO DATE/DATETIME/TIMESTAMP
                            if($field['type'] == 'FIELD_DATE'){
                                $myField = " DATE_FORMAT(".$this->table.".".$field['field'] .",'%d/%m/%Y') AS ".$this->table."_".$field['field'];
                            } elseif($field['type'] == 'FIELD_DATETIME'){
                                $myField = " DATE_FORMAT(".$this->table.".".$field['field'] .",'%d/%m/%Y %H:%i') AS ".$this->table."_".$field['field'];
                            } elseif($field['type'] == 'FIELD_BLOB'){
                                $myField = ' CONCAT(\'| ALLEGATO\',\' |\') AS '.$this->table."_".$field['field'];
                            } elseif($field['type'] == 'FIELD_BLOB_IMG'){
                                $myField = ' CONCAT(\'<img src="data:image/jpeg;base64,\',TO_BASE64('.$field['field'].'),\'" style="width:90px" />\') AS '.$this->table."_".$field['field'];
                            } elseif($field['type'] == 'FIELD_FLOAT'){
                                $myField = " FORMAT(".$this->table.".".$field['field'] .",2,'it_IT') AS ".$this->table."_".$field['field'];
                            } else {
                                $myField = $this->table.".".$field['field']." AS ".$this->table."_".$field['field'];
                            }    
                            $fields[] = $myField;
        
                            $fieldsInfoDetails[$this->table."_".$field['field']]['table'] = $this->table;
                            $fieldsInfoDetails[$this->table."_".$field['field']]['ref_by_field'] = NULL;
                            $fieldsInfoDetails[$this->table."_".$field['field']]['field_name'] = $field['field'];
                            $fieldsInfoDetails[$this->table."_".$field['field']]['field_alias_name'] = $this->table."_".$field['field'];                            
                        }    
                    } else {
                        $fields[] = $this->table.".".$field['field'] ;
    
                        $fieldsInfoDetails[$this->table."_".$field['field']]['table'] = $this->table;
                        $fieldsInfoDetails[$this->table."_".$field['field']]['ref_by_field'] = NULL;
                        $fieldsInfoDetails[$this->table."_".$field['field']]['field_name'] = $field['field'];
                        $fieldsInfoDetails[$this->table."_".$field['field']]['field_alias_name'] = $this->table.".".$field['field'];                      
                    }
                    
                }
            }        
        }       

        //PRELEVO PRIVILEGI
        $perm_read = "";
        $perm_write = "";
        $perm_update = "";
        $perm_delete = "";
		$global_permissions = $this->getPermissionRole($this->session->userdata('role'));
		foreach($global_permissions as $key => $module_permission){
			if($module_permission->mod_name == $this->mod_name){
				$perm_read = $module_permission->perm_read;
				$perm_write = $module_permission->perm_write;
				$perm_update = $module_permission->perm_update;
				$perm_delete = $module_permission->perm_delete;				
				break;
			}
		}

    
        if($this->enableCustomQueryMethod  == FALSE){
            $this->datatables->select(implode(",",$fields));
            $this->datatables->from($this->table);
            foreach($tableReferenced as $key => $val){
                $this->datatables->join($val['tablename_referenced'], $this->table.'.'.$val['column'].' = '.$val['tablename_referenced'].'.'.$val['tablename_referenced_pk'], $val['join'], $val['join_condition']);
            }
        } else {
            if(is_array($this->customQueryMethod["json"]["fields"])){
                $fields = implode(",",$this->customQueryMethod["json"]["fields"]);
            } else {
                $fields = $this->customQueryMethod["json"]["fields"];
            }

            $this->datatables->select($fields);
            $this->datatables->from($this->customQueryMethod["json"]["table_from"]);
            foreach($this->customQueryMethod["json"]["tables_join"] as $key => $val){
                if(isset($val['join_mode'])){
                    $join_mode =  $val['join_mode'];
                } else {
                    $join_mode = "inner";
                }
                $this->datatables->join($val['table'], $val['join'],$join_mode);
            }            
        }
        
       
        //SE IL MODULO E' DI TIPO "CRUD", AGGIUNGO I PULSANTI ADD,EDIT,DELETE
        if($this->mod_type == 'crud'){
            $button = "";   
            if($perm_read == 'Y'){
                $button .= "<a onclick='readAjax(\"$this->mod_name\",$1)' class='btn btn-sm btn-default' title='Visualizza'><i class='fa fa-eye'></i></a><br>";
            }
            if($perm_update == 'Y'){
                $button .= "<a onclick='editAjax(\"$this->mod_name\",$1)' class='btn btn-sm btn-info' title='Modifica'><i class='fa fa-pencil'></i></a><br>";
            }   
            if($perm_delete == 'Y'){
                $button .= "<a onclick='deleteEntry(\"$1\", \"".$this->mod_name."\",\"delete\")' class='btn btn-sm btn-danger deleteItem' title='Cancella'><i class='fa fa-trash'></i></a>";        
            }  

            $this->datatables->add_column('action', $button, 'id');
            $this->datatables->add_column('ids', '<input type="checkbox" id="check_id" name="check_id" value="$1" onchange="verificaNrCheckBoxSelezionati(\'check_id\',\'btDeleteMass\')" />', $this->id);             

        } 

        return $this->datatables->generate();
    }
    

    

    /**
     * 
     * Restituisce tutti i records di una tabella
     * @return array
     */
    public function get_all($table = NULL, $id = NULL, $order = NULL){
        if($table == NULL){
            $table = $this->table;
        }
 
        if(($order != NULL) && ($id != NULL)){
            $id = $this->id;
            $order = $this->order;
            $this->db->order_by($table.".".$id, $order);
        }            
        
        return $this->db->get($table)->result();
    }


    /**
     * 
     * Restituisce tutti i records di una tabella, per ID
     * @return array
     */
    public function get_all_by_id($table = NULL, $id = NULL, $pkId = "id"){
        if($table == NULL){
            $table = $this->table;
        }
          
        if($id != NULL){
            $row = $this->db->where($pkId, $id)->get($table)->result();
        } else {
            $row = $this->db->get($table)->result();
        }
        

        return $row;
    }


    /**
     * 
     * Restituisce un singolo record di una tabella
     * @param mixed $id
     * @return array
     */
    public function get_by_id($id, $table = NULL, $pkId = NULL){
        if($table == NULL){
            $table = $this->table;
        }
        if($pkId == NULL){
            $pkId = $this->id;
        }    
        $this->db->where($pkId, $id);
        return $this->db->get($table)->row();
    }


    /**
     * 
     * Restituisce un singolo record di una tabella
     * @param mixed $id
     * @param mixed $table
     * @param mixed $pkField
     * @return array
     */
    public function get_by_id_customized($id, $table, $pkField){
        $this->db->where($pkField, $id);
        return $this->db->get($table)->row();
    }
    
    
    

    /**
     * 
     * Ritorna il numero records di una tabella
     * @param string|null $str_search
     * @return mixed 
     */
    public function total_rows(String $str_search = null){
        foreach($this->arrayFieldSearch as $key => $value){
            if($value['type'] == FIELD_NUMERIC){
                $this->db->like($value['field'], $str_search);
            } else {
                $this->db->or_like($value['field'], FIELD_STRING);
            }
        }
        $this->db->from($this->table);
        return $this->db->count_all_results();
    }


    
    /**
     * 
     * Ritorna records di una tabella in base ai parametri limit e di ricerca
     * @param string $limit
     * @param int $start
     * @param string|null $str_search
     * @return array
     */
    public function get_limit_data(String $limit, int $start = 0, String $str_search = null){
        foreach($this->arrayFieldSearch as $key => $value){
            if($value['type'] == FIELD_NUMERIC){
                $this->db->like($value['field'], $str_search);
            } else {
                $this->db->or_like($value['field'], FIELD_STRING);
            }
        }
        $this->db->order_by($this->table.".".$this->id, $this->order);
        $this->db->limit($limit, $start);

        return $this->db->get($this->table)->result();
    }



    /**
     * 
     * Inserisce un record in tabella 
     * @param array $data
     */
    public function insert(Array $data){
        $orig_db_debug = $this->db->db_debug;
        $this->db->db_debug = FALSE;

        $this->db->insert($this->table, $data);

        $this->db->db_debug = $orig_db_debug;

        $Err = $this->db->error();

        $ModuleTitleByForeignKeyFailed =  $this->getModuleTitleByForeignKeyFailed($Err['message']);
        $Err['module_title'] = $ModuleTitleByForeignKeyFailed ['module_title'];
        $Err['table_name'] = $ModuleTitleByForeignKeyFailed ['table_name'];
        return $Err;
    }



    /**
     * 
     * Aggiorna un record in tabella 
     * @param mixed $id
     * @param array $data
     */
    public function update($id, $data){
        $orig_db_debug = $this->db->db_debug;
        $this->db->db_debug = FALSE;

        $this->db->where($this->id, $id);
        $this->db->update($this->table, $data);

        $this->db->db_debug = $orig_db_debug;

        $Err = $this->db->error();

        $ModuleTitleByForeignKeyFailed =  $this->getModuleTitleByForeignKeyFailed($Err['message']);

        $Err['module_title'] = $ModuleTitleByForeignKeyFailed ['module_title'];
        $Err['table_name'] = $ModuleTitleByForeignKeyFailed ['table_name'];
        return $Err;  
    }



    /**
     * 
     * Elimina un record in tabella 
     * @param mixed $id
     */
    public function delete($id){
        $orig_db_debug = $this->db->db_debug;
        $this->db->db_debug = FALSE;

        $this->db->where($this->id, $id);
        $this->db->delete($this->table);

        $this->db->db_debug = $orig_db_debug;

        $Err = $this->db->error();

        $ModuleTitleByForeignKeyFailed =  $this->getModuleTitleByForeignKeyFailed($Err['message']);
        $Err['module_title'] = $ModuleTitleByForeignKeyFailed ['module_title'];
        $Err['table_name'] = $ModuleTitleByForeignKeyFailed ['table_name'];
        return $Err;
        
    }



    /**
     * 
     * Elimina un set di records in tabella 
     * @param array $entryListArray
     */
    public function deleteMassive(Array $entryListArray){
        $orig_db_debug = $this->db->db_debug;
        $this->db->db_debug = FALSE;    
            
        if (is_array($entryListArray)) {
            $this->db->where_in($this->id, $entryListArray);
            $this->db->delete($this->table);
        }

        $this->db->db_debug = $orig_db_debug;

        $Err = $this->db->error();

        $ModuleTitleByForeignKeyFailed =  $this->getModuleTitleByForeignKeyFailed($Err['message']);
        $Err['module_title'] = $ModuleTitleByForeignKeyFailed ['module_title'];
        $Err['table_name'] = $ModuleTitleByForeignKeyFailed ['table_name'];
        return $Err;        
    }



    /**
     * 
     * Inserisce un record in una tabella referenziata per il master-details 
     * @param array $data
     */
    public function insert_master_details(Array $data){
        $table = $data['table'];
        unset($data['table']);     
        if(isset($data['entry_to_delete_master'])){
            unset($data['entry_to_delete_master']);  
        }   
        
        $orig_db_debug = $this->db->db_debug;
        $this->db->db_debug = FALSE;

        
        $this->db->insert($table, $data);

        $this->db->db_debug = $orig_db_debug;

        $Err = $this->db->error();

        $ModuleTitleByForeignKeyFailed =  $this->getModuleTitleByForeignKeyFailed($Err['message']);
        $Err['module_title'] = $ModuleTitleByForeignKeyFailed ['module_title'];
        $Err['table_name'] = $ModuleTitleByForeignKeyFailed ['table_name'];
        return $Err;    
    }



    /**
     * 
     * Aggiorna un record in una tabella referenziata per il master-details 
     * @param mixed $id
     * @param array $data
     */
    public function update_master_details($id, $data){
        $table = $data['table'];
        unset($data['table']);        

        $orig_db_debug = $this->db->db_debug;
        $this->db->db_debug = FALSE;

        $this->db->where('id', $id);
        $this->db->update($table, $data);

        $this->db->db_debug = $orig_db_debug;

        $Err = $this->db->error();

        $ModuleTitleByForeignKeyFailed =  $this->getModuleTitleByForeignKeyFailed($Err['message']);
        $Err['module_title'] = $ModuleTitleByForeignKeyFailed ['module_title'];
        $Err['table_name'] = $ModuleTitleByForeignKeyFailed ['table_name'];
        return $Err;         
    }



    /**
     * 
     * Elimina un record in una tabella referenziata per il master-details 
     * @param mixed $id_row_master_details
     * @param mixed $table
     */
    public function delete_row_master_details($id_row_master_details, $table){        
        $orig_db_debug = $this->db->db_debug;
        $this->db->db_debug = FALSE;

        $this->db->where('id', $id_row_master_details);
        $this->db->delete($table);  

        $this->db->db_debug = $orig_db_debug;

        $Err = $this->db->error();

        $ModuleTitleByForeignKeyFailed =  $this->getModuleTitleByForeignKeyFailed($Err['message']);
        $Err['module_title'] = $ModuleTitleByForeignKeyFailed ['module_title'];
        $Err['table_name'] = $ModuleTitleByForeignKeyFailed ['table_name'];
        return $Err;         
    }


    /**
     * 
     * Restituisce tutti i records di una tabella
     * @return array
     */
    public function get_from_master_details_by_id($id, $tableMasterDetails, $pkField){
        $arrayReturn = array();
        $list_fields = $this->db->list_fields($tableMasterDetails);
        foreach($list_fields as $k => $v){
            $arrayReturn[$v] = NULL;
        }
        $this->db->where($pkField, $id);

        $rows = $this->db->get($tableMasterDetails)->row();
        $rows = (array) $rows;
 
        if(count($rows) > 0){
            $arrayReturn = $rows;
        } 

        return $arrayReturn;
    }
    


    /**
     * @deprecated
     * Restituisce tutti i records di una tabella
     * @return array
     */
    public function get_all_from_master_details($id, $table, $pkField){
        $this->db->where($pkField, $id);
        return $this->db->get($table)->result();
    }
    
    

    /**
     * 
     * Ritorna il nome del modulo per iil quale fallisce la chiave constraint
     * @param string $sqlMsgError
     */
    private function getModuleTitleByForeignKeyFailed(String $sqlMsgError){
        $array_return = array("module_title" => "", "table_name" => "");
        $sql ="SELECT name_of_table,title_of_table,TABLE_NAME,COLUMN_NAME,CONSTRAINT_NAME, 
                    REFERENCED_TABLE_NAME,REFERENCED_COLUMN_NAME 
                FROM INFORMATION_SCHEMA.KEY_COLUMN_USAGE 
                LEFT JOIN ". $this->db->database.".core_table_title 
                    ON ". $this->db->database.".core_table_title.name_of_table = TABLE_NAME 
                    WHERE REFERENCED_TABLE_SCHEMA = '". $this->db->database."';";
        $row =  $this->db->query($sql)->result_array();	

        foreach($row as $k => $v){
            if (strpos($sqlMsgError, $v['CONSTRAINT_NAME']) == TRUE){	
                $array_return['module_title'] = $v['title_of_table'];
                $array_return['table_name'] = $v['name_of_table'];
                break;
            } 
        }

        return $array_return;
    }



    /**
     * 
     * Salva un allegato blob in tabella
     * @param mixed $mod_name
     * @param array $field_name
     * @param array $fk_key
     * @param array $nome_allegato
     * 
     */
    public function saveBlob($mod_name,$field_name,$fk_key,$nome_allegato){
        $data = array();
        $data['mod_name'] = $mod_name;
        $data['field_name'] = $field_name;
        $data['fk_key'] = $fk_key;
        $data['nome_allegato'] = $nome_allegato;

        $this->db->where("fk_key", $fk_key);
        $this->db->where("field_name", $field_name);
        $this->db->delete("_mod_allegati_blob");

        $this->db->insert("_mod_allegati_blob", $data);
    }


       

    /**
     * 
     * Elimina un set di records in tabella 
     * @param array $entryListArray
     * @param string $table
     */
    public function delete_massive_master_details(Array $entryListArray, $table){
        $orig_db_debug = $this->db->db_debug;
        $this->db->db_debug = FALSE;    
            
        if (is_array($entryListArray)) {
            $this->db->where_in('id', $entryListArray);
            $this->db->delete($table);
        }

        $this->db->db_debug = $orig_db_debug;

        $Err = $this->db->error();

        $ModuleTitleByForeignKeyFailed =  $this->getModuleTitleByForeignKeyFailed($Err['message']);
        $Err['module_title'] = $ModuleTitleByForeignKeyFailed ['module_title'];
        $Err['table_name'] = $ModuleTitleByForeignKeyFailed ['table_name'];
        return $Err;      
    }



    /**
     * 
     * Ritorna l'id più alto come valore in una tabella
     */
    public function getLastInsertId(){
        $id = $this->id;
        $this->db->select_max($id);
        $this->db->from($this->table);
        $query = $this->db->get();
        $res = $query->result();
        $max = $res[0]->$id;
        //$max += 1;
        $max = (string) $max;
        return $max;
    }



    /**
     * 
     * Override della funzione presente in user_model
     * Necessaria per non dover importare la classe e creare errori
     *
     * @param mixed|null $roleid
     * 
     */
    public function getPermissionRole($roleid){
        switch($this->db->dbdriver){
			case 'mysql':
			case 'mysqli':	
				$select = "cml.mod_id, cml.mod_name, cml.mod_title, cml.mod_parentid,
							crp.perm_read,crp.perm_write, crp.perm_update,crp.perm_delete";
				$this->db->select($select);
				$this->db->from('core_module_list cml');
				$this->db->join('core_roles_permission crp', 'crp.mod_id = cml.mod_id 
								and crp.role_id = '.$roleid,'left');	
			break;
			
			case 'postgre':
				$select = "cml.mod_id, cml.mod_name, cml.mod_title, cml.mod_parentid,
							crp.perm_read,crp.perm_write, crp.perm_update,crp.perm_delete";
				$this->db->select($select);
				$this->db->from('core_module_list cml');
				$this->db->join('core_roles_permission crp', 'crp.mod_id = cml.mod_id 
								and crp.role_id = '.$roleid,'left');
				$this->db->group_by("cml.mod_id"); 		
				$this->db->group_by("cml.mod_name"); 	
				 		
				$this->db->group_by("crp.perm_read"); 	
				$this->db->group_by("crp.perm_write"); 
				$this->db->group_by("crp.perm_update"); 
				$this->db->group_by("crp.perm_delete"); 
 					
			break;
			
			case 'mssql':
                //
			break;				
		}

        $query = $this->db->get();
        
        $result = $query->result();  
        return $result;
    }



    /**
     * 
     * Ritorna un set di records da una tabella referenziata
    * @param mixed $table_referenced
    * @param mixed|null $id
    * @param mixed|null $value
    * @return mixed
    */
    public function getValuesByFk($table_referenced, $id = NULL, $value = NULL, $where_condition = NULL){   
        if(($id == NULL) || ($value == NULL)){
            $sql ="SELECT * FROM ".$table_referenced;
        } else {
            if(isset($this->arrayColumnsReferenced[$table_referenced]['nome'])){
                $field_nome = $this->arrayColumnsReferenced[$table_referenced]['nome'];
                $sql ="SELECT $id as id,  $field_nome as nome FROM ".$table_referenced;
            } else {
                $sql ="SELECT $id as id, $value as nome FROM ".$table_referenced;
            }            
        }

        if($where_condition != NULL){
            $sql .= " ".$where_condition;
        }

        return $this->db->query($sql)->result_array();	;
    }



    /**
     * 
     * Imposta i campi/colonna per la griglia dati Datatable 
     * @param string $fieldName
     * @param string $fieldType
     * @param string|null $tableNameReferenced
     * @param array|null $arrayColumns
     */
    protected function setFieldArrayGrid(String $fieldName, String $fieldType, String $tableNameReferenced = NULL, Array $arrayColumns = NULL, String $aliasName = NULL, $joinType = "INNER", $join_condition = ""){
        if(($fieldType != FIELD_STRING) && ($fieldType != FIELD_NUMERIC) && ($fieldType != FIELD_DATE) && ($fieldType != FIELD_DATETIME) && ($fieldType != FIELD_BLOB) && ($fieldType != FIELD_BLOB_IMG) && ($fieldType != FIELD_FLOAT)){
            $fieldType = FIELD_STRING;
        }

        $arrayParam = array("field" => $fieldName, 'type' => $fieldType);
        if(!is_null($tableNameReferenced)){
            $arrayParam['tablename_referenced'] = $tableNameReferenced;
        }
        if(isset($arrayColumns)){
            $arrayParam['columns_referenced'] = $arrayColumns;
        }
        if(!is_null($aliasName)){
            $arrayParam['alias_name'] = $aliasName;
        }            
        $arrayParam['joinType'] = $joinType;
        $arrayParam['join_condition'] = $join_condition;
 

        $this->arrayFieldGrid[$fieldName] = $arrayParam;
    }
    


    /**
     * 
     * Preleva i permessi
     * @param number $roleid : id ruolo
     * @param number $moduleId : id modulo
     * @return array $result : user information
     */
    public function getPermissionRoleTabs($roleid, $moduleName, $funcName = ''){
        $sql = "SELECT COUNT(*) AS have_tab_perm  FROM core_roles_permission_tabs
            WHERE role_id = ".$roleid." AND mod_name = '".$moduleName."' AND function_tab='".$funcName."'";
        
        return $this->db->query($sql)->result_array();	;
 
    }    



    /**
     * 
     * Restituisce i dettagli di un campo di una tabella
     * @param string $table
     * 
     */
    public function getFieldsDetails($table = NULL){
        if($table == NULL){
            $table = $this->table;
        }
        $query = "SELECT TABLE_NAME,COLUMN_NAME,COLUMN_KEY,DATA_TYPE,COLUMN_TYPE,CHARACTER_MAXIMUM_LENGTH,
                    NUMERIC_PRECISION,NUMERIC_SCALE,COLUMN_COMMENT,
                    IS_NULLABLE,DATETIME_PRECISION,EXTRA 
                FROM INFORMATION_SCHEMA.COLUMNS 
                WHERE TABLE_SCHEMA='".$this->db->database."' 
                AND TABLE_NAME='".$table."'";

        $res = $this->db->query($query)->result_array();
        $fields = array();
        foreach ($res as $key => $row) {		
            $fields[$row['COLUMN_NAME']]['TABLE_NAME'] = $row['TABLE_NAME'];	
            $fields[$row['COLUMN_NAME']]['COLUMN_NAME'] = $row['COLUMN_NAME'];
            $fields[$row['COLUMN_NAME']]['COLUMN_KEY'] = $row['COLUMN_KEY'];
            $fields[$row['COLUMN_NAME']]['DATA_TYPE'] = $row['DATA_TYPE'];
            $fields[$row['COLUMN_NAME']]['CHARACTER_MAXIMUM_LENGTH'] = $row['CHARACTER_MAXIMUM_LENGTH'];
            $fields[$row['COLUMN_NAME']]['NUMERIC_PRECISION'] = $row['NUMERIC_PRECISION'];
            $fields[$row['COLUMN_NAME']]['NUMERIC_SCALE'] = $row['NUMERIC_SCALE'];
            $fields[$row['COLUMN_NAME']]['COLUMN_COMMENT'] = $row['COLUMN_COMMENT'];
            $fields[$row['COLUMN_NAME']]['IS_NULLABLE'] = $row['IS_NULLABLE'];
            $fields[$row['COLUMN_NAME']]['DATETIME_PRECISION'] = $row['DATETIME_PRECISION'];
            $fields[$row['COLUMN_NAME']]['EXTRA'] = $row['EXTRA'];
            $fields[$row['COLUMN_NAME']]['COLUMN_TYPE'] = $row['COLUMN_TYPE'];
        }		
        return $fields;               
    }
    
    
    /**
     * 
     * Restituisce le colonne di una tabella 
     * @param string $table : nome tabell
     */
    public function getColumnsTable($table){
		switch($this->db->dbdriver){	
			case 'mysql':
			case 'mysqli':			
				$sql = "SELECT TABLE_NAME,COLUMN_NAME,COLUMN_KEY,DATA_TYPE,COLUMN_TYPE,CHARACTER_MAXIMUM_LENGTH,
								NUMERIC_PRECISION,NUMERIC_SCALE,COLUMN_COMMENT,IS_NULLABLE 
							FROM INFORMATION_SCHEMA.COLUMNS 
							WHERE TABLE_SCHEMA='".$this->db->database."' 
							AND TABLE_NAME='".$table."' 
                            ORDER BY ORDINAL_POSITION";
			break;
			
			case 'postgre':
				$sql = "SELECT '$table' AS TABLE_NAME, COLUMN_NAME,'' as COLUMN_KEY,DATA_TYPE,DATA_TYPE as COLUMN_TYPE,CHARACTER_MAXIMUM_LENGTH,
								NUMERIC_PRECISION,NUMERIC_SCALE,
								(
									SELECT
										pg_catalog.col_description(c.oid, cols.ordinal_position::int)
									FROM
										pg_catalog.pg_class c
									WHERE
										c.oid = (SELECT ('\"' || cols.TABLE_NAME || '\"')::regclass::oid)
										AND c.relname = cols.TABLE_NAME
								) AS COLUMN_COMMENT,
								IS_NULLABLE 
							FROM INFORMATION_SCHEMA.COLUMNS cols
							WHERE TABLE_SCHEMA='public' 
							AND TABLE_NAME='".$table."'
							AND COLUMN_NAME NOT IN 
							(
								SELECT a.attname AS COLUMN_NAME
								FROM   pg_index i
								INNER JOIN   pg_attribute a ON a.attrelid = i.indrelid
									AND a.attnum = ANY(i.indkey)
								WHERE  i.indrelid = '".$table."'::regclass
								AND    i.indisprimary							
							)
							";			
			break;
			
			case 'mssql':
			
			break;			
		}	 
        
        return $this->db->query($sql)->result_array();	;

    }


    /**
     * 
     * Ritorna i campi della griglia
     * @return array
     */
    public function getFieldsArrayGrid(){
        return $this->arrayFieldGrid;
    }


    /**
     * 
     * @deprecated
     * @param int $id
     * @return array
    */
    protected function getFieldArraySearchById(int $id){
        return $this->arrayFieldSearch[$id];
    }
    
    

    /**
     * 
     * @deprecated
     * @param int $id
     * @return array
     */
    protected function getFieldArrayGridById(int $id){
        return $this->arrayFieldGrid[$id];
    }
    
    
    /**
     * 
     * @deprecated
     * @param string $fieldName
     * @return string
     */
    protected function getFieldArraySearchByName(String $fieldName){
        $fieldToReturn = NULL;
        foreach($this->arrayFieldSearch as $key => $value){
            if($value['field'] == $fieldName){
                $fieldToReturn = $value;
                break;
            }
        }
        return $fieldToReturn;
    }
        


    /**
     * 
     * @deprecated
     * @param string $fieldName
     * @return string
     */
    protected function getFieldArrayGridByName(String $fieldName){
        $fieldToReturn = NULL;
        foreach($this->arrayFieldGrid as $key => $value){
            if($value['field'] == $fieldName){
                $fieldToReturn = $value;
                break;
            }
        }
        return $fieldToReturn;
    }
    

    
    /**
     * 
     * @deprecated
     * @param string $fieldName
     * @return string
     */
    protected function getFieldTypeArraySearchByName(String $fieldName){
        $fieldTypeToReturn = NULL;
        foreach($this->arrayFieldSearch as $key => $value){
            if($value['field'] == $fieldName){
                $fieldTypeToReturn = $value['type'];
                break;
            }
        }
        return $fieldTypeToReturn;
    }


    /**
     * 
     * @deprecated
     * @param string $fieldName
     * @return string
     */
    protected function getFieldTypeArrayGridByName(String $fieldName){
        $fieldTypeToReturn = NULL;
        foreach($this->arrayFieldGrid as $key => $value){
            if($value['field'] == $fieldName){
                $fieldTypeToReturn = $value['type'];
                break;
            }
        }
        return $fieldTypeToReturn;
    }
    
    
    /**
     * 
     * @deprecated
     * @param string $fieldType
     * @return array
     */
    protected function getFieldsArraySearchByType(String $fieldType){
        $fieldListToReturn = array();
        foreach($this->arrayFieldSearch as $key => $value){
            if($value['type'] == $fieldType){
                $fieldListToReturn[] = $value;
            }
        }
        return $fieldListToReturn;
    }


    /**
     * 
     * @deprecated
     * @param string $fieldType
     * @return array
     */
    protected function getFieldsArrayGridByType(String $fieldType){
        $fieldListToReturn = array();
        foreach($this->arrayFieldGrid as $key => $value){
            if($value['type'] == $fieldType){
                $fieldListToReturn[] = $value;
            }
        }
        return $fieldListToReturn;
    }


    /**
     * 
     * @deprecated
     * Imposta l'array per le ricerche
     * @param string $fieldName
     * @param string $fieldType
     * @return array
     */

    protected function setFieldArraySearch(String $fieldName, String $fieldType){
        if(($fieldType != FIELD_STRING) && ($fieldType != FIELD_NUMERIC)){
            $fieldType = FIELD_STRING;
        }
        $this->arrayFieldSearch[] = array("field" => $fieldName, 'type' => $fieldType);
    }



}
