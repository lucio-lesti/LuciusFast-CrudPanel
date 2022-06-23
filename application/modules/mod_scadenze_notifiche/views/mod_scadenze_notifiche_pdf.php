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
				<h2>Mod_scadenze_notifiche List</h2>
				<table class="word-table" style="margin-bottom: 10px">
					<tr>
						<th>No</th>
		<th>Campo Data Scadenza</th>
		<th>Icona Notifica</th>
		<th>Mod Name</th>
		<th>Msg Notifica</th>
		<th>Nr Giorni Data Notifica</th>
		<th>Sql Command</th>
		<th>Table Name</th>
		
					</tr><?php
					foreach ($mod_scadenze_notifiche_data as $mod_scadenze_notifiche)
					{
						?>
						<tr>
		      <td><?php echo ++$start ?></td>
		      <td><?php echo $mod_scadenze_notifiche->campo_data_scadenza ?></td>
		      <td><?php echo $mod_scadenze_notifiche->icona_notifica ?></td>
		      <td><?php echo $mod_scadenze_notifiche->mod_name ?></td>
		      <td><?php echo $mod_scadenze_notifiche->msg_notifica ?></td>
		      <td><?php echo $mod_scadenze_notifiche->nr_giorni_data_notifica ?></td>
		      <td><?php echo $mod_scadenze_notifiche->sql_command ?></td>
		      <td><?php echo $mod_scadenze_notifiche->table_name ?></td>	
						</tr>
						<?php
					}
					?>
				</table>
			</body>
		</html>