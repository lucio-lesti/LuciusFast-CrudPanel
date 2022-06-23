<?php
if (!defined('BASEPATH')){
	exit('No direct script access allowed');
}
require APPPATH . '/libraries/BaseController.php';
use Dompdf\Dompdf;

class Mod_sedi extends BaseController
{

	public function __construct()
	{
		parent::__construct();
		$this->load->model('Mod_sedi_model');
		$this->mod_name = 'mod_sedi';
		$this->mod_title = 'Sedi';
		$this->modelClassModule =  $this->Mod_sedi_model;
		$this->pkIdName = 'id';
		$this->viewName_ListAjax = 'mod_sedi_list_ajax';
		$this->viewName_FormROAjax = 'mod_sedi_read_ajax';
		$this->viewName_FormAjax = 'mod_sedi_form_ajax';

		$this->MsgDBConverted['insert']['error']['1062'] = "Esiste gia questo elemento per il modulo Sedi";
		$this->MsgDBConverted['insert']['error']['1452'] = "Esiste gia questo elemento per il modulo Sedi";
		$this->MsgDBConverted['update']['error']['1062'] = "Esiste gia questo elemento per il modulo Sedi";
		$this->MsgDBConverted['update']['error']['1452'] = "Esiste gia questo elemento per il modulo Sedi";
		$this->MsgDBConverted['insert_massive']['error']['1062'] = "Esiste gia questo elemento per il modulo Sedi";
		$this->MsgDBConverted['insert_massive']['error']['1452'] = "Esiste gia questo elemento per il modulo Sedi";
		$this->MsgDBConverted['update_massive']['error']['1062'] = "Esiste gia questo elemento per il modulo Sedi";
		$this->MsgDBConverted['update_massive']['error']['1452'] = "Esiste gia questo elemento per il modulo Sedi";
		$this->MsgDBConverted['delete']['error']['1217'] = "Impossibile eliminare questo elemento del modulo Sedi. E' usato nei seguenti moduli:";
		$this->MsgDBConverted['delete_massive']['error']['1217'] = "Impossibile eliminare alcuni elementi del modulo Sedi. Sono usati nei seguenti moduli:";

		//NOTE:NELLA FUNZIONE 'setFormFields' INDICARE NEL VETTORE CHE SI COLLEGA ALLA TABELLA REFERENZIATA
		//ALLA CHIAVE 'NOME', IL NOMINATIVO DEL CAMPO COLLEGATO
		$this->setFormFields('fk_azienda','core_settings',array("id" => 'company_id', "nome" => 'company_name'));
		$this->setFormFields('indirizzo');
		$this->setFormFields('nome');
		$this->setFormFields('id');


	}


	public function _rules()
	{
		$this->form_validation->set_rules('fk_azienda', 'azienda', 'trim|numeric|max_length[10]|required');
		$this->form_validation->set_rules('indirizzo', 'indirizzo', 'trim|max_length[100]|required');
		$this->form_validation->set_rules('nome', 'nome sede', 'trim|max_length[100]|required');

		$this->form_validation->set_rules('id', 'id', 'trim');
		$this->form_validation->set_error_delimiters('<span class="text-danger">', '</span>');

	}

}