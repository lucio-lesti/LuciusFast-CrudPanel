<?php defined('BASEPATH') or exit('No direct script access allowed');



/**
 * Class : Utilities (Utilities)
 * @author : Lucio Lesti
 * @version : 1.0
 * @since : 20.09.2021
 */
class Utilities
{


	/**
	 * 
	 * Aggiunge le virgolette in una stringa
	 * @param mixed $str
	 * @return string
	 */
	public function add_quotes($str)
	{
		return sprintf("'%s'", $str);
	}



	/**
	 * 
	 * Ritorna il primo lunedi di un mese
	 * @param mixed $anno
	 * @param mixed $mese
	 * @return string
	 */
	public function getFirstMondayOfMonth($anno, $mese)
	{
		switch ($mese) {
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
		$FirstMondayOfMonth = date('d/m/Y', strtotime("first monday of $month $anno"));
		return $FirstMondayOfMonth;
	}


	/**
	 * 
	 * Ritorna il primo giorno lavorativa di un mese
	 * @param mixed $anno
	 * @param mixed $mese
	 * @return string
	 */
	public function getFirstWorkingDayOfMonth($anno, $mese)
	{
		switch ($mese) {
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
		$publicHolidays = ['01-01', '06-01', '04-25', '05-01', '06-02', '08-15', '11-01', '12-08', '12-25', '12-26', "$pasquetta"]; // Format: mm-dd
		while (true) {
			if ($date->format("N") >= 6) {
				$date->modify("+" . (8 - $date->format("N")) . " days");
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
	public function getPasquetta($anno)
	{
		$pasquetta = "";
		switch ($anno) {
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


	/**
	 * 
	 * Elimina un directory
	 * @param mixed $src
	 */
	public function delete_dir($src)
	{
		$dir = opendir($src);
		while (false !== ($file = readdir($dir))) {
			if (($file != '.') && ($file != '..')) {
				if (is_dir($src . '/' . $file)) {
					$this->delete_dir($src . '/' . $file);
				} else {
					unlink($src . '/' . $file);
				}
			}
		}
		closedir($dir);
		rmdir($src);
	}




	/**
	 * 
	 * Valida una data di tipo date con vari formati
	 * @param mixed $date
	 * @param mixed $format
	 * @return bool 
	 */
	public function validateAllDateType($date, $format = 'd/m/Y')
	{
		$d = DateTime::createFromFormat($format, $date);
		return $d && $d->format($format) === $date;
	}


	/**
	 * 
	 * Valida una data di tipo date formato italiano
	 * @param mixed $date
	 * @return bool 
	 */
	public function checkValidDateIT($date)
	{
		$check = FALSE;
		$tempDate = explode('/', $date);
		if ((isset($tempDate[0])) && (isset($tempDate[1])) && (isset($tempDate[2]))) {
			echo $tempDate[0] . "-" . $tempDate[1] . "-" . $tempDate[2];
			$check = checkdate($tempDate[1], $tempDate[0], $tempDate[2]);
		}
		return $check;
	}



	/**
	 * 
	 * Valida una data di tipo date formato inglese
	 * @param mixed $date
	 * @return bool 
	 */
	public function checkValidDateEN($date)
	{
		$check = FALSE;
		$tempDate = explode('-', $date);
		if ((isset($tempDate[0])) && (isset($tempDate[1])) && (isset($tempDate[2]))) {
			$check = checkdate($tempDate[1], $tempDate[2], $tempDate[0]);
		}

		return $check;
	}


	/**
	 * 
	 * Valida una data di tipo date
	 * @param mixed $date
	 * @return bool 
	 */
	public function valid_date($date)
	{
		$d = DateTime::createFromFormat('d/m/Y', $date);
		return $d && $d->format('d/m/Y') === $date;
	}


	/**
	 * 
	 * Valida una data di tipo datetime
	 * @param mixed $date_time
	 * @return bool 
	 */
	public function valid_date_time($date_time)
	{
		$d = DateTime::createFromFormat('d/m/Y H:i:s', $date_time);
		return $d && $d->format('d/m/Y H:i:s') === $date_time;
	}


	/**
	 * 
	 * Converte un tipo data  in formato inglese
	 * @param mixed $dateIT
	 * @return array 
	 */
	public function convertToDateEN($dateIT)
	{
		$dateArray = explode("/", $dateIT);
		$dateEN = $dateArray[2] . "-" . $dateArray[1] . "-" . $dateArray[0];

		return (string)$dateEN;
	}


	/**
	 * 
	 * Converte un tipo datetime  in formato inglese
	 * @param mixed $dateIT
	 * @return array 
	 */
	public function convertToDateTimeEN($dateIT)
	{
		$dateArray = explode("/", $dateIT);
		$year = $dateArray[2];
		$month = $dateArray[1];
		$arrayDayHour = explode(" ", $dateArray[0]);
		$day = $arrayDayHour[0];
		$HourMin = $arrayDayHour[1];

		$dateEN = $year . "-" . $month . "-" . $day . " " . $HourMin;

		return $dateEN;
	}


	/**
	 * 
	 * Converte un tipo data  in formato italiano
	 * @param mixed $dateEN
	 * @return array 
	 */
	public function convertToDateIT($dateEN)
	{
		$dateArray = explode("-", $dateEN);
		//print'<pre>';print_r($dateArray);print'</pre>';
		$dateIT = $dateArray[2] . "/" . $dateArray[1] . "/" . $dateArray[0];

		return $dateIT;
	}


	/**
	 * 
	 * Converte un tipo datetime  in formato italiano
	 * @param mixed $dateEN
	 * @return array 
	 */
	public function convertToDateTimeIT($dateEN)
	{
		$dateEN = "2021-12-21 12:45";
		$dateArray = explode("-", $dateEN);
		$year = $dateArray[0];
		$month = $dateArray[1];
		$arrayDayHour = explode(" ", $dateArray[2]);
		$day = $arrayDayHour[0];
		$HourMin = $arrayDayHour[1];


		$dateIT = $day . "/" . $month . "/" . $year . " " . $HourMin;

		return $dateIT;
	}



	/**
	 * 
	 * Verifica se una data è maggiore di un'altra
	 * @param mixed $dateFromIT
	 * @param mixed $dateToIT
	 */
	public function check_date_greater_then($dateFromIT, $dateToIT)
	{
		$dateFrom = $this->convertToDateEN($dateFromIT);
		$dateTo = $this->convertToDateEN($dateToIT);
		if ($dateFrom > $dateTo) {
			return false;
		} else {
			return true;
		}
	}



	/**
	 * 
	 * Verifica se una data ora è valida
	 * @param mixed $str
	 */
	public function validate_hour($str)
	{
		$checked = false;
		if (preg_match('/^\d{2}:\d{2}$/', $str)) {
			if (preg_match("/(2[0-3]|[0][0-9]|1[0-9]):([0-5][0-9])/", $str)) {
				$checked = true;
			}
		}

		return $checked;
	}



	/**
	 * 
	 * Verifica se un persone è maggiorenne
	 * @param mixed $dataNascita
	 * @param mixed/null $limitAge
	 */
	public function check_minorenne($dataNascitaIT, $limitAge = NULL)
	{
		if ($limitAge == NULL) {
			$limitAge = '18';
		}
		$dataNascitaEN =  $this->convertToDateEN($dataNascitaIT);

		$isMinorenne = FALSE;
		if (time() < strtotime('+' . $limitAge . ' years', strtotime($dataNascitaEN))) {
			$isMinorenne = TRUE;
		}

		return $isMinorenne;
	}


	/**
	 * 
	 * ritorna i valori in upper case nel request 
	 * @param mixed $request
	 * 
	 */
	public function upperCaseRequest($request)
	{
		$return = array();
		foreach ($request as $key => $req) {
			if (is_array($req)) {
				$return[$key] = $req;
			} else {
				$return[$key] = strtoupper($req);
				$return[$key] = mb_strtoupper($req);
			}
		}

		return $return;
	}



	/**
	 * 
	 * Ritorna il contenuto di un file via https
	 * @param mixed $url
	 */
	public function file_get_contents_ssl($url)
	{
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
		curl_setopt($ch, CURLOPT_HEADER, false);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_REFERER, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 3000); // 3 sec.
		curl_setopt($ch, CURLOPT_TIMEOUT, 10000); // 10 sec.
		$result = curl_exec($ch);
		curl_close($ch);
		return $result;
	}


	/**
	 * 
	 *  Salva il contenuto di un base64 in file
	 *  @param string $base64Image
	 *  @param string $imageDir
	 *  @param string $fileName
	 */
	public function saveBase64Image($base64Image, $imageDir, $fileName)
	{

		$base64Image = trim($base64Image);
		$base64Image = str_replace('data:image/png;base64,', '', $base64Image);
		$base64Image = str_replace('data:image/jpg;base64,', '', $base64Image);
		$base64Image = str_replace('data:image/jpeg;base64,', '', $base64Image);
		$base64Image = str_replace('data:image/gif;base64,', '', $base64Image);
		$base64Image = str_replace(' ', '+', $base64Image);

		$imageData = base64_decode($base64Image);
		//Set image whole path here 
		$filePath = $imageDir . "/" . $fileName;
		file_put_contents($filePath, $imageData);
	}


	/**
	 * 
	 *  Restituisce un'array di elementi anno-mese
	 * 	@param Date $dateFrom
	 *  @param Date $imagedateToDir
	 * 
	 */
	public function getYearsMonthsFromRangeDate($data_da, $data_a)
	{
		$months_year = array();

		$start    = (new DateTime($data_da))->modify('first day of this month');
		$end      = (new DateTime($data_a))->modify('first day of next month');
		$interval = DateInterval::createFromDateString('1 month');
		$period   = new DatePeriod($start, $interval, $end);

		foreach ($period as $dt) {
			$months_year[] = $dt->format("Y-m");
		}

		return $months_year;
	}



	/**
	 * 
	 *  Restituisce il mese in italiano dato un numero
	 * 	@param int $number
	 * 
	 */
	public function getItalianMonthFromNumber($number)
	{
		$ita_month = "";
		switch ($number) {
			case 1:
			case '1':
			case '01':
				$ita_month = "GENNAIO";
				break;

			case 2:
			case '2':
			case '02':
				$ita_month = "FEBBRAIO";
				break;

			case 3:
			case '3':
			case '03':
				$ita_month = "MARZO";
				break;

			case 4:
			case '4':
			case '04':
				$ita_month = "APRILE";
				break;

			case 5:
			case '5':
			case '05':
				$ita_month = "MAGGIO";
				break;


			case 6:
			case '6':
			case '06':
				$ita_month = "GIUGNO";
				break;

			case 7:
			case '7':
			case '07':
				$ita_month = "LUGLIO";
				break;

			case 8:
			case '8':
			case '08':
				$ita_month = "AGOSTO";
				break;

			case 9:
			case '9':
			case '09':
				$ita_month = "SETTEMBRE";
				break;

			case 10:
			case '10':
			case '10':
				$ita_month = "OTTOBRE";
				break;


			case 1:
			case '11':
			case '11':
				$ita_month = "NOVEMBRE";
				break;

			case 12:
			case '12':
			case '12':
				$ita_month = "DICEMBRE";
				break;
		}

		return $ita_month;
	}



	/**
	 * 
	 *  Restituisce un numero dato il mese in italiano 
	 * 	@param string $ita_month
	 * 
	 */
	public function getMonthNumberFromItalianMonth($ita_month)
	{
		$number = "";
		switch ($number) {

			case "GENNAIO":
				$number = "01";
				break;

			case 'FEBBRAIO':
				$number = "02";
				break;

			case 'MARZO':
				$number = "03";
				break;

			case 'APRILE':
				$number = "04";
				break;

			case 'MAGGIO':
				$number = "05";
				break;

			case 'GIUGNO':
				$number = "06";
				break;

			case 'LUGLIO':
				$number = "07";
				break;

			case 'AGOSTO':
				$number = "08";
				break;

			case 'SETTEMBRE':
				$number = "09";
				break;

			case 'OTTOBRE':
				$number = "10";
				break;

			case 'NOVEMBRE':
				$number = "11";
				break;

			case 'DICEMBRE':
				$number = "12";
				break;
		}

		return $number;
	}
}
