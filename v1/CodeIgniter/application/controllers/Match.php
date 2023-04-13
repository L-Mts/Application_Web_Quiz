<!-----------------------------------------------------------
// NOM DU FICHIER: Match.php
// AUTEUR: Loana MOTTAIS
// DATE DE CREATION: 18/11/2022
// VERSION: v1
//-----------------------------------------------------------
// DESCRIPTION
// Architectue MVC (Model-View-Controller) de la v1 de 
// l'application GéoQuiz.
// Controller de la classe Match.
//---------------------------------------------------------->

<?php

defined ('BASEPATH') OR exit('No direct script acces allowed');

class Match extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->helper('url');
        $this->load->model('db_model');
    }


    public function afficher_url($id =FALSE) {  //'=FALSE' -> valeur paramètre par défaut si il n'y en a pas ou est supprimé ou autre --> permet de gérer la redirection
        if ($id==FALSE) {
            $url=base_url();
            header("Location:$url");
        } else {
            $data['titre'] = 'Match : ';
            $data['match'] = $this->db_model->get_match_info($id);

            $this->load->view('templates/haut');
            $this->load->view('templates/menu_visiteur');
            $this->load->view('match_info',$data);
            $this->load->view('templates/bas');
            
        }
    }

    public function afficher() {

        $this->load->helper('form');
        $this->load->library('form_validation');

        $this->form_validation->set_rules('pseudo', 'pseudo', 'trim|required|alpha_numeric', array('required'=>'Veuillez saisir un nouveau pseudo','alpha_numeric'=>'Les caractères spéciaux ne sont pas autorisés'));


        if ($this->form_validation->run() == FALSE) {
            $data['code'] = $this->input->post('code');
            $data['verif'] = $this->db_model->check_match_code();
            $this->load->view('templates/haut');
            $this->load->view('templates/menu_visiteur');
            $this->load->view('match_pseudo', $data);
            $this->load->view('templates/bas');
        } else {
            $data['code'] = $this->input->post('code');
            $data['pseudo'] = $this->input->post('pseudo');
            $data['joueur'] = $this->db_model->check_pseudo_match();  //vérification que le pseudo n'existe pas déjà dans la base
            if ($data['joueur'] == NULL) { //il n'existe pas de joueur ayant ce pseudo
                $this->db_model->set_joueur();
                $data['match'] = $this->db_model->get_match_info($data['code']);
                $this->load->view('templates/haut');
                $this->load->view('templates/menu_visiteur');
                $this->load->view('match_info',$data);
                $this->load->view('templates/bas');
            } else {  //il existe un joueur avec ce pseudo --> il faut changer de pseudo
                $data['error'] = 'pseudo existant';
                $this->load->view('templates/haut');
                $this->load->view('templates/menu_visiteur');
                $this->load->view('errors', $data);
                $this->load->view('match_pseudo', $data);
                $this->load->view('templates/bas');
            }
        }

    }
}

?>