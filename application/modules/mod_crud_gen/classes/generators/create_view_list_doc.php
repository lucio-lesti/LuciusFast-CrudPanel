<?php 

class create_view_list_doc extends AbstractGenerator{
	
	public static function output($param_gen){
		
		$helper = new Helper();
		foreach($param_gen as $key => $value){
			${$key} = $value;
		}			
		$string = "<!doctype html>
		<html>
			<head>
				<title>harviacode.com - codeigniter crud generator</title>
				<link rel=\"stylesheet\" href=\"<?php echo base_url('assets/bootstrap/css/bootstrap.min.css') ?>\"/>
				<style>
					.word-table {
						border:1px solid black !important; 
						border-collapse: collapse !important;
						width: 100%;
					}
					.word-table tr th, .word-table tr td{
						border:1px solid black !important; 
						padding: 5px 10px;
					}
				</style>
			</head>
			<body>
				<h2>".ucfirst($table_name)." List</h2>
				<table class=\"word-table\" style=\"margin-bottom: 10px\">
					<tr>
						<th>No</th>";
		foreach ($non_pk as $row) {
			$string .= "\n\t\t<th>".$helper->label($row['COLUMN_NAME']) . "</th>";
		}
		$string .= "\n\t\t
					</tr>";
		$string .= "<?php
					foreach ($" . $c_url . "_data as \$$c_url)
					{
						?>
						<tr>";

		$string .= "\n\t\t      <td><?php echo ++\$start ?></td>";

		foreach ($non_pk as $row) {
			$string .= "\n\t\t      <td><?php echo $" . $c_url ."->". $row['COLUMN_NAME'] . " ?></td>";
		}

		$string .=  "\t
						</tr>
						<?php
					}
					?>
				</table>
			</body>
		</html>";		
	
		return $string;
	}

}	






?>