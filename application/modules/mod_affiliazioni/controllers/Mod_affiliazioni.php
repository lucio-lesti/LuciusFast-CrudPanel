<?php
if (!defined('BASEPATH')){
	exit('No direct script access allowed');
}
require APPPATH . '/libraries/BaseController.php';
use Dompdf\Dompdf;

class Mod_affiliazioni extends BaseController
{

	public function __construct()
	{
		parent::__construct();
		$this->load->model('Mod_affiliazioni_model');
		$this->mod_name = 'mod_affiliazioni';
		$this->mod_type = 'crud';
		$this->mod_title = 'Affiliazioni';
		$this->modelClassModule =  $this->Mod_affiliazioni_model;
		$this->pkIdName = 'id';
		$this->viewName_ListAjax = 'mod_affiliazioni_list_ajax';
		$this->viewName_FormROAjax = 'mod_affiliazioni_read_ajax';
		$this->viewName_FormAjax = 'mod_affiliazioni_form_ajax';


		/*** CUSTOMIZZAZIONE ERRORI MYSQL PER MODULO***/		
		$this->MsgDBConverted['insert']['error']['1062'] = "Esiste gia questo elemento per il modulo Affiliazioni";
		$this->MsgDBConverted['insert']['error']['1452'] = "Esiste gia questo elemento per il modulo Affiliazioni";
		$this->MsgDBConverted['update']['error']['1062'] = "Esiste gia questo elemento per il modulo Affiliazioni";
		$this->MsgDBConverted['update']['error']['1452'] = "Esiste gia questo elemento per il modulo Affiliazioni";
		$this->MsgDBConverted['insert_massive']['error']['1062'] = "Esiste gia questo elemento per il modulo Affiliazioni";
		$this->MsgDBConverted['insert_massive']['error']['1452'] = "Esiste gia questo elemento per il modulo Affiliazioni";
		$this->MsgDBConverted['update_massive']['error']['1062'] = "Esiste gia questo elemento per il modulo Affiliazioni";
		$this->MsgDBConverted['update_massive']['error']['1452'] = "Esiste gia questo elemento per il modulo Affiliazioni";
		$this->MsgDBConverted['delete']['error']['1217'] = "Impossibile eliminare questo elemento del modulo Affiliazioni. E' usato nei seguenti moduli:";
		$this->MsgDBConverted['delete_massive']['error']['1217'] = "Impossibile eliminare alcuni elementi del modulo Affiliazioni. Sono usati nei seguenti moduli:";


		/*** INIZIALIZZAZIONE CAMPI FORM***/		


		$this->setFormFields('nome');		
		//PER L'ARRAY DI REFERENZIAMENTO, USARE IL CONCAT PER CONCATENARE PIU CAMPI NEL CAMPO 'NOME'
		//ES.CONCAT(cognome," ",nome)
		$this->setFormFields('fk_ente','mod_enti',array("id" => 'id', "nome" => 'nome'));
		$this->setFormFields('fk_esercizio','mod_esercizi',array("id" => 'id', "nome" => 'nome'));
		$this->setFormFields('id');


	}


