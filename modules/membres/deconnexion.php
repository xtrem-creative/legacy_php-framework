<?php
require_once( '../../kernel/begin.php' );
$lang->setModule( 'membres', 'deconnexion' );
if( $member->is_connected() )
{
	$sessions->end_session();
	$member->log_out();
	$error = new Error();
	$error->add_error( translate( 'deconnexion_ok' ), ERROR_GLOBAL, __FILE__, __LINE__, ROOTU . '/modules/accueil/index.php' );
}
else
{
	$error = new Error();
	$error->add_error( translate( 'already_offline' ), ERROR_GLOBAL, __FILE__, __LINE__,  ROOTU . '/modules/accueil/index.php' );
}
#tpl_begin();
#tpl_end();
?>
