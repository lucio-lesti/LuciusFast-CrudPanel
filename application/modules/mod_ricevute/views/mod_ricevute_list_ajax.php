<!-- INIZIO-->
	<div id="top_form"></div>
	<div class="content-wrapper">
	  <!-- Content Header (Page header) -->
	  <section class="content-header">
		<h1>
		  <i class="fa fa-euro"></i> Pagamenti/Ricevute 
		  
			<a class="btn btn-primary" onclick="createAjax()">
			  <i class="fa fa-plus"></i> Nuovo
			</a>	  
			
			<button class="btn btn-sm btn-danger deleteUser" disabled="disabled" id="btDeleteMass" name="btDeleteMass" 
				onclick='deleteMassiveEntry("entry_list", "check_id", "mod_ricevute","deleteMassive")'>
			  <i class="fa fa-trash"></i> Cancellazione Massiva
			</button>			
		</h1>
		
	  </section>
	  <section class="content">
	<!-- INIZIO-->

	 
	<!--DIV BOX-->
	<div class="box">
			<div class="box-header">
			  <div class="box-tools">
			  </div>
			</div>
	<!--DIV BOX-->
			<?php
                $this->load->helper('form');
                $error = $this->session->flashdata('error');
                if($error)
                {
            ?>
            <div class="alert alert-danger alert-dismissable">
              <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
              <?php echo $this->session->flashdata('error'); ?>
            </div>
            <?php } ?>
            <?php  
                $success = $this->session->flashdata('success');
                if($success){
            ?>
            <div class="alert alert-success alert-dismissable">
              <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
              <?php echo $this->session->flashdata('success'); ?>
            </div>
            <?php } ?>			
			<div class="nav-tabs-custom">
				<ul class="nav nav-tabs" id="tab-menu">
				  <li class="active"><a href='#elenco' id='href_elenco' data-toggle="tab" aria-expanded="true"></i><i class="fa fa-list-ol"></i> Elenco  <i class='fa fa-euro'></i></a></li>
				</ul>  
				
				<div class="tab-content">
					<div class="tab-pane tab-margin active" id="elenco">					
						<table class="table table-bordered table-striped" id="mytable" style='width: 100%;'>			
							<thead>
							<tr>
								<th><input type="checkbox" id="check_master" name="check_master" onchange="selezionaDeselezionaTutti('check_master','check_id','btDeleteMass')" /></th>
		<th>Fk Anagrafica</th>
		<th>Tessera Interna</th>
		<th>Tessera Associativa</th>
		<th>Nome</th>
		<th>Cognome</th>
		<th>Fk Disciplina</th>
		<th>Disciplina</th>
		<th>Anno</th>
		<th>Mese</th>
		<th>Data</th>
		<th>Importo</th>
		<th></th>
            </tr></thead>
	    
        </table>
	    		
					   <div class="row">
							<div class="col-md-6">
								<?php echo anchor(site_url('mod_ricevute/excel'), 'Esporta Tutto in  Excel', 'class="btn btn-primary"'); ?>
	 							<br>(*)<b>Note di esportazione:</b> L'esportazione E' TOTALE e riguarda anche i records non selezionati</u>
								<br>(**)<b>Note di importazione:</b> Prima di re-importare il file Excel <u>ELIMINARE LA PRIMA RIGA</u>
							</div>
														
						</div>		
					</div>
				</div>	
			</div>		
</div>		
<!--FINE-->
</section>
</div>
<!--FINE-->		
        <script type="text/javascript">
		
$( "#tab-menu" ).sortable();
		
