<?php
if (!defined('BASEPATH')) {
	exit('No direct script access allowed');
}
require APPPATH . '/libraries/BaseController.php';

use Dompdf\Dompdf;

class Mod_anagrafica_pagamenti_v extends BaseController
{

	public function __construct()
	{
		parent::__construct();
		$this->load->model('Mod_anagrafica_pagamenti_v_model');
		$this->mod_name = 'mod_anagrafica_pagamenti_v';
		$this->mod_type = 'crud';
		$this->mod_title = 'Pagamenti Ricevuti';
		$this->modelClassModule =  $this->Mod_anagrafica_pagamenti_v_model;
		$this->pkIdName = 'id';
		$this->viewName_ListAjax = 'mod_anagrafica_pagamenti_v_list_ajax';
		$this->viewName_FormROAjax = 'mod_anagrafica_pagamenti_v_read_ajax';
		$this->viewName_FormAjax = 'mod_anagrafica_pagamenti_v_form_ajax';

		$this->MsgDBConverted['insert']['error']['1062'] = "Esiste gia questo elemento per il modulo Pagamenti Ricevuti";
		$this->MsgDBConverted['insert']['error']['1452'] = "Esiste gia questo elemento per il modulo Pagamenti Ricevuti";
		$this->MsgDBConverted['update']['error']['1062'] = "Esiste gia questo elemento per il modulo Pagamenti Ricevuti";
		$this->MsgDBConverted['update']['error']['1452'] = "Esiste gia questo elemento per il modulo Pagamenti Ricevuti";
		$this->MsgDBConverted['insert_massive']['error']['1062'] = "Esiste gia questo elemento per il modulo Pagamenti Ricevuti";
		$this->MsgDBConverted['insert_massive']['error']['1452'] = "Esiste gia questo elemento per il modulo Pagamenti Ricevuti";
		$this->MsgDBConverted['update_massive']['error']['1062'] = "Esiste gia questo elemento per il modulo Pagamenti Ricevuti";
		$this->MsgDBConverted['update_massive']['error']['1452'] = "Esiste gia questo elemento per il modulo Pagamenti Ricevuti";
		$this->MsgDBConverted['delete']['error']['1217'] = "Impossibile eliminare questo elemento del modulo Pagamenti Ricevuti. E' usato nei seguenti moduli:";
		$this->MsgDBConverted['delete_massive']['error']['1217'] = "Impossibile eliminare alcuni elementi del modulo Pagamenti Ricevuti. Sono usati nei seguenti moduli:";

		//NOTE:NELLA FUNZIONE 'setFormFields' INDICARE NEL VETTORE CHE SI COLLEGA ALLA TABELLA REFERENZIATA
		//ALLA CHIAVE 'NOME', IL NOMINATIVO DEL CAMPO COLLEGATO
		$this->setFormFields('id');
		$this->setFormFields('anagrafica');
		$this->setFormFields('affiliazione');
		$this->setFormFields('esercizio');
		
		$this->setFormFields('fk_affiliazione', 'mod_affiliazioni', array("id" => 'id', "nome" => 'nome'));
		$this->setFormFields('fk_anagrafica', 'mod_anagrafica', array("id" => 'id', "nome" => 'CONCAT(nome," ",cognome," - ",codfiscale)'));
		$this->setFormFields('fk_esercizio', 'mod_esercizi', array("id" => 'id', "nome" => 'nome'));

		//$this->setFormFields('importo');
		$this->addComboGridFilter(
			'esercizio_id',
			'mod_esercizi',
			"id",
			"nome",
			"Filtra per Esercizio"
		);

		$this->custom_form_data_functions['fk_affiliazione_txt'] = function () {
			$ret = "";
			if ((isset($_REQUEST['fk_affiliazione']))) {
				if ($_REQUEST['fk_affiliazione'] != "") {
					$ret = $this->modelClassModule->getNomeAffiliazione($_REQUEST['fk_affiliazione']);
				}
			}
			return  $ret;
		};

		$this->custom_form_data_functions['mod_esercizio_id'] = function () {
			return $this->getEsercizioCorrente();
		};

		$this->custom_form_data_functions['id_esercizio'] = function () {
			return $this->getEsercizioCorrente();
		};

		$this->custom_form_data_functions['id_anagrafica'] = function () {
			$arr = explode("_", $_REQUEST['recordID']);

			return $arr[0];
		};


		$this->custom_form_data_functions['master_details_list'] = function () {
			$arr = explode("_", $_REQUEST['recordID']);
			$idAnagrafica = $arr[0];
			$idEsercizio = $arr[1];
			$rows = $this->getMasterDetail_listaCorsiPerAllievo($idAnagrafica, $idEsercizio);

			$count = 0;
			foreach ($rows as $k => $v) {
				$idCorso = $v['id'];
				$this->masterDetailsLoadFuncList[$idCorso] = array("title" => $v['nome'], "id" => $idCorso,  "function" => $this->getPagamentiPerCorso($idAnagrafica, $idCorso));
				$count++;
			}
			$count = $count + 1;
			$this->masterDetailsLoadFuncList["tbtess"] = array("title" => "Pagamento Tesseramenti", "id" => "tbtess",  "function" => $this->getPagamentiTesseramenti($idAnagrafica, $idEsercizio));

		};

		$this->custom_form_data_functions['importo'] = function () {
			return $this->getEsercizioCorrente();
		};

		$this->custom_form_data_functions['lista_corsi'] = function () {
			$arr = explode("_", $_REQUEST['recordID']);
			$idAnagrafica = $arr[0];
			$idEsercizio = $arr[1];
			return  $this->modelClassModule->getCorsiPerAllievo($idAnagrafica, $idEsercizio);
		};
	}




	/**
	 * Esegue l'inserimento del modulo in modalita update
	 */
	public function update_action()
	{
 
		$this->_rules();
 
		$arrayBlobFile = array();
		if (isset($_SESSION['success'])) {
			unset($_SESSION['success']);
		}
		if (isset($_SESSION['error'])) {
			unset($_SESSION['error']);
		}
		//print'<pre>';print_r($_REQUEST);die();
		//print'<pre>';print_r($this->formFields);
		//print'<pre>';print_r($_REQUEST);die();
		foreach($_REQUEST['importo_scontato'] as $fk_corso => $v){
			$data = array();
			$data['fk_anagrafica'] = $_REQUEST['fk_anagrafica'];
			$data['fk_corso']  = $fk_corso;
			$data['importo_scontato']  = $v;
			$data['importo']  = $_REQUEST['importo'][$fk_corso];
			$data['data_aggiornamento']  = date('Y-m-d');

			$this->modelClassModule->insertImportoScontato($data);
			$this->session->set_flashdata('success', "Importi Aggiornati");

		}
 
		$dataLog = array(
			'programma' => $this->mod_name,
			'utente' => $_SESSION['name'],
			'azione' => "Modifica Sconti Importi",
			'data' => date('Y-m-d H:i'),
		);
		$this->Common_model->insertLog($dataLog);

		$this->updateAjax($_REQUEST['fk_anagrafica']."_".$_REQUEST['fk_esercizio'] );
	}

	
	
