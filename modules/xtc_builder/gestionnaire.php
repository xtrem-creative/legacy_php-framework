<?php
require_once( '../../kernel/begin.php' );
require_once( 'parser.class.php' );
$lang->setModule( 'xtc_builder', 'gestionnaire' );
$requetePagesExistantes = $bdd->query( 'SELECT * FROM ' . TABLE_PAGES_PHP . '' );
tplBegin();
?>
<table>
	<tr>
		<th>Identifiant de la page</th>
		<th>Nom de la page</th>
		<th>Actions</th>
	</tr>
<?php
while( $dataPages = $bdd->fetch( $requetePagesExistantes ) )
{
?>
	<tr>
		<td><?php echo $dataPages['page_md5']; ?></td>
		<td><?php echo $dataPages['page_nom']; ?></td>
		<td>
			<a href="?action=modifierPage&idPage=<?php echo $dataPages['page_md5']; ?>">Modifier la page</a>
			<a href="?action=voirPage&idPage=<?php echo $dataPages['page_md5']; ?>">Voir la page</a>
		</td>
	</tr>
<?php
}
?>
</table>
<?php
tplEnd();
?>
