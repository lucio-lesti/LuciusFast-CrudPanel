<!-- INIZIO-->
	<div id="top_form"></div>
	<div class="content-wrapper">
	  <!-- Content Header (Page header) -->
	  <section class="content-header">
		<h1>
		  <i class="fa fa-cubes"></i> CRUD Generator   >> Impostazioni
					
		</h1>
		
	  </section>
	  <section class="content">
	<!-- INIZIO-->

	 
	<!--DIV BOX-->
	<div class="box">
			<div class="box-header">
			  <div class="box-tools">
			  </div>
			</div>
	<!--DIV BOX-->
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
                if($success){
            ?>
            <div class="alert alert-success alert-dismissable">
              <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
              <?php echo $this->session->flashdata('success'); ?>
            </div>
            <?php } ?>			
	
	
	
        <div class="row" style='padding:15px'>
            <div class="col-md-3">
                <?php echo $res; ?>
                <form action="settings" method="POST">

                    <div class="form-group">
                        <label>Target Folder</label>
                        <div class="row">
                            <?php $target = $get_setting->target; ?>
                            <div class="col-md-6">
                                <div class="radio" style="margin-bottom: 0px; margin-top: 0px">
                                    <label>
                                        <input type="radio" name="target" value="../application/" <?php echo $target == '../application/' ? 'checked' : ''; ?>>
                                        ../application/
                                    </label>
                                </div>                            
                            </div>
                            <div class="col-md-6">
                                <div class="radio" style="margin-bottom: 0px; margin-top: 0px">
                                    <label>
                                        <input type="radio" name="target" value="output/" <?php echo $target == 'output/' ? 'checked' : ''; ?>>
                                        output/
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <input type="submit" value="Save" name="save" class="btn btn-primary" />
                    <a href="index.php" class="btn btn-default">Back</a>
                </form>
            </div>
            <div class="col-md-4">

            </div>
        </div>
	
	
	
	
</div>		
<!--FINE-->
</section>
</div>
 