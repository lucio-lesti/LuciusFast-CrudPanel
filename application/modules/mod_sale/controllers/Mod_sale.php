<?php
if (!defined('BASEPATH')){
	exit('No direct script access allowed');
}
require APPPATH . '/libraries/BaseController.php';
use Dompdf\Dompdf;

class Mod_sale extends BaseController
{

	public function __construct()
	{
		parent::__construct();
		$this->load->model('Mod_sale_model');
		$this->mod_name = 'mod_sale';
		$this->mod_type = 'crud';
		$this->mod_title = 'Sale';
		$this->modelClassModule =  $this->Mod_sale_model;
		$this->pkIdName = 'id';
		$this->viewName_ListAjax = 'mod_sale_list_ajax';
		$this->viewName_FormROAjax = 'mod_sale_read_ajax';
		$this->viewName_FormAjax = 'mod_sale_form_ajax';

		$this->MsgDBConverted['insert']['error']['1062'] = "Esiste gia questo elemento per il modulo Sale";
		$this->MsgDBConverted['insert']['error']['1452'] = "Esiste gia questo elemento per il modulo Sale";
		$this->MsgDBConverted['update']['error']['1062'] = "Esiste gia questo elemento per il modulo Sale";
		$this->MsgDBConverted['update']['error']['1452'] = "Esiste gia questo elemento per il modulo Sale";
		$this->MsgDBConverted['insert_massive']['error']['1062'] = "Esiste gia questo elemento per il modulo Sale";
		$this->MsgDBConverted['insert_massive']['error']['1452'] = "Esiste gia questo elemento per il modulo Sale";
		$this->MsgDBConverted['update_massive']['error']['1062'] = "Esiste gia questo elemento per il modulo Sale";
		$this->MsgDBConverted['update_massive']['error']['1452'] = "Esiste gia questo elemento per il modulo Sale";
		$this->MsgDBConverted['delete']['error']['1217'] = "Impossibile eliminare questo elemento del modulo Sale. E' usato nei seguenti moduli:";
		$this->MsgDBConverted['delete_massive']['error']['1217'] = "Impossibile eliminare alcuni elementi del modulo Sale. Sono usati nei seguenti moduli:";

		//NOTE:NELLA FUNZIONE 'setFormFields' INDICARE NEL VETTORE CHE SI COLLEGA ALLA TABELLA REFERENZIATA
		//ALLA CHIAVE 'NOME', IL NOMINATIVO DEL CAMPO COLLEGATO
		$this->setFormFields('capienza');
		//PER L'ARRAY DI REFERENZIAMENTO, USARE IL CONCAT PER CONCATENARE PIU CAMPI NEL CAMPO 'NOME'
		//ES.CONCAT(cognome," ",nome)
		$this->setFormFields('fk_sede','mod_sedi',array("id" => 'id', "nome" => 'nome'));
		$this->setFormFields('nome');
		$this->setFormFields('id');

		$this->addMasterDetailsLoadFunc('getMasterDetail_mod_corsi_sale_calendario','Calendario Corsi Sala','getMasterDetail_mod_corsi_sale_calendario');

		//ABILITARE PER LE OPERAZIONI "CUSTOM"

		//LA CHIAVE DEL VETTORE "custom_operations_list" RAPPRESENTA IL NOME DELLA FUNZIONE
		//QUESTO PER AVERE UN CODICE ORDINATO E PER EVITARE CHE LE FUNZIONI CUSTOM NON VENGANO RICHIAMATE CORRETTAMENTE
		/*$this->custom_operations_list['mod_sale_check_date'] = function($request, $id = NULL){
			$ret = $this->utilities->check_date_greater_then($request['data_da'], $request['data_a']);
			if($ret === FALSE){
				$this->session->set_flashdata('error',"Data Da non puo essere maggiore di Data a");
				return false;
			}				
		};*/

	}


