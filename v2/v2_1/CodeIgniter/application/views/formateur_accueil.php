<?php
/*VERIFICATION DE LA SESSION*/
if ($_SESSION['role'] != 'F') {
    redirect(base_url()."index.php/compte/connecter");
}
?>

<!-- Start Sale Statistic area-->
<div class="sale-statistic-area">
    <div class="container">
        <div class="row">
            <div class="col-lg-12 col-md-11 col-sm-10 col-xs-12">
                <div class="sale-statistic-inner notika-shadow mg-tb-30">
                    <div class="curved-inner-pro">
                        <div class="curved-ctn">
                            <h1>Bienvenue</h1>
                            <p><?php echo $this->session->userdata('username'); ?> bienvenue dans votre espace formateur !</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- End Sale Statistic area-->