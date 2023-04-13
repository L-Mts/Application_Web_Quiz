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
                            <th>Réponse</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                            echo validation_errors();
                            echo form_open('reponse_match');
                            foreach($match as $info){
                                if (!isset($traite[$info['QST_ID']])) {
                                    $id_qst=$info['QST_ID'];
                        ?>
                                    <tr>
                                        <td><?php echo $info['QST_ORDRE']?></td>
                                        <td><?php echo $info['QST_INTITULE']?></td>
                                        <td>
                                                <select class="form-control" name="<?php echo $id_qst; ?>">
                                                    <option value="0" selected disabled hidden>Choisir une réponse</option>
                                                    <?php
                                                    foreach($match as $m){
                                                        if(strcmp($id_qst,$m['QST_ID'])==0) {
                                                            echo "<option value='".$m['REP_VRAIE']."'>".$m['REP_TEXTE']."</option>";
                                                        }
                                                    }
                                                ?>
                                                </select>
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
                <input type="hidden" value="<?php echo $pseudo;?>" name="pseudo">
                <input type="hidden" value="<?php echo $code;?>" name="code">
                <button type="submit" class="btn btn-success">Valider</button>
                </form>
            </div>
        </div>
    <?php
        } else {
            echo "Aucun résultat !";
        }
    ?>
</div>
</section>