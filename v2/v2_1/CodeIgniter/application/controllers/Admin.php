<!-----------------------------------------------------------
// NOM DU FICHIER: Admin.php
// AUTEUR: Loana MOTTAIS
// DATE DE CREATION: 30/11/2022
// VERSION: v1
//-----------------------------------------------------------
// DESCRIPTION
// Architectue MVC (Model-View-Controller) de la v1 de 
// l'application GéoQuiz.
// Controller de la classe Admin (administrateur).
//---------------------------------------------------------->

<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Admin extends CI_Controller {
    
    public function __construct() {
        parent::__construct();
        $this->load->model('db_model');
        $this->load->helper('url_helper');
    }

    public function afficher_accueil() {
        $this->load->view('templatesback/haut');         
        $this->load->view('templatesback/menu_admin');      
        $this->load->view('admin_accueil');         
        $this->load->view('templatesback/bas');                      
    }

    public function afficher_infos() {
        $this->load->view('templatesback/haut');         
        $this->load->view('templatesback/menu_admin');      
        $this->load->view('admin_infos');         
        $this->load->view('templatesback/bas');                      
    }

    public function modifier_mdp() {
        $this->load->helper('form');
        $this->load->library('form_validation');

        //règles & messages d'erreur du formulaire de récupération du mot de passe
        $this->form_validation->set_rules('mdp', 'mdp', 'required', array('required' => 'Veuillez entrer votre ancien mot de passe'));
        $this->form_validation->set_rules('new_mdp', 'new_mdp', 'required', array('required' => 'Veuillez entrer un nouveau mot de passe'));
        $this->form_validation->set_rules('new_mdp_2', 'new_mdp_2', 'required|matches[new_mdp]',
                array('required' => 'Veuillez confirmer votre mot de passe', 'matches' => 'La confirmation du mot de passe ne correspond pas au nouveau mot de passe entré dans le champ précédent'));

        //chargement des vues selon données passées dans le formulaire
        if ($this->form_validation->run() == FALSE) {
            $this->load->view('templatesback/haut');         
            $this->load->view('templatesback/menu_admin');      
            $this->load->view('admin_mdp');         
            $this->load->view('templatesback/bas');   
        } else {
            $res = $this->db_model->check_mdp($this->session->userdata('username'));
            if($res == 1) {
                $this->db_model->change_mdp($this->session->userdata('username'));
                $this->load->view('templatesback/haut');
                $this->load->view('templatesback/menu_formateur');
                $this->load->view('admin_mdp_succes');
                $this->load->view('templatesback/bas'); 
            } else {
                $data['error'] = "mdp non changé";
                $this->load->view('templatesback/haut');
                $this->load->view('templatesback/menu_formateur');
                $this->load->view('errors', $data);
                $this->load->view('admin_mdp'); 
                $this->load->view('templatesback/bas'); 
            }
            
        }
    }

    public function deconnecter() {
        if($this->input->post('deconnexion') == "deconnect"){
            session_destroy();
            redirect(base_url()."index.php/compte/connecter");
        }
    }

    public function afficher_profils() {
        $data['profils'] = $this->db_model->get_all_users();
        $this->load->view('templatesback/haut');         
        $this->load->view('templatesback/menu_admin');      
        $this->load->view('admin_users', $data);         
        $this->load->view('templatesback/bas');   

    }


}