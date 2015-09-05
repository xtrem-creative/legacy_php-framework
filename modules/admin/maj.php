<?php
require_once( '../../kernel/begin.php' );
require_once( 'panel_admin.inc.php' );
$form = new Form( translate( 'maj_upload' ), 'post', '', true );
$form->add_fieldset();
$form->add_input( 'file', 'file', translate( 'file_form' ), 'file', 'file' );
$form->add_button();

$fh = new FormHandle( $form );
$fh->handle();
load( 'core/zip' );
if( $fh->okay() )
{
	$file = $fh->get( 'file' );	
	$zip = new zip( $file );
	$zipFiles = $zip->list_files();
	$existingFiles = array();
	$newFiles = array();
	$nameFileToMove = md5( mt_rand() );
	$toDeleteFiles = array();
	foreach( $zipFiles AS $filePath )
	{
		if( file_exists( ROOT . $filePath ) )
			$existingFiles[] = $filePath;
		elseif( $filePath == '_files_to_delete.txt' )
			$toDeleteFiles = explode( "\n", $zip->extract_file( '_files_to_delete.txt' ) );
		elseif( $filePath == '_maj_infos.txt' )
			$nameFileToMove = $zip->extract_file( '_maj_infos.txt' );
		else
			$newFiles[] = $filePath;
	}
	move_uploaded_file( $file, ROOT . 'uploads/' . url_transform( $nameFileToMove ) . '.zip' );
}
if( isset( $_GET['confirmMAJ'] ) )
{
	$file = ROOT . 'uploads/' . $_GET['confirmMAJ'] . '.zip';
	$zip = new zip( $file );
	if( $zip->extract( ROOT ) )
	{
		if( file_exists( ROOT . '_files_to_delete.txt' ) )
		{
			$toDeleteFiles = file( ROOT . '_files_to_delete.txt' );
			foreach( $toDeleteFiles AS $filePathDel )
				rm( ROOT . ltrim( trim( $filePathDel), '/' ), true );
		}
		
		@unlink( ROOT . '_files_to_delete.txt' );
		@unlink( ROOT . '_maj_infos.txt' );
		
		$error = new Error();
		$error->add_error( translate( 'maj_success' ), ERROR_PAGE, __FILE__, __LINE__ );
	}
	else
	{
		$error = new Error();
		$error->add_error( translate( 'maj_fail' ), ERROR_PAGE, __FILE__, __LINE__ );
	}
}
tpl_begin();
?>
<p><?php echo translate( 'help_message' ); ?></p>
<?php
if( $fh->okay() )
{
?>
<h3><?php echo translate( 'new_files' ); ?></h3>
<p><?php echo implode( '<br />', $newFiles ); ?><br /></p>
<h3><?php echo translate( 'updated_files' ); ?></h3>
<p><?php echo implode( '<br />', $existingFiles ); ?><br /></p>
<h3><?php echo translate( 'deleted_files' ); ?></h3>
<p><?php echo implode( '<br />', $toDeleteFiles ); ?><br /></p>
<p><a href="?confirmMAJ=<?php echo url_transform( $nameFileToMove ); ?>"><?php echo translate( 'confirm_maj' ); ?></a></p>
<?php
}
else
	$form->build_all();
tpl_end();
?>
