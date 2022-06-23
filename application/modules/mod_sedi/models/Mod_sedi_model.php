<?php
if (!defined('BASEPATH')){
	exit('No direct script access allowed');
}

require APPPATH . '/libraries/BaseModel.php';

class Mod_sedi_model extends BaseModel
{

	public function __construct(){
		parent::__construct();
		$this->table = 'mod_sedi';
		$this->id = 'id';
		$this->mod_name = 'mod_sedi';


		//NOTE:NELLA FUNZIONE 'setFieldArrayGrid' INDICARE NEL VETTORE CHE SI COLLEGA ALLA TABELLA REFERENZIATA
		//ALLA CHIAVE 'NOME', IL NOMINATIVO DEL CAMPO COLLEGATO

		$this->setFieldArrayGrid('fk_azienda',FIELD_NUMERIC,'core_settings',array("id" => 'company_id', "nome" => 'company_name'));
		$this->setFieldArrayGrid('indirizzo', FIELD_STRING);
		$this->setFieldArrayGrid('nome', FIELD_STRING);
		$this->setFieldArrayGrid('id', FIELD_NUMERIC);

		//ESEMPIO DI TABELLA REFERENZIATA CHE NON HA IL CAMPO 'NOME'. QUI INDICHIAMO AL PROGRAMMA QUALE E' IL CAMPO DA USARE COME CAMPO 'NOME'
		//P.S.QUESTA OPERAZIONE E' POSSIBILE FARLA ANCHE NEL METODO 'setFieldArrayGrid'
		//$this->arrayColumnsReferenced['mod_sport']['nome'] = "sport"; 

		$this->enableCustomQueryMethod = TRUE;
		$this->customQueryMethod["json"]["fields"] = "mod_sedi.indirizzo AS mod_sedi_indirizzo,core_settings.company_name AS core_settings_company_name,
													mod_sedi.nome AS mod_sedi_nome,mod_sedi.id ";
		$this->customQueryMethod["json"]["table_from"] = $this->table;
		$this->customQueryMethod["json"]["tables_join"][] = array("table" => "core_settings", "join" => "mod_sedi.fk_azienda = core_settings.company_id ");


	}



}