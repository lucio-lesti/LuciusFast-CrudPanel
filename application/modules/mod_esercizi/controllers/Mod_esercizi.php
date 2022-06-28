<?php
if (!defined('BASEPATH')){
	exit('No direct script access allowed');
}
require APPPATH . '/libraries/BaseController.php';
use Dompdf\Dompdf;

class Mod_esercizi extends BaseController
{

	public function __construct()
	{
		parent::__construct();
		$this->load->model('Mod_esercizi_model');
		$this->mod_name = 'mod_esercizi';
		$this->mod_title = 'Esercizi';
		$this->modelClassModule =  $this->Mod_esercizi_model;
		$this->pkIdName = 'id';
		$this->viewName_ListAjax = 'mod_esercizi_list_ajax';
		$this->viewName_FormROAjax = 'mod_esercizi_read_ajax';
		$this->viewName_FormAjax = 'mod_esercizi_form_ajax';


		/*
		//ABILITARE PER CUSTOMIZZAZIONE PER MODULO ERRORI SQL 
		//IN CORSO MIGLIORIA PER GESTIRE I MESSAGGI TRAMITE TABELLA DI TRASCODIFICA
		//SPOSTARE LOGICA NEL MODEL					
		$this->MsgDBConverted['insert']['error']['1062'] = "Esiste gia questo elemento per il modulo Esercizi";
		$this->MsgDBConverted['insert']['error']['1452'] = "Esiste gia questo elemento per il modulo Esercizi";
		$this->MsgDBConverted['update']['error']['1062'] = "Esiste gia questo elemento per il modulo Esercizi";
		$this->MsgDBConverted['update']['error']['1452'] = "Esiste gia questo elemento per il modulo Esercizi";
		$this->MsgDBConverted['insert_massive']['error']['1062'] = "Esiste gia questo elemento per il modulo Esercizi";
		$this->MsgDBConverted['insert_massive']['error']['1452'] = "Esiste gia questo elemento per il modulo Esercizi";
		$this->MsgDBConverted['update_massive']['error']['1062'] = "Esiste gia questo elemento per il modulo Esercizi";
		$this->MsgDBConverted['update_massive']['error']['1452'] = "Esiste gia questo elemento per il modulo Esercizi";
		$this->MsgDBConverted['delete']['error']['1217'] = "Impossibile eliminare questo elemento del modulo Esercizi. E' usato nei seguenti moduli:";
		$this->MsgDBConverted['delete_massive']['error']['1217'] = "Impossibile eliminare alcuni elementi del modulo Esercizi. Sono usati nei seguenti moduli:";
		*/


		//NOTE:NELLA FUNZIONE 'setFormFields' INDICARE NEL VETTORE CHE SI COLLEGA ALLA TABELLA REFERENZIATA
		//ALLA CHIAVE 'NOME', IL NOMINATIVO DEL CAMPO COLLEGATO
		$this->setFormFields('data_a');
		$this->setFormFields('data_da');
		$this->setFormFields('nome');
		$this->setFormFields('id');

		//RUCHIAMO FUNZIONE PER IL CARICAMENTO MASTER DETAILS
		$this->addMasterDetailsLoadFunc('getMasterDetail_mod_anagrafica_tessere_interne','Anagrafati Tessere Interne','getMasterDetail_mod_anagrafica_tessere_interne');

	}


