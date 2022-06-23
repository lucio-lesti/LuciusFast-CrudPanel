<!-- INIZIO-->
	<div id="top_form"></div>
	<div class="content-wrapper">
	  <!-- Content Header (Page header) -->
	  <section class="content-header">
		<h1>
		  <i class="fa fa-cubes"></i> CRUD Generator   >> Genera Moduli
					
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
            <div class="col-md-12">
                <form action="process" method="POST" name="frm_gen_mod" id="frm_gen_mod">

                    <div class="form-group">
						<!-- <a href='http://87.106.193.209/phpmyadmin/index.php?route=/database/structure&server=1&db=bailadance' target="_blank"><b><u>Apri PHPMyAdmin</u></b> (Per creazione e Modifica Tabelle)</a> 
						<br><br>
						-->
						
                        <label>Seleziona Tabella Master - <a href="<?php echo $_SERVER['PHP_SELF'] ?>">Refresh</a></label>
                        <select id="table_name" name="table_name" class="form-control" 
						onchange="setname();callAjax('get_list_column','master')">
                            <option value="">Seleziona</option>
                            <?php
                            $table_list_selected = isset($_POST['table_name']) ? $_POST['table_name'] : '';
                            foreach ($table_list as $table) {
								echo"<option value='".$table['TABLE_NAME']."'>".$table['TABLE_NAME']."</option>";
                            }
                            ?>
                        </select>
                    </div>
					<div class="form-group">
							<input type="hidden" name="form_ajax" id="form_ajax" value="Y" />
 				
					</DIV>
 
                     <div class="form-group">
                            <label>
                            Genera Modulo Stampa
                            </label>	
							<select id="form_print" name="form_print" onchange="enableDisableCmbPrintHead(this.value)">
								<option VALUE="Y">SI</option>
								<option VALUE="N">NO</option>
							</select>
							
                            <label>
                            Genera Instestazione per Modulo Stampa
                            </label>	
							<select id="form_print_head" name="form_print_head">
								<option VALUE="Y">SI</option>
								<option VALUE="N">NO</option>
							</select>							
                    </div>  
 
 
                    <div class="form-group">
						<label>
                             Tipo Modulo: 
                        </label>
						<br/>
						<select name="mod_type" class="form-control"
								id="mod_type" onchange='enableDisableTabAccordion(this.value)'>
							<option value="crud">CRUD</option>
							<option value="report">REPORT</option>
						</select>
 
                    </div>   					

                    <div class="form-group">
						<label>
                             Titolo Modulo: 
                        </label>
						<br/>
						<input type='text' name='mod_title' id='mod_title' class="form-control" />
 
                    </div>   					
					


                    <div class="form-group">
						<label>
                             Form Master details (aggiungere tabelle con codice JSON): 
                        </label>
						<br/>
						<textarea class="form-control" name='json_master_details' id='json_master_details'>
						</textarea>
 
                    </div>   	


					<div class="form-group">
						<label>
                            Aggiungi Allegati: 
                        </label>					
						<br/>
						<select name="form_allegati" class="form-control"
								id="form_allegati" >
							<option value="Y">SI</option>
							<option value="N">NO</option>
						</select>					
					</DIV>

					<div class="form-group">
						<label>
                             Icona Modulo: 
                        </label>
			 			<br/>
						  <select style="font-family: 'FontAwesome', 'sans-serif'" 
								class="form-control" name='mod_icon' id='mod_icon' >
							<option value="fa-align-left">&#xf036; fa-align-left</option>
							<option value="fa-align-right">&#xf038; fa-align-right</option>
							<option value="fa-amazon">&#xf270; fa-amazon</option>
							<option value="fa-ambulance">&#xf0f9; fa-ambulance</option>
							<option value="fa-anchor">&#xf13d; fa-anchor</option>
							<option value="fa-android">&#xf17b; fa-android</option>
							<option value="fa-angellist">&#xf209; fa-angellist</option>
							<option value="fa-angle-double-down">&#xf103; fa-angle-double-down</option>
							<option value="fa-angle-double-left">&#xf100; fa-angle-double-left</option>
							<option value="fa-angle-double-right">&#xf101; fa-angle-double-right</option>
							<option value="fa-angle-double-up">&#xf102; fa-angle-double-up</option>

							<option value="fa-angle-left">&#xf104; fa-angle-left</option>
							<option value="fa-angle-right">&#xf105; fa-angle-right</option>
							<option value="fa-angle-up">&#xf106; fa-angle-up</option>
							<option value="fa-apple">&#xf179; fa-apple</option>
							<option value="fa-archive">&#xf187; fa-archive</option>
							<option value="fa-area-chart">&#xf1fe; fa-area-chart</option>
							<option value="fa-arrow-circle-down">&#xf0ab; fa-arrow-circle-down</option>
							<option value="fa-arrow-circle-left">&#xf0a8; fa-arrow-circle-left</option>
							<option value="fa-arrow-circle-o-down">&#xf01a; fa-arrow-circle-o-down</option>
							<option value="fa-arrow-circle-o-left">&#xf190; fa-arrow-circle-o-left</option>
							<option value="fa-arrow-circle-o-right">&#xf18e; fa-arrow-circle-o-right</option>
							<option value="fa-arrow-circle-o-up">&#xf01b; fa-arrow-circle-o-up</option>
							<option value="fa-arrow-circle-right">&#xf0a9; fa-arrow-circle-right</option>
							<option value="fa-arrow-circle-up">&#xf0aa; fa-arrow-circle-up</option>
							<option value="fa-arrow-down">&#xf063; fa-arrow-down</option>
							<option value="fa-arrow-left">&#xf060; fa-arrow-left</option>
							<option value="fa-arrow-right">&#xf061; fa-arrow-right</option>
							<option value="fa-arrow-up">&#xf062; fa-arrow-up</option>
							<option value="fa-arrows">&#xf047; fa-arrows</option>
							<option value="fa-arrows-alt">&#xf0b2; fa-arrows-alt</option>
							<option value="fa-arrows-h">&#xf07e; fa-arrows-h</option>
							<option value="fa-arrows-v">&#xf07d; fa-arrows-v</option>
							<option value="fa-asterisk">&#xf069; fa-asterisk</option>
							<option value="fa-at">&#xf1fa; fa-at</option>
							<option value="fa-automobile">&#xf1b9; fa-automobile</option>
							<option value="fa-backward">&#xf04a; fa-backward</option>
							<option value="fa-balance-scale">&#xf24e; fa-balance-scale</option>
							<option value="fa-ban">&#xf05e; fa-ban</option>
							<option value="fa-bank">&#xf19c; fa-bank</option>
							<option value="fa-bar-chart">&#xf080; fa-bar-chart</option>
							<option value="fa-bar-chart-o">&#xf080; fa-bar-chart-o</option>

							<option value="fa-battery-full">&#xf240; fa-battery-full</option>
							n value="fa-beer">&#xf0fc; fa-beer</option>
							<option value="fa-behance">&#xf1b4; fa-behance</option>
							<option value="fa-behance-square">&#xf1b5; fa-behance-square</option>
							<option value="fa-bell">&#xf0f3; fa-bell</option>
							<option value="fa-bell-o">&#xf0a2; fa-bell-o</option>
							<option value="fa-bell-slash">&#xf1f6; fa-bell-slash</option>
							<option value="fa-bell-slash-o">&#xf1f7; fa-bell-slash-o</option>
							<option value="fa-bicycle">&#xf206; fa-bicycle</option>
							<option value="fa-binoculars">&#xf1e5; fa-binoculars</option>
							<option value="fa-birthday-cake">&#xf1fd; fa-birthday-cake</option>
							<option value="fa-bitbucket">&#xf171; fa-bitbucket</option>
							<option value="fa-bitbucket-square">&#xf172; fa-bitbucket-square</option>
							<option value="fa-bitcoin">&#xf15a; fa-bitcoin</option>
							<option value="fa-black-tie">&#xf27e; fa-black-tie</option>
							<option value="fa-bold">&#xf032; fa-bold</option>
							<option value="fa-bolt">&#xf0e7; fa-bolt</option>
							<option value="fa-bomb">&#xf1e2; fa-bomb</option>
							<option value="fa-book">&#xf02d; fa-book</option>
							<option value="fa-bookmark">&#xf02e; fa-bookmark</option>
							<option value="fa-bookmark-o">&#xf097; fa-bookmark-o</option>
							<option value="fa-briefcase">&#xf0b1; fa-briefcase</option>
							<option value="fa-btc">&#xf15a; fa-btc</option>
							<option value="fa-bug">&#xf188; fa-bug</option>
							<option value="fa-building">&#xf1ad; fa-building</option>
							<option value="fa-building-o">&#xf0f7; fa-building-o</option>
							<option value="fa-bullhorn">&#xf0a1; fa-bullhorn</option>
							<option value="fa-bullseye">&#xf140; fa-bullseye</option>
							<option value="fa-bus">&#xf207; fa-bus</option>
							<option value="fa-cab">&#xf1ba; fa-cab</option>
							<option value="fa-calendar">&#xf073; fa-calendar</option>
							<option value="fa-camera">&#xf030; fa-camera</option>
							<option value="fa-car">&#xf1b9; fa-car</option>
							<option value="fa-caret-up">&#xf0d8; fa-caret-up</option>
							<option value="fa-cart-plus">&#xf217; fa-cart-plus</option>
							<option value="fa-cc">&#xf20a; fa-cc</option>
							<option value="fa-cc-amex">&#xf1f3; fa-cc-amex</option>
							<option value="fa-cc-jcb">&#xf24b; fa-cc-jcb</option>
							<option value="fa-cc-paypal">&#xf1f4; fa-cc-paypal</option>
							<option value="fa-cc-stripe">&#xf1f5; fa-cc-stripe</option>
							<option value="fa-cc-visa">&#xf1f0; fa-cc-visa</option>
							<option value="fa-chain">&#xf0c1; fa-chain</option>
							<option value="fa-check">&#xf00c; fa-check</option>
							<option value="fa-chevron-left">&#xf053; fa-chevron-left</option>
							<option value="fa-chevron-right">&#xf054; fa-chevron-right</option>
							<option value="fa-chevron-up">&#xf077; fa-chevron-up</option>
							<option value="fa-child">&#xf1ae; fa-child</option>
							<option value="fa-chrome">&#xf268; fa-chrome</option>
							<option value="fa-circle">&#xf111; fa-circle</option>
							<option value="fa-circle-o">&#xf10c; fa-circle-o</option>
							<option value="fa-circle-o-notch">&#xf1ce; fa-circle-o-notch</option>
							<option value="fa-circle-thin">&#xf1db; fa-circle-thin</option>
							<option value="fa-clipboard">&#xf0ea; fa-clipboard</option>
							<option value="fa-clock-o">&#xf017; fa-clock-o</option>
							<option value="fa-clone">&#xf24d; fa-clone</option>
							<option value="fa-close">&#xf00d; fa-close</option>
							<option value="fa-cloud">&#xf0c2; fa-cloud</option>
							<option value="fa-cloud-download">&#xf0ed; fa-cloud-download</option>
							<option value="fa-cloud-upload">&#xf0ee; fa-cloud-upload</option>
							<option value="fa-cny">&#xf157; fa-cny</option>
							<option value="fa-code">&#xf121; fa-code</option>
							<option value="fa-code-fork">&#xf126; fa-code-fork</option>
							<option value="fa-codepen">&#xf1cb; fa-codepen</option>
							<option value="fa-coffee">&#xf0f4; fa-coffee</option>
							<option value="fa-cog">&#xf013; fa-cog</option>
							<option value="fa-cogs">&#xf085; fa-cogs</option>
							<option value="fa-columns">&#xf0db; fa-columns</option>
							<option value="fa-comment">&#xf075; fa-comment</option>
							<option value="fa-comment-o">&#xf0e5; fa-comment-o</option>
							<option value="fa-commenting">&#xf27a; fa-commenting</option>
							<option value="fa-commenting-o">&#xf27b; fa-commenting-o</option>
							<option value="fa-comments">&#xf086; fa-comments</option>
							<option value="fa-comments-o">&#xf0e6; fa-comments-o</option>
							<option value="fa-compass">&#xf14e; fa-compass</option>
							<option value="fa-compress">&#xf066; fa-compress</option>
							<option value="fa-connectdevelop">&#xf20e; fa-connectdevelop</option>
							<option value="fa-contao">&#xf26d; fa-contao</option>
							<option value="fa-copy">&#xf0c5; fa-copy</option>
							<option value="fa-copyright">&#xf1f9; fa-copyright</option>
							<option value="fa-creative-commons">&#xf25e; fa-creative-commons</option>
							<option value="fa-credit-card">&#xf09d; fa-credit-card</option>
							<option value="fa-crop">&#xf125; fa-crop</option>
							<option value="fa-crosshairs">&#xf05b; fa-crosshairs</option>
							<option value="fa-css3">&#xf13c; fa-css3</option>
							<option value="fa-cube">&#xf1b2; fa-cube</option>
							<option value="fa-cubes" SELECTED>&#xf1b3; fa-cubes</option>
							<option value="fa-cut">&#xf0c4; fa-cut</option>
							<option value="fa-cutlery">&#xf0f5; fa-cutlery</option>
							<option value="fa-dashboard">&#xf0e4; fa-dashboard</option>
							<option value="fa-dashcube">&#xf210; fa-dashcube</option>
							<option value="fa-database">&#xf1c0; fa-database</option>
							<option value="fa-dedent">&#xf03b; fa-dedent</option>
							<option value="fa-delicious">&#xf1a5; fa-delicious</option>
							<option value="fa-desktop">&#xf108; fa-desktop</option>
							<option value="fa-deviantart">&#xf1bd; fa-deviantart</option>
							<option value="fa-diamond">&#xf219; fa-diamond</option>
							<option value="fa-digg">&#xf1a6; fa-digg</option>
							<option value="fa-dollar">&#xf155; fa-dollar</option>
							<option value="fa-download">&#xf019; fa-download</option>
							<option value="fa-dribbble">&#xf17d; fa-dribbble</option>
							<option value="fa-dropbox">&#xf16b; fa-dropbox</option>
							<option value="fa-drupal">&#xf1a9; fa-drupal</option>
							<option value="fa-edit">&#xf044; fa-edit</option>
							<option value="fa-eject">&#xf052; fa-eject</option>
							<option value="fa-ellipsis-h">&#xf141; fa-ellipsis-h</option>
							<option value="fa-ellipsis-v">&#xf142; fa-ellipsis-v</option>
							<option value="fa-empire">&#xf1d1; fa-empire</option>
							<option value="fa-envelope">&#xf0e0; fa-envelope</option>
							<option value="fa-envelope-o">&#xf003; fa-envelope-o</option>
							<option value="fa-eur">&#xf153; fa-eur</option>
							<option value="fa-euro">&#xf153; fa-euro</option>
							<option value="fa-exchange">&#xf0ec; fa-exchange</option>
							<option value="fa-exclamation">&#xf12a; fa-exclamation</option>
							<option value="fa-exclamation-circle">&#xf06a; fa-exclamation-circle</option>
							<option value="fa-exclamation-triangle">&#xf071; fa-exclamation-triangle</option>
							<option value="fa-expand">&#xf065; fa-expand</option>
							<option value="fa-expeditedssl">&#xf23e; fa-expeditedssl</option>
							<option value="fa-external-link">&#xf08e; fa-external-link</option>
							<option value="fa-external-link-square">&#xf14c; fa-external-link-square</option>
							<option value="fa-eye">&#xf06e; fa-eye</option>
							<option value="fa-eye-slash">&#xf070; fa-eye-slash</option>
							<option value="fa-eyedropper">&#xf1fb; fa-eyedropper</option>
							<option value="fa-facebook">&#xf09a; fa-facebook</option>
							<option value="fa-facebook-f">&#xf09a; fa-facebook-f</option>
							<option value="fa-facebook-official">&#xf230; fa-facebook-official</option>
							<option value="fa-facebook-square">&#xf082; fa-facebook-square</option>
							<option value="fa-fast-backward">&#xf049; fa-fast-backward</option>
							<option value="fa-fast-forward">&#xf050; fa-fast-forward</option>
							<option value="fa-fax">&#xf1ac; fa-fax</option>
							<option value="fa-feed">&#xf09e; fa-feed</option>
							<option value="fa-female">&#xf182; fa-female</option>
							<option value="fa-fighter-jet">&#xf0fb; fa-fighter-jet</option>
							<option value="fa-file">&#xf15b; fa-file</option>
							<option value="fa-file-archive-o">&#xf1c6; fa-file-archive-o</option>
							<option value="fa-file-audio-o">&#xf1c7; fa-file-audio-o</option>
							<option value="fa-file-code-o">&#xf1c9; fa-file-code-o</option>
							<option value="fa-file-excel-o">&#xf1c3; fa-file-excel-o</option>
							<option value="fa-file-image-o">&#xf1c5; fa-file-image-o</option>
							<option value="fa-file-movie-o">&#xf1c8; fa-file-movie-o</option>
							<option value="fa-file-o">&#xf016; fa-file-o</option>
							<option value="fa-file-pdf-o">&#xf1c1; fa-file-pdf-o</option>
							<option value="fa-file-photo-o">&#xf1c5; fa-file-photo-o</option>
							<option value="fa-file-picture-o">&#xf1c5; fa-file-picture-o</option>
							<option value="fa-file-powerpoint-o">&#xf1c4; fa-file-powerpoint-o</option>
							<option value="fa-file-sound-o">&#xf1c7; fa-file-sound-o</option>
							<option value="fa-file-text">&#xf15c; fa-file-text</option>
							<option value="fa-file-text-o">&#xf0f6; fa-file-text-o</option>
							<option value="fa-file-video-o">&#xf1c8; fa-file-video-o</option>
							<option value="fa-file-word-o">&#xf1c2; fa-file-word-o</option>
							<option value="fa-file-zip-o">&#xf1c6; fa-file-zip-o</option>
							<option value="fa-files-o">&#xf0c5; fa-files-o</option>
							<option value="fa-film">&#xf008; fa-film</option>
							<option value="fa-filter">&#xf0b0; fa-filter</option>
							<option value="fa-fire">&#xf06d; fa-fire</option>
							<option value="fa-fire-extinguisher">&#xf134; fa-fire-extinguisher</option>
							<option value="fa-firefox">&#xf269; fa-firefox</option>
							<option value="fa-flag">&#xf024; fa-flag</option>
							<option value="fa-flag-checkered">&#xf11e; fa-flag-checkered</option>
							<option value="fa-flag-o">&#xf11d; fa-flag-o</option>
							<option value="fa-flash">&#xf0e7; fa-flash</option>
							<option value="fa-flask">&#xf0c3; fa-flask</option>
							<option value="fa-flickr">&#xf16e; fa-flickr</option>
							<option value="fa-floppy-o">&#xf0c7; fa-floppy-o</option>
							<option value="fa-folder">&#xf07b; fa-folder</option>
							<option value="fa-folder-o">&#xf114; fa-folder-o</option>
							<option value="fa-folder-open">&#xf07c; fa-folder-open</option>
							<option value="fa-folder-open-o">&#xf115; fa-folder-open-o</option>
							<option value="fa-font">&#xf031; fa-font</option>
							<option value="fa-fonticons">&#xf280; fa-fonticons</option>
							<option value="fa-forumbee">&#xf211; fa-forumbee</option>
							<option value="fa-forward">&#xf04e; fa-forward</option>
							<option value="fa-foursquare">&#xf180; fa-foursquare</option>
							<option value="fa-frown-o">&#xf119; fa-frown-o</option>
							<option value="fa-futbol-o">&#xf1e3; fa-futbol-o</option>
							<option value="fa-gamepad">&#xf11b; fa-gamepad</option>
							<option value="fa-gavel">&#xf0e3; fa-gavel</option>
							<option value="fa-gbp">&#xf154; fa-gbp</option>
							<option value="fa-ge">&#xf1d1; fa-ge</option>
							<option value="fa-gear">&#xf013; fa-gear</option>
							<option value="fa-gears">&#xf085; fa-gears</option>
							<option value="fa-genderless">&#xf22d; fa-genderless</option>
							<option value="fa-get-pocket">&#xf265; fa-get-pocket</option>
							<option value="fa-gg">&#xf260; fa-gg</option>
							<option value="fa-gg-circle">&#xf261; fa-gg-circle</option>
							<option value="fa-gift">&#xf06b; fa-gift</option>
							<option value="fa-git">&#xf1d3; fa-git</option>
							<option value="fa-git-square">&#xf1d2; fa-git-square</option>
							<option value="fa-github">&#xf09b; fa-github</option>
							<option value="fa-github-alt">&#xf113; fa-github-alt</option>
							<option value="fa-github-square">&#xf092; fa-github-square</option>
							<option value="fa-gittip">&#xf184; fa-gittip</option>
							<option value="fa-glass">&#xf000; fa-glass</option>
							<option value="fa-globe">&#xf0ac; fa-globe</option>
							<option value="fa-google">&#xf1a0; fa-google</option>
							<option value="fa-google-plus">&#xf0d5; fa-google-plus</option>
							<option value="fa-google-plus-square">&#xf0d4; fa-google-plus-square</option>
							<option value="fa-google-wallet">&#xf1ee; fa-google-wallet</option>
							<option value="fa-graduation-cap">&#xf19d; fa-graduation-cap</option>
							<option value="fa-gratipay">&#xf184; fa-gratipay</option>
							<option value="fa-group">&#xf0c0; fa-group</option>
							<option value="fa-h-square">&#xf0fd; fa-h-square</option>
							<option value="fa-hacker-news">&#xf1d4; fa-hacker-news</option>
							<option value="fa-hand-grab-o">&#xf255; fa-hand-grab-o</option>
							<option value="fa-hand-lizard-o">&#xf258; fa-hand-lizard-o</option>
							<option value="fa-hand-o-down">&#xf0a7; fa-hand-o-down</option>
							<option value="fa-hand-o-left">&#xf0a5; fa-hand-o-left</option>
							<option value="fa-hand-o-right">&#xf0a4; fa-hand-o-right</option>
							<option value="fa-hand-o-up">&#xf0a6; fa-hand-o-up</option>
							<option value="fa-hand-paper-o">&#xf256; fa-hand-paper-o</option>
							<option value="fa-hand-peace-o">&#xf25b; fa-hand-peace-o</option>
							<option value="fa-hand-pointer-o">&#xf25a; fa-hand-pointer-o</option>
							<option value="fa-hand-rock-o">&#xf255; fa-hand-rock-o</option>
							<option value="fa-hand-scissors-o">&#xf257; fa-hand-scissors-o</option>
							<option value="fa-hand-spock-o">&#xf259; fa-hand-spock-o</option>
							<option value="fa-hand-stop-o">&#xf256; fa-hand-stop-o</option>
							<option value="fa-hdd-o">&#xf0a0; fa-hdd-o</option>
							<option value="fa-header">&#xf1dc; fa-header</option>
							<option value="fa-headphones">&#xf025; fa-headphones</option>
							<option value="fa-heart">&#xf004; fa-heart</option>
							<option value="fa-heart-o">&#xf08a; fa-heart-o</option>
							<option value="fa-heartbeat">&#xf21e; fa-heartbeat</option>
							<option value="fa-history">&#xf1da; fa-history</option>
							<option value="fa-home">&#xf015; fa-home</option>
							<option value="fa-hospital-o">&#xf0f8; fa-hospital-o</option>
							<option value="fa-hotel">&#xf236; fa-hotel</option>
							<option value="fa-hourglass">&#xf254; fa-hourglass</option>
							<option value="fa-hourglass-1">&#xf251; fa-hourglass-1</option>
							<option value="fa-hourglass-2">&#xf252; fa-hourglass-2</option>
							<option value="fa-hourglass-3">&#xf253; fa-hourglass-3</option>
							<option value="fa-hourglass-end">&#xf253; fa-hourglass-end</option>
							<option value="fa-hourglass-half">&#xf252; fa-hourglass-half</option>
							<option value="fa-hourglass-o">&#xf250; fa-hourglass-o</option>
							<option value="fa-hourglass-start">&#xf251; fa-hourglass-start</option>
							<option value="fa-houzz">&#xf27c; fa-houzz</option>
							<option value="fa-html5">&#xf13b; fa-html5</option>
							<option value="fa-i-cursor">&#xf246; fa-i-cursor</option>
							<option value="fa-ils">&#xf20b; fa-ils</option>
							<option value="fa-image">&#xf03e; fa-image</option>
							<option value="fa-inbox">&#xf01c; fa-inbox</option>
							<option value="fa-indent">&#xf03c; fa-indent</option>
							<option value="fa-industry">&#xf275; fa-industry</option>
							<option value="fa-info">&#xf129; fa-info</option>
							<option value="fa-info-circle">&#xf05a; fa-info-circle</option>
							<option value="fa-inr">&#xf156; fa-inr</option>
							<option value="fa-instagram">&#xf16d; fa-instagram</option>
							<option value="fa-institution">&#xf19c; fa-institution</option>
							<option value="fa-internet-explorer">&#xf26b; fa-internet-explorer</option>
							<option value="fa-intersex">&#xf224; fa-intersex</option>
							<option value="fa-ioxhost">&#xf208; fa-ioxhost</option>
							<option value="fa-italic">&#xf033; fa-italic</option>
							<option value="fa-joomla">&#xf1aa; fa-joomla</option>
							<option value="fa-jpy">&#xf157; fa-jpy</option>
							<option value="fa-jsfiddle">&#xf1cc; fa-jsfiddle</option>
							<option value="fa-key">&#xf084; fa-key</option>
							<option value="fa-keyboard-o">&#xf11c; fa-keyboard-o</option>
							<option value="fa-krw">&#xf159; fa-krw</option>
							<option value="fa-language">&#xf1ab; fa-language</option>
							<option value="fa-laptop">&#xf109; fa-laptop</option>
							<option value="fa-lastfm">&#xf202; fa-lastfm</option>
							<option value="fa-lastfm-square">&#xf203; fa-lastfm-square</option>
							<option value="fa-leaf">&#xf06c; fa-leaf</option>
							<option value="fa-leanpub">&#xf212; fa-leanpub</option>
							<option value="fa-legal">&#xf0e3; fa-legal</option>
							<option value="fa-lemon-o">&#xf094; fa-lemon-o</option>
							<option value="fa-level-down">&#xf149; fa-level-down</option>
							<option value="fa-level-up">&#xf148; fa-level-up</option>
							<option value="fa-life-bouy">&#xf1cd; fa-life-bouy</option>
							<option value="fa-life-buoy">&#xf1cd; fa-life-buoy</option>
							<option value="fa-life-ring">&#xf1cd; fa-life-ring</option>
							<option value="fa-life-saver">&#xf1cd; fa-life-saver</option>
							<option value="fa-lightbulb-o">&#xf0eb; fa-lightbulb-o</option>
							<option value="fa-line-chart">&#xf201; fa-line-chart</option>
							<option value="fa-link">&#xf0c1; fa-link</option>
							<option value="fa-linkedin">&#xf0e1; fa-linkedin</option>
							<option value="fa-linkedin-square">&#xf08c; fa-linkedin-square</option>
							<option value="fa-linux">&#xf17c; fa-linux</option>
							<option value="fa-list">&#xf03a; fa-list</option>
							<option value="fa-list-alt">&#xf022; fa-list-alt</option>
							<option value="fa-list-ol">&#xf0cb; fa-list-ol</option>
							<option value="fa-list-ul">&#xf0ca; fa-list-ul</option>
							<option value="fa-location-arrow">&#xf124; fa-location-arrow</option>
							<option value="fa-lock">&#xf023; fa-lock</option>
							<option value="fa-long-arrow-down">&#xf175; fa-long-arrow-down</option>
							<option value="fa-long-arrow-left">&#xf177; fa-long-arrow-left</option>
							<option value="fa-long-arrow-right">&#xf178; fa-long-arrow-right</option>
							<option value="fa-long-arrow-up">&#xf176; fa-long-arrow-up</option>
							<option value="fa-magic">&#xf0d0; fa-magic</option>
							<option value="fa-magnet">&#xf076; fa-magnet</option>

							<option value="fa-mars-stroke-v">&#xf22a; fa-mars-stroke-v</option>
							<option value="fa-maxcdn">&#xf136; fa-maxcdn</option>
							<option value="fa-meanpath">&#xf20c; fa-meanpath</option>
							<option value="fa-medium">&#xf23a; fa-medium</option>
							<option value="fa-medkit">&#xf0fa; fa-medkit</option>
							<option value="fa-meh-o">&#xf11a; fa-meh-o</option>
							<option value="fa-mercury">&#xf223; fa-mercury</option>
							<option value="fa-microphone">&#xf130; fa-microphone</option>
							<option value="fa-mobile">&#xf10b; fa-mobile</option>
							<option value="fa-motorcycle">&#xf21c; fa-motorcycle</option>
							<option value="fa-mouse-pointer">&#xf245; fa-mouse-pointer</option>
							<option value="fa-music">&#xf001; fa-music</option>
							<option value="fa-navicon">&#xf0c9; fa-navicon</option>
							<option value="fa-neuter">&#xf22c; fa-neuter</option>
							<option value="fa-newspaper-o">&#xf1ea; fa-newspaper-o</option>
							<option value="fa-opencart">&#xf23d; fa-opencart</option>
							<option value="fa-openid">&#xf19b; fa-openid</option>
							<option value="fa-opera">&#xf26a; fa-opera</option>
							<option value="fa-outdent">&#xf03b; fa-outdent</option>
							<option value="fa-pagelines">&#xf18c; fa-pagelines</option>
							<option value="fa-paper-plane-o">&#xf1d9; fa-paper-plane-o</option>
							<option value="fa-paperclip">&#xf0c6; fa-paperclip</option>
							<option value="fa-paragraph">&#xf1dd; fa-paragraph</option>
							<option value="fa-paste">&#xf0ea; fa-paste</option>
							<option value="fa-pause">&#xf04c; fa-pause</option>
							<option value="fa-paw">&#xf1b0; fa-paw</option>
							<option value="fa-paypal">&#xf1ed; fa-paypal</option>
							<option value="fa-pencil">&#xf040; fa-pencil</option>
							<option value="fa-pencil-square-o">&#xf044; fa-pencil-square-o</option>
							<option value="fa-phone">&#xf095; fa-phone</option>
							<option value="fa-photo">&#xf03e; fa-photo</option>
							<option value="fa-picture-o">&#xf03e; fa-picture-o</option>
							<option value="fa-pie-chart">&#xf200; fa-pie-chart</option>
							<option value="fa-pied-piper">&#xf1a7; fa-pied-piper</option>
							<option value="fa-pied-piper-alt">&#xf1a8; fa-pied-piper-alt</option>
							<option value="fa-pinterest">&#xf0d2; fa-pinterest</option>
							<option value="fa-pinterest-p">&#xf231; fa-pinterest-p</option>
							<option value="fa-pinterest-square">&#xf0d3; fa-pinterest-square</option>
							<option value="fa-plane">&#xf072; fa-plane</option>
							<option value="fa-play">&#xf04b; fa-play</option>
							<option value="fa-play-circle">&#xf144; fa-play-circle</option>
							<option value="fa-play-circle-o">&#xf01d; fa-play-circle-o</option>
							<option value="fa-plug">&#xf1e6; fa-plug</option>
							<option value="fa-plus">&#xf067; fa-plus</option>
							<option value="fa-plus-circle">&#xf055; fa-plus-circle</option>
							<option value="fa-plus-square">&#xf0fe; fa-plus-square</option>
							<option value="fa-plus-square-o">&#xf196; fa-plus-square-o</option>
							<option value="fa-power-off">&#xf011; fa-power-off</option>
							<option value="fa-print">&#xf02f; fa-print</option>
							<option value="fa-puzzle-piece">&#xf12e; fa-puzzle-piece</option>
							<option value="fa-qq">&#xf1d6; fa-qq</option>
							<option value="fa-qrcode">&#xf029; fa-qrcode</option>
							<option value="fa-question">&#xf128; fa-question</option>
							<option value="fa-question-circle">&#xf059; fa-question-circle</option>
							<option value="fa-quote-left">&#xf10d; fa-quote-left</option>
							<option value="fa-quote-right">&#xf10e; fa-quote-right</option>
							<option value="fa-ra">&#xf1d0; fa-ra</option>
							<option value="fa-random">&#xf074; fa-random</option>
							<option value="fa-rebel">&#xf1d0; fa-rebel</option>
							<option value="fa-recycle">&#xf1b8; fa-recycle</option>
							<option value="fa-reddit">&#xf1a1; fa-reddit</option>
							<option value="fa-reddit-square">&#xf1a2; fa-reddit-square</option>
							<option value="fa-refresh">&#xf021; fa-refresh</option>
							<option value="fa-registered">&#xf25d; fa-registered</option>
							<option value="fa-remove">&#xf00d; fa-remove</option>
							<option value="fa-renren">&#xf18b; fa-renren</option>
							<option value="fa-reorder">&#xf0c9; fa-reorder</option>
							<option value="fa-repeat">&#xf01e; fa-repeat</option>
							<option value="fa-reply">&#xf112; fa-reply</option>
							<option value="fa-reply-all">&#xf122; fa-reply-all</option>
							<option value="fa-retweet">&#xf079; fa-retweet</option>
							<option value="fa-rmb">&#xf157; fa-rmb</option>
							<option value="fa-road">&#xf018; fa-road</option>
							<option value="fa-rocket">&#xf135; fa-rocket</option>
							<option value="fa-rotate-left">&#xf0e2; fa-rotate-left</option>
							<option value="fa-rotate-right">&#xf01e; fa-rotate-right</option>
							<option value="fa-rouble">&#xf158; fa-rouble</option>
							<option value="fa-rss">&#xf09e; fa-rss</option>
							<option value="fa-rss-square">&#xf143; fa-rss-square</option>
							<option value="fa-rub">&#xf158; fa-rub</option>
							<option value="fa-ruble">&#xf158; fa-ruble</option>
							<option value="fa-rupee">&#xf156; fa-rupee</option>
							<option value="fa-safari">&#xf267; fa-safari</option>
							<option value="fa-sliders">&#xf1de; fa-sliders</option>
							<option value="fa-slideshare">&#xf1e7; fa-slideshare</option>
							<option value="fa-smile-o">&#xf118; fa-smile-o</option>
							<option value="fa-sort-asc">&#xf0de; fa-sort-asc</option>
							<option value="fa-sort-desc">&#xf0dd; fa-sort-desc</option>
							<option value="fa-sort-down">&#xf0dd; fa-sort-down</option>
							<option value="fa-spinner">&#xf110; fa-spinner</option>
							<option value="fa-spoon">&#xf1b1; fa-spoon</option>
							<option value="fa-spotify">&#xf1bc; fa-spotify</option>
							<option value="fa-square">&#xf0c8; fa-square</option>
							<option value="fa-square-o">&#xf096; fa-square-o</option>
							<option value="fa-star">&#xf005; fa-star</option>
							<option value="fa-star-half">&#xf089; fa-star-half</option>
							<option value="fa-stop">&#xf04d; fa-stop</option>
							<option value="fa-subscript">&#xf12c; fa-subscript</option>
							<option value="fa-tablet">&#xf10a; fa-tablet</option>
							<option value="fa-tachometer">&#xf0e4; fa-tachometer</option>
							<option value="fa-tag">&#xf02b; fa-tag</option>
							<option value="fa-tags">&#xf02c; fa-tags</option>
						  </select>
					</div>  
					
					
                    <div class="form-group">
						<label>
                             Gerarchia Modulo: 
                        </label>
						<br/>
						<table>
							<tr>
								<td>
									<select name="sel_mod_gerarchia"  
											id="sel_mod_gerarchia" onchange="setModHierarchy()">
										<option value="standalone">Stand Alone</option>
										<option value="figlio">Figlio</option>
									</select>
								</td>
								<td>
									<div id='dv_mod_gen_aggr' style='visibility:hidden'>
										<b style='padding:5px'>Modulo Aggregatore</b>
										<select name="mod_gen_aggr"  
												id="mod_gen_aggr" >
	 
										</select>
									</div>
								</td>								
							</tr>
						</table>		
                    </div> 
					
                    <div class="form-group">
                        <div class="row">
                            <?php $jenis_tabel = isset($_POST['jenis_tabel']) ? $_POST['jenis_tabel'] : 'reguler_table'; ?>
                            <!--
							<div class="col-md-5">
                                <div class="radio" style="margin-bottom: 0px; margin-top: 0px">
                                    <label>
                                        <input type="radio" name="jenis_tabel" value="reguler_table" <?php echo $jenis_tabel == 'reguler_table' ? 'checked' : ''; ?>>
                                        Reguler Table
                                    </label>
                                </div>                            
                            </div>
							-->
                            <div class="col-md-7">
                                <div class="radio" style="margin-bottom: 0px; margin-top: 0px">
                                    <label>
                                        <input type="radio" name="jenis_tabel" value="datatables" <?php echo $jenis_tabel == 'datatables' ? 'checked' : ''; ?> checked>
                                        Serverside Datatables
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>

 										
                    <div class="form-group">
						<label>
                              Nr Colonne Form Layout: 
                        </label>
						<select name="form_columns_nr_layout" id="form_columns_nr_layout">
							<option value="1">1 Colonna</option>
							<option value="2">2 Colonne</option>
							<option value="3">3 Colonne</option>
						</select>
                    </div>    					
					
					<div class="form-group">
						<label>
                             Font Modulo: 
                        </label>
			 			<br/>
						<select>
							 <option style="font-family:arial">Arial</option>
							 <option style="font-family:verdana"> Verdana </option>
							 <option style="font-family:Sans-Serif">Sans-Serif</option>
							 <option style="font-family: monospace;">Monospace</option> 
							 <option style="font-family: cursive;">Cursive</option>
							 <option style="font-family: Georgia;">Georgia</option>
							 <option style="font-family: Calisto MT;">Calisto MT</option>
							 <option style="font-family: Helvetica;">Helvetica</option>
							 <option style="font-family: Calibri;">Calibri</option>
							 <option style="font-family: Lucida Sans;">Lucida Sans</option>
							 <option style="font-family: Candara;">Candara</option>
							 <option style="font-family: Century Gothic;">Century Gothic</option>
							 <option style="font-family: Book Antiqua;">Book Antiqua</option>
							 <option style="font-family: Garamond;">Garamond</option>
							 <option style="font-family: Courier;">Courier</option>
							 <option style="font-family: Courier New;">Courier New</option>
							 <option style="font-family: Comic Sans">Comic Sans</option>
							 <option style="font-family: Comic Sans MS">Comic Sans MS</option>
							 <option style='font-family: Times, "Times New Roman", serif;'>Times New Roman</option>
						</select>
						
					</div>
					     <div class="form-group">
					 
							<label>
								  <u>CUSTOMIZZA CAMPI:</u> 
							</label>
							

							<div id="tabs">
							  <ul>
								<li><a href="#tab_show_column">Visualizza Colonne Griglia</a></li>
								<li><a href="#tab_show_field">Visualizza Campi</a></li>								
								<li><a href="#tab_field_ext_module">Campi Collegati a Moduli Generati</a></li>								
							  </ul>

							  
							  <div id="tab_show_column">
								<b>Seleziona Tutto</b> <input type="checkbox" checked="checked" id="check_master_column" onchange="selezionaDeselezionaTutti('check_master_column','column_name[]')" />
								<ul id="tab_show_column_sortable" name="tab_show_column_sortable" 
									class="list-group">

								</ul>							  
							  
							  </div>
							  
							  <div id="tab_show_field">
							   <b>Seleziona Tutto</b> <input type="checkbox" checked="checked" id="check_master_field"  onchange="selezionaDeselezionaTutti('check_master_field','field_name[]')" />
								<br><span style="color:#990000"><b style="font-size:bolder">(*)</b>Campi Obbligatori, rimuovendoli il form non funzionera nella validazione</span>
								<br><br><ul id="tab_show_field_sortable" name="tab_show_field_sortable" 
									class="list-group">

								</ul>								  
							  </div>
							  
							  <div id="tab_field_ext_module">
							  </div>
							</div>
 							<br>
							 <b>Mostra Master Details se presenti</b>
							 <select id="show_master_detail" name="show_master_detail" class="form-control">
								<option value="Y">SI</option>
								<option value="N">NO</option>
							 </select>											
                    </div>  
                    <div class="form-group">
						<div id="accordion">
						  <h3>Griglia Master/Detail</h3>
						  <div>
						  <p>
							<b style='color:#000'>Griglia Master/Detail</b>
							<input type='checkbox' name="enable_master_detail" id="enable_master_detail" onchange='enableDisableMasterDetail()'/>
							 - <b id='lbl_table_name_detail' style='color:#0059B3'>Seleziona Tabella Detail: </b>
							<select id="table_name_detail" name="table_name_detail" onchange="setname();callAjax('get_list_column','detail')" disabled >
								<option value="">Seleziona</option>
								<?php
								$table_list_selected = isset($_POST['table_name']) ? $_POST['table_name'] : '';
								foreach ($table_list as $table) {
									?>
									<option value="<?php echo $table['TABLE_NAME'] ?>" <?php echo $table_list_selected == $table['TABLE_NAME'] ? 'selected="selected"' : ''; ?>><?php echo $table['TABLE_NAME'] ?></option>
									<?php
								}
								?>
							</select>
							
							
							

							<div id="tabs_grd_detail">
							  <ul>
								<li><a href="#tab_show_column_grd_detail">Visualizza Colonne/ Campi</a></li>								
								<li><a href="#tab_show_field_grd_detail">Visualizza Campi</a></li>	
								<li><a href="#tab_field_ext_module_grd_detail">Campi Collegati a Moduli Generati</a></li>								
							  </ul>

							  
							  <div id="tab_show_column_grd_detail">
								<b>Seleziona Tutto</b> <input type="checkbox" checked="checked" id="check_master_column_detail"  onchange="selezionaDeselezionaTutti('check_master_column_detail','column_name_detail[]')" />
							  <ul id="tab_show_column_sortable_grd_detail" name="tab_show_column_sortable_grd_detail" 
									class="list-group">

								</ul>								  
							  </div>
							  
 							  <div id="tab_show_field_grd_detail">
							   <b>Seleziona Tutto</b> <input type="checkbox" checked="checked" id="check_master_field_detail"  onchange="selezionaDeselezionaTutti('check_master_field_detail','field_name_detail[]')" />
								<ul id="tab_show_field_sortable_grd_detail" name="tab_show_field_sortable_grd_detail" 
									class="list-group">

								</ul>							  
							  </div>
							  
							  <div id="tab_field_ext_module_grd_detail">
							  </div>
							</div>							
							
							
							
							
							
							<small style='color:#990000'> <br>(*)La detail sara' visualizzata nel form del modulo master in formato griglia editabile</small>
							</p><br>
						  </div>
						</div>				
																</div>	
                    <div class="form-group">
                        <div class="checkbox">
                            <?php $export_excel = isset($_POST['export_excel']) ? $_POST['export_excel'] : ''; ?>
                            <label>
                                <input type="checkbox" name="export_excel" value="1" <?php echo $export_excel == '1' ? 'checked' : '' ?>>
                                Tasto Esporta Excel
                            </label>
                        </div>
                    </div>    

 				
                    <div class="form-group">
                        <label>Nome Classe "Controller" del modulo</label>
                        <input type="text" id="controller" name="controller" value="<?php echo isset($_POST['controller']) ? $_POST['controller'] : '' ?>" class="form-control" placeholder="Controller Name" />
                    </div>
                    <div class="form-group">
                        <label>Nome Classe "Model" del modulo</label>
                        <input type="text" id="model" name="model" value="<?php echo isset($_POST['model']) ? $_POST['model'] : '' ?>" class="form-control" placeholder="Model Name" />
                    </div>
                    <input type="button" value="Genera Modulo" name="generate" class="btn btn-primary" onclick="checkParamGen()" />
 
                </form>
                <br>
				<b><u>Elenco Files generati</u></b>
				<br> 					
                <?php
                foreach ($hasil as $h) {
                    echo '<p style="color:#3d7910;font-size:bolder">' . $h . '</p>';
                }
                ?>
            </div>		
        </div>	
	
	
	
	