var columnArray = [];
columnArray[1] = {type:"number", name:"fk_anagrafica"};	
columnArray[2] = {type:"text", name:"tessera"};	
columnArray[3] = {type:"text", name:"tess_asc"};	
columnArray[4] = {type:"text", name:"nome"};	
columnArray[5] = {type:"text", name:"cognome"};	
columnArray[6] = {type:"number", name:"fk_disciplina"};	
columnArray[7] = {type:"text", name:"disciplina"};	
columnArray[8] = {type:"select", name:"anno",items:['2010','2011','2012','2013','2014','2015','2016','2017','2018','2019','2020','2021','2022','2023','2024','2025','2026','2027','2028','2029','2030']};
columnArray[9] = {type:"select", name:"mese",items:['GENNAIO','FEBBRAIO','MARZO','APRILE','MAGGIO','GIUGNO','LUGLIO','AGOSTO','SETTEMBRE','OTTOBRE','NOVEMBRE','DICEMBRE']};
columnArray[10] = {type:"datetime", name:"data"};	
columnArray[11] = {type:"text", name:"importo"};			
			
var filterArray = new Array(); 
            $(document).ready(function() {
				
var columnNrAction = 0;
				
				
                $.fn.dataTableExt.oApi.fnPagingInfo = function(oSettings)
                {
                    return {
                        "iStart": oSettings._iDisplayStart,
                        "iEnd": oSettings.fnDisplayEnd(),
                        "iLength": oSettings._iDisplayLength,
                        "iTotal": oSettings.fnRecordsTotal(),
                        "iFilteredTotal": oSettings.fnRecordsDisplay(),
                        "iPage": Math.ceil(oSettings._iDisplayStart / oSettings._iDisplayLength),
                        "iTotalPages": Math.ceil(oSettings.fnRecordsDisplay() / oSettings._iDisplayLength)
                    };
                };

				$('#mytable thead tr').clone(true).appendTo( '#mytable thead' );
			    $('#mytable thead tr:eq(1) th').each( function (i) {
					columnNrAction++;
				});					
				
				
			    $('#mytable thead tr:eq(1) th').each( function (i) {
					if(i > 0){
						if(i < (columnNrAction - 1)){
							var title = $(this).text();
							switch(columnArray[i].type){
								case 'select':
								    var strSelect = '';
									strSelect = '<SELECT class=\'mysearch_filter\' id="' + columnArray[i].name + '" name="' + columnArray[i].name + '" type_field ="' + columnArray[i].type + '" >';
									strSelect += '<OPTION VALUE="">Cerca...</OPTION>';
									for(var key in columnArray[i].items){
										strSelect += '<OPTION VALUE="' + columnArray[i].items[key] + '">' + columnArray[i].items[key] + '</OPTION>';
									}
									strSelect += '</SELECT>';
									$(this).html( strSelect );										
								break;

								
								case 'text':
			 
									$(this).html( '<input type="text" placeholder="Cerca..." class=\'mysearch_filter\' id="' + columnArray[i].name + '" name="' + columnArray[i].name + '" / style="width:80%"  style="border-radius:3px" autocomplete="off" type_field ="' + columnArray[i].type + '" >' );										
								break;
 
								case 'date':
									$(this).html( '<input type="text" placeholder="Cerca..." class=\'mysearch_filter datetimepicker\' id="' + columnArray[i].name + '" name="' + columnArray[i].name + '"  style="width:80%" autocomplete="off"  type_field ="' + columnArray[i].type + '" />' );										
								break;
 
								case 'datetime':
									$(this).html( '<input type="text" placeholder="Cerca..." class=\'mysearch_filter datetimepicker\' id="' + columnArray[i].name + '" name="' + columnArray[i].name + '"style="width:80%" autocomplete="off"  type_field ="' + columnArray[i].type + '" />' );			
								break;	

								case 'number':
									$(this).html( '<input type="number" placeholder="Cerca..." class=\'mysearch_filter\' id="' + columnArray[i].name + '" name="' + columnArray[i].name + '" style="width:80%" autocomplete="off"  type_field ="' + columnArray[i].type + '" />' );		
								break;		

								case 'blob':
									$(this).html( '' );		
								break;									
							}
					
						} else {
							$(this).html( '' );		
						}

					}

				});	

				
                var t = $("#mytable").dataTable({
                    initComplete: function() {
                        var api = this.api();
                        $('#mytable_filter input')
                            .off('.DT')
                            .on('keyup.DT', function(e) {
                                if (e.keyCode == 13) {
                                    api.search(this.value).draw();
                            }
                        });
						
						$('select')
							.off('.DT')
							.on('change', function(e) {
								var searchFilter = document.getElementsByClassName("mysearch_filter");
								for(var i=0; i< searchFilter.length; i++){
									var element = document.getElementById(searchFilter[i].id);
									var typeField = 'text';
									if(element.classList.contains('datepicker') == true) {
										typeField = 'date'	
									}
									if(element.classList.contains('datetimepicker') == true) {
										typeField = 'datetime'	
									}									
									filterArray[i] = {'field':searchFilter[i].name,'value':searchFilter[i].value,'type_field':typeField};
								}
                                api.search(this.value).draw();
							});							
						
                        $('.mysearch_filter')
                            .off('.DT')
                            .on('keydown.DT', function(e) {
								var searchFilter = document.getElementsByClassName("mysearch_filter");
								for(var i=0; i< searchFilter.length; i++){
									var element = document.getElementById(searchFilter[i].id);
									var typeField = 'text';
									if(element.classList.contains('datepicker') == true) {
										typeField = 'date'	
									}
									if(element.classList.contains('datetimepicker') == true) {
										typeField = 'datetime'	
									}									
									filterArray[i] = {'field':searchFilter[i].name,'value':searchFilter[i].value,'type_field':typeField};
								}
                                if (e.keyCode == 13) {
                                    api.search(this.value).draw();
								}
							}
						);				
						$('.datepicker').datepicker({locale:'it', format: 'DD/MM/YYYY'});
						$('.datetimepicker').datetimepicker({locale:'it', format: 'DD/MM/YYYY'}); 
						$('.datetimepicker').on('dp.change', function() {
							e = $.Event('keydown');
							e.keyCode= 13;
							$('#' + this.id).trigger(e);
						});         
					},
                    oLanguage: {
						sSearch: "Ricerca su tutte le colonne:",	
						emptyTable: "Nessun record",
                        sProcessing: "Caricamento... <IMG SRC='<?php echo base_url(); ?>assets/images/loading3.gif' />",
						paginate: {
							previous: " << ",
							next: " >> "
						},						
                    },
					"lengthChange": false,
                    processing: true,
                    serverSide: true,
                    ajax: {"url": "mod_ricevute/json", 
							"type": "POST",
						"data": function(data){	
							data.searchFilter = filterArray;
						}					
					},
                    columns: [
                        {
                            "data": "ids",
                            "orderable": false /* RIMUOVERE IL SIMBOLO EURO COME PARAMETRO SE SI VUOLE SOL NUMERICO*/
                        },{"data": "fk_anagrafica"},
						{"data": "tessera"},
						{"data": "tess_asc"},
						{"data": "nome"},
						{"data": "cognome"},
						{"data": "fk_disciplina"},
						{"data": "disciplina"},
						{"data": "anno"},{"data": "mese"},{"data": "data"},{"data": "importo"},
                        {
                            "data" : "action",
                            "orderable": false,
                            "className" : "text-center"
                        }
                    ],
                    order: [[1, 'desc']],
                    rowCallback: function(row, data, iDisplayIndex) {
                        var info = this.fnPagingInfo();
                        var page = info.iPage;
                        var length = info.iLength;
                        var index = page * length + (iDisplayIndex + 1);
                       //$('td:eq(0)', row).html(index);
                    },
					orderCellsTop: true,
					fixedHeader: false						
                });
            });		
