<?php
if (!defined('BASEPATH')){
	exit('No direct script access allowed');
}

require APPPATH . '/libraries/BaseModel.php';

class Mod_sport_model extends BaseModel
{

	public function __construct(){
		parent::__construct();
		$this->table = 'mod_sport';
		$this->id = 'id';
		$this->mod_name = 'mod_sport';
		$this->mod_type = 'crud';

		$this->setFieldArrayGrid('sport', FIELD_STRING);
		$this->setFieldArrayGrid('id', FIELD_NUMERIC);

	}



}