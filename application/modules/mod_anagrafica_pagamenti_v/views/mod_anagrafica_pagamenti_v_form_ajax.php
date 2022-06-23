<?php

$field_disabled = "";
if ((isset($id)) && ($id != "")) {
    $id_main_content = $id;
    $field_disabled = "disabled";
} else {
    $id_main_content = rand(0, 1000000);
}
$id_button = $id;

?>
<div <?php if ($winForm == 'TRUE') {
            echo " class='main_content_ajax_form' ";
        } ?> id="main_content_ajax_form_<?php echo $id_main_content; ?>">
    <!-- Content Header (Page header) -->
    <div class="col-md-8">
        <h3>
            <i class="fa fa-cubes"></i>
            <a>
                <u> Pagamenti Ricevuti</u>
            </a>
            <?php
            if ((isset($id)) && ($id != "")) {
            ?>
                <b style='font-size:22px'> >> </b><b style='font-size:22px'>Modifica ID:<?= $id ?></b>
            <?php } else { ?>
                <b style='font-size:22px'> >> </b><b style='font-size:22px'>Nuovo</b>
            <?php } ?>

        </h3>
        <?php
        $error = $this->session->flashdata('error');
        if ($error) {
        ?>
            <div class="alert alert-danger alert-dismissable">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                <?php echo $this->session->flashdata('error'); ?>
            </div>
        <?php } ?>
        <?php
        $success = $this->session->flashdata('success');
        if ($success) {
        ?>
            <div class="alert alert-success alert-dismissable">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                <?php echo $this->session->flashdata('success'); ?>
            </div>
        <?php } ?>

        <div class="row">
            <div class="col-md-12">
                <?php echo validation_errors('<div class="alert alert-danger alert-dismissable">', ' <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button></div>'); ?>
            </div>
        </div>
    </div>
    <div class="row">
        <!-- left column -->
        <div class="col-md-12">
            <!-- general form elements -->
            <div class="box box-primary">
                <!-- /.box-header -->
                <!-- form start -->

                <div class="box-body">
                    <?php $this->load->helper("form"); ?>
                    <?php
                    //SE GIA SALVATO IMPEDISCO IL SUBMIT DEL FORM
                    if ($afterSave == TRUE) {
                        $preventDefault = 'onsubmit="event.preventDefault();return false"';
                    }
                    ?>
                    <form action="<?php echo $action; ?>" method="post" name="<?php echo $frm_module_name . '_' . $id; ?>" id="<?php echo $frm_module_name . '_' . $id; ?>" <?php $preventDefault; ?>\>
                        <div class='col-md-12'>
                            <B STYLE='color:#990000'>(*)</B>Campi obbligatori
                            <br><br>
                        </div>


                        <div class="col-md-8">
                            <div class="form-group">
                                <?php
                                $fk_anagrafica_label = NULL;
                                foreach ($fk_anagrafica_refval as $key => $value) {
                                    if ($value['id'] == $id_anagrafica) {
                                        $fk_anagrafica_label = $value['nome'];
                                    }
                                }
                                ?>

                                <label for="fk_anagrafica"><b style="color:#990000">(*)</b>Anagrafica
                                    <a href="<?php echo base_url() . "mod_anagrafica/?id=$id_anagrafica" ?>" target="_blank">
                                        <i class="fa fa-external-link" style="border-radius: 0;border:solid 1px #999;background-color:#d2d6de;padding:5px"></i>
                                    </a>
                                    <?php echo form_error('fk_anagrafica') ?></label>
                                <div class="input-group">
                                    <div class="input-group-addon"><i class="fa fa-text-height"></i></div>
                                    <input readonly="readonly" type="text" class="form-control" name="anagrafica" id="anagrafica" placeholder="Anagrafica" autocomplete="off" value="<?php echo $fk_anagrafica_label; ?>" />
                                    <input type="hidden" name="fk_anagrafica" id="fk_anagrafica" value="<?php echo $id_anagrafica ?>" />
                                    <input type="hidden" name="recordID" id="recordID" value="<?php echo $id_anagrafica."_".$id_esercizio ?>" />
                                </div>
                            </div>
                        </div>

                        <div class="col-md-8">
                            <div class="form-group">
                                <?php
                                $fk_esercizio_label = "";

                                foreach ($fk_esercizio_refval as $key => $value) {
                                    if ($value['id'] == $id_esercizio) {
                                        $fk_esercizio_label = $value['nome'];
                                    }
                                }
                                ?>

                                <label for="fk_esercizio"><b style="color:#990000">(*)</b>Esercizio <?php echo form_error('fk__esercizio') ?></label>
                                <div class="input-group">
                                    <div class="input-group-addon"><i class="fa fa-text-height"></i></div>
                                    <input readonly="readonly" type="text" class="form-control" name="esercizio" id="esercizio" placeholder="Esercizio" autocomplete="off" value="<?php echo $fk_esercizio_label; ?>" />
                                    <input type="hidden" name="fk_esercizio" id="fk_esercizio" value="<?php echo $id_esercizio ?>" />
                                </div>
                            </div>
                        </div>

                        <?php
                        //print'<pre>';print_r($lista_corsi);die();
                        if (isset($lista_corsi)) {
                            $countTab = 0;
                            echo "<div class=\"col-md-8\"><div class=\"form-group\"><fieldset>";
                            echo "<legend>Sconti su Corsi</legend>";
                            foreach ($lista_corsi as  $key => $corso) {
                                //print'<pre>';print_r($corso);
                        ?>
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <div class="input-group">
                                                        <div class="input-group-addon"><i class="fa fa-text-height"></i></div>
                                                        <input readonly="readonly" style="font-size:12px" type="text" class="form-control" name="nome_corso" id="nome_corso" placeholder="nome_corso" autocomplete="off" value="<?php echo $corso['nome_corso']; ?>" />
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <div class="input-group">
                                                        <div class="input-group-addon"><i class="fa fa-sort-numeric-asc"></i></div>
                                                       <input type="number" id="importo[<?php echo $corso['id_corso']; ?>]" name="importo[<?php echo $corso['id_corso']; ?>]"   style="width:80px;background-color:#EEE;border:2px solid #DEDEDE" readonly="readonly"  value="<?php echo $corso['importo']; ?>" />
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <div class="input-group">
                                                    <b>Scontato:</b>
                                                        <div class="input-group-addon"><i class="fa fa-sort-numeric-asc"></i></div>
                                                        <input type="number" style="width:80px" id="importo_scontato[<?php echo $corso['id_corso']; ?>]" name="importo_scontato[<?php echo $corso['id_corso']; ?>]"  value="<?php echo $corso['importo_scontato']; ?>"  />
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                        <?php
                            }
                            echo "</fieldset></div></div><br>";
                        }
                        ?>





                        <div class="col-md-12">
                            <div class="form-group" id="divAjaxMsg_container">
                                <div id="divAjaxMsg" style="display:none;font-size:18px" class="alert alert-success alert-dismissable" onclick="hideMsg('divAjaxMsg')">
                                </div>
                                <div id="divAjaxMsgErr" style="display:none;font-size:18px" class="alert alert-error alert-dismissable" onclick="hideMsg('divAjaxMsgErr')">
                                </div>
                                <div id="master_details_list" name="master_details_list">
                                    <ul class="nav nav-tabs" id="myTab" role="tablist">
                                        <?php
                                        //print'<pre>';print_r($master_details_list);
                                        if (isset($master_details_list)) {
                                            $countTab = 0;
                                            foreach ($master_details_list as  $key => $master_details) {
                                                if ($countTab == 0) {
                                                    echo '<li class="nav-item active">
                                <a class="nav-link active" id="lnk-' . $master_details['id'] . '" data-toggle="tab" href="#' . $master_details['id'] . '" role="tab" aria-controls="' . $master_details['id'] . '" aria-selected="true" aria-expanded="true">' . $master_details['title'] . '</a>
                                </li>';
                                                } else {
                                                    echo '<li class="nav-item">
                                <a class="nav-link" id="lnk-' . $master_details['id'] . '" data-toggle="tab" href="#' . $master_details['id'] . '" role="tab" aria-controls="' . $master_details['id'] . '" aria-selected="true" aria-expanded="true">' . $master_details['title'] . '</a>
                                </li>';
                                                }
                                                $countTab++;
                                            }
                                        }
                                        ?>
                                    </ul>
                                    <div class="tab-content">
                                        <?php
                                        if (isset($master_details_list)) {
                                            $countTab = 0;
                                            foreach ($master_details_list as  $key => $master_details) {
                                                $active = "active";
                                                if ($countTab > 0) {
                                                    $active = "";
                                                }
                                                echo '<div class="tab-pane ' . $active . '" id="' . $master_details['id'] . '" role="tabpanel" aria-labelledby="' . $master_details['id'] . '-tab">';
                                                echo  $master_details['function'];
                                                echo '</div>';
                                                $countTab++;
                                            }
                                        }
                                        ?>

                                    </div>
                                </div>
                                <p><br><br><br></p>
                            </div>
                        </div>
                        <input type="hidden" name="" value="<?php echo $id; ?>" />
                        <div class="row">
                            <div class="col-md-12">
                                <?php
                                if ($afterSave == NULL) {
                                ?>
                                    <div class='row'>
                                        <div class='col-md-6'>
                                            <?php
                                            if ($winForm == "FALSE") {
                                            ?>
                                                <button id='<?php echo $button_id; ?>' type="submit" type="button" class="btn btn-success  button-submit" data-loading-text="Caricamento..."><span class="fa fa-save"></span> SALVA</button>
                                            <?php } ?>
                                        </div>

                                        <div class="col-md-6" align="right">
                                        </div>

                                    </div>
                            </div>
                        </div>
                    <?php } ?>
                    </form>
                </div>

                <!-- form close -->
            </div>
        </div>
    </div>
    <?php
    if ($type_action == 'create') {
        $ajaxAction = 'mod_anagrafica_pagamenti_v/create_action';
    } else {
        $ajaxAction = 'mod_anagrafica_pagamenti_v/update_action';
    }

    $data['ajaxAction'] = $ajaxAction;
    $data['frm_module_name'] = $frm_module_name;
    $data['id'] = $id;
    $data['id_main_content'] = $id_main_content;
    ?>
    <?php echo $this->load->view("jsconfig/mod_anagrafica_pagamenti_v_form_config.js.php", $data, true); ?>
    <script src="<?php echo base_url(); ?>assets/js/form_submit_ajax.config.js"></script>

    <script>
        arrayValidationFields['winMasterDetail_mod_pagamenti_ricevuti'] = [];
        arrayValidationFields['winMasterDetail_mod_pagamenti_ricevuti']['datapagamento'] = [];
        arrayValidationFields['winMasterDetail_mod_pagamenti_ricevuti']['datapagamento']['label'] = "Data Pagamento";
        arrayValidationFields['winMasterDetail_mod_pagamenti_ricevuti']['datapagamento']['field_type'] = "date";
        arrayValidationFields['winMasterDetail_mod_pagamenti_ricevuti']['mese_anno'] = [];
        arrayValidationFields['winMasterDetail_mod_pagamenti_ricevuti']['mese_anno']['label'] = "Mese - Anno";
        arrayValidationFields['winMasterDetail_mod_pagamenti_ricevuti']['mese_anno']['field_type'] = "int";
        arrayValidationFields['winMasterDetail_mod_pagamenti_ricevuti']['importo'] = [];
        arrayValidationFields['winMasterDetail_mod_pagamenti_ricevuti']['importo']['label'] = "Importo";
        arrayValidationFields['winMasterDetail_mod_pagamenti_ricevuti']['importo']['field_type'] = "int";
        arrayValidationFields['winMasterDetail_mod_pagamenti_ricevuti']['saldo'] = [];
        arrayValidationFields['winMasterDetail_mod_pagamenti_ricevuti']['saldo']['label'] = "Saldo";
        arrayValidationFields['winMasterDetail_mod_pagamenti_ricevuti']['saldo']['field_type'] = "int";        
    </script>
</div>