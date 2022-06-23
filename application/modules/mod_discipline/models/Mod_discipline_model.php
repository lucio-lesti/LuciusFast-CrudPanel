<?php
if (!defined('BASEPATH')){
	exit('No direct script access allowed');
}

require APPPATH . '/libraries/BaseModel.php';

class Mod_discipline_model extends BaseModel
{

	public function __construct(){
		parent::__construct();
		$this->table = 'mod_discipline';
		$this->id = 'id';
		$this->mod_name = 'mod_discipline';
		$this->mod_type = 'crud';


		//NOTE:NELLA FUNZIONE 'setFieldArrayGrid' INDICARE NEL VETTORE CHE SI COLLEGA ALLA TABELLA REFERENZIATA
		//ALLA CHIAVE 'NOME', IL NOMINATIVO DEL CAMPO COLLEGATO

		//NOTE 2: NELLA FUNZIONE 'setFieldArrayGrid' se nella chiave "nome" si usa una array, la classe "BaseModel" lo interpreta come un concat

		$this->setFieldArrayGrid('codice_disciplina', FIELD_STRING);
		$this->setFieldArrayGrid('fk_sport',FIELD_NUMERIC,'mod_sport',array("id" => 'id', "nome" => 'sport'));
		$this->setFieldArrayGrid('nome', FIELD_STRING);
		$this->setFieldArrayGrid('id', FIELD_NUMERIC);

		//ESEMPIO DI TABELLA REFERENZIATA CHE NON HA IL CAMPO 'NOME'. QUI INDICHIAMO AL PROGRAMMA QUALE E' IL CAMPO DA USARE COME CAMPO 'NOME'
		//P.S.QUESTA OPERAZIONE E' POSSIBILE FARLA ANCHE NEL METODO 'setFieldArrayGrid'
		//$this->arrayColumnsReferenced['mod_sport']['nome'] = "sport"; 

	}


	/**
	* Funzione caricamento della master details, tabella _mod_enti_discipline
	* @param mixed $id
	* @param string $isAjax
	* @return string
	**/
	public function getMasterDetail_mod_enti_discipline($id, $isAjax = 'FALSE'){
		$sql ="SELECT _mod_enti_discipline.* 
				,mod_enti.nome AS mod_enti_nome
				,mod_discipline.nome AS mod_discipline_nome
			FROM _mod_enti_discipline
			 INNER JOIN mod_enti
							ON _mod_enti_discipline.fk_ente = mod_enti.id
			 INNER JOIN mod_discipline
							ON _mod_enti_discipline.fk_disciplina = mod_discipline.id
			WHERE _mod_enti_discipline.fk_disciplina = ".$id;
		$row =  $this->db->query($sql)->result_array();	
		return $row;
	}


	/**
	* Funzione caricamento della master details, tabella _mod_insegnanti_discipline
	* @param mixed $id
	* @param string $isAjax
	* @return string
	**/
	public function getMasterDetail_mod_insegnanti_discipline($id, $isAjax = 'FALSE'){
		$sql ="SELECT _mod_insegnanti_discipline.* 
				,CONCAT(mod_anagrafica.nome,' ' ,mod_anagrafica.cognome,' - ',mod_anagrafica.codfiscale)   AS mod_anagrafica_nome
				,mod_discipline.nome AS mod_discipline_nome
			FROM _mod_insegnanti_discipline
			 INNER JOIN mod_anagrafica
							ON _mod_insegnanti_discipline.fk_anagrafica = mod_anagrafica.id
			 INNER JOIN mod_discipline
							ON _mod_insegnanti_discipline.fk_disciplina = mod_discipline.id
			WHERE _mod_insegnanti_discipline.fk_disciplina = ".$id;
		$row =  $this->db->query($sql)->result_array();	
		return $row;
	}


	/**
	* Funzione caricamento delle liste per inserimento multiplo master details, tabella _mod_enti_discipline
	* @param mixed $id
	* @param string $isAjax
	* @return string
	**/
	public function getList_mod_enti_discipline($id, $isAjax = 'FALSE'){
		$sql ="SELECT _mod_enti_discipline.* 
			,mod_enti.nome AS mod_enti_nome
			,mod_discipline.nome AS mod_discipline_nome
					FROM _mod_enti_discipline
				 INNER JOIN mod_enti
							ON _mod_enti_discipline.fk_ente = mod_enti.id
				 INNER JOIN mod_discipline
							ON _mod_enti_discipline.fk_disciplina = mod_discipline.id
			WHERE _mod_enti_discipline.fk_disciplina = ".$id;
		$row =  $this->db->query($sql)->result_array();	
		return $row;
	}


	/**
	* Funzione caricamento delle liste per inserimento multiplo master details, tabella _mod_insegnanti_discipline
	* @param mixed $id
	* @param string $isAjax
	* @return string
	**/
	public function getList_mod_insegnanti_discipline($id, $isAjax = 'FALSE'){
		$sql ="SELECT _mod_insegnanti_discipline.* 
			,mod_anagrafica.nome AS mod_anagrafica_nome
			,mod_discipline.nome AS mod_discipline_nome
					FROM _mod_insegnanti_discipline
				 INNER JOIN mod_anagrafica
							ON _mod_insegnanti_discipline.fk_anagrafica = mod_anagrafica.id
				 INNER JOIN mod_discipline
							ON _mod_insegnanti_discipline.fk_disciplina = mod_discipline.id
			WHERE _mod_insegnanti_discipline.fk_disciplina = ".$id;
		$row =  $this->db->query($sql)->result_array();	
		return $row;
	}


	public function getAllInsegnantiNonAssociati($disciplinaId){
		$sql ="SELECT * FROM mod_anagrafica WHERE anagrafica_attributo LIKE '%INSEGNANTE%'
				AND id NOT IN
			(SELECT fk_anagrafica from _mod_insegnanti_discipline
			WHERE fk_disciplina = $disciplinaId)";
		$row =  $this->db->query($sql)->result_array();	
		return $row;			
	}


}