	/**
	 * 
	* Funzione caricamento della master details, tabella _mod_anagrafica_tessere_assoc
	* @param mixed $id
	* @param string $isAjax
	* @return string
	**/
	public function getMasterDetail_mod_anagrafica_tessere_assoc($id, $isAjax = 'FALSE'){
		$row =  $this->modelClassModule->getMasterDetail_mod_anagrafica_tessere_assoc($id, $isAjax);
		$html = '';
		$winFormType ="form";//VALORI ACCETTATI: {'multi','form'}
	
		if($isAjax == 'FALSE'){
			if($winFormType == "form"){
				$html .= '
							<br><a class="btn btn-primary" style="cursor:pointer" onclick="winFormMasterDetails(\'mod_affiliazioni\',\'winMasterDetail_mod_anagrafica_tessere_assoc\',\'insert\','.$id.',\'NULL\',\'NUOVO Anagrafati Tessere Associative\', arrayValidationFields,\'winMasterDetail_mod_anagrafica_tessere_assoc\',\'form\')">[ Aggiungi un elemento]</a><br>
							<br><br>';
			} else {
				$html .= '
						<br><a class="btn btn-primary" style="cursor:pointer" onclick="winFormMasterDetails(\'mod_affiliazioni\',\'winMasterDetailMulti_mod_anagrafica_tessere_assoc\',\'insert\','.$id.',\'NULL\',\'NUOVO Anagrafati Tessere Associative\', arrayValidationFields,\'winMasterDetailMulti_mod_anagrafica_tessere_assoc\',\'multi\')">[ Aggiungi un elemento]</a><br>
						<br><br>';
			}
		}
		$html .= ' <input  type="text" class="form-control" autocomplete="off" 
							id="search_mod_anagrafica_tessere_assoc" style="width:20%" placeholder="Cerca..."
							onkeypress="disableKeySubmit()"
							onkeyup="searchInMasterDetailsTable(\'search_mod_anagrafica_tessere_assoc\', \'tbl_mod_anagrafica_tessere_assoc\',4)"><br>';
		$html .= "<table class='TFtable' id='tbl_mod_anagrafica_tessere_assoc' style='font-size:12px'>
					<tr>
						<thead>
						<th class='sorting_disabled' rowspan='1' colspan='1' aria-label='' style='width:10%;'>
							<input type='checkbox' id='check_master_mod_anagrafica_tessere_assoc' name='check_master_mod_anagrafica_tessere_assoc' 
							onchange=\"selezionaDeselezionaTutti('check_master_mod_anagrafica_tessere_assoc','check_id_mod_anagrafica_tessere_assoc','btDeleteMass_mod_anagrafica_tessere_assoc')\">
						</th>";
		$html.='<th>Alunno</th>';
		$html.='<th>Affiliazione</th>';
		$html.='<th>Tessera Associativa</th>';
		$html.='</tr>';
		$html.='<tbody>';
		foreach($row as $key => $value){
			$html.="<tr>";

			$html.="<td><input type='checkbox' id='check_id_mod_anagrafica_tessere_assoc' name='check_id_mod_anagrafica_tessere_assoc' value='".$value['id_tesseramento']."' onchange=\"verificaNrCheckBoxSelezionati('check_id_mod_anagrafica_tessere_assoc','btDeleteMass_mod_anagrafica_tessere_assoc')\"></td>";
			$html.="<td><input type='hidden' id='id[]' name='id[]' value='".$value['id_tesseramento']."'>".$value['mod_anagrafica_nome']."</td>";
			$html.="<td><input type='hidden' id='id[]' name='id[]' value='".$value['id_tesseramento']."'>".$value['mod_affiliazioni_nome']."</td>";
			$html.="<td><input type='hidden' id='id[]' name='id[]' value='".$value['id_tesseramento']."'>".$value['tessera_associativa']."</td>";
			
			
			$html.='</tr>';
		}
		$html.='</tbody></table>';
 
		return $html;
	}


