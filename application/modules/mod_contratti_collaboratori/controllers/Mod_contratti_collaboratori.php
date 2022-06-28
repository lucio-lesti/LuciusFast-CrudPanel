<?php
if (!defined('BASEPATH')){
	exit('No direct script access allowed');
}
require APPPATH . '/libraries/BaseController.php';
use Dompdf\Dompdf;

class Mod_contratti_collaboratori extends BaseController
{

	public function __construct()
	{
		parent::__construct();
		$this->load->model('Mod_contratti_collaboratori_model');
		$this->mod_name = 'mod_contratti_collaboratori';
		$this->mod_type = 'crud';
		$this->mod_title = 'Contratti Collaboratori';
		$this->modelClassModule =  $this->Mod_contratti_collaboratori_model;
		$this->pkIdName = 'id';
		$this->viewName_ListAjax = 'mod_contratti_collaboratori_list_ajax';
		$this->viewName_FormROAjax = 'mod_contratti_collaboratori_read_ajax';
		$this->viewName_FormAjax = 'mod_contratti_collaboratori_form_ajax';


		/*
		//ABILITARE PER CUSTOMIZZAZIONE PER MODULO ERRORI SQL 
		//IN CORSO MIGLIORIA PER GESTIRE I MESSAGGI TRAMITE TABELLA DI TRASCODIFICA
		//SPOSTARE LOGICA NEL MODEL				
		$this->MsgDBConverted['insert']['error']['1062'] = "Esiste gia questo elemento per il modulo Contratti Collaboratori";
		$this->MsgDBConverted['insert']['error']['1452'] = "Esiste gia questo elemento per il modulo Contratti Collaboratori";
		$this->MsgDBConverted['update']['error']['1062'] = "Esiste gia questo elemento per il modulo Contratti Collaboratori";
		$this->MsgDBConverted['update']['error']['1452'] = "Esiste gia questo elemento per il modulo Contratti Collaboratori";
		$this->MsgDBConverted['insert_massive']['error']['1062'] = "Esiste gia questo elemento per il modulo Contratti Collaboratori";
		$this->MsgDBConverted['insert_massive']['error']['1452'] = "Esiste gia questo elemento per il modulo Contratti Collaboratori";
		$this->MsgDBConverted['update_massive']['error']['1062'] = "Esiste gia questo elemento per il modulo Contratti Collaboratori";
		$this->MsgDBConverted['update_massive']['error']['1452'] = "Esiste gia questo elemento per il modulo Contratti Collaboratori";
		$this->MsgDBConverted['delete']['error']['1217'] = "Impossibile eliminare questo elemento del modulo Contratti Collaboratori. E' usato nei seguenti moduli:";
		$this->MsgDBConverted['delete_massive']['error']['1217'] = "Impossibile eliminare alcuni elementi del modulo Contratti Collaboratori. Sono usati nei seguenti moduli:";
		*/


		$this->setFormFields('nome');
		//PER L'ARRAY DI REFERENZIAMENTO, USARE IL CONCAT PER CONCATENARE PIU CAMPI NEL CAMPO 'NOME'
		//ES.CONCAT(cognome," ",nome)
		$this->setFormFields('fk_anagrafica','mod_anagrafica',array("id" => 'id', "nome" => 'CONCAT(nome," ",cognome)')," WHERE mod_anagrafica.anagrafica_attributo LIKE '%INSEGNANTE%' or mod_anagrafica.anagrafica_attributo LIKE '%COLLABORATORE%' ");
		$this->setFormFields('mansione');
		$this->setFormFields('data_da');
		$this->setFormFields('data_a');
		$this->setFormFields('importo_mensile');
		$this->setFormFields('fk_tipopagamento','mod_tipopagamento',array("id" => 'id', "nome" => "CONCAT(nome,'_',predefinito)"));
		$this->setFormFields('banca');
		$this->setFormFields('iban');
		$this->setFormFields('id');
		$this->setFormFields('data_firma_contratto');


		/**  RICHIAMO LE FUNZIONI PER IL CARICAMENTO DELLE MASTER DETAILS**/
		$this->addMasterDetailsLoadFunc('getMasterDetail_mod_pagamenti_collaboratori','Pagamenti Collaboratore per questo contratto','getMasterDetail_mod_pagamenti_collaboratori');


		/**  AREA LAMBDA FUNCTIONS - FUNZIONI RICHIAMATE in updateAjax, createAjax e nelle op. di CRUD master details**/		
		$this->custom_operations_list['mod_contratti_collaboratori_check_date'] = function($request, $id = NULL){
			$ret = $this->utilities->check_date_greater_then($request['data_da'], $request['data_a']);
			if($ret === FALSE){
				$this->session->set_flashdata('error',"Data Da non puo essere maggiore di Data a");
				return false;
			}	
			
			$rangeDateEsistenti = $this->modelClassModule->isRangeDateEsistentiContratto($_REQUEST['fk_anagrafica'],$this->utilities->convertToDateEN($_REQUEST['data_da']), $this->utilities->convertToDateEN($_REQUEST['data_a']), $id);
			if($rangeDateEsistenti['check'] == TRUE){
				$this->session->set_flashdata('error',"Esiste gia un contratto per questa persona con queste date:Da <b>".$rangeDateEsistenti['data_da']."</b> A <b>".$rangeDateEsistenti['data_a']."</b><br>Selezionare Date con Range diverso");
				return false;				 
			}			
					
		}; 

  
		//PRELEVO EMAIL COLLABORATORE
		$this->custom_form_data_functions['email']= function($request = NULL, $id = NULL){
			$email = "";
			if($id != NULL){
				$email = $this->modelClassModule->getEmailCollaboratore($id);
			} else {
				if((isset($_REQUEST['recordID'])) && ($_REQUEST['recordID'] != "") ){
					$email = $this->modelClassModule->getEmailCollaboratore($_REQUEST['recordID']);
				}
			}
			
			return $email;
		}; 		


	}

	

