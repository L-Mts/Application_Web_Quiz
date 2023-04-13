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

	/* récupération du role d'un utilisateur de pseudo connu */
	public function role_utilisateur($username) {

		$req = "SELECT USR_ROLE FROM T_UTILISATEUR_USR WHERE USR_PSEUDO='".$username."';";

		$query = $this->db->query($req);

		$res = $query->row();

		return $res->USR_ROLE;

	}

	public function change_mdp($username) {

		$mdp=$this->input->post('mdp');
		$new_mdp=$this->input->post('new_mdp');

		$req = "UPDATE T_UTILISATEUR_USR
				SET USR_MDP='".$new_mdp."'
				WHERE USR_PSEUDO='".$username."'
				AND USR_MDP='".$mdp."';";

		$query = $this->db->query($req);

		return ($query);
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
