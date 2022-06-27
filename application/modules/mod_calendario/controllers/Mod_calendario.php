<?php
if (!defined('BASEPATH')){
	exit('No direct script access allowed');
}
require APPPATH . '/libraries/BaseController.php';
use Dompdf\Dompdf;

class Mod_calendario extends BaseController
{

	public function __construct()
	{
		parent::__construct();
		$this->load->model('Mod_calendario_model');
		$this->mod_name = 'mod_calendario';
		$this->mod_type = 'crud';
		$this->mod_title = 'Calendario';
		$this->modelClassModule =  $this->Mod_calendario_model;
		$this->pkIdName = 'id';
		$this->viewName_ListAjax = 'mod_calendario_list_ajax';
  
	}

 

}