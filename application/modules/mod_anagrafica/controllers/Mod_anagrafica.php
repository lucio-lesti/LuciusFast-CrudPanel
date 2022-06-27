<?php
if (!defined('BASEPATH')) {
	exit('No direct script access allowed');
}
require APPPATH . '/libraries/BaseController.php';

use Dompdf\Dompdf;

class Mod_anagrafica extends BaseController
{

	public function __construct()
	{

		parent::__construct();
		$this->load->model('Mod_anagrafica_model');
		$this->mod_name = 'mod_anagrafica';
		$this->mod_title = 'Anagrafica';
		$this->modelClassModule =  $this->Mod_anagrafica_model;
		$this->pkIdName = 'id';
		$this->viewName_ListAjax = 'mod_anagrafica_list_ajax';
		$this->viewName_FormROAjax = 'mod_anagrafica_read_ajax';
		$this->viewName_FormAjax = 'mod_anagrafica_form_ajax';

		$this->MsgDBConverted['insert']['error']['1062'] = "Esiste gia questo elemento per il modulo Anagrafica";
		$this->MsgDBConverted['insert']['error']['1452'] = "Esiste gia questo elemento per il modulo Anagrafica";
		$this->MsgDBConverted['update']['error']['1062'] = "Esiste gia questo elemento per il modulo Anagrafica";
		$this->MsgDBConverted['update']['error']['1452'] = "Esiste gia questo elemento per il modulo Anagrafica";
		$this->MsgDBConverted['insert_massive']['error']['1062'] = "Esiste gia questo elemento per il modulo Anagrafica";
		$this->MsgDBConverted['insert_massive']['error']['1452'] = "Esiste gia questo elemento per il modulo Anagrafica";
		$this->MsgDBConverted['update_massive']['error']['1062'] = "Esiste gia questo elemento per il modulo Anagrafica";
		$this->MsgDBConverted['update_massive']['error']['1452'] = "Esiste gia questo elemento per il modulo Anagrafica";
		//$this->MsgDBConverted['delete']['error']['1217'] = "Impossibile eliminare questo elemento del modulo Anagrafica. E' usato nei seguenti moduli:";
		//$this->MsgDBConverted['delete_massive']['error']['1217'] = "Impossibile eliminare alcuni elementi del modulo Anagrafica. Sono usati nei seguenti moduli:";

		//NOTE:NELLA FUNZIONE 'setFormFields' INDICARE NEL VETTORE CHE SI COLLEGA ALLA TABELLA REFERENZIATA
		//ALLA CHIAVE 'NOME', IL NOMINATIVO DEL CAMPO COLLEGATO
		$this->setFormFields('anagrafica_attributo');
		$this->setFormFields('cellulare');
		$this->setFormFields('codfiscale');
		$this->setFormFields('cognome');
		$this->setFormFields('datanascita');
		$this->setFormFields('doc_domanda_ammissione_socio');
		$this->setFormFields('documento');
		$this->setFormFields('nome_documento');
		$this->setFormFields('email');
		$this->setFormFields('nr_documento');
		$this->setFormFields('tipo_documento');		
		$this->setFormFields('fk_comune_nascita', 'mod_comuni', array("id" => 'istat', "nome" => 'comune'));
		$this->setFormFields('fk_comune_residenza', 'mod_comuni', array("id" => 'istat', "nome" => 'comune'));
		$this->setFormFields('firma');
		$this->setFormFields('nome_img_firma');

		//NON SI PUO ESSERE TUTORI DI SE STESSO
		$where_condition = " WHERE TIMESTAMPDIFF(YEAR, datanascita, CURDATE()) >= 18";
		if ($this->pkIdValue != NULL) {
			$where_condition .= " AND id <> " . $this->pkIdValue;
		}
		$this->setFormFields('fk_tutore', 'mod_anagrafica', array("id" => 'id', "nome" => 'CONCAT(nome," ",cognome," ", codfiscale)'), $where_condition); 

		$this->setFormFields('img_foto');
		$this->setFormFields('indirizzo');
		$this->setFormFields('nome');
		$this->setFormFields('notetesto');
		$this->setFormFields('sesso');
		$this->setFormFields('sottoposto_regime_green_pass');
		$this->setFormFields('telefono');
		$this->setFormFields('id');

		
		//RICHIAMO LE FUNZIONI PER LA CREAZIONI DELLE MASTER DETAILS
		$this->addMasterDetailsLoadFunc('getMasterDetail_mod_anagrafica_certificati_medici', 'Certificati medici', 'getMasterDetail_mod_anagrafica_certificati_medici');
		$this->addMasterDetailsLoadFunc('getMasterDetail_mod_anagrafica_corsi', 'Corsi Frequentati da alunno', 'getMasterDetail_mod_anagrafica_corsi');
		$this->addMasterDetailsLoadFunc('getMasterDetail_mod_anagrafica_tessere_assoc', 'Storico Tessere', 'getMasterDetail_mod_anagrafica_tessere_assoc');
 

		
		/*** FUNZIONI LAMBDA RICHIAMATE NEI METODI createAjax, updateAjax, AL SALVATAGGIO DEL FORM  - INIZIO **/
		 

		//VERIFICO SE LA PERSONA SIA MINORENNE
		$this->custom_operations_list['check_minorenne'] = function ($request, $id = NULL) {
			$ret = $this->utilities->check_minorenne($request['datanascita']);
			if ($ret === TRUE) {
				if((trim($_REQUEST['fk_tutore']) == "") || ((string)$_REQUEST['fk_tutore'] == "0")){
					$this->session->set_flashdata('error', "Persona minorenne. Selezionare  Tutore.");
					return false;
				} 
			} 
		};


		//PRELEVO IL NOME DEL DOCUMENTO CARICATO
		$this->custom_operations_list['get_nome_documento'] = function ($request, $id = NULL) {
			$_POST['nome_documento'] = $_FILES['documento']['name'];
		};
		


		//PRELEVO LA FOTO DEL PROFILO
		$this->custom_operations_list['get_foto'] = function ($request, $id = NULL) {
			if( !is_uploaded_file($_FILES['img_foto']['tmp_name'])) {
				if($_REQUEST['img_foto_hidden'] != ""){
					$this->utilities->saveBase64Image('data:image/png;base64,'.$_REQUEST['img_foto_hidden'],FCPATH.'uploads/anagrafiche',"img_".$id.".jpg");
					
					$new_buffer = "data:image/jpeg;base64,".base64_encode($this->utilities->file_get_contents_ssl(base_url() . 'uploads/anagrafiche/img_'.$id.'.jpg'));
 					$file_info = new finfo(FILEINFO_MIME_TYPE);
					$mime_type = $file_info->buffer($new_buffer);					
					$_FILES['img_foto']['name'] = "edit_user_icon.png";
					$_FILES['img_foto']['type'] = $mime_type;
					$_FILES['img_foto']['tmp_name'] = $new_buffer;
					$_FILES['img_foto']['error'] ="0";
					$_FILES['img_foto']['size'] = strlen($new_buffer);

					unlink(FCPATH.'uploads/anagrafiche/img_'.$id.'.jpg');
				} else {
 
					$new_buffer = "data:image/jpeg;base64,".base64_encode($this->utilities->file_get_contents_ssl(base_url() . 'assets/images/edit_user_icon.png'));
 					$file_info = new finfo(FILEINFO_MIME_TYPE);
					$mime_type = $file_info->buffer($new_buffer);					
					$_FILES['img_foto']['name'] = "edit_user_icon.png";
					$_FILES['img_foto']['type'] = $mime_type;
					$_FILES['img_foto']['tmp_name'] = $new_buffer;
					$_FILES['img_foto']['error'] ="0";
					$_FILES['img_foto']['size'] = strlen($new_buffer);

				}
			}	
 
		};
	

		//PRELEVO LA FIRMA AL SALVATAGGIO DEL FORM
		$this->custom_operations_list['get_firma'] = function ($request, $id = NULL) {
			if( !is_uploaded_file($_FILES['firma']['tmp_name'])) {
				if($_REQUEST['firma_hidden'] != ""){
					$this->utilities->saveBase64Image('data:image/png;base64,'.$_REQUEST['firma_hidden'],FCPATH.'uploads/anagrafiche',"firma_".$id.".jpg");
					
					$new_buffer = "data:image/jpeg;base64,".base64_encode($this->utilities->file_get_contents_ssl(base_url() . 'uploads/anagrafiche/firma_'.$id.'.jpg'));
 					$file_info = new finfo(FILEINFO_MIME_TYPE);
					$mime_type = $file_info->buffer($new_buffer);					
					$_FILES['firma']['name'] = "firma_".$id.".jpg";
					$_FILES['firma']['type'] = $mime_type;
					$_FILES['firma']['tmp_name'] = $new_buffer;
					$_FILES['firma']['error'] ="0";
					$_FILES['firma']['size'] = strlen($new_buffer);
					$_REQUEST['nome_img_firma'] = "firma_".$id.".jpg";
					$_POST['nome_img_firma'] = "firma_".$id.".jpg";

					unlink(FCPATH.'uploads/anagrafiche/firma_'.$id.'.jpg');
				} else {
					$new_buffer = "data:image/jpeg;base64,".base64_encode($this->utilities->file_get_contents_ssl(base_url() . 'assets/images/firma_default.png'));
 					$file_info = new finfo(FILEINFO_MIME_TYPE);
					$mime_type = $file_info->buffer($new_buffer);					
					$_FILES['firma']['name'] = "firma_default.png";
					$_FILES['firma']['type'] = $mime_type;
					$_FILES['firma']['tmp_name'] = $new_buffer;
					$_FILES['firma']['error'] ="0";
					$_FILES['firma']['size'] = strlen($new_buffer);
					$_REQUEST['nome_img_firma'] = "firma_default.png";
					$_POST['nome_img_firma'] = "firma_default.png";
				}  
			} else {
				$_REQUEST['nome_img_firma'] = "firma_".$id.".jpg";
				$_POST['nome_img_firma'] = "firma_".$id.".jpg";
			} 
 
		};
		
		
		//IN BASE AGLI ATTRIBUTI, SE NON ESENTANTO AGGIUNGI I TAB PER IL GREEN PASS
		$this->custom_rules_updateAjax['check_attributi'] = function ($id = NULL) {
			if((isset($_REQUEST['recordID'])) || (isset($_REQUEST['id'])) || (isset($_REQUEST['entryID']))){
				if(isset($_REQUEST['recordID'])){
					$idAnagrafica = $_REQUEST['recordID'];
				} elseif(isset($_REQUEST['entryID'])){
					$idAnagrafica = $_REQUEST['entryID'];
				} else {
					if($_REQUEST['id'] != ""){
						$idAnagrafica = $_REQUEST['id'];
					} else {
						$idAnagrafica = $id;
					}
					
				}

				$sottopostoRegimeGP = $this->checkSottopostoRegimeGP($idAnagrafica);
				if(($sottopostoRegimeGP == 'SI') ){
					$this->addMasterDetailsLoadFunc('getMasterDetail_mod_anagrafica_green_pass_autocertificazione', 'Lista autocertificazioni Green pass', 'getMasterDetail_mod_anagrafica_green_pass_autocertificazione');
				}
				
		 
				//SE LA PERSONA E' INSEGNANTE AGGIUNGO LA FUNZIONE PER I TABS INSEGNANTI
				$attributiAnagrafica = $this->checkAttributo($idAnagrafica);
				if(in_array("INSEGNANTE",$attributiAnagrafica)){
					$this->addMasterDetailsLoadFunc('getMasterDetail_mod_corsi_insegnanti', 'Elenco corsi svolti da insegnante', 'getMasterDetail_mod_corsi_insegnanti');
					$this->addMasterDetailsLoadFunc('getMasterDetail_mod_insegnanti_discipline', 'Elenco discipline da insegnante ', 'getMasterDetail_mod_insegnanti_discipline');	
				}
		
			} 
		};
	 

	}



	/**
	 * 
	 * Genera tutore temporaneo in attesa di definire quello definitivo
	 * @return json/string
	 */
	public function genTutore(){
		$tutore = $this->modelClassModule->genTutore();
		echo json_encode($tutore);
	}


	/**
	 * 
	 *  Verifica gli attributi di un'anagrafica:se alunno,insegnante, direttivo
	 *  @param mixed $id
	 *  @return array
	*/
	public function checkAttributo($id)
	{
		$lista_attributi = $this->modelClassModule->checkAttributo($id);
		return explode(",",$lista_attributi[0]['anagrafica_attributo']);
	}



	/**
	 * 
	 *  Verifica se un'anagrafica è sottoposto a green pass
	 *  @param mixed $id
	 *  @return string
	*/	
	public function checkSottopostoRegimeGP($id)
	{
		$sottopostoRegimeGP = $this->modelClassModule->checkSottopostoRegimeGP($id);
		return $sottopostoRegimeGP[0]['sottoposto_regime_green_pass'];
	}


	/**
	 * 
	 * Funzione caricamento della master details, tabella _mod_anagrafica_certificati_medici
	 * @param mixed $id
	 * @param string $isAjax
	 * @return string
	 **/
	public function getMasterDetail_mod_anagrafica_certificati_medici($id, $isAjax = 'FALSE')
	{
		$row =  $this->modelClassModule->getMasterDetail_mod_anagrafica_certificati_medici($id, $isAjax);
		$html = '';
		$winFormType = "form"; //VALORI ACCETTATI: {'multi','form'}
		$html .= "<br><br><table class='TFtable' id='tbl_mod_anagrafica_certificati_medici' style='font-size:12px'>
					<tr>
						<thead>
						<th class='sorting_disabled' rowspan='1' colspan='1' aria-label='' style='width:10%;'>
							<input type='checkbox' id='check_master_mod_anagrafica_certificati_medici' name='check_master_mod_anagrafica_certificati_medici' 
							onchange=\"selezionaDeselezionaTutti('check_master_mod_anagrafica_certificati_medici','check_id_mod_anagrafica_certificati_medici','btDeleteMass_mod_anagrafica_certificati_medici')\">
						</th>";
		$html .= '<th>Certificato Medico</th>';
		$html .= '<th>Data Certificato</th>';
		$html .= '<th>Data Scadenza Certificato</th>';
		$html .= '<th>Tipologia Certificato</th>';
		$html .= '</tr>';
		$html .= '<tbody>';
		foreach ($row as $key => $value) {
			$html .= "<tr>";
			$html .= "<td><input type='checkbox' id='check_id_mod_anagrafica_certificati_medici' name='check_id_mod_anagrafica_certificati_medici' value='" . $value['id'] . "' onchange=\"verificaNrCheckBoxSelezionati('check_id_mod_anagrafica_certificati_medici','btDeleteMass_mod_anagrafica_certificati_medici')\"></td>";
			$html .= "<td><input type='hidden' id='id[]' name='id[]' value='" . $value['id'] . "'><a href='mod_anagrafica/scaricaAllegatoBlob/_mod_anagrafica_certificati_medici/documento_upload/".$value['id']."' target='_blank'><img src=\"".base_url()."assets/images/download_multimedia_file_document_icon.png\"> Scarica Documento</a></td>";
			$html .= "<td><input type='hidden' id='id[]' name='id[]' value='" . $value['id'] . "'>" . $this->utilities->convertToDateIT($value['data_certificato']) . "</td>";
			$html .= "<td><input type='hidden' id='id[]' name='id[]' value='" . $value['id'] . "'>" . $this->utilities->convertToDateIT($value['data_scadenza']) . "</td>";
			$html .= "<td><input type='hidden' id='id[]' name='id[]' value='" . $value['id'] . "'>" . $value['tipologia'] . "</td>";
			$html .= '</tr>';
		}
		$html .= '</tbody></table>';

		return $html;
	}