	/**
	 * Funzione caricamento della master details, tabella _mod_pagamenti_ricevuti
	 * @param mixed $id
	 * @param string $isAjax
	 * @return string
	 **/
	public function getPagamentiPerCorso($idAnagrafica, $idCorso)
	{
		$id = $_REQUEST['recordID'];
		$row =  $this->modelClassModule->getPagamentiAllievoPerCorso($idAnagrafica, $idCorso);
		$html = '';
		$winFormType = "form"; //VALORI ACCETTATI: {'multi','form'}

		$tipologia_corso = "";

		if (isset($row[0]['tipologia_corso'])) {
			//print'<pre>';print_r($row[0]);
			$tipologia_corso = $row[0]['tipologia_corso'];
		} else {
			$rowCorso = $this->modelClassModule->get_by_id($idCorso, 'mod_corsi');
			$tipologia_corso = $rowCorso->tipologia_corso;
		}
		//echo "tipologia_corso:".$tipologia_corso ;

		$html .= '<BR><a href="' . base_url() . 'mod_corsi_fast_ins/?id=' . $idCorso . '" target="_blank">
				<i class="fa fa-external-link" 
			style="border-radius: 0;border:solid 1px #999;background-color:#d2d6de;padding:5px"></i> Vai a questo corso
				</a><BR>';
		if ($tipologia_corso == 'MENSILE') {
			$html .= '
			<br><a class="btn btn-primary" style="cursor:pointer" onclick="winFormMasterDetails(\'mod_anagrafica_pagamenti_v\',\'winMasterDetail_mod_pagamenti_ricevuti\',\'insert\',\'' . $id . '_' . $idCorso . '\',\'NULL\',\'Aggiungi Pagamento\', arrayValidationFields,\'winMasterDetail_mod_pagamenti_ricevuti\',\'form\',\''.$idCorso.'\')">[ Aggiungi un elemento]</a> 
			<br><br>';
		}
		$html .= '<br>';
		$html .= ' <input  type="text" class="form-control" autocomplete="off" 
							id="search_mod_pagamenti_ricevuti_'.$idCorso.'" style="width:20%" placeholder="Cerca..."
							onkeypress="disableKeySubmit()"
							onkeyup="searchInMasterDetailsTable(\'search_mod_pagamenti_ricevuti'.$idCorso.'\', \'tbl_mod_pagamenti_ricevuti\',8)"><br>';
		$html .= "<table class='TFtable' id='tbl_mod_pagamenti_ricevuti' style='font-size:12px'>
					<tr>
						<thead>
						<th class='sorting_disabled' rowspan='1' colspan='1' aria-label='' style='width:10%;'>
							<input type='checkbox' id='check_master_mod_pagamenti_ricevuti_$idCorso' name='check_master_mod_pagamenti_ricevuti_$idCorso' 
							onchange=\"selezionaDeselezionaTutti('check_master_mod_pagamenti_ricevuti_$idCorso','check_id_mod_pagamenti_ricevuti_$idCorso','btDeleteMass_mod_pagamenti_ricevuti_$idCorso')\">
						</th>";
		$html .= '<th>Data Pagamento</th>';
		$html .= '<th>Importo</th>';
		$html .= '<th>Data Iscrizione</th>';
		$html .= '<th>Mese</th>';
		$html .= '<th>Anno</th>';
		$html .= '<th>Tipo Pagamento</th>';
		$html .= '<th>Causale Pagamento</th>';
		$html .= '<th>Saldo</th>';
		$html .= '<th>Invia</th>';
		$html .= '<th>Stampa</th>';
		if ($winFormType == "form") {
			$html .= '<th>Modifica</th>';
		}
		if ($tipologia_corso == 'MENSILE') {
			$html .= '<th>Elimina</th>';
		}
		$html .= '</tr>';
		$html .= '<tbody>';
		//print'<pre>';print_r($row);
		foreach ($row as $key => $value) {
			$html .= "<tr>";
			$html .= "<td><input type='checkbox' id='check_id_mod_pagamenti_ricevuti_$idCorso' name='check_id_mod_pagamenti_ricevuti_$idCorso' value='" . $value['id'] . "' onchange=\"verificaNrCheckBoxSelezionati('check_id_mod_pagamenti_ricevuti_$idCorso','btDeleteMass_mod_pagamenti_ricevuti')\"></td>";
			if ($value['datapagamento'] != "") {
				$data_pagamento = $this->utilities->convertToDateIT($value['datapagamento']);
			} else {
				$data_pagamento = "";
			}
			if ($value['data_iscrizione'] != "") {
				$data_iscrizione = $this->utilities->convertToDateIT($value['data_iscrizione']);
			} else {
				$data_iscrizione = "";
			}
			$html .= "<td><input type='hidden' id='id[]' name='id[]' value='" . $value['id'] . "'>" . $data_pagamento . "</td>";
			$html .= "<td><input type='hidden' id='id[]' name='id[]' value='" . $value['id'] . "'> € " . str_replace(".", ".", $value['importo']) . "</td>";
			$html .= "<td><input type='hidden' id='id[]' name='id[]' value='" . $value['id'] . "'>" . $data_iscrizione . "</td>";
			$html .= "<td><input type='hidden' id='id[]' name='id[]' value='" . $value['id'] . "'>" . $value['mese'] . "</td>";
			$html .= "<td><input type='hidden' id='id[]' name='id[]' value='" . $value['id'] . "'>" . $value['anno'] . "</td>";
			$html .= "<td><input type='hidden' id='id[]' name='id[]' value='" . $value['id'] . "'>" . $value['tipo_pagamento'] . "</td>";
			$html .= "<td><input type='hidden' id='id[]' name='id[]' value='" . $value['id'] . "'>" . $value['causale_pagamento'] . "</td>";

			$html .= "<td><input type='hidden' id='id[]' name='id[]' value='" . $value['id'] . "'>" . $value['saldo'] . "</td>";
			$disabled = "";
			if ($value['email'] == '') {
				$disabled = "disabled";
			}

			if (($value['saldo'] != 'NON PAGHERA') && ($value['saldo'] != 'NO') && (trim($value['saldo']) != '')) {
				$html .= "<td><a onclick=\"sendMailWithAttach('mod_anagrafica_pagamenti_v','" . $value['email'] . "'," . $value['id'] . ",'Invio Ricevuta Pagamento')\" style='cursor:pointer' class='btn btn-sm btn-primary'  $disabled  title='Invio via mail'><i class='fa fa-at'></a></td>";
				$html .= "<td><a href='mod_anagrafica_pagamenti_v/stampa/" . $value['id'] . "' target='_blank' style='cursor:pointer' class='btn btn-sm btn-warning'  title='Stampa'><i class='fa fa-print'></a></td>";
			} else {
				$html .= "<td></td>";
				$html .= "<td></td>";
			}

			if ($winFormType == "form") {
				$html .= "<td><a style='cursor:pointer' class='btn btn-sm btn-info' onclick ='winFormMasterDetails(\"mod_anagrafica_pagamenti_v\",\"winMasterDetail_mod_pagamenti_ricevuti\",\"edit\", \"$id\"," . $value['id'] . ",\"Modifica Pagamento\",arrayValidationFields,\"winMasterDetail_mod_pagamenti_ricevuti\",\"form\",\"$idCorso\")' title='Modifica Anagrafati Corsi'><i class='fa fa-edit'></a></td>";
			}
			if ($tipologia_corso == 'MENSILE') {
				$html .= "<td><a style='cursor:pointer' class='btn btn-sm btn-danger deleteUser' onclick ='deleteMasterDetails(\"" . $value['id'] . "\", \"" . $id . "\", \"mod_anagrafica_pagamenti_v\",\"_mod_pagamenti_ricevuti\",\"$idCorso\")' title='Elimina'><i class='fa fa-trash'></a></td>";
			}
			$html .= '</tr>';
		}
		$html .= '</tbody></table>';


		if ($tipologia_corso == 'MENSILE') {
			$html .= '<br/><a class="btn btn-sm btn-danger deleteUser" id="btDeleteMass_mod_pagamenti_ricevuti" name="btDeleteMass_mod_pagamenti_ricevuti"
						onclick="deleteMassiveMasterDetails(\'' . $id . '\',\'entry_list\',\'check_id_mod_pagamenti_ricevuti_'.$idCorso.'\',\'mod_anagrafica_pagamenti_v\',\'_mod_pagamenti_ricevuti\',\''.$idCorso.'\')">
						<i class="fa fa-trash"></i> Cancellazione Massiva
					</a>';	
		}
		return $html;
	}



