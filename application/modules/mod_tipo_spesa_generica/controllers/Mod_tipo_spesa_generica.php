<?php
if (!defined('BASEPATH')){
	exit('No direct script access allowed');
}
require APPPATH . '/libraries/BaseController.php';
use Dompdf\Dompdf;

class Mod_tipo_spesa_generica extends BaseController
{

	public function __construct()
	{
		parent::__construct();
		$this->load->model('Mod_tipo_spesa_generica_model');
		$this->mod_name = 'mod_tipo_spesa_generica';
		$this->mod_title = 'Tipo Spesa';
		$this->modelClassModule =  $this->Mod_tipo_spesa_generica_model;
		$this->pkIdName = 'id';
		$this->viewName_ListAjax = 'mod_tipo_spesa_generica_list_ajax';
		$this->viewName_FormROAjax = 'mod_tipo_spesa_generica_read_ajax';
		$this->viewName_FormAjax = 'mod_tipo_spesa_generica_form_ajax';

		/*
		//ABILITARE PER CUSTOMIZZAZIONE PER MODULO ERRORI SQL 
		//IN CORSO MIGLIORIA PER GESTIRE I MESSAGGI TRAMITE TABELLA DI TRASCODIFICA
		//SPOSTARE LOGICA NEL MODEL				
		$this->MsgDBConverted['insert']['error']['1062'] = "Esiste gia questo elemento per il modulo Tipo Spesa";
		$this->MsgDBConverted['insert']['error']['1452'] = "Esiste gia questo elemento per il modulo Tipo Spesa";
		$this->MsgDBConverted['update']['error']['1062'] = "Esiste gia questo elemento per il modulo Tipo Spesa";
		$this->MsgDBConverted['update']['error']['1452'] = "Esiste gia questo elemento per il modulo Tipo Spesa";
		$this->MsgDBConverted['insert_massive']['error']['1062'] = "Esiste gia questo elemento per il modulo Tipo Spesa";
		$this->MsgDBConverted['insert_massive']['error']['1452'] = "Esiste gia questo elemento per il modulo Tipo Spesa";
		$this->MsgDBConverted['update_massive']['error']['1062'] = "Esiste gia questo elemento per il modulo Tipo Spesa";
		$this->MsgDBConverted['update_massive']['error']['1452'] = "Esiste gia questo elemento per il modulo Tipo Spesa";
		$this->MsgDBConverted['delete']['error']['1217'] = "Impossibile eliminare questo elemento del modulo Tipo Spesa. E' usato nei seguenti moduli:";
		$this->MsgDBConverted['delete_massive']['error']['1217'] = "Impossibile eliminare alcuni elementi del modulo Tipo Spesa. Sono usati nei seguenti moduli:";
		*/

		
		//NOTE:NELLA FUNZIONE 'setFormFields' INDICARE NEL VETTORE CHE SI COLLEGA ALLA TABELLA REFERENZIATA
		//ALLA CHIAVE 'NOME', IL NOMINATIVO DEL CAMPO COLLEGATO
		$this->setFormFields('nome');
		$this->setFormFields('id');


	}


	public function _rules()
	{
		$this->form_validation->set_rules('nome', 'tipo spesa', 'trim|max_length[255]|required');

		$this->form_validation->set_rules('id', 'id', 'trim');
		$this->form_validation->set_error_delimiters('<span class="text-danger">', '</span>');

	}

}