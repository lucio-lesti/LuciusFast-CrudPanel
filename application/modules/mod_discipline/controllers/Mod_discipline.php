<?php
if (!defined('BASEPATH')){
	exit('No direct script access allowed');
}
require APPPATH . '/libraries/BaseController.php';
use Dompdf\Dompdf;

class Mod_discipline extends BaseController
{

	public function __construct()
	{
		parent::__construct();
		$this->load->model('Mod_discipline_model');
		$this->mod_name = 'mod_discipline';
		$this->mod_type = 'crud';
		$this->mod_title = 'Discipline';
		$this->modelClassModule =  $this->Mod_discipline_model;
		$this->pkIdName = 'id';
		$this->viewName_ListAjax = 'mod_discipline_list_ajax';
		$this->viewName_FormROAjax = 'mod_discipline_read_ajax';
		$this->viewName_FormAjax = 'mod_discipline_form_ajax';

		/*
		//ABILITARE PER CUSTOMIZZAZIONE PER MODULO ERRORI SQL 
		//IN CORSO MIGLIORIA PER GESTIRE I MESSAGGI TRAMITE TABELLA DI TRASCODIFICA
		//SPOSTARE LOGICA NEL MODEL					
		$this->MsgDBConverted['insert']['error']['1062'] = "Esiste gia questo elemento per il modulo Discipline";
		$this->MsgDBConverted['insert']['error']['1452'] = "Esiste gia questo elemento per il modulo Discipline";
		$this->MsgDBConverted['update']['error']['1062'] = "Esiste gia questo elemento per il modulo Discipline";
		$this->MsgDBConverted['update']['error']['1452'] = "Esiste gia questo elemento per il modulo Discipline";
		$this->MsgDBConverted['insert_massive']['error']['1062'] = "Esiste gia questo elemento per il modulo Discipline";
		$this->MsgDBConverted['insert_massive']['error']['1452'] = "Esiste gia questo elemento per il modulo Discipline";
		$this->MsgDBConverted['update_massive']['error']['1062'] = "Esiste gia questo elemento per il modulo Discipline";
		$this->MsgDBConverted['update_massive']['error']['1452'] = "Esiste gia questo elemento per il modulo Discipline";
		$this->MsgDBConverted['delete']['error']['1217'] = "Impossibile eliminare questo elemento del modulo Discipline. E' usato nei seguenti moduli:";
		$this->MsgDBConverted['delete_massive']['error']['1217'] = "Impossibile eliminare alcuni elementi del modulo Discipline. Sono usati nei seguenti moduli:";
		*/


		$this->setFormFields('codice_disciplina');
		//NOTE:NELLA FUNZIONE 'setFormFields' INDICARE NEL VETTORE CHE SI COLLEGA ALLA TABELLA REFERENZIATA
		//ALLA CHIAVE 'NOME', IL NOMINATIVO DEL CAMPO COLLEGATO		
		//PER L'ARRAY DI REFERENZIAMENTO, USARE IL CONCAT PER CONCATENARE PIU CAMPI NEL CAMPO 'NOME'
		//ES.CONCAT(cognome," ",nome)
		$this->setFormFields('fk_sport','mod_sport',array("id" => 'id', "nome" => 'sport'));
		$this->setFormFields('nome');
		$this->setFormFields('id');


		/**  RICHIAMO LE FUNZIONI PER IL CARICAMENTO DELLE MASTER DETAILS**/
		$this->addMasterDetailsLoadFunc('getMasterDetail_mod_insegnanti_discipline','Insegnanti associati','getMasterDetail_mod_insegnanti_discipline');

	}


	/**
	* Funzione caricamento della master details, tabella _mod_enti_discipline
	* @param mixed $id
	* @param string $isAjax
	* @return string
	**/
	public function getMasterDetail_mod_enti_discipline($id, $isAjax = 'FALSE'){
		$html = '';
		$winFormType ="form";//VALORI ACCETTATI: {'multi','form'}
	
		$row =  $this->modelClassModule->getMasterDetail_mod_enti_discipline($id, $isAjax);
		$data['id'] = $id;
		$data['isAjax'] = $isAjax;
		$data['row'] = $row;
		$data['winFormType'] = $winFormType;
		$html = $this->load->view('partials/gridtab/grd__mod_enti_discipline.php', $data, TRUE);
		return $html;
	}


	/**
	* Funzione caricamento della master details, tabella _mod_insegnanti_discipline
	* @param mixed $id
	* @param string $isAjax
	* @return string
	**/
	public function getMasterDetail_mod_insegnanti_discipline($id, $isAjax = 'FALSE'){
		$html = '';
		$winFormType ="multi";//VALORI ACCETTATI: {'multi','form'}
	
		$row =  $this->modelClassModule->getMasterDetail_mod_insegnanti_discipline($id, $isAjax);
		$data['id'] = $id;
		$data['isAjax'] = $isAjax;
		$data['row'] = $row;
		$data['winFormType'] = $winFormType;
		$html = $this->load->view('partials/gridtab/grd__mod_insegnanti_discipline.php', $data, TRUE);
		return $html;
	}


	/**
	* Funzione caricamento della finestra per la master details, tabella _mod_enti_discipline
	* @param mixed $action
	* @param string $entryID
	* @param string $entryIDMasterDetails
	* @return string
	**/
	public function winMasterDetail_mod_enti_discipline($action, $entryID, $entryIDMasterDetails = NULL){
		if($entryIDMasterDetails == 'NULL'){
			$entryIDMasterDetails = '';
		}
		$data['action'] = $action;
		$data['rowWinForm'] =  $this->modelClassModule->get_from_master_details_by_id($entryIDMasterDetails, '_mod_enti_discipline', 'id');
		$data['entryID'] = $entryID;
		$data['entryIDMasterDetails'] = $entryIDMasterDetails;
		$data['fk_disciplina_refval'] = $this->modelClassModule->getValuesByFk('mod_discipline',NULL, NULL);
		$data['fk_ente_refval'] = $this->modelClassModule->getValuesByFk('mod_enti',NULL, NULL);
		$html = $this->load->view('partials/winform/win__mod_enti_discipline.php', $data, TRUE);
		return $html;
	}


