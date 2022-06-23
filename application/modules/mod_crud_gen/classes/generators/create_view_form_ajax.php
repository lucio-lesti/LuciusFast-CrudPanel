<?php


class create_view_form_ajax  extends AbstractGenerator{
	
	public static function output($param_gen){
		
		$helper = new Helper();
		foreach($param_gen as $key => $value){
			${$key} = $value;
		}			
		$form_columns_nr_layout = $form_columns_nr_layout;
		$fields_custom_settings = array();

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
		<?php 
			if((isset($id)) && ($id != "")){
				$id_main_content = $id;
			} else {	
				$id_main_content = rand(0,1000000);		
			}	
			$id_button = $id;
		?>
		<div <?php if($winForm == \'TRUE\'){ echo " class=\'main_content_ajax_form\' "; } ?> id="main_content_ajax_form_<?php echo $id_main_content;?>">	
			<!-- Content Header (Page header) -->
				<div class="col-md-8">
				<h3>
					<i class="fa '.$mod_icon .'"></i> 
					<a>
					<u> '.$mod_title.'</u>
					</a>
					<?php 
						if((isset($id)) && ($id != "")){
					?>
					<b style=\'font-size:22px\'> >> </b><b style=\'font-size:22px\'>Modifica ID:<?=$id?></b>
						<?php } else { ?>
					<b style=\'font-size:22px\'> >> </b><b style=\'font-size:22px\'>Nuovo</b>
					<?php }?>		   
				   
				</h3>		
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
				<div class="row">
					<!-- left column -->
					<div class="col-md-12">
						<!-- general form elements -->
						<div class="box box-primary">
							<!-- /.box-header -->
							<!-- form start -->
						
							 <div class="box-body">
								<?php $this->load->helper("form"); ?>
								<?php
									//SE GIA SALVATO IMPEDISCO IL SUBMIT DEL FORM
									if($afterSave == TRUE){
										$preventDefault = \'onsubmit="event.preventDefault();return false"\';	
									}
								?>';
								if ($form_print == 'Y') {							
									$string .= "
										<div class=\"form-group\">
											<?php 
												if((isset(\$id)) && (\$id != '')){
											?>
											<a target='_blank' class='btn btn-default' href='<?php echo base_url().\"$c_url/stampa/\$id\";?>'><i class=\"fa fa-print\"></i> STAMPA</a>
											<?php }?>
										</div>";	 
								}		
								
		$string .= "
				<form action=\"<?php echo \$action; ?>\" method=\"post\" 
					name=\"<?php echo \$frm_module_name.'_'.\$id; ?>\" 
					id=\"<?php echo \$frm_module_name.'_'.\$id; ?>\" <?php \$preventDefault;?>\>";

		$string .= "
					<div class='col-md-12'>
						<B STYLE='color:#990000'>(*)</B>Campi obbligatori
						<br><br>					
					</div>";
					
		$column_non_pk = "";
		$arrayOrd = array();
		foreach ($field_name as $keyReq =>$rowReq) {
			foreach ($non_pk as $row) {
				if($row['COLUMN_NAME'] == $rowReq){
					$arrayOrd[] = $row;
				}
			}
		}			
				
				
		$nrRow = 0;		
		foreach ($arrayOrd as $row) {
			//MOSTRO IL CAMPO SOLO SE SELEZIONATO
		 
			$fields_custom_settings[$nrRow]['field_name'] = $row["COLUMN_NAME"]; 
			$fields_custom_settings[$nrRow]['visible'] = 'TRUE';	
			$fields_custom_settings[$nrRow]['position'] = array("row_nr" => $nrRow, "column_nr" => 1);
			if(isset($select_ext_mod[$row['COLUMN_NAME']])){
				$fields_custom_settings[$nrRow]['ext_mod'] = $select_ext_mod[$row['COLUMN_NAME']];			
			}

			//SETTO LA LABEL
			if($row["COLUMN_COMMENT"] != ""){
				$etichettaLabel = $row["COLUMN_COMMENT"];
			} else {
				$etichettaLabel = $row["COLUMN_NAME"];
			}	
				
			$fields_custom_settings[$nrRow]['label'] = $etichettaLabel;			
				
			$string .= $column_field_begin;	
			
			$lblIsRequired = "";
			if($row['IS_NULLABLE'] == 'NO'){
				$lblIsRequired = "<b style=\"color:#990000\">(*)</b>";				
			}		
				
				
			//PRINT'<PRE>';print_r($row);die();	
			
			switch($row["DATA_TYPE"]){
				case 'blob':
				case 'tinyblob':
				case 'mediumblob':
				case 'longblob':
				case 'binary':
				case 'varbinary':	
					$firstChars = substr($row['COLUMN_NAME'],0,3);		
					$string .= "\n\t    <div class=\"form-group\">
							<label for=\"".$row["COLUMN_NAME"]."\">$lblIsRequired".$helper->label($etichettaLabel)." <?php echo form_error('".$row["COLUMN_NAME"]."') ?>";
					if($firstChars == 'img'){
						$string .= " (250px X 250px)";
					}	
					$string .= "</label>";

					$string .= "				
											<div class=\"input-group\">
												<div class=\"input-group-addon\"><i class=\"fa fa-text-height\"></i></div>";	
					$string .="\n<input type=\"hidden\" name=\"".$row["COLUMN_NAME"]."_hidden\" id=\"".$row["COLUMN_NAME"]."_hidden\"  value='<?php echo (\$".$row["COLUMN_NAME"]." != '') ? 'BLOB_SET' : ''?>' />";
					$string .="\n<input type=\"file\" class=\"form-control\"  name=\"".$row["COLUMN_NAME"]."\" id=\"".$row["COLUMN_NAME"]."\"  />\n";
						
					$string .= "	</div></div>";	
					
					if($firstChars == 'img'){
						$string .=" 
						<?php 
							if($".$row["COLUMN_NAME"]." != ''){
								echo '<div id=\"dv_allegati_blob_".$row["COLUMN_NAME"]."\">
								<div id=\"dv_allegati_blobErr_".$row["COLUMN_NAME"]."\" style=\"display:none;font-size:18px\" class=\"alert alert-error alert-dismissable\" onclick=\"hideMsg(\'dv_allegati_blobErr_".$row["COLUMN_NAME"]."\')\">
								</div>								
								<img src=\"data:image/jpeg;base64,'.$".$row["COLUMN_NAME"].".'\" style=\"width:250px;height:250px\"  />';	
								echo \"<br/><a style='cursor:pointer' onclick=\\\"rimuoviAllegatoBlob('$c_url', '".$row["COLUMN_NAME"]."','\$id','FIELD_BLOB_IMG')\\\"><img src=\\\"".base_url()."assets/images/delete2.png\\\" width='32'/> Elimina immagine</a></div>\";   
							}
						?>";					
					} else {
						$string .=" 
						<?php 
							if($".$row["COLUMN_NAME"]." != ''){
								if(\$id != ''){
									echo \"<div id=\\\"dv_allegati_blob_".$row["COLUMN_NAME"]."\\\">
									<div id=\\\"dv_allegati_blobErr_".$row["COLUMN_NAME"]."\\\" style=\\\"display:none;font-size:18px\\\" class=\\\"alert alert-error alert-dismissable\\\" onclick=\\\"hideMsg('dv_allegati_blobErr_".$row["COLUMN_NAME"]."')\\\">
									</div>										
									<a href='\".base_url().\"$c_url/scaricaAllegatoBlob/$c_url/".$row["COLUMN_NAME"]."/\".\$id.\"' target='_blank'>Scarica Allegato</a>\";
									echo \"<br><a style='cursor:pointer' onclick=\\\"rimuoviAllegatoBlob('$c_url', '".$row["COLUMN_NAME"]."','\$id','FIELD_BLOB')\\\"><img src=\\\"".base_url()."assets/images/delete2.png\\\" width='32'/> Elimina allegato</a></div>\";     
								} else {
									echo \"<b style='color:#990000'>(Eventuali allegati caricati, sono disponibili in modifica)</b>\";
								}
								 
							}
						?>";	
					}						
					
					$fields_custom_settings[$nrRow]['field_type'] = 'blob';						
				break;
				
				
				case 'enum':
					$string .= "\n\t    <div class=\"form-group\">
							<label for=\"".$row["COLUMN_NAME"]."\">$lblIsRequired".$helper->label($etichettaLabel)." <?php echo form_error('".$row["COLUMN_NAME"]."') ?></label>
							<SELECT name='".$row["COLUMN_NAME"]."' id='".$row["COLUMN_NAME"]."' class=\"form-control\" 
								style=\"width:100%;padding: 6px 12px;font-size:14px;
									border-top-right-radius:0px;border-bottom-right-radius:0px;
									border-top-left-radius:0px;border-bottom-left-radius:0px;
									border:1px solid #ccc\">";
					$COLUMN_TYPE =  str_replace("enum(","",$row["COLUMN_TYPE"]);
					$COLUMN_TYPE = substr($COLUMN_TYPE, 0, -1);
					$arrayColumnType = explode(",",$COLUMN_TYPE);	
					//$string .="\n<OPTION VALUE></OPTION>";		
					foreach($arrayColumnType as $key => $value){
						$string .="\n<OPTION <?php echo(\$".$row["COLUMN_NAME"]." == ".$value." ? 'SELECTED' : ''); ?> VALUE='".str_replace("'","",$value)."'>".str_replace("'","",$value)."</OPTION>";	
					}						
					$string .= "</SELECT>
						</div>";
 
					$fields_custom_settings[$nrRow]['field_type'] = 'select';					
				break;			
				
				case 'set':
					$string .= "\n\t    <div class=\"form-group\">
							<label for=\"".$row["COLUMN_NAME"]."\"><u>$lblIsRequired".$helper->label($etichettaLabel)." <?php echo form_error('".$row["COLUMN_NAME"]."') ?></u></label><br>";
					
					$COLUMN_TYPE =  str_replace("set(","",$row["COLUMN_TYPE"]);
					$COLUMN_TYPE = substr($COLUMN_TYPE, 0, -1);
					$arrayColumnType = explode(",",$COLUMN_TYPE);	
					$string .= "\n\t<?php";
					$string .= "\n\t\$".$row["COLUMN_NAME"]."_arr = explode(',',\$".$row["COLUMN_NAME"].");";
 
					foreach($arrayColumnType as $key => $value){
						//$string .= "\n\t<input name='".$row["COLUMN_NAME"]."[]' id='".$row["COLUMN_NAME"]."[]' type=\"checkbox\" value='".str_replace("'","",$value)."' > <b>".str_replace("'","",$value)."</b><br>";
						$string .= "\n\tif(in_array('".str_replace("'","",$value)."',\$".$row["COLUMN_NAME"]."_arr)){";
						$string .= "\n\t\techo \"<input name='".$row["COLUMN_NAME"]."[]' id='".$row["COLUMN_NAME"]."[]' type='checkbox' value='".str_replace("'","",$value)."' checked> <b>".str_replace("'","",$value)." </b><br>\";";
						$string .= "\n\t} else {";
						$string .= "\n\t\techo \"<input name='".$row["COLUMN_NAME"]."[]' id='".$row["COLUMN_NAME"]."[]' type='checkbox' value='".str_replace("'","",$value)."'  ><b>".str_replace("'","",$value)." </b><br>\";";
						$string .= "\n\t}";						
					}						
	 
					$string .= "\n\t?>";
					$string .= "\n\t</div>";

					$fields_custom_settings[$nrRow]['field_type'] = 'checkbox';	
				break;	

				case 'tinytext':
				case 'mediumtext':
				case 'longtext':
				case 'text':	
					$string .= "\n\t    <div class=\"form-group\">
							<label for=\"".$row["COLUMN_NAME"]."\">$lblIsRequired".$helper->label($etichettaLabel)." <?php echo form_error('".$row["COLUMN_NAME"]."') ?></label>
							<textarea class=\"form-control\" rows=\"3\" name=\"".$row["COLUMN_NAME"]."\" id=\"".$row["COLUMN_NAME"]."\" placeholder=\"".$helper->label($etichettaLabel)."\"><?php echo $".$row["COLUMN_NAME"]."; ?></textarea>
						</div>";	
					$fields_custom_settings[$nrRow]['field_type'] = 'textarea';			
					
				break;
				
				case 'char':
				case 'varchar':
					if(substr($row["COLUMN_NAME"],0,3) == "ora"){
						$string .= "\n\t    <div class=\"form-group\">
									<label for=\"".$row["DATA_TYPE"]."\">$lblIsRequired".$helper->label($etichettaLabel)." <?php echo form_error('".$row["COLUMN_NAME"]."') ?></label>
													<div class=\"input-group\">
														<div class=\"input-group-addon\"><i class=\"fa fa-calendar\"></i></div>			
									<input type=\"text\" class=\"form-control timemask\" maxlength='".$row["CHARACTER_MAXIMUM_LENGTH"]."' name=\"".$row["COLUMN_NAME"]."\" id=\"".$row["COLUMN_NAME"]."\" placeholder=\"".$helper->label($etichettaLabel)."\" autocomplete=\"off\" value=\"<?php echo $".$row["COLUMN_NAME"]."; ?>\" />
									</div></div>";		
						$fields_custom_settings[$nrRow]['field_type'] = 'time';							
					} else {
						if((substr($row["COLUMN_NAME"],0,5) == "email") || (substr($row["COLUMN_NAME"],0,4) == "mail")){
							$icon ="fa fa-at";
						} else {
							$icon ="fa fa-text-height";
						}						
						$string .= "\n\t    <div class=\"form-group\">
									<label for=\"".$row["DATA_TYPE"]."\">$lblIsRequired".$helper->label($etichettaLabel)." <?php echo form_error('".$row["COLUMN_NAME"]."') ?></label>
													<div class=\"input-group\">
														<div class=\"input-group-addon\"><i class=\"".$icon."\"></i></div>			
									<input type=\"text\" class=\"form-control\" maxlength='".$row["CHARACTER_MAXIMUM_LENGTH"]."' name=\"".$row["COLUMN_NAME"]."\" id=\"".$row["COLUMN_NAME"]."\" placeholder=\"".$helper->label($etichettaLabel)."\" autocomplete=\"off\" value=\"<?php echo $".$row["COLUMN_NAME"]."; ?>\" />
									</div></div>";	
						$fields_custom_settings[$nrRow]['field_type'] = 'text';		
					}
	
				break;
				
				
				case 'tinyint':
				case 'smallint':
				case 'mediumint':
				case 'bigint':
				case 'int':
				case 'serial':
					//print'<pre>';print_r($arrayRefFields);
					if(array_key_exists($row['COLUMN_NAME'],$arrayRefFields)){
						$string .= "\n\t    <div class=\"form-group\">
						<?php
						\$".$row["COLUMN_NAME"]."_label = NULL;
						foreach (\$".$row["COLUMN_NAME"]."_refval as \$key => \$value) {
							if (\$value['id'] == \$".$row["COLUMN_NAME"].") {
								\$".$row["COLUMN_NAME"]."_label = \$value['nome'];
							}  
						}
						?>						
						<label for=\"".$row["COLUMN_NAME"]."\">$lblIsRequired".$helper->label($etichettaLabel)." <?php echo form_error('".$row["COLUMN_NAME"]."') ?></label> 								
						<a style=\"cursor:pointer\" onclick=\"winFormCombo('".$arrayRefFields[$row["COLUMN_NAME"]]["REFERENCED_TABLE_NAME"]."','frm_".$arrayRefFields[$row["COLUMN_NAME"]]["REFERENCED_TABLE_NAME"]."_','".$row["COLUMN_NAME"]."_datalist','id','nome','Nuovo $etichettaLabel')\"><i class=\"fa fa-ellipsis-h\" style=\"padding: 2px 4px;border:1px solid #ccc;\"></i></a>	
						<a style=\"cursor:pointer\" onclick=\"pulisciCampo('".$row["COLUMN_NAME"]."_datalist_inp')\"><i class=\"fa fa-repeat\" style=\"padding: 2px 4px;border:1px solid #ccc;\"></i></a>	

						\n\t\t<span class=\"arrow_datalist\">
							\n\t\t<input autofocus class=\"form-control\" autocomplete=\"off\"
								list='".$row["COLUMN_NAME"]."_datalist' 
								oninput='onInput(\"".$row["COLUMN_NAME"]."_datalist_inp\",\"".$row["COLUMN_NAME"]."_datalist\",\"".$row["COLUMN_NAME"]."\")'
								style=\"width:100%;padding: 6px 12px;font-size:14px;
									border-top-right-radius:0px;border-bottom-right-radius:0px;
									border-top-left-radius:0px;border-bottom-left-radius:0px;
									border:1px solid #ccc\"
								name='".$row["COLUMN_NAME"]."_datalist_inp' id='".$row["COLUMN_NAME"]."_datalist_inp' 
								value='<?php echo \$".$row["COLUMN_NAME"]."_label;?>'></span>
							\n\t\t<input type=\"hidden\" name='".$row["COLUMN_NAME"]."' id='".$row["COLUMN_NAME"]."' value='<?php echo \$".$row["COLUMN_NAME"].";?>'>
							\n\t\t<datalist name='".$row["COLUMN_NAME"]."_datalist' id='".$row["COLUMN_NAME"]."_datalist' onselect=\"alert(this.text)\">
							\n\t\t";
						$string .="\n<OPTION VALUE></OPTION>";		
						$string .="\n<?php
						foreach (\$".$row["COLUMN_NAME"]."_refval as \$key => \$value) {
							if (\$value['id'] == \$".$row["COLUMN_NAME"].") {
								echo \"<option data-value='\" . \$value['id'] . \"' SELECTED>\" . \$value['nome'] . \"</option>\";
							} else {
								echo \"<option data-value='\".\$value['id'] . \"'>\" . \$value['nome'] . \"</option>\";
							}
						}
						?>";						
						$string .= "
							</div>";

						$fields_custom_settings[$nrRow]['field_type'] = 'select';	

					} else {
						$string .= "\n\t    <div class=\"form-group\">
							<label for=\"".$row["DATA_TYPE"]."\">$lblIsRequired".$helper->label($etichettaLabel)." <?php echo form_error('".$row["COLUMN_NAME"]."') ?></label>
										<div class=\"input-group\">
											<div class=\"input-group-addon\"><i class=\"fa fa-sort-numeric-asc\"></i></div>			
							<input type=\"number\" class=\"form-control\" maxlength='".$row["NUMERIC_PRECISION"]."' name=\"".$row["COLUMN_NAME"]."\" id=\"".$row["COLUMN_NAME"]."\" placeholder=\"".$helper->label($etichettaLabel)."\" autocomplete=\"off\" value=\"<?php echo $".$row["COLUMN_NAME"]."; ?>\" />
							</div></div>";			
						$fields_custom_settings[$nrRow]['field_type'] = 'numeric';			

					}	
					
				break;
				
				
				case 'decimal':
				case 'float':
				case 'double':
				case 'real':
					$string .= "\n\t    <div class=\"form-group\">
					<label for=\"".$row["DATA_TYPE"]."\">$lblIsRequired".$helper->label($etichettaLabel)." <?php echo form_error('".$row["COLUMN_NAME"]."') ?></label>
								<div class=\"input-group\">
									<div class=\"input-group-addon\"><i class=\"fa fa-sort-numeric-asc\"></i></div>			
					<input type=\"number\" class=\"form-control\" maxlength='".$row["NUMERIC_PRECISION"]."' name=\"".$row["COLUMN_NAME"]."\" id=\"".$row["COLUMN_NAME"]."\" placeholder=\"".$helper->label($etichettaLabel)."\" autocomplete=\"off\" value=\"<?php echo $".$row["COLUMN_NAME"]."; ?>\" step=0.01 />
					</div></div>";			
				$fields_custom_settings[$nrRow]['field_type'] = 'float';			


				break;		

				//USATO PER IL FORMATO YYYY/MM/DD
				case 'date':
					$string .= "\n\t    <div class=\"form-group\">
							<label for=\"".$row["DATA_TYPE"]."\">$lblIsRequired".$helper->label($etichettaLabel)." <?php echo form_error('".$row["COLUMN_NAME"]."') ?></label>
											<div class=\"input-group\">
												<div class=\"input-group-addon\"><i class=\"fa fa-calendar\"></i></div>			
							<input type=\"text\" class=\"form-control datemask\" name=\"".$row["COLUMN_NAME"]."\" id=\"".$row["COLUMN_NAME"]."\" placeholder=\"".$helper->label($etichettaLabel)."\" 
								autocomplete=\"off\" style=\"background-color:#FFFFFF\"  value=\"<?php echo $".$row["COLUMN_NAME"]."; ?>\" />
						</div></div>";	
					$fields_custom_settings[$nrRow]['field_type'] = 'date';		
				break;
				
				
				//USATO PER IL FORMATO YYYY/MM/DD HH:MM
				case 'datetime':
				case 'timestamp':
					$string .= "\n\t    <div class=\"form-group\">
							<label for=\"".$row["DATA_TYPE"]."\">$lblIsRequired".$helper->label($etichettaLabel)." <?php echo form_error('".$row["COLUMN_NAME"]."') ?></label>
											<div class=\"input-group\">
												<div class=\"input-group-addon\"><i class=\"fa fa-calendar\"></i></div>			
							<input type=\"text\" class=\"form-control datetimemask\" name=\"".$row["COLUMN_NAME"]."\" id=\"".$row["COLUMN_NAME"]."\" placeholder=\"".$helper->label($etichettaLabel)."\" autocomplete=\"off\" value=\"<?php echo $".$row["COLUMN_NAME"]."; ?>\" />
						</div></div>";		
					$fields_custom_settings[$nrRow]['field_type'] = 'datetime';	
				break;
			

				//USATO PER IL FORMATO HH:MM
				case 'time':
					$string .= "\n\t    <div class=\"form-group\">
							<label for=\"".$row["DATA_TYPE"]."\">$lblIsRequired".$helper->label($etichettaLabel)." <?php echo form_error('".$row["COLUMN_NAME"]."') ?></label>
											<div class=\"input-group\">
												<div class=\"input-group-addon\"><i class=\"fa fa-calendar\"></i></div>			
							<input type=\"text\" class=\"form-control timemask\" name=\"".$row["COLUMN_NAME"]."\" id=\"".$row["COLUMN_NAME"]."\" placeholder=\"".$helper->label($etichettaLabel)."\" autocomplete=\"off\" value=\"<?php echo $".$row["COLUMN_NAME"]."; ?>\" />
						</div></div>";		
					$fields_custom_settings[$nrRow]['field_type'] = 'time';	
				break;				
				

				default:
					$string .= "\n\t    <div class=\"form-group\">
							<label for=\"".$row["DATA_TYPE"]."\">$lblIsRequired".$helper->label($etichettaLabel)." <?php echo form_error('".$row["COLUMN_NAME"]."') ?></label>
											<div class=\"input-group\">
												<div class=\"input-group-addon\"><i class=\"fa fa-cubes\"></i></div>			
							<input type=\"text\" class=\"form-control\" maxlength='".$row["CHARACTER_MAXIMUM_LENGTH"]."' name=\"".$row["COLUMN_NAME"]."\" id=\"".$row["COLUMN_NAME"]."\" placeholder=\"".$helper->label($etichettaLabel)."\" autocomplete=\"off\" value=\"<?php echo $".$row["COLUMN_NAME"]."; ?>\" />
						</div></div>";		
					$fields_custom_settings[$nrRow]['field_type'] = 'text';			
				break;
			}
			$string .= $column_field_end;	
		 
			$nrRow++;
			
		}
		 

		if($form_allegati == 'Y'){		
			$string .= "\n\t
							<div class=\"col-md-12\">
								<div class=\"form-group\">
										<label for=\"note\">Allegati <?php echo form_error(\"allegati\") ?>
										<br><span style=\"font-size:12px\"><b>File Ammessi:</b><b style=\"color:#990000\"><?php echo implode(\",\",\$extAdmitted);?></b></span>
										</label>
										<input type=\"file\" name=\"allegati[]\" id=\"allegati[]\" class=\"form-control\" multiple />
										
										<br>
										<ul>
										<?php		
											\$icon_delete = \"<img src='\".base_url().\"assets/images/delete2.png' width='32'/>\";
											
											\$count = 0;
											foreach(\$allegati as \$key => \$allegato){
												\$nomeAllegato =  \$allegato['allegato'];

												if((\$id != '')){
													echo \"<li id='allegato_\".\$count.\"'><a href='\".base_url().\"$c_url/scaricaAllegato/$c_url/\".\$id.\"/\".\$nomeAllegato.\"' target='_blank'>\".\$allegato['allegato'].\"</a>\"; 
													echo \"<a style='cursor:pointer' onclick=\\\"rimuoviAllegato('$c_url', '\$id','\$nomeAllegato','allegato_\$count')\\\"> | \".\$icon_delete.\"</a></li>\";
													
												} else {
													echo \$allegato['allegato'].\"<BR>\"; 
												}
												
												\$count++;
											}
											if((\$id == '')){
												echo \"<br><br><b> - Note:</b>Gli allegati possono essere cancellati solo in modifica\";
											}
										?>
										</ul>
										<br/>
									</div>
							</div>";
		}					

		if($show_master_detail == 'Y'){
			$string .= "\n\t
			<div class=\"col-md-12\">
			<div class=\"form-group\" id=\"divAjaxMsg_container\">
				<div id=\"divAjaxMsg\" style=\"display:none;font-size:18px\" class=\"alert alert-success alert-dismissable\" onclick=\"hideMsg('divAjaxMsg')\">
				</div>
				<div id=\"divAjaxMsgErr\" style=\"display:none;font-size:18px\" class=\"alert alert-error alert-dismissable\" onclick=\"hideMsg('divAjaxMsgErr')\">
				</div>			
				<div id=\"master_details_list\" name=\"master_details_list\">
					<ul class=\"nav nav-tabs\" id=\"myTab\" role=\"tablist\">	
					<?php
						if(isset(\$master_details_list)){
							\$countTab = 0;
							foreach(\$master_details_list as  \$key => \$master_details){
								if(\$countTab == 0) {
									echo'<li class=\"nav-item active\">
									<a class=\"nav-link active\" id=\"lnk-'.\$master_details['id'].'\" data-toggle=\"tab\" href=\"#'.\$master_details['id'].'\" role=\"tab\" aria-controls=\"'.\$master_details['id'].'\" aria-selected=\"true\" aria-expanded=\"true\">'.\$master_details['title'].'</a>
									</li>';
								} else {
									echo'<li class=\"nav-item\">
									<a class=\"nav-link\" id=\"lnk-'.\$master_details['id'].'\" data-toggle=\"tab\" href=\"#'.\$master_details['id'].'\" role=\"tab\" aria-controls=\"'.\$master_details['id'].'\" aria-selected=\"true\" aria-expanded=\"true\">'.\$master_details['title'].'</a>
									</li>';
								}
								\$countTab++;
							}	
						}
					?>
					</ul>
					<div class=\"tab-content\">
					<?php
						if(isset(\$master_details_list)){
							\$countTab = 0;
							foreach(\$master_details_list as  \$key => \$master_details){
								\$active = \"active\";
								if(\$countTab > 0) {
									\$active = \"\";
								} 
								echo'<div class=\"tab-pane '.\$active.'\" id=\"'.\$master_details['id'].'\" role=\"tabpanel\" aria-labelledby=\"'.\$master_details['id'].'-tab\">';
								echo  \$master_details['function'];
								echo'</div>';
								\$countTab++;
							}	
						}
					?>
	
					</div>
				</div>					
				<p><br><br><br></p>
			</div>
		</div>";
		}


		$string .= "\n\t    <input type=\"hidden\" name=\"".$pk."\" value=\"<?php echo $".$pk."; ?>\" /> ";

		$string .= "<div class=\"row\">
						<div class=\"col-md-12\">";	
		$string .= "\n<?php";
		$string .= "\n\tif (\$afterSave == NULL) {";
		$string .= "\n?>";				
		$string .= "
								  <div class='row'>
									<div class='col-md-6'>
										<?php 
										if(\$winForm == \"FALSE\"){
										?>
										<button id='<?php echo \$button_id; ?>' type=\"submit\" 
										type=\"button\" class=\"btn btn-success  button-submit\"  data-loading-text=\"Caricamento...\"><span class=\"fa fa-save\"></span> SALVA</button>
 										<?php } ?>
									</div>
									
										<div class=\"col-md-6\" align=\"right\">		
										</div>	
										
									</div>
						</div>
					</div>";

		$string .= "\n<?php } ?>";		 
		$string .= "\n\t</form>";

		$string .= "
						</div>
							
						<!-- form close -->
					</div>
				</div>
			</div>
		<?php
			if(\$type_action == 'create'){
				\$ajaxAction = '$c_url/create_action';
			} else {
				\$ajaxAction = '$c_url/update_action';

			}	

			\$data['ajaxAction'] = \$ajaxAction;
			\$data['frm_module_name'] = \$frm_module_name;
			\$data['id'] = \$id;
			\$data['id_main_content'] = \$id_main_content;
		?>";	
		$string .= "\n<?php echo \$this->load->view(\"jsconfig/$c_url"."_form_config.js.php\", \$data,true);?>";
		$string .= "\n<script src=\"<?php echo base_url(); ?>assets/js/form_submit_ajax.config.js\"></script>
		</div>";			
	
		return $string;
	}

}	  


?>