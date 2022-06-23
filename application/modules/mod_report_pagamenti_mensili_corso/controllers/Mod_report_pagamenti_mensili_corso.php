<?php
if (!defined('BASEPATH')) {
	exit('No direct script access allowed');
}
require APPPATH . '/libraries/BaseController.php';
 

class Mod_report_pagamenti_mensili_corso extends BaseController
{

	public function __construct()
	{
		parent::__construct();
		$this->load->model('Mod_report_pagamenti_mensili_corso_model');
		$this->mod_name = 'mod_report_pagamenti_mensili_corso';
		$this->mod_type = 'report';
		$this->mod_title = 'Report Pagamenti Mensili';
		$this->modelClassModule =  $this->Mod_report_pagamenti_mensili_corso_model;
		$this->pkIdName = '';
		$this->viewName_ListAjax = 'mod_report_pagamenti_mensili_corso_list_ajax';


		$this->addComboGridFilter(
			'mod_esercizi_id',
			'mod_esercizi',
			"id",
			"nome",
			"Filtra per Esercizio",
			array("filter_slave_id" => "mod_corsi_id", "filter_slave_population_function" => "populateCorsi")
		);

		$this->addComboGridFilter('mod_corsi_id', NULL, NULL, NULL, "Filtra per Corso",NULL,true,true);
	}


	/**
	 * Metodo principale index
	 */
	public function index()
	{

		$data = array(
			'total_rows' => $this->modelClassModule->total_rows(),
			'module' => $this->mod_name,
			'notifyList' =>  $this->home_model->getNotifyList(),
			'formFields' => $this->formFields,
			'formFieldsReferenced' => $this->formFieldsReferenced,
			'formFieldsReferencedTableRef' => $this->formFieldsReferencedTableRef,
			'mod_type' => $this->mod_type,
			'comboGridFilter' => $this->arrayComboGridFilter,
			'mod_esercizio_id' => $this->getEsercizioCorrente()
		);

		//PRELEVO PRIVILEGI
		$global_permissions = $this->user_model->getPermissionRole($this->session->userdata('role'));
		foreach ($global_permissions as $key => $module_permission) {
			if ($module_permission->mod_name == $this->mod_name) {
				$data['perm_read'] = $module_permission->perm_read;
				$data['perm_write'] = $module_permission->perm_write;
				$data['perm_update'] = $module_permission->perm_update;
				$data['perm_delete'] = $module_permission->perm_delete;
				break;
			}
		}


		$this->global['pageTitle'] = $this->mod_title . ' - Lista';
		$this->loadViews($this->mod_name . '/' . $this->viewName_ListAjax, $this->global, $data, null);
	}


	private function getEsercizioCorrente(){
		return $this->modelClassModule->getEsercizioCorrente();
	}



	public function populateCorsi()
	{
		if (trim($_REQUEST['filter_master_value']) != "") {
			echo json_encode($this->modelClassModule->populateCorsi($_REQUEST['filter_master_value']));
		} else {
			echo json_encode(array());
		}
	}


