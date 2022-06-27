<?php
if (!defined('BASEPATH')){
	exit('No direct script access allowed');
}

require APPPATH . '/libraries/BaseModel.php';

class Mod_anagrafica_model extends BaseModel
{

	public function __construct(){
		parent::__construct();
		$this->table = 'mod_anagrafica';
		$this->id = 'id';
		$this->mod_name = 'mod_anagrafica';


		//NOTE:NELLA FUNZIONE 'setFieldArrayGrid' INDICARE NEL VETTORE CHE SI COLLEGA ALLA TABELLA REFERENZIATA
		//ALLA CHIAVE 'NOME', IL NOMINATIVO DEL CAMPO COLLEGATO

		$this->setFieldArrayGrid('anagrafica_attributo', FIELD_STRING);
		$this->setFieldArrayGrid('cellulare', FIELD_STRING);
		$this->setFieldArrayGrid('codfiscale', FIELD_STRING);
		$this->setFieldArrayGrid('cognome', FIELD_STRING);
		$this->setFieldArrayGrid('datanascita', FIELD_DATE);
		$this->setFieldArrayGrid('doc_domanda_ammissione_socio', FIELD_BLOB);
		$this->setFieldArrayGrid('nome_documento', FIELD_STRING);
		$this->setFieldArrayGrid('documento', FIELD_BLOB);
		$this->setFieldArrayGrid('email', FIELD_STRING);
		$this->setFieldArrayGrid('fk_comune_nascita',FIELD_NUMERIC,'mod_comuni',array("id" => 'istat', "nome" => 'comune'));
		$this->setFieldArrayGrid('fk_comune_residenza',FIELD_NUMERIC,'mod_comuni',array("id" => 'istat', "nome" => 'comune'));
		$this->setFieldArrayGrid('fk_tutore',FIELD_NUMERIC,'anagrafica_v',array("id" => 'id_anagrafica_v', "nome" => array("nome_anagrafica_v"," ","cognome_anagrafica_v"," ' - ' ","codfiscale_anagrafica_v") ),'grd_tutore',"LEFT");
		$this->setFieldArrayGrid('img_foto', FIELD_BLOB_IMG);
		$this->setFieldArrayGrid('firma', FIELD_BLOB_IMG);
		$this->setFieldArrayGrid('indirizzo', FIELD_STRING);
		$this->setFieldArrayGrid('nome', FIELD_STRING);
		$this->setFieldArrayGrid('notetesto', FIELD_STRING);
		$this->setFieldArrayGrid('sesso', FIELD_STRING);
		$this->setFieldArrayGrid('sottoposto_regime_green_pass', FIELD_STRING);
		$this->setFieldArrayGrid('telefono', FIELD_STRING);
		$this->setFieldArrayGrid('id', FIELD_NUMERIC);

		$this->setFieldArrayGrid('tipo_documento', FIELD_STRING);
		$this->setFieldArrayGrid('nr_documento', FIELD_STRING);
		$this->setFieldArrayGrid('nome_img_firma', FIELD_STRING);
		
		//ESEMPIO DI TABELLA REFERENZIATA CHE NON HA IL CAMPO 'NOME'. QUI INDICHIAMO AL PROGRAMMA QUALE E' IL CAMPO DA USARE COME CAMPO 'NOME'
		//P.S.QUESTA OPERAZIONE E' POSSIBILE FARLA ANCHE NEL METODO 'setFieldArrayGrid'
		//$this->arrayColumnsReferenced['mod_sport']['nome'] = "sport"; 

	}


