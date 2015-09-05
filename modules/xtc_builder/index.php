<?php
require_once( '../../kernel/begin.php' );
require_once( 'parser.class.php' );
$lang->setModule( 'xtc_builder', 'index' );

$form = new Form( 'Titre', 'post' );
$form->add_fieldset();
$form->add_input( 'nom_page', 'nom_page', translate( 'page_name' ) );
$form->add_button( 'button', 'include_insert', translate( 'include_insert' ) )->setonClick( 'insert( \'texTop\', \'Include <page.php>\' );' )->setInline( true );
$form->add_button( 'button', 'load_insert', translate( 'load_insert' ) )->setonClick( 'insert( \'texTop\', \'Load <page>\' );' )->setInline( true );
$form->add_button( 'button', 'requetesql_insert', translate( 'requetesql_insert' ) )->setonClick( 'insert( \'texTop\', \'Requete <table,type,champs,conditions,order,limit>\' );' )->setInline( true );
$form->add_button( 'button', 'debut_insert', translate( 'debut_insert' ) )->setonClick( 'insert( \'texTop\', \'AfficDebut\' );' )->setInline( true );
$form->add_button( 'button', 'fin_insert', translate( 'fin_insert' ) )->setonClick( 'insert( \'texTop\', \'AfficFin\' );' )->setInline( true );
$form->add_button( 'button', 'html_insert', translate( 'html_insert' ) )->setonClick( 'insert( \'texTop\', \'Html\n{\n}\' );' )->setInline( true );
$form->add_textarea( 'texTop', 'texTop', 'Contenu à parser' );
$form->add_button();

$fh = new FormHandle( $form );
$fh->handle();

if( $fh->okay() )
{
	$contenuAParser = $fh->get( 'texTop' );
	$parserPage = new xtc_builder_page( $contenuAParser );
	$md5Page = md5( $contenuAParser );
	$bdd->query( 'INSERT INTO ' . TABLE_PAGES_PHP . ' VALUES( ?, ? )', array( $md5Page, $fh->get( 'nom_page' ) ) );
	file_put_contents( 'cache/pagesOriginales/' . $md5Page . '.php', $parserPage );
	file_put_contents( 'cache/pagesPHP/' . $md5Page . '.php', $parserPage->parse_content() );
	$error = new Error();
	$error->add_error( translate( 'page_success' ), ERROR_GLOBAL, __FILE__, __LINE__, ROOTU . 'modules/accueil/index.php' );
}
tpl_begin();
echo '<p><a href="formulaire.php" target="_blank">Créer un formulaire.</a></p>';
echo translate( 'presentation' );
$form->build_all();
tpl_end();
?>
