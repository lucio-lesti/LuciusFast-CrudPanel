<?php
if (!defined('BASEPATH')){
	exit('No direct script access allowed');
}
require APPPATH . '/libraries/BaseController.php';
use Dompdf\Dompdf;

class Mod_riunioni_verbali extends BaseController
{

	public function __construct()
	{
		parent::__construct();
		$this->load->model('Mod_riunioni_verbali_model');
		$this->mod_name = 'mod_riunioni_verbali';
		$this->mod_type = 'crud';
		$this->mod_title = 'Verbali riunioni';
		$this->modelClassModule =  $this->Mod_riunioni_verbali_model;
		$this->pkIdName = 'id';
		$this->viewName_ListAjax = 'mod_riunioni_verbali_list_ajax';
		$this->viewName_FormROAjax = 'mod_riunioni_verbali_read_ajax';
		$this->viewName_FormAjax = 'mod_riunioni_verbali_form_ajax';

		$this->MsgDBConverted['insert']['error']['1062'] = "Esiste gia questo elemento per il modulo Verbali riunioni";
		$this->MsgDBConverted['insert']['error']['1452'] = "Esiste gia questo elemento per il modulo Verbali riunioni";
		$this->MsgDBConverted['update']['error']['1062'] = "Esiste gia questo elemento per il modulo Verbali riunioni";
		$this->MsgDBConverted['update']['error']['1452'] = "Esiste gia questo elemento per il modulo Verbali riunioni";
		$this->MsgDBConverted['insert_massive']['error']['1062'] = "Esiste gia questo elemento per il modulo Verbali riunioni";
		$this->MsgDBConverted['insert_massive']['error']['1452'] = "Esiste gia questo elemento per il modulo Verbali riunioni";
		$this->MsgDBConverted['update_massive']['error']['1062'] = "Esiste gia questo elemento per il modulo Verbali riunioni";
		$this->MsgDBConverted['update_massive']['error']['1452'] = "Esiste gia questo elemento per il modulo Verbali riunioni";
		$this->MsgDBConverted['delete']['error']['1217'] = "Impossibile eliminare questo elemento del modulo Verbali riunioni. E' usato nei seguenti moduli:";
		$this->MsgDBConverted['delete_massive']['error']['1217'] = "Impossibile eliminare alcuni elementi del modulo Verbali riunioni. Sono usati nei seguenti moduli:";

		//NOTE:NELLA FUNZIONE 'setFormFields' INDICARE NEL VETTORE CHE SI COLLEGA ALLA TABELLA REFERENZIATA
		//ALLA CHIAVE 'NOME', IL NOMINATIVO DEL CAMPO COLLEGATO
		$this->setFormFields('data_riunione_verbale');
		$this->setFormFields('note');
		$this->setFormFields('oggetto');
		$this->setFormFields('id');


		//ABILITARE PER LE OPERAZIONI "CUSTOM"

		//LA CHIAVE DEL VETTORE "custom_operations_list" RAPPRESENTA IL NOME DELLA FUNZIONE
		//QUESTO PER AVERE UN CODICE ORDINATO E PER EVITARE CHE LE FUNZIONI CUSTOM NON VENGANO RICHIAMATE CORRETTAMENTE
		/*$this->custom_operations_list['mod_riunioni_verbali_check_date'] = function($request, $id = NULL){
			$ret = $this->utilities->check_date_greater_then($request['data_da'], $request['data_a']);
			if($ret === FALSE){
				$this->session->set_flashdata('error',"Data Da non puo essere maggiore di Data a");
				return false;
			}				
		};*/

	}


	public function _rules()
	{
		$this->form_validation->set_rules('data_riunione_verbale', 'data riunione', 'trim|required');
		$this->form_validation->set_rules('note', 'note', 'trim|max_length[65535]');
		$this->form_validation->set_rules('oggetto', 'oggetto', 'trim|max_length[50]|required');

		$this->form_validation->set_rules('id', 'id', 'trim');
		$this->form_validation->set_error_delimiters('<span class="text-danger">', '</span>');

	}

}