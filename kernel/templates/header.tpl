<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr" lang="fr">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf8" />
	<title>
		<?php echo $tpl->header()->get( 'title' ); ?>
	</title>
	<?php
	$cssheets = $tpl->header()->get( 'styles' );
	foreach( $cssheets AS $fileCSS ) {
	?>
	<link rel="stylesheet" type="text/css" href="<?php echo $fileCSS; ?>.css" media="all" />
	<?php } ?>
	<script type="text/javascript" src="<?php echo ROOTU; ?>js/formulaires.js"></script>
</head>
<body>

<div id="global">

	<div id="entete">
		<h1>
			<img alt="" src="picto/05.png" />
			<?php echo $tpl->header()->get( 'title' ); ?>
		</h1>
		<p class="sous-titre">
			<?php echo $tpl->header()->get( 'description' ); ?>
		</p>
	</div><!-- #entete -->
	<?php
	$error = new Error();
	$errors = $error->getDatas();
	if( !empty( $errors ) )
	{
	    if( !is_array( $errors ) ) $errors = array( $errors );
	    foreach( $errors AS $e )
	    {
	?>
	<div id="errorDiv">
	    <p class="errorDiv<?php echo $e['type']; ?>"><?php echo $e['message']; ?></p>
	</div>
	<?php
	    }
	}
	?>
