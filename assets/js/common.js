//
//
//VARIABILI COMUNI
//
//
var moduleNameAllegato = null;
var entryIDAllegato = null;
var fileNameAllegato = null;
var nr_allegato = null;
var js_custom_operations_list = [];
var skinColorSet = "skin-blue";
$.fn.datepicker.defaults.language = 'it';


//
//
//COMBOBOX AUTOCOMPLETANTE jQueryUI
//
jQuery(document).ready(function () {
  $(function () {
    $.widget("custom.combobox", {
      _create: function () {
        this.wrapper = $("<span>")
          .addClass("custom-combobox")
          .insertAfter(this.element);

        this.element.hide();
        this._createAutocomplete();
        this._createShowAllButton();
      },

      _createAutocomplete: function () {
        var selected = this.element.children(":selected"),
          value = selected.val() ? selected.text() : "";

        this.input = $("<input>")
          .appendTo(this.wrapper)
          .val(value)
          .attr("title", "")
          .addClass("custom-combobox-input ui-widget ui-widget-content ui-state-default ui-corner-left")
          .autocomplete({
            delay: 0,
            minLength: 0,
            source: $.proxy(this, "_source")
          })
          .tooltip({
            classes: {
              "ui-tooltip": "ui-state-highlight"
            }
          });

        this._on(this.input, {
          autocompleteselect: function (event, ui) {
            ui.item.option.selected = true;
            this._trigger("select", event, {
              item: ui.item.option
            });
          },

          autocompletechange: "_removeIfInvalid"
        });
      },

      _createShowAllButton: function () {
        var input = this.input,
          wasOpen = false;

        $("<a>")
          .attr("tabIndex", -1)
          .attr("title", "Show All Items")
          .tooltip()
          .appendTo(this.wrapper)
          .button({
            icons: {
              primary: "ui-icon-triangle-1-s"
            },
            text: false
          })
          .removeClass("ui-corner-all")
          .addClass("custom-combobox-toggle ui-corner-right")
          .on("mousedown", function () {
            wasOpen = input.autocomplete("widget").is(":visible");
          })
          .on("click", function () {
            input.trigger("focus");

            // Close if already visible
            if (wasOpen) {
              return;
            }

            // Pass empty string as value to search for, displaying all results
            input.autocomplete("search", "");
          });
      },

      _source: function (request, response) {
        var matcher = new RegExp($.ui.autocomplete.escapeRegex(request.term), "i");
        response(this.element.children("option").map(function () {
          var text = $(this).text();
          if (this.value && (!request.term || matcher.test(text)))
            return {
              label: text,
              value: text,
              option: this
            };
        }));
      },

      _removeIfInvalid: function (event, ui) {

        // Selected an item, nothing to do
        if (ui.item) {
          return;
        }

        // Search for a match (case-insensitive)
        var value = this.input.val(),
          valueLowerCase = value.toLowerCase(),
          valid = false;
        this.element.children("option").each(function () {
          if ($(this).text().toLowerCase() === valueLowerCase) {
            this.selected = valid = true;
            return false;
          }
        });

        // Found a match, nothing to do
        if (valid) {
          return;
        }

        // Remove invalid value
        this.input
          .val("")
          .attr("title", value + " didn't match any item")
          .tooltip("open");
        this.element.val("");
        this._delay(function () {
          this.input.tooltip("close").attr("title", "");
        }, 2500);
        this.input.autocomplete("instance").term = "";
      },

      _destroy: function () {
        this.wrapper.remove();
        this.element.show();
      }
    });

    $(".combobox").combobox();
    $("toggle").on("click", function () {
      $(".combobox").toggle();
    });
  });

});



//
//
//FUNZIONI DI SALVATAGGIO SUL FORM
//
//
function redirectSuccessfullAjaxCall(urlRedirect, id_main_content) {
  $.ajax({
    type: 'GET',
    url: urlRedirect,
    success: function (response) {
      document.getElementById(id_main_content).innerHTML = response;
      $('#mytable').DataTable().ajax.reload();
      $('.datepicker').datepicker({
        locale: 'it'
      });
      $('.datetimepicker').datetimepicker({
        locale: 'it',
        format: 'DD/MM/YYYY'
      });

      hideBiscuit();

    }
  });
}


function winFormMasterDetails(module, winFormMasterDetailsFunc, action, entryID, entryIDMasterDetails = 'NULL', titleWinForm, arrayValidationFields, winformName = "", winFormType = 'form', gridMasterDetails = "") {
  showBiscuit();
  $("#win_add_edit_master_details").modal();
  $("#dv_add_edit_msdt_confirm").html('<button type="submit" class="btn btn-info" id="bt_add_edit_msdt_confirm">CONFERMA</button>');
  $.ajax({
    url: baseURL + module + '/loadWinMasterDetails/' + action + '/' + winFormMasterDetailsFunc + '/' + entryID + '/' + entryIDMasterDetails,
    type: 'GET',
    dataType: 'html',
    success: function (data) {
      document.getElementById('win_title_add_edit_master_details').innerHTML = titleWinForm;
      document.getElementById('win_msg_add_edit_master_details').innerHTML = data;

      $("#bt_add_edit_msdt_confirm").on("click", function () {
        validation = checkWinFormValue(winformName, arrayValidationFields, winFormType);
        if (validation == true) {
          $('#win_add_edit_master_details').modal('hide');
          $("#dv_add_edit_msdt_confirm").html("");
          winFormMasterDetailsCall(module, action, gridMasterDetails);
        }

      });
      hideBiscuit();
    },
    error: function (request, error) {
      hideBiscuit();
      alert("Request: " + JSON.stringify(request));
    }
  });


}


