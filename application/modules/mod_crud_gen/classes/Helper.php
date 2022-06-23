<?php

class Helper
{
		
	public function safe($str)
	{
		if(!is_array($str)){
			$str = strip_tags(trim($str));
		}
		return $str;
	}

	
	public function readJSON($path)
	{
		$string = file_get_contents($path);
		$obj = json_decode($string);
		return $obj;
	}

	
	public function createFile($string, $path)
	{
		//print'<pre>';print_r($string);
		//print'<pre>';print_r($path);
		//die();
		$create = fopen($path, "w") or die("Change your permision folder for application and harviacode folder to 777");
		fwrite($create, $string);
		fclose($create);
		
		return $path;
	}

	
	public function label($str)
	{
		$label = str_replace('_', ' ', $str);
		$label = ucwords($label);
		return $label;
	}		
	
	public function add_quotes($str) {
		return sprintf("`%s`", $str);
	}

	
	public function add_quotes_pg($str) {
		return sprintf("'%s'", $str);
	}	
}