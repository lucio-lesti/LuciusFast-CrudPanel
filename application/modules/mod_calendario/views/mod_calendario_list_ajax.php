<!-- INIZIO-->
<div class="content-wrapper">
	<!-- Content Header (Page header) -->
	<section class="content-header">
		<h1>
			<i class="fa fa-calendar"></i> Calendario Corsi
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

			<!-- CALENDAR-->
			<div class="col-md-12">
				<div class="form-group">
					<div class="input-group">
					<?php
						
						if (isset($comboGridFilter)) {
							foreach ($comboGridFilter as $filterName => $v) {
								echo "<b>" . $v['label'] . "</b> 
										<SELECT id='" . $filterName . "' name='" . $filterName . "'  ";
								if ($v['multiselect'] == TRUE) {
									echo " multiple ";
								}

								$clsCSS = "";
								if ($v['clsCSS'] != NULL) {
									$clsCSS = $v['clsCSS'];
								} 

								if ($v['bootstrapSelect'] == TRUE) {
									echo " class = 'mysearch_filter_combo_ajax $clsCSS' data-live-search=\"true\" ";
								} else {
									echo " class = 'mysearch_filter $clsCSS' ";
								}

								if (isset($v['filterSlave'])) {
									echo " onchange= 'filter_slave_population_function(\"" . $module . "\", \"" . $v['filterSlave']['filter_slave_population_function'] . "\", 
												this.value,\"" . $v['filterSlave']['filter_slave_id'] . "\", true)' ";
								} else {
									if (isset($v['jsFunction'])) {
										echo " onchange= '".$v['jsFunction']."()' ";
									}
								}

								echo "	style='padding:6px 12px;font-size: 14px;'>";
								echo "<OPTION value=''>Seleziona...</OPTION>";
								foreach ($v['itemsList'] as $kOpt => $vOpt) {
									echo "<OPTION value='" . $vOpt['id'] . "'>" . $vOpt['nome'] . "</OPTION>";
								}
								echo "</SELECT> ";
							}
						}
						?>
 					</div>
 
				</div>
			</div>
			
			
			<div class="col-md-4">
				<div class="form-group">
					<div class="input-group">


						<label for="varchar">Vai a Data: </label>
						<br>
						<input type='text' class='datepicker' style='width:100px' id="goto_date" name="goto_date" autocomplete="off" />
					</div>
 
				</div>
			</div>

			<div id='calendar'></div>

		</div>
		<!--FINE-->
	</section>
</div>
<!--FINE-->

<style>
	.fc-event {
		cursor: pointer;
	}
</style>

<script>
	var calendar = null;
	var filterArray = new Array();
	var singleEvent = new Object();
	var isEventAddedCalendar = false;
	$('.datepicker').datepicker({
		locale:'it',
		format: 'dd/mm/yyyy',
		changeMonth: true,
		changeYear: true,		
		numberOfMonths:2,
		todayHighlight: true,
		autoclose: true
  	}); 

	document.addEventListener('DOMContentLoaded', function() {
		var calendarEl = document.getElementById('calendar');

		calendar = new FullCalendar.Calendar(calendarEl, {
			defaultView: 'timeGridWeek',
			plugins: ['interaction', 'dayGrid', 'timeGrid', 'list'],
			header: {
				left: 'prev,next',
				center: 'title',
				right: 'dayGridMonth,timeGridWeek,timeGridDay,listMonth'
			},
			navLinks: true, // can click day/week names to navigate views
			businessHours: false, // display business hours
			editable: false,
		 
			events: {
				url: 'mod_calendario/json',
				type: 'GET',
				success: function (data) {
        			obj = JSON.stringify(data);
					console.log(obj);
    			},
				failure: function() {
					alert("Errore Dati");
				}
			},
	 
			/*
			events: function(start, end, callback) {
				$.ajax({
					url: 'mod_calendario/json',
					dataType: 'json',
					type: 'GET',
					data: {
						// our hypothetical feed requires UNIX timestamps
						start: start.startStr,
						end: start.endStr,
						mod_corsi_id:document.getElementById('mod_corsi_id').value
					},
					success: function (doc) {
						var events = [];   //javascript event object created here
						var obj = doc;  //.net returns json wrapped in "d"
						$(obj).each(function () { //yours is obj.calevent                          
								events.push({
								title: $(this).attr('title'),  //your calevent object has identical parameters 'title', 'start', ect, so this will work
								start: $(this).attr('start'), // will be parsed into DateTime object    
								end: $(this).attr('end'),
								id: $(this).attr('id')
							});
						});                     
						callback(events);
        				obj = JSON.stringify(doc);
						console.log(obj);						
                	},					
					failure: function() {
						alert("Errore Dati");
					}					
				});
			},
			*/
			eventClick: function(info) {
				$("#modal-generic-corso").modal();
				document.getElementById('modal_body_generic_corso').innerHTML = "<b>" + info.event.title + "</b><br><b>Inizio Lezione:</b>" + convertDate(info.event.start) + "<br><b>Fine Lezione &nbsp;:</b>" + convertDate(info.event.end);
			}			

		});

		calendar.setOption('locale', 'it');
		calendar.render();
		//calendar.addEventSource("mod_calendario/json?mod_corsi_id=" + document.getElementById('mod_corsi_id').value)		

	});


	$('#goto_date').change(function() {
		var IT_date = $('#goto_date').val();
		var arrayDate = IT_date.split("/");
		var EN_date = arrayDate[2] + "-" + arrayDate[1] + "-" + arrayDate[0];
		gotoDate(calendar, EN_date);
	});


	function gotoDate(calendar, date) {
		calendar.gotoDate(date);
	}


	function getArraydata() {
		var arrayData = [];
		$.ajax({
			type: 'GET',
			url: 'modplanning/json',
			success: function(response) {
				var data = response.data;
				for (var key in data) {
					var singleEvent = {};
					singleEvent.title = data[key].subject;
					singleEvent.start = data[key].starttime;
					singleEvent.end = data[key].endtime;
					arrayData.push(singleEvent);
				}
			}
		});
		return arrayData;
	}


	function zeroPad(num, places) {
		var zero = places - num.toString().length + 1;
		return Array(+(zero > 0 && zero)).join("0") + num;
	}
 
 
	$('.select2-autocomplete').select2();

	function reloadMyCalendar(){
		/*
		calendar.removeAllEvents();
		calendar.addEventSource("mod_calendario/json?mod_corsi_id=" + document.getElementById('mod_corsi_id').value)		
		isEventAddedCalendar = true;
		*/
		calendar.refetchEvents()	
	}

</SCRIPT>


<div class="modal" tabindex="-1" role="dialog" id='modal-generic-corso'>
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header" style="background-color:#3c8dbc;">
        <h5 class="modal-title" id='title_generic_corso'><i class="fa fa-info-circle"></i>  <span id="title_generic_corso">Dettaglio Corso</span></h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body" id="modal_body_generic_corso">
      </div>

      <div class="modal-footer" id="modal_footer_generic_form" style="background-color:#DDDDDD">
		<button type="button" id="bt_cancel_generic_form" class="btn btn-secondary" data-dismiss="modal">ESCI</button>
      </div>

    </div>
  </div>
</div>