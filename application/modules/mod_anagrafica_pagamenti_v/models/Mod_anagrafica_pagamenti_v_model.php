<?php
if (!defined('BASEPATH')){
	exit('No direct script access allowed');
}

require APPPATH . '/libraries/BaseModel.php';

class Mod_anagrafica_pagamenti_v_model extends BaseModel
{

	public function __construct(){
		parent::__construct();
		$this->table = 'mod_anagrafica_pagamenti_v';
		$this->id = 'id';
		$this->pkIdName = 'id';
		$this->mod_name = 'mod_anagrafica_pagamenti_v';
		$this->mod_type = 'crud';


	}


    /**
     * 
     * Ritorna il json usato dalla griglia datatable
     * Adattamento al modulo
     * @param array $searchFilter
     * @return JSON
     */	
	public function json(Array $searchFilter){
		$button = "";   
        $perm_read = "";
        $perm_update = "";
		$global_permissions = $this->getPermissionRole($this->session->userdata('role'));
		foreach($global_permissions as $key => $module_permission){
			if($module_permission->mod_name == $this->mod_name){
				$perm_read = $module_permission->perm_read;
				$perm_update = $module_permission->perm_update;		
				break;
			}
		}


		$this->datatables->select("id AS id,
								esercizio_id,
								affiliazione_id,
								anagrafica AS mod_anagrafica_pagamenti_v_anagrafica,
								esercizio AS mod_anagrafica_pagamenti_v_esercizio,
								affiliazione AS mod_anagrafica_pagamenti_v_affiliazione");
		$this->datatables->from("mod_anagrafica_pagamenti_v");	 

		$button = "";   
		/*
		if($perm_read == 'Y'){
			$button .= "<a onclick='readAjax(\"$this->mod_name\",\"$1\")' class='btn btn-sm btn-default' title='Visualizza'><i class='fa fa-eye'></i></a><br>";
		}
		*/
		if($perm_update == 'Y'){
			$button .= "<a onclick='editAjax(\"$this->mod_name\",\"$1\")' class='btn btn-sm btn-info' title='Modifica'><i class='fa fa-pencil'></i></a><br>";
		}  


		$this->datatables->add_column('action', $button, 'id');
		$this->datatables->add_column('ids', '<input type="checkbox" id="check_id" name="check_id" value="$1" onchange="verificaNrCheckBoxSelezionati(\'check_id\',\'btDeleteMass\')" />', $this->id);             	

        return $this->datatables->generate();

	}



	public function populateAffiliazioni($esercId){
		
		$sql = "SELECT mod_affiliazioni.id,mod_affiliazioni.nome
			FROM mod_affiliazioni";
		if(($esercId != "")){
			$sql .=" WHERE mod_affiliazioni.fk_esercizio = ".$esercId;	
		}	
		
		//echo $sql;die();		
		$row =  $this->db->query($sql)->result_array();	

		return $row;
	}	

	
	public function getEsercizioCorrente(){
		$sql = "SELECT id 
				FROM mod_esercizi 
				where '".date('Y')."-".date("m")."-".date("d")."' 
					BETWEEN mod_esercizi.data_da and mod_esercizi.data_a 
				order by data_a desc;";
		$row =  $this->db->query($sql)->result_array();	
 
		return $row[0]['id'];	
	}	


	public function getEsercizioCorrenteByCorso($idCorso){
		$sql = "SELECT mod_esercizi.id 
				FROM mod_esercizi 
				INNER JOIN mod_affiliazioni
					ON mod_affiliazioni.fk_esercizio = mod_esercizi.id
				INNER JOIN mod_corsi
					ON mod_corsi.fk_affiliazione = mod_affiliazioni.id
					AND mod_corsi.id = $idCorso";
		$row =  $this->db->query($sql)->result_array();	
 
		return $row[0]['id'];	
	}	


	public function getNomeAffiliazione($id){
		$sql = "SELECT nome 
				FROM mod_affiliazioni 
				where id = ".$id;
		$row =  $this->db->query($sql)->result_array();	
		//print'<pre>';print_r($row);
		return $row[0]['nome'];	
	}



	public function getMasterDetail_listaCorsiPerAllievo($idAnagrafica, $idEsercizio){
		$sql = "SELECT mod_corsi.id,
				mod_corsi.nome
				FROM mod_corsi
				INNER JOIN mod_affiliazioni
					ON mod_affiliazioni.id = mod_corsi.fk_affiliazione
				INNER JOIN mod_esercizi
					ON mod_esercizi.id = mod_affiliazioni.fk_esercizio
					AND mod_esercizi.id = $idEsercizio
				INNER JOIN _mod_anagrafica_corsi
					ON  _mod_anagrafica_corsi.fk_corso = mod_corsi.id
					AND _mod_anagrafica_corsi.fk_anagrafica = $idAnagrafica";
 
		//echo $sql;
		//die();		
		return $this->db->query($sql)->result_array();		
	}


	public function getPagamentiAllievoPerCorso($idAnagrafica,$idCorso){

		$sql ="SELECT _mod_pagamenti_ricevuti.* ,
				CONCAT(_mod_pagamenti_ricevuti.anno,'-',
				CASE
					WHEN _mod_pagamenti_ricevuti.mese = 'GENNAIO' THEN '01'
					WHEN _mod_pagamenti_ricevuti.mese = 'FEBBRAIO' THEN '02'
					WHEN _mod_pagamenti_ricevuti.mese = 'MARZO' THEN '03'
					WHEN _mod_pagamenti_ricevuti.mese = 'APRILE' THEN '04'
					WHEN _mod_pagamenti_ricevuti.mese = 'MAGGIO' THEN '05'
					WHEN _mod_pagamenti_ricevuti.mese = 'GIUGNO' THEN '06'
					WHEN _mod_pagamenti_ricevuti.mese = 'LUGLIO' THEN '07'
					WHEN _mod_pagamenti_ricevuti.mese = 'AGOSTO' THEN '08'
					WHEN _mod_pagamenti_ricevuti.mese = 'SETTEMBRE' THEN '09'
					WHEN _mod_pagamenti_ricevuti.mese = 'OTTOBRE' THEN '10'
					WHEN _mod_pagamenti_ricevuti.mese = 'NOVEMBRE' THEN '11'
					WHEN _mod_pagamenti_ricevuti.mese = 'DICEMBRE' THEN '12'
				END,'-01')AS mese_anno,
				mod_anagrafica.nome,
				mod_anagrafica.cognome,
				mod_anagrafica.codfiscale,
				mod_anagrafica.email,
				mod_corsi.nome as corso,
				mod_corsi.tipologia_corso,
				mod_corsi.importo_mensile,
				mod_tipopagamento.nome as tipo_pagamento,
				mod_causali_pagamento.nome as causale_pagamento,
				_mod_anagrafica_corsi.data_iscrizione
			FROM _mod_pagamenti_ricevuti
			INNER JOIN mod_anagrafica
				ON mod_anagrafica.id = _mod_pagamenti_ricevuti.fk_anagrafica
				AND fk_anagrafica = $idAnagrafica
			INNER JOIN mod_corsi
				ON mod_corsi.id = _mod_pagamenti_ricevuti.fk_corso
				AND fk_corso = $idCorso		
			INNER JOIN _mod_anagrafica_corsi
				ON mod_anagrafica.id = _mod_anagrafica_corsi.fk_anagrafica
				AND mod_corsi.id = _mod_anagrafica_corsi.fk_corso	
			INNER JOIN mod_tipopagamento
				ON mod_tipopagamento.id = _mod_pagamenti_ricevuti.fk_tipopagamento	
			INNER JOIN mod_causali_pagamento
				ON mod_causali_pagamento.id = _mod_pagamenti_ricevuti.fk_causale_pagamento						
			WHERE 1=1
			AND mod_tipopagamento.nome <> 'TESSERAMENTO'
			/*AND (_mod_pagamenti_ricevuti.saldo = 'SI' or _mod_pagamenti_ricevuti.saldo = 'NO' or _mod_pagamenti_ricevuti.saldo = 'NON PAGHERA')*/
			
			ORDER BY mese_anno ASC";
		return $this->db->query($sql)->result_array();	
	}


	public function getPagamentiUnificati($idAnagrafica,$idEsercizio){
		$sql ="SELECT _mod_pagamenti_ricevuti_unificati.* ,
					_mod_pagamenti_ricevuti.mese,
					_mod_pagamenti_ricevuti.anno,
				mod_anagrafica.nome,
				mod_anagrafica.cognome,
				mod_anagrafica.codfiscale,
				mod_anagrafica.email,
				mod_corsi.nome as corso,
				mod_corsi.tipologia_corso,
				mod_corsi.importo_mensile,
				mod_tipopagamento.nome AS tipo_pagamento,
				mod_causali_pagamento.nome as causale_pagamento
			FROM _mod_pagamenti_ricevuti_unificati
			INNER JOIN _mod_pagamenti_ricevuti
				ON _mod_pagamenti_ricevuti.id = _mod_pagamenti_ricevuti_unificati.fk_mod_pagamenti_ricevuti	
			INNER JOIN mod_anagrafica
				ON mod_anagrafica.id = _mod_pagamenti_ricevuti.fk_anagrafica
				AND mod_anagrafica.id = $idAnagrafica
			INNER JOIN mod_corsi
				ON mod_corsi.id = _mod_pagamenti_ricevuti.fk_corso	
			INNER JOIN mod_affiliazioni
				ON mod_affiliazioni.id = mod_corsi.fk_affiliazione	
			INNER JOIN mod_esercizi
				ON mod_esercizi.id = mod_affiliazioni.fk_esercizio	
				AND mod_esercizi.id = $idEsercizio
			INNER JOIN mod_causali_pagamento
				ON mod_causali_pagamento.id = _mod_pagamenti_ricevuti_unificati.fk_causale_pagamento	
			INNER JOIN mod_tipopagamento
				ON mod_tipopagamento.id = _mod_pagamenti_ricevuti_unificati.fk_tipopagamento";
		return $this->db->query($sql)->result_array();	
	}


	public function getPagamentiUnificatiStampa($idPagamentoUnif){
		$sql ="SELECT _mod_pagamenti_ricevuti_unificati.* ,
					_mod_pagamenti_ricevuti.mese,
					_mod_pagamenti_ricevuti.anno,
				mod_anagrafica.nome,
				mod_anagrafica.cognome,
				mod_anagrafica.codfiscale,
				mod_anagrafica.email,
				mod_corsi.nome as corso,
				mod_corsi.tipologia_corso,
				mod_corsi.importo_mensile,
				mod_tipopagamento.nome AS tipo_pagamento,
				mod_causali_pagamento.nome as causale_pagamento,
				_mod_magazzino_tessere_lista_tessere.codice_tessera as tessera_associativa,
				YEAR(mod_esercizi.data_da) as anno_da,
				YEAR(mod_esercizi.data_a) as anno_a
			FROM _mod_pagamenti_ricevuti_unificati
			INNER JOIN _mod_pagamenti_ricevuti
				ON _mod_pagamenti_ricevuti.id = _mod_pagamenti_ricevuti_unificati.fk_mod_pagamenti_ricevuti	
			INNER JOIN mod_anagrafica
				ON mod_anagrafica.id = _mod_pagamenti_ricevuti.fk_anagrafica
			INNER JOIN mod_corsi
				ON mod_corsi.id = _mod_pagamenti_ricevuti.fk_corso	
			INNER JOIN mod_affiliazioni
				ON mod_affiliazioni.id = mod_corsi.fk_affiliazione	
			INNER JOIN mod_esercizi
				ON mod_esercizi.id = mod_affiliazioni.fk_esercizio	
			INNER JOIN mod_tesseramenti
				ON mod_esercizi.id = mod_tesseramenti.fk_esercizio				
			INNER JOIN _mod_magazzino_tessere_lista_tessere
				ON mod_tesseramenti.fk_tessera_associativa = _mod_magazzino_tessere_lista_tessere.id
			INNER JOIN mod_causali_pagamento
				ON mod_causali_pagamento.id = _mod_pagamenti_ricevuti_unificati.fk_causale_pagamento	
			INNER JOIN mod_tipopagamento
				ON mod_tipopagamento.id = _mod_pagamenti_ricevuti_unificati.fk_tipopagamento
			WHERE _mod_pagamenti_ricevuti_unificati.id = ".$idPagamentoUnif;
		$row = $this->db->query($sql)->result_array();
		
		return $row[0];
	}



	public function getPagamentiUnificatiWin($idPagamentiUnificatiWin){
 
		$sql ="SELECT _mod_pagamenti_ricevuti_unificati.* ,
					_mod_pagamenti_ricevuti.mese,
					_mod_pagamenti_ricevuti.anno,
				mod_anagrafica.nome,
				mod_anagrafica.cognome,
				mod_anagrafica.codfiscale,
				mod_anagrafica.email,
				mod_corsi.nome as corso,
				mod_corsi.tipologia_corso,
				mod_corsi.importo_mensile,
				mod_tipopagamento.nome AS tipo_pagamento,
				mod_causali_pagamento.nome as causale_pagamento
			FROM _mod_pagamenti_ricevuti_unificati
			INNER JOIN _mod_pagamenti_ricevuti
				ON _mod_pagamenti_ricevuti.id = _mod_pagamenti_ricevuti_unificati.fk_mod_pagamenti_ricevuti	
			INNER JOIN mod_anagrafica
				ON mod_anagrafica.id = _mod_pagamenti_ricevuti.fk_anagrafica
			INNER JOIN mod_corsi
				ON mod_corsi.id = _mod_pagamenti_ricevuti.fk_corso	
			INNER JOIN mod_affiliazioni
				ON mod_affiliazioni.id = mod_corsi.fk_affiliazione	
			INNER JOIN mod_esercizi
				ON mod_esercizi.id = mod_affiliazioni.fk_esercizio	
			INNER JOIN mod_causali_pagamento
				ON mod_causali_pagamento.id = _mod_pagamenti_ricevuti_unificati.fk_causale_pagamento	
			INNER JOIN mod_tipopagamento
				ON mod_tipopagamento.id = _mod_pagamenti_ricevuti_unificati.fk_tipopagamento
			WHERE _mod_pagamenti_ricevuti_unificati.id = $idPagamentiUnificatiWin";
 
		return $this->db->query($sql)->result_array();	
	}



	public function getCorsiDaPagareByAnagraficaEsercizio($idAnagrafica,$idEsercizio){
		$sql ="SELECT 
				_mod_pagamenti_ricevuti.id AS fk_mod_pagamenti_ricevuti,
				CONCAT(_mod_pagamenti_ricevuti.anno,'-',_mod_pagamenti_ricevuti.mese)AS mese_anno,
				mod_corsi.nome as corso,
				mod_corsi.tipologia_corso,
				mod_corsi.importo_mensile,				
				mod_anagrafica.nome,
				mod_anagrafica.cognome,
				mod_anagrafica.codfiscale,
				mod_anagrafica.email,
				mod_tipopagamento.nome as tipo_pagamento,
				_mod_anagrafica_corsi.data_iscrizione
			FROM _mod_pagamenti_ricevuti
			INNER JOIN mod_anagrafica
				ON mod_anagrafica.id = _mod_pagamenti_ricevuti.fk_anagrafica
				AND mod_anagrafica.id  = $idAnagrafica
			INNER JOIN mod_tesseramenti
				ON mod_tesseramenti.fk_anagrafica = mod_anagrafica.id 
				AND mod_anagrafica.id  = $idAnagrafica				
			INNER JOIN mod_esercizi
				ON mod_tesseramenti.fk_esercizio = mod_esercizi.id 
				AND mod_esercizi.id  = $idEsercizio
			INNER JOIN mod_corsi
				ON mod_corsi.id = _mod_pagamenti_ricevuti.fk_corso	
			INNER JOIN _mod_anagrafica_corsi
				ON mod_anagrafica.id = _mod_anagrafica_corsi.fk_anagrafica
				AND mod_corsi.id = _mod_anagrafica_corsi.fk_corso	
			INNER JOIN mod_tipopagamento
				ON mod_tipopagamento.id = _mod_pagamenti_ricevuti.fk_tipopagamento						
			WHERE 1=1
			AND mod_tipopagamento.nome <> 'TESSERAMENTO'
			AND (_mod_pagamenti_ricevuti.saldo = 'SI' or _mod_pagamenti_ricevuti.saldo = 'NO' or _mod_pagamenti_ricevuti.saldo = 'NON PAGHERA')
			
			ORDER BY mese_anno ASC";
		return $this->db->query($sql)->result_array();	
	}

	public function getPagamentiTesseramenti($idAnagrafica,$idEsercizio){

		$sql ="SELECT _mod_pagamenti_tesseramenti.*,
					mod_tipopagamento.nome AS tipo_pagamento,
					mod_anagrafica.nome,
					mod_anagrafica.cognome,
					mod_anagrafica.codfiscale,
					mod_anagrafica.email
				FROM  _mod_pagamenti_tesseramenti
				INNER JOIN mod_tesseramenti
					ON _mod_pagamenti_tesseramenti.fk_tesseramento = mod_tesseramenti.id
					AND mod_tesseramenti.fk_anagrafica = $idAnagrafica
					AND mod_tesseramenti.fk_esercizio = $idEsercizio
				INNER JOIN mod_anagrafica
					ON mod_anagrafica.id = mod_tesseramenti.fk_anagrafica
				INNER JOIN mod_tipopagamento
					ON mod_tipopagamento.id = _mod_pagamenti_tesseramenti.fk_tipopagamento";
		return $this->db->query($sql)->result_array();	
	}


	public function getTesseramenti($idTesseramento){

		$sql ="SELECT _mod_pagamenti_tesseramenti.*,
					mod_tipopagamento.nome AS tipo_pagamento,
					mod_anagrafica.nome,
					mod_anagrafica.cognome,
					mod_anagrafica.codfiscale,
					mod_anagrafica.email,
					_mod_magazzino_tessere_lista_tessere.codice_tessera as tessera_associativa,
					YEAR(mod_esercizi.data_da) as anno_da,
					YEAR(mod_esercizi.data_a) as anno_a
				FROM  _mod_pagamenti_tesseramenti
				INNER JOIN mod_tesseramenti
					ON _mod_pagamenti_tesseramenti.fk_tesseramento = mod_tesseramenti.id
					AND _mod_pagamenti_tesseramenti.id = $idTesseramento	
				INNER JOIN mod_esercizi
					ON mod_esercizi.id = mod_tesseramenti.fk_esercizio
				INNER JOIN _mod_magazzino_tessere_lista_tessere
					ON mod_tesseramenti.fk_tessera_associativa = _mod_magazzino_tessere_lista_tessere.id	

				INNER JOIN mod_anagrafica
					ON mod_anagrafica.id = mod_tesseramenti.fk_anagrafica
				INNER JOIN mod_tipopagamento
					ON mod_tipopagamento.id = _mod_pagamenti_tesseramenti.fk_tipopagamento";
		$row = $this->db->query($sql)->result_array();	

		return $row[0];
	}



	public function getPagamentiDatiTesseramentiWin($idAnagrafica,$idEsercizio){

		$sql ="SELECT mod_tesseramenti.id as id_tesseramento,
						_mod_pagamenti_tesseramenti.*,
						mod_tipopagamento.nome AS tipo_pagamento,
						mod_anagrafica.nome,
						mod_anagrafica.cognome,
						mod_anagrafica.codfiscale,
						mod_anagrafica.email
				FROM   mod_tesseramenti
				INNER JOIN mod_anagrafica
					ON mod_anagrafica.id = mod_tesseramenti.fk_anagrafica
				LEFT JOIN _mod_pagamenti_tesseramenti
					ON _mod_pagamenti_tesseramenti.fk_tesseramento = mod_tesseramenti.id
				LEFT JOIN mod_tipopagamento
					ON mod_tipopagamento.id = _mod_pagamenti_tesseramenti.fk_tipopagamento 
				WHERE  mod_tesseramenti.fk_anagrafica = $idAnagrafica
				AND mod_tesseramenti.fk_esercizio = $idEsercizio";
		//echo $sql;
		$row = $this->db->query($sql)->result_array();	

		return $row[0];
	}




	
	public function getListaMesiDaPagare($idAnagrafica,$idEsercizio){
		$mesi = array();
		$mesi[] = 'GENNAIO';
		$mesi[] = 'FEBBRAIO';
		$mesi[] = 'MARZO';
		$mesi[] = 'APRILE';
		$mesi[] = 'MAGGIO';
		$mesi[] = 'GIUGNO';
		$mesi[] = 'LUGLIO';
		$mesi[] = 'AGOSTO';
		$mesi[] = 'SETTEMBRE';
		$mesi[] = 'OTTOBRE';
		$mesi[] = 'NOVEMBRE';
		$mesi[] = 'DICEMBRE';

		return $mesi;
	}


	public function getListaAnniDaPagare($idAnagrafica,$idEsercizio){
		$anni = [];
 
		for($anno = 2010; $anno <= 2050; $anno++){
			$anni[] = $anno;
		}

		return $anni;
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
					mod_corsi.nome as corso,
					mod_corsi.dicitura_corso
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
		$row = $this->db->query($sql)->result_array();
 
		
		return $row[0];
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



	public function getListaMesiAnniDaPagarePerCorso($fk_corso){
		$return = [
			"anno" => [],
			"mese" => [],
			"mese_ita" => [],
		];
		
		$sql = "SELECT data_da,data_a 
					FROM mod_corsi
				WHERE id = ".$fk_corso;
		$row =  $this->db->query($sql)->result_array();	
		$data_da = $row[0]['data_da'];
		$data_a = $row[0]['data_a'];
		$rowDate = $this->utilities->getYearsMonthsFromRangeDate($data_da, $data_a);
 
		$objData_da    = (new DateTime($data_da))->modify('first day of this month');
		$objData_a    = (new DateTime($data_a))->modify('first day of this month');
		$anno_da = $objData_da->format("Y");
		$anno_a =  $objData_a->format("Y");
		$mese_da = $objData_da->format("m");
		$mese_a =  $objData_a->format("m");		
		for($anno = 2010; $anno <= 2050; $anno++){
			if(($anno == $anno_da) || ($anno == $anno_a)){
				$return['anno'][] = $anno;
			}
		}

		for($mese = 1; $mese <= 12; $mese++){
			if(((int)$mese_da <= $mese) || ((int)$mese_a <= $mese)){
				$return['mese'][] = $mese;
			}
		}

		foreach($return['mese'] as $k => $v){
			$return['mese_ita'][] = $this->utilities->getItalianMonthFromNumber($v);
		}


		$return['anno_mese'] = $rowDate;

		foreach($rowDate as $k => $v){
			$annoMeseArray = explode("-", $v);
			$mese_ita = $this->utilities->getItalianMonthFromNumber($annoMeseArray[1]);
			$return['mese_anno_ita'][] = $mese_ita."-".$annoMeseArray[0];
		}

		

		//print'<pre>';print_r($return);
		return $return;
	}		


	public function getCorsiPerAllievo($idAnagrafica, $idEsercizio){
		$sql = "SELECT mod_corsi.id as id_corso,
					mod_corsi.nome as nome_corso,
					mod_corsi.importo_mensile as importo,
					_mod_pagamenti_ricevuti_sconti.importo_scontato
				FROM mod_corsi
				INNER JOIN mod_affiliazioni
					ON mod_corsi.fk_affiliazione = mod_affiliazioni.id
					AND mod_affiliazioni.fk_esercizio = $idEsercizio
				INNER JOIN mod_tesseramenti
					ON mod_affiliazioni.id = mod_tesseramenti.fk_affiliazione
					AND mod_tesseramenti.fk_esercizio = $idEsercizio
					AND mod_tesseramenti.fk_anagrafica = $idAnagrafica
				INNER JOIN _mod_anagrafica_corsi
					ON _mod_anagrafica_corsi.fk_corso = mod_corsi.id
					AND _mod_anagrafica_corsi.fk_tesseramento = mod_tesseramenti.id
					AND _mod_anagrafica_corsi.fk_anagrafica = $idAnagrafica
				LEFT JOIN _mod_pagamenti_ricevuti_sconti
					ON _mod_pagamenti_ricevuti_sconti.fk_corso = mod_corsi.id
					AND _mod_pagamenti_ricevuti_sconti.fk_anagrafica = $idAnagrafica";
		//echo $sql."<br><br>";
		$row = $this->db->query($sql)->result_array();

		return $row;
	} 


	public function  insertImportoScontato($data){
        //CANCELLO IL PREGRESSO
		$this->db->where("fk_anagrafica", $data['fk_anagrafica']);
		$this->db->where("fk_corso", $data['fk_corso']);
        $this->db->delete("_mod_pagamenti_ricevuti_sconti");

		if($data['importo_scontato'] != ""){
			//INSERISCO
			unset($data['importo']);
			$importo = $data['importo_scontato'];
			$this->db->insert("_mod_pagamenti_ricevuti_sconti", $data);			
		} else {
			$importo = $data['importo'];
		}

		//AGGIORNO SUI PAGAMENTI ANCORA DA RICEVERE
		$fk_anagrafica = $data['fk_anagrafica'];
		$fk_corso = $data['fk_corso'];
		
		$sql = "UPDATE _mod_pagamenti_ricevuti 
				SET importo = $importo
			WHERE fk_anagrafica = $fk_anagrafica
			AND fk_corso = $fk_corso
			AND datapagamento IS NULL 
			AND SALDO <> 'NON PAGHERA' ";
		//echo $sql."<br><br>";
		$this->db->query($sql);		

	}	


	public function getTipoCorso($id){
		$sql = "SELECT tipologia_corso
				FROM mod_corsi
 				WHERE id = $id";
		//echo $sql;		
		$row =  $this->db->query($sql)->result_array();	
		$tipologia_corso = $row[0]['tipologia_corso'];

		return $tipologia_corso;
	}
		

}