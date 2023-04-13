/*--------- VUES ----------*/

/* toutes les bonnes réponses*/
create view REPONSES as
	select * from T_REPONSE_REP
	where REP_VRAIE=1;



/*------- FONCTIONS -------*/

/*calcul du nombre de questions d'un quiz*/
DELIMITER //
create function nbr_qst_quiz (ID_QUI int) returns int
begin
	declare nbr_qst int default 0;
	select count(QST_ID) into nbr_qst
	from T_QUESTION_QST where QUI_ID=ID_QUI;
	return nbr_qst;
end;
// 
DELIMITER ;


DELIMITER //
CREATE FUNCTION liste_quiz_formateur (pseudo_usr VARCHAR(20)) RETURNS VARCHAR(500)
BEGIN
	DECLARE liste VARCHAR(500) DEFAULT 'null';
	SET liste := (SELECT GROUP_CONCAT(QUI_INTITULE) FROM T_QUIZ_QUI JOIN T_UTILISATEUR_USR USING (USR_ID) WHERE USR_PSEUDO=pseudo_usr);
	RETURN liste;
END;
// 
DELIMITER ;



DELIMITER //
CREATE FUNCTION genere_code () RETURNS VARCHAR(8)
BEGIN
	DECLARE code VARCHAR(500) DEFAULT 'null';
	SELECT floor(rand()*100) + 899 into @un_deux_trois;
	SELECT char(cast((90 - 65 )*rand() + 65 as integer)) into @quatre;
	SELECT char(cast((90 - 65 )*rand() + 65 as integer)) into @cinq;
	SELECT char(cast((90 - 65 )*rand() + 65 as integer)) into @six;
	SELECT char(cast((90 - 65 )*rand() + 65 as integer)) into @sept;
	SELECT ceiling(rand()*9) into @huit;
	SET code := CONCAT(@un_deux_trois, @quatre, @cinq, @six, @sept, @huit);
	RETURN code;
END;
// 
DELIMITER ;



/*------ PROCEDURES -------*/
/*désactivation quiz (et questions liées)*/
DELIMITER //
CREATE PROCEDURE deactivate_quiz (IN id_qui INT)
BEGIN
	UPDATE T_QUESTION_QST
		SET QST_ACTIVE='0' 
		WHERE QUI_ID=id_qui;
	UPDATE T_QUIZ_QUI
		SET QUI_ACTIF='0'
		WHERE QUI_ID=id_qui;
END;
//
DELIMITER ;



DELIMITER //
CREATE PROCEDURE set_score (IN score_jou INT, IN pseudo_jou VARCHAR(20))
BEGIN
	UPDATE T_JOUEUR_JOU
		SET JOU_SCORE=score_jou 
		WHERE JOU_PSEUDO=pseudo_jou;
END;
//
DELIMITER ;



/*------- TRIGGERS --------*/
/*suppression des réponses associés à une question avant sa suppression*/
DELIMITER //
CREATE TRIGGER supp_rep
BEFORE DELETE ON T_QUESTION_QST
FOR EACH ROW
BEGIN
	DELETE FROM T_REPONSE_REP WHERE QST_ID=OLD.QST_ID;
END;
//
DELIMITER ;

/*actualité lors de l'ajout d'un quiz à la base de données*/
DELIMITER //
CREATE TRIGGER actu_nouveau_quiz
AFTER INSERT ON T_QUIZ_QUI
FOR EACH ROW
BEGIN
	SELECT QUI_INTITULE INTO @titre_qui FROM T_QUIZ_QUI WHERE QUI_ID=NEW.QUI_ID;
    SELECT QUI_ACTIF INTO @actif_qui FROM T_QUIZ_QUI WHERE QUI_ID=NEW.QUI_ID;
	IF (@actif_qui=1) THEN
		INSERT INTO T_ACTUALITE_ACT VALUES
			(NULL, 2, CONCAT('Nouveau quiz accessible pour les formateurs : ',@titre_qui), CONCAT('Un nouveau quiz intitulé ',@titre_qui,' est accessible pour réaliser des matchs'), NOW());
	END IF;
END;
//
DELIMITER ;