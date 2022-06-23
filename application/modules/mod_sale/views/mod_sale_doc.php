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
				<h2>Mod_sale List</h2>
				<table class="word-table" style="margin-bottom: 10px">
					<tr>
						<th>No</th>
		<th>Capienza</th>
		<th>Fk Sede</th>
		<th>Nome</th>
		
					</tr><?php
					foreach ($mod_sale_data as $mod_sale)
					{
						?>
						<tr>
		      <td><?php echo ++$start ?></td>
		      <td><?php echo $mod_sale->capienza ?></td>
		      <td><?php echo $mod_sale->fk_sede ?></td>
		      <td><?php echo $mod_sale->nome ?></td>	
						</tr>
						<?php
					}
					?>
				</table>
			</body>
		</html>