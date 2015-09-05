<?php
require_once( '../../kernel/begin.php' );
$lang->setModule( 'accueil', 'contact' );
$form = new Form( translate( 'contact_form' ) );
$form->add_fieldset();
$form->add_input( 'message_objet', 'message_objet', translate( 'message_objet_form' ) );
$form->add_input( 'message_email', 'message_email', translate( 'message_email_form' ) )->setRequired( false );
$form->add_textarea( 'message_contenu', 'message_contenu', translate( 'message_contenu_form' ) );
$form->add_button();

$fh = new FormHandle( $form );
$fh->handle();
if( $fh->okay() )
{
	$messageObjet = $fh->get( 'message_objet' );
	$messageEmail = $fh->get( 'message_email' );
	$messageContenu = $fh->get( 'message_contenu' );
	$bdd->query( 'INSERT INTO ' . TABLE_CONTACT . ' ( contact_objet, contact_message, contact_email, contact_date, contact_ip ) VALUES( ?, ?, ?, ?, ? )', 
											array( $messageObjet, $messageContenu, $messageEmail, time(), get_ip() ) );
	$error = new Error();
	$error->add_error( translate( 'message_send_okay' ), ERROR_PAGE, __FILE__, __LINE__ );
}
tpl_begin();
$form->build_all();
tpl_end();
?>
