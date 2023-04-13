/*----- MATCHS JOUEURS -----*/
/*5*/
SELECT QST_INTITULE, QST_IMAGE, QST_ORDRE, REP_TEXTE
FROM T_MATCH_MAT
JOIN T_QUIZ_QUI USING (QUI_ID)
JOIN T_QUESTION_QST USING (QUI_ID)
JOIN T_REPONSE_REP USING (QST_ID)
WHERE MAT_ID=5
	AND MAT_CORRECTION=1
	AND REP_VRAIE=1;


/*6*/
/*1 si réponse vraie ou 0 si réponse fausse*/
SELECT REP_VRAIE
FROM T_REPONSE_REP
WHERE REP_ID=12;


/*7*/
UPDATE T_JOUEUR_JOU
	SET JOU_SCORE=10
	WHERE JOU_PSEUDO='léa' AND MAT_ID=3;


/*8*/
SELECT JOU_PSEUDO, JOU_SCORE
FROM T_JOUEUR_JOU
WHERE JOU_PSEUDO='léa' AND MAT_ID=3;



/*----- QUIZ -----*/
/*3*/
SELECT *
FROM T_QUIZ_QUI;


/*4*/
/*fonction qui retourne le pseudo d'un utilisateur à partir de son ID*/
DELIMITER //
CREATE FUNCTION pseudo_usr (ID_USR INT) RETURNS VARCHAR(20)
BEGIN
	DECLARE pseudo VARCHAR(20) DEFAULT 'null';
	SET pseudo := (SELECT USR_PSEUDO FROM T_UTILISATEUR_USR WHERE USR_ID=ID_USR);
	RETURN pseudo;
END;
//
DELIMITER ;

/*requête d'affichage avec appel de la fonction pseudo_usr pour les pseudo des utilisateurs*/
SELECT QUI_INTITULE, pseudo_usr(T_QUIZ_QUI.USR_ID), MAT_CODE, pseudo_usr(T_MATCH_MAT.USR_ID)
FROM T_QUIZ_QUI
JOIN T_MATCH_MAT USING (QUI_ID);


/*5*/
SELECT *
FROM T_QUIZ_QUI
WHERE USR_ID=5;


/*6*/
SELECT *
FROM T_QUIZ_QUI
WHERE USR_ID IN (SELECT USR_ID
				 FROM T_UTILISATEUR_USR
				 WHERE USR_ROLE='A');


/*7*/
/*pour un formateur, tous les quiz créés par ce formateur et les matchs associés à ces quiz s'il y en a*/
SELECT USR_PSEUDO, QUI_INTITULE, MAT_CODE
FROM T_UTILISATEUR_USR
LEFT OUTER JOIN T_QUIZ_QUI USING (USR_ID)
LEFT OUTER JOIN T_MATCH_MAT USING (QUI_ID)
WHERE USR_PSEUDO='Anna';



/*----- MATCHS FORMATEURS -----*/
/*7*/
INSERT INTO T_MATCH_MAT VALUES
	(NULL,4,5,NULL,NULL,'QUI4USR5',0,0);


/*8*/
UPDATE T_MATCH_MAT
 SET MAT_CORRECTION=1;
 WHERE MAT_ID=9;


/*9*/
/*possibilité de faire un trigger pour supprimer les joueurs avant suppression d'un match*/
DELETE FROM T_JOUEUR_JOU WHERE MAT_ID=9;
DELETE FROM T_MATCH_MAT WHERE MAT_ID=9;


/*10*/
UPDATE T_MATCH_MAT
 SET MAT_ACTIF=1;
 WHERE MAT_ID=9;


/*11*/
/*procedure*/
DELIMITER //
CREATE PROCEDURE raz_match (IN ID_MAT INT, IN DBT_MAT DATETIME)
BEGIN
	UPDATE T_MATCH_MAT
		SET MAT_DEBUT=DBT_MAT
		WHERE MAT_ID=ID_MAT;
	UPDATE T_MATCH_MAT
		SET MAT_FIN=NULL
		WHERE MAT_ID=ID_MAT;
END;
//
DELIMITER ;

/*appel procédure*/
CALL raz_match(5,'2022-11-22 12:15:00');

/*les updates de la procédure déclenchent le trigger 'raz_match' qui supprime les joueurs*/

