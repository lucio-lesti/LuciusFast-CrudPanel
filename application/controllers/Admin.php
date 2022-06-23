<?php if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

require APPPATH . '/libraries/BaseController.php';
require APPPATH . 'third_party/PHPExcel.php';
require APPPATH . 'third_party/spreadsheet-reader/php-excel-reader/excel_reader2.php';
require APPPATH . 'third_party/spreadsheet-reader/SpreadsheetReader.php';

/**
 * Class : Admin (AdminController)
 * Admin class to control to authenticate admin credentials and include admin functions.
 * @author : Samet Aydın / sametay153@gmail.com
 * @version : 1.0
 * @since : 27.02.2018
 */
class Admin extends BaseController
{

    /**
     * This is default constructor of the class
     */
    public function __construct()
    {
        parent::__construct();
        $this->load->model('login_model');
    }


    /**
     * This function is used to load the user list
     */
    public function userListing()
    {
        $searchText = $this->security->xss_clean($this->input->post('searchText'));
        $data['searchText'] = $searchText;

        $this->load->library('pagination');

        $count = $this->user_model->userListingCount($searchText);

        $returns = $this->paginationCompress("userListing/", $count, 10);

        $data['userRecords'] = $this->user_model->userListing($searchText, $returns["page"], $returns["segment"]);

        $process = 'Lista Utenti';
        $processFunction = 'Admin/userListing';
        $this->logrecord($process, $processFunction);

        $this->global['pageTitle'] = 'LM-Panel : Lista Utenti';

        $this->loadViews("users", $this->global, $data, null);
    }


    /**
     * This function is used to load the add new form
     */
    public function addNew()
    {
        $data['roles'] = $this->user_model->getUserRoles();

        $this->global['pageTitle'] = 'LM-Panel : Aggiungi Utente';

        $this->loadViews("addNew", $this->global, $data, null);
    }


    /**
     * This function is used to add new user to the system
     */
    public function addNewUser()
    {
        $this->load->library('form_validation');

        $this->form_validation->set_rules('fname', 'Full Name', 'trim|required|max_length[128]');
        $this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email|max_length[128]');
        $this->form_validation->set_rules('password', 'Password', 'required|max_length[20]');
        $this->form_validation->set_rules('cpassword', 'Confirm Password', 'trim|required|matches[password]|max_length[20]');
        $this->form_validation->set_rules('role', 'Role', 'trim|required|numeric');
        $this->form_validation->set_rules('mobile', 'Mobile Number', 'required|min_length[10]');

        if ($this->form_validation->run() == false) {
            $this->addNew();
        } else {
            $name = ucwords(strtolower($this->security->xss_clean($this->input->post('fname'))));
            $email = $this->security->xss_clean($this->input->post('email'));
            $password = $this->input->post('password');
            $roleId = $this->input->post('role');
            $mobile = $this->security->xss_clean($this->input->post('mobile'));
            if (isset($_POST['ip_filter_list'])) {
                $ip_filter_list = implode(",", $this->input->post('ip_filter_list'));
            }
            $userInfo = array('email' => $email, 'password' => getHashedPassword($password), 'roleid' => $roleId, 'name' => $name,
                'mobile' => $mobile, 'createdby' => $this->vendorId,
                'createddtm' => date('Y-m-d H:i:s'), 'ip_filter_list' => $ip_filter_list);

            $result = $this->user_model->addNewUser($userInfo);

            if ($result > 0) {
                $process = 'Aggiungi Utente';
                $processFunction = 'Admin/addNewUser';
                $this->logrecord($process, $processFunction);

                $this->session->set_flashdata('success', 'Utente creato correttamente');
            } else {
                $this->session->set_flashdata('error', 'Creazione utente non riuscita');
            }

            redirect('userListing');
        }
    }