	/**
	* Funzione caricamento della master details, tabella _mod_anagrafica_tessere_interne
	* @param mixed $id
	* @param string $isAjax
	* @return string
	**/
	public function getMasterDetail_mod_anagrafica_tessere_interne($id, $isAjax = 'FALSE'){
		$row =  $this->modelClassModule->getMasterDetail_mod_anagrafica_tessere_interne($id, $isAjax);
		$html = '';
		$winFormType ="form";//VALORI ACCETTATI: {'multi','form'}
	
		if($isAjax == 'FALSE'){
			if($winFormType == "form"){
				$html .= '
							<br><a class="btn btn-primary" style="cursor:pointer" onclick="winFormMasterDetails(\'mod_esercizi\',\'winMasterDetail_mod_anagrafica_tessere_interne\',\'insert\','.$id.',\'NULL\',\'NUOVO Anagrafati Tessere Interne\', arrayValidationFields,\'winMasterDetail_mod_anagrafica_tessere_interne\',\'form\')">[ Aggiungi un elemento]</a><br>
							<br><br>';
			} else {
				$html .= '
						<br><a class="btn btn-primary" style="cursor:pointer" onclick="winFormMasterDetails(\'mod_esercizi\',\'winMasterDetailMulti_mod_anagrafica_tessere_interne\',\'insert\','.$id.',\'NULL\',\'NUOVO Anagrafati Tessere Interne\', arrayValidationFields,\'winMasterDetailMulti_mod_anagrafica_tessere_interne\',\'multi\')">[ Aggiungi un elemento]</a><br>
						<br><br>';
			}
		}
		$html .= "<table class='TFtable' id='tbl_mod_anagrafica_tessere_interne' style='font-size:12px'>
					<tr>
						<thead>
						<th class='sorting_disabled' rowspan='1' colspan='1' aria-label='' style='width:10%;'>
							<input type='checkbox' id='check_master_mod_anagrafica_tessere_interne' name='check_master_mod_anagrafica_tessere_interne' 
							onchange=\"selezionaDeselezionaTutti('check_master_mod_anagrafica_tessere_interne','check_id_mod_anagrafica_tessere_interne','btDeleteMass_mod_anagrafica_tessere_interne')\">
						</th>";
		$html.='<th>Alunno</th>';
		$html.='<th>Esercizio</th>';
		$html.='<th>Tessera Interna</th>';
		if($winFormType == "form"){
			$html.='<th>Modifica</th>';
		}
		$html.='<th>Elimina</th>';
		$html.='</tr>';
		$html.='<tbody>';
		foreach($row as $key => $value){
			$html.="<tr>";
			$html.="<td><input type='checkbox' id='check_id_mod_anagrafica_tessere_interne' name='check_id_mod_anagrafica_tessere_interne' value='".$value['id']."' onchange=\"verificaNrCheckBoxSelezionati('check_id_mod_anagrafica_tessere_interne','btDeleteMass_mod_anagrafica_tessere_interne')\"></td>";
			$html.="<td><input type='hidden' id='id[]' name='id[]' value='".$value['id']."'>".$value['mod_anagrafica_nome']."</td>";
			$html.="<td><input type='hidden' id='id[]' name='id[]' value='".$value['id']."'>".$value['mod_esercizi_nome']."</td>";
			$html.="<td><input type='hidden' id='id[]' name='id[]' value='".$value['id']."'>".$value['tessera_interna']."</td>";
			if($winFormType == "form"){
				$html.="<td><a style='cursor:pointer' class='btn btn-sm btn-info' onclick ='winFormMasterDetails(\"mod_esercizi\",\"winMasterDetail_mod_anagrafica_tessere_interne\",\"edit\", $id,".$value['id'].",\"MODIFICA Anagrafati Tessere Interne\",arrayValidationFields)' title='Modifica Anagrafati Tessere Interne'><i class='fa fa-edit'></a></td>";
			}
			$html.="<td><a style='cursor:pointer' class='btn btn-sm btn-danger deleteUser' onclick ='deleteMasterDetails(".$value['id'].", ".$id.", \"mod_esercizi\",\"_mod_anagrafica_tessere_interne\")' title='Elimina'><i class='fa fa-trash'></a></td>";
			$html.='</tr>';
		}
		$html.='</tbody></table>';
		$html.='<br/><a class="btn btn-sm btn-danger deleteUser" id="btDeleteMass_mod_anagrafica_tessere_interne" name="btDeleteMass_mod_anagrafica_tessere_interne""
					onclick="deleteMassiveMasterDetails('.$id.',\'entry_list\',\'check_id_mod_anagrafica_tessere_interne\',\'mod_esercizi\',\'_mod_anagrafica_tessere_interne\')">
					<i class="fa fa-trash"></i> Cancellazione Massiva
				</a>';
		return $html;
	}


