-- phpMyAdmin SQL Dump
-- version 5.0.2
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1:3306
-- Généré le : mer. 26 jan. 2022 à 09:10
-- Version du serveur :  5.7.31
-- Version de PHP : 7.3.21

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `bd-gestion-utilisateur`
--

-- --------------------------------------------------------

--
-- Structure de la table `statuts`
--

DROP TABLE IF EXISTS `statuts`;
CREATE TABLE IF NOT EXISTS `statuts` (
  `statut_id` int(20) NOT NULL,
  `statut` varchar(120) CHARACTER SET utf8 NOT NULL,
  PRIMARY KEY (`statut_id`),
  UNIQUE KEY `nom` (`statut`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table `tache`
--

DROP TABLE IF EXISTS `tache`;
CREATE TABLE IF NOT EXISTS `tache` (
  `tache_id` int(50) NOT NULL AUTO_INCREMENT,
  `titre` varchar(120) NOT NULL,
  `descriptions` text,
  `utilisateur_id_t` int(20) NOT NULL,
  `statut` int(11) NOT NULL DEFAULT '0' COMMENT '0 = incomplète, 1 = en cours, 2 = terminé',
  PRIMARY KEY (`tache_id`),
  KEY `utilisateur_id_t` (`utilisateur_id_t`)
) ENGINE=InnoDB AUTO_INCREMENT=54 DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `tache`
--

INSERT INTO `tache` (`tache_id`, `titre`, `descriptions`, `utilisateur_id_t`, `statut`) VALUES
(48, 'Finir php', 'Partie utilisateur', 40, 1),
(50, 'wpf', 'regarder video 4', 30, 0),
(52, 'Devoir', 'Finir exercice 3', 42, 2);

-- --------------------------------------------------------

--
-- Structure de la table `utilisateur`
--

DROP TABLE IF EXISTS `utilisateur`;
CREATE TABLE IF NOT EXISTS `utilisateur` (
  `utilisateur_id` int(20) NOT NULL AUTO_INCREMENT,
  `statut` varchar(120) NOT NULL COMMENT 'Admin, Client ou Travailleur',
  `identifiant` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(100) NOT NULL,
  `temp_password` varchar(100) DEFAULT NULL,
  `user_role` int(10) NOT NULL COMMENT '1 = admin, 2 = client ou travailleur',
  PRIMARY KEY (`utilisateur_id`),
  KEY `utilisateur_id` (`utilisateur_id`),
  KEY `statut` (`statut`)
) ENGINE=InnoDB AUTO_INCREMENT=44 DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `utilisateur`
--

INSERT INTO `utilisateur` (`utilisateur_id`, `statut`, `identifiant`, `email`, `password`, `temp_password`, `user_role`) VALUES
(1, 'Admin', 'admin', 'admin@gmail.com', 'cac29d7a34687eb14b37068ee4708e7b', NULL, 1),
(30, 'Travailleur', 'marouane', 'marouane@hotmail.com', '9636fb98ef6c20fa604cbc6dfccbcf42', '', 2),
(39, 'Client', 'islam', 'islam@hotmail.com', 'a1b50735f644583cae29aa6902c70d82', '3150618', 2),
(40, 'Client', 'adil', 'adil@hotmail.com', '55533e2fd7f0c8980e6f07d1f85d5902', '', 2),
(42, 'Client', 'mohammed', 'mohammed@hotmail.com', '55d6e3b1c7f671534b1fc9c8ffc4077c', '', 2);

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `statuts`
--
ALTER TABLE `statuts`
  ADD CONSTRAINT `statuts_ibfk_1` FOREIGN KEY (`statut`) REFERENCES `utilisateur` (`statut`);

--
-- Contraintes pour la table `tache`
--
ALTER TABLE `tache`
  ADD CONSTRAINT `tache_ibfk_1` FOREIGN KEY (`utilisateur_id_t`) REFERENCES `utilisateur` (`utilisateur_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
