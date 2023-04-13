<section class="problem_section layout_padding">
  <h3>
    Match
  </h3>
  <!-- Formulaire pour entrer le code d'un match -->
  <div class="container">
        <div class="form_container">
          <?php echo validation_errors(); ?>
          <?php echo form_open('match_pseudo'); ?>
            <input type="input" placeholder="Pseudo du joueur" name="pseudo"/>
            <input type="hidden" value="<?php echo $code;?>", name="code"/>
            <button class="btn btn-standard" type="submit">
              Valider
            </button>
          </form>
        </div>
  </div>
</section>