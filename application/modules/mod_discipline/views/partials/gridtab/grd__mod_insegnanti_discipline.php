
			<?php
			echo '
				<br><a class="btn btn-primary" style="cursor:pointer" onclick="winFormMasterDetails(\'mod_discipline\',\'winMasterDetail_mod_insegnanti_discipline\',\'insert\',' . $id . ',\'NULL\',\'Associa Insegnanti a disciplina\', arrayValidationFields,\'winMasterDetail_mod_insegnanti_discipline\',\'multi\',\'getMasterDetail_mod_insegnanti_discipline\')">[ Aggiungi un elemento]</a><br>
				<br><br>';
			echo ' <input  type="text" class="form-control" autocomplete="off" 
		id="search_mod_insegnanti_discipline" style="width:20%" placeholder="Cerca..."
		onkeypress="disableKeySubmit()"
		onkeyup="searchInMasterDetailsTable(\'search_mod_insegnanti_discipline\', \'tbl_mod_insegnanti_discipline\',3)"><br>';
			echo "<table class='TFtable' id='tbl_mod_insegnanti_discipline' style='font-size:12px'>
		<tr>
			<thead>
			<th class='sorting_disabled' rowspan='1' colspan='1' aria-label='' style='width:10%;'>
				<input type='checkbox' id='check_master_mod_insegnanti_discipline' name='check_master_mod_insegnanti_discipline' 
				onchange=\"selezionaDeselezionaTutti('check_master_mod_insegnanti_discipline','check_id_mod_insegnanti_discipline','btDeleteMass_mod_insegnanti_discipline')\">
			</th>";
			echo '<th>Insegnante</th>';
 
			if ($winFormType == "form") {
				//echo '<th>Modifica</th>';
			}
			echo '<th>Elimina</th>';
			echo '</tr>';
			echo '<tbody>';
			foreach ($row as $key => $value) {
				echo "<tr>";
				echo "<td><input type='checkbox' id='check_id_mod_insegnanti_discipline' name='check_id_mod_insegnanti_discipline' value='" . $value['id'] . "' onchange=\"verificaNrCheckBoxSelezionati('check_id_mod_insegnanti_discipline','btDeleteMass_mod_insegnanti_discipline')\"></td>";
				echo "<td><input type='hidden' id='id[]' name='id[]' value='" . $value['id'] . "'>" . $value['mod_anagrafica_nome'] . "</td>";
				if ($winFormType == "form") {
					//echo "<td><a style='cursor:pointer' class='btn btn-sm btn-info' onclick ='winFormMasterDetails(\"mod_discipline\",\"winMasterDetail_mod_insegnanti_discipline\",\"edit\", $id," . $value['id'] . ",\"MODIFICA Insegnanti associati a discipline\",arrayValidationFields,\"winMasterDetail_mod_insegnanti_discipline\",\"form\",\"getMasterDetail_mod_insegnanti_discipline\")' title='Modifica Insegnanti associati a discipline'><i class='fa fa-edit'></a></td>";
				}
				echo "<td><a style='cursor:pointer' class='btn btn-sm btn-danger deleteUser' onclick ='deleteMasterDetails(" . $value['id'] . ", " . $id . ", \"mod_discipline\",\"_mod_insegnanti_discipline\",\"getMasterDetail_mod_insegnanti_discipline\")' title='Elimina'><i class='fa fa-trash'></a></td>";
				echo '</tr>';
			}
			echo '</tbody></table>';
			echo '<br/><a class="btn btn-sm btn-danger deleteUser" id="btDeleteMass_mod_insegnanti_discipline" name="btDeleteMass_mod_insegnanti_discipline""
					onclick="deleteMassiveMasterDetails(' . $id . ',\'entry_list\',\'check_id_mod_insegnanti_discipline\',\'mod_discipline\',\'_mod_insegnanti_discipline\',\'getMasterDetail_mod_insegnanti_discipline\')">
					<i class="fa fa-trash"></i> Cancellazione Massiva
				</a>';
