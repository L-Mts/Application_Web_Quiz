<?php

class Utilisateur extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->helper('url');
        $this->load->model('db_model');

    }


    public function connexion() {

        $this->load->helper('form');
        $this->load->library('form_validation');

        $this->load->view('templates/haut');                //chargement view haut.php
        $this->load->view('templates/menu_visiteur');       //chargement view menu_visiteur.php
        $this->load->view('utilisateur_connexion');         //chargement view milieu : utilisateur_connexion.php
        $this->load->view('templates/bas');                 //chargement view bas.php

    }


}

?>