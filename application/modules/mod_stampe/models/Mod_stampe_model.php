<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

require APPPATH . '/libraries/BaseModel.php';
 

class Mod_stampe_model extends BaseModel
{

 
	/**
	 * 
	*/	
    public function __construct(){
        parent::__construct();
        $this->mod_name = 'mod_stampe';	
		$this->table = 'mod_stampe';
		$this->id = 'id';

    }


	/**
	* 
	*/
	public function getRicevute($id_esercizio = NULL,  $idRicevuta = NULL){
		$arrayReturn = array();
	
		$sql ="SELECT mod_anagrafica.nome,
					mod_anagrafica.cognome,
					mod_anagrafica.codfiscale,
					mod_esercizi.nome as anno_sportivo,
					_mod_magazzino_tessere_lista_tessere.codice_tessera AS tessera_associativa,
					_mod_pagamenti_ricevuti.datapagamento as 'data',
					_mod_pagamenti_ricevuti.importo,
					mod_corsi.nome as corso
				FROM _mod_pagamenti_ricevuti
				INNER JOIN mod_anagrafica
					ON mod_anagrafica.id = _mod_pagamenti_ricevuti.fk_anagrafica
				INNER JOIN mod_corsi
					ON mod_corsi.id = _mod_pagamenti_ricevuti.fk_corso
				INNER JOIN _mod_anagrafica_corsi
					ON _mod_anagrafica_corsi.fk_anagrafica = mod_anagrafica.id 
					AND _mod_anagrafica_corsi.fk_corso = mod_corsi.id 
				INNER JOIN mod_tesseramenti
					ON mod_tesseramenti.id = _mod_anagrafica_corsi.fk_tesseramento
				INNER JOIN mod_esercizi
					ON mod_esercizi.id = mod_tesseramenti.fk_esercizio
				INNER JOIN _mod_magazzino_tessere_lista_tessere 
					ON _mod_magazzino_tessere_lista_tessere.id = mod_tesseramenti.fk_tessera_associativa
		WHERE 1=1 ";
		if(($id_esercizio != NULL) && ($id_esercizio != 'NULL')){		
			$sql.= " AND mod_esercizi.id = ".$id_esercizio;
		}
		
		if(($idRicevuta != NULL) && ($idRicevuta != 'NULL')){			
			$sql.= " AND _mod_pagamenti_ricevuti.id ='".$idRicevuta."' ";
		}			
		$sql.= " ORDER BY _mod_pagamenti_ricevuti.datapagamento asc, cognome asc";
		
		//die($sql);
		$arrayRes = $this->db->query($sql)->result_array();
		foreach($arrayRes as $key => $value){
			if(trim($value['data']) == ''){
				$value['data'] = $this->getFirstWorkingDayOfMonth($value['anno'],$value['mese']);
			}
			$arrayReturn[] = $value;
		}
		
		return $arrayReturn;
	}		 


	/**
	* 
	*/	
	public function getTesseramenti($id_esercizio){
		$arrayReturn = array();

		$sql ="SELECT mod_anagrafica.nome,
					mod_anagrafica.cognome,
					mod_anagrafica.codfiscale,
					mod_esercizi.nome as anno_sportivo,
					mod_tesseramenti.tessera_interna,
					_mod_magazzino_tessere_lista_tessere.codice_tessera AS tessera_associativa,
					_mod_pagamenti_ricevuti.datapagamento as 'data',
					_mod_pagamenti_ricevuti.importo
				FROM mod_tesseramenti
				INNER JOIN mod_anagrafica
					ON mod_anagrafica.id = mod_tesseramenti.fk_anagrafica
				INNER JOIN mod_esercizi
					ON mod_esercizi.id = mod_tesseramenti.fk_esercizio
				INNER JOIN _mod_magazzino_tessere_lista_tessere 
					ON _mod_magazzino_tessere_lista_tessere.id = mod_tesseramenti.fk_tessera_associativa
				INNER JOIN _mod_pagamenti_ricevuti
					ON _mod_pagamenti_ricevuti.fk_anagrafica = mod_anagrafica.id
					AND _mod_pagamenti_ricevuti.tipo_pagamento = 'TESSERAMENTO'
				WHERE mod_esercizi.id = ".$id_esercizio;
		$arrayRes = $this->db->query($sql)->result_array();
		//die($sql);		
		foreach($arrayRes as $key => $value){
			/*
			if(!isset($arrayReturn[$value['nome']."_".$value['cognome']])){
				if(trim($value['data']) == ''){
					$value['data'] = $this->getFirstWorkingDayOfMonth($value['anno'],$value['mese']);
				}
				$arrayReturn[$value['nome']."_".$value['cognome']] = $value;				
			}
			*/
			$arrayReturn[$value['nome']."_".$value['cognome']] = $value;		
		}
				
		return $arrayReturn;
	}		 
	
	

