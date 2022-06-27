<?php
if (!defined('BASEPATH')){
	exit('No direct script access allowed');
}

require APPPATH . '/libraries/BaseModel.php';

class Mod_anagrafica_green_pass_autocertificazione_model extends BaseModel
{

	public function __construct(){
		parent::__construct();
		$this->table = '_mod_anagrafica_green_pass_autocertificazione';
		$this->id = 'id';
		$this->mod_name = 'mod_anagrafica_green_pass_autocertificazione';
		$this->mod_type = 'crud';


		//NOTE:NELLA FUNZIONE 'setFieldArrayGrid' INDICARE NEL VETTORE CHE SI COLLEGA ALLA TABELLA REFERENZIATA
		//ALLA CHIAVE 'NOME', IL NOMINATIVO DEL CAMPO COLLEGATO

		//NOTE 2: NELLA FUNZIONE 'setFieldArrayGrid' se nella chiave "nome" si usa una array, la classe "BaseModel" lo interpreta come un concat

		$this->setFieldArrayGrid('fk_anagrafica',FIELD_NUMERIC,'mod_anagrafica',array("id" => 'id', "nome" => array("nome"," ","cognome"," ' - ' ","codfiscale") ),'mod_anagrafica_nome');
		$this->setFieldArrayGrid('data_autocertificazione_fine_validita', FIELD_DATE);
		$this->setFieldArrayGrid('id', FIELD_NUMERIC);

		//ESEMPIO DI TABELLA REFERENZIATA CHE NON HA IL CAMPO 'NOME'. QUI INDICHIAMO AL PROGRAMMA QUALE E' IL CAMPO DA USARE COME CAMPO 'NOME'
		//P.S.QUESTA OPERAZIONE E' POSSIBILE FARLA ANCHE NEL METODO 'setFieldArrayGrid'
		//$this->arrayColumnsReferenced['mod_sport']['nome'] = "sport"; 

	}



}