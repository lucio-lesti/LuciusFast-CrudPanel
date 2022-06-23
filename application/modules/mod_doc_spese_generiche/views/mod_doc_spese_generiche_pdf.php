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
				<h2>Mod_doc_spese_generiche List</h2>
				<table class="word-table" style="margin-bottom: 10px">
					<tr>
						<th>No</th>
		<th>Data</th>
		<th>Descrizione</th>
		<th>Fk Tipo Spesa</th>
		<th>Fk Tipopagamento</th>
		<th>Importo</th>
		<th>Nome</th>
		
					</tr><?php
					foreach ($mod_doc_spese_generiche_data as $mod_doc_spese_generiche)
					{
						?>
						<tr>
		      <td><?php echo ++$start ?></td>
		      <td><?php echo $mod_doc_spese_generiche->data ?></td>
		      <td><?php echo $mod_doc_spese_generiche->descrizione ?></td>
		      <td><?php echo $mod_doc_spese_generiche->fk_tipo_spesa ?></td>
		      <td><?php echo $mod_doc_spese_generiche->fk_tipopagamento ?></td>
		      <td><?php echo $mod_doc_spese_generiche->importo ?></td>
		      <td><?php echo $mod_doc_spese_generiche->nome ?></td>	
						</tr>
						<?php
					}
					?>
				</table>
			</body>
		</html>