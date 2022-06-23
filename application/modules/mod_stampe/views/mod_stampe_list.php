<!-- INIZIO-->
	<div class="content-wrapper">
	  <!-- Content Header (Page header) -->
	  <section class="content-header">
		<h1>
		  <i class="fa fa-print"></i> Stampe Massive
		  
 	
			<button class="btn btn-sm btn-info" id="btDocGen" name="btDocGen" 
				onclick='showWinPrint()' disabled>
			  <i class="fa fa-folder-open"></i> Documenti Generati
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
		  
			<div id="dv_elab_in_corso" style="display:none" class="alert alert-warning alert-dismissable">
				ELABORAZIONE STAMPE IN CORSO...L'OPERAZIONE, IN BASE ALL'INTERVALLO SCELTO, PUO DURARE ANCHE ALCUNE ORE.
			</div>
			
			<div id="dv_stampa_ricevute" style="display:none">
				<b>ANNO ESERCIZIO</b>	
				<select id="anni_esercizi">
					 
					<?php 
						foreach($listaAnniEsercizi as $k => $v){
							//echo "<option value=".$v['anno_esercizio'].">".$v['anno_esercizio']."</option>";
							echo "<option value=".$v['id'].">".$v['nome']."</option>";
						}
					?>
				</select>
				&nbsp;&nbsp;&nbsp;&nbsp;
				<a class="btn btn-default" onclick='launchPrint("jobs","stampaRic", document.getElementById("anni_esercizi").value)' target="_blank">
				<i class="fa fa-print"></i> Stampa Ricevute 
				</a>

				&nbsp;&nbsp;&nbsp;&nbsp;
				<a class="btn btn-default" onclick='launchPrint("jobs","stampaTess", document.getElementById("anni_esercizi").value)' target="_blank">
				<i class="fa fa-print"></i> Stampa Tesseramenti 
				</a>

			&nbsp;&nbsp;&nbsp;&nbsp;			
			<b>Filtro Mese Stampa Ricevute</b>&nbsp;&nbsp;<select id="mese" name="mese">
				<option value="">TUTTI</option>
				<option value="GENNAIO">GENNAIO</option>
				<option value="FEBBRAIO">FEBBRAIO</option>
				<option value="MARZO">MARZO</option>
				<option value="APRILE">APRILE</option>
				<option value="MAGGIO">MAGGIO</option>
				<option value="GIUGNO">GIUGNO</option>
				<option value="LUGLIO">LUGLIO</option>
				<option value="AGOSTO">AGOSTO</option>
				<option value="SETTEMBRE">SETTEMBRE</option>
				<option value="OTTOBRE">OTTOBRE</option>
				<option value="NOVEMBRE">NOVEMBRE</option>
				<option value="DICEMBRE">DICEMBRE</option>
			</select>				
 	
			</div>
			
			
 
<div class="box-footer">
   <div class="row">
		<div class="col-md-6"></div>
									
	</div>		
</div>		
</div>		
<!--FINE-->
</section>
</div>


<div class="modal" tabindex="-1" role="dialog" id='modal-win-print'>
  <div class="modal-dialog" role="document" style="height: 90%;">
    <div class="modal-content" style="width:850px;">
      <div class="modal-header" style="background-color:#3c8dbc;">
        <h5 class="modal-title" id='title_generic_form-add-clienti'><i class="fa fa-folder-open" style="color:#FFFFFF;font-size:22px"></i>  <span id="title_win_print_msg;font-size:22px" style="color:#FFFFFF">Elenco Documenti Generati</span></h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>  
      <div class="modal-body" id="modal_body_win-print">
		
      </div>
		<div class="modal-footer">
			<button type="button" class="btn btn-secondary" id="bt_print_cancel" data-dismiss="modal">ESCI	</button>
		</div>	
	</div>
  </div>
</div>


