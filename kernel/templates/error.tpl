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
</head>
<body>

<div id="global">
	<div id="contenuError">
		<h2>Message</h2>
	    <p>
	        <?php echo $message; ?>
	    </p>
	    <p><a href="<?php echo $link; ?>">Suite</a></p>
	</div>
</div><!-- #global -->
</body>
</html>
