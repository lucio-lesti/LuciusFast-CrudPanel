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
				<h2>Mod_doc_generici List</h2>
				<table class="word-table" style="margin-bottom: 10px">
					<tr>
						<th>No</th>
		<th>Data</th>
		<th>Descrizione</th>
		<th>Nome</th>
		<th>Tipo Doc</th>
		
					</tr><?php
					foreach ($mod_doc_generici_data as $mod_doc_generici)
					{
						?>
						<tr>
		      <td><?php echo ++$start ?></td>
		      <td><?php echo $mod_doc_generici->data ?></td>
		      <td><?php echo $mod_doc_generici->descrizione ?></td>
		      <td><?php echo $mod_doc_generici->nome ?></td>
		      <td><?php echo $mod_doc_generici->tipo_doc ?></td>	
						</tr>
						<?php
					}
					?>
				</table>
			</body>
		</html>