<?php 
//CHECK SE INDIRIZZO IP AUTORIZZATO
if(!isset($ip_access_denied)){ 
?>
<?php

//print'<pre>';print_r($all_mod_aggr_standalone);
?>
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <h1>
      Dashboard - <small>Avvisi</small>
    </h1>
  </section>

  <section class="content">
    <div class="row">

      <!-- ./col -->
      <!-- ./col -->	  
      
      <?php
        $auto_cert_gp_scaduti = $notifyListCount['auto_cert_gp_scaduti'];
        $cert_medici_scaduti = $notifyListCount['cert_medici_scaduti'];
        $pagam_allievi_scaduti = $notifyListCount['pagam_allievi_scaduti'];
        $auto_cert_gp_non_presenti = $notifyListCount['auto_cert_gp_non_presenti'];
        $cert_medici_non_presenti = $notifyListCount['cert_medici_non_presenti'];
        $tessere_assoc_finite = $notifyListCount['tessere_assoc_finite'];

        if($isAdmin == TRUE){
      ?>
        


      <div class="col-lg-3 col-xs-6">
        <!-- small box -->
        <div class="small-box bg-green-active">
          <div class="inner">
            <h3>
              <br>
            </h3>
            <p  style='font-size:18px'>Autocertificazioni Green Pass Scadute (<?php echo $auto_cert_gp_scaduti;?>)</p>
          </div>
          <div class="icon">
            <i class="fa fa-clock-o"></i>
          </div>
 
          <a style="cursor:pointer" onclick="showElementiNonPresenti('auto_cert_gp_scaduti')" class="small-box-footer">Vai
            <i class="fa fa-arrow-circle-right"></i>
          </a>  

        </div>
      </div>		  


      <div class="col-lg-3 col-xs-6">
        <!-- small box -->
        <div class="small-box bg-blue-active">
          <div class="inner">
            <h3>
              <br>
            </h3>
            <p  style='font-size:18px'>Certificati Medici Scaduti (<?php echo $cert_medici_scaduti;?>)</p>
          </div>
          <div class="icon">
            <i class="fa fa-clock-o"></i>
          </div>
          <a style="cursor:pointer" onclick="showElementiNonPresenti('cert_medici_scaduti')" class="small-box-footer">Vai
            <i class="fa fa-arrow-circle-right"></i>
          </a>   
        </div>
      </div>		  

    <!--
      <div class="col-lg-3 col-xs-6">
 
        <div class="small-box bg-maroon-active">
          <div class="inner">
            <h3>
              <br>
            </h3>
            <p  style='font-size:18px'>Pagamenti Allievi scaduti (<?php echo $pagam_allievi_scaduti;?>)</p>
          </div>
          <div class="icon">
            <i class="fa fa-clock-o"></i>
          </div>
 
          <a style="cursor:pointer" onclick="showElementiNonPresenti('pagam_allievi_scaduti')" class="small-box-footer">Vai
            <i class="fa fa-arrow-circle-right"></i>
          </a>          
        </div>
      </div>		  
        -->
      
 
      <div class="col-lg-3 col-xs-6">
        <!-- small box -->
        <div class="small-box bg-green-active">
          <div class="inner">
            <h3>
              <br>
            </h3>
            <p  style='font-size:18px'>Autocertificazioni Green Pass non presenti (<?php echo $auto_cert_gp_non_presenti;?>)</p>
          </div>
          <div class="icon">
            <i class="fa fa-clock-o"></i>
          </div>
          <a style="cursor:pointer" onclick="showElementiNonPresenti('auto_cert_gp_non_presenti')" class="small-box-footer">Vai
            <i class="fa fa-arrow-circle-right"></i>
          </a>
        </div>
      </div>		  

      

      <div class="col-lg-3 col-xs-6">
        <!-- small box -->
        <div class="small-box bg-blue-active">
          <div class="inner">
            <h3>
              <br>
            </h3>
            <p  style='font-size:18px'>Certificati Medici non presenti (<?php echo $cert_medici_non_presenti;?>)</p>
          </div>
          <div class="icon">
            <i class="fa fa-clock-o"></i>
          </div>
          <a style="cursor:pointer" onclick="showElementiNonPresenti('cert_medici_non_presenti')"  class="small-box-footer">Vai
            <i class="fa fa-arrow-circle-right"></i>
          </a>
        </div>
      </div>		  

      
 
      
      <div class="col-lg-3 col-xs-6">
        <!-- small box -->
        <div class="small-box bg-red-active">
          <div class="inner">
            <h3>
              <br>
            </h3>
            <p  style='font-size:18px'>Tessere assoc. finite per enti (<?php echo $tessere_assoc_finite;?>)</p>
          </div>
          <div class="icon">
            <i class="fa fa-clock-o"></i>
          </div>
          <a style="cursor:pointer" onclick="showElementiNonPresenti('tessere_assoc_finite')"  class="small-box-footer">Vai
            <i class="fa fa-arrow-circle-right"></i>
          </a>
        </div>
      </div>		  
  
                 
      <!-- ./col -->
      <?php } else { ?>
      <?php
          //print'<pre>';print_r($all_mod_aggr_standalone);
          foreach($all_mod_aggr_standalone as $key => $module){
              if($module['value_settings']['have_child'] == 'TRUE'){
                foreach($module['sub_mod'] as $keySubMod => $valueSubMod){
                  $css_class = "small-box bg-green";
                  if($valueSubMod['class_dashboard_area'] != ""){
                      $css_class = $valueSubMod['class_dashboard_area'];
                  }  
                  echo'<div class="col-lg-3 col-xs-6">
                    <!-- small box -->
                    <div class="'.$css_class.'">
                      <div class="inner">
                        <h3>
                          <br>
                        </h3>		  
                        <p  style=\'font-size:18px\'>'.$valueSubMod['mod_title'].'</p>
                      </div>
                      <div class="icon">
                        <i class="'.$valueSubMod['mod_icon'].'"></i>
                      </div>
                      <a href="'.base_url().''.$valueSubMod['class_name'].'" class="small-box-footer">Vai
                        <i class="fa fa-arrow-circle-right"></i>
                      </a>
                    </div>
                  </div>';	                  
                }      
              } else {
                  $css_class = "small-box bg-green";
                  if($module['class_dashboard_area'] != ""){
                      $css_class = $module['class_dashboard_area'];
                  }                    
                  echo'<div class="col-lg-3 col-xs-6">
                    <!-- small box -->
                    <div class="'.$css_class.'">
                      <div class="inner">
                        <h3>
                          <br>
                        </h3>		  
                        <p  style=\'font-size:18px\'>'.$module['mod_title'].'</p>
                      </div>
                      <div class="icon">
                        <i class="'.$module['mod_icon'].'"></i>
                      </div>
                      <a href="'.base_url().''.$module['class_name'].'" class="small-box-footer">Vai
                        <i class="fa fa-arrow-circle-right"></i>
                      </a>
                    </div>
                  </div>';
              }    
          }  
       ?>     
      <?php }?>
      <!-- ./col -->
    </div>
  </section>
</div>
<?php } ?>

 