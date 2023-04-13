<!-----------------------------------------------------------
// NOM DU FICHIER: bas.php
// AUTEUR: Loana MOTTAIS
// DATE DE CREATION: 10/11/2022
// VERSION: v1
//-----------------------------------------------------------
// DESCRIPTION
// Architectue MVC (Model-View-Controller) de la v1 de 
// l'application GéoQuiz.
// Vue du footer des pages de l'application.
//-----------------------------------------------------------
// A NOTER
// Fermeture des balise <body> et <html>
//---------------------------------------------------------->

<!--problem section -->
<section class="problem_section layout_padding">
  <div class="container">
    <br/>
    <br/>
  </div>
</section>
<!-- end problem section -->

<div class="footer_bg">
    <!-- info section -->
    <section class="info_section layout_padding2-bottom">
      <div class="container">
        <h3 class="">
          GéoQuiz
        </h3>
      </div>
      <div class="container info_content">

        <div>
          <!--
          <div class="row">
            <div class="col-md-6 col-lg-4">
              <div class="d-flex">
                <h5>
                  Useful Link
                </h5>
              </div>
              <div class="d-flex ">
                <ul>
                  <li>
                    <a href="">
                      About Us
                    </a>
                  </li>
                  <li>
                    <a href="">
                      About services
                    </a>
                  </li>
                  <li>
                    <a href="">
                      About Departments
                    </a>
                  </li>
                  <li>
                    <a href="">
                      Services
                    </a>
                  </li>
                  <li>
                    <a href="">
                      Contact Us
                    </a>
                  </li>
                </ul>
                <ul class="ml-3 ml-md-5">
                  <li>
                    <a href="">
                      Loram ipusm
                    </a>
                  </li>
                  <li>
                    <a href="">
                      Loram ipusm
                    </a>
                  </li>
                  <li>
                    <a href="">
                      Loram ipusm
                    </a>
                  </li>
                  <li>
                    <a href="">
                      Loram ipusm
                    </a>
                  </li>
                  <li>
                    <a href="">
                      Loram ipusm
                    </a>
                  </li>
                </ul>
              </div>
            </div>
            <div class="col-md-6 col-lg-4">
              <div class="d-flex">
                <h5>
                  The Services
                </h5>
              </div>
              <div class="d-flex ">
                <ul>
                  <li>
                    <a href="">
                      About Us
                    </a>
                  </li>
                  <li>
                    <a href="">
                      About services
                    </a>
                  </li>
                  <li>
                    <a href="">
                      About Departments
                    </a>
                  </li>
                  <li>
                    <a href="">
                      Services
                    </a>
                  </li>
                  <li>
                    <a href="">
                      Contact Us
                    </a>
                  </li>
                </ul>
                <ul class="ml-3 ml-md-5">
                  <li>
                    <a href="">
                      Lorem ipsum dolor
                    </a>
                  </li>
                  <li>
                    <a href="">
                      sit amet, consectetur
                    </a>
                  </li>
                  <li>
                    <a href="">
                      adipiscing elit,
                    </a>
                  </li>
                  <li>
                    <a href="">
                      sed do eiusmod
                    </a>
                  </li>
                  <li>
                    <a href="">
                      tempor incididunt
                    </a>
                  </li>
                </ul>
              </div>
            </div>
            <div class="col-md-6 col-lg-4">
              <div class="d-flex">
                <h5>
                  Contact Us
                </h5>
              </div>
              <div class="d-flex ">
                <ul>
                  <li>
                    <a href="">
                      About Us
                    </a>
                  </li>
                  <li>
                    <a href="">
                      About services
                    </a>
                  </li>
                  <li>
                    <a href="">
                      About Departments
                    </a>
                  </li>
                  <li>
                    <a href="">
                      Services
                    </a>
                  </li>
                  <li>
                    <a href="">
                      Contact Us
                    </a>
                  </li>
                </ul>
                <ul class="ml-3 ml-md-5">
                  <li>
                    <a href="">
                      Lorem ipsum
                    </a>
                  </li>
                  <li>
                    <a href="">
                      dolor sit amet,
                    </a>
                  </li>
                  <li>
                    <a href="">
                      consectetur
                    </a>
                  </li>
                  <li>
                    <a href="">
                      adipiscing
                    </a>
                  </li>
                  <li>
                    <a href="">
                      elit, sed do eiusmod
                    </a>
                  </li>
                </ul>
              </div>
            </div>
          </div>
          -->
        </div>
        <div class="d-flex flex-column flex-lg-row justify-content-between align-items-center align-items-lg-baseline">
          <div class="social-box">
            <a href="">
              <img src="<?php echo base_url();?>style/images/fb.png" alt=""/>
            </a>

            <a href="">
              <img src="<?php echo base_url();?>style/images/twitter.png" alt=""/>
            </a>
            <a href="">
              <img src="<?php echo base_url();?>style/images/linkedin1.png" alt=""/>
            </a>
            <a href="">
              <img src="<?php echo base_url();?>style/images/instagram1.png" alt=""/>
            </a>
          </div>
          <div class="form_container mt-5">
            <form action="">
              <label for="subscribeMail">
                Newsletter
              </label>
              <input type="email" placeholder="Enter Your email" id="subscribeMail"/>
              <button type="submit">
                Subscribe
              </button>
            </form>
          </div>
        </div>
      </div>

    </section>
    <!-- end info_section -->

   <!-- footer section -->
   <section class="container-fluid footer_section">
      <p>
        © 2019 All Rights Reserved By
        <a href="https://html.design/">Free Html Templates</a>
      </p>
    </section>
    <!-- footer section -->
  </div>


  <script type="text/javascript" src="<?php echo base_url();?>style/js/jquery-3.4.1.min.js"></script>
  <script type="text/javascript" src="<?php echo base_url();?>style/js/bootstrap.js"></script>



</body></html>