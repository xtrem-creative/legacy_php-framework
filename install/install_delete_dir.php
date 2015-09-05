<?php
if( isset( $_SESSION['__delete_dir_install'] ) ) {
    file_put_contents( ROOT . 'kernel/begin.php', str_replace( "require_once( ROOT . 'install/install_delete_dir.php' );\n", '', file_get_contents( ROOT . 'kernel/begin.php' ) ) );
    unset( $_SESSION['__delete_dir_install'] );
    rm( ROOT . 'install/' );
}
?>
