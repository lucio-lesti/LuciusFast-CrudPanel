<?php 
//CHECK SE INDIRIZZO IP AUTORIZZATO
if(!isset($ip_access_denied)){ 
?>

<div class="content-wrapper">
    
	<!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            <i class="fa fa-users"></i> 
			<a href="<?php echo site_url('user/rolesList') ?>">
			<u>Lista Ruoli</u>
			</a>
           <b style='font-size:20px'> >> </b><b style='font-size:20px'>Visualizza</b>
        </h1>
				
    </section>
	
    <section class="content">
        <div class="row">
            <!-- left column -->
            <div class="col-md-8">
			<div class="nav-tabs-custom">
				<ul class="nav nav-tabs">
				  <li class="active"><a href="#role" data-toggle="tab" aria-expanded="true">Descrizione Ruolo</a></li>
				  <li class=""><a href="#permission" data-toggle="tab" aria-expanded="false">Permessi</a></li>	  
				</ul>  
				<?php $this->load->helper("form"); ?>
				<form	 method="post">
					<div class="tab-content">
						<div class="tab-pane tab-margin active" id="role">			
							<div class="box-body">
								 <div class="row">
									 <div class="col-md-6">
										<div class="form-group">
											<label for="int">Ruolo</label>
											<div class="input-group">
												<div class="input-group-addon"><i class="fa fa-cubes"></i></div>								
													<input type="text" class="form-control" name="role" id="role" placeholder="Ruolo" value="<?php echo $role; ?>" disabled="disabled"  style='background-color:#EEEEEE' />
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
													
													if(isset($value->perm_read)){
														if($value->perm_read == 'Y'){
															echo "Lettura <input 
															type='checkbox' id='mod_id[]' name='mod_id[]'
															checked='checked' value='".$value->mod_id."|perm_read' class='perm_".$value->mod_id."' disabled='disabled' /> | ";	
														} else {
															$def_value = "";
															if(isset($value->mod_id)){
																$def_value = $value->mod_id;
															}
															echo "Lettura <input 
															id='mod_id[]' name='mod_id[]' type='checkbox' value='".$def_value."|perm_read' class='perm_".$def_value."' disabled='disabled' /> | ";	
														}													
													} else {
														$def_value = "";
														if(isset($value->mod_id)){
															$def_value = $value->mod_id;
														}
														echo "Lettura <input 
														id='mod_id[]' name='mod_id[]' type='checkbox' value='".$def_value."|perm_read' class='perm_".$def_value."' disabled='disabled' /> | ";	
													}	
													if(isset($value->perm_read)){
														if($value->perm_write == 'Y'){
															echo "Inserimento <input type='checkbox' checked='checked' id='mod_id[]' name='mod_id[]'
															value='".$value->mod_id."|perm_write' class='perm_".$value->mod_id."'  disabled='disabled' /> | ";
														} else {
															echo "Inserimento <input type='checkbox' 
															id='mod_id[]' name='mod_id[]' value='".$value->mod_id."|perm_write' class='perm_".$value->mod_id."' disabled='disabled'  /> | ";			
														}
													} else {
														$def_value = "";
														if(isset($value->mod_id)){
															$def_value = $value->mod_id;
														}														
														echo "Inserimento <input type='checkbox' 
														id='mod_id[]' name='mod_id[]' value='".$def_value."|perm_write' class='perm_".$def_value ."' disabled='disabled'  /> | ";			

													}	
													if(isset($value->perm_read)){
														if($value->perm_update == 'Y'){
															echo "Modifica <input type='checkbox' checked='checked' 
															id=mod_id[] name=mod_id[]
															value='".$value->mod_id."|perm_update' class='perm_".$value->mod_id."' disabled='disabled'  /> | ";		
														} else {
															echo "Modifica <input type='checkbox' 
															id=mod_id[] name=mod_id[] value='".$value->mod_id."|perm_update' class='perm_".$value->mod_id."' disabled='disabled'  /> | ";				
														}
													} else {
														$def_value = "";
														if(isset($value->mod_id)){
															$def_value = $value->mod_id;
														}															
														echo "Modifica <input type='checkbox' 
														id=mod_id[] name=mod_id[] value='".$def_value."|perm_update' class='perm_".$def_value."' disabled='disabled'  /> | ";				
													}	

													if(isset($value->perm_read)){
														if($value->perm_delete == 'Y'){
															echo "Cancellazione <input type='checkbox' checked='checked' 
															id=mod_id[] name=mod_id[] 
															value='".$value->mod_id."|perm_delete' class='perm_".$value->mod_id."' disabled='disabled'  />";
														} else {
															echo "Cancellazione <input type='checkbox' 
															id=mod_id[] name=mod_id[] 
															value='".$value->mod_id."|perm_delete' class='perm_".$value->mod_id."' disabled='disabled'  />";
														}
													} else {
														$def_value = "";
														if(isset($value->mod_id)){
															$def_value = $value->mod_id;
														}															
														echo "Cancellazione <input type='checkbox' 
														id=mod_id[] name=mod_id[] 
														value='".$def_value."|perm_delete' class='perm_".$def_value."' disabled='disabled'  />";
													}	

													
													foreach($value->child as $childKey => $childValue){
														echo "<div style='margin-left:10px'>";
														echo " --> <b style='color:#990000'>".$childValue->mod_title."</b><br>";
														if($childValue->perm_read == 'Y'){
															echo "Lettura <input 
															type='checkbox' id='mod_id[]' name='mod_id[]'
															checked='checked' value='".$childValue->mod_id."|perm_read' class='perm_".$childValue->mod_parentid."' disabled='disabled' /> | ";	
														} else {
															echo "Lettura <input 
															id='mod_id[]' name='mod_id[]' type='checkbox' value='".$childValue->mod_id."|perm_read' class='perm_".$childValue->mod_parentid."'  disabled='disabled' /> | ";	
														}
														
														if($childValue->perm_write == 'Y'){
															echo "Inserimento <input type='checkbox' checked='checked' id='mod_id[]' name='mod_id[]'
															value='".$childValue->mod_id."|perm_write' class='perm_".$childValue->mod_parentid."'disabled='disabled'  /> | ";
														} else {
															echo "Inserimento <input type='checkbox' 
															id='mod_id[]' name='mod_id[]' value='".$childValue->mod_id."|perm_write' class='perm_".$childValue->mod_parentid."' disabled='disabled'  /> | ";			
														}
														
														if($childValue->perm_update == 'Y'){
															echo "Modifica <input type='checkbox' checked='checked' 
															id=mod_id[] name=mod_id[]
															value='".$childValue->mod_id."|perm_update' class='perm_".$childValue->mod_parentid."' disabled='disabled'  /> | ";		
														} else {
															echo "Modifica <input type='checkbox' 
															id=mod_id[] name=mod_id[] value='".$childValue->mod_id."|perm_update' class='perm_".$childValue->mod_parentid."' disabled='disabled'  /> | ";				
														}
														
														if($childValue->perm_delete == 'Y'){
															echo "Cancellazione <input type='checkbox' checked='checked' 
															id=mod_id[] name=mod_id[] 
															value='".$childValue->mod_id."|perm_delete' class='perm_".$childValue->mod_parentid."' disabled='disabled'  />";
														} else {
															echo "Cancellazione <input type='checkbox' 
															id=mod_id[] name=mod_id[] 
															value='".$childValue->mod_id."|perm_delete' class='perm_".$childValue->mod_parentid."' disabled='disabled'  />";
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
						
						
                         <div class="box-footer">
						    <div class="row">
								<div class="col-md-12" >
									<a href="<?php echo site_url('user/rolesList') ?>" class="btn btn-default"><i class="fa fa-arrow-circle-left"></i> INDIETRO</a>
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
<?php } ?>