	public function getPagamentiTesseramenti($idAnagrafica, $idEsercizio)
	{
		$id = $_REQUEST['recordID'];
		$row =  $this->modelClassModule->getPagamentiTesseramenti($idAnagrafica, $idEsercizio);
		$html = '';
		$winFormType = "form"; //VALORI ACCETTATI: {'multi','form'}
		/*
		$html .= '
		<br><a class="btn btn-primary" style="cursor:pointer" onclick="winFormMasterDetails(\'mod_anagrafica_pagamenti_v\',\'winMasterDetail_tesseramenti\',\'insert\',\'' . $id . '\',\'NULL\',\'Aggiungi Pagamento\', arrayValidationFields,\'winMasterDetail_mod_pagamenti_ricevuti\',\'form\',\'tbtess\')">[ Aggiungi un elemento]</a> 
		<br><br>';
		*/
		
		$html .= '<br><input  type="text" class="form-control" autocomplete="off" 
							id="search_mod_pagamenti_tesseramenti" style="width:20%" placeholder="Cerca..."
							onkeypress="disableKeySubmit()"
							onkeyup="searchInMasterDetailsTable(\'search_mod_pagamenti_tesseramenti\', \'tbl_mod_pagamenti_tesseramenti\',8)"><br>';
		$html .= "<table class='TFtable' id='tbl_mod_pagamenti_tesseramenti' style='font-size:12px'>
					<tr>
						<thead>
						<th class='sorting_disabled' rowspan='1' colspan='1' aria-label='' style='width:10%;'>
							<input type='checkbox' id='check_master_mod_pagamenti_tesseramenti' name='check_master_mod_pagamenti_tesseramenti' 
							onchange=\"selezionaDeselezionaTutti('check_master_mod_pagamenti_tesseramenti','check_id_mod_pagamenti_tesseramenti','btDeleteMass_mod_pagamenti_ricevuti')\">
						</th>";
		$html .= '<th>Data Pagamento</th>';
		$html .= '<th>Importo</th>';
		$html .= '<th>Tipo Pagamento</th>';
		$html .= '<th>Causale Pagamento</th>';
		$html .= '<th>Saldo</th>';

		$html .= '<th>Invia</th>';
		$html .= '<th>Stampa</th>';
		if ($winFormType == "form") {
			//$html .= '<th>Modifica</th>';
		}
		//$html .= '<th>Elimina</th>';
		$html .= '</tr>';
		$html .= '<tbody>';
		//print'<pre>';print_r($row);
		foreach ($row as $key => $value) {
			$html .= "<tr>";
			$html .= "<td><input type='checkbox' id='check_id_mod_pagamenti_tesseramenti' name='check_id_mod_pagamenti_tesseramenti' value='" . $value['id'] . "' onchange=\"verificaNrCheckBoxSelezionati('check_id_mod_pagamenti_tesseramenti','btDeleteMass_mod_pagamenti_ricevuti')\"></td>";
			if ($value['datapagamento'] != "") {
				$data_pagamento = $this->utilities->convertToDateIT($value['datapagamento']);
			} else {
				$data_pagamento = "";
			}
			$html .= "<td><input type='hidden' id='id[]' name='id[]' value='" . $value['id'] . "'>" . $data_pagamento . "</td>";
			$html .= "<td><input type='hidden' id='id[]' name='id[]' value='" . $value['id'] . "'> € " . str_replace(".", ".", $value['importo']) . "</td>";
			$html .= "<td><input type='hidden' id='id[]' name='id[]' value='" . $value['id'] . "'>" . $value['tipo_pagamento'] . "</td>";
			$html .= "<td><input type='hidden' id='id[]' name='id[]' value='" . $value['id'] . "'>TESSERAMENTO</td>";
			$html .= "<td><input type='hidden' id='id[]' name='id[]' value='" . $value['id'] . "'>" . $value['saldo'] . "</td>";
			$disabled = "";
			if ($value['email'] == '') {
				$disabled = "disabled";
			}

			if (($value['saldo'] != 'NON PAGHERA') && ($value['saldo'] != 'NO')) {
				$html .= "<td><a onclick=\"sendMailWithAttach('mod_anagrafica_pagamenti_v','" . $value['email'] . "'," . $value['id'] . ",'Invio Ricevuta Tesseramento')\" style='cursor:pointer' class='btn btn-sm btn-primary'  $disabled  title='Invio via mail'><i class='fa fa-at'></a></td>";
				$html .= "<td><a href='mod_anagrafica_pagamenti_v/stampa_tess/" . $value['id'] . "' target='_blank' style='cursor:pointer' class='btn btn-sm btn-warning'  title='Stampa'><i class='fa fa-print'></a></td>";
			} else {
				$html .= "<td></td>";
				$html .= "<td></td>";
			}

			if ($winFormType == "form") {
				//$html .= "<td><a style='cursor:pointer' class='btn btn-sm btn-info' onclick ='winFormMasterDetails(\"mod_anagrafica_pagamenti_v\",\"winMasterDetail_tesseramenti\",\"edit\", \"$id\"," . $value['id'] . ",\"Modifica Pagamento\",arrayValidationFields,\"winMasterDetail_mod_pagamenti_ricevuti\",\"form\",\"tbtess\")' title='Modifica Anagrafati Corsi'><i class='fa fa-edit'></a></td>";
			}
			//$html .= "<td><a style='cursor:pointer' class='btn btn-sm btn-danger deleteUser' onclick ='deleteMasterDetails(\"" . $value['id'] . "\", \"" . $id . "\", \"mod_anagrafica_pagamenti_v\",\"_mod_pagamenti_ricevuti\",\"tbtess\")' title='Elimina'><i class='fa fa-trash'></a></td>";
			$html .= '</tr>';
		}
		$html .= '</tbody></table>';
		/*
		$html .= '<br/><a class="btn btn-sm btn-danger deleteUser" id="btDeleteMass_mod_pagamenti_tesseramenti" name="btDeleteMass_mod_pagamenti_tesseramenti"
					onclick="deleteMassiveMasterDetails(\'' . $id . '\',\'entry_list\',\'check_id_mod_pagamenti_tesseramenti\',\'mod_anagrafica_pagamenti_v\',\'_mod_pagamenti_tesseramenti\',\'tbtess\')">
					<i class="fa fa-trash"></i> Cancellazione Massiva
				</a>';	
		*/	
		return $html;
	}




