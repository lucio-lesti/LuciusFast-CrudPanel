<?php 
//CHECK SE INDIRIZZO IP AUTORIZZATO
if(!isset($ip_access_denied)){ 
?>
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
			<a style='cursor:pointer'>Utenti >></a>	
            <i class="fa fa-users"></i> Lista Ruoli
         <a class="btn btn-primary" href="<?php echo base_url(); ?>user/addRole">
          <i class="fa fa-plus"></i> Nuovo
		</a>	
		
		<button class="btn btn-sm btn-danger" disabled="disabled" id="btDeleteMass" name="btDeleteMass" 
			onclick='deleteMassiveEntry("entry_list", "check_id", "user","deleteMassiveRole")'>
		  <i class="fa fa-trash"></i> Cancellazione Massiva
		</button>			
        </h1>
    </section>
    <section class="content">
 
        <div class="row">
            <div class="col-xs-12">
                <div class="box">
                    <div class="box-header">
                        <h3 class="box-title">Lista Ruoli</h3>
                                    </div>
                      <!-- /.box-header -->
          <div class="box-body table-responsive no-padding">
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
              <div class="panel-body">
                <table width="100%" class="table table-striped table-bordered table-hover" 
						id="dataTables-example">
                  <thead>
                        <tr>
							<th width="80px" class="no-sort"><input type="checkbox" id="check_master" name="check_master" onchange="selezionaDeselezionaTutti('check_master','check_id','btDeleteMass')" /></th>
                            <th style="width:70%">Ruolo</th>
                            <th>Azioni</th>
                        </tr>
                  </thead>
                  <tbody>
					<?php
                    if(!empty($roleRecords))
                    {
                        foreach($roleRecords as $record)
                        {
                    ?>
                            <tr>
								<td>
								<?php
									if($record->isdeletable != 'N'){
								?>	
									<input type="checkbox" id="check_id" name="check_id" value="<?php echo $record->roleid; ?>" onchange='verificaNrCheckBoxSelezionati("check_id","btDeleteMass")'>
								<?php } else {?>
									<?php echo $record->roleid; ?>							
								<?php }?>
								</td>
								<td>
                                    <?php echo $record->role ?>
                                </td>
                                <td class="text-center">
                                    <a class="btn btn-sm btn-primary" href="<?= base_url().'user/readRole/'.$record->roleid; ?>" title="Visualizza">
                                        <i class="fa fa-book"></i>
                                    </a>  
									<?php
										if($record->isupdatable != 'N'){
									?>										
                                    <a class="btn btn-sm btn-info" href="<?php echo base_url().'user/editRole/'.$record->roleid; ?>" title="Modifica">
                                        <i class="fa fa-pencil"></i>
                                    </a>
									<?php }?>	
									<?php
										if($record->isdeletable != 'N'){
									?>		
									<a class="btn btn-sm btn-danger" style='cursor:pointer' title="Cancella" onclick='deleteEntry("<?php echo $record->roleid;?>", "user","deleteRole")'>
									  <i class="fa fa-trash"></i>
									</a>
									<?php }?>	
                                </td>
                            </tr>
                            <?php
                        }
                    }
                    ?>
                  </tbody>
                        </table>
              </div>
                    </div>
                    <!-- /.box-body -->
                </div>
                <!-- /.box -->
            </div>
        </div>
    </section>
</div>
 
<?php } ?>