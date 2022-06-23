<?php
if (!defined('BASEPATH')){
	exit('No direct script access allowed');
}

require APPPATH . '/libraries/BaseModel.php';

class Mod_comuni_model extends BaseModel
{

	public function __construct(){
		parent::__construct();
		$this->table = 'mod_comuni';
		$this->id = 'istat';
		$this->mod_name = 'mod_comuni';
		$this->mod_type = 'crud';


		//NOTE:NELLA FUNZIONE 'setFieldArrayGrid' INDICARE NEL VETTORE CHE SI COLLEGA ALLA TABELLA REFERENZIATA
		//ALLA CHIAVE 'NOME', IL NOMINATIVO DEL CAMPO COLLEGATO

		$this->setFieldArrayGrid('abitanti', FIELD_NUMERIC);
		$this->setFieldArrayGrid('cap', FIELD_STRING);
		$this->setFieldArrayGrid('codfisco', FIELD_STRING);
		$this->setFieldArrayGrid('codice_provincia', FIELD_STRING);
		$this->setFieldArrayGrid('codice_regione', FIELD_STRING);
		$this->setFieldArrayGrid('comune', FIELD_STRING);
		$this->setFieldArrayGrid('prefisso', FIELD_STRING);
		$this->setFieldArrayGrid('istat', FIELD_NUMERIC);

		//ESEMPIO DI TABELLA REFERENZIATA CHE NON HA IL CAMPO 'NOME'. QUI INDICHIAMO AL PROGRAMMA QUALE E' IL CAMPO DA USARE COME CAMPO 'NOME'
		//P.S.QUESTA OPERAZIONE E' POSSIBILE FARLA ANCHE NEL METODO 'setFieldArrayGrid'
		//$this->arrayColumnsReferenced['mod_sport']['nome'] = "sport"; 

	}

 

}