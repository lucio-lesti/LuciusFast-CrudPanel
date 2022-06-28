<?php
if (!defined('BASEPATH')){
	exit('No direct script access allowed');
}
require APPPATH . '/libraries/BaseController.php';
use Dompdf\Dompdf;

class Mod_doc_generici extends BaseController
{

	public function __construct()
	{
		parent::__construct();
		$this->load->model('Mod_doc_generici_model');
		$this->mod_name = 'mod_doc_generici';
		$this->mod_title = 'Documenti Generici';
		$this->modelClassModule =  $this->Mod_doc_generici_model;
		$this->pkIdName = 'id';
		$this->viewName_ListAjax = 'mod_doc_generici_list_ajax';
		$this->viewName_FormROAjax = 'mod_doc_generici_read_ajax';
		$this->viewName_FormAjax = 'mod_doc_generici_form_ajax';


		/*
		//ABILITARE PER CUSTOMIZZAZIONE PER MODULO ERRORI SQL 
		//IN CORSO MIGLIORIA PER GESTIRE I MESSAGGI TRAMITE TABELLA DI TRASCODIFICA
		//SPOSTARE LOGICA NEL MODEL				
		$this->MsgDBConverted['insert']['error']['1062'] = "Esiste gia questo elemento per il modulo Documenti Generici";
		$this->MsgDBConverted['insert']['error']['1452'] = "Esiste gia questo elemento per il modulo Documenti Generici";
		$this->MsgDBConverted['update']['error']['1062'] = "Esiste gia questo elemento per il modulo Documenti Generici";
		$this->MsgDBConverted['update']['error']['1452'] = "Esiste gia questo elemento per il modulo Documenti Generici";
		$this->MsgDBConverted['insert_massive']['error']['1062'] = "Esiste gia questo elemento per il modulo Documenti Generici";
		$this->MsgDBConverted['insert_massive']['error']['1452'] = "Esiste gia questo elemento per il modulo Documenti Generici";
		$this->MsgDBConverted['update_massive']['error']['1062'] = "Esiste gia questo elemento per il modulo Documenti Generici";
		$this->MsgDBConverted['update_massive']['error']['1452'] = "Esiste gia questo elemento per il modulo Documenti Generici";
		$this->MsgDBConverted['delete']['error']['1217'] = "Impossibile eliminare questo elemento del modulo Documenti Generici. E' usato nei seguenti moduli:";
		$this->MsgDBConverted['delete_massive']['error']['1217'] = "Impossibile eliminare alcuni elementi del modulo Documenti Generici. Sono usati nei seguenti moduli:";
		*/

		
		//NOTE:NELLA FUNZIONE 'setFormFields' INDICARE NEL VETTORE CHE SI COLLEGA ALLA TABELLA REFERENZIATA
		//ALLA CHIAVE 'NOME', IL NOMINATIVO DEL CAMPO COLLEGATO
		$this->setFormFields('data');
		$this->setFormFields('descrizione');
		$this->setFormFields('nome');
		$this->setFormFields('tipo_doc');
		$this->setFormFields('id');


	}


	public function _rules()
	{
		$this->form_validation->set_rules('data', '', 'trim');
		$this->form_validation->set_rules('descrizione', '', 'trim|max_length[65535]');
		$this->form_validation->set_rules('nome', '', 'trim|max_length[100]');
		$this->form_validation->set_rules('tipo_doc', '', 'trim');

		$this->form_validation->set_rules('id', 'id', 'trim');
		$this->form_validation->set_error_delimiters('<span class="text-danger">', '</span>');

	}

}