
			<?php
		$html = '<div>
		<section class="content">
			<div class="row">
				<div class="col-md-12">
					<div class="box box-primary">
						<div class="box-body">
						<div id="msg_err" ondblclick="this.style.display=\'none\';" style="display: none; border: 1px solid; background-color: rgb(255, 64, 0); border-radius: 5px; padding: 10px; color: white; font-weight: bold;">
						</div>									
							<form  name="frm_master_detail" id="frm_master_detail">
							<input type="hidden" id="table" name="table" value="_mod_enti_discipline">
							<input type="hidden" id="action" name="action" value="'.$action.'"/> 
							<input type="hidden" id="saveType" name="saveType" value="form"/> 	
							<input type="hidden" id="entryID"          name="entryID"  value="'.$entryID.'">													
								<div class="col-md-12">
									<div class="form-group">';
		$html .= '
								</div>													
							</div>
						</div>
					</div>
				</div>
				</form>
			</section>
		</div>';