	/**
	 * Funzione caricamento della finestra per la master details, tabella _mod_pagamenti_ricevuti
	 * @param mixed $action
	 * @param string $entryID
	 * @param string $entryIDMasterDetails
	 * @return string
	 **/
	public function winMasterDetail_mod_pagamenti_ricevuti($action, $entryID, $entryIDMasterDetails = NULL)
	{
		$idAnagrafica = "";
		$idEsercizio  = "";
		if ($entryIDMasterDetails == 'NULL') {
			$entryIDMasterDetails = '';
		}
		if ($entryIDMasterDetails != NULL) {
			$rowWinForm = $this->modelClassModule->get_from_master_details_by_id($entryIDMasterDetails, '_mod_pagamenti_ricevuti', 'id');
		} else {
			$arrayId = explode("_", $entryID);
			$fk_corso = $arrayId[2];
			$fk_anagrafica = $arrayId[0];
			$rowCorsi = $this->modelClassModule->get_from_master_details_by_id($fk_corso, 'mod_corsi', 'id');
			$rowWinForm = array();
			$rowWinForm['datapagamento'] = "";
			$rowWinForm['fk_corso'] = $fk_corso;
			$rowWinForm['fk_anagrafica'] = $fk_anagrafica;
			$rowWinForm['saldo'] = "";
			$rowWinForm['mese'] = "";
			$rowWinForm['anno'] = "";
			$rowWinForm['fk_tipopagamento'] = "";
			$rowWinForm['fk_causale_pagamento'] = "";
			$rowWinForm['importo'] = $rowCorsi['importo_mensile'];
			$rowWinForm['notepagamento'] = "";
			$rowWinForm['datapagamento'] = "";
		}
		
		//print'<pre>'.$entryID;print_r($rowWinForm);
		//$idAnagrafica = $rowWinForm['fk_anagrafica'];

		$html = '<div>
				<section class="content">
					<div class="row">
						<div class="col-md-12">
							<div class="box box-primary">
								<div class="box-body">
								<div id="msg_err" ondblclick="this.style.display=\'none\';" style="display: none; border: 1px solid; background-color: rgb(255, 64, 0); border-radius: 5px; padding: 10px; color: white; font-weight: bold;">
								</div>									
									<form  name="frm_master_detail" id="frm_master_detail">
									<input type="hidden" id="table" name="table" value="_mod_pagamenti_ricevuti">
									<input type="hidden" id="action" name="action" value="' . $action . '"/> 
									<input type="hidden" id="saveType" name="saveType" value="form"/> 	
									<input type="hidden" id="entryID"           name="entryID"  value="' . $entryID . '">
									<input type="hidden" id="recordID"          name="recordID"  value="' . $entryID . '">
									<input type="hidden" id="id"          	    name="id"  value="' . $entryIDMasterDetails . '">
									<input type="hidden" id="fk_anagrafica"     name="fk_anagrafica"  value="' . $rowWinForm['fk_anagrafica'] . '">
									<input type="hidden" id="fk_corso"          name="fk_corso"  value="' . $rowWinForm['fk_corso'] . '">
									<input type="hidden" id="entryIDMasterDetails" name="entryIDMasterDetails" value="' . $entryIDMasterDetails . '" >															
										<div class="col-md-12">
											<div class="form-group">';

		$html .= '<div class="col-md-6"><div class="form-group">';
		$html .= '<label for="date"><b style="color:#990000">(*)</b>Data Pagamento </label>';
		$html .= '<div class="input-group">';
		$html .= '<div class="input-group-addon"><i class="fa fa-calendar"></i></div>';
		$html .= '<input type="text" class="form-control datemask" name="datapagamento" id="datapagamento" placeholder="Data Pagamento"';

		if ($rowWinForm['datapagamento'] == "") {
			$rowWinForm['datapagamento'] = date("Y-m-d");
		}
		if (!isset($rowWinForm['datapagamento'])) {
			$rowWinForm['datapagamento'] = date("Y-m-d");
		}
		$html .= 'autocomplete="off" style="background-color:#FFFFFF" value="' . $this->utilities->convertToDateIT($rowWinForm['datapagamento']) . '" />';
		$html .= '</div></div></div>';


		$html .= '<div class="col-md-6"><div class="form-group">';
		$html .= '<label for="date"><b style="color:#990000">(*)</b>Mese - Anno </label>';
		$html .= '<div class="input-group">';
		$html .= '<div class="input-group-addon"><i class="fa fa-text-height"></i></div>';


		$listaMesiAnni = $this->modelClassModule->getListaMesiAnniDaPagarePerCorso($rowWinForm['fk_corso']);
		
		//if ($this->isAdmin() == TRUE) {
			$html .= '<SELECT class="form-control" id="mese_anno" name="mese_anno">';
			$html .= '<OPTION value=""></OPTION>';
			$selected = "";
			foreach ($listaMesiAnni['mese_anno_ita'] as $k => $v) {

				if ($v == $rowWinForm['mese'] . "-" . $rowWinForm['anno']) {
					$selected = "selected";
				} else {
					$selected = "";
				}
				$html .= '<option value="' . $v . '" ' . $selected . '>' . $v . '</option>';
			}
			$html .= '</SELECT>';
		/*
		} else {
			$html .= '<input type="text" readonly="readonly" class="form-control" id="mese_anno" name="mese_anno" value="' . $rowWinForm['mese'] . "-" . $rowWinForm['anno'] . '"> ';
		}
		*/
		$html .= '</div></div></div>';



		$html .= '<div class="col-md-6"><div class="form-group">';
		$html .= '<label for="date"><b style="color:#990000">(*)</b>Importo </label>';
		$html .= '<div class="input-group">';
		$html .= '<div class="input-group-addon"><i class="fa fa-sort-numeric-asc"></i></div>';
		$html .= '<input type="number" class="form-control" name="importo" id="importo" placeholder="Importo"';
		$html .= 'autocomplete="off" style="background-color:#FFFFFF" value="' . $rowWinForm['importo'] . '" />';
		$html .= '</div></div></div>';


		$html .= '<div class="col-md-6"><div class="form-group">';
		$html .= '<label for="date"><b style="color:#990000">(*)</b>Tipo Pagamento </label>';
		$html .= '<div class="input-group">';
		$html .= '<div class="input-group-addon"><i class="fa fa-sort-numeric-asc"></i></div>';

		$rowTipoPagamento = $this->modelClassModule->get_all('mod_tipopagamento');
		$rowTipoPagamento = json_decode(json_encode($rowTipoPagamento), true);
		//print'<pre>';print_r($rowTipoPagamento);

		//if ($this->isAdmin() == TRUE) {
			$html .= '<SELECT class="form-control" id="fk_tipopagamento" name="fk_tipopagamento">';
			foreach ($rowTipoPagamento as $k => $value) {
				$nome = $value['nome'];
				$predefinito = $value['predefinito'];
				if ($value['id'] ==  $rowWinForm['fk_tipopagamento']) {
					$html .=  "<option value='" . $value['id'] . "' SELECTED>" . $nome . "</option>";
				} else {
					if ($rowWinForm['fk_tipopagamento'] == "") {
						if ($predefinito == 'SI') {
							$html .= "<option value='" . $value['id'] . "' SELECTED>" . $nome . "</option>";
						} else {
							$html .= "<option value='" . $value['id'] . "'>" . $nome . "</option>";
						}
					} else {
						$html .= "<option value='" . $value['id'] . "'>" . $nome . "</option>";
					}
				}
			}
			$html .= '</SELECT>';
		/*
		} else {
			
			$html .= '<input type="text" readonly="readonly" class="form-control" id="tipo_pagamento" name="tipo_pagamento" value="' . $rowWinForm['fk_tipopagamento'] . '"> ';
			$html .= '<input type="hidden"  id="fk_tipopagamento" name="fk_tipopagamento" value="' . $rowWinForm['fk_tipopagamento'] . '"> ';
		}
		*/

		$html .= '</div></div></div>';



		$html .= '<div class="col-md-6"><div class="form-group">';
		$html .= '<label for="date"><b style="color:#990000">(*)</b>Causale Pagamento </label>';
		$html .= '<div class="input-group">';
		$html .= '<div class="input-group-addon"><i class="fa fa-sort-numeric-asc"></i></div>';

		$rowCausalePagamento = $this->modelClassModule->get_all('mod_causali_pagamento');
		$rowCausalePagamento = json_decode(json_encode($rowCausalePagamento), true);
		$tipo_corso = $this->modelClassModule->getTipoCorso($rowWinForm['fk_corso'] );
		//if ($this->isAdmin() == TRUE) {
			$html .= '<SELECT class="form-control" id="fk_causale_pagamento" name="fk_causale_pagamento">';
			$html .=  "<option value=''></option>";
			foreach ($rowCausalePagamento as $k => $value) {
 
				if(($value['nome'] == 'ACCONTO')){
					if ($value['id'] ==  $rowWinForm['fk_causale_pagamento']) {
						$html .= "<option value='" . $value['id'] . "' SELECTED >" . $value['nome'] . "</option>";
					} else {
						$html .=  "<option value='" . $value['id'] . "' >" . $value['nome'] . "</option>";
					}
				} else {
					if($tipo_corso == 'ABBONAMENTO'){
						if($value['nome'] == 'PAGAMENTO ALLIEVO ABBONAMENTO'){
							if ($value['id'] ==  $rowWinForm['fk_causale_pagamento']) {
								$html .= "<option value='" . $value['id'] . "' SELECTED >" . $value['nome'] . "</option>";
							} else {
								$html .=  "<option value='" . $value['id'] . "' >" . $value['nome'] . "</option>";
							}
						}		
					}
					
					if($tipo_corso == 'MENSILE'){
						if($value['nome'] == 'PAGAMENTO ALLIEVO MENSILE'){
							if ($value['id'] ==  $rowWinForm['fk_causale_pagamento']) {
								$html .= "<option value='" . $value['id'] . "' SELECTED >" . $value['nome'] . "</option>";
							} else {
								$html .=  "<option value='" . $value['id'] . "' >" . $value['nome'] . "</option>";
							}
						}		
					}						
				}

			}
			$html .= '</SELECT>';
		/*
		} else {
			$html .= '<input type="text" readonly="readonly" class="form-control" id="causale_pagamento" name="causale_pagamento" value="' . $rowWinForm['fk_causale_pagamento'] . '"> ';
			$html .= '<input type="hidden"  id="fk_causale_pagamento" name="fk_causale_pagamento" value="' . $rowWinForm['fk_causale_pagamento'] . '"> ';
		}
		*/
		$html .= '</div></div></div>';

		$html .= '<div class="col-md-6"><div class="form-group">';
		$html .= '<label for="date"><b style="color:#990000">(*)</b>Saldo </label>';
		$html .= '<div class="input-group">';
		$html .= '<div class="input-group-addon"><i class="fa fa-sort-numeric-asc"></i></div>';


		$html .= '<SELECT class="form-control" id="saldo" name="saldo">';
		$html .= '<option value="" ' . ($rowWinForm['saldo'] == '' ? 'SELECTED' : '') . '></option>';
		$html .= '<option value="SI" ' . ($rowWinForm['saldo'] == 'SI' ? 'SELECTED' : '') . '>SI</option>';
		$html .= '<option value="NO" ' . ($rowWinForm['saldo'] == 'NO' ? 'SELECTED' : '') . '>NO</option>';
		//$html .= '<option value="ACCONTO" ' . ($rowWinForm['saldo'] == 'ACCONTO' ? 'SELECTED' : '') . '>ACCONTO</option>';
		//$html .= '<option value="UNIFICATO" ' . ($rowWinForm['saldo'] == 'UNIFICATO' ? 'SELECTED' : '') . '>UNIFICATO</option>';
		$html .= '<option value="NON PAGHERA" ' . ($rowWinForm['saldo'] == 'NON PAGHERA' ? 'SELECTED' : '') . '>NON PAGHERA</option>';
		$html .= '</SELECT>';

		$html .= '</div></div></div>';


		$html .= '<div class="col-md-6"><div class="form-group">';
		$html .= '<label for="date">Note </label>';
		$html .= '<div class="input-group">';
		$html .= '<div class="input-group-addon"><i class="fa fa-text-height"></i></div>';
		$html .= '<textarea class="form-control" id="notepagamento" name="notepagamento">';
		$html .= $rowWinForm['notepagamento'];
		$html .= '</textarea>';
		$html .= '</div></div></div>';


		$html .= '<div class="col-md-12"><div class="form-group">';
		$html .= '<label for="date">Annulla Pagamento <b style="color:#990000;font-size:13px">(Selezionandolo si annullera\' la data di pagamento SE PRESENTE)</b></label>';
		$html .= '<div class="input-group">';
		$html .= '<input type="checkbox" name="annulla_pagamento" id="annulla_pagamento"  />';
		$html .= '</div></div></div>';		

		$html .= '
											</div>													
										</div>
									</div>
								</div>
							</div>
							</form>
						</section>
					</div>';


		return $html;
	}