	/**
	* Funzione caricamento della finestra per la master details, tabella _mod_anagrafica_tessere_assoc
	* @param mixed $action
	* @param string $entryID
	* @param string $entryIDMasterDetails
	* @return string
	**/
	public function winMasterDetail_mod_anagrafica_tessere_assoc($action, $entryID, $entryIDMasterDetails = NULL){
		if($entryIDMasterDetails == 'NULL'){
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
									<input type="hidden" id="action" name="action" value="'.$action.'"/> 
									<input type="hidden" id="saveType" name="saveType" value="form"/> 	
									<input type="hidden" id="entryID"          name="entryID"  value="'.$entryID.'">
									<input type="hidden" id="entryIDMasterDetails" 		name="entryIDMasterDetails" value="'.$entryIDMasterDetails.'" >															
										<div class="col-md-12">
											<div class="form-group">';
		$fk_anagrafica_refval = $this->modelClassModule->getValuesByFk('mod_anagrafica',NULL, NULL);
		$html .= '<div class="form-group">';
		 
							$fk_anagrafica_label = NULL;
							foreach ($fk_anagrafica_refval as $key => $value) {
								if ($value['id'] == $rowWinForm['fk_anagrafica']) {
									$fk_anagrafica_label = $value['nome'];
								}  
							}
				 						
							
		$html .= '<label for="fk_anagrafica"><b style="color:#990000">(*)</b>Alunno </label>';
							
		$html .= "							
							
		<input autofocus class='form-control' autocomplete='off' 
								oninput='onInput(\"fk_anagrafica_datalist_inp\",\"fk_anagrafica_datalist\",\"fk_anagrafica\")'
								style='width:100%;padding: 6px 12px;font-size:14px;
									border-top-right-radius:0px;border-bottom-right-radius:0px;
									border-top-left-radius:0px;border-bottom-left-radius:0px;
									border:1px solid #ccc'						
								list='fk_anagrafica_datalist'  
								name='fk_anagrafica_datalist_inp' 
								id='fk_anagrafica_datalist_inp' value='$fk_anagrafica_label'>
							
		<input type='hidden' name='fk_anagrafica' id='fk_anagrafica' value='".$rowWinForm['fk_anagrafica']."' >
							
		<datalist name='fk_anagrafica_datalist' id='fk_anagrafica_datalist' >";	$html .= '<OPTION VALUE></OPTION>';

		foreach ($fk_anagrafica_refval as $key => $value) {
			if ($value['id'] == $rowWinForm['fk_anagrafica']) {
				$html .= "<option data-value='" . $value['id'] . "' SELECTED>" . $value['nome'] . "</option>";
			} else {
				$html .= "<option data-value='".$value['id'] . "'>" . $value['nome'] . "</option>";
			}
		}
		$html .= '</SELECT>';
										
		$html .= '</div>';
		$fk_affiliazione_refval = $this->modelClassModule->getValuesByFk('mod_affiliazioni',NULL, NULL);
		$html .= '<div class="form-group">';
		 
							$fk_affiliazione_label = NULL;
							foreach ($fk_affiliazione_refval as $key => $value) {
								if ($value['id'] == $rowWinForm['fk_affiliazione']) {
									$fk_affiliazione_label = $value['nome'];
								}  
							}
				 						
							
		$html .= '<label for="fk_affiliazione"><b style="color:#990000">(*)</b>Affiliazione </label>';
							
		$html .= "							
							
		<input autofocus class='form-control' autocomplete='off' 
								oninput='onInput(\"fk_affiliazione_datalist_inp\",\"fk_affiliazione_datalist\",\"fk_affiliazione\")'
								style='width:100%;padding: 6px 12px;font-size:14px;
									border-top-right-radius:0px;border-bottom-right-radius:0px;
									border-top-left-radius:0px;border-bottom-left-radius:0px;
									border:1px solid #ccc'						
								list='fk_affiliazione_datalist'  
								name='fk_affiliazione_datalist_inp' 
								id='fk_affiliazione_datalist_inp' value='$fk_affiliazione_label'>
							
		<input type='hidden' name='fk_affiliazione' id='fk_affiliazione' value='".$rowWinForm['fk_affiliazione']."' >
							
		<datalist name='fk_affiliazione_datalist' id='fk_affiliazione_datalist' >";	$html .= '<OPTION VALUE></OPTION>';

		foreach ($fk_affiliazione_refval as $key => $value) {
			if ($value['id'] == $rowWinForm['fk_affiliazione']) {
				$html .= "<option data-value='" . $value['id'] . "' SELECTED>" . $value['nome'] . "</option>";
			} else {
				$html .= "<option data-value='".$value['id'] . "'>" . $value['nome'] . "</option>";
			}
		}
		$html .= '</SELECT>';
										
		$html .= '</div>';
		$html .= '<div class="form-group">';
							
		$html .= '<label for="varchar"><b style="color:#990000">(*)</b>Tessera Associativa</label>';
							
		$html .= '<div class="input-group">';
							
		$html .= '<div class="input-group-addon"><i class="fa fa-text-height"></i></div>';		
							
		$html .= '<input type="text" class="form-control" maxlength=\'50\' name="tessera_associativa" id="tessera_associativa" placeholder="Tessera Associativa" autocomplete="off" value="'.$rowWinForm['tessera_associativa'].'" />';
							
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
	* Funzione caricamento della finestra per la master details,in modalita di inserimento multiplo, tabella _mod_anagrafica_tessere_assoc
	* @param mixed $action
	* @param string $entryID
	* @return string
	**/
	public function winMasterDetailMulti_mod_anagrafica_tessere_assoc($action, $entryID){
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
		$this->form_validation->set_rules('nome', 'nome affiliazione', 'trim|max_length[50]|required');
		$this->form_validation->set_rules('fk_ente', 'ente', 'trim|numeric|max_length[10]|required');
		$this->form_validation->set_rules('fk_esercizio', 'esercizio', 'trim|numeric|max_length[10]|required');

		$this->form_validation->set_rules('id', 'id', 'trim');
		$this->form_validation->set_error_delimiters('<span class="text-danger">', '</span>');

	}

}