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
				<h2>Mod_pagamenti_collaboratori_v List</h2>
				<table class="word-table" style="margin-bottom: 10px">
					<tr>
						<th>No</th>
		<th>Collaboratore</th>
		<th>Id</th>
		<th>Fk Contratto</th>
		<th>Datapagamento</th>
		<th>Ora Pagamento</th>
		<th>Importo</th>
		<th>Fk Tipopagamento</th>
		<th>Fk Causale Pagamento</th>
		<th>Notepagamento</th>
		<th>Contratto Id</th>
		<th>Contratto Nome</th>
		
					</tr><?php
					foreach ($mod_pagamenti_collaboratori_v_data as $mod_pagamenti_collaboratori_v)
					{
						?>
						<tr>
		      <td><?php echo ++$start ?></td>
		      <td><?php echo $mod_pagamenti_collaboratori_v->collaboratore ?></td>
		      <td><?php echo $mod_pagamenti_collaboratori_v->id ?></td>
		      <td><?php echo $mod_pagamenti_collaboratori_v->fk_contratto ?></td>
		      <td><?php echo $mod_pagamenti_collaboratori_v->datapagamento ?></td>
		      <td><?php echo $mod_pagamenti_collaboratori_v->ora_pagamento ?></td>
		      <td><?php echo $mod_pagamenti_collaboratori_v->importo ?></td>
		      <td><?php echo $mod_pagamenti_collaboratori_v->fk_tipopagamento ?></td>
		      <td><?php echo $mod_pagamenti_collaboratori_v->fk_causale_pagamento ?></td>
		      <td><?php echo $mod_pagamenti_collaboratori_v->notepagamento ?></td>
		      <td><?php echo $mod_pagamenti_collaboratori_v->contratto_id ?></td>
		      <td><?php echo $mod_pagamenti_collaboratori_v->contratto_nome ?></td>	
						</tr>
						<?php
					}
					?>
				</table>
			</body>
		</html>