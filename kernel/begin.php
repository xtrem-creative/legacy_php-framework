<?php
session_start();

header( 'Content-type:text/html; charset=utf-8' );
header( 'Cache-Control: no-cache, must-revalidate' );

require_once( 'framework/functions/basic.inc.php' );
initialize_paths();
require_once( 'framework/inc/constants.inc.php' );

load( 'config/config' );
load( 'functions/useful', INC_LOAD );
load( 'inc/default', INC_LOAD );
load( 'core/cache' );
load( 'core/errors' );
load( 'core/breadcrumb' );
load( 'core/sessions' );
load( 'builder/form' );
load( 'templates/constructor' );
load( 'lang/lang' );
load( 'db/mysql' );
load( 'members/member' );

@require_once( ROOT . 'install/install_delete_dir.php' );

$cache = new Cache( false, false );
$bdd = new Bdd();
$sessions = new Sessions();
$member = new Member();
$config = new Config( $cache, $member->getDesign(), $member->getLang() );
$authorizations = new Rights();
$authorizations->init();
$rights = new Member_rights( $member->getId() );
$rights->init( $config->getInfos( 'module' ), $config->getInfos( 'page' ) );
$lang = new Lang( 'accueil', 'index', $member->getLang() );
$breadcrumb = new Breadcrumb();
$tpl = new TemplatesConstructor();

$tpl->init( $config );

autoload_libs();
?>
