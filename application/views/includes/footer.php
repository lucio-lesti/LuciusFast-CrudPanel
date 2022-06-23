<?php 
//CHECK SE INDIRIZZO IP AUTORIZZATO
if(!isset($ip_access_denied)){ 
?>
<footer class="main-footer">
    <div class="pull-right hidden-xs">
        <b>(L.L. AND M.M.)</b> LM-Panel| V1.0
    </div>
    <strong>Copyright &copy; 2018-2019
    </strong> 
</footer>

<!-- jQuery UI 1.11.2 -->
<!-- <script src="http://code.jquery.com/ui/1.11.2/jquery-ui.min.js" type="text/javascript"></script> -->
<!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->
<!-- Bootstrap 3.3.2 JS -->
<script src="<?php echo base_url(); ?>assets/vendor/bootstrap/js/bootstrap.min.js" type="text/javascript"></script>
<script src="<?php echo base_url(); ?>assets/vendor/dist/js/app.min.js" type="text/javascript"></script>
<!--
<script src="<?php echo base_url(); ?>assets/js/jquery.validate.js" type="text/javascript"></script>

<script src="<?php echo base_url(); ?>assets/js/validation.js" type="text/javascript"></script>
-->
<script src="<?php echo base_url(); ?>assets/js/change_theme.js" type="text/javascript"></script>

<script type="text/javascript">
    var windowURL = window.location.href;
    pageURL = windowURL.substring(0, windowURL.lastIndexOf('/'));
    var x = $('a[href="' + pageURL + '"]');
    x.addClass('active');
    x.parent().addClass('active');
    var y = $('a[href="' + windowURL + '"]');
    y.addClass('active');
    y.parent().addClass('active');

    $(document).keypress(
      function(event){
        if (event.which == '13') {
          event.preventDefault();
        }
    });  
    
 
  var timeout = 5000; // in miliseconds (3*1000)
  $('.alert').delay(timeout).fadeOut(300);
 
</script>
<!-- DataTables JavaScript -->
<script type="text/javascript" src="<?php echo base_url(); ?>assets/vendor/datatables_v1_10_16/datatables.min.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>assets/vendor/datatables_v1_10_16/pdfmake.min.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>assets/vendor/datatables_v1_10_16/vfs_fonts.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>assets/vendor/datatables_v1_10_16/datatables.min.two.js"></script>

<script type="text/javascript" src="<?php echo base_url(); ?>assets/vendor/datatables_v1_10_16/dataTables.buttons.min.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>assets/vendor/datatables_v1_10_16/buttons.flash.min.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>assets/vendor/datatables_v1_10_16/buttons.html5.min.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>assets/vendor/datatables_v1_10_16/buttons.print.min.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>assets/vendor/datatables_v1_10_16/jszip.min.js"></script>
 

<div class="modal" tabindex="-1" role="dialog" id='modal-error1'>
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id='title_error'><i class="fa fa-times-circle"></i>  Messaggio di Sistema</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <p id='msg_error'></p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Chiudi	</button>
      </div>
    </div>
  </div>
</div>


<div class="modal" tabindex="-1" role="dialog" id='modal-error'>
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id='title_error'><i class="fa fa-times-circle"></i>  Messaggio di Sistema</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <p id='msg_error'></p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Chiudi	</button>
      </div>
    </div>
  </div>
</div>


<div class="modal" tabindex="-1" role="dialog" id='modal-warning'>
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id='title_warning'><i class="fa fa-exclamation-triangle"></i>  Messaggio di Sistema</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <p id='msg_warning'></p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Chiudi	</button>
      </div>
    </div>
  </div>
</div>


<div class="modal" tabindex="-1" role="dialog" id='modal-info'>
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id='title_info'><i class="fa fa-info"></i>  Messaggio di Sistema</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <p id='msg_info'></p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Chiudi	</button>
      </div>
    </div>
  </div>
</div>


<div class="modal" tabindex="-1" role="dialog" id='modal-generic-confirm'>
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id='title_info'><i class="fa fa-info"></i>  Messaggio di Sistema</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <p id='msg_generic-confirm'></p>
      </div>
      <div class="modal-footer">
	    <button type="button" class="btn btn-info" id="btok-generic-confirm" data-dismiss="modal">Ok</button>
        <button type="button" class="btn btn-secondary" id="btcancel-generic-confirm"  data-dismiss="modal">Chiudi	</button>
      </div>
    </div>
  </div>
</div>

<div class="modal" tabindex="-1" role="dialog" id='modal-delete'>
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id='dlg_title_delete'><i class="fa fa-trash"></i> <b>Conferma Eliminazione</b></h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <p id='dlg_msg_delete'>Eliminare il record selezionato?</p>
      </div>
	  <form id='frm_delete_entry' method='GET'>
		<input type='hidden' id='module' name='module' />
		<input type='hidden' id='entry_id' name='entry_id' />
		<input type='hidden' id='entry_list' name='entry_list' />
		  <div class="modal-footer">
			<button type="submit" class="btn btn-danger" id="delete_entry">ELIMINA</button>
			<button type="button" class="btn btn-secondary" id="delete_entry_cancel" data-dismiss="modal">ANNULLA	</button>
		  </div>
		</form>

    </div>
  </div>
</div>


<div class="modal" tabindex="-1" role="dialog" id='modal-active-disactive'>
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id='dlg_title_active_disactive_mod'><i class="fa fa-info"></i> <b>Conferma Operazione</b></h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <p id='dlg_msg_active_disactive'></p>
      </div>
	  <form id='frm_active_disactive_mod' method='GET'>
		<input type='hidden' id='module' name='module' />
		<input type='hidden' id='mod_state' name='mod_state' />
		<input type='hidden' id='mod_id' name='mod_id' />
		  <div class="modal-footer">
			<button type="submit" class="btn btn-info" id="bt_active_disactive">CONFERMA</button>
			<button type="button" class="btn btn-secondary" data-dismiss="modal">ANNULLA	</button>
		  </div>
		</form>

    </div>
  </div>
</div>



<div class="modal" tabindex="-1"  id='win_add_edit_read'   role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header" style="background-color: #EEEEEE">
        <h5 class="modal-title" id='win_title_add_edit_read'><i class="fa fa-edit"></i> <b>Titolo Form</b></h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <p id='win_msg_add_edit_read'></p>
      </div>
		<div class="modal-footer">
			<button type="submit" class="btn btn-info" id="bt_add_edit_confirm">CONFERMA</button>
			<button type="button" class="btn btn-secondary" data-dismiss="modal">ANNULLA	</button>
		</div>
    </div>
  </div>
</div>



<div class="modal" tabindex="-1"  id='win_add_edit_master_details'   role="dialog" >
  <div class="modal-dialog" role="document" style="width:800px">
    <div class="modal-content">
      <div class="modal-header" style="background-color: #EEEEEE">
        <h5 class="modal-title"  ><i class="fa fa-edit"></i> <b id="win_title_add_edit_master_details">Titolo Form</b></h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <p id='win_msg_add_edit_master_details'></p>
      </div>
		<div class="modal-footer">
			<div id="dv_add_edit_msdt_confirm" style="float: right;">><button type="submit" class="btn btn-info" id="bt_add_edit_msdt_confirm">CONFERMA</button></div>
			<div id="dv_add_del_msdt_confirm" style="float: left;"><button type="button" class="btn btn-secondary" data-dismiss="modal">ANNULLA	</button></div>
		</div>
    </div>
  </div>
</div>


<div class="modal" tabindex="-1" role="dialog" id='modal-generic-form'>
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header" style="background-color:#3c8dbc;">
        <h5 class="modal-title" id='title_generic_form'><i class="fa fa-exclamation-triangle"></i>  <span id="title_generic_form_msg">Messaggio Generic Form</span></h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body" id="modal_body_generic_form">
      </div>

      <div class="modal-footer" id="modal_footer_generic_form" style="background-color:#DDDDDD">
        <button type="button" id="bt_ok_generic_form" class="btn btn-primary">OK</button>
		<button type="button" id="bt_cancel_generic_form" class="btn btn-secondary" data-dismiss="modal">ANNULLA</button>
      </div>

    </div>
  </div>
</div>


<div class="modal" tabindex="-1" role="dialog" id='modal-send-mail'>
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header" style="background-color:#3c8dbc;">
        <h5 class="modal-title" id='title_send-mail'><i class="fa fa-at"></i> <span id="title_generic_form_msg">Email Inviata con successo</span></h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body" id="modal_send-mail">
      </div>

      <div class="modal-footer" id="modal_footer_send-mail" style="background-color:#DDDDDD">
		<button type="button" id="bt_cancel_send-mail" class="btn btn-secondary" data-dismiss="modal">ESCI</button>
      </div>

    </div>
  </div>
</div>


<div class="modal" tabindex="-1" role="dialog" id='modal-ajax-delete'>
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id='dlg_title_ajax_delete'><i class="fa fa-trash"></i> <b>Conferma Eliminazione</b></h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <p id='dlg_msg_ajax_delete'>Eliminare il record selezionato?</p>
      </div>
		<div class="modal-footer">
			<button type="submit" class="btn btn-danger" id="bt_delete_ajax_entry">ELIMINA</button>
			<button type="button" class="btn btn-secondary" id="bt_delete_ajax_entry_cancel" data-dismiss="modal">ANNULLA	</button>
		</div>
    </div>
  </div>
</div>



<div class="modal" tabindex="-1" role="dialog" id='modal-student-payments'>
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id='dlg_title_delete'><i class="fa fa-euro"></i> <b>Lista Pagamenti</b></h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <p id='dlg_content_student_payments'></p>
      </div>
	  <div class="modal-footer">
		<button type="button" class="btn btn-secondary" id="winpayment_cancel" data-dismiss="modal">ESCI	</button>
	</div>	
	</div>
  </div>
</div>



<div class="modal" tabindex="-1" role="dialog" id='modal-delete-allegato'>
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id='dlg_title_ajax_delete'><i class="fa fa-trash"></i> <b>Conferma Eliminazione</b></h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <p id='dlg_msg_ajax_delete_allegato'>Eliminare Allegato?</p>
      </div>
		<div class="modal-footer">
			<button type="submit" class="btn btn-danger" id="bt_delete_allegato" onclick="rimuoviAllegatoExec()">ELIMINA</button>
			<button type="button" class="btn btn-secondary" id="bt_delete_allegato_cancel" data-dismiss="modal">ANNULLA	</button>
		</div>
    </div>
  </div>
</div>


<div class="modal" tabindex="-1" role="dialog" id='modal-delete-allegatoBlob'>
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id='dlg_title_ajax_delete'><i class="fa fa-trash"></i> <b>Conferma Eliminazione</b></h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <p id='dlg_msg_ajax_delete_allegato'>Eliminare Allegato?</p>
      </div>
		<div class="modal-footer">
			<div id="dv_delete_allegato_blob" style="float: right;"><div id="dv_bt_cancella_allegato"><button type="button" class="btn btn-danger" id="bt_delete_allegato_blob" >ELIMINA</button></div></div>
			<div style="float: left;"><button type="button" class="btn btn-secondary" id="bt_delete_allegato_blob_cancel" data-dismiss="modal">ANNULLA	</button></div>
		</div>
    </div>
  </div>
</div>
 



<div class="modal" tabindex="-1" role="dialog" id='modal-elementi-non-presenti'>
  <div class="modal-dialog " role="document">
    <div class="modal-content">
      <div class="modal-header" style="background-color:#3c8dbc;">
        <h5 class="modal-title" id='title_elementi-non-presenti'><i class="fa fa-exclamation-triangle"></i>  <span id="title_generic_form_msg">Messaggio Generic Form</span></h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body" id="modal_body_elementi-non-presenti" style="height: 300px;overflow-y: auto;">
      </div>

      <div class="modal-footer" id="modal_footer_elementi-non-presenti" style="background-color:#DDDDDD">
	    	<button type="button" id="bt_cancel_elementi-non-presenti" class="btn btn-secondary" data-dismiss="modal">ANNULLA</button>
      </div>

    </div>
  </div>
</div>



<div id="loader" style=" opacity:0.8;background: #222222; width:100%;height:100%; 
                        z-index:1000000;top:0;left:0;position:fixed;display:none;">
    <div  id="container"  style="position: absolute;top:40%;left: 40%;background-color: #FFFFFF;border:#4e95f4 2px solid;border-radius: 5px;padding: 20px" >
        <img src="<?php echo base_url()?>assets/images/attesa.gif"  alt="attendere" />
    </div>
</div>


</body>

</html>
<?php } ?>