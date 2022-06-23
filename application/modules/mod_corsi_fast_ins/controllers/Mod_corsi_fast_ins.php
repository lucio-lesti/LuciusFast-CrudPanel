<?php
if (!defined('BASEPATH')){
	exit('No direct script access allowed');
}
require APPPATH . '/libraries/BaseController.php';
use Dompdf\Dompdf;

class Mod_corsi_fast_ins extends BaseController
{

	public function __construct()
	{
			
		parent::__construct();
		$this->load->model('Mod_corsi_fast_ins_model');
		$this->mod_name = 'mod_corsi_fast_ins';
		$this->mod_type = 'crud';
		$this->mod_title = 'Corsi';
		$this->modelClassModule =  $this->Mod_corsi_fast_ins_model;
		$this->pkIdName = 'id';
		$this->viewName_ListAjax = 'mod_corsi_fast_ins_list_ajax';
		$this->viewName_FormROAjax = 'mod_corsi_fast_ins_read_ajax';
		$this->viewName_FormAjax = 'mod_corsi_fast_ins_form_ajax';

		$this->MsgDBConverted['insert']['error']['1062'] = "Esiste gia questo elemento per il modulo Corsi";
		$this->MsgDBConverted['insert']['error']['1452'] = "Esiste gia questo elemento per il modulo Corsi";
		$this->MsgDBConverted['update']['error']['1062'] = "Esiste gia questo elemento per il modulo Corsi";
		$this->MsgDBConverted['update']['error']['1452'] = "Esiste gia questo elemento per il modulo Corsi";
		$this->MsgDBConverted['insert_massive']['error']['1062'] = "Esiste gia questo elemento per il modulo Corsi";
		$this->MsgDBConverted['insert_massive']['error']['1452'] = "Esiste gia questo elemento per il modulo Corsi";
		$this->MsgDBConverted['update_massive']['error']['1062'] = "Esiste gia questo elemento per il modulo Corsi";
		$this->MsgDBConverted['update_massive']['error']['1452'] = "Esiste gia questo elemento per il modulo Corsi";
		$this->MsgDBConverted['delete']['error']['1217'] = "Impossibile eliminare questo elemento del modulo Corsi. E' usato nei seguenti moduli:";
		$this->MsgDBConverted['delete_massive']['error']['1217'] = "Impossibile eliminare alcuni elementi del modulo Corsi. Sono usati nei seguenti moduli:";

		//NOTE:NELLA FUNZIONE 'setFormFields' INDICARE NEL VETTORE CHE SI COLLEGA ALLA TABELLA REFERENZIATA
		//ALLA CHIAVE 'NOME', IL NOMINATIVO DEL CAMPO COLLEGATO
		$this->setFormFields('data_a');
		$this->setFormFields('data_da');		
		$this->setFormFields('nome');
		//PER L'ARRAY DI REFERENZIAMENTO, USARE IL CONCAT PER CONCATENARE PIU CAMPI NEL CAMPO 'NOME'
		//ES.CONCAT(cognome," ",nome)
		$this->setFormFields('fk_affiliazione','mod_affiliazioni',array("id" => 'id', "nome" => 'nome'));
		//PER L'ARRAY DI REFERENZIAMENTO, USARE IL CONCAT PER CONCATENARE PIU CAMPI NEL CAMPO 'NOME'
		//ES.CONCAT(cognome," ",nome)
		$this->setFormFields('fk_disciplina','mod_discipline',array("id" => 'id', "nome" => 'nome'));
		$this->setFormFields('tipologia_corso');
		$this->setFormFields('importo_mensile');
		$this->setFormFields('id');

		$_SESSION['fast_ins_corsi'] = 'Y';	

		$this->addMasterDetailsLoadFunc('getMasterDetail_mod_anagrafica_corsi','Iscritti al Corso','getMasterDetail_mod_anagrafica_corsi');

		//ABILITARE PER LE OPERAZIONI "CUSTOM"

		//LA CHIAVE DEL VETTORE "custom_operations_list" RAPPRESENTA IL NOME DELLA FUNZIONE
		//QUESTO PER AVERE UN CODICE ORDINATO E PER EVITARE CHE LE FUNZIONI CUSTOM NON VENGANO RICHIAMATE CORRETTAMENTE
		$this->custom_operations_list['mod_corsi_check_date'] = function($request, $id = NULL){
			$ret = $this->utilities->check_date_greater_then($request['data_da'], $request['data_a']);
			if($ret === FALSE){
				$this->session->set_flashdata('error',"Data Da non puo essere maggiore di Data a");
				return false;
			}				
		}; 
		

		$this->addComboGridFilter(
			'mod_esercizi_id',
			'mod_esercizi',
			"id",
			"nome",
			"Filtra per Esercizio",
			array("filter_slave_id" => "fk_affiliazione", "filter_slave_population_function" => "populateAffiliazioni")
		);
		$this->addComboGridFilter('fk_affiliazione',NULL, NULL, NULL,"Filtra per Affiliazione");

 
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
		///print'<pre>';print_r($data);die();


		$this->global['pageTitle'] = $this->mod_title . ' - Lista';
		$this->loadViews($this->mod_name . '/' . $this->viewName_ListAjax, $this->global, $data, null);
	}



