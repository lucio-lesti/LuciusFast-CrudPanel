<?php
if (!defined('BASEPATH')){
	exit('No direct script access allowed');
}

require APPPATH . '/libraries/BaseModel.php';

class Mod_pagamenti_collaboratori_v_model extends BaseModel
{

	public function __construct(){
		parent::__construct();
		$this->table = 'mod_pagamenti_collaboratori_v';
		$this->id = 'id';
		$this->mod_name = 'mod_pagamenti_collaboratori_v';
		$this->mod_type = 'crud';


		//NOTE:NELLA FUNZIONE 'setFieldArrayGrid' INDICARE NEL VETTORE CHE SI COLLEGA ALLA TABELLA REFERENZIATA
		//ALLA CHIAVE 'NOME', IL NOMINATIVO DEL CAMPO COLLEGATO

		//NOTE 2: NELLA FUNZIONE 'setFieldArrayGrid' se nella chiave "nome" si usa una array, la classe "BaseModel" lo interpreta come un concat

		$this->setFieldArrayGrid('collaboratore', FIELD_STRING);
		$this->setFieldArrayGrid('id', FIELD_NUMERIC);
		$this->setFieldArrayGrid('fk_contratto', FIELD_NUMERIC);
		$this->setFieldArrayGrid('datapagamento', FIELD_DATE);
		$this->setFieldArrayGrid('ora_pagamento', FIELD_STRING);
		$this->setFieldArrayGrid('importo', FIELD_FLOAT);
		$this->setFieldArrayGrid('fk_tipopagamento', FIELD_NUMERIC);
		$this->setFieldArrayGrid('fk_causale_pagamento', FIELD_NUMERIC);
		$this->setFieldArrayGrid('notepagamento', FIELD_STRING);
		$this->setFieldArrayGrid('contratto_id', FIELD_NUMERIC);
		$this->setFieldArrayGrid('contratto_nome', FIELD_STRING);


		//ESEMPIO DI TABELLA REFERENZIATA CHE NON HA IL CAMPO 'NOME'. QUI INDICHIAMO AL PROGRAMMA QUALE E' IL CAMPO DA USARE COME CAMPO 'NOME'
		//P.S.QUESTA OPERAZIONE E' POSSIBILE FARLA ANCHE NEL METODO 'setFieldArrayGrid'
		//$this->arrayColumnsReferenced['mod_sport']['nome'] = "sport"; 

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
		/*
		$this->datatables->select("id,
							collaboratore AS mod_pagamenti_collaboratori_v_collaboratore, 
							contratto_nome AS mod_pagamenti_collaboratori_v_contratto_nome");
		$this->datatables->from("mod_pagamenti_collaboratori_v");
	 	*/
		$this->datatables->select("mod_contratti_collaboratori.id as id,
								CONCAT(mod_anagrafica.nome, \" \", mod_anagrafica.cognome, \" - \", mod_anagrafica.codfiscale) AS mod_pagamenti_collaboratori_v_collaboratore, 
		 						mod_contratti_collaboratori.nome AS mod_pagamenti_collaboratori_v_contratto_nome");
		$this->datatables->from("mod_contratti_collaboratori");
		$this->datatables->join("mod_anagrafica","mod_contratti_collaboratori.fk_anagrafica = mod_anagrafica.id","inner");


		$button = "";   
		if($perm_read == 'Y'){
			$button .= "<a onclick='readAjax(\"$this->mod_name\",$1)' class='btn btn-sm btn-default' title='Visualizza'><i class='fa fa-eye'></i></a><br>";
		}
		if($perm_update == 'Y'){
			$button .= "<a onclick='editAjax(\"$this->mod_name\",$1)' class='btn btn-sm btn-info' title='Modifica'><i class='fa fa-pencil'></i></a><br>";
		}  


		$this->datatables->add_column('action', $button, 'id');
		$this->datatables->add_column('ids', '<input type="checkbox" id="check_id" name="check_id" value="$1" onchange="verificaNrCheckBoxSelezionati(\'check_id\',\'btDeleteMass\')" />', $this->id);             	


        return $this->datatables->generate();

	}	



	/**
	* Funzione caricamento della master details, tabella _mod_pagamenti_collaboratori
	* @param mixed $id
	* @param string $isAjax
	* @return string
	**/
	public function getMasterDetail_mod_pagamenti_collaboratori($id, $isAjax = 'FALSE'){
		$sql ="SELECT 
				CONCAT(_mod_pagamenti_collaboratori.anno,'-',
				CASE
					WHEN _mod_pagamenti_collaboratori.mese = 'GENNAIO' THEN '01'
					WHEN _mod_pagamenti_collaboratori.mese = 'FEBBRAIO' THEN '02'
					WHEN _mod_pagamenti_collaboratori.mese = 'MARZO' THEN '03'
					WHEN _mod_pagamenti_collaboratori.mese = 'APRILE' THEN '04'
					WHEN _mod_pagamenti_collaboratori.mese = 'MAGGIO' THEN '05'
					WHEN _mod_pagamenti_collaboratori.mese = 'GIUGNO' THEN '06'
					WHEN _mod_pagamenti_collaboratori.mese = 'LUGLIO' THEN '07'
					WHEN _mod_pagamenti_collaboratori.mese = 'AGOSTO' THEN '08'
					WHEN _mod_pagamenti_collaboratori.mese = 'SETTEMBRE' THEN '09'
					WHEN _mod_pagamenti_collaboratori.mese = 'OTTOBRE' THEN '10'
					WHEN _mod_pagamenti_collaboratori.mese = 'NOVEMBRE' THEN '11'
					WHEN _mod_pagamenti_collaboratori.mese = 'DICEMBRE' THEN '12'
				END,'-01')AS mese_anno,		
				_mod_pagamenti_collaboratori.*,
				mod_tipopagamento.nome as tipo_pagamento,
				mod_causali_pagamento.nome as causale_pagamento, 
				mod_anagrafica.codfiscale,
				mod_anagrafica.datanascita,
				mod_anagrafica.indirizzo,
				mod_anagrafica.telefono,
				mod_anagrafica.cellulare,
				mod_anagrafica.email,
				mod_anagrafica.firma,
				CONCAT(mod_anagrafica.nome, \" \", mod_anagrafica.cognome, \" - \", mod_anagrafica.codfiscale) AS mod_anagrafica_nome
			FROM _mod_pagamenti_collaboratori
			 INNER JOIN mod_contratti_collaboratori
							ON _mod_pagamenti_collaboratori.fk_contratto = mod_contratti_collaboratori.id
			 INNER JOIN mod_anagrafica
							ON mod_contratti_collaboratori.fk_anagrafica = mod_anagrafica.id
			INNER JOIN mod_tipopagamento
				ON _mod_pagamenti_collaboratori.fk_tipopagamento = mod_tipopagamento.id			
			INNER JOIN mod_causali_pagamento
				ON _mod_pagamenti_collaboratori.fk_causale_pagamento = mod_causali_pagamento.id																	
			WHERE _mod_pagamenti_collaboratori.fk_contratto = $id
			ORDER BY mese_anno ASC";

		//echo $sql;
		$row =  $this->db->query($sql)->result_array();	
		return $row;
	}



	/**
	* Funzione caricamento della master details, tabella _mod_pagamenti_collaboratori
	* @param mixed $id
	* @param string $isAjax
	* @return string
	**/
	public function getDettagliRicevuta($id){
		$sql ="SELECT _mod_pagamenti_collaboratori.*,
				mod_contratti_collaboratori.mansione,
				mod_tipopagamento.nome as tipo_pagamento,
				mod_causali_pagamento.nome as causale_pagamento, 
				mod_anagrafica.codfiscale,
				CONCAT(mod_anagrafica.nome, \" \", mod_anagrafica.cognome) AS nominativo,
				mod_anagrafica.datanascita,
				mod_anagrafica.indirizzo,
				mod_anagrafica.telefono,
				mod_anagrafica.cellulare,
				mod_anagrafica.email,
				mod_anagrafica.firma,
				mod_anagrafica.nome_img_firma,
				mod_comuni.comune as comune_nascita,
				mod_comuni2.comune as comune_residenza,
				CONCAT(mod_anagrafica.nome, \" \", mod_anagrafica.cognome, \" - \", mod_anagrafica.codfiscale) AS mod_anagrafica_nome
			FROM _mod_pagamenti_collaboratori
			 INNER JOIN mod_contratti_collaboratori
							ON _mod_pagamenti_collaboratori.fk_contratto = mod_contratti_collaboratori.id
			 INNER JOIN mod_anagrafica
							ON mod_contratti_collaboratori.fk_anagrafica = mod_anagrafica.id
			INNER JOIN mod_tipopagamento
				ON _mod_pagamenti_collaboratori.fk_tipopagamento = mod_tipopagamento.id			
			INNER JOIN mod_causali_pagamento
				ON _mod_pagamenti_collaboratori.fk_causale_pagamento = mod_causali_pagamento.id		
			INNER JOIN mod_comuni
				ON mod_anagrafica.fk_comune_nascita = mod_comuni.istat			
			INNER JOIN mod_comuni as mod_comuni2
				ON mod_anagrafica.fk_comune_residenza = mod_comuni2.istat																	
			WHERE _mod_pagamenti_collaboratori.id = ".$id;

		//echo $sql;
		$row =  $this->db->query($sql)->result_array();	
		return $row[0];
	}


	public function getListaMesiDaPagare($fk_contratto){
		$mesi = array();

		$sql = "SELECT data_da,data_a 
					FROM mod_contratti_collaboratori 
				WHERE id = ".$fk_contratto;
		$row =  $this->db->query($sql)->result_array();	
		$data_da = $row[0]['data_da'];
		$data_a = $row[0]['data_a'];

		$objData_da    = (new DateTime($data_da))->modify('first day of this month');
		$objData_a    = (new DateTime($data_a))->modify('first day of this month');
		$mese_da = $objData_da->format("m");
		$mese_a =  $objData_a->format("m");
		
		
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


	public function getListaAnniDaPagare($fk_contratto){
		$anni = [];
		
		$sql = "SELECT data_da,data_a 
					FROM mod_contratti_collaboratori 
				WHERE id = ".$fk_contratto;
		$row =  $this->db->query($sql)->result_array();	
		$data_da = $row[0]['data_da'];
		$data_a = $row[0]['data_a'];

		$objData_da    = (new DateTime($data_da))->modify('first day of this month');
		$objData_a    = (new DateTime($data_a))->modify('first day of this month');
		$anno_da = $objData_da->format("Y");
		$anno_a =  $objData_a->format("Y");
		for($anno = 2010; $anno <= 2050; $anno++){
			if(($anno == $anno_da) || ($anno == $anno_a)){
				$anni[] = $anno;
			}
			
		}

		return $anni;
	}			



	public function getListaMesiAnniDaPagare($fk_contratto){
		$return = [
			"anno" => [],
			"mese" => [],
			"mese_ita" => [],
		];
		
		$sql = "SELECT data_da,data_a 
					FROM mod_contratti_collaboratori 
				WHERE id = ".$fk_contratto;
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


}