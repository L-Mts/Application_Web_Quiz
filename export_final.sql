-- phpMyAdmin SQL Dump
-- version 5.1.3
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Dec 11, 2022 at 10:01 PM
-- Server version: 10.5.12-MariaDB-0+deb11u1
-- PHP Version: 7.4.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `znl3-zmottailo_2`
--

DELIMITER $$
--
-- Procedures
--
CREATE DEFINER=`zmottailo`@`%` PROCEDURE `actualite_match` (IN `ID_MAT` INT)   BEGIN
	SET @fin := (SELECT MAT_FIN FROM T_MATCH_MAT WHERE MAT_ID=ID_MAT);
	SET @dbt := (SELECT MAT_DEBUT FROM T_MATCH_MAT WHERE MAT_ID=ID_MAT);
	SET @joueurs := (SELECT joueurs_match(ID_MAT));
	SET @intitule := (SELECT QUI_INTITULE FROM T_QUIZ_QUI JOIN T_MATCH_MAT USING (QUI_ID) WHERE MAT_ID=ID_MAT);
	SET @titre = CONCAT("Fin d'un match sur le quiz ",@intitule);
	SET @contenu = CONCAT("Un match sur le quiz ",@intitule," s\'est terminé.\nDEBUT: ",@dbt,"\nFIN: ",@fin,"\nLes joueurs ayant participés au match sont : ",@joueurs);
	IF (@fin<NOW() AND @fin IS NOT NULL) THEN
			INSERT INTO T_ACTUALITE_ACT VALUES (NULL,1,@titre,@contenu,NOW());
	END IF;
END$$

CREATE DEFINER=`zmottailo`@`%` PROCEDURE `deactivate_quiz` (IN `id_qui` INT)   BEGIN
	UPDATE T_QUESTION_QST
		SET QST_ACTIVE='0' 
		WHERE QUI_ID=id_qui;
	UPDATE T_QUIZ_QUI
		SET QUI_ACTIF='0'
		WHERE QUI_ID=id_qui;
END$$

CREATE DEFINER=`zmottailo`@`%` PROCEDURE `nbr_matchs` ()   BEGIN
	SET @Matchs_en_cours := (SELECT COUNT(MAT_ID) FROM T_MATCH_MAT WHERE MAT_DEBUT<NOW() AND MAT_DEBUT IS NOT NULL AND (MAT_FIN>NOW() OR MAT_FIN IS NULL));
	SET @Matchs_finis := (SELECT COUNT(MAT_ID) FROM T_MATCH_MAT WHERE MAT_FIN<NOW() AND MAT_FIN IS NOT NULL);
	SET @Matchs_a_venir := (SELECT COUNT(MAT_ID) FROM T_MATCH_MAT WHERE MAT_DEBUT>NOW() OR MAT_DEBUT IS NULL);
	SELECT @Matchs_a_venir, @Matchs_en_cours, @Matchs_finis;
END$$

CREATE DEFINER=`zmottailo`@`%` PROCEDURE `set_score` (IN `score_jou` INT, IN `pseudo_jou` VARCHAR(20))   BEGIN
	UPDATE T_JOUEUR_JOU
		SET JOU_SCORE=score_jou 
		WHERE JOU_PSEUDO=pseudo_jou;
END$$

--
-- Functions
--
CREATE DEFINER=`zmottailo`@`%` FUNCTION `genere_code` () RETURNS VARCHAR(8) CHARSET utf8mb4  BEGIN
	DECLARE code VARCHAR(500) DEFAULT 'null';
	SELECT floor(rand()*100) + 898 into @un_deux_trois;
	SELECT char(cast((90 - 65 )*rand() + 65 as integer)) into @quatre;
	SELECT char(cast((90 - 65 )*rand() + 65 as integer)) into @cinq;
	SELECT char(cast((90 - 65 )*rand() + 65 as integer)) into @six;
	SELECT char(cast((90 - 65 )*rand() + 65 as integer)) into @sept;
	SELECT ceiling(rand()*9) into @huit;
	SET code := CONCAT(@un_deux_trois, @quatre, @cinq, @six, @sept, @huit);
	RETURN code;
END$$

CREATE DEFINER=`zmottailo`@`%` FUNCTION `joueurs_match` (`ID_MAT` INT) RETURNS TEXT CHARSET utf8mb4  BEGIN
	SELECT GROUP_CONCAT(JOU_PSEUDO) INTO @LISTE FROM T_JOUEUR_JOU WHERE MAT_ID=ID_MAT;
	IF @LISTE IS NULL THEN
		SET @LISTE := "Aucun joueur n'a participé à ce match";
    END IF;
	RETURN @LISTE;
END$$

CREATE DEFINER=`zmottailo`@`%` FUNCTION `liste_quiz_formateur` (`pseudo_usr` VARCHAR(20)) RETURNS VARCHAR(500) CHARSET utf8mb4  BEGIN
	DECLARE liste VARCHAR(500) DEFAULT 'null';
	SET liste := (SELECT GROUP_CONCAT(QUI_INTITULE) FROM T_QUIZ_QUI JOIN T_UTILISATEUR_USR USING (USR_ID) WHERE USR_PSEUDO=pseudo_usr);
	RETURN liste;
END$$

CREATE DEFINER=`zmottailo`@`%` FUNCTION `nbr_qst_quiz` (`ID_QUI` INT) RETURNS INT(11)  begin
	declare nbr_qst int default 0;
	select count(QST_ID) into nbr_qst
	from T_QUESTION_QST where QUI_ID=ID_QUI;
	return nbr_qst;
end$$

