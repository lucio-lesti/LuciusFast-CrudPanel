<?php /*FILE CONFIGURAZIONE DATATABLE*/ ?>

<SCRIPT>

objAjaxConfig.mod_name = 'mod_anagrafica_pagamenti_v';
objAjaxConfig.mod_title = 'Pagamenti Ricevuti';
var columnArray = [];
columnArray[1] = {type:"text", name:"mod_anagrafica_pagamenti_v_codtessera_int"};	
columnArray[2] = {type:"text", name:"mod_anagrafica_pagamenti_v_anagrafica"};	
columnArray[3] = {type:"text", name:"mod_anagrafica_pagamenti_v_esercizio"};	

var columnGrid = [
{"data": "ids","orderable": false},
{"data": "mod_anagrafica_pagamenti_v_codtessera_int"},
{"data": "mod_anagrafica_pagamenti_v_anagrafica"},
{"data": "mod_anagrafica_pagamenti_v_esercizio"},
{"data": "action","orderable": false,"className": "text-center"}
];
if(request_js_id != ''){
	editAjax('mod_anagrafica_pagamenti_v',request_js_id);
}

</SCRIPT>