<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

class Common_model extends CI_Model{
	
	public function getComuni(){
        $sql = "SELECT * FROM  mod_comuni";
        $arrayReturn = $this->db->query($sql)->result_array();
		
        return $arrayReturn;		
	}		

	
	public function insertLog($dataLog){
		$this->db->insert("mod_logs", $dataLog);
	}


}

  