	/**
	 * Carica i dati per un form - MODIFICA
	 * @param mixed $id
	 * @param mixed|null $afterSave
	 */
	public function updateAjax($id, $afterSave = null, String $winForm = "FALSE", $validation_failed = false)
	{
		if(isset($_REQUEST['fast_ins_corsi']) && ($_REQUEST['fast_ins_corsi']) == 'Y'){	
			$_SESSION['fast_ins_corsi'] = 'Y';			
		} else {
			$_SESSION['fast_ins_corsi'] = 'N';
		}
				
		$this->pkIdValue = $id;

		$this->global['pageTitle'] = $this->mod_title . ' - Aggiornamento';
		$row = $this->modelClassModule->get_by_id($id);
		$formFieldsReferenced =  $this->getFormFieldsReferenced();
		$formFieldsReferencedTableRef =  $this->getFormFieldsReferencedTableRef();
		$fieldsArrayGrid = $this->modelClassModule->getFieldsArrayGrid();

		//ESEGUO EVENTUALI "CUSTOM RULES"
		foreach ($this->custom_rules_updateAjax as $functionKey => $function) {
			$function($id);
		}


		if ($row) {
			$data = array();
			foreach ($this->formFields as $key => $property) {
				$fieldType = $fieldsArrayGrid[$property]['type'];
				switch ($fieldType) {
					case FIELD_DATE:
						$data[$property] = $this->utilities->convertToDateIT($row->$property);
						break;

					case FIELD_DATETIME:
						//echo "prop:".$property;
						$data[$property] = $this->utilities->convertToDateTimeIT($row->$property);
						break;

					case FIELD_NUMERIC:
					case FIELD_STRING:
					case FIELD_FLOAT:
						$data[$property] = $row->$property;
						break;

					case FIELD_BLOB:
						$data[$property] = $row->$property;
						$data['nomeAllegatoBlob_' . $property] = $row->$property;
						break;

					case FIELD_BLOB_IMG:
						$data[$property] = base64_encode($row->$property);
						break;
				}
				if (in_array($property, $formFieldsReferenced)) {
					$arrayColumns = $this->getFormFieldsReferencedColumns($property);
					$where_condition = NULL;
					if (isset($arrayColumns['where_condition'])) {
						$where_condition =  $arrayColumns['where_condition'];
					}
					//echo $where_condition."<br>";
					$data[$property . '_refval'] = $this->modelClassModule->getValuesByFk($formFieldsReferencedTableRef[$property], $arrayColumns['id'], $arrayColumns['nome'], $where_condition);
				}
			}


			foreach ($this->masterDetailsLoadFuncList as $key => $masterDetailsLoadFunc) {
				//VERIFICO SE HA I PRIVILEGI SE NON AMMINISTRATORE
				/*
				if (!$this->isAdmin()) {
					$res = $this->modelClassModule->getPermissionRoleTabs($_SESSION['role'],$this->mod_name, $masterDetailsLoadFunc['function']);
					if((int)$res[0]['have_tab_perm'] > 0){
						$function = $masterDetailsLoadFunc['function'];
						$data['master_details_list'][] = array("title" => $masterDetailsLoadFunc['title'], "id" => $masterDetailsLoadFunc['id'], "function" => $this->$function($id));
					}
				} else {
					$function = $masterDetailsLoadFunc['function'];
					$data['master_details_list'][] = array("title" => $masterDetailsLoadFunc['title'], "id" => $masterDetailsLoadFunc['id'], "function" => $this->$function($id));
				}	
				*/
				//DISABILITATO AL MOMENTO CONTROLLO SE ADMIN O NO O SE HA I RELATIVI PERMESSI SUI TAB. DA IMPLEMENTARE IN FUTURO
				$function = $masterDetailsLoadFunc['function'];
				$data['master_details_list'][] = array("title" => $masterDetailsLoadFunc['title'], "id" => $masterDetailsLoadFunc['id'], "function" => $this->$function($id));				
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
	* Funzione caricamento della master details, tabella _mod_anagrafica_corsi
	* @param mixed $id
	* @param string $isAjax
	* @return string
	**/
	public function getMasterDetail_mod_anagrafica_corsi($id, $isAjax = 'FALSE'){
		$row =  $this->modelClassModule->getMasterDetail_mod_anagrafica_corsi($id, $isAjax);
		$html = '';
		$winFormType ="form";//VALORI ACCETTATI: {'multi','form'}
	
		if($isAjax == 'FALSE'){
			$html .= '
						<br><a class="btn btn-primary" style="cursor:pointer" onclick="winFormMasterDetails(\'mod_corsi_fast_ins\',\'winMasterDetail_mod_anagrafica_corsi\',\'insert\','.$id.',\'NULL\',\'Aggiungi tesserato a Corso\', arrayValidationFields,\'winMasterDetail_mod_anagrafica_corsi\',\'form\')">[ Aggiungi un elemento]</a> 
						<br><br>';
		 
		}
		$html .= ' <input  type="text" class="form-control" autocomplete="off" 
							id="search_mod_anagrafica_corsi" style="width:20%" placeholder="Cerca..."
							onkeypress="disableKeySubmit()"
							onkeyup="searchInMasterDetailsTable(\'search_mod_anagrafica_corsi\', \'tbl_mod_anagrafica_corsi\',4)"><br>';
		$html .= "<table class='TFtable' id='tbl_mod_anagrafica_corsi' style='font-size:12px'>
					<tr>
						<thead>
						<th class='sorting_disabled' rowspan='1' colspan='1' aria-label='' style='width:10%;'>
							<input type='checkbox' id='check_master_mod_anagrafica_corsi' name='check_master_mod_anagrafica_corsi' 
							onchange=\"selezionaDeselezionaTutti('check_master_mod_anagrafica_corsi','check_id_mod_anagrafica_corsi','btDeleteMass_mod_anagrafica_corsi')\">
						</th>";
		$html.='<th>Allievo</th>';
		$html.='<th>Data Iscrizione</th>';
		if($winFormType == "form"){
			$html.='<th>Modifica</th>';
		}
		$html.='<th>Elimina</th>';
		$html.='</tr>';
		$html.='<tbody>';
		//print'<pre>';print_r($row);
		foreach($row as $key => $value){
			$html.="<tr>";
			$html.="<td><input type='checkbox' id='check_id_mod_anagrafica_corsi' name='check_id_mod_anagrafica_corsi' value='".$value['id']."' onchange=\"verificaNrCheckBoxSelezionati('check_id_mod_anagrafica_corsi','btDeleteMass_mod_anagrafica_corsi')\"></td>";
			$html.="<td><input type='hidden' id='id[]' name='id[]' value='".$value['id']."'>".$value['mod_anagrafica_nome']."</td>";
			$html.="<td><input type='hidden' id='id[]' name='id[]' value='".$value['id']."'>".$this->utilities->convertToDateIT($value['data_iscrizione'])."</td>";
			if($winFormType == "form"){
				$html.="<td><a style='cursor:pointer' class='btn btn-sm btn-info' onclick ='winFormMasterDetails(\"mod_corsi_fast_ins\",\"winMasterDetail_mod_anagrafica_corsi\",\"edit\", $id,".$value['id'].",\"Modifica tesserato a Corso\",arrayValidationFields,\"winMasterDetail_mod_anagrafica_corsi\",\"form\")' title='Modifica Anagrafati Corsi'><i class='fa fa-edit'></a></td>";
			}
			$html.="<td><a style='cursor:pointer' class='btn btn-sm btn-danger deleteUser' onclick ='deleteMasterDetails(".$value['id'].", ".$id.", \"mod_corsi_fast_ins\",\"_mod_anagrafica_corsi\",\"getMasterDetail_mod_anagrafica_corsi\")' title='Elimina'><i class='fa fa-trash'></a></td>";
			$html.='</tr>';
		}
		$html.='</tbody></table>';
		$html.='<br/><a class="btn btn-sm btn-danger deleteUser" id="btDeleteMass_mod_anagrafica_corsi" name="btDeleteMass_mod_anagrafica_corsi""
					onclick="deleteMassiveMasterDetails('.$id.',\'entry_list\',\'check_id_mod_anagrafica_corsi\',\'mod_corsi_fast_ins\',\'_mod_anagrafica_corsi\',\'getMasterDetail_mod_anagrafica_corsi\')">
					<i class="fa fa-trash"></i> Cancellazione Massiva
				</a>';
		return $html;
	}



	/**
	* Funzione caricamento della finestra per la master details, tabella _mod_anagrafica_corsi
	* @param mixed $action
	* @param string $entryID
	* @param string $entryIDMasterDetails
	* @return string
	**/
	public function winMasterDetail_mod_anagrafica_corsi($action, $entryID, $entryIDMasterDetails = NULL){
		if($entryIDMasterDetails == 'NULL'){
			$entryIDMasterDetails = '';
		}
		$rowWinForm = $this->modelClassModule->get_from_master_details_by_id($entryIDMasterDetails, '_mod_anagrafica_corsi', 'id');
		$fk_tesseramento = "";
		if((isset($rowWinForm['fk_tesseramento'])) && ($rowWinForm['fk_tesseramento'] != "")){
			$fk_tesseramento = $rowWinForm['fk_tesseramento'];
		} 		
		//print'<pre>';print_r($rowWinForm);
		$html = '<div>
				<section class="content">
					<div class="row">
						<div class="col-md-12">
							<div class="box box-primary">
								<div class="box-body">
								<div id="msg_err" ondblclick="this.style.display=\'none\';" style="display: none; border: 1px solid; background-color: rgb(255, 64, 0); border-radius: 5px; padding: 10px; color: white; font-weight: bold;">
								</div>									
									<form  name="frm_master_detail" id="frm_master_detail">
									<input type="hidden" id="table" name="table" value="_mod_anagrafica_corsi">
									<input type="hidden" id="action" name="action" value="'.$action.'"/> 
									<input type="hidden" id="saveType" name="saveType" value="form"/> 	
									<input type="hidden" id="entryID"          name="entryID"  value="'.$entryID.'">
									<input type="hidden" id="fk_corso"          name="fk_corso"  value="'.$entryID.'">
									<input type="hidden" id="fk_tesseramento"          name="fk_tesseramento"  value="' . $fk_tesseramento . '">
									<input type="hidden" id="entryIDMasterDetails" 		name="entryIDMasterDetails" value="'.$entryIDMasterDetails.'" >															
										<div class="col-md-12">
											<div class="form-group">';
		$where_condition = " WHERE 1=1 ";
		if($action == 'insert'){
			$where_condition .= " AND id NOT IN ";
			$where_condition .= "(SELECT fk_anagrafica FROM _mod_anagrafica_corsi WHERE fk_corso = $entryID)";									
		}	
		$fk_affiliazione = $this->modelClassModule->getAffiliazioneByCorso($entryID);
		$where_condition .= " AND id IN(SELECT fk_anagrafica FROM mod_tesseramenti WHERE fk_affiliazione = $fk_affiliazione)";								
																
		$fk_anagrafica_refval = $this->modelClassModule->getValuesByFk('mod_anagrafica',NULL, NULL, $where_condition);
		//print'<pre>';print_r($fk_anagrafica_refval);
		$html .= '<div class="form-group">';
		 
		$fk_anagrafica_label = NULL;
		foreach ($fk_anagrafica_refval as $key => $value) {
			if ($value['id'] == $rowWinForm['fk_anagrafica']) {
				$fk_anagrafica_label = $value['nome']." ".$value['cognome']." - ".$value['codfiscale'];
			}  
		}
				 						
							
		$html .= '<label for="fk_anagrafica"><b style="color:#990000">(*)</b>Allievo </label>';
		$html .= ' <a style="cursor:pointer" onclick="pulisciCampo(\'fk_anagrafica_datalist_inp\')"><i class="fa fa-repeat" style="padding: 2px 4px;border:1px solid #ccc;"></i></a>';			
		$html .= ' &nbsp;&nbsp;<b style="font-size:12px;color:#990000">(saranno elencati solo i tesserati all\'affiliazione di riferimento)</b>';
		$html .= "							
							
		<input autofocus class='form-control' autocomplete='off' 
								oninput='onInput(\"fk_anagrafica_datalist_inp\",\"fk_anagrafica_datalist\",\"fk_anagrafica\")'
								onchange = 'getTesseramento()'
								style='width:100%;padding: 6px 12px;font-size:14px;
									border-top-right-radius:0px;border-bottom-right-radius:0px;
									border-top-left-radius:0px;border-bottom-left-radius:0px;
									border:1px solid #ccc'						
								list='fk_anagrafica_datalist'  
								name='fk_anagrafica_datalist_inp' 
								id='fk_anagrafica_datalist_inp' value='$fk_anagrafica_label'>
							
		<input type='hidden' name='fk_anagrafica' id='fk_anagrafica' value='".$rowWinForm['fk_anagrafica']."' >
 
							
		<datalist name='fk_anagrafica_datalist' id='fk_anagrafica_datalist' >";	
		$html .= '<OPTION VALUE></OPTION>';

		foreach ($fk_anagrafica_refval as $key => $value) {
			if ($value['id'] == $rowWinForm['fk_anagrafica']) {
				$html .= "<option data-value='" . $value['id'] . "' SELECTED>" . $value['nome']." ".$value['cognome'] ." - ".$value['codfiscale']."</option>";
			} else {
				$html .= "<option data-value='".$value['id'] . "'>" . $value['nome']." ".$value['cognome'] ." - ".$value['codfiscale']. "</option>";
			}
		}
		$html .= '</SELECT>';
										
		$html .= '</div>';
 
 
		$html .= '<div class="form-group">';
								
		$html .= '<label for="date"><b style="color:#990000">(*)</b>Data Iscrizione </label>';
									
		$html .= '<div class="input-group">';
									
		$html .= '<div class="input-group-addon"><i class="fa fa-calendar"></i></div>';			
									
		$html .= '<input type="text" class="form-control datemask" name="data_iscrizione" id="data_iscrizione" placeholder="Data Iscrizione"';
		
		if($rowWinForm['data_iscrizione'] == ""){
			$rowWinForm['data_iscrizione'] = date("Y-m-d");
		}
		if(!isset($rowWinForm['data_iscrizione'])){
			$rowWinForm['data_iscrizione'] = date("Y-m-d");
		}		
		$html .= 'autocomplete="off" style="background-color:#FFFFFF" value="'.$this->utilities->convertToDateIT($rowWinForm['data_iscrizione']).'" />';
									
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
	* Funzione caricamento della finestra per la master details,in modalita di inserimento multiplo, tabella _mod_anagrafica_corsi
	* @param mixed $action
	* @param string $entryID
	* @return string
	**/
	public function winMasterDetailMulti_mod_anagrafica_corsi($action, $entryID){
		$html = '<div>
				<section class="content">
					<div class="row">
						<div class="col-md-12">
							<div class="box box-primary">
								<div class="box-body">
								<div id="msg_err" ondblclick="this.style.display=\'none\';" style="display: none; border: 1px solid; background-color: rgb(255, 64, 0); border-radius: 5px; padding: 10px; color: white; font-weight: bold;">
								</div>									
									<form  name="frm_master_detail" id="frm_master_detail">
									<input type="hidden" id="table" name="table" value="_mod_anagrafica_corsi">
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
	 * 
	 * Salvo i dati della master details   - OVERWRITE
	 */
	public function saveMasterDetails()
	{

		$success = 'TRUE';
		$master_details_list = array();
		$html = "";
		$masterDetailsHtml = "";
		$action = $_REQUEST['action'];
		$entryID = $_REQUEST['entryID'];
		$entryIDMasterDetails = $_REQUEST['entryIDMasterDetails'];
		$saveType = $_REQUEST['saveType'];
		$data = array();
		$arrayFieldsLists = array();
		foreach ($_REQUEST as $key => $value) {
			if (strpos($key, 'saveMasterDetails') == false) {
				if (strpos($key, '_datalist_inp') == false) {
					if (!is_array($value)) {
						if ($this->utilities->valid_date($value) == TRUE) {
							$value = $this->utilities->convertToDateEN($value);
						}
					}

					$data[$key] = $value;
				}
			}
			if (is_array($value)) {
				$arrayFieldsLists[$key] = $key;
			}
		}

		foreach ($_FILES as $key => $file) {
			if ($file['tmp_name'] != "") {
				$data[$key] = @file_get_contents($file['tmp_name']);
				//PER QUESTA VERSIONE DO PER SCONTATO CHE ESISTE IL CAMPO "nome_documento"
				$data['nome_documento'] = $file['name'];
			}
		}

		unset($data['action']);
		unset($data['entryID']);
		unset($data['entryIDMasterDetails']);
		unset($data['saveType']);
 
		if ($action == 'insert') {
			if ($saveType == 'form') {
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
					
					if($data['table'] == '_mod_anagrafica_corsi'){
						$this->insertPagamentiDaCorsi($data);
					}					
				} else {
					$success = "FALSE";
					if (isset($this->MsgDBConverted["insert"]["error"][$ret['code']])) {
						$msg = "Errore :" . $ret['code'] . " - " . $this->MsgDBConverted["insert"]["error"][$ret['code']];
					} else {
						$msg = "Errore :" . $ret['code'] . " - " . $this->mysql_error_codes[$ret['code']]['message_template'];
					}
				}
				
			} else {
				//INSERIMENTO MULTI			
				foreach ($arrayFieldsLists as $keyArrayFieldList => $valArrayFieldList) {
					foreach ($data[$keyArrayFieldList] as $keyData => $valData) {
						$dataSave = $data;
						unset($dataSave[$keyArrayFieldList]);
						$dataSave[$keyArrayFieldList] = $valData;

						//INTERVENIRE PER GESTIRE L'ECCEZIONE MYSQL
						$ret = $this->modelClassModule->insert_master_details($dataSave);
						if (($ret['code'] == '0') || ($ret['code'] == 0)) {
							$msg = $this->MsgDBConverted["insert_massive"]["success"];
						} else {
							$success = "FALSE";
							if (isset($this->MsgDBConverted["insert_massive"]["error"][$ret['code']])) {
								$msg = "Errore :" . $ret['code'] . " - " . $this->MsgDBConverted["insert_massive"]["error"][$ret['code']];
							} else {
								$msg = "Errore :" . $ret['code'] . " - " . $this->mysql_error_codes[$ret['code']]['message_template'];
							}
						}
					}
				}

				if ($success == "TRUE") {
					$dataLog = array(
						'programma' => $this->mod_title,
						'utente' => $_SESSION['name'],
						'azione' => "INSERIMENTO MASSIVO MASTER DETAILS " . $action,
						'data' => date('Y-m-d H:i'),
					);
					$this->Common_model->insertLog($dataLog);				
				}
			}
		} else {
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
	 * OVERWRITE
	 * Cancella un record in una master details
	 * @param mixed $id_row_master_details
	 * @param mixed $id
	 * @param mixed $table
	 * @param mixed $masterDetailsLoadFunc
	 */
	public function delete_row_master_details($id_row_master_details, $id, $table)
	{
		$masterDetailsHtml = "";
		$html = "";
		$success = 'TRUE';
		if($table == '_mod_anagrafica_corsi'){
			$counter = $this->checkPagamentiEsistenti($id_row_master_details);
			if($counter > 0){
				$success = "FALSE";
				$masterDetailsHtml = $this->getAjaxMasterDetails($id);
				echo json_encode(array("success" => $success, "msg" => "Impossibile rimuovere allievo dal corso. Ci sono pagamenti collegati", "html" => $masterDetailsHtml));
				return false;
			}
		}	

		$ret = $this->modelClassModule->delete_row_master_details($id_row_master_details, $table);
		if (($ret['code'] == '0') || ($ret['code'] == 0)) {
			$msg = $this->MsgDBConverted["delete"]["success"];
			$dataLog = array(
				'programma' => $this->mod_title,
				'utente' => $_SESSION['name'],
				'azione' => "Cancellazione Master Details " . $table,
				'data' => date('Y-m-d H:i'),
			);
			$this->Common_model->insertLog($dataLog);

		
		} else {
			$success = "FALSE";
			if (isset($this->MsgDBConverted["delete"]["error"][$ret['code']])) {
				$msg = "Errore :" . $ret['code'] . " - " . $this->MsgDBConverted["delete"]["error"][$ret['code']];
			} else {
				$msg =  "Errore :" . $ret['code'] . " - " . $this->mysql_error_codes[$ret['code']]['message_template'];
			}
		}


		//RITORNO LE MASTER DETAILS PER IL FORM / RECORD
		$masterDetailsHtml = $this->getAjaxMasterDetails($id);

		echo json_encode(array("success" => $success, "msg" => $msg, "html" => $masterDetailsHtml));
	}




	/**
	 * 
	 * Cancellazione massiva records  - OVERWRITE
	 */
	public function delete_massive_master_details($id, $entry_list, $table)
	{
		$entryListArray = explode(",", $entry_list);
		$masterDetailsHtml = "";
		$html = "";
		$success = 'TRUE';

		if (is_array($entryListArray)) {
			if($table == '_mod_anagrafica_corsi'){
				$counter = $this->checkPagamentiEsistenti($entryListArray);
				if($counter > 0){
					$success = "FALSE";
					$masterDetailsHtml = $this->getAjaxMasterDetails($id);
					echo json_encode(array("success" => $success, "msg" => "Impossibile rimuovere allievo dal corso. Ci sono pagamenti collegati", "html" => $masterDetailsHtml));
					return false;
				}
			}	

			$ret = $this->modelClassModule->delete_massive_master_details($entryListArray, $table);
			if (($ret == '0') || ($ret == 0) || ($ret['code'] == 0) || ($ret['code'] == '0')) {
				$msg = $this->MsgDBConverted["delete_massive"]["success"];
				$dataLog = array(
					'programma' => $this->mod_title,
					'utente' => $_SESSION['name'],
					'azione' => "Cancellazione Massiva Master Details tabella :" . $table,
					'data' => date('Y-m-d H:i'),
				);
				$this->Common_model->insertLog($dataLog);
 				
			} else {
				$success = "FALSE";
				if (isset($this->MsgDBConverted["delete_massive"]["error"][$ret['code']])) {
					$msg = "Errore :" . $ret['code'] . " - " . $this->MsgDBConverted["delete_massive"]["error"][$ret['code']];
				} else {
					$msg = "Errore :" . $ret['code'] . " - " . $this->mysql_error_codes[$ret['code']]['message_template'];
				}
			}
		}


		//RITORNO LE MASTER DETAILS PER IL FORM / RECORD
		$masterDetailsHtml = $this->getAjaxMasterDetails($id);


		echo json_encode(array("success" => $success, "msg" => $msg, "html" => $masterDetailsHtml));
	}



	private function insertPagamentiDaCorsi($data){
		$this->modelClassModule->insertPagamentiDaCorsi($data);
	}


	public function insertPagamentiFromIscrittiCorsi(){
		return $this->modelClassModule->insertPagamentiFromIscrittiCorsi();
	}
	
	
	private function checkPagamentiEsistenti($ids){
		if(is_array($ids)){
			foreach($ids as $k => $id){
				$row = $this->modelClassModule->get_by_id($id, "_mod_anagrafica_corsi");
				$counter = $this->modelClassModule->getCountPagamenti($row->fk_corso,$row->fk_anagrafica);
				if($counter > 0){
					break;
				}
			}
		} else {
			$row = $this->modelClassModule->get_by_id($ids, "_mod_anagrafica_corsi");
			$counter = $this->modelClassModule->getCountPagamenti($row->fk_corso,$row->fk_anagrafica);
		}

		
		return $counter;
	}

	
	/**
	 * Verifica se le date rientrano nell'esericizio
	 */
	public function check_date_range_esercizio($idCorso, $dataDa, $dataA)
	{
		$ret = $this->modelClassModule->check_date_range_esercizio($idCorso, $dataDa, $dataA);

		return $ret;
	}


	public function populateAffiliazioni()
	{
		echo json_encode($this->modelClassModule->populateAffiliazioni($_REQUEST['filter_master_value']));
	}



	public function getTesseramento()
	{
		echo json_encode($this->modelClassModule->getTesseramento($_REQUEST['fk_anagrafica'],$_REQUEST['fk_corso']));
	}


	private function getEsercizioCorrente()
	{
		return $this->modelClassModule->getEsercizioCorrente();
	}


	public function checkDataIscrizione(){
		$ret = $this->modelClassModule->checkDataIscrizione($_REQUEST['data_iscrizione'],$_REQUEST['fk_affiliazione']);

		echo json_encode(array("ret" => $ret));
	}


	public function getDateEsercizio($fk_affiliazione){
		$dateEsercizio = $this->modelClassModule->getDateEsercizio($fk_affiliazione);

		echo json_encode($dateEsercizio);
	}



	public function cp_corsi()
	{
		$arrayReturn = ["success" => "ok"];
		//$idEserc = $_POST['mod_esercizi_id_copy'];
		$idAffiliaz = $_POST['fk_affiliazione_copy'];
		$lista_corsi = $_POST['entry_list_cp_course'];
		$res = $this->modelClassModule->copyCourses($idAffiliaz, $lista_corsi);

		$arrayReturn["success"] = $res;
		echo json_encode($arrayReturn);
	}


	public function _rules()
	{
		$this->form_validation->set_rules('data_a', 'data a', 'trim|required');
		$this->form_validation->set_rules('data_da', 'data da', 'trim|required');		
		$this->form_validation->set_rules('nome', 'nome corso', 'trim|max_length[50]|required');
		$this->form_validation->set_rules('fk_affiliazione', 'affiliazione', 'trim|numeric|max_length[10]|required');
		$this->form_validation->set_rules('fk_disciplina', 'disciplina', 'trim|numeric|max_length[10]|required');
		$this->form_validation->set_rules('tipologia_corso', 'tipo corso', 'trim|required');
		$this->form_validation->set_rules('importo_mensile', 'importo', 'trim|numeric|max_length[12]|required');

		$this->form_validation->set_rules('id', 'id', 'trim');
		$this->form_validation->set_error_delimiters('<span class="text-danger">', '</span>');

	}

}