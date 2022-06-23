<?php
if (!defined('BASEPATH')){
	exit('No direct script access allowed');
}

require APPPATH . '/libraries/BaseModel.php';

class Mod_corsi_model extends BaseModel
{

	public function __construct(){
		parent::__construct();
		$this->table = 'mod_corsi';
		$this->id = 'id';
		$this->mod_name = 'mod_corsi';
		$this->mod_type = 'crud';


		//NOTE:NELLA FUNZIONE 'setFieldArrayGrid' INDICARE NEL VETTORE CHE SI COLLEGA ALLA TABELLA REFERENZIATA
		//ALLA CHIAVE 'NOME', IL NOMINATIVO DEL CAMPO COLLEGATO
		$this->setFieldArrayGrid('data_a', FIELD_DATE);
		$this->setFieldArrayGrid('data_da', FIELD_DATE);
		$this->setFieldArrayGrid('nome', FIELD_STRING);
		$this->setFieldArrayGrid('fk_affiliazione',FIELD_NUMERIC,'mod_affiliazioni',array("id" => 'id', "nome" => 'nome'));
		$this->setFieldArrayGrid('fk_disciplina',FIELD_NUMERIC,'mod_discipline',array("id" => 'id', "nome" => 'nome'));
		$this->setFieldArrayGrid('tipologia_corso', FIELD_STRING);
		$this->setFieldArrayGrid('importo_mensile', FIELD_FLOAT);
		$this->setFieldArrayGrid('id', FIELD_NUMERIC);

		//ESEMPIO DI TABELLA REFERENZIATA CHE NON HA IL CAMPO 'NOME'. QUI INDICHIAMO AL PROGRAMMA QUALE E' IL CAMPO DA USARE COME CAMPO 'NOME'
		//P.S.QUESTA OPERAZIONE E' POSSIBILE FARLA ANCHE NEL METODO 'setFieldArrayGrid'
		//$this->arrayColumnsReferenced['mod_sport']['nome'] = "sport"; 

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

    /**
     * 
     * Ritorna il json usato dalla griglia datatable
     * Adattamento al modulo
     * @param array $searchFilter
     * @return JSON
     */	
	public function json(Array $searchFilter){
        $fields = array();
        $fieldsInfoDetails = array();
		if($_REQUEST['fast_ins_corsi'] == 'Y'){	
			$_SESSION['fast_ins_corsi'] = 'Y';			
		} else {
			$_SESSION['fast_ins_corsi'] = 'N';
		}

		$fieldsInfoDetails["mod_corsi_nome"]['table'] = "mod_corsi";
		$fieldsInfoDetails["mod_corsi_nome"]['field_name'] = "nome";
		$fieldsInfoDetails["mod_corsi_data_da"]['table'] = "mod_corsi";
		$fieldsInfoDetails["mod_corsi_data_da"]['field_name'] = "data_da";
		$fieldsInfoDetails["mod_corsi_data_a"]['table'] = "mod_corsi";
		$fieldsInfoDetails["mod_corsi_data_a"]['field_name'] = "data_a";
		$fieldsInfoDetails["mod_esercizi_id"]['table'] = "mod_esercizi";
		$fieldsInfoDetails["mod_esercizi_id"]['field_name'] = "id";

		$fieldsInfoDetails["affiliazione"]['table'] = "mod_corsi";
		$fieldsInfoDetails["affiliazione"]['field_name'] = "fk_affiliazione";
		
		
		$button = "";   
        $perm_read = "";
        $perm_write = "";
        $perm_update = "";
        $perm_delete = "";
		$global_permissions = $this->getPermissionRole($this->session->userdata('role'));
		foreach($global_permissions as $key => $module_permission){
			if($module_permission->mod_name == $this->mod_name){
				$perm_read = $module_permission->perm_read;
				$perm_write = $module_permission->perm_write;
				$perm_update = $module_permission->perm_update;
				$perm_delete = $module_permission->perm_delete;				
				break;
			}
		}

		$this->datatables->select("mod_corsi.id as id,
								mod_corsi.nome AS mod_corsi_nome,
								DATE_FORMAT(mod_corsi.data_da,'%d/%m/%Y') AS mod_corsi_data_da,
								DATE_FORMAT(mod_corsi.data_a,'%d/%m/%Y') AS mod_corsi_data_a,
								mod_corsi.fk_affiliazione as affiliazione,
								mod_esercizi.id as mod_esercizi_id ");
		$this->datatables->from("mod_corsi");
		$this->datatables->join("mod_affiliazioni","mod_corsi.fk_affiliazione = mod_affiliazioni.id","inner");
		$this->datatables->join("mod_esercizi","mod_affiliazioni.fk_esercizio = mod_esercizi.id","inner");

		$button = "";   
		if($perm_read == 'Y'){
			$button .= "<a onclick='readAjax(\"$this->mod_name\",$1)' class='btn btn-sm btn-default' title='Visualizza'><i class='fa fa-eye'></i></a><br>";
		}
		if($perm_update == 'Y'){
			$button .= "<a onclick='editAjax(\"$this->mod_name\",$1)' class='btn btn-sm btn-info' title='Modifica'><i class='fa fa-pencil'></i></a><br>";
		}  
		//print'<pre>';print_r($_SESSION); 
		if(($perm_delete == 'Y') && ($_SESSION['fast_ins_corsi'] == 'N')){
			$button .= "<a onclick='deleteEntry(\"$1\", \"".$this->mod_name."\",\"delete\")' class='btn btn-sm btn-danger deleteItem' title='Cancella'><i class='fa fa-trash'></i></a>";        
		}  

		$this->datatables->add_column('action', $button, 'id');
		$this->datatables->add_column('ids', '<input type="checkbox" id="check_id" name="check_id" value="$1" onchange="verificaNrCheckBoxSelezionati(\'check_id\',\'btDeleteMass\')" />', $this->id);             	

        //print'<pre>';print_r($fieldsInfoDetails);
        foreach ($searchFilter as $key => $value) {
	 
            if ($value['value'] != '') {
                $value['field'] = $fieldsInfoDetails[$value['field']]['table'].".".$fieldsInfoDetails[$value['field']]['field_name'];
                $this->datatables->like($value['field'], $value['value']);
            }       
        }

        return $this->datatables->generate();

	}

	/**
	* Funzione caricamento della master details, tabella _mod_anagrafica_corsi
	* @param mixed $id
	* @param string $isAjax
	* @return string
	**/
	public function getMasterDetail_mod_anagrafica_corsi($id, $isAjax = 'FALSE'){
		$sql ="SELECT _mod_anagrafica_corsi.* 
				,CONCAT(mod_anagrafica.nome, \" \", mod_anagrafica.cognome, \" - \", mod_anagrafica.codfiscale) AS mod_anagrafica_nome
				,mod_corsi.nome AS mod_corsi_nome
			FROM _mod_anagrafica_corsi
			 INNER JOIN mod_anagrafica
							ON _mod_anagrafica_corsi.fk_anagrafica = mod_anagrafica.id
			 INNER JOIN mod_corsi
							ON _mod_anagrafica_corsi.fk_corso = mod_corsi.id
			WHERE _mod_anagrafica_corsi.fk_corso = ".$id;
		$row =  $this->db->query($sql)->result_array();	
		return $row;
	}


	/**
	* Funzione caricamento della master details, tabella _mod_corsi_giorni_orari
	* @param mixed $id
	* @param string $isAjax
	* @return string
	**/
	public function getMasterDetail_mod_corsi_giorni_orari($id, $isAjax = 'FALSE'){
		$sql ="SELECT _mod_corsi_giorni_orari.* 
				,mod_corsi.nome AS mod_corsi_nome
			FROM _mod_corsi_giorni_orari
			 INNER JOIN mod_corsi
							ON _mod_corsi_giorni_orari.fk_corso = mod_corsi.id
			WHERE _mod_corsi_giorni_orari.fk_corso = ".$id;
		$row =  $this->db->query($sql)->result_array();	
		return $row;
	}


	/**
	* Funzione caricamento della master details, tabella _mod_corsi_insegnanti
	* @param mixed $id
	* @param string $isAjax
	* @return string
	**/
	public function getMasterDetail_mod_corsi_insegnanti($id, $isAjax = 'FALSE'){
		$sql ="SELECT _mod_corsi_insegnanti.* 
				,mod_corsi.nome AS mod_corsi_nome
				,CONCAT(mod_anagrafica.nome,\" \",mod_anagrafica.cognome,\" - \",mod_anagrafica.codfiscale) AS mod_anagrafica_nome
			FROM _mod_corsi_insegnanti
			 INNER JOIN mod_corsi
							ON _mod_corsi_insegnanti.fk_corso = mod_corsi.id
			 INNER JOIN mod_anagrafica
							ON _mod_corsi_insegnanti.fk_anagrafica = mod_anagrafica.id
			WHERE _mod_corsi_insegnanti.fk_corso = ".$id;
		$row =  $this->db->query($sql)->result_array();	
		return $row;
	}


	/**
	* Funzione caricamento della master details, tabella _mod_corsi_iscrizioni
	* @param mixed $id
	* @param string $isAjax
	* @return string
	**/
	public function getMasterDetail_mod_corsi_iscrizioni($id, $isAjax = 'FALSE'){
		$sql ="SELECT _mod_corsi_iscrizioni.* 
				,mod_corsi.nome AS mod_corsi_nome
				,mod_anagrafica.nome AS mod_anagrafica_nome
			FROM _mod_corsi_iscrizioni
			 INNER JOIN mod_corsi
							ON _mod_corsi_iscrizioni.fk_corso = mod_corsi.id
			 INNER JOIN mod_anagrafica
							ON _mod_corsi_iscrizioni.fk_anagrafica = mod_anagrafica.id
			WHERE _mod_corsi_iscrizioni.fk_corso = ".$id;
		$row =  $this->db->query($sql)->result_array();	
		return $row;
	}


	/**
	* Funzione caricamento della master details, tabella _mod_corsi_sale_calendario
	* @param mixed $id
	* @param string $isAjax
	* @return string
	**/
	public function getMasterDetail_mod_corsi_sale_calendario($id, $isAjax = 'FALSE'){
		$sql ="SELECT _mod_corsi_sale_calendario.* 
				,mod_corsi.nome AS mod_corsi_nome
				,mod_sale.nome AS mod_sale_nome
			FROM _mod_corsi_sale_calendario
			 INNER JOIN mod_corsi
							ON _mod_corsi_sale_calendario.fk_corso = mod_corsi.id
			 INNER JOIN mod_sale
							ON _mod_corsi_sale_calendario.fk_sala = mod_sale.id
			WHERE _mod_corsi_sale_calendario.fk_corso = ".$id;
		$row =  $this->db->query($sql)->result_array();	
		return $row;
	}


	/**
	* Funzione caricamento delle liste per inserimento multiplo master details, tabella _mod_anagrafica_corsi
	* @param mixed $id
	* @param string $isAjax
	* @return string
	**/
	public function getList_mod_anagrafica_corsi($id, $isAjax = 'FALSE'){
		$sql ="SELECT _mod_anagrafica_corsi.* 
			,mod_anagrafica.nome AS mod_anagrafica_nome
			,mod_corsi.nome AS mod_corsi_nome
					FROM _mod_anagrafica_corsi
				 INNER JOIN mod_anagrafica
							ON _mod_anagrafica_corsi.fk_anagrafica = mod_anagrafica.id
				 INNER JOIN mod_corsi
							ON _mod_anagrafica_corsi.fk_corso = mod_corsi.id
			WHERE _mod_anagrafica_corsi.fk_corso = ".$id;
		$row =  $this->db->query($sql)->result_array();	
		return $row;
	}


	/**
	* Funzione caricamento delle liste per inserimento multiplo master details, tabella _mod_corsi_giorni_orari
	* @param mixed $id
	* @param string $isAjax
	* @return string
	**/
	public function getList_mod_corsi_giorni_orari($id, $isAjax = 'FALSE'){
		$sql ="SELECT _mod_corsi_giorni_orari.* 
			,mod_corsi.nome AS mod_corsi_nome
					FROM _mod_corsi_giorni_orari
				 INNER JOIN mod_corsi
							ON _mod_corsi_giorni_orari.fk_corso = mod_corsi.id
			WHERE _mod_corsi_giorni_orari.fk_corso = ".$id;
		$row =  $this->db->query($sql)->result_array();	
		return $row;
	}


	/**
	* Funzione caricamento delle liste per inserimento multiplo master details, tabella _mod_corsi_insegnanti
	* @param mixed $id
	* @param string $isAjax
	* @return string
	**/
	public function getList_mod_corsi_insegnanti($id, $isAjax = 'FALSE'){
		$sql ="SELECT _mod_corsi_insegnanti.* 
			,mod_corsi.nome AS mod_corsi_nome
			,mod_anagrafica.nome AS mod_anagrafica_nome
					FROM _mod_corsi_insegnanti
				 INNER JOIN mod_corsi
							ON _mod_corsi_insegnanti.fk_corso = mod_corsi.id
				 INNER JOIN mod_anagrafica
							ON _mod_corsi_insegnanti.fk_anagrafica = mod_anagrafica.id
			WHERE _mod_corsi_insegnanti.fk_corso = ".$id;
		$row =  $this->db->query($sql)->result_array();	
		return $row;
	}


	/**
	* Funzione caricamento delle liste per inserimento multiplo master details, tabella _mod_corsi_iscrizioni
	* @param mixed $id
	* @param string $isAjax
	* @return string
	**/
	public function getList_mod_corsi_iscrizioni($id, $isAjax = 'FALSE'){
		$sql ="SELECT _mod_corsi_iscrizioni.* 
			,mod_corsi.nome AS mod_corsi_nome
			,mod_anagrafica.nome AS mod_anagrafica_nome
					FROM _mod_corsi_iscrizioni
				 INNER JOIN mod_corsi
							ON _mod_corsi_iscrizioni.fk_corso = mod_corsi.id
				 INNER JOIN mod_anagrafica
							ON _mod_corsi_iscrizioni.fk_anagrafica = mod_anagrafica.id
			WHERE _mod_corsi_iscrizioni.fk_corso = ".$id;
		$row =  $this->db->query($sql)->result_array();	
		return $row;
	}


	/**
	* Funzione caricamento delle liste per inserimento multiplo master details, tabella _mod_corsi_sale_calendario
	* @param mixed $id
	* @param string $isAjax
	* @return string
	**/
	public function getList_mod_corsi_sale_calendario($id, $isAjax = 'FALSE'){
		$sql ="SELECT _mod_corsi_sale_calendario.* 
			,mod_corsi.nome AS mod_corsi_nome
			,mod_sale.nome AS mod_sale_nome
					FROM _mod_corsi_sale_calendario
				 INNER JOIN mod_corsi
							ON _mod_corsi_sale_calendario.fk_corso = mod_corsi.id
				 INNER JOIN mod_sale
							ON _mod_corsi_sale_calendario.fk_sala = mod_sale.id
			WHERE _mod_corsi_sale_calendario.fk_corso = ".$id;
		$row =  $this->db->query($sql)->result_array();	
		return $row;
	}

	public function getAllInsegnantiNonAssociatiCorso($corsoId){
		$sql ="SELECT * FROM mod_anagrafica WHERE id NOT IN
			(SELECT fk_anagrafica from _mod_corsi_insegnanti
			WHERE fk_corso = $corsoId)
			AND anagrafica_attributo LIKE '%INSEGNANTE%'";
		$row =  $this->db->query($sql)->result_array();	
		return $row;			
	}


	public function getDateEsercizio($fk_affiliazione){
		$sql ="SELECT DATE_FORMAT(data_da,'%d/%m/%Y') as data_da , 
					DATE_FORMAT(data_a,'%d/%m/%Y') as  data_a
				FROM mod_esercizi
				INNER JOIN mod_affiliazioni 
					ON mod_affiliazioni.fk_esercizio = mod_esercizi.id
					AND mod_affiliazioni.id = ".$fk_affiliazione;
		$row =  $this->db->query($sql)->result_array();	
		return $row;
	}

	public function getAffiliazioneByCorso($id_corso){
		$sql ="SELECT fk_affiliazione FROM mod_corsi WHERE id = ".$id_corso;
		$row =  $this->db->query($sql)->result_array();	
		$fk_affiliazione = $row[0]['fk_affiliazione'];

		return $fk_affiliazione;
	}



	public function getTesseramento($fk_anagrafica,$fk_corso){
		$sql ="SELECT mod_tesseramenti.id AS fk_tesseramento
			FROM mod_tesseramenti
			INNER JOIN  mod_affiliazioni
				ON mod_affiliazioni.id = mod_tesseramenti.fk_affiliazione  
			INNER JOIN mod_corsi 
				ON mod_corsi.fk_affiliazione = mod_affiliazioni.id
				AND mod_corsi.id = $fk_corso
			WHERE mod_tesseramenti.fk_anagrafica = $fk_anagrafica";
		//echo $sql;	
		$row =  $this->db->query($sql)->result_array();	
		$fk_tesseramento = $row[0]['fk_tesseramento'];

		return array("fk_tesseramento" => $fk_tesseramento);
	}
	
	
	/**
	 * @param mixed $idEserc
	 * @param mixed $lista_corsi
     * @return string:ok|ko|warn
	 */
	//public function copyCourses($idEserc,$lista_corsi){
	public function copyCourses($idAffiliaz, $lista_corsi){		
		$return = "ok";
        //VERIFICO SE QUESTI CORSI SIANO GIA PRESENTI
		$sql ="SELECT * FROM mod_corsi WHERE id IN($lista_corsi)";
		$row =  $this->db->query($sql)->result_array();	
		foreach($row as $k => $v){

			$sql ="SELECT count(*)as count_cors
				FROM mod_corsi 				
				WHERE mod_corsi.nome='".$v['nome']."' AND mod_corsi.fk_affiliazione = $idAffiliaz";			
			$rowCount =  $this->db->query($sql)->result_array();				
			if((int)$rowCount[0]['count_cors'] > 0){
				if($return != "ko"){
					$return = "warn";
				}
			} else {
				$data = array("nome" => $v['nome'], "data_da" => $v['data_da'], "data_a" => $v['data_a'], "fk_affiliazione " => $idAffiliaz,
							"fk_disciplina " => $v['fk_disciplina'],"tipologia_corso" => $v['tipologia_corso'],"importo_mensile" => $v['importo_mensile']);
				$res = $this->db->insert($this->table, $data);
				if(!$res){
					$return = "ko";
				}
			}
		}

        return $return;
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



	public function check_date_range_esercizio($idCorso,$dataDa, $dataA){
		$ret = TRUE;
		
		$sql = "SELECT COUNT(*) AS counter_date
				FROM mod_corsi
				INNER JOIN mod_affiliazioni
					ON mod_affiliazioni.id = mod_corsi.fk_affiliazione
				INNER JOIN mod_esercizi
					ON mod_esercizi.id = mod_affiliazioni.fk_esercizio
				WHERE mod_corsi.id = $idCorso
				AND(
					'$dataDa' BETWEEN mod_esercizi.data_da AND mod_esercizi.data_a
					AND '$dataA' BETWEEN mod_esercizi.data_da AND mod_esercizi.data_a
				) ";
		//echo $sql;		
		$row =  $this->db->query($sql)->result_array();	

		if((int)$row[0]['counter_date'] == 0){
			$ret = FALSE;
		}

		return $ret;
	}	


	public function checkDataIscrizione($data_iscrizione, $fk_affiliazione){
		$ret = "ok";
		
		$sql = "SELECT COUNT(*) as counter_dt_iscr
				FROM mod_affiliazioni 
				INNER JOIN mod_esercizi 
					ON mod_esercizi.id = mod_affiliazioni.fk_esercizio 
					AND mod_affiliazioni.id = $fk_affiliazione 
				WHERE STR_TO_DATE(REPLACE('$data_iscrizione','/', '-'), '%d-%m-%Y') 
				BETWEEN mod_esercizi.data_da AND mod_esercizi.data_a;";
		//echo $sql;		
		$row =  $this->db->query($sql)->result_array();	
		if((int)$row[0]['counter_dt_iscr'] == 0){
			$ret = "ko";
		}

		return $ret;
	}
	
	
	public function insertPagamentiDaCorsi(Array $request = NULL, $fk_anagrafica = null, $fk_corso = null, $data_iscrizione = null){
		if(isset($request)){
			$fk_corso	    = $request['fk_corso'];
			$fk_anagrafica   = $request['fk_anagrafica'];
			$data_iscrizione = $request['data_iscrizione'];	
		} else {
			$fk_corso	    = $fk_corso;
			$fk_anagrafica   = $fk_anagrafica;
			$data_iscrizione = $data_iscrizione;	
		}

		//INSERISCO I PAGAMENTI PRECONFEZIONATI SOLO SE DI TIPO ABBONAMENTO
		if($this->getTipoCorso($fk_corso) == 'ABBONAMENTO'){
			if(!$this->utilities->checkValidDateEN($data_iscrizione)){
				$data_iscrizione = $this->utilities->convertToDateEN($request['data_iscrizione']);	
			}	
	
			$rowCorso = $this->get_by_id($fk_corso, "mod_corsi");	
	
			$data_da = $rowCorso->data_da;
			$data_a  = $rowCorso->data_a;
	 
			$data = array();
			$data['fk_anagrafica'] = $fk_anagrafica;
			$data['fk_corso']  = $fk_corso;
			$data['saldo']  = "NO";
			$data['fk_tipopagamento']  = 11;//CONTANTI
			$data['fk_causale_pagamento']  = 5; //PAGAMENTO ALLIEVO ABBONAMENTO
			$data['importo']  = $rowCorso->importo_mensile;
			$data['datapagamento']  =  NULL;
			
			$objDtIscrizione    = (new DateTime($data_iscrizione))->modify('first day of this month');
			$mese_iscrizione = $objDtIscrizione->format("m");
			$anno_iscrizione = $objDtIscrizione->format("Y");		
			$rowDate = $this->utilities->getYearsMonthsFromRangeDate($data_da, $data_a);
	
			//print'<pre>';print_r($rowDate);die();
			foreach($rowDate as $kDate => $vDate){
				$arrayDate = explode("-",$vDate);
				$data['anno']  = $arrayDate[0];	
				$data['mese']  = $this->utilities->getItalianMonthFromNumber($arrayDate[1]);	
		
				if (strtotime($anno_iscrizione."-".$mese_iscrizione."-01") > strtotime($data['anno']."-".$arrayDate[1]."-01")){
					$data['saldo']  = "NON PAGHERA";
				} else {
					$data['saldo']  = "NO";
				} 
		
				$sql = $this->db->set($data)->get_compiled_insert("_mod_pagamenti_ricevuti");
				$sql = str_replace('INSERT INTO', 'INSERT IGNORE INTO', $sql);
				//echo $sql;
				$this->db->query($sql);	
			}
		}


 
 
	}


	public function insertPagamentiFromIscrittiCorsi(){
		$sql = "SELECT * FROM _mod_anagrafica_corsi";
		$row =  $this->db->query($sql)->result_array();	
		foreach($row as $k => $v){
			$this->insertPagamentiDaCorsi(NULL, $v['fk_anagrafica'], $v['fk_corso'], $v['data_iscrizione']);
		}
 
	}


	public function getCountPagamenti($fk_corso, $fk_anagrafica){
		$sql = "SELECT COUNT(*) AS counter_pagamenti
				FROM _mod_pagamenti_ricevuti
 				WHERE fk_corso = $fk_corso AND fk_anagrafica = $fk_anagrafica 
				AND datapagamento IS NOT NULL";
		//echo $sql;		
		$row =  $this->db->query($sql)->result_array();	
		$counter = $row[0]['counter_pagamenti'];

		return $counter;
	}


	public function checkIscrittiCorso($id){
		$sql = "SELECT COUNT(*) AS counter_iscritti
				FROM _mod_anagrafica_corsi
				WHERE fk_corso = $id";
		//echo $sql;		
		$row =  $this->db->query($sql)->result_array();	
		$counter = $row[0]['counter_iscritti'];

		return $counter;
	}


	public function checkPagamentiCorso($id){
		$sql = "SELECT COUNT(*) AS counter_pagamenti_corso
				FROM _mod_pagamenti_ricevuti
 				WHERE fk_corso = $id";
		//echo $sql;		
		$row =  $this->db->query($sql)->result_array();	
		$counter = $row[0]['counter_pagamenti_corso'];

		return $counter;
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