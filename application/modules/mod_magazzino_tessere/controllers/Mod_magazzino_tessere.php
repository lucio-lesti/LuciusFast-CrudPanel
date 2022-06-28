<?php
if (!defined('BASEPATH')){
	exit('No direct script access allowed');
}
require APPPATH . '/libraries/BaseController.php';
require APPPATH . 'third_party/spreadsheet-reader/php-excel-reader/excel_reader2.php';
require APPPATH . 'third_party/spreadsheet-reader/SpreadsheetReader.php';
use Dompdf\Dompdf;

class Mod_magazzino_tessere extends BaseController
{

	public function __construct()
	{
		parent::__construct();
		$this->load->model('Mod_magazzino_tessere_model');
		$this->mod_name = 'mod_magazzino_tessere';
		$this->mod_type = 'crud';
		$this->mod_title = 'Magazzino Tessere';
		$this->modelClassModule =  $this->Mod_magazzino_tessere_model;
		$this->pkIdName = 'id';
		$this->viewName_ListAjax = 'mod_magazzino_tessere_list_ajax';
		$this->viewName_FormROAjax = 'mod_magazzino_tessere_read_ajax';
		$this->viewName_FormAjax = 'mod_magazzino_tessere_form_ajax';


		/*
		//ABILITARE PER CUSTOMIZZAZIONE PER MODULO ERRORI SQL 
		//IN CORSO MIGLIORIA PER GESTIRE I MESSAGGI TRAMITE TABELLA DI TRASCODIFICA
		//SPOSTARE LOGICA NEL MODEL			
		$this->MsgDBConverted['insert']['error']['1062'] = "Esiste gia questo elemento per il modulo Magazzino Tessere";
		$this->MsgDBConverted['insert']['error']['1452'] = "Esiste gia questo elemento per il modulo Magazzino Tessere";
		$this->MsgDBConverted['update']['error']['1062'] = "Esiste gia questo elemento per il modulo Magazzino Tessere";
		$this->MsgDBConverted['update']['error']['1452'] = "Esiste gia questo elemento per il modulo Magazzino Tessere";
		$this->MsgDBConverted['insert_massive']['error']['1062'] = "Esiste gia questo elemento per il modulo Magazzino Tessere";
		$this->MsgDBConverted['insert_massive']['error']['1452'] = "Esiste gia questo elemento per il modulo Magazzino Tessere";
		$this->MsgDBConverted['update_massive']['error']['1062'] = "Esiste gia questo elemento per il modulo Magazzino Tessere";
		$this->MsgDBConverted['update_massive']['error']['1452'] = "Esiste gia questo elemento per il modulo Magazzino Tessere";
		$this->MsgDBConverted['delete']['error']['1217'] = "Impossibile eliminare questo elemento del modulo Magazzino Tessere. E' usato nei seguenti moduli:";
		$this->MsgDBConverted['delete_massive']['error']['1217'] = "Impossibile eliminare alcuni elementi del modulo Magazzino Tessere. Sono usati nei seguenti moduli:";
		*/


		//NOTE:NELLA FUNZIONE 'setFormFields' INDICARE NEL VETTORE CHE SI COLLEGA ALLA TABELLA REFERENZIATA
		//ALLA CHIAVE 'NOME', IL NOMINATIVO DEL CAMPO COLLEGATO
		$this->setFormFields('nome');
		//PER L'ARRAY DI REFERENZIAMENTO, USARE IL CONCAT PER CONCATENARE PIU CAMPI NEL CAMPO 'NOME'
		//ES.CONCAT(cognome," ",nome)
		$this->setFormFields('fk_affiliazione','mod_affiliazioni',array("id" => 'id', "nome" => 'nome'));
		$this->setFormFields('fk_ente','mod_enti',array("id" => 'id', "nome" => 'nome'));
		$this->setFormFields('id');


		//RICHIAMO FUNZIONE PER IL CARICAMENTO MASTER DETAILS
		$this->addMasterDetailsLoadFunc('getMasterDetail_mod_magazzino_tessere_lista_tessere','Lista Tessere','getMasterDetail_mod_magazzino_tessere_lista_tessere');


		/**  AREA LAMBDA FUNCTIONS - FUNZIONI RICHIAMATE in updateAjax, createAjax e nelle op. di CRUD master details**/
		$this->custom_form_data_functions['fk_ente'] = function () {
			$ret = "";	
			if((isset($_REQUEST['recordID'])))  {
				if($_REQUEST['recordID'] != ""){
					$fk_affiliazione = $this->modelClassModule->getAffiliazioneId($_REQUEST['recordID']);				
					$row = $this->modelClassModule->getEnte($fk_affiliazione);
					$ret = $row[0]['id'];
				} 
				
			}
			
			return  $ret;
		};
		$this->custom_form_data_functions['fk_ente_txt'] = function () {
			$ret = "";	
			if((isset($_REQUEST['recordID'])))  {
				if($_REQUEST['recordID'] != ""){
					$fk_affiliazione = $this->modelClassModule->getAffiliazioneId($_REQUEST['recordID']);				
					$row = $this->modelClassModule->getEnte($fk_affiliazione);
					$ret = $row[0]['nome'];
				}
				
			}
 
			return  $ret;
		};
	}

	

