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
				<h2>Mod_report_pagamenti_mensili_corso List</h2>
				<table class="word-table" style="margin-bottom: 10px">
					<tr>
						<th>No</th>
		<th>Ago</th>
		<th>Anagrafica</th>
		<th>Apr</th>
		<th>Data Iscrizione</th>
		<th>Dic</th>
		<th>Feb</th>
		<th>Gen</th>
		<th>Giu</th>
		<th>Id</th>
		<th>Ids</th>
		<th>Lug</th>
		<th>Mag</th>
		<th>Mar</th>
		<th>Nov</th>
		<th>Ott</th>
		<th>Set</th>
		
					</tr><?php
					foreach ($mod_report_pagamenti_mensili_corso_data as $mod_report_pagamenti_mensili_corso)
					{
						?>
						<tr>
		      <td><?php echo ++$start ?></td>
		      <td><?php echo $mod_report_pagamenti_mensili_corso->Ago ?></td>
		      <td><?php echo $mod_report_pagamenti_mensili_corso->Anagrafica ?></td>
		      <td><?php echo $mod_report_pagamenti_mensili_corso->Apr ?></td>
		      <td><?php echo $mod_report_pagamenti_mensili_corso->Data_Iscrizione ?></td>
		      <td><?php echo $mod_report_pagamenti_mensili_corso->Dic ?></td>
		      <td><?php echo $mod_report_pagamenti_mensili_corso->Feb ?></td>
		      <td><?php echo $mod_report_pagamenti_mensili_corso->Gen ?></td>
		      <td><?php echo $mod_report_pagamenti_mensili_corso->Giu ?></td>
		      <td><?php echo $mod_report_pagamenti_mensili_corso->id ?></td>
		      <td><?php echo $mod_report_pagamenti_mensili_corso->ids ?></td>
		      <td><?php echo $mod_report_pagamenti_mensili_corso->Lug ?></td>
		      <td><?php echo $mod_report_pagamenti_mensili_corso->Mag ?></td>
		      <td><?php echo $mod_report_pagamenti_mensili_corso->Mar ?></td>
		      <td><?php echo $mod_report_pagamenti_mensili_corso->Nov ?></td>
		      <td><?php echo $mod_report_pagamenti_mensili_corso->Ott ?></td>
		      <td><?php echo $mod_report_pagamenti_mensili_corso->Set ?></td>	
						</tr>
						<?php
					}
					?>
				</table>
			</body>
		</html>