	/**
	 * Funzione caricamento della finestra per la master details, tabella _mod_pagamenti_ricevuti
	 * @param mixed $action
	 * @param string $entryID
	 * @param string $entryIDMasterDetails
	 * @return string
	 **/
	public function winMasterDetail_tesseramenti($action, $entryID, $entryIDMasterDetails = NULL)
	{
		$idAnagrafica = "";
		$idEsercizio  = "";
		if ($entryIDMasterDetails == 'NULL') {
			$entryIDMasterDetails = '';
		}

		$arrayId = explode("_", $entryID);
		$fk_anagrafica  = $arrayId[0];
		$fk_esercizio  = $arrayId[1];		

		$rowWinForm = $this->modelClassModule->getPagamentiDatiTesseramentiWin($fk_anagrafica, $fk_esercizio);
		//print'<pre>'.$entryID;print_r($rowWinForm);

		$html = '<div>
				<section class="content">
					<div class="row">
						<div class="col-md-12">
							<div class="box box-primary">
								<div class="box-body">
								<div id="msg_err" ondblclick="this.style.display=\'none\';" style="display: none; border: 1px solid; background-color: rgb(255, 64, 0); border-radius: 5px; padding: 10px; color: white; font-weight: bold;">
								</div>									
									<form  name="frm_master_detail" id="frm_master_detail">
									<input type="hidden" id="table" name="table" value="_mod_pagamenti_tesseramenti">
									<input type="hidden" id="action" name="action" value="' . $action . '"/> 
									<input type="hidden" id="saveType" name="saveType" value="form"/> 	
 
									<input type="hidden" id="entryID"          name="entryID"  value="' . $entryID . '">
									<input type="hidden" id="fk_tesseramento"          name="fk_tesseramento"  value="' . $rowWinForm['id_tesseramento'] . '">
									<input type="hidden" id="recordID"          name="recordID"  value="' . $entryID . '">

									<input type="hidden" id="entryIDMasterDetails" name="entryIDMasterDetails" value="' . $entryIDMasterDetails . '" >															
										<div class="col-md-12">
											<div class="form-group">';

		$html .= '<div class="col-md-6"><div class="form-group">';
		$html .= '<label for="date"><b style="color:#990000">(*)</b>Data Pagamento </label>';
		$html .= '<div class="input-group">';
		$html .= '<div class="input-group-addon"><i class="fa fa-calendar"></i></div>';
		$html .= '<input type="text" class="form-control datemask" name="datapagamento" id="datapagamento" placeholder="Data Pagamento"';

		if ($rowWinForm['datapagamento'] == "") {
			$rowWinForm['datapagamento'] = date("Y-m-d");
		}
		if (!isset($rowWinForm['datapagamento'])) {
			$rowWinForm['datapagamento'] = date("Y-m-d");
		}
		$html .= 'autocomplete="off" style="background-color:#FFFFFF" value="' . $this->utilities->convertToDateIT($rowWinForm['datapagamento']) . '" />';
		$html .= '</div></div></div>';



		$html .= '<div class="col-md-6"><div class="form-group">';
		$html .= '<label for="date"><b style="color:#990000">(*)</b>Importo </label>';
		$html .= '<div class="input-group">';
		$html .= '<div class="input-group-addon"><i class="fa fa-sort-numeric-asc"></i></div>';
		$html .= '<input type="number" class="form-control" name="importo" id="importo" placeholder="Importo"';
		$html .= 'autocomplete="off" style="background-color:#FFFFFF" value="' . $rowWinForm['importo'] . '" />';
		$html .= '</div></div></div>';


		$html .= '<div class="col-md-6"><div class="form-group">';
		$html .= '<label for="date"><b style="color:#990000">(*)</b>Tipo Pagamento </label>';
		$html .= '<div class="input-group">';
		$html .= '<div class="input-group-addon"><i class="fa fa-sort-numeric-asc"></i></div>';

		$rowTipoPagamento = $this->modelClassModule->get_all('mod_tipopagamento');
		$rowTipoPagamento = json_decode(json_encode($rowTipoPagamento), true);

		if ($this->isAdmin() == TRUE) {
			$html .= '<SELECT class="form-control" id="fk_tipopagamento" name="fk_tipopagamento">';
			foreach ($rowTipoPagamento as $k => $value) {
				$nome = $value['nome'];
				$predefinito = $value['predefinito'];
				if ($value['id'] ==  $rowWinForm['fk_tipopagamento']) {
					$html .=  "<option value='" . $value['id'] . "' SELECTED>" . $nome . "</option>";
				} else {
					if ($rowWinForm['fk_tipopagamento'] == "") {
						if ($predefinito == 'SI') {
							$html .= "<option value='" . $value['id'] . "' SELECTED>" . $nome . "</option>";
						} else {
							$html .= "<option value='" . $value['id'] . "'>" . $nome . "</option>";
						}
					} else {
						$html .= "<option value='" . $value['id'] . "'>" . $nome . "</option>";
					}
				}
			}
			$html .= '</SELECT>';
		} else {
			$html .= '<input type="text" readonly="readonly" class="form-control" id="tipo_pagamento" name="tipo_pagamento" value="' . $rowWinForm['tipo_pagamento'] . '"> ';
			$html .= '<input type="hidden"  id="fk_tipopagamento" name="fk_tipopagamento" value="' . $rowWinForm['fk_tipopagamento'] . '"> ';
		}
		$html .= '</div></div></div>';


		$html .= '<div class="col-md-6"><div class="form-group">';
		$html .= '<label for="date"><b style="color:#990000">(*)</b>Saldo </label>';
		$html .= '<div class="input-group">';
		$html .= '<div class="input-group-addon"><i class="fa fa-sort-numeric-asc"></i></div>';


		$html .= '<SELECT class="form-control" id="saldo" name="saldo">';
		$html .= '<option value="SI" ' . ($rowWinForm['saldo'] == 'SI' ? 'SELECTED' : '') . '>SI</option>';
		$html .= '<option value="NO" ' . ($rowWinForm['saldo'] == 'NO' ? 'SELECTED' : '') . '>NO</option>';
		$html .= '<option value="NON PAGHERA" ' . ($rowWinForm['saldo'] == 'NON PAGHERA' ? 'SELECTED' : '') . '>NON PAGHERA</option>';
		$html .= '</SELECT>';

		$html .= '</div></div></div>';


		$html .= '<div class="col-md-6"><div class="form-group">';
		$html .= '<label for="date">Note </label>';
		$html .= '<div class="input-group">';
		$html .= '<div class="input-group-addon"><i class="fa fa-text-height"></i></div>';
		$html .= '<textarea class="form-control" id="notepagamento" name="notepagamento">';
		$html .= $rowWinForm['notepagamento'];
		$html .= '</textarea>';
		$html .= '</div></div></div>';

		$html .= '
											</div>													
										</div>
									</div>
								</div>
							</div>
							</form>
						</section>
					</div>';


		return $html;
	}


