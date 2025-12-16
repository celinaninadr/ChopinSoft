-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1
-- Généré le : lun. 15 déc. 2025 à 14:10
-- Version du serveur : 10.4.32-MariaDB
-- Version de PHP : 8.2.12

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

CREATE TABLE `avatar` (
  `idAvatar` int(11) NOT NULL,
  `nameAvatar` varchar(100) NOT NULL,
  `modelAvatar` varchar(255) NOT NULL,
  `imgAvatar` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `user`
--

CREATE TABLE `user` (
  `idUser` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `userRole` enum('ADMIN','JOUEUR') DEFAULT 'JOUEUR',
  `idAvatar` int(11) NOT NULL,
  `idWorld` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `world`
--

CREATE TABLE `world` (
  `idWorld` int(11) NOT NULL,
  `nameWorld` varchar(100) NOT NULL,
  `imgWorld` varchar(255) DEFAULT NULL,
  `urlWorld` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `avatar`
--
ALTER TABLE `avatar`
  ADD PRIMARY KEY (`idAvatar`);

--
-- Index pour la table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`idUser`),
  ADD UNIQUE KEY `username` (`username`),
  ADD KEY `fk_user_avatar` (`idAvatar`),
  ADD KEY `fk_user_world` (`idWorld`);

--
-- Index pour la table `world`
--
ALTER TABLE `world`
  ADD PRIMARY KEY (`idWorld`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `avatar`
--
ALTER TABLE `avatar`
  MODIFY `idAvatar` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `user`
--
ALTER TABLE `user`
  MODIFY `idUser` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `world`
--
ALTER TABLE `world`
  MODIFY `idWorld` int(11) NOT NULL AUTO_INCREMENT;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `user`
--
ALTER TABLE `user`
  ADD CONSTRAINT `fk_user_avatar` FOREIGN KEY (`idAvatar`) REFERENCES `avatar` (`idAvatar`),
  ADD CONSTRAINT `fk_user_world` FOREIGN KEY (`idWorld`) REFERENCES `world` (`idWorld`);

-- --------------------------------------------------------
-- Données d'exemple
-- --------------------------------------------------------

INSERT INTO `avatar` (`idAvatar`, `nameAvatar`, `modelAvatar`, `imgAvatar`) VALUES
(1, 'Chevalier', '', NULL),
(2, 'Mage', '', NULL),
(3, 'Explorateur', '', NULL);

INSERT INTO `world` (`idWorld`, `nameWorld`, `imgWorld`, `urlWorld`) VALUES
(1, 'Monde 1', NULL, 'https://example.com/monde1'),
(2, 'Monde 2', NULL, 'https://example.com/monde2'),
(3, 'Monde 3', NULL, 'https://example.com/monde3'),
(4, 'Monde 4', NULL, 'https://example.com/monde4'),
(5, 'Monde 5', NULL, 'https://example.com/monde5');

-- Admin par défaut: login admin / mdp admin
-- NOTE: mot de passe hashé (password_hash('admin', PASSWORD_DEFAULT))
INSERT INTO `user` (`idUser`, `username`, `password`, `userRole`, `idAvatar`, `idWorld`) VALUES
(1, 'admin', '$2y$10$Qh6G7xN1gGQX4Qy2VdU7XelQm3lVQ8qzq3z3mS3tqG0d5VQXkz9cK', 'ADMIN', 1, 1);

ALTER TABLE `avatar` AUTO_INCREMENT = 4;
ALTER TABLE `world` AUTO_INCREMENT = 6;
ALTER TABLE `user` AUTO_INCREMENT = 2;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
