<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');
require APPPATH . '/libraries/BaseController.php';
require APPPATH . '/third_party/dompdf/autoload.inc.php';
require APPPATH . '/modules/mod_crud_gen/classes/Helper.php';
include APPPATH . '/modules/mod_crud_gen/classes/generators/AbstractGenerator.php';

class Mod_crud_gen extends BaseController
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Mod_crud_gen_model');
        $this->load->library('form_validation');
		$this->load->model('user_model');
		$this->load->model('Common_model');	
		
		$this->database = $this->db->database;
		$this->dbdriver = $this->db->dbdriver;
		$this->helper = new Helper();
    }

    public function index($hasil = array()) {
        $data = array(
			'table_list' => $this->Mod_crud_gen_model->table_list(),
			'hasil' => $hasil
		);
		
		$this->global['pageTitle'] = 'Crud Generator - Genera Moduli';	
		$this->loadViews('mod_crud_gen/index', $this->global, $data, NULL);	
    } 
	
	
	public function ajaxCall(){
		switch($_REQUEST['call_type']){
			case 'get_list_column':
				$arrayOption = array();
				$table_name = $_REQUEST['tabella'];
				
				switch($this->dbdriver){
					case 'mysql':
					case 'mysqli':			
						$query = "SELECT COLUMN_NAME,IS_NULLABLE,COLUMN_KEY FROM INFORMATION_SCHEMA.COLUMNS 
									WHERE TABLE_SCHEMA='".$this->database."' 
									AND TABLE_NAME='".$table_name."' AND COLUMN_KEY <> 'PRI' ";
					break;
					
					case 'postgre':
						//GESTIRE L'ESCLUSIONE DELLA PRIMARY KEY
						$query = "SELECT COLUMN_NAME,IS_NULLABLE FROM INFORMATION_SCHEMA.COLUMNS 
									WHERE TABLE_SCHEMA='public' 
									AND TABLE_NAME='".$table_name."' ";			
					break;
					
					case 'mssql':
					
					break;			
				}	
				//echo $query;

				$res = $this->db->query($query)->result_array();
				$arrayOption = array();
				foreach ($res as $key => $row) {
					//$arrayOption['column_list'][] = $row['COLUMN_NAME'];
					$mandatory = "TRUE";
					if($row['IS_NULLABLE'] == 'YES'){
						$mandatory = "FALSE";	
					}
					$arrayOption['column_list'][] = array("column_name" => $row['COLUMN_NAME'], "mandatory" => $mandatory);
				}
		 
		 
				 
				//AGGIUNGO ANCHE NEL VETTORE I MODULI GENERATI RICHIAMABILI
				$sqlMod = "SELECT * FROM core_module_list
							WHERE mod_type = 'mod_gen'";
				$res = $this->db->query($sqlMod)->result_array();					
				
				foreach ($res as $key => $rowMod) {
					$arrayOption['mod_gen'][] = array('mod_name' => $rowMod['mod_name'], 'mod_title' =>$rowMod['mod_title']);
				}		
		 
				/*
				//AGGIUNGO ANCHE NEL VETTORE I MODULI GENERATI RICHIAMABILI
				$sqlMod = "SELECT * FROM information_schema.TABLE_CONSTRAINTS 
							WHERE information_schema.TABLE_CONSTRAINTS.CONSTRAINT_TYPE = 'FOREIGN KEY' 
							AND information_schema.TABLE_CONSTRAINTS.TABLE_NAME = '".$table_name."'";
				echo $sqlMod;			
				$res = $this->db->query($sqlMod)->result_array();					
				
				foreach ($res as $key => $rowMod) {
					$arrayOption['mod_gen2'][] = array('mod_name' => $rowMod['mod_name'], 'mod_title' =>$rowMod['mod_title']);
				}		
				*/
 
				
				echo json_encode($arrayOption);	
			break;
			

			case 'list_mod':
				if(!isset($table_name)){
					$table_name = "";
				}
				$arrayListMod = array();
				$query = "SELECT class_name FROM core_crud_settings WHERE table_name NOT IN('".$table_name."')";
				$res = $this->db->query($query)->result_array();

				foreach ($res as $key => $row) {
					$arrayListMod[] = $row['class_name'];
				}
				echo json_encode($arrayListMod);
				
			break;
			
			
			case 'list_mod_gen_aggr':
				$arrayListModGenAggr = array();

				$query = "SELECT mod_id,mod_name,mod_title FROM core_module_list WHERE mod_type = 'mod_gen_aggr' ";
				$res = $this->db->query($query)->result_array();
						
				foreach ($res as $key => $row) {
					$arrayListModGenAggr[] =  array("mod_id"=> $row['mod_id'],"mod_name"=> $row['mod_name'], 'mod_title'=> $row['mod_title']);
				}	
				echo json_encode($arrayListModGenAggr);		
			break;
		}
		
		
	}
	
	public function settings(){
		$res = '';
		$target = APPPATH;
		if (isset($_POST['save'])) {
			//$target = $_POST['target'];
			$string = '{
				"target": "' . $target . '",
				"copyassets": "0"
				}';
			$hasil_setting = $this->helper->createFile($string, APPPATH . '/modules/mod_crud_gen/settingjson.cfg');
			$res = '<p>Setting Updated</p>';
		}

        $data = array(
			'target' => $target,
			'res' => '<p>Setting Updated</p>',
			'get_setting' => $this->helper->readJSON(APPPATH . '/modules/mod_crud_gen/settingjson.cfg')
		);
		
		$this->global['pageTitle'] = 'Crud Generator - Impostazioni ';	
		$this->loadViews('mod_crud_gen/settings', $this->global, $data, NULL);			
	}	
	
	
	public function settings_update(){}	
	
	
	public function process(){
		//print'<pre>';print_r($_REQUEST);die();
		// get form data
		$table_name = $this->helper->safe($_POST['table_name']);
		$jenis_tabel = $this->helper->safe($_POST['jenis_tabel']);
		
		$export_excel = "";
		if(isset($_POST['export_excel'])){
			$export_excel = $this->helper->safe($_POST['export_excel']);	
		}
		/*
		$export_word = $this->helper->safe($_POST['export_word']);
		$export_pdf = $this->helper->safe($_POST['export_pdf']);
		*/
		
		$export_word = "";
		$export_pdf = "";	
		$hasil_view_read = "";
		$hasil_view_doc = "";
		$hasil_view_pdf = "";
		$hasil_pdf = "";
		
		$controller = $this->helper->safe($_POST['controller']);
		$model = $this->helper->safe($_POST['model']);
		$form_ajax = $this->helper->safe($_POST['form_ajax']);
		$form_print = $this->helper->safe($_POST['form_print']);
		$form_allegati = $this->helper->safe($_POST['form_allegati']);
		
		$form_print_head = "";
		if(isset($_POST['form_print_head'])){
			$form_print_head = $this->helper->safe($_POST['form_print_head']);	
		}
		
		if ($table_name <> '')
		{
			// set data
			$table_name = $table_name;
			$c = $controller <> '' ? ucfirst($controller) : ucfirst($table_name);
			$m = $model <> '' ? ucfirst($model) : ucfirst($table_name) . '_model';
			/*
			$v_list = $table_name . "_list";
			$v_read = $table_name . "_read";
			$v_form = $table_name . "_form";
			$v_form_pdf = $table_name . "_form_pdf";
			$v_list_ajax = $table_name . "_list_ajax";
			$v_read_ajax = $table_name . "_read_ajax";
			$v_form_ajax = $table_name . "_form_ajax";		
			$v_doc = $table_name . "_doc";
			$v_pdf = $table_name . "_pdf";
			*/
		
			$v_list = strtolower($c) . "_list";
			$v_read = strtolower($c)  . "_read";
			$v_form = strtolower($c)  . "_form";
			$v_form_pdf = strtolower($c)  . "_form_pdf";
			$v_list_ajax = strtolower($c)  . "_list_ajax";
			$v_read_ajax = strtolower($c)  . "_read_ajax";
			$v_form_ajax = strtolower($c)  . "_form_ajax";		
			$v_doc = strtolower($c)  . "_doc";
			$v_pdf = strtolower($c)  . "_pdf";			

			
			// url
			$c_url = strtolower($c);
			$mod_title = "";
			if($_REQUEST['mod_title'] != ''){
				$mod_title = $_REQUEST['mod_title'];
			} else {
				$mod_title = $c_url;
			}
					
			// filename
			$c_file = $c . '.php';
			$m_file = $m . '.php';
			$v_list_file = $v_list . '.php';
			$v_read_file = $v_read . '.php';
			$v_form_file = $v_form . '.php';
			
			$v_list_file_ajax = $v_list_ajax . '.php';
			$v_read_file_ajax = $v_read_ajax . '.php';
			$v_form_file_ajax = $v_form_ajax . '.php';		
			$v_form_pdf_file = $v_form_pdf . '.php';
			
			$v_doc_file = $v_doc . '.php';
			$v_pdf_file = $v_pdf . '.php';

			$js_form_config_file = strtolower($c)  . "_form_config.js.php";
			$js_datatable_config_file = strtolower($c)  . "_datatable_config.js.php";

			// read setting
			/*
			$get_setting = $this->helper->readJSON('core/settingjson.cfg');
			$target = $get_setting->target;
			*/
			$target = APPPATH."modules/".$c_url;
			if (!file_exists($target))
			{
				mkdir($target, 0777, true);
				mkdir($target."/controllers", 0777, true);
				mkdir($target."/models", 0777, true);
				mkdir($target."/views", 0777, true);
				mkdir($target."/views/partials/gridtab", 0777, true);
				mkdir($target."/views/partials/winform", 0777, true);
				mkdir($target."/views/jsconfig", 0777, true);
				
			}
			
			$pk = $this->Mod_crud_gen_model->primary_field($table_name);
			$non_pk = $this->Mod_crud_gen_model->not_primary_field($table_name);
			$all = $this->Mod_crud_gen_model->all_field($table_name);
			$arrayRefFields = $this->Mod_crud_gen_model->getFieldsRef($table_name);	
			$arrayRefTables = $this->Mod_crud_gen_model->getTableRef($table_name);

			$tablesTitle = $this->Mod_crud_gen_model->getTablesTitle();
			$column_name_ms_details  = $this->Mod_crud_gen_model->getColumnsTableRef($arrayRefTables);

			//print'<pre>';print_r($column_name_ms_details['_mod_corsi_giorni_orari']);
			// generate
			$param_gen = array();

			$export_excel = "";
			if(isset($_POST['export_excel'])){
				$export_excel = $this->helper->safe($_POST['export_excel']);	
			}
			$form_print_head = "";
			if(isset($_POST['form_print_head'])){
				$form_print_head = $this->helper->safe($_POST['form_print_head']);	
			}	
			$enable_master_detail = "";
			if(isset($_POST['enable_master_detail'])){
				$enable_master_detail = $this->helper->safe($_POST['enable_master_detail']);	
			}		
			$column_name_detail = "";
			if(isset($_POST['column_name_detail'])){
				$column_name_detail = $this->helper->safe($_POST['column_name_detail']);	
			}				
			$column_name = "";
			if(isset($_POST['column_name'])){
				$column_name = $this->helper->safe($_POST['column_name']);	
			}	
			
			// generate
			$param_gen = array(
				'target' => $target,
				'table_name'  => $table_name,
				'jenis_tabel' => $jenis_tabel,
				'controller' => $controller,
				'c_url' => $c_url,
				'c' => $c,
				'model' => $model,
				'm' => $m,
				'form_ajax' => $form_ajax,
				'form_print' => $form_print,
				'form_allegati' => $form_allegati,
				'pk' => $pk,
				'non_pk' => $non_pk,
				'all'	=> $all,
				'c_file' => $c_file,
				'm_file' => $m_file,
				'v_form_file' => $v_form_file,
				'v_form_file_ajax' => $v_form_file_ajax,
				'v_form_pdf_file' => $v_form_pdf_file,
				'v_list_file_ajax' => $v_list_file_ajax,
				'v_list_file' => $v_list_file,
				'v_read_file' => $v_read_file,
				'v_read_file_ajax' => $v_read_file_ajax,
				'v_doc_file' => $v_doc_file,
				'v_pdf_file' => $v_pdf_file,	
				'v_list_ajax'=> $v_list_ajax,	
				'v_read_ajax'=> $v_read_ajax,	
				'v_form_ajax'=> $v_form_ajax,	
				'v_form_pdf'=> $v_form_pdf,	
				'js_form_config_file' => $js_form_config_file,
				'js_datatable_config_file' => $js_datatable_config_file,				
				'form_columns_nr_layout' => $_REQUEST['form_columns_nr_layout'],
				'mod_icon' => $_REQUEST['mod_icon'],
				'field_name' => $_REQUEST['field_name'],
				'select_ext_mod' => $_REQUEST['select_ext_mod'],
				'sel_mod_gerarchia' => $_REQUEST['sel_mod_gerarchia'],
				'mod_gen_aggr' => $_REQUEST['mod_gen_aggr'],
				'column_name' => $column_name,
				'mod_title' =>  $_REQUEST['mod_title'],
				'mod_type' => $_REQUEST['mod_type'],
				'export_excel' => $export_excel,
				'form_print_head' => $form_print_head,
				'enable_master_detail' => $enable_master_detail,
				'column_name_detail' => $column_name_detail,
				'database' => $this->database,
				'dbdriver' => $this->dbdriver,	
				'export_word' => $export_word,
				'export_pdf' => $export_pdf,
				'arrayRefFields' => $arrayRefFields,
				'arrayRefTables' => $arrayRefTables,
				'tablesTitle' => $tablesTitle,
				'column_name_ms_details' => $column_name_ms_details,
				'Mod_crud_gen_model' => $this->Mod_crud_gen_model,
				'show_master_detail' => $_REQUEST['show_master_detail']
			);

			//$hasil[] = $this->create_config_pagination($param_gen);
			$hasil[] = $this->create_controller($param_gen);
			$hasil[] = $this->create_model($param_gen);
			if ($jenis_tabel == 'reguler_table') {
				$hasil[] = $this->create_view_list($param_gen);
			} else {
				if ($form_ajax == 'Y') {
					$hasil[] = $this->create_view_list_datatables_ajax($param_gen);	
				} else {
					$hasil[] = $this->create_view_list_datatables($param_gen);
				}	
			}
			if($_REQUEST['mod_type'] == 'crud'){
				if ($form_ajax == 'Y') {
					$hasil[] = $this->create_view_form_ajax($param_gen);
					$hasil[] = $this->create_view_read_ajax($param_gen);
				} else {
					$hasil[] = $this->create_view_form($param_gen);			
				}
			}

			$hasil[] = $this->create_js_form_config($param_gen);
			$hasil[] = $this->create_js_datatable_config($param_gen);

			$this->create_install_file($param_gen);
			
			if ($form_print == 'Y') {
				$hasil[] = $this->create_view_form_pdf($param_gen);		
			}	

			if($export_excel == 1){
				$hasil[] = $this->create_exportexcel_helper($param_gen);	
			}

			
			if(isset($export_word)){
				$hasil[] = $this->create_view_list_doc($param_gen);
			}
			if(isset($export_pdf)){
				$this->create_view_list_pdf($param_gen);
			}		
					
		} else
		{
			$hasil[] = 'No table selected.';
		}
		
	 
		$sqlDelete = "DELETE FROM core_crud_settings WHERE mod_name= '".$table_name."'";	
		$this->Mod_crud_gen_model->execSQL($sqlDelete, TRUE);

		$mod_crud_settings = json_encode($_REQUEST);
		$sqlInsert = "INSERT INTO core_crud_settings(mod_name,
													mod_table_name,
													class_name,
													mod_type,
													mod_title,
													is_generable,
													mod_crud_settings) 
					VALUES('$table_name',
							'$table_name',
							'$table_name',
							'crud',
							'$mod_title',
							'S',
							'$mod_crud_settings')";		
		$this->Mod_crud_gen_model->execSQL($sqlInsert, TRUE);
 
		exec ("chmod 777 -R ". APPPATH . "/modules/".$table_name);
		exec ("chmod 777 -R ". APPPATH . "/modules/".$c_url);
 

		$this->index($hasil);
		
	}


	public function create_controller($param_gen = array()){
		require APPPATH . '/modules/mod_crud_gen/classes/generators/create_controller.php';
		$string = create_controller::output($param_gen);
		//print'<pre>';print_r($param_gen);die();
		return $this->helper->createFile($string, APPPATH."modules/" . $param_gen['c_url'] . "/controllers/" . $param_gen['c_file']);
	}
    
	
	private function create_model($param_gen = array()){
		require APPPATH . '/modules/mod_crud_gen/classes/generators/create_model.php';
		$string = create_model::output($param_gen);
		return $this->helper->createFile($string, APPPATH."modules/" . $param_gen['c_url'] . "/models/" . $param_gen['m_file']);
	}

	private function create_view_form($param_gen = array()){
		require APPPATH . '/modules/mod_crud_gen/classes/generators/create_view_form.php';
		$string = create_view_form::output($param_gen);
		return $this->helper->createFile($string, APPPATH."modules/" . $param_gen['c_url'] . "/views/" . $param_gen['v_form_file']);		
	}

	private function create_view_form_ajax($param_gen = array()){
		require APPPATH . '/modules/mod_crud_gen/classes/generators/create_view_form_ajax.php';
		$string = create_view_form_ajax::output($param_gen);
		return $this->helper->createFile($string, APPPATH."modules/" . $param_gen['c_url'] . "/views/" . $param_gen['v_form_file_ajax']);			
	}

	
	private function create_view_form_pdf($param_gen = array()){
		require APPPATH . '/modules/mod_crud_gen/classes/generators/create_view_form_pdf.php';
		$string = create_view_form_pdf::output($param_gen);		
		return $this->helper->createFile($string, APPPATH."modules/" . $param_gen['c_url'] . "/views/" . $param_gen['v_form_pdf_file']);	
	}

	
	private function create_view_list($param_gen = array()){
		require APPPATH . '/modules/mod_crud_gen/classes/generators/create_view_list.php';
		$string = create_view_list::output($param_gen);		
		return $this->helper->createFile($string, APPPATH."modules/" . $param_gen['c_url'] . "/views/" . $param_gen['v_list_file']);		
	}

	private function create_view_list_datatables($param_gen = array()){
		require APPPATH . '/modules/mod_crud_gen/classes/generators/create_view_list_datatables.php';
		$string = create_view_list_datatables::output($param_gen);		
		return $this->helper->createFile($string, APPPATH."modules/" . $param_gen['c_url'] . "/views/" . $param_gen['v_list_file']);		
	}

	private function create_view_list_datatables_ajax($param_gen = array()){
		require APPPATH . '/modules/mod_crud_gen/classes/generators/create_view_list_datatables_ajax.php';
		$string = create_view_list_datatables_ajax::output($param_gen);		
		return $this->helper->createFile($string, APPPATH."modules/" . $param_gen['c_url'] . "/views/" . $param_gen['v_list_file_ajax']);		
	}

	/*
	private function create_libraries_datatables($param_gen = array()){
		require APPPATH . '/modules/mod_crud_gen/classes/generators/create_libraries_datatables.php';
		$string = create_libraries_datatables::output($param_gen);		
		return $this->helper->createFile($string, APPPATH."libraries/Datatables.php");		
	}
	*/

	private function create_view_read($param_gen = array()){
		require APPPATH . '/modules/mod_crud_gen/classes/generators/create_view_read.php';		
		$string = create_view_read::output($param_gen);		
		return $this->helper->createFile($string, APPPATH."modules/" . $param_gen['c_url'] . "/views/" . $param_gen['v_read_file']);		
	}
	
	private function create_view_read_ajax($param_gen = array()){
		require APPPATH . '/modules/mod_crud_gen/classes/generators/create_view_read_ajax.php';	
		$string = create_view_read_ajax::output($param_gen);			
		return $this->helper->createFile($string, APPPATH."modules/" . $param_gen['c_url'] . "/views/" . $param_gen['v_read_file_ajax']);		
	}	

 	
	private function create_exportexcel_helper($param_gen = array()){
		require APPPATH . '/modules/mod_crud_gen/classes/generators/create_exportexcel_helper.php';
		$string = create_exportexcel_helper::output($param_gen);		
		return $this->helper->createFile($string, APPPATH."helpers/exportexcel_helper.php");	
	}		
 

	/*
	private function create_config_pagination($param_gen = array()){
		require APPPATH . '/modules/mod_crud_gen/classes/generators/create_config_pagination.php';
		$string = create_config_pagination::output($param_gen);		
		return $this->helper->createFile($string, APPPATH."config/pagination.php");		
	}
	*/
	/*
	private function create_pdf_library($param_gen = array()){
		require APPPATH . '/modules/mod_crud_gen/classes/generators/create_pdf_library.php';
		$string = create_pdf_library::output($param_gen);			
		return $this->helper->createFile($string, APPPATH."libraries/pdf.php");
	}
	*/
	private function create_view_list_doc($param_gen = array()){
		require APPPATH . '/modules/mod_crud_gen/classes/generators/create_view_list_doc.php';
		$string = create_view_list_doc::output($param_gen);			
		return $this->helper->createFile($string, APPPATH."modules/" . $param_gen['c_url'] . "/views/" . $param_gen['v_doc_file']);		

	}

	private function create_view_list_pdf($param_gen = array()){
		require APPPATH . '/modules/mod_crud_gen/classes/generators/create_view_list_pdf.php';
		$string = create_view_list_pdf::output($param_gen);			
		return $this->helper->createFile($string, APPPATH."modules/" . $param_gen['c_url'] . "/views/" . $param_gen['v_pdf_file']);		

	}	
	

	private function create_js_form_config($param_gen = array()){
		require APPPATH . '/modules/mod_crud_gen/classes/generators/create_js_form_config.php';
		$string = create_js_form_config::output($param_gen);				
		return $this->helper->createFile($string, APPPATH."modules/" . $param_gen['c_url'] . "/views/jsconfig/" . $param_gen['js_form_config_file']);		

	}	


	private function create_js_datatable_config($param_gen = array()){
		require APPPATH . '/modules/mod_crud_gen/classes/generators/create_js_datatable_config.php';
		$string = create_js_datatable_config::output($param_gen);			
		return $this->helper->createFile($string, APPPATH."modules/" . $param_gen['c_url'] . "/views/jsconfig/" . $param_gen['js_datatable_config_file']);		

	}	
	
	
	private function create_install_file($param_gen = array()){
		require APPPATH . '/modules/mod_crud_gen/classes/generators/create_install_file.php';
		$string = create_install_file::output($param_gen);			
		return $this->helper->createFile($string, APPPATH."modules/" . $param_gen['c_url'] . "/install.txt");
	}		
	
}
