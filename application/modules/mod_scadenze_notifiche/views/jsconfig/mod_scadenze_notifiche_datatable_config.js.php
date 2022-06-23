<?php /*FILE CONFIGURAZIONE DATATABLE*/ ?>

<SCRIPT>

objAjaxConfig.mod_name = 'mod_scadenze_notifiche';
objAjaxConfig.mod_title = 'Scadenze Notifiche';
var columnArray = [];
columnArray[1] = {type:"text", name:"mod_scadenze_notifiche_campo_data_scadenza"};	
columnArray[2] = {type:"text", name:"mod_scadenze_notifiche_icona_notifica"};	
columnArray[3] = {type:"text", name:"mod_scadenze_notifiche_mod_name"};	
columnArray[4] = {type:"text", name:"mod_scadenze_notifiche_msg_notifica"};	
columnArray[5] = {type:"number", name:"mod_scadenze_notifiche_nr_giorni_data_notifica"};	
columnArray[6] = {type:"text", name:"mod_scadenze_notifiche_sql_command"};	
columnArray[7] = {type:"text", name:"mod_scadenze_notifiche_table_name"};	
var columnGrid = [
{"data": "ids","orderable": false},
{"data": "mod_scadenze_notifiche_campo_data_scadenza"},
{"data": "mod_scadenze_notifiche_icona_notifica"},
{"data": "mod_scadenze_notifiche_mod_name"},
{"data": "mod_scadenze_notifiche_msg_notifica"},
{"data": "mod_scadenze_notifiche_nr_giorni_data_notifica"},
{"data": "mod_scadenze_notifiche_sql_command"},
{"data": "mod_scadenze_notifiche_table_name"},
{"data": "action","orderable": false,"className": "text-center"}
];
if(request_js_id != ''){
	editAjax('mod_scadenze_notifiche',request_js_id);
}

</SCRIPT>