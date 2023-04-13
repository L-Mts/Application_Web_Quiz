<?php

if (strcmp($message, 'raz') == 0){
    ?>
        <div class="container">
            <div class="row">
                <p>Le match a été remis à zéro, par défaut la nouvelle date de début de match choisie est demain.</p>
            </div>
        </div>
    <?php
}

if (strcmp($message, 'supprimer') == 0){
    ?>
        <div class="container">
            <div class="row">
                <p>Le match a bien été supprimé.</p>
            </div>
        </div>
    <?php
}

if (strcmp($message, 'activer') == 0){
    ?>
        <div class="container">
            <div class="row">
                <p>Le match a bien été activé.</p>
            </div>
        </div>
    <?php
}

if (strcmp($message, 'desactiver') == 0){
    ?>
        <div class="container">
            <div class="row">
                <p>Le match a bien été désactivé.</p>
            </div>
        </div>
    <?php
}

if (strcmp($message, 'creation') == 0){
    ?>
        <div class="container">
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <p>ATTENTION : seul les quiz complets (pour lesquels il y a des questions, et ces questions ont des options de réponses) sont disponibles dans les choix de quiz, même si les quizs incomplets sont affichés dans le tableau de gestion des matchs</p>
                    <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                        <?php 
                        echo validation_errors();
                        echo form_open('match_creer');
                        ?>
                            <div class="form-group">
                                <label>Sélection du quiz</label>
                                <select class="form-control" name="quiz">
                                    <?php
                                        foreach($quiz as $q){
                                            if (!isset($traite[$q['QUI_ID']])) {
                                                if($q['QST_ID']!=null || $q['REP_ID'] !=null) {
                                                echo "<option value='".$q['QUI_ID']."'>".$q['QUI_INTITULE']."</option>";
                                                }
                                            }
                                            $traite[$q['QUI_ID']] = 1;
                                        }
                                    ?>
                                </select>
                                <button type="submit" classe="btn-success notika-btn-success waves-effect">Valider</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    <?php
}

if (strcmp($message, 'créé') == 0){
    ?>
        <div class="container">
            <div class="row">
                <p>Match créé !</p>
            </div>
        </div>
    <?php
}
?>