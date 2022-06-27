<?php
if (!defined('BASEPATH')){
	exit('No direct script access allowed');
}

require APPPATH . '/libraries/BaseModel.php';

class Mod_tipopagamento_model extends BaseModel
{

	public function __construct(){
		parent::__construct();
		$this->table = 'mod_tipopagamento';
		$this->id = 'id';
		$this->mod_name = 'mod_tipopagamento';

		$this->setFieldArrayGrid('codice', FIELD_STRING);
		$this->setFieldArrayGrid('nome', FIELD_STRING);
		$this->setFieldArrayGrid('id', FIELD_NUMERIC);

	}


}