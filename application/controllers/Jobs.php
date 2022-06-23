<?php

require BASEPATH . '/core/Model.php';
require APPPATH . '/models/Common_model.php';	

require APPPATH . '/modules/mod_stampe/models/Mod_stampe_model.php';
require APPPATH . '/third_party/dompdf/autoload.inc.php';

use Dompdf\Dompdf;


class Jobs extends CI_Controller {

    public function __construct() {
        parent::__construct();
		
        $this->load->model('Mod_stampe_model');
		$this->load->model('user_model');
    }


	/**
    * 
    */	
	public function stampaRic($anno_sportivo, $mese = NULL) {  
		set_time_limit(0);
		$settings = $this->user_model->loadSettings();
		
		
		touch(FCPATH."/ricevute_gen/ELAB");
		$msg= "ELABORAZIONE RICEVUTE IN CORSO...L'OPERAZIONE, IN BASE ALL'INTERVALLO SCELTO, PUO DURARE ANCHE ALCUNE ORE.
				<br>ELABORAZIONE LANCIATA IN DATA <b style='color:#000000'><u>".date("d/m/Y H:i")."</u></b>";		
		file_put_contents(FCPATH."/ricevute_gen/ELAB",$msg);
 
		//unlink(FCPATH."/ricevute_gen/ELAB");	
		
		$ricevute =  $this->Mod_stampe_model->getRicevute($anno_sportivo, $mese);
		//file_put_contents(FCPATH."/ricevute_gen/ELAB",$ricevute);
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
			//$dompdf->stream('Stampa Ricevute '.$anno_sportivo.'.pdf', array("Attachment" => FALSE));
            $output = $dompdf->output();

			$nome_esercizio =  $this->Mod_stampe_model->getNomeEsercizio($anno_sportivo);
			$nomeFile = FCPATH.'/ricevute_gen/Stampa_Ricevute_'.$anno_sportivo;
			//$nomeFile = FCPATH.'/ricevute_gen/Stampa_Ricevute_'.$nome_esercizio;
            if($mese != NULL){
				$nomeFile .= '_'.$mese;
			}
			
			$nomeFile .='['.date('Y-m-d__Hi').'].pdf';
			
			file_put_contents($nomeFile, $output);

		} catch(Exception $e) {
			die($e);
		}
		
		unlink(FCPATH."/ricevute_gen/ELAB");	
		exit(0);	
	}	
	
	
	/**
    * 
    */	
	public function stampaTess($anno_sportivo) {	
		set_time_limit(0);
		$settings = $this->user_model->loadSettings();
		//print'<pre>';print_r($settings);
		//echo FCPATH;
		touch(FCPATH."/ricevute_gen/ELAB");
		//die();
		$msg= "ELABORAZIONE TESSERAMENTI IN CORSO...L'OPERAZIONE, IN BASE ALL'INTERVALLO SCELTO, PUO DURARE ANCHE ALCUNE ORE.
				<br>ELABORAZIONE LANCIATA IN DATA <b style='color:#000000'><u>".date("d/m/Y H:i")."</u></b>";		
		file_put_contents(FCPATH."/ricevute_gen/ELAB",$msg);		
		$dompdf = new Dompdf();
		$this->load->model('user_model');
		$row = array("vettore" => TRUE);

		$tesseramenti = $this->Mod_stampe_model->getTesseramenti($anno_sportivo);
		//print'<pre>';print_r($tesseramenti);

		 
		$data = array('tesseramenti' => $tesseramenti, 'company_logo' => $settings[0]->company_logo, 'manager_signature' => $settings[0]->manager_signature);
		$view = $this->load->view('mod_stampe/stampa_tesseramenti_job.php', $data, TRUE);
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

		//$dompdf->stream('Stampa Tesseramenti '.$anno_sportivo.'.pdf', array("Attachment" => FALSE));
        $output = $dompdf->output();

		$nome_esercizio =  $this->Mod_stampe_model->getNomeEsercizio($anno_sportivo);
		$nomeFile = FCPATH.'/ricevute_gen/Stampa_Tesseramenti_'.$anno_sportivo;
		//$nomeFile = FCPATH.'/ricevute_gen/Stampa_Tesseramenti_'.$nome_esercizio;

		$nomeFile .='['.date('Y-m-d__Hi').'].pdf';

        file_put_contents($nomeFile, $output);
				
		unlink(FCPATH."/ricevute_gen/ELAB");				
		exit(0);			
		 
	}	

}