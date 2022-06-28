<?php
if (!defined('BASEPATH')){
	exit('No direct script access allowed');
}
require APPPATH . '/libraries/BaseController.php';
use Dompdf\Dompdf;

class Mod_tesseramenti extends BaseController
{

	public function __construct()
	{
		parent::__construct();
		$this->load->model('Mod_tesseramenti_model');
		$this->mod_name = 'mod_tesseramenti';
		$this->mod_type = 'crud';
		$this->mod_title = 'Tesseramenti';
		$this->modelClassModule =  $this->Mod_tesseramenti_model;
		$this->pkIdName = 'id';
		$this->viewName_ListAjax = 'mod_tesseramenti_list_ajax';
		$this->viewName_FormROAjax = 'mod_tesseramenti_read_ajax';
		$this->viewName_FormAjax = 'mod_tesseramenti_form_ajax';

		/*
		//ABILITARE PER CUSTOMIZZAZIONE PER MODULO ERRORI SQL 
		//IN CORSO MIGLIORIA PER GESTIRE I MESSAGGI TRAMITE TABELLA DI TRASCODIFICA
		$this->MsgDBConverted['insert']['error']['1062'] = "Esiste gia questo elemento per il modulo Tesseramenti";
		$this->MsgDBConverted['insert']['error']['1452'] = "Esiste gia questo elemento per il modulo Tesseramenti";
		$this->MsgDBConverted['update']['error']['1062'] = "Esiste gia questo elemento per il modulo Tesseramenti";
		$this->MsgDBConverted['update']['error']['1452'] = "Esiste gia questo elemento per il modulo Tesseramenti";
		$this->MsgDBConverted['insert_massive']['error']['1062'] = "Esiste gia questo elemento per il modulo Tesseramenti";
		$this->MsgDBConverted['insert_massive']['error']['1452'] = "Esiste gia questo elemento per il modulo Tesseramenti";
		$this->MsgDBConverted['update_massive']['error']['1062'] = "Esiste gia questo elemento per il modulo Tesseramenti";
		$this->MsgDBConverted['update_massive']['error']['1452'] = "Esiste gia questo elemento per il modulo Tesseramenti";
		$this->MsgDBConverted['delete']['error']['1217'] = "Impossibile eliminare questo elemento del modulo Tesseramenti. E' usato nei seguenti moduli:";
		$this->MsgDBConverted['delete_massive']['error']['1217'] = "Impossibile eliminare alcuni elementi del modulo Tesseramenti. Sono usati nei seguenti moduli:";
		*/


		/** SETTAGGIO CAMPI FORM **/
		$this->setFormFields('data_tesseramento');
		$this->setFormFields('fk_affiliazione','mod_affiliazioni',array("id" => 'id', "nome" => 'nome'));
		$this->setFormFields('fk_anagrafica','mod_anagrafica',array("id" => 'id', "nome" => 'CONCAT(nome," ",cognome," - ",codfiscale)'));
		$this->setFormFields('fk_esercizio','mod_esercizi',array("id" => 'id', "nome" => 'nome'),NULL, "fk_affiliazione");
		$this->setFormFields('fk_tessera_associativa','_mod_magazzino_tessere_lista_tessere',array("id" => 'id', "nome" => 'codice_tessera'));
		$this->setFormFields('tessera_interna');
		$this->setFormFields('importo');
		$this->setFormFields('fk_tipopagamento');
		$this->setFormFields('id');


		/**  AGGIUNGO FILTRO COMBO  NELLA GRIGLIA - QUESTO METODO VA RISCRITTO NEL BASEMODEL **/
		$this->addComboGridFilter(
			'mod_esercizi_nome',
			'mod_esercizi',
			"id",
			"nome",
			"Filtra per Esercizio",
			array("filter_slave_id" => "mod_affiliazioni_nome", "filter_slave_population_function" => "populateAffiliazioni")
		);
		$this->addComboGridFilter('mod_affiliazioni_nome', NULL, NULL, NULL, "Filtra per Affiliazione");

		

		/**  AREA LAMBDA FUNCTIONS - FUNZIONI RICHIAMATE in updateAjax, createAjax e nelle op. di CRUD master details**/
		$this->custom_form_data_functions['fk_affiliazione_txt'] = function () {
			$ret = "";	
			if((isset($_REQUEST['fk_affiliazione'])))  {
				if($_REQUEST['fk_affiliazione'] != ""){
					$ret = $this->modelClassModule->getNomeAffiliazione($_REQUEST['fk_affiliazione']);
				}
				
			}
			
			return  $ret;
		};
		$this->custom_form_data_functions['tbl_storico_tesseramenti'] = function () {
			$ret = "";	
			if(isset($_REQUEST['recordID'])){
				$fkAnagrafica = $this->modelClassModule->getFkAnagrafica($_REQUEST['recordID']);
				$ret = $this->modelClassModule->getStoricoTesseramenti($fkAnagrafica);
			} else if(isset($_REQUEST['fk_anagrafica'])){
				$ret = $this->modelClassModule->getStoricoTesseramenti($_REQUEST['fk_anagrafica']);
			}
			
			return  $ret;
		};

		$this->custom_form_data_functions['mod_esercizio_id'] = function () {
			return $this->getEsercizioCorrente();
		};		

		$this->custom_form_data_functions['fk_tipopagamento_refval'] = function () {
			$row =  $this->modelClassModule->get_all('mod_tipopagamento');
			return $row;
		};		
 
		$this->custom_operations_list['check_data_tesseramento'] = function ($request, $id = NULL) {
			$ret = $this->check_data_tesseramento($request['fk_esercizio'], $this->utilities->convertToDateEN($request['data_tesseramento']));
			if ($ret === FALSE) {
				$this->session->set_flashdata('error', "La data di tesseramento deve essere compresa nell'esercizio di riferimento");
				return false;
			}
		};


		$this->custom_operations_list['update_importo_tesseramento'] = function ($request, $id = NULL) {
			$ret = TRUE;
			if($id != NULL){
				$ret = $this->update_importo_tesseramento($id, $request['importo']);
				if ($ret === FALSE) {
					$this->session->set_flashdata('error', "Aggiornamento importo fallito");
					return false;
				}			
			}

			return $ret;
		};

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



		$this->global['pageTitle'] = $this->mod_title . ' - Lista';
		$this->loadViews($this->mod_name . '/' . $this->viewName_ListAjax, $this->global, $data, null);
	}



