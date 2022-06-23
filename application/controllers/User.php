<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

require APPPATH . '/libraries/BaseController.php';

/**
 * Class : User (UserController)
 * User Class to control all user related operations.
 * @author : Samet Aydın / sametay153@gmail.com
 * @version : 1.0
 * @since : 27.02.2018
 */
class User extends BaseController
{
    /**
     * This is default constructor of the class
     */
    public function __construct()
    {
        parent::__construct();
        //$this->isLoggedIn();
      
    }
	
	
    
    /**
     * This function is used to check whether email already exist or not
     */
    public function checkEmailExists()
    {
        $userId = $this->input->post("userId");
        $email = $this->input->post("email");

        if(empty($userId)){
            $result = $this->user_model->checkEmailExists($email);
        } else {
            $result = $this->user_model->checkEmailExists($email, $userId);
        }

        if(empty($result)){ echo("true"); }
        else { echo("false"); }
    }


    /**
     * This function is used to load edit user view
     */
    public function loadUserEdit()
    {
        $this->global['pageTitle'] = 'LM-Panel : Impostazioni account';
        
        $data['userInfo'] = $this->user_model->getUserInfo($this->vendorId);

        $this->loadViews("userEdit", $this->global, $data, NULL);
    }


    /**
     * This function is used to update the of the user info
     */
    public function updateUser(){
        $this->load->library('form_validation'); 
        $userId = $this->input->post('userId');
        
        $this->form_validation->set_rules('fname','Full Name','trim|required|max_length[128]');
        $this->form_validation->set_rules('email','Email','trim|required|valid_email|max_length[128]');
        $this->form_validation->set_rules('oldpassword','Old password','max_length[20]');
        $this->form_validation->set_rules('cpassword','Password','matches[cpassword2]|max_length[20]');
        $this->form_validation->set_rules('cpassword2','Confirm Password','matches[cpassword]|max_length[20]');
        $this->form_validation->set_rules('mobile','Mobile Number','required|min_length[10]');
        
        if($this->form_validation->run() == FALSE)
        {
            $this->loadUserEdit();
        }
        else
        {
            $name = $this->security->xss_clean($this->input->post('fname'));
            $email = $this->security->xss_clean($this->input->post('email'));
            $password = $this->input->post('cpassword');
            $mobile = $this->security->xss_clean($this->input->post('mobile'));
            $oldPassword = $this->input->post('oldpassword');		
            $userInfo = array();

            if(empty($password))
            {
            $userInfo = array('email'=>$email,'name'=>$name,
                            'mobile'=>$mobile, 'status'=>1, 
							'updatedby'=>$this->vendorId, 
							'updateddtm'=>date('Y-m-d H:i:s'));
            }
            else
            {
                $resultPas = $this->user_model->matchOldPassword($this->vendorId, $oldPassword);
            
                if(empty($resultPas))
                {
                $this->session->set_flashdata('nomatch', 'La tua vecchia password non è corretta');
                redirect('userEdit');
                }
                else
                {
                $userInfo = array('email'=>$email, 'password'=>getHashedPassword($password),
                    'name'=>ucwords($name), 'mobile'=>$mobile,'status'=>1, 'updatedby'=>$this->vendorId, 
                    'updateddtm'=>date('Y-m-d H:i:s'));
                }
            }
            
            $result = $this->user_model->editUser($userInfo, $userId);
            
            if($result == true)
            {
                $process = 'Aggiornamento impostazioni account';
                $processFunction = 'User/updateUser';
                $this->logrecord($process,$processFunction);

                $this->session->set_flashdata('success', 'Le impostazioni del tuo account sono state aggiornate correttamente');
            }
            else
            {
                $this->session->set_flashdata('error', 'Impossibile aggiornare le impostazioni dell\'account');
            }
            
            redirect('userEdit');
        }
    }


    
    /**
     * This function is used to load the change password view
     */
    public function loadChangePass()
    {
        $this->global['pageTitle'] = 'LM-Panel : Cambia password';
        
        $this->loadViews("changePassword", $this->global, NULL, NULL);
    }
    
    
    /**
     * This function is used to change the password of the user
     */
    public function changePassword()
    {
        $this->load->library('form_validation');
        
        $this->form_validation->set_rules('oldPassword','Old password','required|max_length[20]');
        $this->form_validation->set_rules('newPassword','New password','required|max_length[20]');
        $this->form_validation->set_rules('cNewPassword','Confirm new password','required|matches[newPassword]|max_length[20]');
        
        if($this->form_validation->run() == FALSE)
        {
            $this->loadChangePass();
        }
        else
        {
            $oldPassword = $this->input->post('oldPassword');
            $newPassword = $this->input->post('newPassword');
            
            $resultPas = $this->user_model->matchOldPassword($this->vendorId, $oldPassword);
            
            if(empty($resultPas))
            {
                $this->session->set_flashdata('nomatch', 'La tua vecchia password non è corretta');
                redirect('loadChangePass');
            }
            else
            {
                $usersData = array('password'=>getHashedPassword($newPassword),'status'=>1, 'updatedBy'=>$this->vendorId,
                                'updatedDtm'=>date('Y-m-d H:i:s'));
                
                $result = $this->user_model->changePassword($this->vendorId, $usersData);
                
                if($result > 0) {

                    $process = 'Şifre Değiştirme';
                    $processFunction = 'User/changePassword';
                    $this->logrecord($process,$processFunction);

                     $this->session->set_flashdata('success', 'Cambio password effettuato');
                     }
                else {
                     $this->session->set_flashdata('error', 'Cambio password fallito'); 
                    }
                
                redirect('loadChangePass');
            }
        }
    }


