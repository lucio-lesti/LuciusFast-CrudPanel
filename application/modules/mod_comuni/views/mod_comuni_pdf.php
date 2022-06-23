<!doctype html>
		<html>
			<head>
				<title>harviacode.com - codeigniter crud generator</title>
				<link rel="stylesheet" href="<?php echo base_url('assets/bootstrap/css/bootstrap.min.css') ?>"/>
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
				<h2>Mod_comuni List</h2>
				<table class="word-table" style="margin-bottom: 10px">
					<tr>
						<th>No</th>
		<th>Abitanti</th>
		<th>Cap</th>
		<th>Codfisco</th>
		<th>Codice Provincia</th>
		<th>Codice Regione</th>
		<th>Comune</th>
		<th>Prefisso</th>
		
					</tr><?php
					foreach ($mod_comuni_data as $mod_comuni)
					{
						?>
						<tr>
		      <td><?php echo ++$start ?></td>
		      <td><?php echo $mod_comuni->abitanti ?></td>
		      <td><?php echo $mod_comuni->cap ?></td>
		      <td><?php echo $mod_comuni->codfisco ?></td>
		      <td><?php echo $mod_comuni->codice_provincia ?></td>
		      <td><?php echo $mod_comuni->codice_regione ?></td>
		      <td><?php echo $mod_comuni->comune ?></td>
		      <td><?php echo $mod_comuni->prefisso ?></td>	
						</tr>
						<?php
					}
					?>
				</table>
			</body>
		</html>