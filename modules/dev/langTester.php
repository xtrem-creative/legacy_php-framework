<?php
require_once( '../../kernel/begin.php' );
$lang->setModule( 'dev', 'langTester' );
echo translate( 'test_multi', 'argument 1', 'argument 2' );
echo '<br />';
echo translate( 'test_ordre', 'argument 1 mais en 2', 'argument 2 en 1', 'argument 3 en 3' );
?>