	/**
	* Funzione caricamento della master details, tabella _mod_magazzino_tessere_lista_tessere
	* @param mixed $id
	* @param string $isAjax
	* @return string
	**/
	public function getMasterDetail_mod_magazzino_tessere_lista_tessere($id, $isAjax = 'FALSE'){
		$row =  $this->modelClassModule->getMasterDetail_mod_magazzino_tessere_lista_tessere($id, $isAjax);
		$html = '';
		$winFormType ="form";//VALORI ACCETTATI $field_columns_referenced_nome: {'multi','form'}
	
		if($isAjax == 'FALSE'){
			if($winFormType == "form"){
				$html .= '
							<br><a class="btn btn-primary" style="cursor:pointer" onclick="winFormMasterDetails(\'mod_magazzino_tessere\',\'winMasterDetail_mod_magazzino_tessere_lista_tessere\',\'insert\','.$id.',\'NULL\',\'NUOVA TESSERA\', arrayValidationFields,\'winMasterDetail_mod_magazzino_tessere_lista_tessere\',\'form\',\'getMasterDetail_mod_magazzino_tessere_lista_tessere\')">[ Aggiungi un elemento]</a><br>
							<br>';
			} else {
				$html .= '
						<br><a class="btn btn-primary" style="cursor:pointer" onclick="winFormMasterDetails(\'mod_magazzino_tessere\',\'winMasterDetailMulti_mod_magazzino_tessere_lista_tessere\',\'insert\','.$id.',\'NULL\',\'NUOVA TESSERA\', arrayValidationFields,\'winMasterDetailMulti_mod_magazzino_tessere_lista_tessere\',\'multi\',\'getMasterDetail_mod_magazzino_tessere_lista_tessere\')">[ Aggiungi un elemento]</a><br>
						<br>';
			}
		}
		$html .= '<b style="color:#990000">(*) Le tessere con codice "ND" sono tessere provvisorie. 
					Saranno sovrascritte con l\'import delle nuove tessere</b><br><br>';

		$html .= ' <input  type="text" class="form-control" autocomplete="off" 
							id="search_mod_magazzino_tessere_lista_tessere" style="width:20%" placeholder="Cerca..."
							onkeypress="disableKeySubmit()"
							onkeyup="searchInMasterDetailsTable(\'search_mod_magazzino_tessere_lista_tessere\', \'tbl_mod_magazzino_tessere_lista_tessere\',3)"><br>';
		
		$html.='<a href="mod_magazzino_tessere/excel/_mod_magazzino_tessere_lista_tessere/'.$id.'" class="btn btn-primary" style="cursor:pointer" target="_blank">Esporta tabella SQL di riferimento</a><br><br>';

		$html .= "<table class='TFtable' id='tbl_mod_magazzino_tessere_lista_tessere' style='font-size:12px'>
					<tr>
						<thead>
						<th class='sorting_disabled' rowspan='1' colspan='1' aria-label='' style='width:10%;'>
							<input type='checkbox' id='check_master_mod_magazzino_tessere_lista_tessere' name='check_master_mod_magazzino_tessere_lista_tessere' 
							onchange=\"selezionaDeselezionaTutti('check_master_mod_magazzino_tessere_lista_tessere','check_id_mod_magazzino_tessere_lista_tessere','btDeleteMass_mod_magazzino_tessere_lista_tessere')\">
						</th>";
		$html.='<th>ID Tessera</th>';
		$html.='<th>Codice Tessera</th>';
		$html.='<th>Allievo Associato</th>';
		if($winFormType == "form"){
			$html.='<th>Modifica</th>';
		}
		$html.='<th>Elimina</th>';
		$html.='</tr>';
		$html.='<tbody>';
		foreach($row as $key => $value){
			$html.="<tr>";
			$html.="<td><input type='checkbox' id='check_id_mod_magazzino_tessere_lista_tessere' name='check_id_mod_magazzino_tessere_lista_tessere' value='".$value['id']."' onchange=\"verificaNrCheckBoxSelezionati('check_id_mod_magazzino_tessere_lista_tessere','btDeleteMass_mod_magazzino_tessere_lista_tessere')\"></td>";
			$html.="<td><input type='hidden' id='id[]' name='id[]' value='".$value['id']."'>".$value['id']."</td>";
			$html.="<td><input type='hidden' id='id[]' name='id[]' value='".$value['id']."'>".$value['codice_tessera']."</td>";
			$html.="<td><input type='hidden' id='id[]' name='id[]' value='".$value['id']."'>".$value['allievo']."</td>";
			if($winFormType == "form"){
				$html.="<td><a style='cursor:pointer' class='btn btn-sm btn-info' onclick ='winFormMasterDetails(\"mod_magazzino_tessere\",\"winMasterDetail_mod_magazzino_tessere_lista_tessere\",\"edit\", $id,".$value['id'].",\"MODIFICA TESSERA\",arrayValidationFields,\"winMasterDetail_mod_magazzino_tessere_lista_tessere\",\"form\",\"getMasterDetail_mod_magazzino_tessere_lista_tessere\")' title='Modifica _mod_magazzino_tessere_lista_tessere'><i class='fa fa-edit'></a></td>";
			}
			$html.="<td><a style='cursor:pointer' class='btn btn-sm btn-danger deleteUser' onclick ='deleteMasterDetails(".$value['id'].", ".$id.", \"mod_magazzino_tessere\",\"_mod_magazzino_tessere_lista_tessere\",\"getMasterDetail_mod_magazzino_tessere_lista_tessere\")' title='Elimina'><i class='fa fa-trash'></a></td>";
			$html.='</tr>';
		}
		$html.='</tbody></table>';
				$html.='<br/><a class="btn btn-sm btn-danger deleteUser" id="btDeleteMass_mod_magazzino_tessere_lista_tessere" name="btDeleteMass_mod_magazzino_tessere_lista_tessere""
					onclick="deleteMassiveMasterDetails('.$id.',\'entry_list\',\'check_id_mod_magazzino_tessere_lista_tessere\',\'mod_magazzino_tessere\',\'_mod_magazzino_tessere_lista_tessere\',\'getMasterDetail_mod_magazzino_tessere_lista_tessere\')">
					<i class="fa fa-trash"></i> Cancellazione Massiva
				</a>';
		return $html;
	}


