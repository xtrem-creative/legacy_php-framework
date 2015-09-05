<!DOCTYPE html>
<html lang="fr">
	<head>
		<meta charset="utf-8" />
		<title>Installation du CMS XTC</title>
		<style type="text/css">
		    body
            {
	            background:#2e3436;
            }

            header
            {
	            display:block;
	            text-align:center;
	            padding-top:10px;
	            color:#e6e6fa;
	            font-family:Arial;
            }

            #corps
            {
	            margin:40px 20px 40px 20px;
	            background:#FFFFFF;
	            -moz-box-shadow: 0 0 10px #FFFFFF;
	            padding: 5px 5px 5px 15px;
            }

            footer
            {
	            text-align:center;
	            color:#FFFFFF;
	            font-size:0.6em;
	            display:block;
            }
            label 
            {
                display:block;
                width:200px;
                float:left;
            }
            input
            {
                width:300px;
            }
            /* ErrorDiv */
            #errorDiv {
	            padding: 8px 15px;
            }
            #errorDiv a {
	            padding: 6px;
	            line-height: 1.5;
	            font-size: .9em;
	            text-decoration: none;
            }

            .errorDivNo {
                display:block;
                background: url( 'img/error_no.gif' );
                padding:3px 0px 6px 30px;
            }
            .errorDivOk {
                display:block;
                background: url( 'img/error_ok.gif' ) no-repeat;
                padding:3px 0px 6px 30px;
            }
		</style>
	</head>
	<body>
		<header>
			<h1>Installation du CMS XTC</h1>
		</header>
		<div id="corps">
		    <?php
	        $error = new error();
	        $errors = $error->getDatas();
	        if( !empty( $errors ) )
	        {
	            if( !is_array( $errors ) ) $errors = array( $errors );
            ?>
            <div id="errorDiv">
            <?php
	            foreach( $errors AS $e )
	            {
	        ?>
	            <p class="errorDiv<?php echo $e['type']; ?>"><?php echo $e['message']; ?></p>
	        <?php
	            }
            ?>
            </div>
            <?php
	        }
            if( $step == 0 )
            {
            ?>
            <form method="post" action="?step=1">
                <fieldset>
                    <legend><?php echo $lang->get( 'verification' ); ?></legend>
            <?php
                if( $vCompare == 1 )
                    echo '<p>' . $lang->get( 'php_success' ) . '</p>';
                else
                    echo '<p>' . $lang->get( 'php_fail' ) . '</p>';
                foreach( $dirVerification AS $dir => $auth )
                {
            ?>
                <p>
                <strong><?php echo $dir; ?> : </strong>
                <em><?php echo ( $auth[0] === true ? 'Existant' : 'Inexistant' ); ?></em> &
                <em><?php echo ( $auth[1] === true ? 'Inscriptible' : 'Bloqué' ); ?></em>
                </p>
            <?php
                }
            ?>
                <input type="submit" />
            </form>
            <?php
            }
		    elseif( $step == 1 )
		    {
		    ?>
			<form method="post" action="?step=2">
			    <fieldset>
			        <legend><?php echo $lang->get( 'host_title' ); ?></legend>
			        <p><label for="rooturl"><?php echo $lang->get( 'host_rooturl' ); ?> : </label><input type="text" id="rooturl" name="rooturl" value="<?php echo $url; ?>" /></p>
			        <p><label for="root"><?php echo $lang->get( 'host_root' ); ?> : </label><input type="text" id="root" name="root" value="<?php echo $dir; ?>" /></p>
			    </fieldset>
			    <br />
			    <fieldset>
			        <legend><?php echo $lang->get( 'bdd_title' ); ?></legend>
			        <p><label for="host"><?php echo $lang->get( 'bdd_host' ); ?> : </label><input type="text" id="host" name="host" value="localhost" /></p>
			        <p><label for="login"><?php echo $lang->get( 'bdd_login' ); ?> : </label><input type="text" id="login" name="login" /></p>
			        <p><label for="password"><?php echo $lang->get( 'bdd_password' ); ?> : </label><input type="password" id="password" name="password" /></p>
			        <p><label for="database"><?php echo $lang->get( 'bdd_database' ); ?> : </label><input type="text" id="database" name="database" /></p>
			        <p><label for="prefix"><?php echo $lang->get( 'bdd_prefix' ); ?> : </label><input type="text" id="prefix" name="prefix" value="<?php echo PREFIXE_BDD; ?>" /></p>
			    </fieldset>
		        <p><input type="submit" /></p>
			</form>
			<?php
			}
			elseif( $step == 2 )
			{
			?>
            <form method="post" action="?step=3">
                <fieldset>
                    <legend><?php echo $lang->get( 'default_lang' ); ?></legend>
                    <?php
                    foreach( $dirLangs AS $langDef )
                    {
                        $checked = ( $langDef == 'fr' ? 'checked="checked"' : NULL );
                    ?>
                    <p><label for="lang_<?php echo $langDef; ?>"><?php echo ucfirst( $langDef ); ?> - <img src="lang/img/<?php echo $langDef; ?>.png" name="Lang Flag" title="Lang Flag" /> : </label><input type="radio" value="<?php echo $langDef; ?>" name="lang" id="lang_<?php echo $langDef; ?>" <?php echo $checked; ?> /></p>
                    <?php
                    }
                    ?>
                </fieldset>
                <fieldset>
                    <legend><?php echo $lang->get( 'default_theme' ); ?></legend>
                    <?php
                    foreach( $dirDesigns AS $design )
                    {
                        $checked = ( $design == 'base' ? 'checked="checked"' : NULL );
                    ?>
                    <p><label for="design_<?php echo $design; ?>"><?php echo ucfirst( $design ); ?> : </label><input type="radio" value="<?php echo $design; ?>" name="design" id="design_<?php echo $design; ?>" <?php echo $checked; ?> /></p>
                    <?php
                    }
                    ?>
                </fieldset>
                <fieldset>
                    <legend><?php echo $lang->get( 'default_module' ); ?></legend>
                    <?php
                    foreach( $dirModules AS $module )
                    {
                        $checked = ( $module == 'accueil' ? 'checked="checked"' : NULL );
                    ?>
                    <p><label for="module_<?php echo $module; ?>"><?php echo ucfirst( $module ); ?> : </label><input type="radio" value="<?php echo $module; ?>" name="module" id="module_<?php echo $module; ?>" <?php echo $checked; ?> /></p>
                    <?php
                    }
                    ?>
                </fieldset>
                <input type="submit" name="sendstep3" />
            </form>			
			<?php
			}
			elseif( $step == 3 )
			{
			?>
		    <form method="post" action="?step=4">
		        <fieldset>
		            <legend><?php echo $lang->get( 'admin_register' ); ?></legend>
		            <p><label for="login"><?php echo $lang->get( 'admin_login' ); ?> : </label><input type="text" name="login" id="login" /></p>
		            <p><label for="password"><?php echo $lang->get( 'admin_password' ); ?> : </label><input type="password" name="password" id="password" /></p>
		            <p><label for="passwordConfirm"><?php echo $lang->get( 'admin_passwordC' ); ?> : </label><input type="password" name="passwordConfirm" id="passwordConfirm" /></p>
		            <p><label for="email"><?php echo $lang->get( 'admin_email' ); ?> : </label><input type="text" name="email" id="email" /></p>
		            <input type="submit" />
	            </fieldset>
		    </form>
			<?php
			}
			elseif( $step == 4 )
			{
			?>
		    <form method="post" action="?step=5">
		        <fieldset>
		            <legend><?php echo $lang->get( 'installation_go' ); ?></legend>
		            <p><label for="confirmation"><?php echo $lang->get( 'delete_dir' ); ?> : </label><input type="checkbox" name="delete_dir" value="true" id="confirmation" /></p><br />
		            <p><?php echo $lang->get( 'confirm_installation' ); ?></p>
		            <p>
<textarea rows="20" cols="100">###################################
<?php echo $lang->get( 'title_site' ); ?>

###################################
<?php
echo $lang->get( 'version_php', false, ( $_SESSION['__install'][0]['vCompare'] == 1 ) ? 'suffisante' : 'insuffisante' ) . "\n";
echo $lang->get( 'verification_dir', false, array( ( $_SESSION['__install'][0]['allDirOkayExist'] == 1 ) ? 'existants' : 'non-existants', ( $_SESSION['__install'][0]['allDirOkayWritable'] == 1 ) ? 'inscriptibles' : 'bloqués'  ) ) . "\n";
echo $lang->get( 'host_rooturl' ) . ' : ' . $_SESSION['__install'][1]['rootUrl'] . "\n";
echo $lang->get( 'host_root' ) . ' : ' . $_SESSION['__install'][1]['root'] . "\n\n";
?>###################################
<?php echo $lang->get( 'title_sgbd' ); ?>

###################################
<?php
echo $lang->get( 'bdd_host' ) . ' : ' . $_SESSION['__install'][1]['hostBDD'] . "\n";
echo $lang->get( 'bdd_login' ) . ' : ' . $_SESSION['__install'][1]['loginBDD'] . "\n";
echo $lang->get( 'bdd_password' ) . ' : ' . $_SESSION['__install'][1]['passwordBDD'] . "\n";
echo $lang->get( 'bdd_database' ) . ' : ' . $_SESSION['__install'][1]['databaseBDD'] . "\n";
echo $lang->get( 'bdd_prefix' ) . ' : ' . $_SESSION['__install'][1]['prefixeBDD'] . "\n";
?>

###################################
<?php echo $lang->get( 'title_site' ); ?>

###################################
<?php
echo $lang->get( 'default_lang' )  . ' : ' . $_SESSION['__install'][2]['langDefault'] . "\n";
echo $lang->get( 'default_theme' )  . ' : ' . $_SESSION['__install'][2]['designDefault'] . "\n";
echo $lang->get( 'default_module' )  . ' : ' . $_SESSION['__install'][2]['moduleDefault'] . "\n";
?>

###################################
<?php echo $lang->get( 'title_admin' ); ?>

###################################
<?php
echo $lang->get( 'admin_login' )  . ' : ' . $_SESSION['__install'][3]['login'] . "\n";
echo $lang->get( 'admin_password' )  . ' : ************ (' . $_SESSION['__install'][3]['password'] . ")\n";
echo $lang->get( 'admin_email' )  . ' : ' . $_SESSION['__install'][3]['email'];
?></textarea>
		            <input type="submit" />
	            </fieldset>
		    </form>
		    
		    <p><a href="?step=0"><?php echo $lang->get( 'step_one' ); ?></a></p>
			<?php
			}
			elseif( $step == 5 )
			{
			?>
			<h2><?php echo $lang->get( 'installation_ok' ); ?></h2>
			<p><?php echo $lang->get( 'end_message' ); ?></p>
			<?php
			}
			?>
		</div>
		<footer>
			<p>Panel par <strong>Stan</strong>. Vous êtes libre de réutiliser cette création comme bon vous semblera. Vous pouvez me demander les sources par email que vous trouverez dans la section contact.</p>

			<p><a rel="license" href="http://creativecommons.org/licenses/by/2.5/deed.fr"><img alt="Contrat Creative Commons" style="border-width:0" src="http://i.creativecommons.org/l/by/2.5/80x15.png" /></a></p>
		</footer>
	</body>
</html>

