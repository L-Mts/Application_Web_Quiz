<?php
/*VERIFICATION DE LA SESSION*/
if ($_SESSION['role'] != 'A') {
    redirect(base_url()."index.php/compte/connecter");
}
?>


<div class="container">
    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <h2>Confirmation de modification du mot de passe</h2>
            <p>Votre mot de passe a bien été changé !</p>
            </div>
        </div>
    </div>
</div>