    /**
     * This function is used load user edit information
     * @param number $userId : Optional : This is user id
     */
    public function editOld($userId = null)
    {
        if ($userId == null) {
            redirect('userListing');
        }

        $data['roles'] = $this->user_model->getUserRoles();
        $data['userInfo'] = $this->user_model->getUserInfo($userId);

        $this->global['pageTitle'] = 'LM-Panel : Modifica utente';

        $this->loadViews("editOld", $this->global, $data, null);
    }


    /**
     * This function is used to edit the user informations
     */
    public function editUser()
    {
        $this->load->library('form_validation');

        $userId = $this->input->post('userId');

        $this->form_validation->set_rules('fname', 'Full Name', 'trim|required|max_length[128]');
        $this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email|max_length[128]');
        $this->form_validation->set_rules('password', 'Password', 'matches[cpassword]|max_length[20]|required');
        $this->form_validation->set_rules('cpassword', 'Confirm Password', 'matches[password]|max_length[20]|required');
        $this->form_validation->set_rules('role', 'Role', 'trim|required|numeric');
        $this->form_validation->set_rules('mobile', 'Mobile Number', 'required|min_length[10]');

        if ($this->form_validation->run() == false) {
            $this->editOld($userId);
        } else {

            $name = ucwords(strtolower($this->security->xss_clean($this->input->post('fname'))));
            $email = $this->security->xss_clean($this->input->post('email'));
            $password = $this->input->post('password');
            $roleId = $this->input->post('role');
            $mobile = $this->security->xss_clean($this->input->post('mobile'));
            if (isset($_POST['ip_filter_list'])) {
                $ip_filter_list = implode(",", $this->input->post('ip_filter_list'));
            }
            $userInfo = array();

            if (empty($password)) {
                $userInfo = array('email' => $email, 'roleid' => $roleId, 'name' => $name,
                    'mobile' => $mobile, 'status' => 0, 'updatedby' => $this->vendorId,
                    'updateddtm' => date('Y-m-d H:i:s'), 'ip_filter_list' => $ip_filter_list);
            } else {
                $userInfo = array('email' => $email, 'password' => getHashedPassword($password), 'roleid' => $roleId,
                    'name' => ucwords($name), 'mobile' => $mobile, 'status' => 0, 'updatedby' => $this->vendorId,
                    'updateddtm' => date('Y-m-d H:i:s'), 'ip_filter_list' => $ip_filter_list);
            }

            $result = $this->user_model->editUser($userInfo, $userId);

            if ($result == true) {
                $process = 'Aggiornamento utente';
                $processFunction = 'Admin/editUser';
                $this->logrecord($process, $processFunction);

                $this->session->set_flashdata('success', 'Utente aggiornato correttamente');
            } else {
                $this->session->set_flashdata('error', 'Aggiornamento utente non riuscito');
            }

            redirect('userListing');
        }
    }


    /**
     * This function is used to delete the user using userId
     * @return boolean $result : TRUE / FALSE
     */
    public function deleteUser()
    {
        $userId = $this->input->post('userId');
        $userInfo = array('isDeleted' => 1, 'updatedby' => $this->vendorId, 'updateddtm' => date('Y-m-d H:i:s'));

        $result = $this->user_model->deleteUser($userId, $userInfo);

        if ($result > 0) {
            echo (json_encode(array('status' => true)));

            $process = 'Cancellazione Utente';
            $processFunction = 'Admin/deleteUser';
            $this->logrecord($process, $processFunction);

        } else {echo (json_encode(array('status' => false)));}
    }

 
	/**
    * 
    */    
    public function deleteUserFromDb($userId)
    {

        $result = $this->user_model->deleteUserFromDb($userId);

        if ($result == true) {
            $process = 'Cancellazione utente';
            $processFunction = 'Admin/deleteUserFromDb';
            $this->logrecord($process, $processFunction);

            $this->session->set_flashdata('success', 'Utente Cancellato correttamente');
        } else {
            $this->session->set_flashdata('error', 'Cancellazione utente non riuscita');
        }

        redirect('userListing');

    }


