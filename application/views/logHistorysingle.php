<?php 
//CHECK SE INDIRIZZO IP AUTORIZZATO
if(!isset($ip_access_denied)){ 
?>
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            <i class="fa fa-archive"></i> Cronologia Log
            <small>Cronologia log degli utenti</small>
        </h1>
    </section>
    <section class="content">
        <div class="row">
            <div class="col-xs-12">
                <div class="box">
                    <div class="box-header">
                        <h3 class="box-title">
                            <?= $userInfo->name." : ".$userInfo->email ?>
                        </h3>
                        <div class="box-tools">
                        </div>
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
                                <table width="100%" class="table table-striped table-bordered table-hover" id="dataTables-example">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>Username</th>
                                            <th>Processo</th>
                                            <th>Funzione operativa</th>
                                            <th>ID ruolo utente</th>
                                            <th>Ruolo utente</th>
                                            <th>Indirizzo IP</th>
                                            <th>Browser</th>
                                            <th>Browser Tutte le informazionir</th>
                                            <th>Sistema Operativo</th>
                                            <th>Data e Ora</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                      if(!empty($userRecords))
                      {
                          foreach($userRecords as $record)
                          {
                      ?>
                                            <tr>
                                                <td>
                                                    <?php echo $record->id ?>
                                                </td>
                                                <td>
                                                    <?php echo $record->username ?>
                                                </td>
                                                <td>
                                                    <?php echo $record->process ?>
                                                </td>
                                                <td>
                                                    <?php echo $record->processfunction ?>
                                                </td>
                                                <td>
                                                    <?php echo $record->userroleid ?>
                                                </td>
                                                <td>
                                                    <?php echo $record->userroletext ?>
                                                </td>
                                                <td>
                                                    <?php echo $record->userip ?>
                                                </td>
                                                <td>
                                                    <?php echo $record->useragent ?>
                                                </td>
                                                <td>
                                                    <?php echo $record->agentstring ?>
                                                </td>
                                                <td>
                                                    <?php echo $record->platform ?>
                                                </td>
                                                <td>
                                                    <?php echo $record->createddtm ?>
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