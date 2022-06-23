<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

class Home_model extends CI_Model
{

    /**
     *  LISTO TUTTI MODULI
     *  
     */
    public function getAllModByRole($roleId){				
		$sql = "SELECT core_module_list.*
					FROM  core_module_list
					INNER JOIN core_roles_permission
						on core_roles_permission.mod_id = core_module_list.mod_id
					WHERE 1=1
						AND mod_type =  'mod_gen'
						AND mod_parentid =  '0'
						AND active =  'Y'
						AND core_roles_permission.role_id = ".$roleId;
					
		$arrayReturn =  (object)$this->db->query($sql)->result_object();

		return 	$arrayReturn;	
		
    }	
		
	
	/**
	 * LISTO TUTTI MODULI, SIA AGGREGATI CHE STAND-ALONE
	 */
    public function getAllModAggrByRole($roleId){
        $arrayModNoAggr = array();
		$arrayModByAggr = array();
		
		//LISTO TUTTI MODULI PER AGGREGAZIONE
		$this->db->select('core_module_list.mod_id as main_mod_id,
								core_module_list.mod_name as main_mod_name,
								core_module_list.mod_title as main_mod_title,
								core_module_list.mod_icon as main_mod_icon,
								core_module_list.position as main_position,
								core_module_list.display_dashboard,
								core_module_list.class_dashboard_area,
							sub_mod.*');
        $this->db->from('core_module_list');
		$this->db->join("( SELECT * 
							FROM core_module_list 
							WHERE mod_type = 'mod_gen' 
							AND active = 'Y'
							ORDER BY position ASC
						)sub_mod", 'sub_mod.mod_parentid = core_module_list.mod_id');
		$this->db->join("core_roles_permission",'core_roles_permission.mod_id = sub_mod.mod_id','inner');
		$this->db->where('core_module_list.mod_type', 'mod_gen_aggr');
		$this->db->where('core_module_list.active', 'Y');
		$this->db->where('core_roles_permission.role_id',$roleId);	
		$this->db->order_by('core_module_list.position', 'ASC'); 
		$query = $this->db->get();
        
		
        $resultModByAggr = $query->result();
		foreach($resultModByAggr as $keyModByAggr => $valueModByAggr){
			$arrayModByAggr[$valueModByAggr->main_mod_id]['value_settings'] = array('main_mod_title' => $valueModByAggr->main_mod_title,
																'main_mod_icon' => $valueModByAggr->main_mod_icon,
																'main_position' => $valueModByAggr->main_position,
																'display_dashboard' => $valueModByAggr->display_dashboard,
																'class_dashboard_area' => $valueModByAggr->class_dashboard_area,
																'have_child' => 'TRUE');
			
			
			$arrayModByAggr[$valueModByAggr->main_mod_id]['sub_mod'][$valueModByAggr->position] = array('mod_id' => $valueModByAggr->mod_id,
																'class_name' => $valueModByAggr->class_name,
																'mod_title' => $valueModByAggr->mod_title,
																'mod_icon' => $valueModByAggr->mod_icon,
																'position'  => $valueModByAggr->position,
																'display_dashboard' => $valueModByAggr->display_dashboard,
																'class_dashboard_area' => $valueModByAggr->class_dashboard_area,																
																'have_child' => 'FALSE');
			ksort($arrayModByAggr[$valueModByAggr->main_mod_id]['sub_mod']);													
		}		
		
 
        //LISTO TUTTI MODULI STAND-ALONE
		$resultModNoAggr = $this->getAllModByRole($roleId);
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

	
	public function getNotifyList(String $type = NULL){
		$notifyList = array();

		$sql = "SELECT * FROM mod_scadenze_notifiche WHERE 1=1 ";
		if($type != NULL){
			$sql .= " AND tipo_scadenza ='".$type."'";
		}
				
		$arrayReturn =   $this->db->query($sql)->result_array();	
				
		foreach($arrayReturn  as $key => $val){
			$msg_notifica =  $val['msg_notifica'];
			if(trim($val['sql_command']) != ""){
				$res = $this->db->query($val['sql_command'])->result_array();
 
				foreach($res as $kres => $vres){
				    $vres_key = array_keys($vres);
					foreach($vres_key as $k => $v){
						if(substr_count($msg_notifica, "<".$v.">") == 0){
							$msg_notifica = $val['msg_notifica'];
						}							
						$msg_notifica = str_replace("<".$v.">",$vres[$v],$msg_notifica);
					}

					$id = NULL;
					if(isset($vres['id'])){
						$id = $vres['id'];
					}					
					$notifyList[] = array('msg_notifica' => $msg_notifica, 
						"mod_name" => $val["mod_name"],
						"id" => $id,
						"icona_notifica" => $val["icona_notifica"]);
				}

 
			}
		}
		 
        return $notifyList;

    }	



	public function getNotifyListCount(){
		$sql = "SELECT * FROM mod_scadenze_notifiche";
		$notifyList = array();		
		 
		$notifyList["tessere_assoc_non_presenti"] = 0;		
		$notifyList["tessere_assoc_finite"] = 0;	
		$notifyList["mag_non_assoc"] = 0;	
		$notifyList["cert_medici_non_presenti"] = 0;
		$notifyList["cert_medici_scaduti"] = 0;
		$notifyList["auto_cert_gp_non_presenti"] = 0;
		$notifyList["auto_cert_gp_scaduti"] = 0;
		$notifyList["gp_non_presenti"] = 0;
		$notifyList["gp_scaduti"] = 0;		
		$notifyList["pagam_allievi_scaduti"] = 0;
		$notifyList["pagam_allievi_nessuno"] = 0;
		$notifyList["pagam_collaboratore_scaduti"] = 0;
		$notifyList["pagam_collaboratore_nessuno"] = 0;
		$notifyList["contratto_collaboratore_scaduti"] = 0;
		$notifyList["contratto_collaboratore_nessuno"] = 0;

		$arrayReturn =   $this->db->query($sql)->result_array();	
		
		
		foreach($arrayReturn  as $key => $val){
			//print'<pre>';print_r($val);	
			$msg_notifica =  $val['msg_notifica'];
			if(trim($val['sql_command']) != ""){
				$res = $this->db->query($val['sql_command'])->result_array();
 
				foreach($res as $kres => $vres){
				    $vres_key = array_keys($vres);
					foreach($vres_key as $k => $v){
						if(substr_count($msg_notifica, "<".$v.">") == 0){
							$msg_notifica = $val['msg_notifica'];
						}							
						$msg_notifica = str_replace("<".$v.">",$vres[$v],$msg_notifica);
					}

					$id = NULL;
					if(isset($vres['id'])){
						$id = $vres['id'];
					}					
					$notifyList[] = array('msg_notifica' => $msg_notifica, 
						"mod_name" => $val["mod_name"],
						"id" => $id,
						"icona_notifica" => $val["icona_notifica"]);

 
					if(isset($notifyList[$val['tipo_scadenza']])){
						$notifyList[$val['tipo_scadenza']] += 1;
					}  

				}
			}

 

		}
		 
        return $notifyList;

    }	


}

  