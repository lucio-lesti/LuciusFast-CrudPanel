<?php 
//CHECK SE INDIRIZZO IP AUTORIZZATO
if(!isset($ip_access_denied)){ 
?>
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
			<a style='cursor:pointer'>Importa >></a>
            <i class="fa fa-upload"></i> Modulo
            <small>Installazione Modulo</small>
        </h1>
    </section>
    <section class="content">
        <div class="row">
            <div class="col-xs-12">
                <div class="box">
                    <div class="box-header">
                        <h3 class="box-title">Seleziona il tuo file .ZIP per il caricamento del Modulo</h3>
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
                                                    <div class="form-group">
                                                        <label for="file_mod">Seleziona file</label>
                                                        <input type="file" id="file_mod" name="file_mod">
                                                        <p class="help-block">File Ammessi (*.zip)</p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- /.box-body -->

                                    <div class="box-footer">
                                        <input type="submit" class="btn btn-primary" value="OK" />
                                        <input type="reset" class="btn btn-default" value="ANNULLA" />
                                    </div>
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
<?php } ?>