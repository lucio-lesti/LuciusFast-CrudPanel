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
				<h2>Mod_anagrafica_pagamenti_v List</h2>
				<table class="word-table" style="margin-bottom: 10px">
					<tr>
						<th>No</th>
		<th>Id</th>
		<th>Anagrafica Id</th>
		<th>Anagrafica</th>
		<th>Affiliazione Id</th>
		<th>Affiliazione</th>
		<th>Esercizio Id</th>
		<th>Esercizio</th>
		<th>Saldo</th>
		<th>Datapagamento</th>
		<th>Mese</th>
		<th>Anno</th>
		<th>Importo</th>
		
					</tr><?php
					foreach ($mod_anagrafica_pagamenti_v_data as $mod_anagrafica_pagamenti_v)
					{
						?>
						<tr>
		      <td><?php echo ++$start ?></td>
		      <td><?php echo $mod_anagrafica_pagamenti_v->id ?></td>
		      <td><?php echo $mod_anagrafica_pagamenti_v->anagrafica_id ?></td>
		      <td><?php echo $mod_anagrafica_pagamenti_v->anagrafica ?></td>
		      <td><?php echo $mod_anagrafica_pagamenti_v->affiliazione_id ?></td>
		      <td><?php echo $mod_anagrafica_pagamenti_v->affiliazione ?></td>
		      <td><?php echo $mod_anagrafica_pagamenti_v->esercizio_id ?></td>
		      <td><?php echo $mod_anagrafica_pagamenti_v->esercizio ?></td>
		      <td><?php echo $mod_anagrafica_pagamenti_v->saldo ?></td>
		      <td><?php echo $mod_anagrafica_pagamenti_v->datapagamento ?></td>
		      <td><?php echo $mod_anagrafica_pagamenti_v->mese ?></td>
		      <td><?php echo $mod_anagrafica_pagamenti_v->anno ?></td>
		      <td><?php echo $mod_anagrafica_pagamenti_v->importo ?></td>	
						</tr>
						<?php
					}
					?>
				</table>
			</body>
		</html>