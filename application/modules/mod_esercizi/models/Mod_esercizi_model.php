<?php
if (!defined('BASEPATH')){
	exit('No direct script access allowed');
}

require APPPATH . '/libraries/BaseModel.php';

class Mod_esercizi_model extends BaseModel
{

	public function __construct(){
		parent::__construct();
		$this->table = 'mod_esercizi';
		$this->id = 'id';
		$this->mod_name = 'mod_esercizi';


		//NOTE:NELLA FUNZIONE 'setFieldArrayGrid' INDICARE NEL VETTORE CHE SI COLLEGA ALLA TABELLA REFERENZIATA
		//ALLA CHIAVE 'NOME', IL NOMINATIVO DEL CAMPO COLLEGATO

		$this->setFieldArrayGrid('data_a', FIELD_DATE);
		$this->setFieldArrayGrid('data_da', FIELD_DATE);
		$this->setFieldArrayGrid('nome', FIELD_STRING);
		$this->setFieldArrayGrid('id', FIELD_NUMERIC);

		//ESEMPIO DI TABELLA REFERENZIATA CHE NON HA IL CAMPO 'NOME'. QUI INDICHIAMO AL PROGRAMMA QUALE E' IL CAMPO DA USARE COME CAMPO 'NOME'
		//P.S.QUESTA OPERAZIONE E' POSSIBILE FARLA ANCHE NEL METODO 'setFieldArrayGrid'
		//$this->arrayColumnsReferenced['mod_sport']['nome'] = "sport"; 

	}


	/**
	* Funzione caricamento della master details, tabella _mod_anagrafica_tessere_interne
	* @param mixed $id
	* @param string $isAjax
	* @return string
	**/
	public function getMasterDetail_mod_anagrafica_tessere_interne($id, $isAjax = 'FALSE'){
		$sql ="SELECT mod_tesseramenti.tessera_interna
				,mod_anagrafica.nome AS mod_anagrafica_nome
				,mod_esercizi.nome AS mod_esercizi_nome
			FROM mod_tesseramenti
			 INNER JOIN mod_anagrafica
							ON mod_tesseramenti.fk_anagrafica = mod_anagrafica.id
			 INNER JOIN mod_esercizi
							ON mod_tesseramenti.fk_esercizio = mod_esercizi.id
			WHERE mod_tesseramenti.fk_esercizio = ".$id;
		$row =  $this->db->query($sql)->result_array();	
		return $row;
	}


	/**
	* Funzione caricamento delle liste per inserimento multiplo master details, tabella _mod_anagrafica_tessere_interne
	* @param mixed $id
	* @param string $isAjax
	* @return string
	**/
	public function getList_mod_anagrafica_tessere_interne($id, $isAjax = 'FALSE'){
		$sql ="SELECT _mod_anagrafica_tessere_interne.* 
			,mod_anagrafica.nome AS mod_anagrafica_nome
			,mod_esercizi.nome AS mod_esercizi_nome
					FROM _mod_anagrafica_tessere_interne
				 INNER JOIN mod_anagrafica
							ON _mod_anagrafica_tessere_interne.fk_anagrafica = mod_anagrafica.id
				 INNER JOIN mod_esercizi
							ON _mod_anagrafica_tessere_interne.fk_esercizio = mod_esercizi.id
			WHERE _mod_anagrafica_tessere_interne.fk_esercizio = ".$id;
		$row =  $this->db->query($sql)->result_array();	
		return $row;
	}



}