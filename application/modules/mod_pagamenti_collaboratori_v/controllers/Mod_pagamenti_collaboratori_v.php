<?php
if (!defined('BASEPATH')) {
	exit('No direct script access allowed');
}
require APPPATH . '/libraries/BaseController.php';

use Dompdf\Dompdf;

class Mod_pagamenti_collaboratori_v extends BaseController
{

	public function __construct()
	{
		parent::__construct();
		$email = $this->config->item('email');
		$this->load->library('email', $email);

		$this->load->model('Mod_pagamenti_collaboratori_v_model');
		$this->mod_name = 'mod_pagamenti_collaboratori_v';
		$this->mod_type = 'crud';
		$this->mod_title = 'Pagamenti Collaboratori';
		$this->modelClassModule =  $this->Mod_pagamenti_collaboratori_v_model;
		$this->pkIdName = 'id';
		$this->viewName_ListAjax = 'mod_pagamenti_collaboratori_v_list_ajax';
		$this->viewName_FormROAjax = 'mod_pagamenti_collaboratori_v_read_ajax';
		$this->viewName_FormAjax = 'mod_pagamenti_collaboratori_v_form_ajax';

		$this->MsgDBConverted['insert']['error']['1062'] = "Esiste gia questo elemento per il modulo Pagamenti Collaboratori";
		$this->MsgDBConverted['insert']['error']['1452'] = "Esiste gia questo elemento per il modulo Pagamenti Collaboratori";
		$this->MsgDBConverted['update']['error']['1062'] = "Esiste gia questo elemento per il modulo Pagamenti Collaboratori";
		$this->MsgDBConverted['update']['error']['1452'] = "Esiste gia questo elemento per il modulo Pagamenti Collaboratori";
		$this->MsgDBConverted['insert_massive']['error']['1062'] = "Esiste gia questo elemento per il modulo Pagamenti Collaboratori";
		$this->MsgDBConverted['insert_massive']['error']['1452'] = "Esiste gia questo elemento per il modulo Pagamenti Collaboratori";
		$this->MsgDBConverted['update_massive']['error']['1062'] = "Esiste gia questo elemento per il modulo Pagamenti Collaboratori";
		$this->MsgDBConverted['update_massive']['error']['1452'] = "Esiste gia questo elemento per il modulo Pagamenti Collaboratori";
		$this->MsgDBConverted['delete']['error']['1217'] = "Impossibile eliminare questo elemento del modulo Pagamenti Collaboratori. E' usato nei seguenti moduli:";
		$this->MsgDBConverted['delete_massive']['error']['1217'] = "Impossibile eliminare alcuni elementi del modulo Pagamenti Collaboratori. Sono usati nei seguenti moduli:";

		//NOTE:NELLA FUNZIONE 'setFormFields' INDICARE NEL VETTORE CHE SI COLLEGA ALLA TABELLA REFERENZIATA
		//ALLA CHIAVE 'NOME', IL NOMINATIVO DEL CAMPO COLLEGATO
		$this->setFormFields('collaboratore');
		$this->setFormFields('id');

		$this->setFormFields('importo');
		$this->setFormFields('fk_tipopagamento');
		$this->setFormFields('fk_anagrafica');
		$this->setFormFields('fk_causale_pagamento');
		$this->setFormFields('notepagamento');
		$this->setFormFields('contratto_id');
		$this->setFormFields('contratto_nome');


		$this->addMasterDetailsLoadFunc('getMasterDetail_mod_pagamenti_collaboratori', 'Pagamenti', 'getMasterDetail_mod_pagamenti_collaboratori');
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
		$winFormType ="form";
	
		if($isAjax == 'FALSE'){
			$html .= '
						<br><a class="btn btn-primary" style="cursor:pointer" onclick="winFormMasterDetails(\'mod_pagamenti_collaboratori_v\',\'winMasterDetail_mod_pagamenti_collaboratori\',\'insert\','.$id.',\'NULL\',\'Aggiungi Pagamento a Contratto\', arrayValidationFields,\'winMasterDetail_mod_pagamenti_collaboratori\',\'form\')">[ Aggiungi un elemento]</a> 
						<br><br>';
		}
		$html .= ' <input  type="text" class="form-control" autocomplete="off" 
							id="search_mod_pagamenti_collaboratori" style="width:20%" placeholder="Cerca..."
							onkeypress="disableKeySubmit()"
							onkeyup="searchInMasterDetailsTable(\'search_mod_pagamenti_collaboratori\', \'tbl_mod_pagamenti_collaboratori\',4)"><br>';
		$html .= "<table class='TFtable' id='tbl_mod_pagamenti_collaboratori' style='font-size:12px'>
					<tr>
						<thead>
						<th class='sorting_disabled' rowspan='1' colspan='1' aria-label='' style='width:10%;'>
							<input type='checkbox' id='check_master_mod_pagamenti_collaboratori' name='check_master_mod_pagamenti_collaboratori' 
							onchange=\"selezionaDeselezionaTutti('check_master_mod_pagamenti_collaboratori','check_id_mod_pagamenti_collaboratori','btDeleteMass_mod_pagamenti_collaboratori')\">
						</th>";
		$html.='<th>Mese</th>';
		$html.='<th>Anno</th>';
		$html.='<th>Data Pagamento</th>';
		//$html.='<th>Ora Pagamento</th>';
		$html.='<th>Importo</th>';
		$html.='<th>Tipo Pagamento</th>';
		$html.='<th>Causale Pagamento</th>';
		$html.='<th>Invia</th>';
		$html.='<th>Stampa</th>';
		if($winFormType == "form"){
			$html.='<th>Modifica</th>';
		}
		$html.='<th>Elimina</th>';
		$html.='</tr>';
		$html.='<tbody>';
		//print'<pre>';print_r($row);
		foreach($row as $key => $value){
			$html.="<tr>";
			$html.="<td><input type='checkbox' id='check_id_mod_pagamenti_collaboratori' name='check_id_mod_pagamenti_collaboratori' value='".$value['id']."' onchange=\"verificaNrCheckBoxSelezionati('check_id_mod_pagamenti_collaboratori','btDeleteMass_mod_pagamenti_collaboratori')\"></td>";
			$html.="<td><input type='hidden' id='id[]' name='id[]' value='".$value['id']."'>".$value['mese']."</td>";
			$html.="<td><input type='hidden' id='id[]' name='id[]' value='".$value['id']."'>".$value['anno']."</td>";
			$html.="<td><input type='hidden' id='id[]' name='id[]' value='".$value['id']."'>".$this->utilities->convertToDateIT($value['datapagamento'])."</td>";
			//$html.="<td><input type='hidden' id='id[]' name='id[]' value='".$value['id']."'>".$value['ora_pagamento']."</td>";
			$html.="<td><input type='hidden' id='id[]' name='id[]' value='".$value['id']."'>€ ".str_replace(".",",",$value['importo']) ."</td>";
			$html.="<td><input type='hidden' id='id[]' name='id[]' value='".$value['id']."'>".$value['tipo_pagamento']."</td>";
			$html.="<td><input type='hidden' id='id[]' name='id[]' value='".$value['id']."'>".$value['causale_pagamento']."</td>";
			$disabled = "";
			if($value['email'] == ''){
				$disabled = "disabled";
			}
			$html.="<td><a onclick=\"sendMailWithAttach('mod_pagamenti_collaboratori_v','".$value['email']."',".$value['id'].",'Invio Ricevuta Compenso')\" style='cursor:pointer' class='btn btn-sm btn-primary'  $disabled  title='Modifica Anagrafati Corsi'><i class='fa fa-at'></a></td>";
			$html.="<td><a href='mod_pagamenti_collaboratori_v/stampa/".$value['id']."' target='_blank' style='cursor:pointer' class='btn btn-sm btn-warning'  title='Modifica Anagrafati Corsi'><i class='fa fa-print'></a></td>";

			if($winFormType == "form"){
				$html.="<td><a style='cursor:pointer' class='btn btn-sm btn-info' onclick ='winFormMasterDetails(\"mod_pagamenti_collaboratori_v\",\"winMasterDetail_mod_pagamenti_collaboratori\",\"edit\", $id,".$value['id'].",\"Modifica Pagamento Collaboratore\",arrayValidationFields,\"winMasterDetail_mod_pagamenti_collaboratori\",\"form\")' title='Modifica Pagamento Collaboratore'><i class='fa fa-edit'></a></td>";
			}
			$html.="<td><a style='cursor:pointer' class='btn btn-sm btn-danger deleteUser' onclick ='deleteMasterDetails(".$value['id'].", ".$id.", \"mod_pagamenti_collaboratori_v\",\"_mod_pagamenti_collaboratori\",\"getMasterDetail_mod_pagamenti_collaboratori\")' title='Elimina'><i class='fa fa-trash'></a></td>";
			$html.='</tr>';
		}
		$html.='</tbody></table>';
		$html.='<br/><a class="btn btn-sm btn-danger deleteUser" id="btDeleteMass_mod_pagamenti_collaboratori" name="btDeleteMass_mod_pagamenti_collaboratori""
					onclick="deleteMassiveMasterDetails('.$id.',\'entry_list\',\'check_id_mod_pagamenti_collaboratori\',\'mod_pagamenti_collaboratori_v\',\'_mod_pagamenti_collaboratori\',\'getMasterDetail_mod_pagamenti_collaboratori\')">
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
	public function winMasterDetail_mod_pagamenti_collaboratori($action, $entryID, $entryIDMasterDetails = NULL){
		if($entryIDMasterDetails == 'NULL'){
			$entryIDMasterDetails = '';
		}
		$rowWinForm = $this->modelClassModule->get_from_master_details_by_id($entryIDMasterDetails, '_mod_pagamenti_collaboratori', 'id');
		//print'<pre>';print_r($rowWinForm);die();
		
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
										<input type="hidden" id="entryIDMasterDetails" name="entryIDMasterDetails" value="'.$entryIDMasterDetails.'" >															
										<div class="col-md-12">
											<div class="form-group">';
 
		$listaMesiAnni = $this->modelClassModule->getListaMesiAnniDaPagare($entryID);


		$html .= '<div class="col-md-6"><div class="form-group">';	
		$html .= '<label for="date"><b style="color:#990000">(*)</b>Mese - Anno </label>';							
		$html .= '<div class="input-group">';							
		$html .= '<div class="input-group-addon"><i class="fa fa-text-height"></i></div>';	
		
		if ($this->isAdmin() == TRUE) {
			$html .= '<SELECT class="form-control" id="mese_anno" name="mese_anno">';
			$html .= '<OPTION value=""></OPTION>';
			$selected = "";
			foreach($listaMesiAnni['mese_anno_ita'] as $k => $v){

				if($v == $rowWinForm['mese']."-".$rowWinForm['anno']){
					$selected = "selected";
				} else {
					$selected = "";
				}
				$html .= '<option value="'.$v.'" '.$selected.'>'.$v.'</option>';
			}		
			$html .= '</SELECT>';	
		} else {
			$html .= '<input type="text" readonly="readonly" class="form-control" id="anno" name="anno" value="'.$rowWinForm['anno'].'"> ';
		}
		$html .= '</div></div></div>';	

		$html .= '<div class="col-md-6"><div class="form-group">';	
		$html .= '<label for="date"><b style="color:#990000">(*)</b>Data Pagamento </label>';							
		$html .= '<div class="input-group">';							
		$html .= '<div class="input-group-addon"><i class="fa fa-calendar"></i></div>';										
		$html .= '<input type="text" class="form-control datemask" name="datapagamento" id="datapagamento" placeholder="Data Pagamento"';
		
		if($rowWinForm['datapagamento'] == ""){
			$rowWinForm['datapagamento'] = date("Y-m-d");
		}
		if(!isset($rowWinForm['datapagamento'])){
			$rowWinForm['datapagamento'] = date("Y-m-d");
		}		
		$html .= 'autocomplete="off" style="background-color:#FFFFFF" value="'.$this->utilities->convertToDateIT($rowWinForm['datapagamento']).'" />';
									
		$html .= '</div></div></div>';


		$html .= '<div class="col-md-6"><div class="form-group">';	
		$html .= '<label for="date"><b style="color:#990000">(*)</b>Importo </label>';							
		$html .= '<div class="input-group">';							
		$html .= '<div class="input-group-addon"><i class="fa fa-sort-numeric-asc"></i></div>';										
		$html .= '<input type="number" class="form-control" name="importo" id="importo" placeholder="Importo"';
		$html .= 'autocomplete="off" style="background-color:#FFFFFF" value="'.$rowWinForm['importo'].'" />';				
		$html .= '</div></div></div>';		
		

		$fk_tipopagamento_refval = $this->modelClassModule->getValuesByFk('mod_tipopagamento');
		$html .= '<div class="col-md-6"><div class="form-group">';	
		$html .= '<label for="date"><b style="color:#990000">(*)</b>Tipo Pagamento </label>';							
		$html .= '<div class="input-group">';							
		$html .= '<div class="input-group-addon"><i class="fa fa-sort-numeric-asc"></i></div>';										
		$html .= '<SELECT class="form-control" id="fk_tipopagamento" name="fk_tipopagamento">';
		foreach($fk_tipopagamento_refval as $k => $v){
			if($rowWinForm['fk_tipopagamento'] == ""){
				//IMPOSTO COME DEFAULT IL PAGAMENTO IN CONTANTI
				if($v['predefinito'] == 'SI'){
					$html .= '<option value="'.$v['id'].'" SELECTED>'.$v['nome'].'</option>';
				} else {
					$html .= '<option value="'.$v['id'].'">'.$v['nome'].'</option>';
				}
			} else {
				if($rowWinForm['fk_tipopagamento'] == $v['id']){
					$html .= '<option value="'.$v['id'].'" SELECTED>'.$v['nome'].'</option>';
				} else {
					$html .= '<option value="'.$v['id'].'">'.$v['nome'].'</option>';
				}
			}

		}
		$html .= '</SELECT>';	
		$html .= '</div></div></div>';		



		$fk_causale_pagamento_refval = $this->modelClassModule->getValuesByFk('mod_causali_pagamento');
		$html .= '<div class="col-md-6"><div class="form-group">';	
		$html .= '<label for="date"><b style="color:#990000">(*)</b>Causale Pagamento </label>';							
		$html .= '<div class="input-group">';							
		$html .= '<div class="input-group-addon"><i class="fa fa-sort-numeric-asc"></i></div>';										
		$html .= '<SELECT class="form-control" id="fk_causale_pagamento" name="fk_causale_pagamento">';
		$html .= '<OPTION value=""></OPTION>';
		foreach($fk_causale_pagamento_refval as $k => $v){
 
			if(($v['nome'] == 'ACCONTO') || ($v['nome'] == 'PAGAMENTO COLLABORATORE GENERICO') || ($v['nome'] == 'PAGAMENTO COLLABORATORE CORSO')){
				if($rowWinForm['fk_causale_pagamento'] == $v['id']){
					$html .= '<option value="'.$v['id'].'"  SELECTED>'.$v['nome'].'</option>';
				} else {
					$html .= '<option value="'.$v['id'].'"   >'.$v['nome'].'</option>';
				}
			}


		}
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

		$html .= '<div class="col-md-6"><div class="form-group">';	
		$html .= '<label for="date">Note giorni lavorati</label>';							
		$html .= '<div class="input-group">';							
		$html .= '<div class="input-group-addon"><i class="fa fa-text-height"></i></div>';										
		$html .= '<textarea class="form-control" id="note_giorni_lavorati" name="note_giorni_lavorati">';
		$html .= $rowWinForm['note_giorni_lavorati'];
		$html .= '</textarea>';
		$html .= '</div></div></div>';			
		
 
		if(($rowWinForm['anno_730'] == "") ||($rowWinForm['anno_730'] == "0")){
			$rowWinForm['anno_730'] = date('Y');
		}
		$html .= '<div class="col-md-6"><div class="form-group">';	
		$html .= '<label for="date"><b style="color:#990000">(*)</b>Anno 730 </label>';							
		$html .= '<div class="input-group">';							
		$html .= '<div class="input-group-addon"><i class="fa fa-sort-numeric-asc"></i></div>';										
		$html .= '<input type="number" class="form-control" name="anno_730" id="anno_730" placeholder="Anno 730"';
		$html .= 'autocomplete="off" style="background-color:#FFFFFF" value="'.$rowWinForm['anno_730'].'" />';				
		$html .= '</div></div></div>';		


		$html .= '<div class="col-md-6"><div class="form-group">';	
		$html .= '<label for="date"><b style="color:#990000">(*)</b>Totale Altri redditi Anno 730 </label>';							
		$html .= '<div class="input-group">';							
		$html .= '<div class="input-group-addon"><i class="fa fa-sort-numeric-asc"></i></div>';										
		$html .= '<input type="number" class="form-control" name="tot_redditi_anno_730" id="tot_redditi_anno_730" placeholder="Totale redditi anno 730"';
		if($rowWinForm['tot_redditi_anno_730'] == ""){
			$rowWinForm['tot_redditi_anno_730'] = 0;
		}
		$html .= 'autocomplete="off" style="background-color:#FFFFFF" value="'.$rowWinForm['tot_redditi_anno_730'].'" />';				
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

		$meseAnnoArray = explode("-",$_REQUEST['mese_anno']);
		$data['fk_contratto'] = $_REQUEST['fk_contratto'];
		$data['datapagamento'] = $this->utilities->convertToDateEN($_REQUEST['datapagamento']);
		$data['mese'] = $meseAnnoArray[0];
		$data['anno'] = $meseAnnoArray[1];
		$data['importo'] = $_REQUEST['importo'];
		$data['fk_tipopagamento'] = $_REQUEST['fk_tipopagamento'];
		$data['fk_causale_pagamento'] = $_REQUEST['fk_causale_pagamento'];
		$data['notepagamento'] = $_REQUEST['notepagamento'];
		$data['note_giorni_lavorati'] = $_REQUEST['note_giorni_lavorati'];
		$data['anno_730'] = $_REQUEST['anno_730'];
		$data['tot_redditi_anno_730'] = $_REQUEST['tot_redditi_anno_730'];
		$data['table'] = $_REQUEST['table'];

		if ($action == 'insert') {
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

		$row = $this->modelClassModule->getDettagliRicevuta($id);
		//print'<pre>';print_r($row);die();
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
			$view = $this->load->view($this->mod_name . '/ricevute', $data, true);
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
		$this->form_validation->set_rules('collaboratore', '', 'trim|max_length[120]');
		$this->form_validation->set_rules('id', '', 'trim|numeric|max_length[10]');
		$this->form_validation->set_rules('fk_contratto', 'contratto', 'trim|numeric|max_length[10]');
		$this->form_validation->set_rules('datapagamento', 'data pagamento', 'trim');
		$this->form_validation->set_rules('ora_pagamento', 'ora', 'trim');
		$this->form_validation->set_rules('importo', 'importo', 'trim|numeric|max_length[10]');
		$this->form_validation->set_rules('fk_tipopagamento', 'tipo pagamento', 'trim|numeric|max_length[10]');
		$this->form_validation->set_rules('fk_causale_pagamento', 'causale pagamento', 'trim|numeric|max_length[10]');
		$this->form_validation->set_rules('notepagamento', 'note', 'trim|max_length[4294967295]');
		$this->form_validation->set_rules('contratto_id', '', 'trim|numeric|max_length[10]|required');
		$this->form_validation->set_rules('contratto_nome', 'nome contratto', 'trim|max_length[50]|required');

		$this->form_validation->set_rules('', '', 'trim');
		$this->form_validation->set_error_delimiters('<span class="text-danger">', '</span>');
	}
}