	/**
	 * Stampa generica di un modulo
	 * Per le stampe ad-hoc, bisogna reimplentare il metodo o fare metodi su misura per la stampa
	 * Deve sempre ritornare un file stream pdf per il salvataggio sul server
	 * @param mixed $id
	 * @return object
	 * 
	 */
	public function stampa_rep($idEserc = NULL, $listaCorsi = NULL, $anagrafica = NULL, $corsi_nome = NULL,
		$data_iscrizione = NULL, $Sett = NULL, $Ott = NULL, $Nov = NULL, $Dic = NULL, $Gen = NULL, $Feb = NULL,
		$Mar = NULL, $Apr = NULL, $Mag = NULL, $Giu = NULL, $Lug = NULL, $Ago = NULL, $corsi_tipo = NULL)
	{
		$this->global['pageTitle'] = $this->mod_title . ' - Stampa';

		$dompdf = new Dompdf();

		$row = $this->modelClassModule->getPagamentiMensili($idEserc, $listaCorsi, $anagrafica,$corsi_nome,
				$data_iscrizione, $Sett, $Ott, $Nov, $Dic, $Gen, $Feb,$Mar, $Apr, $Mag,$Giu ,$Lug, $Ago, $corsi_tipo);
		$esercizio = "";
		$corso = "";


		if ($idEserc != NULL) {
			$esercizioObj = $this->modelClassModule->get_by_id($idEserc, "mod_esercizi", "id");
			if (isset($esercizioObj->nome)) {
				$esercizio = $esercizioObj->nome;
			}
		}

		$elencoCorsiNome = "";
		if ($listaCorsi != NULL) {
			$listaCorsiArray = explode(",",$listaCorsi);
			foreach($listaCorsiArray as $k => $idCorso){
				$corsoObj = $this->modelClassModule->get_by_id($idCorso, "mod_corsi", "id");
				if (isset($corsoObj->nome)) {
					$elencoCorsiNome .= ", ".$corsoObj->nome;
				}
			}

		}

		$data['righe'] = $row;
		$data['esercizio'] = $esercizio;
		$data['corso'] = $elencoCorsiNome;
 


		//PRINT'<PRE>';print_r($data);die();
		$view = $this->load->view($this->mod_name . '/' . $this->mod_name . '_form_pdf', $data, true);
		$dompdf->loadHtml($view);
		$dompdf->set_option("isPhpEnabled", true);

		$dompdf->setPaper('A4', 'landscape');

		$dompdf->render();

		$x = 520;
		$y = 820;
		$text = "Pagina {PAGE_NUM} di {PAGE_COUNT}";
		$font = $dompdf->getFontMetrics()->get_font('Helvetica', 'normal');
		$size = 10;
		$color = array(0, 0, 0);
		$word_space = 0.0;
		$char_space = 0.0;
		$angle = 0.0;

		$dompdf->getCanvas()->page_text(
			$x,
			$y,
			$text,
			$font,
			$size,
			$color,
			$word_space,
			$char_space,
			$angle
		);

		$out = $dompdf->output();
		$dompdf->stream($this->mod_title . '_' . date('Y-m-d_H_i') . '.pdf', array("Attachment" => false));

		return $out;
	}



	public function _rules()
	{
		$this->form_validation->set_rules('Ago', '', 'trim|max_length[10]');
		$this->form_validation->set_rules('Anagrafica', '', 'trim|max_length[101]');
		$this->form_validation->set_rules('Apr', '', 'trim|max_length[10]');
		$this->form_validation->set_rules('Data_Iscrizione', 'data iscrizione', 'trim');
		$this->form_validation->set_rules('Dic', '', 'trim|max_length[10]');
		$this->form_validation->set_rules('Feb', '', 'trim|max_length[10]');
		$this->form_validation->set_rules('Gen', '', 'trim|max_length[10]');
		$this->form_validation->set_rules('Giu', '', 'trim|max_length[10]');
		$this->form_validation->set_rules('id', '', 'trim|max_length[0]|required');
		$this->form_validation->set_rules('ids', '', 'trim|max_length[0]|required');
		$this->form_validation->set_rules('Lug', '', 'trim|max_length[10]');
		$this->form_validation->set_rules('Mag', '', 'trim|max_length[10]');
		$this->form_validation->set_rules('Mar', '', 'trim|max_length[10]');
		$this->form_validation->set_rules('Nov', '', 'trim|max_length[10]');
		$this->form_validation->set_rules('Ott', '', 'trim|max_length[10]');
		$this->form_validation->set_rules('Set', '', 'trim|max_length[10]');

		$this->form_validation->set_rules('', '', 'trim');
		$this->form_validation->set_error_delimiters('<span class="text-danger">', '</span>');
	}
}