var tabID = 1;
		var button='<button class=\'close\' type=\'button\' title=\'Remove this page\'>×</button>';
		function resetTab(){
			var tabs=$('#tab-list li:not(:first)');
			var len=1
			$(tabs).each(function(k,v){
				len++;
				$(this).find('a').html('Tab ' + len + button);
			})
			tabID--;
		}			
		$('#tab-menu').on('click', '.close', function() {
			var tabID = $(this).parents('a').attr('href');
			showBiscuit();
			$(this).parents('li').remove();
			$(tabID).remove();

			//DISABILITO TEMPORANEAMENTE  LA POSSIBILITA DI PIU FORM APERTI	
			$('#mytable').DataTable().ajax.reload( function ( json ) {
				hideBiscuit();
			});	
			$('.btn.btn-sm.btn-info').removeClass('disabled');
			$('.btn.btn-sm.btn-danger.deleteUser').removeClass('disabled');
			$('.btn.btn-primary').removeClass('disabled');			
			document.getElementById('href_elenco').setAttribute('data-toggle', 'tab');
			
			//display first tab
			var tabFirst = $('#tab-menu a:first');
			resetTab();
			tabFirst.tab('show');
		});
				
		
		var editHandler = function() {
		  var t = $(this);
		  t.css('visibility', 'hidden');
		  $(this).prev().attr('contenteditable', 'true').focusout(function() {
			$(this).removeAttr('contenteditable').off('focusout');
			t.css('visibility', 'visible');
		  });
		};

		$('.edit').click(editHandler);	
			
			
			
		function editAjax(recordID){
			$('.btn.btn-sm.btn-info').addClass('disabled');
			$('.btn.btn-primary').addClass('disabled');
			$('.btn.btn-sm.btn-danger.deleteUser').addClass('disabled');		
			var elementExists = document.getElementById('tab_li_ed' + recordID);
			if(!elementExists){		
				$('#tab-menu').append($("<li class='edit_tab'  id='tab_li_ed" + recordID + "'><a href='#tab" + recordID + "' role='tab' data-toggle='tab'> <i class='fa fa-edit'></i> <span id='tab_lbl_ed" + recordID + "'>Caricamento...</span> <i class='fa fa-euro'></i> <button class='close' type='button' title='Remove this page'> × </button></a></li>"));
			}
			
			$.ajax({
				type: 'GET',
				url: 'mod_ricevute/updateAjax/' + recordID,
				data: {
				  recordID:recordID
				},
				success: function (response) {
					if(!elementExists){	
						$('.tab-content').append($("<div class='tab-pane fade' id='tab" + recordID + "'>" + response + "</div>"));
					}
					
					document.getElementById('tab_lbl_ed' + recordID).innerHTML = 'Modifica [#ID:' + recordID + '] ';
					document.getElementById('tab_li_ed' + recordID).style.cursor = 'pointer';
					document.getElementById('tab_li_ed' + recordID).style.pointerEvents = 'auto';
					document.getElementById('tab_li_ed' + recordID).classList.remove('disabled');
					
					//DISABILITO TEMPORANEAMENTE  LA POSSIBILITA DI PIU FORM APERTI
					document.getElementById('href_elenco').removeAttribute('data-toggle');
					/*
					$('.btn.btn-sm.btn-info').removeClass('disabled');
					$('.btn.btn-sm.btn-danger.deleteUser').removeClass('disabled');
					$('.btn.btn-primary').removeClass('disabled');
					*/
					
					$('.nav-tabs a[href="#tab' + recordID + '"]').tab('show');
					anchor = document.createElement('a');
					anchor.setAttribute('href', '#top_form');
					anchor.setAttribute('onclick','scrollaTop()');
					anchor.setAttribute('id', 'href_top_form');
					anchor.click();
					anchor.remove();
				}
			});
			
		}	
 	
	
		function createAjax(){
			$('.btn.btn-sm.btn-info').addClass('disabled');
			$('.btn.btn-primary').addClass('disabled');
			$('.btn.btn-sm.btn-danger.deleteUser').addClass('disabled');
			$('#tab-menu').append($("<li class='new_tab disabled' style='pointer-events: none;cursor:none' id='tab_li_cr" + tabID + "'><a href='#tab" + tabID + "' role='tab' data-toggle='tab'> <i class='fa fa-plus-square'></i> <span id='tab_lbl_cr" + tabID + "'>Caricamento...</span> <i class='fa fa-euro'></i> <button class='close' type='button' title='Remove this page'> × </button></a></li>"));
			
			$.ajax({
				type: 'GET',
				url: 'mod_ricevute/createAjax/',
				success: function (response) {
					$('.tab-content').append($("<div class='tab-pane fade' id='tab" + tabID + "'>" + response + " </div>"));
					document.getElementById('tab_lbl_cr' + tabID).innerHTML = 'Nuovo';
					document.getElementById('tab_li_cr' + tabID).style.cursor = 'pointer';
					document.getElementById('tab_li_cr' + tabID).style.pointerEvents = 'auto';
					document.getElementById('tab_li_cr' + tabID).classList.remove('disabled');
					//$('.nav-tabs li:eq(' + tabID + ') a').tab('show');
					$('.nav-tabs a[href="#tab' + tabID + '"]').tab('show');
			
					tabID++;

					//DISABILITO TEMPORANEAMENTE  LA POSSIBILITA DI PIU FORM APERTI
					document.getElementById('href_elenco').removeAttribute('data-toggle');
					/*
					$('.btn.btn-sm.btn-info').removeClass('disabled');
					$('.btn.btn-sm.btn-danger.deleteUser').removeClass('disabled');
					$('.btn.btn-primary').removeClass('disabled');
					*/
					
					anchor = document.createElement('a');
					anchor.setAttribute('href', '#top_form');
					anchor.setAttribute('onclick','scrollaTop()');
					anchor.setAttribute('id', 'href_top_form');
					anchor.click();
					anchor.remove();				
				}
			});				
		}

	
		function readAjax(recordID){
			$('.btn.btn-sm.btn-info').addClass('disabled');
			$('.btn.btn-primary').addClass('disabled');
			$('.btn.btn-sm.btn-danger.deleteUser').addClass('disabled');	
			var elementExists = document.getElementById('tab_li_rd' + recordID);
			if(!elementExists){			
				$('#tab-menu').append($("<li class='read_tab'  id='tab_li_rd" + recordID + "'><a href='#tab" + recordID + "' role='tab' data-toggle='tab'> <i class='fa fa-book'></i> <span id='tab_lbl_rd" + recordID + "'>Caricamento...</span> <i class='fa fa-euro'></i> <button class='close' type='button' title='Remove this page'> × </button></a></li>"));
			}
			
			$.ajax({
				type: 'GET',
				url: 'mod_ricevute/readAjax/' + recordID,
				data: {
				  recordID:recordID
				},
				success: function (response) {
					if(!elementExists){
						$('.tab-content').append($("<div class='tab-pane fade' id='tab" + recordID + "'>" + response + "</div>"));
					}
					
					document.getElementById('tab_lbl_rd' + recordID).innerHTML = 'Modifica [#ID:' + recordID + '] ';
					document.getElementById('tab_li_rd' + recordID).style.cursor = 'pointer';
					document.getElementById('tab_li_rd' + recordID).style.pointerEvents = 'auto';
					document.getElementById('tab_li_rd' + recordID).classList.remove('disabled');
 
					
					$('.btn.btn-sm.btn-info').removeClass('disabled');
					$('.btn.btn-sm.btn-danger.deleteUser').removeClass('disabled');
					$('.btn.btn-primary').removeClass('disabled');
					$('.nav-tabs a[href="#tab' + recordID + '"]').tab('show');
					
					anchor = document.createElement('a');
					anchor.setAttribute('href', '#top_form');
					anchor.setAttribute('onclick','scrollaTop()');
					anchor.setAttribute('id', 'href_top_form');
					anchor.click();
					anchor.remove();
				}
			});		
		}	
		
		$('#mytable').css('font-size', '14px');
				
		function scrollaTop(){
			$('html, body').animate({ scrollTop: 0 }, 'slow');
		}
</script>