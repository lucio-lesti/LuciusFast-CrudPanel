<?php 
//CHECK SE INDIRIZZO IP AUTORIZZATO
if(!isset($ip_access_denied)){ 
?>
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
			<a style='cursor:pointer'>Impostazioni >></a>	
            <i class="fa fa-gears"></i> Gestione Moduli
        </h1>
    </section>
    <section class="content">
        <div class="row">
            <div class="col-xs-12">
                <div class="box">
                    <div class="box-header">
                        <h3 class="box-title">Attiva/Disattiva/Ordina Moduli</h3>
                        <div class="box-tools">
                        </div>
                    </div>
                    <!-- /.box-header -->
                    <div class="box-body table-responsive no-padding">
                        <div class="panel-body">
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
                                <form role="form" action="<?php echo base_url() ?>admin/installMod" method="post" id="import_mod" role="form" enctype="multipart/form-data"
                                    accept-charset="utf-8">
                                    <div class="box-body">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
													<ul id="list_field_sortable" name="list_field_sortable" class="list-group ui-sortable">
													<?php 
														//print'<pre>';print_r($allModInstalled);print'</pre>';
														foreach($allModInstalled as $key => $module){
															echo "<li class='list-group-item' moduloid='".$module->mod_id."' style='cursor:pointer;' onmouseover=\"this.style.backgroundColor='#DEDEDE';\" onmouseout=\"this.style.backgroundColor='';\"/>";
															if($module->active == 'Y'){
																echo"<a class=\"btn btn-sm btn-info lockMod\" onclick=\"activeDisactiveMod('".$module->mod_id."', 'disactive')\"
																	style='cursor:pointer' title=\"Abilita/Disabilita Modulo \" >";								
																echo"<i class=\"fa fa-unlock\"></i>";
															} else {
																echo"<a class=\"btn btn-sm btn-info lockMod\" onclick=\"activeDisactiveMod('".$module->mod_id."', 'active')\"
																	style='cursor:pointer' title=\"Abilita/Disabilita Modulo \" >";																
																echo"<i class=\"fa fa-lock\"></i>";
															}

															echo"</a> ";
															echo"<a class=\"btn btn-sm btn-danger deleteMod\" style='cursor:pointer;' 
																	title=\"Cancella Modulo\"  onclick=\"deleteEntry('".$module->mod_id."', 'admin','delete_mod')\" >
																<i class=\"fa fa-trash\"></i>
															</a> ";															
															/*
															echo "
															<ul id=\"list_field_sortable__\" name=\"list_field_sortable__\" class=\"list-group ui-sortable\">
															  <li><div>Subitem 1</div></li>
															  <li><div>Subitem 2</div></li>
															</ul>";		
															*/		
															echo "<span style='font-size:18px'> | <B>".$module->mod_title. "</B> | </span></li>";			
														}
													?>
													</ul>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- /.box-body -->

                                </form>

                        </div>
                    </div>
                    <!-- /.box-body -->
                </div>
                <!-- /.box -->
            </div>
        </div>
    </section>
</div>
<script>
$(".list-group").sortable({
	stop: function(e, ui) {
		changePositionMod();
	}
});

function activeDisactiveMod(mod_id, state){
	if ((typeof mod_id === 'undefined') || (mod_id == "")) {
		return false;
	}	
	
	if ((typeof state === 'undefined') || (state == "")) {
		action = 'disactive';
	}		
	
	if(state == 'active'){
		document.getElementById('mod_state').value = state;
		document.getElementById('dlg_msg_active_disactive').innerHTML = "Abilitare il modulo selezionato?";
	} else {
		document.getElementById('mod_state').value = state;
		document.getElementById('dlg_msg_active_disactive').innerHTML = "Disabilitare il modulo selezionato?";
	}
		
	document.getElementById('frm_active_disactive_mod').action = '<?php echo base_url(); ?>/admin/active_disactive_mod/' + mod_id + '/' + state;
	$("#modal-active-disactive").modal();		
}


function changePositionMod(){
	var arrayModId = new Array();
	var list = document.getElementById("list_field_sortable").getElementsByTagName("li");
	 
	for(var key in list){
		if(!isNaN(key)){
			arrayModId.push(list[key].getAttributeNode("moduloid").value);	
		} 
	}	
	
	
	$.ajax({
	  type: "POST",
	  url: "<?php echo base_url()?>admin/change_position_mod/",
	  data:{
		  mod_list:arrayModId.join(",")
	  },	
	  success: function(risposta){
		/*
		document.getElementById('msg_info').innerHTML = "<b>Moduli correttamente ri-posizionati</b>";
		$("#modal-info").modal();	
		*/	
	  },
	  error: function(){
		document.getElementById('msg_error').innerHTML = "<b>Errore Riposizionamento</b>";
		$("#modal-error").modal();	
	  }
	});	
 
	
}

</script>
<?php } ?>