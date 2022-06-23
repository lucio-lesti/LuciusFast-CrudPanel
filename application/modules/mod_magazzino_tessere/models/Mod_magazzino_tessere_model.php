<?php
if (!defined('BASEPATH')){
	exit('No direct script access allowed');
}

require APPPATH . '/libraries/BaseModel.php';

class Mod_magazzino_tessere_model extends BaseModel
{

	public function __construct(){
		parent::__construct();
		$this->table = 'mod_magazzino_tessere';
		$this->id = 'id';
		$this->mod_name = 'mod_magazzino_tessere';
		$this->mod_type = 'crud';


		//NOTE:NELLA FUNZIONE 'setFieldArrayGrid' INDICARE NEL VETTORE CHE SI COLLEGA ALLA TABELLA REFERENZIATA
		//ALLA CHIAVE 'NOME', IL NOMINATIVO DEL CAMPO COLLEGATO

		//NOTE 2: NELLA FUNZIONE 'setFieldArrayGrid' se nella chiave "nome" si usa una array, la classe "BaseModel" lo interpreta come un concat

		$this->setFieldArrayGrid('nome', FIELD_STRING);
		$this->setFieldArrayGrid('fk_affiliazione',FIELD_NUMERIC,'mod_affiliazioni',array("id" => 'id', "nome" => 'nome'));
		$this->setFieldArrayGrid('id', FIELD_NUMERIC);
		
		//ESEMPIO DI TABELLA REFERENZIATA CHE NON HA IL CAMPO 'NOME'. QUI INDICHIAMO AL PROGRAMMA QUALE E' IL CAMPO DA USARE COME CAMPO 'NOME'
		//P.S.QUESTA OPERAZIONE E' POSSIBILE FARLA ANCHE NEL METODO 'setFieldArrayGrid'
		//$this->arrayColumnsReferenced['mod_sport']['nome'] = "sport"; 

	}


	/**
	* Funzione caricamento della master details, tabella _mod_magazzino_tessere_lista_tessere
	* @param mixed $id
	* @param string $isAjax
	* @return string
	**/
	public function getMasterDetail_mod_magazzino_tessere_lista_tessere($id, $isAjax = 'FALSE'){

		$sql = "SELECT _mod_magazzino_tessere_lista_tessere.*,
					mod_magazzino_tessere.nome AS mod_magazzino_tessere_nome,
					CONCAT(mod_anagrafica.nome, ' ',mod_anagrafica.cognome, ' - ' , mod_anagrafica.codfiscale) as allievo
				FROM   _mod_magazzino_tessere_lista_tessere
					INNER JOIN mod_magazzino_tessere
							ON _mod_magazzino_tessere_lista_tessere.fk_magazzino_tessere =
								mod_magazzino_tessere.id
					LEFT JOIN mod_tesseramenti
							ON mod_tesseramenti.fk_tessera_associativa =
							_mod_magazzino_tessere_lista_tessere.id
					LEFT JOIN mod_anagrafica
							ON mod_anagrafica.id = mod_tesseramenti.fk_anagrafica
				WHERE  _mod_magazzino_tessere_lista_tessere.fk_magazzino_tessere = ".$id;		
		//echo $sql;
		$row =  $this->db->query($sql)->result_array();	
		return $row;
	}


	/**
	* Funzione caricamento delle liste per inserimento multiplo master details, tabella _mod_magazzino_tessere_lista_tessere
	* @param mixed $id
	* @param string $isAjax
	* @return string
	**/
	public function getList_mod_magazzino_tessere_lista_tessere($id, $isAjax = 'FALSE'){
		$sql ="SELECT _mod_magazzino_tessere_lista_tessere.* 
			,mod_magazzino_tessere.nome AS mod_magazzino_tessere_nome
					FROM _mod_magazzino_tessere_lista_tessere
				 INNER JOIN mod_magazzino_tessere
							ON _mod_magazzino_tessere_lista_tessere.fk_magazzino_tessere = mod_magazzino_tessere.id
			WHERE _mod_magazzino_tessere_lista_tessere.fk_magazzino_tessere = ".$id;
		$row =  $this->db->query($sql)->result_array();	
		return $row;
	}



	public function importExcelData($tableName, $excelData, $fk_magazzino){
		$this->db->trans_begin();		
		
		$sql ="SELECT * FROM _mod_magazzino_tessere_lista_tessere 
				WHERE fk_magazzino_tessere = $fk_magazzino AND codice_tessera LIKE 'ND%'";
		$row =  $this->db->query($sql)->result_array();	
		foreach($row as $kRow => $vRow){
			foreach($excelData as $key => $value){
				$sqlCheck ="SELECT COUNT(*) AS c FROM _mod_magazzino_tessere_lista_tessere 
							WHERE codice_tessera = '".$value['codice_tessera']."'";
				$rowCheck =  $this->db->query($sqlCheck)->result_array();	
				if((int)$rowCheck[0]['c'] == 0){
					$this->db->query("UPDATE _mod_magazzino_tessere_lista_tessere SET codice_tessera ='".$value['codice_tessera']."' WHERE id = ".$vRow['id']." AND fk_magazzino_tessere = ".$fk_magazzino);
				}				

				unset($excelData[$key]);
			}	
		}

		foreach($excelData as $key => $value){
			//$this->db->insert($tableName, $value);	
			$insert_query = $this->db->insert_string($tableName, $value);
			$insert_query = str_replace('INSERT INTO', 'INSERT IGNORE INTO', $insert_query);
			$this->db->query($insert_query);
		}
				
		if ($this->db->trans_status() === FALSE){
			$this->db->trans_rollback();
			return false;
		} else {
			$this->db->trans_commit();
			return true;
		}		
		
	}


	public function getAffiliazioneId($fk_magazzino){
		$sql = "SELECT fk_affiliazione FROM mod_magazzino_tessere WHERE id = ".$fk_magazzino;
		$row =  $this->db->query($sql)->result_array();	
		$fk_affiliazione = $row[0]['fk_affiliazione'];	

		return $fk_affiliazione;
	}	


	public function getEnte($fk_affiliazione){
		$sql = "SELECT mod_enti.id as id
					,mod_enti.nome as nome FROM mod_enti
				INNER JOIN mod_affiliazioni
					ON mod_affiliazioni.fk_ente = mod_enti.id
					AND mod_affiliazioni.id = ".$fk_affiliazione;
		$row =  $this->db->query($sql)->result_array();	
		
		//print'<pre>';print_r($row);

		return $row;
	}


}