	/**
	* Funzione caricamento della master details, tabella _mod_pagamenti_collaboratori
	* @param mixed $id
	* @param string $isAjax
	* @return string
	**/
	public function getMasterDetail_mod_pagamenti_collaboratori($id, $isAjax = 'FALSE'){
		$row =  $this->modelClassModule->getMasterDetail_mod_pagamenti_collaboratori($id, $isAjax);
		$html = '';
		$winFormType ="form";//VALORI ACCETTATI: {'multi','form'}
		
		/*
		if($isAjax == 'FALSE'){
			if($winFormType == "form"){
				$html .= '
							<br><a class="btn btn-primary" style="cursor:pointer" onclick="winFormMasterDetails(\'mod_contratti_collaboratori\',\'winMasterDetail_mod_pagamenti_collaboratori\',\'insert\','.$id.',\'NULL\',\'NUOVO Pagamento Collaboratore\', arrayValidationFields,\'winMasterDetail_mod_pagamenti_collaboratori\',\'form\')">[ Aggiungi un elemento]</a><br>
							<br><br>';
			} else {
				$html .= '
						<br><a class="btn btn-primary" style="cursor:pointer" onclick="winFormMasterDetails(\'mod_contratti_collaboratori\',\'winMasterDetailMulti_mod_pagamenti_collaboratori\',\'insert\','.$id.',\'NULL\',\'NUOVO Pagamento Collaboratore\', arrayValidationFields,\'winMasterDetailMulti_mod_pagamenti_collaboratori\',\'multi\')">[ Aggiungi un elemento]</a><br>
						<br><br>';
			}
		}
		*/
		$html .= '<br>';

		$html .= ' <input  type="text" class="form-control" autocomplete="off" 
							id="search_mod_pagamenti_collaboratori" style="width:20%" placeholder="Cerca..."
							onkeypress="disableKeySubmit()"
							onkeyup="searchInMasterDetailsTable(\'search_mod_pagamenti_collaboratori\', \'tbl_mod_pagamenti_collaboratori\',8)"><br>';
		$html .= "<table class='TFtable' id='tbl_mod_pagamenti_collaboratori' style='font-size:12px'>
					<tr>
						<thead>
						<th class='sorting_disabled' rowspan='1' colspan='1' aria-label='' style='width:10%;'>
							<input type='checkbox' id='check_master_mod_pagamenti_collaboratori' name='check_master_mod_pagamenti_collaboratori' 
							onchange=\"selezionaDeselezionaTutti('check_master_mod_pagamenti_collaboratori','check_id_mod_pagamenti_collaboratori','btDeleteMass_mod_pagamenti_collaboratori')\">
						</th>";
		$html.='<th>Data Pagamento</th>';
		//$html.='<th>Ora</th>';
		$html.='<th>Importo</th>';
		$html.='<th>Tipo Pagamento</th>';
		
		/*
		if($winFormType == "form"){
			$html.='<th>Modifica</th>';
		}
		$html.='<th>Elimina</th>';
		*/

		$html.='</tr>';
		$html.='<tbody>';
		foreach($row as $key => $value){
			$html.="<tr>";
			$html.="<td><input type='checkbox' id='check_id_mod_pagamenti_collaboratori' name='check_id_mod_pagamenti_collaboratori' value='".$value['id']."' onchange=\"verificaNrCheckBoxSelezionati('check_id_mod_pagamenti_collaboratori','btDeleteMass_mod_pagamenti_collaboratori')\"></td>";
			$html.="<td><input type='hidden' id='id[]' name='id[]' value='".$value['id']."'>".$this->utilities->convertToDateIT($value['datapagamento'])."</td>";
			//$html.="<td><input type='hidden' id='id[]' name='id[]' value='".$value['id']."'>".substr($value['ora_pagamento'],0,-3)."</td>";
			$html.="<td><input type='hidden' id='id[]' name='id[]' value='".$value['id']."'>".$value['importo']."</td>";
			$html.="<td><input type='hidden' id='id[]' name='id[]' value='".$value['id']."'>".$value['mod_tipopagamento_nome']."</td>";
			
			/*
			if($winFormType == "form"){
				$html.="<td><a style='cursor:pointer' class='btn btn-sm btn-info' onclick ='winFormMasterDetails(\"mod_contratti_collaboratori\",\"winMasterDetail_mod_pagamenti_collaboratori\",\"edit\", $id,".$value['id'].",\"MODIFICA _mod_pagamenti_collaboratori\",arrayValidationFields)' title='Modifica _mod_pagamenti_collaboratori'><i class='fa fa-edit'></a></td>";
			}
 
			$html.="<td><a style='cursor:pointer' class='btn btn-sm btn-danger deleteUser' onclick ='deleteMasterDetails(".$value['id'].", ".$id.", \"mod_contratti_collaboratori\",\"_mod_pagamenti_collaboratori\")' title='Elimina'><i class='fa fa-trash'></a></td>";
			*/
			$html.='</tr>';
		}
		$html.='</tbody></table>';
		/*
		$html.='<br/><a class="btn btn-sm btn-danger deleteUser" id="btDeleteMass_mod_pagamenti_collaboratori" name="btDeleteMass_mod_pagamenti_collaboratori""
					onclick="deleteMassiveMasterDetails('.$id.',\'entry_list\',\'check_id_mod_pagamenti_collaboratori\',\'mod_contratti_collaboratori\',\'_mod_pagamenti_collaboratori\')">
					<i class="fa fa-trash"></i> Cancellazione Massiva
				</a>';
		*/
		return $html;
	}


