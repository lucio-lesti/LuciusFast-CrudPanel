<?php
if (!defined('BASEPATH')){
	exit('No direct script access allowed');
}

require APPPATH . '/libraries/BaseModel.php';

class Mod_report_pagamenti_mensili_corso_model extends BaseModel
{

	public function __construct(){
		parent::__construct();
		$this->table = 'mod_report_pagamenti_mensili_corso';
		$this->id = '';
		$this->mod_name = 'mod_report_pagamenti_mensili_corso';
		$this->mod_type = 'report';


		//NOTE:NELLA FUNZIONE 'setFieldArrayGrid' INDICARE NEL VETTORE CHE SI COLLEGA ALLA TABELLA REFERENZIATA
		//ALLA CHIAVE 'NOME', IL NOMINATIVO DEL CAMPO COLLEGATO

		$this->setFieldArrayGrid('Ago', FIELD_STRING);
		$this->setFieldArrayGrid('Anagrafica', FIELD_STRING);
		$this->setFieldArrayGrid('mod_corsi_nome', FIELD_STRING);
		$this->setFieldArrayGrid('mod_corsi_tipo', FIELD_STRING);
		$this->setFieldArrayGrid('Apr', FIELD_STRING);
		$this->setFieldArrayGrid('Data_Iscrizione', FIELD_DATE);
		$this->setFieldArrayGrid('Dic', FIELD_STRING);
		$this->setFieldArrayGrid('Feb', FIELD_STRING);
		$this->setFieldArrayGrid('Gen', FIELD_STRING);
		$this->setFieldArrayGrid('Giu', FIELD_STRING);
		$this->setFieldArrayGrid('id', FIELD_STRING);
		$this->setFieldArrayGrid('ids', FIELD_STRING);
		$this->setFieldArrayGrid('Lug', FIELD_STRING);
		$this->setFieldArrayGrid('Mag', FIELD_STRING);
		$this->setFieldArrayGrid('Mar', FIELD_STRING);
		$this->setFieldArrayGrid('Nov', FIELD_STRING);
		$this->setFieldArrayGrid('Ott', FIELD_STRING);
		$this->setFieldArrayGrid('Set', FIELD_STRING);
		$this->setFieldArrayGrid('id', FIELD_NUMERIC);

	}


