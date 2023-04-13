<section class="problem_section layout_padding">
<div class="container">
    <?php
        if($match != NULL) {
    ?>
        <div class="custom_heading-container">
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
        <div class="layout_padding2">
            <div class="detail-box">
                <table class="table">
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
        </div>
    <?php
        } else {
            echo "Aucun résultat !";
        }
    ?>
</div>
</section>