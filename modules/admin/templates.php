<?php
require_once( '../../kernel/begin.php' );
require_once( 'panel_admin.inc.php' );
tpl_begin();
$listeFichiers = glob( ROOT . 'kernel/templates/*.tpl' );
if( isset( $_GET['fichier'] ) )
{
	$fichier = $_GET['fichier'];
	$verif = ROOT . 'kernel/templates/' . $fichier . '.tpl';
	if( in_array( $verif, $listeFichiers ) )
	{
		$contenu = file_get_contents( $verif );
		$form = new Form( translate( 'file_form' ), 'post' );
		$form->add_fieldset();
		$form->add_textarea( 'contenu', 'contenu', translate( 'content_file' ) )->setValue( $contenu );
		$form->add_button();
		
		$fh = new FormHandle( $form );
		$fh->handle();
		
		if( $fh->okay() )
		{
			$contenu = $fh->get( 'contenu' );
			file_put_contents( $verif, $contenu );
			$error = new Error();
			$error->add_error( translate( 'modification_ok' ), ERROR_PAGE, __FILE__, __LINE__ );
		}
		else
		{
			$form->build_all();
		}
	}
}
?>
<p><?php echo translate( 'help_message' ); ?></p>
<ul>
<?php
foreach( $listeFichiers AS $fichier )
{
	$lien = str_replace( array( '.tpl', ROOT . 'kernel/templates/' ), '', $fichier );
	echo '<li><a href="?fichier=' . $lien . '">' . $lien . '</a></li>';
}
?>
</ul>
<?php
tplEnd();
?>
