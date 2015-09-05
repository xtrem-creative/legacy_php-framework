<?php
require_once( '../../kernel/begin.php' );
$lang->setModule( 'membres', 'inscription' );

$form = new Form( translate( 'title_form' ), 'post' );
$form->add_fieldset();
$form->add_input( 'login', 'login', translate( 'login_form' ) );
$form->add_input( 'password', 'password', translate( 'password_form' ), 'password' );
$form->add_input( 'password_confirm', 'password_confirm', translate( 'password_confirm' ), 'password' );
$form->add_input( 'email', 'email', translate( 'email_form' ) );
$form->add_button();

$fh = new FormHandle( $form );
$fh->handle();

if( $fh->okay() )
{
	$login = $fh->get( 'login' );
	$password = _hash( $fh->get( 'password' ) );
	$password_confirm = _hash( $fh->get( 'password_confirm' ) );
	$email = $fh->get( 'email' );
	if( $password != $password_confirm )
	{
		$error = new Error();
		$error->add_error( translate( 'two_passwords_not' ), ERROR_PAGE, __FILE__, __LINE__ );
	}
	else
	{
		$params = array( $login, $email, $password, time(), time() );
		$bdd->query( 'INSERT INTO ' . TABLE_MEMBERS . ' ( membre_login, membre_email, membre_password, membre_register, membre_last_up ) VALUES( ?, ?, ?, ?, ? )', $params );
		$error = new Error();
		$error->add_error( translate( 'inscription_ok' ), ERROR_PAGE, __FILE__, __LINE__ );
		tpl_begin();
		echo '<p>' . translate( 'welcome' ) . '</p>';
		tpl_end();
		exit;
	}
}

tpl_begin();
?>
<h2><?php echo translate( 'title' ); ?></h2>
<?php

$form->build_all();

tpl_end();
?>
