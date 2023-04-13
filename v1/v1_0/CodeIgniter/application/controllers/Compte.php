<!-----------------------------------------------------------
// NOM DU FICHIER: Compte.php
// AUTEUR: Loana MOTTAIS
// DATE DE CREATION: 18/11/2022
// VERSION: v1
//-----------------------------------------------------------
// DESCRIPTION
// Architectue MVC (Model-View-Controller) de la v1 de 
// l'application GéoQuiz.
// Controller de la classe Compte.
//---------------------------------------------------------->

<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Compte extends CI_Controller {
    
    public function __construct() {
        parent::__construct();
        $this->load->model('db_model');
        $this->load->helper('url_helper');
    }

    public function lister() {

        //récupérer le résultat de la fonction count_compte()
        $data['nombre'] = $this->db_model->count_compte();

        //récupérer le résultat de la fonction get_all_compte()
        $data['titre'] = 'Liste des pseudos :';
        $data['pseudos'] = $this->db_model->get_all_compte();

        $this->load->view('templates/haut');
        $this->load->view('templates/menu_visiteur');
        $this->load->view('compte_liste',$data);
        $this->load->view('templates/bas');

    }

    public function creer() {
        $this->load->helper('form');
        $this->load->library('form_validation');

        $this->form_validation->set_rules('id', 'id', 'required');
        $this->form_validation->set_rules('mdp', 'mdp', 'required');

        if ($this->form_validation->run() == FALSE) {
            $this->load->view('templates/haut');
            $this->load->view('templates/menu_visiteur');
            $this->load->view('compte_creer');
            $this->load->view('templates/bas');
        } else {
            $this->db_model->set_compte();
            $this->load->view('templates/haut');
            $this->load->view('templates/menu_visiteur');
            $this->load->view('compte_succes');
            $this->load->view('templates/bas');
        }
    }

}

?>