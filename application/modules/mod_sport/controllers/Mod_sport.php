<?php
if (!defined('BASEPATH')){
	exit('No direct script access allowed');
}
require APPPATH . '/libraries/BaseController.php';
use Dompdf\Dompdf;

class Mod_sport extends BaseController
{

	public function __construct()
	{
		parent::__construct();
		$this->load->model('Mod_sport_model');
		$this->mod_name = 'mod_sport';
		$this->mod_type = 'crud';
		$this->mod_title = 'Sports';
		$this->modelClassModule =  $this->Mod_sport_model;
		$this->pkIdName = 'id';
		$this->viewName_ListAjax = 'mod_sport_list_ajax';
		$this->viewName_FormROAjax = 'mod_sport_read_ajax';
		$this->viewName_FormAjax = 'mod_sport_form_ajax';

		/*
		//ABILITARE PER CUSTOMIZZAZIONE PER MODULO ERRORI SQL 
		//IN CORSO MIGLIORIA PER GESTIRE I MESSAGGI TRAMITE TABELLA DI TRASCODIFICA
		//SPOSTARE LOGICA NEL MODEL				
		$this->MsgDBConverted['insert']['error']['1062'] = "Esiste gia questo elemento per il modulo Sports";
		$this->MsgDBConverted['insert']['error']['1452'] = "Esiste gia questo elemento per il modulo Sports";
		$this->MsgDBConverted['update']['error']['1062'] = "Esiste gia questo elemento per il modulo Sports";
		$this->MsgDBConverted['update']['error']['1452'] = "Esiste gia questo elemento per il modulo Sports";
		$this->MsgDBConverted['insert_massive']['error']['1062'] = "Esiste gia questo elemento per il modulo Sports";
		$this->MsgDBConverted['insert_massive']['error']['1452'] = "Esiste gia questo elemento per il modulo Sports";
		$this->MsgDBConverted['update_massive']['error']['1062'] = "Esiste gia questo elemento per il modulo Sports";
		$this->MsgDBConverted['update_massive']['error']['1452'] = "Esiste gia questo elemento per il modulo Sports";
		$this->MsgDBConverted['delete']['error']['1217'] = "Impossibile eliminare questo elemento del modulo Sports. E' usato nei seguenti moduli:";
		$this->MsgDBConverted['delete_massive']['error']['1217'] = "Impossibile eliminare alcuni elementi del modulo Sports. Sono usati nei seguenti moduli:";
		*/


		$this->setFormFields('sport');
		$this->setFormFields('id');
 

	}


	public function _rules()
	{
		$this->form_validation->set_rules('sport', 'nome sport', 'trim|max_length[30]|required');

		$this->form_validation->set_rules('id', 'id', 'trim');
		$this->form_validation->set_error_delimiters('<span class="text-danger">', '</span>');

	}

}