	/**
	 * 
	 * Funzione caricamento della master details, tabella _mod_anagrafica_corsi
	 * @param mixed $id
	 * @param string $isAjax
	 * @return string
	 **/
	public function getMasterDetail_mod_anagrafica_corsi($id, $isAjax = 'FALSE')
	{
		$row =  $this->modelClassModule->getMasterDetail_mod_anagrafica_corsi($id, $isAjax);
		$html = '';
		$winFormType = "form"; //VALORI ACCETTATI: {'multi','form'}


		$html .= '<br><br>';
		$html .= "<table class='TFtable' id='tbl_mod_anagrafica_corsi' style='font-size:12px'>
					<tr>
						<thead>
						<th class='sorting_disabled' rowspan='1' colspan='1' aria-label='' style='width:10%;'>
							<input type='checkbox' id='check_master_mod_anagrafica_corsi' name='check_master_mod_anagrafica_corsi' 
							onchange=\"selezionaDeselezionaTutti('check_master_mod_anagrafica_corsi','check_id_mod_anagrafica_corsi','btDeleteMass_mod_anagrafica_corsi')\">
						</th>";
		$html .= '<th>Corso Frequentato</th>';
		$html .= '</tr>';
		$html .= '<tbody>';
		foreach ($row as $key => $value) {
			$html .= "<tr>";
			$html .= "<td><input type='checkbox' id='check_id_mod_anagrafica_corsi' name='check_id_mod_anagrafica_corsi' value='" . $value['id'] . "' onchange=\"verificaNrCheckBoxSelezionati('check_id_mod_anagrafica_corsi','btDeleteMass_mod_anagrafica_corsi')\"></td>";
			$html .= "<td><input type='hidden' id='id[]' name='id[]' value='" . $value['id'] . "'>" . $value['mod_corsi_nome'] . "</td>";
			$html .= '</tr>';
		}
		$html .= '</tbody></table>';

		return $html;
	}


	/**
	 * 
	 * Funzione caricamento della master details, tabella _mod_anagrafica_green_pass
	 * @param mixed $id
	 * @param string $isAjax
	 * @return string
	 **/
	public function getMasterDetail_mod_anagrafica_green_pass($id, $isAjax = 'FALSE')
	{
		$row =  $this->modelClassModule->getMasterDetail_mod_anagrafica_green_pass($id, $isAjax);
		$html = '';
		$winFormType = "form"; //VALORI ACCETTATI: {'multi','form'}

		if ($isAjax == 'FALSE') {
			if ($winFormType == "form") {
				$html .= '
							<br><a class="btn btn-primary" style="cursor:pointer" onclick="winFormMasterDetails(\'mod_anagrafica\',\'winMasterDetail_mod_anagrafica_green_pass\',\'insert\',' . $id . ',\'NULL\',\'Aggiungi Green Pass\', arrayValidationFields,\'winMasterDetail_mod_anagrafica_green_pass\',\'form\',\'getMasterDetail_mod_anagrafica_green_pass\')">[ Aggiungi un elemento]</a><br>
							<br><br>';
			} else {
				$html .= '
						<br><a class="btn btn-primary" style="cursor:pointer" onclick="winFormMasterDetails(\'mod_anagrafica\',\'winMasterDetailMulti_mod_anagrafica_green_pass\',\'insert\',' . $id . ',\'NULL\',\'Aggiungi Green Pass\', arrayValidationFields,\'winMasterDetailMulti_mod_anagrafica_green_pass\',\'multi\',\'getMasterDetail_mod_anagrafica_green_pass\')">[ Aggiungi un elemento]</a><br>
						<br><br>';
			}
		}
		$html .= "<table class='TFtable' id='tbl_mod_anagrafica_green_pass' style='font-size:12px'>
					<tr>
						<thead>
						<th class='sorting_disabled' rowspan='1' colspan='1' aria-label='' style='width:10%;'>
							<input type='checkbox' id='check_master_mod_anagrafica_green_pass' name='check_master_mod_anagrafica_green_pass' 
							onchange=\"selezionaDeselezionaTutti('check_master_mod_anagrafica_green_pass','check_id_mod_anagrafica_green_pass','btDeleteMass_mod_anagrafica_green_pass')\">
						</th>";
		$html .= '<th>Green Pass</th>';
		$html .= '<th>Data Validita Inizio</th>';
		$html .= '<th>Data Validita Fine</th>';


		$html .= '<th>Tipo Green Pass</th>';
		if ($winFormType == "form") {
			$html .= '<th>Modifica</th>';
		}
		$html .= '<th>Elimina</th>';
		$html .= '</tr>';
		$html .= '<tbody>';
		foreach ($row as $key => $value) {
			$html .= "<tr>";
			$html .= "<td><input type='checkbox' id='check_id_mod_anagrafica_green_pass' name='check_id_mod_anagrafica_green_pass' value='" . $value['id'] . "' onchange=\"verificaNrCheckBoxSelezionati('check_id_mod_anagrafica_green_pass','btDeleteMass_mod_anagrafica_green_pass')\"></td>";
			$html .= "<td><input type='hidden' id='id[]' name='id[]' value='" . $value['id'] . "'><a href='mod_anagrafica/scaricaAllegatoBlob/_mod_anagrafica_green_pass/documento_upload/".$value['id']."' target='_blank'><img src=\"".base_url()."assets/images/download_multimedia_file_document_icon.png\"> Scarica Documento</a></td>";
			$html .= "<td><input type='hidden' id='id[]' name='id[]' value='" . $value['id'] . "'>" . $this->utilities->convertToDateTimeIT($value['data_validita_inizio']) . "</td>";
			$html .= "<td><input type='hidden' id='id[]' name='id[]' value='" . $value['id'] . "'>" . $this->utilities->convertToDateTimeIT($value['data_validita_fine']) . "</td>";

			$html .= "<td><input type='hidden' id='id[]' name='id[]' value='" . $value['id'] . "'>" . $value['tipo_green_pass'] . "</td>";
			if ($winFormType == "form") {
				$html .= "<td><a style='cursor:pointer' class='btn btn-sm btn-info' onclick ='winFormMasterDetails(\"mod_anagrafica\",\"winMasterDetail_mod_anagrafica_green_pass\",\"edit\", $id," . $value['id'] . ",\"MODIFICA Anagrafati Green Pass\",arrayValidationFields,\"winMasterDetail_mod_anagrafica_green_pass\",\"form\",\"getMasterDetail_mod_anagrafica_green_pass\")' title='Modifica Anagrafati Green Pass'><i class='fa fa-edit'></a></td>";
			}
			$html .= "<td><a style='cursor:pointer' class='btn btn-sm btn-danger deleteUser' onclick ='deleteMasterDetails(" . $value['id'] . ", " . $id . ", \"mod_anagrafica\",\"_mod_anagrafica_green_pass\",\"getMasterDetail_mod_anagrafica_green_pass\")' title='Elimina'><i class='fa fa-trash'></a></td>";
			$html .= '</tr>';
		}
		$html .= '</tbody></table>';
		$html .= '<br/><a class="btn btn-sm btn-danger deleteUser" id="btDeleteMass_mod_anagrafica_green_pass" name="btDeleteMass_mod_anagrafica_green_pass""
					onclick="deleteMassiveMasterDetails(' . $id . ',\'entry_list\',\'check_id_mod_anagrafica_green_pass\',\'mod_anagrafica\',\'_mod_anagrafica_green_pass\',\'getMasterDetail_mod_anagrafica_green_pass\')">
					<i class="fa fa-trash"></i> Cancellazione Massiva
				</a>';
		return $html;
	}


	/**
	 * 
	 * Funzione caricamento della master details, tabella _mod_anagrafica_green_pass_autocertificazione
	 * @param mixed $id
	 * @param string $isAjax
	 * @return string
	 **/
	public function getMasterDetail_mod_anagrafica_green_pass_autocertificazione($id, $isAjax = 'FALSE')
	{
		$row =  $this->modelClassModule->getMasterDetail_mod_anagrafica_green_pass_autocertificazione($id, $isAjax);
		$html = '';
		$winFormType = "form"; //VALORI ACCETTATI: {'multi','form'}

		$html .= "<br><br><table class='TFtable' id='tbl_mod_anagrafica_green_pass_autocertificazione' style='font-size:12px'>
					<tr>
						<thead>
						<th class='sorting_disabled' rowspan='1' colspan='1' aria-label='' style='width:10%;'>
							<input type='checkbox' id='check_master_mod_anagrafica_green_pass_autocertificazione' name='check_master_mod_anagrafica_green_pass_autocertificazione' 
							onchange=\"selezionaDeselezionaTutti('check_master_mod_anagrafica_green_pass_autocertificazione','check_id_mod_anagrafica_green_pass_autocertificazione','btDeleteMass_mod_anagrafica_green_pass_autocertificazione')\">
						</th>";
		$html .= '<th>Autocertificazione</th>';			
		$html .= '<th>Data Autocertificazione Data Fine Validita</th>';

		$html .= '</tr>';
		$html .= '<tbody>';
		foreach ($row as $key => $value) {
			$html .= "<tr>";
			$html .= "<td><input type='checkbox' id='check_id_mod_anagrafica_green_pass_autocertificazione' name='check_id_mod_anagrafica_green_pass_autocertificazione' value='" . $value['id'] . "' onchange=\"verificaNrCheckBoxSelezionati('check_id_mod_anagrafica_green_pass_autocertificazione','btDeleteMass_mod_anagrafica_green_pass_autocertificazione')\"></td>";
			$html .= "<td><input type='hidden' id='id[]' name='id[]' value='" . $value['id'] . "'><a href='mod_anagrafica/scaricaAllegatoBlob/_mod_anagrafica_green_pass_autocertificazione/documento_upload/".$value['id']."' target='_blank'><img src=\"".base_url()."assets/images/download_multimedia_file_document_icon.png\"> Scarica Documento</a></td>";
			$html .= "<td><input type='hidden' id='id[]' name='id[]' value='" . $value['id'] . "'>" . $this->utilities->convertToDateIT($value['data_autocertificazione_fine_validita']) . "</td>";

			$html .= '</tr>';
		}
		$html .= '</tbody></table>';

		$html .= '<BR><BR><a href="'.$this->mod_name.'/stampaAutocertificazioneGreenPass/'.$id.'" target="_blank"><img src="'.base_url().'assets/images/pdf.png" width="32"> Stampa Autocertificazione pre-compilata da firmare</a><br><br>';

		return $html;
	}



	/**
	 * 
	 * Funzione caricamento della master details, tabella _mod_anagrafica_green_pass_esentati
	 * @param mixed $id
	 * @param string $isAjax
	 * @return string
	 **/
	public function getMasterDetail_mod_anagrafica_green_pass_esentati($id, $isAjax = 'FALSE')
	{
		$row =  $this->modelClassModule->getMasterDetail_mod_anagrafica_green_pass_esentati($id, $isAjax);
		$html = '';
		$winFormType = "form"; //VALORI ACCETTATI: {'multi','form'}

		if ($isAjax == 'FALSE') {
			if ($winFormType == "form") {
				$html .= '
							<br><a class="btn btn-primary" style="cursor:pointer" onclick="winFormMasterDetails(\'mod_anagrafica\',\'winMasterDetail_mod_anagrafica_green_pass_esentati\',\'insert\',' . $id . ',\'NULL\',\'NUOVO Anagrafati Green pass esentati\', arrayValidationFields,\'winMasterDetail_mod_anagrafica_green_pass_esentati\',\'form\',\'getMasterDetail_mod_anagrafica_green_pass_esentati\')">[ Aggiungi un elemento]</a><br>
							<br><br>';
			} else {
				$html .= '
						<br><a class="btn btn-primary" style="cursor:pointer" onclick="winFormMasterDetails(\'mod_anagrafica\',\'winMasterDetailMulti_mod_anagrafica_green_pass_esentati\',\'insert\',' . $id . ',\'NULL\',\'NUOVO Anagrafati Green pass esentati\', arrayValidationFields,\'winMasterDetailMulti_mod_anagrafica_green_pass_esentati\',\'multi\',\'getMasterDetail_mod_anagrafica_green_pass_esentati\')">[ Aggiungi un elemento]</a><br>
						<br><br>';
			}
		}
		$html .= "<table class='TFtable' id='tbl_mod_anagrafica_green_pass_esentati' style='font-size:12px'>
					<tr>
						<thead>
						<th class='sorting_disabled' rowspan='1' colspan='1' aria-label='' style='width:10%;'>
							<input type='checkbox' id='check_master_mod_anagrafica_green_pass_esentati' name='check_master_mod_anagrafica_green_pass_esentati' 
							onchange=\"selezionaDeselezionaTutti('check_master_mod_anagrafica_green_pass_esentati','check_id_mod_anagrafica_green_pass_esentati','btDeleteMass_mod_anagrafica_green_pass_esentati')\">
						</th>";
		$html .= '<th>Certificato Medico Esenzione</th>';
		$html .= '<th>Alunno/Insegnante</th>';
		if ($winFormType == "form") {
			$html .= '<th>Modifica</th>';
		}
		$html .= '<th>Elimina</th>';
		$html .= '</tr>';
		$html .= '<tbody>';
		foreach ($row as $key => $value) {
			$html .= "<tr>";
			$html .= "<td><input type='checkbox' id='check_id_mod_anagrafica_green_pass_esentati' name='check_id_mod_anagrafica_green_pass_esentati' value='" . $value['id'] . "' onchange=\"verificaNrCheckBoxSelezionati('check_id_mod_anagrafica_green_pass_esentati','btDeleteMass_mod_anagrafica_green_pass_esentati')\"></td>";
			$html .= "<td><input type='hidden' id='id[]' name='id[]' value='" . $value['id'] . "'>" . $value['certificato_medico_esenzione'] . "</td>";
			$html .= "<td><input type='hidden' id='id[]' name='id[]' value='" . $value['id'] . "'>" . $value['mod_anagrafica_nome'] . "</td>";
			if ($winFormType == "form") {
				$html .= "<td><a style='cursor:pointer' class='btn btn-sm btn-info' onclick ='winFormMasterDetails(\"mod_anagrafica\",\"winMasterDetail_mod_anagrafica_green_pass_esentati\",\"edit\", $id," . $value['id'] . ",\"MODIFICA Anagrafati Green pass esentati\",arrayValidationFields,\"form\",\"getMasterDetail_mod_anagrafica_green_pass_esentati\")' title='Modifica Anagrafati Green pass esentati'><i class='fa fa-edit'></a></td>";
			}
			$html .= "<td><a style='cursor:pointer' class='btn btn-sm btn-danger deleteUser' onclick ='deleteMasterDetails(" . $value['id'] . ", " . $id . ", \"mod_anagrafica\",\"_mod_anagrafica_green_pass_esentati\",\"getMasterDetail_mod_anagrafica_green_pass_autocertificazione\")' title='Elimina'><i class='fa fa-trash'></a></td>";
			$html .= '</tr>';
		}
		$html .= '</tbody></table>';
		$html .= '<br/><a class="btn btn-sm btn-danger deleteUser" id="btDeleteMass_mod_anagrafica_green_pass_esentati" name="btDeleteMass_mod_anagrafica_green_pass_esentati""
					onclick="deleteMassiveMasterDetails(' . $id . ',\'entry_list\',\'check_id_mod_anagrafica_green_pass_esentati\',\'mod_anagrafica\',\'_mod_anagrafica_green_pass_esentati\',\'getMasterDetail_mod_anagrafica_green_pass_autocertificazione\')">
					<i class="fa fa-trash"></i> Cancellazione Massiva
				</a>';
		return $html;
	}


