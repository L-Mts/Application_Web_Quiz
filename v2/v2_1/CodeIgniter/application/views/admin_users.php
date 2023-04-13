<?php
/*VERIFICATION DE LA SESSION*/
if ($_SESSION['role'] != 'A') {
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
                                <th>Pseudo</th>
                                <th>Nom</th>
                                <th>Prénom</th>
                                <th>Mail</th>
                                <th>Role</th>
                                <th>Actif</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                                foreach($profils as $pro) {
                                    echo "<tr>";
                                    echo "<td>".$pro['USR_PSEUDO']."</td>";
                                    echo "<td>".$pro['PRO_NOM']."</td>";
                                    echo "<td>".$pro['PRO_PRENOM']."</td>";
                                    echo "<td>".$pro['PRO_MAIL']."</td>";
                                    echo "<td>".$pro['USR_ROLE']."</td>";
                                    if($pro['USR_ETAT'] == 1) {
                                        echo "<td>Activé</td>";
                                    } else {
                                        echo "<td>Désactivé</td>";
                                    }
                                    echo "</tr>";
                                }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>