    /**
     * This function used to show log history
     * @param number $userId : This is user id
     */
    public function logHistory($userId = null)
    {
        $data['dbinfo'] = $this->user_model->gettablemb('core_log', 'cias');
        if (isset($data['dbinfo']->total_size)) {
            if (($data['dbinfo']->total_size) > 1000) {
                $this->backupLogTable();
            }
        }
        $data['userRecords'] = $this->user_model->logHistory($userId);

        $process = 'Visualizzazione Log';
        $processFunction = 'Admin/logHistory';
        $this->logrecord($process, $processFunction);

        $this->global['pageTitle'] = 'LM-Panel : Cronologia accessi utente';

        $this->loadViews("logHistory", $this->global, $data, null);
    }


    /**
     * This function used to show specific user log history
     * @param number $userId : This is user id
     */
    public function logHistorysingle($userId = null)
    {
        $userId = ($userId == null ? $this->session->userdata("userId") : $userId);
        $data["userInfo"] = $this->user_model->getUserInfoById($userId);
        $data['userRecords'] = $this->user_model->logHistory($userId);

        $process = 'Visualizza singolo registro';
        $processFunction = 'Admin/logHistorysingle';
        $this->logrecord($process, $processFunction);

        $this->global['pageTitle'] = 'LM-Panel : Cronologia accessi utente';

        $this->loadViews("logHistorysingle", $this->global, $data, null);
    }


    /**
     * This function used to backup and delete log table
     */
    public function backupLogTable()
    {
        $this->load->dbutil();
        $prefs = array(
            'tables' => array('core_log'),
        );
        $backup = $this->dbutil->backup($prefs);

        date_default_timezone_set('Europe/Rome');
        $date = date('d-m-Y H-i');

        $filename = './backup/' . $date . '.sql.gz';
        $this->load->helper('file');
        write_file($filename, $backup);

        $this->user_model->clearlogtbl();

        if ($backup) {
            $this->session->set_flashdata('success', 'Cancellazione Log riuscita');
            redirect('log-history');
        } else {
            $this->session->set_flashdata('error', 'Cancellazione Log NON riuscita');
            redirect('log-history');
        }
    }


    /**
     * This function used to open the logHistoryBackup page
     */
    public function logHistoryBackup()
    {
        $data['dbinfo'] = $this->user_model->gettablemb('core_log_backup', 'cias');
        if (isset($data['dbinfo']->total_size)) {
            if (($data['dbinfo']->total_size) > 1000) {
                $this->backupLogTable();
            }
        }
        $data['userRecords'] = $this->user_model->logHistoryBackup();

        $process = 'Visualizzazione registro di backup';
        $processFunction = 'Admin/logHistoryBackup';
        $this->logrecord($process, $processFunction);

        $this->global['pageTitle'] = 'LM-Panel : Cronologia login backup utente';

        $this->loadViews("logHistoryBackup", $this->global, $data, null);
    }


    /**
     * This function used to delete backup_log table
     */
    public function backupLogTableDelete()
    {
        $backup = $this->user_model->clearlogBackuptbl();

        if ($backup) {
            $this->session->set_flashdata('success', 'Pulizia della tabella riuscita');
            redirect('log-history-backup');
        } else {
            $this->session->set_flashdata('error', 'Pulizia della tabella NON riuscita');
            redirect('log-history-backup');
        }
    }


    /**
     * This function used to open the logHistoryUpload page
     */
    public function logHistoryUpload()
    {
        $this->load->helper('directory');
        $map = directory_map('./backup/', false, true);

        $data['backups'] = $map;

        $process = 'Caricamento backup utenti';
        $processFunction = 'Admin/logHistoryUpload';
        $this->logrecord($process, $processFunction);

        $this->global['pageTitle'] = 'LM-Panel : Caricamento registro utenti';

        $this->loadViews("logHistoryUpload", $this->global, $data, null);
    }


