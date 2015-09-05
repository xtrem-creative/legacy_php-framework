<?php
require_once( '../../kernel/begin.php' );
require_once( 'panel_admin.inc.php' );
$types = array( 'lang', 'module', 'design' );
$dirs = array( 'lang', 'modules', 'designs' );
$fhs = $formulaires = array();
foreach( $types AS $k => $val )
{
	$formulaires[$val] = new Form( translate( 'file_upload' ), 'post', '', true );
	$formulaires[$val]->add_fieldset();
	$formulaires[$val]->add_input( 'file_' . $val, 'file_' . $val, translate( 'file_form' ), 'file', 'file' );
	$formulaires[$val]->add_button();
	
	$fhs[$val] = new FormHandle( $formulaires[$val] );
	$fhs[$val]->handle();

	if( $fhs[$val]->okay() )
	{
		$file = $fhs[$val]->get( 'file_' . $val );
		
		load( 'core/zip' );
		
		$zip = new zip( $file );
		if( $zip->extract( ROOT . 'cache/tmp/' . $dirs[$k] . '/' ) )
		{
			$nameZip = $_FILES['file_' . $val]['name'];
			if( is_dir( ROOT . $dirs[$k] . '/' . $nameZip ) )
			{
				$error = new Error();
				$error->add_error( translate( 'file_already_exists' ), ERROR_PAGE, __FILE__, __LINE__ );
			}
			else
			{
				if( ( $dirDbFile = ROOT . 'cache/tmp/' . $dirs[$k] . '/' . $nameZip . 'db/db.sql' ) && is_file( $dirDbFile ) )
					$bdd->extract_files( $dirDbFile );
				
				#rm( ROOT . 'cache/tmp/' . $dirs[$k] . '/' . $nameZip . 'db/' );
				rename( ROOT . 'cache/tmp/' . $dirs[$k] . '/' . $nameZip, ROOT . $dirs[$k] . '/' . $nameZip );
				rm( ROOT . 'cache/tmp/' . $dirs[$k] . '/' . $nameZip );
				$error = new Error();
				$error->add_error( translate( 'file_success' ), ERROR_PAGE, __FILE__, __LINE__ );
			}
		}
		else
		{
			$error = new Error();
			$error->add_error( translate( 'file_fail' ), ERROR_PAGE, __FILE__, __LINE__ );
		}
	}
}
tpl_begin();
?>
<p><?php echo translate( 'help_message' ); ?></p>
<?php
foreach( $formulaires AS $type => $f )
{
	echo '<p>' . translate( $type . '_help' ) . '</p>';
	$f->build_all();
}
tpl_end();
?>
