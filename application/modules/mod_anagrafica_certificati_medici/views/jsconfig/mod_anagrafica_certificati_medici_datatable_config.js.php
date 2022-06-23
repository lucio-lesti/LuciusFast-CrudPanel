<?php /*FILE CONFIGURAZIONE DATATABLE*/ ?>

<SCRIPT>

objAjaxConfig.mod_name = 'mod_anagrafica_certificati_medici';
objAjaxConfig.mod_title = 'Certificati Medici';
var columnArray = [];
columnArray[1] = {type:"text", name:"mod_anagrafica_nome"};	
columnArray[2] = {type:"select", name:"_mod_anagrafica_certificati_medici_tipologia",items:['AGONISTICO','NON AGONISTICO']};
columnArray[3] = {type:"date", name:"_mod_anagrafica_certificati_medici_data_certificato"};	
columnArray[4] = {type:"date", name:"_mod_anagrafica_certificati_medici_data_scadenza"};	
var columnGrid = [
{"data": "ids","orderable": false},
{"data": "mod_anagrafica_nome"},
{"data": "_mod_anagrafica_certificati_medici_tipologia"},
{"data": "_mod_anagrafica_certificati_medici_data_certificato"},
{"data": "_mod_anagrafica_certificati_medici_data_scadenza"},
{"data": "action","orderable": false,"className": "text-center"}
];
if(request_js_id != ''){
	editAjax('mod_anagrafica_certificati_medici',request_js_id);
}

</SCRIPT>