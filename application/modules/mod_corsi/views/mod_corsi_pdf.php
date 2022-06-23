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
				<h2>Mod_corsi List</h2>
				<table class="word-table" style="margin-bottom: 10px">
					<tr>
						<th>No</th>
		<th>Data A</th>
		<th>Data Da</th>
		<th>Fk Affiliazione</th>
		<th>Fk Disciplina</th>
		<th>Importo Mensile</th>
		<th>Nome</th>
		<th>Tipologia Corso</th>
		
					</tr><?php
					foreach ($mod_corsi_data as $mod_corsi)
					{
						?>
						<tr>
		      <td><?php echo ++$start ?></td>
		      <td><?php echo $mod_corsi->data_a ?></td>
		      <td><?php echo $mod_corsi->data_da ?></td>
		      <td><?php echo $mod_corsi->fk_affiliazione ?></td>
		      <td><?php echo $mod_corsi->fk_disciplina ?></td>
		      <td><?php echo $mod_corsi->importo_mensile ?></td>
		      <td><?php echo $mod_corsi->nome ?></td>
		      <td><?php echo $mod_corsi->tipologia_corso ?></td>	
						</tr>
						<?php
					}
					?>
				</table>
			</body>
		</html>