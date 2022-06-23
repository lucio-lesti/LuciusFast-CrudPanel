<!DOCTYPE html>
		<html>
		   <head>
			  <meta charset="UTF-8">
			  <title>Report Pagamenti Mensili</title>
		 <style>
				html { 
					margin-bottom: 10px;
					margin-top: 40px;
					margin-left: 20px;
					margin-right: 20px;
				}
				@page {
					margin-bottom: 10px;
					margin-top: 40px;
					margin-left: 20px;
					margin-right: 20px;
				}	
				#footer { position: fixed; right: 0px; bottom: 30px; text-align: right;}
				#footer .page:after { content: counter(page, decimal); }
			
				
				label {
					display: inline-block;
					max-width: 100%;
					margin-bottom: 5px;
					font-weight: 700;
				}					
				.form-control {
					border-radius: 0;
					box-shadow: none;
					border-color: #d2d6de;			
					display: block;
					width: 85% ; 
					height: 34px;
					padding: 6px 12px;
					font-size: 14px;
					line-height: 1.42857143;
					color: #555;
					background-color: #fff;
					background-image: none;
					border: 1px solid #ccc;
					border-radius: 4px;
					-webkit-box-shadow: inset 0 1px 1px rgba(0,0,0,.075);
					box-shadow: inset 0 1px 1px rgba(0,0,0,.075);
					-webkit-transition: border-color ease-in-out .15s,-webkit-box-shadow ease-in-out .15s;
					-o-transition: border-color ease-in-out .15s,box-shadow ease-in-out .15s;
					transition: border-color ease-in-out .15s,box-shadow ease-in-out .15s;
				}
				button, input, optgroup, select, textarea {
					font-family: inherit;
					font-size: inherit;
					line-height: inherit;			
					margin: 0;
					font: inherit;
					color: inherit;
				}
				user agent stylesheet
				input {
					line-height: normal;
					-webkit-writing-mode: horizontal-tb !important;
					text-rendering: auto;
					color: -internal-light-dark-color(black, white);
					letter-spacing: normal;
					word-spacing: normal;
					text-transform: none;
					text-indent: 0px;
					text-shadow: none;
					display: inline-block;
					text-align: start;
					-webkit-appearance: textfield;
					background-color: -internal-light-dark-color(white, black);
					-webkit-rtl-ordering: logical;
					cursor: text;
					margin: 0em;
					font: 400 13.3333px Arial;
					padding: 1px 0px;
					border-width: 2px;
					border-style: inset;
					border-color: initial;
					border-image: initial;
				}
				body {
					font-family: "Helvetica Neue",Helvetica,Arial,sans-serif;
					font-size: 14px;
					line-height: 1.42857143;
					color: #333;
					background-color: #fff;
				}

				.table-bordered {
					border: 1px solid #f4f4f4;
				}
				.table {
					width: 100%;
					max-width: 100%;
					margin-bottom: 20px;
				}	
				table {
					display: table;
					border-spacing: 2px;
					border-color: grey;
					border-collapse: collapse;	
				}
				thead {
					display: table-header-group;
					vertical-align: middle;
					border-color: inherit;
				}	
				
				.table-bordered>thead>tr>th, .table-bordered>tbody>tr>th, .table-bordered>tfoot>tr>th, .table-bordered>thead>tr>td, .table-bordered>tbody>tr>td, .table-bordered>tfoot>tr>td {
					border: 1px solid #f4f4f4;
				}	
				.table>thead>tr>th, .table>tbody>tr>th, .table>tfoot>tr>th, .table>thead>tr>td, .table>tbody>tr>td, .table>tfoot>tr>td {
					border-top: 1px solid #f4f4f4;
				}
				.table-bordered>thead>tr>td, .table-bordered>thead>tr>th {
					border-bottom-width: 2px;
				}
				.table-bordered>tbody>tr>td, .table-bordered>tbody>tr>th, .table-bordered>tfoot>tr>td, .table-bordered>tfoot>tr>th, .table-bordered>thead>tr>td, .table-bordered>thead>tr>th {
					border: 1px solid #ddd;
				}
				.table>thead>tr>th {
					vertical-align: bottom;
					border-bottom: 2px solid #ddd;
				}
				.table>tbody>tr>td, .table>tbody>tr>th, .table>tfoot>tr>td, .table>tfoot>tr>th, .table>thead>tr>td, .table>thead>tr>th {
					padding: 8px;
					line-height: 1.42857143;
					vertical-align: top;
					border-top: 1px solid #ddd;
				}
				.table-striped>tbody>tr:nth-of-type(odd) {
					background-color: #FFFFFF;
				}		
				.table-striped>tbody>tr:nth-of-type(even) {
					background-color: #f9f9f9;
				}		
				
				tr {
					display: table-row;
					vertical-align: inherit;
					border-color: inherit;
				}
				td, th {
					padding: 0;
				}
				user agent stylesheet
				th {
					text-align: left;
					display: table-cell;
					vertical-align: inherit;
					font-weight: bold;
				}	
			</style>
		   </head><body>
				<b>Esercizio:</b> <?php echo $esercizio;?> &nbsp;&nbsp; <b>Corsi:</b><?php echo $corso;?>

		   <table cellspacing="10" cellpadding="10" border="0" WIDTH='100%' style="padding:2px;">
	<colgroup width="113"></colgroup>
	<colgroup width="104"></colgroup>
	<colgroup span="12" width="64"></colgroup>
	<tr>
		<td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; padding:2px; border-right: 1px solid #000000" height="20" align="left" valign=bottom bgcolor="#E7E6E6"><b><font color="#000000">Anagrafica</font></b></td>
		<td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; padding:2px; border-right: 1px solid #000000" align="left" valign=bottom bgcolor="#E7E6E6"><b><font color="#000000">Corso</font></b></td>	
		<td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; padding:2px; border-right: 1px solid #000000" align="left" valign=bottom bgcolor="#E7E6E6"><b><font color="#000000">Tipo</font></b></td>	
		<td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; padding:2px; border-right: 1px solid #000000" align="left" valign=bottom bgcolor="#E7E6E6"><b><font color="#000000">Data iscrizione</font></b></td>
	
		<td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; padding:2px; border-right: 1px solid #000000" align="left" valign=bottom bgcolor="#E7E6E6"><b><font color="#000000">Set</font></b></td>
		<td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; padding:2px; border-right: 1px solid #000000" align="left" valign=bottom bgcolor="#E7E6E6"><b><font color="#000000">Ott</font></b></td>
		<td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; padding:2px; border-right: 1px solid #000000" align="left" valign=bottom bgcolor="#E7E6E6"><b><font color="#000000">Nov</font></b></td>
		<td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; padding:2px; border-right: 1px solid #000000" align="left" valign=bottom bgcolor="#E7E6E6"><b><font color="#000000">Dic</font></b></td>
		<td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; padding:2px; border-right: 1px solid #000000" align="left" valign=bottom bgcolor="#E7E6E6"><b><font color="#000000">Gen</font></b></td>
		<td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; padding:2px; border-right: 1px solid #000000" align="left" valign=bottom bgcolor="#E7E6E6"><b><font color="#000000">Feb</font></b></td>
		<td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; padding:2px; border-right: 1px solid #000000" align="left" valign=bottom bgcolor="#E7E6E6"><b><font color="#000000">Mar</font></b></td>
		<td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; padding:2px; border-right: 1px solid #000000" align="left" valign=bottom bgcolor="#E7E6E6"><b><font color="#000000">Apr</font></b></td>
		<td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; padding:2px; border-right: 1px solid #000000" align="left" valign=bottom bgcolor="#E7E6E6"><b><font color="#000000">Mag</font></b></td>
		<td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; padding:2px; border-right: 1px solid #000000" align="left" valign=bottom bgcolor="#E7E6E6"><b><font color="#000000">Giu</font></b></td>
		<td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; padding:2px; border-right: 1px solid #000000" align="left" valign=bottom bgcolor="#E7E6E6"><b><font color="#000000">Lug</font></b></td>
		<td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; padding:2px; border-right: 1px solid #000000" align="left" valign=bottom bgcolor="#E7E6E6"><b><font color="#000000">Ago</font></b></td>
	</tr>
	<?php 
		if(isset($righe)){
			foreach($righe as $k => $v){
				echo'<tr>
					<td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000;padding:2px; border-right: 1px solid #000000" height="20" align="left" valign=bottom><font color="#000000">'.$v['Anagrafica'].'</font></td>
					<td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000;padding:2px; border-right: 1px solid #000000" align="left" valign=bottom><font color="#000000">'.$v['mod_corsi_nome'].'</font></td>
					<td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000;padding:2px; border-right: 1px solid #000000" align="left" valign=bottom><font color="#000000">'.$v['mod_corsi_tipo'].'</font></td>
					<td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000;padding:2px; border-right: 1px solid #000000" align="left" valign=bottom><font color="#000000">'.$v['Data_Iscrizione'].'</font></td>
					<td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000;padding:2px; border-right: 1px solid #000000" align="right" valign=bottom sdval="0" sdnum="1033;"><font color="#000000">'.$v['Sett'].'</font></td>
					<td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000;padding:2px; border-right: 1px solid #000000" align="right" valign=bottom sdval="0" sdnum="1033;"><font color="#000000">'.$v['Ott'].'</font></td>
					<td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000;padding:2px; border-right: 1px solid #000000" align="right" valign=bottom sdval="0" sdnum="1033;"><font color="#000000">'.$v['Nov'].'</font></td>
					<td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000;padding:2px; border-right: 1px solid #000000" align="right" valign=bottom sdval="0" sdnum="1033;"><font color="#000000">'.$v['Dic'].'</font></td>
					<td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000;padding:2px; border-right: 1px solid #000000" align="right" valign=bottom sdval="0" sdnum="1033;"><font color="#000000">'.$v['Gen'].'</font></td>
					<td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000;padding:2px; border-right: 1px solid #000000" align="right" valign=bottom sdval="50" sdnum="1033;"><font color="#000000">'.$v['Feb'].'</font></td>
					<td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000;padding:2px; border-right: 1px solid #000000" align="right" valign=bottom sdval="0" sdnum="1033;"><font color="#000000">'.$v['Mar'].'</font></td>
					<td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000;padding:2px; border-right: 1px solid #000000" align="right" valign=bottom sdval="0" sdnum="1033;"><font color="#000000">'.$v['Apr'].'</font></td>
					<td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000;padding:2px; border-right: 1px solid #000000" align="right" valign=bottom sdval="0" sdnum="1033;"><font color="#000000">'.$v['Mag'].'</font></td>
					<td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000;padding:2px; border-right: 1px solid #000000" align="right" valign=bottom sdval="0" sdnum="1033;"><font color="#000000">'.$v['Giu'].'</font></td>
					<td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000;padding:2px; border-right: 1px solid #000000" align="right" valign=bottom sdval="0" sdnum="1033;"><font color="#000000">'.$v['Lug'].'</font></td>
					<td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000;padding:2px; border-right: 1px solid #000000" align="right" valign=bottom sdval="0" sdnum="1033;"><font color="#000000">'.$v['Ago'].'</font></td>
				</tr>';
			}
		}


	?>

</table>

		   </body>
		</html>