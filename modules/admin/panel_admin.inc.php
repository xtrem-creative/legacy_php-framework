<?php
$lang->setModule( 'admin', str_replace( '.php', '', basename( $_SERVER['PHP_SELF'] ) ) );
$tpl->menus()->setType( 2 );
if( !$member->is_connected() )
{
	$error = new Error();
	$error->add_error( translate( 'not_online' ), ERROR_GLOBAL, __FILE__, __LINE__ );
	tpl_begin();
	tpl_end();
	die;
}
elseif( $member->getRank() < RANK_ADMIN )
{
	$error = new Error();
	$error->add_error( translate( 'not_admin' ), ERROR_GLOBAL, __FILE__, __LINE__ );
	tpl_begin();
	tpl_end();
	die;
}
?>