function winFormMasterDetailsCall(module, action, gridMasterDetails) {
  showBiscuit();
  var form = $('#frm_master_detail')[0];
  var form_data = new FormData(form);
  $.ajax({
    type: 'POST',
    url: baseURL + module + '/saveMasterDetails/',
    data: form_data,
    processData: false,
    contentType: false,
    dataType: 'json',
    success: function (data) {
      document.getElementById('divAjaxMsg').style.display = "none";
      document.getElementById('divAjaxMsgErr').style.display = "none";
      if (data.success == 'TRUE') {
        document.getElementById('divAjaxMsg').style.display = "block";
        document.getElementById('divAjaxMsg').innerHTML = data.msg;
      } else {
        document.getElementById('divAjaxMsgErr').style.display = "block";
        document.getElementById('divAjaxMsgErr').innerHTML = data.msg;
      }

      document.getElementById("master_details_list").innerHTML = "";
      document.getElementById("master_details_list").innerHTML = data.html;
      if (typeof gridMasterDetails !== "undefined") {
        $('#lnk-' + gridMasterDetails).tab('show');
      }

      window.location.href = '#divAjaxMsg_container';
      hideBiscuit();

    },
    error: function (request, error) {
      hideBiscuit();
      alert("Request: " + JSON.stringify(request));
    }
  });
}



function winFormCombo(module, form_name, combo_name, combo_id_value, combo_name_value, titleWinForm) {
  showBiscuit();
  $("#win_add_edit_master_details").modal();
  $("#dv_add_edit_msdt_confirm").html('<button type="submit" class="btn btn-info" id="bt_add_edit_msdt_confirm">CONFERMA</button>');
  $.ajax({
    url: baseURL + module + '/createAjax/TRUE',
    type: 'GET',
    dataType: 'html',
    success: function (data) {
      document.getElementById('win_title_add_edit_master_details').innerHTML = titleWinForm;
      document.getElementById('win_msg_add_edit_master_details').innerHTML = data;

      $("#bt_add_edit_msdt_confirm").on("click", function () {
        var form = $('#' + form_name)[0];
        var form_data = new FormData(form);
        showBiscuit();
        $.ajax({
          type: 'POST',
          url: baseURL + module + '/create_action/TRUE',
          data: form_data,
          processData: false,
          contentType: false,
          success: function (response) {
            $(".main_content_ajax_form").html(response);
            $('.datepicker').datepicker({ locale: 'it' });
            $('.datetimepicker').datetimepicker({ locale: 'it', format: 'DD/MM/YYYY' });
            $('.select2-autocomplete').select2();

            $.ajax({
              url: baseURL + "/mod_corsi/getKeyValuesFromTable/" + module + "/" + combo_id_value + "/" + combo_name_value,
              type: 'get',
              dataType: 'json',
              success: function (jsonData) {
                $('#' + combo_name).empty();
                for (var i = 0; i < jsonData.length; i++) {
                  $('#' + combo_name).append('<option data-value="' + jsonData[i][combo_id_value] + '">' + jsonData[i][combo_name_value] + '</option>');
                }
              }
            });

            hideBiscuit();
          },
          error: function (xhr, ajaxOptions, thrownError) {
            alert('Errore Lancio Elaborazione.');
            hideBiscuit();
          }
        });
      });

      hideBiscuit();
    },
    error: function (request, error) {
      hideBiscuit();
      alert("Request: " + JSON.stringify(request));
    }
  });


}




function deleteMasterDetails(entryIDMasterDetails, entryID, module, table, gridMasterDetails = "") {
  $('#bt_delete_ajax_entry').unbind('click');

  $("#bt_delete_ajax_entry").prop("disabled", false);
  $("#bt_delete_ajax_entry_cancel").prop("disabled", false);

  if ((typeof entryID === 'undefined') || (entryID == "")) {
    return false;
  }


  if ((typeof entryIDMasterDetails === 'undefined') || (entryIDMasterDetails == "")) {
    return false;
  }

  if ((typeof module === 'undefined') || (module == "")) {
    return false;
  }

  if ((typeof table === 'undefined') || (table == "")) {
    return false;
  }


  $("#bt_delete_ajax_entry").on("click", function () {
    $("#bt_delete_ajax_entry").prop("disabled", true);
    $("#bt_delete_ajax_entry_cancel").prop("disabled", true);
    $('#modal-ajax-delete').modal('hide');
    deleteMasterDetailsCall(entryIDMasterDetails, entryID, module, table, gridMasterDetails);
  });

  $("#modal-ajax-delete").modal();

}


function deleteMassiveMasterDetails(id, idEntryList, idCheckBox, module, table, gridMasterDetails = "") {
  $('#bt_delete_ajax_entry').unbind('click');

  $("#bt_delete_ajax_entry").prop("disabled", false);
  $("#bt_delete_ajax_entry_cancel").prop("disabled", false);

  var checkboxes = document.getElementsByName(idCheckBox);
  var entry_list = "";

  if ((typeof idEntryList === 'undefined') || (idEntryList == "")) {
    return false;
  }

  if ((typeof idCheckBox === 'undefined') || (idCheckBox == "")) {
    return false;
  }


  if ((typeof module === 'undefined') || (module == "")) {
    return false;
  }

  if ((typeof table === 'undefined') || (table == "")) {
    return false;
  }


  var count = 0;
  for (var i = 0, n = checkboxes.length; i < n; i++) {
    if (checkboxes[i].checked == true) {
      if (count > 0) {
        entry_list += "," + checkboxes[i].value;
      } else {
        entry_list += checkboxes[i].value;
      }
      count++;
    }
  }

  if (count == 0) {
    return false;
  }

  $("#bt_delete_ajax_entry").on("click", function () {
    $("#bt_delete_ajax_entry").prop("disabled", true);
    $("#bt_delete_ajax_entry_cancel").prop("disabled", true);
    $('#modal-ajax-delete').modal('hide');
    deleteMassiveMasterDetailsCall(id, entry_list, module, table, gridMasterDetails);
  });

  $("#modal-ajax-delete").modal();

}


