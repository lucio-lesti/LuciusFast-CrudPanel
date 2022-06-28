<?php
if (!defined('BASEPATH')){
	exit('No direct script access allowed');
}
require APPPATH . '/libraries/BaseController.php';
use Dompdf\Dompdf;

class Mod_causali_pagamento extends BaseController
{

	public function __construct()
	{
		parent::__construct();
		$this->load->model('Mod_causali_pagamento_model');
		$this->mod_name = 'mod_causali_pagamento';
		$this->mod_title = 'Causali Pagamento';
		$this->modelClassModule =  $this->Mod_causali_pagamento_model;
		$this->pkIdName = 'id';
		$this->viewName_ListAjax = 'mod_causali_pagamento_list_ajax';
		$this->viewName_FormROAjax = 'mod_causali_pagamento_read_ajax';
		$this->viewName_FormAjax = 'mod_causali_pagamento_form_ajax';



		/*
		//ABILITARE PER CUSTOMIZZAZIONE PER MODULO ERRORI SQL 
		//IN CORSO MIGLIORIA PER GESTIRE I MESSAGGI TRAMITE TABELLA DI TRASCODIFICA
		//SPOSTARE LOGICA NEL MODEL		
		$this->MsgDBConverted['insert']['error']['1062'] = "Esiste gia questo elemento per il modulo Causali Pagamento";
		$this->MsgDBConverted['insert']['error']['1452'] = "Esiste gia questo elemento per il modulo Causali Pagamento";
		$this->MsgDBConverted['update']['error']['1062'] = "Esiste gia questo elemento per il modulo Causali Pagamento";
		$this->MsgDBConverted['update']['error']['1452'] = "Esiste gia questo elemento per il modulo Causali Pagamento";
		$this->MsgDBConverted['insert_massive']['error']['1062'] = "Esiste gia questo elemento per il modulo Causali Pagamento";
		$this->MsgDBConverted['insert_massive']['error']['1452'] = "Esiste gia questo elemento per il modulo Causali Pagamento";
		$this->MsgDBConverted['update_massive']['error']['1062'] = "Esiste gia questo elemento per il modulo Causali Pagamento";
		$this->MsgDBConverted['update_massive']['error']['1452'] = "Esiste gia questo elemento per il modulo Causali Pagamento";
		$this->MsgDBConverted['delete']['error']['1217'] = "Impossibile eliminare questo elemento del modulo Causali Pagamento. E' usato nei seguenti moduli:";
		$this->MsgDBConverted['delete_massive']['error']['1217'] = "Impossibile eliminare alcuni elementi del modulo Causali Pagamento. Sono usati nei seguenti moduli:";
		*/


		//NOTE:NELLA FUNZIONE 'setFormFields' INDICARE NEL VETTORE CHE SI COLLEGA ALLA TABELLA REFERENZIATA
		//ALLA CHIAVE 'NOME', IL NOMINATIVO DEL CAMPO COLLEGATO
		$this->setFormFields('nome');
		$this->setFormFields('id');

	}



	public function _rules()
	{
		$this->form_validation->set_rules('nome', 'causale pagamento', 'trim|max_length[50]|required');

		$this->form_validation->set_rules('id', 'id', 'trim');
		$this->form_validation->set_error_delimiters('<span class="text-danger">', '</span>');

	}

}