	/**
	 * 
	 * Prelevo esercizio corrente
	 */
	private function getEsercizioCorrente(){
		return $this->modelClassModule->getEsercizioCorrente();
	}


	/**
	 * 
	 * Prelevo le affiliazioni in base al filtro passato
	 */
	public function populateAffiliazioni()
	{
		echo json_encode($this->modelClassModule->populateAffiliazioni($_REQUEST['filter_master_value']));
	}


	/**
	 * 
	* Prelevo la tessera in base all'anagrafica
	* @param mixed $fkAnagrafica
	* @param string $fkEsercizio
	* @param string $fk_affiliazione
	* @return string
	**/
	public function getTessera($fkAnagrafica, $fkEsercizio, $fk_affiliazione){
		return $this->modelClassModule->getTessera($fkAnagrafica, $fkEsercizio, $fk_affiliazione);
	}


	/**
	 * 
	 * Verifica se la data di tesseramento rientra nell'esericizio
	 * @param string $idEsercizio
	 * @param date $data_tesseramento
	 * @return bool
	 */
	public function check_data_tesseramento($idEsercizio, $data_tesseramento)
	{
		$ret = $this->modelClassModule->check_data_tesseramento($idEsercizio, $data_tesseramento);

		return $ret;
	}


	/**
	 * 
	 * Aggiorno l'importo del tesseramento
	 * @param string $idtesseramento
	 * @param float $importo
	 */
	public function update_importo_tesseramento($idtesseramento,$importo)
	{
		$ret = $this->modelClassModule->update_importo_tesseramento($idtesseramento, $importo);

		return $ret;
	}
	

	/**
	 * 
	 * Inserimento massivo tesserati
	 */
	public function insertPagamentiFromTesserati(){
		return $this->modelClassModule->insertPagamentiFromTesserati();
	}
	


	public function _rules()
	{
		$this->form_validation->set_rules('data_tesseramento', 'data tesseramento', 'trim|required');
		$this->form_validation->set_rules('fk_affiliazione', 'affiliazione', 'trim|numeric|max_length[10]|required');
		$this->form_validation->set_rules('fk_anagrafica', 'anagrafica', 'trim|numeric|max_length[10]|required');
		$this->form_validation->set_rules('fk_esercizio', 'esercizio', 'trim|numeric|max_length[10]|required');
		$this->form_validation->set_rules('fk_tessera_associativa', 'tessera', 'trim|numeric|max_length[10]|required');
		$this->form_validation->set_rules('tessera_interna', 'tessera interna', 'trim|numeric|max_length[10]|required');
		$this->form_validation->set_rules('importo', 'importo', 'trim|numeric|max_length[9]|required');
		$this->form_validation->set_rules('fk_tipopagamento', 'modalita pagamento', 'trim|required');

		$this->form_validation->set_rules('id', 'id', 'trim');
		$this->form_validation->set_error_delimiters('<span class="text-danger">', '</span>');

	}

}