	public function json($searchFilter) {
		$_REQUEST['searchFilter'] = $searchFilter;
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
		
		$this->datatables->select('mod_anagrafica.id AS id,mod_anagrafica.nome AS mod_anagrafica_nome,
							mod_anagrafica.cognome AS mod_anagrafica_cognome,
							comune AS mod_comuni_comune,DATE_FORMAT(mod_anagrafica.datanascita,"%d/%m/%Y") AS mod_anagrafica_datanascita,
							mod_anagrafica.indirizzo AS mod_anagrafica_indirizzo,
							mod_anagrafica.cellulare AS mod_anagrafica_cellulare,
							CONCAT(mod_anagrafica_tutore.nome," ",mod_anagrafica_tutore.cognome," - ",mod_anagrafica_tutore.codfiscale) AS grd_tutore,
							CONCAT(\'<img src="data:image/jpeg;base64,\',TO_BASE64(mod_anagrafica.img_foto),\'" style="width:90px" />\') AS mod_anagrafica_img_foto,mod_anagrafica.codfiscale');
		$this->datatables->from('mod_anagrafica');
		$this->datatables->join('mod_comuni', 'mod_anagrafica.fk_comune_nascita = mod_comuni.istat');
		$this->datatables->join('mod_anagrafica as mod_anagrafica_tutore', 'mod_anagrafica.fk_tutore = mod_anagrafica_tutore.id',"left");
		$button = "";   
		if($perm_read == 'Y'){
			$button .= "<a onclick='readAjax(\"$this->mod_name\",$1)' class='btn btn-sm btn-default' title='Visualizza'><i class='fa fa-eye'></i></a><br>";
		}
		if($perm_update == 'Y'){
			$button .= "<a onclick='editAjax(\"$this->mod_name\",$1)' class='btn btn-sm btn-info' title='Modifica'><i class='fa fa-pencil'></i></a><br>";
		}  
		//print'<pre>';print_r($_SESSION); 
		if(($perm_delete == 'Y')){
			$button .= "<a onclick='deleteEntry(\"$1\", \"".$this->mod_name."\",\"delete\")' class='btn btn-sm btn-danger deleteItem' title='Cancella'><i class='fa fa-trash'></i></a>";        
		}  

	
		$this->datatables->add_column('action',$button, 'id');
		$this->datatables->add_column('ids','<input type="checkbox" id="check_id" name="check_id" value="$1" onchange="verificaNrCheckBoxSelezionati(\'check_id\',\'btDeleteMass\')" />','id'); 


		return $this->datatables->generate();
	}	


	/**
	 * 
	* Funzione caricamento della master details, tabella _mod_anagrafica_certificati_medici
	* @param mixed $id
	* @param string $isAjax
	* @return string
	**/
	public function getMasterDetail_mod_anagrafica_certificati_medici($id, $isAjax = 'FALSE'){
		$sql ="SELECT _mod_anagrafica_certificati_medici.* 
				,mod_anagrafica.nome AS mod_anagrafica_nome
			FROM _mod_anagrafica_certificati_medici
			 INNER JOIN mod_anagrafica
							ON _mod_anagrafica_certificati_medici.fk_anagrafica = mod_anagrafica.id
			WHERE _mod_anagrafica_certificati_medici.fk_anagrafica = ".$id;
		$row =  $this->db->query($sql)->result_array();	
		return $row;
	}


	/**
	* Funzione caricamento della master details, tabella _mod_anagrafica_corsi
	* @param mixed $id
	* @param string $isAjax
	* @return string
	**/
	public function getMasterDetail_mod_anagrafica_corsi($id, $isAjax = 'FALSE'){
		$sql ="SELECT _mod_anagrafica_corsi.* 
				,mod_anagrafica.nome AS mod_anagrafica_nome
				,mod_corsi.nome AS mod_corsi_nome
			FROM _mod_anagrafica_corsi
			 INNER JOIN mod_anagrafica
							ON _mod_anagrafica_corsi.fk_anagrafica = mod_anagrafica.id
			 INNER JOIN mod_corsi
							ON _mod_anagrafica_corsi.fk_corso = mod_corsi.id
			WHERE _mod_anagrafica_corsi.fk_anagrafica = ".$id;
		$row =  $this->db->query($sql)->result_array();	
		return $row;
	}


	/**
	* Funzione caricamento della master details, tabella _mod_anagrafica_green_pass
	* @param mixed $id
	* @param string $isAjax
	* @return string
	**/
	public function getMasterDetail_mod_anagrafica_green_pass($id, $isAjax = 'FALSE'){
		$sql ="SELECT _mod_anagrafica_green_pass.* 
				,mod_anagrafica.nome AS mod_anagrafica_nome
			FROM _mod_anagrafica_green_pass
			 INNER JOIN mod_anagrafica
							ON _mod_anagrafica_green_pass.fk_anagrafica = mod_anagrafica.id
			WHERE _mod_anagrafica_green_pass.fk_anagrafica = ".$id;
		$row =  $this->db->query($sql)->result_array();	
		return $row;
	}


	/**
	* Funzione caricamento della master details, tabella _mod_anagrafica_green_pass_autocertificazione
	* @param mixed $id
	* @param string $isAjax
	* @return string
	**/
	public function getMasterDetail_mod_anagrafica_green_pass_autocertificazione($id, $isAjax = 'FALSE'){
		$sql ="SELECT _mod_anagrafica_green_pass_autocertificazione.* 
				,mod_anagrafica.nome AS mod_anagrafica_nome
			FROM _mod_anagrafica_green_pass_autocertificazione
			 INNER JOIN mod_anagrafica
							ON _mod_anagrafica_green_pass_autocertificazione.fk_anagrafica = mod_anagrafica.id
			WHERE _mod_anagrafica_green_pass_autocertificazione.fk_anagrafica = ".$id;
		$row =  $this->db->query($sql)->result_array();	
		return $row;
	}


	/**
	* Funzione caricamento della master details, tabella _mod_anagrafica_green_pass_esentati
	* @param mixed $id
	* @param string $isAjax
	* @return string
	**/
	public function getMasterDetail_mod_anagrafica_green_pass_esentati($id, $isAjax = 'FALSE'){
		$sql ="SELECT _mod_anagrafica_green_pass_esentati.* 
				,mod_anagrafica.nome AS mod_anagrafica_nome
			FROM _mod_anagrafica_green_pass_esentati
			 INNER JOIN mod_anagrafica
							ON _mod_anagrafica_green_pass_esentati.fk_anagrafica = mod_anagrafica.id
			WHERE _mod_anagrafica_green_pass_esentati.fk_anagrafica = ".$id;
		$row =  $this->db->query($sql)->result_array();	
		return $row;
	}


	/**
	* Funzione caricamento della master details, tabella _mod_anagrafica_tessere_assoc
	* @param mixed $id
	* @param string $isAjax
	* @return string
	**/
	public function getMasterDetail_mod_anagrafica_tessere_assoc($id, $isAjax = 'FALSE'){

			$sql = "SELECT 
					_mod_magazzino_tessere_lista_tessere.id,
					mod_tesseramenti.tessera_interna,
					_mod_magazzino_tessere_lista_tessere.codice_tessera as tessera_associativa
					,mod_anagrafica.nome AS mod_anagrafica_nome
					,mod_affiliazioni.nome AS mod_affiliazioni_nome					
				FROM mod_tesseramenti
				INNER JOIN mod_anagrafica
							ON mod_tesseramenti.fk_anagrafica = mod_anagrafica.id				
				inner join mod_esercizi
					on mod_esercizi.id = mod_tesseramenti.fk_esercizio
				inner join mod_affiliazioni
					on mod_affiliazioni.id = mod_tesseramenti.fk_affiliazione
				inner join _mod_magazzino_tessere_lista_tessere
					on _mod_magazzino_tessere_lista_tessere.id = mod_tesseramenti.fk_tessera_associativa
				WHERE mod_tesseramenti.fk_anagrafica = $id";		
		$row =  $this->db->query($sql)->result_array();	
		return $row;
	}



	/**
	 * 
	* Funzione caricamento della master details, tabella _mod_anagrafica_tessere_interne
	* @param mixed $id
	* @param string $isAjax
	* @return string
	**/
	public function getMasterDetail_mod_anagrafica_tessere_interne($id, $isAjax = 'FALSE'){
		$sql ="SELECT _mod_anagrafica_tessere_interne.* 
				,mod_anagrafica.nome AS mod_anagrafica_nome
				,mod_esercizi.nome AS mod_esercizi_nome
			FROM _mod_anagrafica_tessere_interne
			 INNER JOIN mod_anagrafica
							ON _mod_anagrafica_tessere_interne.fk_anagrafica = mod_anagrafica.id
			 INNER JOIN mod_esercizi
							ON _mod_anagrafica_tessere_interne.fk_esercizio = mod_esercizi.id
			WHERE _mod_anagrafica_tessere_interne.fk_anagrafica = ".$id;
		$row =  $this->db->query($sql)->result_array();	
		return $row;
	}


	/**
	 * 
	* Funzione caricamento della master details, tabella _mod_corsi_insegnanti
	* @param mixed $id
	* @param string $isAjax
	* @return string
	**/
	public function getMasterDetail_mod_corsi_insegnanti($id, $isAjax = 'FALSE'){
		$sql ="SELECT _mod_corsi_insegnanti.* 
				,mod_corsi.nome AS mod_corsi_nome
				,mod_anagrafica.nome AS mod_anagrafica_nome
			FROM _mod_corsi_insegnanti
			 INNER JOIN mod_corsi
							ON _mod_corsi_insegnanti.fk_corso = mod_corsi.id
			 INNER JOIN mod_anagrafica
							ON _mod_corsi_insegnanti.fk_anagrafica = mod_anagrafica.id
			WHERE _mod_corsi_insegnanti.fk_anagrafica = ".$id;
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
			WHERE _mod_corsi_iscrizioni.fk_anagrafica = ".$id;
		$row =  $this->db->query($sql)->result_array();	
		return $row;
	}


	/**
	* Funzione caricamento della master details, tabella _mod_insegnanti_discipline
	* @param mixed $id
	* @param string $isAjax
	* @return string
	**/
	public function getMasterDetail_mod_insegnanti_discipline($id, $isAjax = 'FALSE'){
		$sql ="SELECT _mod_insegnanti_discipline.* 
				,mod_anagrafica.nome AS mod_anagrafica_nome
				,mod_discipline.nome AS mod_discipline_nome
			FROM _mod_insegnanti_discipline
			 INNER JOIN mod_anagrafica
							ON _mod_insegnanti_discipline.fk_anagrafica = mod_anagrafica.id
			 INNER JOIN mod_discipline
							ON _mod_insegnanti_discipline.fk_disciplina = mod_discipline.id
			WHERE _mod_insegnanti_discipline.fk_anagrafica = ".$id;
		$row =  $this->db->query($sql)->result_array();	
		return $row;
	}


	/**
	* Funzione caricamento delle liste per inserimento multiplo master details, tabella _mod_anagrafica_certificati_medici
	* @param mixed $id
	* @param string $isAjax
	* @return string
	**/
	public function getList_mod_anagrafica_certificati_medici($id, $isAjax = 'FALSE'){
		$sql ="SELECT _mod_anagrafica_certificati_medici.* 
			,mod_anagrafica.nome AS mod_anagrafica_nome
					FROM _mod_anagrafica_certificati_medici
				 INNER JOIN mod_anagrafica
							ON _mod_anagrafica_certificati_medici.fk_anagrafica = mod_anagrafica.id
			WHERE _mod_anagrafica_certificati_medici.fk_anagrafica = ".$id;
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
			WHERE _mod_anagrafica_corsi.fk_anagrafica = ".$id;
		$row =  $this->db->query($sql)->result_array();	
		return $row;
	}


	/**
	* Funzione caricamento delle liste per inserimento multiplo master details, tabella _mod_anagrafica_green_pass
	* @param mixed $id
	* @param string $isAjax
	* @return string
	**/
	public function getList_mod_anagrafica_green_pass($id, $isAjax = 'FALSE'){
		$sql ="SELECT _mod_anagrafica_green_pass.* 
			,mod_anagrafica.nome AS mod_anagrafica_nome
					FROM _mod_anagrafica_green_pass
				 INNER JOIN mod_anagrafica
							ON _mod_anagrafica_green_pass.fk_anagrafica = mod_anagrafica.id
			WHERE _mod_anagrafica_green_pass.fk_anagrafica = ".$id;
		$row =  $this->db->query($sql)->result_array();	
		return $row;
	}


	/**
	* Funzione caricamento delle liste per inserimento multiplo master details, tabella _mod_anagrafica_green_pass_autocertificazione
	* @param mixed $id
	* @param string $isAjax
	* @return string
	**/
	public function getList_mod_anagrafica_green_pass_autocertificazione($id, $isAjax = 'FALSE'){
		$sql ="SELECT _mod_anagrafica_green_pass_autocertificazione.* 
			,mod_anagrafica.nome AS mod_anagrafica_nome
					FROM _mod_anagrafica_green_pass_autocertificazione
				 INNER JOIN mod_anagrafica
							ON _mod_anagrafica_green_pass_autocertificazione.fk_anagrafica = mod_anagrafica.id
			WHERE _mod_anagrafica_green_pass_autocertificazione.fk_anagrafica = ".$id;
		$row =  $this->db->query($sql)->result_array();	
		return $row;
	}


	/**
	* Funzione caricamento delle liste per inserimento multiplo master details, tabella _mod_anagrafica_green_pass_esentati
	* @param mixed $id
	* @param string $isAjax
	* @return string
	**/
	public function getList_mod_anagrafica_green_pass_esentati($id, $isAjax = 'FALSE'){
		$sql ="SELECT _mod_anagrafica_green_pass_esentati.* 
			,mod_anagrafica.nome AS mod_anagrafica_nome
					FROM _mod_anagrafica_green_pass_esentati
				 INNER JOIN mod_anagrafica
							ON _mod_anagrafica_green_pass_esentati.fk_anagrafica = mod_anagrafica.id
			WHERE _mod_anagrafica_green_pass_esentati.fk_anagrafica = ".$id;
		$row =  $this->db->query($sql)->result_array();	
		return $row;
	}


	/**
	* Funzione caricamento delle liste per inserimento multiplo master details, tabella _mod_anagrafica_tessere_assoc
	* @param mixed $id
	* @param string $isAjax
	* @return string
	**/
	public function getList_mod_anagrafica_tessere_assoc($id, $isAjax = 'FALSE'){
		$sql ="SELECT _mod_anagrafica_tessere_assoc.* 
			,mod_anagrafica.nome AS mod_anagrafica_nome
			,mod_affiliazioni.nome AS mod_affiliazioni_nome
					FROM _mod_anagrafica_tessere_assoc
				 INNER JOIN mod_anagrafica
							ON _mod_anagrafica_tessere_assoc.fk_anagrafica = mod_anagrafica.id
				 INNER JOIN mod_affiliazioni
							ON _mod_anagrafica_tessere_assoc.fk_affiliazione = mod_affiliazioni.id
			WHERE _mod_anagrafica_tessere_assoc.fk_anagrafica = ".$id;
		$row =  $this->db->query($sql)->result_array();	
		return $row;
	}


	/**
	* Funzione caricamento delle liste per inserimento multiplo master details, tabella _mod_anagrafica_tessere_interne
	* @param mixed $id
	* @param string $isAjax
	* @return string
	**/
	public function getList_mod_anagrafica_tessere_interne($id, $isAjax = 'FALSE'){
		$sql ="SELECT _mod_anagrafica_tessere_interne.* 
			,mod_anagrafica.nome AS mod_anagrafica_nome
			,mod_esercizi.nome AS mod_esercizi_nome
					FROM _mod_anagrafica_tessere_interne
				 INNER JOIN mod_anagrafica
							ON _mod_anagrafica_tessere_interne.fk_anagrafica = mod_anagrafica.id
				 INNER JOIN mod_esercizi
							ON _mod_anagrafica_tessere_interne.fk_esercizio = mod_esercizi.id
			WHERE _mod_anagrafica_tessere_interne.fk_anagrafica = ".$id;
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
			WHERE _mod_corsi_insegnanti.fk_anagrafica = ".$id;
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
			WHERE _mod_corsi_iscrizioni.fk_anagrafica = ".$id;
		$row =  $this->db->query($sql)->result_array();	
		return $row;
	}


	/**
	* Funzione caricamento delle liste per inserimento multiplo master details, tabella _mod_insegnanti_discipline
	* @param mixed $id
	* @param string $isAjax
	* @return string
	**/
	public function getList_mod_insegnanti_discipline($id, $isAjax = 'FALSE'){
		$sql ="SELECT _mod_insegnanti_discipline.* 
			,mod_anagrafica.nome AS mod_anagrafica_nome
			,mod_discipline.nome AS mod_discipline_nome
					FROM _mod_insegnanti_discipline
				 INNER JOIN mod_anagrafica
							ON _mod_insegnanti_discipline.fk_anagrafica = mod_anagrafica.id
				 INNER JOIN mod_discipline
							ON _mod_insegnanti_discipline.fk_disciplina = mod_discipline.id
			WHERE _mod_insegnanti_discipline.fk_anagrafica = ".$id;
		$row =  $this->db->query($sql)->result_array();	
		return $row;
	}



	/**
	 * 
	 *  Verifica gli attributi di un'anagrafica:se alunno,insegnante, direttivo
	 *  @param mixed $id
	 *  @return array
	*/
	public function checkAttributo($id){
		$row = array();
		if($id != ""){
			$sql = "SELECT anagrafica_attributo FROM mod_anagrafica WHERE id = ".$id;	
			$row =  $this->db->query($sql)->result_array();	
		}

		return $row;		
	} 



	/**
	 * 
	 *  Verifica se un'anagrafica è sottoposto a green pass
	 *  @param mixed $id
	 *  @return string
	*/		
	public function checkSottopostoRegimeGP($id){
		$row = array();
		if($id != ""){
			$sql = "SELECT sottoposto_regime_green_pass FROM mod_anagrafica WHERE id = ".$id;	
			$row =  $this->db->query($sql)->result_array();	
		}

		return $row;		
	} 



	/**
	 * 
	 * Ritorna i dettagli di una anagrafica
	 * @param mixed $fkAnagrafica
	 * @return void
	 */
	public function getAnagrafica($fkAnagrafica){
		$sql ="SELECT * FROM mod_anagrafica WHERE id =".$fkAnagrafica;
		$row =  $this->db->query($sql)->result_array();	
		return $row;
	}


	/**
	 * 
	 * Ritorna i dettagli di un comune
	 * @param mixed $fkAnagrafica
	 * @return void
	 */
	public function getComune($fkComune){
		$sql ="SELECT * FROM mod_comuni WHERE istat =".$fkComune;
		$row =  $this->db->query($sql)->result_array();	
		return $row;
	}	



	/**
	 * 
	 * Ritorna i dettagli un allegato blob
	 * @param string $moduleName
	 * @param string $fieldName
	 * @param int $entryId
	 * @return void
	 */
    public function getAllegatoBlob($moduleName, $fieldName,$entryId){
        $sql ="SELECT ".$moduleName.".".$fieldName." as allegato, 
						nome_documento as nome_allegato
                    FROM $moduleName
                    WHERE $moduleName.id = ".$entryId;              

        return $this->db->query($sql)->result_array();	
    }
	


	/**
	 * 
	 * Genera tutore temporaneo in attesa di definire quello definitivo
	 * @return array
	 */	
	public function genTutore(){
		$row = $this->db->query("SELECT (MAX(id) + 1) AS new_id FROM mod_anagrafica")->result_array();	
		$data = array();
		$data['nome'] = "TUTORE_NOME_".$row[0]['new_id'];
		$data['cognome'] = "TUTORE_COGNOME_".$row[0]['new_id'];
		$data['sesso'] = "M";
		$data['fk_comune_nascita'] = "1001";
		$data['datanascita'] = "1900-01-01";
		$data['fk_comune_residenza'] = "1001";
		$data['indirizzo'] = "VIA DEI TUTORI";
		$data['codfiscale'] = "TUT_CF_".$row[0]['new_id'];
		$data['anagrafica_attributo'] = "ALLIEVO";			
		$data['sottoposto_regime_green_pass'] = "SI";					
		$this->insert($data);
		
		return array("id" => $this->getLastInsertId(),"nome_tutore_gen" => $data['nome']." ".$data['cognome']." ".$data['codfiscale']);	
	}


}