<section class="problem_section layout_padding">
  <h3>
    Connexion Formateurs & Administrateurs
  </h3>
  <!-- Formulaire pour entrer le code d'un match -->
  <div class="container">
        <div class="form_container">
          <?php echo validation_errors(); ?>
          <?php echo form_open('cpt_connexion'); ?>
            <label>Saisissez vos identifiants ici :<label><br>
            <input type="text" placeholder="Pseudo" name="pseudo"/>
            <input type="text" placeholder="Mot de passe" name="mdp"/>
            <button class="btn btn-standard" type="submit">
              Valider
            </button>
          </form>
        </div>
  </div>
</section>