<?php 
//CHECK SE INDIRIZZO IP AUTORIZZATO
if(!isset($ip_access_denied)){ 
?>

<!DOCTYPE html>
<html>

<head>
  <meta charset="UTF-8">
  <title>
    <?php echo $pageTitle; ?>
  </title>
  <meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>

  <!-- load all Font Awesome styles -->
  <link href="<?php echo base_url(); ?>assets/vendor/font_awesome_6.1.1/css/all.css" rel="stylesheet" />

  <!-- support v4 icon references/syntax -->
  <link href="<?php echo base_url(); ?>assets/vendor/font_awesome_6.1.1/css/v4-shims.css" rel="stylesheet" />

  <!-- Bootstrap 3.3.4 -->
  <link href="<?php echo base_url(); ?>assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
  <!-- FontAwesome 4.3.0 -->
  <!--
  <link href="<?php echo base_url(); ?>assets/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css" />
  -->
  <!--
 <link href="<?php echo base_url(); ?>assets/vendor/font_awesome_4.6.1/css/font-awesome.min.4.6.1.css" rel="stylesheet" />
-->
 <link href="<?php echo base_url(); ?>assets/vendor/font_awesome_6.1.1/css/fontawesome.min.css" rel="stylesheet" /> 

  
  <!-- Ionicons 2.0.0 -->
  <link href="<?php echo base_url(); ?>assets/vendor/dist/css/ionicons.min.css" rel="stylesheet" type="text/css" />
  <!-- Theme style -->
  <link href="<?php echo base_url(); ?>assets/vendor/dist/css/AdminLTE2.css" rel="stylesheet" type="text/css" />
  <!-- Datatables style -->
  <link rel="stylesheet" href="<?php echo base_url('assets/vendor/datatables/dataTables.bootstrap.css') ?>"/>
  <script src="<?php echo base_url(); ?>assets/vendor/jquery/jQuery-2.1.4.min.js"></script>
  <script src="<?php echo base_url(); ?>assets/vendor/jquery-ui/jquery-ui.js"></script>
  <link href="<?php echo base_url(); ?>assets/vendor/jquery-ui/themes/base/jquery-ui.css" rel="stylesheet" type="text/css" />
   <script src="<?php echo base_url(); ?>assets/vendor/jQuery-Mask/jquery.mask.min.js"></script>
  <script src="<?php echo base_url('assets/vendor/datatables/jquery.dataTables.js') ?>"></script>
  <script src="<?php echo base_url('assets/vendor/datatables/dataTables.bootstrap.js') ?>"></script>
	
	<!-- LIBRERIE DATETIME E DATETIMEPICKER-->	
	<script src="<?php echo base_url('assets/vendor/plugins/datepicker/bootstrap-datepicker.js') ?>"></script>	
	<script src="<?php echo base_url('assets/vendor/plugins/datepicker/locales/bootstrap-datepicker.it.js') ?>"></script>		
	<link href="<?php echo base_url(); ?>assets/vendor/plugins/datepicker/datepicker3.css" rel="stylesheet" type="text/css" />
 
 	<script src="<?php echo base_url('assets/vendor/plugins/datetimepicker/moment-with-locales.js') ?>"></script>	 
	<script src="<?php echo base_url('assets/vendor/plugins/datetimepicker/bootstrap-datetimepicker.min.js') ?>"></script>	 
 	
	<link href="<?php echo base_url(); ?>assets/vendor/plugins/datetimepicker/bootstrap-datetimepicker.min.css" rel="stylesheet" type="text/css" />	
	<!-- LIBRERIE DATETIME E DATETIMEPICKER-->	
	
	<!-- LIBRERIE Bootstrap-SELECT-->		
	<link rel="stylesheet" href="<?php echo base_url(); ?>assets/vendor/bootstrap-select/bootstrap-select.css">
	<script src="<?php echo base_url(); ?>assets/vendor/bootstrap-select/bootstrap-select.js"></script>


	<link rel="stylesheet" href="<?php echo base_url(); ?>assets/vendor/timepicker/jquery.timepicker.min.css">
	<script src="<?php echo base_url(); ?>assets/vendor/timepicker/jquery.timepicker.min.js"></script>


	<link rel="stylesheet" href="<?php echo base_url(); ?>assets/vendor/select2/select2.min.css">
	
	<!-- COMPATIBILITA IE -->
	<!--<script src="<?php echo base_url(); ?>assets/vendor/select2/mutation-observer.js"></script> -->
	
	<script src="<?php echo base_url(); ?>assets/vendor/select2/select2.min.js"></script>


	<!-- AdminLTE Skins. Choose a skin from the css/skins 
         folder instead of downloading all of them to reduce the load. -->
	<link href="<?php echo base_url(); ?>assets/vendor/dist/css/skins/_all-skins.min.css" rel="stylesheet" type="text/css" />
	<link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/layout_custom.css">
	
  <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
  <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
  <!--[if lt IE 9]>
        <script src="<?php echo base_url() ?>assets/js/html5shiv.min.js"></script>
        <script src="<?php echo base_url() ?>assets/js/respond.min.js"></script>
    <![endif]-->
	
<!-- FULLCALENDAR LIBRARY-->	
<link href='<?php echo base_url(); ?>assets/vendor/fullcalendar/packages/core/main.css' rel='stylesheet' />
<link href='<?php echo base_url(); ?>assets/vendor/fullcalendar/packages/daygrid/main.css' rel='stylesheet' />
<link href='<?php echo base_url(); ?>assets/vendor/fullcalendar/packages/timegrid/main.css' rel='stylesheet' />
<link href='<?php echo base_url(); ?>assets/vendor/fullcalendar/packages/list/main.css' rel='stylesheet' />
<script src='<?php echo base_url(); ?>assets/vendor/fullcalendar/packages/core/main.js'></script>
<script src='<?php echo base_url(); ?>assets/vendor/fullcalendar/packages/core/locales-all.js'></script>
<script src='<?php echo base_url(); ?>assets/vendor/fullcalendar/packages/interaction/main.js'></script>
<script src='<?php echo base_url(); ?>assets/vendor/fullcalendar/packages/daygrid/main.js'></script>
<script src='<?php echo base_url(); ?>assets/vendor/fullcalendar/packages/timegrid/main.js'></script>
<script src='<?php echo base_url(); ?>assets/vendor/fullcalendar/packages/list/main.js'></script>

<script src="<?php echo base_url(); ?>assets/vendor/bootstrap-combobox/js/bootstrap-combobox.js"></script>

<!-- IMPOSTO UNA VAR. BASE URL ANCHE PER JS-->
<script type="text/javascript">
    var baseURL = "<?php echo base_url(); ?>";
    var objAjaxConfig = {};
    objAjaxConfig.form =  {};
    objAjaxConfig.datatable =  {};
    objAjaxConfig.datatable.ordering = true;
    objAjaxConfig.mod_name = null;
    objAjaxConfig.mod_title = null;
</script>

<style>
input::-webkit-calendar-picker-indicator {
  display: none;/* remove default arrow */
}
.arrow_datalist:after {
    /*
    content: url("<?php echo base_url(); ?>/assets/images/arrow_datalist.png");
    margin-left: -20px; 
    padding: .1em;
    pointer-events:none;
    */
    padding: .0em;
}
</style>


<script type="text/javascript" src="<?php echo base_url('assets/js/webcam/webcamjs/webcam.min.js') ?>"></script>
<script type="text/javascript" src="<?php echo base_url('assets/js/common.js') ?>"></script>
</head>


<?php
$sidebarCollapse = '';
if($sidebar_fixed == 'S'){
	$sidebarCollapse = 'sidebar-collapse';
}

if($skin_color == ''){
	$skin_color = 'skin-blue';
}
?>

<body class="<?php echo $skin_color;?> sidebar-mini <?php echo $sidebarCollapse;?>">
  <div class="wrapper">

    <header class="main-header">
      <!-- Logo -->
      <a href="<?php echo base_url(); ?>" class="logo">
        <!-- mini logo for sidebar mini 50x50 pixels -->
        <span class="logo-mini">
          <b>LM</b></span>
        <!-- logo for regular state and mobile devices -->
        <span class="logo-lg">
          <b>LM</b>-Panel V1.0</span>
      </a>
      <!-- Header Navbar: style can be found in header.less -->
      <nav class="navbar navbar-static-top" role="navigation">
        <!-- Sidebar toggle button-->
        <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
          <span class="sr-only">Toggle navigation</span>
        </a>
		
        <div class="navbar-custom-menu">
          <ul class="nav navbar-nav">


		<?php
			$notification_privilege = TRUE;
			
			foreach($all_mod_aggr_standalone as $key => $module){
				if($module['value_settings']['main_mod_title'] == 'Notifiche'){
					$notification_privilege = TRUE;
				}	
			}	
      /*
			if($isAdmin == TRUE){
				$notification_privilege = TRUE;
			}	
			*/
		?>
		<!-- NOTIFICHE--->		
		
		<?php 
      //LE NASCONDO A TUTTI, ANCHE AGLI ADMIN
      //SE LA SI VUOLE RIATTIVARE BASTA COMMENTARE O METTERE A TRUE "$notification_privilege"
      $notification_privilege = FALSE;
			if($notification_privilege == TRUE){
		?>
		<li class="dropdown notifications-menu" >
            <a href="#" class="dropdown-toggle" data-toggle="dropdown"  
              onmouseover="this.style.backgroundColor='#cccccc';" 
                onmouseout="this.style.backgroundColor='#3c8dbc';" style="background-color:#3c8dbc">>
              <i class="fa fa-bell-o"></i>
              <span class="label label-warning"> <?php echo count($notifyList);?></span>
            </a>
            <ul class="dropdown-menu" style="width:500px">
              <li class="header">Hai <?php echo count($notifyList);?> notifiche</li>
              <li>
                <!-- inner menu: contains the actual data -->
                <ul class="menu">
                  <?php
                  	foreach($notifyList as $key => $value){
                      if($value['id'] != NULL){
                        $url = base_url()."".$value['mod_name']."/?id=".$value['id'];
                      } else {
                        $url = "#";
                      }
                      
                      echo"<li>
                          <a href='".$url."'>
                          <i class='fa ".$value['icona_notifica']."'></i>[".$value['msg_notifica']."]
                          </a>
                        </li>";
                      
                    }
                  ?>
                </ul>
              </li>
			  </ul>
        </li>
		<!-- NOTIFICHE-->		  
		<? } ?> 
        
			<li class="dropdown tasks-menu">
              <a href="#" class="dropdown-toggle" data-toggle="dropdown" aria-expanded="true" 
                onmouseover="this.style.backgroundColor='#cccccc';" 
                onmouseout="this.style.backgroundColor='#3c8dbc';" style="background-color:#3c8dbc">
                <i class="fa fa-history"></i>
              </a>
              <ul class="dropdown-menu">
                <li class="header"> Ultimo accesso :
                  <i class="fa fa-clock-o"></i>
                  <?= empty($last_login) ? "Primo accesso" : $last_login; ?>
                </li>
              </ul>
            </li>
            <!-- User Account: style can be found in dropdown.less -->
            <li class="dropdown user user-menu">
              <a href="#" class="dropdown-toggle" data-toggle="dropdown" 
                onmouseover="this.style.backgroundColor='#cccccc';" 
                onmouseout="this.style.backgroundColor='#3c8dbc';" style="background-color:#3c8dbc">
                <img src="<?php echo base_url(); ?>assets/vendor/dist/img/avatar.png" class="user-image" alt="User Image" />
                <span class="hidden-xs">
                  <?php echo $name; ?>
                </span>
              </a>
              <ul class="dropdown-menu">
			  
                <!-- User image -->
                <li class="user-header">
                  <img src="<?php echo base_url(); ?>assets/vendor/dist/img/avatar.png" class="img-circle" alt="User Image" />
                  <p>
                    <?php echo $name; ?>
                    <small>
                      <?php echo $role_text; ?>
                    </small>
                  </p>
                </li>
                <!-- Menu Footer-->
                <li class="user-footer">
                  <div class="pull-left">
                    <a href="<?php echo base_url(); ?>userEdit" class="btn btn-default btn-flat">
                      <i class="fa fa-key"></i> Edita Account </a>
                  </div>
                  <div class="pull-right">
                    <a href="<?php echo base_url(); ?>logout" class="btn btn-default btn-flat">
                      <i class="fa fa-sign-out"></i> Esci</a>
                  </div>
                </li>
              </ul>
            </li>
          </ul>
		  
        </div>
      </nav>
    </header>
	
    <!-- Left side column. contains the logo and sidebar -->
	
    <aside class="main-sidebar">
      <!-- sidebar: style can be found in sidebar.less -->
      <section class="sidebar">
        <!-- sidebar menu: : style can be found in sidebar.less -->
        
		<ul class="sidebar-menu">
          <li class="header">
          </li>
		
		<div class="user-panel">
            <div class="logo-lg">	
			
			<?php 
				if($company_logo != ""){
			?>
			<img style='width:150px;border-radius: 10%;'
				src="<?php echo base_url(); ?>/uploads/logo/<?php echo $company_logo;?>"  alt="<?php echo $company_name;?>">
			<?php } ?> 
			 <p style='color:#FFF;font-size:18px'>
				<?php 
					if(strlen($company_name) > 18){
						$company_name = substr($company_name, 0, 18) . '...';	
					}
					echo $company_name;
				?></p>
			</div>

        </div>		  
		  
          <li class="treeview">
            <a href="<?php echo base_url(); ?>dashboard">
              <i class="fa fa-dashboard"></i>
              <span>Home</span>
              </i>
            </a>
          </li>

			<?php

				
        //print'<pre>';print_r($all_mod_aggr_standalone );die();
				//IL MENU AL MOMENTO E' GESTITO SOLO FINO AL 2° LIVELLO
				foreach($all_mod_aggr_standalone as $key => $module){
					//VERIFICO SE IL MODULO HA SOTTO-MODULI
					if($module['value_settings']['have_child'] == 'TRUE'){
						echo "<li class=\"treeview\">";
						echo "<a href='#'>
								<i class='".$module['value_settings']['main_mod_icon']."'></i>
								<span>".$module['value_settings']['main_mod_title']."</span>
							   <span class=\"pull-right-container\">
							   <i class=\"fa fa-angle-left pull-right\"></i>
							   </span>								
							  </a>"; 	
						echo "<ul class=\"treeview-menu\">";		
						foreach($module['sub_mod'] as $keySubMod => $valueSubMod){
							echo "<li class=\"treeview\">
								  <a href='".base_url().$valueSubMod['class_name']."'>
									<i class='".$valueSubMod['mod_icon']."'></i>
									<span>".$valueSubMod['mod_title']."</span>
								  </a>
								</li>"; 							
						}
						echo "</ul>";
						echo "</li>";	
					} else {
						echo "<li class=\"treeview\">
							  <a href='".base_url().$module['value_settings']['class_name']."'>
								<i class='".$module['value_settings']['mod_icon']."'></i>
								<span>".$module['value_settings']['mod_title']."</span>
							  </a>
							</li>"; 					
					}

				}				
				
			?>				  
		  
		 
			<?php
				if(is_dir(APPPATH."/modules/mod_crud_gen") && ($isAdmin == TRUE)){
			?>			
			<li class="treeview">
			   <a href="#">
			   <i class="fa fa-cube"></i> <span>Crud Generator</span>
			   <span class="pull-right-container">
			   <i class="fa fa-angle-left pull-right"></i>
			   </span>
			   </a>
			   <ul class="treeview-menu">
				  <!--<li><a href="<?php echo base_url(); ?>mod_crud_gen/settings"><i class="fa fa-angle-double-right"></i> <span>Impostazioni</span></a></li>-->	
				  <li><a href="<?php echo base_url(); ?>mod_crud_gen/index"><i class="fa fa-angle-double-right"></i> <span>Genera Moduli</span></a></li>						  
			   </ul>
			</li>
				<?php }?>
			
        </ul>
		
		
      </section>
      <!-- /.sidebar -->
    </aside>

<?php } ?>	
