<?php
require_once( '../../kernel/begin.php' );
require_once( 'panel_admin.inc.php' );
tpl_begin();
ob_start();
phpinfo();
$b = ob_get_contents();
ob_end_clean();
$b = str_replace( 'body {background-color: #ffffff; color: #000000;}', '', $b );
$b = str_replace( '<html><head>', '', $b );
$b = str_replace( 'a:hover {text-decoration: underline;}', '', $b );
$b = str_replace( 'a:link {color: #000099; text-decoration: none; background-color: #ffffff;}', '', $b );
$b = str_replace( '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "DTD/xhtml1-transitional.dtd">
', '', $b );
$b = str_replace( '<br />
</div></body></html>', '', $b );
$b = str_replace( '<title>phpinfo()</title><meta name="ROBOTS" content="NOINDEX,NOFOLLOW,NOARCHIVE" /></head>
<body><div class="center">', '', $b );
echo $b;
tpl_end();
?>
