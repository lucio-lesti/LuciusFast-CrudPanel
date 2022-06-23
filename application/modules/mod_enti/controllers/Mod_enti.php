<?php
if (!defined('BASEPATH')){
	exit('No direct script access allowed');
}
require APPPATH . '/libraries/BaseController.php';
use Dompdf\Dompdf;

class Mod_enti extends BaseController
{

	public function __construct()
	{
		parent::__construct();
		$this->load->model('Mod_enti_model');
		$this->mod_name = 'mod_enti';
		$this->mod_type = 'crud';
		$this->mod_title = 'Enti';
		$this->modelClassModule =  $this->Mod_enti_model;
		$this->pkIdName = 'id';
		$this->viewName_ListAjax = 'mod_enti_list_ajax';
		$this->viewName_FormROAjax = 'mod_enti_read_ajax';
		$this->viewName_FormAjax = 'mod_enti_form_ajax';

		$this->MsgDBConverted['insert']['error']['1062'] = "Esiste gia questo elemento per il modulo Enti";
		$this->MsgDBConverted['insert']['error']['1452'] = "Esiste gia questo elemento per il modulo Enti";
		$this->MsgDBConverted['update']['error']['1062'] = "Esiste gia questo elemento per il modulo Enti";
		$this->MsgDBConverted['update']['error']['1452'] = "Esiste gia questo elemento per il modulo Enti";
		$this->MsgDBConverted['insert_massive']['error']['1062'] = "Esiste gia questo elemento per il modulo Enti";
		$this->MsgDBConverted['insert_massive']['error']['1452'] = "Esiste gia questo elemento per il modulo Enti";
		$this->MsgDBConverted['update_massive']['error']['1062'] = "Esiste gia questo elemento per il modulo Enti";
		$this->MsgDBConverted['update_massive']['error']['1452'] = "Esiste gia questo elemento per il modulo Enti";
		$this->MsgDBConverted['delete']['error']['1217'] = "Impossibile eliminare questo elemento del modulo Enti. E' usato nei seguenti moduli:";
		$this->MsgDBConverted['delete_massive']['error']['1217'] = "Impossibile eliminare alcuni elementi del modulo Enti. Sono usati nei seguenti moduli:";

		//NOTE:NELLA FUNZIONE 'setFormFields' INDICARE NEL VETTORE CHE SI COLLEGA ALLA TABELLA REFERENZIATA
		//ALLA CHIAVE 'NOME', IL NOMINATIVO DEL CAMPO COLLEGATO
		$this->setFormFields('email');
		//PER L'ARRAY DI REFERENZIAMENTO, USARE IL CONCAT PER CONCATENARE PIU CAMPI NEL CAMPO 'NOME'
		//ES.CONCAT(cognome," ",nome)
		$this->setFormFields('fk_comune','mod_comuni',array("id" => 'istat', "nome" => 'comune'));
		$this->setFormFields('indirizzo');
		$this->setFormFields('nome');
		$this->setFormFields('telefono');
		$this->setFormFields('id');

		$this->addMasterDetailsLoadFunc('getMasterDetail_mod_enti_discipline','Discipline praticate','getMasterDetail_mod_enti_discipline');

		//ABILITARE PER LE OPERAZIONI "CUSTOM"

		//LA CHIAVE DEL VETTORE "custom_operations_list" RAPPRESENTA IL NOME DELLA FUNZIONE
		//QUESTO PER AVERE UN CODICE ORDINATO E PER EVITARE CHE LE FUNZIONI CUSTOM NON VENGANO RICHIAMATE CORRETTAMENTE
		/*$this->custom_operations_list['mod_enti_check_date'] = function($request, $id = NULL){
			$ret = $this->utilities->check_date_greater_then($request['data_da'], $request['data_a']);
			if($ret === FALSE){
				$this->session->set_flashdata('error',"Data Da non puo essere maggiore di Data a");
				return false;
			}				
		};*/

	}


