<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

require APPPATH . '/libraries/BaseController.php';


/**
 * Class : Login (LoginController)
 * Admin class to control to authenticate admin credentials and include admin functions.
 * @author : Samet Aydın / sametay153@gmail.com
 * @version : 1.0
 * @since : 27.02.2018
 */
class Login extends BaseController
{
    
    /**
     * This is default constructor of the class
     */
    public function __construct()
    {
        parent::__construct();
		
        $this->load->model('login_model');
		$this->load->model('home_model');
    }


    /**
     * Index Page for this controller.
     */
    public function index()
    {
        $this->isLoggedIn();
    }


    /**
     * This function is used to open error /404 not found page
     */
    public function error()
    {
        $isLoggedIn = $this->session->userdata('isLoggedIn');
        $data['settings'] = $this->user_model->loadSettings();
        
        if(!isset($isLoggedIn) || $isLoggedIn != TRUE)
        {
            $this->load->view('login',$data);
        }
        else
        {
            $process = 'Errore di Autenticazione';
            $processFunction = 'Login/error';
            $this->logrecord($process,$processFunction);
            redirect('pageNotFound');
        }
    }


    /**
     * This function is used to access denied page
     */
    public function noaccess() {
        
        $this->global['pageTitle'] = 'LM-Panel : Accesso negato';
        $this->datas();

        if ($this->getUserStatus() == TRUE)
        {
            $this->session->set_flashdata('error', 'Per favore cambia prima la tua password per la tua sicurezza.');
            redirect('loadChangePass');
        }

        //$data['notifyList'] = $this->user_model->getNotifyList();
        $data['notifyList'] = $this->home_model->getNotifyList();
        $this->loadViews("access", $this->global, $data , NULL);          
        
    }


    /**
     * This function used to check the user is logged in or not
     */
    public function isLoggedIn()
    {
        $isLoggedIn = $this->session->userdata('isLoggedIn');
        $data['settings'] = $this->user_model->loadSettings();
        
        if(!isset($isLoggedIn) || $isLoggedIn != TRUE)
        {
            $this->load->view('login',$data);
        }
        else
        {
            redirect('/dashboard');
        }
    }
    
    
    /**
     * This function used to logged in user
     */
    public function loginMe()
    {
        $this->load->library('form_validation');
        
        $this->form_validation->set_rules('email', 'Email', 'required|valid_email|max_length[128]|trim');
        $this->form_validation->set_rules('password', 'Password', 'required|max_length[32]');
        
        if($this->form_validation->run() == FALSE)
        {
            $this->index();
        }
        else
        {
            $email = $this->security->xss_clean($this->input->post('email'));
            $password = $this->input->post('password');
            
            $result = $this->login_model->loginMe($email, $password);
            
            if(count($result) > 0)
            {
                foreach ($result as $res)
                {
                    $lastLogin = $this->login_model->lastLoginInfo($res->userid);
                    
                    $process = 'Accesso';
                    $processFunction = 'Login/loginMe';

                    $sessionArray = array('userId'=>$res->userid,                    
                                            'role'=>$res->roleid,
                                            'roleText'=>$res->role,
                                            'name'=>$res->name,
                                            'lastLogin'=> $lastLogin->createddtm,
                                            'status'=> $res->status,
                                            'isLoggedIn' => TRUE
                                    );

                    $this->session->set_userdata($sessionArray);

                    unset($sessionArray['userId'], $sessionArray['isLoggedIn'], $sessionArray['lastLogin']);
                    
                    $this->logrecord($process,$processFunction);

                    redirect('/dashboard');
                }
            }
            else
            {
                $this->session->set_flashdata('error', 'L\'indirizzo e-mail o la password non sono corretti');
                
                redirect('/login');
            }
        }
    }


    /**
     * This function used to load forgot password view
     */
    public function forgotPassword()
    {
        $isLoggedIn = $this->session->userdata('isLoggedIn');
        
        if(!isset($isLoggedIn) || $isLoggedIn != TRUE)
        {
            $this->load->view('forgotPassword');
        }
        else
        {
            redirect('/dashboard');
        }
    }
    

