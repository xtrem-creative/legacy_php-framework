<?php
require_once( '../../kernel/begin.php' );
require_once( 'panel_admin.inc.php' );
$lang->setModule( 'admin', 'contact' );
$requeteContacts = $bdd->query( 'SELECT * FROM ' . TABLE_CONTACT . '' );
tpl_begin();
echo '<table>
	<tr>
		<td>#</td>
		<td>Date</td>
		<td>Objet</td>
		<td>Message</td>
		<td>Email</td>
	</tr>';
while( $dataContacts = $bdd->fetch( $requeteContacts ) )
{
	echo '<tr>';
	echo '<td>' . $dataContacts['contact_id'] . '</td>';
	echo '<td>' . date_avancee( $dataContacts['contact_date'] ) . '</td>';
	echo '<td>' . htmlspecialchars( $dataContacts['contact_objet'] ) . '</td>';
	echo '<td>' . htmlspecialchars( $dataContacts['contact_message'] ) . '</td>';
	echo '<td>' . htmlspecialchars( $dataContacts['contact_email'] ) . '</td>';
	echo '</tr>';
}
echo '</table>';
tpl_end();
?>

