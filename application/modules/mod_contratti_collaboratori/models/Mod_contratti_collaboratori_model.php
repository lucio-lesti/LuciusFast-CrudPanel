<?php
if (!defined('BASEPATH')) {
	exit('No direct script access allowed');
}

require APPPATH . '/libraries/BaseModel.php';

class Mod_contratti_collaboratori_model extends BaseModel
{

	public function __construct()
	{
		parent::__construct();
		$this->table = 'mod_contratti_collaboratori';
		$this->id = 'id';
		$this->mod_name = 'mod_contratti_collaboratori';
		$this->mod_type = 'crud';


		//NOTE:NELLA FUNZIONE 'setFieldArrayGrid' INDICARE NEL VETTORE CHE SI COLLEGA ALLA TABELLA REFERENZIATA
		//ALLA CHIAVE 'NOME', IL NOMINATIVO DEL CAMPO COLLEGATO

		$this->setFieldArrayGrid('nome', FIELD_STRING);
		$this->setFieldArrayGrid('fk_anagrafica', FIELD_NUMERIC, 'mod_anagrafica', array("id" => 'id', "nome" => array("nome", " ", "cognome")), "mod_anagrafica_nome");
		$this->setFieldArrayGrid('data_da', FIELD_DATE);
		$this->setFieldArrayGrid('data_a', FIELD_DATE);
		$this->setFieldArrayGrid('id', FIELD_NUMERIC);
	}


	/**
	 * Funzione caricamento della master details, tabella _mod_pagamenti_collaboratori
	 * @param mixed $id
	 * @param string $isAjax
	 * @return string
	 **/
	public function getMasterDetail_mod_pagamenti_collaboratori($id, $isAjax = 'FALSE')
	{
		$sql = "SELECT _mod_pagamenti_collaboratori.* 
				,mod_contratti_collaboratori.nome AS mod_contratti_collaboratori_nome
				,mod_tipopagamento.nome AS mod_tipopagamento_nome
				,mod_causali_pagamento.nome AS mod_causali_pagamento_nome
			FROM _mod_pagamenti_collaboratori
			 INNER JOIN mod_contratti_collaboratori
							ON _mod_pagamenti_collaboratori.fk_contratto = mod_contratti_collaboratori.id
			 INNER JOIN mod_tipopagamento
							ON _mod_pagamenti_collaboratori.fk_tipopagamento = mod_tipopagamento.id
			 INNER JOIN mod_causali_pagamento
							ON _mod_pagamenti_collaboratori.fk_causale_pagamento = mod_causali_pagamento.id
			WHERE _mod_pagamenti_collaboratori.fk_contratto = " . $id;
		$row =  $this->db->query($sql)->result_array();
		return $row;
	}


	/**
	 * Funzione caricamento della master details, tabella _mod_contratti_collaboratori_corsi
	 * @param mixed $id
	 * @param string $isAjax
	 * @return string
	 **/
	public function getMasterDetail_mod_contratti_collaboratori_corsi($id, $isAjax = 'FALSE')
	{
		$sql = "SELECT _mod_contratti_collaboratori_corsi.* 
				,mod_contratti_collaboratori.nome AS mod_contratti_collaboratori_nome
				,mod_corsi.nome AS mod_corsi_nome
			FROM _mod_contratti_collaboratori_corsi
			 INNER JOIN mod_contratti_collaboratori
							ON _mod_contratti_collaboratori_corsi.fk_contratto = mod_contratti_collaboratori.id
			 INNER JOIN mod_corsi
							ON _mod_contratti_collaboratori_corsi.fk_corso = mod_corsi.id
			WHERE _mod_contratti_collaboratori_corsi.fk_contratto = " . $id;
		$row =  $this->db->query($sql)->result_array();
		return $row;
	}


	/**
	 * Funzione caricamento delle liste per inserimento multiplo master details, tabella _mod_pagamenti_collaboratori
	 * @param mixed $id
	 * @param string $isAjax
	 * @return string
	 **/
	public function getList_mod_pagamenti_collaboratori($id, $isAjax = 'FALSE')
	{
		$sql = "SELECT _mod_pagamenti_collaboratori.* 
			,mod_contratti_collaboratori.nome AS mod_contratti_collaboratori_nome
			,mod_tipopagamento.nome AS mod_tipopagamento_nome
			,mod_causali_pagamento.nome AS mod_causali_pagamento_nome
					FROM _mod_pagamenti_collaboratori
				 INNER JOIN mod_contratti_collaboratori
							ON _mod_pagamenti_collaboratori.fk_contratto = mod_contratti_collaboratori.id
				 INNER JOIN mod_tipopagamento
							ON _mod_pagamenti_collaboratori.fk_tipopagamento = mod_tipopagamento.id
				 INNER JOIN mod_causali_pagamento
							ON _mod_pagamenti_collaboratori.fk_causale_pagamento = mod_causali_pagamento.id
			WHERE _mod_pagamenti_collaboratori.fk_contratto = " . $id;
		$row =  $this->db->query($sql)->result_array();
		return $row;
	}