    /**
     * This function used to generate reset password request link
     */
    public function resetPasswordUser()
    {
        $status = '';
        
        $this->load->library('form_validation');
        
        $this->form_validation->set_rules('login_email','Email','trim|required|valid_email');
                
        if($this->form_validation->run() == FALSE)
        {
            $this->forgotPassword();
        }
        else 
        {
            $email = $this->security->xss_clean($this->input->post('login_email'));
            
            if($this->login_model->checkEmailExist($email))
            {
                $encoded_email = urlencode($email);
                
                $this->load->helper('string');
                $data['email'] = $email;
                $data['activation_id'] = random_string('alnum',15);
                $data['createdDtm'] = date('Y-m-d H:i:s');
                $data['agent'] = getBrowserAgent();
                $data['client_ip'] = $this->input->ip_address();
                
                $save = $this->login_model->resetPasswordUser($data);                
                
                if($save)
                {
                    $data1['reset_link'] = base_url() . "resetPasswordConfirmUser/" . $data['activation_id'] . "/" . $encoded_email;
                    $userInfo = $this->login_model->getCustomerInfoByEmail($email);

                    if(!empty($userInfo)){
                        $data1["name"] = $userInfo[0]->name;
                        $data1["email"] = $userInfo[0]->email;
                        $data1["message"] = "Reimposta password";
                    }

                    $sendStatus = resetPasswordEmail($data1);

                    $process = 'Richiesta di reimpostazione password';
                    $processFunction = 'Login/resetPasswordUser';
                    $this->logrecord($process,$processFunction);

                    if($sendStatus){
                        $status = "send";
                        setFlashData($status, "Il link per reimpostare la password è stato inviato correttamente, controlla la tua posta.");
                    } else {
                        $status = "notsend";
                        setFlashData($status, "Invio e-mail non riuscito, riprovare.");
                    }
                }
                else
                {
                    $status = 'unable';
                    setFlashData($status, "Si è verificato un errore durante l'invio delle informazioni, riprovare.");
                }
            }
            else
            {
                $status = 'invalid';
                setFlashData($status, "Il tuo indirizzo email non è registrato nel sistema.");
            }
            redirect('/forgotPassword');
        }
    }


    /**
     * This function used to reset the password 
     * @param string $activation_id : This is unique id
     * @param string $email : This is user email
     */
    public function resetPasswordConfirmUser($activation_id, $email)
    {
        // Get email and activation code from URL values at index 3-4
        $email = urldecode($email);
        
        // Check activation id in database
        $is_correct = $this->login_model->checkActivationDetails($email, $activation_id);
        
        $data['email'] = $email;
        $data['activation_code'] = $activation_id;
        
        if ($is_correct == 1)
        {
            $this->load->view('newPassword', $data);
        }
        else
        {
            redirect('/login');
        }
    }
    
    
    /**
     * This function used to create new password for user
     */
    public function createPasswordUser()
    {
        $status = '';
        $message = '';
        $email = $this->input->post("email");
        $activation_id = $this->input->post("activation_code");
        
        $this->load->library('form_validation');
        
        $this->form_validation->set_rules('password','Password','required|max_length[20]');
        $this->form_validation->set_rules('cpassword','Confirm Password','trim|required|matches[password]|max_length[20]');
        
        if($this->form_validation->run() == FALSE)
        {
            $this->resetPasswordConfirmUser($activation_id, urlencode($email));
        }
        else
        {
            $password = $this->input->post('password');
            $cpassword = $this->input->post('cpassword');
            
            // Check activation id in database
            $is_correct = $this->login_model->checkActivationDetails($email, $activation_id);
            
            if($is_correct == 1)
            {               
                $this->login_model->createPasswordUser($email, $password);
                
                $process = 'Reimpostazione password';
                $processFunction = 'Login/createPasswordUser';
                $this->logrecord($process,$processFunction);

                $status = 'success';
                $message = 'Password modificata correttamente';
            }
            else
            {
                $status = 'error';
                $message = 'Impossibile cambiare la password';
            }
            
            setFlashData($status, $message);

            redirect("/login");
        }
    }
		
	
}

?>