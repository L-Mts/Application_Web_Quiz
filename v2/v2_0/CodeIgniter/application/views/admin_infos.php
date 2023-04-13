<?php
/*VERIFICATION DE LA SESSION*/
if ($_SESSION['role'] != 'A') {
    redirect(base_url()."index.php/compte/connecter");
}
?>

<div class="container">
    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <h2>Informations</h2>
            <p>Un administrateur n'ayant pas de profil personnel dans notre base de données, seul votre pseudo est affiché</p>
            <br/>
            <p>Pseudo : <?php echo $_SESSION['username'];?></p>
        </div>
    </div>
</div>