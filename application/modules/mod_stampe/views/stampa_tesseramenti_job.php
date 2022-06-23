<!DOCTYPE html>
<html>
<head>
      <title>Stampa Tesseramento</title>
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
		#footer { position: fixed; right: 0px; bottom: 30px; text-align: right;}
		#footer .page:after { content: counter(page, decimal); }
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

<?php 
	foreach($tesseramenti as $key =>$value){
?>
	<DIV ALIGN='CENTER'><IMG src='data:image/jpg;base64,<?php echo $Logo_Azienda64;?>' NAME="Immagine 1"  HSPACE=12 WIDTH=115 HEIGHT=77 BORDER=0></DIV>
	<BR><P STYLE="margin-bottom: 0.11in" align="right"><FONT SIZE=4 STYLE="font-size: 15pt"><B>Baila
	Dance A.S.D.</B></FONT></P>
	<P STYLE="margin-bottom: 0.11in" align="right"><FONT SIZE=2><B>Sede Legale:</B></FONT><FONT SIZE=2>
	Via Aldo Moro, 25 <BR/>80020 Crispano (NA) Italy </FONT>
	<BR><FONT SIZE=2><B>Codice fiscale:</B></FONT><FONT SIZE=2>
	95234400638</FONT>	
	</P>
	<br><BR><BR>
	<P ALIGN=CENTER STYLE="margin-bottom: 0.11in"><B>RICEVUTA PER QUOTE SOCIALI
	TESSERAMENTO</B></P>
	<P ALIGN=CENTER STYLE="margin-bottom: 0.11in"><BR>
	</P>

	<P STYLE="margin-bottom: 0.11in">Si riceve dall’iscritto
	<b class="dottedUnderline"><?php echo $value['nome']?> <?php echo $value['cognome']?></b></P>
	<P STYLE="margin-bottom: 0.11in">La somma di Euro
	<B class="dottedUnderline"><?php echo $value['importo']?></B>   per quota
	tesseramento  anno <B class="dottedUnderline"><?php echo $value['anno_sportivo']?></B></P>
	<P STYLE="margin-bottom: 0.11in">li <b class="dottedUnderline">
	<?php 
		$arrData = explode("-",$value['data']);
		echo $arrData[2]."/".$arrData[1]."/".$arrData[0];
	?>
	</b></P>
	<P STYLE="margin-bottom: 0.11in"><BR><BR>
	</P>
	<P STYLE="margin-bottom: 0.11in"><BR><BR>
	</P>
	<P STYLE="margin-bottom: 0.11in"><BR><BR>
	</P>
	<P STYLE="margin-bottom: 0.11in"><BR><BR>
	</P>
	<P STYLE="margin-bottom: 0.11in"><BR>
	</P>
	<div align="right">
	<br>
	<P STYLE="margin-left: 4.43in; margin-bottom: 0.11in">In Fede il
	Legale Rappresentante</P>
	<P STYLE="margin-left: 3.44in; text-indent: 0.49in; margin-bottom: 0.11in">
					<IMG  src='data:image/jpg;base64,<?php echo $Firma_Marco_Miele64;?>'  NAME="Firma_Marco_Miele" ALIGN=LEFT HSPACE=12 WIDTH=148 HEIGHT=41 BORDER=0>
	</P>
 
	</div>	 
	<P STYLE="margin-bottom: 0.11in"><BR>
	</P>
	<P STYLE="margin-bottom: 0.11in"><BR><BR>
	</P>


	<P STYLE="margin-bottom: 0in; line-height: 100%"><FONT FACE="Calibri,Bold, serif"><B>Provento
	non commerciabile non soggetto a IVA (art. 148, comma 1, D.P.R.
	22/12/86, n. 917) ed esente</B></FONT></P>
	<P STYLE="margin-bottom: 0.11in"><FONT FACE="Calibri,Bold, serif"><B>da
	bollo D.P.R. 642/1972 (Art. 7 ultimo comma).</B></FONT></P>
<?php }?>
					
</body>
</html>
