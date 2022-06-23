<?php defined('BASEPATH') or exit('No direct script access allowed');

/**
 * Class : BaseController (BaseController)
 * La classe implementa tutte le funzionalità di base di un form per le operazioni di CRUD
 * @author : Lucio Lesti
 * @version : 1.0
 * @since : 27.02.2021
 */

if (!class_exists('My_Controller')) {
	require APPPATH . 'core/My_Controller.php';
}
require BASEPATH . '/core/Model.php';
require APPPATH . '/models/Common_model.php';
require APPPATH . '/third_party/dompdf/autoload.inc.php';
require APPPATH . '/libraries/Utilities.php';
require APPPATH . '/libraries/MySQLErrorCode.php';
require APPPATH . '/third_party/PHPMailer/PHPMailerAutoload.php';
require APPPATH . '/third_party/PHPMailer/class.smtp.php';

use Dompdf\Dompdf;


class BaseController extends MY_Controller
{

	protected $role = '';
	protected $vendorId = '';
	protected $name = '';
	protected $roleText = '';
	protected $global = array();
	protected $lastLogin = '';
	protected $status = '';

	protected $mod_title = "";
	protected $mod_name = "";

	protected $modelClassModule =  NULL;
	protected $pkIdName = '';
	protected $pkIdValue = NULL;

	protected $viewName_ListAjax = "";
	protected $viewName_List = "";
	protected $viewName_Form = "";
	protected $viewName_FormAjax = "";
	protected $viewName_FormRO = "";
	protected $viewName_FormROAjax = "";
	protected $viewName_PDF = "";
	protected $viewName_DOC = "";

	protected $formFields = array();
	protected $formFieldsReferenced = array();
	protected $formFieldsReferencedTableRef = array();
	private $formFieldsReferencedColumns = array();
	protected $masterDetailsLoadFuncList = array();
	//protected $winMasterDetailsLoadFuncList = array();
	protected $masterDetailsListFromModel = array();
	protected $custom_form_data_functions = array();
	protected $custom_rules_reload_master_details = array();

	protected $utilities = NULL;
	protected $mod_type = "crud";


	protected $MsgDBConverted = array(
		"insert" => array(
			"success" => "Elemento inserito con successo",
			"error" => array(
				"1452" => "Esiste gia questo elemento per il modulo",
				"1062" => "Esiste gia questo elemento per il modulo",
			),
			"record_not_found" => "Elemento Non trovato",
		),
		"update" => array(
			"success" => "Elemento aggiornato con successo",
			"error" => array(
				"1452" => "Esiste gia questo elemento per il modulo",
				"1062" => "Esiste gia questo elemento per il modulo",
			),
			"record_not_found" => "Elemento Non trovato",
		),
		"delete" => array(
			"success" => "Elemento eliminato con successo",
			"error" => array(
				"1217" => "Impossibile eliminare questo elemento. E' usato in altri moduli",
				"1451" => "Impossibile eliminare questo elemento. E' usato in altri moduli",
			),
			"record_not_found" => "Elemento Non trovato",
		),
		"insert_massive" => array(
			"success" => "Elementi inseriti con successo",
			"error" => array(
				"1462" => "Tra gli elementi inseriti, esiste gia un elemento per questo modulo con questa chiave. Non e' stato aggiornato.",
				"1062" => "Tra gli elementi inseriti, esiste gia un elemento per questo modulo. Non e' stato inserito.",
			),
			"record_not_found" => "Elementi Non trovati",
		),
		"update_massive" => array(
			"success" => "Elementi aggiornati con successo",
			"error" => array(
				"1452" => "Tra gli elementi aggiornati, esiste gia un elemento per questo modulo con questa chiave. Non e' stato aggiornato.",
				"1062" => "Tra gli elementi aggiornati, esiste gia un elemento per questo modulo con questa chiave. Non e' stato aggiornato.",
			),
			"record_not_found" => "Elementi Non trovati",
		),
		"delete_massive" => array(
			"success" => "Elementi eliminati con successo",
			"error" => array(
				"1217" => "Impossibile eliminare uno di questi elementi. E' usato in altri moduli",
				"1451" => "Impossibile eliminare uno di questi elementi. E' usato in altri moduli",
			),
			"record_not_found" => "Elementi Non trovati",
		),
	);
	protected $mysql_error_codes = NULL;
	protected $custom_operations_list = array();
	protected $custom_rules_updateAjax = array();
	protected $custom_rules_createAjax = array();
	protected $arrayComboGridFilter = array();


	/**
	 * Costruttore della classe 
	 */
	public function __construct()
	{
		parent::__construct();

		$this->load->model('user_model');
		$this->load->model('home_model');
		$this->load->model('Common_model');

		$this->load->library('form_validation');
		$this->load->library('datatables');
		$this->load->library('session');
		$this->load->helper('email');

		$this->utilities = new Utilities();

		$this->checkIpAddressEnabled();
		$child_class_caller = get_class($this);
		if ($child_class_caller != "Login") {
			$this->datas();
			$isLoggedIn = $this->session->userdata('isLoggedIn');
			if (!isset($isLoggedIn) || $isLoggedIn != TRUE) {
				redirect('login');
			} else {
				if ($child_class_caller != "User") {
					if ($this->isAdmin() == FALSE) {
						$this->checkRoleHasPrivilege($child_class_caller);
					}
				}
			}
		}

		$mysql_error = new MySQLErrorCode();
		$this->mysql_error_codes = $mysql_error->ErrorCodes();
		 

	}



	/**
	 * Verifica se il ruolo ha accesso al modulo  
	 * @param mixed $child_class_caller
	 */
	protected function  checkRoleHasPrivilege($child_class_caller)
	{
		$hasPrivilege = $this->user_model->checkRoleHasPrivilege($child_class_caller, $this->role);
		if ($hasPrivilege == FALSE) {
			redirect('noaccess');
		}
	}


	/**
	 * Verifica se l'IP è abilitato
	 */
	private function checkIpAddressEnabled()
	{
		$arraySettings = $this->user_model->loadSettings();
		$settings = $arraySettings[0];
		if (trim($settings->ip_filter_list) != "") {
			$ipAdmittedList = explode(",", $settings->ip_filter_list);
			if (!in_array($_SERVER['REMOTE_ADDR'], $ipAdmittedList)) {
				$this->ipAddresDenied();
			}
		}
	}


	/**
	 * Manda un messaggio agli amministratori su un tentativo da parte di un IP non autorizzato
	 */
	public function ipAddresDenied()
	{
		$this->load->model('login_model');

		$process = 'Tentativo di accesso da IP ' . $_SERVER['REMOTE_ADDR'] . ' (sconosciuto)';
		$processFunction = 'BaseController/ipAddresDenied';
		$this->logrecord($process, $processFunction);

		$logInfo = array(
			"userId" => "-1",
			"userName" => "unknow",
			"process" => $process,
			"processFunction" => $processFunction,
			"userRoleId" => "unknow",
			"userRoleText" => "unknow",
			"userIp" => $_SERVER['REMOTE_ADDR'],
			"userAgent" => getBrowserAgent(),
			"agentString" => $this->agent->agent_string(),
			"platform" => $this->agent->platform()
		);
		$this->login_model->loginsert($logInfo);
		$message = "<b>MAYTOUR - TENTATIVO DI ACCESSO DA IP SCONOSCIUTO</b>";
		$message .= "<BR><BR>E' STATO APPENA TENTATO L'ACCESSO ALLA PIATTAFORMA DAL SEGUENTE IP (SCONOSCIUTO):<B>" . $_SERVER['REMOTE_ADDR'] . "</B>";
		$this->sendMailToAdmin('Maytour - Tentativo di accesso da IP Sconosciuto', $message);
		$data['ip_access_denied'] = 'Y';
		$this->load->view('access_denied', $data);
	}


	/**
	 * Manda una mail agli amministratori
	 * @param mixed $subject
	 * @param mixed $message
	 */
	public function sendMailToAdmin($subject, $message)
	{
		$userListing = $this->user_model->getAllAdminUsers();

		$this->load->library('email');
		foreach ($userListing as $keyUser => $valueUser) {
			if ($valueUser->role == 'Admin') {
				$this->email->set_mailtype("html");
				$this->email->from('noreply@digitalsupport.eu', 'Alert LM-Panel');
				$this->email->to($valueUser->email);
				$this->email->subject($subject);
				$this->email->message($message);
				$this->email->send();
			}
		}
	}