function deleteMasterDetailsCall(entryIDMasterDetails, entryID, module, table,gridMasterDetails) {
  console.log(entryID);
  showBiscuit();
  $.ajax({
    url: baseURL + module + '/delete_row_master_details' + '/' + entryIDMasterDetails + '/' + entryID + '/' + table,
    type: 'GET',
    dataType: 'json',
    success: function (data) {
      document.getElementById('divAjaxMsg').style.display = "none";
      document.getElementById('divAjaxMsgErr').style.display = "none";
      if (data.success == 'TRUE') {
        document.getElementById('divAjaxMsg').style.display = "block";
        document.getElementById('divAjaxMsg').innerHTML = data.msg;
      } else {
        document.getElementById('divAjaxMsgErr').style.display = "block";
        document.getElementById('divAjaxMsgErr').innerHTML = data.msg;
      }

      document.getElementById("master_details_list").innerHTML = "";
      document.getElementById("master_details_list").innerHTML = data.html;

      if (typeof gridMasterDetails !== "undefined") {
        $('#lnk-' + gridMasterDetails).tab('show');
      }      
      window.location.href = '#divAjaxMsg_container';
      hideBiscuit();
    },
    error: function (request, error) {
      hideBiscuit();
      alert("Request: " + JSON.stringify(request));
    }
  });
}


function deleteMassiveMasterDetailsCall(id, entry_list, module, table,gridMasterDetails) {
  showBiscuit();
  $.ajax({
    url: baseURL + module + '/delete_massive_master_details/' + id + "/" + entry_list + '/' + table,
    type: 'GET',
    dataType: 'json',
    success: function (data) {
      document.getElementById('divAjaxMsg').style.display = "none";
      document.getElementById('divAjaxMsgErr').style.display = "none";
      if (data.success == 'TRUE') {
        document.getElementById('divAjaxMsg').style.display = "block";
        document.getElementById('divAjaxMsg').innerHTML = data.msg;
      } else {
        document.getElementById('divAjaxMsgErr').style.display = "block";
        document.getElementById('divAjaxMsgErr').innerHTML = data.msg;
      }

      document.getElementById("master_details_list").innerHTML = "";
      document.getElementById("master_details_list").innerHTML = data.html;

      if (typeof gridMasterDetails !== "undefined") {
        $('#lnk-' + gridMasterDetails).tab('show');
      }      
      window.location.href = '#divAjaxMsg_container';
      hideBiscuit();
    },
    error: function (request, error) {
      hideBiscuit();
      alert("Request: " + JSON.stringify(request));
    }
  });
}


function deleteMassiveEntry(idEntryList, idCheckBox, module, action) {
  var checkboxes = document.getElementsByName(idCheckBox);
  var entry_list = "";

  if ((typeof idEntryList === 'undefined') || (idEntryList == "")) {
    return false;
  }

  if ((typeof action === 'undefined') || (action == "")) {
    action = 'deleteMassive';
  }

  var count = 0;
  for (var i = 0, n = checkboxes.length; i < n; i++) {
    if (checkboxes[i].checked == true) {
      if (count > 0) {
        entry_list += "," + checkboxes[i].value;
      } else {
        entry_list += checkboxes[i].value;
      }
      count++;
    }
  }
  console.log(entry_list);

  document.getElementById('dlg_msg_delete').innerHTML = "Eliminare i records selezionati?";
  document.getElementById('entry_list').value = entry_list;
  document.getElementById('frm_delete_entry').method = "POST";
  document.getElementById('frm_delete_entry').action = baseURL + '/' + module + '/' + action;

  $("#modal-delete").modal();
  $("#frm_delete_entry").on("submit", function () {
    $("#delete_entry").prop("disabled", true);
    $("#delete_entry_cancel").prop("disabled", true);
  });
}


function deleteEntry(entryID, module, action) {
  if ((typeof entryID === 'undefined') || (entryID == "")) {
    return false;
  }

  if ((typeof action === 'undefined') || (action == "")) {
    action = 'delete';
  }

  document.getElementById('dlg_msg_delete').innerHTML = "Eliminare il record selezionato?";

  document.getElementById('frm_delete_entry').action = baseURL + '/' + module + '/' + action + '/' + entryID;
  $("#modal-delete").modal();
}



function deleteAjaxEntry(entryID, module, action, objParamRefreshLayout) {
  $('#bt_delete_ajax_entry').unbind('click');

  $("#bt_delete_ajax_entry").prop("disabled", false);
  $("#bt_delete_ajax_entry_cancel").prop("disabled", false);

  if ((typeof entryID === 'undefined') || (entryID == "")) {
    return false;
  }

  if ((typeof action === 'undefined') || (action == "")) {
    action = 'delete';
  }

  $("#bt_delete_ajax_entry").on("click", function () {
    $("#bt_delete_ajax_entry").prop("disabled", true);
    $("#bt_delete_ajax_entry_cancel").prop("disabled", true);
    $('#modal-ajax-delete').modal('hide');
    deleteAjaxCall(entryID, module, action, objParamRefreshLayout);
  });

  $("#modal-ajax-delete").modal();

}


function deleteAjaxCall(entryID, module, action, objParamRefreshLayout) {
  console.log(entryID);
  showBiscuit();
  $.ajax({
    url: baseURL + '/' + module + '/' + action + '/' + entryID,
    type: 'GET',
    dataType: 'json',
    success: function (data) {
      //AGGIORNO IL LAYOUT
      //IN QUESTA VERSIONE IL RESPONSE PER GLIA AGGIORNAMENTI DEI LAYOUT E' SEMPRE HTML
      if (objParamRefreshLayout.responseType == "HTML") {
        refreshLayoutFromHTMLResponse(objParamRefreshLayout);
      }
    },
    error: function (request, error) {
      hideBiscuit();
      alert("Request: " + JSON.stringify(request));
    }
  });
}


