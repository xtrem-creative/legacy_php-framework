<?php
require_once( '../../kernel/begin.php' );
load( 'members/authorizations' );
load( 'members/groups' );
echoa( $rights );
var_dump( $rights->verif( RIGHT_READ ) );
var_dump( $rights->verif( RIGHT_MODIFY ) );
var_dump( $rights->verif( RIGHT_DELETE ) );
var_dump( $rights->verif( RIGHT_MODERATE ) );
?>