	/**
	 * Funzione caricamento della master details, tabella _mod_pagamenti_ricevuti
	 * @param mixed $id
	 * @param string $isAjax
	 * @return string
	 **/
	public function getMasterDetail_listaCorsiPerAllievo($idAnagrafica, $idEsercizio, $isAjax = 'FALSE')
	{
		$row =  $this->modelClassModule->getMasterDetail_listaCorsiPerAllievo($idAnagrafica, $idEsercizio);
		return $row;
	}



	/**
	 * Metodo principale index - REWRITE
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



	/**
	 * Carica i dati per un form - MODIFICA
	 * @param mixed $id
	 * @param mixed|null $afterSave
	 */
	public function updateAjax($requestId, $afterSave = null, String $winForm = "FALSE", $validation_failed = FALSE)
	{
		$arrayId = explode("_", $requestId);
		$id = (int)$arrayId[0];

		$this->pkIdValue = $id;

		$this->global['pageTitle'] = $this->mod_title . ' - Aggiornamento';

		if ($validation_failed == TRUE) {
			$request = $_REQUEST;
			$row = new stdClass();
			foreach ($request as $key => $val) {
				$expl = explode("/", $val);
				if (isset($expl[2])) {
					if (checkdate($expl[1], $expl[0], $expl[2]) == TRUE) {
						$row->{$key} = $this->utilities->convertToDateEN($val);
					} else {
						$row->{$key} = $val;
					}
				} else {
					$row->{$key} = $val;
				}

				if (strpos($key, '_hidden') !== FALSE) {
					$newKey = str_replace("_hidden", "", $key);
					$row->{$newKey} = $val;
				}
			}
		} else {
			$row = $this->modelClassModule->get_by_id($id);
		}



		$formFieldsReferenced =  $this->getFormFieldsReferenced();
		$formFieldsReferencedTableRef =  $this->getFormFieldsReferencedTableRef();
		$fieldsArrayGrid = $this->modelClassModule->getFieldsArrayGrid();

		//ESEGUO EVENTUALI "CUSTOM RULES"
		foreach ($this->custom_rules_updateAjax as $functionKey => $function) {
			$function($id);
		}


		if ($row) {
			$data = array();
			//ESEGUO EVENTUALI FUNZIONI AGGIUNTIVE PER CUSTOMIZZARE IL VETTORE $data
			foreach ($this->custom_form_data_functions as $keyData => $function) {
				$data[$keyData] = $function($id);
			}

			foreach ($this->formFields as $key => $property) {
				if (isset($fieldsArrayGrid[$property]['type'])) {
					$fieldType = $fieldsArrayGrid[$property]['type'];
					switch ($fieldType) {
						case FIELD_NUMERIC:
						case FIELD_STRING:
						case FIELD_FLOAT:
							$data[$property] = $row->$property;
							break;
					}
				} else {
					if (isset($row->$property)) {
						$data[$property] = $row->$property;
					} else {
						$data[$property] = NULL;
					}
				}

				if (in_array($property, $formFieldsReferenced)) {
					$arrayColumns = $this->getFormFieldsReferencedColumns($property);
					$where_condition = NULL;
					if (isset($arrayColumns['where_condition'])) {
						$where_condition =  $arrayColumns['where_condition'];
					}
					$data[$property . '_refval'] = $this->modelClassModule->getValuesByFk($formFieldsReferencedTableRef[$property], $arrayColumns['id'], $arrayColumns['nome'], $where_condition);
				}
			}

			//print'<pre>';print_r($this->masterDetailsLoadFuncList);die();
			foreach ($this->masterDetailsLoadFuncList as $key => $masterDetailsLoadFunc) {
				$data['master_details_list'][] = array("title" => $masterDetailsLoadFunc['title'], "id" => $masterDetailsLoadFunc['id'], "function" => $masterDetailsLoadFunc['function']);
			}
			$data['id'] = set_value($this->pkIdName, $id);

			$data['button'] = 'Update';
			$data['button_id'] = 'bt_update';
			$data['frm_module_name'] = 'frm_' . $this->mod_name;
			$data['action'] = site_url($this->mod_name . '/update_action');
			$data['type_action'] = 'update';
			$data['afterSave'] = $afterSave;
			$data['allegati'] = $this->loadAllegati($this->mod_name, $id);
			$data['extAdmitted'] = $this->loadExtAdmitted();
			$data['winForm'] = $winForm;



			$this->load->view($this->mod_name . '/' . $this->mod_name . '_form_ajax', $data);
		} else {
			$this->session->set_flashdata('error', $this->MsgDBConverted['update']["record_not_found"]);
			redirect(site_url($this->mod_name));
		}
	}


