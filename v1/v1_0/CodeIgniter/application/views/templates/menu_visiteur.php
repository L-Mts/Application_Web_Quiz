<!-----------------------------------------------------------
// NOM DU FICHIER: menu_visiter.php
// AUTEUR: Loana MOTTAIS
// DATE DE CREATION: 10/11/2022
// VERSION: v1
//-----------------------------------------------------------
// DESCRIPTION
// Architectue MVC (Model-View-Controller) de la v1 de 
// l'application GéoQuiz.
// Vue chargeant la barre de navigation pour la partie
// front (pour les visiteurs & joueurs) de l'application
// (entre balises <header></header>).
//---------------------------------------------------------->

<div class="hero_area">
    <!-- header section strats -->
    <header class="header_section">
      <div class="container-fluid">
        <div class="row">
          <div class="col-lg-8">
            <nav class="navbar navbar-expand-lg custom_nav-container ">
              <a class="navbar-brand" href="<?php echo base_url();?>index.php">
                <span>
                  BigWing
                </span>
              </a>
              <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
              </button>

              <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <div class="d-flex  flex-column flex-lg-row align-items-center">
                  <ul class="navbar-nav  ">
                    <li class="nav-item active">
                      <a class="nav-link" href="<?php echo base_url();?>index.php">Home <span class="sr-only">(current)</span></a>
                    </li>
                  </ul>
                  <form class="form-inline my-2 my-lg-0 ml-0 ml-lg-4 mb-3 mb-lg-0">
                    <button class="btn  my-2 my-sm-0 nav_search-btn" type="submit"></button>
                  </form>
                </div>
              </div>
            </nav>
          </div>
          <div class="col-lg-2">
          </div>
          <div class="col-lg-2">
            <a href="<?php echo base_url();?>index.php/utilisateur/connexion" class="btn btn-standard"  role="button">Connexion</a>
          </div>
        </div>
      </div>
    </header>
    <!-- end header section -->

    <!-- slider section -->
    <section class=" slider_section ">
      <div id="carouselExampleIndicators" class="carousel slide" data-ride="carousel">
        <div class="carousel-inner">
          <div class="carousel-item active">
            <div class="container">
              <div class="row">
                <div class="col-lg-5 col-md-6">
                  <div class="slider_detail-box">
                    <h1>
                      GéoQuiz
                    </h1>
                  </div>
                </div>
                <div class="col-md-4">
                </div>
                <div class="col-md-2">
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>
    <!-- end slider section -->
  </div>


