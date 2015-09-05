<?php
require_once( '../../kernel/begin.php' );
require_once( 'panel_admin.inc.php' );
if( isset( $_GET['refresh'] ) )
{
	rm( ROOT . 'cache', false );
	copy( ROOT . 'config/index.html', ROOT . 'cache/index.html' );
	$cache->rebuild_caches();
}
tpl_begin();
?>
<p><?php echo translate( 'help_message' ); ?></p>
<p><a href="?refresh"><?php echo translate( 'wanna_refresh' ); ?></a></p>
<?php
tpl_end();
?>
