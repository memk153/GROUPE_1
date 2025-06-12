-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1:3306
-- Généré le : jeu. 12 juin 2025 à 03:35
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
-- Base de données : `freelance`
--

-- --------------------------------------------------------

--
-- Structure de la table `avis`
--

DROP TABLE IF EXISTS `avis`;
CREATE TABLE IF NOT EXISTS `avis` (
  `id` int NOT NULL AUTO_INCREMENT,
  `utilisateur_id` int DEFAULT NULL,
  `note` int DEFAULT NULL,
  `commentaire` text,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=26 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `avis`
--

INSERT INTO `avis` (`id`, `utilisateur_id`, `note`, `commentaire`) VALUES
(6, 2, 5, 'Roger a fait un excellent travail sur notre site. Très professionnel et réactif.'),
(2, 4, 4, 'Jean-Paul a créé une identité visuelle parfaite pour notre startup. Petit retard sur la livraison.'),
(3, 6, 5, 'Samuel a traduit nos documents avec une grande précision. Nous recommandons!'),
(5, 3, 5, 'Aïcha est très professionnelle et sait exactement ce qu\'elle veut. Collaboration très fluide.'),
(7, 4, 4, 'Jean-Paul a un grand sens du design mais quelques retards dans les livraisons.'),
(8, 6, 5, 'Samuel est un traducteur hors pair. Qualité irréprochable.'),
(9, 1, 3, 'Martine est sérieuse mais parfois difficile à joindre pour des modifications.'),
(10, 3, 5, 'Aïcha est très organisée et sait exactement ce qu\'elle veut. Un plaisir de travailler avec elle.'),
(11, 8, 4, 'Bertrand a développé une superbe application mobile pour notre entreprise.'),
(12, 9, 5, 'Stéphanie a considérablement augmenté notre engagement sur les réseaux sociaux.'),
(13, 10, 4, 'Armand a mis en place une stratégie marketing très efficace pour notre PME.'),
(14, 11, 5, 'Les photos de Patricia ont grandement amélioré notre présence en ligne.'),
(15, 12, 3, 'Gaston a fait du bon travail mais avec quelques retards dans les livraisons.'),
(16, 13, 5, 'Sylvie rédige des articles de qualité qui génèrent beaucoup de trafic.'),
(17, 14, 5, 'Olivier a sécurisé notre système comme personne n\'avait su le faire avant.'),
(18, 15, 4, 'Carine a transformé notre espace de travail avec son design innovant.'),
(19, 16, 5, 'Les conseils de Jacques ont sauvé notre entreprise en période difficile.'),
(20, 17, 5, 'Estelle a traduit nos contrats avec une précision juridique impeccable.'),
(21, 5, 4, 'Samuel est très professionnel mais un peu cher pour nos moyens.'),
(22, 7, 5, 'Alain a livré le projet avant la date prévue avec une qualité exceptionnelle.'),
(23, 9, 4, 'Stéphanie est créative et toujours à l\'écoute de nos besoins.'),
(24, 12, 5, 'Gaston a finalement livré un travail de très haute qualité.'),
(25, 14, 5, 'Olivier est un expert en cybersécurité, je recommande vivement!');

-- --------------------------------------------------------

--
-- Structure de la table `offre`
--

DROP TABLE IF EXISTS `offre`;
CREATE TABLE IF NOT EXISTS `offre` (
  `id` int NOT NULL AUTO_INCREMENT,
  `utilisateur_id` int DEFAULT NULL,
  `titre` varchar(255) NOT NULL,
  `description` text,
  `budget` decimal(10,2) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=22 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `offre`
--

INSERT INTO `offre` (`id`, `utilisateur_id`, `titre`, `description`, `budget`) VALUES
(1, 2, 'Site e-commerce pour vêtements', 'Besoin d\'un site e-commerce pour vendre des vêtements traditionnels camerounais avec paiement mobile money', 750000.00),
(2, 4, 'Application mobile de livraison', 'Développement d\'une app de livraison rapide à Yaoundé avec suivi en temps réel', 2500000.00),
(3, 6, 'Site vitrine pour ONG', 'Création d\'un site simple pour présenter nos activités éducatives en zone rurale', 300000.00),
(4, 7, 'Système de gestion de stock', 'Automatisation de la gestion de stock pour un petit commerce à Bafoussam', 500000.00),
(5, 18, 'Site web pour restaurant', 'Site avec menu en ligne et système de réservation pour notre restaurant à Bonapriso', 500000.00),
(6, 19, 'Boutique en ligne de mode', 'Site e-commerce pour vendre nos créations de vêtements africains modernes', 800000.00),
(7, 20, 'Site vitrine pour artiste', 'Portfolio en ligne avec galerie et système de vente de musique', 350000.00),
(8, 21, 'Gestion de propriétés', 'Système pour gérer nos locations et ventes immobilières à Douala', 1200000.00),
(9, 22, 'Numérisation administrative', 'Solution pour gérer les inscriptions, paiements et bulletins de notre école', 600000.00),
(10, 23, 'Application de rendez-vous', 'App pour que nos clients puissent prendre rendez-vous en ligne', 450000.00),
(11, 24, 'Site web pour association sportive', 'Site avec calendrier, résultats et inscriptions aux compétitions', 300000.00),
(12, 25, 'Plateforme e-commerce pour librairie', 'Site pour vendre nos livres avec système de livraison à Yaoundé', 700000.00),
(13, 26, 'Système de réservation hôtel', 'Solution complète de gestion des chambres et réservations en ligne', 1500000.00),
(14, 27, 'Application de suivi de colis', 'App pour que nos clients puissent suivre leurs colis en temps réel', 900000.00),
(15, 28, 'Site vitrine pour produits locaux', 'Présentation de nos produits agroalimentaires made in Cameroon', 400000.00),
(16, 29, 'Migration vers le digital', 'Refonte complète de notre journal papier vers une plateforme en ligne', 850000.00),
(17, 30, 'Prise de rendez-vous en ligne', 'Système intégré à notre site existant pour la clinique', 550000.00),
(18, 31, 'Automatisation des processus', 'Solution pour gérer les réservations, paiements et clients', 1100000.00),
(19, 32, 'Digitalisation des inscriptions', 'Plateforme pour les inscriptions en ligne à notre auto-école', 500000.00),
(20, 1, 'Developpement web', 'A la recherche de quelqu\'un qui est assez balaise dans le JS et PHP, avec des notions plus que moyennes de HTML/CSS', 30000.00),
(21, 37, 'Actions boursieres', 'A la recherche d\'actionnaire qui souhaitent investir dans une mini startup de fabrication de produits menager', 50000.00);

-- --------------------------------------------------------

--
-- Structure de la table `prestataire`
--

DROP TABLE IF EXISTS `prestataire`;
CREATE TABLE IF NOT EXISTS `prestataire` (
  `id` int NOT NULL AUTO_INCREMENT,
  `utilisateur_id` int NOT NULL,
  `photo` varchar(255) DEFAULT NULL,
  `bio` text,
  `competences` text,
  `experiences` text,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=15 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `prestataire`
--

INSERT INTO `prestataire` (`id`, `utilisateur_id`, `photo`, `bio`, `competences`, `experiences`) VALUES
(1, 1, 'roger.jpg', 'Développeur web fullstack avec 5 ans d\'expérience', 'HTML/CSSVBAPythonPHP', '1 an d\'expérience avec M. Fomekong à KEYCE INFORMATIQUE'),
(2, 3, 'jeanpaul.jpg', 'Designer graphique spécialisé en UI/UX', 'Photoshop, Illustrator, Figma, UX research', '4 ans d\'expérience dans diverses agences à Douala'),
(3, 5, 'samuel.jpg', 'Rédacteur technique et traducteur professionnel', 'Rédaction SEO, Anglais-Français, Documentation technique', 'Traduction pour des clients internationaux depuis 2016'),
(4, 8, 'bertrand.jpg', 'Développeur mobile spécialisé Android/iOS', 'Kotlin, Swift, Flutter', '3 ans chez Orange Cameroun'),
(5, 9, 'stephanie.jpg', 'Community Manager bilingue', 'Réseaux sociaux, Stratégie digitale', 'Gestion de comptes pour plusieurs marques locales'),
(6, 10, 'armand.jpg', 'Expert en marketing digital', 'SEO, Google Ads, Analytics', '5 ans d\'expérience en agence'),
(7, 11, 'patricia.jpg', 'Photographe professionnelle', 'Photo de produit, Portrait, Événementiel', 'Photographe pour plusieurs magazines camerounais'),
(8, 12, 'gaston.jpg', 'Monteur vidéo professionnel', 'Premiere Pro, After Effects', 'Travail sur des clips musicaux et publicités'),
(9, 13, 'sylvie.jpg', 'Rédactrice web SEO', 'Rédaction SEO, Blogging, Copywriting', 'Rédaction pour plusieurs startups africaines'),
(10, 14, 'olivier.jpg', 'Expert en cybersécurité', 'Pentesting, Sécurité réseau', 'Consultant pour des banques locales'),
(11, 15, 'carine.jpg', 'Designer d\'intérieur', 'Architecture d\'intérieur, 3D', 'Projets résidentiels et commerciaux à Douala'),
(12, 16, 'jacques.jpg', 'Consultant en gestion', 'Stratégie d\'entreprise, Finance', '15 ans d\'expérience dans divers secteurs'),
(13, 17, 'estelle.jpg', 'Traductrice juridique', 'Anglais-Français, Droit', 'Traduction de contrats pour des entreprises'),
(14, 33, 'uploads/prestataire_33.jpg', 'J\'ai 18ans et je suis étudiante en Bachelor 1 à KEYCE INFORMATIQUE ET IA', 'HTML/CSSVBAPythonPHP', '6 mois de stage a la BEAC');

-- --------------------------------------------------------

--
-- Structure de la table `proposant`
--

DROP TABLE IF EXISTS `proposant`;
CREATE TABLE IF NOT EXISTS `proposant` (
  `id` int NOT NULL AUTO_INCREMENT,
  `utilisateur_id` int DEFAULT NULL,
  `description` text,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=20 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `proposant`
--

INSERT INTO `proposant` (`id`, `utilisateur_id`, `description`) VALUES
(1, 2, 'Entrepreneure cherchant à développer son site e-commerce'),
(2, 4, 'Startup tech recherchant des talents pour une application mobile'),
(3, 6, 'ONG ayant besoin d\'un site web pour sensibiliser sur l\'éducation'),
(4, 7, 'Petite entreprise cherchant à automatiser ses processus'),
(5, 18, 'Restaurant cherchant à créer son site web avec menu en ligne'),
(6, 19, 'Boutique de mode cherchant à améliorer sa présence digitale'),
(7, 20, 'Artiste musicien voulant un site pour promouvoir son travail'),
(8, 21, 'Agence immobilière cherchant un système de gestion de propriétés'),
(9, 22, 'École primaire voulant numériser ses processus administratifs'),
(10, 23, 'Coiffeur cherchant à créer une application de rendez-vous'),
(11, 24, 'Association sportive ayant besoin d\'un site pour ses activités'),
(12, 25, 'Librairie cherchant à développer une plateforme e-commerce'),
(13, 26, 'Hôtel voulant un système de réservation en ligne'),
(14, 27, 'Entreprise de transport cherchant une app de suivi de colis'),
(15, 28, 'Fabricant de produits locaux voulant un site vitrine'),
(16, 29, 'Journal indépendant cherchant à migrer vers le digital'),
(17, 30, 'Clinique privée voulant un système de prise de rendez-vous'),
(18, 31, 'Agence de voyage cherchant à automatiser ses processus'),
(19, 32, 'Auto-école cherchant à digitaliser ses inscriptions');

-- --------------------------------------------------------

--
-- Structure de la table `utilisateurs`
--

DROP TABLE IF EXISTS `utilisateurs`;
CREATE TABLE IF NOT EXISTS `utilisateurs` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nom` varchar(100) NOT NULL,
  `prenom` varchar(100) NOT NULL,
  `email` varchar(191) NOT NULL,
  `tel` varchar(20) DEFAULT NULL,
  `mot_de_passe` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`)
) ENGINE=MyISAM AUTO_INCREMENT=38 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `utilisateurs`
--

INSERT INTO `utilisateurs` (`id`, `nom`, `prenom`, `email`, `tel`, `mot_de_passe`) VALUES
(8, 'Ndongmo', 'Bertrand', 'bertrand.ndongmo@example.com', '677890123', '$2y$10$XYZ123'),
(7, 'Kamdem', 'Alain', 'alain.kamdem@example.com', '655789012', '$2y$10$STU901'),
(5, 'Ndjock', 'Samuel', 'samuel.ndjock@example.com', '677567890', '$2y$10$MNO345'),
(6, 'Fotso', 'Grace', 'grace.fotso@example.com', '690678901', '$2y$10$PQR678'),
(1, 'Tchatchouang', 'Roger', 'roger.tchatchouang@example.com', '677123456', '$2y$10$ABC123'),
(2, 'Ngo', 'Martine', 'martine.ngo@example.com', '690234567', '$2y$10$DEF456'),
(3, 'Mballa', 'Jean-Paul', 'jp.mballa@example.com', '655345678', '$2y$10$GHI789'),
(4, 'Tchakounte', 'Aïcha', 'aicha.tchakounte@example.com', '699456789', '$2y$10$JKL012'),
(9, 'Tchoumi', 'Stéphanie', 'stephanie.tchoumi@example.com', '690901234', '$2y$10$UVW456'),
(10, 'Kengne', 'Armand', 'armand.kengne@example.com', '655012345', '$2y$10$RST789'),
(11, 'Nkoulou', 'Patricia', 'patricia.nkoulou@example.com', '699123456', '$2y$10$OPQ012'),
(12, 'Fokou', 'Gaston', 'gaston.fokou@example.com', '677234567', '$2y$10$LMN345'),
(13, 'Tchamba', 'Sylvie', 'sylvie.tchamba@example.com', '690345678', '$2y$10$IJK678'),
(14, 'Nguimfack', 'Olivier', 'olivier.nguimfack@example.com', '655456789', '$2y$10$EFG901'),
(15, 'Mefire', 'Carine', 'carine.mefire@example.com', '699567890', '$2y$10$HIJ234'),
(16, 'Ndam', 'Jacques', 'jacques.ndam@example.com', '677678901', '$2y$10$KLM567'),
(17, 'Tchakounte', 'Estelle', 'estelle.tchakounte@example.com', '690789012', '$2y$10$NOP890'),
(18, 'Ngo', 'Paul', 'paul.ngo@example.com', '677901234', '$2y$10$QRS123'),
(19, 'Tchatchoua', 'Annette', 'annette.tchatchoua@example.com', '690012345', '$2y$10$TUV456'),
(20, 'Kouam', 'Serge', 'serge.kouam@example.com', '655123456', '$2y$10$WXY789'),
(21, 'Ndjomo', 'Christelle', 'christelle.ndjomo@example.com', '699234567', '$2y$10$ZAB012'),
(22, 'Fotso', 'Alain', 'alain.fotso@example.com', '677345678', '$2y$10$CDE345'),
(23, 'Nkodo', 'Jeanne', 'jeanne.nkodo@example.com', '690456789', '$2y$10$FGH678'),
(24, 'Tchoualack', 'Gérard', 'gerard.tchoualack@example.com', '655567890', '$2y$10$IJK901'),
(25, 'Mefou', 'Sandrine', 'sandrine.mefou@example.com', '699678901', '$2y$10$LMN234'),
(26, 'Nkoulou', 'Joseph', 'joseph.nkoulou@example.com', '677789012', '$2y$10$OPQ567'),
(27, 'Tagne', 'Aurélie', 'aurelie.tagne@example.com', '690890123', '$2y$10$RST890'),
(28, 'Ndjock', 'Pierre', 'pierre.ndjock@example.com', '655901234', '$2y$10$UVW123'),
(29, 'Kengne', 'Marie', 'marie.kengne@example.com', '699012345', '$2y$10$WXY456'),
(30, 'Fokou', 'David', 'david.fokou@example.com', '677123456', '$2y$10$ZAB789'),
(31, 'Tchamba', 'Nathalie', 'nathalie.tchamba@example.com', '690234567', '$2y$10$CDE012'),
(32, 'Nguimfack', 'Christian', 'christian.nguimfack@example.com', '655345678', '$2y$10$FGH345'),
(33, 'Noubi', 'Maeva', 'maevanoubi21@gmail.com', '675570010', '$2y$10$P6Pjq18q3BH8xQsAxvN8WeLxuXdV6qhgDwKM0kdUpgfp/3dye5Q3e'),
(34, 'Noura', 'Edima', 'nouraedima@gmail.com', '672888888', '$2y$10$uwnh4qz1/YQigt31.4YMsO0.9d3IbRb4/rGUmUpO4D.fYVPBFHdLi'),
(37, 'Ngaleu', 'Brel', 'brel@gmail.com', '643321155', '$2y$10$eVGotiBBso4gfXynnfb7zeO7rqcHwgrdThbqwo1IWI0e5KlcQioBO');
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