    /**
     * 
     * Ritorna il json usato dalla griglia datatable
     * @param array $searchFilter
     * @return JSON
     */
    public function json(Array $searchFilter)
    {
		//print'<pre>';print_r($_REQUEST['searchFilter']);die();
		$searchFilterObj = new stdClass();
		if(isset($_REQUEST['searchFilter'])){
			foreach($_REQUEST['searchFilter'] as $k => $v){
				$searchFilterObj->{$v['field']} = $v['value'];
			}
		}
		$dt_Str = '';
		$json = "";	
 
		$idEserc = NULL;
		if((isset($searchFilterObj->mod_esercizi_id))){
			$idEserc = $searchFilterObj->mod_esercizi_id;
		}
		$listaCorsi = NULL;
		if((isset($searchFilterObj->mod_corsi_id))){		
			$listaCorsi = $searchFilterObj->mod_corsi_id;
		}	
		$anagrafica = NULL;
		if((isset($searchFilterObj->Anagrafica))){	
			$anagrafica = $searchFilterObj->Anagrafica;
		}
		$corsi_nome = NULL;
		if((isset($searchFilterObj->mod_corsi_nome))){		
			$corsi_nome = $searchFilterObj->mod_corsi_nome;
		}
		$corsi_tipo = NULL;
		if((isset($searchFilterObj->mod_corsi_tipo))){		
			$corsi_tipo = $searchFilterObj->mod_corsi_tipo;
		}		
		$data_iscrizione = NULL;
		if((isset($searchFilterObj->Data_Iscrizione))){
			$data_iscrizione = $searchFilterObj->Data_Iscrizione;
		}
		$Sett = NULL; 
		if((isset($searchFilterObj->Set))){
			$Sett = $searchFilterObj->Set;
		}
 	
		$Ott = NULL;
		if((isset($searchFilterObj->Ott))){
			$Ott = $searchFilterObj->Ott;
		}			
		$Nov = NULL;
		if((isset($searchFilterObj->Nov))){
			$Nov = $searchFilterObj->Nov;
		}		
		$Dic = NULL;
		if((isset($searchFilterObj->Dic))){
			$Dic = $searchFilterObj->Dic;
		}			
		$Gen = NULL; 
		if((isset($searchFilterObj->Gen))){
			$Gen = $searchFilterObj->Gen;
		}		
		$Feb = NULL;  
		if((isset($searchFilterObj->Feb))){
			$Feb = $searchFilterObj->Feb;
		}			
		$Mar = NULL; 
		if((isset($searchFilterObj->Mar))){	
			$Mar = $searchFilterObj->Mar;
		}		
		$Apr = NULL;  
		if((isset($searchFilterObj->Apr))){		
			$Apr = $searchFilterObj->Apr;
		}			
		$Mag = NULL; 
		if((isset($searchFilterObj->Mag))){			
			$Mag = $searchFilterObj->Mag;
		}			
		$Giu = NULL; 
		if((isset($searchFilterObj->Giu))){		
			$Giu = $searchFilterObj->Giu;
		}			
		$Lug = NULL;  
		if((isset($searchFilterObj->Lug))){			
			$Lug = $searchFilterObj->Lug;
		}			
		$Ago = NULL;
		if((isset($searchFilterObj->Ago))){			
			$Ago = $searchFilterObj->Ago;
		}					
		$datatables_gen = $this->getPagamentiMensili($idEserc, $listaCorsi, $anagrafica, $corsi_nome,
								$data_iscrizione, $Sett, $Ott, $Nov, $Dic, $Gen, $Feb,
								$Mar, $Apr, $Mag, $Giu, $Lug, $Ago, $corsi_tipo);
						
		$data = json_decode(json_encode($datatables_gen));
		$recordsTotal = 0;
		$recordsFiltered = 0;

		//PRINT'<PRE>';print_r($data);
		foreach($data as $key=> $array){
            if($recordsTotal > 0){
                $dt_Str .= ',{';
            } else {
                $dt_Str .= '{';
            }
            
            $count = 0;
            foreach($array as $k => $v){
                if($v == ""){
                    $v = '""';
                } else {
                    $v = '"'.$v.'"';
                }               
                if($count > 0){
                    $dt_Str .= ',"'.$k.'":'.$v;
                } else {
                    $dt_Str .= '"'.$k.'":'.$v;
                } 
                $count++;               
            }

			$recordsTotal++;
			$recordsFiltered++;

            $dt_Str .= '}';
		}
		$json .="{\"draw\":".intval($_POST['draw']).",\"recordsTotal\":$recordsTotal,\"recordsFiltered\":$recordsFiltered,\"data\":[".$dt_Str."]}";

		//echo  $this->db->last_query();	
 
        return $json;
    }


