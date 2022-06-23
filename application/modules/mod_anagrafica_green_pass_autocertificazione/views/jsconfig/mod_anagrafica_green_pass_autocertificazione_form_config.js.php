<?php /* FILE CONFIGURAZIONE FORM */?>

<SCRIPT>

arrayValidationFields = [];
js_custom_operations_list = [];
objAjaxConfig.form.mod_name ='<?php echo $frm_module_name;?>';
objAjaxConfig.form.recordID ='<?php echo $id;?>';
objAjaxConfig.form.ajaxAction = '<?php echo $ajaxAction;?>';
objAjaxConfig.form.id_main_content = '<?php echo $id_main_content;?>';
$('.select2-autocomplete').select2();
</SCRIPT>