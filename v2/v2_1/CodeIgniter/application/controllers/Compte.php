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

    public function connecter() {

        $this->load->helper('form');
        $this->load->library('form_validation');

        $this->form_validation->set_rules('pseudo', 'pseudo', 'required', array('required' => 'Veuillez entrer un pseudo'));
        $this->form_validation->set_rules('mdp', 'mdp', 'required', array('required' => 'Veuillez entrer un mot de passe'));

        if ($this->form_validation->run() == FALSE) {
            $this->load->view('templates/haut');                //chargement view haut.php
            $this->load->view('templates/menu_visiteur');       //chargement view menu_visiteur.php
            $this->load->view('compte_connecter');         //chargement view milieu : utilisateur_connexion.php
            $this->load->view('templates/bas');                 //chargement view bas.php
        } else {
            $username = $this->input->post('pseudo');
            $password = $this->input->post('mdp');
            if ($this->db_model->connect_compte($username,$password)){  //il existe un couple username/mdp dans la base
                $etat = $this->db_model->get_etat_utilisateur($username);
                if($etat == 'A') {
                    $role = $this->db_model->role_utilisateur($username);
                    if ($role == 'F') { //l'utilisateur qui cherche à se connecter est un formateur
                        $session_data = array('username' => $username, 'role' => $role);
                        $this->session->set_userdata($session_data);
                        $this->load->view('templatesback/haut');                     //chargement view haut.php pour backend
                        $this->load->view('templatesback/menu_formateur');           //chargement view menu_formateur.php
                        $this->load->view('formateur_accueil');            //chargement view formateur_afficher.php
                        $this->load->view('templatesback/bas');                      //formateur view bas.php pour backend
                    } else if ($role == 'A') {  //l'utilisateur qui cherche à se connecter est un administrateur
                        $session_data = array('username' => $username, 'role' => $role);
                        $this->session->set_userdata($session_data);
                        $this->load->view('templatesback/haut');                     //chargement view haut.php pour backend
                        $this->load->view('templatesback/menu_admin');              //chargement view menu_administrateur.php
                        $this->load->view('admin_accueil');                //chargement view administrateur_accueil.php
                        $this->load->view('templatesback/bas');                     //formateur view bas.php pour backend
                    } else {  //dans le cas (normalement impossible) qu'une autre lettre soit entrée dans le champ role -> réafficher la page de connexion
                        $data['error'] = 'lettre role non reconnue';
                        $this->load->view('templates/haut');                //chargement view haut.php
                        $this->load->view('templates/menu_visiteur');       //chargement view menu_visiteur.php
                        $this->load->view('errors', $data);
                        $this->load->view('compte_connecter');              //chargement view milieu : utilisateur_connexion.php
                        $this->load->view('templates/bas');                 //chargement view bas.php
                    }
                } else {
                    $data['error'] = 'desactive';
                    $this->load->view('templates/haut');                //chargement view haut.php
                    $this->load->view('templates/menu_visiteur');       //chargement view menu_visiteur.php
                    $this->load->view('errors', $data);
                    $this->load->view('compte_connecter');              //chargement view milieu : utilisateur_connexion.php
                    $this->load->view('templates/bas');                 //chargement view bas.php
                }
            } else {  //login non reconnu
                $data['error'] = 'login non reconnu';
                $this->load->view('templates/haut');                //chargement view haut.php
                $this->load->view('templates/menu_visiteur');        //chargement view menu_visiteur.php
                $this->load->view('errors', $data);
                $this->load->view('compte_connecter');              //chargement view milieu : utilisateur_connexion.php
                $this->load->view('templates/bas');                 //chargement view bas.php
            }
        }
    }

    
    public function deconnecter() {
        $this->session->sess_destroy();

        $this->load->view('templates/haut');
        $this->load->view('templates/menu_visiteur');
        $this->load->view('compte_connecter');
        $this->load->view('templates/bas');
    }

}

?>