<?php
 
class create_view_read extends AbstractGenerator{
	
	public static function output(){
		
		$helper = new Helper();
 		foreach($param_gen as $key => $value){
			${$key} = $value;
		}	
		$form_columns_nr_layout = $form_columns_nr_layout;

		switch($form_columns_nr_layout){
			case 1:
			case '1':
				$column_field_begin ="";
				$column_field_end ="";
			break;
			
			case 2:
			case '2':
				$column_field_begin ="<div class=\"col-md-6\">";
				$column_field_end ="</div>";	
			break;

			case 3:
			case '3':
				$column_field_begin ="<div class=\"col-md-4\">";
				$column_field_end ="</div>";		
			break;	
			
			
			default:
				$column_field_begin ="";
				$column_field_end ="";	
			break;
			
		}


		$mod_icon = $mod_icon;
		$string ='
		<div class="content-wrapper">
			
			<!-- Content Header (Page header) -->
			<section class="content-header">
				<h1>
					<i class="fa '.$mod_icon .'"></i> 
					<a href="<?php echo site_url("'.$c_url.'") ?>">
					<u> '.$mod_title.'</u>
					</a>
				   <b style=\'font-size:20px\'> >> </b><b style=\'font-size:20px\'>Descrizione Azione</b>
				</h1>
					<div class="col-md-4">
						<?php
						$error = $this->session->flashdata(\'error\');
						if($error)
						{
					?>
							<div class="alert alert-danger alert-dismissable">
								<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
								<?php echo $this->session->flashdata(\'error\'); ?>
							</div>
							<?php } ?>
							<?php  
						$success = $this->session->flashdata(\'success\');
						if($success)
						{
					?>
							<div class="alert alert-success alert-dismissable">
								<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
								<?php echo $this->session->flashdata(\'success\'); ?>
							</div>
							<?php } ?>

							<div class="row">
								<div class="col-md-12">
									<?php echo validation_errors(\'<div class="alert alert-danger alert-dismissable">\', \' <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button></div>\'); ?>
								</div>
							</div>
					</div>		
			</section>
			<section class="content">
				
				<div class="row">
					<!-- left column -->
					<div class="col-md-8">
						<!-- general form elements -->
						<div class="box box-primary">
							<div class="box-header">
								<h3 class="box-title">Informazioni Modulo</h3>
							</div>
							<!-- /.box-header -->
							<!-- form start -->
						
							 <div class="box-body">
								<?php $this->load->helper("form"); ?>';
		 
		$nrRow = 0;		
		foreach ($non_pk as $row) {
			
			//MOSTRO IL CAMPO SOLO SE SELEZIONATO
			if(in_array($row['column_name'],$field_name)){		
				//SETTO LA LABEL
				if($row["column_comment"] != ""){
					$etichettaLabel = $row["column_comment"];
				} else {
					$etichettaLabel = $row["column_name"];
				}		
					
				$string .= $column_field_begin;	
				switch($row["data_type"]){
					case 'blob':
					case 'tinyblob':
					case 'mediumblob':
					case 'longblob':
						$string .= "\n\t    <div class=\"form-group\">
								<label for=\"".$row["data_type"]."\">".$helper->label($etichettaLabel)." <?php echo form_error('".$row["column_name"]."') ?></label>";
						$string .=" 
							<?php 
								if($".$row["column_name"]." != ''){
									echo '<img src=\"data:image/jpeg;base64,'.$".$row["column_name"].".'\" style=\"width:90px\"  />';	
								}
							?>				
						";
						$string .= "				
												<div class=\"input-group\">
													<div class=\"input-group-addon\"><i class=\"fa fa-text-height\"></i></div>";	
			
						$string .=" <input disabled='disabled'  type=\"file\" class=\"form-control\"  name=\"".$row["column_name"]."\" id=\"".$row["column_name"]."\"  />";
							
						$string .= "	</div></div>";						
					break;
					
					case 'enum':
					case 'set':
						$string .= "\n\t    <div class=\"form-group\">
								<label for=\"".$row["column_name"]."\">".$helper->label($etichettaLabel)." <?php echo form_error('".$row["column_name"]."') ?></label>
								<SELECT name='".$row["column_name"]."' id='".$row["column_name"]."' class=\"form-control\" DISABLED>";
						$string .=" 
						<?php if($".$row["column_name"]." == 'SI'){
								echo '<OPTION VALUE=\"SI\" SELECTED>SI</OPTION>
									<OPTION VALUE=\"NO\">NO</OPTION>';				
							} else {
								echo '<OPTION VALUE=\"SI\" >SI</OPTION>
									<OPTION VALUE=\"NO\" SELECTED>NO</OPTION>';							
							}
						?>				
						";						
						$string .= "</SELECT>
							</div>";			
					break;			
					
					case 'tinytext':
					case 'mediumtext':
					case 'longtext':
					case 'text':
						$string .= "\n\t    <div class=\"form-group\">
								<label for=\"".$row["column_name"]."\">".$helper->label($etichettaLabel)." <?php echo form_error('".$row["column_name"]."') ?></label>
								<textarea disabled='disabled' class=\"form-control\" rows=\"3\" name=\"".$row["column_name"]."\" id=\"".$row["column_name"]."\" placeholder=\"".$helper->label($etichettaLabel)."\"><?php echo $".$row["column_name"]."; ?></textarea>
							</div>";			
					break;
					
					case 'char':
					case 'varchar':
						$string .= "\n\t    <div class=\"form-group\">
								<label for=\"".$row["data_type"]."\">".$helper->label($etichettaLabel)." <?php echo form_error('".$row["column_name"]."') ?></label>
												<div class=\"input-group\">
													<div class=\"input-group-addon\"><i class=\"fa fa-text-height\"></i></div>			
								<input type=\"text\" disabled='disabled' class=\"form-control\" maxlength='".$row["CHARACTER_MAXIMUM_LENGTH"]."' name=\"".$row["column_name"]."\" id=\"".$row["column_name"]."\" placeholder=\"".$helper->label($etichettaLabel)."\" value=\"<?php echo $".$row["column_name"]."; ?>\" />
							</div></div>";		
					break;
					
					
					case 'tinyint':
					case 'smallint':
					case 'mediumint':
					case 'bigint':
					case 'int':
					case 'decimal':
					case 'float':
					case 'double':
					case 'real':
					case 'serial':
						$string .= "\n\t    <div class=\"form-group\">
								<label for=\"".$row["data_type"]."\">".$helper->label($etichettaLabel)." <?php echo form_error('".$row["column_name"]."') ?></label>
												<div class=\"input-group\">
													<div class=\"input-group-addon\"><i class=\"fa fa-sort-numeric-asc\"></i></div>			
								<input type=\"number\" disabled='disabled' class=\"form-control\" maxlength='".$row["numeric_precision"]."' name=\"".$row["column_name"]."\" id=\"".$row["column_name"]."\" placeholder=\"".$helper->label($etichettaLabel)."\" value=\"<?php echo $".$row["column_name"]."; ?>\" />
							</div></div>";			
				
					break;
					
					
					case 'date':
						$string .= "\n\t    <div class=\"form-group\">
								<label for=\"".$row["data_type"]."\">".$helper->label($etichettaLabel)." <?php echo form_error('".$row["column_name"]."') ?></label>
												<div class=\"input-group\">
													<div class=\"input-group-addon\"><i class=\"fa fa-calendar\"></i></div>			
								<input type=\"text\" disabled='disabled' class=\"form-control\" name=\"".$row["column_name"]."\" id=\"".$row["column_name"]."\" placeholder=\"".$helper->label($etichettaLabel)."\" value=\"<?php echo $".$row["column_name"]."; ?>\" />
							</div></div>";	
			 
					break;
					
					
					case 'datetime':
					case 'timestamp':
					case 'time':
						$string .= "\n\t    <div class=\"form-group\">
								<label for=\"".$row["data_type"]."\">".$helper->label($etichettaLabel)." <?php echo form_error('".$row["column_name"]."') ?></label>
												<div class=\"input-group\">
													<div class=\"input-group-addon\"><i class=\"fa fa-calendar\"></i></div>			
								<input type=\"text\" disabled='disabled' class=\"form-control\" name=\"".$row["column_name"]."\" id=\"".$row["column_name"]."\" placeholder=\"".$helper->label($etichettaLabel)."\" value=\"<?php echo $".$row["column_name"]."; ?>\" />
							</div></div>";		
			 
					break;
					
					
					default:
						$string .= "\n\t    <div class=\"form-group\">
								<label for=\"".$row["data_type"]."\">".$helper->label($etichettaLabel)." <?php echo form_error('".$row["column_name"]."') ?></label>
												<div class=\"input-group\">
													<div class=\"input-group-addon\"><i class=\"fa fa-cubes\"></i></div>			
								<input type=\"text\" disabled='disabled' class=\"form-control\" maxlength='".$row["CHARACTER_MAXIMUM_LENGTH"]."' name=\"".$row["column_name"]."\" id=\"".$row["column_name"]."\" placeholder=\"".$helper->label($etichettaLabel)."\" value=\"<?php echo $".$row["column_name"]."; ?>\" />
							</div></div>";		
					
					break;
				}
				$string .= $column_field_end;	
			}
			
			$nrRow++;
			
		}
		 

		$string .= "\n\t    <input type=\"hidden\" name=\"".$pk."\" value=\"<?php echo $".$pk."; ?>\" /> ";

		$string .= "<div class=\"row\">
						<div class=\"col-md-12\">";	
						
		$string .= "		<div class=\"box-footer\">
								  <div class='row'>
										<div class=\"col-md-12\" align=\"right\">
											<a href=\"<?php echo site_url('$c_url') ?>\" class=\"btn btn-default\"><i class=\"fa fa-arrow-circle-left\"></i> INDIETRO</a>			
										</div>	
										
									</div>
								</div>	
						</div>
					</div>	
			
		</div>		
		</div>";

		 
		$string .= "
							</div>

							</div>
						</div>
					</div>
			</section>

			</div>";			
	
		return $string;
	}

}	 


 


?>