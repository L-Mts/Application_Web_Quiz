<?php
/*VERIFICATION DE LA SESSION*/
if ($_SESSION['role'] != 'F') {
    redirect(base_url()."index.php/compte/connecter");
}
?>

<div class="form-element-area">
    <div class="container">
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="form-element-list mg-t-30">
                    <div class="cmp-tb-hd">
                        <h2>Modification du mot de passe</h2>
                    </div>
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <?php echo validation_errors(); ?>
                            <?php echo form_open('mdp_change'); ?>
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <div class="form-group ic-cmp-int">
                                        <div class="form-ic-cmp">
                                        </div>
                                        <div class="nk-int-st">
                                            <input type="text" class="form-control" name="mdp" placeholder="Mot de passe"/>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">   
                                    <div class="form-group ic-cmp-int">
                                        <div class="form-ic-cmp">
                                        </div>
                                        <div class="nk-int-st">
                                            <input type="text" class="form-control" name="new_mdp" placeholder="Nouveau mot de passe"/>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <div class="form-group ic-cmp-int">
                                        <div class="form-ic-cmp">
                                        </div>
                                        <div class="nk-int-st">
                                            <input type="text" class="form-control" name="new_mdp_2" placeholder="Confirmer nouveau mot de passe"/>
                                        </div>
                                    </div>
                                </div>
                                <button class="btn btn-primary notika-btn-primary waves-effect">
                                    Valider
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>