<?php
require_once( 'begin_install.php' );
if( isset( $_SESSION['__delete_dir_install'] ) ) {
    rm( ROOT . 'install/' );
    unset( $_SESSION['__delete_dir_install'] );
}
$installPage = new otherTPL();
$installPage->setFile( 'install', './' );
if( isset( $_GET['step'] ) )
    $step = (int) $_GET['step'];
else
    $step = 0;
switch( $step )
{
    case 5:
        $root = $_SERVER['DOCUMENT_ROOT'] . $_SESSION['__install'][1]['root'];
        $rootUrl = $_SESSION['__install'][1]['rootUrl'];
        file_put_contents( ROOT . 'config/config.php', "<?php\n\$CONFIG['path']['root']=\"{$root}\";\n\$CONFIG['path']['rootUrl']=\"{$rootUrl}\";\n ?>" );
        $hostBDD = $_SESSION['__install'][1]['hostBDD'];
        $loginBDD = $_SESSION['__install'][1]['loginBDD'];
        $passwordBDD = $_SESSION['__install'][1]['passwordBDD'];
        $databaseBDD = $_SESSION['__install'][1]['databaseBDD'];
        $prefixeBDD = $_SESSION['__install'][1]['prefixeBDD'];
        $table_members = str_replace( PREFIXE_BDD, $prefixeBDD, TABLE_MEMBERS );
        file_put_contents( ROOT . 'config/bdd.php', "<?php\n\$BDD['mysql']['host']=\"{$hostBDD}\";\n\$BDD['mysql']['login']=\"{$loginBDD}\";\n\$BDD['mysql']['password']=\"{$passwordBDD}\";\n\$BDD['mysql']['database']=\"{$databaseBDD}\";\n\$BDD['mysql']['prefixe']=\"{$prefixeBDD}\";\n\$BDD['mysql']['displayErrors']=false;\n?>" );
        $default_lang = $_SESSION['__install'][2]['langDefault'];
        $default_theme = $_SESSION['__install'][2]['designDefault'];
        $default_module = $_SESSION['__install'][2]['moduleDefault'];
        file_put_contents( ROOT . 'config/infos.php', "<?php\n\$INFOS['defaultParams']['lang']=\"{$default_lang}\";\n\$INFOS['defaultParams']['theme']=\"{$default_theme}\";\n\$INFOS['defaultParams']['module']=\"{$default_module}\";\n\$INFOS['defaultParams']['cache']=true;\n?>" );
        $admin_login = $_SESSION['__install'][3]['login'];
        $admin_password = $_SESSION['__install'][3]['password'];
        $admin_email = $_SESSION['__install'][3]['email'];
        chmod( ROOT . 'config/config.php', 0777 );
        chmod( ROOT . 'config/bdd.php', 0777 );
        chmod( ROOT . 'config/infos.php', 0777 );
        $bdd = new bdd( array( 'host' => $hostBDD, 'login' => $loginBDD, 'database' => $databaseBDD, 'password' => $passwordBDD, 'displayErrors' => false ) );
        $bdd->query( str_replace( '#_PREFIX_#', $prefixeBDD, file_get_contents( ROOT . 'install/db/install.sql' ) ) );
        $bdd->query( 'INSERT INTO `' . $table_members . '` ( membre_login, membre_email, membre_password, membre_register, membre_last_up, membre_rank, membre_design, membre_lang ) VALUES ( ?, ?, ?, ?, ?, ?, ?, ? ) ', 
            array( $admin_login, $admin_email, $admin_password, time(), time(), RANK_ADMIN, NULL, NULL ) );
        unset( $_SESSION['__install'] );
        if( isset( $_POST['delete_dir'] ) )
            $_SESSION['__delete_dir_install'] = true;
    break;
    case 4:
        if( isset( $_POST['login'], $_POST['password'], $_POST['passwordConfirm'], $_POST['email'] ) )
        {
            $login = $_POST['login'];
            $password = $_POST['password'];
            $passwordconfirm = $_POST['passwordConfirm'];
            $email = $_POST['email'];
            if( $password == $passwordconfirm )
            {
                $passwordHash = _hash( $password );
                if( check_email( $email ) === 1 )
                {
                    if( check_pseudo( $login ) )
                    {
                        $_SESSION['__install'][3] = array( 'login' => $login, 'password' => $passwordHash, 'email' => $email );    
                        break;
                    }
                    else
                    {
                        $error = new error();
                        $error->addError( 'L\'email n\'est pas à un format conventionnel.', ERROR_PAGE, 'install.php', __LINE__ );
                        $step = 3;    
                    }
                }
                else
                {
                    $error = new error();
                    $error->addError( 'L\'email n\'est pas à un format conventionnel.', ERROR_PAGE, 'install.php', __LINE__ );
                    $step = 3;
                }
            }
            else
            {
                $error = new error();
                $error->addError( 'Les deux mots de passe ne sont pas identiques.', ERROR_PAGE, 'install.php', __LINE__ );
                $step = 3;
            }
        }
    case 3:
        if( isset( $_POST['sendstep3'] ) )
        {
            $moduleDefault = $_POST['module'];
            $designDefault = $_POST['design'];
            $langDefault = $_POST['lang'];
            if( !is_dir( '../lang/' . $langDefault ) ) $langDefault = DEFAULT_LANG;
            if( !is_dir( '../modules/' . $moduleDefault ) ) $moduleDefault = DEFAULT_MODULES;
            if( !is_dir( '../designs/' . $designDefault ) ) $designDefault = DEFAULT_DESIGN;
            $_SESSION['__install'][2] = array( 'langDefault' => $langDefault, 'moduleDefault' => $moduleDefault, 'designDefault' => $designDefault );
        }
    break;
    case 2:
        if( isset( $_POST['rooturl'], $_POST['root'], $_POST['host'], $_POST['login'], $_POST['password'], $_POST['database'] ) )
        {
            $rooturl = $_POST['rooturl'];
            $root = $_POST['root'];
            $host = $_POST['host'];
            $loginBDD = $_POST['login'];
            $passwordBDD = $_POST['password'];
            $databaseBDD = $_POST['database'];
            $prefixeBDD = $_POST['prefix'];
            $bdd = new bdd( array( 'host' => $host, 'login' => $loginBDD, 'database' => $databaseBDD, 'password' => $passwordBDD, 'displayErrors' => true ) );
            if( empty( $bdd->pdo ) )
            {
                $error = new error();
                $error->addError( 'Les informations données sur la base de données ne sont pas correctes, le lien n\'a pas pu être établi. Réessayez.', ERROR_PAGE, 'install.php', __LINE__ );
                $step = 1;
            }
            else
                $_SESSION['__install'][1] = array( 'hostBDD' => $host, 'loginBDD' => $loginBDD, 'databaseBDD' => $databaseBDD, 'passwordBDD' => $passwordBDD, 'root' => $root, 'rootUrl' => $rooturl, 'prefixeBDD' => $prefixeBDD );
        }      
        else
        {
            $error = new error();
            $error->addError( 'Toutes les informations n\'ont pas été données.', ERROR_PAGE, 'install.php', __LINE__ );
            $step = 1;
        }
        if( $step == 2 )
        {
            $dirLangs = glob( '../lang/*', GLOB_ONLYDIR );
            foreach( $dirLangs AS $k => $langs )
                $dirLangs[$k] = str_replace( '../lang/', '', $langs );
            $dirDesigns = glob( '../designs/*', GLOB_ONLYDIR );
            foreach( $dirDesigns AS $k => $designs )
                $dirDesigns[$k] = str_replace( '../designs/', '', $designs );
            $dirModules = glob( '../modules/*', GLOB_ONLYDIR );
            foreach( $dirModules AS $k => $modules )
                $dirModules[$k] = str_replace( '../modules/', '', $modules );
        }
    case 1:
        $url = str_replace( array( 'install/' . basename( __FILE__ ), '?step=1', '?step=2' ), '', "http://" . $_SERVER["SERVER_NAME"] . $_SERVER["SCRIPT_NAME"] );
        $dir = str_replace( array( 'install/' . basename( __FILE__ ), $_SERVER['DOCUMENT_ROOT'] ), '', $_SERVER['SCRIPT_FILENAME'] );
    break;
    case 0;
        $vCompare = version_compare( PHP_VERSION, '5.0.0' );
        $dirToVerify = array( '../cache', '../kernel/db', '../lang', '../uploads', '../feeds' );
        $allOkayWritable = true;
        $allOkayExist = true;
    	foreach ($dirToVerify as $dir)
    	{
    		$isWritable = $isDir = true;
    		if (file_exists($dir) && is_dir($dir)) {
    			if (!is_writable($dir))
    				$isWritable = (@chmod($dir, 0777)) ? true : false;
			}
    		else
    			$isDir = $isWritable = ($fp = @mkdir($dir, 0777)) ? true : false;
			if( $isDir === false ) $allOkayExist = false;
			if( $isWritable === false ) $allOkayWritable = false;
			$dirVerification[str_replace( '../', '', $dir)] = array( $isDir, $isWritable );
    	}   
    	$_SESSION['__install'][0] = array( 'vCompare' => $vCompare, 'dirVerification' => $dirVerification, 'allDirOkayWritable' => $allOkayWritable, 'allDirOkayExist' => $allOkayExist );
    break;
}
require_once( $installPage->display() );
?>
