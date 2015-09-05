<?php
require_once( '../../kernel/begin.php' );
load( 'core/commentaires' );
tpl_begin();
$commentaires = new commentaires( 'news' );
echo $commentaires->panel( 1 );
tpl_end();
?>
