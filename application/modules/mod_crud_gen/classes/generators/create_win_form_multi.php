<?php


class create_win_form_multi {
	
	public static function output($arrayRefFields,$arrayField, $param_gen, $table){
 
		$helper = new Helper();
		foreach($param_gen as $key => $value){
			${$key} = $value;
		}		
		
		$arrayRefFields = $Mod_crud_gen_model->getFieldsRef($table['TABLE_NAME']);	
		$arrayField = $Mod_crud_gen_model->getColumnsTable($table['TABLE_NAME']);

		$nrRow = 0;		
        $column_field_begin ="";
        $column_field_end ="";
        $string = "\n\t\t\t<?php";
 
		$string .= "\n\t\t\$html = '<div>
		<section class=\"content\">
			<div class=\"row\">
				<div class=\"col-md-12\">
					<div class=\"box box-primary\">
						<div class=\"box-body\">
						<div id=\"msg_err\" ondblclick=\"this.style.display=\'none\';\" style=\"display: none; border: 1px solid; background-color: rgb(255, 64, 0); border-radius: 5px; padding: 10px; color: white; font-weight: bold;\">
						</div>									
							<form  name=\"frm_master_detail\" id=\"frm_master_detail\">
							<input type=\"hidden\" id=\"table\" name=\"table\" value=\"".$table['TABLE_NAME']."\">
							<input type=\"hidden\" id=\"action\" name=\"action\" value=\"'.\$action.'\"/> 
							<input type=\"hidden\" id=\"saveType\" name=\"saveType\" value=\"form\"/> 	
							<input type=\"hidden\" id=\"entryID\"          name=\"entryID\"  value=\"'.\$entryID.'\">													
								<div class=\"col-md-12\">
									<div class=\"form-group\">';";	
									
		$string .= "\n\t\t\$html .= '
								</div>													
							</div>
						</div>
					</div>
				</div>
				</form>
			</section>
		</div>';";	

		return $helper->createFile($string, APPPATH."modules/" . $param_gen['c_url'] . "/views/partials/winform/winm_" . $table['TABLE_NAME'].".php");		

	}

}	  


?>