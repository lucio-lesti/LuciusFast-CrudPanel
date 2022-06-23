<?php 
//CHECK SE INDIRIZZO IP AUTORIZZATO
if(!isset($ip_access_denied)){ 
?>
<div class="content-wrapper">
    <section class="content-header">
        <h1>
            404
            <small>PAGINA NON TROVATA</small>
        </h1>
    </section>
    <section class="content">
        <div class="row">
            <div class="col-xs-12 text-center">
                <img src="<?php echo base_url() ?>assets/images/404.png" alt="Page Not Found Image" />
            </div>
        </div>
    </section>
</div>
<?php } ?>