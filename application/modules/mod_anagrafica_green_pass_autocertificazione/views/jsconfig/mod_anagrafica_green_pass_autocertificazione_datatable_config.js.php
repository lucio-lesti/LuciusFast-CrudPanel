<?php /*FILE CONFIGURAZIONE DATATABLE*/ ?>

<SCRIPT>

objAjaxConfig.mod_name = 'mod_anagrafica_green_pass_autocertificazione';
objAjaxConfig.mod_title = 'Autocertificazioni Green Pass';
var columnArray = [];
columnArray[1] = {type:"text", name:"mod_anagrafica_nome"};	
columnArray[2] = {type:"date", name:"_mod_anagrafica_green_pass_autocertificazione_data_autocertificazione_fine_validita"};	
var columnGrid = [
{"data": "ids","orderable": false},
{"data": "mod_anagrafica_nome"},
{"data": "_mod_anagrafica_green_pass_autocertificazione_data_autocertificazione_fine_validita"},
{"data": "action","orderable": false,"className": "text-center"}
];
if(request_js_id != ''){
	editAjax('mod_anagrafica_green_pass_autocertificazione',request_js_id);
}

</SCRIPT>