<div class="modal" tabindex="-1" role="dialog" id='modal-conferma-stampa'>
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id='dlg_title_conferma-stampa'><i class="fa fa-info"></i> <b>Confermi Lancio Stampa?</b></h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <p id='dlg_msg_conferma-stampa'></p>
      </div>
	  <form id='frm_conferma-stampa' method='GET'>
		  <div class="modal-footer">
			<button type="button" class="btn btn-info" id="bt_conferma-stampa" onclick="launchPrintExec('jobs',moduloStampa, document.getElementById('anni_esercizi').value)"> CONFERMA</button>
			<button type="button" class="btn btn-secondary" data-dismiss="modal">ANNULLA	</button>
		  </div>
		</form>

    </div>
  </div>
</div>
<!--FINE-->		
<script type="text/javascript">
	var moduloStampa = "stampaRic";
	
	function showWinPrint(){
		showBiscuit();
		$.ajax({
			type: 'post',
			dataType: "html",
			url: 'mod_stampe/elencoDocGenerati',
			success: function (response) {
				document.getElementById('modal_body_win-print').innerHTML = response;
				hideBiscuit();
				$("#modal-win-print").modal();	
			},
			error: function (xhr, ajaxOptions, thrownError) {
				hideBiscuit();
				alert(xhr.status + " " + thrownError,'Esito Elaborazione');
			}                  
		});  				
		
	}
	
	
	function checkElab(){
		$.ajax({
			url: 'mod_stampe/checkElab',  
			success: function(response){
				var objResponse = JSON.parse(response);
				if(objResponse.CHECK_ELAB == 'NO_ELAB'){
					document.getElementById('dv_stampa_ricevute').style.display = "block";
					document.getElementById('dv_elab_in_corso').style.display = "none";	
					document.getElementById('btDocGen').disabled =  false;						
				} else {
					document.getElementById('dv_stampa_ricevute').style.display = "none";
					document.getElementById('dv_elab_in_corso').style.display = "block";
					document.getElementById('dv_elab_in_corso').innerHTML = objResponse.msg;
					document.getElementById('btDocGen').disabled =  true;	
								
				}
			},
			error: function (xhr, ajaxOptions, thrownError) {
				alert("Errore Lancio Elaborazione.");
			}             
		});   	
	}
	
 
	function launchPrint(controller, routine, anno_sportivo){
		moduloStampa = routine;
		document.getElementById('dlg_msg_conferma-stampa').innerHTML = "Stampare per " + 	$("#anni_esercizi option:selected").text() + "?";
		$("#modal-conferma-stampa").modal();	
	}


	function launchPrintExec(controller, routine, anno_sportivo){
		
		$("#modal-conferma-stampa").modal('hide');
		strUrl = 'mod_stampe/jobStampa/' + controller + '/' + routine + '/' + anno_sportivo;
		if(document.getElementById('mese').value.trim() != ""){
			strUrl += "/" +  document.getElementById('mese').value;
		}
		$.ajax({
			url:strUrl,                          
			success: function(response){
				document.getElementById('dv_stampa_ricevute').style.display = "none";
				document.getElementById('dv_elab_in_corso').style.display = "block";
				
				var d = new Date();
				var msgElab = "ELABORAZIONE STAMPE IN CORSO...L'OPERAZIONE, IN BASE ALL'INTERVALLO SCELTO, PUO DURARE ANCHE ALCUNE ORE.";				
				//msgElab += "<br>ELABORAZIONE LANCIATA IN DATA <b style='color:#000000'><u>"+ d.getDate().toString().padStart(2, "0") + "/" + d.getMonth().toString().padStart(2, "0") + "/" + d.getFullYear().toString().padStart(2, "0") + " " + d.getHours().toString().padStart(2, "0") + ":" + d.getMinutes().toString().padStart(2, "0") +"</u></b>"
				document.getElementById('dv_elab_in_corso').innerHTML = msgElab;
				document.getElementById('btDocGen').disabled =  true;	
			},
			error: function (xhr, ajaxOptions, thrownError) {
				alert("Errore Lancio Elaborazione.");
			}             
		});  	 

	}	
	
	checkElab();

	//VERIFICO OGNI DIECI SECONDI SE C'è IN CORSO UN'ELABORAZIONE
	setInterval(function(){ checkElab(); }, 30000);
</script>