	/**
	 * Funzione caricamento della master details, tabella _mod_anagrafica_tessere_assoc
	 * @param mixed $id
	 * @param string $isAjax
	 * @return string
	 **/
	public function getMasterDetail_mod_anagrafica_tessere_assoc($id, $isAjax = 'FALSE')
	{
		$row =  $this->modelClassModule->getMasterDetail_mod_anagrafica_tessere_assoc($id, $isAjax);
		$html = '';
		$winFormType = "form"; //VALORI ACCETTATI: {'multi','form'}


		$html .= '<br><br>';
		$html .= "<table class='TFtable' id='tbl_mod_anagrafica_tessere_assoc' style='font-size:12px'>
					<tr>
						<thead>
						<th class='sorting_disabled' rowspan='1' colspan='1' aria-label='' style='width:10%;'>
							<input type='checkbox' id='check_master_mod_anagrafica_tessere_assoc' name='check_master_mod_anagrafica_tessere_assoc' 
							onchange=\"selezionaDeselezionaTutti('check_master_mod_anagrafica_tessere_assoc','check_id_mod_anagrafica_tessere_assoc','btDeleteMass_mod_anagrafica_tessere_assoc')\">
						</th>";
		$html .= '<th>Affiliazione</th>';
		$html .= '<th>Tessera Interna</th>';
		$html .= '<th>Tessera Associativa</th>';
		$html .= '</tr>';
		$html .= '<tbody>';
		foreach ($row as $key => $value) {
			$html .= "<tr>";
			$html .= "<td><input type='checkbox' id='check_id_mod_anagrafica_tessere_assoc' name='check_id_mod_anagrafica_tessere_assoc' value='" . $value['id'] . "' onchange=\"verificaNrCheckBoxSelezionati('check_id_mod_anagrafica_tessere_assoc','btDeleteMass_mod_anagrafica_tessere_assoc')\"></td>";
			$html .= "<td><input type='hidden' id='id[]' name='id[]' value='" . $value['id'] . "'>" . $value['mod_affiliazioni_nome'] . "</td>";
			$html .= "<td><input type='hidden' id='id[]' name='id[]' value='" . $value['id'] . "'>" . $value['tessera_interna'] . "</td>";
			$html .= "<td><input type='hidden' id='id[]' name='id[]' value='" . $value['id'] . "'>" . $value['tessera_associativa'] . "</td>";
			$html .= '</tr>';
		}
		$html .= '</tbody></table>';

		return $html;
	}


	/**
	 * Funzione caricamento della master details, tabella _mod_anagrafica_tessere_interne
	 * @param mixed $id
	 * @param string $isAjax
	 * @return string
	 **/
	public function getMasterDetail_mod_anagrafica_tessere_interne($id, $isAjax = 'FALSE')
	{
		$row =  $this->modelClassModule->getMasterDetail_mod_anagrafica_tessere_interne($id, $isAjax);
		$html = '';
		$winFormType = "form"; //VALORI ACCETTATI: {'multi','form'}

		$html .= '<br><br>';
		$html .= "<table class='TFtable' id='tbl_mod_anagrafica_tessere_interne' style='font-size:12px'>
					<tr>
						<thead>
						<th class='sorting_disabled' rowspan='1' colspan='1' aria-label='' style='width:10%;'>
							<input type='checkbox' id='check_master_mod_anagrafica_tessere_interne' name='check_master_mod_anagrafica_tessere_interne' 
							onchange=\"selezionaDeselezionaTutti('check_master_mod_anagrafica_tessere_interne','check_id_mod_anagrafica_tessere_interne','btDeleteMass_mod_anagrafica_tessere_interne')\">
						</th>";
		//$html.='<th>Alunno</th>';
		$html .= '<th>Esercizio</th>';
		$html .= '<th>Tessera Interna</th>';
		if ($winFormType == "form") {
			//	$html.='<th>Modifica</th>';
		}
		//$html.='<th>Elimina</th>';
		$html .= '</tr>';
		$html .= '<tbody>';
		foreach ($row as $key => $value) {
			$html .= "<tr>";
			$html .= "<td><input type='checkbox' id='check_id_mod_anagrafica_tessere_interne' name='check_id_mod_anagrafica_tessere_interne' value='" . $value['id'] . "' onchange=\"verificaNrCheckBoxSelezionati('check_id_mod_anagrafica_tessere_interne','btDeleteMass_mod_anagrafica_tessere_interne')\"></td>";
			$html .= "<td><input type='hidden' id='id[]' name='id[]' value='" . $value['id'] . "'>" . $value['mod_esercizi_nome'] . "</td>";
			$html .= "<td><input type='hidden' id='id[]' name='id[]' value='" . $value['id'] . "'>" . $value['tessera_interna'] . "</td>";
 			$html .= '</tr>';
		}
		$html .= '</tbody></table>';
 
		return $html;
	}


	/**
	 * 
	 * Funzione caricamento della master details, tabella _mod_corsi_insegnanti
	 * @param mixed $id
	 * @param string $isAjax
	 * @return string
	 **/
	public function getMasterDetail_mod_corsi_insegnanti($id, $isAjax = 'FALSE')
	{
		$row =  $this->modelClassModule->getMasterDetail_mod_corsi_insegnanti($id, $isAjax);
		$html = '';
		$winFormType = "form"; //VALORI ACCETTATI: {'multi','form'}

		$html .= '<br><br>';
		$html .= "<table class='TFtable' id='tbl_mod_corsi_insegnanti' style='font-size:12px'>
					<tr>
						<thead>
						<th class='sorting_disabled' rowspan='1' colspan='1' aria-label='' style='width:10%;'>
							<input type='checkbox' id='check_master_mod_corsi_insegnanti' name='check_master_mod_corsi_insegnanti' 
							onchange=\"selezionaDeselezionaTutti('check_master_mod_corsi_insegnanti','check_id_mod_corsi_insegnanti','btDeleteMass_mod_corsi_insegnanti')\">
						</th>";
		$html .= '<th>Corso svolto da insegnante</th>';
		$html .= '</tr>';
		$html .= '<tbody>';
		foreach ($row as $key => $value) {
			$html .= "<tr>";
			$html .= "<td><input type='checkbox' id='check_id_mod_corsi_insegnanti' name='check_id_mod_corsi_insegnanti' value='" . $value['id'] . "' onchange=\"verificaNrCheckBoxSelezionati('check_id_mod_corsi_insegnanti','btDeleteMass_mod_corsi_insegnanti')\"></td>";
			$html .= "<td><input type='hidden' id='id[]' name='id[]' value='" . $value['id'] . "'>" . $value['mod_corsi_nome'] . "</td>";
			$html .= '</tr>';
		}
		$html .= '</tbody></table>';
 
		return $html;
	}


	/**
	 * 
	 * Funzione caricamento della master details, tabella _mod_corsi_iscrizioni
	 * @param mixed $id
	 * @param string $isAjax
	 * @return string
	 **/
	public function getMasterDetail_mod_corsi_iscrizioni($id, $isAjax = 'FALSE')
	{
		$row =  $this->modelClassModule->getMasterDetail_mod_corsi_iscrizioni($id, $isAjax);
		$html = '';
		$winFormType = "form"; //VALORI ACCETTATI: {'multi','form'}

		if ($isAjax == 'FALSE') {
			if ($winFormType == "form") {
				$html .= '
							<br><a class="btn btn-primary" style="cursor:pointer" onclick="winFormMasterDetails(\'mod_anagrafica\',\'winMasterDetail_mod_corsi_iscrizioni\',\'insert\',' . $id . ',\'NULL\',\'NUOVO Iscritti ai corsi\', arrayValidationFields,\'winMasterDetail_mod_corsi_iscrizioni\',\'form\',\'getMasterDetail_mod_corsi_iscrizioni\')">[ Aggiungi un elemento]</a><br>
							<br><br>';
			} else {
				$html .= '
						<br><a class="btn btn-primary" style="cursor:pointer" onclick="winFormMasterDetails(\'mod_anagrafica\',\'winMasterDetailMulti_mod_corsi_iscrizioni\',\'insert\',' . $id . ',\'NULL\',\'NUOVO Iscritti ai corsi\', arrayValidationFields,\'winMasterDetailMulti_mod_corsi_iscrizioni\',\'multi\',\'getMasterDetail_mod_corsi_iscrizioni\')">[ Aggiungi un elemento]</a><br>
						<br><br>';
			}
		}
		$html .= "<table class='TFtable' id='tbl_mod_corsi_iscrizioni' style='font-size:12px'>
					<tr>
						<thead>
						<th class='sorting_disabled' rowspan='1' colspan='1' aria-label='' style='width:10%;'>
							<input type='checkbox' id='check_master_mod_corsi_iscrizioni' name='check_master_mod_corsi_iscrizioni' 
							onchange=\"selezionaDeselezionaTutti('check_master_mod_corsi_iscrizioni','check_id_mod_corsi_iscrizioni','btDeleteMass_mod_corsi_iscrizioni')\">
						</th>";
		$html .= '<th>Anno</th>';
		$html .= '<th>Alunno</th>';
		$html .= '<th>Corso</th>';
		$html .= '<th>Frequentato</th>';
		$html .= '<th>Mese</th>';
		if ($winFormType == "form") {
			$html .= '<th>Modifica</th>';
		}
		$html .= '<th>Elimina</th>';
		$html .= '</tr>';
		$html .= '<tbody>';
		foreach ($row as $key => $value) {
			$html .= "<tr>";
			$html .= "<td><input type='checkbox' id='check_id_mod_corsi_iscrizioni' name='check_id_mod_corsi_iscrizioni' value='" . $value['id'] . "' onchange=\"verificaNrCheckBoxSelezionati('check_id_mod_corsi_iscrizioni','btDeleteMass_mod_corsi_iscrizioni')\"></td>";
			$html .= "<td><input type='hidden' id='id[]' name='id[]' value='" . $value['id'] . "'>" . $value['anno'] . "</td>";
			$html .= "<td><input type='hidden' id='id[]' name='id[]' value='" . $value['id'] . "'>" . $value['mod_anagrafica_nome'] . "</td>";
			$html .= "<td><input type='hidden' id='id[]' name='id[]' value='" . $value['id'] . "'>" . $value['mod_corsi_nome'] . "</td>";
			$html .= "<td><input type='hidden' id='id[]' name='id[]' value='" . $value['id'] . "'>" . $value['is_frequentato'] . "</td>";
			$html .= "<td><input type='hidden' id='id[]' name='id[]' value='" . $value['id'] . "'>" . $value['mese'] . "</td>";
			if ($winFormType == "form") {
				$html .= "<td><a style='cursor:pointer' class='btn btn-sm btn-info' onclick ='winFormMasterDetails(\"mod_anagrafica\",\"winMasterDetail_mod_corsi_iscrizioni\",\"edit\", $id," . $value['id'] . ",\"MODIFICA Iscritti ai corsi\",arrayValidationFields,\"form\",\"getMasterDetail_mod_corsi_iscrizioni\")' title='Modifica Iscritti ai corsi'><i class='fa fa-edit'></a></td>";
			}
			$html .= "<td><a style='cursor:pointer' class='btn btn-sm btn-danger deleteUser' onclick ='deleteMasterDetails(" . $value['id'] . ", " . $id . ", \"mod_anagrafica\",\"_mod_corsi_iscrizioni\",\"getMasterDetail_mod_corsi_iscrizioni\")' title='Elimina'><i class='fa fa-trash'></a></td>";
			$html .= '</tr>';
		}
		$html .= '</tbody></table>';
		$html .= '<br/><a class="btn btn-sm btn-danger deleteUser" id="btDeleteMass_mod_corsi_iscrizioni" name="btDeleteMass_mod_corsi_iscrizioni""
					onclick="deleteMassiveMasterDetails(' . $id . ',\'entry_list\',\'check_id_mod_corsi_iscrizioni\',\'mod_anagrafica\',\'_mod_corsi_iscrizioni\',\'getMasterDetail_mod_corsi_iscrizioni\')">
					<i class="fa fa-trash"></i> Cancellazione Massiva
				</a>';
		return $html;
	}