	/**
	 * 
	 * Salvo i dati della master details  - OVERWRITE
	 */
	public function saveMasterDetails()
	{
		$success = 'TRUE';
		$masterDetailsHtml = "";
		$action = $_REQUEST['action'];
		$entryID = $_REQUEST['entryID'];
		$entryIDMasterDetails = $_REQUEST['entryIDMasterDetails'];
		$data = array();

		foreach ($_FILES as $key => $file) {
			if ($file['tmp_name'] != "") {
				$data[$key] = @file_get_contents($file['tmp_name']);
				//PER QUESTA VERSIONE DO PER SCONTATO CHE ESISTE IL CAMPO "nome_documento"
				$data['nome_documento'] = $file['name'];
			}
		}
		//print'<pre>';print_r($_REQUEST);
		switch($_REQUEST['table']){
			case '_mod_pagamenti_ricevuti':
				$meseAnnoArray = explode("-", $_REQUEST['mese_anno']);
				$data['mese'] = $meseAnnoArray[0];
				$data['anno'] = $meseAnnoArray[1];
				$data['fk_anagrafica'] = $_REQUEST['fk_anagrafica'];
				$data['fk_corso'] = $_REQUEST['fk_corso'];
				$data['fk_causale_pagamento'] = $_REQUEST['fk_causale_pagamento'];
				
				if(!isset($_REQUEST['annulla_pagamento'])){
					$data['datapagamento'] = $this->utilities->convertToDateEN($_REQUEST['datapagamento']);
				} else {
					$data['datapagamento'] = NULL;
				}
				
			break;

			case '_mod_pagamenti_ricevuti_unificati':
				$data['fk_mod_pagamenti_ricevuti'] = $_REQUEST['fk_mod_pagamenti_ricevuti'];
				$data['fk_causale_pagamento'] = $_REQUEST['fk_causale_pagamento'];
				$data['datapagamento'] = $this->utilities->convertToDateEN($_REQUEST['datapagamento']);
			break;	
			
			case '_mod_pagamenti_tesseramenti':
				$data['fk_tesseramento'] = $_REQUEST['fk_tesseramento'];
				$data['datapagamento'] = $this->utilities->convertToDateEN($_REQUEST['datapagamento']);
			break;
		}
		if(isset($_REQUEST['id'])){
			$data['id'] = $_REQUEST['id'];
		}
		
		$data['saldo'] = $_REQUEST['saldo'];
		$data['fk_tipopagamento'] = $_REQUEST['fk_tipopagamento'];
		$data['importo'] = $_REQUEST['importo'];
		$data['notepagamento'] = $_REQUEST['notepagamento'];
		$data['table'] = $_REQUEST['table'];
		
		/*
		print'<pre>';print_r($_REQUEST);
		print'<pre>';print_r($data);
		die();
		*/

		if ($action == 'insert') {
			//print'<pre>INSERT:';print_r($data);
			$ret =  $this->modelClassModule->insert_master_details($data);
			if (($ret['code'] == '0') || ($ret['code'] == 0)) {
				$msg = $this->MsgDBConverted["insert"]["success"];
				$dataLog = array(
					'programma' => $this->mod_title,
					'utente' => $_SESSION['name'],
					'azione' => "INSERIMENTO MASTER DETAILS " . $action,
					'data' => date('Y-m-d H:i'),
				);
				$this->Common_model->insertLog($dataLog);
			} else {
				$success = "FALSE";
				if (isset($this->MsgDBConverted["insert"]["error"][$ret['code']])) {
					$msg = "Errore :" . $ret['code'] . " - " . $this->MsgDBConverted["insert"]["error"][$ret['code']];
				} else {
					$msg = "Errore :" . $ret['code'] . " - " . $this->mysql_error_codes[$ret['code']]['message_template'];
				}
			}
		} else {
			//print'<pre>UPDATE:'.$entryIDMasterDetails."\n";print_r($data);die();
			$ret = $this->modelClassModule->update_master_details($entryIDMasterDetails, $data);
			if (($ret['code'] == '0') || ($ret['code'] == 0)) {
				$msg = $this->MsgDBConverted["update"]["success"];
			} else {
				$success = "FALSE";
				if (isset($this->MsgDBConverted["update"]["error"][$ret['code']])) {
					$msg = "Errore :" . $ret['code'] . " - " . $this->MsgDBConverted["update"]["error"][$ret['code']];
				} else {
					$msg = "Errore :" . $ret['code'] . " - " . $this->mysql_error_codes[$ret['code']]['message_template'];
				}
			}
		}


		//RITORNO LE MASTER DETAILS PER IL FORM / RECORD
		$masterDetailsHtml = $this->getAjaxMasterDetails($entryID);

		echo json_encode(array("success" => $success, "msg" => $msg, "html" => $masterDetailsHtml));
	}


 

	/**
	 * 
	 * Ritorna i tab master details via ajax dopo un'operazione CRUD  - OVERWRITE
	 * @param mixed $id
	 * @return string
	 */
	protected function getAjaxMasterDetails($id)
	{
		$arr = explode("_", $id);
		$_REQUEST['recordID'] = $id;
		$idAnagrafica = $arr[0];
		$idEsercizio = $arr[1];
		$rows = $this->getMasterDetail_listaCorsiPerAllievo($idAnagrafica, $idEsercizio);

		$count = 0;
		foreach ($rows as $k => $v) {
			$idCorso = $v['id'];
			$master_details_list[] = $this->masterDetailsLoadFuncList[$idCorso] = array("title" => $v['nome'], "id" => $idCorso,  "function" => $this->getPagamentiPerCorso($idAnagrafica, $idCorso));
			$count++;
		}
		$count = $count + 1;
		$master_details_list[] =$this->masterDetailsLoadFuncList["tbtess"] = array("title" => "Pagamento Tesseramenti", "id" => "tbtess",  "function" => $this->getPagamentiTesseramenti($idAnagrafica, $idEsercizio));

		$countTab = 0;
		$masterDetailsHtml = '<ul class="nav nav-tabs" id="myTab" role="tablist">	';
		foreach ($master_details_list as  $key => $master_details) {
			if ($countTab == 0) {
				$masterDetailsHtml .= '<li class="nav-item active">
					<a class="nav-link active" id="lnk-' . $master_details['id'] . '" data-toggle="tab" href="#' . $master_details['id'] . '" role="tab" aria-controls="' . $master_details['id'] . '" aria-selected="true" aria-expanded="true">' . $master_details['title'] . '</a>
				</li>';
			} else {
				$masterDetailsHtml .= '<li class="nav-item ">
					<a class="nav-link" id="lnk-' . $master_details['id'] . '" data-toggle="tab" href="#' . $master_details['id'] . '" role="tab" aria-controls="' . $master_details['id'] . '"   >' . $master_details['title'] . '</a>
				</li>';
			}
			$countTab++;
		}
		$masterDetailsHtml .= '</ul>';

		$masterDetailsHtml .= '<div class="tab-content">';
		$countTab = 0;
		foreach ($master_details_list as  $key => $master_details) {
			$active = "active";
			if ($countTab > 0) {
				$active = "";
			}
			$masterDetailsHtml .= '<div class="tab-pane ' . $active . '" id="' . $master_details['id'] . '" role="tabpanel" aria-labelledby="' . $master_details['id'] . '-tab">';
			$masterDetailsHtml .=  $master_details['function'];
			$masterDetailsHtml .= '</div>';
			$countTab++;
		}

		$masterDetailsHtml .= '</div>';

		return $masterDetailsHtml;
		
		//print'<pre>';print_r($master_details_list);

	}	


	


	public function getEsercizioCorrente()
	{
		return $this->modelClassModule->getEsercizioCorrente();
	}


	public function populateAffiliazioni()
	{
		echo json_encode($this->modelClassModule->populateAffiliazioni($_REQUEST['filter_master_value']));
	}


	/**
	 * Stampa di un modulo
	 * Stampa a video, richiamando il metodo stampa_out e salva sul server
	 * @param mixed $id
	 */
	public function stampa($id)
	{

		if (!file_exists(FCPATH . "/stampe/" . $this->mod_name)) {
			$oldmask = umask(0);
			mkdir(FCPATH . "/stampe/" . $this->mod_name, 0777);
			umask($oldmask);
		}
		$out = $this->stampa_out($id);
		file_put_contents(FCPATH . "/stampe/" . $this->mod_name . '/' . $id . '.pdf', $out);
	}


