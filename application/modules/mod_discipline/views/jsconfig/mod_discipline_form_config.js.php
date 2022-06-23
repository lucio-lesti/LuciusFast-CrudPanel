<?php /* FILE CONFIGURAZIONE FORM */?>

<SCRIPT>

arrayValidationFields = [];
js_custom_operations_list = [];
objAjaxConfig.form.mod_name ='<?php echo $frm_module_name;?>';
objAjaxConfig.form.recordID ='<?php echo $id;?>';
objAjaxConfig.form.ajaxAction = '<?php echo $ajaxAction;?>';
objAjaxConfig.form.id_main_content = '<?php echo $id_main_content;?>';
$('.select2-autocomplete').select2();
arrayValidationFields['winMasterDetail_mod_enti_discipline'] = []; 
arrayValidationFields['winMasterDetail_mod_enti_discipline']['fk_disciplina'] = [];
arrayValidationFields['winMasterDetail_mod_enti_discipline']['fk_disciplina']['label'] = "Disciplina"
arrayValidationFields['winMasterDetail_mod_enti_discipline']['fk_disciplina']['field_type'] = "int"
arrayValidationFields['winMasterDetail_mod_enti_discipline']['fk_ente'] = [];
arrayValidationFields['winMasterDetail_mod_enti_discipline']['fk_ente']['label'] = "Ente"
arrayValidationFields['winMasterDetail_mod_enti_discipline']['fk_ente']['field_type'] = "int"

arrayValidationFields['winMasterDetail_mod_insegnanti_discipline'] = [];
arrayValidationFields['winMasterDetail_mod_insegnanti_discipline']['fk_anagrafica'] = [];
arrayValidationFields['winMasterDetail_mod_insegnanti_discipline']['fk_anagrafica']['label'] = "Insegnante"
arrayValidationFields['winMasterDetail_mod_insegnanti_discipline']['fk_anagrafica']['field_type'] = "int"

 

</SCRIPT>