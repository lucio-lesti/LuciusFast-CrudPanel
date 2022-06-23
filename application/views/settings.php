<?php 
//CHECK SE INDIRIZZO IP AUTORIZZATO
if(!isset($ip_access_denied)){ 
?>

<?php



if(!empty($settings))
{
    foreach ($settings as $set)
    {
        $company_id = $set->company_id;
        $company_name = $set->	company_name;
        $company_code = $set->company_code;
        $company_email = $set->company_email;
		
		$company_email_send_comunication1 = $set->company_email_send_comunication1;
		$company_email_send_comunication2 = $set->company_email_send_comunication2;
		$company_email_send_comunication3 = $set->company_email_send_comunication3;

        $company_phone = $set->company_phone;
        $company_address = $set->company_address;
        $company_logo = $set->company_logo;
		$logo_splash = $set->logo_splash;
		$manager_signature = $set->manager_signature;
        $company_description = $set->company_description;
        $company_cellphone = $set->company_cellphone;
        $grid_page_number = $set->grid_page_number;	
		$system_session_time_limit = $set->system_session_time_limit;	
		$system_file_ext_admitted = $set->system_file_ext_admitted;	
		$system_imgfile_ext_admitted = $set->system_imgfile_ext_admitted;	
		$ip_filter_list = $set->ip_filter_list;
		$sidebar_fixed = $set->sidebar_fixed;	
		$change_code_invoice_every_year = $set->change_code_invoice_every_year;	
		$admin_mail = $set->admin_mail;	
		$nrInvoice = (int)$set->nrInvoice;	
    }
}


?>

<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
		<a style='cursor:pointer'>Impostazioni >></a>	
           <i class="fa fa-gears"></i>   Impostazioni Generali
        </h1>
    </section>
    <section class="content">
        <div class="row">
            <div class="col-md-8">
			<!-- general form elements -->
			<div class="nav-tabs-custom">
				<ul class="nav nav-tabs">
				  <li class="active"><a href="#company" data-toggle="tab" aria-expanded="true">Azienda</a></li>
				  <li class=""><a href="#system" data-toggle="tab" aria-expanded="false">Sistema</a></li>	  
				</ul>  
				<?php $this->load->helper("form"); ?>
				<form role="form" id="updateSettings" action="<?php echo base_url() ?>updateSettings" method="post" role="form" enctype="multipart/form-data">				
					<div class="tab-content">
						<div class="tab-pane tab-margin active" id="company">			
							<div class="box-body">
								 <div class="row">
									 <div class="col-md-6">
										 <div class="form-group">
											 <label for="company_logo">Logo Azienda</label>
											 
												<?php 
													if($company_logo != ""){
														echo "<br>";
														echo "<img style='width:150px;'src='".base_url()."uploads/logo/$company_logo'  alt='$company_name'>";
														echo "<br><br>";
													}
												?>
											<div class="input-group">	
												 <div class="input-group-addon"><i class="fa fa-image"></i></div>
												 <input type="file" class="form-control required"  id="company_logo" name="company_logo" maxlength="50">
												 <input type="hidden" id="company_logo_hidden" name="company_logo_hidden" maxlength="50" value ="<?php echo $company_logo;?>">
											 </div>
										 </div>

										</div>
									</div>
								 <div class="row">
									 <div class="col-md-6">
										 <div class="form-group">
											 <label for="manager_signature">Firma Responsabile</label>
											 
												<?php 
													if($manager_signature != ""){
														echo "<br>";
														echo "<img style='width:150px;'src='".base_url()."uploads/logo/$manager_signature'  alt='$manager_signature'>";
														echo "<br><br>";
													}
												?>
											<div class="input-group">	
												 <div class="input-group-addon"><i class="fa fa-image"></i></div>
												 <input type="file" class="form-control required"  id="manager_signature" name="manager_signature" maxlength="50">
												 <input type="hidden" id="manager_signature_hidden" name="manager_signature_hidden" maxlength="50" value ="<?php echo $manager_signature;?>">
											 </div>
										 </div>

										</div>
									</div>									
								 <div class="row">
									 <div class="col-md-6">
										 <div class="form-group">
											 <label for="logo_splash">Immagine Login/Avvio</label>
											 
												<?php 
													if($logo_splash != ""){
														echo "<br>";
														echo "<img style='max-width:450px;'src='".base_url()."uploads/logo/$logo_splash'  alt='$logo_splash'>";
														echo "<br><br>";
													}
												?>
											<div class="input-group">	
												 <div class="input-group-addon"><i class="fa fa-image"></i></div>
												 <input type="file" class="form-control required"  id="logo_splash" name="logo_splash" maxlength="50">
												 <input type="hidden" id="logo_splash_hidden" name="logo_splash_hidden" maxlength="50" value ="<?php echo $logo_splash;?>">
											 </div>
										 </div>

										</div>
									</div>									
									
									<div class="row">		
										<div class="col-md-12">
											 <div class="form-group">
												 <label for="company_name">Nome Azienda</label>
												 <div class="input-group">
													 <div class="input-group-addon"><i class="fa fa-home"></i></div>
													 <input type="text" class="form-control required" placeholder="Nome Azienda" value="<?php echo $company_name; ?>" id="company_name" name="company_name" maxlength="50">
												 </div>
											 </div>

										</div>	
									</div>	
									<div class="row">	
										<div class="col-md-12">
											 <div class="form-group">
												 <label for="company_address">Indirizzo</label>
													<div class="input-group">
														<div class="input-group-addon"><i class="fa fa-map-marker"></i></div>											 
															<input type="text" class="form-control required" placeholder="Indirizzo" value="<?php echo $company_address; ?>" id="company_address" name="company_address" maxlength="100">
													</div>
											 </div>
										</div>	
									</div>
								 <div class="row">
									 <div class="col-md-12">
										 <div class="form-group">
											 <label for="company_description">Breve Descrizione Azienda</label>
											 <div class="input-group">
												<div class="input-group-addon"><i class="fa fa-edit"></i></div>
													<!--
													<textarea class="form-control" id="company_description" name="company_description">
														<?php echo $company_description; ?>
													</textarea>
													-->		
													<input type="text" class="form-control required"  placeholder="Breve Descrizione Azienda" value="<?php echo $company_description; ?>" 
														id="company_description" name="company_description">
											</div>	 
										 </div>
									 </div>
								 </div>											
								 <div class="row">
									 <div class="col-md-6">
										 <div class="form-group">
											 <label for="company_code">P.IVA / Codice Fiscale</label>
											 <div class="input-group">
												 <div class="input-group-addon"><i class="fa fa-credit-card"></i></div>
													<input type="text" class="form-control required"  placeholder="P.IVA / Codice Fiscale" value="<?php echo $company_code; ?>" id="company_code" name="company_code"
												 maxlength="100">
											</div>	 
										 </div>
									 </div>
									 <div class="col-md-6">
										 <div class="form-group">
											 <label for="company_phone">Telefono</label>
												<div class="input-group">
													<div class="input-group-addon"><i class="fa fa-phone"></i></div>											 
														<input type="text" class="form-control required" placeholder="Telefono" value="<?php echo $company_phone; ?>" id="company_phone" name="company_phone" maxlength="50">
												</div>
										 </div>
									 </div>
								 </div>						 
								 <div class="row">
									<div class="col-md-6">
										 <div class="form-group">
											 <label for="company_cellphone">Cellulare</label>
												<div class="input-group">
													<div class="input-group-addon"><i class="fa fa-phone"></i></div>
													<input type="text" class="form-control" placeholder="Cellulare" value="<?php echo $company_cellphone; ?>" id="company_cellphone" name="company_cellphone" maxlength="50">
												</div>
										 </div>
									 </div>
									<div class="col-md-6">
										 <div class="form-group">
											 <label for="company_email">Email</label>
												<div class="input-group">
													<div class="input-group-addon"><i class="fa fa-envelope"></i></div>
													<input type="text" class="form-control required email" placeholder="Email" value="<?php echo $company_email; ?>" id="company_email" name="company_email" maxlength="50">
												</div>
										 </div>
									 </div>		
									 
									 <div class="col-md-6">
										 <div class="form-group">
											 <label for="company_email_send_comunication1">Email Comunicazioni 1</label>
												<div class="input-group">
													<div class="input-group-addon"><i class="fa fa-envelope"></i></div>
													<input type="text" class="form-control required email" placeholder="Email Comunicazioni 1" value="<?php echo $company_email_send_comunication1; ?>" id="company_email_send_comunication1" name="company_email_send_comunication1" maxlength="50">
												</div>
										 </div>
									 </div>	
									 
									 
									 <div class="col-md-6">
										 <div class="form-group">
											 <label for="company_email_send_comunication2">Email Comunicazioni 2</label>
												<div class="input-group">
													<div class="input-group-addon"><i class="fa fa-envelope"></i></div>
													<input type="text" class="form-control required email" placeholder="Email Comunicazioni 2" value="<?php echo $company_email_send_comunication2; ?>" id="company_email_send_comunication2" name="company_email_send_comunication2" maxlength="50">
												</div>
										 </div>
									 </div>	
									 
									 
									 <div class="col-md-6">
										 <div class="form-group">
											 <label for="company_email_send_comunication3">Email Comunicazioni 3</label>
												<div class="input-group">
													<div class="input-group-addon"><i class="fa fa-envelope"></i></div>
													<input type="text" class="form-control required email" placeholder="Email Comunicazioni 3" value="<?php echo $company_email_send_comunication3; ?>" id="company_email_send_comunication3" name="company_email_send_comunication3" maxlength="50">
												</div>
										 </div>
									 </div>	


								 </div>
							 </div>
						</div>
						<div class="tab-pane tab-margin" id="system">
							<div class="box-body">
								 <div class="row">
									 <div class="col-md-6">
										 <div class="form-group">
											 <label for="grid_page_number">Numero Righe per pagina</label>
												<div class="input-group">
													<div class="input-group-addon"><i class="fa fa-sort-numeric-asc"></i></div>
														<input type="text" class="form-control required" placeholder="Numero Righe per pagina" value="<?php echo $grid_page_number; ?>" id="grid_page_number" name="grid_page_number" maxlength="50">
												</div>
										</div>
									 </div>
									 <div class="col-md-6">
										 <div class="form-group">
											 <label for="system_session_time_limit">Session Time Limit</label>
												<div class="input-group">
													<div class="input-group-addon"><i class="fa fa-clock-o"></i></div>											 
														<input type="text" class="form-control required" placeholder="Session Time Limit" value="<?php echo $system_session_time_limit; ?>" id="system_session_time_limit" name="system_session_time_limit" maxlength="50">
												</div>
										 </div>
									 </div>									 
								</div>
								 <div class="row">
									 <div class="col-md-6">
										 <div class="form-group">
											 <label for="system_file_ext_admitted">File Ammessi(separati da una virgola)</label>
												<div class="input-group">
													<div class="input-group-addon"><i class="fa fa-file"></i></div>											 
														<input type="text" class="form-control required" placeholder="File Ammessi" value="<?php echo $system_file_ext_admitted; ?>" id="system_file_ext_admitted" name="system_file_ext_admitted" maxlength="255">
												</div>
										 </div>
									 </div>	
									 <div class="col-md-6">
										 <div class="form-group">
											 <label for="system_imgfile_ext_admitted">File Immagine Ammessi(separati da una virgola)</label>
												<div class="input-group">
													<div class="input-group-addon"><i class="fa fa-file"></i></div>											 
														<input type="text" class="form-control required" placeholder="File Immagine Ammessi" value="<?php echo $system_imgfile_ext_admitted; ?>" id="system_imgfile_ext_admitted" name="system_imgfile_ext_admitted" maxlength="255">
												</div>
										 </div>
									 </div>										 
									 <div class="col-md-6">
										 <div class="form-group">
											 <label for="system_file_ext_admitted">Cambio Numerazione Fattura Ogni Anno</label>
												<div class="input-group">
													<div class="input-group-addon"><i class="fa fa-file"></i></div>											 
														<?php 
															$disabled_select_change_code_invoice_every_year = "";
															if($nrInvoice > 0){
																$disabled_select_change_code_invoice_every_year = " disabled='disabled' ";
																
															}
														?>
														<SELECT <?php echo $disabled_select_change_code_invoice_every_year?> id="change_code_invoice_every_year" name="change_code_invoice_every_year" class="form-control required">
															<?php
																if($change_code_invoice_every_year == 'SI'){
																	echo'<OPTION VALUE="SI" SELECTED>SI</OPTION>';
																	echo'<OPTION VALUE="NO">NO</OPTION>';																	
																} else {
																	echo'<OPTION VALUE="SI">SI</OPTION>';
																	echo'<OPTION VALUE="NO" SELECTED>NO</OPTION>';																																		
																}
															?>
														</SELECT>
												</div>
												<small><b style='color:red'>(*)Il cambio codice si decide una sola volta, prima della creazione della prima fattura</b></small>
	
										 </div>
									 </div>										 
									<div class="col-md-6">
										 <div class="form-group">
											 <label for="ip_filter_list[]">Indirizzi IP Ammessi
											 <br><small><b style='color:red'>(*)Se non è presente nessun indirizzo il filtro non verrà effettuato</b></small>
											 </label>
											 
											<select multiple class="form-control required" id="ip_filter_list[]" name="ip_filter_list[]"	>
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
														<input type="text" class="form-control required" placeholder="Inserisci Indirizzo IP" id="ip_filter_list_add" name="ip_filter_list_add" maxlength="15">
												</div>
												<br>
											<button type='button' class="btn btn-default  button-submit" 
											onclick='addItemListBox("ip_filter_list_add", "ip_filter_list[]")'><span class="fa fa-plus"></SPAN> Aggiungi</button>
											<button type='button' class="btn btn-default  button-submit" onclick='removeItemListBox("ip_filter_list[]")'><span class="fa fa-minus-circle"></SPAN> Rimuovi</button>
										 </div>
									</div>


									<div class="col-md-6">
										 <div class="form-group">
											 <label for="ip_filter_list[]">Mail Amministratore
											 </label>
												<div class="input-group">
													<div class="input-group-addon"><i class="fa fa-at"></i></div>											 
														<input type="text" class="form-control required" placeholder="Inserisci Mail" id="admin_mail" name="admin_mail" maxlength="50" value="<?php echo $admin_mail; ?>">
												</div>

										 </div>
									</div>


								</div>	
								 <div class="row">
									 <div class="col-md-6">
										 <div class="form-group">
											 <label for="sidebar_fixed">Stile Menu Laterale</label>
												<div class="input-group">
													<div class="input-group-addon"><i class="fa fa-gears"></i></div>											 
													<select name='sidebar_fixed' id='sidebar_fixed' class="form-control">
														<?php 
															if($sidebar_fixed == 'S'){
														?>
														<option value="N" >Esteso</option>
														<option value="S" SELECTED>Fixed(Compatto)</option>
														<?php 
															} else {														
														?>
														<option value="N" SELECTED>Esteso</option>
														<option value="S" >Fixed(Compatto)</option>
														<?php } ?>				
													</select>
												</div>
										 </div>
									 </div>	
									 
									<div class="col-md-6">
										<div class="form-group"> 
											<label for="sidebar_fixed">Stile Grafico Dashboard</label>
											<br>
											<input type='hidden' name='skin_color'  id='skin_color' />
											 
											 <ul class="list-unstyled clearfix">
											   <li style="float:left; width: 33.33333%; padding: 5px;">
												  <a style="cursor:pointer"  onclick="changeSkinColor('skin-blue')" data-skin="skin-blue" style="display: block; box-shadow: 0 0 3px rgba(0,0,0,0.4)" class="clearfix full-opacity-hover">
													 <div><span style="display:block; width: 20%; float: left; height: 7px; background: #367fa9"></span><span class="bg-light-blue" style="display:block; width: 80%; float: left; height: 7px;"></span></div>
													 <div><span style="display:block; width: 20%; float: left; height: 20px; background: #222d32"></span><span style="display:block; width: 80%; float: left; height: 20px; background: #f4f5f7"></span></div>
												  </a>
												  <p class="text-center no-margin">Blue</p>
											   </li>
											   <li style="float:left; width: 33.33333%; padding: 5px;">
												  <a style="cursor:pointer" onclick="changeSkinColor('skin-black')" data-skin="skin-black" style="display: block; box-shadow: 0 0 3px rgba(0,0,0,0.4)" class="clearfix full-opacity-hover">
													 <div style="box-shadow: 0 0 2px rgba(0,0,0,0.1)" class="clearfix"><span style="display:block; width: 20%; float: left; height: 7px; background: #fefefe"></span><span style="display:block; width: 80%; float: left; height: 7px; background: #fefefe"></span></div>
													 <div><span style="display:block; width: 20%; float: left; height: 20px; background: #222"></span><span style="display:block; width: 80%; float: left; height: 20px; background: #f4f5f7"></span></div>
												  </a>
												  <p class="text-center no-margin">Black</p>
											   </li>
											   <li style="float:left; width: 33.33333%; padding: 5px;">
												  <a style="cursor:pointer" onclick="changeSkinColor('skin-purple')" data-skin="skin-purple" style="display: block; box-shadow: 0 0 3px rgba(0,0,0,0.4)" class="clearfix full-opacity-hover">
													 <div><span style="display:block; width: 20%; float: left; height: 7px;" class="bg-purple-active"></span><span class="bg-purple" style="display:block; width: 80%; float: left; height: 7px;"></span></div>
													 <div><span style="display:block; width: 20%; float: left; height: 20px; background: #222d32"></span><span style="display:block; width: 80%; float: left; height: 20px; background: #f4f5f7"></span></div>
												  </a>
												  <p class="text-center no-margin">Purple</p>
											   </li>
											   <li style="float:left; width: 33.33333%; padding: 5px;">
												  <a style="cursor:pointer" onclick="changeSkinColor('skin-green')" data-skin="skin-green" style="display: block; box-shadow: 0 0 3px rgba(0,0,0,0.4)" class="clearfix full-opacity-hover">
													 <div><span style="display:block; width: 20%; float: left; height: 7px;" class="bg-green-active"></span><span class="bg-green" style="display:block; width: 80%; float: left; height: 7px;"></span></div>
													 <div><span style="display:block; width: 20%; float: left; height: 20px; background: #222d32"></span><span style="display:block; width: 80%; float: left; height: 20px; background: #f4f5f7"></span></div>
												  </a>
												  <p class="text-center no-margin">Green</p>
											   </li>
											   <li style="float:left; width: 33.33333%; padding: 5px;">
												  <a style="cursor:pointer" onclick="changeSkinColor('skin-red')" data-skin="skin-red" style="display: block; box-shadow: 0 0 3px rgba(0,0,0,0.4)" class="clearfix full-opacity-hover">
													 <div><span style="display:block; width: 20%; float: left; height: 7px;" class="bg-red-active"></span><span class="bg-red" style="display:block; width: 80%; float: left; height: 7px;"></span></div>
													 <div><span style="display:block; width: 20%; float: left; height: 20px; background: #222d32"></span><span style="display:block; width: 80%; float: left; height: 20px; background: #f4f5f7"></span></div>
												  </a>
												  <p class="text-center no-margin">Red</p>
											   </li>
											   <li style="float:left; width: 33.33333%; padding: 5px;">
												  <a style="cursor:pointer" onclick="changeSkinColor('skin-yellow')" data-skin="skin-yellow" style="display: block; box-shadow: 0 0 3px rgba(0,0,0,0.4)" class="clearfix full-opacity-hover">
													 <div><span style="display:block; width: 20%; float: left; height: 7px;" class="bg-yellow-active"></span><span class="bg-yellow" style="display:block; width: 80%; float: left; height: 7px;"></span></div>
													 <div><span style="display:block; width: 20%; float: left; height: 20px; background: #222d32"></span><span style="display:block; width: 80%; float: left; height: 20px; background: #f4f5f7"></span></div>
												  </a>
												  <p class="text-center no-margin">Yellow</p>
											   </li>
											   <li style="float:left; width: 33.33333%; padding: 5px;">
												  <a style="cursor:pointer" onclick="changeSkinColor('skin-blue-light')" data-skin="skin-blue-light" style="display: block; box-shadow: 0 0 3px rgba(0,0,0,0.4)" class="clearfix full-opacity-hover">
													 <div><span style="display:block; width: 20%; float: left; height: 7px; background: #367fa9"></span><span class="bg-light-blue" style="display:block; width: 80%; float: left; height: 7px;"></span></div>
													 <div><span style="display:block; width: 20%; float: left; height: 20px; background: #f9fafc"></span><span style="display:block; width: 80%; float: left; height: 20px; background: #f4f5f7"></span></div>
												  </a>
												  <p class="text-center no-margin" style="font-size: 12px">Blue Light</p>
											   </li>
											   <li style="float:left; width: 33.33333%; padding: 5px;">
												  <a style="cursor:pointer" onclick="changeSkinColor('skin-black-light')" data-skin="skin-black-light" style="display: block; box-shadow: 0 0 3px rgba(0,0,0,0.4)" class="clearfix full-opacity-hover">
													 <div style="box-shadow: 0 0 2px rgba(0,0,0,0.1)" class="clearfix"><span style="display:block; width: 20%; float: left; height: 7px; background: #fefefe"></span><span style="display:block; width: 80%; float: left; height: 7px; background: #fefefe"></span></div>
													 <div><span style="display:block; width: 20%; float: left; height: 20px; background: #f9fafc"></span><span style="display:block; width: 80%; float: left; height: 20px; background: #f4f5f7"></span></div>
												  </a>
												  <p class="text-center no-margin" style="font-size: 12px">Black Light</p>
											   </li>
											   <li style="float:left; width: 33.33333%; padding: 5px;">
												  <a style="cursor:pointer" onclick="changeSkinColor('skin-purple-light')" data-skin="skin-purple-light" style="display: block; box-shadow: 0 0 3px rgba(0,0,0,0.4)" class="clearfix full-opacity-hover">
													 <div><span style="display:block; width: 20%; float: left; height: 7px;" class="bg-purple-active"></span><span class="bg-purple" style="display:block; width: 80%; float: left; height: 7px;"></span></div>
													 <div><span style="display:block; width: 20%; float: left; height: 20px; background: #f9fafc"></span><span style="display:block; width: 80%; float: left; height: 20px; background: #f4f5f7"></span></div>
												  </a>
												  <p class="text-center no-margin" style="font-size: 12px">Purple Light</p>
											   </li>
											   <li style="float:left; width: 33.33333%; padding: 5px;">
												  <a style="cursor:pointer" onclick="changeSkinColor('skin-green-light')" data-skin="skin-green-light" style="display: block; box-shadow: 0 0 3px rgba(0,0,0,0.4)" class="clearfix full-opacity-hover">
													 <div><span style="display:block; width: 20%; float: left; height: 7px;" class="bg-green-active"></span><span class="bg-green" style="display:block; width: 80%; float: left; height: 7px;"></span></div>
													 <div><span style="display:block; width: 20%; float: left; height: 20px; background: #f9fafc"></span><span style="display:block; width: 80%; float: left; height: 20px; background: #f4f5f7"></span></div>
												  </a>
												  <p class="text-center no-margin" style="font-size: 12px">Green Light</p>
											   </li>
											   <li style="float:left; width: 33.33333%; padding: 5px;">
												  <a style="cursor:pointer" onclick="changeSkinColor('skin-red-light')" data-skin="skin-red-light" style="display: block; box-shadow: 0 0 3px rgba(0,0,0,0.4)" class="clearfix full-opacity-hover">
													 <div><span style="display:block; width: 20%; float: left; height: 7px;" class="bg-red-active"></span><span class="bg-red" style="display:block; width: 80%; float: left; height: 7px;"></span></div>
													 <div><span style="display:block; width: 20%; float: left; height: 20px; background: #f9fafc"></span><span style="display:block; width: 80%; float: left; height: 20px; background: #f4f5f7"></span></div>
												  </a>
												  <p class="text-center no-margin" style="font-size: 12px">Red Light</p>
											   </li>
											   <li style="float:left; width: 33.33333%; padding: 5px;">
												  <a style="cursor:pointer" onclick="changeSkinColor('skin-yellow-light')" data-skin="skin-yellow-light" style="display: block; box-shadow: 0 0 3px rgba(0,0,0,0.4)" class="clearfix full-opacity-hover">
													 <div><span style="display:block; width: 20%; float: left; height: 7px;" class="bg-yellow-active"></span><span class="bg-yellow" style="display:block; width: 80%; float: left; height: 7px;"></span></div>
													 <div><span style="display:block; width: 20%; float: left; height: 20px; background: #f9fafc"></span><span style="display:block; width: 80%; float: left; height: 20px; background: #f4f5f7"></span></div>
												  </a>
												  <p class="text-center no-margin" style="font-size: 12px">Yellow Light</p>
											   </li>
											</ul>
										</div>
									</div>									 
									 
								</div>
							</div>		
						</div>
						<div class="box-footer">
							<button id="bt_create" name="bt_create"  type="button" 
							onclick='selectAllItemListBox("ip_filter_list[]");submitFormModule("updateSettings","bt_create","bt_reset_form")' class="btn btn-success  button-submit">
							<span class="fa fa-save"></SPAN> SALVA</button>
							
							<button id="bt_reset_form" name="bt_reset_form" type="reset" 
							class="btn btn-default  button-submit">
							<span class="fa fa-eraser"></SPAN> RESET</button>							
						</div>
					</div>	
				</form>				
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
<?php } ?>