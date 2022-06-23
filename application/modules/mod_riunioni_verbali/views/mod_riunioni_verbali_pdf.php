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
				<h2>Mod_riunioni_verbali List</h2>
				<table class="word-table" style="margin-bottom: 10px">
					<tr>
						<th>No</th>
		<th>Data Riunione Verbale</th>
		<th>Note</th>
		<th>Oggetto</th>
		
					</tr><?php
					foreach ($mod_riunioni_verbali_data as $mod_riunioni_verbali)
					{
						?>
						<tr>
		      <td><?php echo ++$start ?></td>
		      <td><?php echo $mod_riunioni_verbali->data_riunione_verbale ?></td>
		      <td><?php echo $mod_riunioni_verbali->note ?></td>
		      <td><?php echo $mod_riunioni_verbali->oggetto ?></td>	
						</tr>
						<?php
					}
					?>
				</table>
			</body>
		</html>