	/**
    * 
    */
	public function settings(){
		if($this->isAdmin() == FALSE){
			$this->checkRoleHasPrivilege($child_class_caller);
		}
					
        $this->global['pageTitle'] = 'LM-Panel : Impostazioni Generali';	
		
		$data['settings'] = $this->user_model->loadSettings();
		$this->loadViews("settings", $this->global, $data , NULL);
	}
	
	
 	/**
    * 
    */   
	public function settings_update_action(){
        $this->load->library('form_validation');
		$this->global['pageTitle'] = 'LM-Panel : Impostazioni Generali';	
        
		$this->form_validation->set_rules('company_name','Nome Azienda','trim|required|max_length[50]');
		$this->form_validation->set_rules('company_code','Codice Fiscale / P.IVA','trim|required|max_length[100]');
        $this->form_validation->set_rules('company_email','Email','max_length[50]|valid_email');
		$this->form_validation->set_rules('company_phone','Telefono','trim|max_length[50]');	
        $this->form_validation->set_rules('company_cellphone','Cellulare','trim|max_length[50]');
		$this->form_validation->set_rules('company_address','Indirizzo','trim|max_length[100]');
        $this->form_validation->set_rules('company_logo','Logo','trim|max_length[255]');
		$this->form_validation->set_rules('logo_splash','Immagine Login','trim|max_length[255]');
        $this->form_validation->set_rules('manager_signature','Logo','trim|max_length[255]');
        $this->form_validation->set_rules('grid_page_number','Numero Righe per pagina','required|numeric|max_length[50]');	
		$this->form_validation->set_rules('system_file_ext_admitted','File Ammessi','required|max_length[255]');		
		$this->form_validation->set_rules('system_session_time_limit','Session Time Limit','trim|required|numeric');	
        $this->form_validation->set_rules('system_imgfile_ext_admitted','File Immagine Ammessi','required|max_length[255]');		
       
        if($this->form_validation->run() == FALSE) {
			$this->session->set_flashdata('error', 'Modifica Impostazioni Generali NON riuscita');			
			$data['settings'] = $this->user_model->loadSettings();
			$this->loadViews("settings", $this->global, $data , NULL);
        } else{
			$process = 'Aggiornamento Impostazioni Generali';
			$processFunction = 'User/settings_update_action';
			$this->logrecord($process,$processFunction);
			$ip_filter_list = "";
			if(isset($_POST['ip_filter_list'])){
				$ip_filter_list =  implode(",",$this->input->post('ip_filter_list'));				
			}
			
			$sidebar_fixed = $this->input->post('sidebar_fixed');
			$skin_color = $this->input->post('skin_color');
			
			$company_logo = $this->input->post('company_logo_hidden');
			$allowedExt = array('jpg','jpeg','png','gif','bmp');
			if ($_FILES['company_logo']['name'] != ''){
				$config = array(
					'upload_path' => "./uploads/logo/",
					'allowed_types' => $allowedExt,
					'overwrite' => TRUE,
					'max_size' => "20048000", // Can be set to particular file size , here it is 20 MB(20048 Kb)
				);
				
				$this->load->library('upload', $config);
				$upload= $this->upload->do_upload('company_logo');
				$data = $this->upload->data();
				$filepath = $data['full_path'];
				$path_parts = pathinfo($filepath);
				$filetype = $path_parts['extension'];
				$company_logo = $data['file_name'];
				if(!in_array($filetype,$allowedExt)){
					$this->session->set_flashdata('error', 'Estensione non Valida!');
					$this->settings();
					return;
				}					
			}


			$logo_splash = $this->input->post('logo_splash_hidden');
			$allowedExt = array('jpg','jpeg','png','gif','bmp');
			if ($_FILES['logo_splash']['name'] != ''){
				$config = array(
					'upload_path' => "./uploads/logo/",
					'allowed_types' => $allowedExt,
					'overwrite' => TRUE,
					'max_size' => "20048000", // Can be set to particular file size , here it is 20 MB(20048 Kb)
				);
				
				$this->load->library('upload', $config);
				$upload= $this->upload->do_upload('logo_splash');
				$data = $this->upload->data();
				$filepath = $data['full_path'];
				$path_parts = pathinfo($filepath);
				$filetype = $path_parts['extension'];
				$logo_splash = $data['file_name'];
				if(!in_array($filetype,$allowedExt)){
					$this->session->set_flashdata('error', 'Estensione non Valida!');
					$this->settings();
					return;
				}					
			}


			$manager_signature = $this->input->post('manager_signature_hidden');
			$allowedExt = array('jpg','jpeg','png','gif','bmp');
			if ($_FILES['manager_signature']['name'] != ''){
				$config = array(
					'upload_path' => "./uploads/logo/",
					'allowed_types' => $allowedExt,
					'overwrite' => TRUE,
					'max_size' => "20048000", // Can be set to particular file size , here it is 20 MB(20048 Kb)
				);
				
				$this->load->library('upload', $config);
				$upload= $this->upload->do_upload('manager_signature');
				$data = $this->upload->data();
				$filepath = $data['full_path'];
				$path_parts = pathinfo($filepath);
				$filetype = $path_parts['extension'];
				$manager_signature = $data['file_name'];
				if(!in_array($filetype,$allowedExt)){
					$this->session->set_flashdata('error', 'Estensione non Valida!');
					$this->settings();
					return;
				}					
			}
			
            $arraySettings = array(
				'company_name' => $this->input->post('company_name'),
				'company_code' => $this->input->post('company_code'),
				'company_email' => $this->input->post('company_email'),
                'company_email_send_comunication1' => $this->input->post('company_email_send_comunication1'),
                'company_email_send_comunication2' => $this->input->post('company_email_send_comunication2'),
                'company_email_send_comunication3' => $this->input->post('company_email_send_comunication3'),                
				'company_phone' => $this->input->post('company_phone'),
				'company_address' => $this->input->post('company_address'),
				'company_cellphone' => $this->input->post('company_cellphone'),
                'admin_mail' => $this->input->post('admin_mail'),
				'company_description' => trim($this->input->post('company_description')),
				'company_logo' => $company_logo,
				'logo_splash' => $logo_splash,
				'manager_signature' => $manager_signature,
				'grid_page_number' => $this->input->post('grid_page_number'),
				'system_session_time_limit' => $this->input->post('system_session_time_limit'),
				'system_file_ext_admitted' => $this->input->post('system_file_ext_admitted'),
                'system_imgfile_ext_admitted' => $this->input->post('system_imgfile_ext_admitted'),
				'ip_filter_list' => $ip_filter_list,
				'sidebar_fixed' => $sidebar_fixed,
				'skin_color' => $skin_color,
			);		

			if(isset($_REQUEST['arraySettings'])){
				$arraySettings['change_code_invoice_every_year'] = $this->input->post('change_code_invoice_every_year');				
			}	
			$result = $this->user_model->editSettings($arraySettings);
			
			$this->session->set_flashdata('success', 'Modifica Impostazioni Generali riuscita');				
			
			$data['settings'] = $this->user_model->loadSettings();
            
            $this->loadViews("settings", $this->global, $data , NULL);
        }
		
	}	
	
	
	
