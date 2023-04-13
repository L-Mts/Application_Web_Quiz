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

	//fonction récupérant les 5 dernières actualités
	public function get_all_actualite() {
		$query = $this->db->query("SELECT * FROM T_ACTUALITE_ACT join T_UTILISATEUR_USR USING (USR_ID) LIMIT 5;");
		/*test bonne exécution requête*/
		return $query->result_array();
	}

	//fonction récupérant les pseudos de tous les comptes formateurs & administrateurs
	public function get_all_compte() {
		$query = $this->db->query("SELECT USR_PSEUDO FROM T_UTILISATEUR_USR;");
		/*test bonne exécution requête*/
		return $query->result_array();
	}

	//fonction récupérant les infos d'une actualité précise (ID connu)
	public function get_actualite($numero) {
		$query = $this->db->query("SELECT * FROM T_ACTUALITE_ACT JOIN T_UTILISATEUR_USR USING (USR_ID) WHERE ACT_ID=".$numero.";");
		/*test bonne exécution requête*/
		return $query->row();
	}

	//fonction comptant le nombre de comptes dans la base
	public function count_compte() {
		$query = $this->db->query("SELECT COUNT(*) AS NbCpt FROM T_UTILISATEUR_USR;");
		/*test bonne exécution requête*/
		return $query->row();
	}

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

	//fonction vérifiant qu'un code de match existe (retourne la ligne correspondante si le code existe, null sinon)
	public function check_match_code() {
		$this->load->helper('url');

		$code=$this->input->post('code');

		$req="SELECT * FROM T_MATCH_MAT WHERE MAT_CODE='".$code."';";

		/*test bonne exécution requête*/

		$query = $this->db->query($req);

		return $query->row();
	}

	//fonction récupérant la date et l'heure du moment où la fonction est appelée
	public function get_now() {
		$req="SELECT NOW() as now;";
		/*test bonne exécution requête*/
		$query = $this->db->query($req);
		return $query->row();

	}

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

}
?>