	/**
	* Funzione caricamento della finestra per la master details, tabella _mod_anagrafica_tessere_interne
	* @param mixed $action
	* @param string $entryID
	* @param string $entryIDMasterDetails
	* @return string
	**/
	public function winMasterDetail_mod_anagrafica_tessere_interne($action, $entryID, $entryIDMasterDetails = NULL){
		if($entryIDMasterDetails == 'NULL'){
			$entryIDMasterDetails = '';
		}
		$rowWinForm = $this->modelClassModule->get_from_master_details_by_id($entryIDMasterDetails, '_mod_anagrafica_tessere_interne', 'id');
		$html = '<div>
				<section class="content">
					<div class="row">
						<div class="col-md-12">
							<div class="box box-primary">
								<div class="box-body">
								<div id="msg_err" ondblclick="this.style.display=\'none\';" style="display: none; border: 1px solid; background-color: rgb(255, 64, 0); border-radius: 5px; padding: 10px; color: white; font-weight: bold;">
								</div>									
									<form  name="frm_master_detail" id="frm_master_detail">
									<input type="hidden" id="table" name="table" value="_mod_anagrafica_tessere_interne">
									<input type="hidden" id="action" name="action" value="'.$action.'"/> 
									<input type="hidden" id="saveType" name="saveType" value="form"/> 	
									<input type="hidden" id="entryID"          name="entryID"  value="'.$entryID.'">
									<input type="hidden" id="entryIDMasterDetails" 		name="entryIDMasterDetails" value="'.$entryIDMasterDetails.'" >															
										<div class="col-md-12">
											<div class="form-group">';
		$fk_anagrafica_refval = $this->modelClassModule->getValuesByFk('mod_anagrafica',NULL, NULL);
		$html .= '<div class="form-group">';
							
		$html .= '<label for="fk_anagrafica"><b style="color:#990000">(*)</b>Alunno </label>';
							
		$html .= "<!-- 								
							
		<input list='fk_anagrafica_datalist' class='form-control combobox' name='fk_anagrafica' id='fk_anagrafica' value='<?php echo fk_anagrafica;?>'>
							
		<datalist name='fk_anagrafica_datalist' id='fk_anagrafica_datalist' onselect=\"alert(this.text)\">
							
		-->									
							
		<!-- -->";	
							
		$html .= '<SELECT name=\'fk_anagrafica\' id=\'fk_anagrafica\' 
								style="width:100%;padding: 6px 12px;font-size:14px;
								border-top-right-radius:0px;border-bottom-right-radius:0px;
								border-top-left-radius:0px;border-bottom-left-radius:0px;
								border:1px solid #ccc"
							class="select2-autocomplete form-control">';	$html .= '<OPTION VALUE></OPTION>';

		foreach ($fk_anagrafica_refval as $key => $value) {
			if ($value['id'] == $rowWinForm['fk_anagrafica']) {
				$html .= "<option value='" . $value['id'] . "' SELECTED>" . $value['nome'] . "</option>";
			} else {
				$html .= "<option value='".$value['id'] . "'>" . $value['nome'] . "</option>";
			}
		}
		$html .= '</SELECT>';
										
		$html .= '</div>';
		$fk_esercizio_refval = $this->modelClassModule->getValuesByFk('mod_esercizi',NULL, NULL);
		$html .= '<div class="form-group">';
							
		$html .= '<label for="fk_esercizio"><b style="color:#990000">(*)</b>Esercizio </label>';
							
		$html .= "<!-- 								
							
		<input list='fk_esercizio_datalist' class='form-control combobox' name='fk_esercizio' id='fk_esercizio' value='<?php echo fk_esercizio;?>'>
							
		<datalist name='fk_esercizio_datalist' id='fk_esercizio_datalist' onselect=\"alert(this.text)\">
							
		-->									
							
		<!-- -->";	
							
		$html .= '<SELECT name=\'fk_esercizio\' id=\'fk_esercizio\' 
								style="width:100%;padding: 6px 12px;font-size:14px;
								border-top-right-radius:0px;border-bottom-right-radius:0px;
								border-top-left-radius:0px;border-bottom-left-radius:0px;
								border:1px solid #ccc"
							class="select2-autocomplete form-control">';	$html .= '<OPTION VALUE></OPTION>';

		foreach ($fk_esercizio_refval as $key => $value) {
			if ($value['id'] == $rowWinForm['fk_esercizio']) {
				$html .= "<option value='" . $value['id'] . "' SELECTED>" . $value['nome'] . "</option>";
			} else {
				$html .= "<option value='".$value['id'] . "'>" . $value['nome'] . "</option>";
			}
		}
		$html .= '</SELECT>';
										
		$html .= '</div>';
		$html .= '<div class="form-group">';
							
		$html .= '<label for="varchar"><b style="color:#990000">(*)</b>Tessera Interna</label>';
							
		$html .= '<div class="input-group">';
							
		$html .= '<div class="input-group-addon"><i class="fa fa-text-height"></i></div>';		
							
		$html .= '<input type="text" class="form-control" maxlength=\'50\' name="tessera_interna" id="tessera_interna" placeholder="Tessera Interna" autocomplete="off" value="'.$rowWinForm['tessera_interna'].'" />';
							
		$html .= '</div></div>';
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
	* Funzione caricamento della finestra per la master details,in modalita di inserimento multiplo, tabella _mod_anagrafica_tessere_interne
	* @param mixed $action
	* @param string $entryID
	* @return string
	**/
	public function winMasterDetailMulti_mod_anagrafica_tessere_interne($action, $entryID){
		$html = '<div>
				<section class="content">
					<div class="row">
						<div class="col-md-12">
							<div class="box box-primary">
								<div class="box-body">
								<div id="msg_err" ondblclick="this.style.display=\'none\';" style="display: none; border: 1px solid; background-color: rgb(255, 64, 0); border-radius: 5px; padding: 10px; color: white; font-weight: bold;">
								</div>									
									<form  name="frm_master_detail" id="frm_master_detail">
									<input type="hidden" id="table" name="table" value="_mod_anagrafica_tessere_interne">
									<input type="hidden" id="action" name="action" value="'.$action.'"/> 
									<input type="hidden" id="saveType" name="saveType" value="form"/> 	
									<input type="hidden" id="entryID"          name="entryID"  value="'.$entryID.'">													
										<div class="col-md-12">
											<div class="form-group">';
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


	public function _rules()
	{
		$this->form_validation->set_rules('data_a', 'a data', 'trim|required');
		$this->form_validation->set_rules('data_da', 'da data', 'trim|required');
		$this->form_validation->set_rules('nome', 'nome', 'trim|max_length[100]|required');

		$this->form_validation->set_rules('id', 'id', 'trim');
		$this->form_validation->set_error_delimiters('<span class="text-danger">', '</span>');

	}

}