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
				<h2>Mod_libro_soci List</h2>
				<table class="word-table" style="margin-bottom: 10px">
					<tr>
						<th>No</th>
		<th>Data Ammissione</th>
		<th>Doc Verbale Ammissione</th>
		<th>Fk Anagrafica</th>
		
					</tr><?php
					foreach ($mod_libro_soci_data as $mod_libro_soci)
					{
						?>
						<tr>
		      <td><?php echo ++$start ?></td>
		      <td><?php echo $mod_libro_soci->data_ammissione ?></td>
		      <td><?php echo $mod_libro_soci->doc_verbale_ammissione ?></td>
		      <td><?php echo $mod_libro_soci->fk_anagrafica ?></td>	
						</tr>
						<?php
					}
					?>
				</table>
			</body>
		</html>