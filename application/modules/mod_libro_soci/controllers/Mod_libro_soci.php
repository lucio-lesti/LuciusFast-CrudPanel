<?php
if (!defined('BASEPATH')){
	exit('No direct script access allowed');
}
require APPPATH . '/libraries/BaseController.php';
use Dompdf\Dompdf;

class Mod_libro_soci extends BaseController
{

	public function __construct()
	{
		parent::__construct();
		$this->load->model('Mod_libro_soci_model');
		$this->mod_name = 'mod_libro_soci';
		$this->mod_type = 'crud';
		$this->mod_title = 'Libro Soci';
		$this->modelClassModule =  $this->Mod_libro_soci_model;
		$this->pkIdName = 'id';
		$this->viewName_ListAjax = 'mod_libro_soci_list_ajax';
		$this->viewName_FormROAjax = 'mod_libro_soci_read_ajax';
		$this->viewName_FormAjax = 'mod_libro_soci_form_ajax';

		$this->MsgDBConverted['insert']['error']['1062'] = "Esiste gia questo elemento per il modulo Libro Soci";
		$this->MsgDBConverted['insert']['error']['1452'] = "Esiste gia questo elemento per il modulo Libro Soci";
		$this->MsgDBConverted['update']['error']['1062'] = "Esiste gia questo elemento per il modulo Libro Soci";
		$this->MsgDBConverted['update']['error']['1452'] = "Esiste gia questo elemento per il modulo Libro Soci";
		$this->MsgDBConverted['insert_massive']['error']['1062'] = "Esiste gia questo elemento per il modulo Libro Soci";
		$this->MsgDBConverted['insert_massive']['error']['1452'] = "Esiste gia questo elemento per il modulo Libro Soci";
		$this->MsgDBConverted['update_massive']['error']['1062'] = "Esiste gia questo elemento per il modulo Libro Soci";
		$this->MsgDBConverted['update_massive']['error']['1452'] = "Esiste gia questo elemento per il modulo Libro Soci";
		$this->MsgDBConverted['delete']['error']['1217'] = "Impossibile eliminare questo elemento del modulo Libro Soci. E' usato nei seguenti moduli:";
		$this->MsgDBConverted['delete_massive']['error']['1217'] = "Impossibile eliminare alcuni elementi del modulo Libro Soci. Sono usati nei seguenti moduli:";

		//NOTE:NELLA FUNZIONE 'setFormFields' INDICARE NEL VETTORE CHE SI COLLEGA ALLA TABELLA REFERENZIATA
		//ALLA CHIAVE 'NOME', IL NOMINATIVO DEL CAMPO COLLEGATO
		$this->setFormFields('data_ammissione');
		$this->setFormFields('doc_verbale_ammissione');
		//PER L'ARRAY DI REFERENZIAMENTO, USARE IL CONCAT PER CONCATENARE PIU CAMPI NEL CAMPO 'NOME'
		//ES.CONCAT(cognome," ",nome)
		$this->setFormFields('fk_anagrafica','mod_anagrafica',array("id" => 'id', "nome" => 'CONCAT(nome," ",cognome)'), " WHERE anagrafica_attributo LIKE '%DIRETTIVO%' ");
		$this->setFormFields('id');


		//ABILITARE PER LE OPERAZIONI "CUSTOM"

		//LA CHIAVE DEL VETTORE "custom_operations_list" RAPPRESENTA IL NOME DELLA FUNZIONE
		//QUESTO PER AVERE UN CODICE ORDINATO E PER EVITARE CHE LE FUNZIONI CUSTOM NON VENGANO RICHIAMATE CORRETTAMENTE
		/*$this->custom_operations_list['mod_libro_soci_check_date'] = function($request, $id = NULL){
			$ret = $this->utilities->check_date_greater_then($request['data_da'], $request['data_a']);
			if($ret === FALSE){
				$this->session->set_flashdata('error',"Data Da non puo essere maggiore di Data a");
				return false;
			}				
		};*/

	}


	public function _rules()
	{
		$this->form_validation->set_rules('data_ammissione', 'data ammissione', 'trim|required');
		if (empty($_FILES['doc_verbale_ammissione']['name'])){
			if (empty($this->input->post('doc_verbale_ammissione_hidden'))){
				$this->form_validation->set_rules('doc_verbale_ammissione', 'Verbale Ammissione', 'required');
			}
		}
		$this->form_validation->set_rules('fk_anagrafica', 'socio', 'trim|numeric|max_length[10]|required');

		$this->form_validation->set_rules('id', 'id', 'trim');
		$this->form_validation->set_error_delimiters('<span class="text-danger">', '</span>');

	}

}