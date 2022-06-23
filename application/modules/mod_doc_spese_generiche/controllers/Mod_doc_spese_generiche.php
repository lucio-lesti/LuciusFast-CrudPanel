<?php
if (!defined('BASEPATH')){
	exit('No direct script access allowed');
}
require APPPATH . '/libraries/BaseController.php';
use Dompdf\Dompdf;

class Mod_doc_spese_generiche extends BaseController
{

	public function __construct()
	{
		parent::__construct();
		$this->load->model('Mod_doc_spese_generiche_model');
		$this->mod_name = 'mod_doc_spese_generiche';
		$this->mod_type = 'crud';
		$this->mod_title = 'Documenti Spese Generiche';
		$this->modelClassModule =  $this->Mod_doc_spese_generiche_model;
		$this->pkIdName = 'id';
		$this->viewName_ListAjax = 'mod_doc_spese_generiche_list_ajax';
		$this->viewName_FormROAjax = 'mod_doc_spese_generiche_read_ajax';
		$this->viewName_FormAjax = 'mod_doc_spese_generiche_form_ajax';

		$this->MsgDBConverted['insert']['error']['1062'] = "Esiste gia questo elemento per il modulo Documenti Spese Generiche";
		$this->MsgDBConverted['insert']['error']['1452'] = "Esiste gia questo elemento per il modulo Documenti Spese Generiche";
		$this->MsgDBConverted['update']['error']['1062'] = "Esiste gia questo elemento per il modulo Documenti Spese Generiche";
		$this->MsgDBConverted['update']['error']['1452'] = "Esiste gia questo elemento per il modulo Documenti Spese Generiche";
		$this->MsgDBConverted['insert_massive']['error']['1062'] = "Esiste gia questo elemento per il modulo Documenti Spese Generiche";
		$this->MsgDBConverted['insert_massive']['error']['1452'] = "Esiste gia questo elemento per il modulo Documenti Spese Generiche";
		$this->MsgDBConverted['update_massive']['error']['1062'] = "Esiste gia questo elemento per il modulo Documenti Spese Generiche";
		$this->MsgDBConverted['update_massive']['error']['1452'] = "Esiste gia questo elemento per il modulo Documenti Spese Generiche";
		$this->MsgDBConverted['delete']['error']['1217'] = "Impossibile eliminare questo elemento del modulo Documenti Spese Generiche. E' usato nei seguenti moduli:";
		$this->MsgDBConverted['delete_massive']['error']['1217'] = "Impossibile eliminare alcuni elementi del modulo Documenti Spese Generiche. Sono usati nei seguenti moduli:";

		//NOTE:NELLA FUNZIONE 'setFormFields' INDICARE NEL VETTORE CHE SI COLLEGA ALLA TABELLA REFERENZIATA
		//ALLA CHIAVE 'NOME', IL NOMINATIVO DEL CAMPO COLLEGATO
		$this->setFormFields('data');
		$this->setFormFields('descrizione');
		$this->setFormFields('fk_tipo_spesa','mod_tipo_spesa_generica',array("id" => 'id', "nome" => 'nome'));
		$this->setFormFields('fk_tipopagamento','mod_tipopagamento',array("id" => 'id', "nome" => 'nome'));
		$this->setFormFields('importo');
		$this->setFormFields('nome');
		$this->setFormFields('id');


		//ABILITARE PER LE OPERAZIONI "CUSTOM"

		//LA CHIAVE DEL VETTORE "custom_operations_list" RAPPRESENTA IL NOME DELLA FUNZIONE
		//QUESTO PER AVERE UN CODICE ORDINATO E PER EVITARE CHE LE FUNZIONI CUSTOM NON VENGANO RICHIAMATE CORRETTAMENTE
		/*$this->custom_operations_list['mod_doc_spese_generiche_check_date'] = function($request, $id = NULL){
			$ret = $this->utilities->check_date_greater_then($request['data_da'], $request['data_a']);
			if($ret === FALSE){
				$this->session->set_flashdata('error',"Data Da non puo essere maggiore di Data a");
				return false;
			}				
		};*/

	}


	public function _rules()
	{
		$this->form_validation->set_rules('data', 'data pagamento', 'trim');
		$this->form_validation->set_rules('descrizione', 'descrizione', 'trim|max_length[65535]');
		$this->form_validation->set_rules('fk_tipo_spesa', 'tipo spesa', 'trim|numeric|max_length[10]|required');
		$this->form_validation->set_rules('fk_tipopagamento', 'tipo pagamento', 'trim|numeric|max_length[10]|required');
		$this->form_validation->set_rules('importo', 'importo', 'trim|numeric|max_length[9]|required');
		$this->form_validation->set_rules('nome', 'nome', 'trim|max_length[100]');

		$this->form_validation->set_rules('id', 'id', 'trim');
		$this->form_validation->set_error_delimiters('<span class="text-danger">', '</span>');

	}

}