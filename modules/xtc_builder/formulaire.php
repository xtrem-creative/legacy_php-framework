<?php
require_once( '../../kernel/begin.php' );
require_once( 'parser.class.php' );
$lang->setModule( 'xtc_builder', 'index' );

$form = new Form( 'Formulaire', 'post' );
$form->add_fieldset();
$form->add_button( 'button', 'input_insert', 'Input' )->setonClick( 'insert( \'texForm\', \'Input <nom,id,label>\' );' )->setInline( true );
$form->add_button( 'button', 'textarea_insert', 'Textarea' )->setonClick( 'insert( \'texForm\', \'Textarea <nom,id,label>\' );' )->setInline( true );
$form->add_button( 'button', 'fieldset_insert', 'Fieldset' )->setonClick( 'insert( \'texForm\', \'Fieldset <nom>\' );' )->setInline( true );
$form->add_button( 'button', 'button_insert', 'Button' )->setonClick( 'insert( \'texForm\', \'Button <type,nom,valeur>\' );' )->setInline( true );
$form->add_button( 'button', 'liste_insert', 'Liste (avec valeurs)' )->setonClick( 'insert( \'texForm\', \'Liste <type,nom,id,valeur>\', \'avec\' );' )->setInline( true );
$form->add_button( 'button', 'liste_insert', 'Liste (sans valeurs)' )->setonClick( 'insert( \'texForm\', \'Liste <type,nom,id,valeur>\', \'sans\' );' )->setInline( true );
$form->add_button( 'button', 'liste_insert', 'Choix (avec valeurs)' )->setonClick( 'insert( \'texForm\', \'Choix <type,nom>\', \'avec\' );' )->setInline( true );
$form->add_button( 'button', 'liste_insert', 'Choix (sans valeurs)' )->setonClick( 'insert( \'texForm\', \'Choix <type,nom>\', \'sans\' );' )->setInline( true );
$form->add_textarea( 'texForm', 'texForm', 'Contenu à parser' );
$form->add_button();

$fh = new FormHandle( $form );
$fh->handle();
tpl_begin();
if( $fh->okay() )
{
	$contenuAParser = $fh->get( 'texForm' );
	$parserPage = new xtc_builder_formulaire( $contenuAParser );
	$r = $parserPage->parse_content();
	$md5 = md5( $r );
	file_put_contents( ROOT . 'modules/xtc_builder/cache/formulaires/' . $md5 . '.php', $r );
	echo '<p>À insérer dans la zone de texte.</p>';
	echo '<input type="text" value="Form <#' . $md5 . '>" />';
}
else
	$form->buildAll();
tpl_end();
?>
