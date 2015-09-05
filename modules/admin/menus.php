<?php
require_once( '../../kernel/begin.php' );
require_once( 'panel_admin.inc.php' );
if( isset( $_GET['ajouter'] ) )
{
	$form = new Form( translate( 'form_add' ) );
	$form->add_fieldset();
	$form->add_input( 'title', 'title', translate( 'title' ) );
	$form->add_input( 'link', 'link', translate( 'link' ) );
	$form->add_input( 'order', 'order', translate( 'order' ) )->setNotNull( 0 );
	$form->add_input( 'type', 'type', translate( 'type' ) )->setNotNull( 0 );
	$form->add_input( 'position', 'position', translate( 'position' ) );
	$form->add_input( 'html', 'html', translate( 'html' ), 'text', 'bool' );
	$form->add_input( 'authorizations', 'authorizations', translate( 'authorizations' ) );
	$form->add_button();
	
	$fh = new FormHandle( $form );
	$fh->handle();
	
	if( $fh->okay() )
	{
		$title = $fh->get( 'title' );
		$link = $fh->get( 'link' );
		$order = $fh->get( 'order' );
		$type = $fh->get( 'type' );
		$position = $fh->get( 'position' );
		$authorizations = $fh->get( 'authorizations' );
		$html = ( $fh->get( 'html' ) === true ? 1 : 0 );
		$bdd->query( 'INSERT INTO ' . TABLE_MENUS . ' ( menu_title, menu_link, menu_order, menu_type, menu_position, menu_authorizations, menu_html ) VALUES( ?, ?, ?, ?, ?, ?, ? )', 
				array( $title, $link, $order, $type, $position, $authorizations, $html ) );
		$error = new Error();
		$error->add_error( translate( 'addition_successs' ), ERROR_GLOBAL, __FILE__, __LINE__, ROOTU . 'modules/admin/menus.php' );
	}
	else
	{
		tpl_begin();
		$form->build_all();
		tpl_end();
	}
}
elseif( isset( $_GET['supprimer'] ) )
{
	$bdd->query( 'DELETE FROM ' . TABLE_MENUS . ' WHERE menu_id = ?', intval( $_GET['supprimer'] ) );
	$error = new Error();
	$error->add_error( translate( 'deletion_success' ), ERROR_GLOBAL, __FILE__, __LINE__, ROOTU . 'modules/admin/menus.php' );
}
else
{
	$requestEdit = $bdd->query( 'SELECT * FROM ' . TABLE_MENUS . ' ORDER BY menu_type, menu_position, menu_id' );
	ob_start();
	echo translate( 'different_types' );
	?>
	<table>
		<tr>
			<th>#</th>
			<th><?php echo translate( 'title' ); ?></th>
			<th><?php echo translate( 'link' ); ?></th>
			<th><?php echo translate( 'order' ); ?></th>
			<th><?php echo translate( 'type' ); ?></th>
			<th><?php echo translate( 'position' ); ?></th>
			<th><?php echo translate( 'html' ); ?></th>
			<th><?php echo translate( 'authorizations' ); ?></th>
			<th>-</th>
		</tr>
	<?php
	$form = new Form( translate( 'form_edit' ), 'post' );
	$form->add_fieldset();
	$form->display_top();
	while( $data = $bdd->fetch( $requestEdit ) )
	{
	?>
		<tr>
			<td><?php echo $data['menu_id']; ?></td>
			<td><?php $form->add_input( 'title_' . $data['menu_id'], 'title_' . $data['menu_id'], false )->setValue( $data['menu_title'] )->setSize( 30 )->display(); ?></td>
			<td><?php $form->add_input( 'link_' . $data['menu_id'], 'link_' . $data['menu_id'], false )->setValue( $data['menu_link'] )->setSize( 30 )->display(); ?></td>
			<td><?php $form->add_input( 'order_' . $data['menu_id'], 'order_' . $data['menu_id'], false, 'text', 'int' )->setValue( $data['menu_order'] )->setSize( 4 )->setNotNull( 0 )->display(); ?></td>
			<td><?php $form->add_input( 'type_' . $data['menu_id'], 'type_' . $data['menu_id'], false, 'text', 'int' )->setValue( $data['menu_type'] )->setSize( 4 )->setNotNull( 0 )->display(); ?></td>
			<td><?php $form->add_input( 'position_' . $data['menu_id'], 'position_' . $data['menu_id'], false )->setValue( $data['menu_position'] )->setSize( 6 )->display(); ?></td>
			<td><?php $form->add_input( 'html_' . $data['menu_id'], 'html_' . $data['menu_id'], false, 'text', 'int' )->setValue( $data['menu_html'] )->setSize( 6 )->setNotNull( 0 )->display(); ?></td>
			<td><?php $form->add_input( 'authorizations_' . $data['menu_id'], 'authorizations_' . $data['menu_id'], false )->setValue( $data['menu_authorizations'] )->setSize( 10 )->display(); ?></td>
			<td><a href="?supprimer=<?php echo $data['menu_id']; ?>"><img src="<?php echo DESIGN; ?>img/delete.png" title="<?php echo translate( 'delete_menu' ); ?>" alt="<?php echo translate( 'delete_menu' ); ?>" /></a></td>
		</tr>
	<?php
	}
	unset( $data );
	?>
	</table>
	<?php
	$form->add_button()->display();
	$form->display_footer();
	$toAffic = ob_get_contents();
	ob_end_clean();
	$fh = new FormHandle( $form );
	$fh->handle();
	if( $fh->okay() )
	{
		$requestEdit = $bdd->query( 'SELECT * FROM ' . TABLE_MENUS . ' ORDER BY menu_type, menu_position, menu_id' );
		while( $data = $bdd->fetch( $requestEdit ) )
		{
			$requestUpdate = NULL;
			$params = array();
			$change = false;
			foreach( $data AS $k => $d )
				if( $k != 'menu_id' && ( $v = $fh->get( str_replace( 'menu_', '', $k ) . '_' . $data['menu_id'] ) ) != $d )
				{
					$requestUpdate .= ' `' . $k . '` = ? ,';
					$params[] = $v;
				}
			$params[] = $data['menu_id'];
			if( $requestUpdate !== NULL )
				$bdd->query( 'UPDATE ' . TABLE_MENUS . ' SET ' . trim( $requestUpdate, ',' ) . ' WHERE menu_id = ?', $params );
		}
		$error = new Error();
		$error->add_error( translate( 'modification_success' ), ERROR_GLOBAL, __FILE__, __LINE__ );
	}
	elseif( !empty( $_POST ) )
	{
		$error = new Error();
		$error->add_error( translate( 'modification_error' ), ERROR_PAGE, __FILE__, __LINE__ );
	}
	tpl_begin();
?>
<p><a href="?ajouter">Ajouter un menu</a></p>
<?php
	echo $toAffic;
	tpl_end();
}
?>