	/**
	* Funzione caricamento della master details, tabella _mod_contratti_collaboratori_corsi
	* @param mixed $id
	* @param string $isAjax
	* @return string
	**/
	public function getMasterDetail_mod_contratti_collaboratori_corsi($id, $isAjax = 'FALSE'){
		$row =  $this->modelClassModule->getMasterDetail_mod_contratti_collaboratori_corsi($id, $isAjax);
		$html = '';
		$winFormType ="form";//VALORI ACCETTATI: {'multi','form'}
		
		/*
		if($isAjax == 'FALSE'){
			if($winFormType == "form"){
				$html .= '
							<br><a class="btn btn-primary" style="cursor:pointer" onclick="winFormMasterDetails(\'mod_contratti_collaboratori\',\'winMasterDetail_mod_contratti_collaboratori_corsi\',\'insert\','.$id.',\'NULL\',\'Aggiungi Corso a collaboratore\', arrayValidationFields,\'winMasterDetail_mod_contratti_collaboratori_corsi\',\'form\')">[ Aggiungi un elemento]</a><br>
							<br><br>';
			} else {
				$html .= '
						<br><a class="btn btn-primary" style="cursor:pointer" onclick="winFormMasterDetails(\'mod_contratti_collaboratori\',\'winMasterDetailMulti_mod_contratti_collaboratori_corsi\',\'insert\','.$id.',\'NULL\',\'NUOVO _mod_contratti_collaboratori_corsi\', arrayValidationFields,\'winMasterDetailMulti_mod_contratti_collaboratori_corsi\',\'multi\')">[ Aggiungi un elemento]</a><br>
						<br><br>';
			}
		}
		*/
		$html .= '<br>';

		$html .= ' <input  type="text" class="form-control" autocomplete="off" 
							id="search_mod_contratti_collaboratori_corsi" style="width:20%" placeholder="Cerca..."
							onkeypress="disableKeySubmit()"
							onkeyup="searchInMasterDetailsTable(\'search_mod_contratti_collaboratori_corsi\', \'tbl_mod_contratti_collaboratori_corsi\',3)"><br>';
		$html .= "<table class='TFtable' id='tbl_mod_contratti_collaboratori_corsi' style='font-size:12px'>
					<tr>
						<thead>
						<th class='sorting_disabled' rowspan='1' colspan='1' aria-label='' style='width:10%;'>
							<input type='checkbox' id='check_master_mod_contratti_collaboratori_corsi' name='check_master_mod_contratti_collaboratori_corsi' 
							onchange=\"selezionaDeselezionaTutti('check_master_mod_contratti_collaboratori_corsi','check_id_mod_contratti_collaboratori_corsi','btDeleteMass_mod_contratti_collaboratori_corsi')\">
						</th>";
		//$html.='<th>Contratto</th>';
		$html.='<th>Corso</th>';
		if($winFormType == "form"){
		//	$html.='<th>Modifica</th>';
		}
		//$html.='<th>Elimina</th>';
		$html.='</tr>';
		$html.='<tbody>';
		foreach($row as $key => $value){
			$html.="<tr>";
			$html.="<td><input type='checkbox' id='check_id_mod_contratti_collaboratori_corsi' name='check_id_mod_contratti_collaboratori_corsi' value='".$value['id']."' onchange=\"verificaNrCheckBoxSelezionati('check_id_mod_contratti_collaboratori_corsi','btDeleteMass_mod_contratti_collaboratori_corsi')\"></td>";
			//$html.="<td><input type='hidden' id='id[]' name='id[]' value='".$value['id']."'>".$value['mod_contratti_collaboratori_nome']."</td>";
			$html.="<td><input type='hidden' id='id[]' name='id[]' value='".$value['id']."'>".$value['mod_corsi_nome']."</td>";
			if($winFormType == "form"){
			//	$html.="<td><a style='cursor:pointer' class='btn btn-sm btn-info' onclick ='winFormMasterDetails(\"mod_contratti_collaboratori\",\"winMasterDetail_mod_contratti_collaboratori_corsi\",\"edit\", $id,".$value['id'].",\"MODIFICA _mod_contratti_collaboratori_corsi\",arrayValidationFields)' title='Modifica _mod_contratti_collaboratori_corsi'><i class='fa fa-edit'></a></td>";
			}
			//$html.="<td><a style='cursor:pointer' class='btn btn-sm btn-danger deleteUser' onclick ='deleteMasterDetails(".$value['id'].", ".$id.", \"mod_contratti_collaboratori\",\"_mod_contratti_collaboratori_corsi\")' title='Elimina'><i class='fa fa-trash'></a></td>";
			$html.='</tr>';
		}
		$html.='</tbody></table>';
		/*
		$html.='<br/><a class="btn btn-sm btn-danger deleteUser" id="btDeleteMass_mod_contratti_collaboratori_corsi" name="btDeleteMass_mod_contratti_collaboratori_corsi""
					onclick="deleteMassiveMasterDetails('.$id.',\'entry_list\',\'check_id_mod_contratti_collaboratori_corsi\',\'mod_contratti_collaboratori\',\'_mod_contratti_collaboratori_corsi\')">
					<i class="fa fa-trash"></i> Cancellazione Massiva
				</a>';
		*/
		return $html;
	}