	/**
	* Funzione caricamento della master details, tabella _mod_corsi_sale_calendario
	* @param mixed $id
	* @param string $isAjax
	* @return string
	**/
	public function getMasterDetail_mod_corsi_sale_calendario($id, $isAjax = 'FALSE'){
		$row =  $this->modelClassModule->getMasterDetail_mod_corsi_sale_calendario($id, $isAjax);
		$html = '';
		$winFormType ="form";//VALORI ACCETTATI: {'multi','form'}
	
 
		$html .= ' <br><br><input  type="text" class="form-control" autocomplete="off" 
							id="search_mod_corsi_sale_calendario" style="width:20%" placeholder="Cerca..."
							onkeypress="disableKeySubmit()"
							onkeyup="searchInMasterDetailsTable(\'search_mod_corsi_sale_calendario\', \'tbl_mod_corsi_sale_calendario\',7)"><br>';
		$html .= "<table class='TFtable' id='tbl_mod_corsi_sale_calendario' style='font-size:12px'>
					<tr>
						<thead>
";
		$html.='<th>Corso</th>';
		$html.='<th>Data Da</th>';	
		$html.='<th>Ora Da</th>';
		$html.='<th>Data A</th>';
		$html.='<th>Ora A</th>';
		
 
 
		$html.='</tr>';
		$html.='<tbody>';
		foreach($row as $key => $value){
			$html.="<tr>";
			$html.="<td><input type='hidden' id='id[]' name='id[]' value='".$value['id']."'>".$value['mod_corsi_nome']."</td>"; 
			$html.="<td><input type='hidden' id='id[]' name='id[]' value='".$value['id']."'>".$value['data_corso_da']."</td>";
			$html.="<td><input type='hidden' id='id[]' name='id[]' value='".$value['id']."'>".$value['ora_corso_da']."</td>";
			$html.="<td><input type='hidden' id='id[]' name='id[]' value='".$value['id']."'>".$value['data_corso_a']."</td>";
			$html.="<td><input type='hidden' id='id[]' name='id[]' value='".$value['id']."'>".$value['ora_corso_a']."</td>";

			$html.='</tr>';
		}
		$html.='</tbody></table>';
 
		return $html;
	}