	/**
	* Funzione caricamento della finestra per la master details, tabella _mod_magazzino_tessere_lista_tessere
	* @param mixed $action
	* @param string $entryID
	* @param string $entryIDMasterDetails
	* @return string
	**/
	public function winMasterDetail_mod_magazzino_tessere_lista_tessere($action, $entryID, $entryIDMasterDetails = NULL){
		if($entryIDMasterDetails == 'NULL'){
			$entryIDMasterDetails = '';
		}
		$rowWinForm = $this->modelClassModule->get_from_master_details_by_id($entryIDMasterDetails, '_mod_magazzino_tessere_lista_tessere', 'id');
		$html = '<div>
				<section class="content">
					<div class="row">
						<div class="col-md-12">
							<div class="box box-primary">
								<div class="box-body">
								<div id="msg_err" ondblclick="this.style.display=\'none\';" style="display: none; border: 1px solid; background-color: rgb(255, 64, 0); border-radius: 5px; padding: 10px; color: white; font-weight: bold;">
								</div>									
									<form  name="frm_master_detail" id="frm_master_detail">
									<input type="hidden" id="table" name="table" value="_mod_magazzino_tessere_lista_tessere">
									<input type="hidden" id="action" name="action" value="'.$action.'"/> 
									<input type="hidden" id="saveType" name="saveType" value="form"/> 	
									<input type="hidden" id="entryID"          name="entryID"  value="'.$entryID.'">
									<input type="hidden" id="fk_magazzino_tessere"  name="fk_magazzino_tessere"  value="'.$entryID.'">
									
									<input type="hidden" id="entryIDMasterDetails" 		name="entryIDMasterDetails" value="'.$entryIDMasterDetails.'" >															
										<div class="col-md-12">
											<div class="form-group">';

		$html .= '<div class="form-group">';
							
		$html .= '<label for="varchar"><b style="color:#990000">(*)</b>Codice Tessera</label>';
							
		$html .= '<div class="input-group">';
							
		$html .= '<div class="input-group-addon"><i class="fa fa-text-height"></i></div>';		
							
		$html .= '<input type="text" class="form-control" maxlength=\'255\' name="codice_tessera" id="codice_tessera" placeholder="Codice Tessera" autocomplete="off" value="'.$rowWinForm['codice_tessera'].'" />';
							
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
	* Funzione caricamento della finestra per la master details,in modalita di inserimento multiplo, tabella _mod_magazzino_tessere_lista_tessere
	* @param mixed $action
	* @param string $entryID
	* @return string
	**/
	public function winMasterDetailMulti_mod_magazzino_tessere_lista_tessere($action, $entryID){
		$html = '<div>
				<section class="content">
					<div class="row">
						<div class="col-md-12">
							<div class="box box-primary">
								<div class="box-body">
								<div id="msg_err" ondblclick="this.style.display=\'none\';" style="display: none; border: 1px solid; background-color: rgb(255, 64, 0); border-radius: 5px; padding: 10px; color: white; font-weight: bold;">
								</div>									
									<form  name="frm_master_detail" id="frm_master_detail">
									<input type="hidden" id="table" name="table" value="_mod_magazzino_tessere_lista_tessere">
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
    */    
    public function importExcelData()
    {
        //SPENGO GLI ERRORI PERCHE'
        //LA LIBRERIA PHPEXCEL DA WARNING
		$ret = array();
		$ret['msg'] = "";
		$ret['success'] = "ok";
		$ret['master_details_list'] = "";

        //error_reporting(0);
        //ini_set('display_errors', 0);

        $allowedExt = array('xls', 'xlsx', 'csv');

		//print'<pre>';print_r($_FILES);
        if ($_FILES['file_tessere']['name'] != '') {
            $config = array(
                'upload_path' => "./uploads/import_data",
                'allowed_types' => implode("|", $allowedExt),
                'overwrite' => true,
                'max_size' => "20048000",
            );

            $this->load->library('upload', $config);
            $upload = $this->upload->do_upload('file_tessere');
            $data = $this->upload->data();

            $filepath = $data['full_path'];
            $path_parts = pathinfo($filepath);
            $filetype = $path_parts['extension'];

            if (!in_array($filetype, $allowedExt)) {
				$ret['msg'] = "Estensione non Valida!";
				$ret['success'] = "ko";
            } else {
                //VERIFICO IL NOME DEL FILE CHE DEVE CORRISPONDERE AL NOME DI UNA TABELLA
                $tableListObj = $this->user_model->getTableList();
                $tableList = array();
                foreach ($tableListObj as $key => $value) {
                    $tableList[] = strtolower($value->TABLE_NAME);
                }

				$filename  = "_mod_magazzino_tessere_lista_tessere";

                //MI CARICO IN UN VETTORE LE COLONNE CON I TIPI DATI DELLA TABELLA DOVE VOGLIO IMPORTARE I DATI
                $columnsListObj = $this->user_model->getTableColumns($filename);
                $columnsList = array();
                foreach ($columnsListObj as $key => $value) {
                    $columnsList[] = strtolower($value->COLUMN_NAME);
                }

				//RIMUOVO COLONNA "fk_magazzino_tessere", 
				//PER BYPASSARE IL CONTROLLO SUL CAMPO "fk_magazzino_tessere". IL CAMPO SARA VALORIZZATO DAL REQUEST
				foreach($columnsList as $k => $v){
					if($v == 'fk_magazzino_tessere'){
						unset($columnsList[$k]);
					} else if($v == 'id'){
						unset($columnsList[$k]);
					}						
				}
				$columnsList = array_values($columnsList);
                
				//CARICO IL CONTENUTO DELL'EXCEL
                $Reader = new SpreadsheetReader(FCPATH . "/uploads/import_data/" . $data['file_name']);
                $header = array();
                $arr_data = array();
                $rowCount = 0;

                foreach ($Reader as $Row) {
                    $rowCount++;

                    //PRELEVO INTESTAZIONE
                    if ($rowCount == 1) {
                        foreach ($Row as $key => $valueHeader) {
                            $header[] = strtolower($valueHeader);
                        }
                    } else {
                        //IMPOSTO LA CHIAVE COME LA COLONNA DELLA TABELLA
                        foreach ($Row as $key => $valueCell) {
                            $arr_data[$rowCount][$header[$key]] = $valueCell;

							//VALORIZZO IL CAMPO DAL REQUEST
							$arr_data[$rowCount]['fk_magazzino_tessere'] = $_REQUEST['id'];
                        }
                    }
                }


                //VERIFICO SE LE COLONNE DEL FILE CORRISPONDONO COME NOME ALLE COLONNE DELLA TABELLA
                foreach ($header as $key => $headerValue) {
                    if (!in_array($headerValue, $columnsList)) {
                        $ret['msg'] = "Le colonne del file devono corrispondere alle colonne della tabella da importare.";
						$ret['success'] = "ko"; 
                    }
                }

				/*
				print'<pre>';print_r($header);
				print'<pre>';print_r($columnsList);
				print'<pre>';print_r($ret);
				die();
				*/

				if($ret['success'] == "ok"){
					//RE-INDICIZZO L'ARRAY DA ZERO IN UN NUOVO ARRAY
					$excelData = array_values($arr_data);

					//COMINCIO L'INSERIMENTO, MA PRIMA SVUOTO LA TABELLA
					$res = $this->modelClassModule->importExcelData($filename, $excelData,$_REQUEST['id']);
				}  

				if(isset($res)){
					if ($res == true) {
						$ret['success'] = "ok";
						$ret['msg'] = "Importazione dati riuscita.";
						$ret['master_details_list'] = $this->getAjaxMasterDetails($_REQUEST['id']);
					} else {
						$ret['success'] = "ko";
						$ret['msg'] = "Importazione dati NON riuscita.'";
					}
				}


            }

        } else {
			$ret['msg'] = "Non hai caricato nessun file";
            $ret['success'] = "ko";
        }

		echo json_encode($ret);

    }



	public function _rules()
	{
		$this->form_validation->set_rules('nome', 'nome stock tessere', 'trim|max_length[255]|required');
		$this->form_validation->set_rules('fk_affiliazione', 'affiliazione', 'trim|numeric|max_length[10]|required');

		$this->form_validation->set_rules('id', 'id', 'trim');
		$this->form_validation->set_error_delimiters('<span class="text-danger">', '</span>');

	}

}