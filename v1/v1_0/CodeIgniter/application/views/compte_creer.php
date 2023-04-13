<!-----------------------------------------------------------
// NOM DU FICHIER: compte_creer.php
// AUTEUR: Loana MOTTAIS
// DATE DE CREATION: 21/11/2022
// VERSION: v1
//-----------------------------------------------------------
// DESCRIPTION
// Formulaire de création d'un compte dans la base de données
// de l'appli GéoQuiz
//---------------------------------------------------------->
<hr/>

<?php

echo validation_errors();

echo form_open('compte_creer');

?>

<label for="id">Pseudo</label>
<input type="input" name="id" />

<label for="mdp">Mot de passe</label>
<input type="input" name="mdp"/>

<input type="submit" name="submit" value="Créer un compte"/>

</form>