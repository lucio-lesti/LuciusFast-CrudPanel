<?php
if (!defined('BASEPATH')){
	exit('No direct script access allowed');
}

require APPPATH . '/libraries/BaseModel.php';

class Mod_tesseramenti_model extends BaseModel
{

	public function __construct(){
		parent::__construct();
		$this->table = 'mod_tesseramenti';
		$this->id = 'id';
		$this->mod_name = 'mod_tesseramenti';
		$this->mod_type = 'crud';
	}



    /**
	 * 
	 * 
	 * Rewrite funzione json Basemodel
	 */
    public function json($searchFilter) {
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
		
		
		$sql_select_field = "mod_tesseramenti.id AS id";
		$sql_select_field .= ", CONCAT(mod_anagrafica.nome, ' ',mod_anagrafica.cognome,' ', mod_anagrafica.codfiscale) AS nome_anagrafica";
		$sql_select_field .= ", mod_tesseramenti.tessera_interna AS mod_tesseramenti_tessera_interna";
		$sql_select_field .= ", _mod_magazzino_tessere_lista_tessere.codice_tessera AS _mod_magazzino_tessere_lista_tessere_codice_tessera";
		$sql_select_field .= ", DATE_FORMAT(mod_tesseramenti.data_tesseramento,'%d/%m/%Y') AS mod_tesseramenti_data_tesseramento";
		$sql_select_field .= ", mod_esercizi.id AS mod_esercizi_nome";
		$sql_select_field .= ", mod_affiliazioni.id AS mod_affiliazioni_nome";
		
        $this->datatables->select($sql_select_field);
        $this->datatables->from('mod_tesseramenti');
		$this->datatables->join('mod_anagrafica', 'mod_tesseramenti.fk_anagrafica = mod_anagrafica.id');
		$this->datatables->join('mod_esercizi', 'mod_tesseramenti.fk_esercizio = mod_esercizi.id');
		$this->datatables->join('mod_affiliazioni', 'mod_tesseramenti.fk_affiliazione = mod_affiliazioni.id');
		$this->datatables->join('_mod_magazzino_tessere_lista_tessere', 'mod_tesseramenti.fk_tessera_associativa = _mod_magazzino_tessere_lista_tessere.id');
		$this->datatables->order_by("mod_tesseramenti.id");

        $button = "";   
        if($perm_read == 'Y'){
            $button .= "<a onclick='readAjax(\"$this->mod_name\",$1)' class='btn btn-sm btn-default' title='Visualizza'><i class='fa fa-eye'></i></a><br>";
        }
        if($perm_update == 'Y'){
            $button .= "<a onclick='editAjax(\"$this->mod_name\",$1)' class='btn btn-sm btn-info' title='Modifica'><i class='fa fa-pencil'></i></a><br>";
        }   
        if($perm_delete == 'Y'){
            $button .= "<a onclick='deleteEntry(\"$1\", \"".$this->mod_name."\",\"delete\")' class='btn btn-sm btn-danger deleteItem' title='Cancella'><i class='fa fa-trash'></i></a>";        
        }  

        $this->datatables->add_column('action', $button, 'id');
        $this->datatables->add_column('ids', '<input type="checkbox" id="check_id" name="check_id" value="$1" onchange="verificaNrCheckBoxSelezionati(\'check_id\',\'btDeleteMass\')" />', $this->id);             
 		

		$this->datatables->add_column('action',$button, 'id');
		$this->datatables->add_column('ids','<input type="checkbox" id="check_id" name="check_id" value="$1" onchange="verificaNrCheckBoxSelezionati(\'check_id\',\'btDeleteMass\')" />','id'); 
		

        return $this->datatables->generate();
    }



	/**
	 * 
	 * Prelevo le affiliazioni in base al filtro passato
	 * @param mixed $id
	 * @return string
	 */
	public function populateAffiliazioni($esercId){
		
		$sql = "SELECT mod_affiliazioni.id,mod_affiliazioni.nome
			FROM mod_affiliazioni";
		if(($esercId != "")){
			$sql .=" WHERE mod_affiliazioni.fk_esercizio = ".$esercId;	
		}	
				
		$row =  $this->db->query($sql)->result_array();	

		return $row;
	}	

	

	/**
	 * 
	 * Prelevo esercizio corrente
	 */	
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
	* Prelevo nome affiliazione
	* @param mixed $id
	* @return string
	**/		
	public function getNomeAffiliazione($id){
		$sql = "SELECT nome 
				FROM mod_affiliazioni 
				where id = ".$id;
		$row =  $this->db->query($sql)->result_array();	
		return $row[0]['nome'];	
	}



