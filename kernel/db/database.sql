-- phpMyAdmin SQL Dump
-- version 3.3.7deb5build0.10.10.1
-- http://www.phpmyadmin.net
--
-- Serveur: localhost
-- Généré le : Mer 27 Avril 2011 à 19:02
-- Version du serveur: 5.1.49
-- Version de PHP: 5.3.3-1ubuntu9.3

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";

--
-- Base de données: `xtrem-cms`
--

-- --------------------------------------------------------

--
-- Structure de la table `xtremcms_autorisations`
--

DROP TABLE IF EXISTS `xtremcms_autorisations`;
CREATE TABLE IF NOT EXISTS `xtremcms_autorisations` (
  `autorisation_id` int(11) NOT NULL AUTO_INCREMENT,
  `autorisation_membre` int(11) NOT NULL,
  `autorisation_module` varchar(50) CHARACTER SET latin1 NOT NULL,
  `autorisation_page` varchar(50) CHARACTER SET latin1 NOT NULL,
  `autorisation_valeur` int(11) NOT NULL,
  PRIMARY KEY (`autorisation_id`),
  KEY `autorisation_membre` (`autorisation_membre`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=2 ;

-- --------------------------------------------------------

--
-- Structure de la table `xtremcms_config`
--

DROP TABLE IF EXISTS `xtremcms_config`;
CREATE TABLE IF NOT EXISTS `xtremcms_config` (
  `config_name` varchar(100) COLLATE utf8_bin NOT NULL,
  `config_lang` varchar(10) COLLATE utf8_bin DEFAULT 'fr',
  `config_value` text COLLATE utf8_bin,
  PRIMARY KEY (`config_name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Contenu de la table `xtremcms_config`
--

INSERT INTO `xtremcms_config` (`config_name`, `config_lang`, `config_value`) VALUES
('footer', 'fr', 'a:1:{s:9:"copyright";s:19:"<a href="#">XTC</a>";}'),
('header', 'fr', 'a:2:{s:5:"title";s:20:"Xtrem Creative - CMS";s:11:"description";s:19:"Description du site";}   ');

-- --------------------------------------------------------

--
-- Structure de la table `xtremcms_cours`
--

DROP TABLE IF EXISTS `xtremcms_cours`;
CREATE TABLE IF NOT EXISTS `xtremcms_cours` (
  `cours_id` mediumint(8) NOT NULL AUTO_INCREMENT,
  `cours_level` mediumint(8) NOT NULL DEFAULT '0',
  `cours_gauche` mediumint(8) NOT NULL,
  `cours_droite` mediumint(8) NOT NULL,
  `cours_nom` varchar(30) COLLATE utf8_bin NOT NULL,
  `cours_auteur` int(11) NOT NULL DEFAULT '0',
  `cours_texte` text COLLATE utf8_bin NOT NULL,
  `cours_type` tinyint(4) NOT NULL DEFAULT '0' COMMENT '0 = cat, 1 = tut, 2 = partie (texte)',
  PRIMARY KEY (`cours_id`),
  KEY `cours_gauche` (`cours_gauche`),
  KEY `cours_droite` (`cours_droite`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=23 ;

-- --------------------------------------------------------

--
-- Structure de la table `xtremcms_droits`
--

DROP TABLE IF EXISTS `xtremcms_droits`;
CREATE TABLE IF NOT EXISTS `xtremcms_droits` (
  `droit_id` smallint(6) NOT NULL AUTO_INCREMENT,
  `droit_nom` varchar(100) CHARACTER SET latin1 NOT NULL,
  `droit_valeur` int(11) NOT NULL,
  `droit_defaut` int(11) NOT NULL,
  PRIMARY KEY (`droit_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=5 ;

--
-- Contenu de la table `xtremcms_droits`
--

INSERT INTO `xtremcms_droits` (`droit_id`, `droit_nom`, `droit_valeur`, `droit_defaut`) VALUES
(1, 'read', 1, 1),
(2, 'write', 10, 10),
(4, 'delete', 100, 0);

-- --------------------------------------------------------

--
-- Structure de la table `xtremcms_groups`
--

DROP TABLE IF EXISTS `xtremcms_groups`;
CREATE TABLE IF NOT EXISTS `xtremcms_groups` (
  `groupe_id` smallint(6) NOT NULL AUTO_INCREMENT,
  `groupe_nom` varchar(100) CHARACTER SET latin1 NOT NULL,
  `groupe_image` varchar(100) CHARACTER SET latin1 NOT NULL,
  `groupe_nbMax` tinyint(4) NOT NULL DEFAULT '0',
  `groupe_droits` int(11) NOT NULL,
  `groupe_chefs` varchar(100) CHARACTER SET latin1 NOT NULL,
  PRIMARY KEY (`groupe_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Structure de la table `xtremcms_membres`
--

DROP TABLE IF EXISTS `xtremcms_membres`;
CREATE TABLE IF NOT EXISTS `xtremcms_membres` (
  `membre_id` int(11) NOT NULL AUTO_INCREMENT,
  `membre_login` varchar(200) COLLATE utf8_bin DEFAULT NULL,
  `membre_email` varchar(200) COLLATE utf8_bin DEFAULT NULL,
  `membre_password` varchar(200) COLLATE utf8_bin DEFAULT NULL,
  `membre_register` int(11) DEFAULT NULL,
  `membre_last_up` int(11) DEFAULT NULL,
  `membre_citation` varchar(200) COLLATE utf8_bin DEFAULT NULL,
  `membre_biographie` text COLLATE utf8_bin,
  `membre_rank` smallint(6) DEFAULT NULL,
  `membre_ip` varchar(16) COLLATE utf8_bin DEFAULT NULL,
  `membre_lang` varchar(50) COLLATE utf8_bin DEFAULT NULL,
  `membre_design` varchar(50) COLLATE utf8_bin DEFAULT NULL,
  PRIMARY KEY (`membre_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=3 ;

--
-- Contenu de la table `xtremcms_membres`
--

INSERT INTO `xtremcms_membres` (`membre_id`, `membre_login`, `membre_email`, `membre_password`, `membre_register`, `membre_last_up`, `membre_citation`, `membre_biographie`, `membre_rank`, `membre_ip`, `membre_lang`, `membre_design`) VALUES
(0, 'Anonyme', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'fr', NULL); 

-- --------------------------------------------------------

--
-- Structure de la table `xtremcms_menus`
--

DROP TABLE IF EXISTS `xtremcms_menus`;
CREATE TABLE IF NOT EXISTS `xtremcms_menus` (
  `menu_id` smallint(6) NOT NULL AUTO_INCREMENT,
  `menu_title` varchar(50) COLLATE utf8_bin DEFAULT NULL,
  `menu_link` varchar(100) COLLATE utf8_bin DEFAULT NULL,
  `menu_order` smallint(6) DEFAULT NULL,
  `menu_type` tinyint(4) DEFAULT NULL,
  `menu_position` varchar(20) COLLATE utf8_bin DEFAULT NULL,
  `menu_html` tinyint(1) DEFAULT NULL,
  `menu_authorizations` varchar(100) COLLATE utf8_bin DEFAULT NULL,
  PRIMARY KEY (`menu_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=20 ;

--
-- Contenu de la table `xtremcms_menus`
--

INSERT INTO `xtremcms_menus` (`menu_id`, `menu_title`, `menu_link`, `menu_order`, `menu_type`, `menu_position`, `menu_html`, `menu_authorizations`) VALUES
(1, 'Accueil', '/modules/accueil/index.php', 100, 0, 'top', 0, 'all'),
(2, 'Forum', '#', 700, 1, 'left', 0, 'all'),
(3, 'Liste des membres', '/modules/membres/liste.php', 400, 1, 'left', 0, 'all'),
(4, 'Tutoriels', '/modules/cours/index.php', 500, 1, 'left', 0, 'all'),
(5, 'Contact', '#', 600, 1, 'left', 0, 'all'),
(6, 'Connexion', '/modules/membres/connexion.php', 200, 1, 'top', 0, 'guest'),
(7, 'Déconnexion', '/modules/membres/deconnexion.php', 200, 1, 'top', 0, 'member'),
(8, 'Inscription', '/modules/membres/inscription.php', 300, 1, 'left', 0, 'guest'),
(9, 'Administration', '/modules/admin/index.php', 110, 0, 'top', 0, 'admin'),
(10, 'Mises-à-jour', '/modules/admin/maj.php', 200, 2, 'left', 0, 'admin'),
(11, 'News', '/modules/admin/news.php', 300, 2, 'left', 0, 'admin'),
(12, 'Configuration du serveur', '/modules/admin/serveur.php', 500, 2, 'left', 0, 'admin'),
(13, 'Gestion du cache', '/modules/admin/cache.php', 600, 2, 'left', 0, 'admin'),
(14, 'Fichiers de templates', '/modules/admin/templates.php', 400, 2, 'left', 0, 'admin'),
(15, 'Menus', '/modules/admin/menus.php', 450, 2, 'left', 0, 'admin'),
(16, 'Infos sur le CMS', '/modules/admin/infos_cms.php', 490, 2, 'left', 0, 'admin'),
(17, 'Paquets', '/modules/admin/paquets.php', 250, 2, 'left', 0, 'admin'),
(18, 'Créateur de page', '/modules/xtc_builder/index.php', 150, 0, 'top', 0, 'admin'),
(19, 'Gestion des tutoriels', '/modules/cours/gerer.php', 550, 1, 'left', 0, 'member');

-- --------------------------------------------------------

--
-- Structure de la table `xtremcms_news`
--

DROP TABLE IF EXISTS `xtremcms_news`;
CREATE TABLE IF NOT EXISTS `xtremcms_news` (
  `news_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `news_titre` varchar(255) CHARACTER SET latin1 NOT NULL,
  `news_auteur` int(10) unsigned NOT NULL,
  `news_contenu` text CHARACTER SET latin1 NOT NULL,
  `news_creation` datetime NOT NULL,
  `news_modification` datetime NOT NULL,
  PRIMARY KEY (`news_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=2 ;

--
-- Contenu de la table `xtremcms_news`
--

INSERT INTO `xtremcms_news` (`news_id`, `news_titre`, `news_auteur`, `news_contenu`, `news_creation`, `news_modification`) VALUES
(1, 'Titre', 2, '<paragraphe>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Suspendisse porttitor dictum tellus, eu egestas nisi molestie vel. Quisque arcu elit, semper sit amet dictum quis, lobortis id magna. Nam varius lobortis congue. Nullam aliquet enim sit amet eros faucibus id volutpat sapien mollis. Sed fringilla accumsan porta. Proin tortor nisi, auctor id vehicula ac, dignissim nec ipsum. Sed semper neque vitae elit tempor condimentum. Suspendisse nulla purus, convallis tincidunt viverra at, eleifend quis nisi. Vivamus porta, metus vitae aliquet tincidunt, turpis purus semper ligula, eget dictum nisl nibh sed arcu. Nam eu diam quam. Pellentesque habitant morbi tristique senectus et netus et malesuada fames ac turpis egestas. In tellus tellus, vehicula non pharetra vel, sagittis nec lectus. Curabitur malesuada, nibh eu faucibus sodales, erat nunc semper risus, elementum posuere neque lectus vitae ante.</paragraphe>\r\n\r\n<paragraphe>Nunc dapibus consectetur rhoncus. Nam lorem dui, faucibus at tempor id, mattis eget urna. Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Ut vel dolor vitae felis fringilla facilisis sed eu mauris. Nulla facilisi. Aenean sed neque et nisl malesuada luctus. Vestibulum fringilla rhoncus tincidunt. Proin adipiscing vestibulum justo sed faucibus. Proin non lorem sit amet urna egestas venenatis at vestibulum dui. Nulla dapibus felis iaculis nulla rhoncus nec luctus dui facilisis. Pellentesque habitant morbi tristique senectus et netus et malesuada fames ac turpis egestas. Etiam rutrum venenatis leo sed adipiscing. In hac habitasse platea dictumst. Suspendisse potenti. In viverra risus vitae purus ultrices pulvinar. Aliquam varius feugiat faucibus. Nam erat magna, tempor ac dictum dignissim, malesuada vel arcu. Aenean eu condimentum lectus.</paragraphe>\r\n\r\n<paragraphe>Maecenas feugiat congue turpis. Suspendisse eros lectus, sodales nec ornare in, ornare in elit. Suspendisse quis tempor augue. Aliquam sit amet leo at urna lacinia dictum sit amet a massa. Phasellus convallis scelerisque risus quis mollis. Aliquam pellentesque erat augue, quis condimentum est. Nullam sed lacus tortor. Integer accumsan sollicitudin turpis tristique vulputate. Nulla posuere, nisl ut consequat venenatis, neque lectus porta tortor, facilisis varius enim nibh nec eros. Vestibulum malesuada malesuada urna ut malesuada. Nulla tincidunt urna ligula, non pretium turpis. Nam tempor facilisis lectus, ac suscipit dolor iaculis sed. </paragraphe>', '0000-00-00 00:00:00', '0000-00-00 00:00:00');

-- --------------------------------------------------------

--
-- Structure de la table `xtremcms_pages`
--

DROP TABLE IF EXISTS `xtremcms_pages`;
CREATE TABLE IF NOT EXISTS `xtremcms_pages` (
  `page_md5` varchar(32) CHARACTER SET latin1 NOT NULL,
  `page_nom` varchar(200) CHARACTER SET latin1 NOT NULL,
  PRIMARY KEY (`page_md5`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Structure de la table `xtremcms_sessions`
--

DROP TABLE IF EXISTS `xtremcms_sessions`;
CREATE TABLE IF NOT EXISTS `xtremcms_sessions` (
  `session_id` text CHARACTER SET latin1 NOT NULL,
  `session_membre` int(11) NOT NULL,
  `session_date` int(11) NOT NULL,
  `session_ip` varchar(50) CHARACTER SET latin1 NOT NULL,
  `session_infos` text CHARACTER SET latin1 NOT NULL,
  KEY `session_membre` (`session_membre`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Contraintes pour la table `xtremcms_autorisations`
--
ALTER TABLE `xtremcms_autorisations`
  ADD CONSTRAINT `xtremcms_autorisations_ibfk_1` FOREIGN KEY (`autorisation_membre`) REFERENCES `xtremcms_membres` (`membre_id`) ON DELETE CASCADE ON UPDATE CASCADE;
