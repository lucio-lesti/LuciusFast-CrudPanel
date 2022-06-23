<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

class User_model extends CI_Model
{
    /**
     * This function is used to get the user listing count
     * @param string $searchText : This is optional search text
     * @return number $count : This is row count
     */
    public function userListingCount($searchText = '')
    {
        $this->db->select('BaseTbl.userid, BaseTbl.email, BaseTbl.name, BaseTbl.mobile, Role.role');
        $this->db->from('core_users as BaseTbl');
        $this->db->join('core_roles as Role', 'Role.roleid = BaseTbl.roleid','left');
        if(!empty($searchText)) {
            $likeCriteria = "(BaseTbl.email  LIKE '%".$searchText."%'
                            OR  BaseTbl.name  LIKE '%".$searchText."%'
                            OR  BaseTbl.mobile  LIKE '%".$searchText."%')";
            $this->db->where($likeCriteria);
        }
        $this->db->where('BaseTbl.isdeleted', 0);
        $query = $this->db->get();
        
        return $query->num_rows();
    }
    
    /**
     * This function is used to get the user listing count
     * @param string $searchText : This is optional search text
     * @param number $page : This is pagination offset
     * @param number $segment : This is pagination limit
     * @return array $result : This is result
     */
    public function userListing($searchText = '', $page, $segment)
    {
        $this->db->select('BaseTbl.userid, BaseTbl.email, BaseTbl.name, BaseTbl.mobile, Role.role');
        $this->db->from('core_users as BaseTbl');
        $this->db->join('core_roles as Role', 'Role.roleid = BaseTbl.roleid','left');
        if(!empty($searchText)) {
            $likeCriteria = "(BaseTbl.email  LIKE '%".$searchText."%'
                            OR  BaseTbl.name  LIKE '%".$searchText."%'
                            OR  BaseTbl.mobile  LIKE '%".$searchText."%')";
            $this->db->where($likeCriteria);
        }
        $this->db->where('BaseTbl.isdeleted', 0);
		$this->db->where("BaseTbl.userid <> -1");
        $this->db->limit($page, $segment);
        $query = $this->db->get();
        
        $result = $query->result();        
        return $result;
    }
    
	
	
	/**
	 *  Prelevo tutti gli utenti
	 */
    public function getAllIUsers()
    {
        $this->db->select('BaseTbl.userid, BaseTbl.email, BaseTbl.name, BaseTbl.mobile, Role.role');
        $this->db->from('core_users as BaseTbl');
        $this->db->join('core_roles as Role', 'Role.roleid = BaseTbl.roleid','left');
        $this->db->where('BaseTbl.isdeleted', 0);
		$this->db->where("BaseTbl.userid <> -1");
 
        $query = $this->db->get();
        
        $result = $query->result();        
        return $result;
    }

	
	
	/**
	 *  Prelevo tutti gli utenti Amministratori
	 */
    public function getAllAdminUsers()
    {
        $this->db->select('BaseTbl.userid, BaseTbl.email, BaseTbl.name, BaseTbl.mobile, Role.role');
        $this->db->from('core_users as BaseTbl');
        $this->db->join('core_roles as Role', 'Role.roleid = BaseTbl.roleid','left');
        $this->db->where('BaseTbl.isdeleted', 0);
		$this->db->where("BaseTbl.userid <> -1");
		$this->db->where("BaseTbl.roleId = 1");
		
        $query = $this->db->get();
        
        $result = $query->result();        
        return $result;
    }

	
    /**
     * This function is used to get the user roles information
     * @return array $result : This is result of the query
     */
    public function getUserRoles()
    {
        $this->db->select('roleid, role');
        $this->db->from('core_roles');
        $query = $this->db->get();
        
        return $query->result();
    }

    /**
     * This function is used to check whether email id is already exist or not
     * @param {string} $email : This is email id
     * @param {number} $userid : This is user id
     * @return {mixed} $result : This is searched result
     */
    public function checkEmailExists($email, $userid = 0)
    {
        $this->db->select("email");
        $this->db->from("core_users");
        $this->db->where("email", $email);   
        $this->db->where("isdeleted", 0);
        if($userid != 0){
            $this->db->where("userid !=", $userid);
        }
        $query = $this->db->get();

        return $query->result();
    }
    
    
    /**
     * This function is used to add new user to system
     * @return number $insert_id : This is last inserted id
     */
    public function addNewUser($userInfo)
    {
        $this->db->trans_start();
        $this->db->insert('core_users', $userInfo);
        
        $insert_id = $this->db->insert_id();
        
        $this->db->trans_complete();
        
        return $insert_id;
    }
    
    /**
     * This function used to get user information by id
     * @param number $userid : This is user id
     * @return array $result : This is user information
     */
    public function getUserInfo($userid)
    {
        $this->db->select('userid, name, email, mobile, roleid, ip_filter_list');
        $this->db->from('core_users');
        $this->db->where('isdeleted', 0);
        $this->db->where('userid', $userid);
        $query = $this->db->get();
        
        return $query->result();
    }
    
	
    /**
     * This public function is used to update the user information
     * @param array $userInfo : This is users updated information
     * @param number $userid : This is user id
     */
    public function editUser($userInfo, $userid)
    {
        $this->db->where('userid', $userid);
        $this->db->update('core_users', $userInfo);
        
        return TRUE;
    }
    
	
    /**
     * This function is used to delete the user information
     * @param number $userid : This is user id
     * @return boolean $result : TRUE / FALSE
     */
    public function deleteUser($userid, $userInfo)
    {
		/*
		$this->db->where('userid', $userid);
        $this->db->update('core_users', $userInfo);
        */
        $this->db->where('userid', $userid);
        $this->db->delete('core_users');
		
        return $this->db->affected_rows();
    }



    /**
     * This function is used to delete the user information
     * @param number $userid : This is user id
     * @return boolean $result : TRUE / FALSE
     */
    public function deleteUserFromDb($userid)
    {
		
        //die($userid);
		$this->db->where('userid', $userid);
        $this->db->delete('core_users');
		
        return $this->db->affected_rows();
    }




    /**
     * This function is used to match users password for change password
     * @param number $userid : This is user id
     */
    public function matchOldPassword($userid, $oldPassword)
    {
        $this->db->select('userid, password');
        $this->db->where('userid', $userid);        
        $this->db->where('isdeleted', 0);
        $query = $this->db->get('core_users');
        
        $user = $query->result();

        if(!empty($user)){
            if(verifyHashedPassword($oldPassword, $user[0]->password)){
                return $user;
            } else {
                return array();
            }
        } else {
            return array();
        }
    }
    
    /**
     * This function is used to change users password
     * @param number $userid : This is user id
     * @param array $userInfo : This is user updation info
     */
    public function changePassword($userid, $userInfo)
    {
        $this->db->where('userid', $userid);
        $this->db->where('isdeleted', 0);
        $this->db->update('core_users', $userInfo);
        
        return $this->db->affected_rows();
    }


    /**
     * This function is used to get user log history count
     * @param number $userid : This is user id
     */
    	
    public function logHistoryCount($userid)
    {
        $this->db->select('*');
        $this->db->from('core_log as BaseTbl');

        if ($userid == NULL)
        {
            $query = $this->db->get();
            return $query->num_rows();
        }
        else
        {
            $this->db->where('BaseTbl.userid', $userid);
            $query = $this->db->get();
            return $query->num_rows();
        }
    }

    /**
     * This function is used to get user log history
     * @param number $userid : This is user id
     * @return array $result : This is result
     */
    public function logHistory($userid)
    {
        $this->db->select('*');        
        $this->db->from('core_log as BaseTbl');

        if ($userid == NULL)
        {
            $this->db->order_by('BaseTbl.createddtm', 'DESC');
            $query = $this->db->get();
            $result = $query->result();        
            return $result;
        }
        else
        {
            $this->db->where('BaseTbl.userid', $userid);
            $this->db->order_by('BaseTbl.createddtm', 'DESC');
            $query = $this->db->get();
            $result = $query->result();
            return $result;
        }
    }

    /**
     * This function used to get user information by id
     * @param number $userid : This is user id
     * @return array $result : This is user information
     */
    public function getUserInfoById($userid)
    {
        $this->db->select('userid, name, email, mobile, roleid');
        $this->db->from('core_users');
        $this->db->where('isdeleted', 0);
        $this->db->where('userid', $userid);
        $query = $this->db->get();
        
        return $query->row();
    }

	

    /**
     * This function is used to return the size of the table
     * @param string $tablename : This is table name
     * @param string $dbname : This is database name
     * @return array $return : Table size in mb
     */
    public function gettablemb($tablename,$dbname)
    {
        switch($this->db->dbdriver){
			case 'mysql':
			case 'mysqli':	
				$this->db->select('round(((data_length + index_length)/1024/1024),2) as total_size');
				$this->db->from('information_schema.tables');
				$this->db->where('table_name', $tablename);
				$this->db->where('table_schema', $dbname);			
			break;
			
			case 'postgre':
				$this->db->select('round(pg_total_relation_size (oid) / ( 1024.0 * 1024.0 ), 2) as total_size');
				$this->db->from('pg_class c ');
				$this->db->where('relname', $tablename);				
			break;
			
			case 'mssql':
			
			break;				
		}

        $query = $this->db->get($tablename);
        
        return $query->row();
    }

    /**
     * This function is used to delete core_log table records
     */
    public function clearlogtbl()
    {
        $this->db->truncate('core_log');
        return TRUE;
    }

    /**
     * This function is used to delete core_log_backup table records
     */
    public function clearlogBackuptbl()
    {
        $this->db->truncate('core_log_backup');
        return TRUE;
    }

    /**
     * This function is used to get user log history
     * @return array $result : This is result
     */
    public function logHistoryBackup()
    {
        $this->db->select('*');        
        $this->db->from('core_log_backup as BaseTbl');
        $this->db->order_by('BaseTbl.createddtm', 'DESC');
        $query = $this->db->get();
        $result = $query->result();        
        return $result;
    }


    /**
     * This function is used to get the logs count
     * @return array $result : This is result
     */
    public function logsCount()
    {
        $this->db->select('*');
        $this->db->from('core_log as BaseTbl');
        $query = $this->db->get();
        return $query->num_rows();
    }

    /**
     * This function is used to get the users count
     * @return array $result : This is result
     */
    public function usersCount()
    {
        $this->db->select('*');
        $this->db->from('core_users as BaseTbl');
        $this->db->where('isdeleted', 0);
        $query = $this->db->get();
        return $query->num_rows();
    }

    public function getUserStatus($userid)
    {
        $this->db->select('BaseTbl.status');
        $this->db->where('BaseTbl.userid', $userid);
        $this->db->limit(1);
        $query = $this->db->get('core_users as BaseTbl');

        return $query->row();
    }
	
	
    /**
     *  Carica le impostazioni generali
     */
	public function loadSettings(){		
        $this->db->select('*');
        $this->db->from('core_settings');
        $query = $this->db->get();
        
        $objSettings = $query->result();	
		//print'<pre>';print_r($objSettings);print'</pre>';

		$arraySetting = (array)$objSettings;
		$arraySetting = json_decode(json_encode($objSettings), true);
		$arraySetting[0]['nrInvoice'] = NULL;
		//print'<pre>';print_r($arraySetting);print'</pre>';
		
		$objSettings = array(0=>(object)$arraySetting[0]);
		//print'<pre>';print_r($objSettings);print'</pre>';
		
		return $objSettings;
    }

	
    /**
     * This function is used to update settings
     */
    public function editSettings($arraySettings)
    {
        $this->db->update('core_settings', $arraySettings);
        
        return $this->db->affected_rows();
    }


	
	
    /**
     * This function is used to get the roles listing count
     * @param string $searchText : This is optional search text
     * @return number $count : This is row count
     */
    public function rolesListingCount($searchText = ''){
        $this->db->select('BaseTbl.roleid, BaseTbl.role, BaseTbl.isdeletable, BaseTbl.isupdatable');
        $this->db->from('core_roles as BaseTbl');
        if(!empty($searchText)) {
            $likeCriteria = "(BaseTbl.role  LIKE '%".$searchText."%')";
            $this->db->where($likeCriteria);
        }
        $query = $this->db->get();
        
        return $query->num_rows();
    }
	
	
	
    /**
     * This function is used to get the roles listing
     * @param string $searchText : This is optional search text
     * @param number $page : This is pagination offset
     * @param number $segment : This is pagination limit
     * @return array $result : This is result
     */
    public function rolesListing($searchText = '', $page, $segment){
		$this->db->select('BaseTbl.roleid, BaseTbl.role, BaseTbl.isdeletable, BaseTbl.isupdatable');
        $this->db->from('core_roles as BaseTbl');
        if(!empty($searchText)) {
            $likeCriteria = "(BaseTbl.role  LIKE '%".$searchText."%')";
            $this->db->where($likeCriteria);
        }		
		//$this->db->limit($page, $segment);
        $result = $this->db->get()->result();		
		
        return $result;
    }
    

	/**
     *  This function used to get all roles  
     *  @return array $result : This is result 
	 */
    public function getAllRoles(){
        $this->db->select('BaseTbl.roleid, role.email');
        $this->db->from('core_roles as BaseTbl');
 
        $query = $this->db->get();
        
        $result = $query->result();        
        return $result;
    }	
	
	
    /**
     * This function used to get role information by id
     * @param number $roleid : This is role id
     * @return array $result : This is user information
     */
    public function getRoleInfo($roleid){
        $this->db->select('roleid, role');
        $this->db->from('core_roles');
        $this->db->where('roleid', $roleid);
                
        return $this->db->get()->row();
    }

	
    /**
     * This function used to get role information by id
     * @param number $roleid : This is role id
     * @return array $result : This is user information
     */
    public function getPermissionRole($roleid){
        switch($this->db->dbdriver){
			case 'mysql':
			case 'mysqli':	
				$select = "cml.mod_id, cml.mod_name, cml.mod_title, cml.mod_parentid,
							crp.perm_read,crp.perm_write, crp.perm_update,crp.perm_delete";
				$this->db->select($select);
				$this->db->from('core_module_list cml');
                $this->db->where_in('cml.mod_type', array('mod_gen',"mod_gen_aggr"));
                $this->db->where('cml.admin_perm_only', "N");
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
			
			break;				
		}



        $query = $this->db->get();
        
        $result = $query->result();  
        return $result;
    }

	
	/*
     * This function is used to add new roles to system
     * @param array $roleInfo : This is role updated information
     * @return number $insert_id : This is last inserted id
     */
    public function createRole($roleInfo){
        $this->db->trans_start();
        $this->db->insert('core_roles', $roleInfo);
        
        $insert_id = $this->db->insert_id();
        
        $this->db->trans_complete();
        
        return $insert_id;
    }	
	
	
    /**
     * This public function is used to update the role
     * @param array $roleInfo : This is role updated information
     * @param number $roleid : This is role id
     */
    public function updateRole($roleid, $roleInfo){
		 
        $this->db->where('roleid', $roleid);
        $this->db->update('core_roles', $roleInfo);
        
        return TRUE;
    }	
	
	
	
    /**
     * This public function is used to update the permission role
     * @param number $roleid : This is role id
     * @param array $permissionList : This is role permission updated information
     */
    public function updatePermission($roleid, $permissionList){
        $this->db->where('role_id', $roleid);
        $this->db->delete('core_roles_permission');
		$permissionByMod = array();
		
		
		foreach($permissionList as $key => $permission){
			$permissionInsert = array();
			$permissionArray = explode("|",$permission);
			
			$mod_id = $permissionArray[0];
			$permission_type = $permissionArray[1];
			$permissionInsert['role_id'] = $roleid;
			$permissionInsert['mod_id'] = $mod_id;
			$permissionInsert['mod_name'] = "";
			$permissionInsert[$permission_type] = 'Y';
			
			$permissionByMod[$mod_id][] = $permissionInsert;
		}
		
		
		
		
		foreach($permissionByMod as $keyMod => $permissionMod){
			$arrayInsert = array();
			foreach($permissionMod as $key => $value){
				foreach($value as $keyField => $valueField){
					$arrayInsert[$keyField] = $valueField;
				}	
			}
			$this->db->insert('core_roles_permission', $arrayInsert);	
		}	
        return TRUE;
    }	
	

	
	public function getUserCountByRoleId($roleid){
        $this->db->select('roleid');
        $this->db->from('core_users');
        $this->db->where('roleid', $roleid);		
        $query = $this->db->get();
        
        return $query->num_rows();		
	}
	
    /**
     * This function is used to delete the role from system
     * @param number $roleid : This is role id
     * @return boolean $result : TRUE / FALSE
     */
    public function deleteRole($roleid) {		
	
        $this->db->where('role_id', $roleid);
        $this->db->delete('core_roles_permission');
		
        $this->db->where('roleid', $roleid);
        $this->db->delete('core_roles');
        
        return $this->db->affected_rows();
    }
	

    /**
     * This function is used to delete the role from system
     * @param number $roleid : This is role id
     * @return boolean $result : TRUE / FALSE
     */
    public function deleteMassiveRole($roleListArray) {
		if(is_array($roleListArray)){
			$this->db->where_in("roleid", $roleListArray);
			$this->db->delete('core_roles');			
		}
        
        return $this->db->affected_rows();
    }
	
	
	public function execSQL($sql){
		$query = $this->db->query($sql);
	}
	
	
	/**
     *  This function used to get all roles  
     *  @return array $result : This is result 
	 */
    public function getAllModInstalled(){
        $this->db->select('*');
        $this->db->from('core_module_list');
		$this->db->where('mod_type', 'mod_gen');
		$this->db->order_by('position', 'ASC'); 
        $query = $this->db->get();
        
        $result = $query->result();        
        return $result;
    }		
	
	
    public function getAllModActive(){
        $this->db->select('*');
        $this->db->from('core_module_list');
		$this->db->where('mod_type', 'mod_gen');
		$this->db->where('active', 'Y');
		$this->db->order_by('position', 'ASC'); 
        $query = $this->db->get();
        
        $result = $query->result();  
		
        return $result;
    }	

	
    public function getAllModGenNoAggr(){
        $this->db->select('*');
        $this->db->from('core_module_list');
		$this->db->where('mod_type', 'mod_gen');
		$this->db->where('mod_parentid', '0');
		$this->db->where('active', 'Y');
		$this->db->order_by('position', 'ASC'); 
        $query = $this->db->get();
        
        $result = $query->result();  
		
        return $result;
    }	
	
	
	/**
	 * LISTO TUTTI MODULI, SIA AGGREGATI CHE STAND-ALONE
	 */
    public function getAllModGenByAggrAndStandalone(){
        
		//LISTO TUTTI MODULI PER AGGREGAZIONE
		$this->db->select('core_module_list.mod_id as main_mod_id,
								core_module_list.mod_name as main_mod_name,
								core_module_list.mod_title as main_mod_title,
								core_module_list.mod_icon as main_mod_icon,
								core_module_list.position as main_position,
							sub_mod.*');
        $this->db->from('core_module_list');
		$this->db->join("( SELECT * 
							FROM core_module_list 
							WHERE 1=1
							AND active = 'Y'
							ORDER BY position ASC
						)sub_mod", 'sub_mod.mod_parentid = core_module_list.mod_id');
		$this->db->where('core_module_list.mod_type', 'mod_gen_aggr');
		$this->db->where('core_module_list.active', 'Y');
		$this->db->order_by('core_module_list.position', 'ASC'); 
 
		$query = $this->db->get();
        
		$arrayModByAggr = array();
        $resultModByAggr = $query->result();
		foreach($resultModByAggr as $keyModByAggr => $valueModByAggr){
			$arrayModByAggr[$valueModByAggr->main_mod_id]['value_settings'] = array('main_mod_title' => $valueModByAggr->main_mod_title,
																'main_mod_icon' => $valueModByAggr->main_mod_icon,
																'main_position' => $valueModByAggr->main_position,
																'have_child' => 'TRUE');
			
			
			$arrayModByAggr[$valueModByAggr->main_mod_id]['sub_mod'][$valueModByAggr->position] = array('mod_id' => $valueModByAggr->mod_id,
																'class_name' => $valueModByAggr->class_name,
																'mod_title' => $valueModByAggr->mod_title,
																'mod_icon' => $valueModByAggr->mod_icon,
																'position'  => $valueModByAggr->position,
																'have_child' => 'FALSE');
			ksort($arrayModByAggr[$valueModByAggr->main_mod_id]['sub_mod']);													
		}		
		
        //LISTO TUTTI MODULI STAND-ALONE
		$arrayModNoAggr = array();
		$resultModNoAggr = $this->getAllModGenNoAggr();
		foreach($resultModNoAggr as $keyModNoAggr => $valueModNoAggr){
			$arrayModNoAggr[$valueModNoAggr->mod_id]['value_settings'] = array('mod_id' => $valueModNoAggr->mod_id,
																'class_name' => $valueModNoAggr->class_name,
																'mod_title' => $valueModNoAggr->mod_title,
																'mod_icon' => $valueModNoAggr->mod_icon,
																'position'  => $valueModNoAggr->position,
																'have_child' => 'FALSE');
		}	
	
		//LI MERGO IN UNICO VETTORE
		$arrayMod = array_merge($arrayModByAggr, $arrayModNoAggr);
		
		return $arrayMod;
    }	

	
    public function getModById($mod_id){
        $this->db->select('*');
        $this->db->from('core_module_list');
		$this->db->where('mod_id', $mod_id);
		$this->db->order_by('position', 'DESC'); 
 
        return $this->db->get()->row();      
    }	
	
	
	public function deleteMod($mod_id){
        $this->db->where('mod_id', $mod_id);
		$this->db->where('is_deletable', 'Y');
        $this->db->delete('core_module_list');
		
        return $this->db->affected_rows();
	}	
	
	
	public function deleteModPermission($mod_id){
        $this->db->where('mod_id', $mod_id);
        $this->db->delete('core_roles_permission');
		
        return $this->db->affected_rows();
	}		
	
	
	public function activeDisactiveMod($moduleInfo, $mod_id){
        $this->db->where('mod_id', $mod_id);
        $this->db->update('core_module_list', $moduleInfo);
        
        return TRUE;	
	}	
	
	
	public function changePositionMod($moduleInfo, $mod_id){
        $this->db->where('mod_id', $mod_id);
        $this->db->update('core_module_list', $moduleInfo);
	}	
		
	

    public function getTableList()
    {
        $this->db->select('TABLE_NAME');
        $this->db->from('INFORMATION_SCHEMA.TABLES');
		$this->db->where('TABLE_SCHEMA', $this->db->database);
		$this->db->order_by('TABLE_NAME', 'DESC'); 
		
        $query = $this->db->get();
        
        $result = $query->result();        
        return $result;
	}
		
		
    public function getTableColumns($tableName)
    {
        $this->db->select('COLUMN_NAME,COLUMN_KEY,DATA_TYPE,CHARACTER_MAXIMUM_LENGTH,NUMERIC_PRECISION,NUMERIC_SCALE,COLUMN_COMMENT,IS_NULLABLE');
        $this->db->from('INFORMATION_SCHEMA.COLUMNS');
		$this->db->where('TABLE_SCHEMA', $this->db->database);
		$this->db->where('TABLE_NAME', $tableName);
		$this->db->order_by('COLUMN_NAME', 'DESC'); 

        $query = $this->db->get();
        
        $result = $query->result();        
        return $result;		
	}	
	
	
	public function importExcelData($tableName, $excelData){
		$this->db->trans_begin();		
		$this->db->empty_table($tableName);
		
		foreach($excelData as $key => $value){
			$this->db->insert($tableName, $value);	
		}
				
		if ($this->db->trans_status() === FALSE){
			$this->db->trans_rollback();
			return false;
		} else {
			$this->db->trans_commit();
			return true;
		}		
		
	}

		
	public function loadAllegati($moduleName,$entryId){
		$sql = "SELECT * FROM _mod_allegati 
				WHERE mod_name = '$moduleName' 
				AND fk_key = '$entryId'";
		//echo $sql;		
		$allegati = $this->db->query($sql)->result_array();	
		
		return $allegati;
	}	
	
	
	public function rimuoviAllegato($moduleName, $entryId, $fileName){
		$sql = "DELETE FROM _mod_allegati 
				WHERE mod_name = '$moduleName' 
				AND fk_key = '$entryId'
				AND allegato = '$fileName' ";		
		$res = $this->db->query($sql);		
	}


	
	public function rimuoviAllegatoBlob($moduleName, $fieldName, $entryId, $fieldType){
        $orig_db_debug = $this->db->db_debug;
        $this->db->db_debug = FALSE;

		$sql = "UPDATE $moduleName
                SET  $fieldName = NULL
				WHERE id = '$entryId'";		  
		$resUpdate = $this->db->query($sql);	
        
        if($fieldType == 'FIELD_BLOB_IMG'){
            $sql = "DELETE FROM _mod_allegati_blob WHERE fk_key ='$entryId' AND field_name ='$fieldName' ";		  
            $resDelete = $this->db->query($sql);	
        }

        $this->db->db_debug = $orig_db_debug;

        $Err = $this->db->error();

        return $Err['code'];  

	}


	public function rimuoviTuttiGliAllegati($moduleName, $entryId){
		$sql = "DELETE FROM _mod_allegati 
				WHERE mod_name = '$moduleName' 
				AND fk_key = '$entryId' ";		
		$res = $this->db->query($sql);		
	}
	
	
	public function loadExtAdmitted(){
		$sql = "SELECT system_file_ext_admitted FROM core_settings";		
		$extAdmitted = $this->db->query($sql)->result_array();	
		
		return $extAdmitted;
	}	


	public function loadImgExtAdmitted(){
		$sql = "SELECT system_imgfile_ext_admitted FROM core_settings";		
		$extAdmitted = $this->db->query($sql)->result_array();	
		
		return $extAdmitted;
	}	


    /**
     * 
     */
	public function caricaAllegato($moduleName, $entryId, $fileName){
		$sql = "INSERT INTO _mod_allegati(mod_name,fk_key, allegato)  
				VALUES('$moduleName',$entryId, '$fileName')";		
		$res = $this->db->query($sql);		
	}
	
    
	
    /**
     * 
     */
    public function getAllegatoBlob($moduleName, $fieldName,$entryId){
        $sql ="SELECT ".$moduleName.".".$fieldName." as allegato, _mod_allegati_blob.nome_allegato
                    FROM $moduleName
                    INNER JOIN _mod_allegati_blob
                        ON _mod_allegati_blob.fk_key = $moduleName.id
                        AND _mod_allegati_blob.field_name = '".$fieldName."'
                    WHERE $moduleName.id = ".$entryId;      
        //echo $sql;            

        return $this->db->query($sql)->result_array();	
    }
	

	public function  checkRoleHasPrivilege($child_class_caller, $roleId){
		$hasPrivilege = FALSE;
		$sql = "SELECT count(*) as counter 
					FROM  core_roles_permission
					INNER JOIN core_module_list
						on core_roles_permission.mod_id = core_module_list.mod_id
					WHERE core_roles_permission.role_id = ".$roleId."
					AND core_module_list.mod_name = '".strtolower($child_class_caller)."'";	
       // die($sql);
		$arrayReturn = $this->db->query($sql)->result_array();
		
		//SE TROVATO RESTITUISCO TRUE
		if((int)$arrayReturn[0]['counter'] > 0){
			$hasPrivilege = TRUE;
		}
	
	
		//SE Home, E' SEMPRE TRUE, TUTTI GLI UTENTI HANNO ACCESSO ALLA PAGINA INIZIALE
		//Nella Home e' presente la pagina della dashboard
		if(strtolower($child_class_caller) == 'home'){
			$hasPrivilege = TRUE;
		}
		
		return $hasPrivilege;					
	}
	
}

  