	/**
	 * 
	 * Con dati in input di tipo MIXED e di tipo status code crea la risposta
	 * @param array|NULL $data
	 *        	Data to output to the user
	 *        	running the script; otherwise, exit
	 */
	public function response($data = NULL)
	{
		$this->output->set_status_header(200)->set_content_type('application/json', 'utf-8')->set_output(json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES))->_display();
		exit();
	}


	/**
	 * Verifica che l'utente sia autenticato o meno
	 */
	public function isLoggedIn()
	{
		$isLoggedIn = $this->session->userdata('isLoggedIn');

		if (!isset($isLoggedIn) || $isLoggedIn != TRUE) {
			redirect('login');
		} else {
			$this->datas();
		}
	}


	/**
	 * Verifica se il ruolo con cui si è autenticati è di tipo ADMIN
	 */
	public function isAdmin()
	{
		if ($this->role == ROLE_ADMIN) {
			return true;
		} else {
			return false;
		}
	}


	/**
	 * 
	 * Verifica se il ruolo con cui si è autenticati è di tipo ADMIN o MANAGER
	 * @deprecated
	 */
	public function isManagerOrAdmin()
	{
		if ($this->role == ROLE_ADMIN || $this->role == ROLE_MANAGER) {
			return false;
		} else {
			return true;
		}
	}


	/**
	 * Ritorna lo stato dell' utente
	 */
	public function getUserStatus()
	{
		$this->datas();
		$status = $this->user_model->getUserStatus($this->vendorId);
		if ($status->status == 0) {
			return true;
		} else {
			return false;
		}
	}


	/**
	 * Reindirizzamento alla pagina di accesso non autorizzato
	 */
	public function accesslogincontrol()
	{
		$process = 'accesslogincontrol';
		$processFunction = 'Admin/accesslogincontrol';
		$this->logrecord($process, $processFunction);
		redirect(noaccess);
	}


	/**
	 * Logout del sistema con azzeramento della sessione
	 */
	public function logout()
	{

		$process = 'Logout';
		$processFunction = 'BaseController/logout';
		$this->logrecord($process, $processFunction);

		$this->session->sess_destroy();

		redirect('login');
	}


	/**
	 * Reimplementazione della fuzione view di codeigniter
	 * @param {string} $viewName : This is view name
	 * @param {mixed} $headerInfo : This is array of header information
	 * @param {mixed} $pageInfo : This is array of page information
	 * @param {mixed} $footerInfo : This is array of footer information
	 * @return {null} $result : null
	 */
	public function loadViews($viewName = "", $headerInfo = NULL, $pageInfo = NULL, $footerInfo = NULL)
	{


		$headerInfo['mod_active'] = $this->user_model->getAllModActive();

		//MOSTRO TUTTI I MODULI GENERATI SE AMMINISTRATORE
		if ($this->isAdmin() == TRUE) {
			$headerInfo['all_mod_aggr_standalone'] = $this->user_model->getAllModGenByAggrAndStandalone();
		} else {
			//ALTRIMENTI MOSTRO TUTTI I MODULI IMPOSTATI PER IL RUOLO
			$headerInfo['all_mod_aggr_standalone'] = $this->home_model->getAllModAggrByRole($this->role);
		}
		$headerInfo['isAdmin'] = $this->isAdmin();

		$settings = $this->user_model->loadSettings();
		$headerInfo['sidebar_fixed'] = $settings[0]->sidebar_fixed;
		$headerInfo['skin_color'] = $settings[0]->skin_color;
		$headerInfo['company_logo'] = $settings[0]->company_logo;
		$headerInfo['company_name'] = $settings[0]->company_name;
		//$headerInfo['notifyList'] =  $this->user_model->getNotifyList();
		$headerInfo['notifyList'] =  $this->home_model->getNotifyList();
		$headerInfo['notifyListCount'] =  $this->home_model->getNotifyListCount();
		//print'<pre>';print_r($headerInfo['notifyListCount']);

		$this->load->view('includes/header', $headerInfo);
		$this->load->view($viewName, $pageInfo);
		$this->load->view('includes/footer', $footerInfo);
	}


	/**
	 * This function used provide the pagination resources
	 * @param {string} $link : This is page link
	 * @param {number} $count : This is page count
	 * @param {number} $perPage : This is records per page limit
	 * @return {mixed} $result : This is array of records and pagination data
	 */
	public function paginationCompress($link, $count, $perPage = 10, $segment = SEGMENT)
	{
		$this->load->library('pagination');

		$config['base_url'] = base_url() . $link;
		$config['total_rows'] = $count;
		$config['uri_segment'] = $segment;
		$config['per_page'] = $perPage;
		$config['num_links'] = 5;
		$config['full_tag_open'] = '<nav><ul class="pagination">';
		$config['full_tag_close'] = '</ul></nav>';
		$config['first_tag_open'] = '<li class="arrow">';
		$config['first_link'] = 'First';
		$config['first_tag_close'] = '</li>';
		$config['prev_link'] = 'Previous';
		$config['prev_tag_open'] = '<li class="arrow">';
		$config['prev_tag_close'] = '</li>';
		$config['next_link'] = 'Next';
		$config['next_tag_open'] = '<li class="arrow">';
		$config['next_tag_close'] = '</li>';
		$config['cur_tag_open'] = '<li class="active"><a href="#">';
		$config['cur_tag_close'] = '</a></li>';
		$config['num_tag_open'] = '<li>';
		$config['num_tag_close'] = '</li>';
		$config['last_tag_open'] = '<li class="arrow">';
		$config['last_link'] = 'Last';
		$config['last_tag_close'] = '</li>';

		$this->pagination->initialize($config);
		$page = $config['per_page'];
		$segment = $this->uri->segment($segment);

		return array(
			"page" => $page,
			"segment" => $segment
		);
	}


	/**
	 * This function used to load user sessions
	 */
	public function datas()
	{
		$this->role = $this->session->userdata('role');
		$this->vendorId = $this->session->userdata('userId');
		$this->name = $this->session->userdata('name');
		$this->roleText = $this->session->userdata('roleText');
		$this->lastLogin = $this->session->userdata('lastLogin');
		$this->status = $this->session->userdata('status');


		$this->global['name'] = $this->name;
		$this->global['role'] = $this->role;
		$this->global['role_text'] = $this->roleText;
		$this->global['last_login'] = $this->lastLogin;
		$this->global['status'] = $this->status;
	}


	/**
	 * This function insert into log to the log table
	 */
	public function logrecord($process, $processFunction)
	{
		$this->datas();
		$logInfo = array(
			"userid" => $this->vendorId,
			"username" => $this->name,
			"process" => $process,
			"processfunction" => $processFunction,
			"userroleid" => $this->role,
			"userroletext" => $this->roleText,
			"userip" => $_SERVER['REMOTE_ADDR'],
			"useragent" => getBrowserAgent(),
			"agentstring" => $this->agent->agent_string(),
			"platform" => $this->agent->platform()
		);

		$this->load->model('login_model');
		$this->login_model->loginsert($logInfo);
	}



	/**
	 * Carica gli allegati dalla tabella _mod_allegati per il modulo
	 * @param mixed $moduleName
	 * @param mixed $entryId
	 * @return array 
	 */
	public function loadAllegati($moduleName, $entryId)
	{

		$allegati = $this->user_model->loadAllegati($moduleName, $entryId);

		return $allegati;
	}


	/**
	 * Ritorna le estensioni ammesse per l'upload
	 * @return array 
	 */
	public function loadExtAdmitted()
	{

		$extAdmitted = $this->user_model->loadExtAdmitted();

		$extAdmittedArray = explode(",", $extAdmitted[0]['system_file_ext_admitted']);

		return $extAdmittedArray;
	}


	/**
	 * 
	 * Carica Allegato in un modulo
	 * @param mixed $moduleName
	 * @param mixed $entryId
	 */
	public function caricaAllegato($moduleName, $entryId)
	{;
		$msgErr = "";
		$illegalChar = "èéà°ùòç#$%^&*ì£()+=-[]';,/\{}|:<>!?~";
		$illegalChar .= '"';

		$moduleUploadDirPath = FCPATH . "/uploads/allegati/" . $moduleName;
		$entryUploadDirPath = $moduleUploadDirPath . "/" . $entryId;

		if (!file_exists($moduleUploadDirPath)) {
			mkdir($moduleUploadDirPath, 0777, true);
		}

		if (!file_exists($entryUploadDirPath)) {
			mkdir($entryUploadDirPath, 0777, true);
		}

		$extAdmitted = $this->loadExtAdmitted();
		$upload_max_size = ini_get('upload_max_filesize');
		$upload_max_size = str_replace("M", "", $upload_max_size);
		$upload_max_size = (int)$upload_max_size * 1000000;

		$allegatiToUpload = array();
		$allegatiToExclude = array();

		//VERIFICO SE I FILES RISPETTANO LA DIMENSIONE MASSIMA E L'ESTENSIONE
		foreach ($_FILES['allegati']['size'] as $key => $allegato_size) {

			if ($allegato_size > $upload_max_size) {
				$allegatiToExclude[] = array("file" => $_FILES['allegati']['name'][$key], "type" => "EXCEED_SIZE");
			} else {
				$ext = pathinfo($_FILES['allegati']['name'][$key], PATHINFO_EXTENSION);
				if (!in_array($ext, $extAdmitted)) {
					$allegatiToExclude[] = array("file" => $_FILES['allegati']['name'][$key], "type" => "EXT_INCORRECT");
				} else {
					//ARRIVATO QUI, IL FILE RISPETTA DIMENSIONE ED ESTENSIONE
					$allegatiToUpload[] = array("name" => $_FILES['allegati']['name'][$key], "tmp_name" => $_FILES['allegati']['tmp_name'][$key]);
				}
			}
		}

		foreach ($allegatiToExclude as $key => $value) {
			if ($value['type'] == 'EXCEED_SIZE') {
				$this->session->set_flashdata('error', 'Il File <b>' . $value['file'] . "</b> eccede come limite consentito per il caricamento.");
			} else {
				$this->session->set_flashdata('error', 'Il File <b>' . $value['file'] . "</b> ha un estensione non consentita per il caricamento.");
			}
		}

		$nomeFile = "";
		foreach ($allegatiToUpload as $key => $allegato) {
			for ($i = 1; $i <= 10000; $i++) {
				$nomeFile = pathinfo($allegato["name"], PATHINFO_FILENAME);
				$ext = pathinfo($allegato["name"], PATHINFO_EXTENSION);
				$nomeFile = str_replace(" ", "", $nomeFile);
				$nomeFile = $nomeFile . "_" . $i . "." . $ext;
				if (!file_exists($entryUploadDirPath . "/" . $nomeFile)) {
					break;
				}
			}

			//if (preg_match('/[^a-zA-Z\d]/',  pathinfo($allegato["name"], PATHINFO_FILENAME))) {
			if (strpbrk(pathinfo($allegato["name"], PATHINFO_FILENAME), $illegalChar) == TRUE) {
				$msgErr .= "Il File <b>" . $allegato["name"] . "</b> contiene caratteri speciali non consentiti per il caricamento.<BR>";
			} else {
				@move_uploaded_file($allegato['tmp_name'], $entryUploadDirPath . "/" . $nomeFile);
				$this->user_model->caricaAllegato($moduleName, $entryId, $nomeFile);

				$dataLog = array(
					'programma' => "Caricamento Allegato:" . $moduleName . "/" . $entryId . "/" . $nomeFile,
					'utente' => $_SESSION['name'],
					'azione' => "Caricamento Allegato",
					'data' => date('Y-m-d H:i')
				);
				$this->Common_model->insertLog($dataLog);
			}
		}

		if ($msgErr != "") {
			$this->session->set_flashdata('error', $msgErr);
		}
	}



	/**
	 * 
	 * Rimuove un'allegato in un modulo
	 * @param mixed $moduleName
	 * @param mixed $entryId
	 * @param mixed $fileName
	 */
	public function rimuoviAllegato($moduleName, $entryId, $fileName)
	{
		$extAdmitted = $this->user_model->rimuoviAllegato($moduleName, $entryId, $fileName);

		$arrayDelete = array("success" => "OK");
		$file = FCPATH . "/uploads/allegati/" . $moduleName . "/" . $entryId . "/" . $fileName;
		$success = unlink($file);

		if (!$success) {
			$arrayDelete = array("success" => "KO");
		}

		$dataLog = array(
			'programma' => "Cancellazione Allegato:" . $moduleName . "/" . $entryId . "/" . $fileName,
			'utente' => $_SESSION['name'],
			'azione' => "Cancellazione Allegato",
			'data' => date('Y-m-d H:i')
		);
		$this->Common_model->insertLog($dataLog);

		echo json_encode($arrayDelete);
	}


	/**
	 * 
	 * Scarica un'allegato in un modulo
	 * @param mixed $moduleName
	 * @param mixed $entryId
	 * @param mixed $fileName
	 */
	public function scaricaAllegato($moduleName, $entryId, $fileName)
	{
		$this->load->helper('download');

		if ($fileName) {
			$file = FCPATH . "/uploads/allegati/" . $moduleName . "/" . $entryId . "/" . $fileName;

			if (file_exists($file)) {
				// get file content
				$data = file_get_contents($file);

				//force download
				force_download($fileName, $data);
			}
		}
	}



	/**
	 * 
	 * Scarica un'allegato blob in un modulo
	 * @param mixed $moduleName
	 * @param mixed $fieldName
	 * @param mixed $entryId
	 */
	public function scaricaAllegatoBlob($moduleName, $fieldName, $entryId)
	{
		$this->load->helper('download');

		$res = $this->user_model->getAllegatoBlob($moduleName, $fieldName, $entryId);
		/*
		echo $moduleName." - ".$fieldName." - ".$entryId;
		print'<pre>';print_r($res);
		die();
	 	*/
		echo "<b>Errore imprevisto nello scaricamento file!</b> <br><br><u>Chiudi questa pagina e contatta l'amministratore</u>";
		if (isset($res[0]['allegato'])) {
			$data = file_get_contents($res[0]['allegato']);
			force_download($res[0]['nome_allegato'], $res[0]['allegato']);
		} 
	}



	/**
	 * 
	 * Rimuove un'allegato in un modulo
	 * @param mixed $moduleName
	 * @param mixed $entryId
	 * @param mixed $fileName
	 */
	public function rimuoviAllegatoBlob($moduleName, $fieldName, $entryId, $fieldType)
	{
		$arrayDelete = array("success" => "OK");

		$success = $this->user_model->rimuoviAllegatoBlob($moduleName, $fieldName, $entryId, $fieldType);

		if (($success != "") || ($success != 0)) {
			$arrayDelete = array("success" => "KO");
		}

		$dataLog = array(
			'programma' => "Cancellazione Allegato Blob:" . $moduleName . "/" . $entryId . "/" . $fieldName,
			'utente' => $_SESSION['name'],
			'azione' => "Cancellazione Allegato o",
			'data' => date('Y-m-d H:i')
		);
		$this->Common_model->insertLog($dataLog);

		echo json_encode($arrayDelete);
	}


	//
	//
	//FUNZIONI DI BASE COMUNI A TUTTI I CONTROLLER
	//
	//


	/**
	 * Metodo principale index
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
			'formFieldsReferencedColumns' => $this->formFieldsReferencedColumns,
			'mod_type' => $this->mod_type,
			'comboGridFilter' => $this->arrayComboGridFilter
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
	 * Metodo JSON per il caricamento via ajax della griglia datatable
	 */
	public function json()
	{
		header('Content-Type: application/json');
		$searchFilter = array();
		if (isset($_REQUEST['searchFilter'])) {
			foreach ($_REQUEST['searchFilter'] as $keyField => $fieldValue) {
				if ($fieldValue['value'] != '') {
					switch ($fieldValue['type_field']) {
						case 'date':
						case 'datetime':
							$value = date('Y-m-d', strtotime($fieldValue['value']));
							$dateArray = explode('/', $fieldValue['value']);
							$value = $dateArray[2] . '-' . $dateArray[1] . '-' . $dateArray[0];
							$searchFilter[] = array('field' => $fieldValue['field'], 'value' => $value);
							break;

						default:
							$searchFilter[] = array('field' => $fieldValue['field'], 'value' => $fieldValue['value']);
							break;
					}
				}
			}
		}

		//print'<pre>';print_r($_REQUEST);
		echo $this->modelClassModule->json($searchFilter);
	}


	/**
	 * Carica i dati per un form - SOLA LETTURA 
	 * @param mixed $id
	 */
	public function readAjax($id)
	{
		$this->global['pageTitle'] = $this->mod_title . ' - Dettaglio sola lettura';
		$formFieldsReferenced =  $this->getFormFieldsReferenced();
		$formFieldsReferencedTableRef =  $this->getFormFieldsReferencedTableRef();

		$row = $this->modelClassModule->get_by_id($id);
		if ($row) {
			$data = array();
			foreach ($this->formFields as $key => $property) {
				if ($this->utilities->validateAllDateType($row->$property, 'Y-m-d')) {
					$data[$property] = $this->utilities->convertToDateIT($row->$property);
				} elseif ($this->utilities->validateAllDateType($row->$property, 'Y-m-d H:i:s')) {
					$data[$property] = $this->utilities->convertToDateTimeIT($row->$property);
				} else {
					$data[$property] = $row->$property;
				}
				if (in_array($property, $formFieldsReferenced)) {
					$arrayColumns = $this->getFormFieldsReferencedColumns($property);
					$id =  $arrayColumns['id'];
					$value =  $arrayColumns['nome'];
					$where_condition = NULL;
					if (isset($arrayColumns['where_condition'])) {
						$where_condition =  $arrayColumns['where_condition'];
					}
					$data[$property . '_refval'] = $this->modelClassModule->getValuesByFk($formFieldsReferencedTableRef[$property], $id, $value, $where_condition);
				}
			}
			$data['id'] = set_value($property, $id);
			$data['allegati'] = $this->loadAllegati($this->mod_name, $id);
			$this->load->view($this->mod_name . '/' . $this->mod_name . '_read_ajax', $data);
		} else {
			$this->session->set_flashdata('message', 'Record non trovato');
			redirect(site_url($this->mod_name));
		}
	}



	/**
	 * Carica i dati per un form - INSERIMENTO
	 */
	public function createAjax(String $winForm = "FALSE")
	{
		$this->global['pageTitle'] = $this->mod_title . ' - Nuovo';
		$data = array();
		$formFieldsReferenced =  $this->getFormFieldsReferenced();
		$formFieldsReferencedTableRef =  $this->getFormFieldsReferencedTableRef();

		//ESEGUO EVENTUALI "CUSTOM RULES"
		foreach ($this->custom_rules_createAjax as $functionKey => $function) {
			$function();
		}


		//ESEGUO EVENTUALI FUNZIONI AGGIUNTIVE PER CUSTOMIZZARE IL VETTORE $data
		foreach ($this->custom_form_data_functions as $keyData => $function) {
			$data[$keyData] = $function();
		}


		foreach ($this->formFields as $key => $property) {
			$data[$property] = set_value($property);
			if (in_array($property, $formFieldsReferenced)) {
				$arrayColumns = $this->getFormFieldsReferencedColumns($property);
				$id =  $arrayColumns['id'];
				$value =  $arrayColumns['nome'];
				$where_condition = NULL;
				if (isset($arrayColumns['where_condition'])) {
					$where_condition =  $arrayColumns['where_condition'];
				}
				$data[$property . '_refval'] = $this->modelClassModule->getValuesByFk($formFieldsReferencedTableRef[$property], $id, $value, $where_condition);
			}
		}
		$data['id'] = NULL;

		$data['button'] = 'Create';
		$data['button_id'] = 'bt_update';
		$data['frm_module_name'] = 'frm_' . $this->mod_name;
		$data['action'] = site_url($this->mod_name . '/create_action');
		$data['type_action'] = 'create';
		$data['afterSave'] = NULL;
		$data['allegati'] = array();
		$data['winForm'] = $winForm;
		$data['extAdmitted'] = $this->loadExtAdmitted();
		//print'<pre>';print_r($data);die();

		$this->load->view($this->mod_name . '/' . $this->mod_name . '_form_ajax', $data);
	}


	/**
	 * @param mixed $request
	 * @return array
	 */
	private function sanitizeRequest($request)
	{
		$fieldsDetails = $this->modelClassModule->getFieldsDetails();
		//print'<pre>';print_r($fieldsDetails);
		foreach ($request as $property => $value) {
			if (isset($fieldsDetails[$property])) {
				$data_type = $fieldsDetails[$property]['DATA_TYPE'];
				switch ($data_type) {
					case 'set':
					case 'SET':
						$request[$property] = implode(",", $request[$property]);
						break;

					default:
						$request[$property] = $this->input->post($property, true);
						break;
				}
			}
		}

		return $request;
	}


	/**
	 * Esegue l'inserimento del modulo in modalita insert
	 */
	public function create_action(String $winForm = "FALSE")
	{
		$_REQUEST = $this->sanitizeRequest($_REQUEST);
		$_POST = $this->utilities->upperCaseRequest($_POST);

		$fieldsDetails = $this->modelClassModule->getFieldsDetails();
		$this->_rules();
		$arrayBlobFile = array();
		if (isset($_SESSION['success'])) {
			unset($_SESSION['success']);
		}
		if (isset($_SESSION['error'])) {
			unset($_SESSION['error']);
		}

		$upload_max_size = ini_get('upload_max_filesize');
		$upload_max_size_int = str_replace("M", "", $upload_max_size);
		$upload_max_size_int = (int) $upload_max_size_int * 1000000;
		$CONTENT_LENGTH = $_SERVER['CONTENT_LENGTH'];
		if ($CONTENT_LENGTH > ((int) ini_get('post_max_size') * 1024 * 1024)) {
			$this->session->set_flashdata('error', "I files caricati eccedono il limite consentito come peso(" . ini_get('post_max_size') . ")");
			die("<br><br><b style='font-size:22px'>Attenzione! I files caricati superano il limite consentito IN POST (" . ini_get('post_max_size') . ")</b>
							<br><b style='font-size:18px'>Chiudere La pagina e riprovare con files piu leggeri
							<br><u>LE MODIFICHE NON VERRANNO SALVATE</u>.</b>");
		}

		if ($this->form_validation->run() == false) {
			$this->createAjax($winForm);
		} else {
			//ESEGUO EVENTUALI "CUSTOM OPERATIONS"
			foreach ($this->custom_operations_list as $functionKey => $function) {
				$ret = $function($_REQUEST);
				if ($ret === FALSE) {
					$this->createAjax($winForm);
					return false;
				}
			}

			$data = array();
			//print'<pre>';print_r($this->formFields);die();
			$fieldsArrayGrid = $this->modelClassModule->getFieldsArrayGrid();
			foreach ($this->formFields as $key => $property) {
				if (isset($fieldsDetails[$property])) {
					$data_type = $fieldsDetails[$property]['DATA_TYPE'];
					if (($data_type == 'set') || ($data_type == 'SET')) {
						$data[$property] = implode(",", $this->input->post($property));
					} else {
						if ($this->utilities->validateAllDateType($this->input->post($property), 'd/m/Y')) {
							$data[$property] = $this->utilities->convertToDateEN($this->input->post($property));
						} elseif ($this->utilities->validateAllDateType($this->input->post($property), 'd/m/Y H:i:s')) {
							$data[$property] = $this->utilities->convertToDateTimeEN($this->input->post($property));
						} else {
							if ((isset($_FILES[$property])) && (trim($_FILES[$property] != ""))) {
								if (trim($_FILES[$property]['name']) != "") {
									$fieldType = $fieldsArrayGrid[$property]['type'];
									$ext = pathinfo($_FILES[$property]['name'], PATHINFO_EXTENSION);
									if ($fieldType == FIELD_BLOB_IMG) {
										//VERIFICO ESTENSIONI IMMAGINI AMMESSE
										$res = $this->user_model->loadImgExtAdmitted();
										$ImgExtAdmitted = explode(",", $res[0]['system_imgfile_ext_admitted']);
										if (in_array($ext, $ImgExtAdmitted)) {
											$data[$property] = @file_get_contents($_FILES[$property]['tmp_name']);
										} else {
											$this->session->set_flashdata('error', "Estensione File immagine <b><u>$ext</u></b> non ammessa(Ammessi:" . implode(",", $ImgExtAdmitted));
										}
									} else {
										//VERIFICO ESTENSIONI AMMESSE
										$res = $this->user_model->loadExtAdmitted();
										$ExtAdmitted = explode(",", $res[0]['system_file_ext_admitted']);
										if (in_array($ext, $ExtAdmitted)) {
											//SE BLOB LO SALVO NELLA TABELLA ALLEGATI BLOB
											if ($fieldType == FIELD_BLOB) {
												$arrayBlobFile[$property] = array("field_name" => $property, "nome_allegato" => $_FILES[$property]['name']);
												$data[$property] = @file_get_contents($_FILES[$property]['tmp_name']);
											}
										} else {
											$this->session->set_flashdata('error', "Estensione File <b><u>$ext</u></b> non ammessa(Ammessi:" . implode(",", $ExtAdmitted));
										}
									}
								}
							} else {
								$data[$property] = $this->input->post($property, true);
							}
						}
					}
				}
			}

			$ret = $this->modelClassModule->insert($data);
			if (($ret['code'] == 0) || ($ret['code'] == '0')) {
				$this->session->set_flashdata('success', $this->MsgDBConverted["insert"]["success"]);
				$id = $this->modelClassModule->getLastInsertId();

				//SALVO ALLEGATI BLOB IN TABELLA
				foreach ($arrayBlobFile as $k => $v) {
					if (isset($v['field_name'])) {
						$this->modelClassModule->saveBlob($this->mod_name, $v['field_name'], $id, $v['nome_allegato']);
					}
				}

				if (isset($_FILES['allegati'])) {
					if ($CONTENT_LENGTH > $upload_max_size_int) {
						$this->session->set_flashdata('error', "I files caricati eccedono il limite consentito come peso('.$upload_max_size.')");
					} else {
						if ($_FILES['allegati']["error"][0] == "0") {
							$this->caricaAllegato($this->mod_name, $id);
						}
					}
				}


				$dataLog = array(
					'programma' => $this->mod_title,
					'utente' => $_SESSION['name'],
					'azione' => "Inserimento",
					'data' => date('Y-m-d H:i'),
				);
				$this->Common_model->insertLog($dataLog);
				$this->updateAjax($id, TRUE, $winForm);
			} else {
				if (isset($this->MsgDBConverted["insert"]["error"][$ret['code']])) {
					$this->session->set_flashdata('error', "Errore :" . $ret['code'] . " - " . $this->MsgDBConverted["insert"]["error"][$ret['code']]);
				} else {
					$this->session->set_flashdata('error', "Errore :" . $ret['code'] . " - " . $this->mysql_error_codes[$ret['code']]['message_template']);
				}

				$this->createAjax($winForm);
			}
		}
	}



	/**
	 * Carica i dati per un form - MODIFICA
	 * @param mixed $id
	 * @param mixed|null $afterSave
	 */
	public function updateAjax($id, $afterSave = null, String $winForm = "FALSE", $validation_failed = FALSE)
	{
		$this->pkIdValue = $id;

		$this->global['pageTitle'] = $this->mod_title . ' - Aggiornamento';

		if($validation_failed == TRUE){
			$request = $_REQUEST;
			$row = new stdClass();
			foreach($request as $key => $val){
				$expl = explode("/",$val);
				if(isset($expl[2])){
					if(checkdate($expl[1], $expl[0], $expl[2]) == TRUE){
						$row->{$key} = $this->utilities->convertToDateEN($val);
					} else {
						$row->{$key} = $val;
					}
				} else {
					$row->{$key} = $val;
				}

				if (strpos($key, '_hidden') !== FALSE) {
					$newKey = str_replace("_hidden","",$key);
					$row->{$newKey} = $val;
				}					
			}
			
		} else {
			$row = $this->modelClassModule->get_by_id($id);
		}
		//print'<pre>';print_r($row);
 
		$formFieldsReferenced =  $this->getFormFieldsReferenced();
		$formFieldsReferencedTableRef =  $this->getFormFieldsReferencedTableRef();
		$fieldsArrayGrid = $this->modelClassModule->getFieldsArrayGrid();

		//ESEGUO EVENTUALI "CUSTOM RULES"
		foreach ($this->custom_rules_updateAjax as $functionKey => $function) {
			$function($id);
		}


		if ($row) {
			$data = array();

			//print'<pre>';print_r($this->custom_form_data_functions);
			//ESEGUO EVENTUALI FUNZIONI AGGIUNTIVE PER CUSTOMIZZARE IL VETTORE $data
			foreach ($this->custom_form_data_functions as $keyData => $function) {
				$data[$keyData] = $function($id);
			}

			foreach ($this->formFields as $key => $property) {
				if (isset($fieldsArrayGrid[$property]['type'])) {
					$fieldType = $fieldsArrayGrid[$property]['type'];
					switch ($fieldType) {
						case FIELD_DATE:
							$data[$property] = $this->utilities->convertToDateIT($row->$property);
							break;

						case FIELD_DATETIME:
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
				} else {
					if (isset($row->$property)) {
						$data[$property] = $row->$property;
					} else {
						$data[$property] = NULL;
					}
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
					$res = $this->modelClassModule->getPermissionRoleTabs($_SESSION['role'], $this->mod_name, $masterDetailsLoadFunc['function']);
					if ((int)$res[0]['have_tab_perm'] > 0) {
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
	 * Esegue l'inserimento del modulo in modalita update
	 */
	public function update_action()
	{
		$_REQUEST = $this->sanitizeRequest($_REQUEST);
		$_POST = $this->utilities->upperCaseRequest($_POST);
		//print'<pre>';print_r($_POST);die();

		$fieldsDetails = $this->modelClassModule->getFieldsDetails();
		$this->_rules();
		$id = $this->input->post($this->pkIdName, true);
		$arrayBlobFile = array();
		if (isset($_SESSION['success'])) {
			unset($_SESSION['success']);
		}
		if (isset($_SESSION['error'])) {
			unset($_SESSION['error']);
		}

		$upload_max_size = ini_get('upload_max_filesize');
		$upload_max_size_int = str_replace("M", "", $upload_max_size);
		$upload_max_size_int = (int) $upload_max_size_int * 1000000;
		$CONTENT_LENGTH = $_SERVER['CONTENT_LENGTH'];
		if ($CONTENT_LENGTH > ((int) ini_get('post_max_size') * 1024 * 1024)) {
			$this->session->set_flashdata('error', "I files caricati eccedono il limite consentito come peso(" . ini_get('post_max_size') . ")");
			die("<br><br><b style='font-size:22px'>Attenzione! I files caricati superano il limite consentito IN POST (" . ini_get('post_max_size') . ")</b>
						<br><b style='font-size:18px'>Chiudere La pagina e riprovare con files piu leggeri
						<br><u>LE MODIFICHE NON VERRANNO SALVATE</u>.</b>");
		}

		if ($this->form_validation->run() == false) {
			$this->updateAjax($id, NULL, "FALSE", TRUE);
		} else {

			//ESEGUO EVENTUALI "CUSTOM OPERATIONS"
			foreach ($this->custom_operations_list as $functionKey => $function) {
				$ret = $function($_REQUEST, $id);
				if ($ret === FALSE) {
					$this->updateAjax($id, NULL, "FALSE", TRUE);
					return false;
				}
			}

			//print'<pre>';print_r($this->formFields);
			//print'<pre>';print_r($_REQUEST);

			$data = array();
			$fieldsArrayGrid = $this->modelClassModule->getFieldsArrayGrid();

			foreach ($this->formFields as $key => $property) {
				if (isset($fieldsDetails[$property])) {
					$data_type = $fieldsDetails[$property]['DATA_TYPE'];
					if (($data_type == 'set') || ($data_type == 'SET')) {
						$data[$property] = implode(",", $this->input->post($property));
					} else {
						if ($this->utilities->validateAllDateType($this->input->post($property), 'd/m/Y')) {
							$data[$property] = $this->utilities->convertToDateEN($this->input->post($property));
						} elseif ($this->utilities->validateAllDateType($this->input->post($property), 'd/m/Y H:i:s')) {
							$data[$property] = $this->utilities->convertToDateTimeEN($this->input->post($property));
						} else {
							if ((isset($_FILES[$property])) && (trim($_FILES[$property] != ""))) {
								if (trim($_FILES[$property]['name']) != "") {
									$fieldType = $fieldsArrayGrid[$property]['type'];
									$ext = pathinfo($_FILES[$property]['name'], PATHINFO_EXTENSION);
									if ($fieldType == FIELD_BLOB_IMG) {
										//VERIFICO ESTENSIONI IMMAGINI AMMESSE
										$res = $this->user_model->loadImgExtAdmitted();
										$ImgExtAdmitted = explode(",", $res[0]['system_imgfile_ext_admitted']);
										if (in_array($ext, $ImgExtAdmitted)) {
											$data[$property] = file_get_contents($_FILES[$property]['tmp_name']);
										} else {
											$this->session->set_flashdata('error', "Estensione File immagine <b><u>$ext</u></b> non ammessa(Ammessi:" . implode(",", $ImgExtAdmitted));
										}
									} else {
										//VERIFICO ESTENSIONI AMMESSE
										$res = $this->user_model->loadExtAdmitted();
										$ExtAdmitted = explode(",", $res[0]['system_file_ext_admitted']);
										if (in_array($ext, $ExtAdmitted)) {
											//SE BLOB LO SALVO NELLA TABELLA ALLEGATI BLOB
											if ($fieldType == FIELD_BLOB) {
												$arrayBlobFile[$property] = array("field_name" => $property, "nome_allegato" => $_FILES[$property]['name']);
												$data[$property] = file_get_contents($_FILES[$property]['tmp_name']);
											}
										} else {
											$this->session->set_flashdata('error', "Estensione File <b><u>$ext</u></b> non ammessa(Ammessi:" . implode(",", $ExtAdmitted));
										}
									}
								}
							} else {
								$data[$property] = $this->input->post($property, true);
							}
						}
					}
				}
			}


			$ret = $this->modelClassModule->update($id, $data);
			if (($ret['code'] == '0') || ($ret['code'] == 0)) {
				$this->session->set_flashdata('success', $this->MsgDBConverted["update"]["success"]);

				//SALVO ALLEGATI BLOB IN TABELLA
				foreach ($arrayBlobFile as $k => $v) {
					$this->modelClassModule->saveBlob($this->mod_name, $v['field_name'], $id, $v['nome_allegato']);
				}

				if (isset($_FILES['allegati'])) {
					if ($CONTENT_LENGTH > $upload_max_size_int) {
						$this->session->set_flashdata('error', "I files caricati eccedono il limite consentito come peso('.$upload_max_size.')");
					} else {
						if ($_FILES['allegati']["error"][0] == "0") {
							$this->caricaAllegato($this->mod_name, $id);
						}
					}
				}


				$dataLog = array(
					'programma' => $this->mod_name,
					'utente' => $_SESSION['name'],
					'azione' => "Modifica",
					'data' => date('Y-m-d H:i'),
				);
				$this->Common_model->insertLog($dataLog);

				$this->updateAjax($id);
			} else {
				if (isset($this->MsgDBConverted["update"]["error"][$ret['code']])) {
					$this->session->set_flashdata('error', "Errore :" . $ret['code'] . " - " . $this->MsgDBConverted["update"]["error"][$ret['code']]);
				} else {
					$this->session->set_flashdata('error', "Errore :" . $ret['code'] . " - " . $this->mysql_error_codes[$ret['code']]['message_template']);
				}
			}
		}
	}



	/**
	 * 
	 * Cancellazione singolo record
	 * @param mixed $id
	 */
	public function delete($id)
	{
		$row = $this->modelClassModule->get_by_id($id);

		if ($row) {

			$ret = $this->modelClassModule->delete($id);
			//print'<pre>';print_r($ret);die();
			if (($ret['code'] == '0') || ($ret['code'] == 0)) {
				$dataLog = array(
					'programma' => $this->mod_title,
					'utente' => $_SESSION['name'],
					'azione' => "Cancellazione ",
					'data' => date('Y-m-d H:i'),
				);
				$this->Common_model->insertLog($dataLog);
				$this->session->set_flashdata('success', $this->MsgDBConverted["delete"]["success"]);
			} else {
				if (isset($this->MsgDBConverted["delete"]["error"][$ret['code']])) {
					//$this->session->set_flashdata('error', "Errore :" . $ret['code'] . " - " . $this->MsgDBConverted["delete"]["error"][$ret['code']] . " [<a href='" . $ret['link_mod'] . "' target='_blank'>" . $ret['module_title'] . "</a>]");
					$this->session->set_flashdata('error', "Errore :" . $ret['code'] . " - " . $this->MsgDBConverted["delete"]["error"][$ret['code']]  . ": <u>". $ret['module_title']."</u>");					
				} else {
					$this->session->set_flashdata('error', "Errore :" . $ret['code'] . " - " . $this->mysql_error_codes[$ret['code']]['message_template']);
				}
			}
		} else {
			$this->session->set_flashdata('error', $this->MsgDBConverted["delete"]["record_not_found"]);
		}

		redirect(site_url($this->mod_name));
	}



	/**
	 * 
	 * Cancellazione massiva records
	 */
	public function deleteMassive()
	{
		$entry_list = $_REQUEST['entry_list'];
		$entryListArray = explode(",", $entry_list);
		if (is_array($entryListArray)) {

			$ret = $this->modelClassModule->deleteMassive($entryListArray);
			if (($ret['code'] == '0') || ($ret['code'] == 0)) {
				$dataLog = array(
					'programma' => $this->mod_title,
					'utente' => $_SESSION['name'],
					'azione' => "Cancellazione Massiva",
					'data' => date('Y-m-d H:i'),
				);
				$this->Common_model->insertLog($dataLog);
				$this->session->set_flashdata('success', $this->MsgDBConverted["delete_massive"]["success"]);
			} else {
				if (isset($this->MsgDBConverted["delete_massive"]["error"][$ret['code']])) {
					//$this->session->set_flashdata('error', "Errore :" . $ret['code'] . " - " . $this->MsgDBConverted["delete_massive"]["error"][$ret['code']] . " [<a href='" . $ret['link_mod'] . "' target='_blank'>" . $ret['module_title'] . "</a>]");
					$this->session->set_flashdata('error', "Errore :" . $ret['code'] . " - " . $this->MsgDBConverted["delete_massive"]["error"][$ret['code']] . ": <u>". $ret['module_title']."</u>");
				} else {
					$this->session->set_flashdata('error', "Errore :" . $ret['code'] . " - " . $this->mysql_error_codes[$ret['code']]['message_template']);
				}
			}
		} else {
			$this->session->set_flashdata('error', $this->MsgDBConverted["delete_massive"]["record_not_found"]);
		}

		redirect(site_url($this->mod_name));
	}



	public function get_spaiker_cr_gnu_test()
	{
	}


	/**
	 * Validazione campi
	 * Il metodo viene overridato in ogni modulo
	 */
	public function _rules()
	{
	}



	//
	//
	//[GESTIONE CAMPI FORM ]
	//
	//


	/**
	 * Setta i nomi dei campi utilizzati nella griglia e nel form
	 * @param string $fieldName
	 * @param mixed|null $tableNameReferenced
	 * @param mixed|null $arrayColumns
	 */
	protected function setFormFields(String $fieldName, $tableNameReferenced = NULL, $arrayColumns = NULL, $where_condition = NULL, $filter_slave_id = NULL)
	{
		$this->formFields[] = $fieldName;

		if (isset($tableNameReferenced)) {
			$this->formFieldsReferenced[$fieldName] = $fieldName;
			$this->formFieldsReferencedTableRef[$fieldName] = $tableNameReferenced;
		}

		if (isset($arrayColumns['id'])) {
			$this->formFieldsReferencedColumns[$fieldName]['id'] = $arrayColumns['id'];
			$this->formFieldsReferencedColumns[$fieldName]['table'] = $tableNameReferenced;
		}
		if (isset($arrayColumns['nome'])) {
			$this->formFieldsReferencedColumns[$fieldName]['nome'] = $arrayColumns['nome'];
		}
		if (isset($where_condition)) {
			$this->formFieldsReferencedColumns[$fieldName]['where_condition'] = $where_condition;
		}
		if (isset($filter_slave_id)) {
			$this->formFieldsReferencedColumns[$fieldName]['filter_slave_id'] = $filter_slave_id;
		}
	}



	/**
	 * Restituisce l'elenco dei campi referenziati su altra tabella nel form/modulo
	 * @return array
	 */
	public function getFormFieldsReferenced()
	{
		return $this->formFieldsReferenced;
	}


	/**
	 * Setta un campi referenziato su altra tabella nel form/modulo
	 * @param mixed $formFieldsReferenced
	 * @param mixed $tableNameReferenced
	 * @param mixed|null $arrayColumns
	 */
	public function setFormFieldsReferenced($formFieldsReferenced, $tableNameReferenced, $arrayColumns = NULL)
	{
		$this->formFieldsReferenced[$formFieldsReferenced] = $formFieldsReferenced;
		$this->formFieldsReferencedTableRef[$formFieldsReferenced] = $tableNameReferenced;
		if (isset($arrayColumns['id'])) {
			$this->formFieldsReferencedColumns[$formFieldsReferenced]['id'] = $arrayColumns['id'];
		}
		if (isset($arrayColumns['nome'])) {
			$this->formFieldsReferencedColumns[$formFieldsReferenced]['nome'] = $arrayColumns['nome'];
		}
	}


	/**
	 * Restituisce per il campo referenziato la relativa tabella dei riferimento
	 * @return array
	 */
	public function getFormFieldsReferencedTableRef()
	{
		return $this->formFieldsReferencedTableRef;
	}


	/**
	 * Setta per il campo referenziato la relativa tabella dei riferimento
	 * @param mixed $formFieldsReferenced
	 * @param mixed $tableNameReferenced
	 *
	 */
	public function setFormFieldsReferencedTableRef($formFieldsReferenced, $tableNameReferenced)
	{
		$this->formFieldsReferencedTableRef[$formFieldsReferenced] = $tableNameReferenced;
	}


	/**
	 * Ritorna le colonne di una tabella referenziata
	 * @param mixed $tableNameReferenced
	 */
	public function getFormFieldsReferencedColumns($tableNameReferenced)
	{
		return $this->formFieldsReferencedColumns[$tableNameReferenced];
	}


	/**
	 * 
	 * Ritorna le colonne chiave/valore di una tabella referenziata
	 * @param mixed $tableNameReferenced
	 * @param mixed $table_referenced
	 *  @param mixed $id
	 */
	public function getKeyValuesFromTable($table_referenced, $id = "id", $value = "id", $where_condition = NULL)
	{
		$arrayReturn = $this->modelClassModule->getValuesByFk($table_referenced, $id, $value, $where_condition);

		echo json_encode($arrayReturn);
	}



	/**
	 * 
	 * Ritorna le colonne chiave/valore di una tabella referenziata
	 * @param mixed $tableNameReferenced
	 * @param mixed $table_referenced
	 *  @param mixed $id
	 */
	public function getKeyValuesFromTableViaPOST()
	{
		$table_referenced 	= $_REQUEST['table_referenced'];
		$id 			 	= $_REQUEST['id'];
		$value 				= $_REQUEST['value'];
		$where_condition = NULL;
		if (isset($_REQUEST['where_condition'])) {
			$where_condition = $_REQUEST['where_condition'];
		}
		$arrayReturn = $this->modelClassModule->getValuesByFk($table_referenced, $id, $value, $where_condition);

		echo json_encode($arrayReturn);
	}


	//
	//
	//[MASTER DETAILS]
	//
	//


	/**
	 * 
	 * Carica il dettaglio in una finestra per una master details 
	 * @param string $action
	 * @param string $winFormMasterDetailsFunc
	 * @param mixed $entryID
	 * @param mixed|null $entryIDMasterDetails
	 */
	public function loadWinMasterDetails(String $action, String $winFormMasterDetailsFunc, $entryID, $entryIDMasterDetails = NULL)
	{
		if ($action == 'insert') {
			$html = $this->$winFormMasterDetailsFunc($action, $entryID);
		} else {
			$html = $this->$winFormMasterDetailsFunc($action, $entryID, $entryIDMasterDetails);
		}

		echo $html;
	}


	/**
	 * 
	 * Salvo i dati della master details 
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

					/*
					if(is_string($value)){
						$data[$key] = mb_strtoupper($value);
					} else {
						$data[$key] = $value;
					}
					*/

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
		//print'<pre>';print_r($data);die();

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
	 * Cancellazione massiva records
	 */
	public function delete_massive_master_details($id, $entry_list, $table)
	{
		$entryListArray = explode(",", $entry_list);
		$masterDetailsHtml = "";
		$html = "";
		$success = 'TRUE';

		if (is_array($entryListArray)) {
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



	/**
	 * 
	 * Ritorna i tab master details via ajax dopo un'operazione CRUD 
	 * @param mixed $id
	 * @return string
	 */
	protected function getAjaxMasterDetails($id)
	{
		foreach($this->custom_rules_reload_master_details as $k => $function){
			$function($id);
		}
		
		$masterDetailsHtml = "";
		$countTab = 0;
		$master_details_list = array();
		foreach ($this->masterDetailsLoadFuncList as $key => $masterDetailsLoadFunc) {
			$function = $masterDetailsLoadFunc['function'];
			$master_details_list[] = array("title" => $masterDetailsLoadFunc['title'], 'id' => $masterDetailsLoadFunc['id'], "function" => $this->$function($id));
		}


		$countTab = 0;
		$masterDetailsHtml .= '<ul class="nav nav-tabs" id="myTab" role="tablist">	';
		foreach ($master_details_list as  $key => $master_details) {
			if ($countTab == 0) {
				$masterDetailsHtml .= '<li class="nav-item active">
					<a class="nav-link active" id="lnk-' . $master_details['id'] . '" data-toggle="tab" href="#' . $master_details['id'] . '" role="tab" aria-controls="' . $master_details['id'] . '" aria-selected="true" aria-expanded="true">' . $master_details['title'] . '</a>
				</li>';
			} else {
				$masterDetailsHtml .= '<li class="nav-item ">
					<a class="nav-link" id="lnk-' . $master_details['id'] . '" data-toggle="tab" href="#' . $master_details['id'] . '" role="tab" aria-controls="' . $master_details['id'] . '"   >' . $master_details['title'] . '</a>
				</li>';
			}
			$countTab++;
		}
		$masterDetailsHtml .= '</ul>';

		$masterDetailsHtml .= '<div class="tab-content">';
		$countTab = 0;
		foreach ($master_details_list as  $key => $master_details) {
			$active = "active";
			if ($countTab > 0) {
				$active = "";
			}
			$masterDetailsHtml .= '<div class="tab-pane ' . $active . '" id="' . $master_details['id'] . '" role="tabpanel" aria-labelledby="' . $master_details['id'] . '-tab">';
			$masterDetailsHtml .=  $master_details['function'];
			$masterDetailsHtml .= '</div>';
			$countTab++;
		}

		$masterDetailsHtml .= '</div>';

		return $masterDetailsHtml;
	}



	/**
	 * Aggiunge per il modulo le funzioni per il caricamento delle  master details
	 * @param string $masterDetailsLoadFunc
	 */
	protected function addMasterDetailsLoadFunc(String $masterDetailsLoadFunc, String $masterDetailsTitle = NULL, String $masterDetailsID = NULL)
	{
		$this->masterDetailsLoadFuncList[$masterDetailsLoadFunc] = array("title" => $masterDetailsTitle, "id" => $masterDetailsID,  "function" => $masterDetailsLoadFunc);
	}



	/**
	 * Aggiunge un filtro combobox in una griglia
	 * 
	 * @param string $filterName
	 * @param string|null $tableNameReferenced
	 * @param string|null $fielIDName
	 * @param string|null $fieldValueName
	 * @param string|null $label
	 * @param array|null $filterSlave
	 * @param string|bool $multiselect
	 * @param string|bool $bootstrapSelect
	 * @param string|bool $clsCSS
	 * @return void
	 */
	protected function addComboGridFilter(String $filterName, String $tableNameReferenced = NULL, String $fielIDName = NULL, String $fieldValueName = NULL, String $label = NULL, array $filterSlave = NULL, Bool $multiselect = FALSE, Bool $bootstrapSelect = FALSE, String $clsCSS = NULL, String $jsFunction = NULL)
	{
		$this->arrayComboGridFilter[$filterName] = [
			"filterName" => $filterName,
			"label" => $label,
			'id' => $fielIDName,
			'name' => $fieldValueName,
			'itemsList' => array(),
			'filterSlave' => NULL,
			'multiselect' => FALSE,
			'bootstrapSelect' => FALSE
		];

		if (($tableNameReferenced != NULL) && ($fielIDName != NULL) && ($fieldValueName != NULL)) {
			$this->arrayComboGridFilter[$filterName]['itemsList'] =  $this->modelClassModule->getValuesByFk($tableNameReferenced, $fielIDName, $fieldValueName);
		}

		if (($filterSlave != NULL) && (is_array($filterSlave))) {
			$this->arrayComboGridFilter[$filterName]['filterSlave'] = $filterSlave;
		}

		if ((!is_null($multiselect)) && ($multiselect == TRUE)) {
			$this->arrayComboGridFilter[$filterName]['multiselect'] = TRUE;
		} else {
			$this->arrayComboGridFilter[$filterName]['multiselect'] = FALSE;
		}

		if ((!is_null($bootstrapSelect)) && ($bootstrapSelect == TRUE)) {
			$this->arrayComboGridFilter[$filterName]['bootstrapSelect'] = TRUE;
		} else {
			$this->arrayComboGridFilter[$filterName]['bootstrapSelect'] = FALSE;
		}

		if ((!is_null($clsCSS))) {
			$this->arrayComboGridFilter[$filterName]['clsCSS'] = $clsCSS;
		} else {
			$this->arrayComboGridFilter[$filterName]['clsCSS'] = $clsCSS;
		}

		if ((!is_null($jsFunction))) {
			$this->arrayComboGridFilter[$filterName]['jsFunction'] = $jsFunction;
		} else {
			$this->arrayComboGridFilter[$filterName]['jsFunction'] = $jsFunction;
		}

		
	}


	/**
	 * 
	 */
	protected function genTableDataFromRows(array $rows, $id, String $pkField = NULL, $tableName = NULL, array $columns = NULL)
	{
		if ($pkField == NULL) {
			$pkField = 'id';
		}

		$columns_array = array_keys($rows);

		$html = "";
		$html .= '<table class="TFtable" id="' . $tableName . '" style="font-size:12px">
					<tbody>
						<tr>';
		foreach ($columns_array as $key => $colname) {
			$html = '		<td style="width:10%">' . $colname . '</td>';
		}
		$html .= '			<td style="width:10%">Modifica</td>
							<td style="width:10%">Elimina</td>
						</tr>';

		$hiddenSet = FALSE;
		foreach ($rows as $key => $value) {
			$html .= "<tr>";

			foreach ($columns_array as $key => $colname) {
				$html .= "<td>";
				if ($hiddenSet == FALSE) {
					$html .= "<input type='hidden' id='id[]' name='id[]' value='" . $value[$pkField] . "'>";
					$hiddenSet = TRUE;
				}
				$html .= $value[$colname];
				$html .= "</td>";
			}


			$html .= "<td><a style='cursor:pointer' class='btn btn-sm btn-info' onclick ='winFormMasterDetails(\"" . $value[$pkField] . "\",\"" . $value[$pkField] . "\")' title='Modifica'><i class='fa fa-edit'></a></td>";
			$html .= "<td><a style='cursor:pointer' class='btn btn-sm btn-danger deleteUser' onclick ='deleteMasterDetails(\"" . $value[$pkField] . "\", \"" . $id . "\", \"mod_affiliazioni\",\"_mod_affiliazioni_discipline\",\"getMasterDetail__mod_affiliazioni_discipline\",\"dv__mod_affiliazioni_discipline\")' title='Elimina'><i class='fa fa-trash'></a></td>";
			$html .= "</tr>";
		}
		$html .= '</tbody></table>';
	}



	public function sendMailWithAttach(){
		$module= $_REQUEST['module'];
		$email = $_REQUEST['email'];
		$id = $_REQUEST['id'];
		$subject = $_REQUEST['subject'];

		$this->stampa($id);

		$settings = $this->user_model->loadSettings();
		$company_logo = $settings[0]->company_logo;
		$company_name = $settings[0]->company_name;
		$company_email = $settings[0]->company_email;
		$company_email_send_comunication1 = $settings[0]->company_email_send_comunication1;
		$email_explode  = explode("@", $company_email);
		//print'<pre>';print_r($settings);

		ini_set('max_execution_time', 3000); //300 seconds = 5 minute	

		$DESTINATARI = array();
		$DESTINATARI[] = $email;

		$bodytext = "Buongiorno.";
		$bodytext.= "\nin allegato i documenti da lei richiesti.
					\nQuesta e' una mail automatica, si prega di non rispondere. 
					\n\nSaluti,\n$company_name";
		
		$email = new PHPMailer();
		$email->IsSMTP();
        $email->SMTPOptions = array(
			'ssl' => array(
			'verify_peer' => false,
			'verify_peer_name' => false,
			'allow_self_signed' => true
		   )
		);
        $email->SMTPAuth   = true;
        $email->Host       = $this->config->item('smtp_host');
        $email->Port       = $this->config->item('smtp_port');
        $email->SMTPSecure = $this->config->item('smtp_security_protocol');
        $email->Username   = $this->config->item('smtp_user');
        $email->Password   = $this->config->item('smtp_pass');
		
		$email->From      = $this->config->item('smtp_sender');
		$email->FromName  = $company_name;

		//$email->setFrom('no-reply@'.$email_explode[1], $company_name); 
		//$email->addReplyTo('no-reply@'.$email_explode[1], 'Information'); 
		$ccMail = $company_email;
		if($company_email_send_comunication1 != ""){
			$ccMail = $company_email_send_comunication1;
		}
		$email->addCC($ccMail);
		
		$email->Subject   = $company_name. ' - Invio documentazione';
		$email->Body      = $bodytext;
		foreach($DESTINATARI as $address){
			$email->AddAddress($address);
		}
		 
		$attachement = FCPATH . "/stampe/" . $module . '/' . $id .'.pdf';
		//$attachement = "/var/www/html/bailadance_new/stampe/" . $module . '/' . $id .'.pdf';
		$filename = $id .'.pdf';

		$email->AddAttachment($attachement);
		$email->Send();

	}	


	//
	//
	//[STAMPE]
	//
	//


	/**
	 * Stampa di un modulo
	 * Stampa a video, richiamando il metodo stampa_out e salva sul server
	 * @param mixed $id
	 */
	public function stampa($id)
	{
		$row = $this->modelClassModule->get_by_id($id);

		if ($row) {
			if (!file_exists(FCPATH . "/stampe/" . $this->mod_name)) {
				$oldmask = umask(0);
				mkdir(FCPATH . "/stampe/" . $this->mod_name, 0777);
				umask($oldmask);
			}
			$out = $this->stampa_out($id);
			file_put_contents(FCPATH . "/stampe/" . $this->mod_name . '/' . $this->mod_title . '_' . date('Y-m-d_H_i') . '.pdf', $out);
		} else {
			$this->session->set_flashdata('error', 'Record non Trovato');
			redirect(site_url($this->mod_name));
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
			foreach ($this->formFields as $key => $property) {
				$data[$property] = set_value($property, $row->$property);
			}

			$view = $this->load->view($this->mod_name . '/' . $this->mod_name . '_form_pdf', $data, true);
			$dompdf->loadHtml($view);
			$dompdf->set_option("isPhpEnabled", true);

			$dompdf->setPaper('A4');

			$dompdf->render();

			$x = 520;
			$y = 820;
			$text = "Pagina {PAGE_NUM} di {PAGE_COUNT}";
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



	public function excel($table, $id = NULL)
	{
		$this->load->helper('exportexcel');
		$nome_file = $table . ".csv";
		$nr_righe = 1;


		header("Pragma: public");
		header("Expires: 0");
		header("Cache-Control: must-revalidate, post-check=0,pre-check=0");
		header("Content-Type: application/force-download");
		header('Content-Type: text/csv; charset=utf-8');
		header("Content-Type: application/download");
		header("Content-Disposition: attachment;filename=" . $nome_file . "");
		$output = fopen('php://output', 'w');

		$row = $this->modelClassModule->getColumnsTable($table);


		foreach ($row as $key => $v) {
			fwrite($output, $v['COLUMN_NAME'] . ";");
		}
		fwrite($output, "\n");


		foreach ($this->modelClassModule->get_all_by_id($table, $id) as $data) {
			foreach ($row as $key => $v) {
				fwrite($output, $data->{$v['COLUMN_NAME']} . ";");
			}
			fwrite($output, "\n");

			$nr_righe++;
		}

		exit();
	}



 
}
