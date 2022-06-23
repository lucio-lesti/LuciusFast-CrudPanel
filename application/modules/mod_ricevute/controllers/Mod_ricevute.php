<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');
require APPPATH . '/libraries/BaseController.php';
 
use Dompdf\Dompdf;

class Mod_ricevute extends BaseController
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Mod_ricevute_model');				
		
    }
 

 
 
	public function excel(){
        $this->load->helper('exportexcel');
        $namaFile = "mod_ricevute.csv";
        $judul = "mod_ricevute";
        $nourut = 1;
		
        //penulisan header
        header("Pragma: public");
        header("Expires: 0");
        header("Cache-Control: must-revalidate, post-check=0,pre-check=0");
        header("Content-Type: application/force-download");
		header('Content-Type: text/csv; charset=utf-8');
        header("Content-Type: application/download");
        header("Content-Disposition: attachment;filename=" . $namaFile . "");

		$output = fopen('php://output', 'w');
		
        fwrite($output, "id;");
		fwrite($output, "Fk Anagrafica;");
		fwrite($output, "Tessera Interna;");
		fwrite($output, "Tessera Associativa;");
		fwrite($output, "Nome;");
		fwrite($output, "Cognome;");
		fwrite($output, "Fk Disciplina;");
		fwrite($output, "Disciplina;");
		fwrite($output, "Anno;");
		fwrite($output, "Mese;");
		fwrite($output, "Data;");
		fwrite($output, "Importo;");
		fwrite($output,"\n");

        fwrite($output, "id;");
		fwrite($output, "fk_anagrafica;");
		fwrite($output, "tessera;");
		fwrite($output, "tess_asc;");
		fwrite($output, "nome;");
		fwrite($output, "cognome;");		
		fwrite($output, "fk_disciplina;");
		fwrite($output, "disciplina;");
		fwrite($output, "anno;");
		fwrite($output, "mese;");
		fwrite($output, "data;");
		fwrite($output, "importo;");
		fwrite($output,"\n");
		
		foreach ($this->Mod_ricevute_model->get_all() as $data) {
            fwrite($output, $data->id.";");
			fwrite($output, $data->fk_anagrafica.";");
			fwrite($output, $data->tessera.";");
			fwrite($output, $data->tess_asc.";");
			fwrite($output, $data->nome.";");
			fwrite($output, $data->cognome.";");
			fwrite($output, $data->fk_disciplina.";");
			fwrite($output, $data->disciplina.";");
			fwrite($output, $data->anno.";");
			fwrite($output, $data->mese.";");
			fwrite($output, $data->data.";");
			fwrite($output, $data->importo.";");
			fwrite($output,"\n");

        }

        exit();
    }



    public function _rules() 
    {
		$this->form_validation->set_rules('tess_asc', '', 'trim|max_length[255]');
		$this->form_validation->set_rules('tessera', '', 'trim|max_length[255]');
		$this->form_validation->set_rules('fk_anagrafica', '', 'trim|numeric|max_length[10]');
		$this->form_validation->set_rules('nome', '', 'trim|max_length[255]');
		$this->form_validation->set_rules('cognome', '', 'trim|max_length[255]');
		$this->form_validation->set_rules('fk_disciplina', '', 'trim|numeric|max_length[10]');
		$this->form_validation->set_rules('disciplina', '', 'trim|max_length[255]');
		$this->form_validation->set_rules('importo', '', 'trim|max_length[255]');
		$this->form_validation->set_rules('data', '', 'trim|max_length[255]');
		$this->form_validation->set_rules('mese', '', 'trim');
		$this->form_validation->set_rules('anno', '', 'trim');

		$this->form_validation->set_rules('id', 'id', 'trim');
		$this->form_validation->set_error_delimiters('<span class="text-danger">', '</span>');
    }
}