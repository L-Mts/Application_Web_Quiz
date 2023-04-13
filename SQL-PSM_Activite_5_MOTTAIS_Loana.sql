/*POUR LES TESTS*/
INSERT INTO T_QUIZ_QUI VALUES
	(NULL, 2, 'QUIZ DE TESTS', NULL, 0);
/*QUIZ ID 6*/

/*QUESTION INSEREES ET SUPPRIMEES PENDANT LES TESTS*/
INSERT INTO T_QUESTION_QST VALUES
	(NULL, 6, 'QUESTION 1', NULL, 0, 1),
	(NULL, 6, 'QUESTION 2', NULL, 0, 2),
	(NULL, 6, 'QUESTION 3', NULL, 0, 3),
	(NULL, 6, 'QUESTION 4', NULL, 0, 8);



/*TRIGGER ACTUALITE*/

DELIMITER //
CREATE TRIGGER actu_supp_qst
BEFORE DELETE ON T_QUESTION_QST
FOR EACH ROW
BEGIN
	SELECT QUI_ID INTO @id_qui FROM T_QUESTION_QST  WHERE QST_ID=OLD.QST_ID; /*avec AFTER DELETE @id_qui = NULL*/
	SELECT (nbr_qst_quiz(@id_qui)-1) into @nbr_qst;  /* appel fonction nbr_qst_quiz qui compte le nombre de questions liées au quiz dont l'id est passé en paramètre*/
	/*ligne dessus : -1 car BEFORE DELETE donc compte la question qui va être supprimée (BEFORE car sinon @id_qui = NULL)*/
	SET @titre = CONCAT('Modification du quiz n°',@id_qui);
	DELETE FROM T_ACTUALITE_ACT WHERE ACT_TITRE LIKE @titre; /*attention utf8mb4_unicode_ci requis dans colonne TITRE pour que cela fonctionne*/
	IF @nbr_qst>=2 THEN
		SET @modif = CONCAT('Suppression d\'une question, il reste ',@nbr_qst,' questions.');
	ELSEIF @nbr_qst=1 THEN
		SET @modif = 'ATTENTION, plus qu\'une question !';
	ELSE
		SET @modif = 'QUIZ VIDE !';
	END IF;
	SELECT GROUP_CONCAT(MAT_CODE) INTO @ListeMatchs FROM T_MATCH_MAT WHERE QUI_ID=@id_qui;
	SELECT GROUP_CONCAT(USR_PSEUDO) INTO @ListeFormateurs FROM T_MATCH_MAT JOIN T_UTILISATEUR_USR USING (USR_ID) WHERE QUI_ID=@id_qui;
	IF (@ListeMatchs IS NOT NULL AND @ListeFormateurs IS NOT NULL) THEN
		SET @contenu = CONCAT(@modif,'\nListe des matchs concernés :',@ListeMatchs,'\nListe des formateurs concernés :',@ListeFormateurs);
	ELSE
		SET @contenu = CONCAT(@modif,'\nAucun match associé à ce quiz pour l\'instant.');
	END IF;
	INSERT INTO T_ACTUALITE_ACT (`ACT_ID`, `USR_ID`, `ACT_TITRE`, `ACT_CONTENU`, `ACT_DATE`) VALUES
		(NULL, 2, @titre, @contenu, NOW());
END;
//
DELIMITER ;


/*TRIGGER REMISE A ZERO MATCH*/
DELIMITER //
CREATE TRIGGER raz_match
AFTER UPDATE ON T_MATCH_MAT
FOR EACH ROW
BEGIN
	SELECT MAT_DEBUT INTO @dbt_mat FROM T_MATCH_MAT WHERE MAT_ID=NEW.MAT_ID;
	SELECT MAT_FIN INTO @fin_mat FROM T_MATCH_MAT WHERE MAT_ID=NEW.MAT_ID;
	IF (@dbt_mat > NOW() AND @fin_mat IS NULL) THEN
		DELETE FROM T_JOUEUR_JOU WHERE MAT_ID=OLD.MAT_ID;
	END IF;
END;
//
DELIMITER ;