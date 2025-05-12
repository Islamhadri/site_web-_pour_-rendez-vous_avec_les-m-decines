-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1
-- Généré le : lun. 12 mai 2025 à 19:49
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
-- Base de données : `appointment_system`
--

-- --------------------------------------------------------

--
-- Structure de la table `appointments`
--

CREATE TABLE `appointments` (
  `id` int(11) NOT NULL,
  `patient_id` int(11) DEFAULT NULL,
  `doctor_id` int(11) DEFAULT NULL,
  `appointment_date` date DEFAULT NULL,
  `appointment_time` time DEFAULT NULL,
  `urgent_message` text DEFAULT NULL,
  `status` enum('pending','approved','rejected') DEFAULT 'pending'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `appointments`
--

INSERT INTO `appointments` (`id`, `patient_id`, `doctor_id`, `appointment_date`, `appointment_time`, `urgent_message`, `status`) VALUES
(1, NULL, 6, '2025-05-01', '13:30:00', '', 'pending'),
(2, NULL, 9, '2025-05-11', '13:00:00', '', 'pending'),
(3, NULL, 6, '0205-11-11', '13:00:00', '', 'pending'),
(4, NULL, 7, '2025-11-11', '13:00:00', '', 'pending'),
(5, NULL, 6, '2025-11-11', '13:00:00', '', 'pending'),
(6, 2, 6, '2025-11-11', NULL, 'salut', 'approved'),
(7, 2, 9, '2025-11-11', NULL, '', 'pending'),
(8, 5, 11, '2025-11-11', NULL, '', 'pending'),
(9, 5, 6, '2025-05-13', NULL, '', 'approved'),
(10, 5, 12, '2025-11-11', NULL, '', 'rejected'),
(11, 5, 6, '2025-05-12', NULL, '', 'approved'),
(12, 2, 13, '2025-05-18', NULL, '', 'approved');

-- --------------------------------------------------------

--
-- Structure de la table `doctors`
--

CREATE TABLE `doctors` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `doctor_number` varchar(15) NOT NULL,
  `hospital_name` varchar(255) NOT NULL,
  `specialty` varchar(255) NOT NULL,
  `profile_picture` varchar(255) DEFAULT NULL,
  `wilaya` varchar(100) DEFAULT NULL,
  `nom` varchar(100) DEFAULT NULL,
  `prenom` varchar(100) DEFAULT NULL,
  `dob` date DEFAULT NULL,
  `lieu_travail` varchar(30) DEFAULT NULL,
  `num_medical` int(11) DEFAULT NULL,
  `identite_file` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `doctors`
--

INSERT INTO `doctors` (`id`, `user_id`, `doctor_number`, `hospital_name`, `specialty`, `profile_picture`, `wilaya`, `nom`, `prenom`, `dob`, `lieu_travail`, `num_medical`, `identite_file`) VALUES
(1, 26, '', '', '', NULL, 'boumerdas', 'dr mano', 'mano', '1999-10-22', 'boumerdas', 2147483647, 'uploads/681e5bcae3ae4_Capture d’écran_1-5-2025_1214_.jpeg'),
(6, 33, '456464654565456', 'clinqiue alsalam', 'geo', NULL, 'boumerdas', 'adhem', 'aa', '2001-07-22', NULL, NULL, 'uploads/681e75d2d2c8b_Capture d’écran_1-5-2025_12454_.jpeg'),
(7, 35, '12345678', '', 'dentiste', NULL, 'boumerdas', NULL, NULL, NULL, NULL, NULL, NULL),
(9, 38, '123456789991', '', 'dentiste', NULL, 'boumerdas', NULL, NULL, NULL, NULL, NULL, NULL),
(10, 39, '145949846541', '', 'dentiste', NULL, 'boumerdas', NULL, NULL, NULL, NULL, NULL, NULL),
(11, 40, '1234569798', 'alama', 'generaliste', 'uploads/1747001258_Capture d’écran_1-5-2025_1214_.jpeg', '', 'mohamedd', 'mohamedd', '0000-00-00', NULL, 2147483647, 'uploads/1747001258_Capture d’écran_1-5-2025_11215_.jpeg'),
(12, 42, '1234569768', 'alama', 'generaliste', 'uploads/1747001663_Capture d’écran_1-5-2025_13338_.jpeg', '', '', '', '1999-01-11', NULL, 2147483647, 'uploads/1747001663_Capture d’écran_1-5-2025_12454_.jpeg'),
(13, 43, '456489756456', 'sidi yahia', 'generaliste vh', 'uploads/1747055642_Capture d’écran_1-5-2025_11215_.jpeg', '', '', '', '1969-01-11', NULL, 145978536, 'uploads/1747055642_Capture d’écran_1-5-2025_11215_.jpeg');

-- --------------------------------------------------------

--
-- Structure de la table `messages`
--

CREATE TABLE `messages` (
  `id` int(11) NOT NULL,
  `sender_id` int(11) NOT NULL,
  `receiver_id` int(11) NOT NULL,
  `appointment_id` int(11) NOT NULL,
  `message` text NOT NULL,
  `sent_at` datetime DEFAULT current_timestamp(),
  `sender_type` enum('doctor','patient') NOT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `receiver_type` varchar(10) DEFAULT NULL,
  `timestamp` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `messages`
--

INSERT INTO `messages` (`id`, `sender_id`, `receiver_id`, `appointment_id`, `message`, `sent_at`, `sender_type`, `created_at`, `receiver_type`, `timestamp`) VALUES
(1, 6, 5, 11, 'hi', '2025-05-12 01:25:16', 'doctor', '2025-05-12 01:25:16', NULL, '2025-05-12 14:12:39'),
(2, 5, 6, 11, 'salam', '2025-05-12 01:39:51', 'doctor', '2025-05-12 01:39:51', NULL, '2025-05-12 14:12:39'),
(3, 6, 5, 11, 'hi', '2025-05-12 01:49:03', 'doctor', '2025-05-12 01:49:03', NULL, '2025-05-12 14:12:39'),
(4, 6, 5, 11, 'hi houhohuhhh', '2025-05-12 01:49:12', 'doctor', '2025-05-12 01:49:12', NULL, '2025-05-12 14:12:39'),
(5, 33, 0, 11, 'bonjour', '2025-05-12 13:55:24', 'doctor', '2025-05-12 13:55:24', NULL, '2025-05-12 14:12:39'),
(6, 5, 6, 11, 'oui ', '2025-05-12 14:10:25', 'patient', '2025-05-12 14:10:25', 'doctor', '2025-05-12 14:12:39'),
(7, 5, 6, 11, 'cv', '2025-05-12 14:12:49', 'patient', '2025-05-12 14:12:49', 'doctor', '2025-05-12 14:12:49'),
(8, 33, 0, 11, 'hmd et toi ', '2025-05-12 14:13:24', 'doctor', '2025-05-12 14:13:24', NULL, '2025-05-12 14:13:24'),
(9, 5, 6, 11, 'oui monsieur ', '2025-05-12 14:15:54', 'patient', '2025-05-12 14:15:54', 'doctor', '2025-05-12 14:15:54'),
(10, 5, 6, 11, 'on a un rendez vous apres ', '2025-05-12 14:17:05', 'patient', '2025-05-12 14:17:05', 'doctor', '2025-05-12 14:17:05'),
(11, 5, 6, 9, 'salam', '2025-05-12 14:17:30', 'patient', '2025-05-12 14:17:30', 'doctor', '2025-05-12 14:17:30'),
(12, 33, 0, 9, 'oui ', '2025-05-12 14:17:51', 'doctor', '2025-05-12 14:17:51', NULL, '2025-05-12 14:17:51'),
(13, 6, 5, 11, 'je sais ', '2025-05-12 14:52:01', 'doctor', '2025-05-12 14:52:01', 'patient', '2025-05-12 14:52:01'),
(14, 6, 2, 6, 'moh cv khoya', '2025-05-12 15:11:56', 'doctor', '2025-05-12 15:11:56', 'patient', '2025-05-12 15:11:56'),
(15, 2, 6, 6, 'cv et toi ', '2025-05-12 15:12:21', 'patient', '2025-05-12 15:12:21', 'doctor', '2025-05-12 15:12:21'),
(16, 13, 2, 12, 'salam ', '2025-05-12 15:16:08', 'doctor', '2025-05-12 15:16:08', 'patient', '2025-05-12 15:16:08'),
(17, 2, 13, 12, 'oui', '2025-05-12 15:16:37', 'patient', '2025-05-12 15:16:37', 'doctor', '2025-05-12 15:16:37'),
(18, 2, 13, 12, 'kifch drna', '2025-05-12 15:19:35', 'patient', '2025-05-12 15:19:35', 'doctor', '2025-05-12 15:19:35'),
(19, 2, 6, 6, 'docteur \r\n', '2025-05-12 19:20:15', 'patient', '2025-05-12 19:20:15', 'doctor', '2025-05-12 19:20:15');

-- --------------------------------------------------------

--
-- Structure de la table `patients`
--

CREATE TABLE `patients` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `first_name` varchar(100) NOT NULL,
  `last_name` varchar(100) NOT NULL,
  `phone_number` varchar(15) NOT NULL,
  `address` varchar(255) NOT NULL,
  `date_of_birth` date NOT NULL,
  `social_security_number` varchar(50) NOT NULL,
  `medical_conditions` text DEFAULT NULL,
  `nom` varchar(255) DEFAULT NULL,
  `prenom` varchar(100) DEFAULT NULL,
  `dob` date DEFAULT NULL,
  `nss` varchar(20) DEFAULT NULL,
  `maladie` varchar(100) DEFAULT NULL,
  `wilaya` varchar(25) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `patients`
--

INSERT INTO `patients` (`id`, `user_id`, `first_name`, `last_name`, `phone_number`, `address`, `date_of_birth`, `social_security_number`, `medical_conditions`, `nom`, `prenom`, `dob`, `nss`, `maladie`, `wilaya`) VALUES
(1, 19, '', '', '', '', '0000-00-00', '', NULL, 'islam', 'islam', '2001-10-22', '156465498', 'cœur', 'boumerdas'),
(2, 20, '', '', '', '', '0000-00-00', '', NULL, 'mohamed', 'mohamed', '2001-02-22', '1234567890', 'côlon', 'boumerdas'),
(3, 29, '', '', '', '', '0000-00-00', '', NULL, 'mohammed', 'mohamed', '2001-02-22', '1234567890', 'côlon', 'boumerdas'),
(4, 36, '', '', '', '', '0000-00-00', '123455', NULL, NULL, NULL, NULL, NULL, 'sugar', 'boumerdas'),
(5, 41, '', '', '', '', '0000-00-00', '', NULL, 'aziz', 'aziz', NULL, '1234567892', 'sugar', 'alger');

-- --------------------------------------------------------

--
-- Structure de la table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `user_type` enum('patient','doctor') NOT NULL,
  `prenom` varchar(100) DEFAULT NULL,
  `dob` date DEFAULT NULL,
  `nss` varchar(20) DEFAULT NULL,
  `maladie` varchar(100) DEFAULT NULL,
  `wilaya` varchar(25) DEFAULT NULL,
  `nom` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `users`
--

INSERT INTO `users` (`id`, `email`, `password`, `user_type`, `prenom`, `dob`, `nss`, `maladie`, `wilaya`, `nom`) VALUES
(3, 'islam@gmail.com', '$2y$10$YhowV0wmjXwoAagE6UClruacjARCrGLa7jA/APZ6W2MQEYyGr3VWK', 'doctor', NULL, NULL, NULL, NULL, NULL, ''),
(5, 'islam1@gmail.com', '$2y$10$X6RWr8vCxs6DOysV4ypI7.WsU9LqLRxRExC7/FJzSoibYPpuDutoK', 'patient', NULL, NULL, NULL, NULL, NULL, ''),
(7, 'islam1@gmail.com', '$2y$10$JUXB0GNvPcwUJc7IIWO55.JYnu3nYtBRJqj6BN2vfdn3ZdZLlmvMC', 'patient', NULL, NULL, NULL, NULL, NULL, ''),
(8, 'islam1@gmail.com', '$2y$10$ku0BwNyhltTURLDX9veMX.eQ6gWO0cwl8NSL/oY7N49orfXPCRNae', 'patient', NULL, NULL, NULL, NULL, NULL, ''),
(9, 'islam1@gmail.com', '$2y$10$EUIErf7nW2kN/Pf3DHSsiOuClp.RgN4aey7Yf50/xQAoKXAzCWMwC', 'patient', NULL, NULL, NULL, NULL, NULL, ''),
(10, 'islam1@gmail.com', '$2y$10$xboMyY/b6QPmsimBNXy/cusBGdGMSxvdDmfS/y3YFDFFQ40IDLTJO', 'patient', NULL, NULL, NULL, NULL, NULL, ''),
(11, 'islam1@gmail.com', '$2y$10$JF66BjHzgXYp8P16p6nKheI1jSeXICRitymaVuGhnJLLEgH1XpCYe', 'patient', NULL, NULL, NULL, NULL, NULL, ''),
(12, 'islam1@gmail.com', '$2y$10$UTkjL0zZVw..pu12Gwb5T.a3piMdW8XC1NEIWHcc3y.ZSaZ5lyzpm', 'patient', NULL, NULL, NULL, NULL, NULL, ''),
(13, 'islam1@gmail.com', '$2y$10$MQ/4m6uj6EQHCXJ27evp1O3kbfGwsUr0B.G/hqX17g86y0aexTDKq', 'patient', NULL, NULL, NULL, NULL, NULL, ''),
(14, 'islam1@gmail.com', '$2y$10$Nu3vpukFPgH1fHBnqD.BnO26PerjfWj9PEQD9/ig9qAlevM4Ywwqq', 'patient', NULL, NULL, NULL, NULL, NULL, ''),
(15, 'islam1@gmail.com', '$2y$10$gKdxVbvUHRgZ4cC678Uf5e3VIjEjn3461W5Q3bCq3GPL0vtMgNdP6', 'patient', NULL, NULL, NULL, NULL, NULL, ''),
(16, 'islam1@gmail.com', '$2y$10$nCh8FvKdqgT3cqPB4IG7V.mdzQ1g7ax1yPIw9B1ZH.bguNJTD5KDS', 'patient', NULL, NULL, NULL, NULL, NULL, ''),
(17, 'islam1@gmail.com', '$2y$10$wdpaCny/GgKAwET/AdNsRO7QhW54RhxctYsI9OfTond4J1nP27ujG', 'patient', NULL, NULL, NULL, NULL, NULL, ''),
(18, 'islam1@gmail.com', '$2y$10$Ytuejj.0qR0Ob0fMcujeWeXSDeRRyc7kKgZijBdk9/i/ajU1noVDW', 'patient', NULL, NULL, NULL, NULL, NULL, ''),
(19, 'islam1@gmail.com', '$2y$10$axFM30khrSL76Tskw1mrYOFQuX/UEiNHwRYf54seUZ2QbeXQ83eQ.', 'patient', NULL, NULL, NULL, NULL, NULL, ''),
(20, 'moh@gmail.com', '$2y$10$y.8G.A1hh4KsXtpw/5/wSu6wFBOkHmZ5H5CbSI423o1jw5Lu2pLwy', 'patient', NULL, NULL, NULL, NULL, NULL, ''),
(21, 'islam@gmail.com', '$2y$10$qGgc.gMDNTYqdfhUT.F3juxg6cgHU8hgOv8f4it.XOJNFZHZsrbZC', 'doctor', NULL, NULL, NULL, NULL, NULL, ''),
(22, 'islam@gmail.com', '$2y$10$I/950RAWobXrNQmo1f08n.aJrsvXTMJCXkIx8K8063/C6CT33oycS', 'doctor', NULL, NULL, NULL, NULL, NULL, ''),
(23, 'islam@gmail.com', '$2y$10$YcMQ7fVeWhLPGuCvKJq7o.jGYmPXsYtnXE/5ZFICR3STWjf8qQQKG', 'doctor', NULL, NULL, NULL, NULL, NULL, ''),
(24, 'islam@gmail.com', '$2y$10$ncZxobIqUd6Kfc36PW0nW.dLoxBjmzDbkT2XLwvcV2.eWG5xEh812', 'doctor', NULL, NULL, NULL, NULL, NULL, ''),
(25, 'islam@gmail.com', '$2y$10$pMO/4yM0ekXgglonuQ.y8.HfjJLEut1QgscVSn86LakS5TDSrjwuq', 'doctor', NULL, NULL, NULL, NULL, NULL, ''),
(26, 'islam@gmail.com', '$2y$10$dXA9jgF1GlJ7SsBY5uOOW.oPkWK37a3eLXK4JEGWSezqP3lP78/wi', 'doctor', NULL, NULL, NULL, NULL, NULL, ''),
(27, 'islam@gmail.com', '$2y$10$1sV9SPugkOTcajh6sWUcxugUqbv3VsGdHMMp4SZjcRGOmT9oobBn.', 'doctor', NULL, NULL, NULL, NULL, NULL, ''),
(28, 'islam@gmail.com', '$2y$10$Lp.OMZXudAPkcSH7mjZXPuBRF5h3OzRQXkJ4x6h/05XaOo83XFoQm', 'doctor', NULL, NULL, NULL, NULL, NULL, ''),
(29, 'moha@gmail.com', '$2y$10$G5kBWYwYlOXhx4sbipMb2OjZyFL332N9NArVq9hXlDxZ9t.wfZqlu', 'patient', NULL, NULL, NULL, NULL, NULL, ''),
(30, 'islaam@gmail.com', '$2y$10$b0aqskAbIUB0yq/3KCuqqOOjsOqLhgKVHdTDo8//zF9gksC6n3ISa', 'doctor', NULL, NULL, NULL, NULL, NULL, ''),
(31, 'hello@gmail.com', '$2y$10$5kQ22UAxegmsccdQZaya6.jwDB315GBPcoH3DW3/kwk42Vh84SxIa', 'doctor', NULL, NULL, NULL, NULL, NULL, ''),
(32, 'hello@gmail.com', '$2y$10$glpD6Ja0aWShhmSP50mmG.bNmehUyjjBmiUMWTrK9rt9FU2eorHxy', 'doctor', NULL, NULL, NULL, NULL, NULL, ''),
(33, 'aa@gmail.com', '$2y$10$V/H0t9lgcgdHAX271Ds5L.KrHgOtl9DznKpjQf/c1dOuFukhbktqq', 'doctor', NULL, NULL, NULL, NULL, NULL, ''),
(34, 'fire@gmail.com', '$2y$10$AnsrbPqwXNxSFkA6qD1p1.gfJG.4xL/SW9hrBdgHyd6OiTpQYg5pK', '', 'mohamed', NULL, NULL, NULL, NULL, 'fire'),
(35, 'fire2@gmail.com', '$2y$10$YSW5HqX1pyXyrtNpzkl/yONeqDqe1WBCwEaZjYh2O0vIm0kpR3OkO', '', 'mohamed', NULL, NULL, NULL, NULL, 'fire'),
(36, 'moha11@gmail.com', '$2y$10$ocfZszMk5f49vxnw/g4/yuMrc96gbM8Ce6DLVfxuhJzrauMKQ.bRW', 'patient', 'mohamed', NULL, NULL, NULL, NULL, 'mohamed'),
(37, 'fire23@gmail.com', '$2y$10$yc1GagOn6KU5G.jzOApF0erDHj8NmbEw03cpFduMcZyjKYJcVF4Zm', '', 'mohamed', NULL, NULL, NULL, NULL, 'fire'),
(38, 'karim1@gmail.com', '$2y$10$jkFcwe55s6XWre5Kou/.gepzR10Jm6/H6OniesATwcPiD2rpNIuXS', '', 'karim', NULL, NULL, NULL, NULL, 'mohamed'),
(39, 'is@gmail.com', '$2y$10$wGXiL0UCFNJKuyMqpwR7rubEiH6MR5EkYWNkUjvPrJ93aU7.IGsVi', '', 'islama', NULL, NULL, NULL, NULL, 'islam'),
(40, 'is11@gmail.com', '$2y$10$ra1kzUqAhe7ZVSsQsolreeYowesR46dAzl3HQfzc59F42cyJ1NukW', 'doctor', NULL, NULL, NULL, NULL, NULL, ''),
(41, 'az@gmail.com', '$2y$10$uhYKYl4HGl0WZtZhy2.TWe/j.6DY464VrtpVa2PhZPLO8clmsQUmW', 'patient', NULL, NULL, NULL, NULL, NULL, ''),
(42, 'ias@gmail.com', '$2y$10$r269LVTk6NtOSqGnKLqXguVdMYvbfGHDVNvb8M.nIv9JvlEBpo8Aq', 'doctor', NULL, NULL, NULL, NULL, NULL, ''),
(43, 'isa@gmail.com', '$2y$10$P9..ffR9r3VENiN3.PNjLuz1GfX34XILkUyKhuY.EqEzMCUK2qSsy', 'doctor', NULL, NULL, NULL, NULL, NULL, '');

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `appointments`
--
ALTER TABLE `appointments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `patient_id` (`patient_id`),
  ADD KEY `doctor_id` (`doctor_id`);

--
-- Index pour la table `doctors`
--
ALTER TABLE `doctors`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `doctor_number` (`doctor_number`),
  ADD KEY `user_id` (`user_id`);

--
-- Index pour la table `messages`
--
ALTER TABLE `messages`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `patients`
--
ALTER TABLE `patients`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Index pour la table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `appointments`
--
ALTER TABLE `appointments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT pour la table `doctors`
--
ALTER TABLE `doctors`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT pour la table `messages`
--
ALTER TABLE `messages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT pour la table `patients`
--
ALTER TABLE `patients`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT pour la table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=44;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `appointments`
--
ALTER TABLE `appointments`
  ADD CONSTRAINT `appointments_ibfk_1` FOREIGN KEY (`patient_id`) REFERENCES `patients` (`id`),
  ADD CONSTRAINT `appointments_ibfk_2` FOREIGN KEY (`doctor_id`) REFERENCES `doctors` (`id`);

--
-- Contraintes pour la table `doctors`
--
ALTER TABLE `doctors`
  ADD CONSTRAINT `doctors_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Contraintes pour la table `patients`
--
ALTER TABLE `patients`
  ADD CONSTRAINT `patients_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
