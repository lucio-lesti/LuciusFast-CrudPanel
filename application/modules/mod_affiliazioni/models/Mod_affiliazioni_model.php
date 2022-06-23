<?php
if (!defined('BASEPATH')){
	exit('No direct script access allowed');
}

require APPPATH . '/libraries/BaseModel.php';

class Mod_affiliazioni_model extends BaseModel
{

	public function __construct(){
		parent::__construct();
		$this->table = 'mod_affiliazioni';
		$this->id = 'id';
		$this->mod_name = 'mod_affiliazioni';
		$this->mod_type = 'crud';


		//NOTE:NELLA FUNZIONE 'setFieldArrayGrid' INDICARE NEL VETTORE CHE SI COLLEGA ALLA TABELLA REFERENZIATA
		//ALLA CHIAVE 'NOME', IL NOMINATIVO DEL CAMPO COLLEGATO

		//NOTE 2: NELLA FUNZIONE 'setFieldArrayGrid' se nella chiave "nome" si usa una array, la classe "BaseModel" lo interpreta come un concat

		$this->setFieldArrayGrid('nome', FIELD_STRING);
		$this->setFieldArrayGrid('fk_ente',FIELD_NUMERIC,'mod_enti',array("id" => 'id', "nome" => 'nome'));
		$this->setFieldArrayGrid('fk_esercizio',FIELD_NUMERIC,'mod_esercizi',array("id" => 'id', "nome" => 'nome'));
		$this->setFieldArrayGrid('id', FIELD_NUMERIC);

		//ESEMPIO DI TABELLA REFERENZIATA CHE NON HA IL CAMPO 'NOME'. QUI INDICHIAMO AL PROGRAMMA QUALE E' IL CAMPO DA USARE COME CAMPO 'NOME'
		//P.S.QUESTA OPERAZIONE E' POSSIBILE FARLA ANCHE NEL METODO 'setFieldArrayGrid'
		//$this->arrayColumnsReferenced['mod_sport']['nome'] = "sport"; 

	}


	/**
	* Funzione caricamento della master details, tabella _mod_anagrafica_tessere_assoc
	* @param mixed $id
	* @param string $isAjax
	* @return string
	**/
	public function getMasterDetail_mod_anagrafica_tessere_assoc($id, $isAjax = 'FALSE'){
 
		$sql ="SELECT mod_anagrafica.nome AS mod_anagrafica_nome,
			mod_affiliazioni.nome AS mod_affiliazioni_nome,
			_mod_magazzino_tessere_lista_tessere.codice_tessera as tessera_associativa,
			mod_tesseramenti.id as id_tesseramento
			FROM mod_tesseramenti
			INNER JOIN mod_affiliazioni
				ON mod_tesseramenti.fk_affiliazione = mod_affiliazioni.id
				AND mod_tesseramenti.fk_affiliazione = $id
			INNER JOIN mod_anagrafica
				ON mod_tesseramenti.fk_anagrafica = mod_anagrafica.id
			INNER JOIN mod_magazzino_tessere
				ON mod_magazzino_tessere.fk_affiliazione = mod_affiliazioni.id
			INNER JOIN _mod_magazzino_tessere_lista_tessere
				ON _mod_magazzino_tessere_lista_tessere.fk_magazzino_tessere = mod_magazzino_tessere.id";		
		$row =  $this->db->query($sql)->result_array();
			
		return $row;
	}


	/**
	* Funzione caricamento delle liste per inserimento multiplo master details, tabella _mod_anagrafica_tessere_assoc
	* @param mixed $id
	* @param string $isAjax
	* @return string
	**/
	public function getList_mod_anagrafica_tessere_assoc($id, $isAjax = 'FALSE'){
		/*
		$sql ="SELECT _mod_anagrafica_tessere_assoc.* 
			,mod_anagrafica.nome AS mod_anagrafica_nome
			,mod_affiliazioni.nome AS mod_affiliazioni_nome
					FROM _mod_anagrafica_tessere_assoc
				 INNER JOIN mod_anagrafica
							ON _mod_anagrafica_tessere_assoc.fk_anagrafica = mod_anagrafica.id
				 INNER JOIN mod_affiliazioni
							ON _mod_anagrafica_tessere_assoc.fk_affiliazione = mod_affiliazioni.id
			WHERE _mod_anagrafica_tessere_assoc.fk_affiliazione = ".$id;
		*/
		
		$sql ="SELECT mod_anagrafica.nome AS mod_anagrafica_nome,
			mod_affiliazioni.nome AS mod_affiliazioni_nome,
			_mod_magazzino_tessere_lista_tessere.codice_tessera as tessera_associativa
			FROM mod_tesseramenti
			INNER JOIN mod_affiliazioni
				ON mod_tesseramenti.fk_affiliazione = mod_affiliazioni.id
				AND mod_tesseramenti.fk_affiliazione = $id
			INNER JOIN mod_anagrafica
				ON mod_tesseramenti.fk_anagrafica = mod_anagrafica.id
			INNER JOIN mod_magazzino_tessere
				ON mod_magazzino_tessere.fk_affiliazione = mod_affiliazioni.id
			INNER JOIN _mod_magazzino_tessere_lista_tessere
				ON _mod_magazzino_tessere_lista_tessere.fk_magazzino_tessere = mod_magazzino_tessere.id";			
		$row =  $this->db->query($sql)->result_array();	

		return $row;
	}



}