	/**
	* 
	* Prelevo la tessera in base all'anagrafica
	* @param mixed $fkAnagrafica
	* @param string $fkEsercizio
	* @param string $fk_affiliazione
	* @return string
	**/	
	public function getTessera($fkAnagrafica, $fkEsercizio, $fk_affiliazione){
		$tessera_interna = "";
		$lista_tessere_associative = array();

		//PRELEVO LA PRIMA TESSERA ASSOCIATIVA DISPONIBILE
		if(($fkEsercizio != 'NULL') && ($fk_affiliazione != 'NULL') && ($fkAnagrafica != 'NULL')){
			$sql = "SELECT _mod_magazzino_tessere_lista_tessere.id, 
						_mod_magazzino_tessere_lista_tessere.codice_tessera 
						FROM _mod_magazzino_tessere_lista_tessere 
						INNER JOIN mod_magazzino_tessere 
							ON mod_magazzino_tessere.id = _mod_magazzino_tessere_lista_tessere.fk_magazzino_tessere 
						INNER JOIN mod_affiliazioni 
							ON mod_affiliazioni.id = mod_magazzino_tessere.fk_affiliazione 
							AND mod_magazzino_tessere.fk_affiliazione = $fk_affiliazione 
					WHERE _mod_magazzino_tessere_lista_tessere.id NOT IN
					(SELECT mod_tesseramenti.fk_tessera_associativa FROM mod_tesseramenti WHERE fk_anagrafica <> $fkAnagrafica)
					ORDER BY _mod_magazzino_tessere_lista_tessere.id ASC";
			//echo $sql;		
			$row =  $this->db->query($sql)->result_array();	
			if((isset($row[0]['codice_tessera'])) && ($row[0]['codice_tessera'] != '')){
				$lista_tessere_associative = $row;
			} else {
				//SE NON CI SONO TESSERE ASSOCIATIVE, LA GENERO
				$sql = "SELECT MAX(_mod_magazzino_tessere_lista_tessere.id) + 1 AS id,
						CONCAT(\"ND_\",$fk_affiliazione,\"_\",MAX(_mod_magazzino_tessere_lista_tessere.id) + 1) AS codice_tessera,
						mod_magazzino_tessere.id as fk_magazzino_tessere 
						FROM _mod_magazzino_tessere_lista_tessere 
						LEFT JOIN mod_magazzino_tessere 
							ON mod_magazzino_tessere.id = _mod_magazzino_tessere_lista_tessere.fk_magazzino_tessere 
						INNER JOIN mod_affiliazioni 
							ON mod_affiliazioni.id = mod_magazzino_tessere.fk_affiliazione 
							AND mod_magazzino_tessere.fk_affiliazione = $fk_affiliazione";
				$row =  $this->db->query($sql)->result_array();	
				if((!isset($row[0]['codice_tessera'])) || ($row[0]['codice_tessera'] == '')){
					$row[0]['codice_tessera']  = 'ND_'.$fk_affiliazione.'_1';
				}	

				//SE NON ESISTE IL MAGAZZINO ASSOCIATO ALL'AFFILIAZIONE LO CREO
				if((!isset($row[0]['fk_magazzino_tessere'])) || ($row[0]['fk_magazzino_tessere'] == "")){
					$nomeAffiliazione =  $this->getNomeAffiliazione($fk_affiliazione);
					$dataMagazzino = array("nome" => "Stock per $nomeAffiliazione", "fk_affiliazione" => $fk_affiliazione);
					$insert_query = $this->db->insert_string('mod_magazzino_tessere', $dataMagazzino);
					$this->db->query($insert_query);
					$row[0]['fk_magazzino_tessere'] = $this->db->insert_id();
				}

				//INSERISCO NEL MAGAZZINO LA TESSERA GENERATA
				$data = array("fk_magazzino_tessere" => $row[0]['fk_magazzino_tessere'], "codice_tessera" => $row[0]['codice_tessera']);
				$insert_query = $this->db->insert_string('_mod_magazzino_tessere_lista_tessere', $data);
				$this->db->query($insert_query);
				$row[0]['id'] = $this->db->insert_id();
			}
			$lista_tessere_associative[0] = array("id" => $row[0]['id'], "codice_tessera" => $row[0]['codice_tessera']);


			//PRELEVO/GENERO LA TESSERA INTERNA
			$sql = "SELECT MAX(tessera_interna) + 1 AS tessera_interna FROM mod_tesseramenti 
					WHERE fk_esercizio = $fkEsercizio AND fk_anagrafica = $fkAnagrafica";
			$row =  $this->db->query($sql)->result_array();	
			if((isset($row[0]['tessera_interna'])) && ($row[0]['tessera_interna'] != '')){
				$tessera_interna = (int)$row[0]['tessera_interna'];
			} else {
				//SE NON CE PER L'ANAGRAFICA LA GENERO PER L'ESERCIZIO E L'ASSEGNO ALL'ANAGRAFICA
				$sql = "SELECT MAX(tessera_interna) + 1 AS tessera_interna  FROM mod_tesseramenti 
						WHERE fk_esercizio = $fkEsercizio ";
				//echo $sql." - ";
				$row =  $this->db->query($sql)->result_array();	
				if((isset($row[0]['tessera_interna'])) && ($row[0]['tessera_interna'] != '')){
					$tessera_interna = (int)$row[0]['tessera_interna'];			
				} else {
					//SE NON CE NEMMENO PER L'ESERCIZIO PARTE DA 100 LA TESSERA 
					$tessera_interna = '100';					
				}	

			}		

		}

		echo json_encode(array("lista_tessere_associative" => $lista_tessere_associative,
						"tessera_interna" => $tessera_interna));	

	}


	/**
	 * 
	 * Verifica se la data di tesseramento rientra nell'esericizio
	 * @param string $idEsercizio
	 * @param date $data_tesseramento
	 * @return bool
	 */	
	public function check_data_tesseramento($idEsercizio, $data_tesseramento){
		$ret = TRUE;
		
		$sql = "SELECT COUNT(*) AS counter_date
				FROM mod_esercizi
				WHERE mod_esercizi.id = $idEsercizio
				AND '$data_tesseramento' BETWEEN mod_esercizi.data_da AND mod_esercizi.data_a";
		//echo $sql;		
		$row =  $this->db->query($sql)->result_array();	

		if((int)$row[0]['counter_date'] == 0){
			$ret = FALSE;
		}

		return $ret;
	}



	/**
	* 
	* Prelevo id Anagrafica
	* @param mixed $idTesseramento
	* @return string
	**/
	public function getFkAnagrafica($idTesseramento){
		$sql ="SELECT fk_anagrafica FROM mod_tesseramenti WHERE id = $idTesseramento";
		$row =  $this->db->query($sql)->result_array();
		return $row[0]['fk_anagrafica'];
	}	



	/**
	* 
	* Prelevo storico tesseramento per id anagrafica
	* @param mixed $idTesseramento
	* @return string
	**/
	public function getStoricoTesseramenti($fk_anagrafica){
		$sql = "SELECT mod_tesseramenti.data_tesseramento, 
					mod_tesseramenti.importo, 
					mod_tipopagamento.nome as modo_pagamento,
					mod_tesseramenti.id as id_tesseramento,
					_mod_magazzino_tessere_lista_tessere.codice_tessera as tessera_associativa,
					mod_tesseramenti.tessera_interna,
					mod_esercizi.nome as esercizio,
					mod_affiliazioni.nome as affiliazione,
					mod_anagrafica.email,
					_mod_pagamenti_tesseramenti.id as id_pagamento_tesseramento
					FROM mod_tesseramenti
				inner join _mod_pagamenti_tesseramenti
					on mod_tesseramenti.id = _mod_pagamenti_tesseramenti.fk_tesseramento
				inner join mod_anagrafica
					on mod_anagrafica.id = mod_tesseramenti.fk_anagrafica
				inner join mod_esercizi
					on mod_esercizi.id = mod_tesseramenti.fk_esercizio
				inner join mod_affiliazioni
					on mod_affiliazioni.id = mod_tesseramenti.fk_affiliazione
				inner join _mod_magazzino_tessere_lista_tessere
					on _mod_magazzino_tessere_lista_tessere.id = mod_tesseramenti.fk_tessera_associativa
				inner join mod_tipopagamento
					on mod_tipopagamento.id = mod_tesseramenti.fk_tipopagamento
				WHERE mod_tesseramenti.fk_anagrafica = $fk_anagrafica";
		
		return $this->db->query($sql)->result_array();
	}


	/**
	 * 
	 * Inserimento massivo tesserati
	 */
	public function insertPagamentiFromTesserati(){
		
		$sql = "SELECT * FROM mod_tesseramenti
				WHERE id NOT IN (select fk_tesseramento FROM _mod_pagamenti_tesseramenti)";
		$row = $this->db->query($sql)->result_array();

		foreach($row as $k => $v){
			$data['fk_tesseramento'] = $v['id'];
			$data['saldo'] = "SI";
			$data['fk_tipopagamento'] = $v['fk_tipopagamento'];
			$data['datapagamento'] = $v['data_tesseramento'];
			$data['importo'] = $v['importo'];

			$sql = $this->db->set($data)->get_compiled_insert("_mod_pagamenti_tesseramenti");
			$sql = str_replace('INSERT INTO', 'INSERT IGNORE INTO', $sql);
			$this->db->query($sql);	
		}

	}

	
	/**
	 * 
	 * Aggiorno l'importo del tesseramento
	 * @param string $id
	 * @param float $importo
	 */
	public function update_importo_tesseramento($id, $importo){
        $this->db->where("fk_tesseramento", $id);
		$data = array("importo" => $importo);
        return $this->db->update("_mod_pagamenti_tesseramenti", $data);		
 
	}
 
}