    /**
     * This function used to upload backup for backup_log table
     */
    public function logHistoryUploadFile()
    {
        $optioninput = $this->input->post('optionfilebackup');

        if ($optioninput == '0' && $_FILES['filebackup']['name'] != '') {
            $config = array(
                'upload_path' => "./uploads/",
                'allowed_types' => "gz|sql|gzip",
                'overwrite' => true,
                'max_size' => "20048000", // Can be set to particular file size , here it is 20 MB(20048 Kb)
            );

            $this->load->library('upload', $config);
            $upload = $this->upload->do_upload('filebackup');
            $data = $this->upload->data();
            $filepath = $data['full_path'];
            $path_parts = pathinfo($filepath);
            $filetype = $path_parts['extension'];
            if ($filetype == 'gz') {
                // Read entire gz file
                $lines = gzfile($filepath);
                $lines = str_replace('core_log', 'core_log_backup', $lines);
            } else {
                // Read in entire file
                $lines = file($filepath);
                $lines = str_replace('core_log', 'core_log_backup', $lines);
            }
        } else if ($optioninput != '0' && $_FILES['filebackup']['name'] == '') {
            $filepath = './backup/' . $optioninput;
            $path_parts = pathinfo($filepath);
            $filetype = $path_parts['extension'];
            if ($filetype == 'gz') {
                // Read entire gz file
                $lines = gzfile($filepath);
                $lines = str_replace('core_log', 'core_log_backup', $lines);
            } else {
                // Read in entire file
                $lines = file($filepath);
                $lines = str_replace('core_log', 'core_log_backup', $lines);
            }
        }
        // Set line to collect lines that wrap
        $templine = '';

        // Loop through each line
        foreach ($lines as $line) {
            // Skip it if it's a comment
            if (substr($line, 0, 2) == '--' || $line == '') {
                continue;
            }

            // Add this line to the current templine we are creating
            $templine .= $line;

            // If it has a semicolon at the end, it's the end of the query so can process this templine
            if (substr(trim($line), -1, 1) == ';') {
                // Perform the query
                $this->db->query($templine);

                // Reset temp variable to empty
                $templine = '';
            }
        }
        if (empty($lines) || !isset($lines)) {
            $this->session->set_flashdata('error', 'Caricamento del backup NON riuscita');
            redirect('log-history-upload');
        } else {
            $this->session->set_flashdata('success', 'Caricamento del backup riuscita');
            redirect('log-history-upload');
        }
    }


	/**
    * 
    */    
    public function importMod()
    {
        $data = array();
        $process = 'Carica Modulo';
        $processFunction = 'Admin/importMod';
        $this->logrecord($process, $processFunction);

        $this->global['pageTitle'] = 'LM-Panel : Carica Modulo';

        $this->loadViews("import_mod", $this->global, $data, null);
    }


	/**
    * 
    */    
    public function installMod()
    {
        $allowedExt = array('zip');
        if ($_FILES['file_mod']['name'] != '') {
            $config = array(
                'upload_path' => "./uploads/modules",
                'allowed_types' => implode("|", $allowedExt),
                'overwrite' => true,
                'max_size' => "20048000",
            );

            $this->load->library('upload', $config);
            $upload = $this->upload->do_upload('file_mod');
            $data = $this->upload->data();

            $filepath = $data['full_path'];
            $path_parts = pathinfo($filepath);
            $filetype = $path_parts['extension'];

            if (!in_array($filetype, $allowedExt)) {
                $this->session->set_flashdata('error', 'Estensione non Valida!');
                $this->importMod();
            } else {
                $zip = new ZipArchive;
                $res = $zip->open('./uploads/modules/' . $data['file_name']);
                if ($res === true) {
                    $zip->extractTo('./application/modules/');
                    $zip->close();
                    $dirMod = basename($data['file_name'], '.zip');
                    $resInstall = $this->installModSQL('./application/modules/' . $dirMod . "/install.txt");

                    if ($resInstall === true) {
                        $this->session->set_flashdata('success', 'Caricamento del Modulo riuscita');
                    } else {
                        $this->session->set_flashdata('error', 'Caricamento del Modulo NON riuscita. Errore nel SQL di installazione');
                    }
                    unlink('./uploads/modules/' . $data['file_name']);
                    unlink('./application/modules/' . $dirMod . "/install.txt");
                } else {
                    $this->session->set_flashdata('error', 'Caricamento del Modulo NON riuscita');
                }

                $this->importMod();
            }

        } else {
            $this->session->set_flashdata('error', 'Non hai caricato nessun file');
            $this->importMod();
        }
    }


