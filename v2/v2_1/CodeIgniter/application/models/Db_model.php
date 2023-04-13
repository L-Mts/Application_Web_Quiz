<!-----------------------------------------------------------
// NOM DU FICHIER: Db_model.php
// AUTEUR: Loana MOTTAIS
// DATE DE CREATION: 10/11/2022
// VERSION: v1
//-----------------------------------------------------------
// DESCRIPTION
// Partie Model de l'architectue MVC (Model-View-Controller)
// de la v1 de l'application GéoQuiz
//---------------------------------------------------------->

​​​​​<?php 
class Db_model extends CI_Model {

	//constructeur de la classe Db_model
	public function __construct() {
		parent::__construct();
		$this->load->database();
	}


	/* ------------- ACTUALITES -------------*/

	//fonction récupérant les 5 dernières actualités
	public function get_all_actualite() {
		$query = $this->db->query("SELECT * FROM T_ACTUALITE_ACT join T_UTILISATEUR_USR USING (USR_ID) LIMIT 5;");
		/*test bonne exécution requête*/
		return $query->result_array();
	}

	//fonction récupérant les infos d'une actualité précise (ID connu)
	public function get_actualite($numero) {
		$query = $this->db->query("SELECT * FROM T_ACTUALITE_ACT JOIN T_UTILISATEUR_USR USING (USR_ID) WHERE ACT_ID=".$numero.";");
		/*test bonne exécution requête*/
		return $query->row();
	}




	/* ------------- COMPTES -------------*/

	//fonction récupérant les pseudos de tous les comptes formateurs & administrateurs
	public function get_all_compte() {
		$query = $this->db->query("SELECT USR_PSEUDO FROM T_UTILISATEUR_USR;");
		/*test bonne exécution requête*/
		return $query->result_array();
	}

	//fonction comptant le nombre de comptes dans la base
	public function count_compte() {
		$query = $this->db->query("SELECT COUNT(*) AS NbCpt FROM T_UTILISATEUR_USR;");
		/*test bonne exécution requête*/
		return $query->row();
	}

	//fonction ajoutant un compte formateur désactivé à la base de données
	public function set_compte() {
		$this->load->helper('url');

		$id=$this->input->post('id');
		$mdp=$this->input->post('mdp');

		$req="INSERT INTO T_UTILISATEUR_USR VALUES (NULL,'".$id."','".$mdp."','F','D');";
		/*test bonne exécution requête*/
		$query = $this->db->query($req);
		return ($query);
	}

