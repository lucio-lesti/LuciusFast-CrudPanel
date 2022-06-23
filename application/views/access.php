<?php 
//CHECK SE INDIRIZZO IP AUTORIZZATO
if(!isset($ip_access_denied)){ 
?>
<div class="content-wrapper">
    <section class="content-header">
        <h1>
            Accesso negato
            <small>Non sei autorizzato ad accedere a questa pagina.</small>
        </h1>
    </section>
    <section class="content">
        <div class="row">
            <div class="col-xs-12 text-center">
                <img src="<?php echo base_url() ?>assets/images/access.png" alt="Access Denied Image" />
            </div>
        </div>
    </section>
</div>
<?php } ?>