</div>		
<!--FINE-->
</section>
</div>
<!--FINE-->		
<style>

	/* select with custom icons */
	.ui-selectmenu-menu .ui-menu.customicons .ui-menu-item-wrapper {
	  padding: 0.5em 0 0.5em 3em;
	}
	.ui-selectmenu-menu .ui-menu.customicons .ui-menu-item .ui-icon {
	  height: 24px;
	  width: 24px;
	  top: 0.1em;
	}
	.ui-icon.video {
	  background: url("images/24-video-square.png") 0 0 no-repeat;
	}
	.ui-icon.podcast {
	  background: url("images/24-podcast-square.png") 0 0 no-repeat;
	}
	.ui-icon.rss {
	  background: url("images/24-rss-square.png") 0 0 no-repeat;
	}
 
	/* select with CSS avatar icons */
	option.avatar {
	  background-repeat: no-repeat !important;
	  padding-left: 20px;
	}
	.avatar .ui-icon {
	  background-position: left top;
	}			
</style>

<script type="text/javascript">
	$( "#tabs" ).tabs();
	$( "#tabs_grd_detail" ).tabs();
	
	var orderField = []; 
	var orderFieldDetail = []; 
	$(".list-group").sortable();

	$("#accordion").accordion({ header: "h3", collapsible: true, active: false });

	$( "#tabs_grd_detail" ).tabs({
	  disabled: [ 0,1,2]
	});	
	
	//$( "#tab" ).tabs();
    function capitalize(s) {
        return s && s[0].toUpperCase() + s.slice(1);
    }

    function setname() {
        var table_name = document.getElementById('table_name').value.toLowerCase();
        if (table_name != '') {
            document.getElementById('controller').value = capitalize(table_name);
            document.getElementById('model').value = capitalize(table_name) + '_model';
        } else {
            document.getElementById('controller').value = '';
            document.getElementById('model').value = '';
        }
    }
	
	function callAjax(str_call_type,table_type){
		var table_name = "";
		var tab_show_column_sortable = "";
		var tab_show_column = "";
		var tab_show_field = "";
		var tab_field_ext_module = "";
		
		if(table_type == 'master'){
			table_name = "table_name";
			tab_show_column_sortable = "tab_show_column_sortable";
			tab_show_field_sortable = "tab_show_field_sortable";
			tab_show_column = "tab_show_column";
			tab_show_field = "tab_show_field";
			tab_field_ext_module = "tab_field_ext_module";	
		} else {
			table_name = "table_name_detail";
			tab_show_column_sortable = "tab_show_column_sortable_grd_detail";
			tab_show_field_sortable = "tab_show_field_sortable_grd_detail";
			tab_show_column = "tab_show_column_grd_detail";
			tab_show_field = "tab_show_field_grd_detail";
			tab_field_ext_module = "tab_field_ext_module_grd_detail";						
		}
		
		showBiscuit(); 
		
		getModGenAggr();
		
 
		$.ajax({
			url : 'mod_crud_gen/ajaxCall',
			type : 'GET',
			dataType:'json',
			data:{
				call_type:str_call_type,
				tabella:document.getElementById(table_name).value		
			},
			success : function(data) { 
				//var objData = JSON.parse(data);
				var objData = data.column_list;
				var objModGen = data.mod_gen;	
				var listColumnSortable = document.getElementById(tab_show_column_sortable);		
				var listFieldSortable = document.getElementById(tab_show_field_sortable);	
				var containerShowColumn = document.getElementById(tab_show_column);
				var containerShowField = document.getElementById(tab_show_field);							
				var containerExtModule = document.getElementById(tab_field_ext_module);				
			 
				while(listColumnSortable.firstChild){
					listColumnSortable.removeChild(listColumnSortable.firstChild);
				}							
				
				while(listFieldSortable.firstChild){
					listFieldSortable.removeChild(listFieldSortable.firstChild);
				}								
		
				
				for(var key in objData){
					//LISTA CAMPI
					var newOption = document.createElement('option');
					newOption.value = objData[key].column_name;
					newOption.text = objData[key].column_name;					
					
					//LISTA COLONNE VISIBILI 
					var checkboxColumn = document.createElement('input');checkboxColumn.type = "checkbox";
					if(table_type == 'master'){
						checkboxColumn.name = "column_name[]";
						checkboxColumn.id = "column_name[]";
					} else {
						checkboxColumn.name = "column_name_detail[]";
						checkboxColumn.id = "column_name_detail[]";									
					}
					checkboxColumn.value = objData[key].column_name;
					checkboxColumn.checked = true;
					checkboxColumn.className = "chk-column_name";
					//PER LE COLONNE DELLA GRIGLIA NON E' IMPORTANTE MOSTRARE I CAMPI OBBLIGATORI
					/*
					if(objData[key].mandatory == 'TRUE'){
						checkboxColumn.disabled = true;	
					}
					*/

					var label = document.createElement('label');
					
					if(table_type == 'master'){
						label.htmlFor = "column_name_" + key;
					} else {
						label.htmlFor = "column_name_detail_" + key;
					}
					label.appendChild(document.createTextNode(objData[key].column_name));

					//AGGIUNGO IL CHECK BOX AL CAMPO SORTABILE
					var li = document.createElement("li");  
					li.setAttribute("class", "list-group-item");								
					li.appendChild(checkboxColumn);
					li.appendChild(label);
					
					listColumnSortable.appendChild(li);

					//LISTA CAMPI VISIBILI
					var checkboxField = document.createElement('input');checkboxField.type = "checkbox";
					if(table_type == 'master'){
						checkboxField.name = "field_name[]";
						checkboxField.id = "field_name[]";
					} else {
						checkboxField.name = "field_name_detail[]";
						checkboxField.id = "field_name_detail[]";									
					}
					checkboxField.value = objData[key].column_name;
					checkboxField.checked = true;
					checkboxField.className = "chk-field_name";
					var label = document.createElement('label');
					
					if((objData[key].mandatory == 'TRUE') && (objData[key].column_name != 'id') ){
						//checkboxField.disabled = true;	
					}

					if(table_type == 'master'){
						label.htmlFor = "field_name_" + key;
					} else {
						label.htmlFor = "field_name_detail_" + key;
					}

					if(objData[key].mandatory == "TRUE"){
						label.appendChild(document.createTextNode("(*)" + objData[key].column_name));
					} else {
						label.appendChild(document.createTextNode(objData[key].column_name));
					}
					

					//AGGIUNGO IL CHECK BOX AL CAMPO SORTABILE
					var li = document.createElement("li");  
					li.setAttribute("class", "list-group-item");								
					li.appendChild(checkboxField);
					li.appendChild(label);								
					
					listFieldSortable.appendChild(li);
			
			
					//LISTA CAMPI COLLEGATI A MODULI ESTERNI
					var selectExtMod = document.createElement('select');
					if(table_type == 'master'){
						selectExtMod.name = 'select_ext_mod[' + objData[key].column_name + ']';
						selectExtMod.id = 'select_ext_mod[' + objData[key].column_name + ']';
					} else {
						selectExtMod.name = 'select_ext_mod_detail[' + objData[key].column_name + ']';
						selectExtMod.id = 'select_ext_mod_detail[' + objData[key].column_name + ']';									
					}
					selectExtMod.className = 'select_ext_mod';
					
					var label = document.createElement('label');
					if(table_type == 'master'){
						label.htmlFor = "field_ext_mod_" + key;
					} else {
						label.htmlFor = "field_ext_mod_detail_" + key;
					}
					label.appendChild(document.createTextNode(" - " + objData[key].column_name));
					var newOption = document.createElement('option');
					newOption.value = "";
					newOption.text = "--Seleziona--";
					selectExtMod.appendChild(newOption);					

					containerExtModule.appendChild(selectExtMod);	
					containerExtModule.appendChild(label);

					var br = document.createElement('br');
					containerExtModule.appendChild(br);									
				}
				
				//AGGIUNGO GLI ITEM DEI MODULI GENERATI RICHIAMABILI A TUTTE LE COMBO		
				var selectExtModByClassName = document.getElementsByClassName('select_ext_mod');

				for(var keyMod in objModGen){
					
					for(var keySelect in selectExtModByClassName){
						var newOption = document.createElement('option');
						newOption.value = objModGen[keyMod].mod_name;
						newOption.text = objModGen[keyMod].mod_title;
						if(!isNaN(keySelect)){
							selectExtModByClassName[keySelect].appendChild(newOption);	
						}
						
					}		
				}
				
				hideBiscuit();
			}
		});		
 	
	}		

	
	function populateSelectExtMod(){
		$.ajax({
			url : 'mod_crud_gen/ajaxCall',
			type : 'POST',
			dataType:'json',
			data:{
				call_type:'list_mod',
				tabella_escludere:document.getElementById('table_name').value		
			},
			success : function(data) { 
				var objData = data;
				var selectExtMod = document.getElementById('select_ext_mod[]');
				for(var key in objData){
					//LISTA CAMPI
					var newOption = document.createElement('option');
					newOption.value = objData[key];
					newOption.text = objData[key];
					selectExtMod.appendChild(newOption);
				}			
			}						
		});				
	}
	

	function enableDisableTabAccordion(modType){
		if(modType == 'report'){
			$( "#tabs" ).tabs({
			  disabled: [ 1,2 ]
			});
			
			$("#accordion").accordion({ header: "h3", collapsible: true, active: false });
			$( "#accordion" ).accordion( "disable" );				
		} else {
			$( "#tabs" ).tabs( "enable", 0);
			$( "#tabs" ).tabs( "enable", 1);
			$( "#tabs" ).tabs( "enable", 2);	
 			
			$( "#accordion" ).accordion( "enable" );						
		}	
	}
	
	
	/**
	 * @description conta le proprieta di un oggetto  
	 * @param {loaderName} obj
	 * @param {callback} obj
	 * @returns {Null} 
	 */
	function showBiscuit(loaderName, callback) {
		//SE NON E' SETTATO NESSUN ID, GLI SETTO UN ID DI DEFAULT
		if (typeof(loaderName) === 'undefined') {
			loaderName = 'loader';
		}
		if (typeof(loaderName) === null) {
			loaderName = 'loader';
		}

		//RICHIAMO UNA EVENTUALE FUNZIONE PASSATA COME ARGOMENTO
		if (typeof(callback) !== 'undefined') {
			callback();
		}
		document.getElementById(loaderName).style.display = "block";
	}

	function hideBiscuit(loaderName, callback) {
		//SE NON E' SETTATO NESSUN ID, GLI SETTO UN ID DI DEFAULT
		if (typeof(loaderName) === 'undefined') {
			loaderName = 'loader';
		}
		if (typeof(loaderName) === null) {
			loaderName = 'loader';
		}

		setTimeout(function() { document.getElementById(loaderName).style.display = "none"; }, 500);

		//RICHIAMO UNA EVENTUALE FUNZIONE PASSATA COME ARGOMENTO
		if (typeof(callback) !== 'undefined') {
			callback();
		}
	}
	
	function enableDisableMasterDetail(){
		if(document.getElementById('enable_master_detail').checked == true){
			document.getElementById('table_name_detail').disabled = false;	
			$( "#tabs_grd_detail" ).tabs( "enable", 0);
			$( "#tabs_grd_detail" ).tabs( "enable", 1);
			$( "#tabs_grd_detail" ).tabs( "enable", 2);
				
		} else {
			document.getElementById('table_name_detail').disabled = true;	
			$( "#tabs_grd_detail" ).tabs({
			  disabled: [ 0,1,2]
			});									
		}
		
	}
				

	function removeCustomField(typeGrid){
		if(typeGrid == 'master'){
			var columns_list = document.getElementById('select_columns_list');
			var columns_customized = document.getElementById('form_columns_customized');		
		} else {
			var columns_list = document.getElementById('select_columns_list_grd_detail');
			var columns_customized = document.getElementById('form_columns_customized_grd_detail');	
		}
		if(columns_customized.selectedIndex == -1){
			return false;
		}
		
		var option = document.createElement("option");
		var strText = columns_customized.options[columns_customized.selectedIndex].text;
		var strValue = columns_customized.value;
		var textArray = strText.split(":");
		var valueArray = strValue.split("|");
		
		option.text = textArray[0];
		option.value = valueArray[0];
		columns_list.add(option);					
		columns_customized.remove(columns_customized.selectedIndex);
	}	
	
	function showAddItems(value, typeGrid){
		var typeArray = value.split("|");
		var type = typeArray[1];
		switch(type){
			case 'selectbox':
			case 'radiobutton':
			case 'listbox':
			case 'checkbox':
				if(typeGrid == 'master'){
					document.getElementById('dv_list_items_option').style.display = "block";	
					document.getElementById('add_del_item_custom').style.display = "block";	
				} else {
					document.getElementById('dv_list_items_option_grd_detail').style.display = "block";
					document.getElementById('add_del_item_custom_grd_detail').style.display = "block";
				}
				
			break;
			
			default:
				if(typeGrid == 'master'){
					document.getElementById('dv_list_items_option').style.display = "none";	
					document.getElementById('add_del_item_custom').style.display = "none";	
				} else {
					document.getElementById('dv_list_items_option_grd_detail').style.display = "none";
					document.getElementById('add_del_item_custom_grd_detail').style.display = "none";
				}
			break;	
		}
		
	}			
	
	
	function addItem(typeGrid){
		if(typeGrid == 'master'){
			var txt_item_value = document.getElementById('txt_item_value');
			var list_items_option = document.getElementById('list_items_option');					
			var items_type = document.getElementById('items_type');	
		} else {
			var txt_item_value = document.getElementById('txt_item_value_grd_detail');
			var list_items_option = document.getElementById('list_items_option_grd_detail');	
			var items_type = document.getElementById('items_type_grd_detail');	
		}
		
		if((txt_item_value.value == '') || (txt_item_value.value == null)){
			alert("Inserire un valore NON vuoto");
			return false;
		}				
		
		try {
			var valArray = txt_item_value.value.split("|");
			if((valArray.length > 2) || (valArray.length < 2)){
				alert("Inserire un valore SEPARATO DAL CARATTERE | ");	
			}
		}
		catch(err) {
			alert("Inserire un valore SEPARATO DAL CARATTERE | ");
		}				
						
		var option = document.createElement("option");
		if(items_type.value == 'sql'){
			list_items_option.innerHTML = "";
			option.text = txt_item_value.value;
			option.value = "sql";
			list_items_option.add(option);	
		} else {
			option.text = txt_item_value.value;
			option.value = txt_item_value.value;;
			list_items_option.add(option);					
		}
		
		
	}


	function removeItem(typeGrid){
		if(typeGrid == 'master'){
			var list_items_option = document.getElementById('list_items_option');					
		} else {
			var list_items_option = document.getElementById('list_items_option_grd_detail');	
		}
		
		if(list_items_option.selectedIndex == -1){
			return false;
		}
		
		list_items_option.remove(list_items_option.selectedIndex);
						
	}			
	
	function setModHierarchy(){
		if(document.getElementById('sel_mod_gerarchia').value == 'figlio'){
			document.getElementById('dv_mod_gen_aggr').style.visibility = "visible";
		} else {
			document.getElementById('dv_mod_gen_aggr').style.visibility = "hidden";
		}
	}
	
	function getModGenAggr(){
		$.ajax({
			url : 'mod_crud_gen/ajaxCall',
			type : 'POST',
			dataType:'json',
			data:{
				call_type:'list_mod_gen_aggr'		
			},
			success : function(data) { 
				var objData = data;
				var selectModGenAggr = document.getElementById('mod_gen_aggr');
				selectModGenAggr.innerHTML = "";
				for(var key in objData){
					//LISTA CAMPI
					var newOption = document.createElement('option');
					newOption.value = objData[key].mod_id;
					newOption.text = objData[key].mod_title;
					selectModGenAggr.appendChild(newOption);
				}		
					
				hideBiscuit();	
				
			}						
		});						
	}
	
	
	function enableDisableCmbPrintHead(value){
		if(value == 'Y'){
			document.getElementById('form_print_head').disabled = false;
		} else {
			document.getElementById('form_print_head').disabled = true;
		}
	}			
	
	
	function selezionaDeselezionaTutti(idCheckMaster,idCheckBox) {
		var checkboxes = document.getElementsByName(idCheckBox);
		var checkMaster = document.getElementById(idCheckMaster);
		for(var i=0, n=checkboxes.length;i<n;i++) {
			if(checkMaster.checked == true){
				checkboxes[i].checked = true;	
			} else {
				checkboxes[i].checked = false;
			}
		} 
		
	}	


	function checkParamGen(){
		var chkfield_name = 0;
		var chkcolumn_name = 0;

		if(document.getElementById('table_name').value == ""){
			alert("Selezionare tabella");
			return false;
		}
		if(document.getElementById('mod_title').value == ""){
			alert("Inserire Nome Modulo");
			return false;
		}
		

		$('input:checkbox.chk-column_name').each(function () {
			if(this.checked == true){
				chkcolumn_name++;
			}
  		});

		if(chkcolumn_name == 0){
			alert("Selezionare almeno un campo per la creazione della griglia");
			return false;
		}		  

		$('input:checkbox.chk-field_name').each(function () {
       		if(this.checked == true){
				chkfield_name++;
			}
  		});

		if(chkfield_name == 0){
			alert("Selezionare almeno un campo per la creazione del form");
			return false;
		}		
		  
		if(confirm('Generare codice per la tabella selezionata?')){
			document.getElementById('frm_gen_mod').submit();
		}		
	}	
</script>
