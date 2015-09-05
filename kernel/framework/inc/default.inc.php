<?php
require( ROOT . 'config/infos.php' );
define( 'DEFAULT_LANG', $INFOS['defaultParams']['lang'] );
define( 'DEFAULT_DESIGN', $INFOS['defaultParams']['theme'] );
define( 'DEFAULT_MODULE', $INFOS['defaultParams']['module'] );

define( 'CACHE_STATUS', (bool)$INFOS['defaultParams']['cache'] );

define( 'PREFIXE_BDD', get_prefix_BDD() );
?>
