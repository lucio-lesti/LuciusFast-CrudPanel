<?php 
//CHECK SE INDIRIZZO IP AUTORIZZATO
if(!isset($ip_access_denied)){ 
?>
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <h1>
	<a style='cursor:pointer'>Logs >></a>		
    <i class="fa fa-archive"></i> Cronologia
	  
    </h1>
  </section>
  <section class="content">
    <div class="row">
      <div class="col-xs-12">
        <div class="box">
          <div class="box-header">
            <h3 class="box-title"> Dimensioni della tabella di registro:
              <?php
                  if(isset($dbinfo->total_size))
                  {
                    echo $dbinfo->total_size;
                  }
                  else
                  {
                    echo '0';
                  }
                  
                  ?>
                MB</h3>
            <div class="pull-right">
              <a class="btn btn-danger" href="<?php echo base_url(); ?>backupLogTable">Cancellazione Log</a>
            </div>
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
                      <th>Nome Utente</th>
                      <th>Processo</th>
                      <th>Funzione operativa</th>
                      <th>ID ruolo utente</th>
                      <th>Ruolo utente</th>
                      <th>Indirizzo IP</th>
                      <th>Browser</th>
                      <th>Browser Tutte le informazioni</th>
                      <th>Sistema Operativo</th>
                      <th>Data e ora</th>
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