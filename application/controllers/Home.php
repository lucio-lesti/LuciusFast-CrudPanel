<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

require APPPATH . '/libraries/BaseController.php';


class Home extends BaseController
{
    /**
     * This is default constructor of the class
     */
    public function __construct()
    {
        parent::__construct();
		
        $this->load->model('home_model');
        //$this->isLoggedIn();
    }
	
	
    /**
     *  @brief Dashboard
     *  
     */
    public function index()
    {
        $this->global['pageTitle'] = 'LM-Panel : Home';

        $data['logsCount'] = $this->user_model->logsCount();
        $data['usersCount'] = $this->user_model->usersCount();

        if ($this->getUserStatus() == TRUE)
        {
            $this->session->set_flashdata('error', 'Per favore cambia prima la tua password per la tua sicurezza.');
            redirect('loadChangePass');
        }

        //$data['notifyList'] = $this->user_model->getNotifyList();
        $data['notifyList'] = $this->home_model->getNotifyList();
        $this->loadViews("dashboard", $this->global, $data , NULL);
    }


    /**
     * This function is used to open 404 view
     */
    public function pageNotFound()
    {
        $this->global['pageTitle'] = 'LM-Panel : 404 - Pagina non trovata';
        
        $this->loadViews("404", $this->global, NULL, NULL);
    }	
	
	
}	