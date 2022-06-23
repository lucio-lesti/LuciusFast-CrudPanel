<?php 
//CHECK SE INDIRIZZO IP AUTORIZZATO
if(!isset($ip_access_denied)){ 
?>
<div class="content-wrapper">
    
	<!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
		    <a style='cursor:pointer'>Utenti >></a>
            <i class="fa fa-users"></i> 
			<a href="<?php echo site_url('user/rolesList') ?>">
			<b><u>Lista Ruoli</u><b>
			</a>
		
           <b style='font-size:20px'> >> </b><b style='font-size:20px'><?php echo $action_label;?></b>
        </h1>
    </section>
	
	
    <section class="content">
        <div class="row">
            <!-- left column -->
            <div class="col-md-8">
			<div class="nav-tabs-custom">
				<ul class="nav nav-tabs">
				  <li class="active"><a href="#role" data-toggle="tab" aria-expanded="true">Descrizione Ruolo</a></li>
				  	<?php
						if(isset($permission)){
					?>
				  <li class=""><a href="#permission" data-toggle="tab" aria-expanded="false">Permessi</a></li>	 
					<?php  }?>				  
				</ul>  
					<?php $this->load->helper("form"); ?>
					<form role="form" action="<?php echo $action; ?>" method="post" name="<?php echo $frm_module_name; ?>" id="<?php echo $frm_module_name; ?>">
					<div class="tab-content">
						<div class="tab-pane tab-margin active" id="role">			
							<div class="box-body">
								 <div class="row">
									 <div class="col-md-6">
										<div class="form-group">
											<label for="int">Ruolo</label>
											<div class="input-group">
												<div class="input-group-addon"><i class="fa fa-cubes"></i></div>                                        <input type="hidden" value="<?php echo $roles->roleid; ?>" name="roleId" id="roleId" />							
													<input type="text"  class="form-control required" name="role" id="role" placeholder="Immettere Ruolo" required="required" value="<?php echo $roles->role; ?>" />
											</div>
										</div>	

									 </div>
 
								 </div>
 
							 </div>
						</div>
						<?php
							if(isset($permission)){
						?>
						
						<div class="tab-pane tab-margin" id="permission">
							<div class="box-body">
								<div class="row">						
									<div class="col-md-12">
										<div class="form-group">
											<b style='font-size:18px'><u>Permessi</u></b>
											<br><br>
											<b>Seleziona/Deseleziona Tutti i Permessi</b>
											<input type='checkbox' id='select_all_perm' name='select_all_perm' onchange='selectDeselectAllPermission(this.checked)'  />
											<br><br>
												
												<?php
												//MODALITA CON MODULI->SOTTOMODULI
												//AGGIUNGO PER PRIMA I MODULI PRINCIPALI
												$listPermission = array();
												foreach($permission as $key => $value){
													if($value->mod_parentid == 0){
														$listPermission[$value->mod_id] = $value;
														$listPermission[$value->mod_id]->child = array();	
													}

												}	

												//AGGIUNGO PER PRIMA AI MODULI I MODULI FIGLI	
												foreach($permission as $key => $value){		
													if((int)$value->mod_parentid > 0){
														if(!isset($listPermission[$value->mod_parentid])){
															$listPermission[$value->mod_parentid] = new stdClass();		
														}
														$listPermission[$value->mod_parentid]->child[] = $value;
													}
												}
												
												
												//ITERO IL VETTORE MODULI E SOTTOMODULI
												foreach($listPermission as $key => $value){
													//print'<pre>';print_r($value);
													if(isset($value->mod_title)){
														echo "<b>".$value->mod_title."</b><br>";
													}
													
													foreach($value->child as $childKey => $childValue){
														echo "<div style='margin-left:10px'>";
														echo " --> <b style='color:#990000'>".$childValue->mod_title."</b><br>";
														if($childValue->perm_read == 'Y'){
															echo "Lettura <input 
															type='checkbox' id='mod_id[]' name='mod_id[]'
															checked='checked' value='".$childValue->mod_id."|perm_read' class='perm_".$childValue->mod_parentid."'  /> | ";	
														} else {
															echo "Lettura <input 
															id='mod_id[]' name='mod_id[]' type='checkbox' value='".$childValue->mod_id."|perm_read' class='perm_".$childValue->mod_parentid."'   /> | ";	
														}
														
														if($childValue->perm_write == 'Y'){
															echo "Inserimento <input type='checkbox' checked='checked' id='mod_id[]' name='mod_id[]'
															value='".$childValue->mod_id."|perm_write' class='perm_".$childValue->mod_parentid."'  /> | ";
														} else {
															echo "Inserimento <input type='checkbox' 
															id='mod_id[]' name='mod_id[]' value='".$childValue->mod_id."|perm_write' class='perm_".$childValue->mod_parentid."'   /> | ";			
														}
														
														if($childValue->perm_update == 'Y'){
															echo "Modifica <input type='checkbox' checked='checked' 
															id=mod_id[] name=mod_id[]
															value='".$childValue->mod_id."|perm_update' class='perm_".$childValue->mod_parentid."'   /> | ";		
														} else {
															echo "Modifica <input type='checkbox' 
															id=mod_id[] name=mod_id[] value='".$childValue->mod_id."|perm_update' class='perm_".$childValue->mod_parentid."'   /> | ";				
														}
														
														if($childValue->perm_delete == 'Y'){
															echo "Cancellazione <input type='checkbox' checked='checked' 
															id=mod_id[] name=mod_id[] 
															value='".$childValue->mod_id."|perm_delete' class='perm_".$childValue->mod_parentid."'   />";
														} else {
															echo "Cancellazione <input type='checkbox' 
															id=mod_id[] name=mod_id[] 
															value='".$childValue->mod_id."|perm_delete' class='perm_".$childValue->mod_parentid."'   />";
														}
	 
														echo "</div>";
													}
													echo "<br><br>";	
												}											
											?>
										</div>	
									</div>		
								</div>
							</div>		
						</div>
						<?php } ?>
						<div class="row">
							<div class="col-md-12">							
								<div class="box-footer">
									<div class="row">
										<div class="col-md-6">
											<button id='<?php echo $button_id; ?>' 
											onclick='submitFormModule("<?php echo $frm_module_name; ?>","<?php echo $button_id; ?>","reset_form")'
											type="button" class="btn btn-success  button-submit"  data-loading-text="Caricamento..."><span class="fa fa-save"></span> SALVA</button>
				 
											<button id='reset_form' name='reset_form' type="reset" class="btn btn-default">
											<span class="fa fa-eraser"></span> RESET</button>
										</div>
										
 
										<div class="col-md-6" align="right">
											<a href="<?php echo site_url('user/rolesList') ?>" class="btn btn-default"><i class="fa fa-arrow-circle-left"></i> INDIETRO</a>
																	
										</div>								
									</div>		
								</div>	
							</div>
						</div>	
 
 
					</div>
				</form>
			</div>	
            </div>
                <div class="col-md-4">
                    <?php
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
	<script>
		function selectDeselectAllPermission(isChecked){
			if(isChecked == true){
				$(':checkbox').each(function() {
					this.checked = true;                        
				});					
			} else {
				$(':checkbox').each(function() {
					this.checked = false;                        
				});						
			}
		
 
		}
		
		function selectAllPermissionByMod(isChecked, modId){
			if(isChecked == true){
				$('.' + modId).each(function() {
					this.checked = true;   
					                    
				});			
			} else {
				$('.' + modId).each(function() {
					this.checked = false;                        
				});					
			}
 	
		}		
	</script>
<?php } ?>	