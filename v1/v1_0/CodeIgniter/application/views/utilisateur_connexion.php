<section class="problem_section layout_padding">
  <h3>
    Connexion Formateurs & Administrateurs
  </h3>
  <!-- Formulaire pour entrer le code d'un match -->
  <div class="container">
        <div class="form_container">
          <?php echo validation_errors(); ?>
          <?php echo form_open('uti_connexion'); ?>
            <input type="input" placeholder="Pseudo" name="pseudo"/>
            <input type="input" placeholder="Mot de passe" name="mdp"/>
            <button class="btn btn-standard" type="submit">
              Valider
            </button>
          </form>
        </div>
  </div>
</section>