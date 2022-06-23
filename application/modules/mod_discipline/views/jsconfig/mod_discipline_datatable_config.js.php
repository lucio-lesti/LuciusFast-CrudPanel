<?php /*FILE CONFIGURAZIONE DATATABLE*/ ?>

<SCRIPT>

objAjaxConfig.mod_name = 'mod_discipline';
objAjaxConfig.mod_title = 'Discipline';
var columnArray = [];
columnArray[1] = {type:"text", name:"mod_discipline_nome"};	
columnArray[2] = {type:"text", name:"mod_discipline_codice_disciplina"};	
columnArray[3] = {type:"text", name:"mod_sport_sport"};	
var columnGrid = [
{"data": "ids","orderable": false},
{"data": "mod_discipline_nome"},
{"data": "mod_discipline_codice_disciplina"},
{"data": "mod_sport_sport"},
{"data": "action","orderable": false,"className": "text-center"}
];
if(request_js_id != ''){
	editAjax('mod_discipline',request_js_id);
}

</SCRIPT>