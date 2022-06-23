<?php /*FILE CONFIGURAZIONE DATATABLE*/ ?>

<SCRIPT>

objAjaxConfig.mod_name = 'mod_pagamenti_collaboratori_v';
objAjaxConfig.mod_title = 'Pagamenti Collaboratori';
var columnArray = [];
columnArray[1] = {type:"text", name:"mod_pagamenti_collaboratori_v_collaboratore"};	
columnArray[2] = {type:"text", name:"mod_pagamenti_collaboratori_v_contratto_nome"};	
var columnGrid = [
{"data": "ids","orderable": false},
{"data": "mod_pagamenti_collaboratori_v_collaboratore"},
{"data": "mod_pagamenti_collaboratori_v_contratto_nome"},
{"data": "action","orderable": false,"className": "text-center"}
];
if(request_js_id != ''){
	editAjax('mod_pagamenti_collaboratori_v',request_js_id);
}

</SCRIPT>