	public function stampa_tess($id)
	{

		if (!file_exists(FCPATH . "/stampe/" . $this->mod_name)) {
			$oldmask = umask(0);
			mkdir(FCPATH . "/stampe/" . $this->mod_name, 0777);
			umask($oldmask);
		}
		$out = $this->stampa_tess_out($id);
		file_put_contents(FCPATH . "/stampe/" . $this->mod_name . '/' . $id . '.pdf', $out);
	}



	public function stampa_unif($id)
	{

		if (!file_exists(FCPATH . "/stampe/" . $this->mod_name)) {
			$oldmask = umask(0);
			mkdir(FCPATH . "/stampe/" . $this->mod_name, 0777);
			umask($oldmask);
		}
		$out = $this->stampa_unif_out($id);
		file_put_contents(FCPATH . "/stampe/" . $this->mod_name . '/' . $id . '.pdf', $out);
	}



	/**
	 * Stampa ricevuta
	 * @param mixed $id
	 * @return object
	 * 
	 */
	public function stampa_out($id)
	{

		$this->global['pageTitle'] = $this->mod_title . ' - Stampa';

		$dompdf = new Dompdf();

		$settings = $this->user_model->loadSettings();
		$company_logo = $settings[0]->company_logo;
		$company_name = $settings[0]->company_name;
		$company_code = $settings[0]->company_code;
		$company_email = $settings[0]->company_email;
		$company_phone = $settings[0]->company_phone;
		$company_address = $settings[0]->company_address;
		$manager_signature = $settings[0]->manager_signature;
		//$row = $this->modelClassModule->getDettagliRicevuta($id);

		$row = $this->modelClassModule->getRicevute(NULL, $id);
		/*
		print'<pre>';print_r($row);
		print'<pre>';print_r($settings);
		die();
		*/
		if ($row) {
			$data = array(
				'company_name' => set_value('company_name', $company_name),
				'company_code' => set_value('company_code', $company_code),
				'company_email' => set_value('company_email', $company_email),
				'company_phone' => set_value('company_phone', $company_phone),
				'company_address' => set_value('company_address', $company_address),
				'manager_signature' => set_value('manager_signature', $manager_signature),
				'company_logo' => set_value('company_logo', $company_logo),
			);
			foreach ($row as $k => $v) {
				$data['value'][$k] = $v;
			}
			$view = $this->load->view($this->mod_name . '/mod_anagrafica_stampa_ricevute', $data, true);
			$dompdf->loadHtml($view);
			$dompdf->set_option("isPhpEnabled", true);

			$dompdf->setPaper('A4');

			$dompdf->render();

			$x = 520;
			$y = 820;
			//$text = "Pagina {PAGE_NUM} di {PAGE_COUNT}";
			$text = "";
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
		} else {
			$this->session->set_flashdata('error', 'Record non Trovato');
			redirect(site_url($this->mod_name));
		}
	}




	/**
	 * Stampa ricevuta
	 * @param mixed $id
	 * @return object
	 * 
	 */
	public function stampa_tess_out($id)
	{

		$this->global['pageTitle'] = $this->mod_title . ' - Stampa';

		$dompdf = new Dompdf();

		$settings = $this->user_model->loadSettings();
		$company_logo = $settings[0]->company_logo;
		$company_name = $settings[0]->company_name;
		$company_code = $settings[0]->company_code;
		$company_email = $settings[0]->company_email;
		$company_phone = $settings[0]->company_phone;
		$company_address = $settings[0]->company_address;
		$manager_signature = $settings[0]->manager_signature;
		//$row = $this->modelClassModule->getDettagliRicevuta($id);

		$row = $this->modelClassModule->getTesseramenti($id);
		/*
		print'<pre>';print_r($row);
		print'<pre>';print_r($settings);
		die();
		*/
		if ($row) {
			$data = array(
				'company_name' => set_value('company_name', $company_name),
				'company_code' => set_value('company_code', $company_code),
				'company_email' => set_value('company_email', $company_email),
				'company_phone' => set_value('company_phone', $company_phone),
				'company_address' => set_value('company_address', $company_address),
				'manager_signature' => set_value('manager_signature', $manager_signature),
				'company_logo' => set_value('company_logo', $company_logo),
			);
			foreach ($row as $k => $v) {
				$data['value'][$k] = $v;
			}
			$view = $this->load->view($this->mod_name . '/mod_anagrafica_stampa_tess', $data, true);
			$dompdf->loadHtml($view);
			$dompdf->set_option("isPhpEnabled", true);

			$dompdf->setPaper('A4');

			$dompdf->render();

			$x = 520;
			$y = 820;
			//$text = "Pagina {PAGE_NUM} di {PAGE_COUNT}";
			$text = "";
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
		} else {
			$this->session->set_flashdata('error', 'Record non Trovato');
			redirect(site_url($this->mod_name));
		}
	}



	/**
	 * Stampa ricevuta
	 * @param mixed $id
	 * @return object
	 * 
	 */
	public function stampa_unif_out($id)
	{

		$this->global['pageTitle'] = $this->mod_title . ' - Stampa';

		$dompdf = new Dompdf();

		$settings = $this->user_model->loadSettings();
		$company_logo = $settings[0]->company_logo;
		$company_name = $settings[0]->company_name;
		$company_code = $settings[0]->company_code;
		$company_email = $settings[0]->company_email;
		$company_phone = $settings[0]->company_phone;
		$company_address = $settings[0]->company_address;
		$manager_signature = $settings[0]->manager_signature;
		//$row = $this->modelClassModule->getDettagliRicevuta($id);

		$row = $this->modelClassModule->getPagamentiUnificatiStampa($id);
		/*
		print'<pre>';print_r($row);
		print'<pre>';print_r($settings);
		die();
		*/
		if ($row) {
			$data = array(
				'company_name' => set_value('company_name', $company_name),
				'company_code' => set_value('company_code', $company_code),
				'company_email' => set_value('company_email', $company_email),
				'company_phone' => set_value('company_phone', $company_phone),
				'company_address' => set_value('company_address', $company_address),
				'manager_signature' => set_value('manager_signature', $manager_signature),
				'company_logo' => set_value('company_logo', $company_logo),
			);
			foreach ($row as $k => $v) {
				$data['value'][$k] = $v;
			}
			$view = $this->load->view($this->mod_name . '/mod_anagrafica_stampa_unif', $data, true);
			$dompdf->loadHtml($view);
			$dompdf->set_option("isPhpEnabled", true);

			$dompdf->setPaper('A4');

			$dompdf->render();

			$x = 520;
			$y = 820;
			//$text = "Pagina {PAGE_NUM} di {PAGE_COUNT}";
			$text = "";
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
		} else {
			$this->session->set_flashdata('error', 'Record non Trovato');
			redirect(site_url($this->mod_name));
		}
	}

	public function _rules()
	{
		$this->form_validation->set_rules('id', '', 'trim|numeric|max_length[10]|required');
		$this->form_validation->set_rules('anagrafica_id', '', 'trim|numeric|max_length[10]|required');
		$this->form_validation->set_rules('anagrafica', '', 'trim|max_length[120]');
		$this->form_validation->set_rules('affiliazione_id', 'nome affiliazione', 'trim|max_length[50]|required');
		$this->form_validation->set_rules('affiliazione', 'nome affiliazione', 'trim|max_length[50]|required');
		$this->form_validation->set_rules('esercizio_id', '', 'trim|numeric|max_length[10]|required');
		$this->form_validation->set_rules('esercizio', 'nome', 'trim|max_length[100]|required');
		$this->form_validation->set_rules('saldo', 'saldo', 'trim');
		$this->form_validation->set_rules('datapagamento', 'data pagamento', 'trim');
		$this->form_validation->set_rules('mese', 'mese', 'trim');
		$this->form_validation->set_rules('anno', 'anno', 'trim');
		$this->form_validation->set_rules('importo', 'pagato', 'trim|numeric|max_length[10]');

		$this->form_validation->set_rules('', '', 'trim');
		$this->form_validation->set_error_delimiters('<span class="text-danger">', '</span>');
	}
}
