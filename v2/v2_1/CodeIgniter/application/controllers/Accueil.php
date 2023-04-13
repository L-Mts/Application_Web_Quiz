<!-----------------------------------------------------------
// NOM DU FICHIER: Accueil.php
// AUTEUR: Loana MOTTAIS
// DATE DE CREATION: 10/11/2022
// VERSION: v1
//-----------------------------------------------------------
// DESCRIPTION
// Architectue MVC (Model-View-Controller) de la v1 de 
// l'application GéoQuiz.
// Controller de la classe Accueil.
//---------------------------------------------------------->

<?php

class Accueil extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->helper('url');
        $this->load->model('db_model');

    }


    public function afficher() {

        $this->load->helper('form');
        $this->load->library('form_validation');

        $this->form_validation->set_rules('code', 'code', 'required', array('required'=>'Veuillez saisir un code de match'));

        if ($this->form_validation->run() == FALSE) {
            $data['actu']=$this->db_model->get_all_actualite();
            //chargement view haut.php
            $this->load->view('templates/haut');
            $this->load->view('templates/menu_visiteur');
            //chargement view milieu : page_accueil.php
            $this->load->view('page_accueil',$data);
            //chargement view bas.php
            $this->load->view('templates/bas');
        } else {
            $data['code'] = $this->input->post('code');
            $data['verif'] = $this->db_model->check_match_code();
            $data['now'] = $this->db_model->get_now();
            if($data['verif'] != NULL) { //le code du match existe
                if ($data['verif']->MAT_ACTIF == 0) { //le match est désactivé
                    $data['error'] = 'match désactivé';
                    $data['actu']=$this->db_model->get_all_actualite();
                    $this->load->view('templates/haut');
                    $this->load->view('templates/menu_visiteur');
                    $this->load->view('errors', $data);
                    $this->load->view('page_accueil',$data);
                    $this->load->view('templates/bas');
                } else if ($data['verif']->MAT_DEBUT == NULL || $data['verif']->MAT_DEBUT > $data['now']->now) {
                    $data['error'] = 'match non commencé';
                    $data['actu']=$this->db_model->get_all_actualite();
                    $this->load->view('templates/haut');
                    $this->load->view('templates/menu_visiteur');
                    $this->load->view('errors', $data);
                    $this->load->view('page_accueil',$data);
                    $this->load->view('templates/bas');
                } else {
                    $this->load->view('templates/haut');
                    $this->load->view('templates/menu_visiteur');
                    $this->load->view('match_pseudo', $data);
                    $this->load->view('templates/bas');
                }
            } else { //le code du match n'existe pas
                $data['error'] = 'match non existant';
                $data['actu']=$this->db_model->get_all_actualite();
                $this->load->view('templates/haut');
                $this->load->view('templates/menu_visiteur');
                $this->load->view('errors', $data);
                $this->load->view('page_accueil',$data);
                $this->load->view('templates/bas');
            }
        }

    }


}

?>