	/**
    * 
    */    
    private function installModSQL($installSqlFile)
    {
        $resInstall = true;
        $SqlFile = file_get_contents($installSqlFile);

        $arraySQL = explode(";", $SqlFile);

        foreach ($arraySQL as $key => $sql) {
            if (trim($sql) != "") {
                $this->user_model->execSQL($sql);
            }
        }

        return $resInstall;
    }


	/**
    * 
    */    
    public function manage_mod()
    {
        $data = array();
        $process = 'Gestisci Modulo';
        $processFunction = 'Admin/manage_mod';
        $this->logrecord($process, $processFunction);
        $allModInstalled = $this->user_model->getAllModInstalled();
        $data['allModInstalled'] = $allModInstalled;
        $this->global['pageTitle'] = 'LM-Panel : Gestisci Moduli';

        $this->loadViews("manage_mod", $this->global, $data, null);
    }


	/**
    * 
    */    
    public function delete_mod($mod_id)
    {
        $process = 'Cancella Modulo';
        $processFunction = 'Admin/delete_mod';
        $this->logrecord($process, $processFunction);

        //CHECK SU ID
        if (($mod_id == null) || ($mod_id == "")) {
            $this->session->set_flashdata('error', 'Record non trovato');
            redirect(site_url('admin/manage_mod'));
        }

        $moduleInfo = $this->user_model->getModById($mod_id);

        //CANCELLAZIONE DALLA BASE DATI
        $this->user_model->deleteMod($mod_id);
        $this->user_model->deleteModPermission($mod_id);

        //CANCELLAZIONE FILE FISICI
        $mod_name = $moduleInfo->mod_name;
        $this->utilities->delete_dir(APPPATH . "modules/" . $mod_name . "/");

        $this->session->set_flashdata('success', 'Modulo Cancellato con successo');
        redirect(site_url('admin/manage_mod'));

    }


	/**
    * 
    */    
    public function active_disactive_mod($mod_id, $state)
    {
        $data = array();
        $process = 'Attiva/Disattiva Modulo';
        $processFunction = 'Admin/active_disactive_mod';
        $this->logrecord($process, $processFunction);

        //CHECK SU ID
        if (($mod_id == null) || ($mod_id == "")) {
            $this->session->set_flashdata('error', 'Record non trovato');
            redirect(site_url('admin/manage_mod'));
        }

        if ($state == 'active') {
            $moduleInfo = array('active' => 'Y');
            $msg = "Modulo abilitato";
        } else {
            $moduleInfo = array('active' => 'N');
            $msg = "Modulo disabilitato";
        }

        $this->user_model->activeDisactiveMod($moduleInfo, $mod_id);

        $this->session->set_flashdata('success', $msg);
        redirect(site_url('admin/manage_mod'));

    }


	/**
    * 
    */   
    public function change_position_mod()
    {
        $data = array();
        $process = 'Cambia Posizione Modulo';
        $processFunction = 'Admin/change_position_mod';
        $this->logrecord($process, $processFunction);
        $arrayModId = explode(",", $this->input->post('mod_list'));

        //I PRIMI ID CHE TROVO GLI METTO LA POSIZIONE PIU ALTA
        $pos = 0;
        foreach ($arrayModId as $key => $mod_id) {
            $moduleInfo = array("position" => $pos);
            $this->user_model->changePositionMod($moduleInfo, $mod_id);
            $pos++;
        }

    }


