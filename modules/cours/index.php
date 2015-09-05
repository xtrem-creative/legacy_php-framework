<?php
require_once( '../../kernel/begin.php' );
$lang->setModule( 'cours', 'index' );
require_once( 'cours.class.php' );
tpl_begin();
echo translate( 'presentation' );
if( isset( $_GET['afficheTutoriel'] ) )
{
	$idElement = intval( $_GET['afficheTutoriel'] );
	
	$cours = new CoursElement( $idElement );
	$cours->affiche();
}
else
{
	$idElement = ( isset( $_GET['idElement'] ) ? intval( $_GET['idElement'] ) : 0 );
	$cours = new CoursElement( $idElement );
	$cours->elements_enfants();
}
tpl_end();
?>
