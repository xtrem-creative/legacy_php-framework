<?php
require_once( '../../kernel/begin.php' );
$lang->setModule( 'membres', 'liste' );
tpl_begin();

?>
<table>
	<thead>
		<tr>
			<th>#</th>
			<th>Pseudo</th>
		</tr>
	</thead>
	<tbody>
<?php
if( $bdd->count_sql( TABLE_MEMBERS ) > 1 )
{
	$query = $bdd->query( 'SELECT membre_id, membre_login FROM ' . TABLE_MEMBERS . ' WHERE membre_id > 0' );
	while( $fetch = $bdd->fetch( $query ) )
	{
		echo '
		<tr>
			<td>'. $fetch['membre_id'] .'</td>
			<td><a href="' . ROOTU . 'modules/membres/profil.php?idMembre=' . $fetch['membre_id'] . '">'. htmlentities( $fetch['membre_login'] ) .'</a></td>
		</tr>';
	}
}
else
{
	$error = new Error();
	$error->add_error( 'Aucun membre à afficher.', ERROR_PAGE, 'modules/members/liste.php', __LINE__ );
	echo '
		<tr>
			<td colspan="2">Aucun membre</td>
		</tr>';
}
?>
	</tbody>
</table>
<?php

tpl_end();
?>