	/**
	 * Funzione caricamento delle liste per inserimento multiplo master details, tabella _mod_contratti_collaboratori_corsi
	 * @param mixed $id
	 * @param string $isAjax
	 * @return string
	 **/
	public function getList_mod_contratti_collaboratori_corsi($id, $isAjax = 'FALSE')
	{
		$sql = "SELECT _mod_contratti_collaboratori_corsi.* 
			,mod_contratti_collaboratori.nome AS mod_contratti_collaboratori_nome
			,mod_corsi.nome AS mod_corsi_nome
					FROM _mod_contratti_collaboratori_corsi
				 INNER JOIN mod_contratti_collaboratori
							ON _mod_contratti_collaboratori_corsi.fk_contratto = mod_contratti_collaboratori.id
				 INNER JOIN mod_corsi
							ON _mod_contratti_collaboratori_corsi.fk_corso = mod_corsi.id
			WHERE _mod_contratti_collaboratori_corsi.fk_contratto = " . $id;
		$row =  $this->db->query($sql)->result_array();
		return $row;
	}

	/**
	 * 
	 * Ritorna i dettagli di una anagrafica
	 * @param mixed $fkAnagrafica
	 * @return void
	 */
	public function getAnagrafica($fkAnagrafica)
	{
		$sql = "SELECT * FROM mod_anagrafica WHERE id =" . $fkAnagrafica;
		$row =  $this->db->query($sql)->result_array();
		return $row;
	}


	/**
	 * 
	 * Ritorna i dettagli di un comune
	 * @param mixed $fkAnagrafica
	 * @return void
	 */
	public function getComune($fkComune)
	{
		$sql = "SELECT * FROM mod_comuni WHERE istat =" . $fkComune;
		$row =  $this->db->query($sql)->result_array();
		return $row;
	}


	/**
	 * 
	 * Verifico il range di date se esistenti per la persona selezionata 
	 * @param mixed $fkAnagrafica
	 * @return void
	 */
	public function isRangeDateEsistentiContratto($fkAnagrafica, $dataDa, $dataA, $idContratto = NULL)
	{
		$rangeDateEsistenti = array();
		$sql = "SELECT count(*) AS counter_r,
					DATE_FORMAT(data_da, \"%d/%m/%Y\") as data_da,
					DATE_FORMAT(data_a, \"%d/%m/%Y\") as data_a 
					FROM `mod_contratti_collaboratori` 
					WHERE  fk_anagrafica = $fkAnagrafica";
		if ($idContratto != NULL) {
			$sql .= " AND id <> " . $idContratto;
		}
		$sql .= " AND  (data_da BETWEEN '$dataDa' AND '$dataA' OR data_a BETWEEN '$dataDa' AND '$dataA')
				GROUP BY data_da,data_a";
		$row =  $this->db->query($sql)->result_array();

		//echo $sql;		
		//print'<pre>';print_r($row);die();

		$rangeDateEsistenti["check"] = FALSE;
		$rangeDateEsistenti["data_da"] = NULL;
		$rangeDateEsistenti["data_a"] = NULL;

		if (isset($row[0]['counter_r'])) {
			if ((int)$row[0]['counter_r'] > 0) {
				$rangeDateEsistenti["check"] = TRUE;
			}
			$rangeDateEsistenti["data_da"] = $row[0]['data_da'];
			$rangeDateEsistenti["data_a"] = $row[0]['data_a'];
		}


		return $rangeDateEsistenti;
	}



	public function getEmailCollaboratore($id)
	{
		$email = "";
		$sql = "SELECT mod_anagrafica.email  AS email
				FROM mod_anagrafica
				INNER JOIN mod_contratti_collaboratori 
					ON mod_contratti_collaboratori.fk_anagrafica = mod_anagrafica.id
				WHERE mod_contratti_collaboratori.id = " . $id;
		//echo $sql	;	
		$row =  $this->db->query($sql)->result_array();
		//print'<pre>';print_r($row);
		if (isset($row[0])) {
			$email = $row[0]['email'];
		}

		return $email;
	}


	public function getDettagliContratto($id)
	{
		$sql = "SELECT mod_anagrafica.*,
					mod_contratti_collaboratori.id,
					mod_contratti_collaboratori.fk_anagrafica,
					mod_contratti_collaboratori.mansione,
					mod_contratti_collaboratori.data_da,
					mod_contratti_collaboratori.data_a,
					mod_contratti_collaboratori.data_firma_contratto,
					mod_contratti_collaboratori.importo_mensile,
					mod_contratti_collaboratori.fk_tipopagamento,
					mod_tipopagamento.nome as tipo_pagamento,
					mod_contratti_collaboratori.banca,
					mod_contratti_collaboratori.iban,
					mod_comuni_nascita.comune as comune_nascita,
					mod_comuni_residenza.comune as comune_residenza,
					mod_comuni_residenza.cap as cap_residenza,
					mod_comuni_residenza.codice_provincia as prov_residenza
				FROM mod_contratti_collaboratori
				INNER JOIN mod_anagrafica
					ON mod_anagrafica.id = mod_contratti_collaboratori.fk_anagrafica
				INNER JOIN mod_comuni as mod_comuni_nascita
					ON mod_anagrafica.fk_comune_nascita = mod_comuni_nascita.istat			
				INNER JOIN mod_comuni as mod_comuni_residenza			
					ON mod_anagrafica.fk_comune_residenza = mod_comuni_residenza.istat	
				INNER JOIN mod_tipopagamento  
					ON mod_tipopagamento.id = mod_contratti_collaboratori.fk_tipopagamento		
				WHERE mod_contratti_collaboratori.id = " . $id;
		$row =  $this->db->query($sql)->result_array();

		return $row[0];
	}
}
