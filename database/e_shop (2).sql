-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1
-- Généré le : jeu. 05 mars 2026 à 20:42
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
-- Base de données : `e_shop`
--

-- --------------------------------------------------------

--
-- Structure de la table `avis`
--

CREATE TABLE `avis` (
  `id` int(11) NOT NULL,
  `date` date NOT NULL,
  `contenu` longtext NOT NULL,
  `user_id` int(11) NOT NULL,
  `produit_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `commande`
--

CREATE TABLE `commande` (
  `id` int(11) NOT NULL,
  `Datecommande` date NOT NULL,
  `prixtotale` double NOT NULL,
  `user_id` int(11) NOT NULL,
  `facture_id` int(11) DEFAULT NULL,
  `status` varchar(50) NOT NULL DEFAULT 'pending',
  `numero_commande` varchar(255) DEFAULT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `commande`
--

INSERT INTO `commande` (`id`, `Datecommande`, `prixtotale`, `user_id`, `facture_id`, `status`, `numero_commande`, `created_at`, `updated_at`) VALUES
(1, '2026-01-04', 316, 11, NULL, 'confirmed', 'CMD-20260104-000001', '2026-01-04 13:11:54', '2026-01-04 13:34:28'),
(2, '2026-01-04', 189.6, 12, NULL, 'pending', 'CMD-20260104-000002', '2026-01-04 14:52:05', '2026-01-04 14:55:57'),
(3, '2026-01-04', 63.2, 12, NULL, 'pending', 'CMD-20260104-000003', '2026-01-04 15:12:48', '2026-01-04 15:12:48'),
(4, '2026-01-05', 298, 14, NULL, 'confirmed', 'CMD-20260105-000004', '2026-01-05 11:07:06', '2026-01-07 22:22:33'),
(5, '2026-01-05', 126.4, 12, NULL, 'delivered', 'CMD-20260105-000005', '2026-01-05 11:16:40', '2026-01-05 11:18:59');

-- --------------------------------------------------------

--
-- Structure de la table `commande_produit`
--

CREATE TABLE `commande_produit` (
  `id` int(11) NOT NULL,
  `quantite` int(11) DEFAULT NULL,
  `prix_unitaire` double NOT NULL,
  `commande_id` int(11) NOT NULL,
  `produit_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `commande_produit`
--

INSERT INTO `commande_produit` (`id`, `quantite`, `prix_unitaire`, `commande_id`, `produit_id`) VALUES
(1, 5, 63.2, 1, 3),
(2, 3, 63.2, 2, 3),
(3, 1, 63.2, 3, 3),
(4, 2, 149, 4, 9),
(5, 2, 63.2, 5, 3);

-- --------------------------------------------------------

--
-- Structure de la table `doctrine_migration_versions`
--

CREATE TABLE `doctrine_migration_versions` (
  `version` varchar(191) NOT NULL,
  `executed_at` datetime DEFAULT NULL,
  `execution_time` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `doctrine_migration_versions`
--

INSERT INTO `doctrine_migration_versions` (`version`, `executed_at`, `execution_time`) VALUES
('DoctrineMigrations\\Version20250115000000', '2026-01-05 02:11:19', 129),
('DoctrineMigrations\\Version20250115000001', '2026-01-05 02:18:02', 16),
('DoctrineMigrations\\Version20251202145032', '2025-12-08 00:06:48', 5),
('DoctrineMigrations\\Version20251206230000', '2025-12-08 00:07:17', 10),
('DoctrineMigrations\\Version20251206231000', '2025-12-08 00:08:34', 155),
('DoctrineMigrations\\Version20251207235846', '2025-12-08 00:08:51', 81),
('DoctrineMigrations\\Version20260104120237', '2026-01-04 12:04:51', 386),
('DoctrineMigrations\\\\Version20251202145032', '2025-12-08 01:07:32', 0),
('DoctrineMigrations\\\\Version20251206230000', '2025-12-08 01:07:32', 0),
('DoctrineMigrations\\\\Version20251206231000', '2025-12-08 01:07:32', 0);

-- --------------------------------------------------------

--
-- Structure de la table `facture`
--

CREATE TABLE `facture` (
  `id` int(11) NOT NULL,
  `commande_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `facture`
--

INSERT INTO `facture` (`id`, `commande_id`) VALUES
(1, 1),
(2, 2),
(3, 3),
(4, 4),
(5, 5);

-- --------------------------------------------------------

--
-- Structure de la table `messenger_messages`
--

CREATE TABLE `messenger_messages` (
  `id` bigint(20) NOT NULL,
  `body` longtext NOT NULL,
  `headers` longtext NOT NULL,
  `queue_name` varchar(190) NOT NULL,
  `created_at` datetime NOT NULL,
  `available_at` datetime NOT NULL,
  `delivered_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `messenger_messages`
--

INSERT INTO `messenger_messages` (`id`, `body`, `headers`, `queue_name`, `created_at`, `available_at`, `delivered_at`) VALUES
(1, 'O:36:\\\"Symfony\\\\Component\\\\Messenger\\\\Envelope\\\":2:{s:44:\\\"\\0Symfony\\\\Component\\\\Messenger\\\\Envelope\\0stamps\\\";a:1:{s:46:\\\"Symfony\\\\Component\\\\Messenger\\\\Stamp\\\\BusNameStamp\\\";a:1:{i:0;O:46:\\\"Symfony\\\\Component\\\\Messenger\\\\Stamp\\\\BusNameStamp\\\":1:{s:55:\\\"\\0Symfony\\\\Component\\\\Messenger\\\\Stamp\\\\BusNameStamp\\0busName\\\";s:21:\\\"messenger.bus.default\\\";}}}s:45:\\\"\\0Symfony\\\\Component\\\\Messenger\\\\Envelope\\0message\\\";O:51:\\\"Symfony\\\\Component\\\\Mailer\\\\Messenger\\\\SendEmailMessage\\\":2:{s:60:\\\"\\0Symfony\\\\Component\\\\Mailer\\\\Messenger\\\\SendEmailMessage\\0message\\\";O:28:\\\"Symfony\\\\Component\\\\Mime\\\\Email\\\":6:{i:0;N;i:1;N;i:2;s:2232:\\\"<!DOCTYPE html>\n<html>\n<head>\n    <meta charset=\\\"UTF-8\\\">\n    <style>\n        body {\n            font-family: Arial, sans-serif;\n            line-height: 1.6;\n            color: #333;\n            max-width: 600px;\n            margin: 0 auto;\n            padding: 20px;\n        }\n        .header {\n            background-color: #8C907E;\n            color: white;\n            padding: 20px;\n            text-align: center;\n        }\n        .content {\n            background-color: #f9f9f9;\n            padding: 30px;\n        }\n        .button {\n            display: inline-block;\n            background-color: #8C907E;\n            color: white;\n            padding: 12px 30px;\n            text-decoration: none;\n            border-radius: 0;\n            margin: 20px 0;\n            text-transform: uppercase;\n            letter-spacing: 1px;\n        }\n        .footer {\n            text-align: center;\n            padding: 20px;\n            color: #666;\n            font-size: 12px;\n        }\n    </style>\n</head>\n<body>\n    <div class=\\\"header\\\">\n        <h1>Kaira Fashion Store</h1>\n    </div>\n    <div class=\\\"content\\\">\n        <h2>Réinitialisation de votre mot de passe</h2>\n        <p>Bonjour Mohtadi romene,</p>\n        <p>Vous avez demandé à réinitialiser votre mot de passe. Cliquez sur le bouton ci-dessous pour créer un nouveau mot de passe :</p>\n        <p style=\\\"text-align: center;\\\">\n            <a href=\\\"http://127.0.0.1:8000/reset-password/376651c086da0a268beb1bb60b931044a079e4e9802224174a3243fad66cea36\\\" class=\\\"button\\\">Réinitialiser mon mot de passe</a>\n        </p>\n        <p>Ou copiez et collez ce lien dans votre navigateur :</p>\n        <p style=\\\"word-break: break-all; color: #8C907E;\\\">http://127.0.0.1:8000/reset-password/376651c086da0a268beb1bb60b931044a079e4e9802224174a3243fad66cea36</p>\n        <p><strong>Ce lien est valide pendant 1 heure.</strong></p>\n        <p>Si vous n\\\'avez pas demandé cette réinitialisation, ignorez simplement cet email. Votre mot de passe restera inchangé.</p>\n        <p>Cordialement,<br>L\\\'équipe Kaira Fashion Store</p>\n    </div>\n    <div class=\\\"footer\\\">\n        <p>Cet email a été envoyé automatiquement, merci de ne pas y répondre.</p>\n    </div>\n</body>\n</html>\n\\\";i:3;s:5:\\\"utf-8\\\";i:4;a:0:{}i:5;a:2:{i:0;O:37:\\\"Symfony\\\\Component\\\\Mime\\\\Header\\\\Headers\\\":2:{s:46:\\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\Headers\\0headers\\\";a:3:{s:4:\\\"from\\\";a:1:{i:0;O:47:\\\"Symfony\\\\Component\\\\Mime\\\\Header\\\\MailboxListHeader\\\":5:{s:50:\\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\AbstractHeader\\0name\\\";s:4:\\\"From\\\";s:56:\\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\AbstractHeader\\0lineLength\\\";i:76;s:50:\\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\AbstractHeader\\0lang\\\";N;s:53:\\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\AbstractHeader\\0charset\\\";s:5:\\\"utf-8\\\";s:58:\\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\MailboxListHeader\\0addresses\\\";a:1:{i:0;O:30:\\\"Symfony\\\\Component\\\\Mime\\\\Address\\\":2:{s:39:\\\"\\0Symfony\\\\Component\\\\Mime\\\\Address\\0address\\\";s:17:\\\"noreply@kaira.com\\\";s:36:\\\"\\0Symfony\\\\Component\\\\Mime\\\\Address\\0name\\\";s:0:\\\"\\\";}}}}s:2:\\\"to\\\";a:1:{i:0;O:47:\\\"Symfony\\\\Component\\\\Mime\\\\Header\\\\MailboxListHeader\\\":5:{s:50:\\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\AbstractHeader\\0name\\\";s:2:\\\"To\\\";s:56:\\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\AbstractHeader\\0lineLength\\\";i:76;s:50:\\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\AbstractHeader\\0lang\\\";N;s:53:\\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\AbstractHeader\\0charset\\\";s:5:\\\"utf-8\\\";s:58:\\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\MailboxListHeader\\0addresses\\\";a:1:{i:0;O:30:\\\"Symfony\\\\Component\\\\Mime\\\\Address\\\":2:{s:39:\\\"\\0Symfony\\\\Component\\\\Mime\\\\Address\\0address\\\";s:25:\\\"mohtadiromene00@gmail.com\\\";s:36:\\\"\\0Symfony\\\\Component\\\\Mime\\\\Address\\0name\\\";s:0:\\\"\\\";}}}}s:7:\\\"subject\\\";a:1:{i:0;O:48:\\\"Symfony\\\\Component\\\\Mime\\\\Header\\\\UnstructuredHeader\\\":5:{s:50:\\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\AbstractHeader\\0name\\\";s:7:\\\"Subject\\\";s:56:\\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\AbstractHeader\\0lineLength\\\";i:76;s:50:\\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\AbstractHeader\\0lang\\\";N;s:53:\\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\AbstractHeader\\0charset\\\";s:5:\\\"utf-8\\\";s:55:\\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\UnstructuredHeader\\0value\\\";s:61:\\\"Réinitialisation de votre mot de passe - Kaira Fashion Store\\\";}}}s:49:\\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\Headers\\0lineLength\\\";i:76;}i:1;N;}}s:61:\\\"\\0Symfony\\\\Component\\\\Mailer\\\\Messenger\\\\SendEmailMessage\\0envelope\\\";N;}}', '[]', 'default', '2026-01-05 03:02:53', '2026-01-05 03:02:53', NULL),
(2, 'O:36:\\\"Symfony\\\\Component\\\\Messenger\\\\Envelope\\\":2:{s:44:\\\"\\0Symfony\\\\Component\\\\Messenger\\\\Envelope\\0stamps\\\";a:1:{s:46:\\\"Symfony\\\\Component\\\\Messenger\\\\Stamp\\\\BusNameStamp\\\";a:1:{i:0;O:46:\\\"Symfony\\\\Component\\\\Messenger\\\\Stamp\\\\BusNameStamp\\\":1:{s:55:\\\"\\0Symfony\\\\Component\\\\Messenger\\\\Stamp\\\\BusNameStamp\\0busName\\\";s:21:\\\"messenger.bus.default\\\";}}}s:45:\\\"\\0Symfony\\\\Component\\\\Messenger\\\\Envelope\\0message\\\";O:51:\\\"Symfony\\\\Component\\\\Mailer\\\\Messenger\\\\SendEmailMessage\\\":2:{s:60:\\\"\\0Symfony\\\\Component\\\\Mailer\\\\Messenger\\\\SendEmailMessage\\0message\\\";O:28:\\\"Symfony\\\\Component\\\\Mime\\\\Email\\\":6:{i:0;N;i:1;N;i:2;s:2232:\\\"<!DOCTYPE html>\n<html>\n<head>\n    <meta charset=\\\"UTF-8\\\">\n    <style>\n        body {\n            font-family: Arial, sans-serif;\n            line-height: 1.6;\n            color: #333;\n            max-width: 600px;\n            margin: 0 auto;\n            padding: 20px;\n        }\n        .header {\n            background-color: #8C907E;\n            color: white;\n            padding: 20px;\n            text-align: center;\n        }\n        .content {\n            background-color: #f9f9f9;\n            padding: 30px;\n        }\n        .button {\n            display: inline-block;\n            background-color: #8C907E;\n            color: white;\n            padding: 12px 30px;\n            text-decoration: none;\n            border-radius: 0;\n            margin: 20px 0;\n            text-transform: uppercase;\n            letter-spacing: 1px;\n        }\n        .footer {\n            text-align: center;\n            padding: 20px;\n            color: #666;\n            font-size: 12px;\n        }\n    </style>\n</head>\n<body>\n    <div class=\\\"header\\\">\n        <h1>Kaira Fashion Store</h1>\n    </div>\n    <div class=\\\"content\\\">\n        <h2>Réinitialisation de votre mot de passe</h2>\n        <p>Bonjour Mohtadi romene,</p>\n        <p>Vous avez demandé à réinitialiser votre mot de passe. Cliquez sur le bouton ci-dessous pour créer un nouveau mot de passe :</p>\n        <p style=\\\"text-align: center;\\\">\n            <a href=\\\"http://127.0.0.1:8000/reset-password/262c69f418aded9afe340eb5011f1b23bd16615e3cb74cc0f947f9864af1c379\\\" class=\\\"button\\\">Réinitialiser mon mot de passe</a>\n        </p>\n        <p>Ou copiez et collez ce lien dans votre navigateur :</p>\n        <p style=\\\"word-break: break-all; color: #8C907E;\\\">http://127.0.0.1:8000/reset-password/262c69f418aded9afe340eb5011f1b23bd16615e3cb74cc0f947f9864af1c379</p>\n        <p><strong>Ce lien est valide pendant 1 heure.</strong></p>\n        <p>Si vous n\\\'avez pas demandé cette réinitialisation, ignorez simplement cet email. Votre mot de passe restera inchangé.</p>\n        <p>Cordialement,<br>L\\\'équipe Kaira Fashion Store</p>\n    </div>\n    <div class=\\\"footer\\\">\n        <p>Cet email a été envoyé automatiquement, merci de ne pas y répondre.</p>\n    </div>\n</body>\n</html>\n\\\";i:3;s:5:\\\"utf-8\\\";i:4;a:0:{}i:5;a:2:{i:0;O:37:\\\"Symfony\\\\Component\\\\Mime\\\\Header\\\\Headers\\\":2:{s:46:\\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\Headers\\0headers\\\";a:3:{s:4:\\\"from\\\";a:1:{i:0;O:47:\\\"Symfony\\\\Component\\\\Mime\\\\Header\\\\MailboxListHeader\\\":5:{s:50:\\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\AbstractHeader\\0name\\\";s:4:\\\"From\\\";s:56:\\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\AbstractHeader\\0lineLength\\\";i:76;s:50:\\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\AbstractHeader\\0lang\\\";N;s:53:\\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\AbstractHeader\\0charset\\\";s:5:\\\"utf-8\\\";s:58:\\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\MailboxListHeader\\0addresses\\\";a:1:{i:0;O:30:\\\"Symfony\\\\Component\\\\Mime\\\\Address\\\":2:{s:39:\\\"\\0Symfony\\\\Component\\\\Mime\\\\Address\\0address\\\";s:17:\\\"noreply@kaira.com\\\";s:36:\\\"\\0Symfony\\\\Component\\\\Mime\\\\Address\\0name\\\";s:0:\\\"\\\";}}}}s:2:\\\"to\\\";a:1:{i:0;O:47:\\\"Symfony\\\\Component\\\\Mime\\\\Header\\\\MailboxListHeader\\\":5:{s:50:\\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\AbstractHeader\\0name\\\";s:2:\\\"To\\\";s:56:\\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\AbstractHeader\\0lineLength\\\";i:76;s:50:\\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\AbstractHeader\\0lang\\\";N;s:53:\\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\AbstractHeader\\0charset\\\";s:5:\\\"utf-8\\\";s:58:\\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\MailboxListHeader\\0addresses\\\";a:1:{i:0;O:30:\\\"Symfony\\\\Component\\\\Mime\\\\Address\\\":2:{s:39:\\\"\\0Symfony\\\\Component\\\\Mime\\\\Address\\0address\\\";s:25:\\\"mohtadiromene00@gmail.com\\\";s:36:\\\"\\0Symfony\\\\Component\\\\Mime\\\\Address\\0name\\\";s:0:\\\"\\\";}}}}s:7:\\\"subject\\\";a:1:{i:0;O:48:\\\"Symfony\\\\Component\\\\Mime\\\\Header\\\\UnstructuredHeader\\\":5:{s:50:\\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\AbstractHeader\\0name\\\";s:7:\\\"Subject\\\";s:56:\\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\AbstractHeader\\0lineLength\\\";i:76;s:50:\\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\AbstractHeader\\0lang\\\";N;s:53:\\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\AbstractHeader\\0charset\\\";s:5:\\\"utf-8\\\";s:55:\\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\UnstructuredHeader\\0value\\\";s:61:\\\"Réinitialisation de votre mot de passe - Kaira Fashion Store\\\";}}}s:49:\\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\Headers\\0lineLength\\\";i:76;}i:1;N;}}s:61:\\\"\\0Symfony\\\\Component\\\\Mailer\\\\Messenger\\\\SendEmailMessage\\0envelope\\\";N;}}', '[]', 'default', '2026-01-05 03:09:08', '2026-01-05 03:09:08', NULL);

-- --------------------------------------------------------

--
-- Structure de la table `panier_produit`
--

CREATE TABLE `panier_produit` (
  `id` int(11) NOT NULL,
  `quantite` int(11) NOT NULL,
  `prix_unitaire` double NOT NULL,
  `panier_id` int(11) NOT NULL,
  `produit_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `panier_produit`
--

INSERT INTO `panier_produit` (`id`, `quantite`, `prix_unitaire`, `panier_id`, `produit_id`) VALUES
(3, 3, 79, 6, 3),
(10, 1, 79, 4, 3);

-- --------------------------------------------------------

--
-- Structure de la table `pannier`
--

CREATE TABLE `pannier` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `commande_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `pannier`
--

INSERT INTO `pannier` (`id`, `user_id`, `commande_id`) VALUES
(1, 6, NULL),
(2, 7, NULL),
(3, 8, NULL),
(4, 2, NULL),
(5, 1, NULL),
(6, 10, NULL),
(7, 11, NULL),
(8, 12, NULL),
(9, 14, NULL);

-- --------------------------------------------------------

--
-- Structure de la table `produit`
--

CREATE TABLE `produit` (
  `id` int(11) NOT NULL,
  `nom_produit` varchar(255) NOT NULL,
  `prix` double NOT NULL,
  `type` varchar(255) NOT NULL,
  `taille` varchar(50) DEFAULT NULL,
  `couleur` varchar(50) DEFAULT NULL,
  `genre` varchar(50) DEFAULT NULL,
  `pointure` int(11) DEFAULT NULL,
  `accessoire_type` varchar(50) DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `description` longtext DEFAULT NULL,
  `en_stock` tinyint(1) NOT NULL DEFAULT 1,
  `stock` int(11) NOT NULL DEFAULT 0,
  `created_at` datetime NOT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `produit`
--

INSERT INTO `produit` (`id`, `nom_produit`, `prix`, `type`, `taille`, `couleur`, `genre`, `pointure`, `accessoire_type`, `image`, `description`, `en_stock`, `stock`, `created_at`, `updated_at`) VALUES
(2, 'Robe Élégante Soirée', 189, 'vetement', 'S', 'Rouge', 'Femme', NULL, NULL, 'product-item-1.jpg', NULL, 1, 50, '2026-01-04 13:04:51', '2026-01-05 01:44:43'),
(3, 'Pull en Laine Douce', 79, 'vetement', 'L', 'Beige', 'Unisexe', NULL, NULL, 'product-item-2.jpg', NULL, 1, 39, '2026-01-04 13:04:51', '2026-01-05 11:16:40'),
(4, 'Chemise Blanche Formelle', 89, 'vetement', 'M', 'Blanc', 'Homme', NULL, NULL, 'product-item-3.jpg', NULL, 1, 50, '2026-01-04 13:04:51', '2026-01-05 01:44:43'),
(5, 'Jupe Midi Plissée', 69, 'vetement', 'S', 'Bleu', 'Femme', NULL, NULL, 'product-item-4.jpg', NULL, 1, 50, '2026-01-04 13:04:51', '2026-01-05 01:44:43'),
(6, 'Pantalon Taille Haute', 99, 'vetement', 'M', 'Noir', 'Femme', NULL, NULL, 'product-item-5.jpg', NULL, 1, 50, '2026-01-04 13:04:51', '2026-01-05 01:44:43'),
(7, 'Blazer Élégant', 249, 'vetement', 'L', 'Bleu Marine', 'Homme', NULL, NULL, 'product-item-6.jpg', NULL, 1, 50, '2026-01-04 13:04:51', '2026-01-05 01:44:43'),
(8, 'Baskets Sport Premium', 129, 'chaussure', NULL, NULL, NULL, 42, NULL, 'product-item-7.jpg', NULL, 1, 50, '2026-01-04 13:04:51', '2026-01-05 01:44:43'),
(9, 'Escarpins Élégants', 149, 'chaussure', NULL, NULL, NULL, 38, NULL, 'product-item-8.jpg', NULL, 1, 48, '2026-01-04 13:04:51', '2026-01-05 11:07:06'),
(10, 'Bottes en Cuir', 199, 'chaussure', NULL, NULL, NULL, 40, NULL, 'product-item-9.jpg', NULL, 1, 50, '2026-01-04 13:04:51', '2026-01-05 01:44:43'),
(11, 'Mocassins Classiques', 119, 'chaussure', NULL, NULL, NULL, 41, NULL, 'product-item-10.jpg', NULL, 1, 50, '2026-01-04 13:04:51', '2026-01-05 01:44:43'),
(12, 'Sandales Été', 59, 'chaussure', NULL, NULL, NULL, 39, NULL, 'wishlist-item1.jpg', NULL, 1, 50, '2026-01-04 13:04:51', '2026-01-05 01:44:43'),
(13, 'Bottines Automne', 179, 'chaussure', NULL, NULL, NULL, 40, NULL, 'wishlist-item2.jpg', NULL, 1, 50, '2026-01-04 13:04:51', '2026-01-05 01:44:43'),
(14, 'Sac à Main Cuir', 179, 'accessoire', NULL, NULL, NULL, NULL, 'Sac', 'wishlist-item3.jpg', NULL, 1, 50, '2026-01-04 13:04:51', '2026-01-05 01:44:43'),
(15, 'Ceinture en Cuir', 49, 'accessoire', NULL, NULL, NULL, NULL, 'Ceinture', 'cat-item1.jpg', NULL, 1, 50, '2026-01-04 13:04:51', '2026-01-05 01:44:43'),
(17, 'Lunettes de Soleil', 89, 'accessoire', NULL, NULL, NULL, NULL, 'Lunettes', 'cat-item2.jpg', NULL, 1, 50, '2026-01-04 13:04:51', '2026-01-05 01:44:43'),
(18, 'Collier Perles', 129, 'accessoire', NULL, NULL, NULL, NULL, 'Bijoux', 'cat-item3.jpg', NULL, 1, 50, '2026-01-04 13:04:51', '2026-01-05 01:44:43'),
(19, 'Portefeuille Cuir', 79, 'accessoire', NULL, NULL, NULL, NULL, 'Portefeuille', 'product-item-1.jpg', NULL, 1, 50, '2026-01-04 13:04:51', '2026-01-05 01:44:43');

-- --------------------------------------------------------

--
-- Structure de la table `promotion`
--

CREATE TABLE `promotion` (
  `id` int(11) NOT NULL,
  `date_expiration` date NOT NULL,
  `reduction` double NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `promotion`
--

INSERT INTO `promotion` (`id`, `date_expiration`, `reduction`) VALUES
(1, '2026-01-07', 20),
(2, '2025-12-23', 15),
(3, '2025-12-15', 30),
(4, '2200-04-03', 50);

-- --------------------------------------------------------

--
-- Structure de la table `promotion_produit`
--

CREATE TABLE `promotion_produit` (
  `promotion_id` int(11) NOT NULL,
  `produit_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `promotion_produit`
--

INSERT INTO `promotion_produit` (`promotion_id`, `produit_id`) VALUES
(1, 2),
(1, 3),
(2, 4),
(2, 5),
(2, 6),
(4, 10);

-- --------------------------------------------------------

--
-- Structure de la table `user`
--

CREATE TABLE `user` (
  `id` int(11) NOT NULL,
  `email` varchar(180) NOT NULL,
  `nom` varchar(255) NOT NULL,
  `tel` varchar(20) NOT NULL,
  `adresse` varchar(255) NOT NULL,
  `roles` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL CHECK (json_valid(`roles`)),
  `password` varchar(255) NOT NULL,
  `is_verified` tinyint(1) NOT NULL DEFAULT 0,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` datetime NOT NULL,
  `updated_at` datetime DEFAULT NULL,
  `reset_token` varchar(255) DEFAULT NULL,
  `reset_token_expires_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `user`
--

INSERT INTO `user` (`id`, `email`, `nom`, `tel`, `adresse`, `roles`, `password`, `is_verified`, `is_active`, `created_at`, `updated_at`, `reset_token`, `reset_token_expires_at`) VALUES
(1, 'mohtadiromene@gmail.com', 'mohtadi', '24356444', 'mohtadiromene@gmail.com', '[\"ROLE_CLIENT\"]', '$2y$13$5ylQY.0WkUMESH38.piSwe6/Sn/3Sb/Ugiopo/b7GTN97cBJPdOmO', 0, 1, '2026-01-04 13:04:51', NULL, NULL, NULL),
(2, 'mohtadi@gmail.com', 'mohtadi', '12343522', '11', '[\"ROLE_ADMIN\"]', '$2y$13$rqfKuT6WJ4.XKyRFXMF1UOunGGJqFFfu33ZrzTU9HiyZJuwxxwzoK', 0, 1, '2026-01-04 13:04:51', NULL, NULL, NULL),
(3, 'mohtadi.romene@gmail.com', 'mohtadi', '23456345', 'mohtadi.romene@gmail.com', '[\"ROLE_CLIENT\"]', '$2y$13$exioSxkFbtI13IjHlpsCQ..mSbWPxTgai0b2.k9TCpBNAo1KdapSe', 0, 1, '2026-01-04 13:04:51', NULL, NULL, NULL),
(4, 'mohamed@gmail.com', 'mohamed', '25374933', 'hammamet', '[\"ROLE_CLIENT\"]', '$2y$13$AIa3txpvlz12rf140c8mJeNRrswCT.t3jnrXTxyRrGBd6XkH.CwI2', 0, 1, '2026-01-04 13:04:51', NULL, NULL, NULL),
(5, 'admin@kaira.com', 'Administrateur', '0123456789', '123 Rue Admin, Paris', '[\"ROLE_ADMIN\"]', '$2y$13$ldP1sJXinvkNNx5jU6sstO1qdH3SnWABGQb7waV5ko/OWIoKdhD/W', 0, 1, '2026-01-04 13:04:51', '2026-01-05 02:28:37', '30e919eda51185e3306f025a39ba9690a0cc6c1725b723ea6e8d895808d2accc', '2026-01-05 03:28:37'),
(6, 'client1@example.com', 'Jean Dupont', '0612345678', '10 Rue de la Paix, Paris', '[\"ROLE_CLIENT\"]', '$2y$13$BAbDqsDF5eAl1xl5R6rlNeo9RALJ2anRsXbQFCejb5ejPDcO3w/hG', 0, 1, '2026-01-04 13:04:51', NULL, NULL, NULL),
(7, 'client2@example.com', 'Marie Martin', '0623456789', '20 Avenue des Champs, Lyon', '[\"ROLE_CLIENT\"]', '$2y$13$pW/QF3XLOE5wPJ8T5fvSc.UEfyfi8eIl3bxUeb0fkTVvUZg7tdkPu', 0, 1, '2026-01-04 13:04:51', NULL, NULL, NULL),
(8, 'client3@example.com', 'Pierre Bernard', '0634567890', '30 Boulevard Saint-Michel, Marseille', '[\"ROLE_CLIENT\"]', '$2y$13$HLZALczDJGLKILM7VRiWXuZiMe1j4bhChxIG.KTXnbFtOCu90i/ZO', 0, 1, '2026-01-04 13:04:51', NULL, NULL, NULL),
(9, 'imen@gmail.com', 'imen riahi', '24356234', 'beja', '[\"ROLE_CLIENT\"]', '$2y$13$DQj04CGp3y.uFqlqLSgpfuVLEpMpA77qVCcAoWU/I.N35FcAxqGA.', 0, 1, '2026-01-04 13:04:51', NULL, NULL, NULL),
(10, 'saif@gmail.com', 'saif sbaiti', '24356478', 'gabes', '[\"ROLE_CLIENT\"]', '$2y$13$KDeC9FwuvagtiqrMYY/kueugFJvDXcFVi3C7XkXxhxwrc6vIgZAbW', 0, 1, '2026-01-04 13:04:51', NULL, NULL, NULL),
(11, 'romene@gmail.com', 'romene Mohatdi', '24356234', 'Hammamet', '[\"ROLE_CLIENT\"]', '$2y$13$vnJuZceHUG9TOVH9WcR.sOfKDBfPlTHowiRzpyt6KFM76WWyyQvBW', 0, 1, '2026-01-04 12:51:17', NULL, NULL, NULL),
(12, 'imenriahi@gmail.com', 'imen riahi', '26374899', 'bejaaa', '[\"ROLE_CLIENT\"]', '$2y$13$gj1UJaZ.ycR4Hk4B8U21eu5ZmwinATHjmFxhanX2nqJ2iH9kkzgCC', 0, 1, '2026-01-04 14:50:29', NULL, NULL, NULL),
(13, 'mohtadiromene00@gmail.com', 'Mohtadi romene', '28884294', 'Hammamet', '[\"ROLE_CLIENT\"]', '$2y$13$VeRCLE0c8QhMzFtEUClsdO8fgs8Hikm0Wy2VAMAcGUdeIK.PAS9bu', 0, 1, '2026-01-05 02:31:20', '2026-01-05 03:09:08', '262c69f418aded9afe340eb5011f1b23bd16615e3cb74cc0f947f9864af1c379', '2026-01-05 04:09:08'),
(14, 'jasser@gmail.com', 'Jasser', '24356478', 'Ariana', '[\"ROLE_CLIENT\"]', '$2y$13$zPELQTnPXsDmumErL7A0e.AmYrOg/xtV66F8QBsQK5aCfwqmfvf4e', 0, 1, '2026-01-05 11:06:06', NULL, NULL, NULL);

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `avis`
--
ALTER TABLE `avis`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_8F91ABF0A76ED395` (`user_id`),
  ADD KEY `IDX_8F91ABF0F347EFB` (`produit_id`);

--
-- Index pour la table `commande`
--
ALTER TABLE `commande`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `UNIQ_8E4E3C1A7F2DBC08` (`facture_id`),
  ADD KEY `IDX_6EEAA67DA76ED395` (`user_id`);

--
-- Index pour la table `commande_produit`
--
ALTER TABLE `commande_produit`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_DF1E9E8782EA2E54` (`commande_id`),
  ADD KEY `IDX_DF1E9E87F347EFB` (`produit_id`);

--
-- Index pour la table `doctrine_migration_versions`
--
ALTER TABLE `doctrine_migration_versions`
  ADD PRIMARY KEY (`version`);

--
-- Index pour la table `facture`
--
ALTER TABLE `facture`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `UNIQ_FE86641082EA2E54` (`commande_id`);

--
-- Index pour la table `messenger_messages`
--
ALTER TABLE `messenger_messages`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_75EA56E0FB7336F0` (`queue_name`),
  ADD KEY `IDX_75EA56E0E3BD61CE` (`available_at`),
  ADD KEY `IDX_75EA56E016BA31DB` (`delivered_at`);

--
-- Index pour la table `panier_produit`
--
ALTER TABLE `panier_produit`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_D31F28A6F77D927C` (`panier_id`),
  ADD KEY `IDX_D31F28A6F347EFB` (`produit_id`);

--
-- Index pour la table `pannier`
--
ALTER TABLE `pannier`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `UNIQ_AED0E5EBA76ED395` (`user_id`),
  ADD UNIQUE KEY `UNIQ_AED0E5EB82EA2E54` (`commande_id`),
  ADD UNIQUE KEY `UNIQ_8E4E3C1A9D86650F` (`commande_id`);

--
-- Index pour la table `produit`
--
ALTER TABLE `produit`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `promotion`
--
ALTER TABLE `promotion`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `promotion_produit`
--
ALTER TABLE `promotion_produit`
  ADD PRIMARY KEY (`promotion_id`,`produit_id`),
  ADD KEY `IDX_71D81A1D139DF194` (`promotion_id`),
  ADD KEY `IDX_71D81A1DF347EFB` (`produit_id`);

--
-- Index pour la table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `UNIQ_IDENTIFIER_EMAIL` (`email`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `avis`
--
ALTER TABLE `avis`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `commande`
--
ALTER TABLE `commande`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT pour la table `commande_produit`
--
ALTER TABLE `commande_produit`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT pour la table `facture`
--
ALTER TABLE `facture`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT pour la table `messenger_messages`
--
ALTER TABLE `messenger_messages`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT pour la table `panier_produit`
--
ALTER TABLE `panier_produit`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT pour la table `pannier`
--
ALTER TABLE `pannier`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT pour la table `produit`
--
ALTER TABLE `produit`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT pour la table `promotion`
--
ALTER TABLE `promotion`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT pour la table `user`
--
ALTER TABLE `user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `avis`
--
ALTER TABLE `avis`
  ADD CONSTRAINT `FK_8F91ABF0A76ED395` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`),
  ADD CONSTRAINT `FK_8F91ABF0F347EFB` FOREIGN KEY (`produit_id`) REFERENCES `produit` (`id`);

--
-- Contraintes pour la table `commande`
--
ALTER TABLE `commande`
  ADD CONSTRAINT `FK_6EEAA67DA76ED395` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`),
  ADD CONSTRAINT `FK_8E4E3C1A7F2DBC08` FOREIGN KEY (`facture_id`) REFERENCES `facture` (`id`);

--
-- Contraintes pour la table `commande_produit`
--
ALTER TABLE `commande_produit`
  ADD CONSTRAINT `FK_DF1E9E8782EA2E54` FOREIGN KEY (`commande_id`) REFERENCES `commande` (`id`),
  ADD CONSTRAINT `FK_DF1E9E87F347EFB` FOREIGN KEY (`produit_id`) REFERENCES `produit` (`id`);

--
-- Contraintes pour la table `facture`
--
ALTER TABLE `facture`
  ADD CONSTRAINT `FK_FE86641082EA2E54` FOREIGN KEY (`commande_id`) REFERENCES `commande` (`id`);

--
-- Contraintes pour la table `panier_produit`
--
ALTER TABLE `panier_produit`
  ADD CONSTRAINT `FK_D31F28A6F347EFB` FOREIGN KEY (`produit_id`) REFERENCES `produit` (`id`),
  ADD CONSTRAINT `FK_D31F28A6F77D927C` FOREIGN KEY (`panier_id`) REFERENCES `pannier` (`id`);

--
-- Contraintes pour la table `pannier`
--
ALTER TABLE `pannier`
  ADD CONSTRAINT `FK_8E4E3C1A9D86650F` FOREIGN KEY (`commande_id`) REFERENCES `commande` (`id`),
  ADD CONSTRAINT `FK_AED0E5EB82EA2E54` FOREIGN KEY (`commande_id`) REFERENCES `commande` (`id`),
  ADD CONSTRAINT `FK_AED0E5EBA76ED395` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`);

--
-- Contraintes pour la table `promotion_produit`
--
ALTER TABLE `promotion_produit`
  ADD CONSTRAINT `FK_71D81A1D139DF194` FOREIGN KEY (`promotion_id`) REFERENCES `promotion` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `FK_71D81A1DF347EFB` FOREIGN KEY (`produit_id`) REFERENCES `produit` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
