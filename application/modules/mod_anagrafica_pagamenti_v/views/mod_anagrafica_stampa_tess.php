<!DOCTYPE html>
<html>
<head>
      <title>Stampa Ricevute</title>
<style type="text/css">
<!--
body { font-family: Arial; font-size: 18.4px }
.pos { position: absolute; z-index: 0; left: 0px; top: 0px }
-->
 
		html { 
			margin-bottom: 20px;
			margin-top: 40px;
			margin-left: 50px;
			margin-right: 50px;
		}
		@page {
			margin-bottom: 20px;
			margin-top: 40px;
			margin-left: 50px;
			margin-right: 50px;
		}	

		body {
			font-family: "Helvetica Neue",Helvetica,Arial,sans-serif;
			font-size: 14px;
			line-height: 1.42857143;
			color: #333;
			background-color: #fff;
		} 	
		
		
		.TFtable{
			width:100%; 
			border-collapse:collapse; 
			font-size:10px;
		}
		.TFtable td{ 
			padding:7px; border:#A8A8B7 1px solid;
		}
 
		.dottedUnderline { border-bottom: 1px dotted; display: inline-block;}	 	
	</style>
</head>
<body>
 
<?php
	// First get your image
	if($company_logo == ''){
		$Logo_Azienda = FCPATH."/assets/images/Logo_Azienda.jpg";
	} else {
		$Logo_Azienda = FCPATH."/uploads/logo/".$company_logo;
	}

	if($manager_signature == ''){
		$Firma_Marco_Miele = FCPATH."/assets/images/Firma_Marco_Miele.jpg";
	} else {
		$Firma_Marco_Miele = FCPATH."/uploads/logo/".$manager_signature;
	}

	$Logo_Azienda64 = base64_encode(file_get_contents($Logo_Azienda));
	$Firma_Marco_Miele64 = base64_encode(file_get_contents($Firma_Marco_Miele));
?>
 

	<DIV ALIGN='CENTER'><IMG src='data:image/jpg;base64,<?php echo $Logo_Azienda64;?>' NAME="Immagine 1"  HSPACE=12 WIDTH=115 HEIGHT=77 BORDER=0></DIV>
	<BR><P STYLE="margin-bottom: 0.11in" align="right"><FONT SIZE=4 STYLE="font-size: 15pt"><B><?php echo $company_name?></B></FONT></P>
	<P STYLE="margin-bottom: 0.11in" align="right"><FONT SIZE=2><B>Sede Legale:</B></FONT><FONT SIZE=2>
	<?php echo $company_address;?></FONT>
	<BR><FONT SIZE=2><B>Codice fiscale:</B></FONT><FONT SIZE=2>
	<?php echo $company_code;?></FONT>	
	</P>
 <BR>
	<P STYLE="margin-bottom: 0.11in"><BR>
	</P>
	<P ALIGN=CENTER STYLE="margin-bottom: 0.11in"><BR><B>RICEVUTA
	PER PRESTAZIONI RESE AI TESSERATI</B></P>
	<P ALIGN=CENTER STYLE="margin-bottom: 0.11in"><BR>
	</P>
	<?php 
		//print'<pre>';print_r($value);die();
	?>
	<P STYLE="margin-bottom: 0.11in"><FONT FACE="Calibri,Bold, serif">Si
	dichiara di aver ricevuto dall'associato</FONT> <b class="dottedUnderline"><?php echo $value['nome']?> <?php echo $value['cognome']?></b></P>
	<P STYLE="margin-bottom: 0.11in">Numero di Tessera <b class="dottedUnderline"><?php echo $value['tessera_associativa']?></b>
	La somma di Euro <b class="dottedUnderline"><?php echo str_replace(".",",",$value['importo']);?></b>
	</P>
	<P STYLE="margin-bottom: 0.11in"><FONT FACE="Calibri,Bold, serif">Quale
	corrispettivo specifico per il Tesseramento per l'anno </FONT> <U></U> <b class="dottedUnderline"><?php echo $value['anno_da'] ."/".$value['anno_a']?></b> <FONT FACE="Calibri,Bold, serif">volta
	in suo favore dall’Associazione.</FONT></P>
	<P STYLE="margin-bottom: 0.11in"><BR>
	</P>
	<P STYLE="margin-bottom: 0.11in">li  <b class="dottedUnderline">
	<?php 
		$arrData = explode("-",$value['datapagamento']);
		echo $arrData[2]."/".$arrData[1]."/".$arrData[0];
	?>
	</b></P>

	<P STYLE="margin-bottom: 0.11in"><BR><BR>
	</P>
	<P STYLE="margin-bottom: 0.11in"><BR><BR>
	</p>
	<div align="right">
	<P STYLE="margin-left: 4.43in; margin-bottom: 0.11in">
	<br><br> 
	In Fede il Legale Rappresentante </P>
	<P STYLE="margin-left: 3.44in; text-indent: 0.49in; margin-bottom: 0.11in">
					<IMG src='data:image/jpg;base64,<?php echo $Firma_Marco_Miele64;?>'  NAME="Immagine 2" ALIGN=right HSPACE=12 WIDTH=148 HEIGHT=41 BORDER=0>
					<br>
	</P>
 
	</div>	 
	<P STYLE="margin-bottom: 0.11in"><BR><BR>
	</P>
	<P STYLE="margin-bottom: 0.11in"><BR><BR>
	</P>

	<P STYLE="margin-bottom: 0in; line-height: 100%"><FONT FACE="Calibri,Bold, serif"><FONT SIZE=2><B>Esente
	da IVA 8 comma 4, art. 4, D.P.R. 26/10/72 n. 633) e non soggetto a
	IRES (comma 3, art. 148,</B></FONT></FONT></P>
	<P STYLE="margin-bottom: 0in; line-height: 100%"><FONT FACE="Calibri,Bold, serif"><FONT SIZE=2><B>T.U.I.R.).</B></FONT></FONT></P>
	 <FONT FACE="Calibri,Bold, serif"><FONT SIZE=2><B>(bollo
	Euro 1,81 oltre i 77,47 Euro)</B></FONT></FONT> 

 
					
</body>
</html>
