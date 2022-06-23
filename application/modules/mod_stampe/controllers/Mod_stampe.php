<?php
if (!defined('BASEPATH')){
	exit('No direct script access allowed');
}
require APPPATH . '/libraries/BaseController.php';

use Dompdf\Dompdf;

class Mod_stampe extends BaseController
{
    public function __construct()
    {
        parent::__construct();	
		$this->load->model('Mod_stampe_model');
		$this->mod_name = "mod_stampe";
		$this->mod_title = "Stampe";
		$this->pkIdName = 'id';
		
        $this->modelClassModule =  $this->Mod_stampe_model;	
    }


	/**
	* Override del metodo index 
	*/
    public function index() {
		$listaAnniEsercizi = $this->modelClassModule->getListaAnniEsercizi();
	//	print'<pre>';print_r($listaAnniEsercizi);

        $data = array(
			'listaAnniEsercizi' => $listaAnniEsercizi,
            'total_rows' => 0,
			'module' => $this->mod_name
        );
		
		$this->global['pageTitle'] = $this->mod_title.' - Lista';	
		$this->loadViews($this->mod_name .'/mod_stampe_list', $this->global, $data, NULL);	
		
    } 


	/**
	*  
	*/
	public function elencoDocGenerati() {
		$dir    = FCPATH."/ricevute_gen";
		$fileDir = glob($dir.'/*.pdf');
		usort($fileDir, function($a, $b) {
			return filemtime($a) < filemtime($b);
		});


		$count = 0;
		foreach($fileDir as $keyDir => $file){
			if(!is_dir($dir."/".$file)){
				if(($file != 'ELAB')){
					$file = str_replace(FCPATH."/ricevute_gen/","",$file);
					echo "<li class=\"fa fa-file-pdf-o\"></li> <a target='_blank' href='".base_url()."/ricevute_gen/".$file."'>".$file."</a><br>"; 
				}
			}
			$count++;
		}

	}	
	

	/**
	*  
	*/
	public function checkElab(){
		$json_return['msg'] = "";
		if(file_exists( FCPATH."/ricevute_gen/ELAB")){
			$json_return =  array("CHECK_ELAB"=>"SI_ELAB");
			$msg = file_get_contents(FCPATH."/ricevute_gen/ELAB");
			$json_return['msg'] = $msg;
		} else {
			$json_return = array("CHECK_ELAB"=>"NO_ELAB");
		}
		
		echo json_encode($json_return);		
	}


	/**
	*  
	*/
	public function jobStampa($controller,$modulo,$anno_sportivo, $mese = NULL){
		$cmd = "php ".FCPATH."index.php ".$controller." ".$modulo." ".$anno_sportivo;
		if($mese != NULL){
			$cmd.= " ".$mese;
		}
		//echo $cmd;
		shell_exec($cmd ." > /dev/null 2>&1 &");
	}



	/**
	*  
	*/	
	public function stampaRic($anno_sportivo = NULL, $mese = NULL, $idRicevuta = NULL) 
	{
		$this->global['pageTitle'] = 'Stampa Ricevute';	
		$settings = $this->user_model->loadSettings();
		$row = array("vettore" => TRUE);
		
		$ricevute =  $this->Mod_stampe_model->getRicevute($anno_sportivo, $mese, $idRicevuta);

		if ($row) {
			$data = array('ricevute' => $ricevute, 'company_logo' => $settings[0]->company_logo, 'manager_signature' => $settings[0]->manager_signature);
			//LA PARTE COMMENTATA è RELATIVA ALLA GENERAZIONE DEL DOMPDF
 
			try {	
				$view = $this->load->view('mod_stampe/stampa_ricevute_job.php', $data, TRUE);	
				$dompdf = new Dompdf();
				$dompdf->loadHtml($view);
				$dompdf->set_option("isPhpEnabled", TRUE);
		
				$dompdf->setPaper('A4');

				$dompdf->render();

				$x          = 520;
				$y          = 820;
				//$text       = "Pagina {PAGE_NUM} di {PAGE_COUNT}";     
				$text       = "";
				$font       = $dompdf->getFontMetrics()->get_font('Helvetica', 'normal');   
				$size       = 10;    
				$color      = array(0,0,0);
				$word_space = 0.0;
				$char_space = 0.0;
				$angle      = 0.0;
				 
				$dompdf->getCanvas()->page_text(
				  $x, $y, $text, $font, $size, $color, $word_space, $char_space, $angle
				);			
				$dompdf->stream('Stampa Ricevute '.$anno_sportivo.'.pdf', array("Attachment" => FALSE));
			} catch(Exception $e) {
				die($e);
			}
			exit(0);	
	 
		} else {
			$this->session->set_flashdata('error', 'Record non Trovato');
			redirect(site_url('mod_stampe'));
		}
	}	
	
	

	/**
	*  
	*/	
	public function stampaTess($anno_sportivo) 
	{
		$this->global['pageTitle'] = 'Stampa Tesseramenti';
		$settings = $this->user_model->loadSettings();
		$dompdf = new Dompdf();
 
		$this->load->model('user_model');
		$row = array("vettore" => TRUE);

		$tesseramenti = $this->Mod_stampe_model->getTesseramenti($anno_sportivo);
		if ($row) {			
			$data = array('tesseramenti' => $tesseramenti, 'company_logo' => $settings[0]->company_logo, 'manager_signature' => $settings[0]->manager_signature);
			$view = $this->load->view('mod_stampe/stampa_tesseramenti_job.php', $data, TRUE);
			$dompdf->loadHtml($view);
			$dompdf->set_option("isPhpEnabled", TRUE);
	
			$dompdf->setPaper('A4');

			$dompdf->render();

			$x          = 520;
			$y          = 820;
			$text       = "";			
			$font       = $dompdf->getFontMetrics()->get_font('Helvetica', 'normal');   
			$size       = 10;    
			$color      = array(0,0,0);
			$word_space = 0.0;
			$char_space = 0.0;
			$angle      = 0.0;
			 
			$dompdf->getCanvas()->page_text(
			  $x, $y, $text, $font, $size, $color, $word_space, $char_space, $angle
			);			

			$dompdf->stream('Stampa Tesseramenti '.$anno_sportivo.'.pdf', array("Attachment" => FALSE));
						
			exit(0);			
		} else {
			$this->session->set_flashdata('error', 'Record non Trovato');
			redirect(site_url('mod_stampe'));
		}
	}	

 
	
}