	/**
	 * Funzione caricamento della master details, tabella _mod_insegnanti_discipline
	 * @param mixed $id
	 * @param string $isAjax
	 * @return string
	 **/
	public function getMasterDetail_mod_insegnanti_discipline($id, $isAjax = 'FALSE')
	{
		$row =  $this->modelClassModule->getMasterDetail_mod_insegnanti_discipline($id, $isAjax);
		$html = '';
		$winFormType = "form"; //VALORI ACCETTATI: {'multi','form'}

		if ($isAjax == 'FALSE') {
			if ($winFormType == "form") {
				$html .= '
							<br><a class="btn btn-primary" style="cursor:pointer" onclick="winFormMasterDetails(\'mod_anagrafica\',\'winMasterDetail_mod_insegnanti_discipline\',\'insert\',' . $id . ',\'NULL\',\'Associa disciplina\', arrayValidationFields,\'winMasterDetail_mod_insegnanti_discipline\',\'form\',\'getMasterDetail_mod_insegnanti_discipline\')">[ Aggiungi un elemento]</a><br>
							<br><br>';
			} else {
				$html .= '
						<br><a class="btn btn-primary" style="cursor:pointer" onclick="winFormMasterDetails(\'mod_anagrafica\',\'winMasterDetailMulti_mod_insegnanti_discipline\',\'insert\',' . $id . ',\'NULL\',\'NUOVO Insegnanti associati a discipline\', arrayValidationFields,\'winMasterDetailMulti_mod_insegnanti_discipline\',\'multi\',\'getMasterDetail_mod_insegnanti_discipline\')">[ Aggiungi un elemento]</a><br>
						<br><br>';
			}
		}

 
		$html .= "<table class='TFtable' id='tbl_mod_insegnanti_discipline' style='font-size:12px'>
					<tr>
						<thead>
						<th class='sorting_disabled' rowspan='1' colspan='1' aria-label='' style='width:10%;'>
							<input type='checkbox' id='check_master_mod_insegnanti_discipline' name='check_master_mod_insegnanti_discipline' 
							onchange=\"selezionaDeselezionaTutti('check_master_mod_insegnanti_discipline','check_id_mod_insegnanti_discipline','btDeleteMass_mod_insegnanti_discipline')\">
						</th>";
		$html .= '<th>Disciplina</th>';
 
		$html .= '</tr>';
		$html .= '<tbody>';
		foreach ($row as $key => $value) {
			$html .= "<tr>";
			$html .= "<td><input type='checkbox' id='check_id_mod_insegnanti_discipline' name='check_id_mod_insegnanti_discipline' value='" . $value['id'] . "' onchange=\"verificaNrCheckBoxSelezionati('check_id_mod_insegnanti_discipline','btDeleteMass_mod_insegnanti_discipline')\"></td>";
			$html .= "<td><input type='hidden' id='id[]' name='id[]' value='" . $value['id'] . "'>" . $value['mod_discipline_nome'] . "</td>";
			$html .= '</tr>';
		}
		$html .= '</tbody></table>';

		return $html;
	}