    /**
     * This function is used to load the roles list
    */
	public function rolesList(){
		if($this->isAdmin() == FALSE){
			$this->checkRoleHasPrivilege($child_class_caller);
		}
					
        $searchText = $this->security->xss_clean($this->input->post('searchText'));
        $data['searchText'] = $searchText;
        
        $this->load->library('pagination');
        
        $count = $this->user_model->rolesListingCount($searchText);

		$returns = $this->paginationCompress ("admin/rolesListing/", $count, 1 );
        
        $data['roleRecords'] = $this->user_model->rolesListing($searchText, $returns["page"], $returns["segment"]);
        
        $process = 'Lista Ruoli';
        $processFunction = 'Admin/rolesListing';
        $this->logrecord($process,$processFunction);

        $this->global['pageTitle'] = 'LM-Panel : Lista Ruoli';
        
        $this->loadViews("rolesList", $this->global, $data, NULL);
	}
	
	
	/**
	 *  Read role
	 */
    public function readRole($roleId) 
    {
        $process = 'Visualizza Ruolo';
        $processFunction = 'Admin/readRole';
        $this->logrecord($process,$processFunction);

        $this->global['pageTitle'] = 'LM-Panel : Visualizza Ruolo';

		$row = $this->user_model->getRoleInfo($roleId);
				
        if ($row) {
            $data = array(
				'roleid' => $row->roleid,
				'role' => $row->role,
				'permission' => $this->user_model->getPermissionRole($roleId)
	    );
        $this->loadViews('roleRead', $this->global, $data, NULL);
        } else {
            $this->session->set_flashdata('error', 'Record non trovato');
            redirect(site_url('test'));
        }
    }
	
	
    /**
     * This function is used to load the add new role
     */
    public function addRole() {
		$this->global['pageTitle'] = 'LM-Panel : Aggiungi Ruolo';
		
		$data = array(
                'button_id' => 'bt_create',
				'action' => site_url('user/createRole'),
				'action_type' => 'create',
				'action_label' => 'Aggiungi',
				'frm_module_name' => 'frm_role'
		);
		$arrayInitEmptyField = array("roleid" => NULL, "role" => NULL);
		$data['roles'] = (object) $arrayInitEmptyField;
        $this->loadViews("roleAddEdit", $this->global, $data, NULL);
    }
	
	
    /**
     * This function is used to add new roles to the system
     */
    public function createRole() {
        $this->load->library('form_validation');
        
        $this->form_validation->set_rules('role','Ruolo','trim|required|max_length[50]');
        
        if($this->form_validation->run() == FALSE){
            $this->addRole();
        } else {
			$process = 'Creazione Nuovo Ruolo';
			$processFunction = 'user/createRole';
			$this->logrecord($process,$processFunction);
			
            $data = array(
				'role' => $this->input->post('role',TRUE)
			);

			$this->user_model->createRole($data);
			$this->session->set_flashdata('success', 'Creazione Ruolo riuscito');
			redirect(site_url('user/rolesList'));
        }
    }	
	
	
    /**
     * This function is used to load the add new role
     */
    public function editRole($roleId) {
		$data = array(
                'button_id' => 'bt_create',
				'action' => site_url('user/updateRole'),
				'action_type' => 'update',
				'action_label' => 'Modifica',
					'role'=> '',
					'roleid' => '',
				'frm_module_name' => 'frm_role'
		);
        $data['roles'] = $this->user_model->getRoleInfo($roleId);
		$data['permission'] = $this->user_model->getPermissionRole($roleId);
        $this->global['pageTitle'] = 'LM-Panel : Modifica Ruolo';

        $this->loadViews("roleAddEdit", $this->global, $data, NULL);
    }

	
	/**
    * 
    */	
    public function updateRole(){
		$this->load->library('form_validation');
        $this->form_validation->set_rules('role','Ruolo','trim|required|max_length[50]');

        if ($this->form_validation->run() == FALSE) {
            $this->update($this->input->post('roleId', TRUE));
			$this->session->set_flashdata('error', 'Modifica Ruolo NON riuscita');
        } else {
			$process = 'Aggiornamento Ruolo';
			$processFunction = 'user/updateRole';
			$this->logrecord($process,$processFunction);
			
            $data = array(
				'role' => $this->input->post('role',TRUE)
			);

            $this->user_model->updateRole($this->input->post('roleId', TRUE), $data);
			
			$permissionList = $this->input->post('mod_id', TRUE);
			$this->user_model->updatePermission($this->input->post('roleId', TRUE), $permissionList);
            $this->session->set_flashdata('success', 'Modifica Ruolo riuscita');
            redirect(site_url('user/rolesList'));
        }
    }
    	
	
	/**
    * 
    */	
    public function deleteRole($roleId){
		$userCountByRoleId = (int)$this->user_model->getUserCountByRoleId($roleId);
		if($userCountByRoleId  > 0){
            $this->session->set_flashdata('error', 'Impossibile Eliminare. Ci sono utenti con questo ruolo.');
            redirect(site_url('user/rolesList'));			
		}
		
        $row = $this->user_model->getRoleInfo($roleId);

        if ($row) {
			$process = 'Cancellazione Ruolo';
			$processFunction = 'user/deleteRole';
			$this->logrecord($process,$processFunction);
			
            $this->user_model->deleteRole($roleId);
            $this->session->set_flashdata('success', 'Ruolo Cancellato con successo');
            redirect(site_url('user/rolesList'));
        } else {
            $this->session->set_flashdata('error', 'Ruolo non trovato');
            redirect(site_url('user/rolesList'));
        }
    }


