<?php

if (strcmp($error, 'pseudo existant') == 0) {
    ?>
    <section class="problem_section layout_padding">
        <center><p>Attention ce pseudo existe déjà, veuillez en saisir un autre !</p></center>
    </section>
    <?php
}

if (strcmp($error, 'match non existant') == 0) {
    ?>
    <section class="problem_section layout_padding">
        <center><p>Attention ce code de match n'existe pas, veuillez en saisir un autre !</p></center>
    </section>
    <?php
}

if (strcmp($error, 'match désactivé') == 0) {
    ?>
    <section class="problem_section layout_padding">
        <center><p>Ce match est désactivé, vous ne pouvez pas y accéder, essayez-en un autre.</p></center>
    </section>
    <?php
}

if (strcmp($error, 'match non commencé') == 0) {
    ?>
    <section class="problem_section layout_padding">
        <center><p>Ce match n'a pas encore commencé, vous ne pouvez pas y accéder, essayez-en un autre.</p></center>
    </section>
    <?php
}

if (strcmp($error, 'lettre role non reconnue') == 0) {
    ?>
    <section class="problem_section layout_padding">
        <center>
            <p>Votre rôle inscrit dans la base de donnée n'a pas été reconnu comme formateur ou administrateur.</p>
            <p>Si cette erreur se reproduit, veuillez contacter un administrateur :</p>
            <a href="mailto:loana.mottais@etudiant.univ-brest.fr">Envoyer un mail à un administrateur</a>
        </center>
    </section>
    <?php
}

if (strcmp($error, 'login non reconnu') == 0) {
    ?>
    <section class="problem_section layout_padding">
        <center><p>Le couple pseudo - mot de passe n'a pas été reconnu, veuillez réessayer.</p></center>
    </section>
    <?php
}

?>

