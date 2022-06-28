<?php
if (!defined('BASEPATH')){
	exit('No direct script access allowed');
}
require APPPATH . '/libraries/BaseController.php';
use Dompdf\Dompdf;

class Mod_anagrafica_certificati_medici extends BaseController
{

	public function __construct()
	{
		parent::__construct();
		$this->load->model('Mod_anagrafica_certificati_medici_model');
		$this->mod_name = 'mod_anagrafica_certificati_medici';
		$this->mod_type = 'crud';
		$this->mod_title = 'Certificati Medici';
		$this->modelClassModule =  $this->Mod_anagrafica_certificati_medici_model;
		$this->pkIdName = 'id';
		$this->viewName_ListAjax = 'mod_anagrafica_certificati_medici_list_ajax';
		$this->viewName_FormROAjax = 'mod_anagrafica_certificati_medici_read_ajax';
		$this->viewName_FormAjax = 'mod_anagrafica_certificati_medici_form_ajax';


		/*
		//ABILITARE PER CUSTOMIZZAZIONE PER MODULO ERRORI SQL 
		//IN CORSO MIGLIORIA PER GESTIRE I MESSAGGI TRAMITE TABELLA DI TRASCODIFICA
		//SPOSTARE LOGICA NEL MODEL
		$this->MsgDBConverted['insert']['error']['1062'] = "Esiste gia questo elemento per il modulo Certificati Medici";
		$this->MsgDBConverted['insert']['error']['1452'] = "Esiste gia questo elemento per il modulo Certificati Medici";
		$this->MsgDBConverted['update']['error']['1062'] = "Esiste gia questo elemento per il modulo Certificati Medici";
		$this->MsgDBConverted['update']['error']['1452'] = "Esiste gia questo elemento per il modulo Certificati Medici";
		$this->MsgDBConverted['insert_massive']['error']['1062'] = "Esiste gia questo elemento per il modulo Certificati Medici";
		$this->MsgDBConverted['insert_massive']['error']['1452'] = "Esiste gia questo elemento per il modulo Certificati Medici";
		$this->MsgDBConverted['update_massive']['error']['1062'] = "Esiste gia questo elemento per il modulo Certificati Medici";
		$this->MsgDBConverted['update_massive']['error']['1452'] = "Esiste gia questo elemento per il modulo Certificati Medici";
		$this->MsgDBConverted['delete']['error']['1217'] = "Impossibile eliminare questo elemento del modulo Certificati Medici. E' usato nei seguenti moduli:";
		$this->MsgDBConverted['delete_massive']['error']['1217'] = "Impossibile eliminare alcuni elementi del modulo Certificati Medici. Sono usati nei seguenti moduli:";
		*/


		//NOTE:NELLA FUNZIONE 'setFormFields' INDICARE NEL VETTORE CHE SI COLLEGA ALLA TABELLA REFERENZIATA
		//ALLA CHIAVE 'NOME', IL NOMINATIVO DEL CAMPO COLLEGATO
		//PER L'ARRAY DI REFERENZIAMENTO, USARE IL CONCAT PER CONCATENARE PIU CAMPI NEL CAMPO 'NOME'
		//ES.CONCAT(cognome," ",nome)
		$this->setFormFields('fk_anagrafica','mod_anagrafica',array("id" => 'id', "nome" => 'CONCAT(nome," ",cognome," - ",codfiscale)'));
		$this->setFormFields('tipologia');
		$this->setFormFields('data_certificato');
		$this->setFormFields('data_scadenza');
		$this->setFormFields('documento_upload');
		$this->setFormFields('nome_documento');
		$this->setFormFields('id');


		/**  AREA LAMBDA FUNCTIONS - FUNZIONI RICHIAMATE in updateAjax, createAjax e nelle op. di CRUD master details**/

		//VERIFICO DATA CERTIFICATO
		$this->custom_operations_list['mod_check_date'] = function ($request, $id = NULL) {
			$ret = $this->utilities->check_date_greater_then($request['data_certificato'], $request['data_scadenza']);
			if ($ret === FALSE) {
				$this->session->set_flashdata('error', "Data Certificato non puo essere maggiore di Data Scadenza");
				return false;
			}

		};


		//PRELEVO IL NOME DEL DOCUMENTO CARICATO
		$this->custom_operations_list['get_nome_documento'] = function ($request, $id = NULL) {
			$_POST['nome_documento'] = $_FILES['documento_upload']['name'];
		};

	}



	public function _rules()
	{
		$this->form_validation->set_rules('fk_anagrafica', 'anagrafica', 'trim|numeric|max_length[10]|required');
		$this->form_validation->set_rules('tipologia', 'tipologia certificato', 'trim|required');
		$this->form_validation->set_rules('data_certificato', 'data certificato', 'trim|required');
		$this->form_validation->set_rules('data_scadenza', 'data scadenza certificato', 'trim|required');
		if (empty($_FILES['documento_upload']['name'])){
			if (empty($this->input->post('documento_upload_hidden'))){
				$this->form_validation->set_rules('documento_upload', 'Documento Upload', '');
			}
		}
		//$this->form_validation->set_rules('nome_documento', 'nome documento', 'trim|max_length[255]|required');

		$this->form_validation->set_rules('id', 'id', 'trim');
		$this->form_validation->set_error_delimiters('<span class="text-danger">', '</span>');

	}

}