	/**
    * 
    */    
    public function deleteMassiveRole(){
        $isRowsDeleteMassive = TRUE;
		$rolesNotDeletable = array();
		
		$entry_list = $this->input->post('entry_list');
		$entryListArray = explode(",", $entry_list);
		
		foreach($entryListArray as $key => $roleId){
			$userCountByRoleId = (int)$this->user_model->getUserCountByRoleId($roleId);	
			if($userCountByRoleId  > 0){
				$isRowsDeleteMassive = FALSE;
				$objRole = $this->user_model->getRoleInfo($roleId);		
				$rolesNotDeletable[] = $objRole->role; 
			}
		}
		
		if($isRowsDeleteMassive  == FALSE){
			$strRolesNotDeletable = implode(",",$rolesNotDeletable);
            $this->session->set_flashdata('error', 'Impossibile Eliminare i seguenti ruoli:<u>'.$strRolesNotDeletable.'</u>. Ci sono utenti collegati.
			<br>Operazione Abortita.');
            redirect(site_url('user/rolesList'));	
		}		
		
		
        if(is_array($entryListArray)){
			$process = 'Cancellazione Massiva Ruoli';
			$processFunction = 'user/deleteMassiveRole';
			$this->logrecord($process,$processFunction);
			
            $this->user_model->deleteMassiveRole($entryListArray);
            $this->session->set_flashdata('success', 'Ruoli Cancellati con successo');
            redirect(site_url('user/rolesList'));
        } else {
            $this->session->set_flashdata('error', 'Ruolo non trovato');
            redirect(site_url('user/rolesList'));
        }
    }
	
	
	
	
}

?>