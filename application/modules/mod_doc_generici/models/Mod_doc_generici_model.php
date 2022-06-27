<?php
if (!defined('BASEPATH')){
	exit('No direct script access allowed');
}

require APPPATH . '/libraries/BaseModel.php';

class Mod_doc_generici_model extends BaseModel
{

	public function __construct(){
		parent::__construct();
		$this->table = 'mod_doc_generici';
		$this->id = 'id';
		$this->mod_name = 'mod_doc_generici';

		$this->setFieldArrayGrid('data', FIELD_DATE);
		$this->setFieldArrayGrid('nome', FIELD_STRING);
		$this->setFieldArrayGrid('tipo_doc', FIELD_STRING);
		$this->setFieldArrayGrid('id', FIELD_NUMERIC);

	}


}