<?php
if (!defined('BASEPATH')){
	exit('No direct script access allowed');
}

require APPPATH . '/libraries/BaseModel.php';

class Mod_tipo_spesa_generica_model extends BaseModel
{

	public function __construct(){
		parent::__construct();
		$this->table = 'mod_tipo_spesa_generica';
		$this->id = 'id';
		$this->mod_name = 'mod_tipo_spesa_generica';

		$this->setFieldArrayGrid('nome', FIELD_STRING);
		$this->setFieldArrayGrid('id', FIELD_NUMERIC);
	}



}