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
				<h2>_mod_anagrafica_green_pass_autocertificazione List</h2>
				<table class="word-table" style="margin-bottom: 10px">
					<tr>
						<th>No</th>
		<th>Fk Anagrafica</th>
		<th>Data Autocertificazione Fine Validita</th>
		<th>Documento Upload</th>
		<th>Nome Documento</th>
		
					</tr><?php
					foreach ($mod_anagrafica_green_pass_autocertificazione_data as $mod_anagrafica_green_pass_autocertificazione)
					{
						?>
						<tr>
		      <td><?php echo ++$start ?></td>
		      <td><?php echo $mod_anagrafica_green_pass_autocertificazione->fk_anagrafica ?></td>
		      <td><?php echo $mod_anagrafica_green_pass_autocertificazione->data_autocertificazione_fine_validita ?></td>
		      <td><?php echo $mod_anagrafica_green_pass_autocertificazione->documento_upload ?></td>
		      <td><?php echo $mod_anagrafica_green_pass_autocertificazione->nome_documento ?></td>	
						</tr>
						<?php
					}
					?>
				</table>
			</body>
		</html>