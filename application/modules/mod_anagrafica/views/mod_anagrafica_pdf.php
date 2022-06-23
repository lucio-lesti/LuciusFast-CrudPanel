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
				<h2>Mod_anagrafica List</h2>
				<table class="word-table" style="margin-bottom: 10px">
					<tr>
						<th>No</th>
		<th>Anagrafica Attributo</th>
		<th>Cellulare</th>
		<th>Codfiscale</th>
		<th>Cognome</th>
		<th>Datanascita</th>
		<th>Doc Domanda Ammissione Socio</th>
		<th>Documento</th>
		<th>Email</th>
		<th>Fk Comune Nascita</th>
		<th>Fk Comune Residenza</th>
		<th>Fk Tutore</th>
		<th>Img Foto</th>
		<th>Indirizzo</th>
		<th>Nome</th>
		<th>Notetesto</th>
		<th>Sesso</th>
		<th>Sottoposto Regime Green Pass</th>
		<th>Telefono</th>
		
					</tr><?php
					foreach ($mod_anagrafica_data as $mod_anagrafica)
					{
						?>
						<tr>
		      <td><?php echo ++$start ?></td>
		      <td><?php echo $mod_anagrafica->anagrafica_attributo ?></td>
		      <td><?php echo $mod_anagrafica->cellulare ?></td>
		      <td><?php echo $mod_anagrafica->codfiscale ?></td>
		      <td><?php echo $mod_anagrafica->cognome ?></td>
		      <td><?php echo $mod_anagrafica->datanascita ?></td>
		      <td><?php echo $mod_anagrafica->doc_domanda_ammissione_socio ?></td>
		      <td><?php echo $mod_anagrafica->documento ?></td>
		      <td><?php echo $mod_anagrafica->email ?></td>
		      <td><?php echo $mod_anagrafica->fk_comune_nascita ?></td>
		      <td><?php echo $mod_anagrafica->fk_comune_residenza ?></td>
		      <td><?php echo $mod_anagrafica->fk_tutore ?></td>
		      <td><?php echo $mod_anagrafica->img_foto ?></td>
		      <td><?php echo $mod_anagrafica->indirizzo ?></td>
		      <td><?php echo $mod_anagrafica->nome ?></td>
		      <td><?php echo $mod_anagrafica->notetesto ?></td>
		      <td><?php echo $mod_anagrafica->sesso ?></td>
		      <td><?php echo $mod_anagrafica->sottoposto_regime_green_pass ?></td>
		      <td><?php echo $mod_anagrafica->telefono ?></td>	
						</tr>
						<?php
					}
					?>
				</table>
			</body>
		</html>