	/**
	* Funzione caricamento della finestra per la master details, tabella _mod_corsi_sale_calendario
	* @param mixed $action
	* @param string $entryID
	* @param string $entryIDMasterDetails
	* @return string
	**/
	public function winMasterDetail_mod_corsi_sale_calendario($action, $entryID, $entryIDMasterDetails = NULL){
		if($entryIDMasterDetails == 'NULL'){
			$entryIDMasterDetails = '';
		}
		$rowWinForm = $this->modelClassModule->get_from_master_details_by_id($entryIDMasterDetails, '_mod_corsi_sale_calendario', 'id');
		$html = '<div>
				<section class="content">
					<div class="row">
						<div class="col-md-12">
							<div class="box box-primary">
								<div class="box-body">
								<div id="msg_err" ondblclick="this.style.display=\'none\';" style="display: none; border: 1px solid; background-color: rgb(255, 64, 0); border-radius: 5px; padding: 10px; color: white; font-weight: bold;">
								</div>									
									<form  name="frm_master_detail" id="frm_master_detail">
									<input type="hidden" id="table" name="table" value="_mod_corsi_sale_calendario">
									<input type="hidden" id="action" name="action" value="'.$action.'"/> 
									<input type="hidden" id="saveType" name="saveType" value="form"/> 	
									<input type="hidden" id="entryID"          name="entryID"  value="'.$entryID.'">
									<input type="hidden" id="entryIDMasterDetails" 		name="entryIDMasterDetails" value="'.$entryIDMasterDetails.'" >															
										<div class="col-md-12">
											<div class="form-group">';
		$html .= '<div class="form-group">';
									
		$html .= '<label for="date"><b style="color:#990000">(*)</b>Data Corso A	 </label>';
									
		$html .= '<div class="input-group">';
									
		$html .= '<div class="input-group-addon"><i class="fa fa-calendar"></i></div>';			
									
		$html .= '<input type="text" class="form-control datemask" name="data_corso_a" id="data_corso_a" placeholder="Data Corso A	"';
									
		$html .= 'autocomplete="off" style="background-color:#FFFFFF" value="'.$rowWinForm['data_corso_a'].'" />';
									
		$html .= '</div></div>';
		$html .= '<div class="form-group">';
									
		$html .= '<label for="date"><b style="color:#990000">(*)</b>Data Corso Da </label>';
									
		$html .= '<div class="input-group">';
									
		$html .= '<div class="input-group-addon"><i class="fa fa-calendar"></i></div>';			
									
		$html .= '<input type="text" class="form-control datemask" name="data_corso_da" id="data_corso_da" placeholder="Data Corso Da"';
									
		$html .= 'autocomplete="off" style="background-color:#FFFFFF" value="'.$rowWinForm['data_corso_da'].'" />';
									
		$html .= '</div></div>';
		$fk_corso_refval = $this->modelClassModule->getValuesByFk('mod_corsi',NULL, NULL);
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
							class="select2-autocomplete form-control">';	$html .= '<OPTION VALUE></OPTION>';

		foreach ($fk_corso_refval as $key => $value) {
			if ($value['id'] == $rowWinForm['fk_corso']) {
				$html .= "<option value='" . $value['id'] . "' SELECTED>" . $value['nome'] . "</option>";
			} else {
				$html .= "<option value='".$value['id'] . "'>" . $value['nome'] . "</option>";
			}
		}
		$html .= '</SELECT>';
										
		$html .= '</div>';
		$fk_sala_refval = $this->modelClassModule->getValuesByFk('mod_sale',NULL, NULL);
		$html .= '<div class="form-group">';
							
		$html .= '<label for="fk_sala"><b style="color:#990000">(*)</b>Sala </label>';
							
		$html .= "<!-- 								
							
		<input list='fk_sala_datalist' class='form-control combobox' name='fk_sala' id='fk_sala' value='<?php echo fk_sala;?>'>
							
		<datalist name='fk_sala_datalist' id='fk_sala_datalist' onselect=\"alert(this.text)\">
							
		-->									
							
		<!-- -->";	
							
		$html .= '<SELECT name=\'fk_sala\' id=\'fk_sala\' 
								style="width:100%;padding: 6px 12px;font-size:14px;
								border-top-right-radius:0px;border-bottom-right-radius:0px;
								border-top-left-radius:0px;border-bottom-left-radius:0px;
								border:1px solid #ccc"
							class="select2-autocomplete form-control">';	$html .= '<OPTION VALUE></OPTION>';

		foreach ($fk_sala_refval as $key => $value) {
			if ($value['id'] == $rowWinForm['fk_sala']) {
				$html .= "<option value='" . $value['id'] . "' SELECTED>" . $value['nome'] . "</option>";
			} else {
				$html .= "<option value='".$value['id'] . "'>" . $value['nome'] . "</option>";
			}
		}
		$html .= '</SELECT>';
										
		$html .= '</div>';
		$html .= '<div class="form-group">';
							
		$html .= '<label for="varchar"><b style="color:#990000">(*)</b>Ora A</label>';
							
		$html .= '<div class="input-group">';
							
		$html .= '<div class="input-group-addon"><i class="fa fa-text-calendar"></i></div>';		
							
		$html .= '<input type="text" class="form-control timemask" maxlength=\'5\' name="ora_corso_a" id="ora_corso_a" placeholder="Ora A" autocomplete="off" value="'.$rowWinForm['ora_corso_a'].'" />';
							
		$html .= '</div></div>';
		$html .= '<div class="form-group">';
							
		$html .= '<label for="varchar"><b style="color:#990000">(*)</b>Ora Da</label>';
							
		$html .= '<div class="input-group">';
							
		$html .= '<div class="input-group-addon"><i class="fa fa-text-calendar"></i></div>';		
							
		$html .= '<input type="text" class="form-control timemask" maxlength=\'5\' name="ora_corso_da" id="ora_corso_da" placeholder="Ora Da" autocomplete="off" value="'.$rowWinForm['ora_corso_da'].'" />';
							
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
	* Funzione caricamento della finestra per la master details,in modalita di inserimento multiplo, tabella _mod_corsi_sale_calendario
	* @param mixed $action
	* @param string $entryID
	* @return string
	**/
	public function winMasterDetailMulti_mod_corsi_sale_calendario($action, $entryID){
		$html = '<div>
				<section class="content">
					<div class="row">
						<div class="col-md-12">
							<div class="box box-primary">
								<div class="box-body">
								<div id="msg_err" ondblclick="this.style.display=\'none\';" style="display: none; border: 1px solid; background-color: rgb(255, 64, 0); border-radius: 5px; padding: 10px; color: white; font-weight: bold;">
								</div>									
									<form  name="frm_master_detail" id="frm_master_detail">
									<input type="hidden" id="table" name="table" value="_mod_corsi_sale_calendario">
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
		$this->form_validation->set_rules('capienza', 'capienza', 'trim|numeric|max_length[10]|required');
		$this->form_validation->set_rules('fk_sede', 'sede', 'trim|numeric|max_length[10]|required');
		$this->form_validation->set_rules('nome', 'nome sala', 'trim|max_length[100]|required');

		$this->form_validation->set_rules('id', 'id', 'trim');
		$this->form_validation->set_error_delimiters('<span class="text-danger">', '</span>');

	}

}