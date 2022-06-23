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
				<h2>Mod_magazzino_tessere List</h2>
				<table class="word-table" style="margin-bottom: 10px">
					<tr>
						<th>No</th>
		<th>Nome</th>
		<th>Fk Affiliazione</th>
		
					</tr><?php
					foreach ($mod_magazzino_tessere_data as $mod_magazzino_tessere)
					{
						?>
						<tr>
		      <td><?php echo ++$start ?></td>
		      <td><?php echo $mod_magazzino_tessere->nome ?></td>
		      <td><?php echo $mod_magazzino_tessere->fk_affiliazione ?></td>	
						</tr>
						<?php
					}
					?>
				</table>
			</body>
		</html>