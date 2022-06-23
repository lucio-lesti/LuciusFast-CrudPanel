<!DOCTYPE html>
		<html>
		   <head>
			  <meta charset="UTF-8">
			  <title>Sale</title>
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
			<section class="content">
				
				<div class="row">
					<!-- left column -->
					<div class="col-md-8">
						<div class="box box-primary" >
							<!-- /.box-header -->
							<!-- form start -->
						
							 <div class="box-body" style="width: 100%;">	
				<div class="col-md-6" style="float: left; width: 100%;padding-bottom:5px">
					<table style="width:99%"  border=0>
						<tr>
							<td style="width:50%">					
								<?php
									// First get your image
									$image = base_url()."uploads/logo/".$company_logo;
									$pic_base64 = base64_encode(file_get_contents($image));
									echo "<img style='width:200px'  src='data:image/jpg;base64,".$pic_base64."' />";
								?>						
							
							</td>
							<td style="width:50%;" align="center">
								<div style="font-weight:bold;font-size:18px;padding-right:5px;
									border:1px solid #DEDEDE;border-radius:5px" align="center">
								<?php echo $company_name;?>
								<br><?php echo $company_address;?>
								<br><?php echo $company_phone;?>
								<br><?php echo $company_email;?>
								<br>P.IVA <?php echo $company_code;?>
								</div>
							</td>
						</tr>
						
					</table>
				</div></div><p style="clear: left;" /><br/></p>
	 
							<div class="col-md-4"  style="float: left; width: 32%;padding-bottom:5px">
	
								<label for="int">Capienza <?php echo form_error('capienza') ?></label>	
								<input type="text" class="form-control"  name="capienza" id="capienza" value="<?php echo $capienza; ?>" />
	 
							</div>
	 
							<div class="col-md-4"  style="float: left; width: 32%;padding-bottom:5px">
	
								<label for="int">Sede <?php echo form_error('fk_sede') ?></label>	
								<input type="text" class="form-control"  name="fk_sede" id="fk_sede" value="<?php echo $fk_sede; ?>" />
	 
							</div>
	 
							<div class="col-md-4"  style="float: left; width: 32%;padding-bottom:5px">
	
								<label for="varchar">Nome Sala </label>	
								<input type="text" class="form-control"  name="nome" id="nome"  value="<?php echo $nome; ?>" />
	 
							</div><p style="clear: left;" /></p>	
		</div>		
		</div> 
							</div>
							

							</div>
							
							
			</section>   </body>
		</html>