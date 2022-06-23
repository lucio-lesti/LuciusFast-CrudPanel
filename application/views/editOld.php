<?php 
//CHECK SE INDIRIZZO IP AUTORIZZATO
if(!isset($ip_access_denied)){ 
?>

<?php

$userId = '';
$name = '';
$email = '';
$mobile = '';
$roleId = '';

if(!empty($userInfo))
{
    foreach ($userInfo as $uf)
    {
        $userId = $uf->userid;
        $name = $uf->name;
        $email = $uf->email;
        $mobile = $uf->mobile;
        $roleId = $uf->roleid;
		$ip_filter_list = $uf->ip_filter_list;
    }
}


?>

    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <h1>
			    <a style='cursor:pointer'>Utenti >></a>	
                <i class="fa fa-users"></i> <a href='<?php echo base_url()."userListing";?>'><b>Lista utenti</b></a>
				<b>>> Modifica User ID: <?php echo $userId ?></b>
            </h1>
        </section>

        <section class="content">

            <div class="row">
                <!-- left column -->
                <div class="col-md-8">
                    <!-- general form elements -->



                    <div class="box box-primary">
                        <div class="box-header">
                            <h3 class="box-title">Inserisci informazioni utente</h3>
                        </div>
                        <!-- /.box-header -->
                        <!-- form start -->

                        <form action="<?php echo base_url() ?>editUser" method="post" id="editUser" name="editUser" role="form">
                            <div class="box-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="fname">Username</label>
                                            <input readonly="readonly" type="text" class="form-control" id="fname" placeholder="Username" name="fname" value="<?php echo $name; ?>" maxlength="128">
                                            <input type="hidden" value="<?php echo $userId; ?>" name="userId" id="userId" />
                                        </div>

                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="email">Email</label>
                                            <input readonly="readonly" type="email" class="form-control" id="email" placeholder="Email" name="email" value="<?php echo $email; ?>"
                                                maxlength="128">
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="password">Password</label>
                                            <input type="password" class="form-control" id="password" placeholder="Password" name="password" maxlength="20">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="cpassword">Re-inserisci password</label>
                                            <input type="password" class="form-control" id="cpassword" placeholder="Re-inserisci password" name="cpassword" maxlength="20">
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="mobile">Telefono</label>
                                            <input type="text" class="form-control" id="mobile" placeholder="Telefono" name="mobile" value="<?php echo $mobile; ?>"
                                                maxlength="10">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="role">Ruolo</label>
                                            <select class="form-control" id="role" name="role">
                                                <option value="0">Seleziona Ruolo</option>
                                                <?php
                                            if(!empty($roles))
                                            {
                                                foreach ($roles as $rl)
                                                {
                                                    ?>
                                                    <option value="<?php echo $rl->roleid; ?>" <?php if($rl->roleid == $roleId) {echo "selected=selected";} ?>>
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
							onclick='selectAllItemListBox("ip_filter_list[]");submitFormModule("editUser","bt_create","bt_reset_form")' class="btn btn-success  button-submit">
							<span class="fa fa-save"></SPAN> SALVA</button>
							<button id="bt_reset_form" name="bt_reset_form" type="reset" 
							class="btn btn-default  button-submit">
							<span class="fa fa-eraser"></SPAN> RESET</button>	
                            </div>
                        </form>
                    </div>
                </div>
                <div class="col-md-4">
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
                    if($success)
                    {
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
            </div>
        </section>
    </div>

    <script src="<?php echo base_url(); ?>assets/js/editUser.js" type="text/javascript"></script>
<?php } ?>	