	/* fonction de vérification de l'existence d'un compte actif dans la base de données */
	public function connect_compte($username, $password) {
		$salt = "E0QUIZ22MONSEL_1837AZ24ML72!";
		$mdp = hash('sha256', $salt.$password);
		$query = $this->db->query("SELECT *
									FROM T_UTILISATEUR_USR
									WHERE USR_PSEUDO='".$username."'
									AND USR_MDP='".$mdp."';");
 		if($query->num_rows() > 0) {
 			return true;
		} else {
			return false;
		}
	}

	/* récupération du role d'un utilisateur de pseudo connu */
	public function role_utilisateur($username) {

		$req = "SELECT USR_ROLE FROM T_UTILISATEUR_USR WHERE USR_PSEUDO='".$username."';";

		$query = $this->db->query($req);

		$res = $query->row();

		return $res->USR_ROLE;

	}

	//récupération de l'état ('Actif' ou 'Désactif' du compte utilisateur
	public function get_etat_utilisateur($username){
		
		$req = "SELECT USR_ETAT FROM T_UTILISATEUR_USR WHERE USR_PSEUDO='".$username."';";

		$query = $this->db->query($req);

		$res = $query->row();

		return $res->USR_ETAT;
	}

	public function change_mdp($username) {

		$mdp=$this->input->post('mdp');
		$salt = "E0QUIZ22MONSEL_1837AZ24ML72!";
		$password = hash('sha256', $salt.$mdp);
		$new_mdp=$this->input->post('new_mdp');
		$salt = "E0QUIZ22MONSEL_1837AZ24ML72!";
		$new_password = hash('sha256', $salt.$new_mdp);

		$req = "UPDATE T_UTILISATEUR_USR
				SET USR_MDP='".$new_password."'
				WHERE USR_PSEUDO='".$username."'
				AND USR_MDP='".$password."';";

		$query = $this->db->query($req);

		return ($query);
	}

	public function check_mdp($username) {
		$mdp = $this->input->post('mdp');
		$salt = "E0QUIZ22MONSEL_1837AZ24ML72!";
		$password = hash('sha256', $salt.$mdp);

		$query = $this->db->query("SELECT USR_PSEUDO, USR_MDP
									FROM T_UTILISATEUR_USR
									WHERE USR_PSEUDO='".$username."'
									AND USR_MDP='".$password."';");

		if($query->num_rows() > 0) {
			return true;
		} else {
			return false;
		}

	}




	/* ------------- MATCHS -------------*/
	
	//fonction récupérant toutes les informations d'un match (ID connu), du quiz, des questions & des réponses associées
	public function get_match_info($id) {
		$query = $this->db->query("SELECT *
									FROM T_MATCH_MAT
									JOIN T_QUIZ_QUI USING (QUI_ID)
									JOIN T_QUESTION_QST USING (QUI_ID)
									JOIN T_REPONSE_REP USING (QST_ID)
									WHERE MAT_CODE='".$id."';");
		/*test bonne exécution requête*/
		return $query->result_array();
	}

	//fonction récupérant toutes les informations d'un match (ID connu), du quiz, des questions, des réponses & des joueurs associées
	public function get_match_joueurs_info($id) {
		$query = $this->db->query("SELECT *
									FROM T_MATCH_MAT
									JOIN T_JOUEUR_JOU USING (MAT_ID)
									WHERE MAT_CODE='".$id."';");
		/*test bonne exécution requête*/
		return $query->result_array();
	}

	//récupération du nombre de joueurs inscrits
	public function get_nb_joueurs($id){
		$query = $this->db->query("SELECT COUNT(*) AS nb_joueurs
									FROM T_JOUEUR_JOU
									JOIN T_MATCH_MAT USING (MAT_ID)
									WHERE MAT_CODE='".$id."';");
		return $query->row();
	}

	//récupération du nombre de joueurs ayant joué (ayant un score)
	public function get_nb_participants($id){
		$query = $this->db->query("SELECT COUNT(*) AS nb_participants
									FROM T_JOUEUR_JOU
									JOIN T_MATCH_MAT USING (MAT_ID)
									WHERE MAT_CODE='".$id."'
									AND JOU_SCORE IS NOT NULL;");
		return $query->row();
	}

	//récupération du score total des joueurs
	public function get_score_total($id){
		//score calculé en pourcentage du score possible selon le nombre de participants
		//1 question = 1 points -> score total possible = nb_questions * nb_participants
		//pourcentage = score total possible * 100 / somme scores joueurs

		$query_nb_question = $this->db->query("SELECT COUNT(*) AS nb_questions
												FROM T_QUESTION_QST
												JOIN T_QUIZ_QUI USING (QUI_ID)
												JOIN T_MATCH_MAT USING (QUI_ID)
												WHERE MAT_CODE='".$id."';");
		$query_nb_participants = $this->db->query("SELECT COUNT(*) AS nb_participants
													FROM T_JOUEUR_JOU
													JOIN T_MATCH_MAT USING (MAT_ID)
													WHERE MAT_CODE='".$id."'
													AND JOU_SCORE IS NOT NULL;");
		$nb_qst = $query_nb_question->row();
		$nb_participants = $query_nb_participants->row();
		$score_possible = $nb_qst->nb_questions * $nb_participants->nb_participants;

		$query_total_score_joueurs = $this->db->query ("SELECT SUM(JOU_SCORE) AS total_joueurs
														FROM T_JOUEUR_JOU
														JOIN T_MATCH_MAT USING (MAT_ID)
														WHERE MAT_CODE='".$id."';");
		$score_joueurs = $query_total_score_joueurs->row();

		$score = ($score_joueurs->total_joueurs * 100)/$score_possible;
		
		return $score;
	}


	//fonction vérifiant qu'un code de match existe (retourne la ligne correspondante si le code existe, null sinon)
	public function check_match_code() {
		$this->load->helper('url');

		$code=$this->input->post('code');

		$req="SELECT * FROM T_MATCH_MAT WHERE MAT_CODE='".$code."';";

		/*test bonne exécution requête*/

		$query = $this->db->query($req);

		return $query->row();
	}

	//fonction de remise à zéro du match
	public function raz_match($id){
		$date_tomorrow = $this->db->query("SELECT ADDDATE(CURDATE(),1) AS demain;");
		$tomorrow = $date_tomorrow->row();
		$query = $this->db->query("UPDATE T_MATCH_MAT
									SET MAT_DEBUT='".$tomorrow->demain."'
									WHERE MAT_CODE='".$id."';");
		//après update sur MAT_DEBUT -> trigger de suppression des joueurs
		return ($query);
	}

	//fonction de suppression d'un match
	public function delete_match($id) {
		$query_jou = $this->db->query("DELETE FROM T_JOUEUR_JOU WHERE MAT_ID='".$id."';");
		$query_match = $this->db->query("DELETE FROM T_MATCH_MAT WHERE MAT_ID='".$id."';");
		return ($query_match);
	}

	//fonction de désactivation d'un match
	public function deactivate_match($code) {
		$query = $this->db->query("UPDATE T_MATCH_MAT
									SET MAT_ACTIF=0
									WHERE MAT_CODE='".$code."';");
		return ($query);
	}

	//fonction d'activation d'un match
	public function activate_match($code) {
		$query = $this->db->query("UPDATE T_MATCH_MAT
									SET MAT_ACTIF=1
									WHERE MAT_CODE='".$code."';");
		return ($query);
	}

	//fonction de création d'un match (code choisi par le formateur)
	public function create_match($quiz, $username){
		//génération d'un code automatique
		$query_code = $this->db->query ("SELECT genere_code() as code;");
		$code = $query_code->row();
		//récupération de l'id du formateur connecté
		$query_usr_id = $this->db->query("SELECT USR_ID FROM T_UTILISATEUR_USR WHERE USR_PSEUDO='".$username."';");
		$id_usr = $query_usr_id->row();

		$query = $this->db->query("INSERT INTO T_MATCH_MAT VALUES
									(NULL, $quiz, $id_usr->USR_ID, NOW(), NULL, '".$code->code."', 0, 0);");
		return ($query);
	}



	/* ------------- QUIZ -------------*/
	public function get_all_quiz(){
		$query = $this->db->query("SELECT * FROM T_QUIZ_QUI
									JOIN T_QUESTION_QST USING (QUI_ID)
									JOIN T_REPONSE_REP USING (QST_ID);");
		$res = $query->result_array();
		return $res;
	}



	/* ------------- JOUEURS -------------*/

	//fonction vérifiant l'existence d'un pseudo lié au code d'un match (retourne la ligne correspondante si le pseudo existe, null sinon)
	public function check_pseudo_match() {
		$this->load->helper('url');

		$code=$this->input->post('code');
		$pseudo=$this->input->post('pseudo');

		$req="SELECT * FROM T_JOUEUR_JOU JOIN T_MATCH_MAT USING (MAT_ID) WHERE MAT_CODE='".$code."' AND JOU_PSEUDO='".$pseudo."';";
		/*test bonne exécution requête*/

		$query = $this->db->query($req);

		return $query->row();
	}

	//fonction ajoutant un joueur à la table de données
	public function set_joueur() {
		$this->load->helper('url');

		$pseudo=$this->input->post('pseudo');
		$code=$this->input->post('code');

		$req_id_match="SELECT MAT_ID FROM T_MATCH_MAT WHERE MAT_CODE='".$code."';";
		/*test bonne exécution requête*/
		$query_id_match = $this->db->query($req_id_match);

		$res = $query_id_match->row();
		$mat_id = $res->MAT_ID;

		$req="INSERT INTO T_JOUEUR_JOU VALUES (NULL, '".$mat_id."', '".$pseudo."', NULL);";
		/*test bonne exécution requête*/

		$query = $this->db->query($req);
		return ($query);

	}


	/* ------------- FORMATEURS -------------*/

	//récupération des informations d'un formateur (donc ayant un profil)
	public function get_infos_formateur($username) {
		$req = "SELECT * FROM T_UTILISATEUR_USR JOIN T_PROFIL_PRO USING (USR_ID) WHERE USR_PSEUDO='".$username."';";
		$query = $this->db->query($req);
		$res = $query->row();
		return $res;

	}

	//récupération des informations de tous les quiz et matchs associés (de tous les formateurs)
	public function get_all_match() {

		$req = "SELECT T_QUIZ_QUI.*, pseudo_usr(T_QUIZ_QUI.USR_ID) AS QUI_PSEUDO, T_MATCH_MAT.*, pseudo_usr(T_MATCH_MAT.USR_ID) AS MAT_PSEUDO
				FROM T_QUIZ_QUI
				JOIN T_MATCH_MAT USING (QUI_ID);";

		$query = $this->db->query($req);
		$res= $query->result_array();
		return $res;
	}


	/* ------------- ADMINISTRATEURS -------------*/

	//fonction récupérant les informations de tous les utilisateurs
	public function get_all_users() {
		$query = $this->db->query ("SELECT * FROM T_UTILISATEUR_USR
									LEFT OUTER JOIN T_PROFIL_PRO USING (USR_ID);");
		return ($query->result_array());
	}



	/* ------------- AUTRES FONCTIONS UTILES -------------*/

	//fonction récupérant la date et l'heure du moment où la fonction est appelée
	public function get_now() {
		$req="SELECT NOW() as now;";
		/*test bonne exécution requête*/
		$query = $this->db->query($req);
		return $query->row();

	}


}

?>