CREATE DEFINER=`zmottailo`@`%` FUNCTION `pseudo_usr` (`ID_USR` INT) RETURNS VARCHAR(20) CHARSET utf8mb4  BEGIN
	DECLARE pseudo VARCHAR(20) DEFAULT 'null';
	SET pseudo := (SELECT USR_PSEUDO FROM T_UTILISATEUR_USR WHERE USR_ID=ID_USR);
	RETURN pseudo;
END$$

DELIMITER ;

-- --------------------------------------------------------

--
-- Stand-in structure for view `reponses`
-- (See below for the actual view)
--
CREATE TABLE `reponses` (
`QUI_ID` int(11)
,`QST_ID` int(11)
,`REP_ID` int(11)
,`REP_TEXTE` varchar(200)
,`REP_VRAIE` tinyint(4)
,`QST_INTITULE` varchar(200)
,`QST_IMAGE` varchar(200)
,`QST_ACTIVE` tinyint(4)
,`QST_ORDRE` int(11)
,`USR_ID` int(11)
,`QUI_INTITULE` varchar(100)
,`QUI_IMAGE` varchar(200)
,`QUI_ACTIF` tinyint(4)
);

-- --------------------------------------------------------

--
-- Table structure for table `T_ACTUALITE_ACT`
--

CREATE TABLE `T_ACTUALITE_ACT` (
  `ACT_ID` int(11) NOT NULL,
  `USR_ID` int(11) NOT NULL,
  `ACT_TITRE` varchar(100) NOT NULL,
  `ACT_CONTENU` varchar(1000) DEFAULT NULL,
  `ACT_DATE` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `T_ACTUALITE_ACT`
--

INSERT INTO `T_ACTUALITE_ACT` (`ACT_ID`, `USR_ID`, `ACT_TITRE`, `ACT_CONTENU`, `ACT_DATE`) VALUES
(1, 2, 'Lancement de la plateforme de quiz', 'Toute l\'équipe de développement est heureuse de vous apprendre que notre plateforme de quiz sera bientôt publique. Nous tenons à remercier les formateurs et joueurs ayant acceptés de tester notre plateforme avant son lancement. A bientôt !', '2022-10-04 11:16:00'),
(5, 2, 'Quelques précisions concernant le lancement de la plateforme de quiz', 'Nous n\'avons pas encore de date précise pour le lancement publique de notre plateforme, actuellement seule la page d\'accueil est accessible au public. Nous mettrons régulièrement à disposition du public de nouvelles fonctionnalités, jusqu\'au lancement de la plateforme, alors revenez régulièrement pour être au courant de nos mises à jour. A bientôt !', '2022-10-05 15:38:00'),
(6, 7, 'Pour les formateurs : nouveau quiz sur les capitales européennes', 'Bonjour à tous,\nJ\'ai mis en ligne un nouveau quiz sur les capitales européennes, si d\'autres formateurs souhaitent l\'utiliser pour faire un match.', '2022-10-10 11:34:00'),
(9, 1, 'Fin d\'un match sur le quiz Les 7 Merveilles du Monde moderne', 'Un match sur le quiz Les 7 Merveilles du Monde moderne s\'est terminé.\nDEBUT: 2022-10-18 11:00:00\nFIN: 2022-10-19 15:00:00\nLes joueurs ayant participés au match sont : Aucun joueur n\'a participé à ce match', '2022-10-21 00:00:00'),
(10, 1, 'Fin d\'un match sur le quiz Les Pays de l\'Amérique du Sud', 'Un match sur le quiz Les Pays de l\'Amérique du Sud s\'est terminé.\nDEBUT: 2022-10-04 15:00:00\nFIN: 2022-10-04 18:00:00\nLes joueurs ayant participés au match sont : mark', '2022-10-21 00:00:00'),
(12, 1, 'Fin d\'un match sur le quiz Capitales Européennes de l\'Ouest', 'Un match sur le quiz Capitales Européennes de l\'Ouest s\'est terminé.\nDEBUT: 2022-10-04 14:10:00\nFIN: 2022-10-08 14:10:00\nLes joueurs ayant participés au match sont : Aucun joueur n\'a participé à ce match', '2022-10-21 00:00:00'),
(32, 1, 'Fin d\'un match sur le quiz Les Pays de l\'Amérique du Sud', 'Un match sur le quiz Les Pays de l\'Amérique du Sud s\'est terminé.\nDEBUT: 2022-12-05 00:00:00\nFIN: 2022-10-04 18:00:00\nLes joueurs ayant participés au match sont : mark', '2022-12-04 17:18:27'),
(33, 1, 'Fin d\'un match sur le quiz Les Pays de l\'Amérique du Sud', 'Un match sur le quiz Les Pays de l\'Amérique du Sud s\'est terminé.\nDEBUT: 2022-12-05 00:00:00\nFIN: 2022-10-04 18:00:00\nLes joueurs ayant participés au match sont : mark', '2022-12-04 17:19:37'),
(34, 1, 'Fin d\'un match sur le quiz Les Pays de l\'Amérique du Sud', 'Un match sur le quiz Les Pays de l\'Amérique du Sud s\'est terminé.\nDEBUT: 2022-12-05 00:00:00\nFIN: 2022-10-04 18:00:00\nLes joueurs ayant participés au match sont : mark', '2022-12-04 17:19:48'),
(35, 1, 'Fin d\'un match sur le quiz Les Pays de l\'Amérique du Sud', 'Un match sur le quiz Les Pays de l\'Amérique du Sud s\'est terminé.\nDEBUT: 2022-12-05 00:00:00\nFIN: 2022-10-04 18:00:00\nLes joueurs ayant participés au match sont : mark', '2022-12-04 17:20:01'),
(36, 1, 'Fin d\'un match sur le quiz Capitales Européennes de l\'Est', 'Un match sur le quiz Capitales Européennes de l\'Est s\'est terminé.\nDEBUT: 2022-10-28 08:00:00\nFIN: 2022-10-28 11:13:39\nLes joueurs ayant participés au match sont : Aucun joueur n\'a participé à ce match', '2022-12-04 18:30:46'),
(37, 1, 'Fin d\'un match sur le quiz Capitales Européennes de l\'Est', 'Un match sur le quiz Capitales Européennes de l\'Est s\'est terminé.\nDEBUT: 2022-10-28 08:00:00\nFIN: 2022-10-28 11:13:39\nLes joueurs ayant participés au match sont : Aucun joueur n\'a participé à ce match', '2022-12-05 10:10:51'),
(42, 2, 'Modification du quiz n°4', 'QUIZ VIDE !\nAucun match associé à ce quiz pour l\'instant.', '2022-12-05 14:54:21'),
(43, 1, 'Fin d\'un match sur le quiz Capitales Européennes de l\'Est', 'Un match sur le quiz Capitales Européennes de l\'Est s\'est terminé.\nDEBUT: 2022-12-06 00:00:00\nFIN: 2022-10-28 11:13:39\nLes joueurs ayant participés au match sont : Aucun joueur n\'a participé à ce match', '2022-12-05 20:18:19'),
(44, 1, 'Fin d\'un match sur le quiz Capitales Européennes de l\'Est', 'Un match sur le quiz Capitales Européennes de l\'Est s\'est terminé.\nDEBUT: 2022-12-06 00:00:00\nFIN: 2022-10-28 11:13:39\nLes joueurs ayant participés au match sont : Aucun joueur n\'a participé à ce match', '2022-12-05 20:18:32'),
(45, 1, 'Fin d\'un match sur le quiz Capitales Européennes de l\'Est', 'Un match sur le quiz Capitales Européennes de l\'Est s\'est terminé.\nDEBUT: 2022-12-06 00:00:00\nFIN: 2022-10-28 11:13:39\nLes joueurs ayant participés au match sont : Aucun joueur n\'a participé à ce match', '2022-12-05 20:19:00'),
(46, 1, 'Fin d\'un match sur le quiz Capitales Européennes de l\'Est', 'Un match sur le quiz Capitales Européennes de l\'Est s\'est terminé.\nDEBUT: 2022-12-06 00:00:00\nFIN: 2022-10-28 11:13:39\nLes joueurs ayant participés au match sont : Aucun joueur n\'a participé à ce match', '2022-12-05 20:56:07'),
(47, 2, 'Nouveau quiz accessible pour les formateurs : La géographie française', 'Un nouveau quiz intitulé La géographie française est accessible pour réaliser des matchs', '2022-12-06 00:31:24'),
(48, 2, 'Nouveau quiz accessible pour les formateurs : La géographie française _ partie 2', 'Un nouveau quiz intitulé La géographie française _ partie 2 est accessible pour réaliser des matchs', '2022-12-06 00:33:57');

-- --------------------------------------------------------

--
-- Table structure for table `T_JOUEUR_JOU`
--

CREATE TABLE `T_JOUEUR_JOU` (
  `JOU_ID` int(11) NOT NULL,
  `MAT_ID` int(11) NOT NULL,
  `JOU_PSEUDO` varchar(20) NOT NULL,
  `JOU_SCORE` double DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `T_JOUEUR_JOU`
--

INSERT INTO `T_JOUEUR_JOU` (`JOU_ID`, `MAT_ID`, `JOU_PSEUDO`, `JOU_SCORE`) VALUES
(18, 2, 'anna', NULL),
(20, 2, 'henry', NULL),
(21, 2, 'fifi58', NULL),
(40, 2, 'anael', NULL),
(41, 2, 'martin', NULL),
(42, 18, 'anna', NULL),
(43, 2, 'charlotte', NULL),
(44, 2, 'peter', 0),
(45, 2, 'ezekiel', NULL),
(46, 2, 'castiel', 0),
(47, 2, 'gabriel', NULL),
(48, 2, 'michael', 0),
(49, 2, 'asmodeus', 0),
(50, 18, 'castiel', 0),
(51, 8, 'SALMA', NULL),
(52, 8, 'EREZTZ', NULL),
(53, 2, 'salma', NULL),
(62, 18, 'peppa', 1),
(63, 20, 'lara', 3),
(64, 20, 'laura', 2),
(69, 23, 'paul', 3),
(70, 23, 'peter', 0),
(71, 23, 'pauline', 2),
(72, 28, 'vava305', 4),
(73, 28, 'fifi215', 0);

-- --------------------------------------------------------

--
-- Table structure for table `T_MATCH_MAT`
--

CREATE TABLE `T_MATCH_MAT` (
  `MAT_ID` int(11) NOT NULL,
  `QUI_ID` int(11) NOT NULL,
  `USR_ID` int(11) NOT NULL,
  `MAT_DEBUT` datetime DEFAULT NULL,
  `MAT_FIN` datetime DEFAULT NULL,
  `MAT_CODE` char(8) NOT NULL,
  `MAT_ACTIF` tinyint(4) NOT NULL,
  `MAT_CORRECTION` tinyint(4) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `T_MATCH_MAT`
--

INSERT INTO `T_MATCH_MAT` (`MAT_ID`, `QUI_ID`, `USR_ID`, `MAT_DEBUT`, `MAT_FIN`, `MAT_CODE`, `MAT_ACTIF`, `MAT_CORRECTION`) VALUES
(2, 1, 6, '2022-11-15 11:15:00', NULL, 'M2Q1USR6', 1, 0),
(4, 3, 7, '2022-12-07 00:00:00', NULL, 'M4Q3USR7', 0, 0),
(6, 1, 6, '2022-10-04 14:10:00', '2022-10-08 14:10:00', 'USR6QUI1', 0, 0),
(7, 2, 5, '2022-12-07 00:00:00', NULL, 'QUI2USR2', 1, 0),
(8, 3, 6, '2022-10-26 10:00:00', '2022-11-10 11:07:02', 'QUI3USR6', 1, 0),
(16, 1, 11, '2022-12-05 17:24:29', NULL, '926AEUM1', 0, 0),
(17, 1, 11, '2022-12-05 17:24:33', NULL, '962ZBER9', 0, 0),
(18, 5, 5, '2022-12-05 17:36:32', NULL, '899KXNS1', 1, 0),
(19, 3, 5, '2022-12-05 20:41:15', NULL, '937TRES1', 0, 0),
(20, 1, 5, '2022-12-05 20:42:27', '2022-12-15 08:07:29', '912FOGL5', 1, 0),
(21, 7, 5, '2022-12-07 00:00:00', NULL, '981GPIU1', 1, 0),
(22, 8, 5, '2022-12-06 00:47:35', NULL, '987LPRN5', 1, 0),
(23, 3, 5, '2022-12-06 00:49:07', '2022-12-23 08:12:23', '909XJAX6', 1, 0),
(24, 8, 11, '2022-12-06 01:16:55', NULL, '926GJDL9', 0, 0),
(25, 5, 11, '2022-12-14 01:17:10', NULL, '939DIGE2', 1, 0),
(26, 7, 7, '2022-12-06 01:21:23', NULL, '926PFDC1', 1, 0),
(27, 8, 5, '2022-12-06 08:33:37', NULL, '959KFTH2', 1, 0),
(28, 8, 5, '2022-12-06 08:34:39', NULL, '985LPPH6', 1, 0);

--
-- Triggers `T_MATCH_MAT`
--
DELIMITER $$
CREATE TRIGGER `fin_match_actu` AFTER UPDATE ON `T_MATCH_MAT` FOR EACH ROW BEGIN
	IF(NEW.MAT_FIN<=NOW()) THEN
		CALL actualite_match(NEW.MAT_ID);
	END IF;
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `raz_match` AFTER UPDATE ON `T_MATCH_MAT` FOR EACH ROW BEGIN
	SELECT MAT_DEBUT INTO @dbt_mat FROM T_MATCH_MAT WHERE MAT_ID=NEW.MAT_ID;
	SELECT MAT_FIN INTO @fin_mat FROM T_MATCH_MAT WHERE MAT_ID=NEW.MAT_ID;
	IF (@dbt_mat != OLD.MAT_DEBUT AND @dbt_mat > NOW() AND @fin_mat IS NULL) THEN
		DELETE FROM T_JOUEUR_JOU WHERE MAT_ID=OLD.MAT_ID;
	END IF;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `T_PROFIL_PRO`
--

CREATE TABLE `T_PROFIL_PRO` (
  `USR_ID` int(11) NOT NULL,
  `PRO_NOM` varchar(80) NOT NULL,
  `PRO_PRENOM` varchar(80) NOT NULL,
  `PRO_MAIL` varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `T_PROFIL_PRO`
--

INSERT INTO `T_PROFIL_PRO` (`USR_ID`, `PRO_NOM`, `PRO_PRENOM`, `PRO_MAIL`) VALUES
(4, 'PIERRE', 'Jean', 'j.pierre@gmail.com'),
(5, 'DUPONT', 'Pierre', 'p.dupont@gmail.com'),
(6, 'DE CHOISEUL', 'Henry', 'h.dechoiseul@gmail.com'),
(7, 'PREVOST', 'Anna', 'a.prevost@gmail.com'),
(8, 'DESCHAMPS', 'Céline', 'c.deschamps@gmail.com');

-- --------------------------------------------------------

--
-- Table structure for table `T_QUESTION_QST`
--

CREATE TABLE `T_QUESTION_QST` (
  `QST_ID` int(11) NOT NULL,
  `QUI_ID` int(11) NOT NULL,
  `QST_INTITULE` varchar(200) NOT NULL,
  `QST_IMAGE` varchar(200) DEFAULT NULL,
  `QST_ACTIVE` tinyint(4) NOT NULL,
  `QST_ORDRE` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `T_QUESTION_QST`
--

INSERT INTO `T_QUESTION_QST` (`QST_ID`, `QUI_ID`, `QST_INTITULE`, `QST_IMAGE`, `QST_ACTIVE`, `QST_ORDRE`) VALUES
(1, 1, 'Quelle est la capitale de l\'Espagne ?', NULL, 1, 1),
(2, 1, 'Quelle est la capitale du Portugal ?', NULL, 1, 2),
(3, 1, 'Quelle est la capitale des Pays Bas ?', NULL, 1, 3),
(4, 1, 'Quelle est la capitale de l\'Irlande ?', NULL, 1, 4),
(5, 1, 'Zurich est la capitale de quel pays ?', NULL, 1, 5),
(6, 2, 'Quelle est la capitale de la Croatie ?', NULL, 1, 1),
(7, 2, 'Quelle est la capitale de la Bulgarie ?', NULL, 1, 2),
(8, 2, 'Quelle est la capitale de l\'Ukraine ?', NULL, 1, 3),
(9, 2, 'Quelle est la capitale de la Roumanie?', NULL, 1, 4),
(10, 2, 'Riga est la capitale de quel pays ?', NULL, 1, 5),
(11, 3, 'Quelle est la longueur totale des murs de la Grande Muraille de Chine ?', NULL, 1, 1),
(12, 3, 'Dans quel pays de trouve la statue du Christ rédempteur ?', NULL, 1, 2),
(13, 3, 'Dans quelle ville peut-on voir le Colisé ?', NULL, 1, 3),
(14, 3, 'Où se trouve le Machu Picchu ?', NULL, 1, 4),
(15, 3, 'Quelle merveille peut-on observer au Mexique ?', NULL, 1, 5),
(16, 5, 'Quelle est la capitale du Mexique ?', NULL, 1, 1),
(17, 5, 'Le Caire est la capitale de quel pays ?', NULL, 1, 2),
(18, 5, 'Quelle est la capitale de l\'Australie ?', NULL, 1, 3),
(19, 5, 'Pyongyang est la capitale de quel pays ?', NULL, 1, 4),
(20, 5, 'Quelle est la capitale de la Jamaïque ?', NULL, 1, 5),
(43, 7, 'Parmi ces villes, laquelle se situe le plus au Nord de la France ?', NULL, 1, 1),
(44, 7, 'Dans quelle région se trouve le Mont Saint-Michel ?', NULL, 1, 2),
(45, 7, 'Quel département est le moins peuplé de France ?', NULL, 1, 3),
(46, 7, 'Quel est le fleuve le plus long de France ?', NULL, 1, 4),
(47, 7, 'Depuis 2016, combien y a-t-il de régions en France métropolitaine ?', NULL, 1, 5),
(48, 8, 'Lequel de ces sommets est le plus élevé ?', NULL, 1, 1),
(49, 8, 'Combien de pays ont une frontière avec la France métropolitaine', NULL, 1, 2),
(50, 8, 'Laquelle des ces îles n\'est pas française ?', NULL, 1, 3),
(51, 8, 'Combien de mers et d\'océans entourent la France métropolitaine ?', NULL, 1, 4),
(52, 8, 'En 2018, combien y a-t-il d\'habitants en France ?', NULL, 1, 5);

--
-- Triggers `T_QUESTION_QST`
--
DELIMITER $$
CREATE TRIGGER `actu_supp_qst` BEFORE DELETE ON `T_QUESTION_QST` FOR EACH ROW BEGIN
	SELECT QUI_ID INTO @id_qui FROM T_QUESTION_QST  WHERE QST_ID=OLD.QST_ID; /*avec AFTER DELETE @id_qui = NULL*/
	SELECT (nbr_qst_quiz(@id_qui)-1) into @nbr_qst;  /* appel fonction nbr_qst_quiz qui compte le nombre de questions liées au quiz dont l'id est passé en paramètre*/
	/*ligne dessus : -1 car BEFORE DELETE donc compte la question qui va être supprimée (BEFORE car sinon @id_qui = NULL)*/
	SET @titre = CONCAT('Modification du quiz n°',@id_qui);
	DELETE FROM T_ACTUALITE_ACT WHERE ACT_TITRE LIKE @titre; /*attention utf8mb4_unicode_ci requis dans colonne TITRE pour que cela fonctionne*/
	IF @nbr_qst>=2 THEN
		SET @modif = CONCAT('Suppression d''une question, il reste ',@nbr_qst,' questions.');
	ELSEIF @nbr_qst=1 THEN
		SET @modif = 'ATTENTION, plus qu''une question !';
	ELSE
		SET @modif = 'QUIZ VIDE !';
	END IF;
	SELECT GROUP_CONCAT(MAT_CODE) INTO @ListeMatchs FROM T_MATCH_MAT WHERE QUI_ID=@id_qui;
	SELECT GROUP_CONCAT(USR_PSEUDO) INTO @ListeFormateurs FROM T_MATCH_MAT JOIN T_UTILISATEUR_USR USING (USR_ID) WHERE QUI_ID=@id_qui;
	IF (@ListeMatchs IS NOT NULL AND @ListeFormateurs IS NOT NULL) THEN
		SET @contenu = CONCAT(@modif,'\nListe des matchs concernés : ',@ListeMatchs,'\nListe des formateurs concernés : ',@ListeFormateurs);
	ELSE
		SET @contenu = CONCAT(@modif,'\nAucun match associé à ce quiz pour l''instant.');
	END IF;
	INSERT INTO T_ACTUALITE_ACT (`ACT_ID`, `USR_ID`, `ACT_TITRE`, `ACT_CONTENU`, `ACT_DATE`) VALUES
		(NULL, 2, @titre, @contenu, NOW());
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `supp_rep` BEFORE DELETE ON `T_QUESTION_QST` FOR EACH ROW BEGIN
	DELETE FROM T_REPONSE_REP WHERE QST_ID=OLD.QST_ID;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `T_QUIZ_QUI`
--

CREATE TABLE `T_QUIZ_QUI` (
  `QUI_ID` int(11) NOT NULL,
  `USR_ID` int(11) NOT NULL,
  `QUI_INTITULE` varchar(100) NOT NULL,
  `QUI_IMAGE` varchar(200) DEFAULT NULL,
  `QUI_ACTIF` tinyint(4) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `T_QUIZ_QUI`
--

INSERT INTO `T_QUIZ_QUI` (`QUI_ID`, `USR_ID`, `QUI_INTITULE`, `QUI_IMAGE`, `QUI_ACTIF`) VALUES
(1, 7, 'Capitales Européennes de l\'Ouest', NULL, 1),
(2, 7, 'Capitales Européennes de l\'Est', NULL, 1),
(3, 5, 'Les 7 Merveilles du Monde moderne', 'carte_monde.jpg', 1),
(5, 8, 'Les Capitales du Monde', NULL, 1),
(6, 2, 'QUIZ DE TESTS', NULL, 0),
(7, 5, 'La géographie française', NULL, 1),
(8, 5, 'La géographie française _ partie 2', NULL, 1);

--
-- Triggers `T_QUIZ_QUI`
--
DELIMITER $$
CREATE TRIGGER `actu_nouveau_quiz` AFTER INSERT ON `T_QUIZ_QUI` FOR EACH ROW BEGIN
	SELECT QUI_INTITULE INTO @titre_qui FROM T_QUIZ_QUI WHERE QUI_ID=NEW.QUI_ID;
    SELECT QUI_ACTIF INTO @actif_qui FROM T_QUIZ_QUI WHERE QUI_ID=NEW.QUI_ID;
	IF (@actif_qui=1) THEN
		INSERT INTO T_ACTUALITE_ACT VALUES
			(NULL, 2, CONCAT('Nouveau quiz accessible pour les formateurs : ',@titre_qui), CONCAT('Un nouveau quiz intitulé ',@titre_qui,' est accessible pour réaliser des matchs'), NOW());
	END IF;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `T_REPONSE_REP`
--

CREATE TABLE `T_REPONSE_REP` (
  `REP_ID` int(11) NOT NULL,
  `QST_ID` int(11) NOT NULL,
  `REP_TEXTE` varchar(200) NOT NULL,
  `REP_VRAIE` tinyint(4) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `T_REPONSE_REP`
--

INSERT INTO `T_REPONSE_REP` (`REP_ID`, `QST_ID`, `REP_TEXTE`, `REP_VRAIE`) VALUES
(1, 1, 'Lisbonne', 0),
(2, 1, 'Madrid', 1),
(3, 1, 'Barcelone', 0),
(4, 1, 'Rome', 0),
(5, 2, 'Porto', 0),
(6, 2, 'Seville', 0),
(7, 2, 'Lagos', 0),
(8, 2, 'Lisbonne', 1),
(9, 3, 'Bruxelles', 0),
(10, 3, 'Varsovie', 0),
(11, 3, 'Amsterdam', 1),
(12, 3, 'Copenhague', 0),
(13, 4, 'Londres', 0),
(14, 4, 'Dublin', 1),
(15, 4, 'Edinburgh', 0),
(16, 5, 'Suisse', 0),
(17, 5, 'Autriche', 0),
(18, 5, 'Croatie', 0),
(19, 5, 'Aucun', 1),
(20, 6, 'Bucarest', 0),
(21, 6, 'Vienne', 0),
(22, 6, 'Prague', 0),
(23, 6, 'Zagreb', 1),
(24, 7, 'Nicosie', 0),
(25, 7, 'Sofia', 1),
(26, 7, 'Bucarest', 0),
(27, 7, 'Belgrade', 0),
(29, 8, 'Bratislava', 0),
(30, 8, 'Minsk', 0),
(31, 8, 'Kiev', 1),
(32, 8, 'Varsovie', 0),
(33, 9, 'Sofia', 0),
(34, 9, 'Bucarest', 1),
(35, 9, 'Budapest', 0),
(36, 9, 'Varsovie', 0),
(37, 10, 'Estonie', 0),
(38, 10, 'Lituanie', 0),
(39, 10, 'Biélorussie', 0),
(40, 10, 'Lettonie', 1),
(41, 11, '2 232,5 km', 0),
(42, 11, '6 259,6 km', 1),
(43, 11, '8 851,8 km', 0),
(44, 11, '21 196,18 km', 0),
(45, 12, 'Grèce', 0),
(46, 12, 'Espagne', 0),
(47, 12, 'Argentine', 0),
(48, 12, 'Brésil', 1),
(49, 13, 'Athène', 0),
(50, 13, 'Naples', 0),
(51, 13, 'Rome', 1),
(52, 13, 'Milan', 0),
(53, 14, 'Argentine', 0),
(54, 14, 'Chili', 0),
(55, 14, 'Mexique', 0),
(56, 14, 'Peru', 1),
(57, 15, 'Le Machu Picchu', 0),
(58, 15, 'Le site archéologique de Chichén Itza', 1),
(59, 15, 'La Pyramide de Khéops', 0),
(60, 15, 'L\'île de Cozumel', 0),
(61, 16, 'Buenos Aires', 0),
(62, 16, 'Quito', 0),
(63, 16, 'Lima', 0),
(64, 16, 'Mexico', 1),
(65, 17, 'Egypte', 1),
(66, 17, 'Erythrée', 0),
(67, 17, 'Algérie', 0),
(68, 17, 'Tunisie', 0),
(69, 18, 'Auckland', 0),
(70, 18, 'Canberra', 1),
(71, 18, 'Sydney', 0),
(72, 18, 'Melbourne', 0),
(73, 19, 'Chine', 0),
(74, 19, 'Taïwan', 0),
(75, 19, 'Corée du Nord', 1),
(76, 19, 'Corée du Sud', 0),
(77, 20, 'Kingston', 1),
(78, 20, 'Melbourne', 0),
(79, 20, 'Port Antonio', 0),
(80, 20, 'Portmore', 0),
(125, 43, 'Marseille', 0),
(126, 43, 'Lyon', 1),
(127, 43, 'Bordeaux', 0),
(128, 43, 'Grenoble', 0),
(129, 44, 'En Bretagne', 0),
(130, 44, 'En Normandie', 1),
(131, 44, 'Dans les Pays de La Loire', 0),
(132, 44, 'En Île de France', 0),
(133, 45, 'La Creuse', 0),
(134, 45, 'Le Gers', 0),
(135, 45, 'Le Cher', 0),
(136, 45, 'La Lozère', 1),
(137, 46, 'La Loire', 1),
(138, 46, 'La Seine', 0),
(139, 46, 'Le Rhône', 0),
(140, 46, 'La Moselle', 0),
(141, 47, '5', 0),
(142, 47, '24', 0),
(143, 47, '13', 1),
(144, 47, '17', 0),
(145, 48, 'Le mont d\'Or', 0),
(146, 48, 'Le mont Joli', 0),
(147, 48, 'Le mont Blanc', 1),
(148, 48, 'Le mont Noir', 0),
(149, 49, '6', 0),
(150, 49, '5', 0),
(151, 49, '8', 1),
(152, 49, '7', 0),
(153, 50, 'St-Pierre et Miquelon', 0),
(154, 50, 'La Guadeloupe', 0),
(155, 50, 'La Réunion', 0),
(156, 50, 'La Barbade', 1),
(157, 51, '4', 1),
(158, 51, '6', 0),
(159, 51, '3', 0),
(160, 51, '5', 0),
(161, 52, '6,9 millions', 0),
(162, 52, '32 millions', 0),
(163, 52, '67,2 millions', 1),
(164, 52, '85,4 millions', 0);

-- --------------------------------------------------------

--
-- Table structure for table `T_UTILISATEUR_USR`
--

CREATE TABLE `T_UTILISATEUR_USR` (
  `USR_ID` int(11) NOT NULL,
  `USR_PSEUDO` varchar(20) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `USR_MDP` char(64) NOT NULL,
  `USR_ROLE` char(1) NOT NULL,
  `USR_ETAT` char(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `T_UTILISATEUR_USR`
--

INSERT INTO `T_UTILISATEUR_USR` (`USR_ID`, `USR_PSEUDO`, `USR_MDP`, `USR_ROLE`, `USR_ETAT`) VALUES
(1, 'responsable', 'ba007ee59d5f7a2f031d501ec4d065fc35345725ec2f1be2c6d04d047c62d963', 'A', 'A'),
(2, 'LMts', '8b52963ee84b3024fd8c09f596a39f196c1266ea66070d6a1bec8c995215b535', 'A', 'A'),
(4, 'Jean', 'cc066383772e02497bfd1a127231f567141cc0b620f39ef5b06abcb3573fa0fe', 'F', 'A'),
(5, 'Pierre', 'd74199fe337033b6fcc16eda7f3de100e5a2b1d7ee0e62247b8771c65b2545c8', 'F', 'A'),
(6, 'Henry', '21550ffec15e8b9d1a721568687e57f009de49b370e070347ae77b93285c3a9c', 'F', 'D'),
(7, 'Anna', 'a7ae68b43022533315a0eb1be417ce5c12211d8524bff3ae4aaa8df3e63ac865', 'F', 'A'),
(8, 'Céline', '6033f04b81b864413682a44020141e861ac66686b3c770594324f8d534802d0a', 'F', 'D'),
(10, 'noemie', '7b4262f4028b79dcfb77c902f538f6e996cb7a91f3f388f6ea4061eac5ddb00a', 'F', 'D'),
(11, 'Martha', '31843b8fc7f014d7eead21f1aa7793114450382cec88376068de68e49b620bb2', 'F', 'A');

-- --------------------------------------------------------

--
-- Structure for view `reponses`
--
DROP TABLE IF EXISTS `reponses`;

CREATE ALGORITHM=UNDEFINED DEFINER=`zmottailo`@`%` SQL SECURITY DEFINER VIEW `reponses`  AS SELECT `T_QUESTION_QST`.`QUI_ID` AS `QUI_ID`, `T_REPONSE_REP`.`QST_ID` AS `QST_ID`, `T_REPONSE_REP`.`REP_ID` AS `REP_ID`, `T_REPONSE_REP`.`REP_TEXTE` AS `REP_TEXTE`, `T_REPONSE_REP`.`REP_VRAIE` AS `REP_VRAIE`, `T_QUESTION_QST`.`QST_INTITULE` AS `QST_INTITULE`, `T_QUESTION_QST`.`QST_IMAGE` AS `QST_IMAGE`, `T_QUESTION_QST`.`QST_ACTIVE` AS `QST_ACTIVE`, `T_QUESTION_QST`.`QST_ORDRE` AS `QST_ORDRE`, `T_QUIZ_QUI`.`USR_ID` AS `USR_ID`, `T_QUIZ_QUI`.`QUI_INTITULE` AS `QUI_INTITULE`, `T_QUIZ_QUI`.`QUI_IMAGE` AS `QUI_IMAGE`, `T_QUIZ_QUI`.`QUI_ACTIF` AS `QUI_ACTIF` FROM ((`T_REPONSE_REP` join `T_QUESTION_QST` on(`T_REPONSE_REP`.`QST_ID` = `T_QUESTION_QST`.`QST_ID`)) join `T_QUIZ_QUI` on(`T_QUESTION_QST`.`QUI_ID` = `T_QUIZ_QUI`.`QUI_ID`)) WHERE `T_REPONSE_REP`.`REP_VRAIE` = 11  ;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `T_ACTUALITE_ACT`
--
ALTER TABLE `T_ACTUALITE_ACT`
  ADD PRIMARY KEY (`ACT_ID`),
  ADD KEY `fk_T_ACTUALITES_ACT_T_UTILISATEUR_USR1_idx` (`USR_ID`);

--
-- Indexes for table `T_JOUEUR_JOU`
--
ALTER TABLE `T_JOUEUR_JOU`
  ADD PRIMARY KEY (`JOU_ID`),
  ADD KEY `fk_T_JOUEUR_JOU_T_MATCH_MAT1_idx` (`MAT_ID`);

--
-- Indexes for table `T_MATCH_MAT`
--
ALTER TABLE `T_MATCH_MAT`
  ADD PRIMARY KEY (`MAT_ID`),
  ADD UNIQUE KEY `MAT_CODE_UNIQUE` (`MAT_CODE`),
  ADD KEY `fk_T_MATCH_MAT_T_QUIZ_QUI1_idx` (`QUI_ID`),
  ADD KEY `fk_T_MATCH_MAT_T_UTILISATEUR_USR1_idx` (`USR_ID`);

--
-- Indexes for table `T_PROFIL_PRO`
--
ALTER TABLE `T_PROFIL_PRO`
  ADD PRIMARY KEY (`USR_ID`);

--
-- Indexes for table `T_QUESTION_QST`
--
ALTER TABLE `T_QUESTION_QST`
  ADD PRIMARY KEY (`QST_ID`),
  ADD KEY `fk_T_QUESTION_QST_T_QUIZ_QUI1_idx` (`QUI_ID`);

--
-- Indexes for table `T_QUIZ_QUI`
--
ALTER TABLE `T_QUIZ_QUI`
  ADD PRIMARY KEY (`QUI_ID`),
  ADD KEY `fk_T_QUIZ_QUI_T_UTILISATEUR_USR1_idx` (`USR_ID`);

--
-- Indexes for table `T_REPONSE_REP`
--
ALTER TABLE `T_REPONSE_REP`
  ADD PRIMARY KEY (`REP_ID`),
  ADD KEY `fk_T_REPONSE_REP_T_QUESTION_QST1_idx` (`QST_ID`);

--
-- Indexes for table `T_UTILISATEUR_USR`
--
ALTER TABLE `T_UTILISATEUR_USR`
  ADD PRIMARY KEY (`USR_ID`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `T_ACTUALITE_ACT`
--
ALTER TABLE `T_ACTUALITE_ACT`
  MODIFY `ACT_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=49;

--
-- AUTO_INCREMENT for table `T_JOUEUR_JOU`
--
ALTER TABLE `T_JOUEUR_JOU`
  MODIFY `JOU_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=74;

--
-- AUTO_INCREMENT for table `T_MATCH_MAT`
--
ALTER TABLE `T_MATCH_MAT`
  MODIFY `MAT_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;

--
-- AUTO_INCREMENT for table `T_PROFIL_PRO`
--
ALTER TABLE `T_PROFIL_PRO`
  MODIFY `USR_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `T_QUESTION_QST`
--
ALTER TABLE `T_QUESTION_QST`
  MODIFY `QST_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=53;

--
-- AUTO_INCREMENT for table `T_QUIZ_QUI`
--
ALTER TABLE `T_QUIZ_QUI`
  MODIFY `QUI_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `T_REPONSE_REP`
--
ALTER TABLE `T_REPONSE_REP`
  MODIFY `REP_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=165;

--
-- AUTO_INCREMENT for table `T_UTILISATEUR_USR`
--
ALTER TABLE `T_UTILISATEUR_USR`
  MODIFY `USR_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `T_ACTUALITE_ACT`
--
ALTER TABLE `T_ACTUALITE_ACT`
  ADD CONSTRAINT `fk_T_ACTUALITES_ACT_T_UTILISATEUR_USR1` FOREIGN KEY (`USR_ID`) REFERENCES `T_UTILISATEUR_USR` (`USR_ID`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `T_JOUEUR_JOU`
--
ALTER TABLE `T_JOUEUR_JOU`
  ADD CONSTRAINT `fk_T_JOUEUR_JOU_T_MATCH_MAT1` FOREIGN KEY (`MAT_ID`) REFERENCES `T_MATCH_MAT` (`MAT_ID`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `T_MATCH_MAT`
--
ALTER TABLE `T_MATCH_MAT`
  ADD CONSTRAINT `fk_T_MATCH_MAT_T_QUIZ_QUI1` FOREIGN KEY (`QUI_ID`) REFERENCES `T_QUIZ_QUI` (`QUI_ID`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_T_MATCH_MAT_T_UTILISATEUR_USR1` FOREIGN KEY (`USR_ID`) REFERENCES `T_UTILISATEUR_USR` (`USR_ID`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `T_PROFIL_PRO`
--
ALTER TABLE `T_PROFIL_PRO`
  ADD CONSTRAINT `fk_T_PROFIL_PRO_T_UTILISATEUR_USR` FOREIGN KEY (`USR_ID`) REFERENCES `T_UTILISATEUR_USR` (`USR_ID`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `T_QUESTION_QST`
--
ALTER TABLE `T_QUESTION_QST`
  ADD CONSTRAINT `fk_T_QUESTION_QST_T_QUIZ_QUI1` FOREIGN KEY (`QUI_ID`) REFERENCES `T_QUIZ_QUI` (`QUI_ID`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `T_QUIZ_QUI`
--
ALTER TABLE `T_QUIZ_QUI`
  ADD CONSTRAINT `fk_T_QUIZ_QUI_T_UTILISATEUR_USR1` FOREIGN KEY (`USR_ID`) REFERENCES `T_UTILISATEUR_USR` (`USR_ID`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `T_REPONSE_REP`
--
ALTER TABLE `T_REPONSE_REP`
  ADD CONSTRAINT `fk_T_REPONSE_REP_T_QUESTION_QST1` FOREIGN KEY (`QST_ID`) REFERENCES `T_QUESTION_QST` (`QST_ID`) ON DELETE NO ACTION ON UPDATE NO ACTION;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
