<?php
session_start();

header( 'Content-type:text/html; charset=utf-8' );
header( 'Cache-Control: no-cache, must-revalidate' );

require_once( '../kernel/framework/functions/basic.inc.php' );

define( 'ROOT', '../' );
define( 'ROOTU', '../' );
define( 'DEFAULT_LANG', 'fr' );
define( 'DEFAULT_DESIGN', 'base' );
define( 'DEFAULT_MODULE', 'accueil' );
define( 'PREFIXE_BDD', 'xtremcms_' );

require_once( '../kernel/framework/inc/constants.inc.php' );

load( 'config/config' );
load( 'core/cache' );
load( 'core/errors' );
load( 'templates/constructor' );
load( 'lang/lang' );
load( 'db/mysql' );
load( 'members/member' );
load( 'functions/useful', INC_LOAD );

$config = new config( false, 'base', 'fr', false );

$lang = new lang( 'install', 'install', 'fr', ROOT . 'install/lang/' );

$tpl = new templatesConstructor();
?>