	/**
    * 
    */    
    public function importExcelData()
    {
        $data = array();
        $process = 'Importa Dati';
        $processFunction = 'Admin/importExcelData';
        $this->logrecord($process, $processFunction);

        $this->global['pageTitle'] = 'LM-Panel : Importa Dati';

        $this->loadViews("import_data", $this->global, $data, null);
    }


	/**
    * 
    */    
    public function importExcelDataExec()
    {
        //SPENGO GLI ERRORI PERCHE'
        //LA LIBRERIA PHPEXCEL DA WARNING

        error_reporting(0);
        ini_set('display_errors', 0);

        $allowedExt = array('xls', 'xlsx', 'csv');

        if ($_FILES['file_mod']['name'] != '') {
            $config = array(
                'upload_path' => "./uploads/import_data",
                'allowed_types' => implode("|", $allowedExt),
                'overwrite' => true,
                'max_size' => "20048000",
            );

            $this->load->library('upload', $config);
            $upload = $this->upload->do_upload('file_mod');
            $data = $this->upload->data();

            $filepath = $data['full_path'];
            $path_parts = pathinfo($filepath);
            $filetype = $path_parts['extension'];

            if (!in_array($filetype, $allowedExt)) {
                $this->session->set_flashdata('error', 'Estensione non Valida!');
                redirect('admin/importExcelData', 'refresh');
            } else {
                //VERIFICO IL NOME DEL FILE CHE DEVE CORRISPONDERE AL NOME DI UNA TABELLA
                $tableListObj = $this->user_model->getTableList();
                $tableList = array();
                foreach ($tableListObj as $key => $value) {
                    $tableList[] = strtolower($value->TABLE_NAME);
                }
                $filename = strtolower($data['raw_name']);
                if (!in_array($filename, $tableList)) {
                    $this->session->set_flashdata('error', 'Il nome del file deve corrispondere al nome della tabella.');
                    redirect('admin/importExcelData', 'refresh');
                    return;
                }

                //MI CARICO IN UN VETTORE LE COLONNE CON I TIPI DATI DELLA TABELLA DOVE VOGLIO IMPORTARE I DATI
                $columnsListObj = $this->user_model->getTableColumns($filename);
                $columnsList = array();
                foreach ($columnsListObj as $key => $value) {
                    $columnsList[] = strtolower($value->COLUMN_NAME);
                }

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
                        }
                    }
                }

                //VERIFICO SE LE COLONNE DEL FILE CORRISPONDONO COME NOME ALLE COLONNE DELLA TABELLA
                foreach ($header as $key => $headerValue) {
                    if (!in_array($headerValue, $columnsList)) {
                        $this->session->set_flashdata('error', 'Le colonne del file devono corrispondere alle colonne della tabella da importare.');
                        redirect('admin/importExcelData', 'refresh');
                        return;
                    }
                }


                //RE-INDICIZZO L'ARRAY DA ZERO IN UN NUOVO ARRAY
                $excelData = array_values($arr_data);
                //print'<pre>';print_r($excelData);die();

                //COMINCIO L'INSERIMENTO, MA PRIMA SVUOTO LA TABELLA
                $ret = $this->user_model->importExcelData($filename, $excelData);

                if ($ret == true) {
                    $this->session->set_flashdata('success', 'Importazione dati riuscita.');
                } else {
                    $this->session->set_flashdata('error', 'Importazione dati NON riuscita.');
                }

                redirect('admin/importExcelData', 'refresh');
            }

        } else {
            $this->session->set_flashdata('error', 'Non hai caricato nessun file');
            redirect('admin/importExcelData', 'refresh');
        }
    }


}