	/**
	 * 
	 * 
	 * Funzione caricamento della finestra per la master details, tabella _mod_anagrafica_certificati_medici
	 * @param mixed $action
	 * @param string $entryID
	 * @param string $entryIDMasterDetails
	 * @return string
	 **/
	public function winMasterDetail_mod_anagrafica_certificati_medici($action, $entryID, $entryIDMasterDetails = NULL)
	{
 
		$rowWinForm = $this->modelClassModule->get_from_master_details_by_id($entryIDMasterDetails, '_mod_anagrafica_certificati_medici', 'id');

		if(!isset($rowWinForm['data_certificato'])){
			$rowWinForm['data_certificato'] = date("Y-m-d");
		}
		if(!isset($rowWinForm['data_scadenza'])){
			$rowWinForm['data_scadenza'] = date("Y-m-d");
		}

		$html = '<div>
				<section class="content">
					<div class="row">
						<div class="col-md-12">
							<div class="box box-primary">
								<div class="box-body">
								<div id="msg_err" ondblclick="this.style.display=\'none\';" style="display: none; border: 1px solid; background-color: rgb(255, 64, 0); border-radius: 5px; padding: 10px; color: white; font-weight: bold;">
								</div>									
									<form  name="frm_master_detail" id="frm_master_detail">
									<input type="hidden" id="table" name="table" value="_mod_anagrafica_certificati_medici">
									<input type="hidden" id="action" name="action" value="' . $action . '"/> 
									<input type="hidden" id="saveType" name="saveType" value="form"/> 	
									<input type="hidden" id="entryID"          name="entryID"  value="' . $entryID . '">
									<input type="hidden" id="fk_anagrafica"          name="fk_anagrafica"  value="' . $entryID . '">
									<input type="hidden" id="entryIDMasterDetails" 		name="entryIDMasterDetails" value="' . $entryIDMasterDetails . '" >															
										<div class="col-md-12">
											<div class="form-group">';

		$html .= '<div class="form-group">';

		$html .= '<label for="date"><b style="color:#990000">(*)</b>Data Certificato </label>';

		$html .= '<div class="input-group">';

		$html .= '<div class="input-group-addon"><i class="fa fa-calendar"></i></div>';

		$html .= '<input type="text" class="form-control datemask" name="data_certificato" id="data_certificato" placeholder="Data Certificato"';

		$html .= 'autocomplete="off" style="background-color:#FFFFFF" value="' . $this->utilities->convertToDateIT($rowWinForm['data_certificato']) . '" />';

		$html .= '</div></div>';
		$html .= '<div class="form-group">';

		$html .= '<label for="date"><b style="color:#990000">(*)</b>Data Scadenza Certificato </label>';

		$html .= '<div class="input-group">';

		$html .= '<div class="input-group-addon"><i class="fa fa-calendar"></i></div>';

		$html .= '<input type="text" class="form-control datemask" name="data_scadenza" id="data_scadenza" placeholder="Data Scadenza Certificato"';

		$html .= 'autocomplete="off" style="background-color:#FFFFFF" value="' . $this->utilities->convertToDateIT($rowWinForm['data_scadenza']) . '" />';

		$html .= '</div></div>';

		$html .= '<label for="longblob"><b style="color:#990000">(*)</b>Documento Upload </label>';
		

		$html .= '				
												<div class="input-group">
													<div class="input-group-addon"><i class="fa fa-text-height"></i></div>';
		$html .= '<input type="file" class="form-control"  name="documento_upload" id="documento_upload"  />';
	
		$html .= '</div>';
		if ($rowWinForm['documento_upload'] != '') {
			$html .= "<a href='mod_anagrafica/scaricaAllegatoBlob/_mod_anagrafica_certificati_medici/documento_upload/".$rowWinForm['id']."' target='_blank'><img src=\"".base_url()."assets/images/download_multimedia_file_document_icon.png\"> ".$rowWinForm['nome_documento']."</a>";			
		}			
		
		$html .= '</div>';

		$html .= '<div class="form-group">';

		$html .= '<label for="tipologia"><b style="color:#990000">(*)</b>Tipologia Certificato </label>';

		$html .= '<div class="form-group">';



		$html .= '<SELECT name=\'tipologia\' id=\'tipologia\' 
										style="width:100%;padding: 6px 12px;font-size:14px;
										border-top-right-radius:0px;border-bottom-right-radius:0px;
										border-top-left-radius:0px;border-bottom-left-radius:0px;
										border:1px solid #ccc"
									class="form-control">';
		$html .= '<OPTION VALUE></OPTION>';
		if ($rowWinForm['tipologia'] == 'AGONISTICO') {
			$html .= '<OPTION VALUE=\'AGONISTICO\'  SELECTED>AGONISTICO</OPTION>';
		} else {
			$html .= '<OPTION VALUE=\'AGONISTICO\'  >AGONISTICO</OPTION>';
		}
		if ($rowWinForm['tipologia'] == 'NON AGONISTICO') {
			$html .= '<OPTION VALUE=\'NON AGONISTICO\'  SELECTED>NON AGONISTICO</OPTION>';
		} else {
			$html .= '<OPTION VALUE=\'NON AGONISTICO\'  >NON AGONISTICO</OPTION>';
		}
		$html .= '</SELECT>';

		$html .= '</div>';
		$html .= '
						<script>	
						$(\'[name=tipologia] option\').filter(function() { 
							return ($(this).text() =="' . $rowWinForm['tipologia'] . '"); //To select Blue
						}).prop(\'selected\', true);				
						</script>';

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
	 * Funzione caricamento della finestra per la master details, tabella _mod_anagrafica_corsi
	 * @param mixed $action
	 * @param string $entryID
	 * @param string $entryIDMasterDetails
	 * @return string
	 **/
	public function winMasterDetail_mod_anagrafica_corsi($action, $entryID, $entryIDMasterDetails = NULL)
	{
		if ($entryIDMasterDetails == 'NULL') {
			$entryIDMasterDetails = '';
		}
		$rowWinForm = $this->modelClassModule->get_from_master_details_by_id($entryIDMasterDetails, '_mod_anagrafica_corsi', 'id');
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
									<input type="hidden" id="action" name="action" value="' . $action . '"/> 
									<input type="hidden" id="saveType" name="saveType" value="form"/> 	
									<input type="hidden" id="entryID"          name="entryID"  value="' . $entryID . '">
									<input type="hidden" id="entryIDMasterDetails" 		name="entryIDMasterDetails" value="' . $entryIDMasterDetails . '" >															
										<div class="col-md-12">
											<div class="form-group">';
		$fk_anagrafica_refval = $this->modelClassModule->getValuesByFk('mod_anagrafica', NULL, NULL);
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
							class="select2-autocomplete form-control">';
		$html .= '<OPTION VALUE></OPTION>';

		foreach ($fk_anagrafica_refval as $key => $value) {
			if ($value['id'] == $rowWinForm['fk_anagrafica']) {
				$html .= "<option value='" . $value['id'] . "' SELECTED>" . $value['nome'] . "</option>";
			} else {
				$html .= "<option value='" . $value['id'] . "'>" . $value['nome'] . "</option>";
			}
		}
		$html .= '</SELECT>';

		$html .= '</div>';
		$fk_corso_refval = $this->modelClassModule->getValuesByFk('mod_corsi', NULL, NULL);
		$html .= '<div class="form-group">';

		$html .= '<label for="fk_corso"><b style="color:#990000">(*)</b>Corso </label>';

		$html .= "<!-- 								
							
		<input list='fk_corso_datalist' class='form-control combobox' name='fk_corso' id='fk_corso' value='<?php echo fk_corso;?>'>
							
		<datalist name='fk_corso_datalist' id='fk_corso_datalist' onselect=\"alert(this.text)\">
							
		-->									
							
		<!-- -->";

		$html .= '<SELECT name=\'fk_corso\' id=\'fk_corso\' 
								style="width:100%;padding: 6px 12px;font-size:14px;
								border-top-right-radius:0px;border-bottom-right-radius:0px;
								border-top-left-radius:0px;border-bottom-left-radius:0px;
								border:1px solid #ccc"
							class="select2-autocomplete form-control">';
		$html .= '<OPTION VALUE></OPTION>';

		foreach ($fk_corso_refval as $key => $value) {
			if ($value['id'] == $rowWinForm['fk_corso']) {
				$html .= "<option value='" . $value['id'] . "' SELECTED>" . $value['nome'] . "</option>";
			} else {
				$html .= "<option value='" . $value['id'] . "'>" . $value['nome'] . "</option>";
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
	 * 
	 * Funzione caricamento della finestra per la master details, tabella _mod_anagrafica_green_pass
	 * @param mixed $action
	 * @param string $entryID
	 * @param string $entryIDMasterDetails
	 * @return string
	 **/
	public function winMasterDetail_mod_anagrafica_green_pass($action, $entryID, $entryIDMasterDetails = NULL)
	{
		if ($entryIDMasterDetails == 'NULL') {
			$entryIDMasterDetails = '';
		}
		$rowWinForm = $this->modelClassModule->get_from_master_details_by_id($entryIDMasterDetails, '_mod_anagrafica_green_pass', 'id');
		$html = '<div>
				<section class="content">
					<div class="row">
						<div class="col-md-12">
							<div class="box box-primary">
								<div class="box-body">
								<div id="msg_err" ondblclick="this.style.display=\'none\';" style="display: none; border: 1px solid; background-color: rgb(255, 64, 0); border-radius: 5px; padding: 10px; color: white; font-weight: bold;">
								</div>									
									<form  name="frm_master_detail" id="frm_master_detail">
									<input type="hidden" id="table" name="table" value="_mod_anagrafica_green_pass">
									<input type="hidden" id="action" name="action" value="' . $action . '"/> 
									<input type="hidden" id="saveType" name="saveType" value="form"/> 	
									<input type="hidden" id="entryID"          name="entryID"  value="' . $entryID . '">
									<input type="hidden" id="fk_anagrafica"          name="fk_anagrafica"  value="' . $entryID . '">					
									<input type="hidden" id="entryIDMasterDetails" 		name="entryIDMasterDetails" value="' . $entryIDMasterDetails . '" >															
										<div class="col-md-12">
											<div class="form-group">';
		$html .= '<div class="form-group">';

		$html .= '<label for="datetime"><b style="color:#990000">(*)</b>Data Validita Fine </label>';

		$html .= '			<div class="input-group">';

		$html .= '				<div class="input-group-addon"><i class="fa fa-calendar"></i></div>';

		$html .= '<input type="text" class="form-control datetimemask" name="data_validita_fine" id="data_validita_fine" placeholder="Data Validita Fine" autocomplete="off" value="' . $rowWinForm['data_validita_fine'] . '" />';

		$html .= '</div></div>';
		$html .= '<div class="form-group">';

		$html .= '<label for="datetime"><b style="color:#990000">(*)</b>Data Validita Inizio </label>';

		$html .= '			<div class="input-group">';

		$html .= '				<div class="input-group-addon"><i class="fa fa-calendar"></i></div>';

		$html .= '<input type="text" class="form-control datetimemask" name="data_validita_inizio" id="data_validita_inizio" placeholder="Data Validita Inizio" autocomplete="off" value="' . $rowWinForm['data_validita_inizio'] . '" />';

		$html .= '</div></div>';
		$html .= '<div class="form-group">';

		$html .= '<label for="longblob"><b style="color:#990000">(*)</b>Documento Upload </label>';
 
		$html .= '				
												<div class="input-group">
													<div class="input-group-addon"><i class="fa fa-text-height"></i></div>';
		$html .= '<input type="file" class="form-control"  name="documento_upload" id="documento_upload"  />';
		$html .= '</div>';
		if ($rowWinForm['documento_upload'] != '') {
			$html .= "<a href='mod_anagrafica/scaricaAllegatoBlob/_mod_anagrafica_green_pass/documento_upload/".$rowWinForm['id']."' target='_blank'><img src=\"".base_url()."assets/images/download_multimedia_file_document_icon.png\"> ".$rowWinForm['nome_documento']."</a>";			
		}			
				
		$html .= '</div>';

		$html .= '<div class="form-group">';

		$html .= '<label for="tipo_green_pass"><b style="color:#990000">(*)</b>Tipo Green Pass </label>';

		$html .= '<SELECT name=\'tipo_green_pass\' id=\'tipo_green_pass\' 
										style="width:100%;padding: 6px 12px;font-size:14px;
										border-top-right-radius:0px;border-bottom-right-radius:0px;
										border-top-left-radius:0px;border-bottom-left-radius:0px;
										border:1px solid #ccc"
									class="form-control">';
		$html .= '<OPTION VALUE></OPTION>';
		if ($rowWinForm['tipo_green_pass'] == 'VACCINALE') {
			$html .= '<OPTION VALUE=\'VACCINALE\'  SELECTED>VACCINALE</OPTION>';
		} else {
			$html .= '<OPTION VALUE=\'VACCINALE\'  >VACCINALE</OPTION>';
		}
		if ($rowWinForm['tipo_green_pass'] == 'TAMPONE MOLECOLARE') {
			$html .= '<OPTION VALUE=\'TAMPONE MOLECOLARE\'  SELECTED>TAMPONE MOLECOLARE</OPTION>';
		} else {
			$html .= '<OPTION VALUE=\'TAMPONE MOLECOLARE\'  >TAMPONE MOLECOLARE</OPTION>';
		}
		if ($rowWinForm['tipo_green_pass'] == 'TAMPONE RAPIDO') {
			$html .= '<OPTION VALUE=\'TAMPONE RAPIDO\'  SELECTED>TAMPONE RAPIDO</OPTION>';
		} else {
			$html .= '<OPTION VALUE=\'TAMPONE RAPIDO\'  >TAMPONE RAPIDO</OPTION>';
		}
		$html .= '</SELECT>';

		$html .= '</div>';
		$html .= '
						<script>	
						$(\'[name=tipo_green_pass] option\').filter(function() { 
							return ($(this).text() =="' . $rowWinForm['tipo_green_pass'] . '"); //To select Blue
						}).prop(\'selected\', true);				
						</script>';

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
	 * Funzione caricamento della finestra per la master details, tabella _mod_anagrafica_green_pass_autocertificazione
	 * @param mixed $action
	 * @param string $entryID
	 * @param string $entryIDMasterDetails
	 * @return string
	 **/
	public function winMasterDetail_mod_anagrafica_green_pass_autocertificazione($action, $entryID, $entryIDMasterDetails = NULL)
	{
		if ($entryIDMasterDetails == 'NULL') {
			$entryIDMasterDetails = '';
		}
		$rowWinForm = $this->modelClassModule->get_from_master_details_by_id($entryIDMasterDetails, '_mod_anagrafica_green_pass_autocertificazione', 'id');
		$html = '<div>
				<section class="content">
					<div class="row">
						<div class="col-md-12">
							<div class="box box-primary">
								<div class="box-body">
								<div id="msg_err" ondblclick="this.style.display=\'none\';" style="display: none; border: 1px solid; background-color: rgb(255, 64, 0); border-radius: 5px; padding: 10px; color: white; font-weight: bold;">
								</div>									
									<form  name="frm_master_detail" id="frm_master_detail">
									<input type="hidden" id="table" name="table" value="_mod_anagrafica_green_pass_autocertificazione">
									<input type="hidden" id="action" name="action" value="' . $action . '"/> 
									<input type="hidden" id="saveType" name="saveType" value="form"/> 	
									<input type="hidden" id="entryID"          name="entryID"  value="' . $entryID . '">
									<input type="hidden" id="fk_anagrafica"          name="fk_anagrafica"  value="' . $entryID . '">
									<input type="hidden" id="entryIDMasterDetails" 		name="entryIDMasterDetails" value="' . $entryIDMasterDetails . '" >															
										<div class="col-md-12">
											<div class="form-group">';
		$html .= '<div class="form-group">';

		$html .= '<label for="date"><b style="color:#990000">(*)</b>Data Autocertificazione Fine Validita </label>';

		$html .= '<div class="input-group">';

		$html .= '<div class="input-group-addon"><i class="fa fa-calendar"></i></div>';

		$html .= '<input type="text" class="form-control datemask" name="data_autocertificazione_fine_validita" id="data_autocertificazione_fine_validita" placeholder="Data Autocertificazione Fine Validita"';

		if((!isset($rowWinForm['data_autocertificazione_fine_validita'])) || (is_null($rowWinForm['data_autocertificazione_fine_validita'])) || ($rowWinForm['data_autocertificazione_fine_validita'] == "")) {
			$rowWinForm['data_autocertificazione_fine_validita'] = date("Y-m-d");
		}
		$html .= 'autocomplete="off" style="background-color:#FFFFFF" value="' . $this->utilities->convertToDateIT($rowWinForm['data_autocertificazione_fine_validita']) . '" />';

		$html .= '</div></div>';

		$html .= '<label for="longblob"><b style="color:#990000">(*)</b>Documento Upload </label>';
		
		$html .= '				
												<div class="input-group">
													<div class="input-group-addon"><i class="fa fa-text-height"></i></div>';
		
		$html .= '<input type="file" class="form-control"  name="documento_upload" id="documento_upload"  />';
		$html .= '</div>';
		if ($rowWinForm['documento_upload'] != '') {
			$html .= "<a href='mod_anagrafica/scaricaAllegatoBlob/_mod_anagrafica_green_pass_autocertificazione/documento_upload/".$rowWinForm['id']."' target='_blank'><img src=\"".base_url()."assets/images/download_multimedia_file_document_icon.png\"> ".$rowWinForm['nome_documento']."</a>";			
		}			
		
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
	 * 
	 * Funzione caricamento della finestra per la master details, tabella _mod_anagrafica_green_pass_esentati
	 * @param mixed $action
	 * @param string $entryID
	 * @param string $entryIDMasterDetails
	 * @return string
	 **/
	public function winMasterDetail_mod_anagrafica_green_pass_esentati($action, $entryID, $entryIDMasterDetails = NULL)
	{
		if ($entryIDMasterDetails == 'NULL') {
			$entryIDMasterDetails = '';
		}
		$rowWinForm = $this->modelClassModule->get_from_master_details_by_id($entryIDMasterDetails, '_mod_anagrafica_green_pass_esentati', 'id');
		$html = '<div>
				<section class="content">
					<div class="row">
						<div class="col-md-12">
							<div class="box box-primary">
								<div class="box-body">
								<div id="msg_err" ondblclick="this.style.display=\'none\';" style="display: none; border: 1px solid; background-color: rgb(255, 64, 0); border-radius: 5px; padding: 10px; color: white; font-weight: bold;">
								</div>									
									<form  name="frm_master_detail" id="frm_master_detail">
									<input type="hidden" id="table" name="table" value="_mod_anagrafica_green_pass_esentati">
									<input type="hidden" id="action" name="action" value="' . $action . '"/> 
									<input type="hidden" id="saveType" name="saveType" value="form"/> 	
									<input type="hidden" id="entryID"          name="entryID"  value="' . $entryID . '">
									<input type="hidden" id="entryIDMasterDetails" 		name="entryIDMasterDetails" value="' . $entryIDMasterDetails . '" >															
										<div class="col-md-12">
											<div class="form-group">';
		$html .= '<div class="form-group">';

		$html .= '<label for="longblob"><b style="color:#990000">(*)</b>Certificato Medico Esenzione </label>';
		if ($rowWinForm['certificato_medico_esenzione'] != '') {
			$html .= '<img src="data:image/jpeg;base64,' . $rowWinForm['certificato_medico_esenzione.'] . '" style="width:90px"  />';
		}
		$html .= '				
												<div class="input-group">
													<div class="input-group-addon"><i class="fa fa-text-height"></i></div>';
		$html .= '<input type="file" class="form-control"  name="certificato_medico_esenzione" id="certificato_medico_esenzione"  />';
		$html .= '</div></div>';
		$fk_anagrafica_refval = $this->modelClassModule->getValuesByFk('mod_anagrafica', NULL, NULL);
		$html .= '<div class="form-group">';

		$html .= '<label for="fk_anagrafica"><b style="color:#990000">(*)</b>Alunno/Insegnante </label>';

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
							class="select2-autocomplete form-control">';
		$html .= '<OPTION VALUE></OPTION>';

		foreach ($fk_anagrafica_refval as $key => $value) {
			if ($value['id'] == $rowWinForm['fk_anagrafica']) {
				$html .= "<option value='" . $value['id'] . "' SELECTED>" . $value['nome'] . "</option>";
			} else {
				$html .= "<option value='" . $value['id'] . "'>" . $value['nome'] . "</option>";
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
	 * 
	 * Funzione caricamento della finestra per la master details, tabella _mod_anagrafica_tessere_assoc
	 * @param mixed $action
	 * @param string $entryID
	 * @param string $entryIDMasterDetails
	 * @return string
	 **/
	public function winMasterDetail_mod_anagrafica_tessere_assoc($action, $entryID, $entryIDMasterDetails = NULL)
	{
		if ($entryIDMasterDetails == 'NULL') {
			$entryIDMasterDetails = '';
		}
		$rowWinForm = $this->modelClassModule->get_from_master_details_by_id($entryIDMasterDetails, '_mod_anagrafica_tessere_assoc', 'id');
		$html = '<div>
				<section class="content">
					<div class="row">
						<div class="col-md-12">
							<div class="box box-primary">
								<div class="box-body">
								<div id="msg_err" ondblclick="this.style.display=\'none\';" style="display: none; border: 1px solid; background-color: rgb(255, 64, 0); border-radius: 5px; padding: 10px; color: white; font-weight: bold;">
								</div>									
									<form  name="frm_master_detail" id="frm_master_detail">
									<input type="hidden" id="table" name="table" value="_mod_anagrafica_tessere_assoc">
									<input type="hidden" id="action" name="action" value="' . $action . '"/> 
									<input type="hidden" id="saveType" name="saveType" value="form"/> 	
									<input type="hidden" id="entryID"          name="entryID"  value="' . $entryID . '">
									<input type="hidden" id="entryIDMasterDetails" 		name="entryIDMasterDetails" value="' . $entryIDMasterDetails . '" >															
										<div class="col-md-12">
											<div class="form-group">';
		$fk_affiliazione_refval = $this->modelClassModule->getValuesByFk('mod_affiliazioni', NULL, NULL);
		$html .= '<div class="form-group">';

		$html .= '<label for="fk_affiliazione"><b style="color:#990000">(*)</b>Affiliazione </label>';

		$html .= "<!-- 								
							
		<input list='fk_affiliazione_datalist' class='form-control combobox' name='fk_affiliazione' id='fk_affiliazione' value='<?php echo fk_affiliazione;?>'>
							
		<datalist name='fk_affiliazione_datalist' id='fk_affiliazione_datalist' onselect=\"alert(this.text)\">
							
		-->									
							
		<!-- -->";

		$html .= '<SELECT name=\'fk_affiliazione\' id=\'fk_affiliazione\' 
								style="width:100%;padding: 6px 12px;font-size:14px;
								border-top-right-radius:0px;border-bottom-right-radius:0px;
								border-top-left-radius:0px;border-bottom-left-radius:0px;
								border:1px solid #ccc"
							class="select2-autocomplete form-control">';
		$html .= '<OPTION VALUE></OPTION>';

		foreach ($fk_affiliazione_refval as $key => $value) {
			if ($value['id'] == $rowWinForm['fk_affiliazione']) {
				$html .= "<option value='" . $value['id'] . "' SELECTED>" . $value['nome'] . "</option>";
			} else {
				$html .= "<option value='" . $value['id'] . "'>" . $value['nome'] . "</option>";
			}
		}
		$html .= '</SELECT>';

		$html .= '</div>';
		$fk_anagrafica_refval = $this->modelClassModule->getValuesByFk('mod_anagrafica', NULL, NULL);
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
							class="select2-autocomplete form-control">';
		$html .= '<OPTION VALUE></OPTION>';

		foreach ($fk_anagrafica_refval as $key => $value) {
			if ($value['id'] == $rowWinForm['fk_anagrafica']) {
				$html .= "<option value='" . $value['id'] . "' SELECTED>" . $value['nome'] . "</option>";
			} else {
				$html .= "<option value='" . $value['id'] . "'>" . $value['nome'] . "</option>";
			}
		}
		$html .= '</SELECT>';

		$html .= '</div>';
		$html .= '<div class="form-group">';

		$html .= '<label for="varchar"><b style="color:#990000">(*)</b>Tessera Associativa</label>';

		$html .= '<div class="input-group">';

		$html .= '<div class="input-group-addon"><i class="fa fa-text-height"></i></div>';

		$html .= '<input type="text" class="form-control" maxlength=\'50\' name="tessera_associativa" id="tessera_associativa" placeholder="Tessera Associativa" autocomplete="off" value="' . $rowWinForm['tessera_associativa'] . '" />';

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
	 * 
	 * Funzione caricamento della finestra per la master details, tabella _mod_anagrafica_tessere_interne
	 * @param mixed $action
	 * @param string $entryID
	 * @param string $entryIDMasterDetails
	 * @return string
	 **/
	public function winMasterDetail_mod_anagrafica_tessere_interne($action, $entryID, $entryIDMasterDetails = NULL)
	{
		if ($entryIDMasterDetails == 'NULL') {
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
									<input type="hidden" id="action" name="action" value="' . $action . '"/> 
									<input type="hidden" id="saveType" name="saveType" value="form"/> 	
									<input type="hidden" id="entryID"          name="entryID"  value="' . $entryID . '">
									<input type="hidden" id="entryIDMasterDetails" 		name="entryIDMasterDetails" value="' . $entryIDMasterDetails . '" >															
										<div class="col-md-12">
											<div class="form-group">';
		$fk_anagrafica_refval = $this->modelClassModule->getValuesByFk('mod_anagrafica', NULL, NULL);
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
							class="select2-autocomplete form-control">';
		$html .= '<OPTION VALUE></OPTION>';

		foreach ($fk_anagrafica_refval as $key => $value) {
			if ($value['id'] == $rowWinForm['fk_anagrafica']) {
				$html .= "<option value='" . $value['id'] . "' SELECTED>" . $value['nome'] . "</option>";
			} else {
				$html .= "<option value='" . $value['id'] . "'>" . $value['nome'] . "</option>";
			}
		}
		$html .= '</SELECT>';

		$html .= '</div>';
		$fk_esercizio_refval = $this->modelClassModule->getValuesByFk('mod_esercizi', NULL, NULL);
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
							class="select2-autocomplete form-control">';
		$html .= '<OPTION VALUE></OPTION>';

		foreach ($fk_esercizio_refval as $key => $value) {
			if ($value['id'] == $rowWinForm['fk_esercizio']) {
				$html .= "<option value='" . $value['id'] . "' SELECTED>" . $value['nome'] . "</option>";
			} else {
				$html .= "<option value='" . $value['id'] . "'>" . $value['nome'] . "</option>";
			}
		}
		$html .= '</SELECT>';

		$html .= '</div>';
		$html .= '<div class="form-group">';

		$html .= '<label for="varchar"><b style="color:#990000">(*)</b>Tessera Interna</label>';

		$html .= '<div class="input-group">';

		$html .= '<div class="input-group-addon"><i class="fa fa-text-height"></i></div>';

		$html .= '<input type="text" class="form-control" maxlength=\'50\' name="tessera_interna" id="tessera_interna" placeholder="Tessera Interna" autocomplete="off" value="' . $rowWinForm['tessera_interna'] . '" />';

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
	 * 
	 * Funzione caricamento della finestra per la master details, tabella _mod_corsi_insegnanti
	 * @param mixed $action
	 * @param string $entryID
	 * @param string $entryIDMasterDetails
	 * @return string
	 **/
	public function winMasterDetail_mod_corsi_insegnanti($action, $entryID, $entryIDMasterDetails = NULL)
	{
		if ($entryIDMasterDetails == 'NULL') {
			$entryIDMasterDetails = '';
		}
		$rowWinForm = $this->modelClassModule->get_from_master_details_by_id($entryIDMasterDetails, '_mod_corsi_insegnanti', 'id');
		$html = '<div>
				<section class="content">
					<div class="row">
						<div class="col-md-12">
							<div class="box box-primary">
								<div class="box-body">
								<div id="msg_err" ondblclick="this.style.display=\'none\';" style="display: none; border: 1px solid; background-color: rgb(255, 64, 0); border-radius: 5px; padding: 10px; color: white; font-weight: bold;">
								</div>									
									<form  name="frm_master_detail" id="frm_master_detail">
									<input type="hidden" id="table" name="table" value="_mod_corsi_insegnanti">
									<input type="hidden" id="action" name="action" value="' . $action . '"/> 
									<input type="hidden" id="saveType" name="saveType" value="form"/> 	
									<input type="hidden" id="entryID"          name="entryID"  value="' . $entryID . '">
									<input type="hidden" id="entryIDMasterDetails" 		name="entryIDMasterDetails" value="' . $entryIDMasterDetails . '" >															
										<div class="col-md-12">
											<div class="form-group">';
		$fk_anagrafica_refval = $this->modelClassModule->getValuesByFk('mod_anagrafica', NULL, NULL);
		$html .= '<div class="form-group">';

		$html .= '<label for="fk_anagrafica">Insegnante </label>';

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
							class="select2-autocomplete form-control">';
		$html .= '<OPTION VALUE></OPTION>';

		foreach ($fk_anagrafica_refval as $key => $value) {
			if ($value['id'] == $rowWinForm['fk_anagrafica']) {
				$html .= "<option value='" . $value['id'] . "' SELECTED>" . $value['nome'] . "</option>";
			} else {
				$html .= "<option value='" . $value['id'] . "'>" . $value['nome'] . "</option>";
			}
		}
		$html .= '</SELECT>';

		$html .= '</div>';
		$fk_corso_refval = $this->modelClassModule->getValuesByFk('mod_corsi', NULL, NULL);
		$html .= '<div class="form-group">';

		$html .= '<label for="fk_corso"><b style="color:#990000">(*)</b>Corso </label>';

		$html .= "<!-- 								
							
		<input list='fk_corso_datalist' class='form-control combobox' name='fk_corso' id='fk_corso' value='<?php echo fk_corso;?>'>
							
		<datalist name='fk_corso_datalist' id='fk_corso_datalist' onselect=\"alert(this.text)\">
							
		-->									
							
		<!-- -->";

		$html .= '<SELECT name=\'fk_corso\' id=\'fk_corso\' 
								style="width:100%;padding: 6px 12px;font-size:14px;
								border-top-right-radius:0px;border-bottom-right-radius:0px;
								border-top-left-radius:0px;border-bottom-left-radius:0px;
								border:1px solid #ccc"
							class="select2-autocomplete form-control">';
		$html .= '<OPTION VALUE></OPTION>';

		foreach ($fk_corso_refval as $key => $value) {
			if ($value['id'] == $rowWinForm['fk_corso']) {
				$html .= "<option value='" . $value['id'] . "' SELECTED>" . $value['nome'] . "</option>";
			} else {
				$html .= "<option value='" . $value['id'] . "'>" . $value['nome'] . "</option>";
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
	 * 
	 * Funzione caricamento della finestra per la master details, tabella _mod_corsi_iscrizioni
	 * @param mixed $action
	 * @param string $entryID
	 * @param string $entryIDMasterDetails
	 * @return string
	 **/
	public function winMasterDetail_mod_corsi_iscrizioni($action, $entryID, $entryIDMasterDetails = NULL)
	{
		if ($entryIDMasterDetails == 'NULL') {
			$entryIDMasterDetails = '';
		}
		$rowWinForm = $this->modelClassModule->get_from_master_details_by_id($entryIDMasterDetails, '_mod_corsi_iscrizioni', 'id');
		$html = '<div>
				<section class="content">
					<div class="row">
						<div class="col-md-12">
							<div class="box box-primary">
								<div class="box-body">
								<div id="msg_err" ondblclick="this.style.display=\'none\';" style="display: none; border: 1px solid; background-color: rgb(255, 64, 0); border-radius: 5px; padding: 10px; color: white; font-weight: bold;">
								</div>									
									<form  name="frm_master_detail" id="frm_master_detail">
									<input type="hidden" id="table" name="table" value="_mod_corsi_iscrizioni">
									<input type="hidden" id="action" name="action" value="' . $action . '"/> 
									<input type="hidden" id="saveType" name="saveType" value="form"/> 	
									<input type="hidden" id="entryID"          name="entryID"  value="' . $entryID . '">
									<input type="hidden" id="entryIDMasterDetails" 		name="entryIDMasterDetails" value="' . $entryIDMasterDetails . '" >															
										<div class="col-md-12">
											<div class="form-group">';
		$html .= '<div class="form-group">';

		$html .= '<label for="anno"><b style="color:#990000">(*)</b>Anno </label>';

		$html .= '<SELECT name=\'anno\' id=\'anno\' 
										style="width:100%;padding: 6px 12px;font-size:14px;
										border-top-right-radius:0px;border-bottom-right-radius:0px;
										border-top-left-radius:0px;border-bottom-left-radius:0px;
										border:1px solid #ccc"
									class="form-control">';
		$html .= '<OPTION VALUE></OPTION>';
		if ($rowWinForm['anno'] == '2010') {
			$html .= '<OPTION VALUE=\'2010\'  SELECTED>2010</OPTION>';
		} else {
			$html .= '<OPTION VALUE=\'2010\'  >2010</OPTION>';
		}
		if ($rowWinForm['anno'] == '2011') {
			$html .= '<OPTION VALUE=\'2011\'  SELECTED>2011</OPTION>';
		} else {
			$html .= '<OPTION VALUE=\'2011\'  >2011</OPTION>';
		}
		if ($rowWinForm['anno'] == '2012') {
			$html .= '<OPTION VALUE=\'2012\'  SELECTED>2012</OPTION>';
		} else {
			$html .= '<OPTION VALUE=\'2012\'  >2012</OPTION>';
		}
		if ($rowWinForm['anno'] == '2013') {
			$html .= '<OPTION VALUE=\'2013\'  SELECTED>2013</OPTION>';
		} else {
			$html .= '<OPTION VALUE=\'2013\'  >2013</OPTION>';
		}
		if ($rowWinForm['anno'] == '2014') {
			$html .= '<OPTION VALUE=\'2014\'  SELECTED>2014</OPTION>';
		} else {
			$html .= '<OPTION VALUE=\'2014\'  >2014</OPTION>';
		}
		if ($rowWinForm['anno'] == '2015') {
			$html .= '<OPTION VALUE=\'2015\'  SELECTED>2015</OPTION>';
		} else {
			$html .= '<OPTION VALUE=\'2015\'  >2015</OPTION>';
		}
		if ($rowWinForm['anno'] == '2016') {
			$html .= '<OPTION VALUE=\'2016\'  SELECTED>2016</OPTION>';
		} else {
			$html .= '<OPTION VALUE=\'2016\'  >2016</OPTION>';
		}
		if ($rowWinForm['anno'] == '2017') {
			$html .= '<OPTION VALUE=\'2017\'  SELECTED>2017</OPTION>';
		} else {
			$html .= '<OPTION VALUE=\'2017\'  >2017</OPTION>';
		}
		if ($rowWinForm['anno'] == '2018') {
			$html .= '<OPTION VALUE=\'2018\'  SELECTED>2018</OPTION>';
		} else {
			$html .= '<OPTION VALUE=\'2018\'  >2018</OPTION>';
		}
		if ($rowWinForm['anno'] == '2019') {
			$html .= '<OPTION VALUE=\'2019\'  SELECTED>2019</OPTION>';
		} else {
			$html .= '<OPTION VALUE=\'2019\'  >2019</OPTION>';
		}
		if ($rowWinForm['anno'] == '2020') {
			$html .= '<OPTION VALUE=\'2020\'  SELECTED>2020</OPTION>';
		} else {
			$html .= '<OPTION VALUE=\'2020\'  >2020</OPTION>';
		}
		if ($rowWinForm['anno'] == '2021') {
			$html .= '<OPTION VALUE=\'2021\'  SELECTED>2021</OPTION>';
		} else {
			$html .= '<OPTION VALUE=\'2021\'  >2021</OPTION>';
		}
		if ($rowWinForm['anno'] == '2022') {
			$html .= '<OPTION VALUE=\'2022\'  SELECTED>2022</OPTION>';
		} else {
			$html .= '<OPTION VALUE=\'2022\'  >2022</OPTION>';
		}
		if ($rowWinForm['anno'] == '2023') {
			$html .= '<OPTION VALUE=\'2023\'  SELECTED>2023</OPTION>';
		} else {
			$html .= '<OPTION VALUE=\'2023\'  >2023</OPTION>';
		}
		if ($rowWinForm['anno'] == '2024') {
			$html .= '<OPTION VALUE=\'2024\'  SELECTED>2024</OPTION>';
		} else {
			$html .= '<OPTION VALUE=\'2024\'  >2024</OPTION>';
		}
		if ($rowWinForm['anno'] == '2025') {
			$html .= '<OPTION VALUE=\'2025\'  SELECTED>2025</OPTION>';
		} else {
			$html .= '<OPTION VALUE=\'2025\'  >2025</OPTION>';
		}
		if ($rowWinForm['anno'] == '2026') {
			$html .= '<OPTION VALUE=\'2026\'  SELECTED>2026</OPTION>';
		} else {
			$html .= '<OPTION VALUE=\'2026\'  >2026</OPTION>';
		}
		if ($rowWinForm['anno'] == '2027') {
			$html .= '<OPTION VALUE=\'2027\'  SELECTED>2027</OPTION>';
		} else {
			$html .= '<OPTION VALUE=\'2027\'  >2027</OPTION>';
		}
		if ($rowWinForm['anno'] == '2028') {
			$html .= '<OPTION VALUE=\'2028\'  SELECTED>2028</OPTION>';
		} else {
			$html .= '<OPTION VALUE=\'2028\'  >2028</OPTION>';
		}
		if ($rowWinForm['anno'] == '2029') {
			$html .= '<OPTION VALUE=\'2029\'  SELECTED>2029</OPTION>';
		} else {
			$html .= '<OPTION VALUE=\'2029\'  >2029</OPTION>';
		}
		if ($rowWinForm['anno'] == '2030') {
			$html .= '<OPTION VALUE=\'2030\'  SELECTED>2030</OPTION>';
		} else {
			$html .= '<OPTION VALUE=\'2030\'  >2030</OPTION>';
		}
		$html .= '</SELECT>';

		$html .= '</div>';
		$html .= '
						<script>	
						$(\'[name=anno] option\').filter(function() { 
							return ($(this).text() =="' . $rowWinForm['anno'] . '"); //To select Blue
						}).prop(\'selected\', true);				
						</script>';

		$fk_anagrafica_refval = $this->modelClassModule->getValuesByFk('mod_anagrafica', NULL, NULL);
		$html .= '<div class="form-group">';

		$html .= '<label for="fk_anagrafica">Alunno </label>';

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
							class="select2-autocomplete form-control">';
		$html .= '<OPTION VALUE></OPTION>';

		foreach ($fk_anagrafica_refval as $key => $value) {
			if ($value['id'] == $rowWinForm['fk_anagrafica']) {
				$html .= "<option value='" . $value['id'] . "' SELECTED>" . $value['nome'] . "</option>";
			} else {
				$html .= "<option value='" . $value['id'] . "'>" . $value['nome'] . "</option>";
			}
		}
		$html .= '</SELECT>';

		$html .= '</div>';
		$fk_corso_refval = $this->modelClassModule->getValuesByFk('mod_corsi', NULL, NULL);
		$html .= '<div class="form-group">';

		$html .= '<label for="fk_corso"><b style="color:#990000">(*)</b>Corso </label>';

		$html .= "<!-- 								
							
		<input list='fk_corso_datalist' class='form-control combobox' name='fk_corso' id='fk_corso' value='<?php echo fk_corso;?>'>
							
		<datalist name='fk_corso_datalist' id='fk_corso_datalist' onselect=\"alert(this.text)\">
							
		-->									
							
		<!-- -->";

		$html .= '<SELECT name=\'fk_corso\' id=\'fk_corso\' 
								style="width:100%;padding: 6px 12px;font-size:14px;
								border-top-right-radius:0px;border-bottom-right-radius:0px;
								border-top-left-radius:0px;border-bottom-left-radius:0px;
								border:1px solid #ccc"
							class="select2-autocomplete form-control">';
		$html .= '<OPTION VALUE></OPTION>';

		foreach ($fk_corso_refval as $key => $value) {
			if ($value['id'] == $rowWinForm['fk_corso']) {
				$html .= "<option value='" . $value['id'] . "' SELECTED>" . $value['nome'] . "</option>";
			} else {
				$html .= "<option value='" . $value['id'] . "'>" . $value['nome'] . "</option>";
			}
		}
		$html .= '</SELECT>';

		$html .= '</div>';
		$html .= '<div class="form-group">';

		$html .= '<label for="is_frequentato"><b style="color:#990000">(*)</b>Frequentato </label>';

		$html .= '<SELECT name=\'is_frequentato\' id=\'is_frequentato\' 
										style="width:100%;padding: 6px 12px;font-size:14px;
										border-top-right-radius:0px;border-bottom-right-radius:0px;
										border-top-left-radius:0px;border-bottom-left-radius:0px;
										border:1px solid #ccc"
									class="form-control">';
		$html .= '<OPTION VALUE></OPTION>';
		if ($rowWinForm['is_frequentato'] == 'SI') {
			$html .= '<OPTION VALUE=\'SI\'  SELECTED>SI</OPTION>';
		} else {
			$html .= '<OPTION VALUE=\'SI\'  >SI</OPTION>';
		}
		if ($rowWinForm['is_frequentato'] == 'NO') {
			$html .= '<OPTION VALUE=\'NO\'  SELECTED>NO</OPTION>';
		} else {
			$html .= '<OPTION VALUE=\'NO\'  >NO</OPTION>';
		}
		$html .= '</SELECT>';

		$html .= '</div>';
		$html .= '
						<script>	
						$(\'[name=is_frequentato] option\').filter(function() { 
							return ($(this).text() =="' . $rowWinForm['is_frequentato'] . '"); //To select Blue
						}).prop(\'selected\', true);				
						</script>';

		$html .= '<div class="form-group">';

		$html .= '<label for="mese"><b style="color:#990000">(*)</b>Mese </label>';

		$html .= '<SELECT name=\'mese\' id=\'mese\' 
										style="width:100%;padding: 6px 12px;font-size:14px;
										border-top-right-radius:0px;border-bottom-right-radius:0px;
										border-top-left-radius:0px;border-bottom-left-radius:0px;
										border:1px solid #ccc"
									class="form-control">';
		$html .= '<OPTION VALUE></OPTION>';
		if ($rowWinForm['mese'] == 'GENNAIO') {
			$html .= '<OPTION VALUE=\'GENNAIO\'  SELECTED>GENNAIO</OPTION>';
		} else {
			$html .= '<OPTION VALUE=\'GENNAIO\'  >GENNAIO</OPTION>';
		}
		if ($rowWinForm['mese'] == 'FEBBRAIO') {
			$html .= '<OPTION VALUE=\'FEBBRAIO\'  SELECTED>FEBBRAIO</OPTION>';
		} else {
			$html .= '<OPTION VALUE=\'FEBBRAIO\'  >FEBBRAIO</OPTION>';
		}
		if ($rowWinForm['mese'] == 'MARZO') {
			$html .= '<OPTION VALUE=\'MARZO\'  SELECTED>MARZO</OPTION>';
		} else {
			$html .= '<OPTION VALUE=\'MARZO\'  >MARZO</OPTION>';
		}
		if ($rowWinForm['mese'] == 'APRILE') {
			$html .= '<OPTION VALUE=\'APRILE\'  SELECTED>APRILE</OPTION>';
		} else {
			$html .= '<OPTION VALUE=\'APRILE\'  >APRILE</OPTION>';
		}
		if ($rowWinForm['mese'] == 'MAGGIO') {
			$html .= '<OPTION VALUE=\'MAGGIO\'  SELECTED>MAGGIO</OPTION>';
		} else {
			$html .= '<OPTION VALUE=\'MAGGIO\'  >MAGGIO</OPTION>';
		}
		if ($rowWinForm['mese'] == 'GIUGNO') {
			$html .= '<OPTION VALUE=\'GIUGNO\'  SELECTED>GIUGNO</OPTION>';
		} else {
			$html .= '<OPTION VALUE=\'GIUGNO\'  >GIUGNO</OPTION>';
		}
		if ($rowWinForm['mese'] == 'LUGLIO') {
			$html .= '<OPTION VALUE=\'LUGLIO\'  SELECTED>LUGLIO</OPTION>';
		} else {
			$html .= '<OPTION VALUE=\'LUGLIO\'  >LUGLIO</OPTION>';
		}
		if ($rowWinForm['mese'] == 'AGOSTO') {
			$html .= '<OPTION VALUE=\'AGOSTO\'  SELECTED>AGOSTO</OPTION>';
		} else {
			$html .= '<OPTION VALUE=\'AGOSTO\'  >AGOSTO</OPTION>';
		}
		if ($rowWinForm['mese'] == 'SETTEMBRE') {
			$html .= '<OPTION VALUE=\'SETTEMBRE\'  SELECTED>SETTEMBRE</OPTION>';
		} else {
			$html .= '<OPTION VALUE=\'SETTEMBRE\'  >SETTEMBRE</OPTION>';
		}
		if ($rowWinForm['mese'] == 'OTTOBRE') {
			$html .= '<OPTION VALUE=\'OTTOBRE\'  SELECTED>OTTOBRE</OPTION>';
		} else {
			$html .= '<OPTION VALUE=\'OTTOBRE\'  >OTTOBRE</OPTION>';
		}
		if ($rowWinForm['mese'] == 'NOVEMBRE') {
			$html .= '<OPTION VALUE=\'NOVEMBRE\'  SELECTED>NOVEMBRE</OPTION>';
		} else {
			$html .= '<OPTION VALUE=\'NOVEMBRE\'  >NOVEMBRE</OPTION>';
		}
		if ($rowWinForm['mese'] == 'DICEMBRE') {
			$html .= '<OPTION VALUE=\'DICEMBRE\'  SELECTED>DICEMBRE</OPTION>';
		} else {
			$html .= '<OPTION VALUE=\'DICEMBRE\'  >DICEMBRE</OPTION>';
		}
		$html .= '</SELECT>';

		$html .= '</div>';
		$html .= '
						<script>	
						$(\'[name=mese] option\').filter(function() { 
							return ($(this).text() =="' . $rowWinForm['mese'] . '"); //To select Blue
						}).prop(\'selected\', true);				
						</script>';

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
	 * Funzione caricamento della finestra per la master details, tabella _mod_insegnanti_discipline
	 * @param mixed $action
	 * @param string $entryID
	 * @param string $entryIDMasterDetails
	 * @return string
	 **/
	public function winMasterDetail_mod_insegnanti_discipline($action, $entryID, $entryIDMasterDetails = NULL)
	{
		if ($entryIDMasterDetails == 'NULL') {
			$entryIDMasterDetails = '';
		}
		$rowWinForm = $this->modelClassModule->get_from_master_details_by_id($entryIDMasterDetails, '_mod_insegnanti_discipline', 'id');
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
									<input type="hidden" id="action" name="action" value="' . $action . '"/> 
									<input type="hidden" id="saveType" name="saveType" value="form"/> 	
									<input type="hidden" id="entryID"          name="entryID"  value="' . $entryID . '">
									<input type="hidden" id="fk_anagrafica"    name="fk_anagrafica"  value="' . $entryID . '">
									<input type="hidden" id="entryIDMasterDetails" 		name="entryIDMasterDetails" value="' . $entryIDMasterDetails . '" >															
										<div class="col-md-12">
											<div class="form-group">';

		$where_condition = " WHERE id NOT IN (SELECT fk_disciplina FROM _mod_insegnanti_discipline WHERE fk_anagrafica = '$entryID' )";
		$fk_disciplina_refval = $this->modelClassModule->getValuesByFk('mod_discipline', NULL, NULL,$where_condition);
		$html .= '<div class="form-group">';

		$html .= '<label for="fk_disciplina"><b style="color:#990000">(*)</b>Disciplina </label>';

		$html .= "<!-- 								
							
		<input list='fk_disciplina_datalist' class='form-control combobox' name='fk_disciplina' id='fk_disciplina' value='<?php echo fk_disciplina;?>'>
							
		<datalist name='fk_disciplina_datalist' id='fk_disciplina_datalist' onselect=\"alert(this.text)\">
							
		-->									
							
		<!-- -->";

		$html .= '<SELECT name=\'fk_disciplina\' id=\'fk_disciplina\' 
								style="width:100%;padding: 6px 12px;font-size:14px;
								border-top-right-radius:0px;border-bottom-right-radius:0px;
								border-top-left-radius:0px;border-bottom-left-radius:0px;
								border:1px solid #ccc"
							class="select2-autocomplete form-control">';
		$html .= '<OPTION VALUE></OPTION>';

		foreach ($fk_disciplina_refval as $key => $value) {
			if ($value['id'] == $rowWinForm['fk_disciplina']) {
				$html .= "<option value='" . $value['id'] . "' SELECTED>" . $value['nome'] . "</option>";
			} else {
				$html .= "<option value='" . $value['id'] . "'>" . $value['nome'] . "</option>";
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
	 * Funzione caricamento della finestra per la master details,in modalita di inserimento multiplo, tabella _mod_anagrafica_certificati_medici
	 * @param mixed $action
	 * @param string $entryID
	 * @return string
	 **/
	public function winMasterDetailMulti_mod_anagrafica_certificati_medici($action, $entryID)
	{
		$html = '<div>
				<section class="content">
					<div class="row">
						<div class="col-md-12">
							<div class="box box-primary">
								<div class="box-body">
								<div id="msg_err" ondblclick="this.style.display=\'none\';" style="display: none; border: 1px solid; background-color: rgb(255, 64, 0); border-radius: 5px; padding: 10px; color: white; font-weight: bold;">
								</div>									
									<form  name="frm_master_detail" id="frm_master_detail">
									<input type="hidden" id="table" name="table" value="_mod_anagrafica_certificati_medici">
									<input type="hidden" id="action" name="action" value="' . $action . '"/> 
									<input type="hidden" id="saveType" name="saveType" value="form"/> 	
									<input type="hidden" id="entryID"          name="entryID"  value="' . $entryID . '">													
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
	 * Funzione caricamento della finestra per la master details,in modalita di inserimento multiplo, tabella _mod_anagrafica_corsi
	 * @param mixed $action
	 * @param string $entryID
	 * @return string
	 **/
	public function winMasterDetailMulti_mod_anagrafica_corsi($action, $entryID)
	{
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
									<input type="hidden" id="action" name="action" value="' . $action . '"/> 
									<input type="hidden" id="saveType" name="saveType" value="form"/> 	
									<input type="hidden" id="entryID"          name="entryID"  value="' . $entryID . '">													
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
	 * Funzione caricamento della finestra per la master details,in modalita di inserimento multiplo, tabella _mod_anagrafica_green_pass
	 * @param mixed $action
	 * @param string $entryID
	 * @return string
	 **/
	public function winMasterDetailMulti_mod_anagrafica_green_pass($action, $entryID)
	{
		$html = '<div>
				<section class="content">
					<div class="row">
						<div class="col-md-12">
							<div class="box box-primary">
								<div class="box-body">
								<div id="msg_err" ondblclick="this.style.display=\'none\';" style="display: none; border: 1px solid; background-color: rgb(255, 64, 0); border-radius: 5px; padding: 10px; color: white; font-weight: bold;">
								</div>									
									<form  name="frm_master_detail" id="frm_master_detail">
									<input type="hidden" id="table" name="table" value="_mod_anagrafica_green_pass">
									<input type="hidden" id="action" name="action" value="' . $action . '"/> 
									<input type="hidden" id="saveType" name="saveType" value="form"/> 	
									<input type="hidden" id="entryID"          name="entryID"  value="' . $entryID . '">													
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
	 * Funzione caricamento della finestra per la master details,in modalita di inserimento multiplo, tabella _mod_anagrafica_green_pass_autocertificazione
	 * @param mixed $action
	 * @param string $entryID
	 * @return string
	 **/
	public function winMasterDetailMulti_mod_anagrafica_green_pass_autocertificazione($action, $entryID)
	{
		$html = '<div>
				<section class="content">
					<div class="row">
						<div class="col-md-12">
							<div class="box box-primary">
								<div class="box-body">
								<div id="msg_err" ondblclick="this.style.display=\'none\';" style="display: none; border: 1px solid; background-color: rgb(255, 64, 0); border-radius: 5px; padding: 10px; color: white; font-weight: bold;">
								</div>									
									<form  name="frm_master_detail" id="frm_master_detail">
									<input type="hidden" id="table" name="table" value="_mod_anagrafica_green_pass_autocertificazione">
									<input type="hidden" id="action" name="action" value="' . $action . '"/> 
									<input type="hidden" id="saveType" name="saveType" value="form"/> 	
									<input type="hidden" id="entryID"          name="entryID"  value="' . $entryID . '">													
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
	 * Funzione caricamento della finestra per la master details,in modalita di inserimento multiplo, tabella _mod_anagrafica_green_pass_esentati
	 * @param mixed $action
	 * @param string $entryID
	 * @return string
	 **/
	public function winMasterDetailMulti_mod_anagrafica_green_pass_esentati($action, $entryID)
	{
		$html = '<div>
				<section class="content">
					<div class="row">
						<div class="col-md-12">
							<div class="box box-primary">
								<div class="box-body">
								<div id="msg_err" ondblclick="this.style.display=\'none\';" style="display: none; border: 1px solid; background-color: rgb(255, 64, 0); border-radius: 5px; padding: 10px; color: white; font-weight: bold;">
								</div>									
									<form  name="frm_master_detail" id="frm_master_detail">
									<input type="hidden" id="table" name="table" value="_mod_anagrafica_green_pass_esentati">
									<input type="hidden" id="action" name="action" value="' . $action . '"/> 
									<input type="hidden" id="saveType" name="saveType" value="form"/> 	
									<input type="hidden" id="entryID"          name="entryID"  value="' . $entryID . '">													
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
	 * Funzione caricamento della finestra per la master details,in modalita di inserimento multiplo, tabella _mod_anagrafica_tessere_assoc
	 * @param mixed $action
	 * @param string $entryID
	 * @return string
	 **/
	public function winMasterDetailMulti_mod_anagrafica_tessere_assoc($action, $entryID)
	{
		$html = '<div>
				<section class="content">
					<div class="row">
						<div class="col-md-12">
							<div class="box box-primary">
								<div class="box-body">
								<div id="msg_err" ondblclick="this.style.display=\'none\';" style="display: none; border: 1px solid; background-color: rgb(255, 64, 0); border-radius: 5px; padding: 10px; color: white; font-weight: bold;">
								</div>									
									<form  name="frm_master_detail" id="frm_master_detail">
									<input type="hidden" id="table" name="table" value="_mod_anagrafica_tessere_assoc">
									<input type="hidden" id="action" name="action" value="' . $action . '"/> 
									<input type="hidden" id="saveType" name="saveType" value="form"/> 	
									<input type="hidden" id="entryID"          name="entryID"  value="' . $entryID . '">													
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
	 * Funzione caricamento della finestra per la master details,in modalita di inserimento multiplo, tabella _mod_anagrafica_tessere_interne
	 * @param mixed $action
	 * @param string $entryID
	 * @return string
	 **/
	public function winMasterDetailMulti_mod_anagrafica_tessere_interne($action, $entryID)
	{
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
									<input type="hidden" id="action" name="action" value="' . $action . '"/> 
									<input type="hidden" id="saveType" name="saveType" value="form"/> 	
									<input type="hidden" id="entryID"          name="entryID"  value="' . $entryID . '">													
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
	 * Funzione caricamento della finestra per la master details,in modalita di inserimento multiplo, tabella _mod_corsi_insegnanti
	 * @param mixed $action
	 * @param string $entryID
	 * @return string
	 **/
	public function winMasterDetailMulti_mod_corsi_insegnanti($action, $entryID)
	{
		$html = '<div>
				<section class="content">
					<div class="row">
						<div class="col-md-12">
							<div class="box box-primary">
								<div class="box-body">
								<div id="msg_err" ondblclick="this.style.display=\'none\';" style="display: none; border: 1px solid; background-color: rgb(255, 64, 0); border-radius: 5px; padding: 10px; color: white; font-weight: bold;">
								</div>									
									<form  name="frm_master_detail" id="frm_master_detail">
									<input type="hidden" id="table" name="table" value="_mod_corsi_insegnanti">
									<input type="hidden" id="action" name="action" value="' . $action . '"/> 
									<input type="hidden" id="saveType" name="saveType" value="form"/> 	
									<input type="hidden" id="entryID"          name="entryID"  value="' . $entryID . '">													
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
	 * Funzione caricamento della finestra per la master details,in modalita di inserimento multiplo, tabella _mod_corsi_iscrizioni
	 * @param mixed $action
	 * @param string $entryID
	 * @return string
	 **/
	public function winMasterDetailMulti_mod_corsi_iscrizioni($action, $entryID)
	{
		$html = '<div>
				<section class="content">
					<div class="row">
						<div class="col-md-12">
							<div class="box box-primary">
								<div class="box-body">
								<div id="msg_err" ondblclick="this.style.display=\'none\';" style="display: none; border: 1px solid; background-color: rgb(255, 64, 0); border-radius: 5px; padding: 10px; color: white; font-weight: bold;">
								</div>									
									<form  name="frm_master_detail" id="frm_master_detail">
									<input type="hidden" id="table" name="table" value="_mod_corsi_iscrizioni">
									<input type="hidden" id="action" name="action" value="' . $action . '"/> 
									<input type="hidden" id="saveType" name="saveType" value="form"/> 	
									<input type="hidden" id="entryID"          name="entryID"  value="' . $entryID . '">													
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
	 * Funzione caricamento della finestra per la master details,in modalita di inserimento multiplo, tabella _mod_insegnanti_discipline
	 * @param mixed $action
	 * @param string $entryID
	 * @return string
	 **/
	public function winMasterDetailMulti_mod_insegnanti_discipline($action, $entryID)
	{
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
									<input type="hidden" id="action" name="action" value="' . $action . '"/> 
									<input type="hidden" id="saveType" name="saveType" value="form"/> 	
									<input type="hidden" id="entryID"          name="entryID"  value="' . $entryID . '">													
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
    public function stampaAutocertificazioneGreenPass($id)
    {
        $row = $this->modelClassModule->get_by_id($id);

        if ($row) {
			if(!file_exists(FCPATH . "/stampe/". $this->mod_name)){
				$oldmask = umask(0);
				mkdir(FCPATH . "/stampe/". $this->mod_name, 0777);
				umask($oldmask);				
			}
			$out = $this->stampa_autocert_greenpass_out($id);	
			file_put_contents(FCPATH . "/stampe/". $this->mod_name.'/'.$this->mod_title.'_'.date('Y-m-d_H_i').'.pdf', $out);		
        } else {
            $this->session->set_flashdata('error', 'Record non Trovato');
            redirect(site_url($this->mod_name));
        }
    }




	/**
	 * 
	 * Scarica un'allegato blob in un modulo - Adattamento per modulo anagrafica
	 * @param mixed $moduleName
     * @param mixed $fieldName
     * @param mixed $entryId
	 */
	public function scaricaAllegatoBlob($moduleName, $fieldName,$entryId){
		$this->load->helper('download');
		
		$res = $this->modelClassModule->getAllegatoBlob($moduleName, $fieldName,$entryId);

		//print'<pre>';print_r($res);die();
		if ($res[0]['allegato']) {
			$data = file_get_contents ($res[0]['allegato']);
			force_download ( $res[0]['nome_allegato'], $res[0]['allegato']);
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
    public function stampa_autocert_greenpass_out($id)
    {
        $this->global['pageTitle'] = $this->mod_title.' - Stampa';

        $dompdf = new Dompdf();

        $settings = $this->user_model->loadSettings();
        $company_logo = $settings[0]->company_logo;
        $company_name = $settings[0]->company_name;
        $company_code = $settings[0]->company_code;
        $company_email = $settings[0]->company_email;
        $company_phone = $settings[0]->company_phone;
        $company_address = $settings[0]->company_address;

        $row = $this->modelClassModule->get_by_id($id);

        if ($row) {
            $data = array(
                'company_name' => set_value('company_name', $company_name),
                'company_code' => set_value('company_code', $company_code),
                'company_email' => set_value('company_email', $company_email),
                'company_phone' => set_value('company_phone', $company_phone),
                'company_address' => set_value('company_address', $company_address),
                'company_logo' => set_value('company_logo', $company_logo),
            );
			foreach($this->formFields as $key => $property){
				$data[$property] = set_value($property, $row->$property);
			}	

			$anagrafica = $this->modelClassModule->getAnagrafica($id);
			$listaGreenPassAutoCert = $this->modelClassModule->get_by_id($id,"_mod_anagrafica_green_pass_autocertificazione","fk_anagrafica");

			$comuneNascita = $this->modelClassModule->getComune($anagrafica[0]['fk_comune_nascita']);
			$comuneResid   = $this->modelClassModule->getComune($anagrafica[0]['fk_comune_residenza']);

			$data['is_minorenne'] = $this->utilities->check_minorenne($this->utilities->convertToDateIT($data['datanascita']),12);

			if($data['is_minorenne'] == TRUE){
				$data['nome_minore'] = $anagrafica[0]['nome'];
				$data['cognome_minore'] = $anagrafica[0]['cognome'];
				$data['indirizzo_minore'] = $anagrafica[0]['indirizzo'];
				$data['cellulare_minore'] = $anagrafica[0]['cellulare'];
				$data['email_minore'] = $anagrafica[0]['email'];
				$data['datanascita_minore'] = $anagrafica[0]['datanascita'];
				$data['codfiscale_minore'] = $anagrafica[0]['codfiscale'];
				$data['tipo_documento_minore'] = $anagrafica[0]['tipo_documento'];
				$data['nr_documento_minore'] = $anagrafica[0]['nr_documento'];
				$data['comune_nascita_minore'] = $comuneNascita[0]['comune'];
				$data['comune_residenza_minore'] = $comuneResid[0]['comune'];			
				$data['prov_residenza_minore'] = $comuneResid[0]['codice_provincia'];	
				$data['cap_residenza_minore'] = $comuneResid[0]['cap'];	
				$data['sottoposto_regime_green_pass_minore'] = $anagrafica[0]['sottoposto_regime_green_pass'];	
			
				$anagraficaTutore = $this->modelClassModule->getAnagrafica($data['fk_tutore']);	
				$comuneNascitaTutore = $this->modelClassModule->getComune($anagraficaTutore[0]['fk_comune_nascita']);
				$comuneResidtutore   = $this->modelClassModule->getComune($anagraficaTutore[0]['fk_comune_residenza']);
	
				$data['nome'] = $anagraficaTutore[0]['nome'];
				$data['cognome'] = $anagraficaTutore[0]['cognome'];
				$data['indirizzo'] = $anagraficaTutore[0]['indirizzo'];
				$data['cellulare'] = $anagraficaTutore[0]['cellulare'];
				$data['email'] = $anagraficaTutore[0]['email'];
				$data['datanascita'] = $anagraficaTutore[0]['datanascita'];
				$data['codfiscale'] = $anagraficaTutore[0]['codfiscale'];
				$data['tipo_documento'] = $anagraficaTutore[0]['tipo_documento'];
				$data['nr_documento'] = $anagraficaTutore[0]['nr_documento'];
				$data['comune_nascita'] = $comuneNascitaTutore[0]['comune'];
				$data['comune_residenza'] = $comuneResidtutore[0]['comune'];			
				$data['prov_residenza'] = $comuneResidtutore[0]['codice_provincia'];	
				$data['cap_residenza'] = $comuneResidtutore[0]['cap'];	
				$data['sottoposto_regime_green_pass'] = $anagraficaTutore[0]['sottoposto_regime_green_pass'];	
			} else {
				$data['nome'] = $anagrafica[0]['nome'];
				$data['cognome'] = $anagrafica[0]['cognome'];
				$data['indirizzo'] = $anagrafica[0]['indirizzo'];
				$data['cellulare'] = $anagrafica[0]['cellulare'];
				$data['email'] = $anagrafica[0]['email'];
				$data['datanascita'] = $anagrafica[0]['datanascita'];
				$data['codfiscale'] = $anagrafica[0]['codfiscale'];
				$data['tipo_documento'] = $anagrafica[0]['tipo_documento'];
				$data['nr_documento'] = $anagrafica[0]['nr_documento'];
				$data['comune_nascita'] = $comuneNascita[0]['comune'];
				$data['comune_residenza'] = $comuneResid[0]['comune'];			
				$data['prov_residenza'] = $comuneResid[0]['codice_provincia'];	
				$data['cap_residenza'] = $comuneResid[0]['cap'];	
				$data['sottoposto_regime_green_pass'] = $anagrafica[0]['sottoposto_regime_green_pass'];					
			}

 
			if(isset($listaGreenPassAutoCert->data_autocertificazione_fine_validita)){
				$data['data_autocertificazione_fine_validita'] = $this->utilities->convertToDateIT($listaGreenPassAutoCert->data_autocertificazione_fine_validita);	
			} else {
				$data['data_autocertificazione_fine_validita'] = date("d/m/Y");

			}
 
            $view = $this->load->view($this->mod_name.'/autocertificazione_covid_pdf.php', $data, true);
            $dompdf->loadHtml($view);
            $dompdf->set_option("isPhpEnabled", true);

            $dompdf->setPaper('A4');

            $dompdf->render();

            $x = 520;
            $y = 820;
            ///$text = "Pagina {PAGE_NUM} di {PAGE_COUNT}";
            $font = $dompdf->getFontMetrics()->get_font('Helvetica', 'normal');
            $size = 10;
            $color = array(0, 0, 0);
            $word_space = 0.0;
            $char_space = 0.0;
            $angle = 0.0;

 
			$out = $dompdf->output();
			$dompdf->stream($this->mod_title.'_'.date('Y-m-d_H_i').'.pdf', array("Attachment" => false));
			
			return $out;
        } else {
            $this->session->set_flashdata('error', 'Record non Trovato');
            redirect(site_url($this->mod_name));
        }
    }	


	public function _rules()
	{
		$this->form_validation->set_rules('anagrafica_attributo[]', 'attributo anagrafica', 'trim|required');
		$this->form_validation->set_rules('cellulare', 'cellulare', 'trim|max_length[50]');
		$this->form_validation->set_rules('codfiscale', 'codice fiscale', 'trim|max_length[16]|required');
		$this->form_validation->set_rules('cognome', 'cognome', 'trim|max_length[50]|required');
		$this->form_validation->set_rules('datanascita', 'data di nascita', 'trim|required');
		$this->form_validation->set_rules('email', 'e-mail', 'trim|valid_email|max_length[100]');
		$this->form_validation->set_rules('fk_comune_nascita', 'comune di nascita', 'trim|numeric|max_length[10]|required');
		$this->form_validation->set_rules('fk_comune_residenza', 'comune residenza', 'trim|numeric|max_length[10]|required');
		$this->form_validation->set_rules('fk_tutore', 'tutore', 'trim|numeric|max_length[10]');
		$this->form_validation->set_rules('indirizzo', 'indirizzo', 'trim|max_length[100]|required');
		$this->form_validation->set_rules('nome', 'nome', 'trim|max_length[50]|required');
		$this->form_validation->set_rules('notetesto', 'note', 'trim|max_length[4294967295]');
		$this->form_validation->set_rules('sesso', 'sesso', 'trim|required');
		$this->form_validation->set_rules('sottoposto_regime_green_pass', 'sottoposto a regime di green pass', 'trim|required');
		$this->form_validation->set_rules('telefono', 'telefono', 'trim|max_length[50]');
		//$this->form_validation->set_rules('documento_hidden', 'Documento di identita', 'required');
		/*
		if (empty($_FILES['documento']['name'])){
			$this->form_validation->set_rules('documento_hidden', 'Documento di identita', 'required');
		}
		*/			
		//$this->form_validation->set_rules('nr_documento', 'Nr Documento', 'required');
		//$this->form_validation->set_rules('tipo_documento', 'Tipo Documento', 'required');

		$this->form_validation->set_rules('id', 'id', 'trim');
		$this->form_validation->set_error_delimiters('<span class="text-danger">', '</span>');
	}
}
