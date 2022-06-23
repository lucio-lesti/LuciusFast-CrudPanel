<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Mod_crud_gen_model extends CI_Model
{

    public $table = NULL;
    public $id = NULL;
    public $order = NULL;
	public $dbdriver = NULL;
	public $database = NULL;

	
    public function __construct(){
        parent::__construct();
		$this->database = $this->db->database;
		$this->dbdriver = $this->db->dbdriver;		
    }


    public function table_list()
    {
		switch($this->dbdriver){	
			case 'mysql':
			case 'mysqli':
				$query = "SELECT TABLE_NAME 
							FROM INFORMATION_SCHEMA.TABLES 
							WHERE TABLE_SCHEMA='".$this->database."'";
				$query .= " AND TABLE_NAME NOT LIKE 'core_%' ";
				/*
				$query .= " AND TABLE_NAME NOT LIKE 'core_%' 
							AND TABLE_NAME NOT LIKE '_mod%' ";	
				*/	
				$query .= " ORDER BY TABLE_NAME";
			break;
			
			case 'postgre':
				$query = "SELECT TABLE_NAME 
							FROM INFORMATION_SCHEMA.TABLES 
							WHERE TABLE_SCHEMA='public'";
				$query .= " AND TABLE_NAME NOT LIKE 'core_%' ";
				/*
				$query .= " AND TABLE_NAME NOT LIKE 'core_%' 
							AND TABLE_NAME NOT LIKE '_mod%' ";	
				*/	
				$query .= " ORDER BY TABLE_NAME";
			break;
			
			
			case 'mssql':
			
			break;
		}	
		 
		$res = $this->db->query($query)->result_array();
		$fields = array();
		foreach ($res as $key => $row) {
			$fields[]['TABLE_NAME'] = $row['TABLE_NAME'];
		}
		
		return $fields;
    }


	public function table_list_detail(){
		switch($this->dbdriver){	
			case 'mysql':
			case 'mysqli':
				$query = "SELECT TABLE_NAME 
							FROM INFORMATION_SCHEMA.TABLES 
							WHERE TABLE_SCHEMA='".$this->database."'";
				$query .= " AND TABLE_NAME NOT LIKE 'core_%' 
							AND TABLE_NAME LIKE '_mod%' ";
				$query .= " ORDER BY TABLE_NAME";
			break;
			
			case 'postgre':
				$query = "SELECT TABLE_NAME 
							FROM INFORMATION_SCHEMA.TABLES 
							WHERE TABLE_SCHEMA='public'";
				$query .= " AND TABLE_NAME NOT LIKE 'core_%' 
							AND TABLE_NAME LIKE '_mod%' ";
				$query .= " ORDER BY TABLE_NAME";
			break;
			
			
			case 'mssql':
			
			break;
		}	
		 
		$res = $this->db->query($query)->result_array();
		$fields = array();
		foreach ($res as $key => $row) {
			$fields[]['TABLE_NAME'] = $row['TABLE_NAME'];
		}
		
		return $fields;		
	}
	
    public function primary_field($table)
    {
		switch($this->dbdriver){	
			case 'mysql':
			case 'mysqli':		
				$query = "SELECT TABLE_NAME,COLUMN_NAME,COLUMN_KEY 
							FROM INFORMATION_SCHEMA.COLUMNS 
							WHERE TABLE_SCHEMA='".$this->database."' 
							AND TABLE_NAME='".$table."' 
							AND COLUMN_KEY = 'PRI'";
			break;
        
			case 'postgre':
				$query = "SELECT '$table' AS TABLE_NAME,  a.attname AS COLUMN_NAME, 'PRI' AS COLUMN_KEY
							FROM   pg_index i
							INNER JOIN   pg_attribute a ON a.attrelid = i.indrelid
								AND a.attnum = ANY(i.indkey)
							WHERE  i.indrelid = '".$table."'::regclass
							AND    i.indisprimary;";
			break;	

			case 'mssql':
			
			break;
			
		}			
			
		$row = $this->db->query($query)->result_array();
		
		$COLUMN_NAME = "";
		if(isset($row['COLUMN_NAME'])){
			$COLUMN_NAME = $row['COLUMN_NAME'];		
		} else {
			if(isset($row[0]['COLUMN_NAME'])){
				$COLUMN_NAME = $row[0]['COLUMN_NAME'];	
			}
		}			
		
		
		return $COLUMN_NAME;	
 
    }

	
    public function not_primary_field($table)
    {
		switch($this->dbdriver){	
			case 'mysql':
			case 'mysqli':			
				$query = "SELECT TABLE_NAME,COLUMN_NAME,COLUMN_KEY,DATA_TYPE,COLUMN_TYPE,CHARACTER_MAXIMUM_LENGTH,
								NUMERIC_PRECISION,NUMERIC_SCALE,COLUMN_COMMENT,IS_NULLABLE 
							FROM INFORMATION_SCHEMA.COLUMNS 
							WHERE TABLE_SCHEMA='".$this->database."' 
							AND TABLE_NAME='".$table."'
							AND COLUMN_KEY <> 'PRI'";
			break;
			
			case 'postgre':
				$query = "SELECT '$table' AS TABLE_NAME, COLUMN_NAME,'' as COLUMN_KEY,DATA_TYPE,DATA_TYPE as COLUMN_TYPE,CHARACTER_MAXIMUM_LENGTH,
								NUMERIC_PRECISION,NUMERIC_SCALE,
								(
									SELECT
										pg_catalog.col_description(c.oid, cols.ordinal_position::int)
									FROM
										pg_catalog.pg_class c
									WHERE
										c.oid = (SELECT ('\"' || cols.TABLE_NAME || '\"')::regclass::oid)
										AND c.relname = cols.TABLE_NAME
								) AS COLUMN_COMMENT,
								IS_NULLABLE 
							FROM INFORMATION_SCHEMA.COLUMNS cols
							WHERE TABLE_SCHEMA='public' 
							AND TABLE_NAME='".$table."'
							AND COLUMN_NAME NOT IN 
							(
								SELECT a.attname AS COLUMN_NAME
								FROM   pg_index i
								INNER JOIN   pg_attribute a ON a.attrelid = i.indrelid
									AND a.attnum = ANY(i.indkey)
								WHERE  i.indrelid = '".$table."'::regclass
								AND    i.indisprimary							
							)
							";			
			break;
			
			case 'mssql':
			
			break;			
		}	

		$res = $this->db->query($query)->result_array();
		$fields = array();
		$fieldNr = 0;
		foreach ($res as $key => $row) {
			$fields[$fieldNr]['TABLE_NAME'] = $row['TABLE_NAME'];
			$fields[$fieldNr]['COLUMN_NAME'] = $row['COLUMN_NAME'];
			$fields[$fieldNr]['COLUMN_KEY'] = $row['COLUMN_KEY'];
			$fields[$fieldNr]['DATA_TYPE'] = $row['DATA_TYPE'];
			$fields[$fieldNr]['CHARACTER_MAXIMUM_LENGTH'] = $row['CHARACTER_MAXIMUM_LENGTH'];
			$fields[$fieldNr]['NUMERIC_PRECISION'] = $row['NUMERIC_PRECISION'];
			$fields[$fieldNr]['NUMERIC_SCALE'] = $row['NUMERIC_SCALE'];
			$fields[$fieldNr]['COLUMN_COMMENT'] = $row['COLUMN_COMMENT'];
			$fields[$fieldNr]['IS_NULLABLE'] = $row['IS_NULLABLE'];
			$fields[$fieldNr]['COLUMN_TYPE'] = $row['COLUMN_TYPE'];
			$fieldNr++;
		}
		
		//print'<pre>';print_r($fields);

		return $fields;		
		
		
    }

    public function all_field($table)
    {
		switch($this->dbdriver){	
			case 'mysql':
			case 'mysqli':			
				$query = "SELECT TABLE_NAME,COLUMN_NAME,COLUMN_KEY,DATA_TYPE,COLUMN_TYPE,CHARACTER_MAXIMUM_LENGTH,
								NUMERIC_PRECISION,NUMERIC_SCALE,COLUMN_COMMENT,
								IS_NULLABLE,DATETIME_PRECISION,EXTRA 
							FROM INFORMATION_SCHEMA.COLUMNS 
							WHERE TABLE_SCHEMA='".$this->database."' 
							AND TABLE_NAME='".$table."'";
			break;
			
			case 'postgre':
				$query ="SELECT '$table' AS TABLE_NAME, COLUMN_NAME,
						CASE 
						WHEN COLUMN_NAME IN 
							(
								SELECT a.attname AS COLUMN_NAME,DATA_TYPE,DATA_TYPE as COLUMN_TYPE,
								FROM   pg_index i
								INNER JOIN   pg_attribute a ON a.attrelid = i.indrelid
									AND a.attnum = ANY(i.indkey)
								WHERE  i.indrelid = '".$table."'::regclass
								AND    i.indisprimary							
							) THEN 'PR' ELSE ''
						END AS COLUMN_KEY,
						CHARACTER_MAXIMUM_LENGTH,
						NUMERIC_PRECISION,NUMERIC_SCALE,
						(
							SELECT
								pg_catalog.col_description(c.oid, cols.ordinal_position::int)
							FROM
								pg_catalog.pg_class c
							WHERE
								c.oid = (SELECT ('\"' || cols.TABLE_NAME || '\"')::regclass::oid)
								AND c.relname = cols.TABLE_NAME
						) AS COLUMN_COMMENT, IS_NULLABLE,DATETIME_PRECISION, '' AS EXTRA 
						FROM INFORMATION_SCHEMA.COLUMNS cols
							WHERE TABLE_SCHEMA='public' 
						AND TABLE_NAME='".$table."'";
			break;
			
			case 'mssql':
			
			break;				
		}	
		
		$res = $this->db->query($query)->result_array();
		$fields = array();
		$fieldNr = 0;
		foreach ($res as $key => $row) {		
			$fields[$fieldNr]['TABLE_NAME'] = $row['TABLE_NAME'];	
			$fields[$fieldNr]['COLUMN_NAME'] = $row['COLUMN_NAME'];
			$fields[$fieldNr]['COLUMN_KEY'] = $row['COLUMN_KEY'];
			$fields[$fieldNr]['DATA_TYPE'] = $row['DATA_TYPE'];
			$fields[$fieldNr]['CHARACTER_MAXIMUM_LENGTH'] = $row['CHARACTER_MAXIMUM_LENGTH'];
			$fields[$fieldNr]['NUMERIC_PRECISION'] = $row['NUMERIC_PRECISION'];
			$fields[$fieldNr]['NUMERIC_SCALE'] = $row['NUMERIC_SCALE'];
			$fields[$fieldNr]['COLUMN_COMMENT'] = $row['COLUMN_COMMENT'];
			$fields[$fieldNr]['IS_NULLABLE'] = $row['IS_NULLABLE'];
			$fields[$fieldNr]['DATETIME_PRECISION'] = $row['DATETIME_PRECISION'];
			$fields[$fieldNr]['EXTRA'] = $row['EXTRA'];
			$fields[$fieldNr]['COLUMN_TYPE'] = $row['COLUMN_TYPE'];
			$fieldNr++;
		}		
        return $fields;
 
    }
	
	
	public function execSQL($query, $isPersistentQry = FALSE){
		if($isPersistentQry == TRUE){
			$res = $this->db->query($query);
		} else {
			$res = $this->db->query($query)->result_array();
		}
		
		return $res;
	}


	public function getTableRef($table){
		switch($this->dbdriver){	
			case 'mysql':
			case 'mysqli':			
				$query = "SELECT TABLE_NAME,COLUMN_NAME,CONSTRAINT_NAME, 
							REFERENCED_TABLE_NAME,REFERENCED_COLUMN_NAME 
						FROM INFORMATION_SCHEMA.KEY_COLUMN_USAGE 
						WHERE REFERENCED_TABLE_SCHEMA = '".$this->database."' 
							AND REFERENCED_TABLE_NAME = '".$table."'
							AND TABLE_NAME LIKE '%_mod%'";	 
				//echo $query."<br><br>";			
			break;
			
			case 'postgre':
			break;
			
			case 'mssql':
			break;				
		}	


		$res = $this->db->query($query)->result_array();
		$fields = array();
		foreach ($res as $key => $row) {
			$fields[$row['TABLE_NAME']]['TABLE_NAME'] = $row['TABLE_NAME'];
			$fields[$row['TABLE_NAME']]['COLUMN_NAME'] = $row['COLUMN_NAME'];
			$fields[$row['TABLE_NAME']]['REFERENCED_TABLE_NAME'] = $row['REFERENCED_TABLE_NAME'];
			$fields[$row['TABLE_NAME']]['REFERENCED_COLUMN_NAME'] = $row['REFERENCED_COLUMN_NAME'];
			$fields[$row['TABLE_NAME']]['CONSTRAINT_NAME'] = $row['CONSTRAINT_NAME'];


			$fields[$row['TABLE_NAME']]['COLUMNS_REFERENCED'] = $this->getFieldsRef($row['TABLE_NAME']);
		}
		
		//print'<pre>';print_r($fields);

		return $fields;

	}
	
	
	public function getFieldsRef($table){
		switch($this->dbdriver){	
			case 'mysql':
			case 'mysqli':			
				$query = "SELECT TABLE_NAME,COLUMN_NAME,REFERENCED_TABLE_NAME,REFERENCED_COLUMN_NAME
							FROM INFORMATION_SCHEMA.KEY_COLUMN_USAGE 
							WHERE REFERENCED_TABLE_SCHEMA = '".$this->database."' 
							AND TABLE_NAME = '".$table."'";			
				//echo $query."<br><br>";			
			break;
			
			case 'postgre':
			break;
			
			case 'mssql':
			
			break;				
		}	


		$res = $this->db->query($query)->result_array();
		$fields = array();
		foreach ($res as $key => $row) {
			$fields[$row['COLUMN_NAME']]['TABLE_NAME'] = $row['TABLE_NAME'];
			$fields[$row['COLUMN_NAME']]['COLUMN_NAME'] = $row['COLUMN_NAME'];
			$fields[$row['COLUMN_NAME']]['REFERENCED_TABLE_NAME'] = $row['REFERENCED_TABLE_NAME'];
			$fields[$row['COLUMN_NAME']]['REFERENCED_COLUMN_NAME'] = $row['REFERENCED_COLUMN_NAME'];
			$fields[$row['COLUMN_NAME']]['REFERENCED_COLUMN_NAME_TO_SHOW'] = "nome";
		}
		
		//print'<pre>';print_r($fields);

		return $fields;			
	}


	public function getColumnsTable($table){
		$fields = array();
 
		switch($this->dbdriver){	
			case 'mysql':
			case 'mysqli':			
				$query = "SELECT TABLE_NAME,COLUMN_NAME,COLUMN_KEY,DATA_TYPE,COLUMN_TYPE,CHARACTER_MAXIMUM_LENGTH,
								NUMERIC_PRECISION,NUMERIC_SCALE,COLUMN_COMMENT,IS_NULLABLE,DATETIME_PRECISION,EXTRA 
							FROM INFORMATION_SCHEMA.COLUMNS 
							WHERE TABLE_SCHEMA = '".$this->database."' 
							AND TABLE_NAME = '".$table."'";			
			break;
			
			case 'postgre':
			break;
			
			case 'mssql':
			
			break;				
		}	

		$res = $this->db->query($query)->result_array();
		
		$cont = 0;
		//$fields[$table]['COLUMNS_REFERENCED'] = $this->getFieldsRef($table);	
		foreach ($res as $k => $row) {
			$fields[$cont]['TABLE_NAME'] = $row['TABLE_NAME'];	
			$fields[$cont]['COLUMN_NAME'] = $row['COLUMN_NAME'];
			$fields[$cont]['COLUMN_KEY'] = $row['COLUMN_KEY'];
			$fields[$cont]['DATA_TYPE'] = $row['DATA_TYPE'];
			$fields[$cont]['CHARACTER_MAXIMUM_LENGTH'] = $row['CHARACTER_MAXIMUM_LENGTH'];
			$fields[$cont]['NUMERIC_PRECISION'] = $row['NUMERIC_PRECISION'];
			$fields[$cont]['NUMERIC_SCALE'] = $row['NUMERIC_SCALE'];
			$fields[$cont]['COLUMN_COMMENT'] = $row['COLUMN_COMMENT'];
			$fields[$cont]['IS_NULLABLE'] = $row['IS_NULLABLE'];
			$fields[$cont]['DATETIME_PRECISION'] = $row['DATETIME_PRECISION'];
			$fields[$cont]['EXTRA'] = $row['EXTRA'];
			$fields[$cont]['COLUMN_TYPE'] = $row['COLUMN_TYPE'];

			$cont++;
		}			

		//PRINT'<pre>';print_r($fields);

		return $fields;			
	}



	public function getColumnsTableRef($arrayTables){
		$fields = array();
		foreach($arrayTables as $key => $val){
			switch($this->dbdriver){	
				case 'mysql':
				case 'mysqli':			
					$query = "SELECT TABLE_NAME,COLUMN_NAME,COLUMN_KEY,DATA_TYPE,COLUMN_TYPE,CHARACTER_MAXIMUM_LENGTH,
									NUMERIC_PRECISION,NUMERIC_SCALE,COLUMN_COMMENT,IS_NULLABLE,DATETIME_PRECISION,EXTRA 
								FROM INFORMATION_SCHEMA.COLUMNS 
								WHERE TABLE_SCHEMA = '".$this->database."' 
								AND TABLE_NAME = '".$key."'";			
				break;
				
				case 'postgre':
				break;
				
				case 'mssql':
				
				break;				
			}	

			$res = $this->db->query($query)->result_array();
			
			$cont = 0;
			$fields[$key]['COLUMNS_REFERENCED'] = $this->getFieldsRef($key);	
			foreach ($res as $k => $row) {
				$fields[$row['TABLE_NAME']][$cont]['TABLE_NAME'] = $row['TABLE_NAME'];	
				$fields[$row['TABLE_NAME']][$cont]['COLUMN_NAME'] = $row['COLUMN_NAME'];
				$fields[$row['TABLE_NAME']][$cont]['COLUMN_KEY'] = $row['COLUMN_KEY'];
				$fields[$row['TABLE_NAME']][$cont]['DATA_TYPE'] = $row['DATA_TYPE'];
				$fields[$row['TABLE_NAME']][$cont]['CHARACTER_MAXIMUM_LENGTH'] = $row['CHARACTER_MAXIMUM_LENGTH'];
				$fields[$row['TABLE_NAME']][$cont]['NUMERIC_PRECISION'] = $row['NUMERIC_PRECISION'];
				$fields[$row['TABLE_NAME']][$cont]['NUMERIC_SCALE'] = $row['NUMERIC_SCALE'];
				$fields[$row['TABLE_NAME']][$cont]['COLUMN_COMMENT'] = $row['COLUMN_COMMENT'];
				$fields[$row['TABLE_NAME']][$cont]['IS_NULLABLE'] = $row['IS_NULLABLE'];
				$fields[$row['TABLE_NAME']][$cont]['DATETIME_PRECISION'] = $row['DATETIME_PRECISION'];
				$fields[$row['TABLE_NAME']][$cont]['EXTRA'] = $row['EXTRA'];
				$fields[$row['TABLE_NAME']][$cont]['COLUMN_TYPE'] = $row['COLUMN_TYPE'];

				$cont++;
			}			
		}

		//PRINT'<pre>';print_r($fields);

		return $fields;			
	}



	public function getTablesTitle(){
		switch($this->dbdriver){	
			case 'mysql':
			case 'mysqli':			
				$query = "SELECT * FROM core_table_title";			
			break;
			
			case 'postgre':
			break;
			
			case 'mssql':
			
			break;				
		}	


		$res = $this->db->query($query)->result_array();
		$fields = array();
		foreach ($res as $key => $row) {
			$fields[$row['name_of_table']]['name_of_table'] = $row['name_of_table'];
			$fields[$row['name_of_table']]['title_of_table'] = $row['title_of_table'];
		}
		
		return $fields;			
	}


}
