<?php 
//CHECK SE INDIRIZZO IP AUTORIZZATO
if(!isset($ip_access_denied)){ 
?>
<!DOCTYPE html>
<html>

<head>
  <meta charset="UTF-8">
  <title>LL-Crud-Panel v 1.1</title>
  <meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>
  <link href="<?php echo base_url(); ?>assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
  <link href="<?php echo base_url(); ?>assets/vendor/font_awesome_4.6.1/css/font-awesome.min.4.6.1.css" rel="stylesheet" /> 
  <link href="<?php echo base_url(); ?>assets/vendor/dist/css/AdminLTE.min.css" rel="stylesheet" type="text/css" />

  <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
  <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
  <!--[if lt IE 9]>
        <script src="<?php echo base_url() ?>assets/js/html5shiv.min.js"></script>
        <script src="<?php echo base_url() ?>assets/js/respond.min.js"></script>
    <![endif]-->
</head>
<body class="login-page" style='background-image:url("<?php echo base_url() ?>uploads/logo/<?php echo $settings[0]->logo_splash;?>");
			background-repeat:no-repeat;background-position: center;background-size: cover;'>
 
  <div class="login-box">

    <!-- /.login-logo -->
    <div class="login-box-body" style="border:1px solid #aaaaaa;border-radius:5px">
    <div class="login-logo">
      <b>LL-Crud-Panel v 1.1</b>
    </div>    
      <p class="login-box-msg">Login</p>
      <?php $this->load->helper('form'); ?>
      <div class="row">
        <div class="col-md-12">
          <?php echo validation_errors('<div class="alert alert-danger alert-dismissable">', ' <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button></div>'); ?>
        </div>
      </div>
      <?php
        $this->load->helper('form');
        $error = $this->session->flashdata('error');
        if($error)
        {
            ?>
        <div class="alert alert-danger alert-dismissable">
          <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
          <?php echo $error; ?>
        </div>
        <?php }
        $success = $this->session->flashdata('success');
        if($success)
        {
            ?>
        <div class="alert alert-success alert-dismissable">
          <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
          <?php echo $success; ?>
        </div>
        <?php } ?>

        <form action="<?php echo base_url(); ?>loginMe" method="post">
          <div class="form-group has-feedback">
            <input type="email" class="form-control" placeholder="Email" name="email" required />
            <span class="glyphicon glyphicon-envelope form-control-feedback"></span>
          </div>
          <div class="form-group has-feedback">
            <input type="password" class="form-control" placeholder="password" name="password" required />
            <span class="glyphicon glyphicon-lock form-control-feedback"></span>
          </div>
          <div class="row">
            <div class="col-xs-8">
              <!-- <div class="checkbox icheck">
                <label>
                  <input type="checkbox"> Remember Me
                </label>
              </div>  -->
            </div>
            <!-- /.col -->
            <div class="col-xs-4">
              <input type="submit" class="btn btn-primary btn-block btn-flat" value="Accedi" />
            </div>
            <!-- /.col -->
          </div>
        </form>
        <br>

    </div>
    <!-- /.login-box-body -->
  </div>
  <!-- /.login-box -->

  <script src="<?php echo base_url(); ?>assets/vendor/jquery/jQuery-2.1.4.min.js"></script>
  <script src="<?php echo base_url(); ?>assets/vendor/bootstrap/js/bootstrap.min.js" type="text/javascript"></script>
</body>

</html>
<?php } ?>