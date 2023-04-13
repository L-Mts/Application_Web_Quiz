<?php
/*VERIFICATION DE LA SESSION*/
if ($_SESSION['role'] != 'F') {
    redirect(base_url()."index.php/compte/connecter");
}
?>

<div class="data-table-area">
        <div class="container">
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="data-table-list">
                        <div class="basic-tb-hd">
                            <center><h1>Matchs</h1></center>
                        </div>
                        <div class="basic-tb-hd">
                            <form action="creer_match" method="post">
                            <center><button type="submit" class="btn btn-success notika-btn-success waves-effect" value="true">
                                    Créer Match</button></center>
                            </form>
                        </div>
                        <?php
                            if ($match != NULL) {
                        ?>
                        <div class="table-responsive">
                            <table id="data-table-basic" class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>Nom du quiz</th>
                                        <th>Auteur du quiz</th>
                                        <th>Match associé</th>
                                        <th>Auteur du match</th>
                                        <th>Dates début & fin</th>
                                        <th>Gestion</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                        foreach($match as $m) {
                                            echo "<tr>";
                                            echo "<td>".$m['QUI_INTITULE']."</td>";
                                            echo "<td>".$m['QUI_PSEUDO']."</td>";
                                            if(strcmp($m['MAT_PSEUDO'], $this->session->userdata('username')) ==0) {
                                                echo "<td><a href='".base_url()."index.php/formateur/afficher_match/".$m['MAT_CODE']."'>".$m['MAT_CODE']."</a></td>";
                                            } else {
                                                echo "<td>".$m['MAT_CODE']."</td>";
                                            }
                                            echo "<td>".$m['MAT_PSEUDO']."</td>";
                                            echo "<td>Début du match : ".$m['MAT_DEBUT']."<br/>Fin du match : ".$m['MAT_FIN']."</td>";
                                            if(strcmp($m['MAT_PSEUDO'], $this->session->userdata('username')) ==0) {
                                            ?>
                                                <td>
                                                    <form action="raz_match" method="post">
                                                        <input type="hidden" value="<?php echo $m['MAT_CODE'];?>" name="code">
                                                        <button type="submit" class="btn btn-warning notika-btn-warning btn-xs waves-effect">RAZ</button>
                                                    </form>
                                                    <form action="supprimer_match" method="post">
                                                        <input type="hidden" value="<?php echo $m['MAT_ID'];?>" name="id">
                                                        <button type="submit" class="btn btn-danger notika-btn-danger btn-xs waves-effect">SUPPRIMER</button>
                                                    </form>
                                                    <?php
                                                    if ($m['MAT_ACTIF']==1) { //match activé
                                                    ?>
                                                    <form action="desactiver_match" method="post">
                                                        <input type="hidden" value="<?php echo $m['MAT_CODE'];?>" name="code">
                                                        <button type="submit" class="btn btn-success notika-btn-success btn-xs waves-effect">Désactiver</button>
                                                    </form>
                                                    <?php
                                                    } else {  //match désactivé
                                                    ?>
                                                    <form action="activer_match" method="post">
                                                        <input type="hidden" value="<?php echo $m['MAT_CODE'];?>" name="code">
                                                        <button type="submit" class="btn btn-success notika-btn-success btn-xs waves-effect">Activer</button>
                                                    </form>
                                                    <?php
                                                    }
                                                    ?>
                                                </td>
                                            <?php
                                            } else {
                                                echo "<td>Gestion réservée à l'auteur du match</td>";
                                            }
                                            echo "</tr>";
                                        }
                                    ?>
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th>Nom du quiz</th>
                                        <th>Auteur du quiz</th>
                                        <th>Match associé</th>
                                        <th>Auteur du match</th>
                                        <th>Dates début & fin</th>
                                        <th>Gestion</th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                        <?php
                            } else {
                                echo "<p>Aucune donnée à afficher</p>";
                            }
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Data Table area End-->