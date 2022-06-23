<?php
if (!defined('BASEPATH')){
	exit('No direct script access allowed');
}
require APPPATH . '/libraries/BaseController.php';
use Dompdf\Dompdf;

class Mod_scadenze_notifiche extends BaseController
{

	public function __construct()
	{
		parent::__construct();
		$this->load->model('Mod_scadenze_notifiche_model');
		$this->mod_name = 'mod_scadenze_notifiche';
		$this->mod_type = 'crud';
		$this->mod_title = 'Scadenze Notifiche';
		$this->modelClassModule =  $this->Mod_scadenze_notifiche_model;
		$this->pkIdName = 'id';
		$this->viewName_ListAjax = 'mod_scadenze_notifiche_list_ajax';
		$this->viewName_FormROAjax = 'mod_scadenze_notifiche_read_ajax';
		$this->viewName_FormAjax = 'mod_scadenze_notifiche_form_ajax';

		$this->MsgDBConverted['insert']['error']['1062'] = "Esiste gia questo elemento per il modulo Scadenze Notifiche";
		$this->MsgDBConverted['insert']['error']['1452'] = "Esiste gia questo elemento per il modulo Scadenze Notifiche";
		$this->MsgDBConverted['update']['error']['1062'] = "Esiste gia questo elemento per il modulo Scadenze Notifiche";
		$this->MsgDBConverted['update']['error']['1452'] = "Esiste gia questo elemento per il modulo Scadenze Notifiche";
		$this->MsgDBConverted['insert_massive']['error']['1062'] = "Esiste gia questo elemento per il modulo Scadenze Notifiche";
		$this->MsgDBConverted['insert_massive']['error']['1452'] = "Esiste gia questo elemento per il modulo Scadenze Notifiche";
		$this->MsgDBConverted['update_massive']['error']['1062'] = "Esiste gia questo elemento per il modulo Scadenze Notifiche";
		$this->MsgDBConverted['update_massive']['error']['1452'] = "Esiste gia questo elemento per il modulo Scadenze Notifiche";
		$this->MsgDBConverted['delete']['error']['1217'] = "Impossibile eliminare questo elemento del modulo Scadenze Notifiche. E' usato nei seguenti moduli:";
		$this->MsgDBConverted['delete_massive']['error']['1217'] = "Impossibile eliminare alcuni elementi del modulo Scadenze Notifiche. Sono usati nei seguenti moduli:";

		//NOTE:NELLA FUNZIONE 'setFormFields' INDICARE NEL VETTORE CHE SI COLLEGA ALLA TABELLA REFERENZIATA
		//ALLA CHIAVE 'NOME', IL NOMINATIVO DEL CAMPO COLLEGATO
		$this->setFormFields('campo_data_scadenza');
		$this->setFormFields('icona_notifica');
		$this->setFormFields('mod_name');
		$this->setFormFields('msg_notifica');
		$this->setFormFields('nr_giorni_data_notifica');
		$this->setFormFields('sql_command');
		$this->setFormFields('table_name');
		$this->setFormFields('id');

	}


	public function showElementiNonPresenti(String $type){
		$ret =  $this->home_model->getNotifyList($type);
		//print'<pre>';print_r($ret);
		foreach($ret as $key => $value){
			if($value['id'] != NULL){
			  $url = base_url()."".$value['mod_name']."/?id=".$value['id'];
			} else {
			  $url = "#";
			}
			
			echo" 
				<a href='".$url."' target='_blank'>
				<span class='fa ".$value['icona_notifica']."'></span>[".$value['msg_notifica']."]
				</a><br><br>";
			
		}		

	}


	public function _rules()
	{
		$this->form_validation->set_rules('campo_data_scadenza', '', 'trim|max_length[50]|required');
		$this->form_validation->set_rules('icona_notifica', '', 'trim|max_length[255]');
		$this->form_validation->set_rules('mod_name', '', 'trim|max_length[50]|required');
		$this->form_validation->set_rules('msg_notifica', '', 'trim|max_length[255]');
		$this->form_validation->set_rules('nr_giorni_data_notifica', '', 'trim|numeric|max_length[10]');
		$this->form_validation->set_rules('sql_command', '', 'trim|max_length[65535]');
		$this->form_validation->set_rules('table_name', '', 'trim|max_length[50]');

		$this->form_validation->set_rules('id', 'id', 'trim');
		$this->form_validation->set_error_delimiters('<span class="text-danger">', '</span>');

	}

}