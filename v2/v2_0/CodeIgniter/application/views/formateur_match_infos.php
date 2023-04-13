<div class="normal-table-area">
    <?php
        if($match != NULL) {
    ?>
    <div class="container">
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="normal-table-list">
                    <div class="basic-tb-hd">
                        <h2>
                        <?php
                            foreach ($match as $intitule) {
                                if(!isset($traite[$intitule['QUI_INTITULE']])) {
                                    echo $intitule['QUI_INTITULE'];
                                    $traite[$intitule['QUI_INTITULE']]=1;
                                }
                            }
                        ?>
                        </h2>
                    </div>
                    <div class="bsc-tbl">
                        <table class="table table-sc-ex">
                            <thead>
                                <tr>
                                    <th>Numéro de question</th>
                                    <th>Question</th>
                                    <th>Réponses</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                    foreach($match as $info){
                                        if (!isset($traite[$info['QST_ID']])) {
                                            $id_qst=$info['QST_ID'];
                                ?>
                                            <tr>
                                                <td><?php echo $info['QST_ORDRE']?></td>
                                                <td><?php echo $info['QST_INTITULE']?></td>
                                                <td>
                                                    <?php
                                                        foreach($match as $rep){
                                                            echo "<ul>";
                                                            if(strcmp($id_qst,$rep['QST_ID'])==0) {
                                                                echo "<li>";
                                                                echo $rep['REP_TEXTE'];
                                                                echo " -- ";
                                                                if ($rep['REP_VRAIE']==1) {
                                                                    echo "VRAI";
                                                                } else {
                                                                    echo "FAUX";
                                                                }
                                                                echo "</li>";
                                                            }
                                                            echo "</ul>";
                                                        }
                                                    ?>
                                                </td>
                                                <?php
                                                    $traite[$info['QST_ID']]=1;
                                                ?>
                                            </tr>
                                <?php
                                        }
                                    }
                                ?>
                            </tbody>
                        </table>
                    </div>
                <div>
            </div>
        </div>
        <?php
            } else {
                echo "<center>Aucun résultat !</center>";
            }
        ?>
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <br/>
                <center>
                <h3>Score</h3>
                <?php
                    if ($nb_joueurs != NULL){
                ?>
                        <p>Joueurs inscrits : <?php echo $nb_joueurs->nb_joueurs; ?></p>
                        <p>Joueurs ayant répondus : <?php echo $nb_participants->nb_participants; ?></p>
                        <?php
                        if($nb_participants->nb_participants != 0){
                            echo "<p>Score total des joueurs ayant participé = ".$score."%</p>";
                        }
                        ?>
                <?php
                    } else {
                        echo "<center><p>Aucun joueur inscrit !</p><center>";
                    }
                ?>
                </center>
            </div>
        </div>
    </div>
</div>