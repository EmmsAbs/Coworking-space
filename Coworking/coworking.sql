-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1:3308
-- Généré le : ven. 14 fév. 2025 à 09:27
-- Version du serveur : 9.1.0
-- Version de PHP : 8.3.14

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `coworking`
--

-- --------------------------------------------------------

--
-- Structure de la table `client`
--

DROP TABLE IF EXISTS `client`;
CREATE TABLE IF NOT EXISTS `client` (
  `id` int NOT NULL,
  `actif` tinyint(1) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `client`
--

INSERT INTO `client` (`id`, `actif`) VALUES
(39, 1),
(40, 1),
(42, 1);

-- --------------------------------------------------------

--
-- Structure de la table `equipement`
--

DROP TABLE IF EXISTS `equipement`;
CREATE TABLE IF NOT EXISTS `equipement` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nom` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=42 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `equipement`
--

INSERT INTO `equipement` (`id`, `nom`) VALUES
(1, 'Mobilier de bureau ergonomique'),
(2, 'Connexion internet haut débit'),
(3, 'Salles de réunion équipées'),
(4, 'Espaces de détente'),
(5, 'Cuisine/espace repas'),
(6, 'Imprimante/scanner multifonction'),
(7, 'Fournitures de bureau'),
(8, 'Casiers ou espaces de rangement individuels'),
(9, 'Bonne isolation phonique'),
(10, 'Lumière naturelle abondante'),
(11, 'Plantes'),
(12, 'Système de climatisation/chauffage'),
(13, 'Prises électriques en nombre suffisant'),
(14, 'Eau filtrée et collations'),
(15, 'Douches'),
(30, 'Projecteur'),
(37, 'Extincteur'),
(40, 'Ecran plasma 8K'),
(41, 'Cuisine équipé');

-- --------------------------------------------------------

--
-- Structure de la table `evenement`
--

DROP TABLE IF EXISTS `evenement`;
CREATE TABLE IF NOT EXISTS `evenement` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nom` varchar(255) DEFAULT NULL,
  `description` varchar(500) DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `tarif` double DEFAULT NULL,
  `participants` int DEFAULT NULL,
  `salle_id` int DEFAULT NULL,
  `dateDebut` datetime DEFAULT NULL,
  `dateFin` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `FK_Evenement_type` (`salle_id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `evenement`
--

INSERT INTO `evenement` (`id`, `nom`, `description`, `image`, `tarif`, `participants`, `salle_id`, `dateDebut`, `dateFin`) VALUES
(3, 'Afterwork', 'Team building, et relaxe', '../Img67ad868224a3f_afterwork-compressed.jpg', 300, 9, 17, '2025-02-21 20:00:00', '2025-02-21 22:00:00');

-- --------------------------------------------------------

--
-- Structure de la table `evenement_salle_client`
--

DROP TABLE IF EXISTS `evenement_salle_client`;
CREATE TABLE IF NOT EXISTS `evenement_salle_client` (
  `evenement_id` int NOT NULL,
  `salle_id` int NOT NULL,
  `client_id` int NOT NULL,
  `dateDebut` datetime DEFAULT NULL,
  `dateFin` datetime DEFAULT NULL,
  PRIMARY KEY (`evenement_id`,`salle_id`,`client_id`),
  KEY `salle_id` (`salle_id`),
  KEY `client_id` (`client_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Structure de la table `gestionnaire`
--

DROP TABLE IF EXISTS `gestionnaire`;
CREATE TABLE IF NOT EXISTS `gestionnaire` (
  `id` int NOT NULL,
  `type` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `gestionnaire`
--

INSERT INTO `gestionnaire` (`id`, `type`) VALUES
(38, 'SuperAdmin');

-- --------------------------------------------------------

--
-- Structure de la table `participants`
--

DROP TABLE IF EXISTS `participants`;
CREATE TABLE IF NOT EXISTS `participants` (
  `id` int NOT NULL AUTO_INCREMENT,
  `evenement_id` int NOT NULL,
  `client_id` int NOT NULL,
  `date_inscription` datetime NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique_participation` (`evenement_id`,`client_id`),
  KEY `client_id` (`client_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Structure de la table `participation`
--

DROP TABLE IF EXISTS `participation`;
CREATE TABLE IF NOT EXISTS `participation` (
  `evenement_id` int NOT NULL,
  `client_id` int NOT NULL,
  `date_inscription` datetime NOT NULL,
  `active` tinyint(1) DEFAULT '1',
  PRIMARY KEY (`evenement_id`,`client_id`),
  KEY `client_id` (`client_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `participation`
--

INSERT INTO `participation` (`evenement_id`, `client_id`, `date_inscription`, `active`) VALUES
(3, 39, '2025-02-14 06:26:59', 1);

-- --------------------------------------------------------

--
-- Structure de la table `reservation`
--

DROP TABLE IF EXISTS `reservation`;
CREATE TABLE IF NOT EXISTS `reservation` (
  `id` int NOT NULL AUTO_INCREMENT,
  `client_id` int DEFAULT NULL,
  `salle_id` int DEFAULT NULL,
  `dateDebut` datetime DEFAULT NULL,
  `dateFin` datetime DEFAULT NULL,
  `annule` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `client_id` (`client_id`),
  KEY `salle_id` (`salle_id`)
) ENGINE=InnoDB AUTO_INCREMENT=27 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `reservation`
--

INSERT INTO `reservation` (`id`, `client_id`, `salle_id`, `dateDebut`, `dateFin`, `annule`) VALUES
(11, 39, 14, '2025-02-08 14:00:00', '2025-02-08 15:00:00', 0),
(12, 39, 14, '2025-02-09 15:00:00', '2025-02-09 16:00:00', 0),
(13, 40, 18, '2025-02-09 10:00:00', '2025-02-09 11:00:00', 1),
(14, 40, 18, '2025-02-10 13:00:00', '2025-02-10 14:00:00', 0),
(15, 40, 18, '2025-02-07 14:00:00', '2025-02-07 15:00:00', 0),
(16, 40, 14, '2025-02-10 10:00:00', '2025-02-10 14:00:00', 0),
(17, 39, 15, '2025-02-09 10:00:00', '2025-02-09 11:00:00', 0),
(18, 39, 15, '2025-02-08 12:00:00', '2025-02-08 13:00:00', 0),
(19, 39, 14, '2025-02-09 10:00:00', '2025-02-09 11:00:00', 0),
(20, 39, 14, '2025-02-08 12:00:00', '2025-02-08 13:00:00', 0),
(22, NULL, 17, '2025-02-12 20:00:00', '2025-02-12 22:00:00', 0),
(23, 39, 17, '2025-02-20 21:00:00', '2025-02-20 22:00:00', 0),
(24, 39, 17, '2025-02-20 22:00:00', '2025-02-20 23:00:00', 0),
(25, 39, 14, '2025-02-20 21:00:00', '2025-02-20 22:00:00', 0),
(26, 39, 14, '2025-02-20 22:00:00', '2025-02-20 23:00:00', 0);

-- --------------------------------------------------------

--
-- Structure de la table `salle`
--

DROP TABLE IF EXISTS `salle`;
CREATE TABLE IF NOT EXISTS `salle` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nom` varchar(255) DEFAULT NULL,
  `description` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `capacite` int DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `price` double DEFAULT NULL,
  `type_id` int DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `type_id` (`type_id`)
) ENGINE=InnoDB AUTO_INCREMENT=22 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `salle`
--

INSERT INTO `salle` (`id`, `nom`, `description`, `capacite`, `image`, `price`, `type_id`) VALUES
(14, 'Lovelace', 'Salle de réunion privative', 10, '../Img/lovelace.jpg', 100, 3),
(15, 'Namèke', 'Open space', 60, '../Img/namèke.jpg', 150, 1),
(16, 'Le Valala', 'Bureau équipé pour une entreprise', 100, '../Img/le_valala.jpg', 225, 8),
(17, 'Espace Wassa - Foray', 'Coin de détente ', 20, '../Img/espace_wassa_-_foray.jpg', 100, 5),
(18, 'Salle lumière', '', 20, '../Img/salle_lumière.jpg', 175, 4),
(19, 'Spark VIP', '', 10, '../Img/spark_vip.jpg', 300, 4),
(20, 'Salle Murna', '', 40, '../Img/salle_murna.jpg', 150, 6),
(21, 'Murna', 'Bureau privatif', 10, '../Img/murna.jpg', 150, 2);

-- --------------------------------------------------------

--
-- Structure de la table `salle_equipement`
--

DROP TABLE IF EXISTS `salle_equipement`;
CREATE TABLE IF NOT EXISTS `salle_equipement` (
  `salle_id` int NOT NULL,
  `equipement_id` int NOT NULL,
  PRIMARY KEY (`salle_id`,`equipement_id`),
  KEY `equipement_id` (`equipement_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `salle_equipement`
--

INSERT INTO `salle_equipement` (`salle_id`, `equipement_id`) VALUES
(15, 1),
(16, 1),
(17, 1),
(19, 1),
(21, 1),
(15, 2),
(16, 2),
(17, 2),
(18, 2),
(19, 2),
(20, 2),
(14, 3),
(16, 3),
(19, 3),
(21, 3),
(16, 4),
(16, 5),
(14, 6),
(15, 6),
(16, 6),
(15, 7),
(16, 7),
(21, 7),
(16, 9),
(16, 10),
(19, 10),
(19, 11),
(16, 12),
(19, 12),
(14, 13),
(18, 13),
(19, 13),
(18, 14),
(19, 14),
(20, 14),
(16, 15),
(19, 15),
(16, 30),
(18, 30),
(19, 30),
(15, 37),
(16, 37),
(21, 37),
(14, 40),
(16, 40),
(18, 40),
(19, 40),
(21, 40),
(20, 41);

-- --------------------------------------------------------

--
-- Structure de la table `type`
--

DROP TABLE IF EXISTS `type`;
CREATE TABLE IF NOT EXISTS `type` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nom` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `type`
--

INSERT INTO `type` (`id`, `nom`) VALUES
(1, 'Open space'),
(2, 'Bureau privé'),
(3, 'Salle de réunion'),
(4, 'Salle de conférence'),
(5, 'Espace détente'),
(6, 'Cafétéria'),
(7, 'Box de réunion'),
(8, 'Local de bureau complet');

-- --------------------------------------------------------

--
-- Structure de la table `utilisateur`
--

DROP TABLE IF EXISTS `utilisateur`;
CREATE TABLE IF NOT EXISTS `utilisateur` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nom` varchar(255) DEFAULT NULL,
  `username` varchar(255) DEFAULT NULL,
  `prenom` varchar(255) DEFAULT NULL,
  `adresse` varchar(255) DEFAULT NULL,
  `CIN` varchar(20) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `blocked` tinyint(1) DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`),
  UNIQUE KEY `CIN` (`CIN`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=43 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `utilisateur`
--

INSERT INTO `utilisateur` (`id`, `nom`, `username`, `prenom`, `adresse`, `CIN`, `email`, `password`, `blocked`) VALUES
(38, 'Emmanuel', 'Emmanuel', 'Alphonse', 'Marrakech', '987UYGHjk', 'emmalphonse@gmail.com', '$2y$10$B.6YYb/kPhc0hIyW1TOYGuwMJ5boSmViuwbnLFek9rkq0H4wNlala', 0),
(39, 'Hugo', 'ha', 'Almehda', 'Casablanca', 'jdh', 'ha@j.ha', '$2y$10$m7CI5AcdggCpVRi.v4NOue4WOSybLTEFT2EFvTd77Wn9xkxHoV4Ie', 0),
(40, 'Rayan', 'rayan', 'bayerou', 'Paris 9ème', '987YUKJHN', 'rba@gmail.com', '$2y$10$/SvDFt3RQzU.06TqfiOYm.02oG.7CSIeXbweKB11GoMBCZnkHKGmG', 0),
(42, 'Adam', 'Adams', 'Serge', 'Casablanca', 'EZ0254875', 'Adamsserge@gmail.com', '$2y$10$Z4BZAI69sIp6OLC3hLN.a.EepoGqYP2DIYjqMPoOCJIM/TxqgIwQu', 0);

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `client`
--
ALTER TABLE `client`
  ADD CONSTRAINT `client_ibfk_1` FOREIGN KEY (`id`) REFERENCES `utilisateur` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `evenement`
--
ALTER TABLE `evenement`
  ADD CONSTRAINT `FK_Evenement_type` FOREIGN KEY (`salle_id`) REFERENCES `salle` (`id`);

--
-- Contraintes pour la table `evenement_salle_client`
--
ALTER TABLE `evenement_salle_client`
  ADD CONSTRAINT `evenement_salle_client_ibfk_1` FOREIGN KEY (`evenement_id`) REFERENCES `evenement` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `evenement_salle_client_ibfk_2` FOREIGN KEY (`salle_id`) REFERENCES `salle` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `evenement_salle_client_ibfk_3` FOREIGN KEY (`client_id`) REFERENCES `client` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `gestionnaire`
--
ALTER TABLE `gestionnaire`
  ADD CONSTRAINT `gestionnaire_ibfk_1` FOREIGN KEY (`id`) REFERENCES `utilisateur` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `reservation`
--
ALTER TABLE `reservation`
  ADD CONSTRAINT `reservation_ibfk_1` FOREIGN KEY (`client_id`) REFERENCES `client` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `reservation_ibfk_2` FOREIGN KEY (`salle_id`) REFERENCES `salle` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `salle`
--
ALTER TABLE `salle`
  ADD CONSTRAINT `salle_ibfk_1` FOREIGN KEY (`type_id`) REFERENCES `type` (`id`) ON DELETE SET NULL;

--
-- Contraintes pour la table `salle_equipement`
--
ALTER TABLE `salle_equipement`
  ADD CONSTRAINT `salle_equipement_ibfk_1` FOREIGN KEY (`salle_id`) REFERENCES `salle` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `salle_equipement_ibfk_2` FOREIGN KEY (`equipement_id`) REFERENCES `equipement` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
