<?php
if (!defined('BASEPATH')){
	exit('No direct script access allowed');
}
require APPPATH . '/libraries/BaseController.php';
use Dompdf\Dompdf;

class Mod_tipopagamento extends BaseController
{

	public function __construct()
	{
		parent::__construct();
		$this->load->model('Mod_tipopagamento_model');
		$this->mod_name = 'mod_tipopagamento';
		$this->mod_title = 'Tipo Pagamento';
		$this->modelClassModule =  $this->Mod_tipopagamento_model;
		$this->pkIdName = 'id';
		$this->viewName_ListAjax = 'mod_tipopagamento_list_ajax';
		$this->viewName_FormROAjax = 'mod_tipopagamento_read_ajax';
		$this->viewName_FormAjax = 'mod_tipopagamento_form_ajax';

		$this->MsgDBConverted['insert']['error']['1062'] = "Esiste gia questo elemento per il modulo Tipo Pagamento";
		$this->MsgDBConverted['insert']['error']['1452'] = "Esiste gia questo elemento per il modulo Tipo Pagamento";
		$this->MsgDBConverted['update']['error']['1062'] = "Esiste gia questo elemento per il modulo Tipo Pagamento";
		$this->MsgDBConverted['update']['error']['1452'] = "Esiste gia questo elemento per il modulo Tipo Pagamento";
		$this->MsgDBConverted['insert_massive']['error']['1062'] = "Esiste gia questo elemento per il modulo Tipo Pagamento";
		$this->MsgDBConverted['insert_massive']['error']['1452'] = "Esiste gia questo elemento per il modulo Tipo Pagamento";
		$this->MsgDBConverted['update_massive']['error']['1062'] = "Esiste gia questo elemento per il modulo Tipo Pagamento";
		$this->MsgDBConverted['update_massive']['error']['1452'] = "Esiste gia questo elemento per il modulo Tipo Pagamento";
		$this->MsgDBConverted['delete']['error']['1217'] = "Impossibile eliminare questo elemento del modulo Tipo Pagamento. E' usato nei seguenti moduli:";
		$this->MsgDBConverted['delete_massive']['error']['1217'] = "Impossibile eliminare alcuni elementi del modulo Tipo Pagamento. Sono usati nei seguenti moduli:";

		//NOTE:NELLA FUNZIONE 'setFormFields' INDICARE NEL VETTORE CHE SI COLLEGA ALLA TABELLA REFERENZIATA
		//ALLA CHIAVE 'NOME', IL NOMINATIVO DEL CAMPO COLLEGATO
		$this->setFormFields('codice');
		$this->setFormFields('nome');
		$this->setFormFields('id');


	}


	public function _rules()
	{
		$this->form_validation->set_rules('codice', 'codice', 'trim|max_length[10]|required');
		$this->form_validation->set_rules('nome', 'descrizione', 'trim|max_length[255]|required');

		$this->form_validation->set_rules('id', 'id', 'trim');
		$this->form_validation->set_error_delimiters('<span class="text-danger">', '</span>');

	}

}