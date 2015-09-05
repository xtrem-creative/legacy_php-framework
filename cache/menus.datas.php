<?php
$menus = array();
$menus['top'][0] = array( 'label' => 'Accueil', 'link' => '/modules/accueil/index.php', 'auths' => 'all', 'type' => '0' );
$menus['top'][1] = array( 'label' => 'Administration', 'link' => '/modules/admin/index.php', 'auths' => 'admin', 'type' => '0' );
$menus['top'][2] = array( 'label' => 'Créateur de page', 'link' => '/modules/xtc_builder/index.php', 'auths' => 'admin', 'type' => '0' );
$menus['left'][3] = array( 'label' => 'Mises-à-jour', 'link' => '/modules/admin/maj.php', 'auths' => 'admin', 'type' => '2' );
$menus['top'][4] = array( 'label' => 'Connexion', 'link' => '/modules/membres/connexion.php', 'auths' => 'guest', 'type' => '1' );
$menus['top'][5] = array( 'label' => 'Déconnexion', 'link' => '/modules/membres/deconnexion.php', 'auths' => 'member', 'type' => '1' );
$menus['left'][6] = array( 'label' => 'Paquets', 'link' => '/modules/admin/paquets.php', 'auths' => 'admin', 'type' => '2' );
$menus['left'][7] = array( 'label' => 'News', 'link' => '/modules/admin/news.php', 'auths' => 'admin', 'type' => '2' );
$menus['left'][8] = array( 'label' => 'Inscription', 'link' => '/modules/membres/inscription.php', 'auths' => 'guest', 'type' => '1' );
$menus['left'][9] = array( 'label' => 'Config', 'link' => '/modules/admin/config.php', 'auths' => 'admin', 'type' => '2' );
$menus['left'][10] = array( 'label' => 'Liste des membres', 'link' => '/modules/membres/liste.php', 'auths' => 'all', 'type' => '1' );
$menus['left'][11] = array( 'label' => 'Fichiers de templates', 'link' => '/modules/admin/templates.php', 'auths' => 'admin', 'type' => '2' );
$menus['left'][12] = array( 'label' => 'Menus', 'link' => '/modules/admin/menus.php', 'auths' => 'admin', 'type' => '2' );
$menus['left'][13] = array( 'label' => 'Infos sur le CMS', 'link' => '/modules/admin/infos_cms.php', 'auths' => 'admin', 'type' => '2' );
$menus['left'][14] = array( 'label' => 'Configuration du serveur', 'link' => '/modules/admin/serveur.php', 'auths' => 'admin', 'type' => '2' );
$menus['left'][15] = array( 'label' => 'Tutoriels', 'link' => '/modules/cours/index.php', 'auths' => 'all', 'type' => '1' );
$menus['left'][16] = array( 'label' => 'Gestion des tutoriels', 'link' => '/modules/cours/gerer.php', 'auths' => 'member', 'type' => '1' );
$menus['left'][17] = array( 'label' => 'Contact', 'link' => '/modules/accueil/contact.php', 'auths' => 'all', 'type' => '1' );
$menus['left'][18] = array( 'label' => 'Gestion du cache', 'link' => '/modules/admin/cache.php', 'auths' => 'admin', 'type' => '2' );
$menus['left'][19] = array( 'label' => 'Forum', 'link' => '#', 'auths' => 'all', 'type' => '1' );
return $menus;
?>