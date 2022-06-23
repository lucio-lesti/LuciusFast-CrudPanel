$(document).unbind('submit');
$(document).on('submit','#' + objAjaxConfig.form.mod_name + '_' + objAjaxConfig.form.recordID,function(e){

    var form = $('#' + objAjaxConfig.form.mod_name + '_' + objAjaxConfig.form.recordID)[0];  
    var form_data = new FormData(form);   

    e.preventDefault(); 
    showBiscuit();
    $.ajax({ 
        type: 'POST', 
        url: objAjaxConfig.form.ajaxAction, 
        data: form_data,
        processData: false,
        contentType: false,
        success: function (response) {
            
            document.getElementById('main_content_ajax_form_' + objAjaxConfig.form.id_main_content).innerHTML = response;
            $('.datepicker').datepicker({locale:'it'});
            $('.datetimepicker').datetimepicker({locale:'it', format: 'DD/MM/YYYY'}); 
            $('.select2-autocomplete').select2();
            
            window.location.href = '#top_form';
            
            var timeout = 5000; // in miliseconds (3*1000)
            $('.alert').delay(timeout).fadeOut(300);            
            hideBiscuit();					
                        
        },
        error: function (xhr, ajaxOptions, thrownError) {
            alert('Errore Lancio Elaborazione.');
            hideBiscuit();
        } 				   
   });
   return false;
});	


$('.datepicker').datepicker({
    locale:'it',
    format: 'dd/mm/yyyy',
    changeMonth: true,
    changeYear: true,		
    numberOfMonths:2,
    todayHighlight: true,
    autoclose: true
  }); 
  $('.datetimepicker').datetimepicker({locale:'it', format: 'DD/MM/YYYY'});