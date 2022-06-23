<?php
if (!defined('BASEPATH')){
	exit('No direct script access allowed');
}

require APPPATH . '/libraries/BaseModel.php';

class Mod_libro_soci_model extends BaseModel
{

	public function __construct(){
		parent::__construct();
		$this->table = 'mod_libro_soci';
		$this->id = 'id';
		$this->mod_name = 'mod_libro_soci';
		$this->mod_type = 'crud';


		//NOTE:NELLA FUNZIONE 'setFieldArrayGrid' INDICARE NEL VETTORE CHE SI COLLEGA ALLA TABELLA REFERENZIATA
		//ALLA CHIAVE 'NOME', IL NOMINATIVO DEL CAMPO COLLEGATO

		$this->setFieldArrayGrid('data_ammissione', FIELD_DATE);
		$this->setFieldArrayGrid('doc_verbale_ammissione', FIELD_BLOB);
		$this->setFieldArrayGrid('fk_anagrafica',FIELD_NUMERIC,'mod_anagrafica',array("id" => 'id', "nome" => 'nome'));
		$this->setFieldArrayGrid('id', FIELD_NUMERIC);

		//ESEMPIO DI TABELLA REFERENZIATA CHE NON HA IL CAMPO 'NOME'. QUI INDICHIAMO AL PROGRAMMA QUALE E' IL CAMPO DA USARE COME CAMPO 'NOME'
		//P.S.QUESTA OPERAZIONE E' POSSIBILE FARLA ANCHE NEL METODO 'setFieldArrayGrid'
		//$this->arrayColumnsReferenced['mod_sport']['nome'] = "sport"; 

	}



}