<?php
if( file_exists( './config/infos.php' ) )
{
	require( './config/infos.php' );
    if( is_file( './modules/' . $INFOS['defaultParams']['module'] . '/index.php' ) )
		header( 'Location: ./modules/' . $INFOS['defaultParams']['module'] . '/index.php' );
	else
		header( 'Location: ./modules/accueil/index.php' );
}
else
    header( 'Location: ./install/install.php' );
?>
