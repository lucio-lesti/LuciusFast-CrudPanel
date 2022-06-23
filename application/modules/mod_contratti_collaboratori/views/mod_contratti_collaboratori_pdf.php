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
				<h2>Mod_contratti_collaboratori List</h2>
				<table class="word-table" style="margin-bottom: 10px">
					<tr>
						<th>No</th>
		<th>Nome</th>
		<th>Fk Anagrafica</th>
		<th>Mansione</th>
		<th>Data Da</th>
		<th>Data A</th>
		<th>Importo Mensile</th>
		<th>Tipo Pagamento</th>
		<th>Banca</th>
		<th>Iban</th>
		
					</tr><?php
					foreach ($mod_contratti_collaboratori_data as $mod_contratti_collaboratori)
					{
						?>
						<tr>
		      <td><?php echo ++$start ?></td>
		      <td><?php echo $mod_contratti_collaboratori->nome ?></td>
		      <td><?php echo $mod_contratti_collaboratori->fk_anagrafica ?></td>
		      <td><?php echo $mod_contratti_collaboratori->mansione ?></td>
		      <td><?php echo $mod_contratti_collaboratori->data_firma_contratto ?></td>
			  <td><?php echo $mod_contratti_collaboratori->data_da ?></td>
		      <td><?php echo $mod_contratti_collaboratori->data_a ?></td>
		      <td><?php echo $mod_contratti_collaboratori->importo_mensile ?></td>
		      <td><?php echo $mod_contratti_collaboratori->tipo_pagamento ?></td>
		      <td><?php echo $mod_contratti_collaboratori->banca ?></td>
		      <td><?php echo $mod_contratti_collaboratori->iban ?></td>	
						</tr>
						<?php
					}
					?>
				</table>
			</body>
		</html>