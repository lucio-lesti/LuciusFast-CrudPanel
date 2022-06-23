<?php
if (!defined('BASEPATH')){
	exit('No direct script access allowed');
}

require APPPATH . '/libraries/BaseModel.php';

class Mod_calendario_model extends BaseModel
{

	public function __construct(){
		parent::__construct();
		$this->table = 'mod_calendario';
		$this->id = 'id';
		$this->mod_name = 'mod_calendario';
		$this->mod_type = 'crud';

	}


	public function json($searchFilter) {

		//print'<pre>';print_r($_REQUEST);die();

		$startArray = explode("-",$_REQUEST['start']);
		$endArray = explode("-",$_REQUEST['end']);
		$start = $startArray[0]."-".$startArray[1]."-". substr($startArray[2], 0,2);
		$end = $endArray[0]."-".$endArray[1]."-". substr($endArray[2], 0,2);
		$arrayData = array();
		
		$sql = "WITH RECURSIVE dates AS 
			( SELECT '$start' AS DATE UNION ALL SELECT DATE + interval 1 day FROM dates WHERE DATE < '$end' ) SELECT * FROM dates;";
		//echo $sql;
		$row  = $this->db->query($sql)->result_array();

		$andCondition = "";
		if(((isset($_REQUEST['mod_corsi_id'])) && (($_REQUEST['mod_corsi_id']) != ""))){
			$andCondition = " AND mod_corsi.id = ".$_REQUEST['mod_corsi_id'];
		}		
		foreach($row as $k => $v){
			$sqlDate = "SELECT 
						CONCAT('".$v['DATE']."',' ',CAST(_mod_corsi_giorni_orari.ora_da AS CHAR)) AS start,
						CONCAT('".$v['DATE']."',' ',CAST(_mod_corsi_giorni_orari.ora_a AS CHAR)) AS end,
						_mod_corsi_giorni_orari.fk_corso as id,
						mod_corsi.nome as title,
						mod_corsi.id
					FROM `_mod_corsi_giorni_orari`
					INNER JOIN mod_corsi
						ON mod_corsi.id = _mod_corsi_giorni_orari.fk_corso
					WHERE 
					(WEEKDAY('".$v['DATE']."') = 0) AND  giorno_settimana = 'LUNEDI' $andCondition
						UNION
					SELECT  
						CONCAT('".$v['DATE']."',' ',CAST(_mod_corsi_giorni_orari.ora_da AS CHAR)) AS start,
						CONCAT('".$v['DATE']."',' ',CAST(_mod_corsi_giorni_orari.ora_a AS CHAR)) AS end,
						_mod_corsi_giorni_orari.fk_corso as id,
						mod_corsi.nome as title,
						mod_corsi.id
					FROM `_mod_corsi_giorni_orari`
					INNER JOIN mod_corsi
						ON mod_corsi.id = _mod_corsi_giorni_orari.fk_corso
					WHERE 
					(WEEKDAY('".$v['DATE']."') = 1)  AND  giorno_settimana = 'MARTEDI'  $andCondition
						UNION
					SELECT  
						CONCAT('".$v['DATE']."',' ',CAST(_mod_corsi_giorni_orari.ora_da AS CHAR)) AS start,
						CONCAT('".$v['DATE']."',' ',CAST(_mod_corsi_giorni_orari.ora_a AS CHAR)) AS end,
						_mod_corsi_giorni_orari.fk_corso as id,
						mod_corsi.nome as title,
						mod_corsi.id
					FROM `_mod_corsi_giorni_orari`
					INNER JOIN mod_corsi
						ON mod_corsi.id = _mod_corsi_giorni_orari.fk_corso
					WHERE 
					(WEEKDAY('".$v['DATE']."') = 2)  AND  giorno_settimana = 'MERCOLEDI' $andCondition
						UNION
					SELECT  
						CONCAT('".$v['DATE']."',' ',CAST(_mod_corsi_giorni_orari.ora_da AS CHAR)) AS start,
						CONCAT('".$v['DATE']."',' ',CAST(_mod_corsi_giorni_orari.ora_a AS CHAR)) AS end,
						_mod_corsi_giorni_orari.fk_corso as id,
						mod_corsi.nome as title,
						mod_corsi.id
					FROM `_mod_corsi_giorni_orari`
					INNER JOIN mod_corsi
						ON mod_corsi.id = _mod_corsi_giorni_orari.fk_corso
					WHERE 
					(WEEKDAY('".$v['DATE']."') = 3)  AND  giorno_settimana = 'GIOVEDI' $andCondition
						UNION
					SELECT  
						CONCAT('".$v['DATE']."',' ',CAST(_mod_corsi_giorni_orari.ora_da AS CHAR)) AS start,
						CONCAT('".$v['DATE']."',' ',CAST(_mod_corsi_giorni_orari.ora_a AS CHAR)) AS end,
						_mod_corsi_giorni_orari.fk_corso as id,
						mod_corsi.nome as title,
						mod_corsi.id
					FROM `_mod_corsi_giorni_orari`
					INNER JOIN mod_corsi
						ON mod_corsi.id = _mod_corsi_giorni_orari.fk_corso
					WHERE
					(WEEKDAY('".$v['DATE']."') = 4)  AND  giorno_settimana = 'VENERDI' $andCondition
						UNION
					SELECT  
						CONCAT('".$v['DATE']."',' ',CAST(_mod_corsi_giorni_orari.ora_da AS CHAR)) AS start,
						CONCAT('".$v['DATE']."',' ',CAST(_mod_corsi_giorni_orari.ora_a AS CHAR)) AS end,
						_mod_corsi_giorni_orari.fk_corso as id,
						mod_corsi.nome as title,
						mod_corsi.id
					FROM `_mod_corsi_giorni_orari`
					INNER JOIN mod_corsi
						ON mod_corsi.id = _mod_corsi_giorni_orari.fk_corso
					WHERE 
					(WEEKDAY('".$v['DATE']."') = 5)  AND  giorno_settimana = 'SABATO' $andCondition
						UNION
					SELECT  
						CONCAT('".$v['DATE']."',' ',CAST(_mod_corsi_giorni_orari.ora_da AS CHAR)) AS start,
						CONCAT('".$v['DATE']."',' ',CAST(_mod_corsi_giorni_orari.ora_a AS CHAR)) AS end,
						_mod_corsi_giorni_orari.fk_corso as id,
						mod_corsi.nome as title,
						mod_corsi.id
					FROM `_mod_corsi_giorni_orari`
					INNER JOIN mod_corsi
						ON mod_corsi.id = _mod_corsi_giorni_orari.fk_corso
					WHERE 
					(WEEKDAY('".$v['DATE']."') = 6) AND  giorno_settimana = 'DOMENICA' $andCondition ";

			//echo $sqlDate."\n\n";			
			$rowDate  = $this->db->query($sqlDate)->result_array();
			$arrayData = array_merge($arrayData, $rowDate);
 
		}
		echo json_encode($arrayData);
	}	


	/* 
	* Ritorna il numero records di una tabella
	* @param string|null $str_search
	* @return mixed 
	*/
   public function total_rows(String $str_search = null){}

}