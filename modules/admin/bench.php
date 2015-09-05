<?php
function hm($v){return htmlspecialchars($v);}
$debut = microtime();
for( $i = 0; $i < 10000; $i++ )
	echo hm( 'Lorem ipsum' ) . '<br />';
$fin = microtime();
file_put_contents( 'bench.txt', file_get_contents( 'bench.txt' ) . "\n" . ( $fin - $debut ) );
$debut = microtime();
for( $i = 0; $i < 10000; $i++ )
	echo htmlspecialchars( 'Lorem ipsum' ) . '<br />';
$fin = microtime();
var_dump( $fin - $debut );
file_put_contents( 'bench.txt', file_get_contents( 'bench.txt' ) . "\n" . 'NoAlias' . "\n" . ( $fin - $debut ) );
$debut = microtime();
for( $i = 0; $i < 10000; $i++ )
	echo 'Lorem ipsum' . '<br />';
$fin = microtime();
var_dump( $fin - $debut );
file_put_contents( 'bench.txt', file_get_contents( 'bench.txt' ) . "\n" . 'Nothing' . "\n" . ( $fin - $debut ) );
?>
