<?php 
//CHECK SE INDIRIZZO IP AUTORIZZATO
if(!isset($ip_access_denied)){ 
?>
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            <i class="fa fa-users"></i> 
			<a href="<?php echo site_url('userListing') ?>"> Lista utenti </a>
           <b style='font-size:20px'> >> </b><b style='font-size:20px'>Aggiungi / Modifica Utente</b>			
        </h1>
    </section>

    <section class="content">

        <div class="row">
            <!-- left column -->
            <div class="col-md-8">
                <!-- general form elements -->



                <div class="box box-primary">
                    <div class="box-header">
                        <h3 class="box-title">Inserisci le informazioni dell'utente</h3>
                    </div>
                    <!-- /.box-header -->
                    <!-- form start -->
                    <?php $this->load->helper("form"); ?>
                    <form id="addUser" name="addUser" action="<?php echo base_url() ?>addNewUser" method="post" role="form">
                        <div class="box-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="fname">Username</label>
                                        <input type="text" class="form-control required" value="<?php echo set_value('fname'); ?>" id="fname" name="fname" maxlength="128">
                                    </div>

                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="email">Email</label>
                                        <input type="text" class="form-control required email" id="email" value="<?php echo set_value('email'); ?>" name="email"
                                            maxlength="128">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="password">Password</label>
                                        <input type="password" class="form-control required" id="password" name="password" maxlength="20">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="cpassword">Ripeti Password</label>
                                        <input type="password" class="form-control required equalTo" id="cpassword" name="cpassword" maxlength="20">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="mobile">Telefono</label>
                                        <input type="text" class="form-control required digits" id="mobile" value="<?php echo set_value('mobile'); ?>" name="mobile"
                                            maxlength="10">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="role">Ruolo</label>
                                        <select class="form-control required" id="role" name="role">
                                            <option value="0">Seleziona Ruolo</option>
                                            <?php
                                            if(!empty($roles))
                                            {
                                                foreach ($roles as $rl)
                                                {
                                                    ?>
                                                <option value="<?php echo $rl->roleid ?>" <?php if($rl->roleid == set_value('role')) {echo "selected=selected";} ?>>
                                                    <?php echo $rl->role ?>
                                                </option>
                                                <?php
                                                }
                                            }
                                            ?>
                                        </select>
                                    </div>
                                </div>
									<div class="col-md-6">
										 <div class="form-group">
											 <label for="ip_filter_list[]">Indirizzi IP Ammessi per utente
											 <br><small><b style='color:red'>(*)Se non è presente nessun indirizzo il filtro non verrà effettuato sull'utente</b></small>
											 </label>
											 
											<select multiple class="form-control" id="ip_filter_list[]" name="ip_filter_list[]"	>
											 <?php 
												$listIpAddress = explode(",",$ip_filter_list);
												foreach($listIpAddress as $ipaddress){
													echo "<option value='".$ipaddress."'>".$ipaddress."</option>";
												}
											 ?>
											 </select>
											 <br>
												<div class="input-group">
													<div class="input-group-addon"><i class="fa fa-filter"></i></div>											 
														<input type="text" class="form-control" placeholder="Inserisci Indirizzo IP" id="ip_filter_list_add" name="ip_filter_list_add" maxlength="15">
												</div>
												<br>
											<button type='button' class="btn btn-default  button-submit" 
											onclick='addItemListBox("ip_filter_list_add", "ip_filter_list[]")'><span class="fa fa-plus"></SPAN> Aggiungi</button>
											<button type='button' class="btn btn-default  button-submit" onclick='removeItemListBox("ip_filter_list[]")'><span class="fa fa-minus-circle"></SPAN> Rimuovi</button>
										 </div>									
									</div>								
								
                            </div>
                        </div>
                        <!-- /.box-body -->

                        <div class="box-footer">
							<button id="bt_create" name="bt_create"  type="button" 
							onclick='selectAllItemListBox("ip_filter_list[]");submitFormModule("addUser","bt_create","bt_reset_form")' class="btn btn-success  button-submit">
							<span class="fa fa-save"></SPAN> SALVA</button>
							<button id="bt_reset_form" name="bt_reset_form" type="reset" 
							class="btn btn-default  button-submit">
							<span class="fa fa-eraser"></SPAN> RESET</button>
                        </div>
                    </form>
                </div>
            </div>
            <div class="col-md-4">
                    <div class="row">
                        <div class="col-md-12">
                            <?php echo validation_errors('<div class="alert alert-danger alert-dismissable">', ' <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button></div>'); ?>
                        </div>
                    </div>
            </div>
        </div>
    </section>
</div>
<script src="<?php echo base_url(); ?>assets/js/addUser.js" type="text/javascript"></script>
<?php } ?>