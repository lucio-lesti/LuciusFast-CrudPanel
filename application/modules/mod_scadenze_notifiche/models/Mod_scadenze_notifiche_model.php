<?php
if (!defined('BASEPATH')){
	exit('No direct script access allowed');
}

require APPPATH . '/libraries/BaseModel.php';

class Mod_scadenze_notifiche_model extends BaseModel
{

	public function __construct(){
		parent::__construct();
		$this->table = 'mod_scadenze_notifiche';
		$this->id = 'id';
		$this->mod_name = 'mod_scadenze_notifiche';
		$this->mod_type = 'crud';


		//NOTE:NELLA FUNZIONE 'setFieldArrayGrid' INDICARE NEL VETTORE CHE SI COLLEGA ALLA TABELLA REFERENZIATA
		//ALLA CHIAVE 'NOME', IL NOMINATIVO DEL CAMPO COLLEGATO

		//NOTE 2: NELLA FUNZIONE 'setFieldArrayGrid' se nella chiave "nome" si usa una array, la classe "BaseModel" lo interpreta come un concat

		$this->setFieldArrayGrid('campo_data_scadenza', FIELD_STRING);
		$this->setFieldArrayGrid('icona_notifica', FIELD_STRING);
		$this->setFieldArrayGrid('mod_name', FIELD_STRING);
		$this->setFieldArrayGrid('msg_notifica', FIELD_STRING);
		$this->setFieldArrayGrid('nr_giorni_data_notifica', FIELD_NUMERIC);
		$this->setFieldArrayGrid('sql_command', FIELD_STRING);
		$this->setFieldArrayGrid('table_name', FIELD_STRING);
		$this->setFieldArrayGrid('id', FIELD_NUMERIC);

		//ESEMPIO DI TABELLA REFERENZIATA CHE NON HA IL CAMPO 'NOME'. QUI INDICHIAMO AL PROGRAMMA QUALE E' IL CAMPO DA USARE COME CAMPO 'NOME'
		//P.S.QUESTA OPERAZIONE E' POSSIBILE FARLA ANCHE NEL METODO 'setFieldArrayGrid'
		//$this->arrayColumnsReferenced['mod_sport']['nome'] = "sport"; 

	}


 


}