	/**
	* Funzione caricamento della finestra per la master details, tabella _mod_insegnanti_discipline
	* @param mixed $action
	* @param string $entryID
	* @param string $entryIDMasterDetails
	* @return string
	**/
	public function winMasterDetail_mod_insegnanti_discipline($action, $entryID, $entryIDMasterDetails = NULL){
		if($entryIDMasterDetails == 'NULL'){
			$entryIDMasterDetails = '';
		}

		/*
		$data['action'] = $action;
		$data['rowWinForm'] =  $this->modelClassModule->get_from_master_details_by_id($entryIDMasterDetails, '_mod_insegnanti_discipline', 'id');
		$data['entryID'] = $entryID;
		$data['entryIDMasterDetails'] = $entryIDMasterDetails;

		$where_condition = "";
		$data['fk_anagrafica_refval'] = $this->modelClassModule->getValuesByFk('mod_anagrafica',NULL, NULL, $where_condition);
		$data['fk_disciplina_refval'] = $this->modelClassModule->getValuesByFk('mod_discipline',NULL, NULL);
		$html = $this->load->view('partials/winform/win__mod_insegnanti_discipline.php', $data, TRUE);
 		*/

		$row = $this->modelClassModule->getAllInsegnantiNonAssociati($entryID);
		$html = '<div>
				<section class="content">
					<div class="row">
						<div class="col-md-12">
							<div class="box box-primary">
								<div class="box-body">
								<div id="msg_err" ondblclick="this.style.display=\'none\';" style="display: none; border: 1px solid; background-color: rgb(255, 64, 0); border-radius: 5px; padding: 10px; color: white; font-weight: bold;">
								</div>									
									<form  name="frm_master_detail" id="frm_master_detail">
									<input type="hidden" id="table" name="table" value="_mod_insegnanti_discipline">
									<input type="hidden" id="action" name="action" value="'.$action.'"/> 
									<input type="hidden" id="saveType" name="saveType" value="multi"/> 	
									<input type="hidden" id="entryID"          name="entryID"  value="'.$entryID.'">
									<input type="hidden" id="fk_disciplina"          name="fk_disciplina"  value="'.$entryID.'">
									<input type="hidden" id="entryIDMasterDetails" 		name="entryIDMasterDetails" value="'.$entryIDMasterDetails.'" >															
										<div class="col-md-12">
											<div class="form-group">';

		$html .= "<table class='TFtable' id='table_insert_master_detail' style='font-size:12px'>";
		$html .= "<tr class=\"header\">";
		$html .= "<th  style='width:5%'><input type='checkbox' onchange=\"selezionaDeselezionaTutti('entry_to_delete_master','fk_anagrafica[]','btDeleteMass')\"
			name='entry_to_delete_master' id='entry_to_delete_master'></th>";
		
		$html .= "<th  style='width:20%'><b style='font-size:14px'><b>Anagrafica</b></b>
		</th>";
		$html .= "
			</tr>";	
		foreach($row as $key => $value){
			$html .= "<tr>";
			$html .= "<td><input type='checkbox' class='fk_anagrafica' name='fk_anagrafica[]' id='fk_anagrafica[]' value=".$value['id']."></td>";
			$html .= "<td>".$value['nome']." ". $value['cognome']." - ".$value['codfiscale']."</td>";											
			$html .= "</tr>";	
		}
		$html .= "</table>";	

		$html .= '</div>';
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
	* Funzione caricamento della finestra per la master details,in modalita di inserimento multiplo, tabella _mod_enti_discipline
	* @param mixed $action
	* @param string $entryID
	* @return string
	**/
	public function winMasterDetailMulti_mod_enti_discipline($action, $entryID){
		$row = $this->modelClassModule->get_all('_mod_enti_discipline', $entryID);
		$data['action'] = $action;
		$data['entryID'] = $entryID;
		$data['row'] = $row;
		$html = '';
		$html = $this->load->view('partials/winform/winm__mod_enti_discipline.php', $data, TRUE);

		return $html;
	}


	/**
	* Funzione caricamento della finestra per la master details,in modalita di inserimento multiplo, tabella _mod_insegnanti_discipline
	* @param mixed $action
	* @param string $entryID
	* @return string
	**/
	public function winMasterDetailMulti_mod_insegnanti_discipline($action, $entryID){
		$row = $this->modelClassModule->get_all('_mod_insegnanti_discipline', $entryID);
		$data['action'] = $action;
		$data['entryID'] = $entryID;
		$data['row'] = $row;
		$html = '';
		$html = $this->load->view('partials/winform/winm__mod_insegnanti_discipline.php', $data, TRUE);

		return $html;
	}


	public function _rules()
	{
		$this->form_validation->set_rules('codice_disciplina', 'codice disciplina', 'trim|max_length[30]|required');
		$this->form_validation->set_rules('fk_sport', 'sport', 'trim|numeric|max_length[10]|required');
		$this->form_validation->set_rules('nome', 'nome disciplina', 'trim|max_length[255]|required');

		$this->form_validation->set_rules('id', 'id', 'trim');
		$this->form_validation->set_error_delimiters('<span class="text-danger">', '</span>');

	}

}