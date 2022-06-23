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
				<h2>Mod_sedi List</h2>
				<table class="word-table" style="margin-bottom: 10px">
					<tr>
						<th>No</th>
		<th>Fk Azienda</th>
		<th>Indirizzo</th>
		<th>Nome</th>
		
					</tr><?php
					foreach ($mod_sedi_data as $mod_sedi)
					{
						?>
						<tr>
		      <td><?php echo ++$start ?></td>
		      <td><?php echo $mod_sedi->fk_azienda ?></td>
		      <td><?php echo $mod_sedi->indirizzo ?></td>
		      <td><?php echo $mod_sedi->nome ?></td>	
						</tr>
						<?php
					}
					?>
				</table>
			</body>
		</html>