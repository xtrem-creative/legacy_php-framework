<?php
define( 'CLASS_LOAD', '.class.php' );
define( 'INC_LOAD', '.inc.php' );
define( 'LANG_LOAD', '.lang.php' );
define( 'CACHE_LOAD', '.datas.php' );

define( 'CACHE_LIMIT', 60 * 2 );
define( 'SESSIONS_LIMIT', 60 * 10 );

define( 'NB_COMS_PAGE', 10 );

define( 'UPLOAD_MAX_SIZE', 2097152 );
define( 'UPLOAD_EXTENSIONS', serialize( array( 'zip', 'jpg', 'jpeg', 'gif', 'png' ) ) );

define( 'DIR_LANG', ROOT . 'lang/' );
define( 'DIR_TEMPLATES', ROOT . 'kernel/templates/' );
define( 'DIR_STYLES', 'designs/' );
define( 'DIR_CACHE', ROOT . 'cache/' );

define( 'ERROR_404', 404 );
define( 'ERROR_403', 403 );

define( 'ERROR_GLOBAL', 'globalError' );
define( 'ERROR_PAGE', 'pageError' );

define( 'HASH_PREFIXE', '#-_<(^-^)>_-#' );
define( 'HASH_SUFFIXE', 'XTC_CMS_STAN_GILIAM' );

define( 'EMAIL_MAX_LENGTH', 40 );

define( 'RANK_ADMIN', 3 );
define( 'RANK_MODO', 2 );
define( 'RANK_MEMBER', 1 );
define( 'RANK_GUEST', 0 );
?>
