<?php
require_once( '../../kernel/begin.php' );
$lang->setModule( 'news', 'ajouter' );
$xtcode = new XTCode();

$form = new Form( translate( 'title_add_form' ) );
$form->add_fieldset();
$form->add_input( 'news_title', 'news_title', translate( 'news_title' ) );
$form->add_textarea( 'news_content', 'news_content', translate( 'news_content' ) );
$form->add_button();
$fh = new FormHandle( $form );
$fh->handle();


if( $fh->okay() )
{
	$error = new Error();
	$error->add_error( translate( 'addition_success' ), ERROR_GLOBAL, __FILE__, __LINE__, ROOTU . 'modules/news/index.php' );
}
else
{
	tpl_begin();
	$form->build_all();
	tpl_end();
}
?>