	/**
	* Funzione caricamento della master details, tabella _mod_enti_discipline
	* @param mixed $id
	* @param string $isAjax
	* @return string
	**/
	public function getMasterDetail_mod_enti_discipline($id, $isAjax = 'FALSE'){
		$row =  $this->modelClassModule->getMasterDetail_mod_enti_discipline($id, $isAjax);
		$html = '';
		$winFormType ="multi";//VALORI ACCETTATI: {'multi','form'}
	
		if($isAjax == 'FALSE'){
			if($winFormType == "form"){
				$html .= '
							<br><a class="btn btn-primary" style="cursor:pointer" onclick="winFormMasterDetails(\'mod_enti\',\'winMasterDetail_mod_enti_discipline\',\'insert\','.$id.',\'NULL\',\'Aggiungi discipline\', arrayValidationFields,\'winMasterDetail_mod_enti_discipline\',\'form\')">[ Aggiungi un elemento]</a><br>
							<br><br>';
			} else {
				$html .= '
						<br><a class="btn btn-primary" style="cursor:pointer" onclick="winFormMasterDetails(\'mod_enti\',\'winMasterDetail_mod_enti_discipline\',\'insert\','.$id.',\'NULL\',\'Aggiungi discipline\', arrayValidationFields,\'winMasterDetail_mod_enti_discipline\',\'multi\')">[ Aggiungi un elemento]</a><br>
						<br><br>';
			}
		}
		$html .= ' <input  type="text" class="form-control" autocomplete="off" 
							id="search_mod_enti_discipline" style="width:20%" placeholder="Cerca..."
							onkeypress="disableKeySubmit()"
							onkeyup="searchInMasterDetailsTable(\'search_mod_enti_discipline\', \'tbl_mod_enti_discipline\',3)"><br>';
		$html .= "<table class='TFtable' id='tbl_mod_enti_discipline' style='font-size:12px'>
					<tr>
						<thead>
						<th class='sorting_disabled' rowspan='1' colspan='1' aria-label='' style='width:10%;'>
							<input type='checkbox' id='check_master_mod_enti_discipline' name='check_master_mod_enti_discipline' 
							onchange=\"selezionaDeselezionaTutti('check_master_mod_enti_discipline','check_id_mod_enti_discipline','btDeleteMass_mod_enti_discipline')\">
						</th>";
		$html.='<th>Disciplina</th>';

		$html.='<th>Elimina</th>';
		$html.='</tr>';
		$html.='<tbody>';
		foreach($row as $key => $value){
			$html.="<tr>";
			$html.="<td><input type='checkbox' id='check_id_mod_enti_discipline' name='check_id_mod_enti_discipline' value='".$value['id']."' onchange=\"verificaNrCheckBoxSelezionati('check_id_mod_enti_discipline','btDeleteMass_mod_enti_discipline')\"></td>";
			$html.="<td><input type='hidden' id='id[]' name='id[]' value='".$value['id']."'>".$value['mod_discipline_nome']."</td>";

			$html.="<td><a style='cursor:pointer' class='btn btn-sm btn-danger deleteUser' onclick ='deleteMasterDetails(".$value['id'].", ".$id.", \"mod_enti\",\"_mod_enti_discipline\",\"getMasterDetail_mod_enti_discipline\")' title='Elimina'><i class='fa fa-trash'></a></td>";
			$html.='</tr>';
		}
		$html.='</tbody></table>';
		$html.='<br/><a class="btn btn-sm btn-danger deleteUser" id="btDeleteMass_mod_enti_discipline" name="btDeleteMass_mod_enti_discipline""
					onclick="deleteMassiveMasterDetails('.$id.',\'entry_list\',\'check_id_mod_enti_discipline\',\'mod_enti\',\'_mod_enti_discipline\',\'getMasterDetail_mod_enti_discipline\')">
					<i class="fa fa-trash"></i> Cancellazione Massiva
				</a>';
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
		$rowWinForm = $this->modelClassModule->get_from_master_details_by_id($entryIDMasterDetails, '_mod_enti_discipline', 'id');
		
		$row = $this->modelClassModule->getAllDisciplineNonAssociateEnte($entryID);
		//print'<pre>';print_r($row);
		$html = '<div>
				<section class="content">
					<div class="row">
						<div class="col-md-12">
							<div class="box box-primary">
								<div class="box-body">
								<div id="msg_err" ondblclick="this.style.display=\'none\';" style="display: none; border: 1px solid; background-color: rgb(255, 64, 0); border-radius: 5px; padding: 10px; color: white; font-weight: bold;">
								</div>									
									<form  name="frm_master_detail" id="frm_master_detail">
									<input type="hidden" id="table" name="table" value="_mod_enti_discipline">
									<input type="hidden" id="action" name="action" value="'.$action.'"/> 
									<input type="hidden" id="saveType" name="saveType" value="multi"/> 	
									<input type="hidden" id="entryID"          name="entryID"  value="'.$entryID.'">
									<input type="hidden" id="fk_ente"          name="fk_ente"  value="'.$entryID.'">
									<input type="hidden" id="entryIDMasterDetails" 		name="entryIDMasterDetails" value="'.$entryIDMasterDetails.'" >															
										<div class="col-md-12">
											<div class="form-group">';

		$html .= "<table class='TFtable' id='table_insert_master_detail' style='font-size:12px'>";
		$html .= "<tr class=\"header\">";
		$html .= "<th  style='width:5%'><input type='checkbox' onchange=\"selezionaDeselezionaTutti('entry_to_delete_master','fk_disciplina[]','btDeleteMass')\"
			name='entry_to_delete_master' id='entry_to_delete_master'></th>";
		
		$html .= "<th  style='width:20%'><b style='font-size:14px'><b>Disciplina</b></b>
		</th>";
		$html .= "
			</tr>";	
		foreach($row as $key => $value){
			$html .= "<tr>";
			$html .= "<td><input type='checkbox' class='fk_disciplina' name='fk_disciplina[]' id='fk_disciplina[]' value=".$value['id']."></td>";
			$html .= "<td>".$value['nome']."</td>";											
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



	public function _rules()
	{
		$this->form_validation->set_rules('email', 'e-mail', 'trim|valid_email|max_length[100]');
		$this->form_validation->set_rules('fk_comune', 'citta', 'trim|numeric|max_length[10]|required');
		$this->form_validation->set_rules('indirizzo', 'indirizzo', 'trim|max_length[100]|required');
		$this->form_validation->set_rules('nome', 'nome ente', 'trim|max_length[50]|required');
		$this->form_validation->set_rules('telefono', 'telefono', 'trim|max_length[100]');

		$this->form_validation->set_rules('id', 'id', 'trim');
		$this->form_validation->set_error_delimiters('<span class="text-danger">', '</span>');

	}

}