	public function getPagamentiMensili($idEserc = NULL, $listaCorsi = NULL, $anagrafica = NULL, $corsi_nome = NULL,
			$data_iscrizione = NULL, $Sett = NULL, $Ott = NULL, $Nov = NULL, $Dic = NULL, $Gen = NULL, $Feb = NULL,
			$Mar = NULL, $Apr = NULL, $Mag = NULL, $Giu = NULL, $Lug = NULL, $Ago = NULL, $corsi_tipo = NULL) {

		$sql = "SELECT 
				id,
				mod_esercizi_id,
				mod_affiliazioni_id,    
				ids,
				Anagrafica,
				Data_Iscrizione,    
				mod_corsi_id,
				mod_corsi_nome,
				mod_corsi_tipo,
				CASE 
					WHEN Sett IS NULL THEN '-'
					ELSE Sett
				END AS Sett,
				CASE 
					WHEN Ott IS NULL THEN '-'
					ELSE Ott
				END AS Ott,
				CASE 
					WHEN Nov IS NULL THEN '-'
					ELSE Nov
				END AS Nov,
				CASE 
					WHEN Dic IS NULL THEN '-'
					ELSE Dic
				END AS Dic,
				CASE 
					WHEN Gen IS NULL THEN '-'
					ELSE Gen
				END AS Gen,
				CASE 
					WHEN Feb IS NULL THEN '-'
					ELSE Feb
				END AS Feb,
				CASE 
					WHEN Mar IS NULL THEN '-'
					ELSE Mar
				END AS Mar,				
				CASE 
					WHEN Apr IS NULL THEN '-'
					ELSE Apr
				END AS Apr,
				CASE 
					WHEN Mag IS NULL THEN '-'
					ELSE Mag
				END AS Mag,
				CASE 
					WHEN Giu IS NULL THEN '-'
					ELSE Giu
				END AS Giu,
				CASE 
					WHEN Lug IS NULL THEN '-'
					ELSE Lug
				END AS Lug,
				CASE 
					WHEN Ago IS NULL THEN '-'
					ELSE Ago
				END AS Ago
				FROM report_pagamenti_mensili_cors_v WHERE 1=1 ";
	 
		if(($idEserc != NULL) && ($idEserc != "") && ($idEserc != "NULL")){
			$sql .= " AND mod_esercizi_id = ".$idEserc;
		}
		if(($listaCorsi != NULL) && ($listaCorsi != "") && ($listaCorsi != "NULL")){
			//print'<pre>';print_r($listaCorsi);die();
			if(is_array($listaCorsi)){
				$sql .= " AND mod_corsi_id in (".implode(",",$listaCorsi).")";
			} else {
				$sql .= " AND mod_corsi_id in (".$listaCorsi.")";
			}
			
		}
		if(($anagrafica != NULL) && ($anagrafica != "") && ($anagrafica != "NULL")){
			$sql .= " AND UPPER(Anagrafica) LIKE '%".strtoupper($anagrafica)."%'";
		}		
		if(($corsi_nome != NULL) && ($corsi_nome != "") && ($corsi_nome != "NULL")){
			$sql .= " AND mod_corsi_nome LIKE '%".$corsi_nome."%'";
		}	
		if(($corsi_tipo != NULL) && ($corsi_tipo != "") && ($corsi_tipo != "NULL")){
			$sql .= " AND  mod_corsi_tipo LIKE '%".strtoupper($corsi_tipo)."%'";
		}	

		if(($data_iscrizione != NULL) && ($data_iscrizione != "") && ($data_iscrizione != "NULL")){
			$sql .= " AND Data_Iscrizione = '".$data_iscrizione."'";
		}		

		//$sql .= " AND (";
		if(($Sett != NULL) && ($Sett != "") && ($Sett != "NULL")){
			$sql .= " AND Sett  LIKE '%".$Sett."%'";
		}	
		 
		if(($Ott != NULL) && ($Ott != "") && ($Ott != "NULL")){
			$sql .= " AND Ott LIKE '%".$Ott."%'";
		}	
		if(($Nov != NULL) && ($Nov != "") && ($Nov != "NULL")){	
			$sql .= " AND Nov  LIKE '%".$Nov."%'";
		}		
		if(($Dic != NULL) && ($Dic != "") && ($Dic != "NULL")){	
			$sql .= " AND Dic LIKE '%".$Dic."%'";
		}		
		if(($Gen != NULL) && ($Gen != "") && ($Gen != "NULL")){	
			$sql .= " AND Gen  LIKE '%".$Gen."%'";
		}	
		if(($Feb != NULL) && ($Feb != "") && ($Feb != "NULL")){		
			$sql .= " AND Feb  LIKE '%".$Feb."%'";
		}	
		if(($Mar != NULL) && ($Mar != "") && ($Mar != "NULL")){			
			$sql .= " AND Mar  LIKE '%".$Mar."%'";
		}	
		if(($Apr != NULL) && ($Apr != "") && ($Apr != "NULL")){				
			$sql .= " AND Apr LIKE '%".$Apr."%'";
		}	
		if(($Mag != NULL) && ($Mag != "") && ($Mag != "NULL")){			
			$sql .= " AND Mag  LIKE '%".$Mag."%'";
		}	
		if(($Giu != NULL) && ($Giu != "") && ($Giu != "NULL")){		
			$sql .= " AND Giu  LIKE '%".$Giu."%'";
		}
		if(($Lug != NULL) && ($Lug != "") && ($Lug != "NULL")){		
			$sql .= " AND Lug  LIKE '%".$Lug."%'";
		}	
		if(($Ago != NULL) && ($Ago != "") && ($Ago != "NULL")){				
			$sql .= " AND Ago  LIKE '%".$Ago."%'";
		}
		//$sql .= " )";
				
		//$sql .= " AND saldo <> 'NON PAGHERA' ";						
		//echo($sql);die();
		$row =  $this->db->query($sql)->result_array();	

		return $row;
	}



	
	public function populateCorsi($esercId){
		$sql = "SELECT mod_corsi.id,mod_corsi.nome
			FROM mod_corsi
			INNER JOIN mod_affiliazioni 
				ON mod_affiliazioni.id = mod_corsi.fk_affiliazione
			INNER JOIN mod_esercizi 
				ON mod_esercizi.id = mod_affiliazioni.fk_esercizio
				WHERE mod_esercizi.id = ".$esercId;	
		//echo $sql;		
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



}