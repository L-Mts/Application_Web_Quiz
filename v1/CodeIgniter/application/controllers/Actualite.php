<!-----------------------------------------------------------
// NOM DU FICHIER: Actualité.php
// AUTEUR: Loana MOTTAIS
// DATE DE CREATION: 18/11/2022
// VERSION: v1
//-----------------------------------------------------------
// DESCRIPTION
// Architectue MVC (Model-View-Controller) de la v1 de 
// l'application GéoQuiz.
// Controller de la classe Actualité.
//---------------------------------------------------------->

<?php

defined ('BASEPATH') OR exit('No direct script acces allowed');

class Actualite extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('db_model');
        $this->load->helper('url_helper');
    }

    public function afficher($numero =FALSE){  //'=FALSE' -> valeur paramètre par défaut si il n'y en a pas / est supprimé ou autre --> permet de gérer la redirection
        if ($numero==FALSE) {
            $url=base_url();
            header("Location:$url");
        } else {
            $data['titre'] = 'Actualité :';
            $data['actu'] = $this->db_model->get_actualite($numero);

            $this->load->view('templates/haut');
            $this->load->view('templates/menu_visiteur');
            $this->load->view('actualite_afficher',$data);
            $this->load->view('templates/bas');
            
        }
    }

}


?>