	/**
	* Funzione caricamento della finestra per la master details, tabella _mod_pagamenti_collaboratori
	* @param mixed $action
	* @param string $entryID
	* @param string $entryIDMasterDetails
	* @return string
	**/
	public function winMasterDetail_mod_pagamenti_collaboratori($action, $entryID, $entryIDMasterDetails = NULL){
		if($entryIDMasterDetails == 'NULL'){
			$entryIDMasterDetails = '';
		}
		$rowWinForm = $this->modelClassModule->get_from_master_details_by_id($entryIDMasterDetails, '_mod_pagamenti_collaboratori', 'id');
		$html = '<div>
				<section class="content">
					<div class="row">
						<div class="col-md-12">
							<div class="box box-primary">
								<div class="box-body">
								<div id="msg_err" ondblclick="this.style.display=\'none\';" style="display: none; border: 1px solid; background-color: rgb(255, 64, 0); border-radius: 5px; padding: 10px; color: white; font-weight: bold;">
								</div>									
									<form  name="frm_master_detail" id="frm_master_detail">
									<input type="hidden" id="table" name="table" value="_mod_pagamenti_collaboratori">
									<input type="hidden" id="action" name="action" value="'.$action.'"/> 
									<input type="hidden" id="saveType" name="saveType" value="form"/> 	
									<input type="hidden" id="entryID"          name="entryID"  value="'.$entryID.'">
									<input type="hidden" id="fk_contratto"          name="fk_contratto"  value="'.$entryID.'">
									<input type="hidden" id="entryIDMasterDetails" 		name="entryIDMasterDetails" value="'.$entryIDMasterDetails.'" >															
										<div class="col-md-12">
											<div class="form-group">';
 
		$html .= '<div class="form-group">';
									
		$html .= '<label for="date"><b style="color:#990000">(*)</b>Data Pagamento </label>';
									
		$html .= '<div class="input-group">';
									
		$html .= '<div class="input-group-addon"><i class="fa fa-calendar"></i></div>';			
									
		$html .= '<input type="text" class="form-control datemask" name="datapagamento" id="datapagamento" placeholder="Data Pagamento"';
									
		$html .= 'autocomplete="off" style="background-color:#FFFFFF" value="'.$this->utilities->convertToDateIT($rowWinForm['datapagamento']).'" />';
									
		$html .= '</div></div>';
		$html .= '<div class="form-group">';
									
		$html .= '<label for="time"><b style="color:#990000">(*)</b>Ora </label>';
									
		$html .= '			<div class="input-group">';
									
		$html .= '				<div class="input-group-addon"><i class="fa fa-calendar"></i></div>';	
									
		$html .= '<input type="text" class="form-control timemask" name="ora_pagamento" id="ora_pagamento" placeholder="Ora" autocomplete="off" value="'.$rowWinForm['ora_pagamento'].'" />';
									
		$html .= '</div></div>';
		$html .= ' <div class="form-group">';
						
		$html .= '<label for="float"><b style="color:#990000">(*)</b>Importo </label>';
						
		$html .= '<div class="input-group">';
						
		$html .= '<div class="input-group-addon"><i class="fa fa-sort-numeric-asc"></i></div>';			
						
		$html .= '<input type="number" class="form-control" maxlength=\'10\' name="importo" id="importo" placeholder="Importo" autocomplete="off" value="'.$rowWinForm['importo'].'" step=0.01 />';
						
		$html .= '</div></div>';
		$fk_tipopagamento_refval = $this->modelClassModule->getValuesByFk('mod_tipopagamento',NULL, NULL);
		$html .= '<div class="form-group">';
		 
							$fk_tipopagamento_label = NULL;
							foreach ($fk_tipopagamento_refval as $key => $value) {
								if ($value['id'] == $rowWinForm['fk_tipopagamento']) {
									$fk_tipopagamento_label = $value['nome'];
								}  
							}
				 						
							
		$html .= '<label for="fk_tipopagamento"><b style="color:#990000">(*)</b>Tipo Pagamento </label>';
							
		$html .= "							
							
		<input autofocus class='form-control' autocomplete='off' 
								oninput='onInput(\"fk_tipopagamento_datalist_inp\",\"fk_tipopagamento_datalist\",\"fk_tipopagamento\")'
								style='width:100%;padding: 6px 12px;font-size:14px;
									border-top-right-radius:0px;border-bottom-right-radius:0px;
									border-top-left-radius:0px;border-bottom-left-radius:0px;
									border:1px solid #ccc'						
								list='fk_tipopagamento_datalist'  
								name='fk_tipopagamento_datalist_inp' 
								id='fk_tipopagamento_datalist_inp' value='$fk_tipopagamento_label'>
							
		<input type='hidden' name='fk_tipopagamento' id='fk_tipopagamento' value='".$rowWinForm['fk_tipopagamento']."' >
							
		<datalist name='fk_tipopagamento_datalist' id='fk_tipopagamento_datalist' >";	$html .= '<OPTION VALUE></OPTION>';

		foreach ($fk_tipopagamento_refval as $key => $value) {
			if ($value['id'] == $rowWinForm['fk_tipopagamento']) {
				$html .= "<option data-value='" . $value['id'] . "' SELECTED>" . $value['nome'] . "</option>";
			} else {
				$html .= "<option data-value='".$value['id'] . "'>" . $value['nome'] . "</option>";
			}
		}
		$html .= '</SELECT>';
										
		$html .= '</div>';
		$fk_causale_pagamento_refval = $this->modelClassModule->getValuesByFk('mod_causali_pagamento',NULL, NULL);
		$html .= '<div class="form-group">';
		 
							$fk_causale_pagamento_label = NULL;
							foreach ($fk_causale_pagamento_refval as $key => $value) {
								if ($value['id'] == $rowWinForm['fk_causale_pagamento']) {
									$fk_causale_pagamento_label = $value['nome'];
								}  
							}
				 						
							
		$html .= '<label for="fk_causale_pagamento"><b style="color:#990000">(*)</b>Causale Pagamento </label>';
							
		$html .= "							
							
		<input autofocus class='form-control' autocomplete='off' 
								oninput='onInput(\"fk_causale_pagamento_datalist_inp\",\"fk_causale_pagamento_datalist\",\"fk_causale_pagamento\")'
								style='width:100%;padding: 6px 12px;font-size:14px;
									border-top-right-radius:0px;border-bottom-right-radius:0px;
									border-top-left-radius:0px;border-bottom-left-radius:0px;
									border:1px solid #ccc'						
								list='fk_causale_pagamento_datalist'  
								name='fk_causale_pagamento_datalist_inp' 
								id='fk_causale_pagamento_datalist_inp' value='$fk_causale_pagamento_label'>
							
		<input type='hidden' name='fk_causale_pagamento' id='fk_causale_pagamento' value='".$rowWinForm['fk_causale_pagamento']."' >
							
		<datalist name='fk_causale_pagamento_datalist' id='fk_causale_pagamento_datalist' >";	$html .= '<OPTION VALUE></OPTION>';

		foreach ($fk_causale_pagamento_refval as $key => $value) {
			if ($value['id'] == $rowWinForm['fk_causale_pagamento']) {
				$html .= "<option data-value='" . $value['id'] . "' SELECTED>" . $value['nome'] . "</option>";
			} else {
				$html .= "<option data-value='".$value['id'] . "'>" . $value['nome'] . "</option>";
			}
		}
		$html .= '</SELECT>';
										
		$html .= '</div>';
		$html .= '<div class="form-group">';
									
		$html .= '<label for="notepagamento">Note</label>';
									
		$html .= '<textarea class="form-control" rows="3" name="notepagamento" id="notepagamento" placeholder="Note">'.$rowWinForm['notepagamento'].'</textarea>';
									
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
	* Funzione caricamento della finestra per la master details, tabella _mod_contratti_collaboratori_corsi
	* @param mixed $action
	* @param string $entryID
	* @param string $entryIDMasterDetails
	* @return string
	**/
	public function winMasterDetail_mod_contratti_collaboratori_corsi($action, $entryID, $entryIDMasterDetails = NULL){
		if($entryIDMasterDetails == 'NULL'){
			$entryIDMasterDetails = '';
		}
		$rowWinForm = $this->modelClassModule->get_from_master_details_by_id($entryIDMasterDetails, '_mod_contratti_collaboratori_corsi', 'id');
		$html = '<div>
				<section class="content">
					<div class="row">
						<div class="col-md-12">
							<div class="box box-primary">
								<div class="box-body">
								<div id="msg_err" ondblclick="this.style.display=\'none\';" style="display: none; border: 1px solid; background-color: rgb(255, 64, 0); border-radius: 5px; padding: 10px; color: white; font-weight: bold;">
								</div>									
									<form  name="frm_master_detail" id="frm_master_detail">
									<input type="hidden" id="table" name="table" value="_mod_contratti_collaboratori_corsi">
									<input type="hidden" id="action" name="action" value="'.$action.'"/> 
									<input type="hidden" id="saveType" name="saveType" value="form"/> 	
									<input type="hidden" id="entryID"          name="entryID"  value="'.$entryID.'">
									<input type="hidden" id="fk_contratto"          name="fk_contratto"  value="'.$entryID.'">
									<input type="hidden" id="entryIDMasterDetails" 		name="entryIDMasterDetails" value="'.$entryIDMasterDetails.'" >															
										<div class="col-md-12">
											<div class="form-group">';

		$fk_corso_refval = $this->modelClassModule->getValuesByFk('mod_corsi',NULL, NULL);
		$html .= '<div class="form-group">';
		 
							$fk_corso_label = NULL;
							foreach ($fk_corso_refval as $key => $value) {
								if ($value['id'] == $rowWinForm['fk_corso']) {
									$fk_corso_label = $value['nome'];
								}  
							}
				 						
							
		$html .= '<label for="fk_corso"><b style="color:#990000">(*)</b>Corso </label>';
							
		$html .= "							
							
		<input autofocus class='form-control' autocomplete='off' 
								oninput='onInput(\"fk_corso_datalist_inp\",\"fk_corso_datalist\",\"fk_corso\")'
								style='width:100%;padding: 6px 12px;font-size:14px;
									border-top-right-radius:0px;border-bottom-right-radius:0px;
									border-top-left-radius:0px;border-bottom-left-radius:0px;
									border:1px solid #ccc'						
								list='fk_corso_datalist'  
								name='fk_corso_datalist_inp' 
								id='fk_corso_datalist_inp' value='$fk_corso_label'>
							
		<input type='hidden' name='fk_corso' id='fk_corso' value='".$rowWinForm['fk_corso']."' >
							
		<datalist name='fk_corso_datalist' id='fk_corso_datalist' >";	$html .= '<OPTION VALUE></OPTION>';

		foreach ($fk_corso_refval as $key => $value) {
			if ($value['id'] == $rowWinForm['fk_corso']) {
				$html .= "<option data-value='" . $value['id'] . "' SELECTED>" . $value['nome'] . "</option>";
			} else {
				$html .= "<option data-value='".$value['id'] . "'>" . $value['nome'] . "</option>";
			}
		}
		$html .= '</SELECT>';
										
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
	* Funzione caricamento della finestra per la master details,in modalita di inserimento multiplo, tabella _mod_pagamenti_collaboratori
	* @param mixed $action
	* @param string $entryID
	* @return string
	**/
	public function winMasterDetailMulti_mod_pagamenti_collaboratori($action, $entryID){
		$html = '<div>
				<section class="content">
					<div class="row">
						<div class="col-md-12">
							<div class="box box-primary">
								<div class="box-body">
								<div id="msg_err" ondblclick="this.style.display=\'none\';" style="display: none; border: 1px solid; background-color: rgb(255, 64, 0); border-radius: 5px; padding: 10px; color: white; font-weight: bold;">
								</div>									
									<form  name="frm_master_detail" id="frm_master_detail">
									<input type="hidden" id="table" name="table" value="_mod_pagamenti_collaboratori">
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


	/**
	* Funzione caricamento della finestra per la master details,in modalita di inserimento multiplo, tabella _mod_contratti_collaboratori_corsi
	* @param mixed $action
	* @param string $entryID
	* @return string
	**/
	public function winMasterDetailMulti_mod_contratti_collaboratori_corsi($action, $entryID){
		$html = '<div>
				<section class="content">
					<div class="row">
						<div class="col-md-12">
							<div class="box box-primary">
								<div class="box-body">
								<div id="msg_err" ondblclick="this.style.display=\'none\';" style="display: none; border: 1px solid; background-color: rgb(255, 64, 0); border-radius: 5px; padding: 10px; color: white; font-weight: bold;">
								</div>									
									<form  name="frm_master_detail" id="frm_master_detail">
									<input type="hidden" id="table" name="table" value="_mod_contratti_collaboratori_corsi">
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


	/**
	 * Stampa di un modulo
	 * Stampa a video, richiamando il metodo stampa_out e salva sul server
	 * @param mixed $id
	 */
	public function stampa($id){

		if (!file_exists(FCPATH . "/stampe/" . $this->mod_name)) {
			$oldmask = umask(0);
			mkdir(FCPATH . "/stampe/" . $this->mod_name, 0777);
			umask($oldmask);
		}
		$out = $this->stampa_out($id);
		file_put_contents(FCPATH . "/stampe/" . $this->mod_name . '/' . $id .'.pdf', $out);
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

		$row = $this->modelClassModule->getDettagliContratto($id);

		if ($row) {
			$data = array(
				'company_name' => set_value('company_name', $company_name),
				'company_code' => set_value('company_code', $company_code),
				'company_email' => set_value('company_email', $company_email),
				'company_phone' => set_value('company_phone', $company_phone),
				'company_address' => set_value('company_address', $company_address),
				'company_logo' => set_value('company_logo', $company_logo),
			);			
			foreach($row as $k => $v){
				$data[$k] = $v;
			}
			//print'<pre>';print_r($data);die();
			$view = $this->load->view($this->mod_name . '/mod_contratti_collaboratori_form_pdf', $data, true);
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
		$this->form_validation->set_rules('nome', 'nome contratto', 'trim|max_length[50]|required');
		$this->form_validation->set_rules('fk_anagrafica', 'collaboratore', 'trim|numeric|max_length[10]|required');
		$this->form_validation->set_rules('mansione', 'mansione', 'trim|max_length[255]|required');
		$this->form_validation->set_rules('data_firma_contratto', 'data firma contratto', 'trim|required');
		$this->form_validation->set_rules('data_da', 'data da', 'trim|required');
		$this->form_validation->set_rules('data_a', 'data a', 'trim|required');
		$this->form_validation->set_rules('importo_mensile', 'importo mensile', 'trim|numeric|max_length[9]|required');
		$this->form_validation->set_rules('fk_tipopagamento', 'tipo pagamento', 'trim|required');
		$this->form_validation->set_rules('banca', 'banca', 'trim|max_length[255]');
		$this->form_validation->set_rules('iban', 'iban', 'trim|max_length[255]');

		$this->form_validation->set_rules('id', 'id', 'trim');
		$this->form_validation->set_error_delimiters('<span class="text-danger">', '</span>');

	}

}