    /**
	 * 
	 * Aggiunge le virgolette in una stringa
     * @param mixed $str
     * @return string
    */
    private function add_quotes($str) {
    	return sprintf("'%s'", $str);
	}
	


    /**
	 * 
	 * Ritorna il primo giorno lavorativa di un mese
	 * @param mixed $anno
	 * @param mixed $mese
	 * @return string
    */   
	private function getFirstWorkingDayOfMonth($anno, $mese){
		switch($mese){
			case 'GENNAIO':
				$month = "JANUARY";
			break;
			
			case 'FEBBRAIO':
				$month = "FEBRUARY";
			break;

			case 'MARZO':
				$month = "MARCH";
			break;

			case 'APRILE':
				$month = "APRIL";
			break;

			case 'MAGGIO':
				$month = "may";
			break;

			case 'GIUGNO':
				$month = "JUNE";
			break;

			case 'LUGLIO':
				$month = "JULY";
			break;

			case 'AGOSTO':
				$month = "AUGUST";
			break;

			case 'SETTEMBRE':
				$month = "SEPTEMBER";
			break;

			case 'OTTOBRE':
				$month = "OCTOBER";
			break;

			case 'NOVEMBRE':
				$month = "NOVEMBER";
			break;

			case 'DICEMBRE':
				$month = "DECEMBER";
			break;			
		}
		
		$date = new DateTime("first monday of $month $anno");
		$pasquetta = $this->getPasquetta($anno);
		$publicHolidays = ['01-01', '06-01', '04-25','05-01','06-02','08-15','11-01','12-08','12-25','12-26',"$pasquetta"]; // Format: mm-dd
		while (true) {
			if ($date->format("N") >= 6) {
				$date->modify("+".(8-$date->format("N"))." days");
			} elseif (in_array($date->format("m-d"), $publicHolidays)) {
				$date->modify("+1 day");
			} else {
				break;
			}
		}

		$FirstWorkingDayOfMonth = $date->format("d/m/Y");		
		return $FirstWorkingDayOfMonth;
		
	}		


	/**
	 * 
	 * Ritorna la data della pasquetta 
     * @param mixed $anno
	 * @return string
    */ 
	private function getPasquetta($anno){
		$pasquetta = "";
		switch($anno){
			case '2010':
				$pasquetta = "04-05";
			break;
			
			case '2011':
				$pasquetta = "04-25";
			break;
			
			case '2012':
				$pasquetta = "04-09";
			break;
			
			case '2013':
				$pasquetta = "04-01";
			break;
			
			case '2014':
				$pasquetta = "04-21";
			break;
			
			case '2015':
				$pasquetta = "04-06";
			break;
			
			case '2016':
				$pasquetta = "03-28";
			break;
			
			case '2017':
				$pasquetta = "04-17";
			break;
			
			case '2018':
				$pasquetta = "04-02";
			break;
			
			case '2019':
				$pasquetta = "04-22";
			break;
			
			case '2020':
				$pasquetta = "04-13";
			break;
			
			case '2021':
				$pasquetta = "04-5";
			break;
			
			case '2022':
				$pasquetta = "04-18";
			break;
			
			case '2023':
				$pasquetta = "04-10";	
			break;
			
			case '2024':
				$pasquetta = "04-01";
			break;
			
			case '2025':
				$pasquetta = "04-21";
			break;
			
			case '2026':
				$pasquetta = "04-06";
			break;
			
			case '2027':
				$pasquetta = "03-29";
			break;
			
			case '2028':
				$pasquetta = "04-17";
			break;
			
			case '2029':
				$pasquetta = "04-02";
			break;
			
			case '2030':	
				$pasquetta = "04-22";
			break;	
		}
		
		return $pasquetta;
	}


	public function getListaAnniEsercizi(){
		$sql ="SELECT mod_esercizi.id,
					mod_esercizi.nome,
					EXTRACT(YEAR FROM  data_da) AS anno_da, 
					EXTRACT(YEAR FROM  data_a) AS anno_a,
				CONCAT(EXTRACT(YEAR FROM  data_da),'_',EXTRACT(YEAR FROM  data_a)) AS anno_esercizio	
				FROM mod_esercizi ";
		$row =  $this->db->query($sql)->result_array();	
		return $row;
	}


	public function getNomeEsercizio($id_esercizio){
		$sql ="SELECT nome FROM 
				mod_esercizi WHERE id = ".$id_esercizio;
		$row =  $this->db->query($sql)->result_array();	
		
		return $row[0]['nome'];
	}

	
}