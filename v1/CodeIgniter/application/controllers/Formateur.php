<!-----------------------------------------------------------
// NOM DU FICHIER: Formateur.php
// AUTEUR: Loana MOTTAIS
// DATE DE CREATION: 30/11/2022
// VERSION: v1
//-----------------------------------------------------------
// DESCRIPTION
// Architectue MVC (Model-View-Controller) de la v1 de 
// l'application GéoQuiz.
// Controller de la classe Formateur.
//---------------------------------------------------------->

<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Formateur extends CI_Controller {
    
    public function __construct() {
        parent::__construct();
        $this->load->model('db_model');
        $this->load->helper('url_helper');
    }

    public function afficher_accueil() {
        $this->load->view('templatesback/haut');         
        $this->load->view('templatesback/menu_formateur');      
        $this->load->view('formateur_accueil');         
        $this->load->view('templatesback/bas');                      
    }

    public function deconnecter() {
        if($this->input->post('deconnexion') == "deconnect"){
            session_destroy();
            redirect(base_url()."index.php/compte/connecter");
        }
    }

    public function afficher_infos() {
        //récupération des informations concernant la personne
        $data['infos'] = $this->db_model->get_infos_formateur($this->session->userdata('username'));

        $this->load->view('templatesback/haut');         
        $this->load->view('templatesback/menu_formateur');      
        $this->load->view('formateur_infos', $data);         
        $this->load->view('templatesback/bas');                      
    }

    public function modifier_mdp() {
        $this->load->helper('form');
        $this->load->library('form_validation');

        //règles & messages d'erreur du formulaire de récupération du mot de passe
        $this->form_validation->set_rules('mdp', 'mdp', 'required', array('required' => 'Veuillez entrer votre ancien mot de passe'));
        $this->form_validation->set_rules('new_mdp', 'new_mdp', 'required', array('required' => 'Veuillez entrer un nouveau mot de passe'));
        $this->form_validation->set_rules('new_mdp_2', 'new_mdp_2', 'required|matches[new_mdp]', array('required' => 'Veuillez confirmer votre mot de passe', 'matches[new_mdp]' => 'Ne correspond pas au nouveau mot de passe entré dans le champ précédent'));

        //chargement des vues selon données passées dans le formulaire
        if ($this->form_validation->run() == FALSE) {
            $this->load->view('templatesback/haut');         
            $this->load->view('templatesback/menu_formateur');      
            $this->load->view('formateur_mdp');         
            $this->load->view('templatesback/bas');   
        } else {
            $this->db_model->change_mdp($this->session->userdata('username'));
            $this->load->view('templatesback/haut');
            $this->load->view('templatesback/menu_formateur');
            $this->load->view('formateur_mdp_succes');
            $this->load->view('templatesback/bas'); 
            
        }
    }

    public function afficher_matchs() {
        $data['match'] = $this->db_model->get_all_match();
        $this->load->view('templatesback/haut');
        $this->load->view('templatesback/menu_formateur');
        $this->load->view('formateur_match',$data);
        $this->load->view('templatesback/bas');
    }

    public function afficher_match($mat_code=FALSE){
        if ($mat_code==FALSE) {
            redirect(base_url()."formateur/afficher_matchs");
        } else {
            $data['match'] = $this->db_model->get_match_info($mat_code);
            $data['nb_joueurs'] = $this->db_model->get_nb_joueurs($mat_code);
            $data['nb_participants'] = $this->db_model->get_nb_participants($mat_code);
            $data['score'] =  $this->db_model->get_score_total($mat_code);

            $this->load->view('templatesback/haut');
            $this->load->view('templatesback/menu_formateur');
            $this->load->view('formateur_match_infos',$data);
            $this->load->view('templatesback/bas');
            
        }
    }

}