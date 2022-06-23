
			<?php
		echo '<div class="form-group">';
		 
							$fk_disciplina_label = NULL;
							foreach ($fk_disciplina_refval as $key => $value) {
								if ($value['id'] == $rowWinForm['fk_disciplina']) {
									$fk_disciplina_label = $value['nome'];
								}  
							}
				 						
							
		echo '<label for="fk_disciplina"><b style="color:#990000">(*)</b>Disciplina </label>';
							
		echo "							
							
		<input autofocus class='form-control' autocomplete='off' 
								oninput='onInput(\"fk_disciplina_datalist_inp\",\"fk_disciplina_datalist\",\"fk_disciplina\")'
								style='width:100%;padding: 6px 12px;font-size:14px;
									border-top-right-radius:0px;border-bottom-right-radius:0px;
									border-top-left-radius:0px;border-bottom-left-radius:0px;
									border:1px solid #ccc'						
								list='fk_disciplina_datalist'  
								name='fk_disciplina_datalist_inp' 
								id='fk_disciplina_datalist_inp' value='$fk_disciplina_label'>
							
		<input type='hidden' name='fk_disciplina' id='fk_disciplina' value='".$rowWinForm['fk_disciplina']."' >
							
		<datalist name='fk_disciplina_datalist' id='fk_disciplina_datalist' >";	echo '<OPTION VALUE></OPTION>';

		foreach ($fk_disciplina_refval as $key => $value) {
			if ($value['id'] == $rowWinForm['fk_disciplina']) {
				echo "<option data-value='" . $value['id'] . "' SELECTED>" . $value['nome'] . "</option>";
			} else {
				echo "<option data-value='".$value['id'] . "'>" . $value['nome'] . "</option>";
			}
		}
		echo '</SELECT>';
										
		echo '</div>';
		echo '<div class="form-group">';
		 
							$fk_ente_label = NULL;
							foreach ($fk_ente_refval as $key => $value) {
								if ($value['id'] == $rowWinForm['fk_ente']) {
									$fk_ente_label = $value['nome'];
								}  
							}
				 						
							
		echo '<label for="fk_ente"><b style="color:#990000">(*)</b>Ente </label>';
							
		echo "							
							
		<input autofocus class='form-control' autocomplete='off' 
								oninput='onInput(\"fk_ente_datalist_inp\",\"fk_ente_datalist\",\"fk_ente\")'
								style='width:100%;padding: 6px 12px;font-size:14px;
									border-top-right-radius:0px;border-bottom-right-radius:0px;
									border-top-left-radius:0px;border-bottom-left-radius:0px;
									border:1px solid #ccc'						
								list='fk_ente_datalist'  
								name='fk_ente_datalist_inp' 
								id='fk_ente_datalist_inp' value='$fk_ente_label'>
							
		<input type='hidden' name='fk_ente' id='fk_ente' value='".$rowWinForm['fk_ente']."' >
							
		<datalist name='fk_ente_datalist' id='fk_ente_datalist' >";	echo '<OPTION VALUE></OPTION>';

		foreach ($fk_ente_refval as $key => $value) {
			if ($value['id'] == $rowWinForm['fk_ente']) {
				echo "<option data-value='" . $value['id'] . "' SELECTED>" . $value['nome'] . "</option>";
			} else {
				echo "<option data-value='".$value['id'] . "'>" . $value['nome'] . "</option>";
			}
		}
		echo '</SELECT>';
										
		echo '</div>';