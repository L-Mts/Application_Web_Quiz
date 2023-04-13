<section class="problem_section layout_padding">
  <h3>
    Match
  </h3>
  <!-- Formulaire pour entrer le code d'un match -->
  <div class="container">
      <div class="form_container">
        <?php echo validation_errors(); ?>
        <?php echo form_open('match_code'); ?>
          <input type="input" placeholder="Code du match" name="code"/>
          <button class="btn btn-standard" type="submit">
            Valider
          </button>
        </form>
      </div>
  </div>
</section>

<!-- welcome section -->
<section class="welcome_section layout_padding">
  <div class="container">
    <div class="custom_heading-container">
      <h2>
        Actualités
      </h2>
    </div>
    <?php
      if($actu != NULL) {
    ?>
        <table class="table table-striped">
          <thead>
            <tr>
              <th>Titre</th>
              <th>Contenu</th>
              <th>Auteur</th>
              <th>Date</th>
            </tr>
          </thead>
          <tbody>
            <?php
                foreach($actu as $a){
                  ?>
                  <tr>
                    <td><a href="<?php echo base_url();?>index.php/actualite/afficher/<?php echo $a["ACT_ID"];?>">
                      <?php echo $a["ACT_TITRE"];?>
                    </a></td>
                    <td><?php echo $a["ACT_CONTENU"]; ?></td>
                    <td><?php echo $a["USR_PSEUDO"]; ?></td>
                    <td><?php echo $a["ACT_DATE"]; ?></td>
                  </tr>
                  <?php
                }
            ?>
          </tbody>
        </table>
      <?php
      } else {
        ?>
          <center><p>Il n'y a aucune actualité à afficher !</p></center>
        <?php
      }
      ?>
  </div>
</section>
<!-- end welcome section -->