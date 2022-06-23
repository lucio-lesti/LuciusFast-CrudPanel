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
				<h2>Mod_affiliazioni List</h2>
				<table class="word-table" style="margin-bottom: 10px">
					<tr>
						<th>No</th>
		<th>Nome</th>
		<th>Fk Ente</th>
		<th>Fk Esercizio</th>
		
					</tr><?php
					foreach ($mod_affiliazioni_data as $mod_affiliazioni)
					{
						?>
						<tr>
		      <td><?php echo ++$start ?></td>
		      <td><?php echo $mod_affiliazioni->nome ?></td>
		      <td><?php echo $mod_affiliazioni->fk_ente ?></td>
		      <td><?php echo $mod_affiliazioni->fk_esercizio ?></td>	
						</tr>
						<?php
					}
					?>
				</table>
			</body>
		</html>