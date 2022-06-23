<?php
if (!defined('BASEPATH')){
	exit('No direct script access allowed');
}

require APPPATH . '/libraries/BaseModel.php';

class Mod_enti_model extends BaseModel
{

	public function __construct(){
		parent::__construct();
		$this->table = 'mod_enti';
		$this->id = 'id';
		$this->mod_name = 'mod_enti';
		$this->mod_type = 'crud';


		//NOTE:NELLA FUNZIONE 'setFieldArrayGrid' INDICARE NEL VETTORE CHE SI COLLEGA ALLA TABELLA REFERENZIATA
		//ALLA CHIAVE 'NOME', IL NOMINATIVO DEL CAMPO COLLEGATO

		$this->setFieldArrayGrid('email', FIELD_STRING);
		$this->setFieldArrayGrid('fk_comune',FIELD_NUMERIC,'mod_comuni',array("id" => 'istat', "nome" => 'comune'));
		$this->setFieldArrayGrid('indirizzo', FIELD_STRING);
		$this->setFieldArrayGrid('nome', FIELD_STRING);
		$this->setFieldArrayGrid('telefono', FIELD_STRING);
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
			WHERE _mod_enti_discipline.fk_ente = ".$id;
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
			WHERE _mod_enti_discipline.fk_ente = ".$id;
		$row =  $this->db->query($sql)->result_array();	
		return $row;
	}


	public function getAllDisciplineNonAssociateEnte($enteId){
		$sql ="SELECT * FROM mod_discipline WHERE id NOT IN
			(SELECT fk_disciplina from _mod_enti_discipline
			WHERE fk_ente = $enteId)";
		$row =  $this->db->query($sql)->result_array();	
		return $row;			
	}



}