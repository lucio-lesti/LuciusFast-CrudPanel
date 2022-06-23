<?php
if (!defined('BASEPATH')){
	exit('No direct script access allowed');
}

require APPPATH . '/libraries/BaseModel.php';

class Mod_sale_model extends BaseModel
{

	public function __construct(){
		parent::__construct();
		$this->table = 'mod_sale';
		$this->id = 'id';
		$this->mod_name = 'mod_sale';
		$this->mod_type = 'crud';


		//NOTE:NELLA FUNZIONE 'setFieldArrayGrid' INDICARE NEL VETTORE CHE SI COLLEGA ALLA TABELLA REFERENZIATA
		//ALLA CHIAVE 'NOME', IL NOMINATIVO DEL CAMPO COLLEGATO

		$this->setFieldArrayGrid('capienza', FIELD_NUMERIC);
		$this->setFieldArrayGrid('fk_sede',FIELD_NUMERIC,'mod_sedi',array("id" => 'id', "nome" => 'nome'));
		$this->setFieldArrayGrid('nome', FIELD_STRING);
		$this->setFieldArrayGrid('id', FIELD_NUMERIC);

		//ESEMPIO DI TABELLA REFERENZIATA CHE NON HA IL CAMPO 'NOME'. QUI INDICHIAMO AL PROGRAMMA QUALE E' IL CAMPO DA USARE COME CAMPO 'NOME'
		//P.S.QUESTA OPERAZIONE E' POSSIBILE FARLA ANCHE NEL METODO 'setFieldArrayGrid'
		//$this->arrayColumnsReferenced['mod_sport']['nome'] = "sport"; 

	}


	/**
	* Funzione caricamento della master details, tabella _mod_corsi_sale_calendario
	* @param mixed $id
	* @param string $isAjax
	* @return string
	**/
	public function getMasterDetail_mod_corsi_sale_calendario($id, $isAjax = 'FALSE'){
		$sql ="SELECT _mod_corsi_sale_calendario.* 
				,mod_corsi.nome AS mod_corsi_nome
				,mod_sale.nome AS mod_sale_nome
			FROM _mod_corsi_sale_calendario
			 INNER JOIN mod_corsi
							ON _mod_corsi_sale_calendario.fk_corso = mod_corsi.id
			 INNER JOIN mod_sale
							ON _mod_corsi_sale_calendario.fk_sala = mod_sale.id
			WHERE _mod_corsi_sale_calendario.fk_sala = ".$id;
		$row =  $this->db->query($sql)->result_array();	
		return $row;
	}


	/**
	* Funzione caricamento delle liste per inserimento multiplo master details, tabella _mod_corsi_sale_calendario
	* @param mixed $id
	* @param string $isAjax
	* @return string
	**/
	public function getList_mod_corsi_sale_calendario($id, $isAjax = 'FALSE'){
		$sql ="SELECT _mod_corsi_sale_calendario.* 
			,mod_corsi.nome AS mod_corsi_nome
			,mod_sale.nome AS mod_sale_nome
					FROM _mod_corsi_sale_calendario
				 INNER JOIN mod_corsi
							ON _mod_corsi_sale_calendario.fk_corso = mod_corsi.id
				 INNER JOIN mod_sale
							ON _mod_corsi_sale_calendario.fk_sala = mod_sale.id
			WHERE _mod_corsi_sale_calendario.fk_sala = ".$id;
		$row =  $this->db->query($sql)->result_array();	
		return $row;
	}



}