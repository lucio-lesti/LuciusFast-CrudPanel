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
				<h2>Mod_enti List</h2>
				<table class="word-table" style="margin-bottom: 10px">
					<tr>
						<th>No</th>
		<th>Email</th>
		<th>Fk Comune</th>
		<th>Indirizzo</th>
		<th>Nome</th>
		<th>Telefono</th>
		
					</tr><?php
					foreach ($mod_enti_data as $mod_enti)
					{
						?>
						<tr>
		      <td><?php echo ++$start ?></td>
		      <td><?php echo $mod_enti->email ?></td>
		      <td><?php echo $mod_enti->fk_comune ?></td>
		      <td><?php echo $mod_enti->indirizzo ?></td>
		      <td><?php echo $mod_enti->nome ?></td>
		      <td><?php echo $mod_enti->telefono ?></td>	
						</tr>
						<?php
					}
					?>
				</table>
			</body>
		</html>