function ajaxCallFormData(formData, module, action, objParamRefreshLayout) {
  console.log(entryID);
  showBiscuit();
  $.ajax({
    url: baseURL + '/' + module + '/' + action + '/' + entryID,
    type: 'GET',
    dataType: 'json',
    success: function (data) {
      //AGGIORNO IL LAYOUT
      //IN QUESTA VERSIONE IL RESPONSE PER GLIA AGGIORNAMENTI DEI LAYOUT E' SEMPRE HTML
      if (objParamRefreshLayout.responseType == "HTML") {
        refreshLayoutFromHTMLResponse(objParamRefreshLayout);
      }
    },
    error: function (request, error) {
      hideBiscuit();
      alert("Request: " + JSON.stringify(request));
    }
  });
}


function refreshLayoutFromHTMLResponse(objParamRefreshLayout) {
  $.ajax({
    //I PARAMETRI DI CHIAMATA SONO TUTTI CONTENUTI NELLA VAR STRINGA ajaxCallForRefresh
    url: objParamRefreshLayout.ajaxCallForRefresh,
    type: 'GET',
    dataType: "html",
    success: function (response) {
      //VISUALIZZO IL MESSAGGIO DI SUCCESSO DELLA CHIAMATA AJAX
      document.getElementById(objParamRefreshLayout.idDivMsg).style.display = "block";
      document.getElementById(objParamRefreshLayout.idDivMsg).innerHTML = objParamRefreshLayout.message;
      document.getElementById(objParamRefreshLayout.idDivLayout).innerHTML = response;
      hideBiscuit();
    },
    error: function (request, error) {
      alert("Request: " + JSON.stringify(request));
    }
  });
}


function submitFormModule(formName, buttonOkId, buttonResetId) {
  if ((typeof buttonOkId === 'undefined') || (buttonOkId == "")) {
    return false;
  }

  if ((typeof buttonResetId !== 'undefined')) {
    document.getElementById(buttonResetId).disabled = true;
  }

  document.getElementById(buttonOkId).disabled = true;
  document.getElementById(formName).submit();
}



//
//
//FUNZIONI PER IL CRUD(CREATE,READ,EDIT)
//

