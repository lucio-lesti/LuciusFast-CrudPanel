<?php
if (!defined('BASEPATH')){
	exit('No direct script access allowed');
}

require APPPATH . '/libraries/BaseModel.php';

class Mod_doc_spese_generiche_model extends BaseModel
{

	public function __construct(){
		parent::__construct();
		$this->table = 'mod_doc_spese_generiche';
		$this->id = 'id';
		$this->mod_name = 'mod_doc_spese_generiche';
		$this->mod_type = 'crud';


		//NOTE:NELLA FUNZIONE 'setFieldArrayGrid' INDICARE NEL VETTORE CHE SI COLLEGA ALLA TABELLA REFERENZIATA
		//ALLA CHIAVE 'NOME', IL NOMINATIVO DEL CAMPO COLLEGATO

		$this->setFieldArrayGrid('data', FIELD_DATE);
		$this->setFieldArrayGrid('descrizione', FIELD_STRING);
		$this->setFieldArrayGrid('fk_tipo_spesa',FIELD_NUMERIC,'mod_tipo_spesa_generica',array("id" => 'id', "nome" => 'nome'));
		$this->setFieldArrayGrid('fk_tipopagamento',FIELD_NUMERIC,'mod_tipopagamento',array("id" => 'id', "nome" => 'nome'));
		$this->setFieldArrayGrid('importo', FIELD_FLOAT);
		$this->setFieldArrayGrid('nome', FIELD_STRING);
		$this->setFieldArrayGrid('id', FIELD_NUMERIC);

		//ESEMPIO DI TABELLA REFERENZIATA CHE NON HA IL CAMPO 'NOME'. QUI INDICHIAMO AL PROGRAMMA QUALE E' IL CAMPO DA USARE COME CAMPO 'NOME'
		//P.S.QUESTA OPERAZIONE E' POSSIBILE FARLA ANCHE NEL METODO 'setFieldArrayGrid'
		//$this->arrayColumnsReferenced['mod_sport']['nome'] = "sport"; 

	}



}