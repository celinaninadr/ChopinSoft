-- phpMyAdmin SQL Dump
-- version 5.2.3
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1:3306
-- Généré le : mer. 17 déc. 2025 à 09:32
-- Version du serveur : 8.4.7
-- Version de PHP : 8.3.28

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `chopin_vr`
--

-- --------------------------------------------------------

--
-- Structure de la table `avatar`
--

DROP TABLE IF EXISTS `avatar`;
CREATE TABLE IF NOT EXISTS `avatar` (
  `idAvatar` int NOT NULL AUTO_INCREMENT,
  `nameAvatar` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `modelAvatar` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `imgAvatar` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  PRIMARY KEY (`idAvatar`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `avatar`
--

INSERT INTO `avatar` (`idAvatar`, `nameAvatar`, `modelAvatar`, `imgAvatar`) VALUES
(1, 'astronaute', 'assets/modelAvatar/astronaut.glb', 'assets/images/avatar/astronaute.jpg'),
(2, 'avanturier', 'assets/modelAvatar/adventurer.glb', 'assets/images/avatar/aventurier.jpg'),
(3, 'chien_pug', 'assets/modelAvatar/pug.glb', 'assets/images/avatar/chien_pug.jpg'),
(4, 'fitness_man', 'assets/modelAvatar/fitness.glb', 'assets/images/avatar/fitness_man.jpg'),
(5, 'requin_mako', 'assets/modelAvatar/mako.glb', 'assets/images/avatar/requin_mako.jpg');

-- --------------------------------------------------------

--
-- Structure de la table `user`
--

DROP TABLE IF EXISTS `user`;
CREATE TABLE IF NOT EXISTS `user` (
  `idUser` int NOT NULL AUTO_INCREMENT,
  `username` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `userRole` enum('ADMIN','JOUEUR') COLLATE utf8mb4_general_ci DEFAULT 'JOUEUR',
  `idAvatar` int NOT NULL,
  `idWorld` int NOT NULL,
  PRIMARY KEY (`idUser`),
  UNIQUE KEY `username` (`username`),
  KEY `fk_user_avatar` (`idAvatar`),
  KEY `fk_user_world` (`idWorld`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `user`
--

INSERT INTO `user` (`idUser`, `username`, `password`, `userRole`, `idAvatar`, `idWorld`) VALUES
(4, 'oni.no.kage', '$2y$10$3PCe7EP2mzm0EXio8HlwjukE6JJnzw5PbIHxR9oysRuIGqdy1QOj2', 'ADMIN', 3, 1),
(5, 'qfyeqb', '$2y$10$xJCuNUaWCpDdI3znw.qn/.TfCW4fGGEee63CiJjtK9eB5KPsxnUvK', 'JOUEUR', 3, 1);

-- --------------------------------------------------------

--
-- Structure de la table `world`
--

DROP TABLE IF EXISTS `world`;
CREATE TABLE IF NOT EXISTS `world` (
  `idWorld` int NOT NULL AUTO_INCREMENT,
  `nameWorld` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `imgWorld` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `urlWorld` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  PRIMARY KEY (`idWorld`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `world`
--

INSERT INTO `world` (`idWorld`, `nameWorld`, `imgWorld`, `urlWorld`) VALUES
(1, 'desert', 'assets/images/world/desert.jpg', ''),
(2, 'foret', 'assets/images/world/foret.jpg', ''),
(3, 'hiver', 'assets/images/world/hiver.jpg', ''),
(6, 'prairie', 'assets/images/world/prairie.jpg', ''),
(7, 'tron', 'assets/images/world/tron.jpg', '');

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `user`
--
ALTER TABLE `user`
  ADD CONSTRAINT `fk_user_avatar` FOREIGN KEY (`idAvatar`) REFERENCES `avatar` (`idAvatar`),
  ADD CONSTRAINT `fk_user_world` FOREIGN KEY (`idWorld`) REFERENCES `world` (`idWorld`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
