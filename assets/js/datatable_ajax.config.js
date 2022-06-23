var apiFilterDataTable = null
var filterArray = new Array();
$(document).ready(function () {
    var columnNrAction = 0;
    $.fn.dataTableExt.oApi.fnPagingInfo = function (oSettings) {
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

    $('#mytable thead tr').clone(true).appendTo('#mytable thead');
    $('#mytable thead tr:eq(1) th').each(function (i) {
        columnNrAction++;
    });


    $('#mytable thead tr:eq(1) th').each(function (i) {
        if (i > 0) {
            if (i < (columnNrAction - 1)) {
                var title = $(this).text();
                switch (columnArray[i].type) {
                    case 'select':
                        var strSelect = '';
                        strSelect = '<SELECT class=\'mysearch_filter form-control\' id="' + columnArray[i].name +
                            '" name="' + columnArray[i].name + '" type_field ="' + columnArray[i].type +
                            '" title="Seleziona..." >';

                        strSelect += '<OPTION VALUE="">Cerca...</OPTION>';
                        for (var key in columnArray[i].items) {
                            strSelect += '<OPTION VALUE="' + columnArray[i].items[key] + '">' +
                                columnArray[i].items[key] + '</OPTION>';
                        }
                        strSelect += '</SELECT>';
                        $(this).html(strSelect);
                        break;

                    case 'select_ajax':
                        var strSelect = '';
                        strSelect = '<SELECT class=\'mysearch_filter_combo_ajax\' id="' + columnArray[i].name +
                            '" name="' + columnArray[i].name + '" type_field ="' + columnArray[i].type +
                            '"  multiple data-live-search="true"  data-actions-box="true" title="Seleziona..." >';
                        strSelect += '</SELECT>';
                        $(this).html(strSelect);
                        break;


                    case 'datalist_ajax':
                        var strSelect = '<input autofocus="" class="mysearch_filter form-control" list="' + columnArray[i].name + '_datalist" ' +
                            ' name="' + columnArray[i].name + '" id="' + columnArray[i].name + '" onchange="apiFilterDataTable.search(document.getElementById(\'' + columnArray[i].name + '\').value).draw();">';
                        strSelect += '<DATALIST  id="' + columnArray[i].name +
                            '_datalist" name="' + columnArray[i].name + '_datalist" + '
                        ' type_field ="' + columnArray[i].type +
                            '" >';
                        strSelect += '</DATALIST>';
                        $(this).html(strSelect);
                        break;

                    case 'text':
                        $(this).html(
                            '<input type="text" placeholder="Cerca..." class=\'mysearch_filter form-control\' id="' +
                            columnArray[i].name + '" name="' + columnArray[i].name +
                            '" / style="width:100%"  style="border-radius:3px" autocomplete="off" type_field ="' +
                            columnArray[i].type + '" >');
                        break;

                    /*   
                    //DATO TIPO CALENDARIO 
                    case 'date':
                        $(this).html(
                            '<input type="text" placeholder="Cerca..." class=\'mysearch_filter datetimepicker form-control\' id="' +
                            columnArray[i].name + '" name="' + columnArray[i].name +
                            '"  style="width:80%" autocomplete="off"  type_field ="' + columnArray[
                                i].type + '" />');
                        break;

                    case 'datetime':
                        $(this).html(
                            '<input type="text" placeholder="Cerca..." class=\'mysearch_filter datetimepicker form-control\' id="' +
                            columnArray[i].name + '" name="' + columnArray[i].name +
                            '"style="width:80%" autocomplete="off"  type_field ="' + columnArray[i]
                            .type + '" />');
                    break;
                    */

                    case 'date':
                        $(this).html(
                            '<input type="text" placeholder="Cerca..." class=\'mysearch_filter datemask form-control\' id="' +
                            columnArray[i].name + '" name="' + columnArray[i].name +
                            '"style="width:100%" autocomplete="off"  type_field ="' + columnArray[i]
                                .type + '" />');
                        break;


                    case 'datetime':
                        $(this).html(
                            '<input type="text" placeholder="Cerca..." class=\'mysearch_filter datetimemask form-control\' id="' +
                            columnArray[i].name + '" name="' + columnArray[i].name +
                            '"style="width:100%" autocomplete="off"  type_field ="' + columnArray[i]
                                .type + '" />');
                        break;


                    case 'number':
                        $(this).html(
                            '<input type="number" placeholder="Cerca..." class=\'mysearch_filter form-control\' id="' +
                            columnArray[i].name + '" name="' + columnArray[i].name +
                            '" style="width:100%" autocomplete="off"  type_field ="' + columnArray[i]
                                .type + '" />');
                        break;

                    case 'blob':
                        $(this).html('');
                        break;
                }

            } else {
                $(this).html('');
            }

        }

    });


    var mydata_table = $("#mytable").DataTable({
        initComplete: function () {
            var api = this.api();
            apiFilterDataTable = this.api();;
            $('#mytable_filter input')
                .off('.DT')
                .on('keyup.DT', function (e) {
                    if (e.keyCode == 13) {
                        api.search(this.value).draw();
                    }

                });

            /*$('select')*/
            $('.mysearch_filter')
                .off('.DT')
                .on('change', function (e) {
                    var searchFilter = document.getElementsByClassName("mysearch_filter");
                    for (var i = 0; i < searchFilter.length; i++) {
                        var element = document.getElementById(searchFilter[i].id);
                        var valuePOST = searchFilter[i].value;
                        var typeField = 'text';
                        if (element.classList.contains('datepicker') == true) {
                            typeField = 'date'
                        }
                        if (element.classList.contains('datetimepicker') == true) {
                            typeField = 'datetime'
                        }

                        if ($('#' + searchFilter[i].name).is('select') == true) {
                            valuePOST = $('select#' + searchFilter[i].name).val();
                        }

                        filterArray[i] = {
                            'field': searchFilter[i].name,
                            'value': searchFilter[i].value,
                            'type_field': typeField
                        };
                    }
                    //alert("2:" + this.value);;
                    api.search(this.value).draw();
                    $('input[type=search]').val("");
                });

            $('.mysearch_filter_combo_ajax')
                .off('.DT')
                .on('change', function (e) {

                    var searchFilter = document.getElementsByClassName("mysearch_filter_combo_ajax");

                    for (var i = 0; i < searchFilter.length; i++) {
                        var valuePOST = searchFilter[i].value;
                        var element = document.getElementById(searchFilter[i].id);
                        var typeField = 'select';


                        if ($('#' + searchFilter[i].name).is('select') == true) {
                            valuePOST = $('select#' + searchFilter[i].name).val();
                        }

                        if (typeof searchFilter[i].name !== 'undefined') {
                            filterArray[i] = {
                                'field': searchFilter[i].name,
                                'value': valuePOST,
                                'type_field': typeField
                            };
                        }

                    }
                    //alert("2:" + this.value);;

                    try {
                        api.search(this.value).draw();
                    } catch (err) {
                        console.log("Exception Datatable:" + err);
                    }

                    $('input[type=search]').val("");

                });

            $('.mysearch_filter')
                .off('.DT')
                .on('keydown.DT', function (e) {
                    var searchFilter = document.getElementsByClassName("mysearch_filter");
                    for (var i = 0; i < searchFilter.length; i++) {
                        var element = document.getElementById(searchFilter[i].id);
                        var valuePOST = searchFilter[i].value;
                        var typeField = 'text';
                        if (element.classList.contains('datepicker') == true) {
                            typeField = 'date'
                        }
                        if (element.classList.contains('datetimepicker') == true) {
                            typeField = 'datetime'
                        }
                        if ($('#' + searchFilter[i].name).is('select') == true) {
                            valuePOST = $('select#' + searchFilter[i].name).val();
                        }
                        filterArray[i] = {
                            'field': searchFilter[i].name,
                            'value': searchFilter[i].value,
                            'type_field': typeField
                        };
                    }
                    if (e.keyCode == 13) {
                        //alert("3:" + this.value);
                        api.search(this.value).draw();
                        $('input[type=search]').val("");
                    }


                });
            $('.datepicker').datepicker({
                locale: 'it',
                format: 'DD/MM/YYYY'
            });
            $('.datetimepicker').datetimepicker({
                locale: 'it',
                format: 'DD/MM/YYYY'
            });
            $('.datetimepicker').on('dp.change', function () {
                e = $.Event('keydown');
                e.keyCode = 13;
                $('#' + this.id).trigger(e);
            });
        },
        oLanguage: {
            sSearch: "Ricerca su tutte le colonne:",
            emptyTable: "Nessun record",
            sProcessing: "Caricamento... <IMG SRC='" + baseURL + "assets/images/loading3.gif' />",
            paginate: {
                previous: " << ",
                next: " >> "
            },
        },
        "lengthChange": false,
        processing: true,
        serverSide: true,
        ordering: objAjaxConfig.datatable.ordering,
        ajax: {
            "url": objAjaxConfig.mod_name + "/json",
            "type": "POST",
            "data": function (data) {
                data.searchFilter = filterArray;
            }
        },
        columns: columnGrid,
        order: [
            [1, 'asc']
        ],
        rowCallback: function (row, data, iDisplayIndex) {
            var info = this.fnPagingInfo();
            var page = info.iPage;
            var length = info.iLength;
            var index = page * length + (iDisplayIndex + 1);
        },
        orderCellsTop: true,
        fixedHeader: false,
        autoWidth: false,
        deferRender: true,
    });

    //mydata_table.columns.adjust();

    $('.mysearch_filter_combo').selectpicker({
        includeSelectAllOption: true,
        deselectAllText: 'Deselez. tutti',
        selectAllText: 'Seleziona tutti',
        noneResultsText: 'Nessun risultato per {0}',
        dropupAuto: false,
        refresh: true
    });


    $('.mysearch_filter_combo_ajax').selectpicker({
        includeSelectAllOption: true,
        deselectAllText: 'Deselez. tutti',
        selectAllText: 'Seleziona tutti',
        noneResultsText: 'Nessun risultato per {0}',
        dropupAuto: false,
        refresh: true,
        noneSelectedText: 'Seleziona...'
    });

});
var tabID = 1;
var button = '<button class=\'close\' type=\'button\' title=\'Remove this page\'>×</button>';
$('#tab-menu').on('click', '.close', function () {
    var tabID = $(this).parents('a').attr('href');
    showBiscuit();
    $(this).parents('li').remove();
    $(tabID).remove();

    //DISABILITO TEMPORANEAMENTE  LA POSSIBILITA DI PIU FORM APERTI
    $('#mytable').DataTable().ajax.reload(function (json) {
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


var editHandler = function () {
    var t = $(this);
    t.css('visibility', 'hidden');
    $(this).prev().attr('contenteditable', 'true').focusout(function () {
        $(this).removeAttr('contenteditable').off('focusout');
        t.css('visibility', 'visible');
    });
};
$('.edit').click(editHandler);



