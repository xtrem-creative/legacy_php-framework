<?php
require_once( '../../kernel/begin.php' );
$lang->setModule( 'membres', 'connexion' );
if( $member->is_connected() )
{
	$error = new Error();
	$error->add_error( translate( 'already_online' ), ERROR_GLOBAL, __FILE__, __LINE__, ROOTU . 'modules/accueil/index.php' );
}
else
{
	$form = new Form( translate( 'title_form' ), 'post' );
	$form->add_fieldset();
	$form->add_input( 'login', 'login', translate( 'login_form' ) );
	$form->add_input( 'password', 'password', translate( 'password_form' ), 'password' );
	$form->add_button();

	$fh = new FormHandle( $form );
	$fh->handle();

	if( $fh->okay() )
	{
		$login = $fh->get( 'login' );
		$password = _hash( $fh->get( 'password' ) );
		$params = array( $login, $password );
		$cSql = $bdd->count_sql( TABLE_MEMBERS, 'WHERE membre_login = ? AND membre_password = ?', $params );
		if( $cSql == 0 )
		{
			$error = new Error();
			$error->add_error( translate( 'inexistant_member' ), ERROR_PAGE, __FILE__, __LINE__ );
		}
		else
		{
			$requete = $bdd->query( 'SELECT * FROM ' . TABLE_MEMBERS . ' WHERE membre_login = ? AND membre_password = ?', $params );
			$resultats = $bdd->fetch( $requete );
			$hashKey = _hash( $resultats['membre_id'] . $login, 'XTC_CMS' );
			$sessions->add_session( 'pseudo', $login, '__member' )->add_session( 'id', $resultats['membre_id'], '__member' )
			->add_session( 'key', $hashKey, '__member' )->add_session( 'isConnected', true, '__member' );
			$member->log_in( $resultats['membre_id'] );
			$error = new Error();
			$error->add_error( translate( 'connexion_ok' ), ERROR_GLOBAL, __FILE__, __LINE__, ROOTU . 'modules/accueil/index.php' );
		}
	}
}
tpl_begin();
if( isset( $form ) )
	$form->build_all();
tpl_end();
?>
