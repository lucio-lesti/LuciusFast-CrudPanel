<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');
require APPPATH . '/libraries/BaseController.php';

class Mod_logs extends BaseController
{
    
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Mod_logs_model');
		$this->mod_name = "mod_logs";
		$this->mod_title = "Logs";
        $this->modelClassModule =  $this->Mod_logs_model;
        $this->pkIdName = "id";
		$this->viewName_ListAjax = "mod_logs_list_ajax";
		$this->viewName_FormROAjax = "mod_logs_read_ajax";
		$this->viewName_FormAjax = "mod_logs_form_ajax";
        
        $this->setFormFields('programma');
        $this->setFormFields('utente');
        $this->setFormFields('azione');     
        $this->setFormFields('data');       
    }


	/**
	 * 
	 */
    public function index()
    {
        $data = array(
            'total_rows' => $this->modelClassModule->total_rows(),
            'module' => $this->mod_name,
        );

		//PRELEVO PRIVILEGI
		$global_permissions = $this->Mod_logs_model->getPermissionRole($this->session->userdata('role'));
		foreach($global_permissions as $key => $module_permission){
			if($module_permission->mod_name == $this->mod_name){
				$data['perm_read'] = $module_permission->perm_read;
				$data['perm_write'] = $module_permission->perm_write;
				$data['perm_update'] = $module_permission->perm_update;
				$data['perm_delete'] = $module_permission->perm_delete;				
				break;
			}
		}
		
		
        $this->global['pageTitle'] = $this->mod_title.' - Lista';
        $this->loadViews($this->mod_name.'/'.$this->viewName_ListAjax, $this->global, $data, null);
    }    


    public function _rules() 
    {
	    $this->form_validation->set_rules('programma', 'programma / modulo', 'trim|max_length[100]');
	    $this->form_validation->set_rules('utente', '', 'trim|max_length[100]');
	    $this->form_validation->set_rules('azione', '', 'trim|max_length[100]');
	    $this->form_validation->set_rules('data', '', 'trim');
	    $this->form_validation->set_rules('id', 'id', 'trim');
	    $this->form_validation->set_error_delimiters('<span class="text-danger">', '</span>');
    }


}