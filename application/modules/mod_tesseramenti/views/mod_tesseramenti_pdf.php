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
				<h2>Mod_tesseramenti List</h2>
				<table class="word-table" style="margin-bottom: 10px">
					<tr>
						<th>No</th>
		<th>Data Tesseramento</th>
		<th>Fk Affiliazione</th>
		<th>Fk Anagrafica</th>
		<th>Fk Esercizio</th>
		<th>Fk Tessera</th>
		<th>Importo</th>
		<th>Modo Pagamento</th>
		
					</tr><?php
					foreach ($mod_tesseramenti_data as $mod_tesseramenti)
					{
						?>
						<tr>
		      <td><?php echo ++$start ?></td>
		      <td><?php echo $mod_tesseramenti->data_tesseramento ?></td>
		      <td><?php echo $mod_tesseramenti->fk_affiliazione ?></td>
		      <td><?php echo $mod_tesseramenti->fk_anagrafica ?></td>
		      <td><?php echo $mod_tesseramenti->fk_esercizio ?></td>
		      <td><?php echo $mod_tesseramenti->fk_tessera ?></td>
		      <td><?php echo $mod_tesseramenti->importo ?></td>
		      <td><?php echo $mod_tesseramenti->modo_pagamento ?></td>	
						</tr>
						<?php
					}
					?>
				</table>
			</body>
		</html>