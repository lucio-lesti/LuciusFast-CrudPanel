<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

require APPPATH . '/libraries/BaseModel.php';    
class Mod_logs_model extends BaseModel
{

	/**
	* 
	*/
    public function __construct(){
        parent::__construct();
		$this->table = 'mod_logs';
		$this->id = 'id';
        $this->mod_name = 'mod_logs';
    }



	public function json($searchFilter) {
		$this->datatables->select('id AS id,programma,utente,azione,DATE_FORMAT(data,"%d/%m/%Y %H:%i") AS data');
		$this->datatables->from('mod_logs');
		
		$this->datatables->add_column('action',"", 'id');		
		$this->datatables->add_column('ids','<input type="checkbox" id="check_id" name="check_id" value="$1" onchange="verificaNrCheckBoxSelezionati(\'check_id\',\'btDeleteMass\')" />','id'); 
		foreach($searchFilter as $key => $value){
			if($value['value'] != ''){
				$this->datatables->like($value['field'],$value['value']);	
			}
		}
 
		return $this->datatables->generate();
	}



    public function getPermissionRole($roleid){
        switch($this->db->dbdriver){
			case 'mysql':
			case 'mysqli':	
				$select = "cml.mod_id, cml.mod_name, cml.mod_title, cml.mod_parentid,
							crp.perm_read,crp.perm_write, crp.perm_update,crp.perm_delete";
				$this->db->select($select);
				$this->db->from('core_module_list cml');
                $this->db->where('cml.mod_name','mod_logs');
				$this->db->join('core_roles_permission crp', 'crp.mod_id = cml.mod_id 
								and crp.role_id = '.$roleid,'left');
 
			break;
			
			case 'postgre':
				$select = "cml.mod_id, cml.mod_name, cml.mod_title, cml.mod_parentid,
							crp.perm_read,crp.perm_write, crp.perm_update,crp.perm_delete";
				$this->db->select($select);
				$this->db->from('core_module_list cml');
				$this->db->where('cml.mod_name','mod_logs');			
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
			
			break;				
		}

        $query = $this->db->get();
        
        $result = $query->result();  
        return $result;
    }
	

}