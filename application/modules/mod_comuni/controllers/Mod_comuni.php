<?php
if (!defined('BASEPATH')){
	exit('No direct script access allowed');
}
require APPPATH . '/libraries/BaseController.php';
use Dompdf\Dompdf;

class Mod_comuni extends BaseController
{

	public function __construct()
	{
		parent::__construct();
		$this->load->model('Mod_comuni_model');
		$this->mod_name = 'mod_comuni';
		$this->mod_type = 'crud';
		$this->mod_title = 'Comuni';
		$this->modelClassModule =  $this->Mod_comuni_model;
		$this->pkIdName = 'istat';
		$this->viewName_ListAjax = 'mod_comuni_list_ajax';
		$this->viewName_FormROAjax = 'mod_comuni_read_ajax';
		$this->viewName_FormAjax = 'mod_comuni_form_ajax';

		$this->MsgDBConverted['insert']['error']['1062'] = "Esiste gia questo elemento per il modulo Comuni";
		$this->MsgDBConverted['insert']['error']['1452'] = "Esiste gia questo elemento per il modulo Comuni";
		$this->MsgDBConverted['update']['error']['1062'] = "Esiste gia questo elemento per il modulo Comuni";
		$this->MsgDBConverted['update']['error']['1452'] = "Esiste gia questo elemento per il modulo Comuni";
		$this->MsgDBConverted['insert_massive']['error']['1062'] = "Esiste gia questo elemento per il modulo Comuni";
		$this->MsgDBConverted['insert_massive']['error']['1452'] = "Esiste gia questo elemento per il modulo Comuni";
		$this->MsgDBConverted['update_massive']['error']['1062'] = "Esiste gia questo elemento per il modulo Comuni";
		$this->MsgDBConverted['update_massive']['error']['1452'] = "Esiste gia questo elemento per il modulo Comuni";
		$this->MsgDBConverted['delete']['error']['1217'] = "Impossibile eliminare questo elemento del modulo Comuni. E' usato nei seguenti moduli:";
		$this->MsgDBConverted['delete_massive']['error']['1217'] = "Impossibile eliminare alcuni elementi del modulo Comuni. Sono usati nei seguenti moduli:";

		//NOTE:NELLA FUNZIONE 'setFormFields' INDICARE NEL VETTORE CHE SI COLLEGA ALLA TABELLA REFERENZIATA
		//ALLA CHIAVE 'NOME', IL NOMINATIVO DEL CAMPO COLLEGATO
		$this->setFormFields('abitanti');
		$this->setFormFields('cap');
		$this->setFormFields('codfisco');
		$this->setFormFields('codice_provincia');
		$this->setFormFields('codice_regione');
		$this->setFormFields('comune');
		$this->setFormFields('prefisso');
		$this->setFormFields('istat');


		//ABILITARE PER LE OPERAZIONI "CUSTOM"

		//LA CHIAVE DEL VETTORE "custom_operations_list" RAPPRESENTA IL NOME DELLA FUNZIONE
		//QUESTO PER AVERE UN CODICE ORDINATO E PER EVITARE CHE LE FUNZIONI CUSTOM NON VENGANO RICHIAMATE CORRETTAMENTE
		/*$this->custom_operations_list['mod_comuni_check_date'] = function($request, $id = NULL){
			$ret = $this->utilities->check_date_greater_then($request['data_da'], $request['data_a']);
			if($ret === FALSE){
				$this->session->set_flashdata('error',"Data Da non puo essere maggiore di Data a");
				return false;
			}				
		};*/

	}


	public function _rules()
	{
		$this->form_validation->set_rules('abitanti', 'nr. abitanti', 'trim|numeric|max_length[10]');
		$this->form_validation->set_rules('cap', 'cap', 'trim|max_length[10]|required');
		$this->form_validation->set_rules('codfisco', 'codfisc', 'trim|max_length[10]');
		$this->form_validation->set_rules('codice_provincia', 'prov.', 'trim|max_length[3]|required');
		$this->form_validation->set_rules('codice_regione', 'regione', 'trim|max_length[3]|required');
		$this->form_validation->set_rules('comune', 'comune', 'trim|max_length[100]|required');
		$this->form_validation->set_rules('prefisso', 'prefisso', 'trim|max_length[10]|required');

		$this->form_validation->set_rules('istat', 'istat', 'trim');
		$this->form_validation->set_error_delimiters('<span class="text-danger">', '</span>');

	}

}