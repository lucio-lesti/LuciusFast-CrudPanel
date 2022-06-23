<?php /* FILE CONFIGURAZIONE FORM */?>

<SCRIPT>

arrayValidationFields = [];
js_custom_operations_list = [];
objAjaxConfig.form.mod_name ='<?php echo $frm_module_name;?>';
objAjaxConfig.form.recordID ='<?php echo $id;?>';
objAjaxConfig.form.ajaxAction = '<?php echo $ajaxAction;?>';
objAjaxConfig.form.id_main_content = '<?php echo $id_main_content;?>';
$('.select2-autocomplete').select2();

arrayValidationFields['winMasterDetail_mod_pagamenti_collaboratori'] = [];
arrayValidationFields['winMasterDetail_mod_pagamenti_collaboratori']['datapagamento'] = [];
arrayValidationFields['winMasterDetail_mod_pagamenti_collaboratori']['datapagamento']['label'] = "Data Pagamento"
arrayValidationFields['winMasterDetail_mod_pagamenti_collaboratori']['datapagamento']['field_type'] = "datetime"

/*
arrayValidationFields['winMasterDetail_mod_pagamenti_collaboratori']['ora_pagamento'] = [];
arrayValidationFields['winMasterDetail_mod_pagamenti_collaboratori']['ora_pagamento']['label'] = "Ora Pagamento"
arrayValidationFields['winMasterDetail_mod_pagamenti_collaboratori']['ora_pagamento']['field_type'] = "time"
*/

arrayValidationFields['winMasterDetail_mod_pagamenti_collaboratori'] = [];
arrayValidationFields['winMasterDetail_mod_pagamenti_collaboratori']['mese_anno'] = [];
arrayValidationFields['winMasterDetail_mod_pagamenti_collaboratori']['mese_anno']['label'] = "Mese / Anno";
arrayValidationFields['winMasterDetail_mod_pagamenti_collaboratori']['mese_anno']['field_type'] = "datetime";

arrayValidationFields['winMasterDetail_mod_pagamenti_collaboratori']['fk_causale_pagamento'] = [];
arrayValidationFields['winMasterDetail_mod_pagamenti_collaboratori']['fk_causale_pagamento']['label'] = "Causale Pagamento"
arrayValidationFields['winMasterDetail_mod_pagamenti_collaboratori']['fk_causale_pagamento']['field_type'] = "int"

arrayValidationFields['winMasterDetail_mod_pagamenti_collaboratori']['importo'] = [];
arrayValidationFields['winMasterDetail_mod_pagamenti_collaboratori']['importo']['label'] = "Importo"
arrayValidationFields['winMasterDetail_mod_pagamenti_collaboratori']['importo']['field_type'] = "float"

//arrayValidationFields['winMasterDetail_mod_pagamenti_collaboratori']['notepagamento'] = [];
//arrayValidationFields['winMasterDetail_mod_pagamenti_collaboratori']['notepagamento']['label'] = "Note Pagamento"
//arrayValidationFields['winMasterDetail_mod_pagamenti_collaboratori']['notepagamento']['field_type'] = "longtext"

arrayValidationFields['winMasterDetail_mod_pagamenti_collaboratori']['fk_tipopagamento'] = [];
arrayValidationFields['winMasterDetail_mod_pagamenti_collaboratori']['fk_tipopagamento']['label'] = "Tipo Pagamento"
arrayValidationFields['winMasterDetail_mod_pagamenti_collaboratori']['fk_tipopagamento']['field_type'] = "int"

</SCRIPT>