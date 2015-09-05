<?php
require_once( '../../kernel/begin.php' );
require_once( 'panel_admin.inc.php' );
tpl_begin();
include( ROOT . 'kernel/infos.php' );

if( $fileVersion = @fopen( 'http://www.xtrem-creative.com/xtrem-cms/version.txt', 'r' ) )
{
	$versionsLine = fgets( $fileVersion );
	$versionsLine = explode( ',', $versionsLine );
	$versionCMS = trim( $versionsLine[0] );
	$versionKernel = trim( $versionsLine[1] );
	if( version_compare( $versionCMS, $infosCMS['version_cms'] ) == 1 )
		$toUpdateAll = true;
	if( version_compare( $versionKernel, $infosCMS['version_kernel'] ) == 1 )
		$toUpdateKernel = true;
}
else
	echo '<p>' . translate( 'file_update_not_found' ) . '</p>';
?>
<h3><?php echo translate( 'info_cms_version' ); ?></h3>
<p><?php echo translate( 'version_of_is', 'CMS' ); ?> : <strong><?php echo $infosCMS['version_cms']; ?></strong></p>
<p><?php echo translate( 'version_of_is', 'kernel' ); ?> : <strong><?php echo $infosCMS['version_kernel']; ?></strong></p>
<p><?php echo translate( 'version_alpha' ); ?></p>
<h3><?php echo translate( 'compare_with_last' ); ?></h3>
<?php
if( isset( $toUpdateAll ) ) 
	echo '<p>' . translate( 'not_last_version', 'CMS', $versionCMS ) . '</p>';
if( isset( $toUpdateKernel ) )
	echo '<p>' . translate( 'not_last_version', 'CMS', $versionKernel ) . '</p>';
if( !isset( $toUpdateAll, $toUpdateKernel ) )
	echo '<p>' . translate( 'no_update_found' ) . '</p>';
?>
<p></p>
<?php
tpl_end();
?>