function editAjax(mod_name, recordID) {
  $('.btn.btn-sm.btn-info').addClass('disabled');
  $('.btn.btn-primary').addClass('disabled');
  $('.btn.btn-sm.btn-danger.deleteUser').addClass('disabled');
  var elementExists = document.getElementById('tab_li_ed' + recordID);
  if (!elementExists) {
    $('#tab-menu').append($("<li class='edit_tab'  id='tab_li_ed" + recordID + "'><a href='#tab" + recordID +
      "' role='tab' data-toggle='tab'> <i class='fa fa-edit'></i> <span id='tab_lbl_ed" + recordID +
      "'>Caricamento...</span> <i class='fa fa-cubes'></i> <button class='close' type='button' title='Remove this page'> × </button></a></li>"
    ));
  }

  $.ajax({
    type: 'GET',
    url: mod_name + '/updateAjax/' + recordID,
    data: {
      recordID: recordID
    },
    success: function (response) {
      if (!elementExists) {
        $('.tab-content').append($("<div class='tab-pane fade' id='tab" + recordID + "'>" +
          response + "</div>"));
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
      anchor.setAttribute('onclick', 'scrollaTop()');
      anchor.setAttribute('id', 'href_top_form');
      anchor.click();
      anchor.remove();
    }
  });

}


function createAjax(mod_name) {
  $('.btn.btn-sm.btn-info').addClass('disabled');
  $('.btn.btn-primary').addClass('disabled');
  $('.btn.btn-sm.btn-danger.deleteUser').addClass('disabled');
  $('#tab-menu').append($("<li class='new_tab disabled' style='pointer-events: none;cursor:none' id='tab_li_cr" +
    tabID + "'><a href='#tab" + tabID +
    "' role='tab' data-toggle='tab'> <i class='fa fa-plus-square'></i> <span id='tab_lbl_cr" + tabID +
    "'>Caricamento...</span> <i class='fa fa-cubes'></i> <button class='close' type='button' title='Remove this page'> × </button></a></li>"
  ));

  $.ajax({
    type: 'GET',
    url: mod_name + '/createAjax/',
    success: function (response) {
      $('.tab-content').append($("<div class='tab-pane fade' id='tab" + tabID + "'>" + response +
        " </div>"));
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
      anchor.setAttribute('onclick', 'scrollaTop()');
      anchor.setAttribute('id', 'href_top_form');
      anchor.click();
      anchor.remove();
    }
  });
}


function readAjax(mod_name, recordID) {
  $('.btn.btn-sm.btn-info').addClass('disabled');
  $('.btn.btn-primary').addClass('disabled');
  $('.btn.btn-sm.btn-danger.deleteUser').addClass('disabled');
  var elementExists = document.getElementById('tab_li_rd' + recordID);
  if (!elementExists) {
    $('#tab-menu').append($("<li class='read_tab'  id='tab_li_rd" + recordID + "'><a href='#tab" + recordID +
      "' role='tab' data-toggle='tab'> <i class='fa fa-book'></i> <span id='tab_lbl_rd" + recordID +
      "'>Caricamento...</span> <i class='fa fa-cubes'></i> <button class='close' type='button' title='Remove this page'> × </button></a></li>"
    ));
  }

  $.ajax({
    type: 'GET',
    url: mod_name + '/readAjax/' + recordID,
    data: {
      recordID: recordID
    },
    success: function (response) {
      if (!elementExists) {
        $('.tab-content').append($("<div class='tab-pane fade' id='tab" + recordID + "'>" +
          response + "</div>"));
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
      anchor.setAttribute('onclick', 'scrollaTop()');
      anchor.setAttribute('id', 'href_top_form');
      anchor.click();
      anchor.remove();
    }
  });
}



//
//FUNZIONI DI GESTIONE CAMPI SUL FORM
//
//
function hideMessage(id) {
  document.getElementById(id).style.display = "none";
}


function hideMsg(id) {
  if (typeof id === 'undefined') {
    id = "divAjaxMsg";
  }
  document.getElementById(id).style.display = "none";
}



function pulisciCampoData(id) {
  $('#' + id).val('').datepicker('update');
}


function rimuoviAllegato(moduleName, entryId, fileName, NrAllegato) {
  moduleNameAllegato = moduleName;
  entryIDAllegato = entryId;
  fileNameAllegato = fileName;
  nr_allegato = NrAllegato;
  $("#modal-delete-allegato").modal();
}


function rimuoviAllegatoExec() {
  $("#modal-delete-allegato").modal('hide');
  showBiscuit();
  $.ajax({
    url: baseURL + "/" + moduleNameAllegato + '/rimuoviAllegato/' + moduleNameAllegato + '/' + entryIDAllegato + '/' + fileNameAllegato,
    type: 'GET',
    dataType: 'json',
    success: function (data) {
      console.log(data);
      $("#modal-delete-allegato").modal('hide');

      if (data.success == 'KO') {
        document.getElementById('msg_error').innerHTML = "<B>Impossibile eliminare il file</B>";
        $("#modal-error").modal()
      } else {
        //document.getElementById(nr_allegato).innerHTML = "";
        $('#' + nr_allegato).remove();
      }
      hideBiscuit();

    },
    error: function (request, error) {
      hideBiscuit();
      alert("Request: " + JSON.stringify(request));
    }
  });
}


function selezionaDeselezionaTutti(idCheckMaster, idCheckBox, idBtDeleteMass) {
  var checkboxes = document.getElementsByName(idCheckBox);
  var checkMaster = document.getElementById(idCheckMaster);
  for (var i = 0, n = checkboxes.length; i < n; i++) {
    if (checkMaster.checked == true) {
      checkboxes[i].checked = true;
    } else {
      checkboxes[i].checked = false;
    }
  }

  if ((typeof idBtDeleteMass !== 'undefined')) {
    if (checkMaster.checked == false) {
      document.getElementById(idBtDeleteMass).disabled = true;
    } else {
      document.getElementById(idBtDeleteMass).disabled = false;
    }
  }


  if ((typeof document.getElementById('bt_copia_corsi') !== 'undefined')) {
    if (checkMaster.checked == false) {
      document.getElementById('bt_copia_corsi').disabled = true;
    } else {
      document.getElementById('bt_copia_corsi').disabled = false;
    }
  }


}


function verificaNrCheckBoxSelezionati(idCheckBox, idBtDeleteMass) {
  var nrCheckBoxSelezionati = 0;
  var checkboxes = document.getElementsByName(idCheckBox);
  for (var i = 0, n = checkboxes.length; i < n; i++) {
    if (checkboxes[i].checked == true) {
      nrCheckBoxSelezionati++;
    }
  }

  if (nrCheckBoxSelezionati > 0) {
    document.getElementById(idBtDeleteMass).disabled = false;
  } else {
    document.getElementById(idBtDeleteMass).disabled = true;
  }

}


function addItemListBox(sourceId, listboxId) {
  var listBox = document.getElementById(listboxId);
  var option = document.createElement("option");
  var ipAddressToAdd = document.getElementById(sourceId).value;
  if (validateIPaddress(ipAddressToAdd) == true) {
    option.text = ipAddressToAdd;
    option.value = ipAddressToAdd;
    listBox.add(option);
  } else {
    document.getElementById('msg_info').innerHTML = "<b>Indirizzo IP non valido</b>";
    $("#modal-info").modal();
  }
}


function removeItemListBox(listboxId) {
  var listBox = document.getElementById(listboxId);
  var itemId = document.getElementById(listboxId).selectedIndex;
  listBox.remove(itemId);
}


function selectAllItemListBox(listboxId) {
  var select = document.getElementById(listboxId);
  for (i = 0; i < select.options.length; i++) {
    select.options[i].selected = true;
  }
}


//
//FUNZIONI CALENDARIO
//
//
var calendar = null;

function renderizzaCalendario() {
  if (calendar !== null) {
    calendar.destroy();
    calendar = null;
  }

  var filterArray = new Array();
  var singleEvent = new Object();

  var calendarEl = document.getElementById('calendar');
  calendar = new FullCalendar.Calendar(calendarEl, {
    defaultView: 'timeGridWeek',
    height: 350,
    plugins: ['interaction', 'dayGrid', 'timeGrid', 'list'],
    header: {
      left: 'prev,next',
      center: 'title',
      right: 'dayGridMonth,timeGridWeek,timeGridDay,listMonth'
    },
    navLinks: true, // can click day/week names to navigate views
    businessHours: true, // display business hours
    editable: false
  });

  calendar.setOption('locale', 'it');
  calendar.render();
  $('#goto_date').change(function () {
    var IT_date = $('#goto_date').val();
    var arrayDate = IT_date.split("/");
    var EN_date = arrayDate[2] + "-" + arrayDate[1] + "-" + arrayDate[0];
    gotoDate(calendar, EN_date);
  });

  function gotoDate(calendar, date) {
    calendar.gotoDate(date);
  }

  function zeroPad(num, places) {
    var zero = places - num.toString().length + 1;
    return Array(+(zero > 0 && zero)).join("0") + num;
  }

  calendar.changeView("timeGridWeek");

}


function reloadCalendar(calendar) {
  calendar.removeAllEvents();
  filterArray[0] = {
    'field': 'id_automezzo',
    'value': document.getElementById('filter_bus').value
  };
  calendar.addEventSource("modplanning/json?searchFilter=" + JSON.stringify(filterArray))
  calendar.rerenderEvents();
}


//
//
//LOADER
//
//
function showBiscuit(loaderName, callback) {
  //SE NON E' SETTATO NESSUN ID, GLI SETTO UN ID DI DEFAULT
  if (typeof (loaderName) === 'undefined') {
    loaderName = 'loader';
  }
  if (typeof (loaderName) === null) {
    loaderName = 'loader';
  }

  //RICHIAMO UNA EVENTUALE FUNZIONE PASSATA COME ARGOMENTO
  if (typeof (callback) !== 'undefined') {
    callback();
  }
  document.getElementById(loaderName).style.display = "block";
}



function hideBiscuit(loaderName, callback) {
  //SE NON E' SETTATO NESSUN ID, GLI SETTO UN ID DI DEFAULT
  if (typeof (loaderName) === 'undefined') {
    loaderName = 'loader';
  }
  if (typeof (loaderName) === null) {
    loaderName = 'loader';
  }

  setTimeout(function () {
    document.getElementById(loaderName).style.display = "none";
  }, 500);

  //RICHIAMO UNA EVENTUALE FUNZIONE PASSATA COME ARGOMENTO
  if (typeof (callback) !== 'undefined') {
    callback();
  }
}


//
//INIALIZZAZIONE DATEPICKER, TAB E DATATABLE
//
//

$('.datepicker').datepicker({
  locale: 'it',
  format: 'dd/mm/yyyy',
  changeMonth: true,
  changeYear: true,
  numberOfMonths: 2,
  todayHighlight: true,
  autoclose: true
});
$('.datetimepicker').datetimepicker({ locale: 'it', format: 'DD/MM/YYYY' });

$("#tab-menu").sortable();
$('#mytable').css('font-size', '14px');

function scrollaTop() {
  $('html, body').animate({
    scrollTop: 0
  }, 'slow');
}

function resetTab() {
  var tabs = $('#tab-list li:not(:first)');
  var len = 1
  $(tabs).each(function (k, v) {
    len++;
    $(this).find('a').html('Tab ' + len + button);
  })
  tabID--;
}




//
//UTILITY
//
//
function validateIPaddress(ipaddress) {
  if (/^(25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.(25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.(25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.(25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)$/.test(ipaddress)) {
    return true;
  } else {
    return false;
  }
}


function changeSkinColor(skinColor) {
  $("body").removeClass(skinColorSet).addClass(skinColor);
  skinColorSet = skinColor;
  document.getElementById('skin_color').value = skinColor;
}


function tog(v) { return v ? 'addClass' : 'removeClass'; }
$(document).on('input', '.mysearch_filter', function () {
  $(this)[tog(this.value)]('x');
}).on('mousemove', '.x', function (e) {
  $(this)[tog(this.offsetWidth - 18 < e.clientX - this.getBoundingClientRect().left)]('onX');
}).on('touchstart click', '.onX', function (ev) {
  ev.preventDefault();
  $(this).removeClass('x onX').val('').change();
});


function disableKeySubmit() {
  ev = event || Event;

  if (ev.keyCode == 13) {
    ev.preventDefault();
    return false;
  }

}


/**
 * Ricerca in una tabella master details
 * @param {*} myInput 
 * @param {*} myTable 
 * @param {*} nrTd 
 * @returns 
 */
/*
function searchInMasterDetailsTable(myInput, myTable, nrTd) {
  ev = event || Event;
  
  if(ev.keyCode == 13){
    ev.preventDefault();
    return false;
  }
  
  var input, filter, table, tr, td, i, txtValue;
  var find = false;
  input = document.getElementById(myInput);
  filter = input.value.toUpperCase();
  table = document.getElementById(myTable);
  tr = table.getElementsByTagName("tr");
  for (i = 1; i < tr.length; i++) {
    for(iNrTd=0; iNrTd < nrTd; iNrTd++){
      td = tr[i].getElementsByTagName("td")[iNrTd];
      if (td) {
        txtValue = td.textContent || td.innerText;
        if (txtValue.toUpperCase().indexOf(filter) > -1) {
            find = true;
        }
      }  
    }  
    
    if (find == true) {
      tr[i].style.display = "";
    } else {
      tr[i].style.display = "none";
    }    

  }
}
*/



function searchInMasterDetailsTable(myInput, myTable, nrTd) {
  var input, filter, table, tr, td, i, txtValue;
  var find = false;
  input = document.getElementById(myInput);
  filter = input.value.toUpperCase();
  table = document.getElementById(myTable);
  tr = table.getElementsByTagName("tr");
  for (i = 2; i < tr.length; i++) {
    for (iNrTd = 1; iNrTd < nrTd; iNrTd++) {
      td = tr[i].getElementsByTagName("td")[iNrTd];
      if (td) {
        txtValue = td.textContent || td.innerText;
        if (txtValue.toUpperCase().indexOf(filter) > -1) {
          find = true;
        }
      }
    }

    if (find == true) {
      tr[i].style.display = "";
    } else {
      tr[i].style.display = "none";
    }

  }
}


/**
 * 
 * Valida un'email
 * @param {*} email 
 * @returns 
 */
function validateEmail(email) {
  var re = /\S+@\S+\.\S+/;
  return re.test(email);
}


function rimuoviAllegatoBlob(moduleName, fieldName, entryId, fieldType) {
  $("#modal-delete-allegatoBlob").modal();
  $("#dv_bt_cancella_allegato").html("");

  $("#dv_bt_cancella_allegato").html('<button type="button" class="btn btn-danger" id="bt_delete_allegato_blob" >ELIMINA</button>');
  $("#bt_delete_allegato_blob").on("click", function () {
    $('#modal-delete-allegatoBlob').modal('hide');
    $("#dv_bt_cancella_allegato").html("");
    rimuoviAllegatoBlobExec(moduleName, fieldName, entryId, fieldType);
  });


}


function rimuoviAllegatoBlobExec(moduleName, fieldName, entryId, fieldType) {
  showBiscuit();
  $.ajax({
    url: baseURL + "/" + moduleName + '/rimuoviAllegatoBlob/' + moduleName + '/' + fieldName + '/' + entryId + '/' + fieldType,
    type: 'GET',
    dataType: 'json',
    success: function (data) {
      if (data.success == 'KO') {
        document.getElementById('dv_allegati_blobErr').style.display = "block";
        document.getElementById('dv_allegati_blobErr').innerHTML = "<B>Impossibile eliminare il file</B>";
      } else {
        document.getElementById(fieldName + '_hidden').value = "";
        document.getElementById('dv_allegati_blob_' + fieldName).innerHTML = "";
      }
      hideBiscuit();
    },
    error: function (request, error) {
      hideBiscuit();
      alert("Request: " + JSON.stringify(request));
    }
  });
}


/**
 * 
 * Valida un Winform
 * @param {*} arrayValidationFields 
 * @returns 
 */
function checkWinFormValue(winformName, arrayValidationFields, winFormType) {

  validation = true;
  ev = event || Event;
  validationRules = arrayValidationFields[winformName];


  if (winFormType == 'form') {
    for (var keyField in validationRules) {
      if (document.getElementById(keyField).value.trim() == '') {
        document.getElementById('msg_err').style.display = "block";
        document.getElementById('msg_err').innerHTML = " \"" + validationRules[keyField]['label'] + "\" non valorizzato";
        ev.preventDefault();
        validation = false;
      } else {
        if (validationRules[keyField]['field_type'] == 'email') {
          if (!validateEmail(document.getElementById(keyField).value)) {
            document.getElementById('msg_err').style.display = "block";
            document.getElementById('msg_err').innerHTML = "\"" + validationRules[keyField]['label'] + "\" deve essere una mail";
            ev.preventDefault();
            validation = false;
          }
        }

      }
    }
  } else {
    for (var keyField in validationRules) {
      var countCheck = $("." + keyField + ":checkbox").filter(":checked");
      break;
    }
    if (parseInt(countCheck.length) == 0) {
      document.getElementById('msg_err').style.display = "block";
      document.getElementById('msg_err').innerHTML = "Selezionare almeno un elemento";
      validation = false;
    }

  }


  for (var key in js_custom_operations_list[winformName]) {
    validation = js_custom_operations_list[winformName][key]();
  }

  console.log(validation);

  return validation;

}


//
//
//DATATABLE
//
//

if (typeof DataTable !== 'undefined') {
  $(document).ready(function () {
    $('#dataTables-example').DataTable({
      dom: "<'row'<'col-sm-6'l><'col-sm-6'f>>" +
        "<'row'<'col-sm-12'tr>>" +
        "<'row'<'col-sm-9'i><'col-sm-3'B>>" +
        "<'row'<'col-sm-7 col-centered'p>>",
      lengthChange: false,
      lengthMenu: [
        [10, 15, 25, 50, -1],
        [10, 15, 25, 50, "Tutto"]
      ],

      //Dil
      language: {
        select: {
          rows: "%d riga selezionata."
        },

        url: baseURL + "application/i18n/Italian.json"
      },
      buttons: [{
        extend: "print",
        text: "Stampa",
        exportOptions: {
          orthogonal: 'export',
          columns: ':visible'
        },
      },
      {
        extend: 'excelHtml5',
        exportOptions: {
          orthogonal: 'export'
        },
        text: "Excel",
      }
      ],
      "order": [],
      responsive: true,
      "columnDefs": [{
        "targets": 'no-sort',
        "orderable": false,
      }]
    });
  });
}

$('.datemask').mask("00/00/0000", { placeholder: "__/__/____" });
$('.datetimemask').mask("00/00/0000 00:00", { placeholder: "__/__/____ __:__" });
$('.timemask').mask("00:00", { placeholder: "__:__" });



function onInput(input, dlist, hidden, divName = null) {
  var val = document.getElementById(input).value;
  var opts = document.getElementById(dlist).childNodes;
  for (var i = 0; i < opts.length; i++) {
    if (opts[i].value === val) {
      console.log(opts[i].value);
      console.log(opts[i].dataset.value);
      if (divName !== null) {
        document.querySelector("#" + divName).querySelector("#" + hidden).value = opts[i].dataset.value;
      } else {
        document.getElementById(hidden).value = opts[i].dataset.value;
      }

    }
  }
}


function pulisciCampo(id, divName = null) {
  if (divName !== null) {
    document.querySelector("#" + divName).querySelector("#" + id).value = "";
  } else {
    $('#' + id).val('');;
  }

  //SE NEL NOME È PRESENTE LA PAROLA "DATALIST" PULISCO ANCHE IL CAMPO HIDDEN COLELGATO
  if (id.indexOf("_datalist_inp") !== -1) {
    var hiddenInpId = id.substring(0, id.length - 13);
    $('#' + hiddenInpId).val('');;
  }
}

function pulisciSelect2(id) {
  $('#' + id).val(null).trigger('change');
}  


function check_date_greater_then(date_from_name, date_to_name) {
  if (document.getElementById(date_from_name).value == '') {
    return false;
  }

  if (document.getElementById(date_to_name).value == '') {
    return false;
  }


  var date_from_array = document.getElementById(date_from_name).value.split("/");
  var hourFrom = "";
  var dayFrom = date_from_array[0];
  var monthFrom = date_from_array[1];
  var yearFrom = date_from_array[2].split(" ");
  if (typeof yearFrom[1] !== "undefined") {
    hourFrom = " " + yearFrom[1];
  }
  date_fromEN = yearFrom + "-" + monthFrom + "-" + dayFrom + hourFrom;

  var date_to_array = document.getElementById(date_to_name).value.split("/");
  var hourTo = "";
  var dayTo = date_to_array[0];
  var monthTo = date_to_array[1];
  var yearTo = date_to_array[2].split(" ");
  if (typeof yearTo[1] !== "undefined") {
    hourTo = " " + yearTo[1];
  }
  date_toEN = yearTo + "-" + monthTo + "-" + dayTo + hourTo;


  var objDateFrom = new Date(date_fromEN);
  var objDateTo = new Date(date_toEN);

  if (objDateFrom.getTime() > objDateTo.getTime()) {
    return false;
  }

  return true;

}


function check_valid_date(date) {

  return true;
}


function check_valid_hour(hour) {

  return true;
}



function filter_slave_population_function(module, population_function, filter_master_value, filter_slave_id, addOptionEmpty) {
  //showBiscuit();
  console.log(module + " - " + population_function + " - " + filter_master_value + " - " + filter_slave_id + " - " + addOptionEmpty);
  document.getElementById(filter_slave_id).innerHTML = "";
  $.ajax({
    type: 'GET',
    url: baseURL + module + "/" + population_function,
    data: {
      'filter_master_value': filter_master_value
    },
    dataType: 'json',
    success: function (jsonData) {

      var select, i, option;

      select = document.getElementById(filter_slave_id);

      if ((typeof addOptionEmpty !== "undefined") && (addOptionEmpty == true)) {
        option = document.createElement('option');
        option.value = "";
        option.text = "Seleziona...";
        select.add(option);
      }

      for (var i = 0; i < jsonData.length; i++) {
        option = document.createElement('option');
        option.value = jsonData[i].id;
        option.text = jsonData[i].nome;
        select.add(option);
      }


      if (document.querySelector('.mysearch_filter_combo_ajax') !== null) {
        $('#' + filter_slave_id).selectpicker("refresh");
      }

      //hideBiscuit();
    },
    error: function (request, error) {
      //hideBiscuit();
      alert("Request: " + JSON.stringify(request));
    }
  });
}



function jumpToTop() {
  window.location.href = '#top_form';
}



function validateHhMm(value) {
  var validation = true;
  if (value.substr(2, 1) === ':') {
    if (!value.match(/^\d\d:\d\d/)) {
      validation = false;
    } else if (parseInt(value.substr(0, 2)) >= 24 || parseInt(value.substr(3, 2)) >= 60) {
      validation = false;
    }
  } else {
    validation = false;
  }

  return validation;

}

function showElementiNonPresenti(type){
  var title = "Elementi non presenti";
  switch(type){

    case 'tessere_assoc_non_presenti':
      title = "Tessere associative finite o non presenti per enti";
    break; 

    case 'cert_medici_non_presenti':
      title = "Certificati Medici non presenti";
    break; 
    
    case 'auto_cert_gp_non_presenti':
      title = "Autocertificazioni Green Pass non presenti";
    break;     
  }

  
  document.getElementById("title_generic_form").innerHTML = '<i class="fa fa-exclamation-triangle"></i>  <span id="title_generic_form_msg">' + title + '</span>';
  $("#modal-generic-form").modal();

}


	
function convertDate(inputFormat) {
  function pad(s) { return (s < 10) ? '0' + s : s; }
    var d = new Date(inputFormat)
    var dateStr = [pad(d.getDate()), pad(d.getMonth()+1), d.getFullYear()].join('/');
    return dateStr + " " + d.getHours() + ":" + padLeft(d.getMinutes(),2);
}

 

function padLeft(number, len) {
  var zeroes = "0".repeat(len);
  return zeroes.substring(number.toString().length, len) + number;
}


function sendMailWithAttach(module, email, id, subject){
  showBiscuit();
  $.ajax({
    url: baseURL + module + '/sendMailWithAttach/',
    type: 'POST',
    data:{
      module:module,
      email:email,
      id:id,
      subject:subject
    },
    dataType: 'html',
    success: function (data) {
      hideBiscuit();
      document.getElementById('modal_send-mail').innerHTML = "Email inviata con successo al seguente indirizzo: " + email;
      $('#modal-send-mail').modal();
    },
    error: function (request, error) {
      hideBiscuit();
      alert("Request: " + JSON.stringify(request));
    }
  });  
}


function showElementiNonPresenti(type){
  showBiscuit();

  $.ajax({
    url: baseURL  + 'mod_scadenze_notifiche/showElementiNonPresenti/' + type,
    type: 'GET',
    dataType: 'html',
    success: function (data) {
      document.getElementById('title_elementi-non-presenti').innerHTML  = '<i class="fa fa-exclamation-triangle"></i>  <span id="title_generic_form_msg">Notifiche</span></h5>';
      document.getElementById('modal_body_elementi-non-presenti').innerHTML  = data;
      $('#modal-elementi-non-presenti').modal();
      hideBiscuit();
    },
    error: function (request, error) {
 

      document.getElementById('title_elementi-non-presenti').innerHTML  = '<i class="fa fa-exclamation-triangle"></i>  <span id="title_generic_form_msg">ERRORE</span></h5>';
      document.getElementById('modal_body_elementi-non-presenti').innerHTML  = JSON.stringify(request);
      $('#modal-elementi-non-presenti').modal();
      hideBiscuit();      
    }
  });    


}