<?php
/*VERIFICATION DE LA SESSION*/
if ($_SESSION['role'] != 'F') {
    redirect(base_url()."index.php/compte/connecter");
}
?>

<div class="container">
    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="normal-table-list">
                <div class="basic-tb-hd">
                    <h2>Informations</h2>
                </div>
                <div class="bsc-tbl">
                    <table class="table table-sc-ex">
                        <thead>
                            <tr>
                                <th>Nom</th>
                                <th>Prénom</th>
                                <th>Pseudo</th>
                                <th>Mail</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td><?php echo $infos->PRO_NOM; ?></td>
                                <td><?php echo $infos->PRO_PRENOM; ?></td>
                                <td><?php echo $infos->USR_PSEUDO; ?></td>
                                <td><?php echo $infos->PRO_MAIL; ?></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>