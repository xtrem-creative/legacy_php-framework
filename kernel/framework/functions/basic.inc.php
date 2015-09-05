<?php
function load( $dir, $type = CLASS_LOAD, $return = false )
{
	$return = true;
    if( is_file( ROOT . 'kernel/framework/' . $dir . $type ) )
		if( $return === true )
			$return = require_once( ROOT . 'kernel/framework/' . $dir . $type );
		else
			require_once( ROOT . 'kernel/framework/' . $dir . $type );
    elseif( is_file( ROOT . 'kernel/libs/' . $dir . $type ) )
		if( $return === true )
			$return = require_once( ROOT . 'kernel/libs/' . $dir . $type );
		else
			require_once( ROOT . 'kernel/libs/' . $dir . $type );
    else
        echo '->' . $dir . ', inclusion interrompue : <strong>fichier inexistant</strong><br />';
	return $return;
}

function autoload_libs()
{
	$libraries = parcourt_arborescence( ROOT . 'kernel/libs/', false, true, false );
	foreach( $libraries As $library )
	{
		if( file_exists( ROOT . 'kernel/libs/' . $library . '/_autoload' . INC_LOAD ) )
			load( $library . '/' . '_autoload', INC_LOAD );
	}
}

function initialize_paths()
{
    $dir = str_replace( 'kernel/framework/functions', '', dirname( __FILE__ ) );
    require( $dir . 'config/config.php' );
    $root = $CONFIG['path']['root'];
    $rootUrl = $CONFIG['path']['rootUrl'];
    define( 'ROOT', $root );
    define( 'ROOTU', $rootUrl );
}

function get_prefix_bdd()
{
    require( ROOT . 'config/bdd.php' );
    return $BDD['mysql']['prefixe'];
}

function tpl_begin()
{
    global $tpl;
	global $breadcrumb;
	global $member;
    require_once( $tpl->header()->display() );
    require_once( $tpl->menus()->display() );
    require_once( $tpl->top()->display() );
}

function tpl_end()
{
    global $tpl;
    require_once( $tpl->footer()->display() );
}
?>
